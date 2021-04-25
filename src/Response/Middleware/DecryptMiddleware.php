<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DecryptMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!call_user_func([config('samego-response.crypt.instance'), 'decrypt'], $request->getContent())) {
            Log::debug('decrypting by decrypt middleware encrypt error value:' . $request->getContent());

            return Response('Invalid Param', '403');
        }
        Log::debug('decrypting by decrypt middleware encrypt success value:' . $request->getContent());

        return $next($request);
    }
}
