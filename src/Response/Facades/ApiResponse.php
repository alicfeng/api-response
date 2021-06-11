<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Facades;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Facade;
use Samego\Response\Contracts\ApiResponseInterface;
use Samego\Response\Response;

/**
 * Class ApiResponse.
 * @method static ResponseFactory result(array $code_enum, $data = [], int $status_code = 200, array $headers = [])
 * @method static Response statusCode(int $status_code)
 * @version 1.0.0
 * @author  AlicFeng
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiResponseInterface::class;
    }
}
