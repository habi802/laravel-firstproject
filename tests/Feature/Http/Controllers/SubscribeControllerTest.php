<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribed as SubscribedMailable;

class SubscribeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 블로그 구독에 대한 검증
    public function testUserSubscribeBlog()
    {
        Mail::fake();

        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $this->actingAs($user)
             ->post(route('subscribe'), [
                'blog_id' => $blog->id,
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);

        Mail::assertQueued(SubscribedMailable::class);
    }

    // 블로그 구독 취소에 대한 검증
    public function testUserUnsubscribeBlog()
    {
        $user = User::factory()->create();

        $blog = Blog::factory()->hasAttached(
            factory: $user,
            relationship: 'subscribers'
        )->create();

        $this->actingAs($user)
             ->post(route('unsubscribe'), [
                'blog_id' => $blog->id,
             ])
             ->assertRedirect();

        $this->assertDatabaseMissing('blog_user', [
            'user_id' => $user->id,
            'blog_id' => $blog->id
        ]);
    }
}
