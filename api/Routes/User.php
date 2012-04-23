<?php

$app->get('/user/:id', function($id) use ($app){
	$user = new Slim_User($app->db);
	$data = $user->getUser($id);
	echo json_encode($data);
});

$app->post('/user', function() use ($app) {
	$user = new Slim_User($app->db);
	$data = $user->setUser($app->request(), $app->session, $app->config('usergroup.new'));
	echo json_encode($data);
});

?>