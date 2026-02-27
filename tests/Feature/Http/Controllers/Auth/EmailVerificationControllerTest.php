<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // 이메일 인증 테스트
    public function testVerifyEmail()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user) // actiongAs()로 테스트 시 로그인이 된 것으로 간주
             ->withoutMiddleware(ValidateSignature::class) // 전자서명을 검증하는 singed 미들웨어는 테스트에 방해가 되므로 제외시킴
             ->get(route('verification.verify', [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
             ]))
             ->assertRedirect('/');

        $this->assertTrue($user->hasVerifiedEmail());
    }

    // 이메일 인증 메일 전송 뷰 테스트
    public function testReturnVerifyEmailViewForUnverifiedUser()
    {
        $this->withoutMiddleware(Authenticate::class)
             ->get(route('verification.notice'))
             ->assertOk()
             ->assertViewIs('auth.verify-email');
    }

    // 이메일 인증 메일 전송 테스트
    public function testSendEmailForEmailVerification()
    {
        Notification::fake(); // 테스트 시 실제로 알림이 가지 않게 Notification::fake() 사용

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
             ->withoutMiddleware(ValidateSignature::class)
             ->post(route('verification.send'))
             ->assertRedirect();

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
