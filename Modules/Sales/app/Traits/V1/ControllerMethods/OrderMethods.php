<?php

namespace Modules\Sales\Traits\V1\ControllerMethods;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Modules\Sales\Models\Order;

trait OrderMethods
{
    use ResponseTrait;
    public function changeStatus(Request $request, Order $order) 
    {
        $data = $request->validate(
            [
                "status" => ["required" , "in:pending,confirmed,shipped,delivered,cancelled"]
            ]
        );
        $order->update($data);
        return $this->updated($order , "Status Updated Successfully");
    }
}