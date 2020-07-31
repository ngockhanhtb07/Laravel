<?php

namespace App\Http\ExternalScript\GHN;

use App\Helper\CartHelper;
use App\Helper\ShippingHelper;
use App\Http\Service\GHN\GHNServiceAPIClient;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Shop\ShopRepositoryInterface;
use App\Traits\CommonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GHNScript {

    protected $_serviceAPIClient;
    protected $_cartItemRepository;
    protected $_shopRepository;
    protected $_orderRepository;

    public function __construct(
        GHNServiceAPIClient $serviceAPIClient,
        CartItemRepositoryInterface $cartItemRepository,
        ShopRepositoryInterface $shopRepository,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->_serviceAPIClient = $serviceAPIClient;
        $this->_cartItemRepository = $cartItemRepository;
        $this->_shopRepository = $shopRepository;
        $this->_orderRepository = $orderRepository;
    }

    public function getToken() {
        $result = $this->_serviceAPIClient->getToken();
        if (!$result->code) {
            return $this->errorResponse($result->msg, 400);
        }
        return $result->data->Token;
    }

    public function calculateShippingFee(Request $request){
        $shippingMethodId = $request->input('shipping_method_id');
        $shopId = $request->input('shop_id');
        $cartId = Auth::user()->customer->cart->cart_id;

        $cartItems = $this->_cartItemRepository->getCartItemByShop($cartId, $shopId);

        $shop = $this->_shopRepository->find($shopId);
        if ($cartItems->count() == 0) {
            $msg = "You don't have any products in shop '$shop->shop_name'";
            Log::error($msg);
            return false;
        }
        $cartHelper = new CartHelper();
        $totalWeight = $cartHelper->totalWeightShop($cartItems);

        // get district ID
        $shippingHelper = new ShippingHelper($this->_serviceAPIClient);
        $districtFromId = $shippingHelper->getDistrictIDByName($shop->district);
        $districtToId = $shippingHelper->getDistrictIDByName($request->input('districtTo'));
        // format data before send
        $parameters = $shippingHelper->formatShippingData($districtFromId, $totalWeight, $districtToId);
        // send request
        $result = $this->_serviceAPIClient->calculateShippingFee($parameters);
        if (!$result->code) {
            Log::error($result->msg);
            return false;
        }
        return $result->data->CalculatedFee;
    }

    public function createShipment($orderNumber) {
        $order = $this->_orderRepository->getOrderById($orderNumber);
        $trackingNumber = $order->tracking_number;
        if ($trackingNumber) {
            $msg = "Shipment is created before with tracking number: '$trackingNumber'";
            Log::error($msg);
            $data = new \stdClass();
            $data->code = 0;
            $data->msg = $msg;
            $data->data = null;
            return $data;
        }
        $shippingHelper = new ShippingHelper($this->_serviceAPIClient);
        $parameters = $shippingHelper->formatShipmentData($order);
        $result = $this->_serviceAPIClient->createShipment($parameters);

        if (class_basename($result) == 'Response' && $result->getStatusCode() != 200) { // Psy\Response
            $result = json_decode($result->getBody()->getContents());
        }
        if (!$result->code) { // StdClass
            Log::error($result->msg);
            Log::error(json_encode($result->data));
        }
        $order->update(['tracking_number' => $result->data->OrderCode]);
        return $result;
    }

    public function cancelShipment($orderNumber) {
        $order = $this->_orderRepository->getOrderById($orderNumber);
        $trackingNumber = $order->tracking_number;
        if (!$trackingNumber) {
            Log::error("Shipment is not exist!");
            return false;
        }
        $parameters = ['OrderCode' => $trackingNumber];

        $result = $this->_serviceAPIClient->cancelShipment($parameters);
        if (class_basename($result) == 'Response' && $result->getStatusCode() != 200) { // Psy\Response
            $result = json_decode($result->getBody()->getContents());
        }
        if (!$result->code) { // StdClass
            Log::error($result->msg);
            Log::error(json_encode($result->data));
        }

        return $result;
    }
}