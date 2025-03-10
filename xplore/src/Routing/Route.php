<?php

namespace Xplore\Routing;

use Xplore\BaseFacade;

class Route extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return RouterInterface::class;
    }
}