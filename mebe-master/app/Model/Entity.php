<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entities';
    protected $primaryKey = 'entity_id';
    protected $fillable = [
        'value',
        'type'
    ];
}
