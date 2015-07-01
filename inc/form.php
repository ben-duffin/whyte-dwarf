<form action="do.php?task=setup" method="post" onSubmit="javascript: return confirm('Are you sure the details are correct?);'">
	  <fieldset>
  	<legend>Crawler</legend>
		    <input type="text" name="user" placeholder="Apache Username" style="width: 200px;" />
    		<input type="text" name="pass" placeholder="Apache Password" style="width: 200px;" />   
        <label><input type="checkbox" name="respect_robots_txt" value="true" checked="checked" /> Respect robots.txt</label> 
        <label><input type="checkbox" name="respect_robots_meta" value="true" checked="checked" /> Respect meta robots</label> 
        <label><input type="checkbox" name="respect_canonical" value="true" checked="checked" /> Respect Canonical</label> 
        
				<input type="url" name="domain" placeholder="Enter Domain Name ( with Protocol )" style="width: 300px;" />&nbsp;<input type="submit" value="Setup and Start Crawl Interface" />
  </fieldset>
</form>