<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next){
        $path = $request->path();
        $loggedIn = session()->has('loggedInUser');

        if (!$loggedIn && !in_array($path, ['/', 'register'])) {
            return redirect('/');
        } elseif ($loggedIn && in_array($path, ['/', 'register'])) {
            return back();
        }

        return $next($request);
    }
}

