<?php

$app->get('/bookmarks/:id', function($id) use ($app){
	$user = new Slim_Controllers_Bookmarks($app->db);
	$data = $user->getBookmark($id);
	echo json_encode($data);
});

$app->get('/bookmarks', function() use ($app){
	$user = new Slim_Controllers_Bookmarks($app->db);
	$data = $user->getBookmarks();
	echo json_encode($data);
});

$app->post('/bookmarks', function() use ($app) {
	$user = new Slim_Controllers_Bookmarks($app->db);
	$data = $user->setBookmark();
	echo json_encode($data);
});

$app->put('/bookmarks/:id', function($id) use ($app) {
	$user = new Slim_Controllers_Bookmarks($app->db);
	$data = $user->updateBookmark();
	echo json_encode($data);
});

$app->delete('/bookmarks/:id', function($id) use ($app) {
	$user = new Slim_Controllers_Bookmarks($app->db);
	$data = $user->deleteBookmark();
	echo json_encode($data);
});

?>