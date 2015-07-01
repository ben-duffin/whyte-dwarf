<?php
session_start();
if(!isset($_GET['task'])){
	die('<p>Nothing to do!</p>');
}else{
	switch($_GET['task']){
		case 'setup' :
			include('inc/setup.php');
		break;
		
		case 'crawl' :
			if(!isset($_SESSION['crawler'])) die('<p>No crawl parameters has been defined</p>');
			include('inc/crawl.php');
		break;
		
		case 'scrape' :
			if(!isset($_SESSION['crawler'])) die('<p>No crawl parameters has been defined</p>');		
			include('inc/scrape.php');
		break;
		
		case 'delete-scrape' :
			unlink('crawl_json/'.urldecode($_GET['crawl']));
		break;

		case 'delete-list' :
			unlink('crawl_lists/'.urldecode($_GET['crawl']));
		break;
		
		case 'index' :
			if(!isset($_SESSION['crawler'])) die('<p>No crawl parameters has been defined</p>');		
			include('inc/solr_update.php');
		break;
		
		case 'reset' :
			session_destroy();
			// Empty the lists and jsons
			$dirs = array('crawl_lists/', 'crawl_json/');
			foreach($dirs as $dir){
				$d = opendir($dir);
				while(($file = readdir($d)) != false){
					if($file != '.' && $file != '..'){
						unlink($dir.$file);
					}
				}
				closedir($d);
			}
		break;
	}
}

header('Location: index.php');
exit;

?>