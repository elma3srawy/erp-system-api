<?php

namespace Modules\Inventory\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SectionRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:sections,name',
            'category_id' => 'required|integer|exists:categories,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $sectionId = $this->route('section')->id;
            $rules['name'] = 'required|string|max:100|unique:sections,name,' . $sectionId;
            $rules['category_id'] = 'required|integer|exists:categories,id';
        }       

        return $rules;
    }
}
