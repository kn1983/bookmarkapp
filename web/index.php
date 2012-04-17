<?php

require 'common.php';

// $test = $request->requestVar('p', 1);

// $template = $twig->loadTemplate('page/index.html');

// echo $template->render(array(
// 	// Holds basic information	
// 	'topics'			=> 'hejdå',	
// ));
echo '<!DOCTYPE html>
<html>
    <head>       
        <title>My Webpage</title>
        <link type="text/css" rel="stylesheet" href="web/css/base.css" />
        <link type="text/css" rel="stylesheet" href="web/css/header.css" />
        <link type="text/css" rel="stylesheet" href="web/css/forms.css" />
    </head>
    <body>
		    <div id="header">Header</div>
		<div id="sidebarFirst">Sidebar first</div>
		<div id="content">Content</div>
		<div id="sidebarSecond">Sidebar second</div>
		<div id="footer">Footer</div>
		<script type="text/template" id="login-form-tpl">	
			<form method="post" action="login" id="login">
				<fieldset>				
					<label for="username">Username: </label>
					<input type="text" name="username" id="username" value="{{ data.username }}" />

					<label for="password">Password: </label>
					<input type="password" id="password" name="password" value="{{ data.password }}" />
					
					<div class="radioBtn">
						<label for="autologin"><strong>Log me on automatically each visit:</strong> </label>
						<input type="checkbox" name="autologin" id="autologin" value="1" />							
					</div>

					<span><a href="{U_SEND_PASSWORD}">I forgot my password</a> | <a href="{U_SEND_PASSWORD}">I forgot my E-mail</a></span>
					<input type="hidden" name="login" value="1" />
					<input class="btnGrey loginBtn" type="submit" value="Login" />
				</fieldset>
			</form>
        </script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="web/js/libs/underscore-min.js"></script>
        <script type="text/javascript" src="web/js/libs/backbone-min.js"></script>
        <script type="text/javascript" src="web/js/main.js"></script>
	</body>
</html>';
?>