<?php

// Created by avid
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
}}while($str||$str==='0'){
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
}}while($str||$str==='0'){
$arr[]=mb_substr($str,0,$num);
$str=mb_substr($str,$num);
}return $arr;
}function var_read(...$var){
ob_start();
var_dump(...$var);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function replaceone($from,$to,$str){
return substr_replace($str,$to,strpos($str,$from),strlen($from));
}class ThumbCode {
private $code=false;
public function __construct($func){
$this->code=$func;
}public function __destruct(){
if($this->code)();
}public function close(){
$this->code=false;
}public function clone(){
return new ThumbCode($this->code);
}
}function thumbCode($func){
return new ThumbCode($func);
}function var_move(&$var1,&$var2){
$var3=$var1;
$var1=$var2;
$var2=$var3;
}function var_add($to,...$args){
$t=gettype($to);
switch($t){
case "NULL":return null;break;
case "boolean":
foreach($args as $arg){
if($arg)return true;
}return false;
break;case "integer":
case "float":
case "double":
foreach($args as $arg){
$to+=$arg;
}return $to;
break;case "string":
foreach($args as $arg){
$to.=$arg;
}return $to;
break;case "array":
foreach($args as $arg){
$to=array_merge($to,$arg);
}return $to;
break;case "object":
if(get_class($to)=="stdClass"){
$to=(array)$to;
foreach($args as $arg){
$to=array_merge($to,(array)$arg);
}return (object)$to;
}break;
}new XNError("var_add","type invalid");
}

?>
