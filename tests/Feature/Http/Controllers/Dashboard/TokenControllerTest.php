<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\RequirePassword;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsTokensDashboardViewForListOfToken()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->get(route('dashboard.tokens'))
             ->assertViewIs('dashboard.tokens');
    }
}
