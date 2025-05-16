<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\User;
use Auth;

class CheckMaintenance
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
        $general = Settings::findOrFail('1');
        if($general->maintenance == 1){
            if(Auth::user()){
                $users = User::find(Auth::user()->id);
                if($users->role == 'administrators' || $users->role == 'moderators' || $users->role == 'authors'){
                    return $next($request);
                }else{
                    return redirect()->route('maintenance');
                }
            }else{
                return redirect()->route('maintenance');
            }

        }
        return $next($request);
    }
}
