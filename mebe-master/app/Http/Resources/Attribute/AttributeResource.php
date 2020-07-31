<?php

namespace App\Http\Resources\Attribute;

use App\Http\Resources\AttributeValue\AttributeValueCollection;
use App\Model\AttributeValue;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource {

    use FormatResponse;

    public function toArray($request)
    {
        $attributeValue = AttributeValue::with('variantsProduct')->where('attribute_id', $this->attribute_id)->get();
        $attribute = [
            'attribute_id' => $this->format($this->attribute_id,"integer"),
            'attribute_name' => $this->format($this->attribute_name),
            'attribute_type' => $this->format($this->attribute_type),
            'attribute_frontend_type' => $this->format($this->attribute_frontend_type),
            'values' => new AttributeValueCollection($attributeValue)
        ];

        return $attribute;
    }
}