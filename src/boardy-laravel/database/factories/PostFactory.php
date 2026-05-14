<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'     => fake()->sentence(4),
            'body'      => fake()->paragraph(),
            'author_id' => User::factory(),
            'created_at'=> now(),
            'updated_at'=> now(),
        ];
    }
}
