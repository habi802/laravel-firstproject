<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubscribeRequest;
use App\Models\Blog;
use App\Events\Subscribed;
use App\Http\Requests\UnsubscribeRequest;

class SubscribeController extends Controller
{
    public function subscribe(SubscribeRequest $request)
    {
        $user = $request->user();
        $blog = Blog::find($request->blog_id);
        
        $user->subscriptions()->attach($blog->id);

        event(new Subscribed($user, $blog));

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
