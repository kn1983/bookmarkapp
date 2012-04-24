<?php

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'api/Slim/Slim.php';
require _DIR_ . 'api/Vendor/Twig/Autoloader.php';

// Init the Twig classloader
Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem(_DIR_ . 'web/views');

// Init some global classes
$app 	  = new Slim();
$twig 	  = new Twig_Environment($loader, array('cache' => _DIR_ . 'api/cache/templates', 'auto_reload' => true,));

// Do the session thing
$app->session->sessionBegin();

// Make an array of all global variables
$data = array(
	'userLoggedIn'  => $app->session->data['logged_in'] == true ? true : false,
	'username'		=> $app->session->data['username'],
);

// Add the array for using in templates
$twig->addGlobal(
	'global', $data
);