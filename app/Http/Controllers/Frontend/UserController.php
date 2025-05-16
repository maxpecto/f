<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Items;
use App\Models\Like;
use App\Models\Watchlists;
use App\Models\EpisodeLike;
use Hash;
use Auth;
use Redirect;
use Image;
use File;

use Trackers;

class UserController extends Controller
{

    public function index($username){
        Trackers::track_agent();
        $users = User::where('username' , $username)->first();

        if (!$users) {
            abort(404);
        }

        $user_id = $users->id;
        $users->increment('views');

        if(Auth::id() == $user_id){
            $is_current_user = true;
        }else{
            $is_current_user = false;
        }

        $likedItems = Like::with('items')->where('user_id', $user_id)->where('liked', 1)->orderBy('id', 'DESC')->get();
        $wathlistItems = Watchlists::with('items')->where('user_id', $user_id)->orderBy('id', 'DESC')->get();
        $likedEpisodes = EpisodeLike::with('episodes')->where('user_id', $user_id)->where('liked', 1)->orderBy('id', 'DESC')->get();

        return view('frontend.user.profile',compact('users','likedItems','likedEpisodes','wathlistItems','is_current_user'));
    }

    public function edit_profile(){
        Trackers::track_agent();
        $id = Auth::id();
        $users = User::find($id);
        return view('frontend.user.edit_profile',compact('users'));
    }

    public function update_profile(Request $request){
        $id = Auth::id();
        $user = User::find($id);

        $this->validate($request,[
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id
        ]);

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->description = $request->description;
        $user->website = $request->website;
        $user->instagram = $request->instagram;
        $user->twitter = $request->twitter;
        $user->location = $request->location;

        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }

        $user->hidden_items = $request->has('hide_data');

        $profile_img = $request->file('user_image');
        if($profile_img == ''){
            if($user->profile_img == ''){
                $user->profile_img = 'default.png';
            }
        }else{
            $extension_poster = $profile_img->getClientOriginalExtension();
            $file_user_profile = '/user_'.$user->id.'.'.$extension_poster;
            Image::make($profile_img)->fit(800)->save(public_path('/assets/users'.$file_user_profile));
            $user->profile_img = $file_user_profile;
        }
        $user->save();

        return Redirect::back()->with('success','Profile Updated Successfully');
    }

    //Delete user
    public function delete_profile($id){
        $user = User::where('id', $id)->first();
        $user->delete();
        return redirect()->route('home');
    }

}
