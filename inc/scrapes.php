<fieldset>
	<legend>Available Index Lists</legend>
  <ul>
    <?php
    	$root_path = 'crawl_json/';
			$dir = opendir($root_path);
			$list = '';
			while(($next_json = readdir($dir)) != false){
				if($next_json != '.' && $next_json != '..' && stristr($next_json, '.crawl.json')){
					$list .= '<li><a href="do.php?task=index&json='.urlencode($next_json).'" onClick="javascript: return confirm(\'Are you sure you want to index this scrape set?\')">Index</a>| <a href="do.php?task=delete-scrape&crawl='.urlencode($next_json).'" onClick="javascript: return confirm(\'Are you sure you want to delete this scrape?\')">Delete</a> :'.$next_json.'</li>';
				}
			}
			closedir($dir);
			print($list);
    ?>
  </ul>
</fieldset>