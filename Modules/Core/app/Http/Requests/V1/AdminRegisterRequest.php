<?php

namespace Modules\Core\Http\Requests\V1;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required' , 'string' , 'min:3' , 'max:50'],
            'email' => ['required', 'email' , 'unique:admins,email'],
            'password' => [Password::min(8)->max(50)->mixedCase()]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
