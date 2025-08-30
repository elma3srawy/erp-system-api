<?php

namespace Modules\CRM\Http\Controllers\V1;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\CRM\Http\Requests\V1\CustomerLoginRequest;
use Modules\CRM\Models\Customer;

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

    public function createToken(CustomerLoginRequest $request)
    {
        $customer = Customer::where('email' , $request->email)->first();

        if($customer && Hash::check($request->password , $customer->password)) {
            $token = $customer->createToken('Customer-Token' , ['customer'])->plainTextToken;
            return $this->success(["customer" => $customer , "token" => $token], 'Token Created Successfully');
        }
        return $this->error('The provided credentials are incorrect.');
    }
}
