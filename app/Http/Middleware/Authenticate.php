<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    
    public function handle(Request $request, Closure $next){   
        if ($request->user()){
            $request->session()->put('back_button_disabled', true);
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
