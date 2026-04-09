<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Attachment;

class AttachmentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 글과 연결되지 않은 Attachment가 아티즌 콘솔에서 model:prune을 호출한 뒤 파일이 함께 제거되었는지 검증
    public function testPruningAssociatedUploadedFile()
    {
        $storage = Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $file->store('/', 'public');

        $attachment = Attachment::factory()->state([
            'original_name' => $file->getClientOriginalName(),
            'name' => $file->hashName(),
        ])->create();

        $storage->assertExists($attachment->name);

        $this->artisan('model:prune', [
            '--model' => [Attachment::class],
        ])->assertSuccessful();

        $this->assertDatabaseMissing('attachments', [
            'id' => $attachment->id,
        ]);

        $storage->assertMissing($attachment->name);
    }
}
