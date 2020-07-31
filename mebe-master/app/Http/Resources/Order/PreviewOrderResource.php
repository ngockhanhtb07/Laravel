<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Cart\ItemCartCollection;
use App\Http\Resources\Shipping\ShippingCollection;
use App\Model\CartItem;
use App\Model\Shop;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviewOrderResource extends JsonResource
{
    use FormatResponse;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $shop = $this->shop_id;
        $items = $this->list_product;
        $item = explode(",", $items);
        $listProduct = CartItem::where(['cart_id' => $this->cart_id])
            ->whereIn('product_id',$item)->with('product')
            ->whereHas('product' ,function($query) use($shop){
            $query->where('shop_id',$shop);
        })->get();

        $sub_total_shop = 0;
        foreach ($listProduct as $product){
            $sub_total_shop += $product->product->final_price * $product->quantity;
        }

        $total_cart = $this->total_incart;
        $priceInTotal = $this->price_product;
        $check_change = $total_cart != $priceInTotal ? true : false;

        $listShippingVendor = Shop::find($shop)->vendors()->where('is_enabled',env('ACTIVE_DEFAULT_VALUE'))->get();
        $canOrder = $listShippingVendor->count()== 0 ? false : true;

        return [
            'id' => $this->format($this->shop_id,"integer"),
            'name' => $this->format($this->shop_name),
            'image' => $this->format($this->url_image),
            'product_total' => $listProduct->sum('quantity'),
            'sub_total' => $sub_total_shop,
            'can_order' => $canOrder,
            'is_changed_price' => $check_change,
            'shipping' => new ShippingCollection($listShippingVendor),
            'products' => new ItemCartCollection($listProduct),

        ];
    }
}
