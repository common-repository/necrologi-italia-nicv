<?php

  require_once 'nicv.conf.php';

  $status = array(
    'errors' => false
  );


  if(!isset($_GET['apikey']) || $_GET['apikey'] !=NICV_apikey){
    $status['errors'] = true;
    $status['erroCode'] = 1;
    $status['errMsg'] = 'apikey mismatch';
  }

  $content = json_decode(file_get_contents('php://input'), true);

  if(!$content){
    $status['errors'] = true;
    $status['erroCode'] = 2;
    $status['errMsg'] = 'cant decode content';
    goto end;
  };



  if(isset($content['updateFrom'])){

    $status['updatingFrom'] = $content['updateFrom'];

    $content = json_decode(file_get_contents($content['updateFrom'] . '?apikey=' . NICV_apikey . '&id_azienda=' . NICV_id_azienda), true);

    if(!$content){
      $status['errors'] = true;
      $status['erroCode'] = 8;
      $status['errMsg'] = 'cant decode updateFrom content';
      goto end;
    };

  }



  $ignoreRollback = false;
  if(isset($content['ignoreRollback']) && $content['ignoreRollback']) $ignoreRollback = true;


  if(!is_dir('temp') && !mkdir('temp')){
    $status['errors'] = true;
    $status['erroCode'] = 7;
    $status['errMsg'] = 'temp dir !exists !mkdir';
    goto end;
  }

  $uploadedFiles = array();
  $backupsError = array();
  foreach($content['files'] as $filename => $d){

    // saving files in temp
    $wroteBytes = 0;
    $h = fopen('temp/' . $filename, 'w');
    $wroteBytes = fwrite($h, base64_decode($d['content']));
    fclose($h);

    if($wroteBytes == 0){
      $status['errors'] = true;
      $status['erroCode'] = 3;
      $status['errMsg'] = 'cant upload some files';
    }

    $uploadedFiles[$filename] = $wroteBytes;


    // backups copies
    if(file_exists($filename)){
      if(!copy($filename, 'temp/backup_' . $filename)){
        $status['errors'] = true;
        $backupsError[] = $filename;
      }
    }

  } // each content


  // MOVING UPLOADED IN POSITION
  $movedErrors = array();
  if(!$status['errors']){

    foreach($content['files'] as $filename => $d){

      if(!file_exists('temp/' . $filename) || !rename('temp/' . $filename, $filename)){
        $status['errors'] = true;
        $status['erroCode'] = 4;
        $status['errMsg'] = 'cant move some files';
        $movedErrors[] = $filename;
      }

    }

  }


  // CHECKING HASHES
  $hashesOk = true;
  foreach($content['files'] as $filename => $d){

    $fileContent = file_get_contents($filename);
    if(md5($fileContent) != $d['md5']) $hashesOk = false;

  }


  if($hashesOk){

    // files seems ok, check if nicv is running

    $test = file_get_contents(NICV_host . 'necrologi-italia-nicv/chkconf.php');
    $statusCode = explode(' ', $http_response_header[0]); // TODO improve to find the right index
    $statusCode = $statusCode[1];

    if($statusCode != '200'){
      $status['errors'] = true;
      $status['erroCode'] = 6;
      $status['errMsg'] = 'chkconf failed';
      $status['statusCode'] = $statusCode;

      rollback();
    } // statusCode != 200

  }else{

    // !hashesOk

    $status['errors'] = true;
    $status['erroCode'] = 5;
    $status['errMsg'] = 'hash errors';

    rollback();

  } // else hashesOk


  deleteTempFiles();


  $status['uploadedFiles'] = $uploadedFiles;
  if(count($backupsError) <> 0) $status['backupsError'] = $backupsError;
  if(count($movedErrors) <> 0) $status['movedErrors'] = $movedErrors;


  end:
  echo json_encode($status);


  function rollback(){
    global $status, $content, $ignoreRollback;

    if($ignoreRollback){
      deleteTempFiles();
      $status['rolledBack'] = false;
      $status['ignoreRollback'] = true;
      return false;
    }

    $status['rolledBack'] = true;
    foreach($content['files'] as $filename => $d){
      if(!file_exists('temp/backup_' . $filename) || !rename('temp/backup_' . $filename, $filename)) $status['rolledBack'] = false;
    }

    return true;

  }

  function deleteTempFiles(){
    global $content;

    foreach($content['files'] as $filename => $d){
      if(file_exists('temp/backup_' . $filename)) unlink('temp/backup_' . $filename);
    }

    return true;
  }