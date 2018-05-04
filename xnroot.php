<?php

// Created by ...
// xn Root File v1

class XNError extends Error {
protected $message;
static function show($sh=null){
if($sh===null)$GLOBALS['-XN-']['errorShow']=!$GLOBALS['-XN-']['errorShow'];
else $GLOBALS['-XN-']['errorShow']=$sh;
}static function handlr($func){
$GLOBALS['-XN-']['errorHandlr']=$func;
}public function __construct($in,$text,$level=0){
$type=["Warning","Notic","User Error","User Warning","User Notic","Recoverable Error","Syntax Error","Unexpected","Undefined","Anonimouse","System Error","Secury Error","Fatal Error","Arithmetic Error","Parse Error","Type Error"][$level];
$debug=debug_backtrace();
$th=end($debug);
$date=date("ca");
$console="[$date]XN $type > $in : $text in {$th['file']} on line {$th['line']}\n";
$message="<br>\n[$date]<b>XN $type</b> &gt; <i>$in</i> : ".str_replace("\n","<br>",$text)." in <b>{$th['file']}</b> on line <b>{$th['line']}</b>\n<br>";
$this->HTMLMessage=$message;
$this->consoleMessage=$console;
$this->message="XN $type > $in : $text";
if(isset($GLOBALS['-XN-']['errorHandlr'])){
try{
$GLOBALS['-XN-']['errorHandlr']($this);
}catch(Error $e){
}catch(Expection $e){
}catch(XNError $e){
}}
if($GLOBALS['-XN-']['errorShow'])echo $message;
if($GLOBALS['-XN-']['errorShow']&&is_string($GLOBALS['-XN-']['errorShow']))fadd($GLOBALS['-XN-']['errorShow'],$console);
}public function __toString(){
return $this->message;
}
}function subsplit($str,$num=1,$rms=false){
$arr=[];
if($rms){
$len=strlen($str);
if($len%$num){
$arr[]=substr($str,0,$len%$num);
$str=substr($str,$len%$num);
}}while($str){
$arr[]=substr($str,0,$num);
$str=substr($str,$num);
}return $arr;
}function mb_subsplit($str,$num=1,$rms=false){
$arr=[];
if($rms){
$len=mb_strlen($str);
if($len%$num){
$arr[]=mb_substr($str,0,$len%$num);
$str=mb_substr($str,$len%$num);
}}while($str){
$arr[]=mb_substr($str,0,$num);
$str=mb_substr($str,$num);
}return $arr;
}function var_read(...$var){
ob_start();
var_dump(...$var);
$r=ob_get_contents();
ob_end_clean();
return $r;
}

?>
