<?php
define('SOLR_URL', ''); // fully qualified domain with trailing slash
define('SOLR_UPDATE_URL', SOLR_URL.'solr/update/json?commit=true');
define('SOLR_DELETE_URL', SOLR_URL.'solr/update?commit=true&stream.body=<delete><query>silo: ##SILO##</query></delete>');
define('SOLR_PORT', 8080);
define('MAX_CRAWL_DELAY', 5);
?>