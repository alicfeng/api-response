<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response;

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
     * api-response config.
     * @var array
     */
    protected $config;

    /**
     * helper transform_class.
     * @var null
     */
    protected $transform_class = null;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @functionName set http header
     * @description  setting http header
     * @param array $headers
     * @return Response $this
     */
    private function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->config['header'] ?? [], $headers);

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
        if (true === $this->config['translate']['model'] ?? false) {
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
        return call_user_func([$this->config['translate']['instance'], 'translate'], $message);
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    private function generate()
    {
        // package.data transform
        if (null !== $this->transform_class) {
            $this->data = app($this->transform_class)->transfrom($this->data);
        }

        // build package structure
        $package = [
            $this->config['structure']['code']       => $this->code,
            $this->config['structure']['message']    => $this->message,
            $this->config['structure']['data']       => $this->data,
            $this->config['structure']['request_id'] => $GLOBALS['MS_TRANCE_REQUEST_ID'] ?? '',
        ];

        // debug meta message
        if (true === $this->config['debug']) {
            $package['debug'] = [
                'time' => (int) (microtime(true) * 1000) - (int) (LARAVEL_START * 1000) . ' ms',
            ];
        }

        // translate package
        $this->response = json_encode($package, JSON_UNESCAPED_UNICODE);

        // unset useless vars
        unset($package);

        return response($this->response, $this->status_code, $this->headers);
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory
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
