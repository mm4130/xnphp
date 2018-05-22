<?php

// Created by ...
// xn script v1

$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['startTime'] = $GLOBALS['-XN-']['startTime'][0] + $GLOBALS['-XN-']['startTime'][1];
function require_url_nter($url){
  $random = rand(0,99999999).rand(0,99999999);
  copy($url,"xn$random.log");
  @require "xn$random.log";
  unlink("xn$random.log");
}
function xnupdate(){
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php","xn.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xnroot.php","xnroot.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xnfiles.php","xnfiles.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xntelegram.php","xntelegram.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xntime.php","xntime.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.php","xndata.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.txt","xndata.txt");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xnwikipedia.php","xnwikipedia.php");
}
if(time()%1000>500&&@$XNUPDATE===1)xnupdate();

// include librarys
if(file_exists("xnroot.php")){
  ob_start();
  include "xnroot.php";
  include "xnfiles.php";
  include "xntelegram.php";
  include "xntime.php";
  ob_end_clean();
}else{
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnroot.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnfiles.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntelegram.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntime.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnwikipedia.php");
}

$GLOBALS['-XN-']['endTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['endTime'] = $GLOBALS['-XN-']['endTime'][0] + $GLOBALS['-XN-']['endTime'][1];

function xnscript() {
  return ["version"=> "1.2",
          "libs"=>["types", "index", "telegram", "files", "time"],
          "start_time"=>$GLOBALS['-XN-']['startTime'],
          "end_time"=>$GLOBALS['-XN-']['endTime'],
          "loaded_time"=>$GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime']
         ];
}


?>
