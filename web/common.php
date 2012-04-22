<?php

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'api/Slim/Slim.php';
require _DIR_ . 'api/Classes/Session.php';
require _DIR_ . 'api/Classes/Config.php';
require _DIR_ . 'vendors/Twig/Autoloader.php';

// Init the Twig classloader
Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem(_DIR_ . 'web/views');

// Init some global classes
$app 	  = new Slim();
$session  = new Classes\Session();
$twig 	  = new Twig_Environment($loader, array('cache' => _DIR_ . 'cache/templates', 'auto_reload' => true,));

// Do the session thing
$session->sessionBegin();
unset($session->data['password']);

// Make an array of all global variables
$data = array(
	'userLoggedIn'  => $session->data['logged_in'] == true ? true : false,
	'username'		=> $session->data['username'],
);

// Add the array for using in templates
$twig->addGlobal(
	'global', $data
);