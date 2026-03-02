<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Enums\Provider;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login', [
            'providers' => Provider::cases(),
        ]);
    }

    public function login(LoginRequest $request)
    {
        // 로그인 실패 시
        if (!auth()->attempt($request->validated(), $request->boolean('remember'))) {
            return back()->withErrors([
                'failed' => __('auth.failed'),
            ]);
        }

        // 로그인 성공 시 사용자가 원래 접속하려던 페이지로 다시 돌아가게 함
        return redirect()->intended();
    }

    public function logout()
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
