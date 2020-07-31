<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'slug',
        'group_id',
        'type',
        'is_enabled',
        'url_image',
        'created_user',
        'parent_id',
        'updated_user',
        'type'
    ];


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attribute', 'category_id', 'attribute_id')->withTimestamps();
    }

    public function group() {
        return $this->belongsTo(CategoryGroup::class, 'group_id');
    }

    public function createdUser() {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedUser() {
        return $this->belongsTo(User::class, 'updated_user');
    }

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * @return string
     */
    public function breadCrumb(){
        return $this->parent()->with('breadCrumb');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_enabled', 1);
    }
}
