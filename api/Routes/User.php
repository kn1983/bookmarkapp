<?php

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

?>