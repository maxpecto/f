<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Episodes;
use App\Models\Like;
use App\Models\EpisodeLike;
use App\Models\Sliders;
use Illuminate\Support\Facades\Cache;
use Trackers;

class HomeController extends Controller
{
    public function index(Request $request){
        //Tackers
        Trackers::track_agent();

        $sliders = Cache::remember('home_sliders', now()->addMinutes(60), function () {
            return Sliders::where('visible','1')->orderBy('id', 'DESC')->get();
        });

        $currentPage = $request->input('page', 1);

        $latestmovies = Cache::remember('home_latest_movies_page_' . $currentPage, now()->addMinutes(10), function () {
            return Items::with(['genres', 'platform'])->where('type', 'movies')->where('visible','1')->orderBy('id', 'DESC')->paginate(10);
        });

        $latestseries = Cache::remember('home_latest_series_page_' . $currentPage, now()->addMinutes(10), function () {
            return Items::with(['genres', 'platform'])->where('type', 'series')->where('visible','1')->orderBy('id', 'DESC')->paginate(10);
        });

        return view('frontend.home',compact(
            'latestmovies',
            'latestseries',
            'sliders'
        ));
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
