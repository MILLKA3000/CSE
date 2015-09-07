<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  \Closure $params array of allowed roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        $rolesList = array();

        #if no auth -> 404
        if (!Auth::user()) {
            abort(404);
        }


        $rolesList[] = Auth::user()->roles->name;


        // if $params is an array type then we must work with foreach loop
        if (is_array($params)) {
            foreach ($params as $uRole) {
                if (in_array($uRole, $rolesList)) {
                    return $next($request);
                }
            }
            // otherwise if $params is a string, just checking one role
        } elseif (is_string($params)) {
            if (in_array($params, $rolesList)) {
                return $next($request);
            }
        }


        // if no matches - do abort to 404 page
       return redirect('/')->with('flash_message', 'NO ACCESS!');

    }
}
