<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Years extends Model
{
    use HasFactory;

    public function items(){
    	return $this->belongsToMany('App\Models\Items','years_items','items_id','years_id');
    }

    protected $fillable = ['name'];
}
