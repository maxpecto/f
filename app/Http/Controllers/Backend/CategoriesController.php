<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_categories = Categories::get();

        if(!empty($request->search)){
            $data = Categories::where([
                ['name', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.categories.categories',compact('data','total_categories'));
        }else{
            $data = Categories::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.categories.categories',compact('data','total_categories'));
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required'
        ], [
            'name.required' => 'Name Field is Required!',
        ]);

        $categories = new Categories();
        $categories->name = $request->name;
        $categories->visible = 1;
        $categories->save();

        return redirect()->action([CategoriesController::class,'index'])->with('success','Categories Created Successfully');
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'name' => 'required'
        ], [
            'name.required' => 'Name Field is Required!',
        ]);
        $categories = Categories::find($id);
        $categories->name = $request->name;
        $categories->save();
        return redirect()->action([CategoriesController::class,'index'])->with('success','Categories Updated Successfully');
    }

    public function destroy($id){
        $ids = trim($id, '[]');
        $categoriesid = explode(",",$ids);
        $categories = Categories::whereIn('id', $categoriesid)->get();
        foreach ($categories as $categorie) {
            //Delete Categories
            $categorie->delete();
        }
        return redirect()->action([CategoriesController::class,'index'])->with('success','Categories Deleted Successfully!');
    }

}
