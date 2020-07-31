<?php


namespace App\Repositories\Order;


interface OrderRepositoryInterface
{
    public function getPreviewOrder(array $list_item);
    public function getOrder(array $orders, $customer);
    public function getOrders($customer_id,$status);
    public function getOrderById($orderId);

}