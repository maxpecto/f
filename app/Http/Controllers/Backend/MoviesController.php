<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                $file_movie_poster = 'movie_poster_'.$movies->id.'.'.$extension_poster;
            Image::make($new_poster_uploaded)->resize(405,600)->save(public_path('/assets/movies/poster/'.$file_movie_poster));
            if ($movies->poster && $movies->poster != 'default_poster.jpg' && File::exists(public_path('/assets/movies/poster/'.$movies->poster))) {
                File::delete(public_path('/assets/movies/poster/'.$movies->poster));
            }
            $movies->poster = $file_movie_poster;
        } elseif ($poster_url_provided) {
            try {
                $file_movie_poster = 'movie_poster_'.$movies->id.'.jpg';
                Image::make($request->movie_poster_url)->resize(405,600)->save(public_path('/assets/movies/poster/'.$file_movie_poster));
                if ($movies->poster && $movies->poster != 'default_poster.jpg' && $movies->poster != $file_movie_poster && File::exists(public_path('/assets/movies/poster/'.$movies->poster))) {
                    File::delete(public_path('/assets/movies/poster/'.$movies->poster));
                }
                $movies->poster = $file_movie_poster;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut poster korunur
            }
        }

        //Movies Backdrop
        $new_backdrop_uploaded = $request->file('movie_image');
        $backdrop_url_provided = $request->filled('movie_image_url');

        if ($new_backdrop_uploaded) {
            $extension_image = $new_backdrop_uploaded->getClientOriginalExtension();
                $file_movie_image = 'movie_image_'.$movies->id.'.'.$extension_image;
            Image::make($new_backdrop_uploaded)->resize(1000,600)->save(public_path('/assets/movies/backdrop/'.$file_movie_image));
            if ($movies->backdrop && $movies->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/movies/backdrop/'.$movies->backdrop))) {
                File::delete(public_path('/assets/movies/backdrop/'.$movies->backdrop));
            }
            $movies->backdrop = $file_movie_image;
        } elseif ($backdrop_url_provided) {
            try {
                $file_movie_image = 'movie_image_'.$movies->id.'.jpg';
                Image::make($request->movie_image_url)->resize(1000,600)->save(public_path('/assets/movies/backdrop/'.$file_movie_image));
                if ($movies->backdrop && $movies->backdrop != 'default_backdrop.jpg' && $movies->backdrop != $file_movie_image && File::exists(public_path('/assets/movies/backdrop/'.$movies->backdrop))) {
                    File::delete(public_path('/assets/movies/backdrop/'.$movies->backdrop));
                }
                $movies->backdrop = $file_movie_image;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut backdrop korunur
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
            $extension_poster = $new_poster_uploaded->getClientOriginalExtension();
                $file_movie_poster = 'movie_poster_'.$movies->id.'.'.$extension_poster;
            Image::make($new_poster_uploaded)->resize(405,600)->save(public_path('/assets/movies/poster/'.$file_movie_poster));
            if ($movies->poster && $movies->poster != 'default_poster.jpg' && File::exists(public_path('/assets/movies/poster/'.$movies->poster))) {
                File::delete(public_path('/assets/movies/poster/'.$movies->poster));
            }
            $movies->poster = $file_movie_poster;
        } elseif ($poster_url_provided) {
            try {
                $file_movie_poster = 'movie_poster_'.$movies->id.'.jpg';
                Image::make($request->movie_poster_url)->resize(405,600)->save(public_path('/assets/movies/poster/'.$file_movie_poster));
                if ($movies->poster && $movies->poster != 'default_poster.jpg' && $movies->poster != $file_movie_poster && File::exists(public_path('/assets/movies/poster/'.$movies->poster))) {
                    File::delete(public_path('/assets/movies/poster/'.$movies->poster));
                }
                $movies->poster = $file_movie_poster;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut poster korunur
            }
        }

        //Movies Backdrop
        $new_backdrop_uploaded = $request->file('movie_image');
        $backdrop_url_provided = $request->filled('movie_image_url');

        if ($new_backdrop_uploaded) {
            $extension_image = $new_backdrop_uploaded->getClientOriginalExtension();
                $file_movie_image = 'movie_image_'.$movies->id.'.'.$extension_image;
            Image::make($new_backdrop_uploaded)->resize(1000,600)->save(public_path('/assets/movies/backdrop/'.$file_movie_image));
            if ($movies->backdrop && $movies->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/movies/backdrop/'.$movies->backdrop))) {
                File::delete(public_path('/assets/movies/backdrop/'.$movies->backdrop));
            }
            $movies->backdrop = $file_movie_image;
        } elseif ($backdrop_url_provided) {
            try {
                $file_movie_image = 'movie_image_'.$movies->id.'.jpg';
                Image::make($request->movie_image_url)->resize(1000,600)->save(public_path('/assets/movies/backdrop/'.$file_movie_image));
                if ($movies->backdrop && $movies->backdrop != 'default_backdrop.jpg' && $movies->backdrop != $file_movie_image && File::exists(public_path('/assets/movies/backdrop/'.$movies->backdrop))) {
                    File::delete(public_path('/assets/movies/backdrop/'.$movies->backdrop));
                }
                $movies->backdrop = $file_movie_image;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut backdrop korunur
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
