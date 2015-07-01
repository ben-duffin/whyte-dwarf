<?php
 class Scrape {
	private static $canonicals 	= array();
	private static $cookies 		= false;
	private static $is_start		= true;
	
	public static function getCookies($for){
		$ch = curl_init($for);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$result = curl_exec($ch);
		preg_match_all('/(Set-Cookie:[^\n]*)/i', $result, $cookies);
		if(isset($cookies[0])){
			$curl_cookies = array();
			$str = '';
			foreach($cookies[0] as $cookie_idx => $cookie){
				$tmp = str_ireplace('Set-Cookie:', 'Cookie: ', $cookie);
				$tmp .= '; ';
				$str .= $tmp;
			}
			self::$cookies = $str;
		}
	}
	
	public static function fetch($url, $auth = false, $respect_meta = true, $respect_canonical = true){
			$ch = curl_init();
			$timeout = 30;      
			if(preg_match("/(https?:\/\/[A-Za-z0-9-.]*):([0-9]{1,5})*/i", $url, $matches)){
        $parsed = parse_url($url);
        $urls = $matches[1];
        curl_setopt($ch, CURLOPT_PORT, $matches[2]);
      }else{
        curl_setopt($ch, CURLOPT_PORT, 80);
      }			

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      if($auth != false){
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
      }
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			
			if(self::$cookies !== false){
				curl_setopt($ch, CURLOPT_COOKIE, self::$cookies);
			}
			
			$data = curl_exec($ch);
			curl_close($ch);
			
			// Find Meta Tags
			if($respect_meta){
				if(preg_match_all('/\<meta.*?\>/mis', $data, $m) and strstr(join(',', $m[0]), 'noindex')){
					// We hit a noindex tag - fuck this page off
					return false;
				}
			}
			
			if($respect_canonical){
				preg_match_all('/<link\srel="canonical"\shref="([^"]+)"/is', $data, $m);
				if(isset($m[1]) && !empty($m[1])){
					$canonical = $m[1];
					if(!in_array($canonical, self::$canonicals)){
						self::$canonicals[] = $canonical;
					}else{
						// Canonicalised page - ta ta!!
						return false;
					}
				}
			}
			
			$plain_text = '';
			
			// strip elements
			$strip = array('script', 'style', 'link');
			
			// load the HTML document
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			libxml_use_internal_errors (true);
			$dom->loadHTML(strtolower($data));
			
			// Remove unwanted Tags 
			foreach($strip as $element_tag_name){
				self::removeElements($element_tag_name, $dom);
			}
			
			$xPath 		= new DOMXPath($dom);
			 
			// Go through the resultant $html and get all text nodes
			$title = '';
			$contents = $xPath->query('/html/head/title');
			if ($contents->length > 0) {
				$title = $contents->item(0)->textContent;
				if(is_array($title)) $title = $title[0];
			}
			
			$description = '';
			$contents = $xPath->query('/html/head/meta[@name="description"]/@content');
			if ($contents->length > 0) {
				foreach ($contents as $content) {
					$description .= $content->value;
				}
			}

			$keywords = '';
			$contents = $xPath->query('/html/head/meta[@name="keywords"]/@content');
			if ($contents->length > 0) {
				foreach ($contents as $content) {
					$keywords .= $content->value;
				}
			}
			
			$textNodes = $xPath->evaluate('//text()');
			$stopWords = array();
			foreach ($textNodes as $textNode) {
				// Do some magic on the gathered text
				$nodeValue = strtolower($textNode->nodeValue);
				$nodeValue = str_replace($stopWords,' ', $nodeValue);
				$nodeValue = preg_replace("/[.:()\/\$\'\#]/", ' ', $nodeValue);
				$nodeValue = preg_replace('/[^a-z0-9 -\\._]/', '', $nodeValue);
				$nodeValue = trim($nodeValue);
				if (!empty($nodeValue)) {
					$plain_text .= $nodeValue." ";
				}
			}

			$document = array('title' 			=> $title,
												'description' => $description,
												'keywords'		=> $keywords,
												'plaintext' 	=> $plain_text);
												
			return $document;
	}
	
	private static function removeElements($element, &$doc){
		$nodeList = $doc->getElementsByTagName($element);
			for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0; ) {
				$node = $nodeList->item($nodeIdx);
				$node->parentNode->removeChild($node);
			}	
		}
 }
?>