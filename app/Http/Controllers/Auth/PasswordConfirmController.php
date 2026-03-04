<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordConfirmRequest;

class PasswordConfirmController extends Controller
{
    // 비밀번호 확인 페이지
    public function showPasswordConfirmationForm()
    {
        return view('auth.confirm-password');
    }

    // 비밀번호 확인
    public function confirm(PasswordConfirmRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }
}
