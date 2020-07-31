<?php

namespace App\Http\Resources\AttributeValue;

use App\Http\Resources\Product\ProductResource;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource {
    use FormatResponse;
    public function toArray($request)
    {
        $attributeValue = [
            'attribute_value_id' => $this->format($this->attribute_value_id,"integer"),
            'attribute_id' => $this->format($this->attribute_id,"integer"),
            'value' => $this->format($this->value),
            'text' => $this->format($this->display_text),
        ];
        return $attributeValue;
    }
}