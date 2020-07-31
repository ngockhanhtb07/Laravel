<?php


namespace App\Repositories\Like;


use App\Model\Like;
use App\Repositories\EloquentRepository;

class LikeRepository extends EloquentRepository implements LikeRepositoryInterface
{
    public function getModel()
    {
        return Like::class;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    public function createOrDelete()
    {

    }

}