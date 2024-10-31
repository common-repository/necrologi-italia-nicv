<?php

	require_once 'nicv_version.inc.php';
	$nicv_options = get_option( 'nicv_settings' );
	$nicv_css_options = get_option( 'nicv_css_settings' );

/*------- Settaggi Funzionali  -------*/
	define('NICV_HOST', $nicv_options['NI_HOST']);
	define('NICV_id_azienda', $nicv_options['nicv_id_azienda']); // ## fornita all'attivazione del servizio
  	define('NICV_apikey', $nicv_options['nicv_apikey']); // ## fornita all'attivazione del servizio
	define('NICV_id_policy', $nicv_options['nicv_id_policy']); // ## fornita all'attivazione del servizio
	define('NICV_id_gruppo', $nicv_options['nicv_id_gruppo']); // se id_gruppo e' diverso da 0, nicv mostrera' tutti i defunti del gruppo
	define('NICV_host', $nicv_options['nicv_host']); // completo di http:// e trailing slash
	define('NICV_pagesPath', $nicv_options['nicv_servizioNecrologi']); // eventuale subdir (completa di trailing slash) di dove sono situate la pagine del nicv oppure vuoto
	define('NICV_ditta', $nicv_options['nicv_ditta']); // non inserire "Onoranze Funebri" o "Pompe funebri" oppure modificare seo.inc.php
	define('NICV_sedi', $nicv_options['nicv_sedi']); // comuni in cui e'/sono dislocata/e la/e sede/i
	define('NICV_citta', $nicv_options['nicv_citta']); // comune in cui e' dislocata la sede principale
	define('NICV_provincia', $nicv_options['nicv_provincia']); // sigla della provincia es. MI
	define('NICV_forzaCitta', false); // forza l'uso di _citta al posto di luogo_decesso e comune_cimitero

/*------- Settaggi Grafici  -------*/
	define('NICV_pads', $nicv_options['nicv_pads']); // numero di pads da visualizzare in servizio-necrologi
	define('NICV_carouselpads', $nicv_options['nicv_carouselpads']); // numero di pads da visualizzare nell'include ultimi-defunti.inc.php
	define('NICV_logos', $nicv_options['nicv_logos']); // attiva/disattiva la visualizzazione del logo aziendale sulle pads e scheda defunto
	define('NICV_banner', $nicv_options['nicv_banner']); // attiva/disattiva la visualizzazione del banner aziendale su scheda defunto


/*------- Colori NICV --------*/
	$necrologi=array(
		'ne0'=>$nicv_css_options['nicv_ne0'],   				// 0 Colore scritte pad
		'ne1'=>$nicv_css_options['nicv_ne1'],    			// 1 texture sfondo pad (dentro necrologi-italia-nicv/images/texture)
		//'ne2'=>$css_options['ne2'],					// 2 caratteri pad nicv
		//'ne3'=>$css_options['ne3'],					// 3 titoli pad nicv
		//'ne4'=>$css_options['ne4'],					// 4 font titolo scheda defunto nicv (nome defunto)
		'ne5'=>$nicv_css_options['nicv_ne5'],				// 5 grandezza titolo scheda defunto nicv (nome defunto)

		'sc0'=>$nicv_css_options['nicv_sc0'],   				// 0 texture scheda defunto (dentro necrologi-italia-nicv/images/texture) 
		'sc1'=>$nicv_css_options['nicv_sc1'],				// 1 colore nome defunto
		'sc2'=>$nicv_css_options['nicv_sc2'],				// 2 colore testi
		'sc3'=>$nicv_css_options['nicv_sc3'],				// 3 colore link messaggio
		'sc4'=>$nicv_css_options['nicv_sc4'],				// 4 colore hover link messaggio
		'sc5'=>$nicv_css_options['nicv_sc5'],				// 5 colore icone chiesa, croce, matita e busta
	); 


	define('NICV_formatoData', $nicv_options['nicv_formatoData']); // formato per la stampa delle date - vedi date
	define('NICV_charset', $nicv_options['nicv_charset']);
	define('NICV_rewriteRule', $nicv_options['nicv_rewriteRule']);
	//define('_nicvSeo', $nicv_options['_nicvSeo']);

	define ('NICV_servizioNecrologi', $nicv_options['nicv_servizioNecrologi']); // filename della pagina servizio-necrologi-php
	define ('NICV_schedaNecrologio', $nicv_options['nicv_schedaNecrologio']); // filename della pagina scheda-necrologio.php
    define ('NICV_schedaCommemorazione', $nicv_options['nicv_schedaCommemorazione']); // filename della pagina scheda-commemorazione.php
	define('NICV_nicvsitemap', 'sitemap_nicv.xml');
	define('NICV_sitemap_timing', 86400);


/*------- Font Selection  -------*/
function font_select($params,$necrologi = NULL){
	if(ISSET($necrologi))
	{
		$font = implode('|',array_unique(array($params[8],$params[12],$params[15],$params[18],$params[23],$params[26],$necrologi['ne2'],$necrologi['ne3'],$necrologi['ne4'])
		));
	} else {
		$font = implode('|',array_unique(array($params[8],$params[12],$params[15],$params[18],$params[23],$params[26])
		));
	}
	return $font;
	}