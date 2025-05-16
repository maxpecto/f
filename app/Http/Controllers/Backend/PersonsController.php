<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Persons;
use Image;
use File;

class PersonsController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_persons = Persons::get();
        if(!empty($request->search)){
            $data = Persons::where([
                ['name', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.persons.lists',compact('data','total_persons'));
        }else{
            $data = Persons::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.persons.lists',compact('data','total_persons'));
        }
    }

    public function add(){
        return view('backend.persons.add');
    }

    public function edit($id){
        $person = Persons::find($id);
        return view('backend.persons.edit',compact('person'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'persons_tmdb_id' => 'required|unique:persons,tmdb_id',
            'persons_name' => 'required',
            'persons_gender' => 'required',
        ]);

        $person = new Persons();
        $person->tmdb_id = $request->persons_tmdb_id;
        $person->imdb_id = $request->persons_imdb_id;
        $person->name = $request->persons_name;
        $person->gender = $request->persons_gender;
        $person->biography = $request->persons_biography;
        $person->birthday = $request->persons_birth_date;
        $person->deathday = $request->persons_death_date;
        $person->homepage = $request->persons_homepage;
        $person->known_for_department = $request->persons_known_for_department;
        $person->place_of_birth = $request->persons_place_of_birth;
        $person->popularity = $request->persons_popularity;

        if($request->persons_poster_url == ''){
            $persons_poster = $request->file('persons_poster');
            if($persons_poster == ''){
                $person->profile_path = 'default_person.jpg';
            }else{
                $extension_poster = $persons_poster->getClientOriginalExtension();
                $file_persons_poster = $request->persons_tmdb_id.'.'.$extension_poster;
                Image::make($persons_poster)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                $person->profile_path = $file_persons_poster;
            }
        }else{
            $file_persons_poster = $request->persons_tmdb_id.'.jpg';
            Image::make($request->persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
            $person->profile_path = $file_persons_poster;
        }

        $person->save();

        return redirect()->action([PersonsController::class,'index'])->with('success','Person Created Successfully');
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'persons_name' => 'required',
            'persons_gender' => 'required',
        ]);


        $person = Persons::find($id);

        $person->imdb_id = $request->persons_imdb_id;
        $person->name = $request->persons_name;
        $person->gender = $request->persons_gender;
        $person->biography = $request->persons_biography;
        $person->birthday = $request->persons_birth_date;
        $person->deathday = $request->persons_death_date;
        $person->homepage = $request->persons_homepage;
        $person->known_for_department = $request->persons_known_for_department;
        $person->place_of_birth = $request->persons_place_of_birth;
        $person->popularity = $request->persons_popularity;

        if($request->persons_poster_url == ''){
            $persons_poster = $request->file('persons_poster');
            if($persons_poster == ''){
                $person->profile_path = '/default_person.jpg';
            }else{
                $extension_poster = $persons_poster->getClientOriginalExtension();
                $file_persons_poster = $person->tmdb_id.'.'.$extension_poster;
                Image::make($persons_poster)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
                $person->profile_path = $file_persons_poster;
            }
        }else{
            $file_persons_poster = $person->tmdb_id.'.jpg';
            Image::make($request->persons_poster_url)->resize(405,600)->save(public_path('/assets/persons/'.$file_persons_poster));
            $person->profile_path = $file_persons_poster;
        }

        $person->save();
        return redirect()->action([PersonsController::class,'index'])->with('success','Person Updated Successfully');
    }

    public function destroy($id){
        $ids = trim($id, '[]');
        $personsid = explode(",",$ids);
        $persons = Persons::whereIn('id', $personsid)->get();

        foreach ($persons as $person) {
            //Delete person
            $person->delete();
            if($person->profile_path != 'default_person.jpg'){
                $image_path = public_path('/assets/persons/'.$person->profile_path);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        }
        return redirect()->action([PersonsController::class,'index'])->with('success','Persons Deleted Successfully!');
    }

    //CODE Get Movies Data
    public function get_person_data($person_id){
        $data = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/person/'.$person_id.'?language='.config('services.tmdb.lang'))
        ->json();

        return $data;
    }

    public function check_person($person_id){
        $data = Persons::where('tmdb_id', $person_id)->get();
        return count($data);
    }

}
