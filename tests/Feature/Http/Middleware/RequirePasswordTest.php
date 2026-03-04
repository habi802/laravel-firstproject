<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\RequirePassword;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Enums\Provider;

class RequirePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 소셜 로그인이 아닐 때 실제로 리다이렉트 응답을 반환하는지 검증
    public function testRequirePasswordRedirect()
    {
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $response = $requirePasswordMiddleware->handle($request, function () {});

        $this->assertEquals($response->getStatusCode(), 302);
    }

    // 소셜 로그인일 때 리다이렉트하지 않는지 검증
    public function testRequirePasswordDoesNotRedirect()
    {
        $requirePasswordMiddleware = app(RequirePassword::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        //$request->session()->socialite(Provider::Github, $this->faker->safeEmail);
        //$request->session()->socialite(Provider::Kakao, $this->faker->safeEmail);
        $request->session()->socialite(Provider::Naver, $this->faker->safeEmail);

        $response = $requirePasswordMiddleware->handle($request, function () {});

        $this->assertEquals($response, null);
    }
}
