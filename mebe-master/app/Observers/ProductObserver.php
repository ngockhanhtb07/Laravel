<?php

namespace App\Observers;

use App\Model\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "saved" updated event.
     *
     * @param Product $product
     * @return void
     */
    public function saved(Product $product) {
        try {
            $product->addToIndex();
        } catch (\Exception $exception) {
            Log::warning($exception->getMessage());
        }
    }

}