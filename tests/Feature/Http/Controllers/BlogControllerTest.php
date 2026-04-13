<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 블로그 조회 뷰 반환에 대한 검증
    public function testReturnsIndexViewForListOfBlog()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(route('blogs.index'))
             ->assertViewIs('blogs.index');
    }

    // 블로그 등록 뷰 반환에 대한 검증
    public function testReturnsCreateViewForBlog()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(route('blogs.create'))
             ->assertViewIs('blogs.create');
    }

    // 블로그 등록에 대한 검증
    public function testCreateBlog()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->unique()->words(3, true)
        ];

        $this->actingAs($user)
             ->post(route('blogs.store'), $data)
             ->assertRedirect();

        $this->assertDatabaseHas('blogs', $data);
    }

    // 블로그 상세 조회 뷰 반환에 대한 검증
    public function testReturnsShowViewForBlog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $this->actingAs($user)
             ->get(route('blogs.show', $blog))
             ->assertOk()
             ->assertViewIs('blogs.show');
    }

    // 블로그 수정 뷰 반환에 대한 검증
    public function testReturnsEditViewForBlog()
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
             ->get(route('blogs.edit', $blog))
             ->assertOk()
             ->assertViewIs('blogs.edit');
    }

    // 블로그 수정에 대한 검증
    public function testUpdateBlog()
    {
        $blog = Blog::factory()->create();

        $data = [
            'name' => $this->faker->userName,
            'display_name' => $this->faker->unique()->words(3, true)
        ];

        $this->actingAs($blog->user)
             ->put(route('blogs.update', $blog), $data)
             ->assertRedirect();

        $this->assertDatabaseHas('blogs', $data);
    }

    // 블로그 삭제에 대한 검증
    public function testDeleteBlog()
    {
        $blog = Blog::factory()->create();

        $this->actingAs($blog->user)
             ->delete(route('blogs.destroy', $blog))
             ->assertRedirect();

        $this->assertDatabaseMissing('blogs', [
            'name' => $blog->name
        ]);
    }
}
