<?php

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'api/Config/config.php';
require _DIR_ . 'api/Slim/Slim.php';
require _DIR_ . 'api/Vendor/Twig/Autoloader.php';

// Init the Twig classloader
Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem(_DIR_ . 'web/views');

// Init some global classes
$app 	  = new Slim();
$twig 	  = new Twig_Environment($loader, array('cache' => _DIR_ . 'api/cache/templates', 'auto_reload' => true,));
$session  = Slim::getInstance()->session();

// Do the session thing
$session->sessionBegin();