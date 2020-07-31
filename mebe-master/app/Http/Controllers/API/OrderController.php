<?php

namespace App\Http\Controllers\API;

use App\Model\OrderItem;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\ExternalScript\GHN\GHNScript;
use App\Http\Resources\Order\OrderResource;
use App\Repositories\Shop\ShopRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;

class OrderController extends Controller
{
    use CommonResponse;

    protected $_userRepository;
    protected $_orderRepository;
    protected $_cartItemRepository;
    protected $_shopRepository;
    protected $_productRepository;
    protected $_ghnScript;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartItemRepositoryInterface $cartItemRepository,
        ShopRepositoryInterface $shopRepository,
        ProductRepositoryInterface $productRepository,
        GHNScript $GHNScript
    ) {
        $this->_orderRepository = $orderRepository;
        $this->_cartItemRepository = $cartItemRepository;
        $this->_shopRepository = $shopRepository;
        $this->_productRepository = $productRepository;
        $this->_ghnScript = $GHNScript;
    }

    public function show($order_id)
    {
        $order = $this->_checkValidOrder($order_id);
        if (!is_null($order)) {
            $data = new OrderResource($order);
            return $this->successResponse($data, 'success', 200);
        } else {
            return $this->errorResponse('Order Not Found', 404);
        }

    }

    public function preview(Request $request)
    {
        $items = $request->items;
        $validate = $this->_validateData($request);
        if (!is_null($validate)) {
            return $validate;
        }
        $data = $this->_orderRepository->getPreviewOrder($items);
        $cartId = Auth::user()->customer->cart->cart_id;
        $this->_cartItemRepository->changeCart($cartId);
        return $this->successResponse($data, 'success', 200);
    }

    public function orders($status)
    {
        $customer_id = Auth::user()->customer->customer_id;
        $data = $this->_orderRepository->getOrders($customer_id, $status);
        if (count($data) > 0) {
            return $this->successResponse($data, 'get data success', 200);
        } else {
            return $this->errorResponse('Data Not Found', 404);
        }
    }

    public function store(Request $request)
    {
        $validate = $this->_validateDataCheckOut($request);
        if (!is_null($validate)) {
            return $validate;
        }
        $dataSave = $this->_saveData($request);
        return $this->successResponse($dataSave, 'create order success', 200);
    }

    public function cancel(Request $request)
    {
        $validate = $this->_validateData($request);
        if (!is_null($validate)) {
            return $validate;
        }

        $valid = $this->_checkProcessOrder($request);

        if (!$valid) {
            return $this->errorResponse('can not cancel. please check id again', 400);
        }

        $this->_changeStatusOrder($request->items);
        $data = new \stdClass();
        return $this->successResponse($data, 'success', 200);
    }

    protected function _checkValidOrder($order_id)
    {
        $customer = Auth::user()->customer->customer_id;
        $attribute['order_id'] = $order_id;
        $attribute['customer_id'] = $customer;
        $order = $this->_orderRepository->findByListCondition($attribute)->first();
        if (is_null($order)) {
            return null;
        } else {
            return $order;
        }
    }

    protected function _checkProcessOrder($request)
    {
        $customer = Auth::user()->customer->customer_id;
        $idOrders = array_unique($request->items);
        $listOrder = $this->_orderRepository->getOrder($idOrders, $customer);
        if (count($listOrder) == 0 || count($listOrder) != count($idOrders)) {
            return false;
        }
        foreach ($listOrder as $order) {
            // cancel shipment
            $shipment = $this->_ghnScript->cancelShipment($order->order_number);
            if ($order->status != 1 || !$shipment || !$shipment->code) {
                return false;
            }
        }
        return true;
    }

    protected function _changeStatusOrder(array $listId)
    {
        foreach ($listId as $id) {
            $attribute['status'] = 0;
            $result = $this->_orderRepository->update($attribute, $id);
            if ($result == false) {
                return $this->errorResponse('error', 204);
            }
        }

    }


    /**
     * @param $request
     */
    protected function _saveData($request)
    {
        // get info data
        $customer = Auth::user()->customer->customer_id;
        $shippingAddress = $request->address;
        $cartId = Auth::user()->customer->cart->cart_id;
        $orders = $request->shop_orders;
        $note = (isset($request->note)) ? $request->note : null;
        $orderSaved = $this->_createOrder($customer, $cartId, $orders, $shippingAddress, $note);
        return $orderSaved;
    }

    protected function _createOrder($customer, $cart_id, $shop_orders, $address, $note)
    {
        $orderSaved = array();
        DB::transaction(function () use (&$orderSaved, $customer, $cart_id, $shop_orders, $address, $note){

            foreach ($shop_orders as $key => $value) {
                $orderNumber = $this->_generateOrderNumber();
                $paymentMethod = 1;
                $shippingMethod = $value['ship_id'];
                $request = new Request(['shipping_method_id' => $value['ship_id'], 'shop_id' => $key]);
                $shippingFee = $this->_ghnScript->calculateShippingFee($request);
                if (!$shippingFee) {
                    throw new \Exception('error shipping method');
                }
                $cart = $this->_cartItemRepository
                    ->getListItem($cart_id, $value['products']);

                // calculator sub total, total
                $subTotal = $cart->sum('sub_total');
                $total = $subTotal + $shippingFee;

                $attribute['order_number'] = $orderNumber;
                $attribute['shipping_address'] = $address;
                $attribute['customer_id'] = $customer;
                $attribute['shop_id'] = $key;
                $attribute['shipping_method'] = $shippingMethod;
                $attribute['payment_method'] = $paymentMethod;
                $attribute['total'] = $total;
                $attribute['sub_total'] = $subTotal;
                $attribute['shipping_fee'] = $shippingFee;
                $attribute['note'] = $note;
                // create order
                $order = $this->_orderRepository->create($attribute);
                // create order item
                $orderItem = $this->_createOderItem($cart, $order);
                if ($orderItem && $order) {
                    // create shipment
                    $this->_ghnScript->createShipment($order->order_number);
                }
                array_push($orderSaved, $order->order_number);
            }
        });
        return $orderSaved;
    }

    protected function _minusQtyProduct($product_id, $qty)
    {
        $isChange = $this->_productRepository->changeQty($product_id,$qty,-1);
        if (!$isChange)
            throw new \Exception('error change qty product');

    }

    protected function _createOderItem($cart, $order)
    {
        foreach ($cart as $cartItem) {
            // prepare data and save cart item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->order_id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->sku = $cartItem->sku;
            $orderItem->price = $cartItem->price;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->product_type = $cartItem->product_type;
            $orderItem->save();
            $this->_minusQtyProduct($cartItem->product_id,$cartItem->quantity);
            $cartItem->delete();
        }
        return true;
    }

    protected function _validateData($request)
    {
        $validate = Validator::make($request->input(), [
            'items' => 'required|array',
            'items.*' => 'required|numeric|min:1',
        ]);
        if ($validate->fails()) {
            return $this->errorResponse($validate->errors(), 400);
        }
        return null;
    }

    protected function _validateDataCheckOut($request)
    {
        $validate = Validator::make($request->input(), [
            'address' => 'required|numeric|min:1',
            'note' => 'max:190',
            'shop_orders' => 'required||array||min:1',
            'shop_orders.*.products' => 'required|array|min:1',
            'shop_orders.*.products.*' => 'required|numeric|min:1',
            'shop_orders.*.ship_id' => 'required|numeric|min:1',
        ]);
        if ($validate->fails()) {
            return $this->errorResponse($validate->errors(), 400);
        }

        $addressValid = $this->_checkAddress($request->address);
        if (is_null($addressValid)) {
            return $this->errorResponse("Can not order to this address", 400);
        }

        $canBuy = $this->_checkProductCanBuy($this->_getProduct($request->shop_orders));
        if (!$canBuy) {
            return $this->errorResponse("Please check item in shop_orders or [products]", 400);
        }
        return null;
    }

    protected function _getProduct($list_shop)
    {
        $listProduct = array();
        foreach ($list_shop as $key => $items) {

            // if type of key (shop_id) is numeric
            if (!is_numeric($key)) {
                return array();
            }

            // add all product into list to compare with product in cart
            $checkShip = $this->_shopRepository->checkVendorInShop($key, $items['ship_id']);
            $product = $this->_productRepository->findByListCondition([
                'shop_id' => $key, 'product_id' => $items['products']
            ])->get();

            // validate product, shipping method belong to shop
            if (!$checkShip || $product->count() < 1) {
                return array();
            }
            foreach ($items['products'] as $value) {
                array_push($listProduct, $value);
            }
        }
        return $listProduct;
    }

    protected function _checkAddress($address_id)
    {
        // check valid address
        $address = Auth::user()->customer->addresses->find($address_id);
        return $address;
    }


    protected function _checkProductCanBuy(array $product)
    {
        $cart = Auth::user()->customer->cart->cart_id;
        $listProductInCart = $this->_getListProductInCart($cart);

        //check list product
        if (count($product) == 0) {
            return false;
        }

        // check list product in cart
        $checkExist = array_diff($product, $listProductInCart);
        if (count($checkExist) != 0) {
            return false;
        }

        // check price, quantity product
        $quantityChange = $this->_cartItemRepository->changeCart($cart, $product);
        if ($quantityChange > 0) {
            return false;
        }
        return true;
    }

    protected function _getListProductInCart($cart_id)
    {
        $listProductInCart = $this->_cartItemRepository->findBy('cart_id', $cart_id)->pluck('product_id')->toArray();
        return $listProductInCart;
    }

    protected function _generateOrderNumber()
    {
        $orderNumber = uniqid('od', true);
        $orderNumber = str_replace('.', '', $orderNumber);
        return $orderNumber;

    }


}
