<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

use App\Models\User;

class IsStaff
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
        $userIsAdmin = Auth::check() && Auth::user()->user_type == User::ADMIN;
        if($userIsAdmin){
            return $next($request);
        }
        return abort(401);
    }
}
