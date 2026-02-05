<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->to(RouteServiceProvider::HOME);
    }

    public function send(Request $request)
    {
        $user = $request->user();

        $user->sendEmailVerificationNotification();

        return back();
    }
}
