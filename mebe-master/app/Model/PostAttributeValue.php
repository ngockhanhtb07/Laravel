<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostAttributeValue extends Model
{
    protected $table = 'post_attribute_value';

    protected $primaryKey = 'post_id';

    protected $fillable = ['post_id', 'attribute_value_id'];
}
