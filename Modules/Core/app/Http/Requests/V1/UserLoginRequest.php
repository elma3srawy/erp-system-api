<?php

namespace Modules\Core\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
         return [
                'email' => ['Required' , 'email' , 'string'],
                'password' => ['required' , 'string' , 'min:3' , 'max:255'],
                'remember' => ['boolean' , 'sometimes']
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
