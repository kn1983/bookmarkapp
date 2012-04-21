<?php

namespace Classes\Security;

#
# Form Validator.
# 
# Starts with validateData and then do calls to 
# the other methods depending on the array given.
#
class FormValidation
{

	public function validateData($data, $val_ary)
	{
		$error = array();
		
		foreach ($val_ary as $var => $val_seq)
		{
			if (!is_array($val_seq[0]))
			{
				$val_seq = array($val_seq);
			}
			
			foreach ($val_seq as $validate)
			{
				$function = array_shift($validate);
				array_unshift($validate, $data[$var]);

				$newValidation = new FormValidation();
				if ($result = call_user_func_array(array($newValidation, 'validate_' . $function), $validate))
				{
					$error = $result;
				}
			}
		}

		return $error;
	}

	/**
	* Checks if a user exist.
	* Returns an error if the user does not exist.
	*/
	private function validate_userexist($username)
	{
		global $db;

		$username = strtolower($username);
		
		$sql = "SELECT username 
				FROM users 
				WHERE username_clean = '{$username}'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		
		// Check if the user exist 
		if (!$row['username']) 
		{
			return 'This user does not exist';
		}

		return false;
	}

	/*
	* Validates a string.
	* Checking min and max length.
	*/
	private function validate_string($string, $field, $min = 0, $max = 0)
	{	
		if ($min && strlen(htmlspecialchars_decode($string)) < $min)
		{
			return 'The ' . $field . ' is too short. It needs to be at least ' . $min . ' characters';
		}
		else if ($max && strlen(htmlspecialchars_decode($string)) > $max)
		{
			return 'The ' . $field . ' is too long. It can only contain ' . $max . ' characters';
		}

		return false;
	}

	/*
	* Validates a number.
	* Checking min and max length.	
	*/
	private function validate_num($num, $field, $min = 0, $max = 1E99)
	{	
		if ($num < $min || $num > $max)
		{
			return 'The ' . $field . ' needs to be a number between ' . $min . ' and ' . $max;
		}

		return false;
	}

	/**
	* Validate a website address.
	* Adds 'http://' and checks header if the website exist.
	*/
	private function validate_website($url)
	{
		if ($url == '')
		{
			return;
		}

		if ((strpos($url, 'http')) === false)
		{
			$url = 'http://' . $url;
		}
		
		$header = @get_headers($url);
		
		if (is_array($header))
		{
			if($header[0] == 'HTTP/1.1 404 Not Found') 
			{
				return $url . ' is not a valid website';
			}
			else 
			{
				return false;
			}
		}

		return $url . ' is not a valid website';
	}

	/**
	* Validate an image.
	* Check mimetype for a valid image.
	* Valid images are gif, jpg and png.
	*/
	private function validate_image($url)
	{
		if ($url == '')
		{
			return;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Made some changes here without checking it. Just a note.		
		if(curl_exec($ch)!==FALSE)
		{
			$info = getimagesize($url);
			$mimetype = image_type_to_mime_type($info[2]);			
		}
		else
		{
			return 'This is not a valid image.';
		}
		
		if (!$mimetype == 'image/giff' && 
			!$mimetype == 'image/gif' && 
			!$mimetype == 'image/png' &&
			!$mimetype == 'image/x-png' && 
			!$mimetype == 'image/jpg' && 
			!$mimetype == 'image/jpeg' &&
			!$mimetype == 'image/pjpeg')
		{
			return 'This is not a valid image.';
		}
		
		return false;
	}

	/**
	* Validates a username
	* Checks if the username already exist or it it contains illegal characters
	*/
	private function validate_username($username)
	{
		global $db;

		if ($username == '')
		{
			return false;
		}	

		$clean_username = strtolower($username);

		if (!preg_match('#^[a-zA-Z0-9]+$#u', $username))
		{
			return 'This is not a valid username';
		}	

		$sql = "SELECT username
				FROM users
				WHERE username_clean = '{$clean_username}'";		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		if ($row)
		{
			return 'The username ' . $username. ' is already taken';
		}

		return false;
	}

	/**
	* Validates a password
	* As for now only checks that it does not contain illegal
	* characters. But could possible also do some complexity check? 
	*/	
	private function validate_password($password)
	{
		if ($password == '')
		{
			return false;
		}

		if (!preg_match('#^[a-zA-Z0-9]+$#u', $password))
		{
			return 'The password can only contain letter and numbers';
		}

		return false;
	}

	/**
	* Validates an email
	* Checks if the email already exist and that it is a valid email
	*/
	private function validate_email($email)
	{
		global $db;

		if ($email == '')
		{
			return false;
		}	

		if (!preg_match('/^' . '([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)' . '$/i', $email))
		{
			return 'This is not a valid e-mail';
		}

		$email = strtolower($email);

		$sql = "SELECT email
				FROM users
				WHERE email = '{$email}'";		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		if ($row)
		{
			return 'This email ' . $email. ' is already used by someone';
		}

		return false;
	}
}
?>