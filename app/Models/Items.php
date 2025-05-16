<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use Likable;
    use HasFactory;

    protected $guarded = [];

    /**
     * Bu item'ın (dizi/film) ait olduğu platform.
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function episodes(){
        return $this->hasMany('App\Models\Episodes');
    }

    public function genres(){
        return $this->belongsToMany('App\Models\Genres');
    }

    public function collections(){
        return $this->belongsToMany('App\Models\Collections');
    }

    public function actors(){
    	return $this->belongsToMany('App\Models\Persons','actor_items','items_id','persons_id');
    }

    public function directors(){
    	return $this->belongsToMany('App\Models\Persons','director_items','items_id','persons_id');
    }

    public function writers(){
    	return $this->belongsToMany('App\Models\Persons','writer_items','items_id','persons_id');
    }

    public function persons(){
        return $this->belongsToMany('App\Models\Persons','persons_items','items_id','persons_id');
    }

    public function users(){
    	return $this->belongsToMany('App\Models\User','users_items','items_id','users_id');
    }

    public function qualities(){
        return $this->belongsToMany('App\Models\Qualities','qualities_items','items_id','qualities_id');
    }

    public function countries(){
        return $this->belongsToMany('App\Models\Countries','countries_items','items_id','countries_id');
    }

    public function keywords(){
        return $this->belongsToMany('App\Models\Keywords','keywords_items','items_id','keywords_id');
    }

    public function years(){
        return $this->belongsToMany('App\Models\Years','years_items','items_id','years_id');
    }

    public function comments(){
    	return $this->belongsToMany('App\Models\Comments','comments_items','items_id','comments_id');
    }

    public function watchlists(){
        return $this->hasMany('App\Models\Watchlists');
    }

    public function remove_watchlist($user = null){
        //return $this->add_watchlist($user, false);
        $this->watchlists()->delete();

    }

    public function add_watchlist($user = null, $watchlisted = true){
        $this->watchlists()->updateOrCreate(
            [
                'user_id' => $user ? $user->id : auth()->id(),
            ],
            [
                'watchlisted' => $watchlisted,
            ]
        );
    }

    public function isWatchlistedBy(User $user){
        return (bool) $this->watchlists()->where('user_id', $user->id)->exists();
    }


}
