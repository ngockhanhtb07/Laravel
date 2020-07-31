<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\ExternalScript\GHN\GHNScript;
use App\Http\Service\GHN\GHNServiceAPIClient;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Shop\ShopRepositoryInterface;
use App\Traits\CommonResponse;
use Illuminate\Http\Request;


class ShippingController extends Controller {

    use CommonResponse;
    protected $_ghnScript;

    public function __construct(
        GHNScript $GHNScript
    )
    {
        $this->_ghnScript = $GHNScript;
    }

    public function getToken() {
        $result = $this->_ghnScript->getToken();
        if (!$result->code) {
            return $this->errorResponse($result->msg, 400);
        }
        return $this->successResponse($result->data, 'success');
    }

    public function calculateShippingFee(Request $request){
        $shippingFee = $this->_ghnScript->calculateShippingFee($request);
        if (!$shippingFee) {
            return $this->errorResponse('Can\'t get shipping fee, please try again later!' , 400);
        }
        return $this->successResponse(['shippingFee' => $shippingFee], 'success');
    }

    public function shipment(Request $request) {
        $orderNumber = $request->input('order_number');
        $shipment = $this->_ghnScript->createShipment($orderNumber);
        if ($shipment->code) {
            return $this->successResponse($shipment->data, 'success');
        }
        return $this->errorResponse($shipment->msg, 400, $shipment->data);
    }

    public function cancel(Request $request) {
        $orderNumber = $request->input('order_number');
        $shipment = $this->_ghnScript->cancelShipment($orderNumber);
        if ($shipment->code) {
            return $this->successResponse($shipment->data, 'success');
        }
        return $this->errorResponse($shipment->msg, 400, $shipment->data);
    }


}