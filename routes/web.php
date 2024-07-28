<?php

$app = \Panda\Application::$app;

$app->router->get('/', function () {
    dd('Working');
});