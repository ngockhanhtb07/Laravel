<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cart_id';
    protected $fillable = [
        'customer_id',
        'status',
    ];

    public function customer() {
        return $this->hasOne(Customer::class, 'customer_id');
    }

    public function cartItems() {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
}
