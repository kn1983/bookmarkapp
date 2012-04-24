<?php

$app->get('/auth', function() use ($app) {
	$auth = new Slim_Controllers_Auth($app->db);
	$data = $auth->getAuth($app->request(), $app->getDefaultSettings());
	echo json_encode($data);
});

$app->post('/auth', function() use ($app) { 
	$auth = new Slim_Controllers_Auth($app->db);
	$data = $auth->setAuth($app->request(), $app->session, $app->getDefaultSettings());
	echo json_encode($data);
});

$app->get('/auth/:logout', function($logout) use ($app) {
    $app->session->sessionKill();
    $app->session->sessionBegin();
	$app->redirect('/');
})->conditions(array('logout' => 'logout'));

?>