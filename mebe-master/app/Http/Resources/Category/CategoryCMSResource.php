<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Post\PostShortResource;
use App\Model\Post;
use App\Traits\FormatResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCMSResource extends JsonResource
{
    use FormatResponse;

    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }
        $parent = $this->parent ? $this->parent : null;
        $parents = $this->breadCrumb;
        $breadcrumb = '';
        while ($parents) {
            if ('' == $breadcrumb) {
                $breadcrumb = $parents->name;
            } else {
                $breadcrumb = $breadcrumb . '->' . $parents->name;
            }
            $parents = $parents->parent;
        }
        $nameCategory = empty($breadcrumb) ? $this->format($this->name) : $this->format($this->name) . '->' . $breadcrumb;
        $data = [
            'category_id' => $this->format($this->category_id, "integer"),
            'category_name' => $nameCategory,
            'url_image' => $this->format($this->url_image),
            'slug' => $this->format($this->slug),
            'group' => $this->group->group_name,
            'enabled' => $this->is_enabled,
            'created' => $this->created_at,
            'parent' => $parent,
            'type' => $this->type
        ];

        return $data;
    }

}
