<?php

use Classes\Config;
use Classes\Forms;
use Classes\Security\FormValidation;

require 'common.php';
require _DIR_ . 'api/Classes/Security/FormValidation.php';
require _DIR_ . 'api/Classes/Security/PasswordHash.php';
require _DIR_ . 'api/Classes/Forms.php';

// Get requested page
$mode = $app->request()->params('mode');

// Just do a redirect if the user tries to access these pages while logged in
if ($mode == 'register' || $mode == 'login')
{
	if ($session->data['logged_in'])
	{
		header('Location: index');
	}
}

// Check which action we are dealing with
switch ($mode)
{
	// User is registering
	case 'register':

		$submitReg	= (isset($_POST['register'])) ? true : false;

		/*$form_token = Forms::addFormKey('ucp_register');*/

		$data = array(
			'username'	=> $request->requestVar('username', ''),
			'password'	=> $request->requestVar('password', ''),
			'email'		=> $request->requestVar('email', ''),
		);


		if ($submitReg)
		{
			checkRegistration($data);
		}

	break;

	// User is logging in
	case 'login':

		$submit	= (isset($_POST['login'])) ? true : false;

		/*$form_token = Forms::addFormKey('ucp_login');*/

		$data = array(
			'username'	=> $request->requestVar('username', ''),
			'password'	=> $request->requestVar('password', ''),
			'autologin'	=> $request->requestVar('autologin', 0),
		);

		if ($submit)
		{	
			checkLogin($data);
		}

	break;

	// User is logging out
	case 'logout':

		$session->sessionKill();
		$session->sessionBegin();
		header('Location: index');

	break;
}

/**
* Function for checking a registration attempt
*/
function checkRegistration($data)
{
	global $db, $session;

	$error = array();

	$passwordHash = new Classes\Security\PasswordHash();

	$message = array(
		'registration' 	=> false,
		'error'			=> false,
	);	

/*	if (!Forms::checkFormKey('ucp_register'))
	{
		echo json_encode('An error occurred while making the request. Reload the page and try again');
	}
	else
	{ */
		$error = FormValidation::validateData($data, array(
			'password'		=> array(
				array('string', 'password', 5, 60),
				array('password')),				
			'email'			=> array(
				array('string', 'e-mail address', 6, 60),
				array('email')),
			'username'		=> array(
				array('string', 'username', 2, 30),
				array('username', '')),						
		));

		if (!sizeof($error))
		{
			$sql_ary = array(
				'type'				=> Config::USER_NEW,
				'username'			=> $data['username'],
				'username_clean'	=> strtolower($data['username']),
				'password'			=> $passwordHash->HashPassword($data['password']),
				'email'				=> strtolower($data['email']),
				'form_salt'			=> uniqid(),
				'last_visit'		=> time(),			
			);

			$sql = "INSERT INTO users " . $db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);

			// Log in the user and redirect to the startpage or maybe change to user page or something later?
			$user_id = mysql_insert_id();
			$session->sessionCreate($user_id);

			$message['registration'] = true;
			echo json_encode($message);
		}
		else
		{
			$message['error'] = $error;
			echo json_encode($message);
		}		
/*	}*/	
}

/**
* Function for checking a login attempt
*/
function checkLogin($data)
{
	global $db, $session;

	$passwordHash = new Classes\Security\PasswordHash();

	$message = array(
		'login' 	=> false,
		'error'		=> false,
	);

/*	if (!Forms::checkFormKey('ucp_login'))
	{
		echo json_encode('An error occurred while making the request. Reload the page and try again');
	}
	else
	{	*/		
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
					AND type IN (" . Config::USER_NEW . ", " . Config::USER_NORMAL . ", " . Config::USER_ADMIN . ")";
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

					$message['login'] = true;
					echo json_encode($message);
				} 
				else 
				{
					$message['error'] = 'You have entered a wrong username or password';
					echo json_encode($message);				
				}
			}
			else
			{
				$message['error'] = 'You have entered a wrong username or password';
				echo json_encode($message);						
			}
		}
/*	}*/
}
?>