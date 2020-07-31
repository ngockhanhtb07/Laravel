<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'attribute_value';

    protected $primaryKey = 'attribute_value_id';

    protected $fillable = ['attribute_id', 'value'];

    public function variantsProduct() {
        return $this->belongsToMany(Product::class, 'product_attribute_value', 'attribute_value_id', 'product_id');
    }

    public function posts() {
        return $this->belongsToMany(Post::class, 'post_attribute_value', 'attribute_value_id', 'post_id');
    }


}