<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            // display_name을 기준으로, URL에 쓰기 좋게 변환해 줌
            // display_name이 Hello World라면, name은 hello-world가 됨
            'name' => function (array $attributes) {
                return Str::slug($attributes['display_name']);
            },
            'display_name' => fake()->unique()->words(3, true),
        ];
    }
}
