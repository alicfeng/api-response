<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Middleware;

use Closure;
use Illuminate\Http\Request;

class TraceRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $GLOBALS['MS_TRANCE_REQUEST_ID'] = $request->header('MS_TRANCE_REQUEST_ID') ?? uuid_create();

        return $next($request);
    }
}
