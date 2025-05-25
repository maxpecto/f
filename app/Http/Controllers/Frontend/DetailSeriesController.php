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
use Auth;

use App\Models\PreRollVideo;
use Trackers;

class DetailSeriesController extends Controller
{

    public function index($id,Request $request){
        Trackers::track_agent();
		$series = Items::where('slug','=',$id)->where('type', 'series')->first();

        if (!$series) {
            abort(404);
        }

        $series->increment('views');
        $totalLikes = Like::where('items_id', $series->id)->where('liked', true)->count();
        $totalDislikes = Like::where('items_id', $series->id)->where('liked', false)->count();

        $allepisodes = Episodes::where('series_id',$series->id)->get();
        $uniqueSeason = $allepisodes->unique('season_id')->all();

        // İlk gösterilecek bölümü belirle
        $firstEpisodeToShow = Episodes::where('series_id', $series->id)
                                    ->orderBy('season_id', 'asc')
                                    ->orderBy('episode_id', 'asc')
                                    ->first();

        $relatedseries = Items::inRandomOrder()->where('type','series')->whereHas('genres', function ($q) use ($series) {
		    $q->whereIn('name', $series->genres->pluck('name'));
		})
		->where('slug', '!=', $id)
		->limit(4)->get();

    	$download = json_decode($series->download);

        $tmdbdata =  Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series->tmdb_id.'/images')
        ->json();

        $activePreRollVideo = PreRollVideo::where('is_active', true)->first();

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
        $settings = Settings::findOrFail('1');
    	$site_comments_moderation = $settings->site_comments_moderation;

        $series = Items::find($request->series_id);
        $comments = new Comments();
        $comments->comments = $request->comments;
        $comments->spoiler = $request->has('spoiler');
        $comments->type = 1; //episodes
        $comments->users_id = Auth::user()->id;

        if($site_comments_moderation == 1){
            $comments->approve = 0;
            $comments->save();
            $series->comments()->sync($comments->id,false);
            return redirect(url()->previous().'#comment-form')->with('success','Your comment is awaiting moderation!');
        }else{
            $comments->approve = 1;
            $comments->save();
            $series->comments()->sync($comments->id,false);
            return redirect(url()->previous().'#comment-form')->with('success','Comments Added Successfully!');
        }

    }

    public function deletecomments($id, Request $request){
    	$userid = Auth::id();
        $user = User::find($userid);

        if($userid == null){
            return redirect()->back()->with('success','User not login!');
        }else{
            $commentdata = Comments::find($id);
            $commentemail = $commentdata->users_id;
            $useremail = $user->id;

            if($useremail == $commentemail ){
                //Delete comment
                $commentdata->delete();
                return redirect(url()->previous().'#comment-form')->with('success','Comments Deleted Successfully!');
            }else{
                return redirect(url()->previous().'#comment-form')->with('success','This Comment is not yours!');
            }
        }
    }

}
