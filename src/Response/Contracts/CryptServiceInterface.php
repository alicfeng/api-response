<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Contracts;

interface CryptServiceInterface
{
    /**
     * @function    encrypt
     * @description encrypt handle
     * @param string $plain_text
     * @param string $key
     * @return mixed
     * @author      AlicFeng
     */
    public static function encrypt(string $plain_text, $key = '');

    /**
     * @function    decrypt
     * @description decrypt handle
     * @param string $cipher_text
     * @param string $key
     * @return mixed
     * @author      AlicFeng
     */
    public static function decrypt(string $cipher_text, $key = '');
}
