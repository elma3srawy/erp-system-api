<?php

namespace Modules\Core\Http\Controllers\V1\Authentication;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UserVerificationController extends Controller
{
    use ResponseTrait;
    public function sendVerificationEmail(Request $request)
    {
        $request->user('user')->sendEmailVerificationNotification();
        return $this->success(message:'Verification link sent!');
    }
    public function verifyEmail(EmailVerificationRequest $request)
    {
        if($request->user('user')->hasVerifiedEmail())
        {
            return $this->error('Email is already verified.');
        }
        $request->fulfill();

        return $this->success(message: 'email verified successfully');
    }
}
