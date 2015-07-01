<?php

	// Get Config
	include('config/config.php');
	include('classes/Scrape.class.php');
	
	$silo 	= $_SESSION['crawler']['silo'];
	$auth 	= ($_SESSION['crawler']['auth'] == false) ? false : "{$_SESSION['crawler']['user']}:{$_SESSION['crawler']['pass']}";
	$list 	= file_get_contents('crawl_lists/'.urldecode($_GET['crawl']));
	$links 	= unserialize($list);
	$json 	= array();
	Scrape::getCookies($_SESSION['crawler']['domain']);
	foreach($links as $idx => $link){
		$crawl_delay 														= mt_rand(1, MAX_CRAWL_DELAY); // random wait-time between 1 second and $max_delay seconds
		sleep($crawl_delay);
		
		$scrape 																= Scrape::fetch($link, $auth, $_SESSION['crawler']['respect_robots_meta'], $_SESSION['crawler']['respect_canonical']);
		if($scrape == false)										continue;
		$title 																	= $scrape['title'];
		$description 														= $scrape['description'];
		$content 																= $scrape['plaintext'];
		$keywords																= $scrape['keywords'];
		$json_member 														= array();
		$json_member['silo']										= $_SESSION['crawler']['silo'];
		$json_member['id'] 											=	$json_member['page_url'] = $link;
		$json_member['page_md5'] 								= md5($content);
		$json_member['page_title'] 							= $title;
		$json_member['page_meta_description'] 	= $description;
		$json_member['page_meta_keywords'] 			= $keywords;				
		$json_member['page_content'] 						= $content;
		$json[] 																= $json_member;
	}
	
	
	die(sprintf('<pre>%s</pre>', print_r($json, true)));
	$json 		= json_encode($json);
	$filename = 'crawl_json/'.$_SESSION['crawler']['silo'].'-'.date('dmY-His').'.crawl.json';
	$filename = str_replace(array('https', 'http', '://'), '', $filename);
	$fp 			= fopen($filename, 'w');
	fwrite($fp, $json, strlen($json));
	fclose($fp);
?>