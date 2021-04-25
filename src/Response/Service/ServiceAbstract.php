<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\Service;

use Samego\Response\Response;

abstract class ServiceAbstract
{
    /**
     * @var Response
     */
    public $rspHelper;

    public function __construct()
    {
        $this->rspHelper = app(Response::class);
    }
}
