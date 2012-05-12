<?php

$app->get('/users/:id', 'getUser');
$app->get('/users', 'getUsers');
$app->post('/users', 'setUser');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');

function getUser($id, $return = false) 
{
	$db = Slim::getInstance()->db();

    $sql = "SELECT id, username
            FROM users
            WHERE id = '{$id}'";
    $result = $db->sql_query($sql);
    $data = $db->sql_fetchrow($result);

    echo json_encode($data);
}

function getUsers() 
{
	$db = Slim::getInstance()->db();
    $data = array();

    $sql = "SELECT id, username FROM users";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        $data[] = $row;
    }

    echo json_encode($data);	
}

function setUser() 
{
	$db = Slim::getInstance()->db();
    $session = Slim::getInstance()->session();
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $user = json_decode($body);

    $validation = new Slim_Forms_FormValidator();
    $passwordHash = new Slim_Security_PasswordHash();
    $message = array('status' => false, 'error' => false);

    $data = array(
        'username'  => $user->username,
        'password'  => $user->password,
        'email'     => $user->email,
    );

    $error = $validation->validateData($data, array(
        'password'      => array(
            array('string', 'password', 5, 60),
            array('password')),             
        'email'         => array(
            array('string', 'e-mail address', 6, 60),
            array('email')),
        'username'      => array(
            array('string', 'username', 2, 30),
            array('username', '')),                     
    ));

    if (!sizeof($error)) {
        $sql_ary = array(
            'type'              => USER_NEW,
            'username'          => $data['username'],
            'username_clean'    => strtolower($data['username']),
            'password'          => $passwordHash->HashPassword($data['password']),
            'email'             => strtolower($data['email']),
            'form_salt'         => uniqid(),
            'last_visit'        => time(),          
        );

        $sql = "INSERT INTO users " . $db->sql_build_array('INSERT', $sql_ary);
        $db->sql_query($sql);

        $data['id'] = mysql_insert_id();
        $session->sessionCreate($data['id']);
        getUser($data['id']);

    } else {
        $message['error'] = $error;
        return $message;
    }
}

function updateUser() 
{
    $data = 'Updating a user';
    echo json_encode($data);
}

function deleteUser() 
{
    $data = 'Deleting a user';
    echo json_encode($data);
}

?>