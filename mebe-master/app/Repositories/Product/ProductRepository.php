<?php

namespace App\Repositories\Product;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Model\Product;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;

class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{

    public function getModel()
    {
        return Product::class;
    }

    public function index(Request $request)
    {
        if ($request->has('q')) {
            $keyword = $request->input('q');
            $products = $this->_model->searchByQuery([
                'multi_match' => [
                    'query' => $keyword,
                    'fields' => ['product_name', 'sku', 'slug']
                ]
            ]);
            return new ProductCollection($products);
        }
        return false;
    }

    public function all($columns = array('*'), $perPage = 5)
    {
        $result = $this->_model->select($columns)->with('variants')
            ->active()
            ->typeOf('variant', false)
            ->paginate($perPage);
        $products = new ProductCollection($result);
        return $products;
    }

    /*
     * Get all products by product type: simple, master, variant
     */
    public function getProductsByType($type, $perPage = 5)
    {
        $result = $this->_model->with('master', 'variants')
            ->active()
            ->typeOf($type)
            ->paginate($perPage);
        $products = new ProductCollection($result);
        return $products;
    }

    public function getProductByIdOrSku($id, $type = null)
    {
        $condition = [
            'is_enabled' => true
        ];
        if ($type) {
            $condition['product_type'] = $type;
        }
        $result = $this->_model->where($condition)->where(function ($query) use ($id) {
            $query->where('product_id', $id)
                ->orWhere('sku', $id);
        })->with('master', 'variants')->get();
        return new ProductCollection($result);
    }

    public function getVariantProductsByMaster($id)
    {
        $masterProduct = $this->getProductByIdOrSku($id, 'master')->first();
        if ($masterProduct) {
            $result = $masterProduct->variants()->where('is_enabled', true)->with('variants')->get();
            $variants = new ProductCollection($result);
            return $variants;
        }
        return false;
    }

    public function productIsValid($id)
    {
        $product = $this->_model->where('product_id', $id)
            ->where('status', env('ACTIVE_DEFAULT_VALUE', true))
            ->where('quantity', '>', 0)
            ->typeOf(env('PRODUCT_MASTER', 'master'), false)
            ->active();
        if (is_null($product)) {
            return false;
        }
        return true;
    }

    public function filterProductByRangePrice($from, $to)
    {
        $products = $this->_model->active()->rangePrice($from, $to)->get();
        return new ProductCollection($products);
    }

    public function getProductBySlug($slug, $type = null)
    {
        $condition = [
            'is_enabled' => true
        ];
        if ($type) {
            $condition['product_type'] = $type;
        }
        /*
         * separate slug = slug + '-' + sku
         */
        $sku = substr($slug, strrpos($slug, '-') + 1);
        $slugOriginal = substr($slug, 0, strrpos($slug, '-'));
        $product = $this->_model->where($condition)->where(function ($query) use ($sku, $slugOriginal) {
            $query->where('slug', $slugOriginal)
                ->orWhere('sku', $sku);
        })->with('master', 'variants')->firstOrFail();
        return new ProductResource($product);
    }

    /*
     * Product related by product id or sku
     */
    public function getProductRelated($productId)
    {
        $product = $this->_model
            ->active()
            ->inStock()
            ->where('product_id', $productId)
            ->orWhere('sku', $productId)
            ->firstOrFail();

        $productRelated = $this->_model
            ->active()
            ->inStock()
            ->typeOf('variant', false)
            ->where('category_id', $product->category_id)
            ->orderBy('updated_at', 'desc')
            ->get();
        return new ProductCollection($productRelated);
    }

    /**
     *
     * Function change quantity number of product
     *
     * @param $id
     * @param $qty
     * @param  integer  $typeChange  typeChange -1 is minus, 0 is update to, 1 add
     * @return bool
     */
    public function changeQty($id, $qty, int $typeChange = 0)
    {
        if (is_null($id) || is_null($qty)) {
            return false;
        }
        $product = $this->_model
            ->active()
            ->inStock()
            ->typeOf(env('PRODUCT_MASTER', 'master'), false)
            ->where('product_id', $id)->first();
        if (is_null($product)) {
            return false;
        }
        switch ($typeChange) {
            case -1:
                $product->quantity = $product->quantity - $qty;
                break;
            case 0:
                $product->quantity = $qty;
                break;
            case 1:
                $product->quantity = $product->quantity + $qty;
                break;
            default:
                return false;
        }
        $product->save();
        return true;
    }


}