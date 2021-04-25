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
    private $log_enable     = false;

    public function __construct(Request $request)
    {
        $this->log_enable     = config('samego-response.runtime.enable', false);
        $this->request_trace  = config('samego-response.runtime.trace.request', false);
        $this->response_trace = config('samego-response.runtime.response.request', false);
        $this->filter_uri     = in_array($request->path(), config('samego-response.runtime.trace.filter_uri', []), true);
    }

    public function handle(Request $request, Closure $next)
    {
        /*trace request core message into log with trace-model*/
        if (true === $this->log_enable && true === $this->request_trace && false === $this->filter_uri) {
            Log::info('trace request message begin');
            Log::info('path    : ' . $request->path());
            Log::info('method  : ' . $request->method());
            Log::info('ip      : ' . $request->ip());
            Log::info('params  : ' . json_encode($request->all(), JSON_UNESCAPED_UNICODE));
            Log::info('trace request message finish');
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
        if (true === $this->log_enable && true === $this->response_trace && false === $this->filter_uri) {
            Log::info('trace response message begin');
            Log::info('response package : ' . $response->getContent());
            Log::info('trace response message finish');
        }
    }
}
