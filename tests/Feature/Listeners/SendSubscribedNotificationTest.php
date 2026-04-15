<?php

namespace Tests\Feature\Listenrs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Blog;
use App\Events\Subscribed;
use App\Listeners\SendSubscribedNotification;
use App\Notifications\Subscribed as SubscribedNotification;

class SendSubscribedNotificationTest extends TestCase
{
    use RefreshDatabase;

    // 블로그 구독 이벤트에 관한 검증
    public function testSubscribedNotificationSentToBlogOwner()
    {
        Notification::fake();

        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $event = new Subscribed($user, $blog);

        $listener = new SendSubscribedNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            $event->blog->user,
            SubscribedNotification::class
        );
    }
}
