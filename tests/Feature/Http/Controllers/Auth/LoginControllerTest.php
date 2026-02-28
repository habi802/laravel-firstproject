<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 로그인 뷰를 반환하는지 검증
    public function testReturnsLoginView()
    {
        $this->get(route('login'))
             ->assertOk()
             ->assertViewIs('auth.login');
    }

    // 로그인 성공 시, 비밀번호를 전달하고 인증 여부를 체크한 뒤, 리다이렉트 여부를 검증
    public function testLoginForValidCrdentials()
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect();

        $this->assertAuthenticated();
    }

    // 로그인 실패 시 로그인이 되지 않음을 검증하고, 이후 응답이 리다이렉트를 포함하는지, failed를 세션 에러로 가지고 있는지 검증
    public function testFailToLoginForInvalidCredentials()
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('failed');

        $this->assertGuest();
    }

    // 로그아웃 이후 다시 게스트 사용자인지 검증
    public function testLogout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('logout'))
             ->assertRedirect('/');

        $this->assertGuest();
    }
}
