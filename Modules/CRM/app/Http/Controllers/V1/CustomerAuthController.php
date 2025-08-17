<?php

namespace Modules\CRM\Http\Controllers\V1;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\V1\CustomerLoginRequest;


class CustomerAuthController extends Controller
{
    use ResponseTrait;
    
    public function login(CustomerLoginRequest $request)
    {
        if(auth()->guard('customer')->attempt(
            $request->only('email' , 'password'),
            $request->boolean('remember')
        )){
            $request->session()->regenerate();
            return $this->success(['customer' => auth()->guard('customer')->user()]);
        }
        return $this->error('Invalid credentials');
    }
}
