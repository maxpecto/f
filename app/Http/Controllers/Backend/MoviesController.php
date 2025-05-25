<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Items;
use App\Models\Persons;
use App\Models\Actors;
use App\Models\Genres;
use App\Models\Qualities;
use App\Models\Countries;
use App\Models\Keywords;
use App\Models\Years;
use App\Models\Platform;

use Image;
use File;

class MoviesController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_movies = Items::where('type', 'movies')->get();
        if(!empty($request->search)){
            $data = Items::where([
                ['title', 'LIKE', '%' . $request->search . '%']
            ])->where('type','movies')->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.movies.lists',compact('data','total_movies'));
        }else{
            $data = Items::orderBy('id', 'DESC')->where('type','movies')->paginate(10)->onEachSide(1);
            return view('backend.movies.lists',compact('data','total_movies'));
        }
    }

    public function add(){
        $platforms = Platform::orderBy('name')->get();
        return view('backend.movies.add', compact('platforms'));
    }

    //Display Movies Edit
 	public function edit($id){
        $movies = Items::find($id);
        $persons = Actors::where('items_id',$id);
        $platforms = Platform::orderBy('name')->get();

        $player = json_decode($movies->player,true);
        $download = json_decode($movies->download,true);

        return view('backend.movies.edit',compact('movies','persons','player','download', 'platforms'));
    }

    //Add Movies
    public function store(Request $request){
        $this->validate($request,[
            'movie_name' => 'required|unique:items,title',
            'movie_id' => 'nullable|unique:items,tmdb_id',
        ]);

        $movies = new Items();
        $movies->type = 'movies';
        $movies->tmdb_id = $request->movie_id;
        $movies->imdb_id = $request->imdb_id;
        $movies->title = $request->movie_name;
        $movies->slug = Str::slug($request->movie_name);
        $movies->tagline = $request->movie_tagline;
        $movies->overviews = $request->movie_description;
        $movies->duration = $request->movie_duration;
        $movies->rating = $request->movie_rating;
        $movies->release_date = $request->movie_release_date;
        $movies->trailer = $request->movie_trailer;
        $movies->views = 0;
        $movies->visible = 1;
        $movies->feature = 0;
        $movies->recommended = $request->has('recommended_status') ? $request->recommended_status : 0;
        $movies->platform_id = $request->platform_id;

        $type["type"] = $request->player_type;
        $name["name"] = $request->player_name;
        $url["url"] = $request->player_url;
        $player = array_merge_recursive($type,$name,$url);
        $movies->player = json_encode($player);

        $download = array_combine($request->download_name, $request->download_url);
        $firstDownloadKey = array_key_first($download);
        if($firstDownloadKey!= "" ){
            $movies->download = json_encode($download);
        }

        $movies->save();

        //Movies Poster
        $new_poster_uploaded = $request->file('movie_poster');
        $poster_url_provided = $request->filled('movie_poster_url');

        if ($new_poster_uploaded) {
            $extension_poster = $new_poster_uploaded->getClientOriginalExtension();
            $original_poster_name = 'movie_poster_'.$movies->id.'.'.$extension_poster;
            $webp_poster_name = 'movie_poster_'.$movies->id.'.webp';

            $original_poster_path_relative = 'assets/movies/poster/'.$original_poster_name;
            $webp_poster_path_relative = 'assets/movies/poster/'.$webp_poster_name;

            // Save original image to storage
            $img_original_poster = Image::make($new_poster_uploaded)->resize(405,600)->encode($extension_poster, 80);
            Storage::disk('public')->put($original_poster_path_relative, (string) $img_original_poster);

            // Save WebP version to storage
            $img_webp_poster = Image::make($new_poster_uploaded)->resize(405,600)->encode('webp', 80);
            Storage::disk('public')->put($webp_poster_path_relative, (string) $img_webp_poster);
            
            $movies->poster = $original_poster_path_relative; // DB'ye orijinalin yeni göreli yolunu kaydet

        } else if ($poster_url_provided) {
            $poster_url = $request->movie_poster_url;
            try {
                $poster_contents = file_get_contents($poster_url);
                if ($poster_contents === false) {
                    throw new \Exception("Görsel içeriği alınamadı.");
                }
                $poster_extension = pathinfo($poster_url, PATHINFO_EXTENSION) ?: 'jpg'; // Extension yoksa jpg varsay
                // Geçerli uzantıları kontrol et
                if (!in_array(strtolower($poster_extension), ['jpeg', 'jpg', 'png', 'gif'])) {
                    $poster_extension = 'jpg'; // Desteklenmiyorsa jpg olarak kaydet
                }

                $original_poster_name_from_url = 'movie_poster_url_'.$movies->id.'.'.$poster_extension;
                $webp_poster_name_from_url = 'movie_poster_url_'.$movies->id.'.webp';

                $original_poster_path_relative_from_url = 'assets/movies/poster/'.$original_poster_name_from_url;
                $webp_poster_path_relative_from_url = 'assets/movies/poster/'.$webp_poster_name_from_url;

                // Save original image from URL to storage
                $img_original_poster_url = Image::make($poster_contents)->resize(405,600)->encode($poster_extension, 80);
                Storage::disk('public')->put($original_poster_path_relative_from_url, (string) $img_original_poster_url);

                // Save WebP version from URL to storage
                $img_webp_poster_url = Image::make($poster_contents)->resize(405,600)->encode('webp', 80);
                Storage::disk('public')->put($webp_poster_path_relative_from_url, (string) $img_webp_poster_url);

                $movies->poster = $original_poster_path_relative_from_url; // DB'ye orijinalin yeni göreli yolunu kaydet
            } catch (\Exception $e) {
                // Log error or notify user
                // Toastr()->error('Poster URL görseli işlenirken bir hata oluştu: '.$e->getMessage());
            }
        }

        //Movies Backdrop
        $new_backdrop_uploaded = $request->file('movie_image');
        $backdrop_url_provided = $request->filled('movie_image_url');

        if ($new_backdrop_uploaded) {
            $extension_image = $new_backdrop_uploaded->getClientOriginalExtension();
            $original_backdrop_name = 'movie_image_'.$movies->id.'.'.$extension_image;
            $webp_backdrop_name = 'movie_image_'.$movies->id.'.webp';

            $original_backdrop_path_relative = 'assets/movies/backdrop/'.$original_backdrop_name;
            $webp_backdrop_path_relative = 'assets/movies/backdrop/'.$webp_backdrop_name;

            // Save original image to storage
            $img_original_backdrop = Image::make($new_backdrop_uploaded)->resize(1000,600)->encode($extension_image, 75);
            Storage::disk('public')->put($original_backdrop_path_relative, (string) $img_original_backdrop);

            // Save WebP version to storage
            $img_webp_backdrop = Image::make($new_backdrop_uploaded)->resize(1000,600)->encode('webp', 75);
            Storage::disk('public')->put($webp_backdrop_path_relative, (string) $img_webp_backdrop);

            $movies->backdrop = $original_backdrop_path_relative; // DB'ye orijinalin yeni göreli yolunu kaydet

        } elseif ($backdrop_url_provided) {
            $backdrop_url = $request->movie_image_url;
             try {
                $backdrop_contents = file_get_contents($backdrop_url);
                if ($backdrop_contents === false) {
                    throw new \Exception("Görsel içeriği alınamadı.");
                }
                $backdrop_extension = pathinfo($backdrop_url, PATHINFO_EXTENSION) ?: 'jpg';
                 if (!in_array(strtolower($backdrop_extension), ['jpeg', 'jpg', 'png', 'gif'])) {
                    $backdrop_extension = 'jpg';
                }

                $original_backdrop_name_from_url = 'movie_image_url_'.$movies->id.'.'.$backdrop_extension;
                $webp_backdrop_name_from_url = 'movie_image_url_'.$movies->id.'.webp';

                $original_backdrop_path_relative_from_url = 'assets/movies/backdrop/'.$original_backdrop_name_from_url;
                $webp_backdrop_path_relative_from_url = 'assets/movies/backdrop/'.$webp_backdrop_name_from_url;

                // Save original image from URL to storage
                $img_original_backdrop_url = Image::make($backdrop_contents)->resize(1000,600)->encode($backdrop_extension, 75);
                Storage::disk('public')->put($original_backdrop_path_relative_from_url, (string) $img_original_backdrop_url);

                // Save WebP version from URL to storage
                $img_webp_backdrop_url = Image::make($backdrop_contents)->resize(1000,600)->encode('webp', 75);
                Storage::disk('public')->put($webp_backdrop_path_relative_from_url, (string) $img_webp_backdrop_url);

                $movies->backdrop = $original_backdrop_path_relative_from_url; // DB'ye orijinalin yeni göreli yolunu kaydet
            } catch (\Exception $e) {
                // Toastr()->error('Backdrop URL görseli işlenirken bir hata oluştu: '.$e->getMessage());
            }
        }
        
        $movies->save(); // Poster ve backdrop yollarını kaydet

        //Movies Actors
        if(isset($request->movie_actors)){
            $movie_actors_arr = explode(",", $request->movie_actors);
            foreach($movie_actors_arr as $actor) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$actor.'?language='.config('services.tmdb.lang'))
                ->json();
                $checkactor = Persons::where('tmdb_id', '=', $actor)->first();
                if (empty($checkactor)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];

                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();
                    $movies->actors()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->actors()->sync($checkactor->id, false);
                    $movies->persons()->sync($checkactor->id, false);
                }
            }
        }

        //Movies Directors
        if(isset($request->movie_directors)){
            $movie_directors_arr = explode(",", $request->movie_directors);
            foreach($movie_directors_arr as $director) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$director.'?language='.config('services.tmdb.lang'))
                ->json();

                $checkdirector = Persons::where('tmdb_id', '=', $director)->first();
                if (empty($checkdirector)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];

                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();
                    $movies->directors()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->directors()->sync($checkdirector->id, true);
                    $movies->persons()->sync($checkdirector->id, false);
                }
            }
        }

        //Movies Writers
        if(isset($request->movie_writers)){
            $movie_writers_arr = explode(",", $request->movie_writers);
            foreach($movie_writers_arr as $writer) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$writer.'?language='.config('services.tmdb.lang'))
                ->json();

                $checkwriter = Persons::where('tmdb_id', '=', $writer)->first();
                if (empty($checkwriter)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];

                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();
                    $movies->writers()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->writers()->sync($checkwriter->id, false);
                    $movies->persons()->sync($checkwriter->id, false);
                }
            }
        }

        //Genres
        if(isset($request->movie_genres)){
            $movie_genres_arr = explode(",", $request->movie_genres);
            $movies->genres()->sync($movie_genres_arr, false);
        }
        //End Genres

        //Keywords
        if(isset($request->movie_keywords)){
            $movie_keywords_arr = explode(",", $request->movie_keywords);
            foreach($movie_keywords_arr as $keywords) {
                $checkkeywords = Keywords::where('name', '=', $keywords)->first();
                if (empty($checkkeywords)) {
                    $keywordsId = Keywords::create(['name' => $keywords])->id;
                    $movies->keywords()->sync($keywordsId, false);
                }else{
                    $movies->keywords()->sync($checkkeywords->id, false);
                }
            }
        }
        //End Keywords

        //Years
        if(isset($request->movie_release_date)){
            $years = date('Y', strtotime($request->movie_release_date));
            $checkyears = Years::where('name', '=', $years)->first();
            if (empty($checkyears)) {
                $yearsId = Years::create(['name' => $years])->id;
                $movies->years()->sync($yearsId, false);
            }else{
                $movies->years()->sync($checkyears->id, false);
            }
        }
        //End Years

        //Qualities
        if(isset($request->movie_quality)){
            $movie_qualities_arr = explode(",", $request->movie_quality);
            foreach($movie_qualities_arr as $qualities) {
                $checkqualities = Qualities::where('name', '=', $qualities)->first();
                if (empty($checkqualities)) {
                    $qualitiesId = Qualities::create(['name' => $qualities])->id;
                    $movies->qualities()->sync($qualitiesId, false);
                }else{
                    $movies->qualities()->sync($checkqualities->id, false);
                }
            }
        }
        //End Qualities

        //Countries
        if(isset($request->movie_countries)){
            $movie_countries_arr = explode(",", $request->movie_countries);
            foreach($movie_countries_arr as $countries) {
                $checkcountries = Countries::where('name', '=', $countries)->first();
                if (empty($checkcountries)) {
                    $countriesId = Countries::create(['name' => $countries])->id;
                    $movies->countries()->sync($countriesId, false);
                }else{
                    $movies->countries()->sync($checkcountries->id, false);
                }
            }
        }
        //End Countries

        return redirect()->action([MoviesController::class,'index'])->with('success','Movie Created Successfully');
    }

    //Update Movies
    public function update(Request $request, $id){
        $movies = Items::find($id);

        $this->validate($request,[
            'movie_name' => 'required|unique:items,title,'.$movies->id,
            'movie_id' => 'nullable|unique:items,tmdb_id,'.$movies->id,
        ]);

        $movies->actors()->detach();
        $movies->directors()->detach();
        $movies->writers()->detach();
        $movies->genres()->detach();
        $movies->qualities()->detach();
        $movies->countries()->detach();
        $movies->keywords()->detach();
        $movies->years()->detach();

        $movies->type = 'movies';
        $movies->tmdb_id = $request->movie_id;
        $movies->imdb_id = $request->imdb_id;
        $movies->title = $request->movie_name;
        $movies->slug = Str::slug($request->movie_name);
        $movies->tagline = $request->movie_tagline;
        $movies->overviews = $request->movie_description;
        $movies->duration = $request->movie_duration;
        $movies->rating = $request->movie_rating;
        $movies->release_date = $request->movie_release_date;
        $movies->trailer = $request->movie_trailer;
        $movies->recommended = $request->has('recommended_status') ? $request->recommended_status : 0;
        $movies->platform_id = $request->platform_id;

        $type["type"] = $request->player_type;
        $name["name"] = $request->player_name;
        $url["url"] = $request->player_url;
        $player = array_merge_recursive($type,$name,$url);
        $movies->player = json_encode($player);

        $download = array_combine($request->download_name, $request->download_url);
        $firstDownloadKey = array_key_first($download);
        if($firstDownloadKey!= "" ){
            $movies->download = json_encode($download);
        }

        $movies->save();

        //Movies Poster
        $new_poster_uploaded = $request->file('movie_poster');
        $poster_url_provided = $request->filled('movie_poster_url');

        if ($new_poster_uploaded) {
            // Delete old poster files
            if ($movies->poster) {
                $old_original_poster_path = $movies->poster;
                $old_webp_poster_path = Str::replaceLast(pathinfo($old_original_poster_path, PATHINFO_EXTENSION), 'webp', $old_original_poster_path);
                if (Storage::disk('public')->exists($old_original_poster_path)) {
                    Storage::disk('public')->delete($old_original_poster_path);
                }
                if (Storage::disk('public')->exists($old_webp_poster_path)) {
                    Storage::disk('public')->delete($old_webp_poster_path);
                }
            }

            $extension_poster = $new_poster_uploaded->getClientOriginalExtension();
            $original_poster_name = 'movie_poster_'.$movies->id.'_updated_'.time().'.'.$extension_poster;
            $webp_poster_name = 'movie_poster_'.$movies->id.'_updated_'.time().'.webp';

            $original_poster_path_relative = 'assets/movies/poster/'.$original_poster_name;
            $webp_poster_path_relative = 'assets/movies/poster/'.$webp_poster_name;

            $img_original_poster = Image::make($new_poster_uploaded)->resize(405,600)->encode($extension_poster, 80);
            Storage::disk('public')->put($original_poster_path_relative, (string) $img_original_poster);

            $img_webp_poster = Image::make($new_poster_uploaded)->resize(405,600)->encode('webp', 80);
            Storage::disk('public')->put($webp_poster_path_relative, (string) $img_webp_poster);
            
            $movies->poster = $original_poster_path_relative;

        } else if ($poster_url_provided && $request->movie_poster_url !== $movies->getRawOriginal('poster_url_field_if_exists')) { // Varsayımsal bir alan adı, URL'den yüklenenin değişip değişmediğini kontrol etmek için
            // Delete old poster files if they came from a previous URL or upload
            if ($movies->poster) {
                 $old_original_poster_path = $movies->poster;
                 $old_webp_poster_path = Str::replaceLast(pathinfo($old_original_poster_path, PATHINFO_EXTENSION), 'webp', $old_original_poster_path);
                if (Storage::disk('public')->exists($old_original_poster_path)) {
                    Storage::disk('public')->delete($old_original_poster_path);
                }
                if (Storage::disk('public')->exists($old_webp_poster_path)) {
                    Storage::disk('public')->delete($old_webp_poster_path);
                }
            }
            
            $poster_url = $request->movie_poster_url;
            try {
                $poster_contents = file_get_contents($poster_url);
                 if ($poster_contents === false) {
                    throw new \Exception("Görsel içeriği alınamadı.");
                }
                $poster_extension = pathinfo($poster_url, PATHINFO_EXTENSION) ?: 'jpg';
                if (!in_array(strtolower($poster_extension), ['jpeg', 'jpg', 'png', 'gif'])) {
                    $poster_extension = 'jpg';
                }

                $original_poster_name_from_url = 'movie_poster_url_'.$movies->id.'_updated_'.time().'.'.$poster_extension;
                $webp_poster_name_from_url = 'movie_poster_url_'.$movies->id.'_updated_'.time().'.webp';

                $original_poster_path_relative_from_url = 'assets/movies/poster/'.$original_poster_name_from_url;
                $webp_poster_path_relative_from_url = 'assets/movies/poster/'.$webp_poster_name_from_url;

                $img_original_poster_url = Image::make($poster_contents)->resize(405,600)->encode($poster_extension, 80);
                Storage::disk('public')->put($original_poster_path_relative_from_url, (string) $img_original_poster_url);

                $img_webp_poster_url = Image::make($poster_contents)->resize(405,600)->encode('webp', 80);
                Storage::disk('public')->put($webp_poster_path_relative_from_url, (string) $img_webp_poster_url);

                $movies->poster = $original_poster_path_relative_from_url;
            } catch (\Exception $e) {
                // Toastr()->error('Poster URL görseli işlenirken bir hata oluştu: '.$e->getMessage());
            }
        }

        //Movies Backdrop
        $new_backdrop_uploaded = $request->file('movie_image');
        $backdrop_url_provided = $request->filled('movie_image_url');

        if ($new_backdrop_uploaded) {
            // Delete old backdrop files
            if ($movies->backdrop) {
                $old_original_backdrop_path = $movies->backdrop;
                $old_webp_backdrop_path = Str::replaceLast(pathinfo($old_original_backdrop_path, PATHINFO_EXTENSION), 'webp', $old_original_backdrop_path);
                 if (Storage::disk('public')->exists($old_original_backdrop_path)) {
                    Storage::disk('public')->delete($old_original_backdrop_path);
                }
                if (Storage::disk('public')->exists($old_webp_backdrop_path)) {
                    Storage::disk('public')->delete($old_webp_backdrop_path);
                }
            }

            $extension_backdrop = $new_backdrop_uploaded->getClientOriginalExtension();
            $original_backdrop_name = 'movie_image_'.$movies->id.'_updated_'.time().'.'.$extension_backdrop;
            $webp_backdrop_name = 'movie_image_'.$movies->id.'_updated_'.time().'.webp';

            $original_backdrop_path_relative = 'assets/movies/backdrop/'.$original_backdrop_name;
            $webp_backdrop_path_relative = 'assets/movies/backdrop/'.$webp_backdrop_name;

            $img_original_backdrop = Image::make($new_backdrop_uploaded)->resize(1000,600)->encode($extension_backdrop, 75);
            Storage::disk('public')->put($original_backdrop_path_relative, (string) $img_original_backdrop);

            $img_webp_backdrop = Image::make($new_backdrop_uploaded)->resize(1000,600)->encode('webp', 75);
            Storage::disk('public')->put($webp_backdrop_path_relative, (string) $img_webp_backdrop);

            $movies->backdrop = $original_backdrop_path_relative;

        } elseif ($backdrop_url_provided && $request->movie_image_url !== $movies->getRawOriginal('backdrop_url_field_if_exists')) { // Varsayımsal
             // Delete old backdrop files
            if ($movies->backdrop) {
                $old_original_backdrop_path = $movies->backdrop;
                $old_webp_backdrop_path = Str::replaceLast(pathinfo($old_original_backdrop_path, PATHINFO_EXTENSION), 'webp', $old_original_backdrop_path);
                if (Storage::disk('public')->exists($old_original_backdrop_path)) {
                    Storage::disk('public')->delete($old_original_backdrop_path);
                }
                if (Storage::disk('public')->exists($old_webp_backdrop_path)) {
                    Storage::disk('public')->delete($old_webp_backdrop_path);
                }
            }

            $backdrop_url = $request->movie_image_url;
            try {
                $backdrop_contents = file_get_contents($backdrop_url);
                if ($backdrop_contents === false) {
                    throw new \Exception("Görsel içeriği alınamadı.");
                }
                $backdrop_extension = pathinfo($backdrop_url, PATHINFO_EXTENSION) ?: 'jpg';
                if (!in_array(strtolower($backdrop_extension), ['jpeg', 'jpg', 'png', 'gif'])) {
                    $backdrop_extension = 'jpg';
                }

                $original_backdrop_name_from_url = 'movie_image_url_'.$movies->id.'_updated_'.time().'.'.$backdrop_extension;
                $webp_backdrop_name_from_url = 'movie_image_url_'.$movies->id.'_updated_'.time().'.webp';

                $original_backdrop_path_relative_from_url = 'assets/movies/backdrop/'.$original_backdrop_name_from_url;
                $webp_backdrop_path_relative_from_url = 'assets/movies/backdrop/'.$webp_backdrop_name_from_url;

                $img_original_backdrop_url = Image::make($backdrop_contents)->resize(1000,600)->encode($backdrop_extension, 75);
                Storage::disk('public')->put($original_backdrop_path_relative_from_url, (string) $img_original_backdrop_url);

                $img_webp_backdrop_url = Image::make($backdrop_contents)->resize(1000,600)->encode('webp', 75);
                Storage::disk('public')->put($webp_backdrop_path_relative_from_url, (string) $img_webp_backdrop_url);

                $movies->backdrop = $original_backdrop_path_relative_from_url;
            } catch (\Exception $e) {
                // Toastr()->error('Backdrop URL görseli işlenirken bir hata oluştu: '.$e->getMessage());
            }
        }
        
        $movies->save();

        //Movies Actors
        if(isset($request->movie_actors)){
            $movie_actors_arr = explode(",", $request->movie_actors);
            foreach($movie_actors_arr as $actor) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$actor.'?language='.config('services.tmdb.lang'))
                ->json();
                $checkactor = Persons::where('tmdb_id', '=', $actor)->first();
                if (empty($checkactor)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];
                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();

                    $movies->actors()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->actors()->sync($checkactor->id, false);
                    $movies->persons()->sync($checkactor->id, false);
                }
            }
        }

        //Movies Directors
        if(isset($request->movie_directors)){
            $movie_directors_arr = explode(",", $request->movie_directors);
            foreach($movie_directors_arr as $director) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$director.'?language='.config('services.tmdb.lang'))
                ->json();

                $checkdirector = Persons::where('tmdb_id', '=', $director)->first();
                if (empty($checkdirector)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];
                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();
                    $movies->directors()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->directors()->sync($checkdirector->id, false);
                    $movies->persons()->sync($checkdirector->id, false);
                }
            }
        }

        //Movies Writers
        if(isset($request->movie_writers)){
            $movie_writers_arr = explode(",", $request->movie_writers);
            foreach($movie_writers_arr as $writer) {
                $data = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/person/'.$writer.'?language='.config('services.tmdb.lang'))
                ->json();

                $checkwriter = Persons::where('tmdb_id', '=', $writer)->first();
                if (empty($checkwriter)) {
                    $person = new Persons();
                    $person->tmdb_id = $data['id'];
                    $person->imdb_id = $data['imdb_id'];
                    $person->name = $data['name'];
                    $person->gender = $data['gender'];
                    $person->biography = $data['biography'];
                    $person->birthday = $data['birthday'];
                    $person->deathday = $data['deathday'];
                    $person->homepage = $data['homepage'];
                    $person->known_for_department = $data['known_for_department'];
                    $person->place_of_birth = $data['place_of_birth'];
                    $person->popularity = $data['popularity'];
                    $persons_poster = $data['profile_path'];
                    $persons_poster_url = "https://image.tmdb.org/t/p/original".$persons_poster;
                    if($persons_poster == ''){
                        $person->profile_path = 'default_person.jpg';
                    }else{
                        $file_persons_poster = $data['id'].'.jpg';
                        Image::make($persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                        $person->profile_path = $file_persons_poster;
                    }
                    $person->save();
                    $movies->writers()->sync($person->id, false);
                    $movies->persons()->sync($person->id, false);
                }else{
                    $movies->writers()->sync($checkwriter->id, false);
                    $movies->persons()->sync($checkwriter->id, false);
                }
            }
        }

        //Genres
        if(isset($request->movie_genres)){
            $movie_genres_arr = explode(",", $request->movie_genres);
            $movies->genres()->sync($movie_genres_arr, false);
        }
        //End Genres

        //Keywords
        if(isset($request->movie_keywords)){
            $movie_keywords_arr = explode(",", $request->movie_keywords);
            foreach($movie_keywords_arr as $keywords) {
                $checkkeywords = Keywords::where('name', '=', $keywords)->first();
                if (empty($checkkeywords)) {
                    $keywordsId = Keywords::create(['name' => $keywords])->id;
                    $movies->keywords()->sync($keywordsId, false);
                }else{
                    $movies->keywords()->sync($checkkeywords->id, false);
                }
            }
        }
        //End Keywords

        //Years
        if(isset($request->movie_release_date)){
            $years = date('Y', strtotime($request->movie_release_date));
            $checkyears = Years::where('name', '=', $years)->first();
            if (empty($checkyears)) {
                $yearsId = Years::create(['name' => $years])->id;
                $movies->years()->sync($yearsId, false);
            }else{
                $movies->years()->sync($checkyears->id, false);
            }
        }
        //End Years

        //Qualities
        if(isset($request->movie_quality)){
            $movie_qualities_arr = explode(",", $request->movie_quality);
            foreach($movie_qualities_arr as $qualities) {
                $checkqualities = Qualities::where('name', '=', $qualities)->first();
                if (empty($checkqualities)) {
                    $qualitiesId = Qualities::create(['name' => $qualities])->id;
                    $movies->qualities()->sync($qualitiesId, false);
                }else{
                    $movies->qualities()->sync($checkqualities->id, false);
                }
            }
        }
        //End Qualities

        //Countries
        if(isset($request->movie_countries)){
            $movie_countries_arr = explode(",", $request->movie_countries);
            foreach($movie_countries_arr as $countries) {
                $checkcountries = Countries::where('name', '=', $countries)->first();
                if (empty($checkcountries)) {
                    $countriesId = Countries::create(['name' => $countries])->id;
                    $movies->countries()->sync($countriesId, false);
                }else{
                    $movies->countries()->sync($checkcountries->id, false);
                }
            }
        }
        //End Countries

        return redirect()->action([MoviesController::class,'index'])->with('success','Movie Updated Successfully');
    }

    //Delete Movies
    public function destroy($id){
        $ids = trim($id, '[]');
        $moviesid = explode(",",$ids);
        $movies = Items::whereIn('id', $moviesid)->get();
        foreach ($movies as $movie) {
            //Delete movies
            $movie->delete();
            if($movie->poster != 'default_poster.jpg'){
                $image_path = public_path('/assets/movies/poster/'.$movie->poster);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            if($movie->backdrop != 'default_backdrop.jpg'){
                $image_path = public_path('/assets/movies/backdrop/'.$movie->backdrop);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        }
        return redirect()->action([MoviesController::class,'index'])->with('success','Movies Deleted Successfully!');
    }

    //CODE Get Movies Data
    public function get_movie_data($movie_id){
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$movie_id.'?language='.config('services.tmdb.lang'))
        ->json();

        $cast = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$movie_id.'/credits?language='.config('services.tmdb.lang'))
        ->json();

        $video = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$movie_id.'/videos?language='.config('services.tmdb.lang'))
        ->json();

        $keywords = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/movie/'.$movie_id.'/keywords?language='.config('services.tmdb.lang'))
        ->json();

        return $data+$cast+$video+$keywords;
    }

    //Check if Already Added Movie
    public function check_tmdb($movie_id){
        $data = Items::where('tmdb_id', $movie_id)->get();
        return count($data);
    }

    //Change Visible
    public function visible(Request $request){
        $items = Items::find($request->id);
        if ($items->visible == 1) {
            $items->visible = 0;
        }else{
            $items->visible = 1;
        }
        $items->save();
    }

    //Change Feature
    public function feature(Request $request){
        $items = Items::find($request->id);
        if ($items->feature == 1) {
            $items->feature = 0;
        }else{
            $items->feature = 1;
        }
        $items->save();
    }

    //Change Recommended
    public function recommended(Request $request){
        $items = Items::find($request->id);
        if ($items->recommended == 1) {
            $items->recommended = 0;
        }else{
            $items->recommended = 1;
        }
        $items->save();
    }

}
