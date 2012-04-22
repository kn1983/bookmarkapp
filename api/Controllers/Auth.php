<?php

use Classes\Config;
use Classes\Session;
use Classes\Security\PasswordHash;
use Classes\Security\Validation;

class AuthController 
{
	public $user_id;
	public $session_id;

	public $data;

	public function __construct()
	{
		global $app;

		$this->user_id    = (int) $app->getCookie(Config::COOKIE_NAME . '_u');
		$this->session_id = $app->getCookie(Config::COOKIE_NAME . '_sid');		
	}

	public function getAuthInformation()
	{
		global $app;

		$sql = "SELECT u.id, u.type, u.username, u.form_salt
				FROM sessions AS s
				LEFT JOIN users AS u
				ON u.id = s.user_id
				WHERE s.sid = '{$this->session_id}' 
				AND s.user_id = '{$this->user_id}'
				LIMIT 1";
		$result = $app->db->sql_query($sql);
		$this->data = $app->db->sql_fetchrow($result);

		$this->setLoginStatus();

		echo json_encode($this->data);
	}

	public function setLoginStatus() {
		$this->data['logged_in'] = ($this->data['type'] == Config::USER_NEW || $this->data['type'] == Config::USER_NORMAL || $this->data['type'] == Config::USER_ADMIN) ? true : false;
	}

	public function getLoginStatus() {
		return $this->data['logged_in'];
	}

	public function logInUser()
	{
		global $app;

		$submit	= (isset($_POST['login'])) ? true : false;

		/*$form_token = Forms::addFormKey('ucp_login');*/

		$data = array(
			'username'	=> $app->request()->post('username', ''),
			'password'	=> $app->request()->post('password', ''),
			'autologin'	=> $app->request()->post('autologin', 0),
		);

		if ($submit)
		{	
			$this->checkLogin($data);
		}		
	}

	private function checkLogin($data)
	{
		global $app;

		require 'Classes/Session.php';
		require 'Classes/Security/PasswordHash.php';
		require 'Classes/Security/FormValidation.php';
		$session = new Classes\Session();
		$validation = new Classes\Security\FormValidation();
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
				$result = $app->db->sql_query($sql);
				$row = $app->db->sql_fetchrow($result);

				if ($row['id']) {
					// Check match of passwords and log in the user if match
					if ($passwordHash->CheckPassword($data['password'], $row['password']))
					{
						$sql_ary = array(
							'password'	 => $passwordHash->HashPassword($data['password']),
							'last_visit' => time(),					
						);

						$sql = 'UPDATE users SET ' . $app->db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE id = ' . $row['id'];
						$app->db->sql_query($sql);							

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
}
?>