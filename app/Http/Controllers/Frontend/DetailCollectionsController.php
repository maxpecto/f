<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Collections;
use App\Models\Settings;

use Trackers;

class DetailCollectionsController extends Controller{

	public function index($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;

		$collections_items = Collections::where('visible',1)->where('id',$id)->first();
        $collections_items->increment('views');

        $collections_lists_items = $collections_items->items()->paginate($site_items_per_page)->onEachSide(1);

		return view('frontend.single-collections',compact('collections_items','collections_lists_items'));
	}



}
