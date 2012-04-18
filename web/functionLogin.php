<?php

use Classes\Config;
use Classes\Forms;
use Classes\Security\FormValidation;

require . 'common.php';
require _DIR_ . 'Classes/Security/FormValidation.php';
require _DIR_ . 'Classes/Security/PasswordHash.php';
require _DIR_ . 'Classes/Forms.php';

$error = array();

$submit	= (isset($_POST['login'])) ? true : false;

$form_token = Forms::addFormKey('ucp_login');

$data = array(
	'username'	=> $request->requestVar('username', ''),
	'password'	=> $request->requestVar('password', ''),
	'autologin'	=> $request->requestVar('autologin', 0),
);

if ($submit)
{	
	$error[] = checkLogin($data);
}

/**
* Function for checking a login attempt
*/
function checkLogin($data)
{
	global $db, $session;

	$passwordHash = new Classes\Security\PasswordHash();

	if (!Forms::checkFormKey('ucp_login'))
	{
		return 'An error occurred while making the request. Reload the page and try again';
	}
	else
	{			
		if (!$data['username'] || !$data['password']) {
			return 'You need to enter a username and a password';
		}

		$username_clean = strtolower($data['username']);

		$sql = "SELECT id, username, password, email, type
				FROM users
				WHERE username_clean = '{$username_clean}'
				AND type IN (" . Config::userNew . ", " . Config::userNormal . ", " . Config::userAdmin . ")";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		if ($row['id']) {
			// Check match of passwords and log in the user if match
			if ($passwordHash->CheckPassword($data['password'], $row['password']))
			{
				$sql_ary = array(
					'password'	 => $passwordHash->HashPassword($data['password']),
					'last_visit' => time(),					
				);

				$sql = 'UPDATE users SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE id = ' . $row['id'];
				$db->sql_query($sql);							

				// Creates the session that actually logs in the user
				$session->sessionCreate($row['id'], $data['autologin']);
				header('Location: index');
			} 
			else 
			{
				return 'You have entered a wrong username or password';
			}
		}
		else
		{
			return 'You have entered a wrong username or password';			
		}
	}
}
?>