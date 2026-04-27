<?php

namespace Tests\Feature\Mail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Mail\Advertisement;

class AdvertisementTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplayListOfPostTitles()
    {
        $posts = Post::factory(5)->create();

        $mailable = new Advertisement($posts);

        $mailable->assertHasSubject(
            '[라라벨] 라라벨 커뮤니티의 최신글 살펴보기'
        );

        $mailable->assertSeeInOrderInHtml(
            $posts->pluck('title')->toArray()
        );
    }
}
