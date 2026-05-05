<?php

namespace App\Services;

use App\Events\Published;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class PostService
{
    public function __construct(private readonly AttachmentService $attachmentService)
    {

    }

    public function store(array $data, Blog $blog)
    {
        $post = $blog->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data, 'attachments')) {
            $this->attachments($data['attachments'], $post);
        }

        if ($blog->subscribers()->exists()) {
            event(new Published($blog->subscribers, $post));
        }

        return $post;
    }

    public function update(array $data, Post $post)
    {
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (Arr::exists($data, 'attachments')) {
            $this->attachments($data['attachments'], $post);
        }
    }

    public function destroy(Post $post)
    {
        $post->delete();
    }

    private function attachments(array $attachments, Post $post)
    {
        $data = [
            'attachments' => $attachments,
        ];

        $this->attachmentService->store($data, $post);
    }
}