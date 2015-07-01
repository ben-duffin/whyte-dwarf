<!DOCTYPE HTML>
<html>
  <head>
  <meta charset="UTF-8">
  <title>Domain Crawler and Solr Updater</title>
  </head>

	<body>
  <?php if(isset($_SESSION['crawler'])){ ?>
  	<h3>Ready to Spider <?php print($_SESSION['crawler']['silo']); ?> For Scraping and Passing to Solr!</h3>
    <fieldset>
    	<legend>Current Configuration</legend>
      <strong>SOLR Settings</strong>
      <ul>
      	<li>SOLR_UPDATE_URL: <?php print(SOLR_UPDATE_URL); ?></li>
      	<li>SOLR_DELETE_URL: <?php print(SOLR_DELETE_URL); ?></li>
      	<li>SOLR_PORT: <?php print(SOLR_PORT); ?></li>
      </ul>
      <strong>Crawler Settings</strong>
      <ul>
      	<li>MAX_CRAWL_DELAY: <?php print(MAX_CRAWL_DELAY); ?></li>
        <li>Username: <?php echo (isset($_SESSION['crawler']['user'])) ? $_SESSION['crawler']['user'] : ''; ?></li>
        <li>password: <?php echo (isset($_SESSION['crawler']['pass'])) ? $_SESSION['crawler']['pass'] : ''; ?></li>
        <li>Using Authentication: <?php echo ($_SESSION['crawler']['auth'] == true) ? 'yes' : 'no'; ?></li>
        <li>Respecting Robots.txt: <?php echo ($_SESSION['crawler']['respect_robots_txt'] == true) ? 'yes' : 'no'; ?></li>
        <li>Respecting Meta Robots: <?php echo ($_SESSION['crawler']['respect_robots_meta'] == true) ? 'yes' : 'no'; ?></li>
        <li>Respecting Canonical Tags: <?php echo ($_SESSION['crawler']['respect_canonical'] == true) ? 'yes' : 'no'; ?></li>
        <li>Actual URL: <?php print($_SESSION['crawler']['domain']); ?></li>
        <li>Silo URL: <?php print($_SESSION['crawler']['silo']); ?></li>
      </ul>
    </fieldset>
		<p><a href="do.php?task=crawl">Start a Crawl</a> or <a href="do.php?task=reset">Reset Crawler details and start again</a></p>
	<?php } ?>