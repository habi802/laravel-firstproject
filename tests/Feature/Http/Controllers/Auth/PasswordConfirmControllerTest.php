<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Middleware\Authenticate;
use App\Models\User;

class PasswordConfirmControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 비밀번호 확인 뷰를 반환하는지 검증
    public function testReturnsPasswordConfirmView()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(route('password.confirm'))
             ->assertOk()
             ->assertViewIs('auth.confirm-password');
    }

    // 비밀번호 확인이 성공했는지에 대한 검증
    public function testConfirmsPasswordForCorrectPassword()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('password.confirm'), [
                'password' => 'password',
             ])
             ->assertRedirect();
    }

    // 비밀번호 확인이 실패했는지에 대한 검증
    public function testFailToConfirmPasswordForIncorrectPassword()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('password.confirm'), [
                'password' => $this->faker->password(8),
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('password');
    }
}
