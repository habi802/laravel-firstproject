<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Advertisement;
use App\Models\User;
use App\Models\Post;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {--Q|queue=default : The names of the queues to send emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send advertisement emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queue = $this->option('queue');

        $this->sendEmails($queue);

        return 0;
    }

    private function sendEmails(string $queue)
    {
        $posts = $this->posts();

        $this->users()->each(fn (User $user) => Mail::to($user)->send(
            (new Advertisement($posts))->onQueue($queue)
        ));
    }

    private function users()
    {
        return User::all();
        // return User::verified()->get();
    }

    private function posts()
    {
        return Post::latest()->limit(5)->get();
    }
}
