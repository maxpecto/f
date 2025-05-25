<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\User;
use App\Models\Episodes;
use App\Models\Like;
use App\Models\EpisodeLike;
use App\Models\Comments;
use App\Models\Settings;
use App\Models\PreRollVideo;
use Illuminate\Support\Facades\Log;
use Auth;
use Response;
use DB;
use Illuminate\Support\Facades\Cache;

use Trackers;

class DetailEpisodeController extends Controller{

	public function index($id,$series_slug,$series_season,$series_episode){
        Trackers::track_agent();
		
        $season_number = filter_var($series_season, FILTER_SANITIZE_NUMBER_INT);
        $episode_number = filter_var($series_episode, FILTER_SANITIZE_NUMBER_INT);
        $eUID = $id . $season_number . $episode_number;

        $cacheKeyEpisode = "episode_details_{$eUID}";
        $cacheKeyActivePreRoll = 'active_preroll_video_episode';
        $cacheKeyAllEpisodesForSeason = "allepisodes_{$id}_season_{$season_number}";
        $cacheKeyRelatedSeries = "related_series_for_series_{$id}_episode_page";
        $cacheKeyTmdb = "tmdb_images_tv_{$id}_episode_page";

        $episode = Cache::remember($cacheKeyEpisode, now()->addHours(1), function () use ($eUID, $id) {
            return Episodes::with([
                'series' => function ($query) use ($id) {
                    $query->with(['genres', 'platform', 'countries', 'keywords', 'qualities', 'persons'])
                          ->where('id', $id);
                },
                'comments.user'
            ])
            ->withCount([
                'likes as totalLikes' => function ($query) {
                    $query->where('liked', true);
                },
                'likes as totalDislikes' => function ($query) {
                    $query->where('liked', false);
                }
            ])
            ->where('episode_unique_id', $eUID)
            ->first();
        });

        if (!$episode || !$episode->series) {
            Cache::forget($cacheKeyEpisode);
            abort(404, 'Episode or associated series not found.');
        }

        $series = $episode->series;
        
        // Mevcut kullanıcının beğenme durumunu ekleyelim
        $is_liked_by_current_user = false;
        $is_disliked_by_current_user = false;
        if (Auth::check()) {
            $user = Auth::user();
            // EpisodeLike modelini kullanarak doğrudan sorgu yapalım
            $like_status = EpisodeLike::where('episodes_id', $episode->id)
                                      ->where('user_id', $user->id)
                                      ->first();
            if ($like_status) {
                if ($like_status->liked) {
                    $is_liked_by_current_user = true;
                } else {
                    $is_disliked_by_current_user = true;
                }
            }
        }

        $activePreRollVideo = Cache::remember($cacheKeyActivePreRoll, now()->addMinutes(15), function () {
            return PreRollVideo::where('is_active', true)->first();
        });

        Log::info('====== PRE-ROLL VIDEO DEBUG START ======');
        if ($activePreRollVideo) {
            Log::info('Active PreRollVideo Data for JSON: ' . json_encode($activePreRollVideo->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP));
        } else {
            Log::info('Active PreRollVideo is NULL');
        }
        Log::info('====== PRE-ROLL VIDEO DEBUG END ======');

        $allepisodesforseasons = Cache::remember($cacheKeyAllEpisodesForSeason, now()->addHours(1), function () use ($id, $season_number) {
            return Episodes::where('series_id',$id)->where('season_id',$season_number)->orderBy('episode_id', 'asc')->get();
        });
        
        $totalLikes = $episode->totalLikes;
        $totalDislikes = $episode->totalDislikes;

        $player = json_decode($episode->player, true);
    	$download = json_decode($episode->download);

    	$relatedseries = Cache::remember($cacheKeyRelatedSeries, now()->addHours(1), function () use ($series, $id) {
            if ($series->genres->isEmpty()) {
                return collect();
            }
            $genreNames = $series->genres->pluck('name');
            $query = Items::where('type','series')
                ->with(['genres', 'platform'])
                ->whereHas('genres', function ($q) use ($genreNames) {
                    $q->whereIn('name', $genreNames);
                })
                ->where('id', '!=', $id);

            $count = $query->count();
            if ($count === 0) return collect();
            
            $limit = 4;
            $take = min($count, $limit * 3); 

            $items = $query->orderByDesc('rating')
                           ->take($take)
                           ->get();
            
            return $items->shuffle()->take($limit);
        });

        $tmdbdata = Cache::remember($cacheKeyTmdb, now()->addDays(7), function () use ($series) {
            if (empty($series->tmdb_id)) {
                return ['posters' => [], 'backdrops' => []];
            }
            $tmdbId = (int) $series->tmdb_id;
            if ($tmdbId === 0) {
                 Log::warning("Invalid tmdb_id for series: {$series->id} - {$series->title}. tmdb_id was: {$series->tmdb_id}");
                 return ['posters' => [], 'backdrops' => []];
            }
            return Http::withToken(config('services.tmdb.token'))
                ->get("https://api.themoviedb.org/3/tv/{$tmdbId}/images")
                ->json();
        });
        
		return view('frontend.single-episode',compact('series','episode','allepisodesforseasons','player','download','relatedseries','tmdbdata','totalLikes','totalDislikes', 'activePreRollVideo', 'is_liked_by_current_user', 'is_disliked_by_current_user'));
	}

    public function comments(Request $request){
        $this->clearEpisodeCacheByEpisodeId($request->episodes_id);

        $settings = Cache::rememberForever('settings', function () {
            return Settings::findOrFail('1');
        });
        $site_comments_moderation = $settings->site_comments_moderation;

        $episodeForComment = Episodes::find($request->episodes_id);
        if(!$episodeForComment) return redirect(url()->previous().'#comment-form')->with('error','Episode not found!');

        $comments = new Comments();
        $comments->comments = $request->comments;
        $comments->spoiler = $request->has('spoiler');
        $comments->type = 2; //episodes
        $comments->users_id = Auth::user()->id;

        if($site_comments_moderation == 1){
            $comments->approve = 0;
            $comments->save();
            $episodeForComment->comments()->attach($comments->id);
            return redirect(url()->previous().'#comment-form')->with('success','Your comment is awaiting moderation!');
        }else{
            $comments->approve = 1;
            $comments->save();
            $episodeForComment->comments()->attach($comments->id);
            return redirect(url()->previous().'#comment-form')->with('success','Comments Added Successfully!');
        }
    }

    public function deletecomments($id, Request $request){
    	$userid = Auth::id();
        $user = User::find($userid);

        if($userid == null){
            return redirect()->back()->with('error','User not login!');
        }else{
            $commentdata = Comments::with('commentable')->find($id);

            if(!$commentdata){
                 return redirect(url()->previous().'#comment-form')->with('error','Comment not found!');
            }

            if($commentdata->users_id == $userid ){
                if ($commentdata->commentable_type === Episodes::class && $commentdata->commentable_id) {
                    $this->clearEpisodeCacheByEpisodeId($commentdata->commentable_id);
                }
                if($commentdata->commentable_type === Episodes::class && $commentdata->commentable) {
                    $commentdata->commentable->comments()->detach($commentdata->id);
                }
                $commentdata->delete();
                return redirect(url()->previous().'#comment-form')->with('success','Comments Deleted Successfully!');
            }else{
                return redirect(url()->previous().'#comment-form')->with('error','This Comment is not yours!');
            }
        }
    }

    private function clearEpisodeCacheByEpisodeId($episodeId){
        $episodeForCache = Episodes::find($episodeId); 
        if ($episodeForCache) {
            $eUID = $episodeForCache->episode_unique_id; 
            if ($eUID) {
                Cache::forget("episode_details_{$eUID}");
            }
        }
    }

}
