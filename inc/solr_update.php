<?php
	// Get Config
	include('config/config.php');
	include('classes/SolrUpdate.class.php');

	// Port Solr is running on
	SolrUpdate::setPort(SOLR_PORT);
	
	// Authentication
	if($_SESSION['crawler']['auth'] == true){
		SolrUpdate::setAuth("{$_SESSION['crawler']['user']}:{$_SESSION['crawler']['pass']}");
	}
	// Update SOLR url
	SolrUpdate::setUpdate(SOLR_UPDATE_URL);
			
	// If we want to drop the index each time we spider we need to specify the drop url, to disable again use disableDrop()
	$drop_url = str_replace('##SILO##', $_SESSION['crawler']['silo'], SOLR_DELETE_URL);
	SolrUpdate::setDrop($drop_url);
			
	SolrUpdate::update('crawl_json/'.$_GET['json']);
?>