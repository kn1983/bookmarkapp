<?php

class Slim_Session 
{
	public $data;

	// Cookie data
	public $cookie_id;
	public $cookie_key;
	public $session_id;

	// Info about user
	public $time;
	public $browser;
	public $ip;
	public $page_url;


	public function __construct(Slim_Http_Request $request, Slim_Http_Response $response, Slim_Database_Dbal $db, $settings)
	{

        $this->request = $request;
        $this->response = $response;
        $this->config = $settings;
        $this->db = $db;	

		$this->time 		= time();
		$this->browser		= $this->request->getUserAgent();
		$this->ip 			= $this->request->getIp();
		$this->server_name 	= $this->request->getHost();

		// Check if we have a cookie set
		if ($request->cookies($this->config['cookies.name'] . '_sid') || $request->cookies($this->config['cookies.name'] . '_u')) {
			$this->cookie_id 	= (int) $request->cookies($this->config['cookies.name'] . '_u');
			$this->cookie_key 	= $request->cookies($this->config['cookies.name'] . '_k');
			$this->session_id 	= $request->cookies($this->config['cookies.name'] . '_sid');
		}
	}

	/**
	* Starts the session management
	*
	* This is being run on all pages. Basically what it does
	* is to check if a session already exist and if not, then
	* we create one. :-)
	*/
	public function sessionBegin()
	{
		global $app;

		if (isset($this->session_id) && isset($this->cookie_id))
		{
			$sql = "SELECT u.*, s.*
					FROM sessions AS s
					LEFT JOIN users AS u
					ON u.id = s.user_id
					WHERE s.sid = '{$this->session_id}'
					LIMIT 1";
			$result = $this->db->sql_query($sql);
			$this->data = $this->db->sql_fetchrow($result);

			// Did the user exist?
			if (isset($this->data['user_id']))
			{
				// We will only check the first part of the ip to avoid trouble with non-static ips
				$s_ip = implode('.', array_slice(explode('.', $this->data['ip']), 0, 3));
				$u_ip = implode('.', array_slice(explode('.', $this->ip), 0, 3));

				// Cleaning the browser strings before comparing
				$s_browser = trim(strtolower(substr($this->data['browser'], 0, 149)));
				$u_browser = trim(strtolower(substr($this->browser, 0, 149)));

				// Is everything as it should? Great, let's move along!
				if ($u_ip === $s_ip && $s_browser === $u_browser) 
				{
					$session_expired = false;
					
					// Check if the session is still valid
					if (!$this->data['autologin']) 
					{
						if ($this->data['time'] < $this->time - $this->config['cookies.lifetime']) 
						{
							$session_expired = true;
						}
					}

					// We continue if the session did not expire or if we are dealing with an autologin
					if ($session_expired == false) {
						// Only update session if a minute has passed or if the user enters another page
						if ($this->time - $this->data['time'] > 60) 
						{
							$sql_ary = array(
								'time' => $this->time,
							);

							$sql = "UPDATE sessions SET " . $this->db->sql_build_array('UPDATE', $sql_ary) . "
									WHERE sid = '{$this->session_id}'";
							$this->db->sql_query($sql);
						}

						$this->data['logged_in'] = ($this->data['id'] != $this->config['user.guest'] && ($this->data['type'] == $this->config['usergroup.new'] || $this->data['type'] == $this->config['usergroup.normal'] || $this->data['user_type'] == $this->config['usergroup.admin'])) ? true : false;					
						return true;
					}

					$sql = "DELETE FROM sessions
							WHERE sid = '{$this->session_id}'";
					$this->db->sql_query($sql);
				
				}
			}
		}
		return false;
		// If we got this far it means no cookie... and tadaah! We need to create one ;-)
		/*$this->sessionCreate();*/

	}

	/**
	* Creates a new session
	*
	* Used when a session cookie does not exist, during 
	* registration and during login
	*/
	public function sessionCreate($user_id = false, $autologin = false)
	{
		global $app;

		$this->data = array();

		// If we're presented with an autologin key we'll join against it. Else if we've been passed a user_id we'll grab data based on that
		if (isset($this->cookie_key) && $this->cookie_key && $this->cookie_id && !sizeof($this->data)) 
		{
			$cookie_key = md5($this->cookie_key);

			$sql = "SELECT u.*, u.id AS user_id
					FROM users AS u
					LEFT JOIN session_keys AS k
					ON k.user_id = u.id
					WHERE u.id = '{$this->cookie_id}'
					AND u.type IN (" . $this->config['usergroup.new'] . ", " . $this->config['usergroup.normal'] . ", " . $this->config['usergroup.admin'] . ")
					AND k.key_id = '{$cookie_key}'";
			$result = $this->db->sql_query($sql);
			$this->data = $this->db->sql_fetchrow($result);

		} 
		else if ($user_id !== false && !sizeof($this->data)) 
		{
			
			$this->cookie_key = '';
			$this->cookie_id = $user_id;

			$sql = "SELECT u.*, u.id AS user_id
					FROM users AS u
					WHERE id = '{$this->cookie_id}'
					AND type IN (" . $this->config['usergroup.new'] . ", " . $this->config['usergroup.normal'] . ", " . $this->config['usergroup.admin'] . ")";
			$result = $this->db->sql_query($sql);
			$this->data = $this->db->sql_fetchrow($result);
		}

		// If no data was returned it means no key was returned or that the user did not exist in the db
		if (!sizeof($this->data) || !is_array($this->data)) 
		{
			$this->cookie_key = '';
			$this->cookie_id = $this->config['user.guest'];

			$sql = "SELECT u.*, u.id AS user_id 
					FROM users AS u
					WHERE id = '{$this->cookie_id}'";

			$result = $this->db->sql_query($sql);
			$this->data = $this->db->sql_fetchrow($result);
		}

		$this->data['last_visit'] = $this->time;
		$this->data['logged_in'] = ($this->data['id'] != $this->config['user.guest'] && ($this->data['type'] == $this->config['usergroup.new'] || $this->data['type'] == $this->config['usergroup.normal'] || $this->data['user_type'] == $this->config['usergroup.admin'])) ? true : false;

		$session_autologin = (($this->cookie_key || $autologin) && $this->data['logged_in']) ? true : false;

		$sql_ary = array(
			'user_id'		=> $this->data['id'],
			'last_visit'	=> $this->data['last_visit'],
			'time'			=> $this->time,
			'browser'		=> trim(substr($this->browser, 0, 149)),
			'ip'			=> $this->ip,
			'autologin'		=> ($session_autologin) ? 1 : 0,
		);

		$sql = "DELETE FROM sessions
				WHERE sid = '{$this->session_id}'
				AND user_id = " . $this->config['user.guest'];
		$this->db->sql_query($sql);

		$this->session_id = $this->data['sid'] = md5(uniqid());

		$sql_ary['sid'] = (string) $this->session_id;

		$sql = "INSERT INTO sessions " . $this->db->sql_build_array('INSERT', $sql_ary);
		$this->db->sql_query($sql);

		if ($session_autologin) 
		{
			$this->setLoginKey();
		}

		$this->data = array_merge($this->data, $sql_ary);

		// Set new cookies
		$this->setNewCookie('u', $this->cookie_id);
		$this->setNewCookie('k', $this->cookie_key);
		$this->setNewCookie('sid', $this->session_id);

		$sql = "SELECT COUNT(sid) AS sessions
				FROM sessions
				WHERE user_id = '{$this->data['user_id']}'
				AND time >= " . (int) ($this->time - $this->config['cookies.lifetime']);
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		if ($row['sessions'] <= 1 || empty($this->data['form_salt'])) 
		{

			$this->data['form_salt'] = uniqid();

			$sql = "UPDATE users
					SET form_salt = '{$this->data['form_salt']}'
					WHERE id = '{$this->data['user_id']}'";
			$this->db->sql_query($sql);
		}
	}

	/**
	* Destroys a session
	* Kills a session and resets all cookies
	*/
	public function sessionKill()
	{
		global $app;

		$sql = "DELETE FROM sessions
				WHERE sid = '{$this->session_id}'
				AND user_id = '{$this->data['user_id']}'";
		$this->db->sql_query($sql);

		if ($this->data['user_id'] != $this->config['user.guest']) {

			// Delete existing session, update last visit info first!
			if (!isset($this->data['time'])) 
			{
				$this->data['time'] = $this->time;
			}

			if ($this->cookie_key) 
			{
				$cookie_key = md5($this->cookie_key);

				$sql = "DELETE FROM session_keys
						WHERE user_id = '{$this->data['user_id']}'
						AND key_id = '{$cookie_key}'";
				$this->db->sql_query($sql);
			}

			// Reset the data array
			$this->data = array();

			$sql = "SELECT *
					FROM users
					WHERE id = " . $this->config['user.guest'];
			$result = $this->db->sql_query($sql);
			$this->data = $this->db->sql_fetchrow($result);
		}

		$this->setNewCookie('u', '');
		$this->setNewCookie('k', '');
		$this->setNewCookie('sid', '');

		$this->session_id = '';
	}

	/**
	* Sets a login key
	* Used when a user is logging in with a pesistent login alias autologin
	*/
	private function setLoginKey()
	{
		global $app;

		$key_id = uniqid(hexdec(substr($this->session_id, 0, 8)));

		$sql_ary = array(
			'key_id'	=> md5($key_id),
			'ip'		=> $this->ip,
			'browser'	=> $this->browser,
			'time'		=> $this->time
		);

		if (!$this->cookie_key) 
		{
			$sql_ary += array(
				'user_id'	=> $this->data['user_id']
			);
		}

		if ($this->cookie_key) 
		{
			$key = md5($this->cookie_key);

			$sql = "UPDATE session_keys
					SET " . $this->db->sql_build_array('UPDATE', $sql_ary) . "
					WHERE user_id = '{$this->data['user_id']}'
					AND key_id = '{$key}'";
		} 
		else 
		{
			$sql = "INSERT INTO session_keys " . $this->db->sql_build_array('INSERT', $sql_ary);
		}

		$this->db->sql_query($sql);

		$this->cookie_key = $key_id;

		return false;
	}

	/**
	* Sets a cookie value
	* Info: http://php.net/manual/en/function.setcookie.php
	*/	
	private function setNewCookie($name, $cookie_data)
	{
		$name = $this->config['cookies.name'] . '_' . $name;
		$expires = $this->time + 31536000;		
		$domain = $this->server_name;

		// setcookie($name, $cookie_data, $expires, '', $domain, false, false);
		setcookie($name, $cookie_data, $expires, '/');		
	}
}
?>