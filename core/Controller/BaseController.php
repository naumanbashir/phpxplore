<?php

namespace Panda\Controller;

use core\Application;

abstract class BaseController
{
    protected function render(string $view, array $data = []) {
        echo Application::$app->router->renderView($view, $data);
    }
}