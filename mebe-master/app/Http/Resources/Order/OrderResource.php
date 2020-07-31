<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Shop\ShopResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        $address = $this->address;
        $shop = $this->shop;
        $shop->user = null;
        $orderItem = $this->orderItems;
        return [
            'order_id' => $this->format($this->order_id),
            'order_number' => $this->format($this->order_number),
            'address_info' => new CategoryResource($address),
            'shop_info' => new ShopResource($shop),
            'items' => new OrderItemCollection($orderItem),
            'shipping_info' => 'cod',
            'total' => $this->format($this->total,"integer"),
            'sub_total' => $this->format($this->sub_total,"integer"),
            'shipping_fee' => $this->format($this->shipping_fee,"integer"),
            'status' => $this->format($this->status,"integer"),
            'note' => $this->format($this->note),
            'status_payment' => $this->format($this->status_payment),
            'time_order' => strtotime($this->created_at)
        ];
    }
}
