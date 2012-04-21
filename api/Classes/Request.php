<?php

namespace Classes;

class Request
{
	/**
	* Request a cookie value
	*/
	public static function getCookie($val, $fallback)
	{
		if (isset($_COOKIE[$val])) 
		{
			return htmlspecialchars($_COOKIE[$val]);
		}

		return $fallback;
	}

	/**
	* Method to retrieve server variables. 
	*/
	public static function server($var)
	{
		$value = '';

		if (isset($_SERVER[$var])) 
		{
			$value = htmlspecialchars($_SERVER[$var]);
		} 
		else if (getenv($var)) 
		{
			$value = getenv($var);
		}

		return $value;
	}	

	/**
	* Request a value from a get or post
	*/
	public static function requestVar($val, $fallback)
	{

		if (!isset($_GET[$val]) && !isset($_POST[$val])) 
		{
			return $fallback;
		}

		$_REQUEST[$val] = isset($_POST[$val]) ? $_POST[$val] : $_GET[$val];

		$var = $GLOBALS['_REQUEST'][$val];

		if (is_numeric($fallback)) 
		{
			if (!is_numeric($var)) {
				return $fallback;
			}

			$var = (int) $var;
		}

		return $var;
	}	
}
?>