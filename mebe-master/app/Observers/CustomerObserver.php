<?php

namespace App\Observers;

use App\Model\Customer;
use Illuminate\Support\Facades\Log;

class CustomerObserver
{
    /**
     * Handle the Customer "deleting" updated event.
     *
     * @param Customer $customer
     * @return void
     */
    public function deleting(Customer $customer) {
        try {
            $customer->cart->delete();
        } catch (\Exception $exception) {
            Log::warning($exception->getMessage());
        }
    }

}