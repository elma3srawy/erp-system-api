<?php

namespace Modules\Sales\Rules\V1;

use Closure;
use Illuminate\Http\Request;
use Modules\Sales\Repositories\V1\OrderRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Inventory\Repositories\V1\ProductRepository;


class ProductAvailableQuanity implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function __construct(private Request $request){

    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        try {
            foreach ($value as $product) {
                $availableQuantity = ProductRepository::getQuantityById($product['product_id']);
                if($this->request->routeIs('api.v1.sales.orders.store')){
                    if($availableQuantity < $product['quantity'])
                    {
                        $fail('product quantity not available');
                    }
                }  
                if($this->request->routeIs('api.v1.sales.orders.update')){
                    $currentQuantity = OrderRepository::getQuantityByProductId($this->request->order , $product['product_id']);
                    if($availableQuantity + $currentQuantity < $product['quantity'])
                    {
                        $fail('product quantity not available');
                    }
                }
            }
        } catch (\Throwable $th) {
            $fail('product not found');
        }

    }
}
