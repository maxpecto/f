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
use Auth;
use Response;
use DB;

use Trackers;

class DetailEpisodeController extends Controller{

	public function index($id,$series_slug,$series_season,$series_episode){
        Trackers::track_agent();
		
        // Extract numeric season and episode numbers from URL slugs
        $season_number = filter_var($series_season, FILTER_SANITIZE_NUMBER_INT);
        $episode_number = filter_var($series_episode, FILTER_SANITIZE_NUMBER_INT);

        // Construct the unique ID using numeric values
        $eUID = $id . $season_number . $episode_number;

		$series = Items::where('id',$id)->first();
		$episode = Episodes::where('episode_unique_id',$eUID)->first();

        if (!$episode) {
            abort(404, 'Episode not found.');
        }

        $episode->increment('views');

        $allepisodesforseasons = Episodes::where('series_id',$id)->where('season_id',$season_number)->get();

        $totalLikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', true)->count();
        $totalDislikes = EpisodeLike::where('episodes_id', $episode->id)->where('liked', false)->count();

        $player = json_decode($episode->player, true);
    	$download = json_decode($episode->download);

    	$relatedseries = Items::inRandomOrder()->where('type','series')->whereHas('genres', function ($q) use ($series) {
		    $q->whereIn('name', $series->genres->pluck('name'));
		})
		->where('id', '!=', $id)
		->limit(4)->get();

        $tmdbdata =  Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series->tmdb_id.'/images')
        ->json();

		return view('frontend.single-episode',compact('series','episode','allepisodesforseasons','player','download','relatedseries','tmdbdata','totalLikes','totalDislikes'));
	}

    public function comments(Request $request){
        $settings = Settings::findOrFail('1');
    	$site_comments_moderation = $settings->site_comments_moderation;

        $episodes = Episodes::find($request->episodes_id);
        $comments = new Comments();
        $comments->comments = $request->comments;
        $comments->spoiler = $request->has('spoiler');
        $comments->type = 2; //episodes
        $comments->users_id = Auth::user()->id;

        if($site_comments_moderation == 1){
            $comments->approve = 0;
            $comments->save();
            $episodes->comments()->sync($comments->id,false);
            return redirect(url()->previous().'#comment-form')->with('success','Your comment is awaiting moderation!');
        }else{
            $comments->approve = 1;
            $comments->save();
            $episodes->comments()->sync($comments->id,false);
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
