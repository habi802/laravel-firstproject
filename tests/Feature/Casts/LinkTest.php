<?php

namespace Tests\Feature\Casts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Castables\Link as LinkCastable;
use Exception;

class LinkTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 파일의 외부 링크 접근자에 관한 검증
    public function testLinkAccessorWithExternalPath()
    {
        $attachment = Attachment::factory()->state([
            'name' => $this->faker->imageUrl(),
        ])->create();

        $this->assertEquals($attachment->name, $attachment->link->path);
    }

    // 파일의 내부 링크 접근자에 관한 검증
    public function testLinkAccessorWithFilePath()
    {
        $attachment = UploadedFile::fake()->image('avatar.jpg');

        $attachment = Attachment::factory()->state([
            'original_name' => $attachment->getClientOriginalName(),
            'name' => $attachment->hashName(),
        ])->create();

        $this->assertEquals(
            Storage::disk('public')->url($attachment->name),
            $attachment->link->path
        );
    }

    // 파일 변이자 LinkCastable 을 설정했을 때에 관한 검증
    public function testLinkMutatorSetsCastable()
    {
        $attachment = Attachment::factory()->create();

        $linkCastable = new LinkCastable(
            $this->faker->imageUrl()
        );

        $attachment->link = $linkCastable;

        $this->assertEquals($linkCastable->path, $attachment->link->path);
    }

    // 파일 변이자 LinkCastable 을 설정하지 않았을 때에 관한 검증
    public function testLinkMutatorThrowsExceptionOnInvalidValue()
    {
        $attachment = Attachment::factory()->create();

        $this->expectException(Exception::class);

        $attachment->link = null;
    }
}
