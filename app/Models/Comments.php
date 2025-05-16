<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function items(){
    	return $this->belongsToMany('App\Models\Items');
    }

    public function user(){
    	return $this->belongsToMany('App\Models\User');
    }

    public function episodes(){
    	return $this->belongsToMany('App\Models\Episodes');
    }

    public function comments(){
        return $this->hasMany(Comments::class,'users_id','id');
    }

}
