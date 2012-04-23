<?php

require 'Slim/Slim.php';

$app = new Slim();

Slim_Route::setDefaultConditions(array(
    'id' => '[0-9]+'
));

require 'Routes/User.php';
require 'Routes/Auth.php';

// Autoloader - This might actually turned out to be slower with
// all the validation in place so we probaly not use it.
/*$env = $app->environment('PATH_INFO');
$paramSize = explode('/', $env['PATH_INFO']);
$param = sizeof($paramSize) > 2 ? substr($env['PATH_INFO'], 0, strrpos($env['PATH_INFO'], '/')) : $env['PATH_INFO'];
$route = 'Routes' . $param . '.php';

if(file_exists($route)) {
	require_once $route;
}*/

$app->run();
?>