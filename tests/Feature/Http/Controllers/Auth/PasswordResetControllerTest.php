<?php

namespace Tests\Feature\Http\Controller\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 비밀번호 재설정 메일 전송 뷰를 반환하는지 검증
    public function testReturnsForgotPasswordView()
    {
        $this->get(route('password.request'))
             ->assertOk()
             ->assertViewIs('auth.forgot-password');
    }

    // 존재하는 User를 대상으로 이메일을 보내고, 알림이 전송되었는지 검증
    public function testSendEmailForPasswordResets()
    {
        // 실제로 이메일, 알림을 보내면 안되기 때문에 fake()를 호출함
        Notification::fake();

        $user = User::factory()->create();

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

        Notification::assertSentTo(
            $user, ResetPassword::class
        );
    }

    // 이메일 보내기에 실패했을 경우 이메일이 보내지지 않았는지, email 에러를 뱉는지 검증
    public function testFailToSendEmailForPasswordResets()
    {
        Mail::fake();

        $this->post(route('password.email'), [
            'email' => $this->faker->safeEmail,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    // 비밀번호 재설정 뷰를 반환하는지 검증
    public function testReturnsResetPasswordView()
    {
        $token = Str::random(32);

        $this->get(route('password.reset', [
            'token' => $token,
        ]))
        ->assertOk()
        ->assertViewIs('auth.reset-password');
    }

    // 비밀번호 설정에 성공했을 경우에 대한 검증
    public function testPasswordResetsForValidToken()
    {
        Event::fake();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

        Event::assertDispatched(PasswordReset::class);
    }

    // 비밃번호 설정에 실패했을 경우에 대한 검증
    public function testFailToPasswordResetsForInvalidToken()
    {
        Event::fake();

        $this->post(route('password.update'), [
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => Str::random(),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('email');

        Event::assertNotDispatched(PasswordReset::class);
    }
}