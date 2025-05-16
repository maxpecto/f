<?php

use App\Models\Like;
use App\Models\EpisodeLike;
use App\Models\User;

function getUsername($uerId) {
    return User::where('id', $uerId)->first();
}

function current_user(){
    return auth()->user();
}

function totalLike($items){
    return Like::where('items_id', $items)->where('liked', true)->count();
}
function totalDislike($items){
    return Like::where('items_id', $items)->where('liked', false)->count();
}

function totalEpisodeLike($items){
    return EpisodeLike::where('episodes_id', $items)->where('liked', true)->count();
}
function totalEpisodeDislike($items){
    return EpisodeLike::where('episodes_id', $items)->where('liked', false)->count();
}

function comp_numb($input){
    $input = number_format($input);
    $input_count = substr_count($input, ',');
    $arr = array(1=>'K','M','B','T');
    if(isset($arr[(int)$input_count]))
        return substr($input,0,(-1*$input_count)*4).$arr[(int)$input_count];
    else return $input;
}
