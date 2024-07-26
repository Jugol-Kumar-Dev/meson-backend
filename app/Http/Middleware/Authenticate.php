<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if(Auth::check()){
            if(Auth::user()->role == 'admin'){
                return redirect('/panel/dashboard');
            }else{
                return redirect()->route('dashboard');
            }
        }else{
            return $request->expectsJson() ? null : route('login');
        }
    }
}
