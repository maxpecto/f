<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actors extends Model
{
    use HasFactory;

    public function persons(){
    	return $this->belongsToMany('App\Models\Persons');
    }

}
