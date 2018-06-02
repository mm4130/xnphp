<?php

// Created by avid
// xn Root File v1

$GLOBALS['-XN-']['errorShow'] = true;
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
$f=0;
if($rms){
$len=strlen($str);
if($len%$num){
$f=$len%$num;
$arr[]=substr($str,0,$f);
}}while(isset($str[$f])){
$arr[]=substr($str,$f,$num);
$f+=$num;
}return $arr;
}function mb_subsplit($str,$num=1,$rms=false){
$arr=[];
$f=0;
if($rms){
$len=mb_strlen($str);
if($len%$num){
$f=$len%$num;
$arr[]=mb_substr($str,0,$f);
}}while(isset($str[$f])){
$arr[]=mb_substr($str,$f,$num);
$f+=$num;
}return $arr;
}function var_read(...$var){
ob_start();
var_dump(...$var);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function replaceone($from,$to,$str){
return substr_replace($str,$to,strpos($str,$from),strlen($from));
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
}function xneval($code,&$save=5636347437634){
$p=strpos($code,"<?");
if($p===false||$p==-1)$code="<?php ".$code;
$random = rand(0,99999999).rand(0,99999999);
fput("xn$random.log",$code);
$z=new thumbCode(function()use($random){
unlink("xn$random.log");
});
if($save===5636347437634){
$r=@require "xn$random.log";
}else{
ob_start();
$r=@require "xn$random.log";
$save=ob_get_contents();
ob_end_clean();
}return $r;
}function thecode(){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
return $c;
}function theline(){
$t=debug_backtrace();
return $t[0]['line'];
}function thefile(){
$t=debug_backtrace();
return $t[0]['file'];
}function thedir(){
$t=debug_backtrace();
return dirname($t[0]['file']);
}function var_name(&$var){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
preg_match('/var_name[\n ]*\([@\n ]*\$([a-zA-Z_0-9]+)[\n ]*((\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[[^\]]+\])|(\([^\)]*\)))*\)/',$c,$s);
$s[0]=substr($s[0],9,-1);
preg_match_all('/(\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[[^\]]+\])|(\([^\)]*\))/',$s[0],$j);
$u=[];
foreach($j[1] as $e){
if($e)$u[]=["caller"=>'->',
"type"=>"object_method",
"value"=>substr($e,2)];
}foreach($j[2] as $e){
if($e)$u[]=["caller"=>"::",
"type"=>"static_method",
"value"=>substr($e,2)];
}foreach($j[3] as $e){
if($e)$u[]=["caller"=>"[]",
"type"=>"array_index",
"value"=>substr($e,1,-1)];
}foreach($j[4] as $e){
if($e)$u[]=["caller"=>"()",
"type"=>"closure_call",
"value"=>substr($e,1,-1)];
}if(isset($s[1]))return ["name"=>$s[1],
"full"=>$s[0],
"calls"=>$u];
new XNError("var_name","invalid variable");
return false;
}function define_name($define){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
preg_match('/define_name[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\)/',$c,$s);
if(isset($s[1]))return $s[1];
new XNError("define_name","define type error");
return false;
}function countin($text,$in){
return count(explode($in,$text))-1;
}function function_name($func){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
preg_match('/function_name[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\(/',$c,$s);
if(isset($s[1]))return $s[1];
new XNError("define_name","this not is a function");
return false;
}

?>
