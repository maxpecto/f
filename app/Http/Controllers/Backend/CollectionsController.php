<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Models\Items;
use App\Models\Collections;

class CollectionsController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_collections = Collections::get();
        if(!empty($request->search)){
            $data = Collections::where([
                ['name', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.collections.lists',compact('data','total_collections'));
        }else{
            $data = Collections::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.collections.lists',compact('data','total_collections'));
        }
    }

    public function add(){
        return view('backend.collections.add');
    }

    //Display collections Edit
 	public function edit($id){
        $editcollections = Collections::find($id);
        return view('backend.collections.edit',compact('editcollections'));
    }

    //Add collections
    public function store(Request $request){
        $this->validate($request,[
            'c_name' => 'required',
        ]);
        $collections = new Collections();
        $collections->name = $request->c_name;
        $collections->visible = 1;
        $collections->save();

        //Genres
        if(isset($request->c_items)){
            $c_items_arr = explode(",", $request->c_items);
            $collections->items()->sync($c_items_arr, false);
        }
        //End Genres
        $collections->save();

        return redirect()->action([CollectionsController::class,'index'])->with('success','Collections Created Successfully');
    }

    //Update collections
    public function update(Request $request, $id){
        $this->validate($request,[
            'c_name' => 'required',
        ]);
        $collections = Collections::find($id);
        $collections->name = $request->c_name;
        $collections->save();

        //Collections
        if(isset($request->c_items)){
            $collections_items_arr = explode(",", $request->c_items);
            $collections->items()->sync($collections_items_arr, true);
        }
        //End Collections

        return redirect()->action([CollectionsController::class,'index'])->with('success','Collections Updated Successfully');
    }

    //Delete collections
    public function destroy($id){
        $ids = trim($id, '[]');
        $collectionsid = explode(",",$ids);
        $collections = Collections::whereIn('id', $collectionsid)->get();
        foreach ($collections as $collection ) {
            //Delete Collection
            $collection->delete();
        }
        return redirect()->action([MoviesController::class,'index'])->with('success','Collections Deleted Successfully!');
    }

    //Change Visible
    public function visible(Request $request){
        $collections = Collections::find($request->id);
        if ($collections->visible == 1) {
            $collections->visible = 0;
        }else{
            $collections->visible = 1;
        }
        $collections->save();
    }

}
