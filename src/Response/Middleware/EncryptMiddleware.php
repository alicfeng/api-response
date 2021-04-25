<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * encrypt package middleware
 * Class EncryptMiddleware.
 */
class EncryptMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {
            Log::debug('encrypting by encrypt middleware');
            $response->setContent(call_user_func([config('samego-response.crypt.instance'), 'encrypt'], $response->getContent()));
        }

        return $response;
    }
}
