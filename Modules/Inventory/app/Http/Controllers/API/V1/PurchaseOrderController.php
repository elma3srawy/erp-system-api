<?php

namespace Modules\Inventory\Http\Controllers\API\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\PurchaseOrder;
use Modules\Inventory\Http\Resources\V1\PurchaseOrderResource;
use Modules\Inventory\Http\Requests\V1\StorePurchaseOrderRequest;
use Modules\Inventory\Traits\V1\ControllerMethods\PurchaseOrderMethods;

class PurchaseOrderController extends Controller
{
    use ResponseTrait, PurchaseOrderMethods;

    public function index(): JsonResponse
    {
        $purchaseOrders = Cache::tags('purchase_orders')->remember('purchase-orders-'.request('page'), 60*60, function () {
            return PurchaseOrder::with(['supplier', 'details.product'])->latest()->paginate(PAGINATION)->toResourceCollection(PurchaseOrderResource::class)->resource;
        });
        return $this->success($purchaseOrders , 'Purchase orders retrieved successfully.');
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::create($request->except('items'));

            $this->insertOrderDetails($purchaseOrder, $request->validated('items'));

            DB::commit();

            $purchaseOrder->load(['supplier', 'details.product']);

            return $this->created(new PurchaseOrderResource($purchaseOrder), 'Purchase order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error creating purchase order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load(['supplier', 'details.product']);
        return $this->success(new PurchaseOrderResource($purchaseOrder) , "Purchase order retrieved successfully.");
    }

    public function update(StorePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        DB::beginTransaction();
        try {
            $purchaseOrder->update($request->except('items'));
            
            $purchaseOrder->details()->delete();

            $this->insertOrderDetails($purchaseOrder, $request->validated('items'));

            DB::commit();

            $purchaseOrder->load(['supplier', 'details.product']);

            return $this->updated(new PurchaseOrderResource($purchaseOrder), 'Purchase order updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error updating purchase order: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        try {
            $purchaseOrder->delete();
            return $this->deleted('Purchase order deleted successfully.');
        } catch (\Exception $e) {
            return $this->error('Error deleting purchase order: ' . $e->getMessage());
        }
    }
}
