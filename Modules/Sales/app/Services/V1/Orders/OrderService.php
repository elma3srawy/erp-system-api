<?php

namespace Modules\Sales\Services\V1\Orders;

use Modules\Sales\Models\Order;

class OrderService
{
    public function storeOrder(array $data) 
    {
        $order =  Order::create($data);
        $this->storeOrderDetails($order , $data['products']);

        return $order;
    }

    
    public function storeOrderDetails(Order $order , $data)
    {
        $order->details()->createMany($data);
    }
    
    public function updateOrderDetails(Order $order , $data): Order
    {
        $order->update(['total_amount' => $data['total_amount']]);
        $order->details()->delete();
        $this->storeOrderDetails($order,$data['products']);
        return $order;
    }

   
}
