<?php

namespace App\Helper;

class OrderHelper {

    public function totalWeightShop($orderItems) {
        return $orderItems->sum(function($item) {
            $val = 0;
            $val += $item->quantity * $item->product->weight;
            return $val;
        });
    }
}