<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{
    protected $table = 'categories';
    protected $date = ['delete_at'];

    /**
     * Get all data categories
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getListCategory()
    {
        return Category::pluck('name', 'id');
    }

    /**
     * Foreign Key with job table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }
}
