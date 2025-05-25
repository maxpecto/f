<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Items;
use App\Models\Episodes;
use App\Models\Persons;
use App\Models\Actors;
use App\Models\Genres;
use App\Models\Qualities;
use App\Models\Countries;
use App\Models\Keywords;
use App\Models\Years;

use Image;
use File;

class EpisodesController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_episodes = Episodes::get();
        $search = $request->search;
        if(!empty($request->search)){
            $data = Episodes::whereHas('series', function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.episodes.lists',compact('data','total_episodes'));
        }else{
            $data = Episodes::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.episodes.lists',compact('data','total_episodes'));
        }
    }

    public function add(){
        return view('backend.episodes.add');
    }

    //Display Episodes Edit
 	public function edit($id){
        $episodes = Episodes::find($id);
        $player = json_decode($episodes->player,true);
        $download = json_decode($episodes->download,true);
        return view('backend.episodes.edit',compact('episodes','player','download'));
    }

    //Add Episodes
    public function store(Request $request){
        $this->validate($request,[
            'series_list' => 'required',
            'series_episode' => 'required|integer',
            // 'episode_unique_id' => 'unique:episodes,episode_unique_id', // unique_id'yi aşağıda farklı oluşturacağız
        ], [
            'series_list.required' => 'You Must Select Series!',
            'series_episode.required' => 'Episode number is required.',
            'series_episode.integer' => 'Episode number must be an integer.',
            // 'episode_unique_id.unique' => 'You had added this episode already!'
        ]);

        $episodes = new Episodes();
        $episodes->series_id = $request->series_list;

        $season_number = null;
        if (!empty($request->new_season_number)) {
            $season_number = intval($request->new_season_number);
        } elseif (!empty($request->season_id)) {
            $season_number = intval($request->season_id);
        }

        if (is_null($season_number) || $season_number <= 0) {
            return redirect()->back()->withErrors(['season_id' => 'Season selection or new season number is required and must be a positive integer.'])->withInput();
        }

        $episodes->season_id = $season_number;
        $episodes->episode_id = intval($request->series_episode); // Blade'de name="series_episode" yaptık
        
        // episode_unique_id'yi series_id . season_id . episode_id şeklinde (arada dolgu olmadan) oluşturma
        $potential_unique_id_string = $request->series_list . $season_number . intval($request->series_episode);
        $potential_unique_id = intval($potential_unique_id_string);

        // Benzersizlik kontrolü, bu sefer potential_unique_id üzerinden
        $existing_episode_check = Episodes::where('episode_unique_id', $potential_unique_id)->first();
        if ($existing_episode_check) {
             return redirect()->back()->withErrors(['episode_unique_id' => 'This episode with unique ID ' . $potential_unique_id . ' (Series: ' . $request->series_list . ', Season: ' . $season_number . ', Episode: ' . $request->series_episode . ') already exists.'])->withInput();
        }
        // VEYA series_id, season_id, episode_id kombinasyonuna göre de kontrol edilebilir.
        $existing_episode_combination_check = Episodes::where('series_id', $request->series_list)
                                    ->where('season_id', $season_number)
                                    ->where('episode_id', intval($request->series_episode))
                                    ->first();
        if ($existing_episode_combination_check) {
             return redirect()->back()->withErrors(['episode_unique_id' => 'This episode (Series ID: ' . $request->series_list . ', Season: ' . $season_number . ', Episode: ' . $request->series_episode . ') combination already exists.'])->withInput();
        }

        $episodes->episode_unique_id = $potential_unique_id; 

        $episodes->name = $request->episode_name;
        $episodes->description = $request->episode_description;
        $episodes->air_date = $request->episode_airdate;
        $episodes->views = 0;

        $type["type"] = $request->player_type;
        $name["name"] = $request->player_name;
        $url["url"] = $request->player_url;
        $player = array_merge_recursive($type,$name,$url);
        $episodes->player = json_encode($player);

        $download = array_combine($request->download_name, $request->download_url);
        $firstDownloadKey = array_key_first($download);
        if($firstDownloadKey!= "" ){
            $episodes->download = json_encode($download);
        }

        $episodes->save();

        //Movies Poster
        if($request->episode_image_url == ''){
            $episode_image = $request->file('episode_image');
            if($episode_image == ''){
                $episodes->backdrop = asset('backend/image/default.jpg');
            }else{
                $extension_image = $episode_image->getClientOriginalExtension();
                $file_episodes_image = 'episodes_image_'.$episodes->id.'.'.$extension_image;
                Image::make($episode_image)->resize(1000,600)->save(public_path('/assets/episodes/backdrop/'.$file_episodes_image));
                $episodes->backdrop = $file_episodes_image;
            }
        }else{
            $file_episodes_image = 'episodes_image_'.$episodes->id.'.jpg';
            Image::make($request->episode_image_url)->resize(1000,600)->save(public_path('/assets/episodes/backdrop/'.$file_episodes_image));
            $episodes->backdrop = $file_episodes_image;
        }

        $episodes->save();

        return redirect()->action([EpisodesController::class,'index'])->with('success','Episodes Created Successfully');
    }

    //Update Episodes
    public function update(Request $request, $id){
        $episodes = Episodes::find($id);
        $episodes->name = $request->episode_name;
        $episodes->description = $request->episode_description;
        $episodes->air_date = $request->episode_airdate;

        $type["type"] = $request->player_type;
        $name["name"] = $request->player_name;
        $url["url"] = $request->player_url;
        $player = array_merge_recursive($type,$name,$url);
        $episodes->player = json_encode($player);

        $download = array_combine($request->download_name, $request->download_url);
        $firstDownloadKey = array_key_first($download);
        if($firstDownloadKey!= "" ){
            $episodes->download = json_encode($download);
        } else {
            $episodes->download = null; 
        }

        // Process backdrop image
        $new_backdrop_saved = false;
        if ($request->hasFile('episode_image')) {
            // New file uploaded
            $episode_image = $request->file('episode_image');
                $extension_image = $episode_image->getClientOriginalExtension();
                $file_episodes_image = 'episodes_image_'.$episodes->id.'.'.$extension_image;
            try {
                Image::make($episode_image)->resize(1000, 600)->save(public_path('/assets/episodes/backdrop/' . $file_episodes_image));
                // Delete old image if it exists and is not the default
                if ($episodes->backdrop && $episodes->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/episodes/backdrop/'.$episodes->backdrop))) {
                    File::delete(public_path('/assets/episodes/backdrop/'.$episodes->backdrop));
                }
                $episodes->backdrop = $file_episodes_image;
                $new_backdrop_saved = true;
            } catch (\Exception $e) {
                \Log::error("Error saving uploaded episode backdrop for ID {$episodes->id}: " . $e->getMessage());
            }
        } elseif ($request->filled('episode_image_url')) {
            // URL provided (potentially from existing backdrop)
            $image_url = $request->episode_image_url;
            // Basic check: ensure the URL doesn't just end with the directory path
            $base_path = asset('/assets/episodes/backdrop/');
            if ($image_url != $base_path && filter_var($image_url, FILTER_VALIDATE_URL)) {
                 // Check if the URL corresponds to the current backdrop, if so, do nothing unless a new file was uploaded
                 // This avoids reprocessing the same image if no changes were made
                 $current_backdrop_url = $episodes->backdrop ? asset('/assets/episodes/backdrop/'.$episodes->backdrop) : null;
                 if ($image_url != $current_backdrop_url) {
                    try {
                        // Attempt to process the URL - assumes it's a valid image URL
                        $file_episodes_image = 'episodes_image_' . $episodes->id . '.jpg'; // Assume jpg for URLs for simplicity
                        Image::make($image_url)->resize(1000, 600)->save(public_path('/assets/episodes/backdrop/' . $file_episodes_image));
                         // Delete old image if it exists and is not the default
                        if ($episodes->backdrop && $episodes->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/episodes/backdrop/'.$episodes->backdrop))) {
                             // Avoid deleting if the old name is the same as the new one (though extension might differ)
                            if ($episodes->backdrop != $file_episodes_image) {
                                File::delete(public_path('/assets/episodes/backdrop/'.$episodes->backdrop));
                            }
                        }
            $episodes->backdrop = $file_episodes_image;
                        $new_backdrop_saved = true;
                    } catch (\Intervention\Image\Exception\NotReadableException $e) {
                        \Log::warning("Could not read image from URL for episode ID {$episodes->id}: " . $image_url . " - likely invalid URL or non-image.");
                        // Keep the old backdrop value as the URL was likely invalid
                    } catch (\Exception $e) {
                        \Log::error("Error processing image from URL for episode ID {$episodes->id}: " . $e->getMessage());
                         // Keep the old backdrop value
                    }
                 } // else: URL is the same as current backdrop, do nothing
            } else {
                 \Log::info("Skipping backdrop update for episode ID {$episodes->id}: Invalid or unchanged URL provided: " . $image_url);
                 // Keep the old backdrop value as the URL was likely invalid or the base path
            }
        } else {
             // No file uploaded and no URL provided - potentially keep the old backdrop or set to default if needed.
             // If $episodes->backdrop is already NULL, it remains NULL.
             // If you want to set a default here if it's NULL, you could add:
             // if (is_null($episodes->backdrop)) {
             //     $episodes->backdrop = 'default_backdrop.jpg';
             // }
        }

        $episodes->save();

        return redirect()->action([EpisodesController::class,'index'])->with('success','Episodes Updated Successfully');
    }

    //Delete Episodes
    public function destroy($id){
        $ids = trim($id, '[]');
        $episodesid = explode(",",$ids);
        $episodes = Episodes::whereIn('id', $episodesid)->get();
        foreach ($episodes as $episode) {
            //Delete movies
            $episode->delete();
            if($episode->backdrop != 'default_backdrop.jpg'){
                $image_path = public_path('/assets/episodes/backdrop/'.$episode->backdrop);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        }
        return redirect()->action([EpisodesController::class,'index'])->with('success','Episodes Deleted Successfully!');
    }

    //CODE Get Series Seasons
    public function get_series_seasons($series_id){
        // Bu fonksiyon TMDB API'sini kullanıyordu, şimdilik yorum satırı yapıyorum.
        // Eğer hala TMDB'den sezon çekmek isterseniz, bu yorumu kaldırabilirsiniz.
        /*
        $tmdb = Items::find($series_id);
        $tmdb_id = $tmdb->tmdb_id;
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$tmdb_id.'?language='.config('services.tmdb.lang'))
        ->json();
        return $data;
        */
        // Bunun yerine lokal sezonları getiren yeni bir metodumuz olacak.
        // Bu metodun çağrıldığı yeri kontrol edip get_series_local_seasons'a yönlendirmek gerekebilir.
        // Şimdilik boş bir array döndürelim veya hata mesajı.
        return response()->json([]); // Veya hata mesajı
    }

    // Yeni metod: Lokal veritabanından bir diziye ait sezonları getirir
    public function get_series_local_seasons($series_id)
    {
        if (!Items::find($series_id)) {
            return response()->json(['error' => 'Series not found'], 404);
        }

        $seasons = Episodes::where('series_id', $series_id)
                            ->select('season_id')
                            ->distinct()
                            ->orderBy('season_id', 'asc')
                            ->get();
        return response()->json($seasons);
    }

    //CODE Get Series Seasons/Episodes
    public function get_series_episodes($series_id,$season_id){
        $tmdb = Items::find($series_id);
        $tmdb_id = $tmdb->tmdb_id;
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$tmdb_id.'/season/'.$season_id.'?language='.config('services.tmdb.lang'))
        ->json();
        return $data;
    }

    //CODE Get Episode Data
    public function get_episodes_data($series_id,$season_id,$episode_id){
        $tmdb = Items::find($series_id);
        $tmdb_id = $tmdb->tmdb_id;
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$tmdb_id.'/season/'.$season_id.'/episode/'.$episode_id.'?language='.config('services.tmdb.lang'))
        ->json();
        return $data;
    }

}
