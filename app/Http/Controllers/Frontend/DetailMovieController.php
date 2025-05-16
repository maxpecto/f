<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Like;
use App\Models\Comments;
use App\Models\Settings;
use App\Models\User;
use Auth;
use Response;
use DB;

use Trackers;

class DetailMovieController extends Controller
{

    public function index($id,Request $request){
        Trackers::track_agent();
		$movies = Items::where('slug','=',$id)->where('type', 'movies')->first();

        if (!$movies) {
            abort(404);
        }

        $movies->increment('views');
        $totalLikes = Like::where('items_id', $movies->id)->where('liked', true)->count();
        $totalDislikes = Like::where('items_id', $movies->id)->where('liked', false)->count();


        $player = json_decode($movies->player,true);
        $download = json_decode($movies->download,true);

    	$relatedmovies = Items::inRandomOrder()->whereHas('genres', function ($q) use ($movies) {
		    $q->whereIn('name', $movies->genres->pluck('name'));
		})
		->where('slug', '!=', $id)
        ->where('type', 'movies')
		->limit(5)->get();

        $tmdbdata =  Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$movies->tmdb_id.'/images')
        ->json();

		return view('frontend.single',compact('movies','player','download','relatedmovies','tmdbdata','totalLikes','totalDislikes'));
    }

    public function comments(Request $request){
        $settings = Settings::findOrFail('1');
    	$site_comments_moderation = $settings->site_comments_moderation;

        $movies = Items::find($request->movie_id);
        $comments = new Comments();
        $comments->comments = $request->comments;
        $comments->spoiler = $request->has('spoiler');
        $comments->type = 0; //movie
        $comments->users_id = Auth::user()->id;

        if($site_comments_moderation == 1){
            $comments->approve = 0;
            $comments->save();
            $movies->comments()->sync($comments->id,false);
            return redirect(url()->previous().'#comment-form')->with('success','Your comment is awaiting moderation!');
        }else{
            $comments->approve = 1;
            $comments->save();
            $movies->comments()->sync($comments->id,false);
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
