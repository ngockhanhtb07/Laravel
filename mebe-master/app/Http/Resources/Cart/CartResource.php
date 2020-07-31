<?php

namespace App\Http\Resources\Cart;

use App\Model\CartItem;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    use FormatResponse;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $shop = $this->shop_id;
        $list_product = CartItem::where(['cart_id' => $this->cart_id])->with('product')->whereHas('product',
            function ($query) use ($shop) {
                $query->where('shop_id', $shop);
            })->get();

        $sub_total_shop = 0;
        foreach ($list_product as $product) {
            $sub_total_shop += $product->product->final_price * $product->quantity;
        }

        return [
            'id' => $this->format($this->shop_id, "integer"),
            'name' => $this->format($this->shop_name),
            'image' => $this->format($this->url_image),
            'product_total' => $this->format($list_product->sum('quantity'), "integer"),
            'sub_total' => $sub_total_shop,
            'products' => new ItemCartCollection($list_product),
        ];

    }
}
