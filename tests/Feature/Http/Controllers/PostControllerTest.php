<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use App\Events\Published;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 글 목록 뷰에 대한 검증
    public function testReturnsIndexViewForListOfPost()
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
             ->get(route('blogs.posts.index', $blog))
             ->assertOk()
             ->assertViewIs('blogs.posts.index');
    }

    // 글쓰기 뷰에 관한 검증
    public function testReturnsCreateViewForPost()
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
             ->get(route('blogs.posts.create', $blog))
             ->assertOk()
             ->assertViewIs('blogs.posts.create');
    }

    // 글쓰기에 관한 검증
    public function testCreatePostForBlog()
    {
        Event::fake();

        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $blog = Blog::factory()->hasSubscribers()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        $this->actingAs($blog->user)
             ->post(route('blogs.posts.store', $blog), [
                ...$data,
                'attachments' => [$attachment],
             ])
             ->assertRedirect();

        $this->assertCount(1, $blog->posts);
        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );

        Event::assertDispatched(Published::class);
    }

    // 글 조회에 관한 검증
    public function testReturnsShowViewForPost()
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
             ->get(route('posts.show', $post))
             ->assertOk()
             ->assertViewIs('blogs.posts.show');
    }

    // 글 수정 뷰에 관한 검증
    public function testReturnsEditViewForPost()
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
             ->get(route('posts.edit', $post))
             ->assertViewIs('blogs.posts.edit');
    }

    // 글 수정에 관한 검증
    public function testUpdatePost()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->create();

        $data = [
            'title' => $this->faker->text(50),
            'content' => $this->faker->text,
        ];

        $this->actingAs($post->blog->user)
             ->put(route('posts.update', $post), [
                ...$data,
                'attachments' => [$attachment],
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('posts', $data);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );
    }

    // 글 삭제에 관한 검증
    public function testDeletePost()
    {
        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
             ->delete(route('posts.destroy', $post))
             ->assertRedirect();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
