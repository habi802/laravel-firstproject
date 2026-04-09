<?php

namespace Tests\Feature\Observers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\Attachment;
use App\Observers\AttachmentObserver;

class AttachmentObserverTest extends TestCase
{
    use RefreshDatabase;

    // 파일 삭제 시 옵저버가 제대로 실행되었는지 검증
    public function testDeletingUploadedFileOnAttachmentDeletion()
    {
        $storage = Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $file->store('/', 'public');

        $attachment = Attachment::factory()->state([
            'original_name' => $file->getClientOriginalName(),
            'name' => $file->hashName(),
        ])->create();

        $observer = new AttachmentObserver();

        $observer->deleted($attachment);

        $storage->assertMissing($attachment->name);
    }
}
