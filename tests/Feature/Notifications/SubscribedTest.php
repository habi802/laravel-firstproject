<?php

namespace Tests\Feature\Notifications;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;
use App\Notifications\Subscribed;
use App\Mail\Subscribed as SubscribedMailable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SubscribedTest extends TestCase
{
    use RefreshDatabase;

    public function testToMailReturnsSubscribedMailable()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $notification = new Subscribed($user, $blog);

        $this->assertInstanceOf(SubscribedMailable::class, $notification->toMail($user));
        $this->assertInstanceOf(SubscribedMailable::class, $notification->toMail(new AnonymousNotifiable()));
    }
}
