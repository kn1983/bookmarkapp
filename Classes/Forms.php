<?php

namespace Classes;

use Classes\Request;

class Forms
{

	/**
	* Adds a hidden token to a form. Basically what it does is to check that the
	* recieved data is the same as the submitted one and that it is coming from 
	* the page that it should come from.
	*/
	public static function addFormKey($form_name)
	{
		global $session;

		$now = time();

		$token = md5($now . $session->data['form_salt'] . $form_name);

		$s_fields = self::buildHiddenFields(array(
			'creation_time' => $now,
			'form_token'	=> $token,
		));

		return $s_fields;		
	}

	/**
	* Validates the string generated from addFormKey
	*/
	public static function checkFormKey($form_name)
	{
		global $session;

		$creation_time	= Request::requestVar('creation_time', false);
		$token 			= Request::requestVar('form_token', false);

		if ($creation_time && $token) 
		{

			$diff = time() - $creation_time;

			// Only pass forms the are not older than 1 hour
			if ($diff <= Config::oneHour && $creation_time != 0) 
			{
				$key = md5($creation_time . $session->data['form_salt'] . $form_name);

				if ($key === $token) 
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	* Adds hidden fields to a form
	*/
	public static function buildHiddenFields($field_ary)
	{
		$s_hidden_fields = '';

		foreach ($field_ary as $name => $vars) 
		{
			$s_hidden_fields .= self::_buildHiddenFields($name, $vars);
		}

		return $s_hidden_fields;
	}

	/**
	* A helper for the buildHiddenFields function
	*/
	private function _buildHiddenFields($key, $value)
	{
		$hidden_fields = '';

		if (!is_array($value)) 
		{
			$hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
		} 
		else 
		{
			foreach ($value as $_key => $_value) 
			{
				$hidden_fields .= self::_buildHiddenFields($key . '[' . $_key . ']', $_value);
			}
		}

		return $hidden_fields;
	}
}
?>