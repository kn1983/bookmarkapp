<?php

use Classes\Config;

define('_DIR_', '../');

// Include some common classes
require _DIR_ . 'Classes/Db.php';
require _DIR_ . 'Classes/Request.php';
require _DIR_ . 'Classes/Config.php';

// Init some global classes
$db 	  = new Classes\Db();
$request  = new Classes\Request();

$data = array();

$user_id 	= (int) $request->getCookie(Config::COOKIE_NAME . '_u', 0);
$session_id = $request->getCookie(Config::COOKIE_NAME . '_sid', '');

$sql = "SELECT u.id, u.type, u.username, u.form_salt
		FROM sessions AS s
		LEFT JOIN users AS u
		ON u.id = s.user_id
		WHERE s.sid = '{$session_id}' 
		AND s.user_id = '{$user_id}'
		LIMIT 1";
$result = $db->sql_query($sql);
$data = $db->sql_fetchrow($result);

$data['logged_in']  = ($data['id'] != Config::GUEST_ID && ($data['type'] == Config::USER_NEW || $data['type'] == Config::USER_NORMAL || $data['type'] == Config::USER_ADMIN)) ? true : false;

echo json_encode($data);
?>