<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\Comments;

class CommentsController extends BackendController
{
    public function index(Request $request){
        $total_comments = Comments::get();
        $data = Comments::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);

        return view('backend.comments.lists',compact('data','total_comments'));
    }

    public function destroy($id){
        $ids = trim($id, '[]');
        $tagsid = explode(",",$ids);
        $tags = Comments::whereIn('id', $tagsid)->get();
        foreach ($tags as $tag) {
            //Delete Comments
            $tag->delete();
        }
        return redirect()->action([CommentsController::class,'index'])->with('success','Comments Deleted Successfully!');
    }

    //Change Approve
    public function approve(Request $request){
        $comment = Comments::find($request->id);
        if ($comment->approve == 1) {
            $comment->approve = 0;
        }else{
            $comment->approve = 1;
        }
        $comment->save();
    }

}
