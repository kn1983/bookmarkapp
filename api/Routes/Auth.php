<?php

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

?>