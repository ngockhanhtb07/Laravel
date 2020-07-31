<?php


namespace App\Repositories\Shop;


use App\Model\Shop;
use App\Repositories\EloquentRepository;

class ShopRepository extends EloquentRepository implements ShopRepositoryInterface
{
    public function getModel()
    {
        return Shop::class;
    }
    public function checkVendorInShop($shop,$ship)
    {
        $shipping =  $this->_model->find($shop)->vendors()
            ->where(['is_enabled' => env('ACTIVE_DEFAULT_VALUE', true), 'shipping_id' => $ship])
            ->get();
        if ($shipping->count()<1)
            return false;
        return true;
    }

}