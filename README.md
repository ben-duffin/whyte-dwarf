# whyte-dwarf
Custom crawler and automatic Solr index updater

# Dangerous Code: Unsanitized input at present
PRERQUISITES: 

**crawler_lists** must be writable

**crawler_json** must be writable

Edit config/config.php for your Solr settings and Crawl Delay

Use the form to setup your crawl parameters

Save multiple crawls / scrapes of the same domain while testing, then push the desired scrape data to Solr ready for indexing and searching.


I will soon add the simple static Solr adapter class, Example Schema.xml for Silo based indexes ( domain based keys ) and search form for example use. Setting up Solr is up to you!!


# Credits


**PHPCrawl

PHPCrawl was created by sminnee

https://github.com/sminnee/phpcrawl



**Robots.class.php

Robots wase created by Andy Pieters

Pieters.Andy@gmail.com



**PHP Simple HTML DOM Parser

http://simplehtmldom.sourceforge.net/

