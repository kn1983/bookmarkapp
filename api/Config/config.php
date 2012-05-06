<?php

//Mode
define('MODE', 'development');

//Debugging
define('DEBUG', true);

//Logging
define('LOG_WRITER', null);
define('LOG_LEVEL', 4);
define('LOG_ENABLED', true);

//Database
define('DB_HOST', 'localhost');
define('DB_PORT', '');
define('DB_NAME', 'page');
define('DB_USER', 'root');
define('DB_PW', '');

//Cookies
define('COOKIE_NAME', 'page_kl7dk');
define('COOKIE_LIFETIME', '20 minutes');
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', null);
define('COOKIE_SECURE', false);
define('COOKIE_HTTPONLY', false);

//Encryption
define('COOKIE_SECRET_KEY', 'ds6kl7dk');
define('COOKIE_CIPHER', MCRYPT_RIJNDAEL_256);
define('COOKIE_CIPHER_MODE', MCRYPT_MODE_CBC);

//HTTP
define('HTTP_VERSION', '1.1');

//User groups
define('USER_ADMIN', 1);
define('USER_NEW', 2);
define('USER_NORMAL', 3);

//Templates
define('TEMPLATE_PATH', './templates');
define('VIEW', 'Slim_View');
?>