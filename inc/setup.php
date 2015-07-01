<?php
	$_SESSION['crawler'] = array();
	if(isset($_POST['user']) && $_POST['user'] != '') $_SESSION['crawler']['user'] = $_POST['user'];
	if(isset($_POST['pass']) && $_POST['pass'] != '') $_SESSION['crawler']['pass'] = $_POST['pass'];
	if((isset($_POST['user']) && $_POST['user'] != '') && (isset($_POST['pass']) && $_POST['pass'] != '')){
		$_SESSION['crawler']['auth'] = true;
	}else{
		$_SESSION['crawler']['auth'] = false;
	}

	if(isset($_POST['respect_robots_txt'])){
		$_SESSION['crawler']['respect_robots_txt'] = true;
	}else{
		$_SESSION['crawler']['respect_robots_txt'] = false;		
	}
	if(isset($_POST['respect_robots_meta'])){
		$_SESSION['crawler']['respect_robots_meta'] = true;
	}else{
		$_SESSION['crawler']['respect_robots_meta'] = false;		
	}
	if(isset($_POST['respect_canonical'])){
		$_SESSION['crawler']['respect_canonical'] = true;
	}else{
		$_SESSION['crawler']['respect_canonical'] = false;		
	}
	
	$_SESSION['crawler']['domain'] = $_POST['domain'];
	
	// Make Silo		
	$parsed_url = parse_url($_SESSION['crawler']['domain']);
	if(!isset($parsed_url['host'])){
		die('<p>Sorry, but your domain name appears to be invalid as it canot be reduced to a raw domain name. Please ensure you enter it fully complete with correct protocol! Crawler must exit now.</p>');	
	}else{
		$_SESSION['crawler']['silo'] = $parsed_url['host'];
	}	
	
	header('Location: index.php');
	exit;
?>