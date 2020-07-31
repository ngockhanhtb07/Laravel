<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\ProductResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
        return [
            'id' => $this->order_id,
            'product_id' => $this->product->product_id,
            'product_name' => $this->product->product_name,
            'product_slug' => $this->product->slug,
            'product_image' => $this->product->url_image,
            'product_description' => $this->product->description,
            'product_final_price' => $this->product->final_price,
            'product_price' => $this->product->price,
            'product_status' => $this->product->status,
            'quantity' => $this->quantity,
            'product_type'=> $this->product_type,
            'time_order' => strtotime($this->created_at)
        ];
    }
}
