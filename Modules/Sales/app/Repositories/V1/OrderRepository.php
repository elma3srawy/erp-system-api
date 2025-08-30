<?php

namespace Modules\Sales\Repositories\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\Sales\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderRepository
{
    private static Model $model;  
    public function __construct() {
        self::$model = Order::getModel();
    }   
    public static function getQuantityByProductId(Order $order , $product_id) 
    {
        return $order->details()->where('product_id' , $product_id)->value('quantity');
    }

}
