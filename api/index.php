<?php

require 'Slim/Slim.php';

$app = new Slim();
$enviroment = $app->environment();
require_once 'Routes' . $enviroment['PATH_INFO'] . '.php';
$app->run();
?>