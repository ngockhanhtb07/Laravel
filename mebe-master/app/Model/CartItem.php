<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'cart_id',
        'product_id',
        'sku',
        'quantity',
        'price',
        'product_type',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
