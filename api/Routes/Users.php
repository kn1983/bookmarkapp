<?php

$app->get('/users/:id', function($id) use ($app){
	$user = new Slim_Controllers_Users($app->db);
	$user->setId($id);
	$data = $user->getUser();
	echo json_encode($data);
});

$app->get('/users', function() use ($app){
	$user = new Slim_Controllers_Users($app->db);
	$data = $user->getUsers();
	echo json_encode($data);
});

$app->post('/users', function() use ($app) {
	$user = new Slim_Controllers_Users($app->db);
	$data = $user->setUser($app->request(), $app->session, $app->getDefaultSettings());
	echo json_encode($data);
});

$app->put('/users/:id', function($id) use ($app) {
	$user = new Slim_Controllers_Users($app->db);
	$data = $user->updateUser($id);
	echo json_encode($data);
});

$app->delete('/users/:id', function($id) use ($app) {
	$user = new Slim_Controllers_Users($app->db);
	$data = $user->deleteUser($id);
	echo json_encode($data);
});

?>