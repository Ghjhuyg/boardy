<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Тест',
            'email'    => 'test@boardy.local',
            'password' => bcrypt('password'),
        ]);

        $users = User::factory()->count(4)->create();

        $allUsers = User::all();
        Post::factory()->count(10)->create([
            'author_id' => fn() => $allUsers->random()->id,
        ]);

        $posts = Post::all();
        Comment::factory()->count(25)->create([
            'post_id'   => fn() => $posts->random()->id,
            'author_id' => fn() => $allUsers->random()->id,
        ]);
    }
}
