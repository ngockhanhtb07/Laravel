<?php


namespace App\Repositories\OrderItem;


use App\Model\OrderItem;
use App\Repositories\EloquentRepository;

class OrderItemRepository extends EloquentRepository implements OrderItemRepositoryInterface
{
    public function getModel()
    {
        return OrderItem::class;
    }

}