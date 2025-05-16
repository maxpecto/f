<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pages;

class PagesController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_pages = Pages::get();
        if(!empty($request->search)){
            $data = Pages::where([
                ['title', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.pages.lists',compact('data','total_pages'));
        }else{
            $data = Pages::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.pages.lists',compact('data','total_pages'));
        }
    }

    public function add(){
        return view('backend.pages.add');
    }

    //Display Page Edit
 	public function edit($id){
        $pages = Pages::find($id);
        return view('backend.pages.edit',compact('pages'));
    }

    //Add Page
    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|unique:pages,title',
            'body' => 'required',
        ]);

        $pages = new Pages();
        $pages->title = $request->title;
        $pages->slug = Str::slug($request->title);
        $pages->body = $request->body;
        $pages->save();

        return redirect()->action([PagesController::class,'index'])->with('success','Page Created Successfully');
    }

    //Update Page
    public function update(Request $request, $id){
        $pages = Pages::find($id);

        $this->validate($request,[
            'title' => 'required|unique:pages,title,'.$pages->id,
            'body' => 'required',
        ]);

        $pages->title = $request->title;
        $pages->slug = Str::slug($request->title);
        $pages->body = $request->body;
        $pages->save();

        return redirect()->action([PagesController::class,'index'])->with('success','Page Updated Successfully');
    }

    //Delete Page
    public function destroy($id){
        $ids = trim($id, '[]');
        $pagesid = explode(",",$ids);
        $pages = Pages::whereIn('id', $pagesid)->get();
        foreach ($pages as $page ) {
            //Delete Page
            $page->delete();
        }
        return redirect()->action([PagesController::class,'index'])->with('success','Page Deleted Successfully!');
    }

    //Change Visible
    public function visible(Request $request){
        $pages = Pages::find($request->id);
        if ($pages->visible == 1) {
            $pages->visible = 0;
        }else{
            $pages->visible = 1;
        }
        $pages->save();
    }

}
