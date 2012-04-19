<?php

namespace Classes;

class Config 
{

	// Database settings
	const DB_HOST = 'localhost';
	const DB_PORT = '';
	const DB_NAME = 'page';
	const DB_USER = 'root';
	const DB_PW   = '';

	// Some cookie setup
	const COOKIE_NAME    = 'page_kl7dk';
	const COOKIE_DOMAIN  = 'localhost';
	const COOKIE_PATH    = '/';
	const COOKIE_SECURE  = 0;
	const SESSION_LENGTH = 3600;

	// User groups
	const USER_ADMIN  = 1;
	const USER_NEW 	  = 2;
	const USER_NORMAL = 3;
	const USER_GUEST  = 4;

	// User is a guest
	const GUEST_ID = 1;

	// Some times
	const ONE_HOUR = 3600;
}
?>