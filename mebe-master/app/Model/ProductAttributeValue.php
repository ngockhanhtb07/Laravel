<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $table = 'product_attribute_value';

    protected $primaryKey = 'product_id';

    protected $fillable = ['product_id', 'attribute_value_id'];
}