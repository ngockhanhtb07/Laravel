<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';
    protected $primaryKey = 'media_id';
    protected $fillable = [
        'link',
        'type',
        'description',
        'user_id',
        'owner_id',
        'entity_id',
        'is_enabled',
        'index',
        'external_id',
        'link_ads'
    ];
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
