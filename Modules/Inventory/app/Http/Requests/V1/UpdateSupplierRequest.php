<?php

namespace Modules\Inventory\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:100',
            'contact_name' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:100',
           'phone' => ['nullable', 'string', 'max:20', 'regex:/^(010|011|012|015)[0-9]{8}$/'],

        ];
    }
}
