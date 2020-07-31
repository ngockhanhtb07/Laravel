<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Shop\ShopResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource {
    use FormatResponse;
    public function toArray($request)
    {
        $product = [
            'product_id' => $this->format($this->product_id,"integer"),
            'product_name' => $this->format($this->product_name),
            'product_type' => $this->format($this->product_type),
            'slug' => $this->format($this->slug),
            'url_image' => $this->format($this->url_image),
            'description' => $this->format($this->description),
            'sku' => $this->format($this->sku),
            'price' => $this->format($this->price,"integer"),
            'final_price' => $this->format($this->final_price,"integer"),
            'quantity' => $this->format($this->quantity,"integer"),
            'status' => $this->format($this->status,"integer")
        ];
        if ($this->product_type == 'variant') {
            $product['master'] = new ProductResource($this->whenLoaded('master'));
        } else {
            $product['shop'] = new ShopResource($this->shop);
        }
        if ($this->product_type == 'master') {
            $product['variants'] = new ProductCollection($this->whenLoaded('variants'));
        }
        return $product;
    }
}