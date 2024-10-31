<?php

function NICV_resources() {
      add_action('wp_head','nicv_css');
      wp_enqueue_script('jquery', 'jquery-2.1.4-1.js', array(), null, true);

      wp_register_script('nicvmailjs', plugins_url( 'nicvmail.js', __FILE__ ));
      wp_enqueue_script('nicvmailjs');

      wp_localize_script('nicvmailjs', 'my_vars', array(

                'ajaxurl' => admin_url('admin-ajax.php'),

            ));

      wp_register_script('nicvpolicyjs', plugins_url( 'nicvpolicy.js', __FILE__ ));
      wp_enqueue_script('nicvpolicyjs');

      wp_localize_script('nicvpolicyjs', 'my_vars', array(

                'ajaxurl' => admin_url('admin-ajax.php'),

            ));
      }
add_action( 'wp_enqueue_scripts', 'NICV_resources');

function nicv_condoglianze_ajax(){

    session_start();
    $nicv_options = get_option( 'nicv_settings' );

    if(strtoupper($_POST['nicaptcha']) == $_SESSION['nicaptcha']){

      $fields = array(
        'id_defunto',
        'mittente',
        'replyto',
        'msg',
        'pubblicato',
        'privacy',
        'message-consent',

        'ringraziamento_indirizzo',
        'ringraziamento_email',
        'ringraziamento_messaggi',
        'ringraziamento_numero',
      );

      $nicv_data = array();
      foreach($fields as $f){
        if(wp_kses_post($_POST[$f])!= null) $nicv_data[$f] = wp_kses_post($_POST[$f]);
      }

      echo nicvmail($nicv_options['nicv_id_azienda'], $nicv_options['nicv_apikey'], $nicv_data);

    }else{

      echo '-ko#Codice di sicurezza errato';

    }

}

function nicvmail($id_azienda, $apikey, $nicv_data){

    $nicv_options = get_option( 'nicv_settings' );

    $mess = array_merge($nicv_data, array(
        'id_azienda' => $nicv_options['nicv_id_azienda'],
        'apikey' => $nicv_options['nicv_apikey'],
        'consenso' => 1,));

    $nicv_result = wp_remote_retrieve_body(wp_remote_post(NICV_HOST . "nicvmail.api", array(
      'method'      => 'POST',
      'body'        => $mess)));

    return $nicv_result;
  }

  add_action('wp_ajax_condoglianze', 'nicv_condoglianze_ajax');
  add_action('wp_ajax_nopriv_condoglianze', 'nicv_condoglianze_ajax');


  function nicv_policy_ajax(){
        $nicv_options = get_option( 'nicv_settings' );
        define('server', 'http://www.methodoweb.com/privacy/getdoc.php');
        
        $qs = array(
            'ids' => $nicv_options['nicv_id_policy'],
            'key' => 'asdasd',
            'doc' => 3
        );

        $nicv_result = wp_remote_retrieve_body(wp_remote_post(server, array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'ids' => $nicv_options['nicv_id_policy'],
            'key' => 'asdasd',
            'doc' => 3
        ),)));

        if(substr($nicv_result, 0, 3) <> '+ok'){
            echo esc_attr('error: ' . $nicv_result);
        }else{
            echo utf8_encode(substr($nicv_result, 3));
            // echo substr($result, 3);
        }
  }

  add_action('wp_ajax_nicv_policy', 'nicv_policy_ajax');
  add_action('wp_ajax_nopriv_nicv_policy', 'nicv_policy_ajax');


function nicv_css(){ 
$css = get_option('nicv_css_settings');
$nicv_url = plugins_url();

echo "<style>
/* Stile Cimitero Virtuale */
a {outline: 0;text-decoration:none!important;}
* {-webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;}
.nicv-wrapper{padding: 8px; width:100%; background: #FFFFFF none repeat scroll 0% 0%; float: left; box-sizing: border-box;display:flex;justify-content:center;align-content:center;flex-wrap: wrap;}
.nicv-wrapper h2{font-size:1.250em; font-weight: normal;}
.nicv-menu-letters{list-style: none; width:100%; float:left; margin:20px 0 0 10px;}
.nicv-menu-letters li{float:left; margin:3px; text-align:center; width:3.11%; background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_ne1']."'); -moz-border-radius: 3px; border-radius: 3px; -webkit-border-radius: 3px;list-style-type:none;}
.nicv-menu-letters li a{text-decoration: none; color: #999; display:block;  padding:5px; border:1px solid #d6d6d6;  -moz-border-radius: 3px; border-radius: 3px; -webkit-border-radius: 3px;}
.nicv-menu-letters li a:hover{background-image:url('images/texture/redox_01-letters-menu-hover.jpg'); color:#fff; /*border: 1px solid #fff;*/ text-shadow: 0px 0px 0px #F2F2F2, 0px 0px 0px #666; -moz-border-radius: 3px; border-radius: 3px; -webkit-border-radius: 3px;}
#nicv-search{width:100%; margin:20px 0; float:left; color: #999;position:relative;z-index:5;}
#nicv-nome-search{width:69%; padding:10px; -moz-border-radius: 3px; border-radius: 3px; -webkit-border-radius: 3px; border:1px solid #d6d6d6; font-size:1em;position:relative;z-index:99999;}
#nicv-search-button{width: 20%; margin-left:7.5%; padding:10px; cursor:pointer; background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_ne1']."'); -moz-border-radius: 3px; border-radius: 3px; -webkit-border-radius: 3px; border:1px solid #d6d6d6; color: #999;font-size:1em;}
#nicv-search-button:hover{background-image:url('images/texture/redox_01-letters-menu-hover.jpg');}
.nicv-pad-wrapper{width:100%; float:left; display:table; background: #FFFFFF none repeat scroll 0% 0%;}
.nicv-pad,.nicv-pad-last{width:230px; float:left; margin-bottom:20px;}
.firma{font-size: 13px;text-align: center;}
p.nicv-dati a,.firma a{text-decoration: underline;color:".$css['nicv_ne4']."}
p.nicv-dati a:hover,.firma a:hover{text-decoration: none;}
p.nicv-dati.center{color:#666;background: #fff;border: 1px solid #fff;margin-top:10px;padding:30px;}
.nicv-pad,.nicv-pad-last{position: relative;display: inline-block;background-color: #fff;box-shadow: 0 1px 2px rgba(0,0,0,0.15);transition: all 0.3s ease-in-out;margin:0 2.5% 20px;}
.nicv-pad:hover,.nicv-pad-last:hover{ -webkit-box-shadow: 4px 4px 6px 0px rgba(0,0,0,0.4); -moz-box-shadow: 4px 4px 6px 0px rgba(0,0,0,0.4); box-shadow: 4px 4px 6px 0px rgba(0,0,0,0.4); transition: box-shadow 0.3s ease-in, color 0.2s linear;}
.nicv-pad-info{width:100%; background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_ne1']."'); padding:10px 0; border:1px solid #d6d6d6; height:200px; color:".$css['nicv_ne0'].";}
.nicv-nome{font-weight: bold;font-size: ".$css['nicv_ne5'].";color:".$css['nicv_ne4']."}
.nicv-dati{padding: 5px 0 0 10px;}
.nicv-dati a, .nicv-scheda-col a {text-decoration: underline; color:".$css['nicv_sc3'].";}
.nicv-dati a:hover, .nicv-scheda-col a:hover {text-decoration: none;color:".$css['nicv_sc4'].";}
.nicv-h1{font-size: 3em; text-align: center; padding: 30px 0;}
.nicv-pad img, .nicv-pad-last img{height:300px; width:100%;width:230px;}
.nicv-pad p a, .nicv-pad-last p a{display:block;  color:#999; font-size:1.250em; font-weight:bold;}
.nicv-dati{font-size:1em;}
/*.nicv-scheda-defunto{float:left; width:98%; padding: 0 1%; display: table-row;}
.nicv-scheda-defunto div{display:table;}
.nicv-scheda-col{width:31%; float:left; height:258px;} */
.nicv-scheda-col, .nicv-social{margin:1%; background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_sc0']."');/*display: table-cell;*/ border-radius:3px;text-align:center;}
.nicv-scheda-col img{width:auto; height:auto;max-height: 240px;}
.nicv-scheda-col p,.nicv-scheda-col-last p{line-height:25px; padding:0 0 0 3%;color:".$css['nicv_sc2'].";font-size:".$css['nicv_sc6'].";}
.nicv-scheda-col h2{color:".$css['nicv_sc1'].";font-size:".$css['nicv_sc9'].";font-weight: bold;}
.nicv-link{float:left; text-decoration:underline; color:#999; margin-bottom:20px;}
/*.first{text-align: center;}*/
.nicv-link:hover{color:#676767;}
.center{text-align:center;}
img.logoazienda {border-top-left-radius: 0px;border-top-right-radius: 0px;}
.prova{width:70%;margin:0 auto;}
.grazie{font-size:".$css['nicv_sc9'].";color:".$css['nicv_sc1'].";}
.colonna{width:100%;}
#messaggi{width:70%;height:40px;}
.ringraziamento{width:70%;height:40px;}
.img-messaggi{width:40px;height:40px;margin-right:5px;}
#btn-ringraziamenti{padding:10px;border-radius:10px;}
.foto-defunto{border-radius:5px;box-shadow: 0px 0px 15px 5px #888888;margin-top:-10px;max-height:300px !important;}
.info-ricorrenze{width:48%;display:inline-block;}
.posizione-nome{top:-50px;position:relative;}
.cont-ricorrenza{max-width:50%;margin:0 auto;}
.titolo-ricorrenza{width:100%;display:inline-block;}
.famiglia{font-size:".$css['nicv_r5'].";color:".$css['nicv_r4'].";}
.link-manifesti{text-decoration: underline; color:".$css['nicv_sc3'].";}
.link-manifesti:hover{text-decoration: none;color:".$css['nicv_sc4'].";}
.tasto{border:transparent;border-radius:2px;color:".$css['nicv_sc2'].";padding:10px;border-radius:5px;width:100px;}
.titolo-ricorrenza h2{color:".$css['nicv_r1'].";font-size:".$css['nicv_r2'].";word-break: break-word;}
.cerca{background:#FFFFFF;color:#a1a1a1;padding:10px;border-radius:5px;}
.manifesto{max-width:600px;}
.img-commemorazione{width:150px!important;height:200px!important;border-radius:5px;margin:0 auto;}
.pad-commemorazione{background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_r6']."');border:1px solid #EDEDED;height:550px;}
.nicv-commemorazione{font-size:".$css['nicv_r8'].";color:".$css['nicv_r9'].";}
.info-commemorazione{background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_r6']."');border:transparent;padding:25px;width:100%;color:".$css['nicv_ne0'].";min-height:200px;}
.box-mittente{background-color:#FFFFFF;padding:5px;width:100%;}
#button_privacy{text-decoration:underline !important;}

.icon{display:inline-block;margin:5px 10px 0 0; width:32px; height:32px; /*float:left;*/ transition:width 2s, height 2s; -webkit-transition:width 2s, height 2s, -webkit-transform 2s; /* Safari */ opacity:0.7; -moz-transition:width 2s, height 2s, -moz-transform 2s; /* Firefox*/ filter:alpha(opacity=70); /* For IE8 and earlier */}
.icon:hover{ transform:rotate(360deg);  -webkit-transform:rotate(360deg);/* Safari */ -moz-transform:rotate(360deg);/* Firefox*/ opacity:1.0; filter:alpha(opacity=100); /* For IE8 and earlier */}

.no-svg .svg {display: block;width: 100px;height: 100px;}
/*#colore-icone {fill: ".$css['nicv_sc5'].";}*/
.svg{display:none;}

.link-condoglianze{font-size:20px;color:#555555;/*text-decoration:underline ".$css['nicv_sc1'].";*/text-align:left;font-size:15px;}
.condoglianze:hover{color:".$css['nicv_sc1'].";}
.condoglianze{border-bottom: 1px solid #dedede; margin-bottom:5px;padding:5px;text-align:left;cursor:pointer;}
.frasi{padding:10px;font-size:15px;float:right;}

.grid-item {
  width: 47%;
  min-height:300px;
  float: left;
  background-image:url('".$nicv_url."/necrologi-italia-nicv/images/texture/".$css['nicv_sc0']."');
  border: 1px solid ".$css['nicv_sc5'].";
  border-radius: 5px;
  padding:0px 10px 0px 10px;
}

.grid-item img{
    padding:20px;
}

.icona-ricorrenza{position:relative;}
.logo-ni{float:left;margin-top:-50px;opacity:0.6;bottom:-20px;position:relative;}


.row-ringr{
    display:flex;
    align-items:center;
    align-content:center;
    justify-content:center;
    margin-bottom:20px;
}

#mittente,#replyto,#msg,#nicaptcha,#indirizzo-fisico,#email_ringraziamento,#messaggi,#numero-telefono{
    font-size: ".$css['nicv_sc6'].";
}

#indirizzo-fisico,#email_ringraziamento,#messaggi,#numero-telefono{
    width:80%
}

#bottone,#btn-ringraziamenti{
    font-size: ".$css['nicv_ne5'].";
}

/* MESSAGGI PUBBLICATI */

.message-card{
    display: inline-block;
    border: 1px solid #DADADA;
    max-width:1920px;
    width:100%;
    margin:5px;
}

.message-consent{background-color: ".$css['nicv_f1'].";text-align: left;padding: 10px;}
#bottone, #btn-ringraziamenti{background-color: ".$css['nicv_f2'].";border:1px solid ".$css['nicv_f2'].";color:".$css['nicv_f4'].";}
#bottone:hover, #btn-ringraziamenti:hover{background-color: ".$css['nicv_f3'].";border:1px solid ".$css['nicv_f3'].";color:".$css['nicv_f5'].";}
.busta-messaggio{filter:brightness(0.4);}

.mittente{
    background: ".$css['nicv_m0'].";
    padding:10px;
    color: ".$css['nicv_m1'].";
    text-align:left;
}

.corpo-messaggio{
    padding:10px;
    color: ".$css['nicv_m2'].";
}

.testo-messaggio{
    font-size: ".$css['nicv_sc6']."!important;
}

.data-messaggio{
    font-size:12px;
    font-style:italic;
    text-align:right;
    color: ".$css['nicv_m2'].";
}

.form-style{
    background: ".$css['nicv_f0'].";
    padding:20px;
    width:96%;
}


@media (max-width: 1200px){

    .grid-item{width:100%;min-height:200px;border:transparent;border-bottom:1px solid ".$css['nicv_sc5'].";border-radius:0px;padding:10px;}

}

@media (min-width: 768px) and (max-width: 1023px) {
    /* Structure 768 to 1200 */
    .nicv-pad,.nicv-pad-last{width:230px;margin:0 1.6% 20px;}
    #nicv-search-button{margin-left:5.5%;}
    .nicv-menu-letters li {width:5%;}
    .nicv-scheda-col{width:100%;}
  .titolo-ricorrenza{width:100%;display:inline-block;float:left;}
}
@media (max-width: 767px) {
    /* Structure  < 767 */
    .nicv-pad,.nicv-pad-last{width:230px; margin: 0 2.5% 20px;}
    .nicv-pad img, .nicv-pad-last img{width:100%; height:320px;}
    .nicv-pad-info{width:100%;}
    #nicv-search-button{margin-left:3%;}
    .nicv-menu-letters li {width:8%;}
    /*.nicv-scheda-col{width:35%;}*/
    .second{width:60%;}
    .third,.fourth,.fifth,.sixth{width:97%;}
    .prova{width:100%;margin:0 auto;}
    .grid-item{height:auto;}
    .info-ricorrenze{width:100%;display:inline-block;}
    .posizione-nome{top:0px;position:relative;}
  .nicv-wrapper{width:100%; background: #FFFFFF none repeat scroll 0% 0%; float: left; box-sizing: border-box;}
}

@media (max-width : 480px) {
/* Structure < 480px */
    #nicv-search-button{margin-left:2%;}
    .nicv-menu-letters li {width:20%;}
    .nicv-pad,.nicv-pad-last{width:230px; margin: 10px auto; float: none;}
    .nicv-scheda-col{width:100%;}
    .prova{width:100%;margin:0 auto;}
    .grid-item{height:auto;width:96.4%;}
  .nicv-wrapper{width:100%; background: #FFFFFF none repeat scroll 0% 0%; float: left; box-sizing: border-box;}
  .manifesto{max-width:270px;}
  .nicv-scheda-col img{max-width:250px;}
}

@media (max-width : 300px) {
/* Structure < 320px */
    .nicv-pad,.nicv-pad-last{width:80%; margin: 0 auto 20px; float: none;}
    .nicv-pad-info{ width:100%; margin:0; height:100%;}
    .nicv-pad img, .nicv-pad-last img{max-width:100%; height:auto;}
    .nicv-menu-letters li {width:12%;}
    .prova{width:100%;margin:0 auto;}
    .grid-item{height:auto;width:96.4%;}
    .nicv-wrapper{width:100%; background: #FFFFFF none repeat scroll 0% 0%; float: left; box-sizing: border-box;}
  }

  .masonry {
  columns: 1;
  column-gap: 5px;
  }

  .masonry-manifesto {
  columns: 3;
  column-gap: 5px;
  }

  .message-masonry {
  columns: 4;
  column-gap: 5px;
  }

  .masonry img{border-radius:5px;}
  @media (max-width: 1200px) {
    .masonry{columns: 1;}
  }
  @media (min-width: 993px) and (max-width: 1023px){
    .message-masonry{columns: 3}
    .masonry-manifesto{columns: 2;}
  }
  @media (max-width: 992px) {
    .masonry{columns: 1;}
    .message-masonry{columns: 3}
    .masonry-manifesto{columns: 2;}
  }
  @media (max-width: 768px){
    .masonry{columns: 1;}
    .message-masonry{columns: 1}
    .masonry-manifesto{columns: 1;}
  .grid {
    display: inline-block;
    margin-bottom: 16px;
    position: relative;
    width: 100%;
  }
  .masonry  img {
      border-radius: 5px;
    }
  }

  .mt-auto {
    margin-top:auto;
  }

  .accordion{
    background:#FFFFFF;
    color:#333333;
    padding:0em;
    position: relative;
    border-radius:5px;
    text-align:center;
  }

  div.acco{
    position: relative;
  }
  div.p{
    max-height:0px;
    overflow: hidden;
    transition:max-height 0.5s;
    background-color: white;
  }
  input:checked ~ p ~ div.p{
    max-height:200px;
  }

  @media (max-width: 768px){
    input:checked ~ p ~ div.p{
      max-height:500px;
    }
  }
</style>";

}

function nicv_shortcode_servizio_necrologi() {
    if ( ! defined( 'ABSPATH' ) ) {
        exit( 'Direct script access denied.' );
    }
    $nicv_options = get_option( 'nicv_settings' );
    $nicv_pagina = $nicv_options['nicv_servizioNecrologi'];
    global $pagename;
    global $wp_query;
    require_once 'nicv.inc.php';
    $nicv_url = plugins_url();
    add_action('template_redirect', 'nicv_nuovoSeo');
    echo utf8_decode(html_entity_decode(generateMeta($nicv_pagina, $nicv_options['nicv_citta'], ''))); // --- NICV META TAGS ---
    $nicv_message = '<link rel="canonical" href="'.site_url().'/'.$pagename.'" />';
    if(!isset($wp_query->query_vars['c'])){
      $nicv_message = $nicv_message.'

          <div class="acco">
            <input style="visibility:hidden;" type="checkbox" id="faq-1">
            <p class="accordion"><label class="label cerca" for="faq-1">Cerca&nbsp;&nbsp;<img width="20" style="vertical-align:middle;" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/ricerca-defunto.png" alt="Ricerca defunto"/></label></p>
          <div class="p">
                <div id="nicv-search">
                  <form method="get" action="'.$nicv_options['nicv_servizioNecrologi'].'" abineguid="">
                      <input id="nicv-nome-search" type="text" value="" placeholder="Cerca per nome o cognome" name="c"></input>
                      <input id="nicv-search-button" type="submit" value="Cerca"></input>
                  </form>
              </div>

                <center><p style="color:#aaa7a7;">Oppure cerca per iniziali del cognome</p></center>
                <div class="nicv-menu-wrapper">
                <ul class="nicv-menu-letters">';
                    for($i = 65; $i < 91; $i++){
                      $nicv_message = $nicv_message.'<li><a href="necrologi-funebri-'.chr($i).'.php" title="necrologio dei defunti che hanno iniziale del cognome per '. chr($i) .'">' . chr($i) . '</a></li>';
                    }
                $nicv_message = $nicv_message.'</ul>
              </div>
              <p>&nbsp;&nbsp;</p>
              <p>&nbsp;&nbsp;</p>
          </div>
        </div>';

    if($nicv_options['nicv_abilitaManifesti'] == "true"){
        $nicv_message = $nicv_message. '<p>&nbsp;&nbsp;</p><center><p><a class="link-manifesti" href="'.site_url().'/'.$nicv_options['nicv_manifestiFunebri'].'" title="Manifesti funebri di '.$nicv_options['nicv_citta'].'">Passa alla versione manifesti</a></p></center>';
      }

     }


    $nicv_message = $nicv_message.'<p>&nbsp;</p>';

    if(!isset($wp_query->query_vars['c'])){ 
        $nicv_message = $nicv_message.'
    
    <br/>
    <br/>
    <br/>';
    }
    $nicv_message = $nicv_message.'<p>&nbsp;</p>'; 

    if(!isset($wp_query->query_vars['c'])){
        $nicv_message = $nicv_message.'<h3 style="text-align: center;">Ultimi annunci funebri inseriti:</h3>
        <p>&nbsp;</p>
        <p>&nbsp;</p>';
    }else{
        $nicv_message = $nicv_message.'<center><h2>Annunci funebri trovati:</h2></center>';
    }
    $nicv_message = $nicv_message.'<div class="nicv-pad-wrapper">';

    $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
       $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
       $ni->set_apikey($nicv_options['nicv_apikey']);
       if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);
   
   if(isset($wp_query->query_vars['c'])){
           $defunti = $ni->get_by_char($wp_query->query_vars['c']);
       }else{
           $defunti = $ni->get_last($nicv_options['nicv_pads']);
       }
   
       if(!$defunti){
           if(!isset($wp_query->query_vars['c'])){
               $nicv_message = $nicv_message.'<br/><br/><p>Non ci sono annunci funebri da visualizzare</p><br/><br/>';
           }else {
                $nicv_message = $nicv_message.'<p class="nicv-dati center"><b>Spiacenti, ma questa ricerca non ha prodotto risultati.</b><br/><br/>Puoi tentare di estendere la ricerca anche all\'interno dell\'archivio nazionale di <a href="https://www.necrologi-italia.it/ni_search.php?id_azienda=' . $nicv_options['nicv_id_azienda'] . '&query=' .  $wp_query->query_vars['c'] . '" title="Archivio nazionale Necrologi Italia - Servizio Necrologie in tempo reale: tutte le informazioni prima e dopo le esequie funebri" >Necrologi Italia</a>.</p><br/><br/><br/><br/><br/><br/>';
           }
       }else{
           foreach($defunti as $defunto){
           
               
                   $nicv_message = $nicv_message.'<center><div class="nicv-pad">';
                   
                   $nicv_message = $nicv_message.'<a href="'.site_url() .'/'. nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']) . '" title="Necrologio e funerali di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'">
   
                            <img src="' . $defunto['img_src'] . '" width="100%" height="350" alt="Necrologio ed informazioni sul funerale di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'"/>';
   
                     
                            $nicv_message = $nicv_message.'<div class="nicv-pad-info">';
   
                            $nicv_message = $nicv_message.'<p class="nicv-nome">' . $defunto['nome'] . ' ' . $defunto['cognome'] .'
                           </p>';
                       if($defunto['ts_data_nascita']){
                           if($defunto['eta'] > 1){
                            $nicv_message = $nicv_message.'<p class="nicv-dati anni">Di anni ' . $defunto['eta'] . '</p>';
                           }
                       }
                       $nicv_message = $nicv_message.'<p class="nicv-dati decesso">' . ucfirst($defunto['txt-deceduto']) . ' il ' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . '</p>';
                       $nicv_message = $nicv_message.'</div>';
   if ((isset($defunto['logo_azienda'])) AND (( $nicv_options['nicv_logos']) === 'true'))
   {	
    $nicv_message = $nicv_message.'<img src="' . $defunto['logo_azienda'] . '" class="logoazienda" style="max-height:50px;max-width:150px;margin-top:0px;" /><p></p>';
   };
   $nicv_message = $nicv_message.'</a>
           </div></center>';
           }
       }
    $nicv_message = $nicv_message.'</div>
    <p style="font-size: 10px;">&nbsp;</p>
    <p class="firma">Servizio necrologi a cura di <a href="https://www.necrologi-italia.it" title="Servizio necrologi locali e nazionali in tempo reale www.necrologi-italia.it">necrologi-italia.it</a></p>
    
    </div></center>';
    return $nicv_message;
    }

    function nicv_shortcode_scheda_defunto() {
      if ( ! defined( 'ABSPATH' ) ) {
        exit( 'Direct script access denied.' );
      }
      global $metaDefunto, $pagename, $nicv_pagina, $defunto;
      $nicv_options = get_option( 'nicv_settings' );
      $nicv_css_options = get_option( 'nicv_css_settings' );
      $nicv_pagina = $nicv_options['nicv_schedaNecrologio'];
      $nicv_url = plugins_url();
		
      global $wp_query;
      $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);

      $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
      $ni->set_apikey($nicv_options['nicv_apikey']);
      require_once 'nicv.inc.php';

      if(isset($wp_query->query_vars['id'])){
          $defunto = $ni->get_by_id($wp_query->query_vars['id']);
          $nicv_seo_citta = $nicv_options['nicv_citta'];
            if(isset($wp_query->query_vars['id'])){

            $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);

            $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
            $ni->set_apikey($nicv_options['nicv_apikey']);
            if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);

            $defunto = $ni->get_by_id($wp_query->query_vars['id']);
            
            if($defunto){
              define('NICV_seoCitta', trim(seoCitta($defunto['luogo_decesso'], $defunto['comune_cimitero'])));
              $metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta);
              $wp_query->query_vars['title'] = NICV_seoCitta;
              $urlScheda = nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso'], true);
              $fbSharerLink = 'https://www.facebook.com/sharer/sharer.php?sdk=joey&u=' . urlencode($urlScheda) . '?ref=fb&display=popup&ref=plugin&src=share_button?ref=fb';
              $twSharerLink = 'https://www.twitter.com/share?url=' . urlencode($urlScheda) . '?ref=tw';
              $waSharerLink = 'https://api.whatsapp.com/send?text=Lutto+per+la+scomparsa+di+' . $defunto['cognome'] . '+' . $defunto['nome'] .' '.urlencode($urlScheda) . '?ref=wa+E\'+possibile+inviare+gratuitamente+messaggi+di+cordoglio.';
              $tgSharerLink = 'https://telegram.me/share/url?url='  . urlencode($urlScheda) . '?ref=tg&text=Lutto per la scomparsa di ' . $defunto['cognome'] . ' ' . $defunto['nome'] . ' - Puoi inviare gratuitamente messaggi di cordoglio - ';
            }
            
          }else{
            $defunto = false;
            $urlScheda = "";
            $fbSharerLink = '';
            $twSharerLink = '';
            $waSharerLink = '';
            $tgSharerLink = '';
          }
          //echo html_entity_decode(generateTitle());
          add_action('template_redirect', 'nicv_nuovoSeo');
      }
      else{
          $nicv_seo_citta = "";
      }

        if(isset($defunto['id'])){
            $nicv_slug = nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']);
        }
        else{
            $nicv_slug = "";
        }
        
        // --- START NICV BASE --- // --- NICV BASE --- (just after <head> !!!)
        $nicv_message = '<base href="' . $nicv_options['nicv_host'] . $nicv_options['nicv_pagesPath'] . '">';
        // --- END NICV BASE ---


        // --- START NICV META TAGS ---
        $nicv_message = $nicv_message.'<link rel="canonical" href="'. site_url().'/'.$nicv_slug .'" />';


        // --- START NICV CSS RESOURCES ---
        wp_register_style('nicvmailcss', plugins_url( 'nicvmail.css', __FILE__ ));
        wp_enqueue_style('nicvmailcss');
        wp_register_style('remodalcss', plugins_url( 'remodal.css', __FILE__ ));
        wp_enqueue_style('remodalcss');
        wp_register_style('remodalthemecss', plugins_url( 'remodal-default-theme.css', __FILE__ ));
        wp_enqueue_style('remodalthemecss');
        // --- END NICV CSS RESOURCES ---

        

        $nicv_message = $nicv_message.'<p>&nbsp;</p>
                <a class="nicv-link" style="text-align:left;" href="'.site_url().'/'.$nicv_options['nicv_servizioNecrologi'].'" title="Torna alla pagina dei necrologi di '.$nicv_options['nicv_citta'].'"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/torna-indietro.png" title="Torna al servizio necrologi '.$nicv_options['nicv_citta'].'"/>
                </a>
                <div class="nicv-wrapper">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <div class="nicv-pad-wrapper" itemscope itemtype="https://schema.org/Person">
                <div>';

                  if(!$defunto){
                  $nicv_message = $nicv_message.'<br/><br/><p>Errore</p><br/><br/>';
                }
                  else{
                $nicv_message = $nicv_message.'<div class="nicv-scheda-col" style="border:transparent;">';    // FOTO DEFUNTO
                  $nicv_message = $nicv_message.'<img itemprop="image" class="foto-defunto" src="' . $defunto['img_src'] . '" width="205" height="273" alt="Funerali ' . $nicv_seo_citta . ' - Necrologio di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                $nicv_message = $nicv_message.'</div><br/>';
                $nicv_message = $nicv_message.'<div class="nicv-scheda-col info-defunto-animation">';    // NOME DEFUNTO
                  $nicv_message = $nicv_message.'<h2><span itemprop="name">' . $defunto['displayName'] . '</span></h2>';
                  if($defunto['eta'] > 1){
                    $nicv_message = $nicv_message.'<p>Di anni ' . $defunto['eta'] . '</p>';
                  }
                  $nicv_message = $nicv_message.'<p>' . ucfirst($defunto['txt-deceduto']) . ' il <span itemprop="deathDate">' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . '</span> ' . $ni->city_prefix($defunto['luogo_decesso']) . '</p>';
                  if($defunto['txt-coniugale'] <> ''){
                    $nicv_message = $nicv_message.'<p>' . $defunto['txt-coniugale'] . ' ' . $defunto['nome_c'] . ' ' . $defunto['cognome_c'] . '</p>';
                  }
                $nicv_message = $nicv_message.'<br/><!--<div><button type="button" class="tasto">Ti penso<br/><img src="necrologi-italia-nicv/images/icon-nicv/cuore-like.png"/> 100</button>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="tasto">Mi manchi<br/><img src="necrologi-italia-nicv/images/icon-nicv/cuore-like.png"/> 100</button></div>--><br/></div>';
            }

                    $nicv_message = $nicv_message.'<div class="masonry">';
                
                            $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // CHIESA
                            if(isset($defunto['txt-funerale']) <> ''){
                                $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/chiesa-funerale.png" width="" height="" alt="Chiesa in cui si celebra il funerale di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                $nicv_message = $nicv_message.'<p>' . str_replace('{{data-funerale}}', date($nicv_options['nicv_formatoData'], $defunto['ts_data_funerale']), $defunto['txt-funerale']) . '</p>';
                $nicv_message = $nicv_message.'</div>';

                            $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // CIMITERO
                            $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/cimitero-salma.png" width="" height="" alt="Cimitero che ospita la salma di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                    $nicv_message = $nicv_message.'<p>' . $defunto['txt-trattamento'] . '</p>';
                $nicv_message = $nicv_message.'</div>';
                
                
                if($defunto['note'] <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';   // MESSAGGIO DELLA FAMIGLIA
                    $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/messaggio-famiglia.png" width="" height="" alt="Messaggio della famiglia per ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                $nicv_message = $nicv_message.'<p>' . $defunto['note'] . '</p>';
                $nicv_message = $nicv_message.'</div>';
                  }
                
                if($defunto['txt-annunciano'] <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // ANNUNCIO
                    $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/annuncio-funebre.png" width="" height="" alt="Annuncio funebre di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                $nicv_message = $nicv_message.'<p>' . $defunto['txt-annunciano'] . '</p>';
                $nicv_message = $nicv_message.'</div>';
                  }
                
                
                if($defunto['infobox'] <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';     // INFOBOX
                    $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/informazioni-salma.png" width="" height="" alt="Informazioni sul funerali di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                $nicv_message = $nicv_message.'<p>' . $defunto['infobox'] . '</p>';
                $nicv_message = $nicv_message.'</div>';
                  }
                
                
                if(isset($defunto['associazione']['txtDonazione']) <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // DONAZIONI
                    $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/donazione-in-memoria.png" width="" height="" alt="Donazioni e offerte" />';
                if($defunto['associazione']['pagelinkAssociazione'] <> '')
                $nicv_message = $nicv_message.'<p>' . $defunto['associazione']['txtDonazione'] . '&nbsp;<a href="'.$defunto['associazione']['pagelinkAssociazione'].'">'.$defunto['associazione']['associazione'].'</a></p>';
                else
                $nicv_message = $nicv_message.'<p>' . $defunto['associazione']['txtDonazione'] . '&nbsp;'.$defunto['associazione']['associazione'].'</p>';
                $nicv_message = $nicv_message.'</div>';
                  }
                
                
                if($defunto['manifesto_src'] <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // MANIFESTO
                    $nicv_message = $nicv_message.'<img class="svg" src="" width="" height="" alt="Manifesto funebre per ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" />';
                    $nicv_message = $nicv_message.'<center><a href="' . site_url().'/'.$nicv_slug . '/#modal3"><img src=" '. $defunto['manifesto_src'].'"></a></center>';
                    $nicv_message = $nicv_message.'</div>';
                  }


                            if(($nicv_options['nicv_abilitaFiori'])&&($defunto['linkFiori'] != "")){

                              $fiori = json_decode($defunto['linkFiori']);

                              $variables = array("nome"=>$defunto['nome'],"cognome"=>$defunto['cognome']); 
                              $url_fiori = $fiori->url;
                              $alt_fiori = $fiori->altTitle;
                              foreach($variables as $key => $value){ 
                                $url_fiori = str_replace('{{'.$key.'}}', $value, $url_fiori);
                                $alt_fiori = str_replace('{{'.$key.'}}', $value, $alt_fiori); 
                              } 

                        // INVIO FIORI
                      echo '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/fiori-per-funerale.png" width="" height="" alt="Invio composizioni floreali per funerale" /><br/><br/>';
                      echo '<a href="'.$url_fiori.'" title="'.$alt_fiori.'" rel="'.$fiori->rel.'">'.$fiori->a.'</a></div>';
                  }        
                
                if($defunto['partecipanoAlLutto'] <> '')
                {
                    $nicv_message = $nicv_message.'<div class="nicv-scheda-col grid-item grid">';    // CHI HA FATTO LE CONDOGLIANZE??
                          $nicv_message = $nicv_message.'<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/messaggio.png" width="" height="" alt="Condoglianze gratuite" />';
                          $nicv_message = $nicv_message.'<p>' . $defunto['partecipanoAlLutto'] . '</p>';
                          $nicv_message = $nicv_message.'</div>';
                }
                
                $nicv_message = $nicv_message.'</div>
                <p>&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;</p>';
                $nicv_message = $nicv_message.'<div class="nicv-scheda-col form-style">';   // FORM
                $nicv_message = $nicv_message.'<p>&nbsp;&nbsp;</p><img class="busta-messaggio" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/messaggio-condoglianze-gratuite.png" width="" height="" alt="Messaggio della famiglia per ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" /><br/><br/>
          <h3 class="domanda">Invia gratuitamente le tue condoglianze</h3><br/>
          <div id="nicvmail-command"><p>Sarà nostra cura recapitarle quanto prima alla famiglia di '. $defunto['nome'] . ' ' . $defunto['cognome'] .'</p>';
        
        $nicv_message = $nicv_message.'

  <form id="nicvmail-form">
            <br /><input type="text" id="mittente" placeholder="Nome" required/>
            <input type="text" id="replyto" placeholder="Email" required/>
            <p>&nbsp;&nbsp;</p>
            <textarea id="msg" row="8" col="50" placeholder="Scrivi il tuo messaggio di cordoglio" onchange="document.getElementById(\'msg\').value = document.getElementById(\'msg\').value.replace(/[^\p{L}\p{N}\p{P}\p{Z}^$\n]/gu, \'\');" required></textarea>
            <p><a class="frasi" href="' .site_url().'/'.$nicv_slug . '/#modal">Esempi di frasi di cordoglio</a></p>
            <p>&nbsp;&nbsp;</p>
            <p>&nbsp;&nbsp;</p>
            <img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/divisore.png"/>
            <p>&nbsp;&nbsp;</p>
            <p>&nbsp;&nbsp;</p>
            <label>Verifica il codice di sicurezza:</label><br/>
            <img class="codice" src="'.$nicv_url.'/necrologi-italia-nicv/nicaptcha.jpeg.php" ?ts="time()">
            <input type="text" class="input-form" value="" id="nicaptcha" placeholder="Riscrivi il codice di sicurezza"  />
            <input type="hidden" class="input-form" id="id_defunto" value="'.$defunto['id'].'" /><br/><br/><br/><br/>
          
            <p><a href="' .site_url().'/'.$nicv_slug . '/#modal2">Desideri ricevere un eventuale ringraziamento della famiglia?</a></p><br/><br/>
            <div class="message-consent"><input name="message-consent" id="message-consent" value="si" type="checkbox"/>&nbsp;&nbsp;Desidero che il messaggio sia visibile su questa pagina<br/><span style="font-size:12px;">Il messaggio verrà pubblicato, previa verifica, entro breve tempo.</span></div><br/><br></p>
              <p class="nicv-mail-text">

      <input name="privacy" id="privacy" value="si" type="checkbox"/>  <a id="button_privacy">Ho letto l\'informativa privacy</a>, e acconsento alla memorizzazione dei miei dati nel vostro archivio secondo quanto stabilito dal regolamento europeo per la protezione dei dati personali n. 679/2016, GDPR.<br/><br></p>


                <br/>
              </p>
              <p>&nbsp;&nbsp;</p>
              <p>&nbsp;&nbsp;</p>
              <img class="logo-ni" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/necrologi-italia.png" alt="Servizio di necrologi e morti in Italia in tempo reale"/>

              <input type="submit" id="bottone" value="Invia" />
              
            </div>

          </div>
          
         <!-- </form> -->';

     $nicv_message = $nicv_message.'</div>';

     $nicv_message = $nicv_message.'</div>
</div>';

$nicv_message = $nicv_message.'<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p>
<div class="nicv-pad-wrapper messaggi-animation">';

$nicv_pubblicazioni = array_column($defunto['messaggi'], 'pubblicato');

if(($defunto['messaggi'] != null)&&(in_array(1,$nicv_pubblicazioni))){


$nicv_message = $nicv_message.'<center><h2>In memoria</h2></center>
<p>&nbsp;&nbsp;</p>
  <div class="message-masonry">';


  foreach($defunto['messaggi'] as $nicv_messaggi => $nicv_messaggio){
    if($nicv_messaggio['pubblicato'] == 1){
      $nicv_message = $nicv_message.'<div class="grid">
        <div class="message-card">
          <div class="mittente"><h5><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/messaggio-pubblicato.png" alt="Messaggio di cordoglio per '. $defunto['nome'] . ' ' . $defunto['cognome'] .'"/>&nbsp;&nbsp;'.$nicv_messaggio['mittente'].'</h5></div>
          <div class="corpo-messaggio">
            <p class="testo-messaggio">'.$nicv_messaggio['msg'].'</p><br/>
            <p class="data-messaggio">- '.date("d-m-Y", $nicv_messaggio['ts']).' -
          </div>
        </div>
      </div>';
    }
  }

$nicv_message = $nicv_message.'</div>';

}

$nicv_message = $nicv_message.'</div>';

/*echo '<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p></div>';*/

if (isset($defunto['logo_azienda']) AND ( $nicv_options['nicv_logos'] === "true")) {	
  $nicv_message = $nicv_message. '<div class="nicv-wrapper"><center><img src="' . $defunto['logo_azienda'] . '" style="max-height:100px;max-width:200px;position:relative;top:60px;margin:0 auto;" /></center></div><p>&nbsp;</p>';
  };
  
  $nicv_message = $nicv_message.'<div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));</script>';
  
  
 
  
  $nicv_message = $nicv_message. '<p>&nbsp;&nbsp;</p><p>&nbsp;&nbsp;</p>';
  $nicv_message = $nicv_message. '<div class="nicv-wrapper"><center><p>CONDIVIDI SU:</p>';
  $nicv_message = $nicv_message. '<div class="icon"><a href="'.$fbSharerLink.'" target="_blank" rel="nofollow"><img src="'. $nicv_url . '/necrologi-italia-nicv/images/social/Facebook-icon.png" width="32" height="32" alt="Condividi il lutto di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Facebook"></a></div>';
  $nicv_message = $nicv_message. '<div class="icon"><a href="'.$twSharerLink.'" target="_blank" rel="nofollow"><img src="'. $nicv_url . '/necrologi-italia-nicv/images/social/Twitter-icon.png" width="32" height="32" alt="Condividi il lutto di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Twitter"></a></div>';
  $nicv_message = $nicv_message. '<div class="icon"><a href="'.$waSharerLink.'" data-action="share/whatsapp/share"><img src="'. $nicv_url . '/necrologi-italia-nicv/images/social/Whatsapp-icon.png" width="32" height="32" alt="Condividi il lutto di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su WhatsApp"></a></div>';
  $nicv_message = $nicv_message. '<div class="icon"><a href="'.$tgSharerLink.'" target="_blank" rel="nofollow"><img src="'. $nicv_url . '/necrologi-italia-nicv/images/social/Telegram-icon.png" width="32" height="32" alt="Condividi il lutto di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Telegram"></a></div>';
  $nicv_message = $nicv_message. '<div class="icon"><a href="https://www.necrologi-italia.it/qrcode-'.$defunto['id'].'.jpeg.php" target="_blank" rel="nofollow"><img style="border-radius:25px;" src="'. $nicv_url . '/necrologi-italia-nicv/images/social/qrcode.png" width="32" height="32" alt="Condividi il lutto di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Facebook"></a></div>';
  $nicv_message = $nicv_message.'<p style="font-size: 10px;">&nbsp;</p>
  <p class="firma">Servizio necrologi a cura di <a href="https://www.necrologi-italia.it" title="Servizio necrologi locali e nazionali in tempo reale www.necrologi-italia.it">necrologi-italia.it</a></p></center></div>
  
  ';
  

  $nicv_message = $nicv_message. '<p>&nbsp;</p>';
  if (isset($defunto['bannerScheda']) AND ( $nicv_options['nicv_banner'] === "true"))  {
    $nicv_message = $nicv_message.'<center><img src="' . $defunto['bannerScheda'] . '" style="max-height:max-width:1045px;" /></center>';
  };
  
  $nicv_message = $nicv_message.'<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>';
    
    $nicv_message = $nicv_message.nicv_frasiCordoglio();
  
    $nicv_message = $nicv_message.'<br/>
            </div>
      </div>
      </div></div>
  
  
  
      <div class="remodal" data-remodal-id="modal2" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>';
  
   $nicv_message = $nicv_message.nicv_ringraziamentiFamiglia();
   $nicv_message = $nicv_message.'</div>
  
          <div class="remodal" data-remodal-id="modal3" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal3Desc">
            <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            <div>';
                  if($defunto['manifesto_src'] <> ''){
                    $nicv_message = $nicv_message.'<center><img src="'. $defunto['manifesto_src'].'"/></center>';
                  }
                  $nicv_message = $nicv_message.'</div>
              </div>
  
  
    </div>
  </div>
  
  <script type="text/javascript">
  
    document.getElementById("1").onclick = function() {condoglianze(1);};
    document.getElementById("2").onclick = function() {condoglianze(2)};
    document.getElementById("3").onclick = function() {condoglianze(3)};
    document.getElementById("4").onclick = function() {condoglianze(4)};
    document.getElementById("5").onclick = function() {condoglianze(5)};
    document.getElementById("6").onclick = function() {condoglianze(6)};
    document.getElementById("7").onclick = function() {condoglianze(7)};
    document.getElementById("8").onclick = function() {condoglianze(8)};
    document.getElementById("9").onclick = function() {condoglianze(9)};
    document.getElementById("10").onclick = function() {condoglianze(10)};
    document.getElementById("11").onclick = function() {condoglianze(11)};
    document.getElementById("12").onclick = function() {condoglianze(12)};
    document.getElementById("13").onclick = function() {condoglianze(13)};
    document.getElementById("14").onclick = function() {condoglianze(14)};
    document.getElementById("15").onclick = function() {condoglianze(15)};
    document.getElementById("16").onclick = function() {condoglianze(16)};
    document.getElementById("17").onclick = function() {condoglianze(17)};
    document.getElementById("18").onclick = function() {condoglianze(18)};
    document.getElementById("19").onclick = function() {condoglianze(19)};
    document.getElementById("20").onclick = function() {condoglianze(20)};
    document.getElementById("21").onclick = function() {condoglianze(21)};
    document.getElementById("22").onclick = function() {condoglianze(22)};
    document.getElementById("23").onclick = function() {condoglianze(23)};
    document.getElementById("24").onclick = function() {condoglianze(24)};
    document.getElementById("25").onclick = function() {condoglianze(25)};
    document.getElementById("26").onclick = function() {condoglianze(26)};
    document.getElementById("27").onclick = function() {condoglianze(27)};
    document.getElementById("28").onclick = function() {condoglianze(28)};
    document.getElementById("29").onclick = function() {condoglianze(29)};
    document.getElementById("30").onclick = function() {condoglianze(30)};
  
  
  
    function condoglianze(id)
    {
      document.getElementById("msg").value = ""
      document.getElementById("msg").value = document.getElementById(id).title;
    }
  
  </script>';
}

            // --- START NICV RESOURCES ---  (just before tag </BODY>)
            wp_enqueue_script('jquery-masonry');
            wp_register_script('remodaljs', plugins_url( 'remodal.js', __FILE__ ));
            wp_enqueue_script('remodaljs');
            wp_register_style('toastcss', plugins_url( 'jquery.toast.min.css', __FILE__ ));
            wp_enqueue_style('toastcss');
            wp_register_script('toastjs', plugins_url( 'jquery.toast.min.js', __FILE__ ));
            wp_enqueue_script('toastjs');


            // --- END NICV RESOURCES ---

            $response = wp_remote_get("nicvmail.service.php");
            $annuncio = json_decode(wp_remote_retrieve_body($response), true);

    return $nicv_message;

        
    }


    function nicv_shortcode_manifesti_funebri(){

        if ( ! defined( 'ABSPATH' ) ) {
            exit( 'Direct script access denied.' );
        }
        $nicv_options = get_option( 'nicv_settings' );
        $nicv_pagina = $nicv_options['nicv_servizioNecrologi'];
        add_action('template_redirect', 'nicv_nuovoSeo');
        global $pagename;
        global $wp_query;
        require_once 'nicv.inc.php';
        $nicv_url = plugins_url();
        add_action('template_redirect', 'nicv_nuovoSeo');
        $nicv_meta_manifesti = nicv_metaManifesti($nicv_options['nicv_citta']);
        //echo utf8_decode(html_entity_decode(generateMeta($nicv_pagina, $nicv_options['nicv_citta'], $nicv_meta_manifesti))); // --- NICV META TAGS ---
        $nicv_message = '<link rel="canonical" href="'.site_url().'/'.$pagename.'" />';

if($nicv_options['nicv_abilitaManifesti'] == "true"){
        if(!isset($wp_query->query_vars['c'])){
  $nicv_message = $nicv_message.'

      <div class="acco">
        <input style="visibility:hidden;" type="checkbox" id="faq-1">
        <p class="accordion"><label class="label cerca" for="faq-1">Cerca&nbsp;&nbsp;<img width="20" style="vertical-align:middle;" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/ricerca-defunto.png" alt="Ricerca defunto"/></label></p>
      <div class="p">
          <div id="nicv-search">
              <form method="get" action="'.$nicv_options['nicv_servizioNecrologi'].'" abineguid="">
                  <input id="nicv-nome-search" type="text" value="" placeholder="Cerca per nome o cognome" name="c"></input>
                  <input id="nicv-search-button" type="submit" value="Cerca"></input>
              </form>
          </div>

            <center><p style="color:#aaa7a7;">Oppure cerca per iniziali del cognome</p></center>
            <div class="nicv-menu-wrapper">
            <ul class="nicv-menu-letters">';
                for($i = 65; $i < 91; $i++){
                  $nicv_message = $nicv_message.'<li><a href="'.site_url().'/'.$nicv_options['nicv_servizioNecrologi'].'/necrologi-funebri-'.chr($i).'.php" title="necrologio dei defunti che hanno iniziale del cognome per '. chr($i) .'">' . chr($i) . '</a></li>';
                }
            $nicv_message = $nicv_message.'</ul>
          </div>
          <p>&nbsp;&nbsp;</p>
          <p>&nbsp;&nbsp;</p>
      </div>
    </div>';

 }

$nicv_message = $nicv_message.'<p><p>&nbsp;&nbsp;</p><center><a class="link-manifesti" href="'.site_url().'/'.$nicv_options['nicv_servizioNecrologi'].'" title="Annunci funebri di '.$nicv_options['nicv_citta'].'">Passa alla versione testuale</a></p></center>
<hr></hr>';
    if(!isset($wp_query->query_vars['c'])){
        $nicv_message = $nicv_message.'<center><br/><h2>Ultimi manifesti funebri inseriti:</h2></center>';
        $nicv_message = $nicv_message.'<p>&nbsp;</p>';
        $nicv_message = $nicv_message.'<p>&nbsp;</p>';
    }else{
        $nicv_message = $nicv_message.'
        <center><h2>Manifesti funebri trovati:</h2></center>';
        $nicv_message = $nicv_message.'<p>&nbsp;</p>';
        $nicv_message = $nicv_message.'<p>&nbsp;</p>';
    }

$nicv_message = $nicv_message.'<div class="nicv-wrapper">
  <div class="masonry-manifesto">';

  $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
  $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
  $ni->set_apikey($nicv_options['nicv_apikey']);
  if(defined($nicv_options['nicv_id_gruppo'])) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);
  $ricorrenze = $ni->get_last_ricorrenze($nicv_options['nicv_pads']);

  if(isset($wp_query->query_vars['c'])){
    $defunti = $ni->get_by_char($wp_query->query_vars['c']);
  }else{
    $defunti = $ni->get_last($nicv_options['nicv_pads']);
  }


  if(!$defunti){
    if(!isset($wp_query->query_vars['c'])){
      $nicv_message = $nicv_message.'<br/><br/><p>Non ci sono annunci funebri da visualizzare</p><br/><br/>';
    }else{
      $nicv_message = $nicv_message.'<br/><br/><p class="nicv-dati center"><b>Spiacenti, ma questa ricerca non ha prodotto risultati.</b><br/><br/>Puoi tentare di estendere la ricerca anche all\'interno dell\'archivio nazionale di <a href="https://www.necrologi-italia.it/ni_search.php?id_azienda=' . $nicv_options['nicv_id_azienda'] . '&query=' . $wp_query->query_vars['c'] . '" title="Archivio nazionale Necrologi Italia - Servizio Necrologie in tempo reale: tutte le informazioni prima e dopo le esequie funebri" >Necrologi Italia</a>.</p><br/><br/><br/><br/><br/><br/>';
    }
  }else{
    foreach($defunti as $defunto){

      if($defunto['manifesto_src'] != ''){
    
        $nicv_message = $nicv_message.'<div class="grid">
                <a href="'.site_url() .'/'. nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']) . '" title="Necrologio e funerali di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'">
                  <img style="max-width:100%" src="'.$defunto['manifesto_src'].'" alt="Manifesto funebre di '.$defunto['nome'].' '.$defunto['cognome'].'"/>
                </a>
              </div>';
            }
            else{

              $nicv_message = $nicv_message.'<div class="grid">
                      <a href="'.site_url() .'/'. nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']) . '">
                        <img style="max-width:100%" src="'.$nicv_options['NI_HOST'].'manifesto/necrologio/'.$defunto['id'].'" alt="Manifesto funebre di '.$defunto['nome'].' '.$defunto['cognome'].'"/>
                      </a>
                    </div>';

            }
    }
    foreach($ricorrenze as $ricorrenza){
      if($ricorrenza['img_manifesto_ricorrenza'] == 1){
    
        $nicv_message = $nicv_message.'<div class="grid">
                <a href="'.site_url() .'/'. nicvLinkCommemorazione($ricorrenza) . '" title="Commemorazione di '.$ricorrenza['nome'] . ' ' . $ricorrenza['cognome'] .'">
                  <img src="'.$nicv_options['NI_HOST'].'resource/manifesti/'.$ricorrenza['azienda'].'/'.$ricorrenza['manifesto_ricorrenza'].'" alt="Manifesto funebre di '.$ricorrenza['nome'].' '.$ricorrenza['cognome'].'"/>
                </a>
              </div>';
            }
      else{

        $nicv_message = $nicv_message.'<div class="grid">
                <a href="'.site_url() .'/' . nicvLinkCommemorazione($ricorrenza) . '" title="Commemorazione di '.$ricorrenza['nome'] . ' ' . $ricorrenza['cognome'] .'">
                  <img src="'.$nicv_options['NI_HOST'].'manifesto/'.$ricorrenza['tipo_ricorrenza'].'/'.$ricorrenza['id_ricorrenza'].'" alt="Manifesto funebre di '.$ricorrenza['nome'].' '.$ricorrenza['cognome'].'"/>
                </a>
              </div>';

      }
    }
}

$nicv_message = $nicv_message.'</div>
</div>
<p style="font-size: 10px;">&nbsp;</p>
<p class="firma">Servizio necrologi a cura di <a href="https://www.necrologi-italia.it" title="Servizio necrologi locali e nazionali in tempo reale www.necrologi-italia.it">necrologi-italia.it</a></p>

</div>';
}
return $nicv_message;

    }

    function invioMessaggio(){
            session_start();

            $nicv_options = get_option( 'nicv_settings' );


            if(strtoupper($_POST['nicaptcha']) == $_SESSION['nicaptcha']){

            $fields = array(
              'id_defunto',
              'mittente',
              'replyto',
              'msg',
              'privacy',

              'ringraziamento_indirizzo',
              'ringraziamento_email',
              'ringraziamento_messaggi',
              'ringraziamento_numero',
            );

            $nicv_data = array();
            foreach($fields as $f){
              if(wp_kses_post($_POST[$f])!= null) $nicv_data[$f] = wp_kses_post($_POST[$f]);
            }

            echo nicvmail($nicv_options['nicv_id_azienda'], $nicv_options['nicv_apikey'], $nicv_data);

            }else{

                echo '-ko#Codice di sicurezza errato';

            }

            function nicvmail($id_azienda, $apikey, $nicv_data){

                $mess = array_merge($nicv_data, array(
                'id_azienda' => $id_azienda,
                'apikey' => $apikey,
                'consenso' => 1,));

                $nicv_result = wp_remote_retrieve_body(wp_remote_post(NICV_HOST . "nicvmail.api", array(
                    'method'      => 'POST',
                    'body'        => $mess)));

                return $nicv_result;
            }
    }


    function nicv_shortcode_ultimi_defunti() {

      $nicv_options = get_option( 'nicv_settings' );

      $nicv_url = plugins_url();

      $nicv_message = "<center><h2>Ultimi defunti</h2>";
      if($nicv_options['nicv_abilitaManifesti'] == "true"){
        $nicv_message = $nicv_message."<p><a href='".site_url().'/'.$nicv_options['nicv_manifestiFunebri']."' title='Manifesti e annunci funebri'>Passa alla versione manifesti</a></p><br/>";
      }

      $nicv_message = $nicv_message."<div class='nicv-wrapper'>";

      $defunti = "";

      require_once 'nicv.inc.php'; // --- NICV LOADER --- (first element before any output)


  $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
	$ni->set_id_azienda($nicv_options['nicv_id_azienda']);
	$ni->set_apikey($nicv_options['nicv_apikey']);

		if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);

	if(!isset($_GET['c'])){
	
		$defunti = $ni->get_last($nicv_options['nicv_carouselpads']);
	};


	if(!$defunti){
		if(!isset($_GET['c'])){
			$nicv_message =  '<br/><br/><div class="text"><center><p>Non ci sono annunci funebri da visualizzare</p></center></div><br/><br/>';
		}else{
			$nicv_message = $nicv_message. '<br/><br/><p class="nicv-dati center"><b>Spiacenti, ma questa ricerca non ha prodotto risultati.</b><br/><br/>Puoi tentare di estendere la ricerca anche all\'interno dell\'archivio nazionale di <a href="https://www.necrologi-italia.it/ni_search.php?id_azienda=' . $nicv_options['nicv_id_azienda'] . '&query=' . $_GET['c'] . '" title="Archivio nazionale Necrologi Italia - Servizio Necrologie in tempo reale: tutte le informazioni prima e dopo le esequie funebri" >Necrologi Italia</a>.</p><br/><br/><br/><br/><br/><br/>';
		}
	}else{
		foreach($defunti as $defunto){
		
      $nicv_message = $nicv_message. '<div class="nicv-pad">';
				
				$nicv_message = $nicv_message. '<a href="' .$nicv_options['nicv_host'] . nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']) . '" title="Necrologio e funerali di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'">

 
				<img src="' . $defunto['img_src'] . '" width="205" height="280" alt="Necrologio ed informazioni sul funerale di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'"/>

				';
				$nicv_message = $nicv_message. '<div class="nicv-pad-info">';
        $nicv_message = $nicv_message. '<p class="nicv-nome">'

. $defunto['nome'] . ' ' . $defunto['cognome'] .'





				</p>';
					if($defunto['ts_data_nascita']){
						if($defunto['eta'] > 1){
							$nicv_message = $nicv_message. '<p class="nicv-dati anni">Di anni ' . $defunto['eta'] . '</p>';
						}
					}
          $nicv_message = $nicv_message. '<p class="nicv-dati decesso">' . ucfirst($defunto['txt-deceduto']) . ' il ' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . '</p>';
          $nicv_message = $nicv_message. '</div>


				</a>';
        $nicv_message = $nicv_message. '</div>';
		}
	}
  $nicv_message = $nicv_message.'<!--</div>-->
<p style="font-size: 10px;">&nbsp;</p>

<!--</div>-->
</div></center>';
return $nicv_message;

    }

    function nicv_shortcode_ultime_commemorazioni(){

      $nicv_options = get_option( 'nicv_settings' );
      global $metaCommemorazione;

      $nicv_url = plugins_url();
      $nicv_message = "<center><h2>Ultime commemorazioni</h2>";
      if($nicv_options['nicv_abilitaManifesti'] == "true"){
        $nicv_message = $nicv_message."<p><a href='".site_url().'/'.$nicv_options['nicv_manifestiFunebri']."' title='Manifesti e annunci funebri'>Passa alla versione manifesti</a></p><br/>";
      }

      $nicv_message = $nicv_message."<div class='nicv-wrapper'>";
      require_once 'nicv.inc.php'; // --- NICV LOADER --- (first element before any output)


  $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
	$ni->set_id_azienda($nicv_options['nicv_id_azienda']);
	$ni->set_apikey($nicv_options['nicv_apikey']);

		if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);


	
		$defunti = $ni->get_last_ricorrenze($nicv_options['nicv_carouselpads']);



	if(!$defunti){
	//	if(!isset($_GET['c'])){
if(!isset($wp_query->query_vars['c'])){
			$nicv_message = $nicv_message. '<br/><br/><div class="text"><center><p>Non ci sono commemorazioni da visualizzare</p></center></div><br/><br/>';
		}else{
			$nicv_message = $nicv_message. '<br/><br/><p class="nicv-dati center"><b>Spiacenti, ma questa ricerca non ha prodotto risultati.</b><br/><br/>Puoi tentare di estendere la ricerca anche all\'interno dell\'archivio nazionale di <a href="https://www.necrologi-italia.it/ni_search.php?id_azienda=' . $nicv_options['nicv_id_azienda'] . '&query=' . $wp_query->query_vars['c'] . '" title="Archivio nazionale Necrologi Italia - Servizio Necrologie in tempo reale: tutte le informazioni prima e dopo le esequie funebri" >Necrologi Italia</a>.</p><br/><br/><br/><br/><br/><br/>';
		}
	}else{
		foreach($defunti as $defunto){
		
      $nicv_message = $nicv_message. '<div class="nicv-pad pad-commemorazione">';
				
$nicv_message = $nicv_message. '
				

<a href="'. $nicv_options['nicv_host'] . nicvLinkCommemorazione($defunto).'">
 


				<div class="info-commemorazione">';

        $nicv_message = $nicv_message. '<img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-' . strtolower(trim(preg_replace('/[^A-Za-z0-9.\-]/', '-', basename($defunto['tipo_ricorrenza'])))) . '.png" style="width:auto;height:40px;filter: brightness(30%);"/><p class="nicv-commemorazione">' . ucfirst($defunto['tipo_ricorrenza']) . '</p>';

        if($defunto['mittente'] != ""){
                $nicv_message = $nicv_message. '<div class="box-mittente"><p>'.$defunto['mittente'].'</p></div>';
            }

        $nicv_message = $nicv_message. '<p class="nicv-dati" style="font-size:80%;">' . date($nicv_options['nicv_formatoData'], $defunto['ts_ricorrenza']) . '</p>';
                $nicv_message = $nicv_message. '</div>

                <img class="img-commemorazione" src="' . $defunto['img_src'] . '" alt="Necrologio ed informazioni sul funerale di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'"/>';

                $nicv_message = $nicv_message. '<p>&nbsp;&nbsp;</p><p class="nicv-nome" style="height:100px;">'

. $defunto['nome'] . ' ' . $defunto['cognome'] .'

                </p>';

                $nicv_message = $nicv_message. '</a>';
            $nicv_message = $nicv_message. '</div>';
        }
    }


$nicv_message = $nicv_message. '</div>
</center>

<div class="nicv-wrapper"><center><p style="font-size: 10px;">&nbsp;</p>
<p class="firma">Servizio necrologi a cura di <a href="https://www.necrologi-italia.it" title="Servizio necrologi locali e nazionali in tempo reale www.necrologi-italia.it">necrologi-italia.it</a></p></center></div>
';
return $nicv_message;
    }

    function nicv_shortcode_scheda_commemorazione(){

      if ( ! defined( 'ABSPATH' ) ) {
        exit( 'Direct script access denied.' );
    }

    $nicv_options = get_option( 'nicv_settings' );
    $nicv_css_options = get_option('nicv_css_settings');
    $nicv_url = plugins_url();
    global $wp_query, $metaDefunto, $pagename, $metaCommemorazione, $defunto;
    $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
    $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
    $ni->set_apikey($nicv_options['nicv_apikey']);

    if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);

      $ref = '';
      if(isset($wp_query->query_vars['idr'])){


        $defunto = $ni->get_commemorazione_by_id($wp_query->query_vars['idr'], $ref);
        

        if($defunto){
          define('NICV_seoCitta', ''); 
          $metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta); 
          $metaCommemorazione = nicv_metaCommemorazione($defunto);
        }

      }else{
        $defunto = false;
      }
      //echo html_entity_decode(generateTitle());
      add_action('template_redirect', 'nicv_nuovoSeo');

    $urlScheda = nicvLinkCommemorazione($defunto, true);
    $nicv_slug = str_replace(".php","",$urlScheda);
    $nicv_message =  '<link rel="canonical" href="'.$urlScheda .'" />';
    $fbSharerLink = 'https://www.facebook.com/sharer/sharer.php?sdk=joey&u=' . urlencode($urlScheda) . '?ref=fb&display=popup&ref=plugin&src=share_button?ref=fb';
    $twSharerLink = 'https://www.twitter.com/share?url=' . urlencode($urlScheda) . '?ref=tw';
    $waSharerLink = 'https://api.whatsapp.com/send?text=Lutto+per+la+scomparsa+di+' . $defunto['cognome'] . '+' . $defunto['nome'] .' '.urlencode($urlScheda) . '?ref=wa+E\'+possibile+inviare+gratuitamente+messaggi+di+cordoglio.';
    $tgSharerLink = 'https://telegram.me/share/url?url='  . urlencode($urlScheda) . '?ref=tg&text=Lutto per la scomparsa di ' . $defunto['cognome'] . ' ' . $defunto['nome'] . ' - Puoi inviare gratuitamente messaggi di cordoglio - ';
    require_once 'nicv.inc.php';
    // --- START NICV BASE --- // --- NICV BASE --- (just after <head> !!!)
    $nicv_message = $nicv_message.'<base href="' . $nicv_options['nicv_host'] . $nicv_options['nicv_pagesPath'] . '">';
    // --- END NICV BASE ---


    // --- START NICV CSS RESOURCES ---
    wp_register_style('nicvmailcss', plugins_url( 'nicvmail.css', __FILE__ ));
    wp_enqueue_style('nicvmailcss');
    wp_register_style('remodalcss', plugins_url( 'remodal.css', __FILE__ ));
    wp_enqueue_style('remodalcss');
    wp_register_style('remodalthemecss', plugins_url( 'remodal-default-theme.css', __FILE__ ));
    wp_enqueue_style('remodalthemecss');
    // --- END NICV CSS RESOURCES ---


      $nicv_message = $nicv_message .'



<a class="nicv-link" style="float:left;" href="'.site_url().'/'.$nicv_options['nicv_servizioNecrologi'].'" title="Torna alla pagina dei necrologi di '.$nicv_options['nicv_citta'].'"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/torna-indietro.png" title="Torna ai necrologi online"/>
        </a>




<p>&nbsp;</p>
<hr/>
<p>&nbsp;</p>
<p>&nbsp;</p>

<div class="nicv-wrapper">
<div class="nicv-pad-wrapper" itemscope itemtype="https://schema.org/Person">
<div>';
	if(!$defunto){
		$nicv_message = $nicv_message. '<br/><br/><p>Errore</p><br/><br/>';
	}else{
		$nicv_message = $nicv_message. '<div class="nicv-scheda-col">';    //FOTO E NOME DEFUNTO
    $nicv_message = $nicv_message. '<div class="info-ricorrenze"><center><img itemprop="image" class="foto-defunto" src="' . $defunto['img_src'] . '" width="205" height="273" alt="Funerali ' . NICV_seoCitta . ' - Necrologio di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" /></center></div>';
    $nicv_message = $nicv_message. '<div class="nicv-scheda-col"><center><h2><span itemprop="name">' . $defunto['displayName'] . '</span></h2></center>';
			if($defunto['eta'] > 1){
				$nicv_message = $nicv_message.  '<center><p>Di anni ' . $defunto['eta'] . '</p></center><br/>';
			}
			$nicv_message = $nicv_message.  '<center><p>' . ucfirst($defunto['txt-deceduto']) . ' il <span itemprop="deathDate">' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . '</span> ' . $ni->city_prefix($defunto['luogo_decesso']) . '</p></center><br/>';
			if($defunto['txt-coniugale'] <> ''){
				$nicv_message = $nicv_message.  '<center><p>' . $defunto['txt-coniugale'] . ' ' . $defunto['nome_c'] . ' ' . $defunto['cognome_c'] . '</p></center>';
			}
      $nicv_message = $nicv_message.  '</div></div>

<div class="masonry">';

      $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid">';    // ANNUNCIO


switch($defunto['tipo_ricorrenza'])
{
case 'santo rosario';
              $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-santo-rosario.png" alt="Santo Rosario"/>';

    $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><h2>Santo Rosario</h2></div><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p></div>';
break;

case 'anniversario';

              $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-anniversario.png" alt="Anniversario di morte"/>';

    $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>'.$defunto['edizione_anniversario'].' anniversario</h2></center></div><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p></div>';

break;


case 'ringraziamento';

              $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-ringraziamento.png" title="Ringraziamento per commemorazione"/>';
    $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>Ringraziamento</h2></center></div><br/><br/><h3 class="famiglia">'.$defunto['mittente'].'</h3><br/><p>' . $defunto['testo_ricorrenza'] . '</p><p style="text-align:right;padding:20px;"><i>'.$defunto['comune_ricorrenza'].', '. date($nicv_options['nicv_formatoData'], $defunto['ts_ricorrenza']) .'</i></p></div>';

break;

case 'commemorazione';

              $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-commemorazione.png" title="Commemorazione ricorrenza morte"/>';
    $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>Commemorazione</h2></center></div><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p></div>';

break;

case 'partecipazione';

              $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-partecipazione.png"/>';
    $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>Partecipazione</h2></center></div><br/><br/><h3 class="famiglia">'.$defunto['mittente'].'</h3><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p><br/><p style="text-align:right;padding:20px;"><i>'.$defunto['comune_ricorrenza'].', '. date($nicv_options['nicv_formatoData'], $defunto['ts_ricorrenza']) .'</i></p></div>';

break;

case 'messa di settima';

                      $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-messa-di-settima.png" title="Messa di settima per commemorazione"/>';
            $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>Messa di settima</h2></center></div><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p></div>';

 break;

case 'messa di trigesimo';                                
                                      $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/icona-messa-di-trigesimo.png" title="Messa di trigesimo"/>';
                            $nicv_message = $nicv_message.  '<div class="titolo-ricorrenza"><center><h2>Messa di trigesimo</h2></center></div><br/><br/><p>' . $defunto['testo_ricorrenza'] . '</p></div>';
break;

}

if($defunto['txt_cerimonia_ricorrenza'] <> ''){
		    // CHIESA
				$nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/chiesa-funerale.png" width="" height="" alt="Chiesa in cui si celebra la '.$defunto['tipo_ricorrenza'].' di ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" /><br/><br/>';
        $nicv_message = $nicv_message.  '<center><p>' . str_replace('{{data_ricorrenza}}', date($nicv_options['nicv_formatoData'], $defunto['ts_ricorrenza']), $defunto['txt_cerimonia_ricorrenza']) . '</p></center></div><br/><br/><br/><br/>';
  }
if($defunto['manifesto_ricorrenza_src'] <> ''){

   // MANIFESTO

      $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><center><a href="' . $urlScheda . '/#modal3"><img src=" '. $defunto['manifesto_ricorrenza_src'].'" alt="Manifesto ' . $defunto['tipo_ricorrenza'] . ' per ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '"></a></center></div><br/><br/><br/><br/>';

     
  }

  if($defunto['pubblicato'] == 1){

            $nicv_message = $nicv_message.  '<div class="nicv-scheda-col grid-item grid"><center><img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/scheda-defunto.png" title="Necrologio di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'"/><br/><br/></center>

            <p><a href="'.site_url().'/'. nicvLink($defunto['id'], $defunto['nome'], $defunto['cognome'], $defunto['luogo_decesso']) . '" title="Necrologio e funerali di '.$defunto['nome'] . ' ' . $defunto['cognome'] .'">Informazioni sul funerale di '. $defunto['nome'] . ' ' . $defunto['cognome'] .'</a></p>

      </div>';
  }
  $nicv_message = $nicv_message.'</div>
                <p>&nbsp;&nbsp;</p>
                <p>&nbsp;&nbsp;</p>
                </div>
                </div>';
                $nicv_message = $nicv_message.'<div class="nicv-scheda-col form-style">';   // FORM
                $nicv_message = $nicv_message.'<p>&nbsp;&nbsp;</p><img class="busta-messaggio" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/messaggio-condoglianze-gratuite.png" width="" height="" alt="Messaggio della famiglia per ' . $defunto['nome'] . ' ' . $defunto['cognome'] . '" /><br/><br/>
          <h3 class="domanda">Invia gratuitamente le tue condoglianze</h3><br/>
          <div id="nicvmail-command"><p>Sarà nostra cura recapitarle quanto prima alla famiglia di '. $defunto['nome'] . ' ' . $defunto['cognome'] .'</p>';
        
        $nicv_message = $nicv_message.'

  <form id="nicvmail-form">
            <br /><input type="text" id="mittente" placeholder="Nome" required/>
            <input type="text" id="replyto" placeholder="Email" required/>
            <p>&nbsp;&nbsp;</p>
            <textarea id="msg" row="8" col="50" placeholder="Scrivi il tuo messaggio di cordoglio" onchange="document.getElementById(\'msg\').value = document.getElementById(\'msg\').value.replace(/[^\p{L}\p{N}\p{P}\p{Z}^$\n]/gu, \'\');" required></textarea>
            <p><a class="frasi" href="'.$urlScheda . '/#modal">Esempi di frasi di cordoglio</a></p>
            <p>&nbsp;&nbsp;</p>
            <p>&nbsp;&nbsp;</p>
            <img src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/divisore.png"/>
            <p>&nbsp;&nbsp;</p>
            <p>&nbsp;&nbsp;</p>
            <label>Verifica il codice di sicurezza:</label><br/>
            <img class="codice" src="'.$nicv_url.'/necrologi-italia-nicv/nicaptcha.jpeg.php" ?ts="time()">
            <input type="text" class="input-form" value="" id="nicaptcha" placeholder="Riscrivi il codice di sicurezza"  />
            <input type="hidden" class="input-form" id="id_defunto" value="'.$defunto['id'].'" /><br/><br/><br/><br/>
          
            <p><a href="'.$urlScheda . '/#modal2">Desideri ricevere un eventuale ringraziamento della famiglia?</a></p><br/><br/>
            <div class="message-consent"><input name="message-consent" id="message-consent" value="si" type="checkbox"/>&nbsp;&nbsp;Desidero che il messaggio sia visibile su questa pagina<br/><span style="font-size:12px;">Il messaggio verrà pubblicato, previa verifica, entro breve tempo.</span></div><br/><br></p>
              <p class="nicv-mail-text">

      <input name="privacy" id="privacy" value="si" type="checkbox"/>  <a id="button_privacy">Ho letto l\'informativa privacy</a>, e acconsento alla memorizzazione dei miei dati nel vostro archivio secondo quanto stabilito dal regolamento europeo per la protezione dei dati personali n. 679/2016, GDPR.<br/><br></p>


                <br/>
              </p>
              <p>&nbsp;&nbsp;</p>
              <p>&nbsp;&nbsp;</p>
              <img class="logo-ni" src="'.$nicv_url.'/necrologi-italia-nicv/images/icon-nicv/necrologi-italia.png" alt="Servizio di necrologi e morti in Italia in tempo reale"/>

              <input type="submit" id="bottone" value="Invia" />
              
            </div>

          </div>
          
         <!-- </form> -->';





	}
if (isset($defunto['logo_azienda']) AND ( $nicv_options['nicv_logos'] === "true")) {	
  $nicv_message = $nicv_message.  '<p>&nbsp;&nbsp;</p><p>&nbsp;&nbsp;</p><div class="nicv-wrapper"><center><img src="' . $defunto['logo_azienda'] . '" style="max-height:100px;max-width:200px;position:relative;margin:0 auto;" /></center></div><p>&nbsp;</p>';
}

$nicv_message = $nicv_message. '<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, "script", "facebook-jssdk"));</script>';

$nicv_message = $nicv_message.  '<div class="nicv-wrapper"><p>&nbsp;&nbsp;</p><p>&nbsp;&nbsp;</p><center><p>CONDIVIDI SU:</p>';
$nicv_message = $nicv_message.  '<div class="icon"><a href="'.$fbSharerLink.'" target="_blank" rel="nofollow"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/Facebook-icon.png" width="32" height="32" alt="Condividi la ricorrenza di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Facebook"></a></div>';
$nicv_message = $nicv_message.  '<div class="icon"><a href="'.$twSharerLink.'" target="_blank" rel="nofollow"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/Twitter-icon.png" width="32" height="32" alt="Condividi la ricorrenza di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Twitter"></a></div>';
$nicv_message = $nicv_message.  '<div class="icon"><a href="'.$waSharerLink.'" data-action="share/whatsapp/share"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/Whatsapp-icon.png" width="32" height="32" alt="Condividi la ricorrenza di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su WhatsApp"></a></div>';
$nicv_message = $nicv_message.  '<div class="icon"><a href="'.$tgSharerLink.'" target="_blank" rel="nofollow"><img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/Telegram-icon.png" width="32" height="32" alt="Condividi la ricorrenza di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Telegram"></a></div>';
$nicv_message = $nicv_message.  '<div class="icon"><a href="https://www.necrologi-italia.it/qrcode-commemorazione-'.$defunto['id_ricorrenza'].'.jpeg.php" target="_blank" rel="nofollow"><img style="border-radius:25px;" src="'.$nicv_url.'/necrologi-italia-nicv/images/social/qrcode.png" width="32" height="32" alt="Condividi la ricorrenza di '.$defunto['nome'] . ' ' . $defunto['cognome'] .' su Facebook"></a></div>';
$nicv_message = $nicv_message. '<p style="font-size: 10px;">&nbsp;</p>
<p class="firma">Servizio necrologi a cura di <a href="https://www.necrologi-italia.it" title="Servizio necrologi locali e nazionali in tempo reale www.necrologi-italia.it">necrologi-italia.it</a></p></center></div>

</div><p>&nbsp;</p>';
if (isset($defunto['bannerScheda']) AND ( $nicv_options['nicv_banner'] === "true"))  {
	$nicv_message = $nicv_message. '<center><img src="' . $defunto['bannerScheda'] . '" style="max-height:max-width:1045px;" /></center>';
}

$nicv_message = $nicv_message. '<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>';

  $nicv_message = $nicv_message.nicv_frasiCordoglio();

 $nicv_message = $nicv_message. '</div>



    <div class="remodal" data-remodal-id="modal2" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal2Desc">
      <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>';
 
$nicv_message = $nicv_message.nicv_ringraziamentiFamiglia();
 $nicv_message = $nicv_message. '</div>

        <div class="remodal" data-remodal-id="modal3" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal3Desc">
          <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
          <div>';
                 if($defunto['manifesto_ricorrenza_src'] <> '')
                 $nicv_message = $nicv_message.  '<img src="'. $defunto['manifesto_ricorrenza_src'].'"/>';
                 $nicv_message = $nicv_message.  '</div>
            </div>


  </div>
</div>';

// --- START NICV RESOURCES ---  (just before tag </BODY>) 
                        wp_register_script('remodaljs', plugins_url( 'remodal.js', __FILE__ ));
                        wp_enqueue_script('remodaljs');
                        wp_register_style('toastcss', plugins_url( 'jquery.toast.min.css', __FILE__ ));
                        wp_enqueue_style('toastcss');
                        wp_register_script('toastjs', plugins_url( 'jquery.toast.min.js', __FILE__ ));
                        wp_enqueue_script('toastjs');
            // --- END NICV RESOURCES ---

            $nicv_message = $nicv_message. '<script type="text/javascript">

  document.getElementById("1").onclick = function() {condoglianze(1);};
  document.getElementById("2").onclick = function() {condoglianze(2)};
  document.getElementById("3").onclick = function() {condoglianze(3)};
  document.getElementById("4").onclick = function() {condoglianze(4)};
  document.getElementById("5").onclick = function() {condoglianze(5)};
  document.getElementById("6").onclick = function() {condoglianze(6)};
  document.getElementById("7").onclick = function() {condoglianze(7)};
  document.getElementById("8").onclick = function() {condoglianze(8)};
  document.getElementById("9").onclick = function() {condoglianze(9)};
  document.getElementById("10").onclick = function() {condoglianze(10)};
  document.getElementById("11").onclick = function() {condoglianze(11)};
  document.getElementById("12").onclick = function() {condoglianze(12)};
  document.getElementById("13").onclick = function() {condoglianze(13)};
  document.getElementById("14").onclick = function() {condoglianze(14)};
  document.getElementById("15").onclick = function() {condoglianze(15)};
  document.getElementById("16").onclick = function() {condoglianze(16)};
  document.getElementById("17").onclick = function() {condoglianze(17)};
  document.getElementById("18").onclick = function() {condoglianze(18)};
  document.getElementById("19").onclick = function() {condoglianze(19)};
  document.getElementById("20").onclick = function() {condoglianze(20)};
  document.getElementById("21").onclick = function() {condoglianze(21)};
  document.getElementById("22").onclick = function() {condoglianze(22)};
  document.getElementById("23").onclick = function() {condoglianze(23)};
  document.getElementById("24").onclick = function() {condoglianze(24)};
  document.getElementById("25").onclick = function() {condoglianze(25)};
  document.getElementById("26").onclick = function() {condoglianze(26)};
  document.getElementById("27").onclick = function() {condoglianze(27)};
  document.getElementById("28").onclick = function() {condoglianze(28)};
  document.getElementById("29").onclick = function() {condoglianze(29)};
  document.getElementById("30").onclick = function() {condoglianze(30)};



  function condoglianze(id)
  {
    document.getElementById("msg").value = "";
    document.getElementById("msg").value = document.getElementById(id).title;
  }

</script>';
return $nicv_message;
    }


    // register shortcode
    add_shortcode('nicv_servizio_necrologi', 'nicv_shortcode_servizio_necrologi');
    add_shortcode('nicv_scheda_defunto', 'nicv_shortcode_scheda_defunto');
    add_shortcode('nicv_ultimi_defunti', 'nicv_shortcode_ultimi_defunti');
    add_shortcode('nicv_ultime_commemorazioni', 'nicv_shortcode_ultime_commemorazioni');
    add_shortcode('nicv_scheda_commemorazione', 'nicv_shortcode_scheda_commemorazione');
    add_shortcode('nicv_manifesti_funebri', 'nicv_shortcode_manifesti_funebri');

    /************************ Necrologi Italia  *************************/

function custom_rewrite_tag() {

    //  call --> scheda necrologio rule 1
    add_rewrite_tag('%id%', '([^&]+)');
  
    // call _self servizio necrologi rule 1
    add_rewrite_tag('%c%', '([^&]+)');

    // call scheda commemorazione rule 1
    add_rewrite_tag('%idr%', '([^&]+)');
  
  }
  add_action('init', 'custom_rewrite_tag', 10, 0);
  
  // es. 1589 --> id della pagina servizio necrologi
  // es. 1593 --> id della pagina scheda necrologio
  
  function custom_rewrite_rule() {

    $nicv_options = get_option( 'nicv_settings' );
    $servizio_necrologi = get_page_by_path($nicv_options['nicv_servizioNecrologi']);
    $scheda_necrologio = get_page_by_path($nicv_options['nicv_schedaNecrologio']);
    $scheda_commemorazione = get_page_by_path($nicv_options['nicv_schedaCommemorazione']);
    $manifesti_funebri = get_page_by_path($nicv_options['nicv_manifestiFunebri']);
  
    //  call --> scheda necrologio
    if($scheda_necrologio != ''){
      add_rewrite_rule('^'.$nicv_options['nicv_servizioNecrologi'].'/funerali-([^/]+)-([0-9]+)/?','index.php?page_id='.$scheda_necrologio->ID.'&id=$matches[2]','top');
    // call _self servizio necrologi rule 1
      add_rewrite_rule('^'.$nicv_options['nicv_servizioNecrologi'].'/necrologi-funebri-([A-Z]+)\.php','index.php?page_id='.$servizio_necrologi->ID.'&c=$matches[1]','top');
    }
    if($scheda_commemorazione != ''){
      add_rewrite_rule('^'.$nicv_options['nicv_servizioNecrologi'].'[/[0-9A-Za-z-/]+([^/]+)-c([0-9]+)\.php?','index.php?page_id='.$scheda_commemorazione->ID.'&idr=$matches[2]','top');
    }
  
    }
    add_action('init', 'custom_rewrite_rule', 10, 0);

    function generateTitle($title){
        global $nicvMeta, $defunto, $wp_query;
        $nicv_options = get_option( 'nicv_settings' );
        $scheda_necrologio = get_page_by_path($nicv_options['nicv_schedaNecrologio']);
        $scheda_commemorazione = get_page_by_path($nicv_options['nicv_schedaCommemorazione']);
        $manifesti_funebri = get_page_by_path($nicv_options['nicv_manifestiFunebri']);
        $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
        $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
        $ni->set_apikey($nicv_options['nicv_apikey']);
        $nicv_pageTitle = "";

        if(isset($wp_query->query_vars['id']) && is_page($scheda_necrologio->ID) ){
            $defunto = $ni->get_by_id($wp_query->query_vars['id']);

            if(isset($defunto) && $defunto && isset($defunto['meta'])){
                // se da api sono specificati dei meta, li sovrascrivo
                // (usato principalmente per i meta delle ricorrenze, essendo complessi non volevo gestirli in locale)
                $metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta);
                $nicv_pageTitle = $metaDefunto['pageTitle'];

                $title['title'] = $nicv_pageTitle; 
                $title['tagline'] = ''; // optional
            }
        }
        else{

            if(is_page($manifesti_funebri->ID)){
                $nicv_meta_manifesti = nicv_metaManifesti($nicv_options['nicv_citta']);
                //$metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta);
                $nicv_pageTitle = $nicv_meta_manifesti['pageTitle'];

                $title['title'] = $nicv_pageTitle; 
                $title['tagline'] = ''; // optional

            }
            else{
                if(is_page($scheda_commemorazione->ID)){
                    $metaCommemorazione = nicv_metaCommemorazione($defunto);
                    $nicv_pageTitle = $metaCommemorazione['pageTitle'];

                    $title['title'] = $nicv_pageTitle; 
                    $title['tagline'] = ''; // optional
                }
            }
        }
        //return '<title>' . $nicv_pageTitle . '</title>';
        
        return $title; 
    }



    function generateMeta($nicv_page = NICV_servizioNecrologi, $nicv_seoCitta=0, $metaDefunto = false){
    
    $nicv_options = get_option( 'nicv_settings' );
    $nicv_pagina = $nicv_page;
    global $pagename;
		global $defunto, $nicvMeta;

		$nicv_str = "";

        add_filter('document_title_parts', 'generateTitle',10);



    if(($nicv_pagina == $nicv_options['nicv_schedaNecrologio'] && $metaDefunto != '')||($nicv_pagina == $nicv_options['nicv_schedaCommemorazione']) && ($metaDefunto != '')){
      // se sono sulla scheda defunto, sovrascrivo i meta generali con quelli specifici per il defunto definiti in locale
      $nicvMeta = array_merge($nicvMeta, $metaDefunto);
      $nicv_pageTitle = $nicvMeta['pageTitle'];

      if($pagename == $nicv_options['nicv_schedaDefunto'] || $pagename == $nicv_options['nicv_schedaCommemorazione']){
        echo "<title>".$nicv_pageTitle."</title>";
      }


    $nicv_str .= '';



		foreach($nicvMeta as $f => $v){


		  if($v == 'UNSET') continue;

		  // per iniettare nei meta arrivati tramite api le specifiche del cliente
		  $v = str_replace('{{ditta}}', $nicv_options['nicv_ditta'], $v);
      $v = str_replace('{{citta}}', $nicv_options['nicv_citta'], $v);
      $v = str_replace('{{NICV_seoCitta}}', $nicv_seoCitta, $v);


			switch($f){
				case 'pageTitle':
					break;

				case 'DC-links':
					$nicv_str .= $v . "\n";
					break;

				case 'og:image': case 'og:url': case 'og:type': case 'og:title': case 'og:description': case 'og:image:width': case 'og:image:height': case 'og:site_name':
					$nicv_str .= '<meta property="' . $f . '" content="' . $v . '">' . "\n";
					break;

				default:
					$nicv_str .= '<meta name="' . $f . '" content="' . $v . '">' . "\n";
			}
		}
		return $nicv_str;
    }


    if(isset($defunto) && $defunto && isset($defunto['meta'])){
      // se da api sono specificati dei meta, li sovrascrivo
      // (usato principalmente per i meta delle ricorrenze, essendo complessi non volevo gestirli in locale)
      $nicvMeta = array_merge($nicvMeta, $defunto['meta']);
      $nicv_pageTitle = $nicvMeta['pageTitle'];


    $nicv_str .= '';


		foreach($nicvMeta as $f => $v){


		  if($v == 'UNSET') continue;

		  // per iniettare nei meta arrivati tramite api le specifiche del cliente
		  $v = str_replace('{{ditta}}', $nicv_options['nicv_ditta'], $v);
      $v = str_replace('{{citta}}', $nicv_options['nicv_citta'], $v);
      $v = str_replace('{{NICV_seoCitta}}', $nicv_seoCitta, $v);


			switch($f){
				case 'pageTitle':
					break;

				case 'DC-links':
					$nicv_str .= $v . "\n";
					break;

				case 'og:image': case 'og:url': case 'og:type': case 'og:title': case 'og:description': case 'og:image:width': case 'og:image:height': case 'og:site_name':
					$nicv_str .= '<meta property="' . $f . '" content="' . $v . '">' . "\n";
					break;

				default:
					$nicv_str .= '<meta name="' . $f . '" content="' . $v . '">' . "\n";
			}
		}
		return $nicv_str;
    }

    if($pagename == $nicv_options['nicv_servizioNecrologi']){
    $nicv_pageTitle = $nicvMeta['pageTitle'];
    $nicv_str .= '<title>' . $nicv_pageTitle . '</title>';


		foreach($nicvMeta as $f => $v){


		  if($v == 'UNSET') continue;

		  // per iniettare nei meta arrivati tramite api le specifiche del cliente
		  $v = str_replace('{{ditta}}', $nicv_options['nicv_ditta'], $v);
      $v = str_replace('{{citta}}', $nicv_options['nicv_citta'], $v);
      $v = str_replace('{{NICV_seoCitta}}', $nicv_seoCitta, $v);


			switch($f){
				case 'pageTitle':
					break;

				case 'DC-links':
					$nicv_str .= $v . "\n";
					break;

				case 'og:image': case 'og:url': case 'og:type': case 'og:title': case 'og:description': case 'og:image:width': case 'og:image:height': case 'og:site_name':
					$nicv_str .= '<meta property="' . $f . '" content="' . $v . '">' . "\n";
					break;

				default:
					$nicv_str .= '<meta name="' . $f . '" content="' . $v . '">' . "\n";
			}
		}
		return $nicv_str;
  }

      if($pagename == $nicv_options['nicv_manifestiFunebri']){

      $nicv_pageTitle = $metaDefunto['pageTitle'];
      $nicvmetaManifesti = $metaDefunto;
      $nicv_str .= '';

      echo "<title>".$nicv_pageTitle."</title>";


        foreach($nicvmetaManifesti as $f => $v){


          if($v == 'UNSET') continue;

          // per iniettare nei meta arrivati tramite api le specifiche del cliente
          $v = str_replace('{{ditta}}', $nicv_options['nicv_ditta'], $v);
        $v = str_replace('{{citta}}', $nicv_options['nicv_citta'], $v);
        $v = str_replace('{{NICV_seoCitta}}', $nicv_seoCitta, $v);


            switch($f){
                case 'pageTitle':
                    break;

                case 'DC-links':
                    $nicv_str .= $v . "\n";
                    break;

                case 'og:image': case 'og:url': case 'og:type': case 'og:title': case 'og:description': case 'og:image:width': case 'og:image:height': case 'og:site_name':
                    $nicv_str .= '<meta property="' . $f . '" content="' . $v . '">' . "\n";
                    break;

                default:
                    $nicv_str .= '<meta name="' . $f . '" content="' . $v . '">' . "\n";
            }
        }
        return $nicv_str;
    }

	}

    function keywordsScheda($nicv_keywords, $luogo_decesso = '', $comune_cimitero = ''){
        $nicv_options = get_option( 'nicv_settings' );
		if($nicv_options['nicv_forzaCitta'] == "true"){

			return array_merge(
					array(
						'necrologi ' . $nicv_options['nicv_citta'],
					),
					$nicv_keywords,
					array($luogo_decesso, $comune_cimitero)
				);
		}elseif($luogo_decesso <> '' && $comune_cimitero <> '' && strtolower($luogo_decesso) <> strtolower($comune_cimitero)){
			return array_merge(
					array(
						'necrologi ' . $luogo_decesso,
						'necrologi ' . $comune_cimitero,
					),
					$nicv_keywords,
					array($luogo_decesso, $comune_cimitero)
				);
		}elseif($luogo_decesso <> ''){
			return array_merge(
					array('necrologi ' . $luogo_decesso),
					$nicv_keywords,
					array($luogo_decesso)
				);
		}elseif($comune_cimitero <> ''){
			return array_merge(
					array('necrologi ' . $comune_cimitero),
					$nicv_keywords,
					array($comune_cimitero)
				);
		}else{
			return array_merge(
					array('necrologi ' .$options['nicv_citta']),
					$nicv_keywords,
					array($options['nicv_citta'])
				);
		}
	}

	function seoCitta($luogo_decesso = '', $comune_cimitero = ''){
    $nicv_options = get_option( 'nicv_settings' );
		if($nicv_options['nicv_forzaCitta'] == "true"){
			return $nicv_options['nicv_citta'];
		}elseif($luogo_decesso <> '' && strtolower($luogo_decesso) <> strtolower($comune_cimitero)){
			return $luogo_decesso . ' ' . $comune_cimitero;
		}elseif($luogo_decesso <> ''){
			return $luogo_decesso;
		}elseif($comune_cimitero <> ''){
			return $comune_cimitero;
		}else{
			return $nicv_options['nicv_citta'];
		}
	}
 
    
    $nicv_options = get_option( 'nicv_settings' );
    global $nicvKeywords;

    $nicvKeywords = array(
      'onoranze funebri',
      'pompe funebri',
      'servizi funebri',
      'necrologi',
      'necrologio',
      'necrologie'
    );

    $nicvMeta =
    
    array(
        

		'pageTitle' => 'Servizio necrologi per ' . $nicv_options['nicv_citta'] . ' - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '.$nicv_options['nicv_provincia'],

		'Title' => 'Servizio Necrologi - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

		'Description' => 'Le onoranze funebri '. $nicv_options['nicv_ditta'] .' offrono servizi di pompe funebri di eccellenza grazie alla pluriennale esperienza. Le sedi sono a '. $nicv_options['nicv_sedi'],

		'Keywords' => implode(';', $nicvKeywords),

		'abstract' => 'Le pompe funebri '. $nicv_options['nicv_ditta'] .' sono in grado di offrire servizi funebri di eccellenza grazie alla pluriennale esperienza. Le sedi sono a '. $nicv_options['nicv_sedi'],
		
		'og:url' =>  substr($nicv_options['nicv_host'], 0, -1) . sanitize_url($_SERVER['REQUEST_URI']) ,
	
		'og:type' => 'website',
	
		'og:title' => 'Servizio Necrologi - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

		'og:description' => 'Le onoranze funebri '. $nicv_options['nicv_ditta'] .' offrono servizi di pompe funebri di eccellenza grazie alla pluriennale esperienza. Le sedi sono a ' . $nicv_options['nicv_sedi'],

		'og:image' => $nicv_options['NI_HOST'],

    'og:image:type' => 'image/jpeg',

		'og:image:width' => '205',

		'og:image:height' => '273',

    'og:image:alt' => 'Immagine defunto',
		
		'og:site_name' => 'Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

		'DC-links' => '<link rel="schema.DC" href="https://purl.org/dc/elements/1.1/" />' . '<link rel="schema.DCTERMS" href="https://purl.org/dc/terms/" />',

		'DC.title' => 'Servizio Necrologie - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

		'DC.creator' => 'Onoranze e pompe funebri '. $nicv_options['nicv_ditta'],

		'DC.subject' => implode(';', $nicvKeywords),

		'DC.description' => 'Le onoranze funebri '. $nicv_options['nicv_ditta'] .' offrono servizi di pompe funebri di eccellenza grazie alla pluriennale esperienza. Le sedi sono a '. $nicv_options['nicv_sedi'],

		'DC.publisher' => 'Methodo Web Agency - realizzazione e posizionamento siti web',

		'DC.type" scheme="DCTERMS.DCMIType' => 'Text',

		'DC.format' => 'text/html; charset=' . $nicv_options['nicv_charset'],

		'DC.identifier' => substr($nicv_options['nicv_host'], 0, -1) . sanitize_url($_SERVER['REQUEST_URI']),

		'DC.language" scheme="DCTERMS.RFC1766' => 'IT'
	);

	function nicv_metaDefunto($defunto, $nicv_seoCitta){
    $nicv_options = get_option( 'nicv_settings' );
		global $nicvKeywords;

		if((ISSET($defunto['txt-trattamento']))&&(ISSET($defunto['comune_cimitero']))&&(ISSET($defunto['comune_chiesa']))&&(ISSET($defunto['data_funerale']))&&(ISSET($defunto['orario_funerale'])))
		{
		return array(
			'pageTitle' => 'Necrologi ' . $nicv_seoCitta . ' - ' . $defunto['displayName'] .  ' - Onoranze funebri ' . $nicv_options['nicv_ditta'],

			'Title' => 'Necrologi ' . $nicv_seoCitta . ' - ' . $defunto['displayName'] .  ' - Onoranze funebri ' . $nicv_options['nicv_ditta'],

			'Description' => 'Necrologi ' . $nicv_seoCitta . ': il ' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . ' nella citt&agrave; di ' . $defunto['luogo_decesso'] . ' &egrave; ' . $defunto['txt-venuto'] . ' a mancare ' . $defunto['nome'] . ' ' . $defunto['cognome'] . ' e ' . lcfirst($defunto['txt-trattamento']) . ' - Servizio necrologie a cura di ' .$nicv_options['nicv_ditta'] . ' di ' . $nicv_options['nicv_citta'],

			'Keywords' => implode(',', keywordsScheda($nicvKeywords, $defunto['luogo_decesso'], $defunto['comune_cimitero'])),

			'abstract' => 'Necrologi ' . $nicv_seoCitta . ': il ' . date($nicv_options['nicv_formatoData'], $defunto['ts_data_morte']) . ' nella citt&agrave; di ' . $defunto['luogo_decesso'] . ' &egrave; ' . $defunto['txt-venuto'] . ' a mancare ' . $defunto['nome'] . ' ' . $defunto['cognome'] . ' e ' . lcfirst($defunto['txt-trattamento']) . ' - Servizio necrologie a cura di ' . $nicv_options['nicv_ditta'] . ' di ' . $nicv_options['nicv_citta'],

			'DC.subject' => implode(';', keywordsScheda($nicvKeywords, $defunto['luogo_decesso'], $defunto['comune_cimitero'])),
			
			'og:image' => $defunto['img_src'],

			'og:title' => 'Ci ha lasciati ' . $defunto['displayName'],

      'og:description' => 'I funerali saranno celebrati a '. $defunto['comune_chiesa'] .' il ' . $defunto['data_funerale'] . ' alle ore '. $defunto['orario_funerale'] . '. E` possibile inviare gratuitamente messaggi di cordoglio cliccando sull`immagine - Onoranze funebri '. $nicv_options['nicv_ditta'],
		);
	}
	}

  function nicvLink($id_defunto, $nicv_nome, $nicv_cognome, $luogo_decesso = '', $nicv_host = false){
    $nicv_options = get_option( 'nicv_settings' );
		if($nicv_host){
			$nicv_host = $nicv_options['nicv_host'] . $nicv_options['nicv_pagesPath'];
		}else{
			$nicv_host = '';
		}

		if($luogo_decesso <> '' && $nicv_options['nicv_forzaCitta'] == "false"){
			$nicv_citta = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $luogo_decesso);
		}else{
			$nicv_citta = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $nicv_options['nicv_citta']);
		}
    switch($nicv_options['nicv_rewriteRule']){
			case 2:
				$nicv_pageName = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', 'funerale-' . $nicv_nome . '-' . $nicv_cognome);
				return $nicv_host . $nicv_options['nicv_servizioNecrologi'].'/'.str_replace(' ', '-', 'necrologi/' . $id_defunto . '/' . $nicv_citta . '/' . $nicv_pageName );
				break;

			default:
				return $nicv_host . $nicv_options['nicv_servizioNecrologi'].'/'.str_replace(' ', '-', preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', 'funerali-' . $nicv_citta . '-' . $nicv_nome . '-' . $nicv_cognome . '-' . $id_defunto));
		}
	}

  function nicvLinkCommemorazione($d, $nicv_host = false){

    $nicv_options = get_option( 'nicv_settings' );

    if($nicv_host){
      $nicv_host = $nicv_options['nicv_host'] . $nicv_options['nicv_pagesPath'];
    }else{
      $nicv_host = '';
    }


    $edizione_anniversario = ($d['numero_ricorrenza'] == 0)? '' : "{$d['numero_ricorrenza']}-";


    switch($nicv_options['nicv_rewriteRule']){
      case 2:
        $nicv_pageName = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', 'funerale-' . $d['nome'] . '-' . $d['cognome']);
        return $nicv_host . $nicv_options['nicv_servizioNecrologi'].'/' . str_replace(' ', '-', 'commemorazioni/' . $d['numero_ricorenza'] . '-' . $d['tipo_ricorrenza'] . '/' . $d['id_ricorrenza'] . '/' . $nicv_pageName . '.php');
        break;

      default:
        return $nicv_host . $nicv_options['nicv_servizioNecrologi'].'/' . str_replace(' ', '-', preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]|[\.]{2,})", '', $edizione_anniversario . $d['tipo_ricorrenza'] . '-' . $d['nome'] . '-' . $d['cognome'] . '-c' . $d['id_ricorrenza'])) . '.php';
    }
  }

  function nicv_frasiCordoglio(){
    $nicv_frasi = '  <div>
    <h2 id="nicvmail-title" class="domanda">Clicca e scegli il messaggio di cordoglio da inviare</h2>
    <div class="text">
      <!-- <center><p>Clicca sul messaggio per inserirlo nel form</p></center>-->
      <br/><br/>
    </div>
    <div id="nicvmail">
      <div id="nicvmail-content">
      <p class="link-condoglianze"><div id="1" class="condoglianze" data-remodal-action="close" title="Sentite e sincere condoglianze.">Sentite e sincere condoglianze.</div></p>
      <p class="link-condoglianze"><div id="2" class="condoglianze" data-remodal-action="close" title="Sinceramente addolorati dalla triste notizia, porgiamo sentite condoglianze.">Sinceramente addolorati dalla triste notizia, porgiamo sentite condoglianze.</div></p>
      <p class="link-condoglianze"><div id="3" class="condoglianze" data-remodal-action="close" title="In questa circostanza così triste giunga a voi il nostro aiuto e sostegno.">In questa circostanza così triste giunga a voi il nostro aiuto e sostegno.</div></p>
      <p class="link-condoglianze"><div id="4" class="condoglianze" data-remodal-action="close" title="Sarai per sempre l’angelo più bello.">Sarai per sempre l’angelo più bello.</div></p>
      <p class="link-condoglianze"><div id="5" class="condoglianze" data-remodal-action="close" title="Nessuno muore nel cuore di chi resta.">Nessuno muore nel cuore di chi resta.</div></p>
      <p class="link-condoglianze"><div id="6" class="condoglianze" data-remodal-action="close" title="Coraggio. Ti siamo vicini.">Coraggio. Ti siamo vicini.</div></p>
      <p class="link-condoglianze"><div id="7" class="condoglianze" data-remodal-action="close" title="Il mio cuore ti è vicino in questo momento difficile.">Il mio cuore ti è vicino in questo momento difficile.</div></p>
      <p class="link-condoglianze"><div id="8" class="condoglianze" data-remodal-action="close" title="La sua gentilezza e bontà vivranno sempre in noi.">La sua gentilezza e bontà vivranno sempre in noi.</div></p>
      <p class="link-condoglianze"><div id="9" class="condoglianze" data-remodal-action="close" title="Colpiti per la grave perdita vogliate accettare le più sincere condoglianze.">Colpiti per la grave perdita vogliate accettare le più sincere condoglianze.</div></p>
      <p class="link-condoglianze"><div id="10" class="condoglianze" data-remodal-action="close" title="Profondamente rattristato per la disgrazia, porgo le mie più sentite condoglianze.">Profondamente rattristato per la disgrazia, porgo le mie più sentite condoglianze.</div></p>
      <p class="link-condoglianze"><div id="11" class="condoglianze" data-remodal-action="close" title="Vi siamo vicini in questo triste giorno.">Vi siamo vicini in questo triste giorno.</div></p>
      <p class="link-condoglianze"><div id="12" class="condoglianze" data-remodal-action="close" title="Ho appreso la notizia con un immenso dolore. Che il ricordo dei bei momenti passati insieme possa lenire il tuo dolore.">Ho appreso la notizia con un immenso dolore. Che il ricordo dei bei momenti passati insieme possa lenire il tuo dolore.</div></p>
      <p class="link-condoglianze"><div id="13" class="condoglianze" data-remodal-action="close" title="Mi unisco al vostro dolore per la grande perdita che vi ha colpiti. Le mie più sincere condoglianze.">Mi unisco al vostro dolore per la grande perdita che vi ha colpiti. Le mie più sincere condoglianze.</div></p>
      <p class="link-condoglianze"><div id="14" class="condoglianze" data-remodal-action="close" title="Ci uniamo alla vostra famiglia in questo doloroso momento con commozione e affetto.">Ci uniamo alla vostra famiglia in questo doloroso momento con commozione e affetto.</div></p>
      <p class="link-condoglianze"><div id="15" class="condoglianze" data-remodal-action="close" title="Condoglianze per il lutto che ha colpito la vostra famiglia.">Condoglianze per il lutto che ha colpito la vostra famiglia.</div></p>
      <p class="link-condoglianze"><div id="16" class="condoglianze" data-remodal-action="close" title="Ci uniamo al vostro dolore. Sentite condoglianze.">Ci uniamo al vostro dolore. Sentite condoglianze.</div></p>
      <p class="link-condoglianze"><div id="17" class="condoglianze" data-remodal-action="close" title="Siamo colpiti per la grave perdita e vi siamo vicini in questo momento di dolore.">Siamo colpiti per la grave perdita e vi siamo vicini in questo momento di dolore.</div></p>
      <p class="link-condoglianze"><div id="18" class="condoglianze" data-remodal-action="close" title="I colleghi si uniscono al dolore della tragedia che ha colpito la sua famiglia.">I colleghi si uniscono al dolore della tragedia che ha colpito la sua famiglia.</div></p>
      <p class="link-condoglianze"><div id="19" class="condoglianze" data-remodal-action="close" title="Prendiamo parte al dolore della vostra famiglia.">Prendiamo parte al dolore della vostra famiglia.</div></p>
      <p class="link-condoglianze"><div id="20" class="condoglianze" data-remodal-action="close" title="Tutto l’ufficio si unisce al vostro dolore. Le nostre più sentite condoglianze a tutta la famiglia.">Tutto l’ufficio si unisce al vostro dolore. Le nostre più sentite condoglianze a tutta la famiglia.</div></p>
      <p class="link-condoglianze"><div id="21" class="condoglianze" data-remodal-action="close" title="Possa il nostro pensiero essere di conforto. Tutta la nostra famiglia si stringe intorno al vostro dolore. Condoglianze.">Possa il nostro pensiero essere di conforto. Tutta la nostra famiglia si stringe intorno al vostro dolore. Condoglianze.</div></p>
      <p class="link-condoglianze"><div id="22" class="condoglianze" data-remodal-action="close" title="Esprimiamo il nostro cordoglio per il grave lutto e la grande perdita.">Esprimiamo il nostro cordoglio per il grave lutto e la grande perdita.</div></p>
      <p class="link-condoglianze"><div id="23" class="condoglianze" data-remodal-action="close" title="Condividiamo il vostro momento di dolore. Possa il Signore accoglierlo/accoglierla nella Sua Pace.">Condividiamo il vostro momento di dolore. Possa il Signore accoglierlo/accoglierla nella Sua Pace.</div></p>
      <p class="link-condoglianze"><div id="24" class="condoglianze" data-remodal-action="close" title="Che il Signore conceda alla sua anima la pace eterna.">Che il Signore conceda alla sua anima la pace eterna.</div></p>
      <p class="link-condoglianze"><div id="25" class="condoglianze" data-remodal-action="close" title="La Fede in Dio è la sola forza e consolazione in questo momento.">La Fede in Dio è la sola forza e consolazione in questo momento.</div></p>
      <p class="link-condoglianze"><div id="26" class="condoglianze" data-remodal-action="close" title="Prego che Dio vi dia la forza per superare questo triste momento.">Prego che Dio vi dia la forza per superare questo triste momento.</div></p>
      <p class="link-condoglianze"><div id="27" class="condoglianze" data-remodal-action="close" title="Non ci sono addii per noi. Ovunque tu sia, sarai sempre nel mio cuore.">Non ci sono addii per noi. Ovunque tu sia, sarai sempre nel mio cuore.</div></p>
      <p class="link-condoglianze"><div id="28" class="condoglianze" data-remodal-action="close" title="Non so dove vanno le persone quando scompaiono, ma so dove restano.">Non so dove vanno le persone quando scompaiono, ma so dove restano.</div></p>
      <p class="link-condoglianze"><div id="29" class="condoglianze" data-remodal-action="close" title="In questo giorno di dolore, ci stringiamo a voi con un grande abbraccio.">In questo giorno di dolore, ci stringiamo a voi con un grande abbraccio.</div></p>
      <p class="link-condoglianze"><div id="30" class="condoglianze" data-remodal-action="close" title="Appresa la triste notizia, ci stringiamo a voi con dolore in questo momento così difficile. Sentite condoglianze.">Appresa la triste notizia, ci stringiamo a voi con dolore in questo momento così difficile. Sentite condoglianze.</div></p>

      </div>

          <br/>
      </div>
</div>';
return $nicv_frasi;
  }

  function nicv_ringraziamentiFamiglia(){
    $nicv_url = plugins_url();
    $nicv_ringraziamenti = '<div>
    <h2 id="nicvmail-title" class="domanda">Per ricevere il ringraziamento</h2>
    <div class="text">
      <center><p>Scegli tra posta tradizionale, email o cellulare.</p><small style="font-size:75%;color:#777777;">(Compila almeno uno dei campi)</small></center><br/><br/>
    </div>
    <div id="nicvmail">
      <div id="nicvmail-content">
      <div class="colonna">


<div class="row-ringr">


<img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/icona-casa.png" width="32" height="32">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="indirizzo-fisico" class="ringraziamento" placeholder="Indirizzo abitazione. Es: Via Verdi 1, Roma"/>


</div>
<div class="row-ringr">

<img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/icona-email.png" width="32" height="32">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="email_ringraziamento" class="ringraziamento" placeholder="Email"/>


</div>
<div class="row-ringr">


<img src="'.$nicv_url.'/necrologi-italia-nicv/images/social/icona-sms.png" width="32" height="32">&nbsp;&nbsp;&nbsp;&nbsp;<select name="messaggi" id="messaggi">
          <option value="scegli">Scegli modalità</option>
          <option value="whatsapp">Whatsapp</option>
          <option value="telegram">Telegram</option>
          <option value="sms">SMS</option>
        </select><br/>
</div>
<div class="row-ringr">
<img class="img-messaggi" src="'.$nicv_url.'/necrologi-italia-nicv/images/social/icona-telefono.png" style="visibility:hidden;" width="32" height="32"/><input type="text" id="numero-telefono" class="ringraziamento" placeholder="Numero di cellulare"/><br /><br/>
</div>
      
      </div>
      <input type="button" data-remodal-action="close" id="btn-ringraziamenti" value="Ok" onclick="controlla();"/>
      </div>

          <br/>
      </div>
</div>

</form>

<script type="text/javascript">
  function controlla(){
    var valore = document.getElementById("messaggi").selectedIndex;
    var numero = document.getElementById("numero-telefono").value;
    if((valore != 0)&&(numero == "")){
      $.toast({
        heading: "Errore",
        text: "Inserisci il tuo numero di cellulare!",
        icon: "error",
        loader: false,        // Change it to false to disable loader
        loaderBg:"#9EC600",  // To change the background
        position: "mid-center",
        stack: false,
      });
      document.getElementById("numero-telefono").style.border = "1px solid red";
      return;
    }
    else{
      document.getElementById("btn-ringraziamenti").setAttribute("data-remodal-action", "close");
    }
  }
</script>';
return $nicv_ringraziamenti;      
  }

function nicv_metaCommemorazione($defunto){

  return array(
    'pageTitle' => 'Commemorazione di ' . $defunto['displayName'] .': '. ucwords($defunto['tipo_ricorrenza']) . ' '  . ucwords($defunto['mittente']) .' '.date('d-m-Y', $defunto['ts_ricorrenza']) .' '. $defunto['comune_ricorrenza'],

    'Title' => 'Commemorazione di ' . $defunto['displayName'] .': '. ucwords($defunto['tipo_ricorrenza']) . ' '  . ucwords($defunto['mittente']) .' '.$defunto['ts_ricorrenza'], true .' '. $defunto['comune_ricorrenza'],

    'Description' => 'Oggi si celebra la memoria di '.$defunto['displayName'].', '.$defunto['tipo_ricorrenza'].' del '. date('d-m-Y', $defunto['ts_ricorrenza']),

    'og:image' => $defunto['img_src'],

    'og:image:alt' => 'Immagine defunto di '. $defunto['nome'] . ' ' . $defunto['cognome'],

    'og:title' => 'Commemorazione di ' . $defunto['displayName'],




  );
}

function nicv_metaManifesti($nicv_seoCitta){
    $nicv_options = get_option( 'nicv_settings' );
        global $nicvKeywords;
    return array(

        'pageTitle' => 'Manifesti funebri ' . $nicv_options['nicv_sedi'] . ' - Affissioni oggi '. $nicv_options['nicv_citta'] .' '.$nicv_options['nicv_provincia'],

        'Title' => 'Manifesti funebri - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

        'Description' => 'Annunci funebri '.$nicv_options['nicv_sedi'].' e manifesti per comune delle onoranze funebri '. $nicv_options['nicv_ditta'] .' con possibilità di inviare condoglianze gratuite alla famiglia del defunto',

        'Keywords' => implode(',', $nicvKeywords),

        'abstract' => 'Le pompe funebri '. $nicv_options['nicv_ditta'] .' offrono un servizio di manifesti e annunci funebri a '.$nicv_options['nicv_citta'],
        
        'og:url' =>  substr($nicv_options['nicv_host'], 0, -1) . $_SERVER['REQUEST_URI'] ,
    
        'og:type' => 'website',
    
        'og:title' => 'Manifesti funebri '.$nicv_options['nicv_sedi'].' - Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

        'og:description' => 'Annunci funebri '.$nicv_options['nicv_sedi'].' e manifesti per comune delle onoranze funebri '. $nicv_options['nicv_ditta'] .' con possibilità di inviare condoglianze gratuite alla famiglia del defunto',

        'og:image' => '_NI_HOST',

        'og:image:type' => 'image/jpeg',

        'og:image:width' => '205',

        'og:image:height' => '273',
        
        'og:image:alt' => 'Immagine defunto',

        'og:site_name' => 'Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

        'DC-links' => '<link rel="schema.DC" href="https://purl.org/dc/elements/1.1/" />' . "\n" . '<link rel="schema.DCTERMS" href="https://purl.org/dc/terms/" />',

        'DC.title' => 'Manifesti funebri '.$nicv_options['nicv_sedi'].'- Onoranze e pompe funebri '. $nicv_options['nicv_ditta'] .' '. $nicv_options['nicv_citta'] .' '. $nicv_options['nicv_provincia'],

        'DC.creator' => 'Onoranze e pompe funebri '. $nicv_options['nicv_ditta'],

        'DC.subject' => implode(';', $nicvKeywords),

        'DC.description' => 'Annunci funebri '.$nicv_options['nicv_sedi'].' e manifesti per comune delle onoranze funebri '. $nicv_options['nicv_ditta'] .' con possibilità di inviare condoglianze gratuite alla famiglia del defunto',

        'DC.publisher' => 'Methodo Web Agency - realizzazione e posizionamento siti web',

        'DC.type" scheme="DCTERMS.DCMIType' => 'Text',

        'DC.format' => 'text/html; charset=' . $nicv_options['nicv_charset'],

        'DC.identifier' => substr($nicv_options['nicv_host'], 0, -1) . $_SERVER['REQUEST_URI'],

        'DC.language" scheme="DCTERMS.RFC1766' => 'IT'
    );
}

function nicv_nuovoSeo(){
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  $nicv_options = get_option( 'nicv_settings' );
  $servizio_necrologi = get_page_by_path($nicv_options['nicv_servizioNecrologi']);
  $scheda_necrologio = get_page_by_path($nicv_options['nicv_schedaNecrologio']);
  $scheda_commemorazione = get_page_by_path($nicv_options['nicv_schedaCommemorazione']);
  $manifesti_funebri = get_page_by_path($nicv_options['nicv_manifestiFunebri']);
  if(($scheda_necrologio != '')&&(is_page($servizio_necrologi->ID))||($scheda_necrologio != '')&&(is_page($scheda_necrologio->ID))||($scheda_commemorazione != '')&&(is_page($scheda_commemorazione->ID))||($manifesti_funebri != '')&&(is_page($manifesti_funebri->ID))){
    remove_action('wp_head','rel_canonical'); 
    remove_action('wp_head', '_wp_render_title_tag', 1);
    remove_action('wp_head', 'og:description');

  /*if(($servizio_necrologi != '')&&(is_page($servizio_necrologi->ID))){
    add_filter('wp_head','nicv_meta_query_servizio');
  }*/

  if(($manifesti_funebri != '')&&(is_page($manifesti_funebri->ID))){
    add_filter('wp_head','nicv_meta_query_manifesti');
  }

  if(($scheda_necrologio != '')&&(is_page($scheda_necrologio->ID))){
    add_filter('wp_head','nicv_meta_query_defunto');
  }
  if(($scheda_commemorazione != '')&&(is_page($scheda_commemorazione->ID))){
    add_filter('wp_head','nicv_meta_query_commemorazione');
  }
    if(is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')){
      $front_end = YoastSEO()->classes->get( Yoast\WP\SEO\Integrations\Front_End_Integration::class );
      remove_action( 'wpseo_head', [ $front_end, 'present_head' ], -9999 );
      add_filter('wpseo_canonical', '__return_false');
    }
  }
}

/*function nicv_meta_query_servizio(){
  $nicv_options = get_option( 'nicv_settings' );
  $nicv_pagina = $nicv_options['nicv_servizioNecrologi'];
  echo utf8_decode(html_entity_decode(generateMeta($nicv_pagina, $nicv_options['nicv_citta'], ''))); // --- NICV META TAGS ---
}*/

function nicv_meta_query_manifesti(){
  $nicv_options = get_option( 'nicv_settings' );
  $nicv_pagina = $nicv_options['nicv_manifestiFunebri'];
  $nicv_meta_manifesti = nicv_metaManifesti($nicv_options['nicv_citta']);
  echo utf8_decode(html_entity_decode(generateMeta($nicv_pagina, $nicv_options['nicv_citta'], $nicv_meta_manifesti))); // --- NICV META TAGS ---
}


function nicv_meta_query_defunto(){
  global $metaDefunto, $pagename, $nicv_pagina, $defunto;
  $nicv_options = get_option( 'nicv_settings' );
  $nicv_pagina = $nicv_options['nicv_schedaNecrologio'];
  $nicv_url = plugins_url();
  global $wp_query;
  require_once 'nicv.inc.php';
  if(isset($wp_query->query_vars['id'])){

  $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);

  $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
  $ni->set_apikey($nicv_options['nicv_apikey']);
  if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);

  $defunto = $ni->get_by_id($wp_query->query_vars['id']);
  
  if($defunto){
    define('NICV_seoCitta', trim(seoCitta($defunto['luogo_decesso'], $defunto['comune_cimitero'])));
    $metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta);
    $wp_query->query_vars['title'] = NICV_seoCitta;
  }
  
}else{
  $defunto = false;

}
echo html_entity_decode(generateMeta($nicv_pagina, 'NICV_seoCitta', $metaDefunto));
return $defunto;
}


function nicv_meta_query_commemorazione(){
      global $wp_query, $metaDefunto, $pagename, $metaCommemorazione, $defunto;
      $nicv_options = get_option( 'nicv_settings' );
      $ni = new NICV_niplug(_NICV_VERSION, $nicv_options['NI_HOST']);
      $ni->set_id_azienda($nicv_options['nicv_id_azienda']);
      $ni->set_apikey($nicv_options['nicv_apikey']);
      if(defined('id_gruppo')) $ni->set_id_gruppo($nicv_options['nicv_id_gruppo']);

      $ref = '';
      if(isset($wp_query->query_vars['idr'])){


        $defunto = $ni->get_commemorazione_by_id($wp_query->query_vars['idr'], $ref);
        

        if($defunto){
          define('NICV_seoCitta', ''); 
          $metaDefunto = nicv_metaDefunto($defunto, NICV_seoCitta); 
          $metaCommemorazione = nicv_metaCommemorazione($defunto);
        }

      }else{
        $defunto = false;
      }
      echo html_entity_decode(generateMeta($nicv_options['nicv_schedaCommemorazione'], 'NICV_seoCitta', $metaCommemorazione));
      return $defunto;
}
?>