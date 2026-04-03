<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\RequirePassword;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    // 대시보드 댓글 뷰에 관한 검증
    public function testReturnsCommentsDashboardViewForListOfComment()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->withoutMiddleware(RequirePassword::class)
             ->get(route('dashboard.comments'))
             ->assertOk()
             ->assertViewIs('dashboard.comments');
    }
}
