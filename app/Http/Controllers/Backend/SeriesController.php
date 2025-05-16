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

class SeriesController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_series = Items::where('type', 'series')->get();
        if(!empty($request->search)){
            $data = Items::where([
                ['title', 'LIKE', '%' . $request->search . '%']
            ])->where('type','series')->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.series.lists',compact('data','total_series'));
        }else{
            $data = Items::orderBy('id', 'DESC')->where('type','series')->paginate(10)->onEachSide(1);
            return view('backend.series.lists',compact('data','total_series'));
        }
    }

    public function add(){
        $platforms = Platform::orderBy('name')->get();
        return view('backend.series.add', compact('platforms'));
    }

    //Display Series Edit
 	public function edit($id){
        $series = Items::find($id);
        $platforms = Platform::orderBy('name')->get();
        $persons = Actors::where('items_id',$id);
        $download = json_decode($series->download,true);
        return view('backend.series.edit',compact('series','persons','download', 'platforms'));
    }

    //Add Series
    public function store(Request $request){
        $this->validate($request,[
            'series_name' => 'required|unique:items,title',
            'series_id' => 'nullable|unique:items,tmdb_id',
        ]);

        $series = new Items();
        $series->type = 'series';
        $series->tmdb_id = $request->series_id;
        $series->imdb_id = $request->series_id;
        $series->title = $request->series_name;
        $series->slug = Str::slug($request->series_name);
        $series->tagline = $request->series_tagline;
        $series->overviews = $request->series_description;
        $series->duration = $request->series_duration;
        $series->rating = $request->series_rating;
        $series->release_date = $request->series_release_date;
        $series->trailer = $request->series_trailer;
        $series->views = 0;
        $series->visible = 1;
        $series->feature = 0;
        $series->recommended = $request->has('recommended_status') ? $request->recommended_status : 0;
        $series->platform_id = $request->platform_id;

        $download = array_combine($request->download_name, $request->download_url);
        $firstDownloadKey = array_key_first($download);
        if($firstDownloadKey!= "" ){
            $series->download = json_encode($download);
        }

        $series->save();

        //Series Poster
        $new_poster_uploaded = $request->file('series_poster');
        $poster_url_provided = $request->filled('series_poster_url');

        if ($new_poster_uploaded) {
            $extension_poster = $new_poster_uploaded->getClientOriginalExtension();
                $file_series_poster = 'series_poster_'.$series->id.'.'.$extension_poster;
            Image::make($new_poster_uploaded)->resize(405,600)->save(public_path('/assets/series/poster/'.$file_series_poster));
            // Eski posteri sil (default değilse)
            if ($series->poster && $series->poster != 'default_poster.jpg' && File::exists(public_path('/assets/series/poster/'.$series->poster))) {
                File::delete(public_path('/assets/series/poster/'.$series->poster));
            }
            $series->poster = $file_series_poster;
        } elseif ($poster_url_provided) {
            try {
                $file_series_poster = 'series_poster_'.$series->id.'.jpg'; // Dışarıdan gelen resimler için .jpg varsayalım
                Image::make($request->series_poster_url)->resize(405,600)->save(public_path('/assets/series/poster/'.$file_series_poster));
                // Eski posteri sil (default değilse)
                if ($series->poster && $series->poster != 'default_poster.jpg' && $series->poster != $file_series_poster && File::exists(public_path('/assets/series/poster/'.$series->poster))) {
                    File::delete(public_path('/assets/series/poster/'.$series->poster));
                }
                $series->poster = $file_series_poster;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut poster korunur
            }
        }

        //Series Backdrop
        $new_backdrop_uploaded = $request->file('series_image');
        $backdrop_url_provided = $request->filled('series_image_url');

        if ($new_backdrop_uploaded) {
            $extension_image = $new_backdrop_uploaded->getClientOriginalExtension();
                $file_series_image = 'series_image_'.$series->id.'.'.$extension_image;
            Image::make($new_backdrop_uploaded)->resize(1000,600)->save(public_path('/assets/series/backdrop/'.$file_series_image));
            if ($series->backdrop && $series->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/series/backdrop/'.$series->backdrop))) {
                File::delete(public_path('/assets/series/backdrop/'.$series->backdrop));
            }
            $series->backdrop = $file_series_image;
        } elseif ($backdrop_url_provided) {
            try {
                $file_series_image = 'series_image_'.$series->id.'.jpg';
                Image::make($request->series_image_url)->resize(1000,600)->save(public_path('/assets/series/backdrop/'.$file_series_image));
                if ($series->backdrop && $series->backdrop != 'default_backdrop.jpg' && $series->backdrop != $file_series_image && File::exists(public_path('/assets/series/backdrop/'.$series->backdrop))) {
                    File::delete(public_path('/assets/series/backdrop/'.$series->backdrop));
                }
                $series->backdrop = $file_series_image;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut backdrop korunur.
            }
        }
        $series->save(); // Resim adları güncellendiyse tekrar kaydet

        //Series Actors
        if(isset($request->series_actors)){
            $series_actors_arr = explode(",", $request->series_actors);
            foreach($series_actors_arr as $actor) {
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
                    $series->actors()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->actors()->sync($checkactor->id, false);
                    $series->persons()->sync($checkactor->id, false);
                }
            }
        }

        //Series Directors
        if(isset($request->series_directors)){
            $series_directors_arr = explode(",", $request->series_directors);
            foreach($series_directors_arr as $director) {
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
                    $series->directors()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->directors()->sync($checkdirector->id, false);
                    $series->persons()->sync($checkdirector->id, false);
                }
            }
        }

        //Series Writers
        if(isset($request->series_writers)){
            $series_writers_arr = explode(",", $request->series_writers);
            foreach($series_writers_arr as $writer) {
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
                    $series->writers()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->writers()->sync($checkwriter->id, false);
                    $series->persons()->sync($checkwriter->id, false);
                }
            }
        }

        //Genres
        if(isset($request->series_genres)){
            $series_genres_arr = explode(",", $request->series_genres);
            $series->genres()->sync($series_genres_arr, false);
        }
        //End Genres

        //Keywords
        if(isset($request->series_keywords)){
            $series_keywords_arr = explode(",", $request->series_keywords);
            foreach($series_keywords_arr as $keywords) {
                $checkkeywords = Keywords::where('name', '=', $keywords)->first();
                if (empty($checkkeywords)) {
                    $keywordsId = Keywords::create(['name' => $keywords])->id;
                    $series->keywords()->sync($keywordsId, false);
                }else{
                    $series->keywords()->sync($checkkeywords->id, false);
                }
            }
        }
        //End Keywords

        //Years
        if(isset($request->series_release_date)){
            $years = date('Y', strtotime($request->series_release_date));
            $checkyears = Years::where('name', '=', $years)->first();
            if (empty($checkyears)) {
                $yearsId = Years::create(['name' => $years])->id;
                $series->years()->sync($yearsId, false);
            }else{
                $series->years()->sync($checkyears->id, false);
            }
        }
        //End Years

        //Qualities
        if(isset($request->series_quality)){
            $series_qualities_arr = explode(",", $request->series_quality);
            foreach($series_qualities_arr as $qualities) {
                $checkqualities = Qualities::where('name', '=', $qualities)->first();
                if (empty($checkqualities)) {
                    $qualitiesId = Qualities::create(['name' => $qualities])->id;
                    $series->qualities()->sync($qualitiesId, false);
                }else{
                    $series->qualities()->sync($checkqualities->id, false);
                }
            }
        }
        //End Qualities

        //Countries
        if(isset($request->series_countries)){
            $series_countries_arr = explode(",", $request->series_countries);
            foreach($series_countries_arr as $countries) {
                $checkcountries = Countries::where('name', '=', $countries)->first();
                if (empty($checkcountries)) {
                    $countriesId = Countries::create(['name' => $countries])->id;
                    $series->countries()->sync($countriesId, false);
                }else{
                    $series->countries()->sync($checkcountries->id, false);
                }
            }
        }
        //End Countries

        return redirect()->action([SeriesController::class,'index'])->with('success','Series Created Successfully');
    }

    //Update Series
    public function update(Request $request, $id){
        $series = Items::find($id);

        $this->validate($request,[
            'series_name' => 'required|unique:items,title,'.$series->id,
            'series_id' => 'nullable|unique:items,tmdb_id,'.$series->id,
        ]);

        $series->actors()->detach();
        $series->directors()->detach();
        $series->writers()->detach();
        $series->genres()->detach();
        $series->qualities()->detach();
        $series->countries()->detach();
        $series->keywords()->detach();
        $series->years()->detach();

        $series->type = 'series';
        $series->tmdb_id = $request->series_id;
        $series->imdb_id = $request->imdb_id;
        $series->title = $request->series_name;
        $series->slug = Str::slug($request->series_name);
        $series->tagline = $request->series_tagline;
        $series->overviews = $request->series_description;
        $series->duration = $request->series_duration;
        $series->rating = $request->series_rating;
        $series->release_date = $request->series_release_date;
        $series->trailer = $request->series_trailer;
        $series->recommended = $request->has('recommended_status') ? $request->recommended_status : 0;
        $series->platform_id = $request->platform_id;
        $series->save();

        //Series Poster
        $new_poster_uploaded = $request->file('series_poster');
        $poster_url_provided = $request->filled('series_poster_url');

        if ($new_poster_uploaded) {
            $extension_poster = $new_poster_uploaded->getClientOriginalExtension();
                $file_series_poster = 'series_poster_'.$series->id.'.'.$extension_poster;
            Image::make($new_poster_uploaded)->resize(405,600)->save(public_path('/assets/series/poster/'.$file_series_poster));
            // Eski posteri sil (default değilse)
            if ($series->poster && $series->poster != 'default_poster.jpg' && File::exists(public_path('/assets/series/poster/'.$series->poster))) {
                File::delete(public_path('/assets/series/poster/'.$series->poster));
            }
            $series->poster = $file_series_poster;
        } elseif ($poster_url_provided) {
            try {
                $file_series_poster = 'series_poster_'.$series->id.'.jpg'; // Dışarıdan gelen resimler için .jpg varsayalım
                Image::make($request->series_poster_url)->resize(405,600)->save(public_path('/assets/series/poster/'.$file_series_poster));
                // Eski posteri sil (default değilse)
                if ($series->poster && $series->poster != 'default_poster.jpg' && $series->poster != $file_series_poster && File::exists(public_path('/assets/series/poster/'.$series->poster))) {
                    File::delete(public_path('/assets/series/poster/'.$series->poster));
                }
                $series->poster = $file_series_poster;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut poster korunur
            }
        }

        //Series Backdrop
        $new_backdrop_uploaded = $request->file('series_image');
        $backdrop_url_provided = $request->filled('series_image_url');

        if ($new_backdrop_uploaded) {
            $extension_image = $new_backdrop_uploaded->getClientOriginalExtension();
                $file_series_image = 'series_image_'.$series->id.'.'.$extension_image;
            Image::make($new_backdrop_uploaded)->resize(1000,600)->save(public_path('/assets/series/backdrop/'.$file_series_image));
            if ($series->backdrop && $series->backdrop != 'default_backdrop.jpg' && File::exists(public_path('/assets/series/backdrop/'.$series->backdrop))) {
                File::delete(public_path('/assets/series/backdrop/'.$series->backdrop));
            }
            $series->backdrop = $file_series_image;
        } elseif ($backdrop_url_provided) {
            try {
                $file_series_image = 'series_image_'.$series->id.'.jpg';
                Image::make($request->series_image_url)->resize(1000,600)->save(public_path('/assets/series/backdrop/'.$file_series_image));
                if ($series->backdrop && $series->backdrop != 'default_backdrop.jpg' && $series->backdrop != $file_series_image && File::exists(public_path('/assets/series/backdrop/'.$series->backdrop))) {
                    File::delete(public_path('/assets/series/backdrop/'.$series->backdrop));
                }
                $series->backdrop = $file_series_image;
            } catch (\Intervention\Image\Exception\NotReadableException $e) {
                // Hata durumunda mevcut backdrop korunur.
            }
        }
        $series->save(); // Resim adları güncellendiyse tekrar kaydet

        //Series Actors
        if(isset($request->series_actors)){
            $series_actors_arr = explode(",", $request->series_actors);
            foreach($series_actors_arr as $actor) {
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
                    $series->actors()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->actors()->sync($checkactor->id, false);
                    $series->persons()->sync($checkactor->id, false);
                }
            }
        }

        //Series Directors
        if(isset($request->series_directors)){
            $series_directors_arr = explode(",", $request->series_directors);
            foreach($series_directors_arr as $director) {
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
                    $series->directors()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->directors()->sync($checkdirector->id, false);
                    $series->persons()->sync($checkdirector->id, false);
                }
            }
        }

        //Series Writers
        if(isset($request->series_writers)){
            $series_writers_arr = explode(",", $request->series_writers);
            foreach($series_writers_arr as $writer) {
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
                    $series->writers()->sync($person->id, false);
                    $series->persons()->sync($person->id, false);
                }else{
                    $series->writers()->sync($checkwriter->id, false);
                    $series->persons()->sync($checkwriter->id, false);
                }
            }
        }

        //Genres
        if(isset($request->series_genres)){
            $series_genres_arr = explode(",", $request->series_genres);
            $series->genres()->sync($series_genres_arr, false);
        }
        //End Genres

        //Keywords
        if(isset($request->series_keywords)){
            $series_keywords_arr = explode(",", $request->series_keywords);
            foreach($series_keywords_arr as $keywords) {
                $checkkeywords = Keywords::where('name', '=', $keywords)->first();
                if (empty($checkkeywords)) {
                    $keywordsId = Keywords::create(['name' => $keywords])->id;
                    $series->keywords()->sync($keywordsId, false);
                }else{
                    $series->keywords()->sync($checkkeywords->id, false);
                }
            }
        }
        //End Keywords

        //Years
        if(isset($request->series_release_date)){
            $years = date('Y', strtotime($request->series_release_date));
            $checkyears = Years::where('name', '=', $years)->first();
            if (empty($checkyears)) {
                $yearsId = Years::create(['name' => $years])->id;
                $series->years()->sync($yearsId, false);
            }else{
                $series->years()->sync($checkyears->id, false);
            }
        }
        //End Years

        //Qualities
        if(isset($request->series_quality)){
            $series_qualities_arr = explode(",", $request->series_quality);
            foreach($series_qualities_arr as $qualities) {
                $checkqualities = Qualities::where('name', '=', $qualities)->first();
                if (empty($checkqualities)) {
                    $qualitiesId = Qualities::create(['name' => $qualities])->id;
                    $series->qualities()->sync($qualitiesId, false);
                }else{
                    $series->qualities()->sync($checkqualities->id, false);
                }
            }
        }
        //End Qualities

        //Countries
        if(isset($request->series_countries)){
            $series_countries_arr = explode(",", $request->series_countries);
            foreach($series_countries_arr as $countries) {
                $checkcountries = Countries::where('name', '=', $countries)->first();
                if (empty($checkcountries)) {
                    $countriesId = Countries::create(['name' => $countries])->id;
                    $series->countries()->sync($countriesId, false);
                }else{
                    $series->countries()->sync($checkcountries->id, false);
                }
            }
        }
        //End Countries

        return redirect()->action([SeriesController::class,'index'])->with('success','Series Updated Successfully');
    }

    //Delete Series
    public function destroy($id){
        $ids = trim($id, '[]');
        $seriesid = explode(",",$ids);
        $series = Items::whereIn('id', $seriesid)->get();
        foreach ($series as $serie) {
            //Delete series
            $serie->delete();
            if($serie->poster != 'default_poster.jpg'){
                $image_path = public_path('/assets/series/poster/'.$serie->poster);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            if($serie->backdrop != 'default_backdrop.jpg'){
                $image_path = public_path('/assets/series/backdrop/'.$serie->backdrop);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        }
        return redirect()->action([SeriesController::class,'index'])->with('success','Series Deleted Successfully!');
    }

    //CODE Get Series Data
    public function get_series_data($series_id){
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series_id.'?language='.config('services.tmdb.lang'))
        ->json();

        $cast = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series_id.'/credits?language='.config('services.tmdb.lang'))
        ->json();

        $keywords = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series_id.'/keywords?language='.config('services.tmdb.lang'))
        ->json('results');
        $keyword = ['keywords' => $keywords];

        $videos = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/'.$series_id.'/videos?language='.config('services.tmdb.lang'))
        ->json('results');
        $video = ['video' => $videos];

        return $data+$cast+$video+$keyword;
    }

    //Check if Already Added Series
    public function check_tmdb($series_id){
        $data = Items::where('tmdb_id', $series_id)->get();
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
