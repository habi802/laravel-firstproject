<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SocialLoginController extends Controller
{
    public function redirect(Provider $provider)
    {
        return Socialite::driver($provider->value)->redirect();
    }

    public function callback(Provider $provider)
    {
        $socialUser = Socialite::driver($provider->value)->user();
        $user = $this->register($socialUser);

        auth()->login($user);

        session()->socialite($provider, $socialUser->getEmail());

        return redirect()->intended();
    }

    private function register(SocialiteUser $socialUser): User
    {
        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
        ]);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $user;
    }
}
