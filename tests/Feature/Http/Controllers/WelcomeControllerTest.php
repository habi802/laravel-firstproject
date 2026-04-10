<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\WelcomeController;
use App\Models\Blog;

class WelcomeControllerTest extends TestCase
{
    use RefreshDatabase;

    // 웰컴 뷰에 관한 검증
    public function testReturnsWelcomeView()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(action(WelcomeController::class))
             ->assertOk()
             ->assertViewIs('welcome');
    }

    // 구독한 블로그가 있는 사용자에 관한 검증
    public function testReturnWelcomeViewWithSubscriptions()
    {
        $subscriptions = Blog::factory()->hasPosts(5)->create();

        $user = User::factory()->hasAttached(
            factory: $subscriptions,
            relationship: 'subscriptions'
        )->create();

        $this->actingAs($user)
             ->get(action(WelcomeController::class))
             ->assertOk()
             ->assertViewIs('welcome');
    }
}
