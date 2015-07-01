<?php
		// Get Config
		include('config/config.php');

		// It may take a whils to crawl a site ...
		set_time_limit(-1);
	
		// Inculde the phpcrawl-mainclass
		include("lib/PHPCrawler/PHPCrawler.class.php");
		include("classes/Robots.class.php");
		include('lib/dom/simple_html_dom.php');
	
		// Extend the class and override the handleDocumentInfo()-method
		class MyCrawler extends PHPCrawler {
	
			private $links          = array();
			private $primary_domain = '';
			private $auth_string		= false;
			
			public function __construct($primary){
				parent::__construct();				
				$parsed               = parse_url($primary);
				$primary              = (isset($parsed['host'])) ? $parsed['host'] : $parsed['path'];
				$this->primary_domain = $primary;
				$this->setURL($this->primary_domain);
			}
	
	
			function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo){
				if($DocInfo->http_status_code != '200'){
					return;
				}
	
				// Now you should do something with the content of the actual
				// received page or file ($DocInfo->source), we skip it in this example
				if(count($DocInfo->links_found) > 0){
					$tmp = array();
					foreach($DocInfo->links_found as $idx => $href_set){
						if(stristr($href_set['url_rebuild'], $this->primary_domain)){
							$url    = $href_set['link_raw'];
							$parsed = parse_url($url);
							if(isset($parsed['host'])){
								if($parsed['host'] != $this->primary_domain) continue;
							}
							$url = $parsed['path'];
							if(isset($parsed['query'])) 		$url .= '?' . $parsed['query'];
							if(isset($parsed['fragmant']))	$url .= '#' . $parsed['fragment'];
							$tmp[] = $url;
						}
					}
					$tmp         = array_unique($tmp);
					$this->links = array_merge($this->links, $tmp);
				}
			}
	
			public function set_url_test_auth($p, $u){
				$this->auth_string = "{$p}:{$u}";
			}
	
			private function url_exists($url_to_test){
				$ch = curl_init($url_to_test);
	      if($this->auth_string != false){
  	      curl_setopt($ch, CURLOPT_USERPWD, $this->auth_string);
    	  }
				curl_setopt($ch, CURLOPT_NOBODY, true);
				curl_exec($ch);
				$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
	
				return ($retcode == 200) ? true : false;
			}
	
	
			public function processLinks($domain, $use_robots = true){
				$this->links = array_unique($this->links);
				$this->links = array_values($this->links);
				foreach($this->links as $ldx => $link){
					
					if(stristr($link, '..')){
						unset($this->links[$ldx]);
						continue;
					}
				 	if(!stristr($link, $domain)) $link = $domain . $link;
					if($use_robots == true && $this->url_exists($domain.'/robots.txt')){
						if(!Robots_txt::urlAllowed($link, $this->PageRequest->userAgentString)){
							unset($this->links[$ldx]);
							continue;
						}
					}
					
					if(!$this->url_exists($link)){
						unset($this->links[$ldx]);
						continue;
					}
					
					$this->links[$ldx] = $link;
				}
	
				// Final re-order
				$this->links = array_values($this->links);
	
				return $this->links;
			}
		}
	
		// Now, create a instance of your class, define the behaviour
		// of the crawler (see class-reference for more options and details)
		// and start the crawling-process.
		$crawler 	= new MyCrawler($_SESSION['crawler']['domain']);
		$crawler->setFollowMode(2);
		$crawler->addContentTypeReceiveRule("#text/html#");
		$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");
		$crawler->enableCookieHandling(true);
		if($_SESSION['crawler']['respect_robots_txt'] == true){
			$crawler->obeyRobotsTxt(true, $_SESSION['crawler']['domain'].'/robots.txt');
			$crawler->obeyNoFollowTags(true);
		}
		
		$crawler->enableAggressiveLinkSearch(false);
		$crawler->excludeLinkSearchDocumentSections(PHPCrawlerLinkSearchDocumentSections::ALL_SPECIAL_SECTIONS);
		$crawler->addLinkSearchContentType("#text/html# i");
		$crawler->setLinkExtractionTags(array('href'));
		$crawler->setUserAgentString('Crawl_Scrape_Solr_Index/1.0)'); // no data on poage yet
		if($_SESSION['crawler']['auth'] == true){
			$crawler->set_url_test_auth($_SESSION['crawler']['user'], $_SESSION['crawler']['pass']);
			$pattern = "/https?://".str_replace('.', '\.', $_SESSION['crawler']['silo'])."/is";
			$crawler->addBasicAuthentication($pattern, $_SESSION['crawler']['user'], $_SESSION['crawler']['pass']);
		}
	
		// Thats enough, now here we go
		$crawler->go();
	
		// At the end, after the process is finished, we print a short
		// report (see method getProcessReport() for more information)
		$report = $crawler->getProcessReport();
		$links  = $crawler->processLinks($_SESSION['crawler']['domain'], $_SESSION['crawler']['respect_robots_txt']);

		//$lb     = "<br />";
		//echo "Summary:" . $lb;
		//echo "Links followed: " . $report->links_followed . $lb;
		//echo "Links extracted: " . count($links) . $lb;
		//echo "Documents received: " . $report->files_received . $lb;
		//echo "Bytes received: " . $report->bytes_received . " bytes." . $lb;
		//echo "Spider Process runtime: " . round($report->process_runtime, 2) . " seconds." . $lb . $lb;

		if(count($links) > 0){
			// Save scrape file
			$scrape_file 	= 'crawl_lists/'.$_SESSION['crawler']['silo'].'-'.date('dmY-His').'.crawl.list';
			$list 				= serialize($links);
			$fp 					= fopen($scrape_file, 'w');
			fwrite($fp, $list, strlen($list));
			fclose($fp);
		}
				
		header('Location: index.php');
		exit;	
?>