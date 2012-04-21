<?php

require 'common.php';

$template = $twig->loadTemplate('page/index.html');

echo $template->render(array(
	// Holds basic information	
	'topics'			=> 'hejdå',	
));
?>