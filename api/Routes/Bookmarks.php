<?php

$app->get('/bookmarks/:id', 'getBookmark');
$app->get('/bookmarks', 'getBookmarks');
$app->post('/bookmarks', 'setBookmark');
$app->put('/bookmarks/:id', 'updateBookmark');
$app->delete('/bookmarks/:id', 'deleteBookmark');

?>