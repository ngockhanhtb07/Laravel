<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'address_id';
    protected $table = 'addresses';

    protected $fillable = [
        'customer_id',
        'type',
        'first_name',
        'last_name',
        'phone',
        'province',
        'city',
        'district',
        'ward',
        'street',
        'is_default'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}