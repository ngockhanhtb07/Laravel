<?php

namespace App\Model;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model {

    use HasCompositePrimaryKey;
    protected $table = 'post_hist';
    protected $primaryKey = ['post_id', 'time_id'];
    public $incrementing = false;
    protected $fillable = [
        'post_id',
        'time_id',
        'title',
        'quote',
        'slug',
        'content',
        'author',
        'url_image',
        'category_id',
        'status',
        'created_user'
    ];
}