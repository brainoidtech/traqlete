<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {

        if(!Auth::check()){
            // abort(403, 'Unauthorized User');
            return redirect('/login');
        }

        $checkRole =  Auth::user()->userRole->role_name;
        

        if(!in_array($checkRole, $role))
        {
            // abort(403, 'Unauthorized User');
            return redirect('/login');
        }
        return $next($request);

    }
}
