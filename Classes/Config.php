<?php

namespace Classes;

class Config 
{

	// Database settings
	const dbHost = 'localhost';
	const dbPort = '';
	const dbName = 'page';
	const dbUser = 'root';
	const dbPw 	 = '';

	// Some cookie setup
	const cookie_name = 'page_kl7dk';
	const cookie_domain = 'localhost';
	const cookie_path = '/';
	const cookie_secure = 0;
	const sessionLength = 3600;

	// CAPTCHA settings
	const captchaPrivateKey = '6LebFM8SAAAAAGGRx_b9v4aBCRryd-H_4tjQuxSn';
	const captchaPublicKey = '6LebFM8SAAAAAMu6fTaMEJOkZbtW42Za75jjmhbG';

	// User groups
	const userAdmin = 1;
	const userNew = 2;
	const userNormal = 3;
	const userGuest = 4;

	// User is a guest
	const guestId = 1;

	// Some times
	const oneHour = 3600;
}
?>