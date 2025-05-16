<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Persons;

use Carbon\Carbon;

use Trackers;

class DetailPersonsController extends Controller{

	public function index($id){
        Trackers::track_agent();
		$persons_data = Persons::where('id',$id)->first();

        $age = Carbon::parse($persons_data->birthday)->diff(Carbon::now())->y;

        $tmdbdata =  Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$persons_data->tmdb_id.'/images')
        ->json();

		return view('frontend.single-person',compact('persons_data','tmdbdata','age'));
	}

}
