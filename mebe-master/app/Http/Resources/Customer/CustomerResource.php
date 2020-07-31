<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->format($this->customer_id,"integer"),
            'info' => new UserResource($this->user),
            'address' => CategoryResource::collection($this->addresses),
        ];
    }
}
