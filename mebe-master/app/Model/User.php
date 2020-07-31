<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name', 'email', 'avatar', 'phone', 'status', 'is_active', 'external_id', 'token_user', 'birthday', 'type_mom'
    ];
    public function getId()
    {
        return $this->user_id;
    }

    public function customer() {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function notificationPost() {
        return $this->belongsToMany(Post::class, 'notification_post_user', 'user_id', 'post_id')->withPivot('watch')->withTimestamps();
    }

}
