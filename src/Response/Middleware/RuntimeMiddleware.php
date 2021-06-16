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
    private $log_level      = 'info';

    public function __construct(Request $request)
    {
        $this->log_enable     = config('api-response.log.enable', false);
        $this->log_level      = config('api-response.log.level', 'info');
        $this->request_trace  = config('api-response.runtime.trace.request', false);
        $this->response_trace = config('api-response.runtime.response.request', false);
        $this->filter_uri     = in_array($request->path(), config('api-response.runtime.trace.filter_uri', []), true);
    }

    public function handle(Request $request, Closure $next)
    {
        /*trace request core message into log with trace-model*/
        if (true === $this->log_enable && true === $this->request_trace && false === $this->filter_uri) {
            Log::{$this->log_level}('trace request message begin');
            Log::{$this->log_level}('path    : ' . $request->path());
            Log::{$this->log_level}('method  : ' . $request->method());
            Log::{$this->log_level}('ip      : ' . $request->ip());
            Log::{$this->log_level}('params  : ' . json_encode($request->all(), JSON_UNESCAPED_UNICODE));
            Log::{$this->log_level}('trace request message finish');
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
            Log::{$this->log_level}('trace response message begin');
            Log::{$this->log_level}('response package : ' . $response->getContent());
            Log::{$this->log_level}('trace response message finish');
        }
    }
}
