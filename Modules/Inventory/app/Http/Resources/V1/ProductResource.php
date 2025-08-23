<?php

namespace Modules\Inventory\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inventory\Http\Resources\V1\SectionResource;
use Modules\Inventory\Http\Resources\V1\SupplierResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'section' => $this->whenLoaded('section', function () {
                return $this->section->toResource(SectionResource::class);
            }),
            'suppliers' => $this->whenLoaded('suppliers', function () {
                return $this->suppliers->toResourceCollection(SupplierResource::class);
            }),
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];

    }
}
