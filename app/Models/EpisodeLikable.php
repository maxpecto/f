<?php

namespace App\Models;

trait EpisodeLikable{

    public function isEpisodeLikedBy(User $user)
    {
        return (bool) $user->epsiode_likes
            ->where('episodes_id', $this->id)
            ->where('liked', true)
            ->count();
    }

    public function isEpisodeDislikedBy(User $user)
    {
        return (bool) $user->epsiode_likes
            ->where('episodes_id', $this->id)
            ->where('liked', false)
            ->count();
    }

    public function epsiode_likes()
    {
        return $this->hasMany(EpisodeLike::class);
    }

    public function episode_dislike($user = null)
    {
        return $this->episode_like($user, false);
    }

    public function episode_like($user = null, $liked = true)
    {
        $this->epsiode_likes()->updateOrCreate(
            [
                'user_id' => $user ? $user->id : auth()->id(),
            ],
            [
                'liked' => $liked,
            ]
        );
    }
}
