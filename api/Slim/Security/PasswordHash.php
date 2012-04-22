<?php

#
# PHP password hashing framework.
# Written by Solar Designer.
#
# Reference: http://www.openwall.com/phpass/
#
class Slim_Security_PasswordHash 
{
	public $itoa64;
	public $iteration_count_log2;
	public $random_state;

	public function __construct()
	{
		global $app;
		
		$this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$this->iteration_count_log2 = 8;

		$this->random_state = microtime();
		$this->random_state .= $app->request()->getIp();
	}

	/**
	* Hash a password
	*/
	public function HashPassword($password)
	{
		$random = '';

		$random = $this->get_random_bytes(16);
		$hash = crypt($password, $this->gensalt_blowfish($random));

		return $hash;
	}

	/**
	* Check if a password is correct
	*/
	public function CheckPassword($password, $stored_hash)
	{
		$hash = $this->crypt_private($password, $stored_hash);

		if ($hash[0] == '*') {
			$hash = crypt($password, $stored_hash);
		}

		return $hash == $stored_hash;
	}	

	private function get_random_bytes($count)
	{
		$output = '';

		for ($i = 0; $i < $count; $i += 16) {
			$this->random_state =
			    md5(microtime() . $this->random_state);
			$output .=
			    pack('H*', md5($this->random_state));
		}
			
		$output = substr($output, 0, $count);

		return $output;
	}

	private function encode64($input, $count)
	{
		$output = '';
		$i = 0;
		do {
			$value = ord($input[$i++]);
			$output .= $this->itoa64[$value & 0x3f];
			if ($i < $count)
				$value |= ord($input[$i]) << 8;
			$output .= $this->itoa64[($value >> 6) & 0x3f];
			if ($i++ >= $count)
				break;
			if ($i < $count)
				$value |= ord($input[$i]) << 16;
			$output .= $this->itoa64[($value >> 12) & 0x3f];
			if ($i++ >= $count)
				break;
			$output .= $this->itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);

		return $output;
	}

	private function crypt_private($password, $setting)
	{
		$output = '*0';
		if (substr($setting, 0, 2) == $output)
			$output = '*1';

		$id = substr($setting, 0, 3);

		if ($id != '$P$' && $id != '$H$')
			return $output;

		$count_log2 = strpos($this->itoa64, $setting[3]);
		if ($count_log2 < 7 || $count_log2 > 30)
			return $output;

		$count = 1 << $count_log2;

		$salt = substr($setting, 4, 8);
		if (strlen($salt) != 8)
			return $output;

		$hash = md5($salt . $password, TRUE);
			
		do {
			$hash = md5($hash . $password, TRUE);
		} 
		while (--$count);

		$output = substr($setting, 0, 12);
		$output .= $this->encode64($hash, 16);

		return $output;
	}

	private function gensalt_blowfish($input)
	{
		$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$output = '$2a$';
		$output .= chr(ord('0') + $this->iteration_count_log2 / 10);
		$output .= chr(ord('0') + $this->iteration_count_log2 % 10);
		$output .= '$';

		$i = 0;
		do {
			$c1 = ord($input[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;
			if ($i >= 16) {
				$output .= $itoa64[$c1];
				break;
			}

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);

		return $output;
	}
}
?>