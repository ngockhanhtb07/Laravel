<?php


namespace App\Repositories\Customer;


use App\Model\Customer;
use App\Repositories\EloquentRepository;

class CustomerRepository extends EloquentRepository implements CustomerRepositoryInterface
{
    public function getModel()
    {
        return Customer::class;
    }

}