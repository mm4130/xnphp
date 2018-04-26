<?php

// Created by whiteweb // xn.white-web.ir // @white_web
// xn script v1

$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['startTime'] = $GLOBALS['-XN-']['startTime'][0] + $GLOBALS['-XN-']['startTime'][1];
function require_url_nter($url){
  copy($url,"xn.log");
  require "xn.log";
  unlink("xn.log");
}

// include librarys
if(file_exists("xntypes.php")){
  ob_start();
  include "xntypes.php";
  
  include "xnfiles.php";
  include "xntelegram.php";
  include "xntime.php";
  ob_end_clean();
}else{
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntypes.php");
  
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnfiles.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntelegram.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntime.php");
}

$GLOBALS['-XN-']['endTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['endTime'] = $GLOBALS['-XN-']['endTime'][0] + $GLOBALS['-XN-']['endTime'][1];

function xnscript() {
  return ["version"=> "1",
          "libs"=>["types", "index", "telegram", "files", "time"],
          "start_time"=>$GLOBALS['-XN-']['startTime'],
          "end_time"=>$GLOBALS['-XN-']['endTime'],
          "loaded_time"=>$GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime']
         ];
}


?>
