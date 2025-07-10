<?php

namespace Modules\Core\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface AuthenticationServiceInterface
{
    public function register(Request $request): JsonResponse;
    public function login(Request $request): JsonResponse;
    public function logout(Request $request): JsonResponse;
}
