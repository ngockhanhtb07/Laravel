<?php


namespace App\Repositories\Cart;


use App\Model\Cart;
use App\Repositories\EloquentRepository;

class CartRepository extends EloquentRepository implements CartRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Cart::class;
    }


}