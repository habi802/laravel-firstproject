<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Support\Str;

class KernelTest extends TestCase
{
    public function testMailSendCommandSchedule()
    {
        Event::fake();

        $data = now()
                ->startOfWeek()
                ->weekday(Schedule::MONDAY)
                ->hour(8);

        $this->travelTo($data);
        $this->artisan('schedule:run');

        Event::assertDispatched(
            ScheduledTaskFinished::class,
            function (ScheduledTaskFinished $event) {
                return Str::contains($event->task->command, 'mail:send --queue=emails');
            }
        );
    }
}
