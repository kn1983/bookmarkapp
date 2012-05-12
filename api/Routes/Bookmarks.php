<?php

$app->get('/bookmarks/:id', 'getBookmark');
$app->get('/bookmarks', 'getBookmarks');
$app->post('/bookmarks', 'setBookmark');
$app->put('/bookmarks/:id', 'updateBookmark');
$app->delete('/bookmarks/:id', 'deleteBookmark');

function getBookmark($id, $return = false) 
{
	$db = Slim::getInstance()->db();

    $sql = "SELECT id, title, url
            FROM bookmarks
            WHERE id = '{$id}'";
    $result = $db->sql_query($sql);
    $data = $db->sql_fetchrow($result);

    echo json_encode($data);
}

function getBookmarks() 
{
	$db = Slim::getInstance()->db();
	$data = array();

    $sql = "SELECT id, title, url 
    		FROM bookmarks";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        $data[] = $row;
    }

    echo json_encode($data);	
}

function setBookmark() 
{
	$db = Slim::getInstance()->db();
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $bookmark = json_decode($body);

    $validation = new Slim_Forms_FormValidator();
    $message = array('status' => false, 'error' => false);

    $data = array(
        'title' => $bookmark->title,
        'url'  	=> $bookmark->url,
    );

    $error = $validation->validateData($data, array(
        'url' => array(
            array('string', 'url', 5, 60),
            array('website')),                     
    ));

    if (!sizeof($error)) {
        $sql_ary = array(
            'title'             => $data['title'],
            'url'               => $data['url'],         
        );

        $sql = "INSERT INTO bookmarks " . $db->sql_build_array('INSERT', $sql_ary);
        $db->sql_query($sql);

        $data['id'] = mysql_insert_id();
        getBookmark($data['id']);

    } else {
        $message['error'] = $error;
        return $message;
    }
}

function updateBookmark() 
{
    $data = 'Updating a bookmark';
    echo json_encode($data);
}

function deleteBookmark() 
{
    $data = 'Deleting a bookmark';
    echo json_encode($data);
}

?>