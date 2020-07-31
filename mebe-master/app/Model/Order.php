<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'order_number',
        'customer_id',
        'shop_id',
        'shipping_address',
        'shipping_method',
        'tracking_number',
        'payment_method',
        'status_payment',
        'total',
        'sub_total',
        'shipping_fee',
        'status',
        'note'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'shipping_address');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function shippingAddress() {
        return $this->belongsTo(Address::class, 'shipping_address');
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingVendor::class, 'shipping_method');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_method');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
