<?php

use Classes\Config;

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'vendors/Twig/Autoloader.php';
require _DIR_ . 'Classes/Db.php';
require _DIR_ . 'Classes/Request.php';
require _DIR_ . 'Classes/Session.php';
require _DIR_ . 'Classes/Config.php';

// Init the Twig classloader
Twig_Autoloader::register();
$loader   = new Twig_Loader_Filesystem(_DIR_ . 'web/views');

// Init some global classes
$db 	  = new Classes\Db();
$request  = new Classes\Request();
$session  = new Classes\Session();
$twig 	  = new Twig_Environment($loader, array('cache' => _DIR_ . 'cache/templates', 'auto_reload' => true,));

// Connect to the database
$db->sql_connect(Config::dbHost, Config::dbUser, Config::dbPw, Config::dbName, Config::dbPort);

// Do the session thing
$session->sessionBegin();
unset($session->data['password']);

var_dump($session->data);
var_dump($_COOKIE);

// Make an array of all global variables
$data = array(
	'userLoggedIn'  => $session->data['logged_in'] == true ? true : false,
	'username'		=> $session->data['username'],
);

// Add the array for using in templates
$twig->addGlobal(
	'global', $data
);
