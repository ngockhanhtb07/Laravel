<?php


namespace App\Http\Resources\Category;

use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryShortResource extends JsonResource
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
        if (is_null($this->resource)) {
            return [];
        }
        $data = [
            'category_id' => $this->format($this->category_id, "integer"),
            'category_name' => $this->format($this->name),
            'type' => $this->format($this->type,"integer"),
            'url_image' => $this->format($this->url_image),
        ];
        return $data;
    }


}