# whyte-dwarf
Custom crawler and automatic Solr index updater

PRERQUISITES: 

**crawler_lists** must be wraitable

**crawler_json** must be writable

Edit config/config.php for your Solr settings and Crawl Delay

Use the form to setup your crawl parameters

Save multple crawls / scrapes of the same domain while testing, then push the desired scrape data to Solr redayd for indexing and searching.


I will soon add the simple static Solr adapter class, Example Schema.xml for Silo based indexes ( domain sharding ) and search form for example use. Setting up Solr is up to you!!
