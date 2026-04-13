<?php

namespace Tests\Feature\Mail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;
use App\Mail\Subscribed;

class SubscribedTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplaysUserNameAndBlogDisplayName()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $mailable = new Subscribed($user, $blog);

        $mailable->assertHasSubject(
            '[라라벨] 구독 알림'
        );

        $mailable->assertSeeInOrderInHtml([
            $user->name,
            $blog->display_name,
        ]);
    }
}
