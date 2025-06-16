<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {

        $roles = [
            'admin' => 'admin',
            'user' => 'user'
        ];

        if(auth()->check() && auth()->user()->role !== $roles[$role]) return $next($request);

        if(!auth()->check()) {
            return redirect()->intended('/login');
        }

        abort(403, 'Unauthorized action.');


    }

}
