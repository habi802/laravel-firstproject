<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // 이메일 인증 메일 전송 페이지
    public function notice()
    {
        return view('auth.verify-email');
    }

    // 이메일 인증 완료
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/');
    }

    // 이메일 인증 메일 전송
    public function send(Request $request)
    {
        $user = $request->user();

        $user->sendEmailVerificationNotification();

        return back();
    }
}
