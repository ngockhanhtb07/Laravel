<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Payment\PaymentResource;
use App\Model\Payment;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PreviewOrderCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $customer = $this->collection->get('customer');
        $cart_id = $customer->cart->cart_id;
        $items = $this->collection->get('items');
        $rawData = $this->_getRawData($items,$cart_id);

        $dataPreview = collect($rawData);

        return [
            'customer' => new CustomerResource($customer),
            'shop' => PreviewOrderResource::collection($dataPreview),
            'payment' => PaymentResource::collection(Payment::where('status',1)->get()),
        ];
    }

    protected function _getRawData(array $items,$cart_id)
    {
        $list_id = implode(',',$items);
        $data = DB::select(DB::raw(" SELECT tb_sum.shop_id, tb_sum.cart_id, tb_sum.shop_name, "
            ." 	tb_sum.url_image, sum(tb_sum.quantity) quantity,                            "
            ." 	sum(tb_sum.total_incart) total_incart,                                      "
            ." 	sum(tb_sum.price_product) price_product,                                    "
            ." 	sum(tb_sum.subtotal) subtotal,                                               "
            ." 	'".$list_id."' list_product                                               "
            ." FROM                                                                         "
            ." (                                                                            "
            ." 	SELECT a.shop_id, b.cart_id, c.shop_name, c.url_image, b.product_id,        "
            ." 		sum(b.quantity) quantity,                                               "
            ." 		SUM(b.price) total_incart,                                              "
            ." 		SUM(a.final_price) price_product,                                       "
            ." 		SUM(a.final_price)*sum(b.quantity) subtotal                             "
            ." 	FROM products a, cart_items b, shops c                                      "
            ." 	WHERE a.product_id = b.product_id                                           "
            ." 	AND a.shop_id = c.shop_id                                                   "
            ." 	AND b.cart_id = :cart_id                                                   "
            ." 	AND b.product_id in (".$list_id.")                                                  "
            ." 	GROUP BY a.shop_id, b.cart_id, c.shop_name, c.url_image, b.product_id       "
            ." ) tb_sum                                                                     "
            ." GROUP BY tb_sum.shop_id, tb_sum.cart_id, tb_sum.shop_name, tb_sum.url_image  "),
            ['cart_id'=> $cart_id]);
        return $data;
    }
}
