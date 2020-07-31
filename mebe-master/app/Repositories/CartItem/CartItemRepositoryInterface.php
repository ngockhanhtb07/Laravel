<?php


namespace App\Repositories\CartItem;


interface CartItemRepositoryInterface
{
    public function addItemToCart(array $item,array $attribute);
    public function getItemCart($cart_id);
    public function changeCart($cart_id,array $item_id = null);
    public function getListItem($cart_id,array $product);
    public function getCartItemByShop($cartId, $shopId);
}