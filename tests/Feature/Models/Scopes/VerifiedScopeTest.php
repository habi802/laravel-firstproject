<?php

namespace Tests\Feature\Models\Scopes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Scopes\VerifiedScope;

class VerifiedScopeTest extends TestCase
{
    use RefreshDatabase;

    public function testVerifiedScope()
    {
        $user = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();

        User::addGlobalScope(new VerifiedScope());

        $users = User::all();

        $this->assertCount(1, $users);

        $this->assertTrue(
            $users->contains($user)
        );

        $this->assertFalse(
            $users->contains($unverifiedUser)
        );
    }
}
