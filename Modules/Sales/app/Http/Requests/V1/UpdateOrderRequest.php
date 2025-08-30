<?php

namespace Modules\Sales\Http\Requests\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Sales\Rules\V1\ProductAvailableQuanity;
use Modules\Inventory\Repositories\V1\ProductRepository;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "products" => ["required","array", "min:1" , new ProductAvailableQuanity($this)],
            "products.*.product_id" => ["required", "integer","exists:products,id", 'distinct'],
            "products.*.quantity" => ["required", "integer","min:1"]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::check('can-update-order' , $this->order);
    }


    public function toOrderData(): array
    {
        $productsWithPrice = [];

        foreach ($this->validated('products') as $product) {
            $unit_price = ProductRepository::getPriceById($product['product_id']);

            $productsWithPrice[] = [
                'product_id' => $product['product_id'],
                'quantity'   => $product['quantity'],
                'unit_price' => $unit_price,
                'total'      => $unit_price * $product['quantity'],
            ];
        }

        return [
            'total_amount' => array_sum(array_column($productsWithPrice, 'total')),
            'products' => $productsWithPrice,
        ];
    }
}
