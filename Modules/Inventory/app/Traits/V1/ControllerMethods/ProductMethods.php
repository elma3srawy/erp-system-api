<?php

namespace Modules\Inventory\Traits\V1\ControllerMethods;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Http\Requests\V1\UpdateProductSuppliersRequest;

trait ProductMethods
{
    use ResponseTrait;
    public function updateSuppliers(UpdateProductSuppliersRequest $request, Product $product)
    {
        try {
            $data = [];
            foreach ($request->validated('suppliers')  as $value) {

                $data[$value['supplier_id']] = ['price' => $value['price']];
            }

            $product->suppliers()->sync($data);
            return $this->success([],'Supplier updated successfully');
        } catch (\Throwable $th) {
            return $this->error('Error updating supplier: '.$th->getMessage());
        }
    }
}