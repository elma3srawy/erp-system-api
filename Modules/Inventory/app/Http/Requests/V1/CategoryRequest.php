<?php

namespace Modules\Inventory\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:100|unique:categories,name',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $categoryId = $this->route('category')->id;
            $rules['name'] = 'required|string|max:100|unique:categories,name,' . $categoryId;
        }       

        return $rules;
    }
}
