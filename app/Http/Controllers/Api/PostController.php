<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Models\Blog;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Blog $blog)
    {
        // return $blog->posts()
        //             ->latest()
        //             ->paginate(5);

        $posts = $blog->posts()
                      ->latest()
                      ->get();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $this->postService->store($request->validated(), $blog);

        //return response()->json($post, 201);
        return (new PostResource($post))
             ->response()
             ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //return $post;
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($request->validated(), $post);

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->destroy($post);

        return response()->noContent();
    }
}
