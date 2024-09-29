<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next, ...$roles)
    {

        if (Auth::user() &&  Auth::user()->role == 'admin') {
            return $next($request);
        } else {
            return redirect('/home')->with('error', 'You do not have access to this section.');
        }
    }

    // public function handle($request, Closure $next)
    // {
    //     if (Auth::user() &&  Auth::user()->role == 'admin') {
    //         return $next($request);
    //     }

    //     return redirect('home')->with('error', 'You have not admin access');
    //     // It will redirect user back to home screen if they do not have is_admin=1 assigned in database
    // }
    // public function handle(Request $request, Closure $next, ...$roles)
    // {
    //     print_r($roles);
    //     // Check if the user is authenticated and has the required role
    //     // if (!$request->user() || !in_array($request->user()->role, $roles)) {
    //     //     return redirect('/home')->with('error', 'You do not have access to this section.');
    //     // }

    //     return $next($request);
    // }
}
