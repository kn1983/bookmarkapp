<?php

require 'common.php';

// Get requested page
$mode = $app->request()->params('mode');
$session = Slim::getInstance()->session();

// Check which action we are dealing with
switch ($mode)
{
	// User is logging out
	case 'logout':

		$session->sessionKill();
		$session->sessionBegin();
		header('Location: index');

	break;
}
?>