<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Like;
use App\Models\Episodes;
use App\Models\Comments;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\PreRollVideo;
use Auth;
use Trackers;

class DetailSeriesController extends Controller
{

    public function index($slug, Request $request){
        Trackers::track_agent();

        $cacheKeySeries = "series_details_{$slug}";
        $series = Cache::remember($cacheKeySeries, now()->addHours(1), function () use ($slug) {
            return Items::with([
                'genres',
                'countries',
                'keywords',
                'qualities',
                'platform',
                'persons',
                'episodes',
                'comments.user'
            ])->where('slug', $slug)->where('type', 'series')->firstOrFail();
        });

        $activePreRollVideo = Cache::remember('active_preroll_video', now()->addMinutes(15), function () {
            return PreRollVideo::where('is_active', true)->first();
        });

        $series->increment('views');
        $totalLikes = Like::where('items_id', $series->id)->where('liked', true)->count();
        $totalDislikes = Like::where('items_id', $series->id)->where('liked', false)->count();

        $allepisodes = $series->episodes;
        $uniqueSeason = $allepisodes->unique('season_id')->all();

        $firstEpisodeToShow = $allepisodes->sortBy('season_id')->sortBy('episode_id')->first();

        $cacheKeyRelated = "related_series_{$slug}";
        $relatedseries = Cache::remember($cacheKeyRelated, now()->addHours(1), function () use ($series, $slug) {
            $genreIds = $series->genres->pluck('id')->toArray();
            return Items::with(['genres', 'platform'])
                        ->whereHas('genres', function ($q) use ($genreIds) {
                            $q->whereIn('genres.id', $genreIds);
                        })
                        ->where('id', '!=', $series->id)
                        ->where('type', 'series')
                        ->where('visible', 1)
                        ->orderByDesc('rating')
                        ->take(10)
                        ->get()
                        ->shuffle()
                        ->take(5);
        });

    	$download = json_decode($series->download, true);

        $tmdbdata = null;
        if ($series->tmdb_id) {
            $cacheKeyTmdb = "tmdb_tv_images_{$series->tmdb_id}";
            $tmdbdata = Cache::remember($cacheKeyTmdb, now()->addDays(7), function () use ($series) {
                $response = Http::withToken(config('services.tmdb.token'))
                                ->get("https://api.themoviedb.org/3/tv/{$series->tmdb_id}/images");
                return $response->successful() ? $response->json() : null;
            });
        }

		return view('frontend.single-series',compact('series','allepisodes','uniqueSeason','download','relatedseries','tmdbdata','totalLikes','totalDislikes', 'firstEpisodeToShow', 'activePreRollVideo'));
    }

    public function getSeasonsEpisodes($series_id,$season_id){
        $allepisodes = Episodes::where('series_id',$series_id)->where('season_id',$season_id)->orderBy('episode_id', 'ASC')->get();
		return view('frontend.layouts.episodes', compact('allepisodes'));
	}

    public function getAllEpisodes($series_id){
		$allepisodes = Episodes::where('series_id',$series_id)->get();
		return view('frontend.layouts.episodes', compact('allepisodes'));
	}

    public function comments(Request $request){
        $settings = Cache::rememberForever('settings_all', function(){
            return Settings::findOrFail('1');
        });
    	$site_comments_moderation = $settings->site_comments_moderation;

        $request->validate([
            'comments' => 'required|string|max:1000',
            'series_id' => 'required|integer|exists:items,id',
        ]);

        $series = Items::find($request->series_id);
        if (!$series) {
            return redirect()->back()->with('error', 'Dizi bulunamadı!');
        }

        $comment = new Comments();
        $comment->comments = $request->comments;
        $comment->spoiler = $request->has('spoiler');
        $comment->type = 1;
        $comment->users_id = Auth::id();
        $comment->approve = ($site_comments_moderation == 1) ? 0 : 1;
        $comment->save();

        $series->comments()->attach($comment->id);

        Cache::forget("series_details_{$series->slug}");

        $message = ($site_comments_moderation == 1) ? 'Yorumunuz onay bekliyor!' : 'Yorum başarıyla eklendi!';
        return redirect(url()->previous().'#comment-form')->with('success', $message);
    }

    public function deletecomments($id, Request $request){
    	$userid = Auth::id();
        $comment = Comments::findOrFail($id);

        if($userid == null || $comment->users_id != $userid ){
            return redirect()->back()->with('error','Bu işlemi yapmaya yetkiniz yok veya yorum size ait değil!');
        }
        
        $comment->delete();
        return redirect(url()->previous().'#comment-form')->with('success','Yorum başarıyla silindi!');
    }

}
