<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function create(User $user): bool
    {
        return true; // любой авторизованный может создавать
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->author_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->author_id;
    }
}
