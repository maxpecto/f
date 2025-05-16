<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()){
            $users = User::find(Auth::user()->id);
            if($users->role == 'administrators'){
                return $next($request);
            }else{
                return redirect('/');
            }
        }
        return redirect('/login');
    }
}
