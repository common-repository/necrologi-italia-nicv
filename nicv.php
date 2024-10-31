<?php
/*
Plugin Name: Necrologi Italia NICV
Version: 2.0.7
Description: Il plugin NICV pubblica sul tuo sito gli annunci funebri e commemorazioni che inserisci sul portale www.necrologi-italia.it. Permette inoltre di raccogliere messaggi di cordoglio centralizzati e di generare interazioni efficaci con i tuoi visitatori.
Author: Methodo Digital Media - Necrologi Italia
Author URI: https://www.necrologi-italia.it/
*/


if (!defined('ABSPATH')) exit;
setlocale(LC_ALL, 'it_IT.utf8');
define('NICV_VERSION', '1.0.0');
define('NICV_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NICV_IMG', NICV_PLUGIN_URL.'images/icon-nicv/');

require_once(plugin_dir_path(__FILE__).'functions.php');

date_default_timezone_set('Europe/Rome');

add_action('init', 'nicv_shortcode_servizio_necrologi');
add_action('init', 'nicv_shortcode_scheda_defunto');
add_action('init', 'nicv_shortcode_ultimi_defunti');
add_action('init', 'nicv_shortcode_manifesti_funebri');

/*
* Add the admin page
*/
add_action('admin_menu', 'nicv_admin_page');
function nicv_admin_page(){
add_menu_page( 'Necrologi Italia', 'Necrologi Italia', 'manage_options', 'nicv-plugin', 'nicv_admin_page_callback', NICV_IMG . 'nicv.png');
add_submenu_page( 'nicv-plugin', 'Configurazione', 'Configurazione', 'manage_options', 'nicv-plugin', 'nicv_admin_page_callback');
add_submenu_page( 'nicv-plugin', 'Installazione', 'Installazione', 'manage_options', 'nicv-utilizzo', 'nicv_utilizzo_admin_page_callback');
add_submenu_page( 'nicv-plugin', 'Personalizzazione CSS', 'Custom CSS', 'manage_options', 'nicv-css-plugin', 'nicv_css_admin_page_callback');
}

/*
 * Register the settings
 */

function my_plugin_activate() {

  //add_option( 'Activated_Plugin', 'Plugin-Slug' );

  /* activation code here */

  $array_settings = [

  'nicv_id_azienda' => '555',
  'nicv_apikey' => 'C06C5B121F44B47F355488AD03970E8714DABE6E8E8E53A1F6DA581653BE6F29',
  'nicv_id_policy' => 205,
  'nicv_id_gruppo' => 0,
  'nicv_ditta' => 'Azienda di test',
  'nicv_citta' => 'Camerata Picena',
  'nicv_provincia' => 'AN',
  'nicv_sedi' => 'Camerata Picena, Falconara, Chiaravalle',
  'nicv_forzaCitta' => "false",
  'nicv_pads' => 12,
  'nicv_carouselpads' => 4,
  'nicv_logos' => 'false',
  'nicv_abilitaManifesti' => 'true',
  'nicv_banner' => 'false',
  'nicv_abilitaFiori' => 'false',
  'NI_HOST' => 'https://www.necrologi-italia.it/',
  'nicv_host' => site_url().'/',
  'nicv_pagesPath' => '',
  'nicv_formatoData' => 'd-m-Y',
  'nicv_charset' => 'UTF-8',
  'nicv_rewriteRule' => 1,
  'nicv_servizioNecrologi' => 'servizio-necrologi',
  'nicv_schedaNecrologio' => 'scheda-defunto',
  'nicv_schedaCommemorazione' => 'scheda-commemorazione',
  'nicv_manifestiFunebri' => 'manifesti-funebri',
];
  add_option('nicv_settings', $array_settings);

  $array_css_settings = [

    'nicv_ne0' => '#222222',
    'nicv_ne1' => 'muro.jpg',
    'nicv_ne4' => '#222222',
    'nicv_ne5' => '20px',
    'nicv_sc0' => 'bg-col.png',
    'nicv_sc1' => '#222222',
    'nicv_sc2' => '#555555',
    'nicv_sc3' => '#555555',
    'nicv_sc4' => '#DADADA',
    'nicv_sc5' => '#DADADA',
    'nicv_sc6' => '18px',
    'nicv_sc9' => '30px',
    'nicv_r1' => '#222222',
    'nicv_r2' => '25px',
    'nicv_r4' => '#222222',
    'nicv_r5' => '20px',
    'nicv_r6' => 'stucco.png',
    'nicv_r8' => '20px',
    'nicv_r9' => '#555555',
    'nicv_f0' => '#EAEAEA',
    'nicv_f1' => '#FFFFFF',
    'nicv_f2' => '#222222',
    'nicv_f3' => '#CCCCCC',
    'nicv_f4' => '#AAAAAA',
    'nicv_f5' => '#555555',
    'nicv_m0' => '#CCCCCC',
    'nicv_m1' => '#555555',
    'nicv_m2' => '#444444'
  ];

  add_option('nicv_css_settings', $array_css_settings);
}
register_activation_hook( __FILE__, 'my_plugin_activate' );

add_action('admin_init', 'nicv_register_settings');
function nicv_register_settings(){
    register_setting('nicv_settings', 'nicv_settings');
//    register_setting('nicv_settings', 'nicv_settings', 'nicv_settings_validate');
    register_setting('nicv_css_settings', 'nicv_css_settings');    
}

function nicv_settings_validate($args){
    if(!isset($args['nicv_email']) || !is_email($args['nicv_email'])){
        $args['nicv_email'] = '';
    add_settings_error('nicv_settings', 'nicv_invalid_email', 'Please enter a valid email!', $type = 'error');   
    }
    return $args;
}

// Display the validation errors and update messages
/*
 * Admin notices
 */
add_action('admin_notices', 'nicv_admin_notices');
function nicv_admin_notices(){
   settings_errors();
}


// sezione configurazione
function nicv_admin_page_callback(){ ?>
    <div class="wrap">
        <img src="<?php echo plugins_url().'/necrologi-italia-nicv/images/icon-nicv/necrologi-italia.png'; ?>"/>
    </div>
    <div class="wrap">
    <h2>Necrologi Italia - Configurazione</h2>
    <form action="options.php" method="post"><?php
        settings_fields( 'nicv_settings' );
        do_settings_sections( __FILE__ );
        $options = get_option( 'nicv_settings' );
    ?>



<div class="container">
<p>&nbsp;</p>
  <div class="row nicv">
    <p>I necrologi pubblicati sul portale <a href="https://www.necrologi-italia.it/">www.necrologi-italia.it</a>, saranno automaticamente riportati anche su questo sito, <a href="https://necrologimilano.com/" title="Necrologi Milano">come in questo esempio</a></p>
    <hr/><br/>
    <h3 style="font-size:30px;">5 semplici passaggi per vederlo in azione</h3>
    <p><p>1) Crea una nuova pagina e chiamala "<b>servizio-necrologi</b>". Inserisci al suo interno lo shortcode [<b>nicv_servizio_necrologi]</b>.</p>
        <p>2) Crea una nuova pagina e chiamala "<b>scheda-defunto</b>". Inserisci al suo interno lo shortcode <b>[nicv_scheda_defunto]</b>.</p>
        <p>3) Crea una nuova pagina e chiamala "<b>scheda-commemorazione</b>". Inserisci al suo interno lo shortcode <b>[nicv_scheda_commemorazione]</b>.</p>
        <p>4) Crea una nuova pagina e chiamala "<b>manifesti-funebri</b>". Inserisci al suo interno lo shortcode <b>[nicv_manifesti_funebri]</b>.</p>
    <p>5) Settare i permalink su <a href="/wp-admin/options-permalink.php">Impostazioni -> Permalink -> "Nome articolo"</a>. Salvarli di nuovo anche se sono già impostati su quest'ultimo. <b>Questo passaggio è fondamentale per il funzionamento del plugin</b></p><br/>
    
    <h3>Vuoi collegare la tua Agenzia Funebre?</h3>
    <p>a) <b style="color:mediumvioletred;">NON SEI ANCORA ISCRITTO SU NECROLOGI ITALIA? <a style="color: green;" href="https://www.necrologi-italia.it/registrazione-onoranze-funebri.php" title="Necrologi e servizio di necrologi in tempo reale">PROVALO PER TRE MESI</a></b><br/>Inizia la prova gratuita della Tua Impresa Funebre sul portale leader in Italia per la pubblicazione necrologi <a href="https://www.necrologi-italia.it/registrazione-onoranze-funebri.php" title="Necrologi e servizio di necrologi in tempo reale">A QUESTO LINK</a>.<br/></p>
    <p>b) <b style="color:RoyalBlue;">SEI GIÀ ISCRITTO SU NECROLOGI ITALIA?</b><br/>Invia una richiesta di attivazione <a style="color: green;" href="https://www.necrologi-italia.it/attivazione-wp-nicv.php" title="Richiesta per attivazione plugin Necrologi Italia"><b>QUI</b></a> dopo aver eseguito l'accesso su Necrologi Italia e attendi i tuoi parametri di configurazione.</p><br/>
    <h3>Assistenza sempre disponibile</h3>
    <p>Il servizio assistenza di Necrologi Italia, <b>anche per prove gratuite</b>, rimane comunque a tua disposizione all'indirizzo <a href="mailto:assistenza@necrologi-italia.it">assistenza@necrologi-italia.it</a>. Saremo lieti di aiutarti!</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <h3>Credenziali</h3>
    <p>(Fare riferimento alla mail di attivazione)</p>
    <hr/>
    <div class="col-sm">
<!-- <input name="nicv_settings[nicv_email]" type="text" id="nicv_email" value="<?php echo (isset($options['nicv_email']) && $options['nicv_email'] != '') ? $options['nicv_email'] : ''; ?>"/> -->
                            
<p>
<input name="nicv_settings[nicv_id_azienda]" type="text" id="id_azienda" value="<?php echo esc_html((isset($options['nicv_id_azienda']) && $options['nicv_id_azienda'] != '') ? $options['nicv_id_azienda'] : '555'); ?>" placeholder="id_azienda *" size="20"/>
                            <span>ID Azienda.</span>
</p><p>  
<input name="nicv_settings[nicv_apikey]" type="text" id="apikey" value="<?php echo esc_html((isset($options['nicv_apikey']) && $options['nicv_apikey'] != '') ? $options['nicv_apikey'] : 'C06C5B121F44B47F355488AD03970E8714DABE6E8E8E53A1F6DA581653BE6F29'); ?>" placeholder="apikey *" size="20"/>
                            <span>Read ApiKey.</span>
</p><p>  
<input name="nicv_settings[nicv_id_policy]" type="text" id="id_policy" value="<?php echo esc_html((isset($options['nicv_id_policy']) && $options['nicv_id_policy'] != '') ? $options['nicv_id_policy'] : '205'); ?>" placeholder="id_policy *" size="20"/>
                            <span>ID Privacy Policy.</span>
</p><p>  
<input name="nicv_settings[nicv_id_gruppo]" type="text" id="_id_gruppo" value="<?php echo esc_html((isset($options['nicv_id_gruppo']) && $options['nicv_id_gruppo'] != '') ? $options['nicv_id_gruppo'] : '0'); ?>" placeholder="id_gruppo *" size="20"/>
                            <span>ID Gruppo. Valore di default "0" (senza apici). Se la tua Agenzia Funebre è parte di un gruppo richiedi il tuo ID Gruppo.</span>
</p>
<p><i>* campi obbligatori</i></p><hr>
<h3>Informazioni Agenzia Funebre</h3>
<p>
<input name="nicv_settings[nicv_ditta]" type="text" id="_ditta" value="<?php echo esc_html((isset($options['nicv_ditta']) && $options['nicv_ditta'] != '') ? $options['nicv_ditta'] : 'Azienda di test'); ?>" placeholder="ditta *"  size="20"/>
                           <span>Nome Insegna Agenzia Funebre. <U>Non inserire "Onoranze Funebri" o "Pompe funebri"</U></span>
</p><p>  
<input name="nicv_settings[nicv_citta]" type="text" id="citta" value="<?php echo esc_html((isset($options['nicv_citta']) && $options['nicv_citta'] != '') ? $options['nicv_citta'] : 'Camerata Picena'); ?>" placeholder="citta *"  size="20"/>
                           <span>Comune. Indicare il comune in cui è dislocata la sede principale.</span>
</p><p>
<input name="nicv_settings[nicv_provincia]" type="text" id="_provincia" value="<?php echo esc_html((isset($options['nicv_provincia']) && $options['nicv_provincia'] != 'AN') ? $options['nicv_provincia'] : 'AN'); ?>" placeholder="provincia *"  size="20"/>
                           <span>Sigla provincia. Indicare la sigla della provincia, es. MI</span>
</p><p>  
<input name="nicv_settings[nicv_sedi]" type="text" id="_sedi" value="<?php echo esc_html((isset($options['nicv_sedi']) && $options['nicv_sedi'] != '') ? $options['nicv_sedi'] : 'Camerata Picena, Falconara, Chiaravalle'); ?>" placeholder="sedi *"  size="20"/>
                           <span>Sedi. Comuni in cui è/sono dislocata/e la/e sede/i. E' possibile aggiungere più comuni separati da una virgola.</span>
</p><p>  
<input name="nicv_settings[nicv_forzaCitta]" type="text" id="_forzaCitta" value="<?php echo esc_html((isset($options['nicv_forzaCitta']) && $options['nicv_forzaCitta'] != '') ? $options['nicv_forzaCitta'] : 'false'); ?>" placeholder="forzaCitta *"  size="20"/>
                           <span>Forza Città.  Valore di default "false" (senza apici). Se portato a "true" forza l'uso sul SEO di _citta al posto di luogo_decesso e comune_cimitero</span>
</p>

<p><i>* campi obbligatori</i></p><hr>
<h3>Impostazioni di visualizzazione</h3>
<p>
<input name="nicv_settings[nicv_pads]" type="text" id="_pads" value="<?php echo esc_html((isset($options['nicv_pads']) && $options['nicv_pads'] != '') ? $options['nicv_pads'] : '12'); ?>" placeholder="pads *"  size="20"/>
                           <span>Pads. Numero di pads da visualizzare nella pagina della lista necrologi. Valore di default "12" (senza apici).</span>
</p><p>  
<input name="nicv_settings[nicv_carouselpads]" type="text" id="_carouselpads" value="<?php echo esc_html((isset($options['nicv_carouselpads']) && $options['nicv_carouselpads'] != '') ? $options['nicv_carouselpads'] : '4'); ?>" placeholder="anteprimaPads *"  size="20"/>
                           <span>Pads anteprima. Numero di pads da visualizzare nello shortcode "Ultimi defunti" e "Ultime commemorazioni". Valore di default "4" (senza apici)</span>
</p><p>  
<input name="nicv_settings[nicv_logos]" type="text" id="_logos" value="<?php echo esc_html((isset($options['nicv_logos']) && $options['nicv_logos'] != '') ? $options['nicv_logos'] : 'false'); ?>" placeholder="logoAzienda *"  size="20"/>
                           <span>Logo Aziendale. Valore di default "false" (senza apici). Attiva/disattiva la visualizzazione del logo aziendale sulle pads e scheda defunto</span>
</p>
<p>
<input name="nicv_settings[nicv_abilitaManifesti]" type="text" id="_abilitaManifesti" value="<?php echo esc_html((isset($options['nicv_abilitaManifesti']) && $options['nicv_abilitaManifesti'] != '') ? $options['nicv_abilitaManifesti'] : 'true'); ?>" placeholder="abilitaManifesti *"  size="20"/>
                           <span>Manifesti funebri. Valore di default "true" (senza apici). Attiva/disattiva la visualizzazione dei manifesti funebri</span>
</p><p>  
<input name="nicv_settings[nicv_banner]" type="text" id="_banner" value="<?php echo esc_html((isset($options['nicv_banner']) && $options['nicv_banner'] != '') ? $options['nicv_banner'] : 'false'); ?>" placeholder="banner *"  size="20"/>
                           <span>Banner Aziendale. Valore di default "false" (senza apici). Attiva/disattiva la visualizzazione del banner aziendale su scheda defunto (vedere sez. installazione)</span>
</p><p>
<input name="nicv_settings[nicv_abilitaFiori]" type="text" id="_abilitaFiori" value="<?php echo esc_html((isset($options['nicv_abilitaFiori']) && $options['nicv_abilitaFiori'] != '') ? $options['nicv_abilitaFiori'] : 'false'); ?>" placeholder="abilitaFiori *"  size="20"/>
                           <span>Invio fiori. Valore di default "false" (senza apici). Attiva/disattiva la possibilità di inviare fiori alla famiglia del defunto (vedere sez. installazione)</span>
</p>
<p><i>* campi obbligatori</i></p><hr>
<h3>Parametri di configurazione</h3>
<p>
<input name="nicv_settings[NI_HOST]" type="text" id="NI_HOST" value="<?php echo esc_html((isset($options['NI_HOST']) && $options['NI_HOST'] != '') ? $options['NI_HOST'] : 'https://www.necrologi-italia.it/'); ?>" placeholder="NI_HOST *"  size="20"/>
                           <span>Indirizzo di accesso a Necrologi Italia. Valore di default: "https://www.necrologi-italia.it/" (senza apici)</span>
</p><hr><p>  
<input name="nicv_settings[nicv_host]" type="text" id="host" value="<?php echo esc_html((isset($options['nicv_host']) && $options['nicv_host'] != '') ? $options['nicv_host'] : site_url().'/'); ?>" placeholder="host *" size="20"/>
                            <span>Host. Inserire  l'indirizzo di questo sito web, completo di "https://" e trailing slash finale "/" ad esempio:  https://www.miodominio.com/</span>
</p><p> 
<input name="nicv_settings[nicv_pagesPath]" type="text" id="pagesPath" value="<?php echo esc_html((isset($options['nicv_pagesPath']) && $options['nicv_pagesPath'] != '') ? $options['nicv_pagesPath'] : ''); ?>" placeholder="pagesPath"  size="20"/>
                           <span>Eventual Subdir. Valore di default: vuoto. Indicare eventuale subdir (completa di trailing slash) di dove sono situate la pagine di NICV</span>
</p><p>  
<input name="nicv_settings[nicv_formatoData]" type="text" id="formatoData" value="<?php echo esc_html((isset($options['nicv_formatoData']) && $options['nicv_formatoData'] != '') ? $options['nicv_formatoData'] : 'd-m-Y'); ?>" placeholder="formatoData *"  size="20"/>
                           <span>Formato Data.  Valore di default: "d-m-Y" (senza apici). Formato per la visualizzazione delle date</span>
</p><p>  
<input name="nicv_settings[nicv_charset]" type="text" id="charset" value="<?php echo esc_html((isset($options['nicv_charset']) && $options['nicv_charset'] != '') ? $options['nicv_charset'] : 'UTF-8'); ?>" placeholder="_charset *"  size="20"/>
                           <span>Charset. Valore di default: "UTF-8" (senza apici) </span>
</p><p>  
<input name="nicv_settings[nicv_rewriteRule]" type="text" id="rewriteRule" value="<?php echo esc_html((isset($options['nicv_rewriteRule']) && $options['nicv_rewriteRule'] != '') ? $options['nicv_rewriteRule'] : '1'); ?>" placeholder="rewriteRule *"  size="20"/>
                           <span>Rewrite Rule. Valore di default: "1" (senza apici). Attiva /disattiva l'url rewriting delle pagine NICV</span>
</p><p>  
<!--<input name="nicv_settings[_nicvSeo]" type="text" id="_nicvSeo" value="<?php echo esc_html((isset($options['nicvSeo']) && $options['nicvSeo'] != '') ? $options['nicvSeo'] : ''); ?>" placeholder="_nicvSeo"  size="20"/>
                           <span>Rewrite Rule. Valore di default: "true" (senza apici). Attiva /disattiva il SEO genenerato da NICV</span>-->
</p><p>  
<input name="nicv_settings[nicv_servizioNecrologi]" type="text" id="servizioNecrologi" value="<?php echo esc_html((isset($options['nicv_servizioNecrologi']) && $options['nicv_servizioNecrologi'] != '') ? $options['nicv_servizioNecrologi'] : 'servizio-necrologi'); ?>" placeholder="servizioNecrologi *"  size="20"/>
                           <span>Slug pagina lista necrologi senza "/" alla fine. * </b>La pagina va creata obbligatoriamente per il corretto funzionamento </span>
</p><p>  
<input name="nicv_settings[nicv_schedaNecrologio]" type="text" id="schedaNecrologio" value="<?php echo esc_html((isset($options['nicv_schedaNecrologio']) && $options['nicv_schedaNecrologio'] != '') ? $options['nicv_schedaNecrologio'] : 'scheda-defunto'); ?>" placeholder="schedaDefunto *"  size="20"/>
                           <span>Slug pagina scheda defunto. Valore di default: "scheda-defunto" (senza apici). <b> * </b>La pagina va creata obbligatoriamente per il corretto funzionamento  </span>
</p><p>  
<input name="nicv_settings[nicv_schedaCommemorazione]" type="text" id="schedaCommemorazione" value="<?php echo esc_html((isset($options['nicv_schedaCommemorazione']) && $options['nicv_schedaCommemorazione'] != '') ? $options['nicv_schedaCommemorazione'] : 'scheda-commemorazione'); ?>" placeholder="schedaCommemorazione *"  size="20"/>
                           <span>Slug pagina scheda commemorazione. Valore di default: "scheda-commemorazione" (senza apici). <b> * </b>La pagina va creata obbligatoriamente per il corretto funzionamento </span>
</p>
<input name="nicv_settings[nicv_manifestiFunebri]" type="text" id="manifestiFunebri" value="<?php echo esc_html((isset($options['nicv_manifestiFunebri']) && $options['nicv_manifestiFunebri'] != '') ? $options['nicv_manifestiFunebri'] : 'manifesti-funebri'); ?>" placeholder="manifestiFunebri"  size="20"/>
                           <span>Slug pagina manifesti funebri. Valore di default: "manifesti-funebri" (senza apici).</span>
</p><!--<p>  
<input name="nicv_settings[nicv_nicvsitemap]" type="text" id="_nicvsitemap" value="<?php echo (isset($options['nicv_nicvsitemap']) && $options['nicv_nicvsitemap'] != '') ? $options['nicv_nicvsitemap'] : ''; ?>" placeholder="_nicvsitemap"  size="20"/>
                           <span>Filename sitemap SEO. Valore di default: "sitemap_nicv.xml" (senza apici). </span>
</p><p>  
<input name="nicv_settings[nicv_sitemap_timing]" type="text" id="_sitemap_timing" value="<?php echo (isset($options['nicv_sitemap_timing']) && $options['nicv_sitemap_timing'] != '') ? $options['nicv_sitemap_timing'] : ''; ?>" placeholder="_sitemap_timing"  size="20"/>
                           <span>Timing sitemap SEO. Valore di default: "86400" (senza apici). Espresso in secondi indica il refresh delle informazioni in sitemap</span>
</p>-->

    </div>
 
  </div>

  <p><i>* campi obbligatori</i></p>
</div>

<p>&nbsp;</p>

        <input type="submit" value="Salva le modifiche" class="button button-primary"/>
    </form>
</div>
<?php }


// sezione utilizzo
function nicv_utilizzo_admin_page_callback() { ?>
    <div class="wrap">
        <img src="<?php echo plugins_url().'/necrologi-italia-nicv/images/icon-nicv/necrologi-italia.png'; ?>"/>
    </div>
 <div class="wrap">
    <h2>Necrologi Italia - Istruzioni per l'installazione</h2>


<div class="container">

   <p>&nbsp;</p>
 
  <div class="row nicv">
    <div class="col-sm">
		<h2>Istruzioni per l'utilizzo</h2>
    <p><p>1) Crea una nuova pagina e chiamala "<b>servizio-necrologi</b>". Inserisci al suo interno lo shortcode [<b>nicv_servizio_necrologi]</b>.</p>
        <p>2) Crea una nuova pagina e chiamala "<b>scheda-defunto</b>". Inserisci al suo interno lo shortcode <b>[nicv_scheda_defunto]</b>.</p>
        <p>3) Crea una nuova pagina e chiamala "<b>scheda-commemorazione</b>". Inserisci al suo interno lo shortcode <b>[nicv_scheda_commemorazione]</b>.</p>
        <p>4) Crea una nuova pagina e chiamala "<b>manifesti-funebri</b>". Inserisci al suo interno lo shortcode <b>[nicv_manifesti_funebri]</b>.</p>
    <p>5) Settare i permalink su <a href="/wp-admin/options-permalink.php">Impostazioni -> Permalink -> "Nome articolo"</a>. Salvarli di nuovo anche se sono già impostati su quest'ultimo. <b>Questo passaggio è fondamentale per il funzionamento del plugin</b></p>
    <p>6) Gli shortcode <b>[nicv_ultimi_defunti]</b> e <b>[nicv_ultime_commemorazioni]</b> possono essere inseriti in qualsiasi pagina del sito, senza vincoli sui nomi</p><br/>
    

    <h2>Shortcode da prelevare</h2>
    <p><code>[nicv_ultimi_defunti]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare l'anteprima degli ultimi defunti pubblicati (normalmente va messo in home)</span></p>
    <p><code>[nicv_ultime_commemorazioni]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare l'anteprima delle ultime commemorazioni pubblicate (normalmente va messo in home sotto gli ultimi defunti)</span></p>
    <p><code>[nicv_servizio_necrologi]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare la lista completa dei defunti</span></p>
    <p><code>[nicv_scheda_defunto]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare la scheda defunto</span></p>
    <p><code>[nicv_scheda_commemorazione]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare la scheda commemorazione</span></p>
    <p><code>[nicv_manifesti_funebri]</code>&nbsp;&nbsp;<span>Shortcode per visualizzare i manifesti funebri (se non caricati dall'agenzia vengono generati in automatico)</span></p>
    <p>&nbsp;</p><br/><br/>

    <h2>Banner e servizio fiori</h2>
    <p>Sono entrambi dei servizi aggiuntivi, per i quali è necessaria l'attivazione dal proprio account sul portale www.necrologi-italia.it.</p>
    <p>&nbsp;</p><br/><br/>
		
      <h2>Best practices</h2>
      <p>- Se cambi il tema dopo aver installato il plugin, potrebbe essere necessario ricreare le pagine inserendo nuovamente gli shortcode e salvare di nuovo i permalink su <a href="/wp-admin/options-permalink.php">Impostazioni -> Permalink -> "Nome articolo"</a>.</p>
      <p>- Se qualcosa non dovesse funzionare corettamente, si consiglia di entrare sulla pagina permalink e di salvarla, anche se è già impostata su "Nome articolo".</p>
      <p>- Testato su tema Divi a partire dalla versione 4.14.4.</p>
      <p>&nbsp;</p><br/><br/>
    </div>
  </div>
</div>

   
</div>

<?php }


// sezione custom css
function nicv_css_admin_page_callback(){ ?>
    <div class="wrap">
        <img src="<?php echo plugins_url().'/necrologi-italia-nicv/images/icon-nicv/necrologi-italia.png'; ?>"/>
    </div>
 <div class="wrap">
    <h2>Necrologi Italia - custom CSS</h2>
    <form action="options.php" method="post"><?php
        settings_fields( 'nicv_css_settings' );
        do_settings_sections( __FILE__ );
        $options = get_option( 'nicv_css_settings' ); ?>


<div class="container">

   <p>&nbsp;</p>
 
  <div class="row nicv">
<h3>Modifica CSS</h3>

<h4>Servizio necrologi e pad in home</h4>

    <div class="col-sm">
      <p><input name="nicv_css_settings[nicv_ne0]" type="text" id="ne0" value="<?php echo esc_html((isset($options['nicv_ne0']) && $options['nicv_ne0'] != '') ? $options['nicv_ne0'] : '#222222'); ?>" placeholder="ne0" size="20"/>
      <span>Colore delle scritte sulla pad del defunto. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_ne1]" type="text" id="ne1" value="<?php echo esc_html((isset($options['nicv_ne1']) && $options['nicv_ne1'] != '') ? $options['nicv_ne1'] : 'muro.jpg'); ?>" placeholder="ne1" size="20"/>
      <span>Texture di sfondo della pad. Inserire il nome dell'immagine della texture. Valore di default "muro.jpg" senza vorgolette. Altre texture puoi trovarle nella cartella del plugin, a questo percorso: wp-content/plugins/necrologi-italia-nicv/images/texture.</span></p>
      <p><input name="nicv_css_settings[nicv_ne4]" type="text" id="ne4" value="<?php echo esc_html((isset($options['nicv_ne4']) && $options['nicv_ne4'] != '') ? $options['nicv_ne4'] : '#222222'); ?>" placeholder="ne4" size="20"/>
      <span>Colore nome defunto. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_ne5]" type="text" id="ne5" value="<?php echo esc_html((isset($options['nicv_ne5']) && $options['nicv_ne5'] != '') ? $options['nicv_ne5'] : '20px'); ?>" placeholder="ne5" size="20"/>
      <span>Grandezza del nome del defunto. Inserire la dimensione del font con l'unità di misura. Valore di default: 20px.</span></p>

<hr/>
<h4>Scheda defunto e commemorazione</h4>


      <p><input name="nicv_css_settings[nicv_sc0]" type="text" id="sc0" value="<?php echo esc_html((isset($options['nicv_sc0']) && $options['nicv_sc0'] != '') ? $options['nicv_sc0'] : 'bg-col.png'); ?>" placeholder="sc0" size="20"/>
      <span>Texture di sfondo sulla scheda defunto. Inserire il nome dell'immagine della texture. Valore di default "bg-col.png" senza virgolette. Altre texture puoi trovarle nella cartella del plugin, a questo percorso: wp-content/plugins/necrologi-italia-nicv/images/texture.</span></p>
      <p><input name="nicv_css_settings[nicv_sc1]" type="text" id="sc1" value="<?php echo esc_html((isset($options['nicv_sc1']) && $options['nicv_sc1'] != '') ? $options['nicv_sc1'] : '#222222'); ?>" placeholder="sc1" size="20"/>
      <span>Colore del nome del defunto sulla scheda defunto e sulla scheda commemorazione. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_sc2]" type="text" id="sc2" value="<?php echo esc_html((isset($options['nicv_sc2']) && $options['nicv_sc2'] != '') ? $options['nicv_sc2'] : '#555555'); ?>" placeholder="sc2" size="20"/>
      <span>Colore dei testi sulla scheda defunto e sulla scheda commemorazione. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #555555.</span></p>
      <p><input name="nicv_css_settings[nicv_sc3]" type="text" id="sc3" value="<?php echo esc_html((isset($options['nicv_sc3']) && $options['nicv_sc3'] != '') ? $options['nicv_sc3'] : '#555555'); ?>" placeholder="sc3" size="20"/>
      <span>Colore link sul form della scheda defunto e della scheda commemorazione. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #555555.</span></p>
      <p><input name="nicv_css_settings[nicv_sc4]" type="text" id="sc4" value="<?php echo esc_html((isset($options['nicv_sc4']) && $options['nicv_sc4'] != '') ? $options['nicv_sc4'] : '#DADADA'); ?>" placeholder="sc4" size="20"/>
      <span>Colore hover link sul form della scheda defunto e della scheda commemorazione. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #DADADA.</span></p>
      <p><input name="nicv_css_settings[nicv_sc5]" type="text" id="sc5" value="<?php echo esc_html((isset($options['nicv_sc5']) && $options['nicv_sc5'] != '') ? $options['nicv_sc5'] : '#DADADA'); ?>" placeholder="sc5" size="20"/>
      <span>Colore del bordo dei box. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #F4F4F4.</span></p>
      <p><input name="nicv_css_settings[nicv_sc6]" type="text" id="sc5" value="<?php echo esc_html((isset($options['nicv_sc6']) && $options['nicv_sc6'] != '') ? $options['nicv_sc6'] : '18px'); ?>" placeholder="sc6" size="20"/>
      <span>Grandezza dei testi sulla scheda defunto e sulla scheda commemorazione. Inserire la dimensione del font con l'unità di misura. Valore di default: 18px.</span></p>
      <p><input name="nicv_css_settings[nicv_sc9]" type="text" id="sc5" value="<?php echo esc_html((isset($options['nicv_sc9']) && $options['nicv_sc9'] != '') ? $options['nicv_sc9'] : '30px'); ?>" placeholder="sc9" size="20"/>
      <span>Grandezza del nome defunto sulla scheda defunto. Inserire la dimensione del font con l'unità di misura. Valore di default: 30px.</span></p>
      <p>&nbsp;&nbsp;</p>
      <p><input name="nicv_css_settings[nicv_r1]" type="text" id="r1" value="<?php echo esc_html((isset($options['nicv_r1']) && $options['nicv_r1'] != '') ? $options['nicv_r1'] : '#222222'); ?>" placeholder="r1" size="20"/>
      <span>Colore carattere del tipo di commemorazione sulla scheda commemorazione. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_r2]" type="text" id="r2" value="<?php echo esc_html((isset($options['nicv_r2']) && $options['nicv_r2'] != '') ? $options['nicv_r2'] : '25px'); ?>" placeholder="r2" size="20"/>
      <span>Dimensione carattere del tipo di commemorazione sulla commemorazione. Inserire la dimensione del font con l'unità di misura. Valore di default: 25px.</span></p>
      <p><input name="nicv_css_settings[nicv_r4]" type="text" id="r4" value="<?php echo esc_html((isset($options['nicv_r4']) && $options['nicv_r4'] != '') ? $options['nicv_r4'] : '#222222'); ?>" placeholder="r4" size="20"/>
      <span>Colore famiglia su partecipazioni e ringraziamenti. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_r5]" type="text" id="r5" value="<?php echo esc_html((isset($options['nicv_r5']) && $options['nicv_r5'] != '') ? $options['nicv_r5'] : '20px'); ?>" placeholder="r5" size="20"/>
      <span>Dimensione famiglia su partecipazioni e ringraziamenti. Inserire la dimensione del font con l'unità di misura. Valore di default: 20px.</span></p>
      <p><input name="nicv_css_settings[nicv_r6]" type="text" id="r6" value="<?php echo esc_html((isset($options['nicv_r6']) && $options['nicv_r6'] != '') ? $options['nicv_r6'] : 'stucco.png'); ?>" placeholder="r6" size="20"/>
      <span>Texture sfondo pad commemorazione in home. Inserire il nome dell'immagine della texture. Valore di default "stucco.png" senza virgolette. Altre texture puoi trovarle nella cartella del plugin, a questo percorso: wp-content/plugins/necrologi-italia-nicv/images/texture.</span></p>
      <p><input name="nicv_css_settings[nicv_r8]" type="text" id="r8" value="<?php echo esc_html((isset($options['nicv_r8']) && $options['nicv_r8'] != '') ? $options['nicv_r8'] : '20px'); ?>" placeholder="r8" size="20"/>
      <span>Dimensione carattere ricorrenze su ultime commemorazioni in home. Inserire la dimensione del font con l'unità di misura. Valore di default: 20px.</span></p>
      <p><input name="nicv_css_settings[nicv_r9]" type="text" id="r9" value="<?php echo esc_html((isset($options['nicv_r9']) && $options['nicv_r9'] != '') ? $options['nicv_r9'] : '#555555'); ?>" placeholder="r9" size="20"/>
      <span>Colore carattere ricorrenze su ultime commemorazioni in home. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #555555.</span></p>

      <hr/>
      <h4>Form condoglianze</h4>

      <p><input name="nicv_css_settings[nicv_f0]" type="text" id="f0" value="<?php echo esc_html((isset($options['nicv_f0']) && $options['nicv_f0'] != '') ? $options['nicv_f0'] : '#EAEAEA'); ?>" placeholder="f0" size="20"/>
      <span>Colore background form. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #EAEAEA.</span></p>
      <p><input name="nicv_css_settings[nicv_f1]" type="text" id="f1" value="<?php echo esc_html((isset($options['nicv_f1']) && $options['nicv_f1'] != '') ? $options['nicv_f1'] : '#FFFFFF'); ?>" placeholder="f1" size="20"/>
      <span>Colore background spunta pubblica messaggio. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #FFFFFF.</span></p>
      <p><input name="nicv_css_settings[nicv_f2]" type="text" id="f2" value="<?php echo esc_html((isset($options['nicv_f2']) && $options['nicv_f2'] != '') ? $options['nicv_f2'] : '#222222'); ?>" placeholder="f2" size="20"/>
      <span>Colore background bottoni. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #222222.</span></p>
      <p><input name="nicv_css_settings[nicv_f3]" type="text" id="f3" value="<?php echo esc_html((isset($options['nicv_f3']) && $options['nicv_f3'] != '') ? $options['nicv_f3'] : '#CCCCCC'); ?>" placeholder="f3" size="20"/>
      <span>Colore background hover bottoni. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #CCCCCC.</span></p>
      <p><input name="nicv_css_settings[nicv_f4]" type="text" id="f4" value="<?php echo esc_html((isset($options['nicv_f4']) && $options['nicv_f4'] != '') ? $options['nicv_f4'] : '#AAAAAA'); ?>" placeholder="f4" size="20"/>
      <span>Colore scritte bottoni. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #AAAAAA.</span></p>
      <p><input name="nicv_css_settings[nicv_f5]" type="text" id="f5" value="<?php echo esc_html((isset($options['nicv_f5']) && $options['nicv_f5'] != '') ? $options['nicv_f5'] : '#555555'); ?>" placeholder="f5" size="20"/>
      <span>Colore scritte hover bottoni. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #555555.</span></p>

      <hr/>
      <h4>Messaggi pubblicati</h4>

      <p><input name="nicv_css_settings[nicv_m0]" type="text" id="m0" value="<?php echo esc_html((isset($options['nicv_m0']) && $options['nicv_m0'] != '') ? $options['nicv_m0'] : '#CCCCCC'); ?>" placeholder="m0" size="20"/>
      <span>Colore header messaggio. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #CCCCCC.</span></p>
      <p><input name="nicv_css_settings[nicv_m1]" type="text" id="m1" value="<?php echo esc_html((isset($options['nicv_m1']) && $options['nicv_m1'] != '') ? $options['nicv_m1'] : '#555555'); ?>" placeholder="m1" size="20"/>
      <span>Colore titolo mittente messaggio. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #555555.</span></p>
      <p><input name="nicv_css_settings[nicv_m2]" type="text" id="m2" value="<?php echo esc_html((isset($options['nicv_m2']) && $options['nicv_m2'] != '') ? $options['nicv_m2'] : '#444444'); ?>" placeholder="m2" size="20"/>
      <span>Colore testo messaggio. Inserire il colore in esadecimale comprendendo anche il simbolo. Valore di default: #444444.</span></p>
    </div>
  </div>
</div>

        </table>
        <input type="submit" value="Salva le modifiche" class="button button-primary"/>
    </form>
</div>


<?php }

?>
