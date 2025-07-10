<?php

namespace Modules\Core\Http\Controllers\V1\Authentication;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\V1\AdminLoginRequest;
use Modules\Core\Services\Authentication\AdminAuthTokenService;

class  AdminAuthTokenController
{
    use ResponseTrait;
    public function __construct(protected AdminAuthTokenService $adminAuth ) {

    }

    public function create(AdminLoginRequest $request)
    {
        try {
            return $this->adminAuth->create($request);
        } catch (\Throwable $th) {
            return $this->error('something error!');
        }
    }
    public function delete(Request $request)
    {
        try {
            return $this->adminAuth->delete($request);
        } catch (\Throwable $th) {
            return $this->error('something error!');
        }
    }
}
