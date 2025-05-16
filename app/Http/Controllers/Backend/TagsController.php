<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Tags;

class TagsController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    //Get Tags List
    public function get_tags(){
        $tags = Tags::orderBy('name', 'ASC')->where('visible',1)->get();
        return $tags;
    }

    public function index(Request $request){
        $total_tags = Tags::get();

        if(!empty($request->search)){
            $data = Tags::where([
                ['name', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.tags.tags',compact('data','total_tags'));
        }else{
            $data = Tags::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.tags.tags',compact('data','total_tags'));
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:tags,name'
        ], [
            'name.required' => 'Tag Name Field is Required!',
            'name.unique' => 'Tag Name Has Been Created Already!',
        ]);

        $tags = new Tags();
        $tags->name = $request->name;
        $tags->visible = 1;
        $tags->save();

        return redirect()->action([TagsController::class,'index'])->with('success','Tag Created Successfully');
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => "required|unique:tags,name,{$id}"
        ], [
            'name.required' => 'Tag Name Field is Required!',
            'name.unique' => 'Tag Name Has Been Created Already!',
        ]);
        $tags = Tags::find($id);
        $tags->name = $request->name;
        $tags->save();
        return redirect()->action([TagsController::class,'index'])->with('success','Tags Updated Successfully');
    }

    public function destroy($id){
        $ids = trim($id, '[]');
        $tagsid = explode(",",$ids);
        $tags = Tags::whereIn('id', $tagsid)->get();
        foreach ($tags as $tag) {
            //Delete Tag
            $tag->delete();
        }
        return redirect()->action([TagsController::class,'index'])->with('success','Tags Deleted Successfully!');
    }

}
