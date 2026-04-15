<?php

namespace Tests\Feature\Listeners;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Post;
use App\Events\Published;
use App\Listeners\SendPublishedNotification;
use App\Notifications\Published as PublishedNotification;

class SendPublishedNotificationTest extends TestCase
{
    use RefreshDatabase;

    // 글 작성 시 구독자 알림에 관한 검증
    public function testPublishedNotificationSentToSubscribers()
    {
        Notification::fake();

        $subscribers = User::factory(10)->create();
        $post = Post::factory()->create();

        $event = new Published($subscribers, $post);

        $listener = new SendPublishedNotification();
        $listener->handle($event);

        Notification::assertSentTo(
            $event->subscribers,
            PublishedNotification::class
        );
    }
}
