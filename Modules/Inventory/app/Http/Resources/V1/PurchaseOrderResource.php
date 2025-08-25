<?php

namespace Modules\Inventory\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'order_date' => $this->order_date->format('Y-m-d'),
            'status' =>$this->whenNotNull($this->status, 'pending'),
            'total' => $this->whenLoaded('details', function () {
                return $this->details->sum('total');
            }),
            'details' => PurchaseOrderDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
