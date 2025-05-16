<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Persons;
use App\Models\Genres;
use App\Models\Qualities;
use App\Models\Countries;
use App\Models\Keywords;
use App\Models\Items;

class DataController extends BackendController
{
    //get_items List
    public function get_items(){
        $items = Items::where('visible',1)->orderBy('title', 'ASC')->get();
        return $items;
    }

    //get_series List
    public function get_series(){
        $series = Items::where('type','series')->orderBy('title', 'ASC')->get();
        return $series;
    }


    //get_keywords List
    public function get_keywords(){
        $keywords = Keywords::orderBy('name', 'ASC')->get();
        return $keywords;
    }

    //get_quality List
    public function get_quality(){
        $qualities = Qualities::orderBy('name', 'ASC')->get();
        return $qualities;
    }

    //get_countries List
    public function get_countries(){
        $countries = Countries::orderBy('name', 'ASC')->get();
        return $countries;
    }

    //get_genres List
    public function get_genres(){
        $genres = Genres::orderBy('name', 'ASC')->where('visible', 1)->get();
        return $genres;
    }

    //get_actors List
    public function get_actors(){
        $persons = Persons::orderBy('name', 'ASC')->where('known_for_department', 'Acting')->get();
        return $persons;
    }

    //get_directing List
    public function get_directing(){
        $persons = Persons::orderBy('name', 'ASC')->where('known_for_department', 'Directing')->get();
        return $persons;
    }

    //get_writing List
    public function get_writing(){
        $persons = Persons::orderBy('name', 'ASC')->where('known_for_department', 'Writing')->get();
        return $persons;
    }
}
