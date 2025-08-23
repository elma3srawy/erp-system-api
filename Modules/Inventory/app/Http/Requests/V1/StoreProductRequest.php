<?php

namespace Modules\Inventory\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'name' => 'required|string|max:100|unique:products,name',
            'section_id' => 'required|integer|exists:sections,id',
            'sku' => 'required|string|max:50|unique:products,sku',
            'quantity' => 'required|integer|min:0|max:10000',
            'price' => 'required|numeric|min:0|max:1000000',
            'supplier_id' => 'required|integer|exists:suppliers,id',
       ];
    }
}
