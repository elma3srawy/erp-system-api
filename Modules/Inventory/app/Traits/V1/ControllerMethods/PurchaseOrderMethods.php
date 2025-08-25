<?php

namespace Modules\Inventory\Traits\V1\ControllerMethods;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Modules\Inventory\Models\PurchaseOrder;

trait PurchaseOrderMethods
{
    use ResponseTrait;

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,received,cancelled',
        ]);

        try {
            $purchaseOrder->update(['status' => $request->status]);
            return $this->success([], 'Purchase order status updated successfully.');
        } catch (\Exception $e) {
            return $this->error('Error updating status: ' . $e->getMessage());
        }
    }
    public function insertOrderDetails(PurchaseOrder $purchaseOrder, $validated)
    {
        foreach ($validated as $item) {
            $purchaseOrder->details()->create($item);
        }
    }
}
