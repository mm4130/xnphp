<?php

// Created by ...
// xn script v2

$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['startTime'] = $GLOBALS['-XN-']['startTime'][0] + $GLOBALS['-XN-']['startTime'][1];
$GLOBALS['-XN-']['dirName'] = explode(DIRECTORY_SEPARATOR,__FILE__);
unset($GLOBALS['-XN-']['dirName'][count($GLOBALS['-XN-']['dirName']) - 1]);
$GLOBALS['-XN-']['dirName'] = implode(DIRECTORY_SEPARATOR,$GLOBALS['-XN-']['dirName']);
$GLOBALS['-XN-']['dirNameDir'] = $GLOBALS['-XN-']['dirName'] . DIRECTORY_SEPARATOR;
$GLOBALS['-XN-']['lastUpdate'] = "1527777713{[LASTUPDATE]}";
$GLOBALS['-XN-']['lastUse'] = "1527959745{[LASTUSE]}";
$GLOBALS['-XN-']['DATA'] = "ImhlbGxvIg=={[DATA]}";
$DATA = json_decode(base64_decode(substr($GLOBALS['-XN-']['DATA'],0,-8)),@$XNDATA === 1);

class ThumbCode {
private $code=false;
public function __construct($func){
$this->code=$func;
}public function __destruct(){
if($this->code)($this->code)();
}public function close(){
$this->code=false;
}public function clone(){
return new ThumbCode($this->code);
}
}function thumbCode($func){
return new ThumbCode($func);
}

function set_last_update_nter(){
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
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
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
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
function set_data_nter(){
  $data = base64_encode(json_encode($GLOBALS['DATA']));
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f,"{[DA"."TA]}");
  while($p>0 && $f[$p--] != '"');
  if($p<=0)return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  $f = str_replace("$h{[DA"."TA]}","$data{[D"."ATA]}",$f);
  return file_put_contents($file,$f);
}
function require_url_nter($url){
  $random = rand(0,99999999).rand(0,99999999);
  $z = new thumbCode(function()use($random){
    unlink("xn$random.log");
  });
  copy($url,"xn$random.log");
  @require "xn$random.log";
}
function xnupdate(){
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php",$GLOBALS['-XN-']['dirNameDir'] . "xn.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xnroot.php",$GLOBALS['-XN-']['dirNameDir'] . "xnroot.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xnfiles.php",$GLOBALS['-XN-']['dirNameDir'] . "xnfiles.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xntelegram.php",$GLOBALS['-XN-']['dirNameDir'] . "xntelegram.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xntime.php",$GLOBALS['-XN-']['dirNameDir'] . "xntime.php");
  copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xncoding.php",$GLOBALS['-XN-']['dirNameDir'] . "xncoding.php");
  set_last_update_nter();
}
if(@$XNUPDATE===1 && substr($GLOBALS['-XN-']['lastUpdate'],0,-14)+10000 <= time())xnupdate();

// include librarys
if(file_exists($GLOBALS['-XN-']['dirNameDir'] . "xnroot.php") && @$XNUPDATE !== 2){
  include $GLOBALS['-XN-']['dirNameDir'] . "xnroot.php";
  include $GLOBALS['-XN-']['dirNameDir'] . "xnfiles.php";
  include $GLOBALS['-XN-']['dirNameDir'] . "xntelegram.php";
  include $GLOBALS['-XN-']['dirNameDir'] . "xntime.php";
  include $GLOBALS['-XN-']['dirNameDir'] . "xncoding.php";
}else{
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnroot.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xnfiles.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntelegram.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xntime.php");
  require_url_nter("https://raw.githubusercontent.com/xnlib/xnphp/master/xncoding.php");
}

$GLOBALS['-XN-']['runEnd'] = thumbCode(function(){
  global $DATA;
  set_data_nter();
  set_last_use_nter();
});

$GLOBALS['-XN-']['endTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['endTime'] = $GLOBALS['-XN-']['endTime'][0] + $GLOBALS['-XN-']['endTime'][1];

function xnscript() {
  $lastuse = substr($GLOBALS['-XN-']['lastUse'],0,-11);
  $lastupdate = substr($GLOBALS['-XN-']['lastUpdate'],0,-14);
  return ["version"=> "1.5",
          "libs"=>["types", "index", "telegram", "files", "time", "data", "wikipedia"],
          "start_time"=>$GLOBALS['-XN-']['startTime'],
          "end_time"=>$GLOBALS['-XN-']['endTime'],
          "loaded_time"=>$GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime'],
          "dir_name"=>$GLOBALS['-XN-']['dirName'],
          "last_update"=>$lastupdate,
          "last_use"=>$lastuse
         ];
}


?>
