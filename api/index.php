<?php

require 'Slim/Slim.php';

$app = new Slim();

Slim_Route::setDefaultConditions(array(
    'id' => '[0-9]+'
));

$env = $app->environment('PATH_INFO');
$param = explode('/', $env['PATH_INFO']);
$route = 'Routes/' . $param[1] . '.php';

if(file_exists($route)) {
	require_once $route;
}

$app->run();
?>