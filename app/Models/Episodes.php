<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EpisodeLike;

class Episodes extends Model
{
    use EpisodeLikable;
	use HasFactory;

    protected $guarded = [];

	public function series(){
    	return $this->belongsTo('App\Models\Items');
    }

    public function users(){
    	return $this->belongsToMany('App\Models\User','users_items','items_id','users_id');
    }

    public function comments(){
    	return $this->belongsToMany('App\Models\Comments','comments_episodes','episodes_id','comments_id');
    }

    /**
     * Get all of the likes for the Episode.
     */
    public function likes()
    {
        return $this->hasMany(EpisodeLike::class, 'episodes_id');
    }

}
