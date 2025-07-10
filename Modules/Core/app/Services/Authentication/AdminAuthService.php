<?php

namespace Modules\Core\Services\Authentication;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Modules\Core\Interfaces\AdminAuthRepositoryInterface;
use Modules\Core\Interfaces\AuthenticationServiceInterface;

class AdminAuthService implements AuthenticationServiceInterface
{
    use ResponseTrait;
    private  Guard|StatefulGuard $guard;

    public function __construct(protected AdminAuthRepositoryInterface $adminAuthRepo)
    {
        $this->guard = Auth::guard('admin');
    }
    public function register(Request $request): JsonResponse
    {
        $admin = $this->adminAuthRepo->createNewAdmin($request->only('name' , 'email', 'password'));
        $this->guard->login($admin);
        return $this->success($admin , 'Admin register successfully');
    }
    public function login(Request $request): JsonResponse
    {
        if($this->guard->attempt(
            $request->only('email' , 'password'),
            $request->boolean('remember')))
        {
            $request->session()->regenerate();
            return $this->success(['admin' => $request->user('admin')],'Admin login successfully');
        }
        return $this->error('email or password is incorrect');
    }
    public function logout(Request $request): JsonResponse
    {
        $this->guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->success(message: 'Admin logout successfully');
    }

}
