<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserHasPolda as UHP;
use Illuminate\Http\Request;

class UserHasPolda
{

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->hasRole('administrator')) {
            return $next($request);
        } else {
            $find = UHP::where("user_id", $user->id)->first();
            if(empty($find)) {
                flash('Your account has not been connected to any Polda data. Please contact the admin')->error();
                return redirect('/');
            } else {
                return $next($request);
            }
        }
    }
}