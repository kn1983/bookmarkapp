<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/auth', function () {
	require 'Controllers/Auth.php';
	$auth = new AuthController();
	$auth->getAuthInformation();
});

$app->post('/auth', function () use ($app) { 
	require 'Controllers/Auth.php';
	$auth = new AuthController();
	$auth->logInUser();
});

$app->get('/user/:id', function ($id) {
	require 'Controllers/User.php';
	$user = new UserController();
	$user->getUserInformation($id);
});

$app->post('/user', function () use ($app) { 
	require 'Controllers/User.php';
	$user = new UserController();
	$user->addNewUser();
});

$app->run();
?>