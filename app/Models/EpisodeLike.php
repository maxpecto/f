<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeLike extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function series(){
        return $this->belongsTo('App\Models\Items');
    }

    public function episodes(){
        return $this->belongsTo('App\Models\Episodes');
    }
}
