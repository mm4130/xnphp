<?php

// Created by ...
// xn script v2

$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['startTime'] = $GLOBALS['-XN-']['startTime'][0] + $GLOBALS['-XN-']['startTime'][1];
$GLOBALS['-XN-']['dirName'] = explode(DIRECTORY_SEPARATOR,__FILE__);
unset($GLOBALS['-XN-']['dirName'][count($GLOBALS['-XN-']['dirName']) - 1]);
$GLOBALS['-XN-']['dirName'] = implode(DIRECTORY_SEPARATOR,$GLOBALS['-XN-']['dirName']);
$GLOBALS['-XN-']['lastUpdate'] = "0{[LASTUPDATE]}"; 
$GLOBALS['-XN-']['lastUse'] = "0{[LASTUSE]}";

function set_last_update_nter(){
  $file = $GLOBALS['-XN-']['dirName'] . DIRECTORY_SEPARATOR . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f,"{[LASTUPDATE]}");
  while($p>0 && $f[$p--] != '"');
  if($p<=0)return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  if(!is_numeric($h))return false;
  $f = str_replace("$h{[LASTUPDATE]}",time()."{[LASTUPDATE]}",$f);
  return file_put_contents($file,$f);
}
function set_last_use_nter(){
  $file = $GLOBALS['-XN-']['dirName'] . DIRECTORY_SEPARATOR . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f,"{[LASTUSE]}");
  while($p>0 && $f[$p--] != '"');
  if($p<=0)return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  if(!is_numeric($h))return false;
  $f = str_replace("$h{[LASTUSE]}",time()."{[LASTUSE]}",$f);
  return file_put_contents($file,$f);
}
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
  set_last_update_nter();
}
if(@$XNUPDATE===1 && substr($GLOBALS['-XN-']['lastUpdate'],0,-14)+1000 <= time())xnupdate();

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

$GLOBALS['-XN-']['lastUseRun'] = thumbCode(function(){
  set_last_use_nter();
});

$GLOBALS['-XN-']['endTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['endTime'] = $GLOBALS['-XN-']['endTime'][0] + $GLOBALS['-XN-']['endTime'][1];

function xnscript() {
  $lastuse = substr($GLOBALS['-XN-']['lastUse'],0,-11);
  $lastupdate = substr($GLOBALS['-XN-']['lastUpdate'],0,-14);
  $dir = $GLOBALS['-XN-']['dirName'];
  return ["version"=> "1.3",
          "libs"=>["types", "index", "telegram", "files", "time"],
          "start_time"=>$GLOBALS['-XN-']['startTime'],
          "end_time"=>$GLOBALS['-XN-']['endTime'],
          "loaded_time"=>$GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime'],
          "dir_name"=>$dir,
          "last_update"=>$lastupdate,
          "last_use"=>$lastuse
         ];
}


?>
