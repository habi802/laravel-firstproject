<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\RequirePassword;
use Illuminate\Support\Facades\Hash;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 마이페이지 뷰를 반환하는지 검증
    public function testReturnsShowView()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->get(route('profile.show'))
             ->assertOk()
             ->assertViewIs('auth.profile.show');
    }
    
    // 개인정보 수정 페이지 뷰를 반환하는지 검증
    public function testReturnsEditView()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->get(route('profile.edit'))
             ->assertOk()
             ->assertViewIs('auth.profile.edit');
    }
    
    // 개인정보 수정 검증
    public function testUpdate()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->put(route('profile.update'), $data)
             ->assertRedirect(route('profile.show'));

        $this->assertTrue(Hash::check('password', $user->getAuthPassword()));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
        ]);
    }
    
    // 개인정보 수정 검증(비밀번호 입력)
    public function testUpdateContainsPassword(): void
    {
        $user = User::factory()->create();
        $password = $this->faker->password(8);

        $data = [
            'name' => $this->faker->name(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->put(route('profile.update'), $data)
             ->assertRedirect(route('profile.show'));

        $this->assertTrue(Hash::check($password, $user->getAuthPassword()));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
        ]);
    }
}
