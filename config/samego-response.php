<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

return [
    // about package setting
    /*Response Package Structure*/
    'structure' => [
        'code'    => 'code',
        'message' => 'message',
        'data'    => 'data',
    ],

    // Default Header simple:Content-Type => application/json
    'header'    => [
        'Content-Type' => 'application/json',
    ],

    /*Package encrypt Setting*/
    'crypt'     => [
        'instance' => \Samego\Response\Service\CryptService::class,
        'method'   => 'aes-128-ecb',
        'password' => '1234qwer',
    ],

    /*Log*/
    'log'       => [
        'log'   => true,
        'level' => 'notice',
    ],

    // translate
    'translate' => [
        'model'    => true,
        'instance' => \Samego\Response\Service\Translation::class,
    ],

    // runtime model
    'runtime'   => [
        'trace' => [
            'request'    => true,
            'response'   => false,
            'filter_uri' => [
            ],
        ],
    ],

    // debug model setting
    'debug'     => false,
];
