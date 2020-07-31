<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    protected $primaryKey = 'children_id';

    protected $table = 'children';

    protected $fillable = [
        'parent_id',
        'nickname',
        'gender',
        'weight',
        'height',
        'date_of_birth',
        'url_image',
        'external_id'
    ];
}
