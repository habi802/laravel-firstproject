<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SendResetLinkRequest;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // 비밀번호 재설정 메일 전송 페이지
    public function request()
    {
        return view('auth.forgot-password');
    }

    // 비밀번호 재설정 메일 전송
    public function email(SendResetLinkRequest $request)
    {
        // 비밀번호 재설정 메일 전송
        $status = Password::sendResetLink($request->validated());

        // 전송 결과를 확인하여 성공 시 성공 메세지, 실패 시 에러 메세지와 함께 이전 페이지로 이동
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // 비밀번호 재설정 페이지
    public function reset(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    // 비밃번호 재설정
    public function update(ResetPasswordRequest $request)
    {
        $status = Password::reset($request->validated(), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? to_route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
