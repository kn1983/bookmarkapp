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
	$user = new Slim_User($id);
	$data = $user->getUser();
	echo json_encode($data);
});

$app->post('/user', function () use ($app) { 
	$user = new Slim_User();
	$data = $user->setUser();
	echo json_encode($data);
});

$app->run();
?>