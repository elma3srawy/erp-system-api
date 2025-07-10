<?php

namespace Modules\Core\Services\Authentication;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Modules\Core\Interfaces\AuthenticationServiceInterface;
use Modules\Core\Interfaces\UserAuthRepositoryInterface;

class UserAuthService implements AuthenticationServiceInterface
{
    use ResponseTrait;
    private  Guard|StatefulGuard  $guard;
    public function __construct(protected UserAuthRepositoryInterface $userAuthRepo)
    {
        $this->guard = Auth::guard('user');
    }
    public function register(Request $request): JsonResponse
    {
        $user = $this->userAuthRepo->createNewUser($request->only('name' , 'email' , 'password'));
        $this->guard->login($user);
        return $this->success(['user' => $user] , 'User register successfully!');
    }
    public function login(Request $request): JsonResponse
    {
        if($this->guard->attempt(
        $request->only('email' , 'password'),
           $request->boolean('remember')))
        {
            $request->session()->regenerate();
            return $this->success(['user' => $request->user()],'User login successfully!');
        }
        return $this->error(message: 'Invalid credentials');
    }
    public function logout(Request $request): JsonResponse
    {
        $this->guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->success(message: 'User logout successfully!');
    }

}
