<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Service;

use Illuminate\Support\Facades\App;
use Samego\Response\Contracts\TranslationInterface;

class Translation implements TranslationInterface
{
    /**
     * @function    translate
     * @description translate
     * @param mixed|null $key     translate key
     * @param mixed|null $replace replace key
     * @return mixed
     * @author      AlicFeng
     * @datatime    19-11-25 下午9:17
     */
    public static function translate($key = null, $replace = [])
    {
        return trans($key, $replace, App::getLocale()) ?? $key;
    }
}
