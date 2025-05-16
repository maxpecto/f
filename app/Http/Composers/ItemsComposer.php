<?php

namespace App\Http\Composers;

use Illuminate\View\View;
use App\Models\Items;

use App\Models\Episodes;
use App\Models\Genres;
use App\Models\Years;
use App\Models\Pages;
use App\Models\Collections;
use App\Models\Persons;
use App\Models\Qualities;
use App\Models\Countries;

use Illuminate\Support\Facades\DB;

class ItemsComposer{

    public function compose(View $view)
    {
        $latestmovies = Items::orderBy('id','DESC')->where('visible', 1)->where('type', 'movies')->take(10)->get();
        $latestseries = Items::orderBy('id','DESC')->where('visible', 1)->where('type', 'series')->take(10)->get();
        $latestepisodes = Episodes::with('series')->orderBy('id','DESC')->take(10)->get();

        $hometrendings = Items::where('visible', 1)->orderBy('views','DESC')->take(10)->get();
        $homerecommendeds = Items::orderBy('id','DESC')->where('visible', 1)->where('recommended',1)->take(10)->get();

        $featureItems = Items::where('feature', 1)->orderBy('views','DESC')->take(10)->get();
        $homecollections = Collections::where('visible',1)->orderBy('id', 'DESC')->take(16)->get();

        $persons = Persons::orderBy('popularity', 'DESC')->take(10)->get();

        $pages_lists = Pages::where('visible', 1)->get();

        $join1 = Items::inRandomOrder()->where('visible', 1)->take(2)->get();
        $join2 = Items::inRandomOrder()->where('visible', 1)->take(3)->get();
        $join3 = Items::inRandomOrder()->where('visible', 1)->take(2)->get();

        $randoms = Items::inRandomOrder()->where('visible', 1)->take(5)->get();
        $trendings = Items::where('visible', 1)->orderBy('views','DESC')->take(5)->get();
        $recommendeds = Items::orderBy('id','DESC')->where('visible', 1)->where('recommended',1)->take(5)->get();

        $genres = Genres::orderBy('name', 'ASC')->where('visible',1)->has('items')->get();
        $homequality = Qualities::orderBy('name', 'ASC')->has('items')->get();
        $homecountries = Countries::orderBy('name', 'ASC')->has('items')->get();
        $years = Years::orderBy('name', 'ASC')->has('items')->get();

        $view->with('latestmovies', $latestmovies);
        $view->with('latestseries', $latestseries);
        $view->with('latestepisodes', $latestepisodes);

        $view->with('trendings', $trendings);
        $view->with('recommendeds', $recommendeds);
        $view->with('randoms', $randoms);
        $view->with('featureItems', $featureItems);
        $view->with('collections', $homecollections);
        $view->with('persons', $persons);
        $view->with('hometrendings', $hometrendings);
        $view->with('homerecommendeds', $homerecommendeds);

        $view->with('genres', $genres);
        $view->with('years', $years);
        $view->with('homequality', $homequality);
        $view->with('homecountries', $homecountries);

        $view->with('join1', $join1);
        $view->with('join2', $join2);
        $view->with('join3', $join3);

        $view->with('pages_lists', $pages_lists);
    }

}
