<?php

namespace Modules\Core\Services\Authentication;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Modules\Core\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


class AdminAuthTokenService
{
    use ResponseTrait;
    public function create(Request $request): JsonResponse
    {
        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return $this->error('The provided credentials are incorrect.');
        }

        $token = $admin->createToken('Admin-Token' , ['admin'])->plainTextToken;
        return $this->success(["admin" => $admin, 'token' => $token] ,  "Admin token created successfully!");
    }
    public function delete(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success('Token deleted successfully.');
    }
}
