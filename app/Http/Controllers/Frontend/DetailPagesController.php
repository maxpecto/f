<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Pages;
use Response;

use Trackers;

class DetailPagesController extends Controller
{
    public function index($id,Request $request){
        Trackers::track_agent();
		$pages = Pages::where('slug','=',$id)->first();
        if (!$pages) {
            abort(404);
        }
        $pages->increment('views');
		return view('frontend.page',compact('pages'));
    }

}
