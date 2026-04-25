<?php

namespace Tests\Feature\Broadcasting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Broadcasting\UserChannel;

class UserChannelTest extends TestCase
{
    use RefreshDatabase;

    public function testJoinMethodGrantsAccessToChannelForAuthenticatedUser()
    {
        $user = User::factory()->create();

        $userChannel = new UserChannel();

        $this->assertTrue(
            $userChannel->join($user, $user->id)
        );
    }
}
