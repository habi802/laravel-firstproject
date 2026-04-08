<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Blog;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Blog $blog)
    {
        return view('blogs.posts.index', [
            'posts' => $blog->posts()->latest()->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Blog $blog)
    {
        return view('blogs.posts.create', [
            'blog' => $blog
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $blog->posts()->create(
            $request->only(['title', 'content'])
        );

        $this->attachments($request, $post);

        return to_route('posts.show', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Model::loadCount()를 사용하여 뷰에서 $post->comments_count를 사용하여 댓글의 개수를 출력할 수 있음
        // QueriesRelationships::doesntHave('parent')를 사용하여 부모가 설정되지 않은, 최상위 댓글만을 가져온 뒤,
        // with()를 사용하여 댓글 작성자, 대댓글, 대댓글 작성자까지 가져옴
        return view('blogs.posts.show', [
            'post' => $post->loadCount('comments'),
            'comments' => $post->comments()
                               ->doesntHave('parent')
                               ->with(['user', 'replies.user'])
                               ->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('blogs.posts.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update(
            $request->only(['title', 'content'])
        );

        $this->attachments($request, $post);

        return to_route('posts.show', $post);
    }

    private function attachments(Request $request, $post)
    {
        if ($request->hasFile('attachments')) {
            app(AttachmentController::class)->store($request, $post);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('blogs.posts.index', $post->blog);
    }
}
