<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Enums\Provider;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register', [
            'providers' => Provider::cases(),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            ...$request->validated(),
            'password' => Hash::make($request->password),
        ]);

        // 로그인
        auth()->login($user);

        // 이메일 인증
        event(new Registered($user));
      
        return to_route('verification.notice');
    }
}
