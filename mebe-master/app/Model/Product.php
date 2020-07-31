<?php

namespace App\Model;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ElasticquentTrait;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'shop_id',
        'product_name',
        'product_type',
        'description',
        'weight',
        'slug',
        'sku',
        'price',
        'discount_price',
        'quantity',
        'parent_id',
        'category_id',
        'status'
    ];

    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_id')->where('product_type', '=', 'variant');
    }

    public function master()
    {
        return $this->belongsTo(Product::class, 'parent_id')->where('product_type', '=', 'master');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id',
            'attribute_id')->withTimestamps();
    }

    public function variantAttributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_value', 'product_id',
            'attribute_value_id')->withTimestamps();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function scopeRangePrice($query, $from, $to)
    {
        return $query->whereBetween('final_price', [$from, $to]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_enabled', env('ACTIVE_DEFAULT_VALUE', true));
    }

    public function scopeTypeOf($query, $type, $operator = true) {
        return $query->where('product_type', ($operator ? '=' : '!='), $type);
    }

    public function scopeInStock($query) {
        return $query->where('quantity', '>', 0);
    }

    public function isValid()
    {
        if ($this->status == env('ACTIVE_DEFAULT_VALUE', true))
            return true;
        return false;
    }

}
