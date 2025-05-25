<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Like;
use App\Models\Comments;
use App\Models\Settings;
use App\Models\User;
use App\Models\PreRollVideo;
use Illuminate\Support\Facades\Cache;
use Auth;
use Response;
use DB;

use Trackers;

class DetailMovieController extends Controller
{

    public function index($slug, Request $request){
        Trackers::track_agent();

        $cacheKeyMovie = "movie_details_{$slug}";
        $movies = Cache::remember($cacheKeyMovie, now()->addHours(1), function () use ($slug) {
            return Items::with([
                'genres', 
                'countries', 
                'keywords', 
                'qualities', 
                'platform',
                'persons',
                'comments.user'
            ])->where('slug', $slug)->where('type', 'movies')->firstOrFail();
        });

        $activePreRollVideo = Cache::remember('active_preroll_video', now()->addMinutes(15), function () {
            return PreRollVideo::where('is_active', true)->first();
        });

        $movies->increment('views');
        $totalLikes = Like::where('items_id', $movies->id)->where('liked', true)->count();
        $totalDislikes = Like::where('items_id', $movies->id)->where('liked', false)->count();

        $player = json_decode($movies->player,true);
        $download = json_decode($movies->download,true);

        $cacheKeyRelated = "related_movies_{$slug}";
        $relatedmovies = Cache::remember($cacheKeyRelated, now()->addHours(1), function () use ($movies, $slug) {
            $genreIds = $movies->genres->pluck('id')->toArray();
            return Items::with(['genres', 'platform'])
                        ->whereHas('genres', function ($q) use ($genreIds) {
                            $q->whereIn('genres.id', $genreIds);
                        })
                        ->where('id', '!=', $movies->id)
                        ->where('type', 'movies')
                        ->where('visible', 1)
                        ->orderByDesc('rating')
                        ->take(10)
                        ->get()
                        ->shuffle()
                        ->take(5);
        });

        $tmdbdata = null;
        if ($movies->tmdb_id) {
            $cacheKeyTmdb = "tmdb_movie_images_{$movies->tmdb_id}";
            $tmdbdata = Cache::remember($cacheKeyTmdb, now()->addDays(7), function () use ($movies) {
                $response = Http::withToken(config('services.tmdb.token'))
                                ->get("https://api.themoviedb.org/3/movie/{$movies->tmdb_id}/images");
                return $response->successful() ? $response->json() : null;
            });
        }

		return view('frontend.single',compact('movies','player','download','relatedmovies','tmdbdata','totalLikes','totalDislikes', 'activePreRollVideo'));
    }

    public function comments(Request $request){
        $settings = Cache::rememberForever('settings_all', function(){
            return Settings::findOrFail('1');
        });
    	$site_comments_moderation = $settings->site_comments_moderation;

        $request->validate([
            'comments' => 'required|string|max:1000',
            'movie_id' => 'required|integer|exists:items,id',
        ]);

        $movies = Items::find($request->movie_id);
        if (!$movies) {
            return redirect()->back()->with('error', 'Film bulunamadı!');
        }

        $comment = new Comments();
        $comment->comments = $request->comments;
        $comment->spoiler = $request->has('spoiler');
        $comment->type = 0; //movie
        $comment->users_id = Auth::id();
        $comment->approve = ($site_comments_moderation == 1) ? 0 : 1;
        $comment->save();

        $movies->comments()->attach($comment->id);

        Cache::forget("movie_details_{$movies->slug}");

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
