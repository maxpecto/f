<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Genres;

class GenresController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_genres = Genres::where("visible",1)->get();
        if(!empty($request->search)){
            $data = Genres::where([
                ['name', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'ASC')->paginate(10)->onEachSide(1);
            return view('backend.genres.lists',compact('data','total_genres'));
        }else{
            $data = Genres::orderBy('id', 'ASC')->paginate(10)->onEachSide(1);
            return view('backend.genres.lists',compact('data','total_genres'));
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:genres,name'
        ], [
            'name.required' => 'Genres Name Field is Required!',
            'name.unique' => 'Genres Name Has Been Created Already!',
        ]);

        $genres = new Genres();
        $genres->name = $request->name;
        $genres->visible = 1;
        $genres->save();
        return redirect()->action([GenresController::class,'index'])->with('success','Genres Added Successfully');
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => "required|unique:genres,name,{$id}"
        ], [
            'name.required' => 'Genres Name Field is Required!',
            'name.unique' => 'Genres Name Has Been Created Already!',
        ]);
        $genres = Genres::find($id);
        $genres->name = $request->name;
        $genres->save();
        return redirect()->action([GenresController::class,'index'])->with('success','Genres Updated Successfully');
    }

    public function destroy($id){
        $ids = trim($id, '[]');
        $genresid = explode(",",$ids);
        $genres = Genres::whereIn('id', $genresid)->get();
        foreach ($genres as $genre) {
            //Genres Tag
            $genre->delete();
        }
        return redirect()->action([GenresController::class,'index'])->with('success','Genres Deleted Successfully!');
    }

    public function visible(Request $request){
        $genres = Genres::find($request->id);
        if ($genres->visible == 1) {
            $genres->visible = 0;
        }else{
            $genres->visible = 1;
        }
        $genres->save();
    }

}
