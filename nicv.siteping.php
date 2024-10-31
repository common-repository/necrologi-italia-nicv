<?php
	require_once(plugin_dir_path(__FILE__).'/nicv.conf.php');
	if(!isset($_REQUEST['apikey']) || strtolower($_REQUEST['apikey']) <> strtolower(NICV_apikey)) die('-ko#bazinga');

	$sitemap_url = 'https://www.google.com/webmasters/sitemaps/ping?sitemap=' . NICV_host . NICV_nicvsitemap;

	$result = wp_remote_retrieve_body(wp_remote_get($sitemap_url));

	if(stripos($result, 'google')){
		echo '+ok#';
	}else{
		echo '-ko#';
	}