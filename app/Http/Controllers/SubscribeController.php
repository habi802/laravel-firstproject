<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubscribeRequest;
use App\Models\Blog;
use App\Events\Subscribed;
use App\Http\Requests\UnsubscribeRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribed as SubscribedMailable;

class SubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request)
    {
        $user = $request->user();
        $blog = Blog::find($request->blog_id);
        
        $user->subscriptions()->attach($blog->id);

        event(new Subscribed($user, $blog));

        Mail::to($blog->user)
            //->send(new SubscribedMailable($user, $blog));
            // ->queue(
            //     (new SubscribedMailable($user, $blog))->onQueue('emails')
            // );
            ->send(
                (new SubscribeMailable($user, $blog))->onQueue('emails')
            );

        return back();
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        $user = $request->user();
        $blog = Blog::find($request->blog_id);
        
        $user->subscriptions()->detach($blog->id);

        return back();
    }
}
