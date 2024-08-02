<?php

namespace Panda\Routing;

use Panda\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}