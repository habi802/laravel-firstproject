<?php

namespace Tests\Feature\Notifications;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Notifications\Published;
use Illuminate\Notifications\Messages\MailMessage;

class PublishedTest extends TestCase
{
    use RefreshDatabase;

    // 글 작성 시 메일 전송 및 메일 내용에 관한 검증
    public function testToMailContainsExpectedSubjectAndContent()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $notification = new Published($post);

        $mailMessage = $notification->toMail($user);
        $this->assertInstanceOf(MailMessage::class, $mailMessage);

        $this->assertStringContainsString(
            $notification->post->blog->display_name,
            $mailMessage->subject
        );
        $this->assertStringContainsString(
            $notification->post->title,
            $mailMessage->subject
        );
        $this->assertContains(
            substr($notification->post->content, 0, 200),
            $mailMessage->introLines
        );
        $this->assertStringContainsString(
            route('posts.show', $notification->post),
            $mailMessage->actionUrl
        );
    }
}
