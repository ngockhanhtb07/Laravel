<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    protected $table = 'category_group';

    protected $primaryKey = 'group_id';

    protected $fillable = [
        'group_name'
    ];
}