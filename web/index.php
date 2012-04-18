<?php

require 'common.php';

$test = $request->requestVar('p', 1);

$template = $twig->loadTemplate('page/index.html');

echo $template->render(array(
	// Holds basic information	
	'topics'			=> 'hejdå',	
));
?>