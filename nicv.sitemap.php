<?php
	
	require_once 'niplug.class.php';
	require_once 'nicv.conf.php';
    $ni = new NICV_niplug(NICV_VERSION, NI_HOST);
	$ni->set_id_azienda(NICV_id_azienda);
	$ni->set_apikey(NICV_apikey);
	if(defined('id_gruppo')) $ni->set_id_gruppo(NICV_id_gruppo);

	$defunti = $ni->get_last(5);

	if(!$defunti){
		header('HTTP/1.1 500 Internal Server Error');
	}else{
		$ts_now = time();

		header('Content-Type: application/xml; charset=utf-8');
		
		echo esc_attr('<?xml version="1.0" encoding="UTF-8"?>');
		echo esc_attr('<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">');

		foreach($defunti as $d){
			echo esc_attr('<url>');
			echo esc_attr('<loc>' . nicvLink($d['id'], $d['nome'], $d['cognome'], $d['luogo_decesso'], true) . '</loc>');
			
			if($d['ts_data_funerale'] > $d['ts_caricamento']){
				if($ts_now < $d['ts_data_funerale'] + NICV_sitemap_timing){
					echo esc_attr('<lastmod>' . lastmod($d['ts_ultima_modifica']) . '</lastmod>');		
					echo esc_attr('<changefreq>hourly</changefreq>');
					echo esc_attr('<priority>1.0</priority>');
			
				}else{
					echo esc_attr('<lastmod>' . lastmod($d['ts_data_funerale'] + NICV_sitemap_timing) . '</lastmod>');		
					echo esc_attr('<changefreq>yearly</changefreq>');
					echo esc_attr('<priority>0.3</priority>');
			
				}
			}else{
				if($ts_now < $d['ts_caricamento'] + NICV_sitemap_timing){
					echo esc_attr('<lastmod>' . lastmod($d['ts_ultima_modifica']) . '</lastmod>');		
					echo esc_attr('<changefreq>hourly</changefreq>');
					echo esc_attr('<priority>1.0</priority>');
			
				}else{
					echo esc_attr('<lastmod>' . lastmod($d['ts_caricamento'] + NICV_sitemap_timing) . '</lastmod>');		
					echo esc_attr('<changefreq>yearly</changefreq>');
					echo esc_attr('<priority>0.3</priority>');
			
				}
			}
			
			echo esc_attr('</url>');
		}

		echo esc_attr('</urlset>');
	}

	function lastmod($ts){
		if($ts == '' || $ts == 0) $ts = 1405796603;
		$objDateTime = new DateTime(date('m/d/Y H:i:s', $ts));
		$isoDate = $objDateTime->format(DateTime::W3C);
		return $isoDate;
	}