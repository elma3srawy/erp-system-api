<?php

namespace Modules\Inventory\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|string|max:100|unique:products,name,' . $this->product->id,
            'section_id' => 'sometimes|exists:sections,id',
            'sku' => 'sometimes|string|max:50|unique:products,sku,' . $this->product->id,
            'quantity' => 'sometimes|integer|min:0|max:10000',
            'price' => 'sometimes|numeric|min:0|max:1000000',
        ];
    }
}
