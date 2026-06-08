<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\JwtLoginRequest;

class JwtLoginController extends Controller
{
    public function store(JwtLoginRequest $request)
    {
        if (!$token = $this->guard()->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    private function guard()
    {
        $guard = auth('api');

        return $guard;
    }

    private function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    public function update()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    public function destroy()
    {
        $guard = $this->guard();

        $guard->logout();
        $guard->invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
