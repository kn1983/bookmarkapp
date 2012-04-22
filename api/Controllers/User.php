<?php

class UserController
{
	public $user_id;
	public $session_id;

	public $data;


	/**
	* Returns userdata
	*/
	public function getUserInformation($user_id)
	{
		global $app;

		$sql = "SELECT id, username
				FROM users
				WHERE id = '{$user_id}' 
				LIMIT 1";
		$result = $app->db->sql_query($sql);
		$this->data = $app->db->sql_fetchrow($result);


		echo json_encode($this->data);
	}

	public function addNewUser()
	{
		global $app;

		$submitReg	= (isset($_POST['register'])) ? true : false;

		/*$form_token = Forms::addFormKey('ucp_register');*/

		$data = array(
			'username'	=> $app->request()->post('username'),
			'password'	=> $app->request()->post('password'),
			'email'		=> $app->request()->post('email'),
		);


		if ($submitReg)
		{
			$this->checkRegistration($data);
		}

	}

	private function checkRegistration($data)
	{
		global $app;

		$validation = new Slim_Forms_FormValidator();
		$passwordHash = new Slim_Security_PasswordHash();

		$error = array();

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
			$error = $validation->validateData($data, array(
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
					'type'				=> $app->config('usergroup.new'),
					'username'			=> $data['username'],
					'username_clean'	=> strtolower($data['username']),
					'password'			=> $passwordHash->HashPassword($data['password']),
					'email'				=> strtolower($data['email']),
					'form_salt'			=> uniqid(),
					'last_visit'		=> time(),			
				);

				$sql = "INSERT INTO users " . $app->db->sql_build_array('INSERT', $sql_ary);
				$app->db->sql_query($sql);

				// Log in the user and redirect to the startpage or maybe change to user page or something later?
				$user_id = mysql_insert_id();
				$app->session->sessionCreate($user_id);

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
}
?>