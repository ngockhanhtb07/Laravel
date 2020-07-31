<?php

namespace App\Http\Controllers\API;

use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Traits\CommonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use CommonResponse;

    /**
     * @var CartRepository
     */
    protected $_cartRepository;
    protected $_productRepository;
    protected $_cartItemRepository;
    protected $_customerRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        CartItemRepositoryInterface $cartItemRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->_cartRepository = $cartRepository;
        $this->_productRepository = $productRepository;
        $this->_cartItemRepository = $cartItemRepository;
        $this->_customerRepository = $customerRepository;
    }

    public function show()
    {
        $cartId = Auth::user()->customer->cart->cart_id;
        $data = $this->_cartItemRepository->getItemCart($cartId);
        return $this->successResponse($data,'success',200);

    }

    public function getQtyCart()
    {
        // get cart id by user
        $cart = Auth::user()->customer->cart->cart_id;
        // get record by cart and sum quantity
        $quantity = $this->_cartItemRepository->findBy('cart_id',$cart)->sum('quantity');
        return $this->successResponse($quantity,'success',200);

    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->input(), [
            'item_id' => 'required|numeric',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validate->fails())
            return $this->errorResponse($validate->errors(), 400);

        $product_valid = $this->_productRepository->productIsValid($request->item_id);

        if (!$product_valid)
            return $this->errorResponse('Product not found or check variant product', 404);

        $save_success = $this->addItemToCart($request->item_id, $request->quantity);

        if (!$save_success) {
            return $this->errorResponse('failed. check number qty again', 400);
        }

        return $this->successResponse(null, 'success', 200);
    }

    public function delete($item_id)
    {
        $item = $this->checkValid($item_id);
        if (is_null($item)) {
            return $this->errorResponse('Refresh app and check again', 404);
        }

        $is_delete = $this->_cartItemRepository->delete($item->item_id);

        if (!$is_delete) {
            return $this->errorResponse('failed. Check again', 400);
        }
        return $this->successResponse($item_id, 'Success', 200);

    }

    // function check item in cart.
    protected function checkValid($productId)
    {
        $list_condition = array();
        $cartId = Auth::user()->customer->cart->cart_id;
        $list_condition['cart_id'] = $cartId;
        $list_condition['product_id'] = $productId;

        $result = $this->_cartItemRepository->findByListCondition($list_condition)->first();
        return $result;
    }

    protected function addItemToCart($id, $qty)
    {
        $product = $this->_productRepository->find($id)->only(['sku', 'final_price', 'product_type', 'quantity']);
        if (!is_null($product)) {
            if ($product['quantity'] < $qty) {
                return false;
            }
            $cartId = Auth::user()->customer->cart->cart_id;
            $item = [
                'cart_id' => (int) $cartId,
                'product_id' => (int) $id
            ];
            $attribute = [
                'sku' => $product['sku'],
                'quantity' => $qty,
                'price' => $product['final_price'],
                'product_type' => $product['product_type']
            ];
            $this->_cartItemRepository->addItemToCart($item, $attribute);
            return true;
        }
        return false;
    }


    // check change item in cart
    protected function checkChangeInCart($cart_id)
    {
        $number_change = $this->_cartItemRepository->changeCart($cart_id);
        return ($number_change > 0);
    }


}
