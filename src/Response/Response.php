<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class Response
{
    /**
     * http status code.
     * @var int
     */
    protected $status_code = 200;

    /**
     * http response headers.
     * @var array
     */
    protected $headers = [];

    /**
     * package structure code dom.
     * @var int null
     */
    protected $code = null;

    /**
     * package structure message dom.
     * @var string null
     */
    protected $message = null;

    /**
     * package structure data dom.
     * @var object null
     */
    protected $data = [];

    /**
     * http response.
     * @var string
     */
    private $response = '';

    /**
     * response log flag
     * if true that printer log message
     * by using laravel Log.
     * @var bool
     */
    protected $log = true;

    /**
     * log level
     * setting by helper configuration file or log function
     * default notice level.
     * @var string
     */
    protected $log_level = 'notice';

    /**
     * helper debug.
     * @var bool
     */
    protected $debug = false;

    /**
     * helper transform_class.
     * @var null
     */
    protected $transform_class = null;

    public function __construct()
    {
        $this->log       = config('samego-response.log.enable', true);
        $this->log_level = config('samego-response.log.level', 'notice');
        $this->debug     = config('samego-response.debug', false);
    }

    /**
     * @functionName set http header
     * @description  setting http header
     * @param array $headers
     * @return Response $this
     */
    private function setHeaders(array $headers)
    {
        $this->headers = array_merge(config('samego-response.header', []), $headers);

        return $this;
    }

    /**
     * @function     set data
     * @description  setting package.data dom value
     * @param mixed $data data of package
     * @return $this
     */
    private function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @function    setCodeEnum
     * @description setting package.code and package.message
     * @param array $code_enum [code,message]
     * @return self $this
     */
    private function setCodeEnum(array $code_enum)
    {
        $this->code    = $code_enum[0];
        $this->message = end($code_enum);

        // translate
        if (true === config('samego-response.translate.model')) {
            $this->message = $this->translate($this->message);
        }

        return $this;
    }

    /**
     * @function    translate
     * @description translate
     * @param mixed $message translate
     * @return mixed
     * @author      AlicFeng
     * @datatime    19-11-25 下午9:15
     */
    private function translate($message)
    {
        return call_user_func([config('samego-response.translate.instance'), 'translate'], $message);
    }

    /**
     * @function    setting status code
     * @description setting http.status_code
     * @param int $status_code
     * @return $this
     */
    private function setStatusCode(int $status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    /**
     * @function    generate the response
     * @description generate response package message
     * @return ResponseFactory
     */
    private function generate()
    {
        // package.data transform
        if (null !== $this->transform_class) {
            $this->data = app($this->transform_class)->transfrom($this->data);
        }

        // build package structure
        $structure = config('samego-response.structure', []);
        $package   = [
            Arr::get($structure, 'code', 'code')       => $this->code,
            Arr::get($structure, 'message', 'message') => $this->message,
            Arr::get($structure, 'data', 'data')       => $this->data,
        ];

        // debug meta message
        if (true === $this->debug) {
            $package['debug'] = [
                'runtime' => (int) (microtime(true) * 1000) - (int) (LARAVEL_START * 1000) . ' ms',
            ];
        }

        // translate package
        $this->response = json_encode($package, JSON_UNESCAPED_UNICODE);

        // unset useless vars
        unset($package, $structure);

        return response($this->response, $this->status_code, $this->headers);
    }

    /**
     * @function    log
     * @description setting log configuration
     * @param bool $flag
     * @param null $level
     * @return self $this
     */
    public function log(bool $flag, $level = null)
    {
        $this->log = $flag;

        if (null === $level) {
            $this->log_level = config('samego-response.log.level', 'notice');
        }

        return $this;
    }

    /**
     * @function    transform
     * @description transform
     * @param string $transform_class
     * @return self $this
     * @author      alicfeng
     */
    public function transform(string $transform_class)
    {
        $this->transform_class = $transform_class;

        return $this;
    }

    /**
     * @function    statusCode
     * @description set http status code
     * @param int $status_code
     * @return $this
     * @author      AlicFeng
     */
    public function statusCode(int $status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    /**
     * @function    generate the response result
     * @description generate the response result
     * @param array        $code_enum   package[code,message]
     * @param object|array $data        package.data
     * @param int          $status_code http.status_code
     * @param array        $headers     http.headers
     * @return ResponseFactory
     */
    public function result(array $code_enum, $data = [], int $status_code = 200, array $headers = [])
    {
        return $this
            ->setStatusCode($status_code)
            ->setHeaders($headers)
            ->setCodeEnum($code_enum)
            ->setData($data)
            ->generate();
    }
}
