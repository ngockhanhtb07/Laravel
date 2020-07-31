<?php

namespace App\Helper;

class CartHelper {

    public function totalWeightShop($cartItems) {
        return $cartItems->sum(function($item) {
            $val = 0;
            $val += $item->quantity * $item->product->weight;
            return $val;
        });
    }
}