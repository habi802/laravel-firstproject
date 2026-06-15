<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\JWT;

class JwtLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 로그인 시 토큰 발급에 관한 검증
    public function testCreateJwtForValidCredentials()
    {
        $user = User::factory()->create();

        $response = $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['access_token', 'token_type', 'expires_in']);
        })
        ->assertSuccessful();

        $this->assertAuthenticated('api');

        $this->assertTrue(
            app(JWT::class)->setToken(
                $response->json()['access_token']
            )->check()
        );
    }

    // 로그인 시 토큰 발급 실패에 관함 검증
    public function testFailToCreateJwtForInvalidCredentials()
    {
        $user = User::factory()->create();

        $this->post(route('jwt.login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertJson(function (AssertableJson $json) {
            $json->has('error');
        })
        ->assertUnauthorized();

        $this->assertGuest('api');
    }

    // 토큰 갱신에 관한 검증
    public function testRefreshJwt()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this->withToken($token)
                         ->put(route('jwt.refresh'))
                         ->assertJson(function (AssertableJson $json) {
                            $json->hasAll(['access_token', 'token_type', 'expires_in']);
                         })
                         ->assertSuccessful();

        $this->assertTrue(
            app(JWT::class)->setToken(
                $response->json()['access_token']
            )->check()
        );

        $this->assertFalse(
            app(JWT::class)->setToken($token)->check()
        );
    }

    // 토큰 삭제에 관한 검증
    public function testDeleteJwt()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $this->withToken($token)
             ->delete(route('jwt.logout'))
             ->assertJson(function (AssertableJson $json) {
                $json->has('message');
             })
             ->assertSuccessful();

        $this->assertGuest('api');

        $this->assertFalse(
            app(JWT::class)->setToken($token)->check()
        );
    }
}
