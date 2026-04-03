<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 댓글 등록에 관한 검증
    public function testCreateCommentForPost()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $data = [
            'content' => $this->faker->text,
        ];

        $this->actingAs($user)
             ->post(route('posts.comments.store', $post), $data)
             ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            ...$data,
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);
    }

    // 대댓글 등록에 관한 검증
    public function testCreateChildCommentForComment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $data = [
            'content' => $this->faker->text,
        ];

        $this->actingAs($user)
             ->post(route('posts.comments.store', $comment->commentable), [
                ...$data,
                'parent_id' => $comment->id,
             ])
             ->assertRedirect();

        $this->assertCount(1, $comment->replies);

        $this->assertDatabaseHas('comments', [
            ...$data,
            'parent_id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }

    // 댓글 수정에 관한 검증
    public function testUpdateComment()
    {
        $comment = Comment::factory()->create();

        $data = [
            'content' => $this->faker->text,
        ];

        $this->actingAs($comment->user)
             ->put(route('comments.update', $comment), $data)
             ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            ...$data,
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }

    // 댓글 삭제에 관한 검증
    public function testDeleteComment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)
             ->delete(route('comments.destroy', $comment))
             ->assertRedirect();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'commentable_type' => Post::class,
            'commentable_id' => $comment->commentable->id,
        ]);
    }
}
