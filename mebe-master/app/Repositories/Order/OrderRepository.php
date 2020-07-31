<?php


namespace App\Repositories\Order;


use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\PreviewOrderCollection;
use App\Model\Order;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Order::class;
    }

    public function getPreviewOrder(array $list_item)
    {
        $customer =  Auth::user()->customer;
        $condition = ['customer' => $customer];
        $condition['items'] = $list_item;
        $collection = collect($condition);
        $data = new PreviewOrderCollection($collection);
        return $data;

    }

    public function getOrder(array $orders, $customer)
    {
        if (is_null($customer))
            return $this->_model->whereIn('order_id',$orders)->get();
        else
            return $this->_model->where('customer_id',$customer)->whereIn('order_id',$orders)->get();
    }

    public function getOrders($customer_id, $status)
    {
        if (!is_null($status))
        {
            $filter = [
                ['customer_id' ,'=',$customer_id],
                ['status','=', $status],
            ];
        } else {
            $filter = [
                ['customer_id' ,'=',$customer_id]
            ];
        }
        $order = $this->_model->where($filter)->get();
        $data = new OrderCollection($order);
        return $data;
    }

    public function getOrderById($orderId) {
        $order = $this->_model->with(['shop', 'orderItems'])
            ->where('order_id', $orderId)
            ->orWhere('order_number', $orderId)
            ->firstOrFail();
        return new OrderResource($order);
    }

}