<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualities extends Model
{
    use HasFactory;


    public function items(){
    	return $this->belongsToMany('App\Models\Items','qualities_items','items_id','qualities_id');
    }

    protected $fillable = ['name'];
}
