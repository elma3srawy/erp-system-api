<?php

namespace Modules\Sales\Transformers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "customer_id" => $this->customer_id,
            "order_date" => $this->order_date,
            "total_amount" => $this->total_amount,
            "status" => $this->status,
            "details" => $this->whenLoaded('details' , function(){
                return $this->details;
            }),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
