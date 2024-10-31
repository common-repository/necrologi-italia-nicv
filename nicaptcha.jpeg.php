<?php
	session_start();

	/*$stringa = md5(microtime());
	$risultato = strtoupper(substr($stringa, 0, 5));*/
	$risultato = getRandCaptcha();

	$immagine = imagecreate(120, 25);
	$sfondo = imagecolorallocate($immagine, 255, 255, 255);
	$testo = imagecolorallocate($immagine, 000, 000, 000);
	imagefill($immagine, 0, 0, $sfondo);
	imagestring($immagine, 5, 5, 5, $risultato, $testo);
	$_SESSION['nicaptcha'] = $risultato;
	header("Content-type: image/jpeg");
	imagejpeg($immagine);


  function getRandCaptcha($length = 7, $usePool = 'safe'){

    $pools = array(
      'full' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', // completo
      'safe' => 'ABCDEFGHJKMNPRTUVWXY3456789', // senza ambiguità
    );

    if(!isset($pools[$usePool])) $usePool = 'safe';

    $pool = $pools[$usePool];

    $max = strlen($pool)-1;

    $captcha = '';
    for($i=0; $i<$length; $i++){
      $captcha .= $pool[rand(0, $max)];
    }

    return $captcha;
  }
