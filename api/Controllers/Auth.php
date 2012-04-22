<?php

use Classes\Config;
use Classes\Session;
use Classes\Security\Validation;

class AuthController 
{
	public $user_id;
	public $session_id;

	public $data;

	public function __construct()
	{
		global $app;	

		$this->user_id    = (int) $app->getCookie($app->config('cookies.name') . '_u');
		$this->session_id = $app->getCookie($app->config('cookies.name') . '_sid');		
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
		global $app;

		$this->data['logged_in'] = ($this->data['id'] != $app->config('user.guest') && ($this->data['type'] == $app->config('usergroup.new') || $this->data['type'] == $app->config('usergroup.registered') || $this->data['user_type'] == $app->config('usergroup.admin'))) ? true : false;					
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
			'username'	=> $app->request()->post('username'),
			'password'	=> $app->request()->post('password'),
			'autologin'	=> $app->request()->post('autologin'),
		);

		if ($submit)
		{	
			$this->checkLogin($data);
		}		
	}

	private function checkLogin($data)
	{
		global $app;

		$validation = new Slim_Forms_FormValidator();
		$passwordHash = new Slim_Security_PasswordHash();

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
						AND type IN (" . $app->config('usergroup.new') . ", " . $app->config('usergroup.normal') . ", " . $app->config('usergroup.admin') . ")";
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
						$app->session->sessionCreate($row['id'], $data['autologin']);

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