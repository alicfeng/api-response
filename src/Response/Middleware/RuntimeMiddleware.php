<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RuntimeMiddleware
{
    private $request_trace  = false;
    private $response_trace = false;
    private $filter_uri     = false;

    public function __construct(Request $request)
    {
        $this->request_trace  = config('helper.runtime.trace.request', false);
        $this->response_trace = config('helper.runtime.response.request', false);
        $this->filter_uri     = in_array($request->path(), config('helper.runtime.trace.filter_uri', []), true);
    }

    public function handle(Request $request, Closure $next)
    {
        /*trace request core message into log with trace-model*/
        if (true === $this->request_trace && false === $this->filter_uri) {
            Log::notice('trace request message begin');
            Log::notice('path    : ' . $request->path());
            Log::notice('method  : ' . $request->method());
            Log::notice('ip      : ' . $request->ip());
            Log::notice('params  : ' . json_encode($request->all(), JSON_UNESCAPED_UNICODE));
            Log::notice('trace request message finish');
        }

        return $next($request);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @author      AlicFeng
     */
    public function terminate($request, $response)
    {
        if (true === $this->response_trace && false === $this->filter_uri) {
            Log::notice('trace response message begin');
            Log::notice('response package : ' . $response->getContent());
            Log::notice('trace response message finish');
        }
    }
}
