<?php

namespace App\Models;

trait EpisodeLikable{

    public function isEpisodeLikedBy(User $user)
    {
        if (!$user->relationLoaded('episode_likes')) {
            $user->load('episode_likes');
        }
        return (bool) $user->episode_likes
            ->where('episodes_id', $this->id)
            ->where('liked', true)
            ->count();
    }

    public function isEpisodeDislikedBy(User $user)
    {
        if (!$user->relationLoaded('episode_likes')) {
            $user->load('episode_likes');
        }
        return (bool) $user->episode_likes
            ->where('episodes_id', $this->id)
            ->where('liked', false)
            ->count();
    }

    public function episode_likes()
    {
        return $this->hasMany(EpisodeLike::class, 'episodes_id');
    }

    public function episode_dislike($user = null)
    {
        return $this->episode_like($user, false);
    }

    public function episode_like($user = null, $liked = true)
    {
        $this->episode_likes()->updateOrCreate(
            [
                'user_id' => $user ? $user->id : auth()->id(),
            ],
            [
                'liked' => $liked,
            ]
        );
    }
}
