<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profile_path',
        'tmdb_id',
        'gender',
        // Gelecekte eklenebilecek diğer alanlar:
        // 'biography',
        // 'birthday',
        // 'deathday',
        // 'place_of_birth',
    ];

    public function actors(){
    	return $this->belongsToMany('App\Models\Persons','actor_items','items_id','persons_id');
    }

    public function directors(){
    	return $this->belongsToMany('App\Models\Persons','director_items','items_id','persons_id');
    }

    public function writers(){
    	return $this->belongsToMany('App\Models\Persons','writer_items','items_id','persons_id');
    }

    public function items(){
    	return $this->belongsToMany('App\Models\Items','persons_items','persons_id','items_id');
    }

}
