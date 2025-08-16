<?php

namespace Modules\CRM\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => ['sometimes', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'address' => 'sometimes|string',
        ];
    }
}
