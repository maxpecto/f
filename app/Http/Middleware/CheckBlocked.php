<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class CheckBLocked
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->blocked == 1) {
            $message = 'Your account has been blocked by admin. Please contact administrator.';
            auth()->logout();
            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
