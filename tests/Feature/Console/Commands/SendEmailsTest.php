<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\Advertisement;

class SendEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function testMailSendCommandQueuesAdvertisementMailable()
    {
        Mail::fake();

        User::factory(10)->create();

        $this->artisan('mail:send --queue=emails')
             ->assertSuccessful();

        Mail::assertQueued(Advertisement::class);
    }
}
