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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class ItemsComposer{

    // Önbellek süreleri (dakika cinsinden)
    const CACHE_TIME_SHORT = 5;
    const CACHE_TIME_MEDIUM = 15;
    const CACHE_TIME_LONG = 60;
    const CACHE_TIME_VERY_LONG = 1440; // 1 gün

    private function getRandomItems($count, $cacheKey, $relations = ['genres'])
    {
        return Cache::remember($cacheKey, self::CACHE_TIME_MEDIUM, function () use ($count, $relations) {
            $itemIds = Items::where('visible', 1)->pluck('id')->toArray();
            if (empty($itemIds)) {
                return collect();
            }
            $randomIds = Arr::random($itemIds, min($count, count($itemIds)));
            return Items::with($relations)->whereIn('id', $randomIds)->get();
        });
    }

    public function compose(View $view)
    {
        $latestmovies = Cache::remember('latest_movies', self::CACHE_TIME_SHORT, function () {
            return Items::with(['genres', 'platform'])->orderBy('id','DESC')->where('visible', 1)->where('type', 'movies')->take(10)->get();
        });

        $latestseries = Cache::remember('latest_series', self::CACHE_TIME_SHORT, function () {
            return Items::with(['genres', 'platform'])->orderBy('id','DESC')->where('visible', 1)->where('type', 'series')->take(10)->get();
        });

        $latestepisodes = Cache::remember('latest_episodes', self::CACHE_TIME_SHORT, function () {
            return Episodes::with('series.genres')->orderBy('id','DESC')->take(10)->get(); // series.genres ile iç içe ilişki
        });

        $hometrendings = Cache::remember('home_trendings', self::CACHE_TIME_MEDIUM, function () {
            return Items::with(['genres', 'platform'])->where('visible', 1)->orderBy('views','DESC')->take(10)->get();
        });

        $homerecommendeds = Cache::remember('home_recommendeds', self::CACHE_TIME_MEDIUM, function () {
            return Items::with(['genres', 'platform'])->orderBy('id','DESC')->where('visible', 1)->where('recommended',1)->take(10)->get();
        });

        $featureItems = Cache::remember('feature_items', self::CACHE_TIME_MEDIUM, function () {
            return Items::with(['genres', 'platform'])->where('feature', 1)->orderBy('views','DESC')->take(10)->get();
        });

        $homecollections = Cache::remember('home_collections', self::CACHE_TIME_LONG, function () {
            return Collections::where('visible',1)->orderBy('id', 'DESC')->take(16)->get();
        });

        $persons = Cache::remember('popular_persons', self::CACHE_TIME_LONG, function () {
            return Persons::orderBy('popularity', 'DESC')->take(10)->get();
        });

        $pages_lists = Cache::rememberForever('pages_lists', function () {
            return Pages::where('visible', 1)->get();
        });

        // Optimize edilmiş rastgele item çekme
        $join1 = $this->getRandomItems(2, 'join1_items');
        $join2 = $this->getRandomItems(3, 'join2_items');
        $join3 = $this->getRandomItems(2, 'join3_items');
        $randoms = $this->getRandomItems(5, 'randoms_items');

        // Bu trendings ve recommendeds ana sayfadaki hometrendings/homerecommendeds ile aynı olabilir, eğer öyleyse birleştirilebilir.
        // Şimdilik ayrı önbellek anahtarlarıyla bırakıyorum.
        $trendings = Cache::remember('sidebar_trendings', self::CACHE_TIME_MEDIUM, function () {
            return Items::with(['genres', 'platform'])->where('visible', 1)->orderBy('views','DESC')->take(5)->get();
        });
        $recommendeds = Cache::remember('sidebar_recommendeds', self::CACHE_TIME_MEDIUM, function () {
            return Items::with(['genres', 'platform'])->orderBy('id','DESC')->where('visible', 1)->where('recommended',1)->take(5)->get();
        });

        $genres = Cache::remember('all_genres_with_items', self::CACHE_TIME_VERY_LONG, function () {
            return Genres::orderBy('name', 'ASC')->where('visible',1)->whereHas('items')->get(); // whereHas daha iyi olabilir
        });
        $homequality = Cache::remember('all_qualities_with_items', self::CACHE_TIME_VERY_LONG, function () {
            return Qualities::orderBy('name', 'ASC')->whereHas('items')->get();
        });
        $homecountries = Cache::remember('all_countries_with_items', self::CACHE_TIME_VERY_LONG, function () {
            return Countries::orderBy('name', 'ASC')->whereHas('items')->get();
        });
        $years = Cache::remember('all_years_with_items', self::CACHE_TIME_VERY_LONG, function () {
            return Years::orderBy('name', 'ASC')->whereHas('items')->get();
        });

        $view->with(compact(
            'latestmovies', 'latestseries', 'latestepisodes', 'hometrendings', 'homerecommendeds',
            'featureItems', 'homecollections', 'persons', 'pages_lists', 'join1', 'join2', 'join3',
            'randoms', 'trendings', 'recommendeds', 'genres', 'homequality', 'homecountries', 'years'
        ));
    }

}
