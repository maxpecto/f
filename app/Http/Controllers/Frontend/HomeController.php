<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Episodes;
use App\Models\Like;
use App\Models\EpisodeLike;
use App\Models\Settings;
use App\Models\Sliders;
use App\Models\Platform;

use Trackers;

class HomeController extends Controller
{
    public function index(){
        //Tackers
        Trackers::track_agent();
        $general = Settings::findOrFail('1');
        $sliders = Sliders::where('visible','1')->orderBy('id', 'DESC')->get();
        $latestmovies = Items::where('type', 'movies')->where('visible','1')->orderBy('id', 'DESC')->paginate(10);
        $latestseries = Items::where('type', 'series')->where('visible','1')->orderBy('id', 'DESC')->paginate(10);
        $featuremovies = Items::where('type', 'movies')->where('visible','1')->where('feature','1')->orderBy('id', 'DESC')->paginate(10);
        $featureseries = Items::where('type', 'series')->where('visible','1')->where('feature','1')->orderBy('id', 'DESC')->paginate(10);
        $recommendedmovies = Items::where('type', 'movies')->where('visible','1')->where('recommended','1')->orderBy('id', 'DESC')->paginate(10);
        $recommendedseries = Items::where('type', 'series')->where('visible','1')->where('recommended','1')->orderBy('id', 'DESC')->paginate(10);
        $platforms = Platform::whereHas('items', function($query) {
            $query->where('visible', '1');
        })->withCount(['items' => function($query) {
            $query->where('visible', '1');
        }])->having('items_count', '>', 0)->orderBy('name')->get();

        return view('frontend.home',compact('general','latestmovies','latestseries','featuremovies','featureseries','recommendedmovies','recommendedseries','sliders', 'platforms'));
    }

    public function onboarding(){
        return view('auth.onboarding');
    }

    public function LikeStore(Items $items){
        $items->like(current_user());
        $totalLikes = Like::where('items_id', $items->id)->where('liked', true)->count();
        $totalDislikes = Like::where('items_id', $items->id)->where('liked', false)->count();
        return response()->json(['like' => true, 'total_like' => $totalLikes, 'total_dislike' => $totalDislikes]);
    }

    public function LikeDestroy(Items $items){
        $items->dislike(current_user());
        $totalDislikes = Like::where('items_id', $items->id)->where('liked', false)->count();
        $totalLikes = Like::where('items_id', $items->id)->where('liked', true)->count();
        return response()->json(['dislike' => true, 'total_dislike' => $totalDislikes, 'total_like' => $totalLikes]);
    }

    public function EpisodeLikeStore(Episodes $episode){
        $episode->episode_like(current_user());
        $totalEpisodeLikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', true)->count();
        $totalEpisodeDislikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', false)->count();
        return response()->json(['like' => true, 'total_like' => $totalEpisodeLikes, 'total_dislike' => $totalEpisodeDislikes]);
    }

    public function EpisodeLikeDestroy(Episodes $episode){
        $episode->episode_dislike(current_user());
        $totalEpisodeDislikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', false)->count();
        $totalEpisodeLikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', true)->count();
        return response()->json(['dislike' => true, 'total_dislike' => $totalEpisodeDislikes, 'total_like' => $totalEpisodeLikes]);
    }

    public function watchlistStore(Items $items){
        if($items->isWatchlistedBy(current_user())){
            $items->remove_watchlist(current_user());
            return response()->json(['watchlist' => false]);
        }else{
            $items->add_watchlist(current_user());
            return response()->json(['watchlist' => true]);
        }
    }


}
