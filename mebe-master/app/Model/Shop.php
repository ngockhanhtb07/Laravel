<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $primaryKey = 'shop_id';

    protected $fillable = [
        'user_id',
        'address',
        'shop_name',
        'is_active',
        'description',
        'url_image',
        'rating',
        'response_time'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(ShippingVendor::class, 'shipping_shops', 'shop_id',
            'shipping_id');
    }
}