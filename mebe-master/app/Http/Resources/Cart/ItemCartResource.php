<?php

namespace App\Http\Resources\Cart;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemCartResource extends JsonResource
{
    use FormatResponse;
    public function toArray($request)
    {
        $url_product = $this->product->slug.'-'.$this->product->product_id;
        $valid = $this->product->isValid();
        return [
            'id' => $this->format($this->product_id,"integer"),
            'sku' => $this->format($this->sku),
            'quantity' => $this->format($this->quantity,"integer"),
            'price' => $this->format($this->product->final_price,"double"),
            'product_type' => $this->format($this->product_type),
            'product_name' => $this->format($this->product->product_name),
            'product_image' => $this->format($this->product->url_image),
            'product_url' => url("/products/{$url_product}"),
            'is_valid' => $this->format($valid,"boolean"),
        ];
    }
}
