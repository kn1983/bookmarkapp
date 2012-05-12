<?php
if(isset($_POST['url'])){
	$url = $_POST['url'];
	echo get_page_title($url);
}
function get_page_title($url){

	if( !($data = file_get_contents($url)) ) return false;

	if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
		return trim($t[1]);
	} else {
		return false;
	}
}
?>