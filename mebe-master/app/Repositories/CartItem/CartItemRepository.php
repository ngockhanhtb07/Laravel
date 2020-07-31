<?php


namespace App\Repositories\CartItem;


use App\Http\Resources\Cart\CartCollection;
use App\Http\Resources\Cart\ItemCartCollection;
use App\Model\CartItem;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;

class CartItemRepository extends EloquentRepository implements CartItemRepositoryInterface
{
    public function getModel()
    {
        return CartItem::class;
    }

    public function addItemToCart(array $item, array $attribute)
    {
        $this->_model->updateOrCreate(
            $item, $attribute
        );
    }

    public function getItemCart($cart_id)
    {
        $item = $this->getShopByCart($cart_id);
        $collect = collect($item);
        $data = new CartCollection($collect);
        $this->changeCart($cart_id);
        return $data;
    }

    public function getListItem($cart_id, array $product)
    {
        return $this->_model
            ->select('item_id','cart_id','product_id','sku','quantity','price','product_type',DB::raw('(quantity*price) as sub_total'))
            ->where('cart_id', $cart_id)
            ->whereIn('product_id' , $product)
            ->get();
    }

    protected function getShopByCart($cart_id)
    {
        return DB::select(DB::raw(" SELECT tb_sum.shop_id, tb_sum.cart_id, tb_sum.shop_name, "
            ." 	tb_sum.url_image, sum(tb_sum.quantity) quantity,                            "
            ." 	sum(tb_sum.total_incart) total_incart,                                      "
            ." 	sum(tb_sum.price_product) price_product,                                    "
            ." 	sum(tb_sum.subtotal) subtotal                                               "
            ." FROM                                                                         "
            ." (                                                                            "
            ." 	SELECT a.shop_id, b.cart_id, c.shop_name, c.url_image, b.product_id,        "
            ." 		sum(b.quantity) quantity,                                               "
            ." 		SUM(b.price) total_incart,                                              "
            ." 		SUM(a.final_price) price_product,                                       "
            ." 		SUM(a.final_price)*sum(b.quantity) subtotal                             "
            ." 	FROM products a, cart_items b, shops c                                      "
            ." 	WHERE a.product_id = b.product_id                                           "
            ." 	AND a.shop_id = c.shop_id                                                   "
            ." 	AND b.cart_id = :cart_id                                                    "
            ." 	GROUP BY a.shop_id, b.cart_id, c.shop_name, c.url_image, b.product_id       "
            ." ) tb_sum                                                                     "
            ." GROUP BY tb_sum.shop_id, tb_sum.cart_id, tb_sum.shop_name, tb_sum.url_image  "),
            ['cart_id' => $cart_id]);
    }

    public function changeCart($cart_id, array $item_id = null)
    {
        // check price in product and price in cart.
        // check quantity in cart always smaller quantity in product
        if (is_null($item_id))
            $items = $this->_model->where('cart_id',$cart_id)->with('product')->get();
        else
            $items = $this->_model->where('cart_id',$cart_id)->whereIn('item_id',$item_id)->with('product')->get();
        $is_changed = 0;
        foreach ($items as $item){
            if($item->price != $item->product->final_price)
            {
                $item->price = $item->product->final_price;
                $is_changed++;
            }
            if ($item->product->quantity > 0 && $item->product->quantity < $item->quantity){
                $item->quantity = $item->product->quantity;
                $is_changed++;
            }
            if ($item->getOriginal()!=$item){
                $item->save();
            }
        }
        return $is_changed;
    }

    public function getCartItemByShop($cartId, $shopId)
    {
        $cartItems = $this->_model->where('cart_id', $cartId)->whereHas('product', function($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        })->get(['cart_items.*']);
        return new ItemCartCollection($cartItems);
    }
}