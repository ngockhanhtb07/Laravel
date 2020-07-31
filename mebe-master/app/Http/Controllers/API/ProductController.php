<?php

namespace App\Http\Controllers\API;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Traits\CommonResponse;
use App\Traits\UploadMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    use CommonResponse;
    use UploadMedia;

    protected $_productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->_productRepository = $productRepository;
    }

    public function show(Request $request) {
        $perPage = $request->perPage;
        $products = $this->_productRepository->all(array('*'), $perPage);
        return $this->successResponse($products, 'success');
    }

    public function detail($productId) {
        $product = $this->_productRepository->getProductByIdOrSku($productId);
        if ($product->count() > 0) {
            return $this->successResponse($product, 'success');
        }
        return $this->errorResponse("Can't find any product with product or sku is '$productId'", 404);
    }

    public function productByType($productType) {
        $products = $this->_productRepository->getProductsByType($productType);
        if ($products->count() > 0) {
            return $this->successResponse($products, 'success');
        }
        return $this->errorResponse("Can't find any product with type is '$productType'", 404);
    }

    public function productVariantByMaster($productId) {
        $products = $this->_productRepository->getVariantProductsByMaster($productId);

        if ($products && $products->count() > 0) {
            return $this->successResponse($products, 'success');
        }
        return $this->errorResponse("Product with id or sku '$productId' is not exists or don't have any variant products!", 404);
    }

    public function productByRangePrice(Request $request) {
        $products = $this->_productRepository->filterProductByRangePrice($request->from, $request->to);

        if ($products && $products->count() > 0) {
            return $this->successResponse($products, 'success');
        }
        return $this->errorResponse("Can't find any products match with conditions", 404);
    }

    public function productBySlug($slug) {
        $product = $this->_productRepository->getProductBySlug($slug);
        return $this->successResponse($product, 'success');
    }

    public function productRelated($productId) {
        $products = $this->_productRepository->getProductRelated($productId);
        if ($products && $products->count() > 0) {
            return $this->successResponse($products, 'success');
        }
        return $this->errorResponse('Can\'t find any products', 404);
    }
}
