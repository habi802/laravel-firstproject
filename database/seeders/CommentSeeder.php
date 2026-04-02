<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::all()->each(function (Post $post) {
            $factory = Comment::factory()
                              ->for($post, 'commentable')
                              ->state(function (array $attributes) {
                                  return [
                                      'user_id' => User::pluck('id')->random()
                                  ];
                              });

            $factory->has($factory->count(2), 'replies')->create();
            $factory->create();
        });
    }
}
