<fieldset>
	<legend>Available Scrape Lists</legend>
  <ul>
    <?php
    	$root_path = 'crawl_lists/';
			$dir = opendir($root_path);
			$list = '';
			while(($next_list = readdir($dir)) != false){
				if($next_list != '.' && $next_list != '..' && stristr($next_list, '.crawl.list')){
					$list .= '<li><a href="do.php?task=scrape&crawl='.urlencode($next_list).'" onClick="javascript: return confirm(\'Are you sure you want to scrape this list?\')">Scrape</a> | <a href="do.php?task=delete-list&crawl='.urlencode($next_list).'" onClick="javascript: return confirm(\'Are you sure you want to delete this list?\')">Delete</a> :'.$next_list.'</li>';
				}
			}
			closedir($dir);
			print($list);
    ?>
  </ul>
</fieldset>