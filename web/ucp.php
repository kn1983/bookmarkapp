<?php

require 'common.php';

// Get requested page
$mode = $app->request()->params('mode');

// Check which action we are dealing with
switch ($mode)
{
	// User is logging out
	case 'logout':

		$app->session->sessionKill();
		$app->session->sessionBegin();
		header('Location: index');

	break;
}
?>