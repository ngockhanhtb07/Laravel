<?php

namespace App\Repositories\User;

use App\Model\Role;
use App\Model\User;
use App\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{

    public function getModel()
    {
        return User::class;
    }

    public function setRole($roleName, User $user) {
        $role = Role::where('role_name', $roleName)->firstOrFail();
        if ($role && $role->role_id) {
            $user->role_id = $role->role_id;
            $user->save();
            return $user;
        }
        return false;
    }

    public function getUserByExternalId($userId)
    {
        $user = $this->_model->where('external_id', $userId)->firstOrFail();
        return $user;
    }
    public function getListUser(){
        return  $this->_model->limit(1000)->offset(0)->get();
    }
    public function setDirayNumber($userId,$numberDiary){
        $index= 'user_index';
        $post = [
            'doc'=>[
                'number_diary' => $numberDiary
            ]           
        ];
        $ch = curl_init("http://localhost:9200/$index/_doc/$userId/_update");  
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
