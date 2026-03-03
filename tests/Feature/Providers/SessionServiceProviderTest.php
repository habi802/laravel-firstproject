<?php

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Provider;
use Illuminate\Support\Facades\Session;

class SessionServiceProviderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // SessionServiceProvider.php에 정의한 socialite 매크로가 정상 등록되었고, 실제로 세션에 값을 저장하는지 검증
    public function testSocialiteMacro(): void
    {
        $this->assertTrue(Session::hasMacro('socialite'));

        //Session::socialite(Provider::Github, $this->faker->safeEmail());
        //Session::socialite(Provider::Kakao, $this->faker->safeEmail());
        Session::socialite(Provider::Naver, $this->faker->safeEmail());

        //$this->assertTrue(Session::has('socialite.github'));
        //$this->assertTrue(Session::has('socialite.kakao'));
        $this->assertTrue(Session::has('socialite.naver'));
    }

    // socialiteMissingAll 매크로가 소셜 로그인 관련 세션 값이 비어 있는지를 판단하는지 검증
    public function testSocialiteMissingAllMacro(): void
    {
        $this->assertTrue(Session::hasMacro('socialiteMissingAll'));

        $this->assertTrue(Session::socialiteMissingAll());

        //Session::put('socialite.github', $this->faker->safeEmail());
        //Session::put('socialite.kakao', $this->faker->safeEmail());
        Session::put('socialite.naver', $this->faker->safeEmail());

        $this->assertFalse(Session::socialiteMissingAll());
    }
}
