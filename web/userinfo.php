<?php

use Classes\Config;

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'Classes/Db.php';
require _DIR_ . 'Classes/Request.php';
require _DIR_ . 'Classes/Session.php';
require _DIR_ . 'Classes/Config.php';

// Init some global classes
$db 	  = new Classes\Db();
$request  = new Classes\Request();
$session  = new Classes\Session();

// Connect to the database
$db->sql_connect(Config::dbHost, Config::dbUser, Config::dbPw, Config::dbName, Config::dbPort);

// Do the session thing
$session->sessionBegin();
unset($session->data['password']);

echo json_encode($session->data);
?>