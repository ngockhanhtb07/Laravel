<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    protected $table = 'category_attribute';

    protected $primaryKey = ['category_id','attribute_id'];

    protected $fillable = [
        'group_name',
        'attribute_id',
    ];
}
