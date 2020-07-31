<?php

namespace App\Http\Resources\Cart;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $total_cart = $this->collection->sum('total_incart');
        $quantity = $this->collection->sum('quantity');
        $priceInTotal = $this->collection->sum('price_product');
        $subTotal = $this->collection->sum('subtotal');
        $check_change = $total_cart != $priceInTotal ? true : false;
        return [
            'sub_total' => $subTotal,
            'count_product' => $quantity,
            'is_changed_price' => $check_change,
            'shops' => CartResource::collection($this->collection),
        ];
    }
}
