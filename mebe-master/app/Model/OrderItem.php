<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'order_id',
        'sku',
        'quantity',
        'price',
        'product_type',
        'item_type',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
