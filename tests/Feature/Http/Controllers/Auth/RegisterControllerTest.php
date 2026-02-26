<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;

class RegisterControllerTest extends TestCase
{
    // RefreshDatabase: 테스트 케이스가 실행되면 데이터베이스를 초기화함
    // WithFaker: 모델 팩토리에서 Faker를 사용할 수 있도록 해줌
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testReturnsRegisterView()
    {
        $this->get(route('register'))
             ->assertOk()
             ->assertViewIs('auth.register');
    }

    public function testUserRegisteration()
    {
        Event::fake();
        
        $email = $this->faker->safeEmail;

        $this->post(route('register'), [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'password'
        ])
        ->assertRedirect(
            route('verification.notice')
        );

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $this->assertAuthenticated();

        Event::assertDispatched(Registered::class);
    }
}
