<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Notifiable;
    protected $table='productmanager';
    protected $primaryKey='id';
    protected $fillable=[
        'name',
        'price',
        'image',
    ];
}
