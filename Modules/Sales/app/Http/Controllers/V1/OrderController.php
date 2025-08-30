<?php

namespace Modules\Sales\Http\Controllers\V1;


use Exception;
use App\Traits\ResponseTrait;
use Modules\Sales\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Modules\Sales\Events\V1\OrderCreated;
use Modules\Sales\Events\V1\OrderUpdated;
use Modules\Sales\Jobs\V1\ResetProductQuantity;
use Modules\Sales\Transformers\V1\OrderResource;
use Modules\Sales\Services\V1\Orders\OrderService;
use Modules\Sales\Http\Requests\V1\StoreOrderRequest;
use Modules\Sales\Http\Requests\V1\UpdateOrderRequest;
use Modules\Sales\Traits\V1\ControllerMethods\OrderMethods;

class OrderController extends Controller
{
    use ResponseTrait,
        OrderMethods;

    public function __construct(protected OrderService $order) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Cache::tags('orders')->remember('order-page'.request('page', 1), 60*60, function () {
            return Order::with(['details.product'])->latest()->paginate(PAGINATION)->toResourceCollection(OrderResource::class)->resource;
        });
        return $this->success($orders , 'Orders retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $order = $this->order->storeOrder($request->toOrderData());
            
            OrderCreated::dispatch($order);
            
            $order->load('details')->refresh();
            
            DB::commit();
            return $this->created($order->toResource(OrderResource::class), "Order Created Successfully");
            
        }catch(Exception $e){
            DB::rollBack();
            return $this->serverError();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['details.product']);

        return $this->success($order->toResource(OrderResource::class) , "Order retrieve successfully");
    }

    public function update(UpdateOrderRequest $request, Order $order) 
    {
        DB::beginTransaction();
        try {
            $order->load('details.product');

            ResetProductQuantity::dispatch($order->details);

            $order = $this->order->updateOrderDetails($order , $request->toOrderData());
            
            $order->refresh();
            
            OrderUpdated::dispatch($order);
            
            DB::commit();
            
            return $this->updated($order->toResource(OrderResource::class), "Order Updated Successfully");
        }catch(Exception $e){
            DB::rollBack();
            return $this->serverError();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {

        if (! Gate::allows('can-delete-order', $order)) {
            abort(403);
        }
        
        DB::beginTransaction();
        try {
            $order->delete();
            DB::commit();
            return $this->deleted("Order Deleted Successfully");
        }catch(Exception $e){
            DB::rollBack();
            return $this->serverError();
        }
    }
}
