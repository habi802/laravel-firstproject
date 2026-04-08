<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Post;
use App\Models\Attachment;

class AttachmentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 파일 생성에 관한 검증
    public function testCreateAttachmentForPost()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->create();

        $this->actingAs($post->blog->user)
             ->post(route('posts.attachments.store', $post), [
                 'attachments' => [$attachment]
             ])
             ->assertSuccessful();
        
        $this->assertCount(1, $post->attachments);

        $this->assertDatabaseHas('attachments', [
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName('attachments'),
        ]);

        Storage::disk('public')->assertExists(
            $attachment->hashName('attachments')
        );
    }

    // 파일 삭제에 관한 검증
    public function testDeleteAttachmentFromPost()
    {
        Storage::fake('public');

        $attachment = UploadedFile::fake()->image('file.jpg');

        $post = Post::factory()->has(
            Attachment::factory()->state([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName('attachments'),
            ])
        )->create();

        foreach ($post->attachments as $attachment) {
            $this->actingAs($post->blog->user)
                 ->delete(route('attachments.destroy', $attachment))
                 ->assertRedirect();

            $this->assertDatabaseMissing('attachments', [
                'id' => $attachment->id,
            ]);
        }
    }
}
