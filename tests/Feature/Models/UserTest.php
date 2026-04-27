<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testVerifiedScope()
    {
        $user = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();

        $users = User::verified()->get();

        $this->assertCount(1, $users);

        $this->assertTrue(
            $users->contains($user)
        );

        $this->assertFalse(
            $users->contains($unverifiedUser)
        );
    }
}
