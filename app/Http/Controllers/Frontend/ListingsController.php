<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Items;
use App\Models\Episodes;
use App\Models\Persons;
use App\Models\Collections;
use App\Models\Watchlists;
use App\Models\Settings;

use Response;
use Auth;

use Trackers;

class ListingsController extends Controller{

    public function movies(Request $request){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
        $site_items_per_page = $settings->site_items_per_page;

        if(!empty( $request->except('_token') ) ){
            $items = Items::query();

            if(isset($request->rating)){
                $rating = $request->rating;
                $items->where('rating', '>=', $rating);
            }

            if(isset($request->duration_min)){
                $duration_min = $request->duration_min;
                $items->where('duration', '>=', $duration_min);
            }

            if(isset($request->duration_max)){
                $duration_max = $request->duration_max;
                $items->where('duration', '<=', $duration_max);
            }

            if(isset($request->genres)){
                $items->whereHas('genres', function($query) use($request) {
                    $query->whereIn('name', [$request->genres]);
                });
            }

            if(isset($request->quality)){
                $items->whereHas('qualities', function($query) use($request) {
                    $query->whereIn('name', [$request->quality]);
                });
            }

            if(isset($request->countries)){
                $items->whereHas('countries', function($query) use($request) {
                    $query->whereIn('code', [$request->countries]);
                });
            }

            if(isset($request->year)){
                $items->whereHas('years', function($query) use($request) {
                    $query->whereIn('name', [$request->year]);
                });
            }

            if(isset($request->sorting)){

                if($request->sorting == 'ASC'){
                    $items->orderBy('created_at', 'ASC');
                }else if($request->sorting == 'DESC'){
                    $items->orderBy('created_at', 'DESC');
                }else if($request->sorting == 'ASC-1'){
                    $items->orderBy('id', 'ASC');
                }else if($request->sorting == 'DESC-1'){
                    $items->orderBy('id', 'DESC');
                }else if($request->sorting == 'views'){
                    $items->orderBy('views', 'DESC');
                }else if($request->sorting == 'release'){
                    $items->orderBy('release_date', 'DESC');
                }else if($request->sorting == 'rating'){
                    $items->orderBy('rating', 'DESC');
                }

            }

            $movies = $items->orderBy('id', 'DESC')->where('type', 'movies')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
        }else{
            $movies = Items::orderBy('id','DESC')->where('visible', 1)->where('type', 'movies')->paginate($site_items_per_page)->onEachSide(1);
        }
		return view('frontend.movies-lists', compact('movies'));
	}

    public function search(Request $request){
        Trackers::track_agent();
        $itemsQuery = Items::query();

        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;

        if(isset($request->keywords)){
            $name = $request->keywords;
            $itemsQuery->where('title', 'like', '%'.$name.'%');
        }

        // Canlı arama için sadece birkaç sonuç getirelim, tüm sayfayı değil.
        if ($request->ajax() || $request->input('source') === 'live_search') {
            // Canlı aramada diğer filtreleri de dikkate almak isterseniz burada işleyebilirsiniz.
            // Örneğin:
            // if(isset($request->genres) && !empty($request->genres)){
            //     $itemsQuery->whereHas('genres', function($query) use($request) {
            //         $query->whereIn('name', is_array($request->genres) ? $request->genres : [$request->genres]);
            //     });
            // }
            // ... diğer filtreler (years, quality vb.)

            $results = $itemsQuery->where('visible', 1)
                                  ->orderBy('id', 'DESC') // Veya alaka düzeyine göre bir sıralama (örn: Puan, izlenme sayısı)
                                  ->take(10) // Örneğin ilk 10 sonucu göster
                                  ->get();
            
            // Sonuçları _live_search_results.blade.php partial view'ına gönder
            return view('frontend.partials._live_search_results', compact('results'))->render();
        }

        // Normal arama (AJAX değilse) - Mevcut kodunuz devam ediyor
        if(isset($request->rating_from, $request->rating_to)){
             if(!empty($request->rating_from) && !empty($request->rating_to)){
                $itemsQuery->whereBetween('rating', [$request->rating_from, $request->rating_to]);
             }
        }

        if(isset($request->duration_from, $request->duration_to)){
            if(!empty($request->duration_from) && !empty($request->duration_to)){
                $itemsQuery->whereBetween('duration', [$request->duration_from, $request->duration_to]);
            }
        }

        if(isset($request->genres) && !empty($request->genres)){
            $itemsQuery->whereHas('genres', function($query) use($request) {
                $query->whereIn('name', is_array($request->genres) ? $request->genres : [$request->genres]);
            });
        }

        if(isset($request->quality) && !empty($request->quality)){
            $itemsQuery->whereHas('qualities', function($query) use($request) {
                $query->whereIn('name', is_array($request->quality) ? $request->quality : [$request->quality]);
            });
        }

        if(isset($request->countries) && !empty($request->countries)){
            $itemsQuery->whereHas('countries', function($query) use($request) {
                $query->whereIn('code', is_array($request->countries) ? $request->countries : [$request->countries]);
            });
        }

        if(isset($request->years) && !empty($request->years)){
            $itemsQuery->whereHas('years', function($query) use($request) {
                $query->whereIn('name', is_array($request->years) ? $request->years : [$request->years]);
            });
        }

        $data = $itemsQuery->orderBy('id', 'DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);

        return view('frontend.search-lists', compact('data'));
    }

    public function series(Request $request){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;

        if(!empty( $request->except('_token') ) ){
            $items = Items::query();
            if(isset($request->rating)){
                $rating = $request->rating;
                $items->where('rating', '>=', $rating);
            }
            if(isset($request->duration_min)){
                $duration_min = $request->duration_min;
                $items->where('duration', '>=', $duration_min);
            }
            if(isset($request->duration_max)){
                $duration_max = $request->duration_max;
                $items->where('duration', '<=', $duration_max);
            }
            if(isset($request->genres)){
                $items->whereHas('genres', function($query) use($request) {
                    $query->whereIn('name', [$request->genres]);
                });
            }
            if(isset($request->quality)){
                $items->whereHas('qualities', function($query) use($request) {
                    $query->whereIn('name', [$request->quality]);
                });
            }
            if(isset($request->countries)){
                $items->whereHas('countries', function($query) use($request) {
                    $query->whereIn('code', [$request->countries]);
                });
            }
            if(isset($request->year)){
                $items->whereHas('years', function($query) use($request) {
                    $query->whereIn('name', [$request->year]);
                });
            }
            if(isset($request->sorting)){
                if($request->sorting == 'ASC'){
                    $items->orderBy('created_at', 'ASC');
                }else if($request->sorting == 'DESC'){
                    $items->orderBy('created_at', 'DESC');
                }else if($request->sorting == 'ASC-1'){
                    $items->orderBy('id', 'ASC');
                }else if($request->sorting == 'DESC-1'){
                    $items->orderBy('id', 'DESC');
                }else if($request->sorting == 'views'){
                    $items->orderBy('views', 'DESC');
                }else if($request->sorting == 'release'){
                    $items->orderBy('release_date', 'DESC');
                }else if($request->sorting == 'rating'){
                    $items->orderBy('rating', 'DESC');
                }
            }
            $series = $items->orderBy('id', 'DESC')->where('type', 'series')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
        }else{
            $series = Items::orderBy('id','DESC')->where('visible', 1)->where('type', 'series')->paginate($site_items_per_page)->onEachSide(1);
        }
		return view('frontend.series-lists', compact('series'));
	}

    public function watchlists(){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;

        $userid = Auth::user()->id;
        $watchlists_lists = Watchlists::with('items')->where('user_id', $userid)->orderBy('id', 'DESC')->paginate($site_items_per_page)->onEachSide(1);
        return view('frontend.watchlists-lists', compact('watchlists_lists'));
    }

    public function trendings(){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
		$trendings_lists = Items::where('visible', 1)->orderBy('views','DESC')->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.trendings-lists', compact('trendings_lists'));
	}

    public function recommendeds(){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
		$recommendeds_lists = Items::where('visible', 1)->where('recommended',1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.recommendeds-lists', compact('recommendeds_lists'));
	}

    public function collections(){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $collections_lists = Collections::where('visible',1)->orderBy('id', 'DESC')->paginate($site_items_per_page)->onEachSide(1);
        return view('frontend.collections-lists', compact('collections_lists'));
    }

    public function episodes(){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
		$episodes = Episodes::with('series')->orderBy('id', 'DESC')->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.episodes-lists', compact('episodes'));
	}

    public function persons(Request $request){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        if(!empty( $request->except('_token') ) ){
            $persons_lists = Persons::orderBy('popularity','DESC')->where('name', 'like', '%'.$request->search_person.'%')->paginate($site_items_per_page)->onEachSide(1);
            return view('frontend.persons-lists', compact('persons_lists'));
        }else{
            $persons_lists = Persons::orderBy('popularity','DESC')->paginate(40)->onEachSide(1);
            return view('frontend.persons-lists', compact('persons_lists'));
        }

	}

    public function alpa($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $start_with = ucwords($id);
		$alpa_lists = Items::where('title', 'regexp', '^['.$id.']+')->orderBy('id','DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.alpa-lists', compact('alpa_lists','start_with'));
	}

    public function genres($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $heading_name = ucwords(trim(str_replace('-',' ', $id)));
        $data = Items::whereHas('genres', function ($subQuery) use ($id) {
            $subQuery->where('name', ucwords(str_replace("-"," ",$id)));
        })->orderBy('id', 'DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.listings', compact('data','heading_name'));
	}

    public function years($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $heading_name = ucwords(trim(str_replace('-',' ', $id)));
        $data = Items::whereHas('years', function ($subQuery) use ($id) {
            $subQuery->where('name', ucwords(str_replace("-"," ",$id)));
        })->orderBy('id', 'DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.listings', compact('data','heading_name'));
	}

    public function countries($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $heading_name = ucwords(trim(str_replace('-',' ', $id)));
        $data = Items::whereHas('countries', function ($subQuery) use ($id) {
            $subQuery->where('name', ucwords(str_replace("-"," ",$id)));
        })->orderBy('id', 'DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.listings', compact('data','heading_name'));
	}

    public function qualities($id){
        Trackers::track_agent();
        $settings = Settings::findOrFail('1');
    	$site_items_per_page = $settings->site_items_per_page;
        $heading_name = ucwords(trim(str_replace('-',' ', $id)));
        $data = Items::whereHas('qualities', function ($subQuery) use ($id) {
            $subQuery->where('name', ucwords(str_replace("-"," ",$id)));
        })->orderBy('id', 'DESC')->where('visible', 1)->paginate($site_items_per_page)->onEachSide(1);
		return view('frontend.listings', compact('data','heading_name'));
	}


}
