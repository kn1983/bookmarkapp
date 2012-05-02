<?php

$app->get('/auth', 'getAuth');
$app->post('/auth', 'setAuth');
$app->get('/auth/:logout', 'deleteAuth')->conditions(array('logout' => 'logout'));

function getAuth()
{
	$db = Slim::getInstance()->db();
    $request = Slim::getInstance()->request();
    $settings = Slim::getInstance()->settings();

    $user_id    = $request->cookies($settings['cookies.name'] . '_u');
    $session_id = $request->cookies($settings['cookies.name'] . '_sid');         

    $sql = "SELECT u.id, u.type, u.username, u.form_salt
            FROM sessions AS s
            LEFT JOIN users AS u
            ON u.id = s.user_id
            WHERE s.sid = '{$session_id}' 
            AND s.user_id = '{$user_id}'
            LIMIT 1";
    $result = $db->sql_query($sql);
    $data = $db->sql_fetchrow($result);

    $data['logged_in'] = ($data['type'] == $settings['usergroup.new'] || $data['type'] == $settings['usergroup.normal'] || $data['type'] == $settings['usergroup.admin']) ? true : false;

    echo json_encode($data);
}

function setAuth()
{
	$db = Slim::getInstance()->db();
    $request = Slim::getInstance()->request();
    $settings = Slim::getInstance()->settings();
    $session = Slim::getInstance()->session();

    $validation = new Slim_Forms_FormValidator();
    $passwordHash = new Slim_Security_PasswordHash();
    $message = array('login' => false, 'error' => false);

    $data['username']  = $request->post('username');
    $data['password']  = $request->post('password');
    $data['autologin'] = $request->post('autologin');

    if (!$data['username'] || !$data['password']) {           
        $message['error'] = 'You need to enter a username and a password';
        echo json_encode($message);
    }

    if (!$message['error'])
    {
        $username_clean = strtolower($data['username']);

        $sql = "SELECT id, username, password, email, type
                FROM users
                WHERE username_clean = '{$username_clean}'
                AND type IN (" . $settings['usergroup.new'] . ", " . $settings['usergroup.normal'] . ", " . $settings['usergroup.admin'] . ")";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);

        if ($row['id']) {
            if ($passwordHash->CheckPassword($data['password'], $row['password']))
            {
                $sql_ary = array(
                    'password'   => $passwordHash->HashPassword($data['password']),
                    'last_visit' => time(),                 
                );

                $sql = 'UPDATE users SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
                        WHERE id = ' . $row['id'];
                $db->sql_query($sql);                          

                $session->sessionCreate($row['id'], $data['autologin']);

                $message['login'] = true;
                echo json_encode($message);
            } else {
                $message['error'] = 'You have entered a wrong username or password';
                echo json_encode($message);            
            }
        } else {
            $message['error'] = 'You have entered a wrong username or password';
            echo json_encode($message);                 
        }
    }
}

function deleteAuth()
{
	$session = Slim::getInstance()->session();

	$session->sessionKill();
    $session->sessionBegin();
	/*$app->redirect('/');*/
	header('Location: index');
}

?>