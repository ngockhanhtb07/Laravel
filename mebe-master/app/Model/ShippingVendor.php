<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingVendor extends Model
{
    protected $table = 'shipping_vendors';
    protected $primaryKey = 'vendor_id';
    protected $fillable = [
        'name',
        'username',
        'token_api',
        'status',
        'created_user',
    ];
}
