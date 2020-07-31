<?php

namespace App\Http\Resources\Shop;

use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource {
    use FormatResponse;
    public function toArray($request)
    {
        $user = isset($this->user) ? new UserResource($this->user) : null;
        return [
            'shop_id' => $this->format($this->shop_id,"integer"),
            'shop_name' => $this->format($this->shop_name),
            'address' => $this->format($this->address),
            'description' => $this->format($this->description),
            'url_image' => $this->format($this->url_image),
            'rating' => $this->format($this->rating,"integer"),
            'join_at' => $this->created_at == null ? "" : strtotime($this->created_at),
            'user' => is_null($user) ? null: $user,
        ];
    }
}