<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\RequirePassword;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testReturnsSubscribersDashboardViewForListOfSubscriber()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->get(route('dashboard.subscribers'))
             ->assertOk()
             ->assertViewIs('dashboard.subscribers');
    }
}
