<?php

// Created by whiteweb // xn.white-web.ir // @white_web
// xn plugin files

class XNStringPosition {
private $s='',$p=0;
public function __construct($s){
$this->s=$s;
}public function pos(){
return $this->p;
}public function end(){
return $this->s[$this->p=strlen($this->s)-1];
}public function eof(){
return !isset($this->s[$this->p]);
}public function next(){
return $this->s[++$this->p];
}public function back(){
return $this->s[--$this->p];
}public function start(){
return $this->s[$this->p=0];
}public function goto($c){
return $this->s[$this->p=$c];
}public function backby($c){
return $this->s[$this->p-=$c];
}public function nextby($c){
return $this->s[$this->p+=$c];
}public function endto($c){
return $this->s[$this->p=strlen($this->s)-1-$c];
}public function sliceto($l,$k=false){
if(!$k)$this->p+=$l;
return substr($this->s,$this->p,$l);
}public function slicefrom($l,$k=false){
if(!$k)$this->p-=$l;
return substr($this->s,$this->p-$l,$l);
}public function XNMBStringPosition(){
return new XNMBStringPosition($this->s);
}}function XNStringPosition($x){
return new XNStringPosition($x);
}class XNMBStringPosition {
private $s='',$p=0;
public function __construct($s){
$this->s=$s;
}public function pos(){
return $this->p;
}public function end(){
$this->p=mb_strlen($this->s);
return mb_substr($this->s,-1,1);
}public function eof(){
return mb_substr($this->s,$this->p,1)!=true;
}public function next(){
return mb_substr($this->s,++$this->p,1);
}public function back(){
return mb_substr($this->s,--$this->p,1);
}public function start(){
$this->p=0;
return mb_substr($this->s,0,1);
}public function goto($c){
return mb_substr($this->s,$this->p=$c,1);
}public function backby($c){
return mb_substr($this->s,$this->p-=$c,1);
}public function nextby($c){
return mb_substr($this->s,$this->p+=$c,1);
}public function endto($c){
return mb_substr($this->s,$this->p=mb_strlen($this->s)-1-$c,1);
}public function sliceto($l,$k=false){
if(!$k)$this->p+=$l;
return mb_substr($this->s,$this->p,$l);
}public function slicefrom($l,$k=false){
if(!$k)$this->p-=$l;
return mb_substr($this->s,$this->p-$l,$l);
}public function XNStringPosition(){
return new XNStringPosition($this->s);
}}function XNMBStringPosition($x){
return new XNMBStringPosition($x);
}$GLOBALS['-XN-']['errorShow']=true;
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
$message="<br>\n[$date]<b>XN $type</b> &gt; <i>$in</i> : $text in <b>{$th['file']}</b> on line <b>{$th['line']}</b>\n<br>";
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
}

?>
