<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Image;
use File;

class UsersController extends BackendController
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $total_users = User::get();
        if(!empty($request->search)){
            $data = User::where([
                ['username', 'LIKE', '%' . $request->search . '%']
            ])->orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.users.lists',compact('data','total_users'));
        }else{
            $data = User::orderBy('id', 'DESC')->paginate(10)->onEachSide(1);
            return view('backend.users.lists',compact('data','total_users'));
        }
    }

    public function add(){
        return view('backend.users.add');
    }

    //Display user Edit
 	public function edit($id){
        $users = User::find($id);
        return view('backend.users.edit',compact('users'));
    }

    //Add user
    public function store(Request $request){
        $this->validate($request,[
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = new User();
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->description = $request->description;
        $user->website = $request->website;
        $user->instagram = $request->instagram;
        $user->twitter = $request->twitter;
        $user->location = $request->location;
        $user->role = $request->user_role;
        $user->save();

        $profile_img = $request->file('profile_image');
        if($profile_img == ''){
            if($user->profile_img == ''){
                $user->profile_img = '/default.png';
            }
        }else{
            $extension_poster = $profile_img->getClientOriginalExtension();
            $file_user_profile = '/user_'.$user->id.'.'.$extension_poster;
            Image::make($profile_img)->fit(800)->save(public_path('/assets/users'.$file_user_profile));
            $user->profile_img = $file_user_profile;
        }
        $user->save();

        return redirect()->action([UsersController::class,'index'])->with('success','User Created Successfully');
    }

    //Update user
    public function update(Request $request, $id){

        $user = User::find($id);

        $this->validate($request,[
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,

        ],[
            'fname.required' => "First Name field is required",
            'lname.required' => "Last Name field is required",
            'user_email.required' => "Email field is required",
            'user_email.email' => "Invalid email format",
            'user_email.unique' => "Email has been used",
            'username.required' => "Username field is required",
            'username.unique' => "Username has been used",
        ]);

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->description = $request->description;
        $user->website = $request->website;
        $user->instagram = $request->instagram;
        $user->twitter = $request->twitter;
        $user->location = $request->location;
        $user->role = $request->user_role;
        $user->save();

        $profile_img = $request->file('profile_image');
        if($profile_img == ''){
            if($user->profile_img == ''){
                $user->profile_img = '/default.png';
            }
        }else{
            $extension_poster = $profile_img->getClientOriginalExtension();
            $file_user_profile = '/user_'.$user->id.'.'.$extension_poster;
            Image::make($profile_img)->fit(800)->save(public_path('/assets/users'.$file_user_profile));
            $user->profile_img = $file_user_profile;
        }
        $user->save();

        return redirect()->action([UsersController::class,'index'])->with('success','User Updated Successfully');
    }

    //Delete user
    public function destroy($id){
        $ids = trim($id, '[]');
        $usersid = explode(",",$ids);
        $users = User::whereIn('id', $usersid)->get();
        foreach ($users as $user ) {
            //Delete User
            $user->delete();
        }
        return redirect()->action([UsersController::class,'index'])->with('success','User Deleted Successfully!');
    }

    //Change Blocked
    public function blocked(Request $request){
        $user = User::find($request->id);
        if ($user->blocked == 1) {
            $user->blocked = 0;
        }else{
            $user->blocked = 1;
        }
        $user->save();
    }

    //Change Verify Badge
    public function verify(Request $request){
        $user = User::find($request->id);
        if ($user->verify_Badge == 1) {
            $user->verify_Badge = 0;
        }else{
            $user->verify_Badge = 1;
        }
        $user->save();
    }
}
