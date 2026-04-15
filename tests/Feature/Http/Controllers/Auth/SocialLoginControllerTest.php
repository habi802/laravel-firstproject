<?php

namespace Tests\Feature\Http\Controller\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;

class SocialLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 서비 제공자의 인증 페이지로 리다이렉트하는지 검증
    public function testRedirectToProvider()
    {
        //$provider = Provider::Github;
        //$provider = Provider::Kakao;
        $provider = Provider::Naver;

        $this->get(route('login.social', $provider))
             //->assertRedirectContains('https://github.com/login/oauth/authorize');
             //->assertRedirectContains('https://kauth.kakao.com/oauth/authorize');
             ->assertRedirectContains('https://nid.naver.com/oauth2.0/authorize');
    }

    // 가짜 유저를 생성하여 인증 및 DB, 리다이렉트 검증
    public function testSocialLoginAndUpdateOrCreateUser()
    {
        //$provider = Provider::Github;
        //$provider = Provider::Kakao;
        $provider = Provider::Naver;

        $data = [
            'email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
        ];

        $socialUser = $this->mock(SocialiteUser::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('getEmail')
                 ->andReturn($data['email']);
            $mock->shouldReceive('getName')
                 ->andReturn($data['name']);
        });

        Socialite::shouldReceive('driver->user')
            ->once()
            ->andReturn($socialUser);

        $this->get(route('login.social.callback', $provider))
             ->assertRedirect();
        
        $this->assertEquals(session()->socialite($provider), $socialUser->getEmail());

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', $data);
    }
}
