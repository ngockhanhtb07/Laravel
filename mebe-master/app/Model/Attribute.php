<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {

    protected $primaryKey = 'attribute_id';

    protected $fillable = [
        'attribute_name',
        'attribute_type',
        'attribute_frontend_type',
        'is_standard_attribute'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_attribute', 'attribute_id','category_id');
    }
}