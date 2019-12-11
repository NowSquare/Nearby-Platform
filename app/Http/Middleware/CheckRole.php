<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role1, $role2 = 'NULL', $role3 = 'NULL', $role4 = 'NULL', $role5 = 'NULL')
    {
        if (! $request->user()->hasRole([$role1, $role2, $role3, $role4, $role5])) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}