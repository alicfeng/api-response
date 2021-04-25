<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Contracts;

interface TranslationInterface
{
    /**
     * @function    translate
     * @description translate
     * @param string|null $key translate key
     * @return mixed
     * @author      AlicFeng
     * @datatime    19-11-25 下午9:17
     */
    public static function translate($key = null);
}
