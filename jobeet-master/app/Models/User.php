<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $dates = [
        'deleted_at'
    ];

    protected $perPage = 10;

    protected $fillable = [
        'name',
        'email',
        'password',
        'skills',
        'remember_token',
        'created_at',
        'updated_at',
    ];


    /**
     * Set Password to bcrypt
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    /**
     * Set foreign key to jobs table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    /**
     * Get all data User
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getListUser()
    {
        return User::paginate();
    }

    /**
     * Get data one user
     *
     * @param int $id
     * @return-App\Models\User | null
     */
    public static function getUser(int $id)
    {
        return User::find($id);
    }

    /**
     * Delete one user
     *
     * @param int $id
     * @return boolean
     */
    public static function deleteUser(int $id)
    {
        return User::find($id)->delete();
    }

    /**
     * Create new user
     *
     * @param Request $request
     * @return-App\Models\Users | null;
     */
    public static function createUser($request)
    {
        $user = User::create($request);
        return $user;
    }

    /**
     * Update data one user
     *
     * @param Request $request
     * @param int $id
     * @return boolean
     */
    public static function updateUser($request, int $id)
    {
        return User::find($id)->update($request);
    }

}
