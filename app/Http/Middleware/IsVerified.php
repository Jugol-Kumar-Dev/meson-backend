<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.enable_verification'))
        {
            if(Auth::User()->email_verified_at != null){
                return $next($request);
            }else{
                return response()->json('Please Verify First Your Account', 401);
            }
        }else {
            return $next($request);
        }
    }
}
