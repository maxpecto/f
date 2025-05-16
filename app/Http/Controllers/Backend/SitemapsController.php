<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Crawler\Profile;

use App\Models\Items;
use App\Models\Episodes;
use App\Models\Pages;
use App\Models\Genres;

class SitemapsController extends BackendController
{
	//Display Sitemaps List
    public function sitemaps_lists(Request $request){
        return view('backend.sitemaps.sitemaps');
    }
    //CODE Sitemaps Movies
    public function sitemapsmovie(){
        $sitemap = Sitemap::create();
        Items::where('type','movies')->get()->each(function (Items $movieitem) use ($sitemap) {
            $sitemap->add(Url::create("/movie/{$movieitem->slug}"));
        });
        $sitemap->writeToFile(public_path('movies-sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Movies Sitemaps Generated');
    }
    //CODE Sitemaps Series
    public function sitemapsseries(){
        $sitemap = Sitemap::create();
        Items::where('type','series')->get()->each(function (Items $seriesitem) use ($sitemap) {
            $sitemap->add(Url::create("/series/{$seriesitem->slug}"));
        });
        $sitemap->writeToFile(public_path('series-sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Series Sitemaps Generated');
    }
    //CODE Sitemaps Episodes
    public function sitemapsepisodes(){
        $sitemap = Sitemap::create();
        Episodes::all()->each(function (Episodes $episodesitem) use ($sitemap) {
            $sitemap->add(Url::create("{$episodesitem->series->id}/{$episodesitem->series->slug}/season-{$episodesitem->season_id}/episode-{$episodesitem->episode_id}"));
        });
        $sitemap->writeToFile(public_path('episodes-sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Episodes Sitemaps Generated');
    }
    //CODE Sitemaps Pages
    public function sitemapspage(){
        $sitemap = Sitemap::create();
        Pages::all()->each(function (Pages $pageitem) use ($sitemap) {
            $sitemap->add(Url::create("/page/{$pageitem->slug}"));
        });
        $sitemap->writeToFile(public_path('pages-sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Pages Sitemaps Generated');
    }
    //CODE Sitemaps Genres
    public function sitemapsgenre(){
        $sitemap = Sitemap::create();
        Genres::all()->each(function (Genres $genreitem) use ($sitemap) {
            $genresslug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $genreitem->name)));
            $sitemap->add(Url::create("/genres/{$genresslug}"));
        });
        $sitemap->writeToFile(public_path('genres-sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Genres Sitemaps Generated');
    }
    //CODE Sitemaps Index
    public function sitemapsindex(){
        $moviesitemap = Sitemap::create();
        Items::where('type','movies')->get()->each(function (Items $movieitem) use ($moviesitemap) {
            $moviesitemap->add(Url::create("/movie/{$movieitem->slug}"));
        });
        $moviesitemap->writeToFile(public_path('movies-sitemap.xml'));

        $seriessitemap = Sitemap::create();
        Items::where('type','series')->get()->each(function (Items $seriesitem) use ($seriessitemap) {
            $seriessitemap->add(Url::create("/series/{$seriesitem->slug}"));
        });
        $seriessitemap->writeToFile(public_path('series-sitemap.xml'));

        $episodessitemap = Sitemap::create();
        Episodes::all()->each(function (Episodes $episodesitem) use ($episodessitemap) {
            $episodessitemap->add(Url::create("{$episodesitem->series->id}/{$episodesitem->series->slug}/season-{$episodesitem->season_id}/episode-{$episodesitem->episode_id}"));
        });
        $episodessitemap->writeToFile(public_path('episodes-sitemap.xml'));

        $pagesitemap = Sitemap::create();
        Pages::all()->each(function (Pages $pageitem) use ($pagesitemap) {
            $pagesitemap->add(Url::create("/page/{$pageitem->slug}"));
        });
        $pagesitemap->writeToFile(public_path('pages-sitemap.xml'));

        $genresitemap = Sitemap::create();
        Genres::all()->each(function (Genres $genreitem) use ($genresitemap) {
            $genresslug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $genreitem->name)));
            $genresitemap->add(Url::create("/genres/{$genresslug}"));
        });
        $genresitemap->writeToFile(public_path('genres-sitemap.xml'));

        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/'))
            ->add(Url::create('/movies-sitemap.xml'))
            ->add(Url::create('/series-sitemap.xml'))
            ->add(Url::create('/episodes-sitemap.xml'))
            ->add(Url::create('/pages-sitemap.xml'))
            ->add(Url::create('/genres-sitemap.xml'))->writeToFile(public_path('sitemap.xml'));
        return redirect()->action([SitemapsController::class,'sitemaps_lists'])->with('success','Done! Sitemaps Generated');
    }

}
