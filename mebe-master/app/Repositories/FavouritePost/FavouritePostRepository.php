<?php


namespace App\Repositories\FavouritePost;


use App\Model\FavouritePost;
use App\Repositories\EloquentRepository;

class FavouritePostRepository extends EloquentRepository implements FavouritePostRepositoryInterface
{
    public function getModel()
    {
        return FavouritePost::class;
    }

}