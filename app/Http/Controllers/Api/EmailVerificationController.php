<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    use ApiResponse;

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return $this->error('invalid verification link', 403);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->success(null, 'email already verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->success(null, 'email verified successfully');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->success(null, 'email already verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->success(null, 'verification link sent');
    }
}
