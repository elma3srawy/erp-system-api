<?php

namespace Modules\Sales\Listeners\V1;


use Illuminate\Queue\InteractsWithQueue;
use Modules\Sales\Events\V1\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Sales\Events\V1\OrderUpdated;

class DecrementProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreated|OrderUpdated $event): void 
    {  
        $event->order->details->each(function ($details) {
            $details->product()->decrement('quantity', $details->quantity);
        });
    }
}
