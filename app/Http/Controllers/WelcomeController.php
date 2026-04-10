<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class WelcomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->subscriptions()->exists()) {
            $subscriptions = $user->subscriptions()
                                  ->with('posts', fn ($query) => $query->limit(20))
                                  ->get();
                                
            $posts = $subscriptions->feed();
        } else {
            $posts = Post::latest()->limit(20)->get();
        }

        return view('welcome', [
            'posts' => $posts->paginate(5, $request->page ?? 1),
        ]);
    }
}
