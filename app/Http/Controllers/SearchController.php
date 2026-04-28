<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchRequest $request)
    {
        $query = $request->input('query');

        return view('search', [
            'posts' => Post::search($query)->get(),
            'query' => $query,
        ]);
    }
}
