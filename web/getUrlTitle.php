<?php
if(isset($_POST['url'])){
	$url = addHttpToUrl($_POST['url']);
	$title = get_page_title($url);

	echo json_encode(array("urltitle" => $title, "url" => $url));
}
function get_page_title($url){
	if( !($data = @file_get_contents($url)) ) return false;

	if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
		return trim($t[1]);
	} else {
		return false;
	}
}
function addHttpToUrl($url){
	if(!preg_match('/^((http|https|ftp):\/\/).*$/', $url)){
		$url = 'http://'. $url;
	}
	return $url;
}
?>


