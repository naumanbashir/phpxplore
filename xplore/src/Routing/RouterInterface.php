<?php

namespace Xplore\Routing;

use Xplore\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}