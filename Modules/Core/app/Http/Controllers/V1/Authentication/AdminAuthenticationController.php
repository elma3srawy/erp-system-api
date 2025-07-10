<?php

namespace Modules\Core\Http\Controllers\V1\Authentication;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\V1\AdminLoginRequest;
use Modules\Core\Http\Requests\V1\AdminRegisterRequest;
use Modules\Core\Interfaces\AuthenticationServiceInterface;

class AdminAuthenticationController extends Controller
{
    use ResponseTrait;
    public function __construct(protected AuthenticationServiceInterface $authenticationServiceInterface)
    {

    }
    public function login(AdminLoginRequest $request): JsonResponse
    {
        try {
            return $this->authenticationServiceInterface->login($request);
        } catch (\Throwable $th) {
            return $this->error('something error!');
        }
    }


    public function register(AdminRegisterRequest $request): JsonResponse
    {
        try {
            return $this->authenticationServiceInterface->register($request);
        } catch (\Throwable $th) {
            return $this->error('something error!');
        }
    }

    public function logout(Request $request): JsonResponse
    {
         try {
            return $this->authenticationServiceInterface->logout($request);
        } catch (\Throwable $th) {
            return $this->error('something error!');
        }
    }

    public function me(Request $request)
    {
        return $this->success(['admin' => $request->user()]);
    }
}
