<?php
class SolrUpdate {
	private static $solr_port 	= false;
	private static $solr_url 		= false;
	private static $solr_reset 	= false;
	private static $solr_auth 	= false;
	
	private static function drop(){
		if(self::$solr_reset == false) return;
		
		// initialise the curl request
		$ch = curl_init(self::$solr_reset);
		
		// send a file
		curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_PORT, self::$solr_port);
	 if(self::$solr_auth != false){
			curl_setopt($ch, CURLOPT_USERPWD, self::$solr_auth);
		}
		
		// Fire it
		$response = curl_exec($ch);
		
		// close the session
		curl_close($ch);	
	}
	
	public static function disableDrop(){
		self::$solr_reset = false;
	}

	public static function setAuth($auth){
		self::$solr_auth = $auth;
	}
	
	public static function setUpdate($url){
		self::$solr_url = $url;
	}
	
	public static function setDrop($url){
		self::$solr_reset = $url;
	}
	
	public static function setPort($port){
		self::$solr_port = $port;
	}
	
	public static function update($json_path){
		if(self::$solr_port == false){
			trigger_error('Solr Update cannot continue: no Port has been set!', E_USER_ERROR);
		}

		// Drop the existing index
		self::drop();
		
		if(self::$solr_url == false){
			trigger_error('Solr Update cannot continue: no Update URL has been set!', E_USER_ERROR);
		}
		
		// initialise the curl request
		$ch = curl_init(self::$solr_url);
		
		// send a file
		curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_PORT, self::$solr_port);
	 	if(self::$solr_auth != false){
			curl_setopt($ch, CURLOPT_USERPWD, self::$solr_auth);
		}
		@curl_setopt(
				$ch,
				CURLOPT_POSTFIELDS,
				array(
					'file' => '@' . realpath($json_path)
          . ';type='    . 'application/json'					
				));
		
		// capture the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// Fire it
		$response = curl_exec($ch);
		
		// close the session
		curl_close($ch);	
		
		// pass back response
		return $response;
	}
}