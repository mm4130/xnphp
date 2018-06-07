<?php

// Created by avid
// xn script v1.5

if(PHP_VERSION<6.7){
throw new Error("<b>xn library</b> needs more than or equal to 6.7 version");
exit;
}

$GLOBALS['-XN-']=[];
$GLOBALS['-XN-']['startTime']=microtime(1);
$GLOBALS['-XN-']['dirName']=substr(__FILE__,0,strrpos(__FILE__,DIRECTORY_SEPARATOR));
$GLOBALS['-XN-']['dirNameDir']=$GLOBALS['-XN-']['dirName'].DIRECTORY_SEPARATOR;
$GLOBALS['-XN-']['lastUpdate']="0{[LASTUPDATE]}";
$GLOBALS['-XN-']['lastUse']="1528371305{[LASTUSE]}";
$GLOBALS['-XN-']['DATA']="W10={[DATA]}";
$DATA=json_decode(base64_decode(substr($GLOBALS['-XN-']['DATA'],0,-8)),@$XNDATA===1);

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
}}function thumbCode($func){
return new ThumbCode($func);
}function set_last_update_nter(){
$file=$GLOBALS['-XN-']['dirNameDir'].'xn.php';
$f=file_get_contents($file);
$p=strpos($f,"{[LASTUPDATE]}");
while($p>0&&$f[$p--]!='"');
if($p<=0)return false;
$h='';
$p+=2;
while($f[$p]!='{')$h.=$f[$p++];
if(!is_numeric($h))return false;
$f=str_replace("$h{[LASTUPDATE]}",time()."{[LASTUPDATE]}",$f);
return file_put_contents($file,$f);
}function set_last_use_nter(){
$file=$GLOBALS['-XN-']['dirNameDir'].'xn.php';
$f=file_get_contents($file);
$p=strpos($f,"{[LASTUSE]}");
while($p>0&&$f[$p--]!='"');
if($p<=0)return false;
$h='';
$p+=2;
while($f[$p]!='{')$h.=$f[$p++];
if(!is_numeric($h))return false;
$f=str_replace("$h{[LASTUSE]}",time()."{[LASTUSE]}",$f);
return file_put_contents($file,$f);
}function set_data_nter(){
$data=base64_encode(json_encode($GLOBALS['DATA']));
$file=$GLOBALS['-XN-']['dirNameDir'].'xn.php';
$f=file_get_contents($file);
$p=strpos($f,"{[DA"."TA]}");
while($p>0&&$f[$p--]!='"');
if($p<=0)return false;
$h='';
$p+=2;
while($f[$p]!='{')$h.=$f[$p++];
$f=str_replace("$h{[DA"."TA]}","$data{[D"."ATA]}",$f);
return file_put_contents($file,$f);
}function xnupdate(){
copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php",$GLOBALS['-XN-']['dirNameDir']."xn.php");
if(file_exists("xn.beautify.php"))
copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.beautify.php",$GLOBALS['-XN-']['dirNameDir']."xn.beautify.php");
set_last_update_nter();
}if(@$XNUPDATE===2||(@$XNUPDATE===1&&substr($GLOBALS['-XN-']['lastUpdate'],0,-14)+10000<=time()))xnupdate();
if(@$SAVELASTES !== 1){
$GLOBALS['-XN-']['runEnd']=thumbCode(function(){
global $DATA;
set_data_nter();
set_last_use_nter();
});
}

// XNCodes

// Root-------------------------------------
$GLOBALS['-XN-']['errorShow'] = true;
class XNError extends Error {
protected $message;
static function show($sh=null){
if($sh===null)$GLOBALS['-XN-']['errorShow']=!$GLOBALS['-XN-']['errorShow'];
else $GLOBALS['-XN-']['errorShow']=$sh;
}static function handlr($func){
$GLOBALS['-XN-']['errorHandlr']=$func;
}public function __construct($in,$text,$level=0,$en=false){
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
if($en)exit;
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
}function array_read(...$var){
ob_start();
print_r(...$var);
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
}new XNError("var_add","type invalid",0,true);
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
$t=end($t);
$l=file($t['file']);
$c=$l[$t['line']-1];
return $c;
}function theline(){
$t=debug_backtrace();
$t=end($t);
return $t['line'];
}function thefile(){
$t=debug_backtrace();
$t=end($t);
return $t['file'];
}function thedir(){
$t=debug_backtrace();
$t=end($t);
return dirname($t['file']);
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
new XNError("var_name","invalid variable",1);
return false;
}function define_name($define){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
preg_match('/define_name[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\)/',$c,$s);
if(isset($s[1]))return $s[1];
new XNError("define_name","invalid define",1);
return false;
}function countin($text,$in){
return count(explode($in,$text));
}function function_name($func){
$t=debug_backtrace();
$l=file($t[0]['file']);
$c=$l[$t[0]['line']-1];
preg_match('/function_name[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\(/',$c,$s);
if(isset($s[1]))return $s[1];
new XNError("define_name","this value not is a function",1);
return false;
}function printsc($k=true){
$t=debug_backtrace();
$l=file($t[0]['file']);
$p=$t[0]['line'];
if($k)while(isset($l[$p][1])&&$l[$p][0].$l[$p][1]=='#>'){
echo evalc(substr($l[$p++],2));
}else while(isset($l[$p][1])&&$l[$p][0].$l[$p][1]=='#>'){
echo substr($l[$p++],2);
}
}function evalc($code){
return eval('return '.$code.';');
}function is_function($f){
return (is_string($f)&&function_exists($f))||(is_object($f)&&($f instanceof Closure));
}function is_json($json){
$obj=@json_decode($json);
return $obj!==false&&is_string($json)&&(is_object($obj)||is_array($obj));
}function random($str,$leng=1){
if(is_string($str))$str=str_split($str);
$r='';$c=count($str)-1;
while($leng>0){
$r=$r.$str[rand(0,$c)];
$leng--;}
return $r;
}function split($str,$count=1,$space=1){
$arr=[];
$length=strlen($str);
$str=str_split($str);
$loc=0;
while($loc<$length){
$c=0;
$r='';
while($c<$count){
$r=$r.$str[$loc+$c];
$c++;}
$arr[]=$r;
$loc+=$space;}
return $arr;
}function ContentType($c){
return header("Content-Type: $c");
}function equal($a,$b,$c='==',$d=0){
$ia=is_array($a)||is_object($a);
$ib=is_array($b)||is_object($a);
if($ia)$a=(array)$a;
if($ib)$b=(array)$b;
$z1=true;
$z2=true;
if($c[0]=='-'||$c[0]=='+'||$c[0]=='*'||$c[0]=='/'||$c[0]=='~'){
if($ia){
if($c[0]=='-')foreach($a as &$x){
$t=gettype($x);
$x--;
settype($x,$t);
}elseif($c[0]=='+')foreach($a as &$x){
$t=gettype($x);
$x++;
settype($x,$t);
}elseif($c[0]=='*')foreach($a as &$x){
$t=gettype($x);
$x*=$x;
settype($x,$t);
}elseif($c[0]=='/')foreach($a as &$x){
$t=gettype($x);
$x=1/$x;
settype($x,$t);
}elseif($c[0]=='~')foreach($a as &$x){
$t=gettype($x);
$x=~$x;
settype($x,$t);
}
}else{
$t=gettype($a);
if($c[0]=='-')$a--;
if($c[0]=='+')$a++;
if($c[0]=='*')$a*=$a;
if($c[0]=='/')$a=1/$a;
if($c[0]=='~')$a=~$a;
settype($a,$t);
}$c=substr($c,1);
}$l=strlen($c)-1;
if(!isset($c[$l]));
elseif($c[$l]=='+'){
$z2=true;
$c=substr($c,0,-1);
}elseif($c[$l]=='-'){
$z2=false;
$c=substr($c,0,-1);
}$l--;
if(!isset($c[$l]));
elseif($c[$l]=='+'){
$z1=true;
$c=substr($c,0,-1);
}elseif($c[$l]=='-'){
$z1=false;
$c=substr($c,0,-1);
}$l--;
if(!isset($c[$l]));
elseif($c[$l]=='/'){
$c=substr($c,0,-1);
if(is_array($a)&&$z1)foreach($a as &$x)$x=(string)$x;
else $a=(string)$a;
if(is_array($b)&&$z2)foreach($b as &$x)$x=(string)$x;
else $b=(string)$b;
}if($c=='!==='||$c=='!>'||$c=='!<'||$c=='!>='||$c=='!<=')$c=[
'==='=>'!==',
'!<'=>'>=',
'!>'=>'<=',
'!>='=>'<',
'!<='=>'>'
][substr($c,1)];
if(is_numeric($c))$c=@['==','!=','>=','<=','>','<','!==','==='][$c];
$cv=$c[0]=='.'||$c[0]=='$'?substr($c,1):$c;
if($c!='$'&&$c!='.'&&$cv!='=='&&$cv!='!='&&$cv!='>='&&$cv!='<='&&$cv!='<'&&$cv!='>'&&$cv!='!=='&&$cv!='==='){
new XNError("equal","equal type invalid",0);
return false;
}$pp=function($a,$b)use($c){
$ia=(is_string($a)||is_numeric($a));
$ib=(is_string($b)||is_numeric($b));
if($c[0]=='.'&&is_array($b)){
return in_array($a,$b);
}elseif($c[0]=='.'&&$ia&&$ib){
$p=strpos($a,$b);
return $p!==false&&$p!=-1;
}elseif($c[0]=='$'&&is_array($b)){
return isset($b[$a]);
}elseif($c[0]=='$'&&is_object($b)){
return isset($b->{$a})||method_exists($b,$a);
}$a=serialize($a);
$b=serialize($b);
return eval("return unserialize('$a'){$c}unserialize('$b');");
};if($d===1){
if($ia&&$ib&&$z1&&$z2){
foreach($a as $x){
foreach($b as $y){
if($r=$pp($x,$y))break;
}if($r)return true;
}return false;
}if($ia&&$z1){
foreach($a as $x){
if($pp($x,$b))return true;
}return false;
}if($ib&&$z2){
foreach($b as $x){
if($pp($a,$x))return true;
}return false;
}
}elseif($d===0){
if($ia&&$ib&&$z1&&$z2){
foreach($a as $x){
foreach($b as $y){
if($r=$pp($x,$y))break;
}if(!$r)return false;
}return true;
}if($ia&&$z1){
foreach($a as $x){
if(!$pp($x,$b))return false;
}return true;
}if($ib&&$z2){
foreach($b as $x){
if(!$pp($a,$x))return false;
}return true;
}
}elseif($d===2){
if($ia&&$ib&&$z1&&$z2){
foreach($a as $k=>$x){
if($pp($x,$b[$k]))return true;
}return false;
}if($ia&&$z1){
foreach($a as $x){
if($r=$pp($x,$b))return true;
}return false;
}if($ib&&$z2){
foreach($b as $x){
if($r=$pp($a,$x))return true;
}return false;
}
}elseif($d==3){
if($ia&&$ib&&$z1&&$z2){
foreach($a as $k=>$x){
if(!$pp($x,$b[$k]))return false;
}return true;
}if($ia&&$z1){
foreach($a as $x){
if(!$pp($x,$b))return false;
}return true;
}if($ib&&$z2){
foreach($b as $x){
if(!$pp($a,$x))return false;
}return true;
}
}return $pp($a,$b);
}function array_string($arr,$js=false){
if(!is_array($arr)&&!is_object($arr)){
new XNError("array_string","can not convert ".gettype($arr)." to array string",0);
return false;
}$r='[';
$p=0;
foreach((array)$arr as $k=>$v){
if($r!='[')$r.=',';
if(is_array($v))$v=array_string($v,$js);
if(is_numeric($k)&&$k==$p){
$r.=json_encode($v,$js);
$p++;
}else $r.=json_encode($k,$js).'=>'.json_encode($v,$js);
}$r.=']';
return $r;
}function func_repeat($func,$c){
$r='';
while($c>0)$r.=$func($c--);
return $r;
}function ifstr($a,$b,$c=87438975298754978){
return $c==87438975298754978?($a?"$a":"$b"):$a?"$b":"$c";
}function array_repeat($arr,$count=1){
for($c=0;$c<$count;$c++){
foreach($arr as $v)$arr[]=$v;
}return $arr;
}function array_settype($type,$arr){
foreach($arr as &$v)settype($v,$type);
return $arr;
}function evals($str){
return evalc("\"$str\"");
}function findurls($s){
preg_match_all('/([hH][tT][tT][pP][sS]{0,1}:\/\/)([a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)+)(:[0-9]{1,8}){0,1}(\/([^\/\?\# ])*)*(\#[^\n ]*){0,1}(\?[^\n\# ]*){0,1}(\#[^\n ]*){0,1}/',$s,$u);
if(!isset($u[0][0]))return false;
return $u[0];
}
// Data-----------------------------------
function xndata($name){
if(file_exists($GLOBALS['-XN-']['dirNameDir'].'xndata.xnj'))
$xnj=new XNJsonFile($GLOBALS['-XN-']['dirNameDir'].'xndata.xnj');
else $xnj=new XNJsonURL("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.xnj");
$value=$xnj->value($name);
$xnj->close();
return $value;
}
// Telegram-------------------------------
class TelegramBotKeyboard {
private $btn=[],$button=[];
public $resize=false,$onetime=false,$selective=false;
public function size($size=null){
if($size===null)$size=!$this->resize;
$this->resize=$size==true;
return $this;
}public function onetime($onetime=null){
if($onetime===null)$onetime=!$this->onetime;
$this->onetime=$onetime==true;
return $this;
}public function selective($selective=null){
if($selective===null)$selective=!$this->selective;
$this->selective=$selective==true;
return $this;
}public function add($name,$type=''){
$btn=["text"=>$name];
if($type=="contact")$btn["request_contact"]=true;
elseif($type=="location")$btn["request_location"]=true;
$this->btn[]=$btn;
return $this;
}public function line(){
$this->button[]=$this->btn;
$this->btn=[];
return $this;
}public function get($json=false){
$this->button[]=$this->btn;
$btn=["keyboard"=>$this->button];
if($this->resize)$btn['resize_keyboard']=true;
if($this->onetime)$btn['one_time_keyboard']=true;
if($this->selective)$btn['selective']=true;
$this->button=[];
$this->btn=[];
$this->size=false;
return $json?json_encode($btn):$btn;
}public function reset(){
$this->button=[];
$this->btn=[];
$this->size=false;
return $this;
}
}class TelegramBotInlineKeyboard {
private $btn=[],$button=[];
public $resize=false,$onetime=false,$selective=false;
public function size($size=null){
if($size===null)$size=!$this->resize;
$this->resize=$size==true;
return $this;
}public function onetime($onetime=null){
if($onetime===null)$onetime=!$this->onetime;
$this->onetime=$onetime==true;
return $this;
}public function selective($selective=null){
if($selective===null)$selective=!$this->selective;
$this->selective=$selective==true;
return $this;
}public function add($name,$type,$data=''){
$btn=["text"=>$name];
if($type=="pay")$data=true;
elseif($type=="game")$type="callback_game";
elseif($type=="switch")$type="switch_inline_query";
elseif($type=="switch_current_chat")$type="switch_inline_query_current_chat";
elseif($type=="callback"||$type=="data")$type="callback_data";
elseif($type=="link")$type="url";
$btn[$type]=$data;
$this->btn[]=$btn;
return $this;
}public function line(){
$this->button[]=$this->btn;
$this->btn=[];
return $this;
}public function get($json=false){
$this->button[]=$this->btn;
$btn=["inline_keyboard"=>$this->button];
if($this->resize)$btn['resize_keyboard']=true;
if($this->onetime)$btn['one_time_keyboard']=true;
if($this->selective)$btn['selective']=true;
$this->button=[];
$this->btn=[];
$this->size=false;
return $json?json_encode($btn):$btn;
}public function reset(){
$this->button=[];
$this->btn=[];
$this->size=false;
return $this;
}
}class TelegramBotQueryResult {
public $get;
public function add($type,$id,$title,$input,$args=[]){
$args["type"]=$type;
$args["id"]=$id;
$args["title"]=$title;
$args["input_message_content"]=$input;
$this->get[]=$args;
return $this;
}public function inputMessage($text,$parse=false,$preview=false){
$args=["message_text"=>$text];
if($parse)$args["parse_mode"]=$parse;
if($preview)$args["disable_web_page_preview"]=$preview;
return $args;
}public function inputLocation($latitude,$longitude,$live=false){
$args=["latitude"=>$latitude,"longitude"=>$longitude];
if($live)$args['live_period']=$live;
return $args;
}public function inputVenue($latitude,$longitude,$title,$address,$id=false){
$args=["latitude"=>$latitude,"longitude"=>$longitude,"title"=>$title,"address"=>$address];
if($id)$args["foursquare_id"]=$id;
return $args;
}
}class TelegramBotButtonSave {
private $btns=[],$btn=[];
public function get($name,$json=true){
if($json)return @$this->btn[$name];
return @$this->btns[$name];
}public function add($name,$btn){
if(is_array($btn))$btns=json_encode($btn);
elseif(!is_json($btn))return false;
else $btn=json_decode($btns=$btn);
if(!isset($btns['inline_keyboard'])||
   !isset($btns['keyboard'])||
   !isset($btns['force_reply'])||
   !isset($btns['remove_keyboard']))
   return false;
$this->btns=$btns;
$this->btn=$btn;
return $this;
}public function delete($name){
if(isset($this->btn[$name])){
unset($this->btn[$name]);
unset($this->btns[$name]);
}return $this;
}public function reset(){
$this->btn=[];
$this->btns=[];
}
}class TelegramBotSends {
private $bot;
public $chat,$level;
public function chat($chat){
$this->chat;
return $this;
}public function level($level){
$lthis->level=$level;
return $this;
}public function __construct($bot,$chat=null,$level=null){
$this->bot=$bot;
$this->chat=$chat;
$this->level=$level;
}public function __wakeup($chat=null,$level=null){
if($chat&&$level){
$this->chat=$chat;
$this->level=$level;
}elseif($chat){
if($chat<100)$this->level=$chat;
else $this->chat=$chat;
}return $this;
}public function action($action){
$this->bot->sendAction($this->chat,$action,$this->level);
return $this;
}public function typing(){
$this->bot->sendAction($this->chat,"typing",$this->level);
return $this;
}public function msg($text,$args=[]){
$this->bot->sendMessage($this->chat,$text,$args,$this->level);
return $this;
}public function btnmsg($text,$btn,$args=[]){
$args['reply_markup']=$btn;
$this->bot->sendMessage($this->chat,$text,$args,$this->level);
return $this;
}public function media($type,$media,$args=[]){
$this->bot->sendMedia($this->chat,$type,$media,$args,$this->level);
return $this;
}public function mediamsg($type,$media,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendMedia($this->chat,$type,$media,$args,$this->level);
return $this;
}public function mediabtn($type,$media,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendMedia($this->chat,$type,$media,$args,$this->level);
return $this;
}public function mediamsgbtn($type,$media,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendMedia($this->chat,$type,$media,$args,$this->level);
return $this;
}public function photo($photo,$args=[]){
$this->bot->sendPhoto($this->chat,$photo,$args,$$this->level);
return $this;
}public function voice($voice,$args=[]){
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function video($video,$args=[]){
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audio($audio,$args=[]){
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonote($videonote,$args=[]){
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function sticker($sticker,$args=[]){
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function document($document,$args=[]){
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function file($file,$args=[]){
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}public function photomsg($photo,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendPhoto($this->chat,$photo,$args,$$this->level);
return $this;
}public function voicemsg($voice,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function videomsg($video,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audiomsg($audio,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonotemsg($videonote,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function stickermsg($sticker,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function documentmsg($document,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function filemsg($file,$caption,$args=[]){
$args['caption']=$caption;
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}public function photobtn($photo,$markup,$args=[]){
$args['caption']=$caption;
$this->bot->sendPhoto($this->chat,$photo,$args,$$this->level);
return $this;
}public function voicebtn($voice,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function videobtn($video,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audiobtn($audio,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonotebtn($videonote,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function stickerbtn($sticker,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function documentbtn($document,$markup,$args=[]){
$args['reply_markup']=$caption;
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function filebtn($file,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}public function photomsgbtn($photo,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendPhoto($this->chat,$photo,$args,$$this->level);
return $this;
}public function voicemsgbtn($voice,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function videomsgbtn($video,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audiomsgbtn($audio,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonotemsgbtn($videonote,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function stickermsgbtn($sticker,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function documentmsgbtn($document,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function filemsgbtn($file,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$caption;
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}
}class TelegramBot {
public $data,$token,$final,$results=[],$sents=[],$save=true,$last;
public $keyboard,$inlineKeyboard,$foreReply,$removeKeyboard,$queryResult,$menu,$send;
public function send($chat=null,$level=null){
return new TelegramBotSends($this,$chat,$level);
}public function setToken($token=''){
$this->last=$this->token;
$this->token=$token;
return $this;
}public function backToken(){
$token=$this->token;
$this->token=$this->last;
$this->last=$token;
return $this;
}public function __construct($token=''){
$this->token=$token;
$this->keyboard=new TelegramBotKeyboard;
$this->inlineKeyboard=new TelegramBotInlineKeyboard;
$this->queryResult=new TelegramBotQueryResult;
$this->menu=new TelegramBotButtonSave;
$this->send=new TelegramBotSends($this);
$this->forceReply=["force_reply"=>true];
$this->removeKeyboard=["remove_keyboard"=>true];
}public function update($offset=-1,$limit=1,$timeout=0){
if(isset($this->data->message_id))return $this->data;
elseif($this->data=json_decode(file_get_contents("php://input")))return $this->data;
else $res=$this->data=$this->request("getUpdates",[
"offset"=>$offset,
"limit"=>$limit,
"timeout"=>$timeout
],3);
if(!$res->ok)return (object)[];
return $res;
}public function request($method,$args=[],$level=3){
$args=$this->parse_args($args);
if($level==1){
header("Content-Type: application/json");
$args['method']=$method;
echo json_encode($args);
$res=true;
}elseif($level==2){
$res=fclose(fopen("https://api.telegram.org/bot$this->token/$method?".http_build_query($args),'r'));
}elseif($level==3){
$ch=curl_init("https://api.telegram.org/bot$this->token/$method");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$args);
$res=json_decode(curl_exec($ch));
curl_close($ch);
}elseif($level==4){
$res=fclose(fopen("https://api.pwrtelegram.xyz/bot$this->token/$method?".http_build_query($args),'r'));
}elseif($level==5){
$ch=curl_init("https://api.pwrtelegram.xyz/bot$this->token/$method");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$args);
$res=json_decode(curl_exec($ch));
curl_close($ch);
}else return false;
$args['method']=$method;
$args['level']=$level;
if($this->save){
$this->sents[]=$args;
$this->results[]=$this->final=$res;
}if($res===false)return false;
if($res===true)return true;
if(!$res->ok){
new XNError("TelegramBot","$res->description [$res->error_code]",1);
return $res;
}return $res;
}public function reset(){
$this->final=null;
$this->results=[];
$this->sents=[];
$this->data=null;
}public function close(){
$this->final=null;
$this->results=null;
$this->sents=null;
$this->data=null;
$this->token=null;
}public function sendMessage($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$args['text']=$text;
return $this->request("sendMessage",$args,$level);
}public function sendMessages($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$texts=subsplit($text,4096);
foreach($texts as $text){
$args['text']=$text;
$this->request("sendMessage",$args,$level);
}return $this;
}public function sendMessageRemoveKeyboard($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$args['text']=$text;
$args['reply_markup']=json_encode(["remove_keyboard"=>true]);
return $this->request("sendMessage",$args,$level);
}public function sendMessageForceReply($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$args['text']=$text;
$args['reply_markup']=json_encode(['force_reply'=>true]);
return $this->request("sendMessage",$args,$level);
}public function sendAction($chat,$action,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>$action
],$level);
}public function sendTyping($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"typing"
],$level);
}public function setWebhook($url='',$args=[],$level=3){
$args['url']=$url?$url:'';
return $this->request("setWebhook",$args,$level);
}public function deleteWebhook($level=3){
return $this->request("setWebhook",[],$level);
}public function getChat($chat,$level=3){
return $this->request("getChat",[
"chat_id"=>$chat
],$level);
}public function getMembersCount($chat,$level=3){
return $this->request("getChatMembersCount",[
"chat_id"=>$chat
],$level);
}public function getMember($chat,$user,$level=3){
return $this->request("getChatMember",[
"chat_id"=>$chat,
"user_id"=>$user
],$level);
}public function getProfile($user,$level=3){
$args['user_id']=$user;
$args['chat_id']=$user;
return $this->request("getUserProfilePhotos",$args,$level);
}public function banMember($chat,$user,$time=false,$level=3){
$args=[
"chat_id"=>$chat,
"user_id"=>$user
];
if($time)$args['until_date']=$time;
return $this->request("kickChatMember",$args,$level);
}public function unbanMember($chat,$user,$level=3){
return $this->request("unbanChatMember",[
"chat_id"=>$chat,
"user_id"=>$user
],$level);
}public function kickMember($chat,$user,$level=3){
return [$this->banMember($chat,$user,$level),$this->unbanMember($chat,$user,$level)];
}public function getMe($level=3){
return $this->request("getMe",[],$level);
}public function getWebhook($level=3){
return $this->request("getWebhookInfo",[],$level);
}public function restrictMember($chat,$user,$args,$time=false,$level=3){
foreach($args as $key=>$val)$args["can_$key"]=$val;
$args['chat_id']=$chat;
$args['user_id']=$user;
if($time)$args['until_date']=$time;
return $this->request("restrictChatMember",$args,$level);
}public function promoteMember($chat,$user,$args=[],$level=3){
foreach($args as $key=>$val)$args["can_$key"]=$val;
$args['chat_id']=$chat;
$args['user_id']=$user;
return $this->request("promoteChatMember",$args,$level);
}public function exportInviteLink($chat,$level=3){
$this->request("exportChatInviteLink",[
"chat_id"=>$chat
],$level);
}public function setChatPhoto($chat,$photo,$level=3){
return $this->request("setChatPhoto",[
"chat_id"=>$chat,
"photo"=>$photo
],$level);
}public function deleteChatPhoto($chat,$level=3){
return $this->request("deleteChatPhoto",[
"chat_id"=>$chat
],$level);
}public function setTitle($chat,$title,$level=3){
return $this->request("setChatTitle",[
"chat_id"=>$chat,
"title"=>$title
],$level);
}public function setDescription($chat,$description,$level=3){
return $this->request("setChatDescription",[
"chat_id"=>$chat,
"description"=>$description
],$level);
}public function pinMessage($chat,$message,$disable=false,$level=3){
return $this->request("pinChatMessage",[
"chat_id"=>$chat,
"message_id"=>$message,
"disable_notification"=>$disable
],$level);
}public function unpinMessage($chat,$level=3){
return $this->request("unpinChatMessage",[
"chat_id"=>$chat
],$level);
}public function leaveChat($chat,$level=3){
return $this->request("leaveChat",[
"chat_id"=>$chat
],$level);
}public function getAdmins($chat,$level=3){
return $this->request("getChatAdministrators",[
"chat_id"=>$chat
],$level);
}public function setChatStickerSet($chat,$sticker,$level=3){
return $this->request("setChatStickerSet",[
"chat_id"=>$chat,
"sticker_set_name"=>$sticker
],$level);
}public function deleteChatStickerSet($chat,$level=3){
return $this->request("deleteChatStickerSet",[
"chat_id"=>$chat
],$level);
}public function answerCallback($id,$text,$args=[],$level=3){
$args['callback_query_id']=$id;
$args['text']=$text;
return $this->request("answerCallbackQuery",$args,$level);
}public function editText($text,$args=[],$level=3){
$args['text']=$text;
return $this->request("editMessageText",$args,$level);
}public function editMessageText($chat,$msg,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$args['message_id']=$msg;
$args['text']=$text;
return $this->request("editMessageText",$args,$level);
}public function editInlineText($msg,$text,$args=[],$level=3){
$args['inline_message_id']=$msg;
$args['text']=$text;
return $this->request("editMessageText",$args,$level);
}public function editCaption($caption,$args=[],$level=3){
$args['caption']=$caption;
return $this->request("editMessageCaption",$args,$level);
}public function editMessageCaption($chat,$msg,$caption,$args=[],$level=3){
$args['chat_id']=$chat;
$arsg['message_id']=$msg;
$args['caption']=$caption;
return $this->request("editMessageCaption",$args,$level);
}public function editInlineCaption($msg,$caption,$args=[],$level=3){
$arsg['inline_message_id']=$msg;
$args['caption']=$caption;
return $this->request("editMessageCaption",$args,$level);
}public function editReplyMarkup($reply_makup,$args=[],$level=3){
$args['reply_markup']=$reply_markup;
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editMessageReplyMarkup($chat,$msg,$reply_makup,$args=[],$level=3){
$args['chat_id']=$chat;
$args['message_id']=$msg;
$args['reply_markup']=$reply_markup;
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editInlineReplyMarkup($msg,$reply_makup,$args=[],$level=3){
$args['inline_message_id']=$msg;
$args['reply_markup']=$reply_markup;
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editInlineKeyboard($reply_makup,$args=[],$level=3){
$args['reply_markup']=["inline_keyboard"=>$reply_markup];
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editMessageInlineKeyboard($chat,$msg,$reply_makup,$args=[],$level=3){
$args['chat_id']=$chat;
$args['message_id']=$msg;
$args['reply_markup']=["inline_keyboard"=>$reply_markup];
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editInlineInlineKeyboard($msg,$reply_makup,$args=[],$level=3){
$args['inline_message_id']=$msg;
$args['reply_markup']=["inline_keyboard"=>$reply_markup];
return $this->request("editMessageReplyMarkup",$args,$level);
}public function deleteMessage($chat,$message,$level=3){
return $this->request("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$message
],$level);
}public function deleteMessages($chat,$messages,$level=3){
if($level>5){
$level-=5;
$from=min(...$messages);
$to=max(...$messages);
for(;$from<=$to;$from++)
$this->request("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$from
],$level);
}else{
foreach($messages as $message)
$this->request("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$message
],$level);
}
}public function sendMedia($chat,$type,$file,$args=[],$level=3){
$type=strtolower($type);
if($type=="videonote")$type="video_note";
$args['chat_id']=$chat;
$args[$type]=$file;
return $this->request("send".str_replace('_','',$type),$args,$level);
}public function sendFile($chat,$file,$args=[],$level=3){
$type=TelegramCode::getFileType($file);
if(!$type)return false;
$args['chat_id']=$chat;
$args[$type]=$file;
return $this->request("send".str_replace('_','',$type),$args,$level);
}public function getStickerSet($name,$level=3){
return $this->request("getStickerSet",[
"name"=>$name
],$level);
}public function sendDocument($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['document']=$file;
return $this->request("sendDocument",$args,$level);
}public function sendPhoto($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['photo']=$file;
return $this->request("sendPhoto",$args,$level);
}public function sendVideo($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['video']=$file;
return $this->request("sendVideo",$args,$level);
}public function sendAudio($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['audio']=$file;
return $this->request("sendAudio",$args,$level);
}public function sendVoice($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['voice']=$file;
return $this->request("sendVoice",$args,$level);
}public function sendSticker($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['sticker']=$file;
return $this->request("sendSticker",$args,$level);
}public function sendVideoNote($chat,$file,$args=[],$level=3){
$args['chat_id']=$chat;
$args['video_note']=$file;
return $this->request("sendVideoNote",$args,$level);
}public function uploadStickerFile($user,$file,$level=3){
return $this->request("uploadStickerFile",[
"user_id"=>$user,
"png_sticker"=>$file
],$level);
}public function createNewStickerSet($user,$name,$title,$args=[],$level=3){
$args['user_id']=$user;
$args['name']=$name;
$args['title']=$title;
return $this->request("createNewStickerSet",$args,$level);
}public function addStickerToSet($user,$name,$args=[],$level=3){
$args['user_id']=$user;
$args['name']=$name;
return $this->request("addStickerToSet",$args,$level);
}public function setStickerPositionInSet($sticker,$position,$level=3){
return $this->request("setStickerPositionInSet",[
"sticker"=>$sticker,
"position"=>$position
],$level);
}public function deleteStickerFromSet($sticker,$level=3){
return $this->request("deleteStickerFromSet",[
"sticker"=>$sticker
],$level);
}public function answerInline($id,$results,$args=[],$switch=[],$level=3){
$args['inline_query_id']=$id;
$args['results']=is_array($results)?json_encode($results):$results;
if($switch['text'])$args['switch_pm_text']=$switch['text'];
if($switch['parameter'])$args['switch_pm_parameter']=$switch['parameter'];
return $this->request("answerInlineQuery",$args,$level);
}public function answerPreCheckout($id,$ok=true,$level=3){
if($ok===true)$args=[
"pre_checkout_query_id"=>$id,
"ok"=>true
];
else $args=[
"pre_checkout_query_id"=>$id,
"ok"=>false,
"error_message"=>$ok
];
return $this->request("answerPreCheckoutQuery",$args,$level);
}public function setGameScore($user,$score,$args=[],$level=3){
$args['user_id']=$user;
$args['score']=$score;
return $this->request("setGameScore",$args,$level);
}public function getGameHighScores($user,$args=[],$level=3){
$args['user_id']=$user;
return $this->request("getGameHighScores",$args,$level);
}public function sendGame($chat,$name,$args=[],$level=3){
$args['chat_id']=$chat;
$args['name']=$name;
return $this->request("sendGame",$args,$level);
}public function getFile($file,$level=3){
return $this->request("getFile",[
"file_id"=>$file
],$level);
}public function readFile($path,$level=3,$speed=false){
if($speed)$func="fget";
else $func="file_get_contents";
if($level==3){
return ($func)("https://api.telegram.org/file/bot$this->token/$path");
}elseif($level==5){
return ($func)("https://api.pwrtelegram.xyz/file/bot$this->token/$path");
}else return false;
}public function downloadFile($file,$level=3){
return $this->readFile($this->getFile($file,3)->result->file_path,$level);
}public function downloadFileProgress($file,$func,$al,$level=3){
$file=$this->request("getFile",[
"file_id"=>$file
],$level);
if(!$file->ok)return false;
$size=$file->result->file_size;
$path=$file->result->file_path;
$time=microtime(true);
if($level==3){
return fgetprogress("https://api.telegram.org/file/bot$this->token/$path",function($data)use($size,$func,$time){
$dat=strlen($data);
$up=microtime(true)-$time;
$speed=$dat/$up;
$all=$size/$dat*$time-$time;
$pre=100/($size/$dat);
return $func((object)["content"=>$data,"downloaded"=>$dat,"size"=>$size,"time"=>$up,"endtime"=>$all,"speed"=>$speed,"pre"=>$pre]);
},$al);
}elseif($level==5){
return fgetprogress("https://api.pwrtelegram.xyz/file/bot$this->token/$path",function($data)use($size,$func,$time){
$dat=strlen($data);
$up=microtime(true)-$time;
$speed=$dat/$up;
$all=$size/$dat*$time-$time;
$pre=$size/$dat*100;
return $func((object)["content"=>$data,"downloaded"=>$dat,"size"=>$size,"time"=>$up,"endtime"=>$all,"speed"=>$speed,"pre"=>$pre]);
},$al);
}else return false;
}public function sendContact($chat,$phone,$args=[],$level=3){
$args['chat_id']=$chat;
$args['phone_number']=$phone;
return $this->request("sendContact",$args,$level);
}public function sendVenue($chat,$latitude,$longitude,$title,$address,$args=[],$level=3){
$args['chat_id']=$chat;
$args['latitude']=$latitude;
$args['longitude']=$longitude;
$args['title']=$title;
$args['address']=$address;
return $this->request("sendVenue",$args,$level);
}public function stopMessageLiveLocation($args,$level=3){
return $this->request("stopMessageLiveLocation",$args,$level);
}public function editMessageLiveLocation($latitude,$longitude,$args=[],$level=3){
$args['latitude']=$latitude;
$args['longitude']=$longitude;
return $this->request("editMessageLiveLocation",$args,$level);
}public function sendLocation($chat,$latitude,$longitude,$args=[],$level=3){
$args['chat_id']=$chat;
$args['latitude']=$latitude;
$args['longitude']=$longitude;
$this->request("sendLocation",$args,$level);
}public function sendMediaGroup($chat,$media,$args=[],$level=3){
$args['chat_id']=$chat;
$args['media']=json_encode($media);
return $this->request("sendMediaGroup",$args,$level);
}public function forwardMessage($chat,$from,$message,$disable=false,$level=3){
return $this->request("forwardMessage",[
"chat_id"=>$chat,
"from_chat_id"=>$from,
"message_id"=>$message,
"disable_notification"=>$disable
],$level);
}public $removekey=["remove_keyboard"=>true];
public $forcereply=["force_reply"=>true];
public function updateType($update=false){
if(!$update)$update=$this->lastUpdate();
if(isset($update->message))
return "message";
elseif(isset($update->callback_query))
return "callback_query";
elseif(isset($update->chosen_inline_result))
return "chosen_inline_result";
elseif(isset($update->inline_query))
return "inline_query";
elseif(isset($update->channel_post))
return "channel_post";
elseif(isset($update->edited_message))
return "edited_message";
elseif(isset($update->edited_channel_post))
return "edited_channel_post";
elseif(isset($update->shipping_query))
return "shipping_query";
elseif(isset($update->pre_checkout_query))
return "pre_checkout_query";
return "unknow_update";
}public function getUpdateInType($update=false){
return ($update?$update:$this->lastUpdate())->{$this->updateType()};
}public function readUpdates($func,$while=0,$limit=1,$timeout=0){
if($while==0)$while=-1;
$offset=0;
while($while>0||$while<0){
$updates=$this->update($offset,$limit,$timeout);
if(isset($updates->message_id)){
if($offset==0)$updates=(object)["result"=>[$updates]];
else return;
}if(isset($updates->result)){
foreach($updates->result as $update){
$offset=$update->update_id+1;
if($func($update))return true;
}$while--;}}
}public function filterUpdates($filter=[],$func=false){
if(in_array($this->updateType(),$filter)){
if($func)$func($this->data);exit();
}
}public function unfilterUpdates($filter=[],$func=false){
if(!in_array($this->updateType(),$filter)){
if($func)$func($this->data);exit();
}
}public function getUser($update=false){
$update=$this->getUpdateInType($update);
if(!isset($update->chat))return (object)[
"chat"=>$update->from,
"from"=>$update->from
];return (object)[
"chat"=>$update->chat,
"from"=>$update->from
];}public function getDate($update=false){
$update=$this->getUpdateInType($update);
if(isset($update->date))return $update->date;
return false;
}public function getData($update=false){
$update=$this->getUpdateInType($update);
if(isset($update->text))return $update->text;
if(isset($update->query))return $update->query;
return false;
}public function isChat($user,$update=false){
$chat=$this->getUser($update)->chat->id;
if(is_array($user)&&in_array($chat,$user))return true;
elseif($user==$chat)return true;
return false;
}public function lastUpdate(){
$update=$this->update();
if(isset($update->update_id))return $update;
elseif(isset($update->result[0]->update_id))return $update->result[0];
else return [];
}public function getUpdates(){
$update=$this->update(0,999999999999,0);
if(isset($update->update_id))return [$update];
elseif($update->result[0]->update_id)return $update->result;
else return [];
}public function lastUpdateId($update=false){
if(!$update)$update=$this->update(-1,1,0);
if($update->result[0]->update_id)
return end($update->result)->update_id;
elseif(isset($update->update_id))
return $update->update_id;
else return 0;
}public function fileType($message=false){
if(!$message&&isset($this->lastUpdate()->message))$message=$this->lastUpdate()->message;
elseif(!$message)return false;
if(isset($message->photo))return "photo";
if(isset($message->voice))return "voice";
if(isset($message->audio))return "audio";
if(isset($message->video))return "video";
if(isset($message->sticker))return "sticker";
if(isset($message->document))return "document";
if(isset($message->video_note))return "videonote";
return false;
}public function fileInfo($message=false){
if(!$message&&isset($this->lastUpdate()->message))$message=$this->lastUpdate()->message;
elseif(!$message)return false;
if(isset($message->photo))return end($message->photo);
if(isset($message->voice))return $message->voice;
if(isset($message->audio))return $message->audio;
if(isset($message->video))return $message->video;
if(isset($message->sticker))return $message->sticker;
if(isset($message->document))return $message->document;
if(isset($message->video_note))return $message->video_note;
return false;
}public function isFile($message=false){
if(!$message&&isset($this->lastUpdate()->message))$message=$this->lastUpdate()->message;
elseif(!$message)return false;
if($message->text)return false;
return true;
}public function convertFile($file,$type,$name,$chat,$level=3){
if(file_exists($name))$read=file_get_contents($name);
else $read=false;
file_put_contents($name,$this->downloadFile($file));
$r=$this->sendMedia($chat,$type,new CURLFile($name));
unlink($name);
if($read!==false)file_put_contents($name,$read);
return $r;
}private function parse_args($args=[]){
if(isset($args['user']))$args['user_id']=$args['user'];
if(isset($args['chat']))$args['chat_id']=$args['chat'];
if(isset($args['message']))$args['message_id']=$args['message'];
if(isset($args['msg']))$args['message_id']=$args['msg'];
if(isset($args['msg_id']))$args['message_id']=$args['msg_id'];
if(!isset($args['chat_id'])&&isset($args['message_id'])){
$args['inline_message_id']=$args['message_id'];
unset($args['message_id']);
}if(isset($args['id']))$args['callback_query_id']=$args['inline_query_id']=$args['id'];
if(isset($args['mode']))$args['parse_mode']=$args['mode'];
if(isset($args['markup']))$args['reply_markup']=$args['markup'];
if(isset($args['reply']))$args['reply_to_message_id']=$args['reply'];
if(isset($args['from_chat']))$args['from_chat_id']=$args['from_chat'];
if(isset($args['file']))$args['photo']=$args['document']=$args['video']=$args['voice']=$args['video_note']=$args['audio']=$args['sticker']=
                        $args['photo_file_id']=$args['document_file_id']=$args['video_file_id']=
                        $args['voice_file_id']=$args['video_note_file_id']=$args['audio_file_id']=$args['sticker_file_id']=
                        $args['photo_url']=$args['document_url']=$args['video_url']=$args['voice_url']=$args['video_note_url']=
                        $args['audio_url']=$args['sticker_url']=$args['file_id']=$args['file'];
if(isset($args['phone']))$args['phone_number']=$args['phone'];
if(isset($args['allowed_updates'])&&is_array($args['allowed_updates']))
$args['allowed_updates']=json_encode($args['allowed_updates']);
if(isset($args['reply_markup'])&&is_array($args['reply_markup']))
$args['reply_markup']=json_encode($args['reply_markup']);
return $args;
}
}

class TelegramLink {
static function getMessage($chat,$message){
try{
$g=file_get_contents("https://t.me/$chat/$message?embed=1");
$x=new DOMDocument;
@$x->loadHTML($g);
$x=@new DOMXPath($x);
$path="//div[@class='tgme_widget_message_bubble']";
$enti=$x->query("$path//div[@class='tgme_widget_message_text']")[0];
$entities=[];
$last=0;$pos=false;$line=0;
$textlen=strlen($enti->nodeValue);
$entit=new DOMDocument;
$entit->appendChild($entit->importNode($enti,true));
$text=trim(html_entity_decode(strip_tags(str_replace('<br/>',"\n",$entit->saveXML()))));
foreach((new DOMXPath($entit))->query("//code|i|b|a") as $num=>$el){
$len=strlen($el->nodeValue);
$pos=strpos(substr($enti->nodeValue,$last,$textlen),$el->nodeValue)+$last;
$last=$pos+$len;
$entities[$num]=[
"offset"=>$pos,
"length"=>$len
];if($el->tagName=='a')
$entities[$num]['url']=$el->getAttribute("href");
elseif($el->tagName=='b')$entities[$num]['type']='bold';
elseif($el->tagName=='i')$entities[$num]['type']='italic';
elseif($el->tagName=='code')$entities[$num]['type']='code';
elseif($el->tagName=='a')$entities[$num]['type']='link';
}if($entities==[])$entities=false;
$date=strtotime($x->query("$path//a[@class='tgme_widget_message_date']")[0]->getElementsByTagName('time')[0]->getAttribute("datetime"));
$views=$x->query("$path//span[@class='tgme_widget_message_views']");
if(isset($views[0]))$views=$views[0]->nodeValue;
else $views=false;
$author=$x->query("$path//span[@class='tgme_widget_message_from_author']");
if(isset($author[0]))$author=$author[0]->nodeValue;
else $author=false;
$via=$x->query("$path//a[@class='tgme_widget_message_via_bot']");
if(isset($via[0]))$via=substr($via[0]->nodeValue,1);
else $via=false;
$forward=$x->query("$path//a[@class='tgme_widget_message_forwarded_from_name']")[0];
if($forward){
$forwardname=$forward->nodeValue;
$forwarduser=$forward->getAttribute("href");
$forwarduser=end(explode('/',$forwarduser));
$forward=$forwardname?[
"title"=>$forwardname,
"username"=>$forwarduser
]:false;
}else $forward=false;
$replyid=$x->query("$path//a[@class='tgme_widget_message_reply']");
if(isset($replyid[0])){
$replyid=$replyid[0]->getAttribute("href");
$replyid=explode('/',$replyid);
$replyid=end($replyid);
$replyname=$x->query("$path//a[@class='tgme_widget_message_reply']//span[@class='tgme_widget_message_author_name']")[0]->nodeValue;
$replytext=$x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_text']")[0]->nodeValue;
$replymeta=$x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_metatext']")[0]->nodeValue;
$replyparse=explode(' ',$replymeta);
$replythumb=$x->query("$path//a[@class='tgme_widget_message_reply']//i[@class='tgme_widget_message_reply_thumb']")[0];
if($replythumb)$replythumb=$replythumb->getAttribute('style');
else $replythumb=false;
preg_match('/url\(\'(.{1,})\'\)/',$replythumb,$pr);
$replythumb=$pr[1];
$reply=[
"message_id"=>$replyid,
"title"=>$replyname
];if($replytext)$reply['text']=$replytext;
elseif($replyparse[0]=='Service'||$replyparse[0]=='Channel')$reply['service_message']=true;
elseif($replyparse[1]=='Sticker'){
$reply['emoji']=$replyparse[0];
$reply['sticker']=$replythumb;
}elseif($replyparse[0]=='Photo')$reply['photo']=$replythumb;
elseif($replyparse[0]=='Voice')$reply['voice']=true;
elseif($replythumb)$reply['document']=$replythumb;
}else $reply=false;
$service=$x->query("$path//div[@class='message_media_not_supported_label']");
if(isset($service[0]))$service=$service[0]->nodeValue=='Service message';
else $service=false;
$photo=$x->query("$path//a[@class='tgme_widget_message_photo_wrap']")[0];
if($photo){
$photo=$photo->getAttribute('style');
preg_match('/url\(\'(.{1,})\'\)/',$photo,$pr);
$photo=["photo"=>$pr[1]];
}else $photo=false;
$voice=$x->query("$path//audio[@class='tgme_widget_message_voice']");
if(isset($voice[0])){
$voice=$voice[0]->getAttribute("src");
$voiceduration=$x->query("$path//time[@class='tgme_widget_message_voice_duration']")[0]->nodeValue;
$voiceex=explode(':',$voiceduration);
if(count($voiceex)==3)$voiceduration=$voiceex[0]*3600+$voiceex[1]*60+$voiceex[2];
else $voiceduration=$voiceex[0]*60+$voiceex[1];
$voice=[
"voice"=>$voice,
"duration"=>$voiceduration
];
}else $voice=false;
$sticker=$x->query("$path//div[@class='tgme_widget_message_sticker_wrap']");
if(isset($sticker[0])){
$stickername=$sticker[0]->getElementsByTagName("a")[0];
$sticker=$stickername->getElementsByTagName('i')[0]->getAttribute("style");
preg_match('/url\(\'(.{1,})\'\)/',$sticker,$pr);
$sticker=$pr[1];
$stickername=$stickername->getAttribute("href");
$stickername=explode('/',$stickername);
$stickername=end($stickername);
$sticker=[
"sticker"=>$sticker,
"setname"=>$stickername
];
}else $sticker=false;
$document=$x->query("$path//div[@class='tgme_widget_message_document_title']");
if(isset($document[0])){
$document=$document[0]->nodeValue;
$documentsize=$x->query("$path//div[@class='tgme_widget_message_document_extra']")[0]->nodeValue;
$document=[
"title"=>$document,
"size"=>$documentsize
];
}else $document=false;
$video=$x->query("$path//a[@class='tgme_widget_message_video_player']");
if(isset($video[0])){
$video=$video[0]->getElementsByTagName("i")[0]->getAttribute("style");
preg_match('/url\(\'(.{1,})\'\)/',$video,$pr);
$video=$pr[1];
$videoduration=$vide->getElementsByTagName("time")[0]->nodeValue;
$videoex=explode(':',$videoduration);
if(count($videoex)==3)$videoduration=$videoex[0]*3600+$videoex[1]*60+$videoex[2];
else $videoduration=$videoex[0]*60+$videoex[1];
$video=[
"video"=>$video,
"duration"=>$videoduration
];
}else $video=false;
if($text&&($document||$sticker||$photo||$voice||$video)){
$caption=$text;
$text=false;
}$r=["username"=>$chat,
"message_id"=>$message];
if($author)$r['author']=$author;
if($text)$r['text']=$text;
if(isset($caption)&&$caption)$r['caption']=$caption;
if($views)$r['views']=$views;
if($date)$r['date']=$date;
if($photo)$r['photo']=$photo;
if($voice)$r['voice']=$photo;
if($video)$r['video']=$video;
if($sticker)$r['sticker']=$sticker;
if($document)$r['document']=$document;
if($forward)$r['forward']=$forward;
if($reply)$r['reply']=$reply;
if($entities)$r['entities']=$entities;
if($service)$r['service_message']=true;
return (object)$r;
}catch(Error $e){
return false;}
}static function getChat($chat){
$g=file_get_contents("https://t.me/$chat");
$g=str_replace('<br/>',"\n",$g);
$x=new DOMDocument;
$x->loadHTML($g);
$x=new DOMXPath($x);
$path="//div[@class='tgme_page_wrap']";
$photo=$x->query("$path//img[@class='tgme_page_photo_image']");
if(isset($photo[0]))
$photo=$photo[0]->getAttribute("src");
else $photo=false;
$title=$x->query("$path//div[@class='tgme_page_title']");
if(!isset($title[0]))return false;
$title=trim($title[0]->nodeValue);
$description=$x->query("$path//div[@class='tgme_page_description']")[0]->nodeValue;
$members=explode(' ',$x->query("$path//div[@class='tgme_page_extra']")[0]->nodeValue)[0];
$r=["title"=>$title];
if($photo)$r['photo']=$photo;
if($description)$r['description']=$description;
if($members>0)$r['members']=$members*1;
return (object)$r;
}static function getJoinChat($code){
return self::getChat("joinchat/$code");
}static function getSticker($name){
$g=file_get_contents("https://t.me/addstickers/$name");
$x=new DOMDocument;
$x->loadHTML($g);
$x=new DOMXPath($x);
$title=$x->query("//div[@class='tgme_page_description']");
if(!isset($title[0]))return false;
$title=$title[0]->getElementsByTagName("strong")[1]->nodeValue;
return (object)[
"setname"=>$name,
"title"=>$title
];
}
}

class TelegramCode {
static function getFileType($file){
$file=base64_decode(strtr($file,'-_','+/'));
return [
0=>"thumb",
2=>"image",
5=>"document",
3=>"voice",
10=>"document",
4=>"video",
9=>"audio",
13=>"video_note",
8=>"sticker"
][ord($file[0])];
}static function getMimeType($type,$mime_type="text/plan"){
return ["document"=>$mime_type,"audio"=>"audio/mp3","video"=>"video/mp4","vide_note"=>"video/mp4","voice"=>"audio/ogg","photo"=>"image/jpeg","sticker"=>"image/webp"][$type];
}static function getFormat($type,$format="txt"){
return ["document"=>$format,"audio"=>"mp3","video"=>"mp4","vide_note"=>"mp4","voice"=>"ogg","photo"=>"jpg","sticker"=>"webp"][$type];
}static function getJoinChat($code){
$code=base64_decode(strtr($code,'-_','+/'));
return base_convert(bin2hex(substr($code,4,4)),16,10);
}static function faketoken_random(){
$tokens=xndata("faketoken/random");
return $tokens[array_rand($tokens)];
}
}

class TelegramUploder {
private static function getbot(){
return new TelegramBot("348695851:AAE5GyQ7NVgxq9i1UToQQXBydGiNVD06rpo");
}static function upload($content){
$bot=self::getbot();
$codes='';
$contents=subsplit($content,5242880);
foreach($contents as $content){
$random=rand(0,999999999).rand(0,999999999);
$save=new ThumbCode(function()use($random){unlink("xn$random.log");});
fput("xn$random.log",$content);
$file=new CURLFile("xn$random.log");
$code=$bot->sendDocument("@tebrobot",$file)->result->document->file_id;
if($codes)$codes.=".$code";
else $codes=$code;
unset($save);
}$random=rand(0,999999999).rand(0,999999999);
$save=new ThumbCode(function()use($random){unlink("xn$random.log");});
fput("xn$random.log",$codes);
$file=new CURLFile("xn$random.log");
$code=$bot->sendDocument("@tebrobot",$file)->result->document->file_id;
unset($save);
return $code;
}static function download($code){
$bot=self::getbot();
$codes=$bot->downloadFile($code);
$codes=explode('.',$codes);
foreach($codes as &$code){
$code=$bot->downloadFile($code);
}return implode('',$codes);
}static function uploadFile($file){
$bot=self::getbot();
$codes='';
$f=@fopen($file,'r');
if(!$f){
new XNError("file '$file' not found!");
return false;
}while(($content=fread($f,5242880))!==''){
$random=rand(0,999999999).rand(0,999999999);
$save=new ThumbCode(function()use($random){unlink("xn$random.log");});
fput("xn$random.log",$content);
$file=new CURLFile("xn$random.log");
$code=$bot->sendDocument("@tebrobot",$file)->result->document->file_id;
if($codes)$codes.=".$code";
else $codes=$code;
unset($save);
}$random=rand(0,999999999).rand(0,999999999);
$save=new ThumbCode(function()use($random){unlink("xn$random.log");});
fput("xn$random.log",$codes);
$file=new CURLFile("xn$random.log");
$code=$bot->sendDocument("@tebrobot",$file)->result->document->file_id;
fclose($f);
unset($save);
return $code;
}static function downloadFile($code,$file){
$bot=self::getbot();
$f=@fopen($file,'w');
if(!$f){
new XNError("not can open file '$file'!");
return false;
}$codes=$bot->downloadFile($code);
$codes=explode('.',$codes);
foreach($codes as $code){
$code=$bot->downloadFile($code);
fwrite($f,$code);
}return fclose($f);
}static function convert($code,$type,$name){
$bot=self::getbot();
$code=$bot->convertFile($code,$file,$type,"@tebrobot");
if(!$code->ok)return $code;
return $code->result->{$type};
}
}

class PWRTelegram {
public $token,$phone;
public function __invoke($phone=''){
$phone=str_replace(['+',' ','(',')','.',','],'',$phone);
if(is_numeric($phone))$this->phone=$phone;
else $this->token=$phone;
}public function checkAPI(){
$f=@fopen("https://api.pwrtelegram.xyz",'r');
if(!$f)return false;
fclose($f);
return true;
}public function __construct($phone=''){
$phone=str_replace(['+',' ','(',')','.',','],'',$phone);
if(is_numeric($phone))$this->phone=$phone;
else $this->token=$phone;
}public function request($method,$args=[],$level=2){
if(@$this->token){
if($level==1){
$r=@fclose(@fopen("https://api.pwrtelegram.xyz/user$this->token/$method?".http_build_query($args),"r"));
}elseif($level==2){
$ch=curl_init("https://api.pwrtelegram.xyz/user$this->token/$method");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$args);
$r=json_decode(curl_exec($ch));
curl_close($ch);
}elseif($level==3){
$r=json_decode(file_get_contents("https://api.pwrtelegram.xyz/user$this->token/$method?".http_build_query($args)));
}else{
new XNError("PWRTelegram","invalid level type",1);
return false;
}if($r===false)return false;
if($r===true)return true;
if($r===null){
new XNError("PWRTelegram","PWRTelegram api is offlined",1);
return null;
}if(!$r->ok){
new XNError("PWRTelegram","$r->description [$r->error_code]",1);
return $r;
}return $r;
}if($level==1){
$r=@fclose(@fopen("https://api.pwrtelegram.xyz/$method?".http_build_query($args),"r"));
}elseif($level==2){
$ch=curl_init("https://api.pwrtelegram.xyz/$method");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$args);
$r=json_decode(curl_exec($ch));
curl_close($ch);
}elseif($level==3){
$r=json_decode(file_get_contents("https://api.pwrtelegram.xyz/$method?".http_build_query($args)));
}else{
new XNError("PWRTelegram","invalid level type",1);
return false;
}if($r===false)return false;
if($r===true)return true;
if($r===null){
new XNError("PWRTelegram","PWRTelegram api is offlined",1);
return null;
}if(!$r->ok){
new XNError("PWRTelegram","$r->description [$r->error_code]",1);
return $r;
}return $r;
}public function login($level=2){
$r=$this->request("phonelogin",[
"phone"=>$this->phone
],$level);
$this->token=$r->result;
return $r;
}public function completeLogin($pass,$level=2){
$res=$this->request("completephonelogin",[
"code"=>$pass
],$level);
if($res->ok)$this->token=$res->result;
return $res;
}public function complete2FA($pass,$level=2){
$res=$this->request("complete2FALogin",[
"password"=>$pass
],$level);
if($res->ok)$this->token=$res->result;
return $res;
}public function signup($first,$last='',$level=2){
$res=$this->request("completesignup",$last?[
"first_name"=>$first,
"last_name"=>$last
]:[
"first_name"=>$first
],$level);
if($res->ok)$this->token=$res->result;
return $res;
}public function fullLogin($args=[],$level=2){
if(!$this->token)return $this->login($level);
if(!isset($args['code']))return false;
$res=$this->completeLogin($args['code'],$level);
if($res->ok)return $res;
if(strpos($res->description,"2FA is enabled: call the complete2FALogin method with the password as password parameter")===0){
if(!isset($args['password']))return false;
return $this->complete2FA($args['password'],$level);
}if($res->description=="Need to sign up: call the completesignup method"){
if(!isset($args['first_name']))return false;
if(!isset($args['last_name']))$args['last_name']='';
return $this->signup($args['first_name'],$args['last_name'],$level);
}return $res;
}public function getChat($chat){
return $this->request("getChat",[
"chat_id"=>$chat
]);
}public function messagesRequest($method,$args=[],$level=2){
return $this->request("messages.$method",$args,$level);
}public function authRequest($method,$args=[],$level=2){
return $this->request("auth.$method",$args,$level);
}public function accountRequest($method,$args=[],$level=2){
return $this->request("account.$method",$args,$level);
}public function channelsRequest($method,$args=[],$level=2){
return $this->request("channels.$method",$args,$level);
}public function helpRequest($method,$args=[],$level=2){
return $this->request("help.$method",$args,$level);
}public function contactsRequest($method,$args=[],$level=2){
return $this->request("contacts.$method",$args,$level);
}public function phoneRequest($method,$args=[],$level=2){
return $this->request("phone.$method",$args,$level);
}public function photosRequest($method,$args=[],$level=2){
return $this->request("photos.$method",$args,$level);
}public function stickersRequest($method,$args=[],$level=2){
return $this->request("stickers.$method",$args,$level);
}public function paymentsRequest($method,$args=[],$level=2){
return $this->request("payments.$method",$args,$level);
}public function uploadRequest($method,$args=[],$level=2){
return $this->request("upload.$method",$args,$level);
}public function usersRequest($method,$args=[],$level=2){
return $this->request("users.$method",$args,$level);
}public function langpackRequest($method,$args=[],$level=2){
return $this->request("langpack.$method",$args,$level);
}public function getUpdates($offset=-1,$limit=1,$timeout=0){
return (array)$this->request("getUpdates",[
"offset"=>$offset,
"limit"=>$limit,
"timeout"=>$timeout
]);
}public function LastUpdate(){
return $this->getUpdates(-1,1,0);
}public function readUpdates($func,$while=0,$limit=1,$timeout=0){
if($while==0)$while=-1;
$offset=0;
while($while>0||$while<0){
$updates=$this->getUpdates($offset,$limit,$timeout)['result'];
foreach($updates as $update){
$offset=$update->update_id+1;
if($func($update))return true;
}$while--;}
}public function installStickerSet($stickerset,$archived=false,$level=2){
return $this->messagesRequest("installStickerSet",[
"stickerset"=>$stickerset,
"archived"=>$archived
],$level);
}public function inviteToChannel($channel,$users,$level=2){
return $this->channelsRequest("inviteToChannel",[
"channel"=>$channel,
"users"=>$users
],$level);
}public function block($user,$level=2){
return $this->contactsRequest("block",[
"id"=>$user
],$level);
}public function sendAction($user,$action="typing",$level=2){
return $this->messagesRequest("setTyping",[
"peer"=>$user,
"action"=>$action,
],$level);
}public function getMessageEditData($peer,$id,$level=2){
return $this->messagesRequest("getMessageEditData",[
"peer"=>$peer,
"id"=>$id
],$level);
}public function checkChatInvite($hash,$level=2){
return $this->messagesRequest("checkChatInvite",[
"hash"=>$hash
],$level);
}public function checkPhone($phone,$level=2){
return $this->authRequest("checkPhone",[
"phone_number"=>$phone
],$level);
}public function availableUsername($username,$level=2){
return $this->accountRequest("checkUsername",[
"username"=>$username
],$level);
}public function checkUsername($channel,$username,$level=2){
return $this->channelsRequest("checkUsername",[
"channel"=>$channel,
"username"=>$username
],$level);
}public function createChat($title,$userns,$level=2){
return $this->messagesRequest("createChat",[
"users"=>$users,
"title"=>$title
],$level);
}public function createChannel($title,$args=[],$level=2){
$result=["title"=>$title];
$result['about']=isset($args['about'])?$args['about']:'';
if(isset($args['broadcast']))$result['broadcast']=$args['broadcast'];
if(isset($args['megagroup']))$result['megagroup']=$args['megagroup'];
return $this->channelsRequest("createChannel",$result,$level);
}public function deleteChannel($channel,$level=2){
return $this->channelsRequest("deleteChannel",[
"channel"=>$channel
],$level);
}public function deleteContact($id,$level=2){
return $this->contactsRequest("deleteContact",[
"id"=>$id
],$level);
}public function deleteMessages($channel,$ids,$level=2){
if($channel===true||$channel===false)
return $this->messagesRequest("deleteMessages",[
"revoke"=>$channel,
"id"=>json_encode($ids)
],$level);
return $this->channelsRequest("deleteMessages",[
"channel"=>$channel,
"id"=>json_encode($ids)
],$level);
}public function unblock($user,$level=2){
return $this->contactsRequest("unblock",[
"id"=>$user
],$level);
}public function forwardMessage($from,$to,$id,$args=[],$level=2){
$args['from_peer']=$from;
$args['to_peer']=$to;
$args['id']=$id;
return $this->messagesRequest("forwardMessage",$args,$level);
}public function exportInvite($channel,$level=2){
return $this->channelsRequest("exportInvite",[
"channel"=>$channel
],$level);
}public function exportChatInvite($chat,$level=2){
return $this->messagesRequest("exportChatInvite",[
"chat_id"=>$chat
],$level);
}public function getStickerSet($stickerset,$level=2){
return $this->messagesRequest("getStickerSet",[
"stickerset"=>$stickerset
],$level);
}public function exportCard($level=2){
return $this->contactsRequest("exportCard",[],$level);
}public function editTitle($channel,$title,$level=2){
return $this->channelsRequest("editTitle",[
"channel"=>$channel,
"title"=>$title
],$level);
}public function editChatTitle($chat,$title,$level=2){
return $this->messagesRequest("editChatTitle",[
"chat_id"=>$chat,
"title"=>$title
],$level);
}public function editAbout($channel,$about,$level=2){
return $this->channelsRequest("editAbout",[
"channel"=>$channel,
"about"=>$about
],$level);
}public function deleteContacts($id,$level=2){
return $this->contactsRequest("deleteContacts",[
"id"=>$id
],$level);
}public function getAllChats($id,$level=3){
return $this->messagesRequest("getAllChats",[
"except_ids"=>$id
],$level);
}public function getAllStickers($hash,$level=3){
return $this->messagesRequest("getAllStickers",[
"hash"=>$hash
],$level);
}public function getPeerDialogs($peers,$level=3){
return $this->messagesRequest("getPeerDialogs",[
"peers"=>$peers
],$level);
}public function getGameHighScores($peer,$id,$user,$level=2){
return $this->messagesRequest("getGameHighScores",[
"peer"=>$peer,
"id"=>$id,
"user_id"=>$user
],$level);
}public function getAppUpdate($level=2){
return $this->helpRequest("getAppUpdate",[],$level);
}public function getChats($id,$level=2){
return $this->messagesRequest("getAppUpdate",[
"id"=>$id
],$level);
}public function getUsers($id,$level=2){
return $this->usersRequest("getUsers",[
"id"=>$id
],$level);
}public function getChannels($id,$level=2){
return $this->channelsRequest("getChannels",[
"id"=>$id
],$level);
}public function getSupport($level=2){
return $this->helpRequest("getSupport",[],$level);
}public function getDifference($from,$level=2){
return $this->langpackRequest("getDifference",[],$level);
}public function sendMessage($peer,$message,$args=[],$level=2){
$args['peer']=$peer;
$args['message']=$message;
return $this->messagesRequest("sendMessage",$args,$level);
}public function contactsSearch($q,$limit=0,$level=2){
return $this->contactsRequest("search",[
"q"=>$q,
"limit"=>$limit
],$level);
}public function searchGlobal($q,$date=0,$peer=0,$id=0,$limit=0,$level=2){
return $this->messagesRequest("searchGlobal",[
"q"=>$q,
"offset_date"=>$date,
"offset_peer"=>$peer,
"offset_id"=>$id,
"limit"=>$limit
],$level);
}public function resetAuthorizations($level=2){
return $this>authRequest("resetAuthorizations",[],$level);
}public function deleteUserHistory($args=[],$level=2){
if(!is_array($args))$args=["channel"=>$args];
return $this->channelsRequest("deleteUserHistory",$args,$level);
}public function dropTempAuthKeys($keys,$level=2){
return $this->authRequest("dropTempAuthKeys",[
"except_auth_keys"=>$keys
],$level);
}public function deleteHistory($args=[],$level=2){
return $this->messagesRequest("deleteHistory",$args,$level);
}public function deleteAccount($reason,$level=2){
return $this->accountRequest("deleteAccount",[
"reason"=>$reason
],$level);
}public function updateDeviceLocked($period,$level=2){
return $this->accountRequest("updateDeviceLocked",[
"period"=>$period
],$level);
}public function getWebFile($location,$offset,$limit,$level=2){
return $this->uploadRequest("getWebFile",[
"location"=>$location,
"offset"=>$offset,
"limit"=>$limit
],$level);
}public function editMessage($peer,$id,$args=[],$level=2){
$args['peer']=$peer;
$args['id']=$id;
return $this->messagesRequest("editMessage",$args,$level);
}public function editAdmin($channel,$user,$admin,$level=2){
return $this->channelsRequest("editAdmin",[
"user_id"=>$user,
"channel"=>$channel,
"admin_rights"=>$admin
],$level);
}public function editChatAdmin($chat,$user,$admin,$level=2){
return $this->messagesRequest("editChatAdmin",[
"chat_id"=>$chat,
"user_id"=>$user,
"is_admin"=>$admin
],$level);
}public function editChatPhoto($chat,$photo,$level=2){
return $this->messagesRequest("editChatPhoto",[
"chat_id"=>$chat,
"photo"=>$photo
],$level);
}public function toggleChatAdmins($chat,$enabled=true,$level=2){
return $this->messagesRequest("toggleChatAdmins",[
"chat_id"=>$chat,
"enabled"=>$enabled
],$level);
}public function togglePreHistoryHidden($channel,$enabled=true,$level=2){
return $this->channelsRequest("togglePreHistoryHidden",[
"channel"=>$channel,
"enabled"=>$enabled
],$level);
}public function getCdnConfig($level=2){
return $this->helpRequest("getCdnConfig",[],$level);
}public function getAccountTTL($level=2){
return $this->accountRequest("getAccountTTL",[],$level);
}public function getAdminLog($q,$args=[],$level=2){
$args['q']=$q;
return $this->channelsRequest("getAdminLog",$args,$level);
}public function getArchivedStickers($offset,$limit,$masks=false,$level=2){
return $this->messagesRequest("getArchivedStickers",[
"offset_id"=>$offset,
"limit"=>$limit,
"mask"=>$mask
],$level);
}public function getAuthorizations($level=2){
return $this->accountRequest("getAuthorizations",[],$level);
}public function getAllDrafts($level=2){
return $this->messagesRequest("getAllDrafts",[],$level);
}public function getAdminedPublicChannels($level=2){
return $this->channelsRequest("getAdminedPublicChannels",[],$level);
}public function getMessagesViews($peer,$id,$increment=false,$level=2){
return $this->messagesRequest("getMessagesViews",[
"peer"=>$peer,
"id"=>$id,
"increment"=>$increment
],$level);
}public function getLanguages($level=2){
return $this->langpackRequest("getLanguages",[],$level);
}public function getBlocked($offset,$limit,$level=2){
return $this->contactsRequest("getBlocked",[
"offset"=>$offset,
"limit"=>$limit
],$level);
}public function getParticipants($offset,$limit,$hash,$filter,$channel=false,$level=2){
return $this->channelsRequest("getParticipants",$channel?[
"offset"=>$offset,
"limit"=>$limit,
"hash"=>$hash,
"filter"=>$filter,
"channel"=>$channel
]:[
"offset"=>$offset,
"limit"=>$limit,
"hash"=>$hash,
"filter"=>$filter
],$level);
}public function getCallConfig($level=2){
return $this->phoneRequest("getCallConfig",[],$level);
}public function getCommonChats($max,$limit,$user=false,$level=2){
return $this->messagesRequest("getCommonChats",$user?[
"max_id"=>$max,
"limit"=>$limit,
"user_id"=>$user
]:[
"max_id"=>$max,
"limit"=>$limit
],$level);
}public function getDocumentByHash($hash,$size,$mime,$level=2){
return $this->messagesRequest("getDocumentByHash",[
"sha256"=>$hash,
"size"=>$size,
"mime_type"=>$mime
],$level);
}public function getInlineGameHighScores($user,$id,$level=2){
return $this->messagesRequest("getInlineGameHighScores",[
"user_id"=>$usre,
"id"=>$id
],$level);
}public function getInviteText($level=2){
return $this->helpRequest("getInviteText",[],$level);
}public function getStrings($lang,$keys,$level=2){
return $this->langpackRequest("getStrings",[
"lang_code"=>$lang,
"keys"=>$keys
],$level);
}public function getLangPack($lang,$level=2){
return $this->langpackRequest("getLangPack",[
"lang_code"=>$lang
],$level);
}public function getTopPeers($offset,$limit,$hash,$args=[],$level=2){
$args['offset']=$oggset;
$args['limit']=$limit;
$args['hash']=$hash;
return $this->contactsRequest("getTopPeers",$args,$level);
}public function getNearestDc($level=2){
return $this->helpRequest("getNearestDc",[],$level);
}public function getStatuses($level=2){
return $this->contactsRequest("getStatuses",[],$level);
}public function getNotifySettings($peer,$level=2){
return $this->accountRequest("getNotifySettings",[
"peer"=>$peer
],$level);
}public function getPinnedDialogs($level=2){
return $this->messagesRequest("getPinnedDialogs",[],$level=2);
}public function getHistory($ofid,$ofdate,$ofadd,$limit,$maxid,$minid,$hash,$peer=false,$level=2){
$args=[
"offset_id"=>$ofid,
"offset_date"=>$ofdate,
"add_offset"=>$ofadd,
"limit"=>$limit,
"max_id"=>$maxid,
"min_id"=>$minid,
"hash"=>$hash
];if($peer)$args['peer']=$peer;
return $this->messagesRequest("getHistory",$args,$level);
}public function getPrivacy($key,$level=2){
return $this->accountRequest("getPrivacy",[
"key"=>$key
],$level);
}public function updateStatus($offline=true,$level=2){
return $this->accountRequest("updateStatus",[
"offline"=>$offline
],$level);
}public function offline($level=2){
return $this->updateStatus(true,$level);
}public function online($level=2){
return $this->updateStatus(false,$level);
}public function changeUsername($channel,$username,$level=2){
return $this->channelsRequest("updateUsername",[
"channel"=>$channel,
"username"=>$username
],$level);
}public function updateUsername($username,$level=2){
return $this->accountRequest("updateUsername",[
"username"=>$username
],$level);
}public function updatePasswordSettings($hash,$setting,$level=2){
return $this->accountRequest("updatePasswordSettings",[
"current_password_hash"=>$hash,
"new_settings"=>$setting
],$level);
}public function getPassword($level=2){
return $this->accountRequest("getPassword",[],$level);
}public function getPasswordSettings($hash,$level=2){
return $this->accountRequest("getPasswordSettings",[
"current_password_hash"=>$hash
],$level);
}public function passwordSettings($email,$level=2){
return $this->accountRequest("passwordSettings",[
"email"=>$email
],$level);
}public function sendChangePhoneCode($phone,$args=[],$level=2){
$args['phone_number']=$phone;
return $this->accountRequest("sendChangePhoneCode",$args,$level);
}public function changePhone($phone,$code,$hash,$level=2){
return $this->accountRequest("changePhone",[
"phone_number"=>$phone,
"phone_code_hash"=>$hash,
"phone_code"=>$code
],$level);
}public function faveSticker($unfave,$id=false,$level=2){
return $this->messagesRequest("faveSticker",$id?[
"unfave"=>$unfave,
"id"=>$id
]:[
"unfave"=>$unfave
],$level);
}public function addChatUser($chat,$user,$fwd,$level=2){
return $this->messagesRequest("addChatUser",[
"chat_id"=>$chat,
"user_id"=>$user,
"fwd_limit"=>$fwd
],$level);
}public function saveRecentSticker($unsave,$args=[],$level=2){
$args['unsave']=$unsave;
return $this->messagesRequest("saveRecentSticker",$args,$level);
}public function addStickerToSet($stickerset,$sticker,$level=2){
return $this->stickersRequest("addStickerToSet",[
"stickerset"=>$stickerset,
"sticker"=>$sticker
],$result);
}public function toggleInvites($channel,$enabled,$level=2){
return $this->channelsRequest("toggleInvites",[
"channel"=>$channel,
"enabled"=>$enabled
],$level);
}public function changeStickerPosition($sticker,$pos,$level=2){
return $this->stickersRequest("changeStickerPosition",[
"sticker"=>$sticker,
"position"=>$pos
],$level);
}public function resetWebAuthorization($hash,$level=2){
return $this->accountRequest("resetWebAuthorization",[
"hash"=>$hash
],$level);
}public function getFavedStickers($hash=0,$level=2){
return $this->messagesRequest("getFavedStickers",[
"hash"=>$hash
],$level);
}public function getFeaturedStickers($hash=0,$level=2){
return $this->messagesRequest("getFeaturedStickers",[
"hash"=>$hash
],$level);
}public function getRecentLocations($peer,$limit,$level=2){
return $this->messagesRequest("getRecentLocations",[
"peer"=>$peer,
"limit"=>$limit
],$level);
}public function getRecentStickers($hash,$att=false,$level=2){
return $this->messagesRequest("getRecentStickers",[
"hash"=>$hash,
"attached"=>$att
],$level);
}public function getRecentMeUrls($referer,$level=2){
return $this->helpRequest("getRecentMeUrls",[
"referer"=>$referer
],$level);
}public function getSavedGifs($hash=0,$level=2){
return $this->messagesRequest("getSavedGifs",[
"hash"=>$hash
],$level);
}public function getConfig($level=2){
return $this->helpRequest("getConfig",[],$level);
}public function getAttachedStickers($media,$level=2){
return $this->messagesRequest("getAttachedStickers",[
"media"=>$media
],$level);
}public function getWebAuthorizations($level=2){
return $this->accountRequest("getWebAuthorizations",[],$level);
}public function getTmpPassword($hash,$per,$level=2){
return $this->accountRequest("getTmpPassword",[
"hash"=>$hash,
"period"=>$per
],$level);
}public function getTermsOfService($level=2){
return $this->helpRequest("getTermsOfService",[],$level);
}public function getBotCallbackAnswer($id,$args=[],$level=2){
$args['msg_id']=$id;
return $this->messagesRequest("getBotCallbackAnswer",$args,$level);
}public function getAppChangelog($x,$level=2){
return $this->helpRequest("getAppChangelog",[
"prev_app_version"=>$x
],$level);
}public function exportMessageLink($id,$grouped,$channel=false,$level=2){
return $this->channelsRequest("exportMessageLink",$channel?[
"id"=>$id,
"grouped"=>$grouped,
"channel"=>$channel
]:[
"id"=>$id,
"grouped"=>$grouped
],$level);
}public function getUserPhotos($user,$offset,$max,$limit,$level=2){
return $this->photosRequest("getUserPhotos",[
"user_id"=>$user,
"offset"=>$offset,
"max_id"=>$max,
"limit"=>$limit
],$level);
}public function getPeerSettings($peer=false,$level=2){
return $this->messagesRequest("getPeerSettings",[
"peer"=>$peer
],$level);
}public function getUnreadMentions($peer,$ofid,$ofadd,$limit,$maxid,$minid,$level=2){
return $this->messagesRequest("getUnreadMentions",[
"peer"=>$peer,
"offset_id"=>$ofid,
"add_offset"=>$ofadd,
"limit"=>$limit,
"max_id"=>$maxid,
"min_id"=>$minid
],$level);
}public function getWebPage($url,$hash=0,$level=2){
return $this->messagesRequest("getWebPage",[
"url"=>$url,
"hash"=>$hash
],$level);
}public function getWebPagePreview($message,$args=[],$level=2){
$args['message']=$message;
return $this->messagesRequest("getWebPagePreview",$args,$level);
}public function getDialogs($ofdate,$ofid,$limit,$args=[],$level=2){
$args['offset_date']=$ofdate;
$args['offset_id']=$ofid;
$args['limit']=$limit;
return $this->messagesRequest("getDialogs",$args,$level);
}public function hideReportSpam($peer,$level=2){
return $this->messagesRequest("hideReportSpam",[
"peer"=>$peer
],$level);
}public function importCard($card,$level=2){
return $this->contactsRequest("importCard",[
"export_card"=>$card
],$level);
}public function importChatInvite($hash,$level=2){
return $this->messagesRequest("importChatInvite",[
"hash"=>$hash
],$level);
}public function initConnection($args=[],$level=2){
return $this->request("initConnection",$args,$level);
}public function cancelCode($number,$hash,$level=2){
return $this->authRequest("cancelCode",[
"phone_number"=>$number,
"phone_code_hash"=>$hash
],$level);
}public function sendInvites($number,$message,$level=2){
return $this->authRequest("sendInvites",[
"phone_number"=>$number,
"message"=>$message
],$level);
}public function invokeWithLayer($layer,$query,$level=2){
return $this->request("invokeWithLayer",[
"layer"=>$layer,
"query"=>$query
],$level);
}public function invokeWithoutUpdates($query,$level=2){
return $this->request("invokeWithoutUpdates",[
"query"=>$query
],$level);
}public function invokeAfterMsg($id,$query,$level=2){
return $this->request("invokeAfterMsg",[
"msg_id"=>$id,
"query"=>$query
],$level);
}public function joinChannel($channel,$level=2){
return $this->channelsRequest("joinChannel",[
"channel"=>$channel
],$level);
}public function editBanned($channel,$user,$banned=true,$level=2){
return $this->channelsRequest("editBanned",[
"channe"=>$channel,
"user_id"=>$user,
"banned_rights"=>$banned
],$level);
}public function leaveChannel($channel,$level=2){
return $this->channelsRequest("leaveChannel",[
"channel"=>$channel
],$level);
}public function saveAppLog($events,$level=2){
return $this->helpRequest("saveAppLog",[
"events"=>$events
],$level);
}public function readHistory($channel,$max,$level=2){
return $this->channelsRequest("readHistory",[
"channel"=>$channel,
"max_id"=>$max
],$level);
}public function readMessageContents($channel,$id,$level=2){
return $this->channelsRequest("readMessageContents",[
"channel"=>$channel,
"id"=>$id
],$level);
}public function readMentions($peer,$level=2){
return $this->messagesRequest("readMentions",[
"peer"=>$peer
],$level);
}public function updateProfile($args=[],$level=2){
return $this->accountRequest("updateProfile",$args,$level);
}public function startBot($peer=false,$bot,$start=false,$level=2){
return $this->messagesRequest("startBot",$peer?[
"bot"=>$bot,
"peer"=>$peer,
"start_param"=>$start
]:[
"bot"=>$bot,
"start_param"=>$start
],$level);
}public function readEncryptedHistory($peer,$max,$level=2){
return $this->messagesRequest("readEncryptedHistory",[
"peer"=>$peer,
"max_id"=>$max
],$level);
}public function readChatHistory($peer,$max,$level=2){
return $this->messagesRequest("readHistory",[
"peer"=>$peer,
"max_id"=>$max
],$level);
}public function receivedMessages($max,$level=2){
return $this->messagesRequest("receivedMessages",[
"max_id"=>$max
],$level);
}public function readFeaturedStickers($id,$level=2){
return $this->messagesRequest("readFeaturedStickers",[
"id"=>$id
],$level);
}public function receivedCall($peer,$level=2){
return $this->phoneRequest("receivedCall",[
"peer"=>$peer
],$level);
}public function toggleDialogPin($peer,$pin=null,$level=2){
return $this->messagesRequest("toggleDialogPin",$pin!==null?[
"peer"=>$peer,
"pin"=>$pin
]:[
"peer"=>$peer
],$level);
}public function registerDevice($type,$token,$app,$other,$level=2){
return $this->accountRequest("registerDevice",[
"token_type"=>$type,
"token"=>$token,
"app_sendbox"=>$app,
"other_uids"=>$other
],$level);
}public function uninstallStickerSet($stikerset,$level=2){
return $this->messagesRequest("uninstallStickerSet",[
"stickerset"=>$stickerset
],$level);
}public function removeStickerFromSet($sticker,$level=2){
return $this->stickersRequest("removeStickerFromSet",[
"sticker"=>$sticker
],$level);
}public function reorderPinnedDialogs($order,$force=null,$level=2){
return $this->messagesRequest("reorderPinnedDialogs",$force!==null?[
"order"=>$order,
"force"=>$force
]:[
"order"=>$order
],$level);
}public function reorderStickerSets($order,$masks=null,$level=2){
return $this->messagesRequest("reorderStickerSets",$masks!==null?[
"order"=>$order,
"force"=>$masks
]:[
"order"=>$order
],$level);
}public function reportSpamChannel($channel,$user=false,$id,$level=2){
return $this->channelsRequest("reportSpam",$user?[
"channel"=>$channel,
"user_id"=>$user,
"id"=>$id
]:[
"channel"=>$channel,
"id"=>$id
],$level);
}public function reportSpam($peer,$level=2){
return $this->messagesRequest("reportSpam",[
"peer"=>$peer
],$level);
}public function resendCode($phone,$hash,$level=2){
return $this->authRequest("resendCode",[
"phone_number"=>$phone,
"phone_code_hash"=>$hash
],$level);
}public function reportEncryptedSpam($peer,$level=2){
return $this->messagesRequest("reportEncryptedSpam",[
"peer"=>$peer
],$level);
}public function reportPeer($peer,$reason,$level=2){
return $this->accountRequest("reportPeer",[
"peer"=>$peer,
"reason"=>$reason
],$level);
}public function resetNotifySettings($level=2){
return $this->accountRequest("resetNotifySettings",[],$level);
}public function resetWebAuthorizations($level=2){
return $this-accountRequest("resetWebAuthorizations",[],$level);
}public function resetSaved($level=2){
return $this->contactsRequest("resetSaved",[],$level);
}public function resetTopPeerRating($category,$peer,$level=2){
return $this->contactsRequest("resetTopPeerRating",[
"category"=>$category,
"peer"=>$peer
],$level);
}public function invokeAfterMsgs($msg,$query,$level=2){
return $this->request("invokeAfterMsgs",[
"msg_ids"=>$msg,
"query"=>$query
],$level);
}public function getWallPapers($level=2){
return $this->accountRequest("getWallPapers",[],$level);
}public function saveGif($id,$level=2){
return $this->messagesRequest("saveGif",[
"id"=>$id,
"unsave"=>false
],$level);
}public function unsaveGif($id,$level=2){
return $this->messagesRequest("saveGif",[
"id"=>$id,
"unsave"=>true
],$level);
}public function saveDraft($peer,$message,$args=[],$level=2){
$args['peer']=$peer;
$args['message']=$message;
return $this->messagesRequest("saveDraft",$args,$level);
}public function saveCallDebug($peer,$debug,$level=2){
return $this->phoneRequest("saveCallDebug",[
"peer"=>$peer,
"debug"=>$debug
],$level);
}public function sendEncryptedFile($peer,$message,$file,$level=2){
return $this->messagesRequest("sendEncryptedFile",[
"peer"=>$peer,
"message"=>$message,
"file"=>$file
],$level);
}public function sendMedia($peer,$media,$args=[],$level=2){
if(!isset($args['message']))$args['message']='';
return $this->messagesRequest("sendMedia",$args,$level);
}public function sendEncryptedService($peer,$message,$level=2){
return $this->messagesRequest("sendEncryptedService",[
"peer"=>$peer,
"message"=>$message
],$level);
}public function sendMultiMedia($peer,$media,$args=[],$level=2){
$args['peer']=$peer;
$args['multi_media']=$media;
return $this->messagesRequest("sendMultiMedia",$args,$level);
}public function requestPasswordRecovery($level=2){
return $this->authRequest("requestPasswordRecovery",[],$level);
}public function sendConfirmPhoneCode($hash,$allow=false,$current=false,$level=2){
return $this->accountRequest("sendConfirmPhoneCode",[
"hash"=>$hash,
"allow_flashcall"=>$allow,
"current_number"=>$current
],$level);
}public function sendEncrypted($peer,$message,$level=2){
return $this->messagesRequest("sendEncrypted",[
"peer"=>$peer,
"message"=>$message
],$level);
}public function sendScreenshotNotification($peer,$reply,$level=2){
return $this->messagesRequest("sendScreenshotNotification",[
"peer"=>$peer,
"reply_to_msg_id"=>$reply
],$level);
}public function setEncryptedTyping($peer,$typing=true,$level=2){
return $this->messagesRequest("setEncryptedTyping",[
"peer"=>$peer,
"typing"=>$typing
],$level);
}public function setAccountTTL($ttl,$level=2){
return $this->accountRequest("setAccountTTL",[
"ttl"=>$ttl
],$level);
}public function setCallRating($peer,$rating,$comment,$level=2){
return $this->phoneRequest("setCallRating",[
"peer"=>$peer,
"rating"=>$rating,
"comment"=>$comment
],$level);
}public function setPrivacy($key,$rules,$level=2){
return $this->accountRequest("setPrivacy",[
"key"=>$key,
"rules"=>$rules
],$level);
}public function updatePinnedMessage($channel,$id,$silent=false,$level=2){
return $this->channelsRequest("updatePinnedMessage",[
"channel"=>$channel,
"id"=>$id,
"silent"=>$silent
],$level);
}public function setStickers($channel,$stickerset,$level=2){
return $this->channelsRequest("setStickers",[
"channel"=>$channe,
"stickerset"=>$stickerset
],$level);
}public function unregisterDevice($type,$token,$other,$level=2){
return $this->accountRequest("unregisterDevice",[
"token_type"=>$type,
"token"=>$token,
"other_uids"=>$other
],$level);
}public function toggleSignatures($channel,$enabled=true,$level=2){
return $this->channelsRequest("toggleSignatures",[
"channel"=>$channel,
"enabled"=>$enabled
],$level);
}public function updateProfilePhoto($id,$level=2){
return $this->photosRequest("updateProfilePhoto",[
"id"=>$id
],$level);
}public function uploadMedia($peer,$media,$level=2){
return $this->messagesRequest("uploadMedia",[
"peer"=>$peer,
"media"=>$media
],$level);
}public function uploadEncryptedFile($peer,$file,$level=2){
return $this->messagesRequest("uploadEncryptedFile",[
"peer"=>$peer,
"file"=>$file
],$level);
}public function uploadProfilePhoto($file,$level=2){
return $this->photosRequest("uploadProfilePhoto",[
"file"=>$file
],$level);
}public function recoverPassword($code,$level=2){
return $this->authRequest("recoverPassword",[
"code"=>$code
],$level);
}public function close(){
$this->token=null;
$this->phone=null;
}
}
function findtokens($s){
preg_match_all("/[0-9]{4,20}:AA[GFHE][a-zA-Z0-9-_]{32}/",$s,$u);
if(!isset($u[0][0]))return false;
return $u[0];
}

class XNTelegram {
// Soon ...
}
// Files-------------------------------
function fvalid($file){
$f=@fopen($file,'r');
if(!$f)return false;
fclose($f);
return true;
}function fcreate($file){
$f=@fopen($file,'w');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}fclose($f);
return true;
}function fget($file){
$size=@filesize($file);
if($size!==false&&$size!==null){
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}$r=fread($f,$size);
}else{
$ch=@curl_init($file);
if($ch){
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($ch);
curl_close($ch);
return $r;
}else{
$r='';
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}while(($c=fgetc($f))!==false)$r.=$c.fread($f,1024);
}}fclose($f);
return $r;
}function fput($file,$con){
$f=fopen($file,'w');
if(!$f)return false;
$r=fwrite($f,$con);
fclose($f);
return $r;
}function fadd($file,$con){
$f=fopen($file,'a');
if(!$f)return false;
$r=fwrite($f,$con);
fclose($f);
return $r;
}function fdel($file){
return unlink($file);
}function fputjson($file,$con,$json=false){
return fput($file,json_encode($con,$json));
}function fgetjson($file,$json=false){
return json_decode(fget($file),$json);
}function faddjson($file,$con,$json=false){
$f=fopen($file,'a+');
if(!$f)return false;
$r='';
while($c=fgetc($f))$r.=$c;
$r=json_decode($r,true);
$r=array_merge($r,(array)$con);
$w=fwrite($f,json_encode($con,$json));
fclose($f);
return $w;
}function fexists($file){
return file_exists($file);
}function fsize($file){
return filesize($file);
}function fspeed($file,$type='r'){
if($f=@fopen($file,$type))fclose($f);
return $f;
}function ftype($file){
return filetype($file);
}function fdir($file){
return dirname($file);
}function fname($file){
$file=explode('/',$file);
return end($file);
}function fformat($file){
$file=explode('.',$file);
return end($file);
}function dirdel($dir){
$s=dirscan($dir);
foreach($s as $f){
if(filetype("$dir/$f")=='dir')dirdel("$dir/$f");
else unlink("$dir/$f");
}return rmdir($dir);
}function dirscan($dir){
$s=scandir($dir);
if($s[0]=='..')unset($s[0]);
if($s[1]=='.')unset($s[1]);
if($s[0]=='.')unset($s[0]);
return $s;
}function dircopy($from,$to){
$s=dirscan($dir);
mkdir($to);
foreach($s as $file){
if(filetype("$dir/$file")=='dir')dircopy("$dir/$file","$to/$file");
else copy("$dir/$file","$to/$file");
}
}function dirsearch($dir,$search){
$s=dirscan($dir);
$r=[];
foreach($s as $file){
if(strpos($file,$search))$r[]="$dir/$file";
if(filetype("$dir/$file")=='dir')$r=array_merge($r,dirsearch("$dir/$file",$search));
}return $r;
}function preg_dirsearch($dir,$search){
$s=dirscan($dir);
$r=[];
foreach($s as $file){
if(preg_match($search,$file))$r[]="$dir/$file";
if(filetype("$dir/$file")=='dir')$r=array_merge($r,dirsearch("$dir/$file",$search));
}return $r;
}function dirread($dir){
$s=scandir($dir);
$r=[];
foreach($s as $file){
if($file=='..')$r[$file]=true;
elseif($file=='.')$r[$file]=&$r;
elseif(filetype("$dir/$file")=='dir'){
$r[$file]=dirread("$dir/$file");
$r[$file]['..']=&$r;
}else $r=(object)[
"read"=>function()use($dir,$file){
return fget("$dir/$file");
},"write"=>function($con)use($dir,$file){
return fput("$dir/$file",$con);
},"add"=>function($con)use($dir,$file){
return fadd("$dir/$file",$con);
},"pos"=>function($pos)use($dir,$file){
return fpos("$dir,$file",$pos);
},"explode"=>function($ex)use($dir,$file){
return fexplode("$dir/$file",$ex);
},"size"=>filesize("$dir/$file"),
"mode"=>fileperms("$dir/$file"),
"address"=>"$dir/$file"
];
}
}function fperms($file){
return fileperms($file);
}function fpos($file,$str,$from=false){
$f=fopen($file,'r');
if($from)fseek($f,$from);
$s='';$m=0;$o=0;
while(($c=fgetc($f))!==false&&$s!=$str){
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o-1;
return false;
}function mb_fgetc($file){
$l='';$s='';
while(mb_strlen($s)<2&&!feof($file)){
$l=$s;$s=$s.fgetc($file);
}fseek($file,-1,SEEK_CUR);
return $l;
}function mb_fpos($file,$str,$from=false){
$f=fopen($file,'r');
if($from)fseek($f,$from);
$s='';$m=0;$o=0;
while(($c=mb_fgetc($f))&&$s!=$str){
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o-1;
return false;
}function fexplode($file,$str){
$f=fopen($file,'r');
$s='';$m=0;$r=[];$k='';
$p=true;
while(($c=fgetc($f))!==false){
$l=$c;
if($s==$str){
$r[]=$k;
$s='';$m=0;$k='';
}if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$k="$k$s$c";
$s='';$m=0;
}}$r[]=$k;
fclose($f);
if($str==$l||$str=='')$r[]='';
return $r;
}function is_url($file){
return filter_var($file,FILTER_VALIDATE_URL)&&fvalid($file)&&!file_exists($file);
}function fsubget($file,$from=0,$to=false){
if($to===false)$t=filesize($file);
elseif($to<0)$to=filesize($file)+$to;
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=fgetc($f))!==false&&$to!=0){
$r.=$c;
$to--;
}fclose($r);
return $r;
}function mb_fsubget($file,$from=0,$to=false){
if($to===false)$t=filesize($file);
elseif($to<0)$to=filesize($file)+$to;
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=mb_fgetc($f))&&$to!=0){
$r.=$c;
$to--;
}fclose($r);
return $r;
}function fcopy($from,$to){
$to=@fopen($to,'w');
if(!$to)return false;
$w=fwrite($to,fget($from));
return fclose($to)?$w:false;
}function freplace($file,$str,$to){
$f=fopen($file,'r');
$d=fopen("xn_log.$file",'w');
$s='';$m=0;
while(($c=fgetc($f))!==false){
if($s==$str){
fwrite($d,$to);
$s='';$m=0;
}if($str[$m]==$c){
$m++;$s="$s$c";
}else{
fwrite($d,"$s$c");
$s='';$m=0;
}}if($s==$str){
fwrite($d,$to);
$s='';$m=0;
}fclose($f);
fclose($d);
copy("xn_log.$file",$file);
return unlink("xn_log.$file");
}function fgetprogress($file,$func,$al){
$al=$al>0?$al:1;
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}$r='';
while(!feof($f)){
$r.=fread($f,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function dirfilesinfo($dir){
$size=0;
$foldercount=0;
$filecount=0;
$s=dirscan($dir);
if($dir=='/')$dir='';
foreach($s as $file){
if($file=='.'||$file=='..');
if(filetype("$dir/$file")=="dir"){
$dircount++;
$size+=filesize("$dir/$file");
$i=dirfilesinfo("$dir/$file");
$size+=$i->size;
$foldercount+=$i->folder;
$filecount+=$i->file;
}else{
$filecount++;
$size+=filesize("$dir/$file");
}
}return (object)["size"=>$size,"folder"=>$foldercount,"file"=>$filecount];
}function dirfcreate($dir,$cur='.',$in=false){
$dirs=$dir=explode('/',$dir);
unset($dirs[count($dirs)-1]);
foreach($dirs as $d){
$pt=false;
if(@file_exists("$cur/$d")&&@filetype("$cur/$d")=="file"){
if($in)$pt=fget("$cur/$d");
@unlink("$cur/$d");
}@mkdir($cur="$cur/$d");
if($in&&$pt!==false)@fput("$cur/$d/$in",$pt);
}return @fcreate("$cur/".end($dir));
}function fputprogress($file,$content,$func,$al){
$al=$al>0?$al:1;
$f=@fopen($file,'w');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}$r='';
while($content){
$r.=$th=substr($content,0,$al);
fwrite($f,$th);
$content=substr($content,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function faddprogress($file,$content,$func,$al){
$al=$al>0?$al:1;
$f=@fopen($file,'a');
if(!$f){
new XNError("Files","No such file or directory.",1);
return false;
}$r='';
while($content){
$r.=$th=substr($content,0,$al);
fwrite($f,$th);
$content=substr($content,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function sizeformater($size,$join=' ',$offset=1){
if($size<1024*$offset)return floor($size).$join.'B';
if($size<1048576*$offset)return floor($size/1024).$join.'K';
if($size<1073741824*$offset)return floor($size/1048576).$join.'M';
if($site<1099511627776*$offset)return floor($size/1073741824).$join.'G';
return floor($size/109951162776).$join.'T';
}function header_parser($headers){
$r=[];
if(is_string($headers))$headers=explode("\n",$headers);
elseif(!is_array($headers))return false;
$http=explode(' ',$headers[0]);
$r['protocol']=$http[0];
$r['http_code']=(int)$http[1];
$r['description']=$http[2];
unset($headers[0]);
foreach($headers as $header){
$header=explode(':',$header);
$headername=trim(trim($header[0],"\t"));
$headername=strtr($headername,"QWERTYUIOPASDFGHJKLZXCVBNM-","qwertyuiopasdfghjklzxcvbnm_");
unset($header[0]);
$header=trim(trim(implode(':',$header),"\t"));
$header=explode(';',$header);
if(isset($header[1])){
$eadervalue=[];
foreach($header as $k=>$hdr){
$headervalue[$k]=$hdr;
}}else $headervalue=$header[0];
$r[$headername]=$headervalue;
}return $r;
}function get_headers_parsed($url){
return header_parser(get_headers($url));
}function fcopy_implicit($from,$to,$limit=1,$sleep=0){
$from=@fopen($from,'r');
$to=@fopen($to,'w');
if(!$from||!$to)return false;
if($sleep>0)while(($r=fread($from,$limit))!==''){fwrite($to,$r);usleep($sleep);}
else while(($r=fread($from,$limit))!=='')fwrite($to,$r);
fclose($from);
fclose($to);
return true;
}function urlinclude($url){
$random=rand(0,99999999).rand(0,99999999);
$z=new thumbCode(function()use($random){
unlink("xn$random.log");
});@copy($url,"xn$random.log");
require "xn$random.log";
}function xnfprint($file,$limit=1,$sleep=0){
if(!isset($GLOBALS['-XN-']['xnprint'])){
new XNError("xnprint","one starting XNPrint");
return false;
}$file=@fopen($file,'r');
if(!$file)return false;
if($sleep>0)while(($r=fread($file,$limit))!==''){fwrite($GLOBALS['-XN-']['xnprint'],$r);usleep($sleep);}
else while(($r=fread($file,$limit))!=='')fwrite($GLOBALS['-XN-']['xnprint'],$r);
fclose($file);
return true;
}function xnprint($text,$limit=1,$sleep=0){
if(!isset($GLOBALS['-XN-']['xnprint'])){
new XNError("xnprint","one starting XNPrint");
return false;
}$from=0;$l=strlen($text)-1;
if($sleep>0)while($from<=$l){fwrite($GLOBALS['-XN-']['xnprint'],substr($text,$from,$limit));usleep($sleep);$from+=$limit;}
else while($from<=$l){fwrite($GLOBALS['-XN-']['xnprint'],substr($text,$from,$limit));$from+=$limit;}
return true;
}function xnprint_start(){
@ob_end_clean();
ob_implicit_flush(1);
$GLOBALS['-XN-']['xnprint']=fopen("php://output",'w');
$GLOBALS['-XN-']['xnprintsave']=new ThumbCode(function(){
fclose($GLOBALS['-XN-']['xnprint']);
});
}function xnecho($d){
if(!isset($GLOBALS['-XN-']['xnprint'])){
new XNError("xnprint","one starting XNPrint");
}fwrite($GLOBALS['-XN-']['xnprint'],$d);
}function get_uploaded_file($file){
$random=rand(0,999999999).rand(0,999999999);
if(!move_uploaded_file($file,"xn$random.log"))return false;
$get=fget("xn$random.log");
unlink("xn$random.log");
return $get;
}function format_to_mimetype($format){
return xndata("fromattomimetype")[$format];
}function mimetype_to_format($mimetype){
return xndata("formattomimetype")[$mimetype];
}function xnlcencode($file,$to){
$f=@fopen($file,'r');
$t=@fopen($to,'w');
if(!$f||!$t)return false;
$l='';
while(($c=fgetc($f))!==false){
$c=base2_encode($c);
$r='';
for($o=0;$o<8;$o+=2){
if($l==$c)$r="\n";
else $r.=["00"=>["X","N"][rand(0,1)],"10"=>"x","01"=>"n","11"=>" "][$c[$o].$c[$o+1]];
}$r=strrev($r);
fwrite($t,$r);
$l=$c;
}fclose($f);
fclose($t);
}function xnlcdecode($file,$to){
$f=@fopen($file,'r');
$t=@fopen($to,'w');
if(!$f||!$t)return false;
$l='';
while(($c=fgetc($f))!==false){
if($c=="\n"){
$r=$l;
fwrite($t,$r);
}else{
$r='';
$c.=fread($f,3);
$c=strrev($c);
for($o=0;$o<4;$o++){
$r.=["X"=>"00","N"=>"00","x"=>"10","n"=>"01"," "=>"11"][$c[$o]];
}$r=base2_decode($r);
$l=$r;
fwrite($t,$r);
}
}fclose($f);
fclose($t);
}function xnlcrequire($file){
$random=rand(0,999999999).rand(0,999999999);
if(!xnlcdecode($file,"xn$random.log"))return false;
$s=new ThumbCode(function()use($random){
unlink("xn$random.log");
});
require "xn$random.log";
return true;
}function xnrand_open($min,$max){
if($min>$max)var_move($min,$max);
if(!is_numeric($min)||!is_numeric($max)){
new XNError("xnrand","give number to start");
return false;
}$min=(int)$min;
$max=(int)$max;
return range($min,$max);
}function xnrand(&$xnrand){
if(!is_array($xnrand)||$xnrand==[]){
new XNError("xnprint","give xnrand handler on parameter",1);
return false;
}$rand=array_rand($xnrand);
$r=$xnrand[$rand];
unset($xnrand[$rand]);
return (int)$r;
}function xnrandopen($str){
if(is_string($str))$str=str_split($str);
elseif(is_array($str));
else return false;
return $str;
}function strhave($str,$in){
$p=strpos($str,$in);
return $p!==false&&$p!=-1;
}function strihave($str,$in){
$p=stripos($str,$in);
return $p!==false&&$p!=-1;
}function strshave($str,$in){
$p=strpos($str,$in);
return $p===0;
}function strsihave($str,$in){
$p=stripos($str,$in);
return $p===0;
}
// Time-------------------------------------
function xndateoption($date=1){
if($date==2)return -19603819800;
if($date==3)return -18262450800;
if($date==4)return -62167219200;
return 0;
}function xntimeoption($time){
return (new DateTime(null,new DateTimeZone($time)))->getOffset();
}function xntime($option=0,$unix=false){
return ($unix===false?microtime(true):$unix)+$option;
}function xndate($date="c",$option=0,$unix){
return date($date,xntime($option,$unix));
}function xndatetimeoption($time,$date=1){
return xntimeoption($time)+xndateoption($date);
}function timeformater($time,$join=' ',$offset=1){
if($time<60*$offset)return floor($time).$join."s";
if($time<3600*$offset)return floor($time/60).$join."m";
if($time<86400*$offset)return floor($time/3600).$join."h";
if($time<2592000*$offset)return floor($time/86400).$join."d";
if($time<186645600*$offset)return floor($time/2592000).$join."n";
return floor($time/186645600).$join."y";
}function ssleep($c){
while($c>0)$c--;
}
// Coding----------------------------------
function base10_encode($str){
$c=0;$r=0;
while(@$str[$c]){
$r=$r*256+ord($str[$c++]);
}return $r;
}function base10_decode($num){
$r='';
while($num>0){
$r=chr($num%256).$r;
$num=(int)($num/256);
}return $r;
}function base2_encode($text){
$l=strlen($text);$r='';
for($c=0;$c<$l;$c++){
$a=ord($text[$c]);
$r=$r.(($a>>7)&1).(($a>>6)&1).
(($a>>5)&1).(($a>>4)&1).
(($a>>3)&1).(($a>>2)&1).
(($a>>1)&1).(($a)&1);
}return $r;
}function base2_decode($text){
$l=strlen($text);$r='';$c=0;
while($c<$l){
$r=$r.chr(($text[$c++]<<7)+($text[$c++]<<6)+
($text[$c++]<<5)+($text[$c++]<<4)+
($text[$c++]<<3)+($text[$c++]<<2)+
($text[$c++]<<1)+($text[$c++]));
}return $r;
}function base64url_encode($data){
return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
}function base64url_decode($data){
return base64_decode(str_pad(strtr($data,'-_','+/'),strlen($data)%4,'=',STR_PAD_RIGHT));
}function baseconvert($text,$from,$to=false){
$fromel=mb_subsplit($from);
$frome=[];
foreach($fromel as $key=>$value){
$frome[$value]=$key;
}unset($fromel);
$fromc=count($frome);
$toe=mb_subsplit($to);
$toc=count($toe);
$texte=array_reverse(mb_subsplit($text));
$textc=count($texte);
$bs=0;
$th=1;
for($i=0;$i<$textc;$i++){
$bs=$bs+@$frome[$texte[$i]]*$th;
$th=$th*$fromc;
}$r='';
if($to===false)return "$bs";
while($bs>0){
$r=$toe[$bs%$toc].$r;
$bs=floor($bs/$toc);
}return "$r";
}function number_string_encode($str){
$c=0;
$s='';
while(isset($str[$c])){
$s.='9'.base_convert(ord($str[$c++]),10,9);
}return substr($s,1);
}function number_string_decode($str){
$c=0;
$str=explode('9',$str);
$s='';
while(isset($str[$c])){
$s.=chr(base_convert($str[$c++],9,10));
}return $s;
}
class XNJsonMath {
private $xnj;
public function __construct($xnj){
$this->xnj=$xnj;
}public function add($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)+$count);
return $xnj;
}public function rem($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)-$count);
return $xnj;
}public function div($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)/$count);
return $xnj;
}public function mul($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)*$count);
return $xnj;
}public function pow($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)**$count);
return $xnj;
}public function rect($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)%$count);
return $xnj;
}public function calc($key,$calc){
$this->xnj->set($key,XNCalc::calc($calc,['x'=>$this->xnj->value($key)]));
return $xnj;
}public function join($key,$data){
$this->xnj->set($key,$this->xnj->value($key).$data);
return $xnj;
}
}class XNJsonProMath {
private $xnj;
public function __construct($xnj){
$this->xnj=$xnj;
}public function add($key,$count=1){
$this->xnj->set($key,XNProCalc::add($this->xnj->value($key),$count));
return $xnj;
}public function rem($key,$count=1){
$this->xnj->set($key,XNProCalc::rem($this->xnj->value($key),$count));
return $xnj;
}public function mul($key,$count=1){
$this->xnj->set($key,XNProCalc::mul($this->xnj->value($key),$count));
return $xnj;
}public function div($key,$count=1){
$this->xnj->set($key,XNProCalc::div($this->xnj->value($key),$count));
return $xnj;
}public function rect($key,$count=1){
$this->xnj->set($key,XNProCalc::rect($this->xnj->value($key),$count));
return $xnj;
}public function pow($key,$count=1){
$this->xnj->set($key,XNProCalc::pow($this->xnj->value($key),$count));
return $xnj;
}public function calc($key,$calc){
$this->xnj->set($key,XNProCalc::calc($calc,['x'=>$this->xnj->value($key)]));
return $xnj;
}
}
class XNJsonString {
private $data;
public $math,$proMath;
public function __construct($data=','){
$this->data=$data;
$this->math=new XNJsonMath($this);
$this->proMath=new XNJsonProMath($this);
}public function convert($file){
fput($file,$this->data);
return new XNJsonFile($file);
}public function reset(){
$this->data=',';
return $this;
}public function get(){
return $this->data;
}public function close(){
$this->data=null;
}public function __toString(){
return $this->data;
}private function encode($data){
$type=gettype($data);
switch($type){
case "NULL":
$type=1;
$data='';
break;case "boolean":
if($data)$type=2;
else $type=3;
$data='';
break;case "integer":
$type=4;
break;case "float":
$type=5;
break;case "double":
$type=6;
break;case "string":
$type=7;
break;case "array":
case "object":
$type=8;
$data=serialize($data);
break;default:
new XNError("XNJson","invalid data type");
return false;
}$zdata=zlib_encode($data,31);
if(strlen($zdata)<strlen($data)){
$data=$zdata;
$type+=8;
}$data=base64url_encode(chr($type).$data);
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return $size.':'.$data;
}private function decode($data){
$data=explode(':',$data);
$data=end($data);
$data=base64url_decode($data);
$type=ord($data);
$data=substr($data,1);
if($type>8){
$data=zlib_decode($data);
$type-=8;
}switch($type){
case 1:
return null;
break;case 2:
return true;
break;case 3:
return false;
break;case 4:
return (int)$data;
break;case 5:
return (float)$data;
break;case 6:
return (double)$data;
break;case 7:
return (string)$data;
break;case 8:
return unserialize($data);
}
}private function elencode($key,$value){
$data="$key.$value";
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return "$size;$data";
}private function eldecode($code){
return explode('.',explode(";",$code)[1]);
}private function sizedecode($size){
return base_convert(bin2hex(base64url_decode($size)),16,10);
}public function value($key){
$key=';'.$this->encode($key).'.';
$p=strpos($this->data,$key);
if($p===false||$p==-1)return false;
$p+=strlen($key);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$size=$this->sizedecode($size);
return $this->decode(substr($this->data,$p,$size));
}public function key($value){
$value='.'.$this->encode($value).',';
$p=strpos($this->data,$value);
if($p===false||$p==-1)return false;
$key='';
while(($h=$this->data[$p--])!==':')$key=$h.$key;
return $this->decode($key);
}public function iskey($key){
$key=';'.$this->encode($key).'.';
$p=strpos($this->data,$key);
return $p!=-1&&$p!==false;
}public function isvalue($value){
$value='.'.$this->encode($value).',';
$p=strpos($this->data,$key);
return $p!=-1&&$p!==false;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}public function keys($value){
$values=[];
$data=$this->data;
$value='.'.$this->encode($value).',';
$vallen=strlen($value)-1;
while($data!=','){
$p=strpos($data,$value);
if($p===false||$p==-1)break;
$pp=$p;
$key='';
while(($h=$data[$p--])!==':')$key=$h.$key;
$data=substr($data,$pp+$vallen);
$values[]=$this->decode($key);
}return $values;
}private function replace($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el2=$this->elencode($key,$value);
$ky=';'.$key.'.';
$p=strpos($this->data,$ky)+strlen($ky);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$sizee=$size;
$size=$this->sizedecode($size);
$value=$sizee.':'.substr($this->data,$p,$size);
$el1=$this->elencode($key,$value);
$this->data=str_replace($el1,$el2,$this->data);
return $this;
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$this->data.="$el,";
return $this;
}public function set($key,$value=null){
if(self::iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function array(){
$data=explode(',',substr($this->data,1,-1));
foreach($data as &$dat){
$dat=$this->eldecode($dat);
$dat[0]=$this->decode($dat[0]);
$dat[1]=$this->decode($dat[1]);
}return $data;
}public function count(){
return count(explode(',',$this->data))-2;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}
}class XNJsonFile {
private $file;
public $math,$proMath;
public function __construct($file){
$this->file=$file;
$this->math=new XNJsonMath($this);
$this->proMath=new XNJsonProMath($this);
if(!file_exists($file))fput($file,',');
}public function convert(){
return new XNJsonString(fget($this->file));
}public function reset(){
fput($this->file,',');
return $this;
}public function get(){
return fget($this->file);
}public function close(){
$this->file=null;
}public function __toString(){
return fget($this->file);
}public function getFile(){
return $this->file;
}private function encode($data){
$type=gettype($data);
switch($type){
case "NULL":
$type=1;
$data='';
break;case "boolean":
if($data)$type=2;
else $type=3;
$data='';
break;case "integer":
$type=4;
break;case "float":
$type=5;
break;case "double":
$type=6;
break;case "string":
$type=7;
break;case "array":
case "object":
$type=8;
$data=serialize($data);
break;default:
new XNError("XNJson","invalid data type");
return false;
}$zdata=zlib_encode($data,31);
if(strlen($zdata)<strlen($data)){
$data=$zdata;
$type+=8;
}$data=base64url_encode(chr($type).$data);
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return $size.':'.$data;
}private function decode($data){
$data=explode(':',$data);
$data=end($data);
$data=base64url_decode($data);
$type=ord($data);
$data=substr($data,1);
if($type>8){
$data=zlib_decode($data);
$type-=8;
}switch($type){
case 1:
return null;
break;case 2:
return true;
break;case 3:
return false;
break;case 4:
return (int)$data;
break;case 5:
return (float)$data;
break;case 6:
return (double)$data;
break;case 7:
return (string)$data;
break;case 8:
return unserialize($data);
}
}private function elencode($key,$value){
$data="$key.$value";
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return "$size;$data";
}private function eldecode($code){
return explode('.',explode(";",$code)[1]);
}private function sizedecode($size){
return base_convert(bin2hex(base64url_decode($size)),16,10);
}public function key($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
$p--;
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=ftell($f);
fseek($f,$s+1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fseek($f,$key);
$key=fread($f,$s);
fclose($f);
return $this->decode($key);
}public function value($key){
$f=fopen($this->file,'r');
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fseek($f,$p,SEEK_CUR);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
$value=fread($f,$p);
fclose($f);
return $this->decode($value);
}public function keys($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
$keys=[];
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1){
$m=0;
$o=1;
$p='';
$s='';
$keys[]=$this->decode($key);
}elseif($value[$m]==$h){
$m++;
}else{
$m=0;
@fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=fread($f,$s);
fseek($f,1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}fclose($f);
return $keys;
}public function iskey($key){
$f=fopen($this->file,'r');
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fseek($f,$p,SEEK_CUR);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function isvalue($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
fseek($f,$s+1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}private function replace($key,$value){
$key=$this->encode($key).'.';
$value=$this->encode($value).',';
$el=$this->elencode($key,$value);
$f=fopen($this->file,'r');
$random=rand(0,999999999).rand(0,999999999);
$t=fopen("xn$random.$this->file.log",'w');
fwrite($t,',');
fseek($f,1);
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1){
$m=0;
fwrite($t,$this->elencode(substr($key,0,-1),substr($value,0,-1)));
fseek($f,$p,SEEK_CUR);
break;
}elseif($key[$m]==$h){
$m++;
}else{
$o=false;
fwrite($t,self::elencode(...explode('.',($m>0?substr($key,0,$m):'').$h.fread($f,$p))));
$m=0;
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}
$g=ftell($f);
fseek($f,0,SEEK_END);
$u=ftell($f)-$g;
if($u>0){
fseek($f,$g);
fwrite($t,fread($f,$u));
}fclose($f);
fclose($t);
copy("xn$random.$this->file.log",$this->file);
unlink("xn$random.$this->file.log");
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$f=fopen($this->file,'a');
fwrite($f,"$el,");
fclose($f);
}public function set($key,$value=null){
if($this->iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function array(){
$f=fopen($this->file,'r');
fseek($f,1);
$arr=[];
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
$arr[]=$ar;
fseek($f,1,SEEK_CUR);
$p='';
}else{
$p.=$h;
}}return $arr;
}public function count(){
$f=fopen($this->file,'r');
fseek($f,1);
$c=0;
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
fseek($f,$p+1,SEEK_CUR);
$c++;
$p='';
}else{
$p.=$h;
}}return $c;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}
}class XNJsonURL {
private $file;
public function __construct($file){
$this->file=$file;
}public function convert(){
return new XNJsonString(fget($this->file));
}public function reset(){
fput($this->file,',');
return $this;
}public function get(){
return fget($this->file);
}public function close(){
$this->file=null;
}public function __toString(){
return fget($this->file);
}public function getURL(){
return $this->file;
}private function encode($data){
$type=gettype($data);
switch($type){
case "NULL":
$type=1;
$data='';
break;case "boolean":
if($data)$type=2;
else $type=3;
$data='';
break;case "integer":
$type=4;
break;case "float":
$type=5;
break;case "double":
$type=6;
break;case "string":
$type=7;
break;case "array":
case "object":
$type=8;
$data=serialize($data);
break;default:
new XNError("XNJson","invalid data type");
return false;
}$zdata=zlib_encode($data,31);
if(strlen($zdata)<strlen($data)){
$data=$zdata;
$type+=8;
}$data=base64url_encode(chr($type).$data);
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return $size.':'.$data;
}private function decode($data){
$data=explode(':',$data);
$data=end($data);
$data=base64url_decode($data);
$type=ord($data);
$data=substr($data,1);
if($type>8){
$data=zlib_decode($data);
$type-=8;
}switch($type){
case 1:
return null;
break;case 2:
return true;
break;case 3:
return false;
break;case 4:
return (int)$data;
break;case 5:
return (float)$data;
break;case 6:
return (double)$data;
break;case 7:
return (string)$data;
break;case 8:
return unserialize($data);
}
}private function elencode($key,$value){
$data="$key.$value";
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return "$size;$data";
}private function eldecode($code){
return explode('.',explode(";",$code)[1]);
}private function sizedecode($size){
return base_convert(bin2hex(base64url_decode($size)),16,10);
}public function key($value){
$f=fopen($this->file,'r');
fgetc($f);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fread($f,$p);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
$p--;
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=fread($f,$s);
fgetc($f);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return $this->decode($key);
}public function value($key){
$f=fopen($this->file,'r');
fgetc($f);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fread($f,$p);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
$value=fread($f,$p);
fclose($f);
return $this->decode($value);
}public function keys($value){
$f=fopen($this->file,'r');
fgetc($f);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
$keys=[];
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1){
$m=0;
$o=1;
$p='';
$s='';
$keys[]=$this->decode($key);
}elseif($value[$m]==$h){
$m++;
}else{
$m=0;
@fread($f,$p);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=fread($f,$s);
fgetc($f);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}fclose($f);
return $keys;
}public function iskey($key){
$f=fopen($this->file,'r');
fgetc($f);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fread($f,$p);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function isvalue($value){
$f=fopen($this->file,'r');
fgetc($f);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fread($f,$p);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
fread($f,$s+1);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}public function array(){
$f=fopen($this->file,'r');
fgetc($f);
$arr=[];
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
$arr[]=$ar;
fgetc($f);
$p='';
}else{
$p.=$h;
}}return $arr;
}public function count(){
$f=fopen($this->file,'r');
fgetc($f);
$c=0;
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
fread($f,$p+1);
$c++;
$p='';
}else{
$p.=$h;
}}return $c;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}
}function XNJson($j=',',$file=false){
if(is_array($j)){
if($file&&$file!='.'&&$file!='..')$xnj=new XNJsonFile($file);
else $xnj=new XNJsonString();
$xnj->list($j);
return $xnj;
}if(!$file&&$j!='.'&&$j!='..'&&file_exists($j))return new XNJsonFile($j);
if($file){
if(strpos($j,'://')>0)return new XNJsonURL($j);
return new XNJsonFile($j);
}return new XNJsonString($j);
}
// Calc-------------------------------------
class XNProCalc {
// consts variables
static function PI($l=-1){
$pi=xndata("pi");
if($l<0)return $pi;
if($l==0)return substr($pi,1);
return substr($pi,0,$l+2);
}static function PHI($l=-1){
$phi=xndata("phi");
if($l<0)return $phi;
if($l==0)return substr($phi,1);
return substr($phi,0,$l+2);
}
// system functions
static function _check($a){
if(!is_numeric($a)){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","invalid number \"$a\".");
return false;
}return true;
}static function _view($a){
if($a[0]=='-')return true;
return false;
}static function abs($a){
if($a[0]=='-'||$a[0]=='+')return substr($a,1);
return $a;
}static function _change($a){
if($a==0)return '0';
if($a[0]=='-')return substr($a,1);
if($a[0]=='+')return '-'.substr($a,1);
return '-'.$a;
}static function _get0($a){
$c=0;$k=0;
while(@$a[$c++]==='0')$k++;
return substr($a,$k);
}static function _get1($a){
$c=strlen($a)-1;$k=0;
while(@$a[$c--]==='0')$k++;
return substr($a,0,strlen($a)-$k);
}static function _get2($a){
$a=self::_mo($a);
$a[1]=isset($a[1])?$a[1]:'0';
$a[0]=self::_get0($a[0]);
$a[1]=self::_get1($a[1]);
if($a[0]&&$a[1])return "{$a[0]}.{$a[1]}";
if($a[1])return "0.{$a[1]}";
if($a[0])return "{$a[0]}";
return "0";
}static function _get3($a){
if(self::_view($a))return '-'.self::_get2(self::abs($a));
return self::_get2(self::abs($a));
}static function _get($a){
if(!self::_check($a))return false;
return self::_get3($a);
}static function _set0($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return str_repeat('0',$l).$a;
}static function _set1($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return $a.str_repeat('0',$l);
}static function _set2($a,$b){
$a=self::_mo($a);
$b=self::_mo($b);
if(!isset($a[1])&&isset($b[1])){
$a[1]='0';
}if(isset($a[1]))$a[1]=self::_set1($a[1],@$b[1]);
$a[0]=self::_set0($a[0],$b[0]);
if(!isset($a[1]))return "{$a[0]}";
return "{$a[0]}.{$a[1]}";
}static function _set3($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_set2(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
                                      return     self::_set2(self::abs($a),self::abs($b));
}static function _set($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set3($a,$b);
}static function _full($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set(self::_get($a),self::_get($b));
}static function _setfull(&$a,&$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
$a=self::_get($a);
$b=self::_get($b);
$a=self::_set($a,$b);
$b=self::_set($b,$a);
}static function _mo($a){
return explode('.',$a);
}static function _lm($a){
return strpos($a,'.');
}static function _im($a){
$p=self::_lm($a);
return $p!==false&&$p!=-1;
}static function _nm($a){
return str_replace('.','',$a);
}static function _st($a,$b){
if(!isset($a[$b])||$b==0)return $a;
return substr_replace($a,'.',$b,0);
}static function _iz($a){
$a=$a[strlen($a)-1];
return $a=='0'||$a=='2'||$a=='4'||$a=='6'||$a=='8';
}static function _if($a){
$a=$a[strlen($a)-1];
return $a=='1'||$a=='3'||$a=='5'||$a=='7'||$a=='9';
}static function _so($a,$b){
$l=strlen($a)%$b;
if($l==0)return $a;
else return str_repeat('0',$b-$l).$a;
}static function _pl($a){
$l='0';
while($a!=$l){
$l=$a;
$a=str_replace(['--','-+','+-','++'],['+','-','-','+'],$a);
}return $a;
}
// retry calc functions
static function _powTen0($a,$b){
$p=self::_lm($a);
$i=$p===false||$p==-1;
$a=self::_nm($a);
$l=strlen($a);
if($i)$s=strlen($a)+$b;
else $s=$p+$b;
if($s==$l)return $a;
if($s>$l)return $a.str_repeat('0',$s-$l);
if($s==0)return "0.$a";
if($s<0)return "0.".str_repeat('0',abs($s)).$a;
return substr_replace($a,".",$s,0);
}static function _powTen1($a,$b){
if(self::_view($a))return '-'.self::_powTen0(self::abs($a),$b);
return self::_powTen0(self::abs($a),$b);
}static function powTen($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_get(self::_powTen1($a,$b));
}static function _mulTwo0($a){
$a=subsplit($a,13);
$c=count($a)-1;
while($c>=0){
$a[$c]*=2;
$k=0;
while(@$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _mulTwo1($a){
$a=self::_mo($a);
$a[0]=self::_so($a[0],13);
$a[0]=self::_mulTwo0("0000000000000{$a[0]}");
if(isset($a[1])){
$l=strlen($a[1]);
$a[1]=self::_so($a[1],13);
$a[1]=self::_mulTwo0("0000000000000{$a[1]}");
$a[2]=substr($a[1],0,-$l);
$a[1]=substr($a[1],-$l);
if($a[2]>0)$a[0]=self::_add0("0000000000000{$a[0]}","0000000000000".str_repeat('0',strlen($a[0])-1).'1');
return "{$a[0]}.{$a[1]}";
}return $a[0];
}static function _mulTwo2($a){
if(self::_view($a))return '-'.self::_mulTwo1(self::abs($a));
return self::_mulTwo1(self::abs($a));
}static function mulTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_mulTwo2(self::_get3($a)));
}static function _divTwo0($a){
$s='';
$c=0;
$k=false;
while(isset($a[$c])){
$h=substr($a,$c,14);
$b=floor($h/2);
$b=$k?$b+50000000000000:$b;
$s.=self::_so($b,14);
if($h%2==1)$k=true;
$c+=14;
}if($k)$s.='5';
return $s;
}static function _divTwo1($a){
$p=self::_lm($a);
$a=self::_nm($a);
if($p===false||$p==-1)$p=strlen($a);
$l=strlen($a);
$a=self::_so($a,14);
$p+=strlen($a)-$l;
$a=self::_divTwo0($a);
return self::_st($a,$p);
}static function _divTwo2($a){
if(self::_view($a))return '-'.self::_divTwo1(self::abs($a));
return self::_divTwo1(self::abs($a));
}static function divTwo($a){
return self::_get(self::_divTwo2(self::_get($a)));
}static function _powTwo0($a){
$a=subsplit($a,1);
$x=false;
$c=$d=count($a)-1;
$k=0;
while($c>=0){
$y='';
$e=$d;
$s=0;
while($e>=0){
$t=$a[$c]*$a[$e]+$s;
$s=floor($t/10);
$t-=$s*10;
$y=$t.$y;
$e--;
}$c--;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
$k++;
}return $x;
}static function _powTwo1($a){
$p=self::_lm($a);
if(!$p)return self::_powTwo0($a);
$p=strlen($a)-$p-1;
$p*=2;
$a=self::_nm($a);
$a='0'.self::_powTwo0($a);
return self::_st($a,strlen($a)-$p);
}static function _powTwo2($a){
return self::_powTwo1(self::abs($a));
}static function powTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_powTwo2(self::_get3($a)));
}
// set functions
static function floor($a){
if(!self::_check($a))return false;
return explode('.',"$a")[0];
}static function ceil($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])?self::add($a[0],'1'):$a[0];
}static function round($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])&&$a[1][0]>=5?self::add($a[0],'1'):$a[0];
}
// calc functions
static function _add0($a,$b){
$a=subsplit("0000000000000$a",13);
$b=subsplit("0000000000000$b",13);
$c=count($a)-1;
while($c>=0){
$a[$c]+=$b[$c];
$k=0;
while(isset($a[$c-$k])&&$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _add1($a,$b){
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_add0($a,$b),$p);
return self::_add0($a,$b);
}static function _add2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return     self::_rem1(self::abs($b),self::abs($a));
if(!self::_view($a)&& self::_view($b))return     self::_rem1(self::abs($a),self::abs($b));
                                      return     self::_add1(self::abs($a),self::abs($b));
}static function add($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==0?$b:
   $b==0?$a:
   self::_add2($a,$b);
return self::_get3($r);
}public function _rem0($a,$b){
$a=subsplit($a,13);
$b=subsplit($b,13);
$c=count($a)-1;
while($c>=0){
$a[$c]-=$b[$c];
$k=0;
while(isset($a[$c-$k])&&$a[$c-$k]<0){
$a[$c-$k-1]-=1;
$a[$c-$k]+=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _rem1($a,$b){
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_add0($a,$b),$p);
return self::_rem0($a,$b);
}static function _rem2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_rem1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_add1(self::abs($a),self::abs($b));
                                      return     self::_rem1(self::abs($a),self::abs($b));
}static function _rem3($a,$b){
if($a<$b){
return '-'.self::_rem2($b,$a);
}return self::_rem2($a,$b);
}static function rem($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==0?self::_change($b):
   $b==0?$a:
   self::_rem3($a,$b);
return self::_pl(self::_get3($r));
}static function _mul0($a,$b){
$a=subsplit($a,1);
$b=subsplit($b,1);
$x=false;
$c=$d=count($a)-1;
$k=0;
while($c>=0){
$y='';
$e=$d;
$s=0;
while($e>=0){
$t=$a[$c]*$b[$e]+$s;
$s=floor($t/10);
$t-=$s*10;
$y=$t.$y;
$e--;
}$c--;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
$k++;
}return $x;
}static function _mul1($a,$b){
$ap=self::_lm($a);
$bp=self::_lm($b);
if(!$ap)return self::_mul0($a,$b);
$ap=strlen($a)-$ap-1;
$bp=strlen($b)-$bp-1;
$p=$ap+$bp;
$a=self::_nm($a);
$b=self::_nm($b);
$a='0'.self::_mul0($a,$b);
return self::_st($a,strlen($a)-$p);
}static function _mul2($a,$b){
if( self::_view($a)&& self::_view($b))return     self::_mul1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
                                      return     self::_mul1(self::abs($a),self::abs($b));
}static function mul($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$b==0?0:
   $b==1?$a:
   $b==2?self::mulTwo($a):
   $a==2?self::mulTwo($b):
   $a==0?0:
   $a==1?$b:
   self::_mul2($a,$b);
return self::_get3($r);
}static function _rand0($a){
$rand="0.";
$b=floor($a/9);
for($c=0;$c<$b;$c++){
$rand.=self::_so(rand(0,999999999),9);
}if($a%9==0)return $rand;
return $rand.self::_so(rand(0,str_repeat('9',$a%9)),$a%9);
}static function _rand1($a,$b){
$c=self::rem($a,$b);
$d=self::_rand0(strlen($a));
return self::add(self::floor(self::mul(self::add($c,'1'),$d)),$b);
}static function _rand2($a,$b){
$p=self::_lm($a);
if(!$p)return self::_rand1($a,$b);
$p=strlen($a)-$p-1;
$a=self::_nm($a);
$b=self::_nm($b);
$a='0'.self::_rand1($a,$b);
return self::_st($a,strlen($a)-$p);
}static function _rand3($b,$a){
if($a>$b)return self::_rand2($a,$b);
return self::_rand2($b,$a);
}static function _rand4($a,$b){
if(self::_view($a)&&self::_view($b))return '-'.self::_rand3(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$a));
}if(self::_view($a)&&!self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$b));
}return self::_rand3(self::abs($a),self::abs($b));
}static function rand($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==$b?$a:
   self::_rand4($a,$b);
return self::_get($r);
}static function _div0($a,$b){
if($b>$a)return 0;
if(($c=self::mulTwo($b))>$a)return 1;
if(self::mul($b,'3')>$a)return 2;
if(($c=self::mulTwo($c))>$a)return 3;
if(self::mul($b,'5')>$a)return 4;
if(self::mul($b,'6')>$a)return 5;
if(self::mul($b,'7')>$a)return 6;
if(self::mulTwo($c)>$a)return 7;
if(self::mul($b,'9')>$a)return 8;
                        return 9;
}static function _div1($a,$b,$o=0){
$a=subsplit($a,1);
$p=$r=$i=$d='0';
$c=count($a);
while($i<$c){
$d.=$a[$i];
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
$i++;
}if($d==0||$o<=0)return $r;
$r.='.';
while($d>0&&$o>0){
$d.='0';
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
$o--;
}return $r;
}static function _div2($a,$b,$c=0){
$a=self::_nm($a);
$b=self::_nm($b);
if($c<0)$c=0;
return self::_div1($a,$b,$c);
}static function _div3($a,$b,$c=0){
if( self::_view($a)&& self::_view($b))return     self::_div2(self::abs($a),self::abs($b),$c);
if( self::_view($a)&&!self::_view($b))return '-'.self::_div2(self::abs($a),self::abs($b),$c);
if(!self::_view($a)&& self::_view($b))return '-'.self::_div2(self::abs($a),self::abs($b),$c);
                                      return     self::_div2(self::abs($a),self::abs($b),$c);
}static function div($a,$b,$c=0){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($b==0){
new XNError("XNProCalc","not can div by Ziro");
return false;
}$r=$a==0?0:
    $a==$b?1:
    $b==1?$a:
    self::_div3($a,$b,$c);
return self::_get2($r);
}static function _rest0($a,$b){
$a=subsplit($a,1);
$p=$r=$i=$d='0';
$c=count($a);
while($i<$c){
$d.=$a[$i];
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
$i++;
}return $d;
}static function _rest1($a,$b){
$a=self::_nm($a);
$b=self::_nm($b);
return self::_rest0($a,$b);
}static function _rest2($a,$b){
if(self::_view($a))return '-'.self::_rest1(self::abs($a),self::abs($b));
                   return     self::_rest1(self::abs($a),self::abs($b));
}static function rest($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($b==0){
new XNError("XNProCalc","not can div by Ziro");
return false;
}$r=$a==0?0:
    $b==1?0:
    $a==$b?0:
    self::_rest2($a,$b);
return self::_get($r);
}static function fact($a){
if(!self::_check($a))return false;
$r='1';
while($a>0){
$r=self::mul($r,$a);
$a=self::rem($a,'1');
}return $r;
}
// run functions
static function fromNumberString($a='0'){
if(!self::_check($a))return false;
return $a*1;
}static function toNumberString($a=0){
if("$a"=="INF"){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","this number is NAN");
return false;
}if("$a"=="NAN"){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","this number is NAN");
return false;
}$a=explode('E',$a);
if(!isset($a[1]))return "{$a[0]}";
$a=self::powTen($a[0],$a[1]);
return $a;
}
}

class XNCalc {
// run functions
static function calc($c){
$c=str_replace([' ',"\n",'×','÷'],['','','*','/'],$c);
// brackets
$c=preg_replace_callback('/([0-9\)\]])([a-zA-Z\(\[])/',function($a){
return $a[1].'*'.$a[2];
},$c);
$c=preg_replace("/([^a-zA-Z0-9])(\[\]|\[\)|\(\]|\(\))/","$1",$c);
$l='';
while($c!=$l){
$l=$c;
$c=preg_replace_callback('/([^a-zA-Z0-9])\(([^\(\)]+)\)/',function($a){
return $a[1].self::calc($a[2]);
},$c);
$c=preg_replace_callback('/\[([^\[\]]+)\]/',function($a){
return floor(self::calc($a[1]));
},$c);
$c=preg_replace_callback('/fact\(([^\(\)])\)|([0-9]+(\.[0-9]+){0,1})\!/',function($a){
return fact(end($a));
},$c);
}
return $c;
}
}
function fact($n){
$n=(int)$n;
$r=1;
if($n>=171)return INF;
while($n>0){
$r*=$n--;
}return $r;
}function strprogress($p1,$p2,$c,$x,$n,$o=''){
if($n>$x)var_move($x,$n);
$p=(int)($n/$x*$c);
if($p==$c)return str_repeat($p1,$p).$o;
if($p==0)return $o.str_repeat($p2,$c);
return str_repeat($p1,$p).$o.str_repeat($p2,$c-$p);
}
// CFile----------------------------------------------
class XNColor {
static function init($color=0){
return [$color&0xff,($color>>8)&0xff,($color>>16)&0xff,($color>>24)&0xff];
}static function read($color=0){
return ["red"=>$color&0xff,"green"=>($color>>8)&0xff,"blue"=>($color>>16)&0xff,"alpha"=>($color>>24)&0xff];
}static function par($a=0,$b=false,$c=false,$d=false){
if(is_array($a)){
$b=isset($a[1])?$a[1]:0;
$c=isset($a[2])?$a[2]:0;
$d=isset($a[3])?$a[3]:0;
$a=isset($a[0])?$a[0]:0;
}elseif($a&&gettype($a)=="string"&&$b===false&&$c===false&&$d===false){
$r=@[][$a];
if($r===null){
$l=strlen($a);
if($l%2==1&&$l!=3){
$a=substr($a,1);
$l--;
}if($l==3)$a=$a[0].$a[0].$a[1].$a[1].$a[2].$a[2];
elseif($l==4)$a=$a[0].$a[0].$a[1].$a[1].$a[2].$a[2].$a[3].$a[3];
elseif($l!=6&&$l!=8){
new XNError("XNColor","Invalid hex color or color name.",1);
return false;
}$d=isset($a[6])?hexdec($a[6].$a[7]):0;
$b=hexdec($a[2].$a[3]);
$c=hexdec($a[4].$a[5]);
$a=hexdec($a[0].$a[1]);
return [$a,$b,$c,$d];
}return $r;
}if(!is_numeric($a)){
new XNError("XNColor","Parameters is not number.",1);
return false;
}$a=$a&&$a>-1?$a%256:0;
$b=$b&&$b>-1?$b%256:0;
$c=$c&&$c>-1?$c%256:0;
$d=$d&&$d>-1?$d%256:0;
return [$a,$b,$c,$d];
}static function get($a=0,$b=false,$c=false,$d=false){
$color=self::par($a,$b,$c,$d);
if($color===false)return false;
return ($color[0]+($color[1]<<8)+($color[2]<<16)+($color[3]<<24));
}static function hex($a=0,$b=false,$c=false,$d=false,$tag=true){
$color=self::par($a,$b,$c,$d);
if($color===false)return false;
$last=[$color[0],$color[1],$color[2],$color[3]];
$color[0]=dechex($color[0]);
$color[1]=dechex($color[1]);
$color[2]=dechex($color[2]);
$color[3]=$color[3]?dechex($color[3]):false;
$color[0]=$last[0]<10?'0'.$color[0]:$color[0];
$color[1]=$last[1]<10?'0'.$color[1]:$color[1];
$color[2]=$last[2]<10?'0'.$color[2]:$color[2];
$color[3]=$color[3]?($last[3]<10?'0'.$color[3]:$color[3]):false;
if(!$color[3]){
if($color[0][0]==$color[0][1]&&$color[1][0]==$color[1][1]&&$color[2][0]==$color[2][1])
return ($tag?"#":'').$color[0][0].$color[1][0].$color[2][0];
return ($tag?"#":'').$color[0].$color[1].$color[2];
}else{
if($color[0][0]==$color[0][1]&&$color[1][0]==$color[1][1]&&$color[2][0]==$color[2][1]&&$color[3][0]==$color[3][1])
return ($tag?"#":'').$color[0][0].$color[1][0].$color[2][0].$color[3][0];
return ($tag?"#":'').$color[0].$color[1].$color[2].$color[3];
}
}static function fromXYBri($x,$y,$br){
$_x=($x*$br)/$y;
$_y=$br;
$_z=((1-$x-$y)*$br)/$y;
$r=$_x*3.2406 +$_y*-1.5372+$_z*-0.4986;
$g=$_x*-0.9689+$_y*1.8758 +$_z*0.0415 ;
$b=$_x*0.0557 +$_y*-0.2040+$_z*1.0570 ;
$r=$r>0.0031308?1.055*pow($r,1/2.4)-0.055:12.92*$r;
$g=$g>0.0031308?1.055*pow($g,1/2.4)-0.055:12.92*$g;
$b=$b>0.0031308?1.055*pow($b,1/2.4)-0.055:12.92*$b;
$r=$r>0?round($r*255):0;
$g=$g>0?round($g*255):0;
$b=$b>0?round($b*255):0;
return ["red"=>$r,"green"=>$g,"blue"=>$b];
}static function toHsvInt($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$min=min($rgb);
$max=max($rgb);
$hsv=['hue'=>0,'sat'=>0,'val'=>$max];
if($max==0)return $hsv;
$hsv['sat']=round(255*($max-$min)/$hsv['val']);
if($hsv['sat']==0){
$hsv['hue']=0;
return $hsv;
}$hsv['hue']=$max==$rgb['red']?round(0+43*($rgb['green']-$rgb['blue'])/($max-$min)):
($max==$rgb['green']?round(171+43*($rgb['red']-$rgb['green'])/($max-$min)):
round(171+43*($rgb['red']-$rgb['green'])/($max-$min)));
if($hsv['hue']<0)$hsv['hue']+=255;
return $hsv;
}static function toHsvFloat($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$min=min($rgb);
$max=max($rgb);
$hsv=['hue'=>0,'sat'=>0,'val'=>$max];
if($hsv['val']==0)return $hsv;
$rgb['red']/=$hsv['val'];
$rgb['green']/=$hsv['val'];
$rgb['blue']/=$hsv['val'];
$min=min($rgb);
$max=max($rgb);
$hsv['sat']=$max-$min;
if($hsv['sat']==0){
$hsv['hue']=0;
return $hsv;
}$rgb['red'] =($rgb['red']  -$min)/($max-$min);
$rgb['green']=($rgb['green']-$min)/($max-$min);
$rgb['blue'] =($rgb['blue'] -$min)/($max-$min);
$min=min($rgb);
$max=max($rgb);
if($max==$rgb['red']){
$hsv['hue']=0.0+60*($rgb['green']-$rgb['blue']);
if($hsv['hue']<0){
$hsv['hue']+=360;
}}else $hsv['hue']=$max==$rgb['green']?120+(60*($rgb['blue']-$rgb['red'])):
240+(60*($rgb['red']-$rgb['green']));
return $hsv;
}static function toXYZ($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$rgb=array_map(function($i){
return $i/255;
},$rgb);
$rgb=array_map(function($i){
return $i>0.04045?pow((($i+0.055)/1.055)*100,2.4):$item/12.92*100;
},$rgb);
$xyz=[
'x'=>($rgb['red']*0.4124)+($rgb['green']*0.3576)+($rgb['blue']*0.1805),
'y'=>($rgb['red']*0.2126)+($rgb['green']*0.7152)+($rgb['blue']*0.0722),
'z'=>($rgb['red']*0.0193)+($rgb['green']*0.1192)+($rgb['blue']*0.9505)
];return $xyz;
}static function toLabCie($a=0,$b=false,$c=false) {
$xyz=$this->toXYZ($a,$b,$c);
if($xyz===false)return false;
$xyz['x']/=95.047;
$xyz['y']/=100;
$xyz['z']/=108.883;
$xyz=array_map(function($item){
if($item>0.008856){
return pow($item,1/3);
}else{
return (7.787*$item)+(16/116);
}},$xyz);
$lab=[
'l'=>(116*$xyz['y'])-16,
'a'=>500*($xyz['x']-$xyz['y']),
'b'=>200*($xyz['y']-$xyz['z'])
];return $lab;
}static function toXYBri($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$r=$rgb['red'];
$g=$rgb['green'];
$b=$rgb['blue'];
$r=$r/255;
$g=$g/255;
$b=$b/255;
if($r<0||$r>1||$g<0||$g>1||$b<0||$b>1){
new XNError("XNColor XYBri","Invalid RGB array. [{$r},{$b},{$g}]");
}$rt=($r>0.04045)?pow(($r+0.055)/(1.0+0.055),2.4):($r/12.92);
$gt =($g>0.04045)?pow(($g+0.055)/(1.0+0.055),2.4):($g/12.92);
$bt =($b>0.04045)?pow(($b+0.055)/(1.0+0.055),2.4):($b/12.92);
$cie_x=$rt*0.649926 +$gt*0.103455+$bt*0.197109;
$cie_y=$rt*0.234327 +$gt*0.743075+$bt*0.022598;
$cie_z=$rt*0.0000000+$gt*0.053077+$bt*1.035763;
if($cie_x+$cie_y+$cie_z==0){
$hue_x=0.1;
$hue_y=0.1;
}else{
$hue_x=$cie_x/($cie_x+$cie_y+$cie_z);
$hue_y=$cie_y/($cie_x+$cie_y+$cie_z);
}return ['x'=>$hue_x,'y'=>$hue_y,'bri'=>$cie_y];
}static function average($from,$to=false){
$from=self::init($from);
if(!$to){
return ($from[0]+$from[1]+$from[2])/3;
}$to=self::init($to);
$from[0]=($from[0]+$to[0])/2;
$from[1]=($from[1]+$to[1])/2;
$from[2]=($from[2]+$to[2])/2;
$from[3]=($from[3]+$to[3])/2;
return $from;
}static function averageAll($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2)/3;
return [$av,$av,$av];
}static function averageAllAlpha($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2+($from[3]+$to[3]))/4;
return [$av,$av,$av,$av];
}static function toBW($color){
$color=self::init($color);
return 16777215*(int)(($color[0]+$color[1]+$color[2])/3>127.5);
}static function fromname($color){
return base_convert(substr(xndata("colorsname/name"),1),16,10);
}static function getname($color){

}
}


class XNImage {
private $headers=[];
public $pixels=[],$info=[];
const HEADER_PNG="\x89\x50\x4e\x47\x0d\x0a\x1a\x0a";
public function __construct($data=''){
$this->color=new XNColor;

}private function _clone($headers,$pixels,$info){
$this->headers=$headers;
$this->pixels=$pixels;
$this->info=$info;
}public function clone(){
$im=new XNImage;
$im->_clone($this->headers,$this->pixels,$this->info);
return $im;
}public function __clone(){
$im=new XNImage;
$im->_clone($this->headers,$this->pixels,$this->info);
return $im;
}public function serialize(){
$im=new XNImage;
unset($im->color);
$im->headers=$this->headers;
$im->pixels=$this->pixels;
$im->info=$this->info;
return serialize($im);
}static function unserialize($str){
$im=new XNImage;
$str=unserialize($str);
$im->headers=$str->headers;
$im->pixels=$str->pixels;
$im->info=$str->info;
return $im;
}public function reset(){
$this->headers=[];
$this->pixels=[];
$this->info=[];
}public function close(){
$this->color=null;
$this->headers=null;
$this->pixels=null;
$this->info=null;
}public function __destruct(){
$this->color=null;
$this->headers=null;
$this->pixels=null;
$this->info=null;
}public function frompng($png){
$pos=0;
if(isset($png[7])&&substr($png,0,8)==self::HEADER_PNG){
$pos=8;
}elseif(file_exists($png)){
return $this->frompng(file_get_contents($png));
}else{
new XNError("XNImage","invalid png image");
return false;
}$htitle='';
while($htitle!="IEND"){
$hsize=base10_encode(substr($png,$pos,4));
$pos+=4;
$htitle=substr($png,$pos,4);
$pos+=4;
$hcontent=substr($png,$pos,$hsize);
$pos+=$hsize;
$hcrc=substr($png,$pos,4);
$pos+=4;
if(!$htitle){
new XNError("XNImage","invalid png image");
return false;
}if(!isset($this->headers[$htitle]))$this->headers[$htitle]=["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc];
elseif(is_string($this->headers[$htitle]))$this->headers[$htitle]=[
$this->headers[$htitle],
["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc]
];else $this->headers[$htitle][]=["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc];
}if(!isset($this->headers['IDAT'])||!isset($this->headers['IHDR'])){
new XNError("XNImage","invalid png image");
return false;
}$this->info['width']=base10_encode(substr($this->headers['IHDR']['content'],0,4));
$this->info['height']=base10_encode(substr($this->headers['IHDR']['content'],4,4));
$this->info['depth']=ord($this->headers['IHDR']['content'][8]);
$this->info['color']=ord($this->headers['IHDR']['content'][9]);
$this->info['compression']=ord($this->headers['IHDR']['content'][10]);
$this->info['filter']=ord($this->headers['IHDR']['content'][11]);
$this->info['interlace']=ord($this->headers['IHDR']['content'][12]);
$pixels=$this->headers['IDAT']['content'];
$pixels=zlib_decode($pixels);
$pos=0;
$x=-1;$y=0;
while(@$pixels[$pos+3]){
$x++;
if($x+1>$this->info['width']){
$x=0;$y++;
}$this->pixels[$y][$x]=base10_encode(substr($pixels,$pos,4));
$pos+=4;
}

return $this;
}

}
// API
function clockanalogimage($req=[],$rs=false){
$size=ifstr($req['size'],512);
$borderwidth=ifstr($req['borderwidth'],3);
$bordercolor=ifstr($req['bordercolor'],'000');
$numberspace=ifstr($req['numberspace'],76);
$line1space=ifstr($req['line1space'],98);
$line1length=ifstr($req['line1length'],10);
$line1width=ifstr($req['line1width'],1);
$line1color=ifstr($req['line1color'],'000');
$line1type=ifstr($req['line1type'],3);
$line2space=ifstr($req['line2space'],98);
$line2length=ifstr($req['line2length'],10);
$line2width=ifstr($req['line2width'],1);
$line2color=ifstr($req['line2color'],'000');
$line2type=ifstr($req['line2type'],3);
$line3space=ifstr($req['line3space'],98);
$line3length=ifstr($req['line3length'],10);
$line3width=ifstr($req['line3width'],1);
$line3color=ifstr($req['line3color'],'000');
$line3type=ifstr($req['line3type'],3);
$numbersize=ifstr($req['numbersize'],20);
$numbertype=ifstr($req['numbertype'],1);
$hourcolor=ifstr($req['hourcolor'],'000');
$mincolor=ifstr($req['mincolor'],'000');
$seccolor=ifstr($req['seccolor'],'f00');
$hourlength=ifstr($req['hourlength'],45);
$minlength=ifstr($req['minlength'],70);
$seclength=ifstr($req['seclength'],77);
$hourwidth=ifstr($req['hourwidth'],5);
$minwidth=ifstr($req['minwidth'],5);
$secwidth=ifstr($req['secwidth'],1);
$hourtype=ifstr($req['hourtype'],3);
$mintype=ifstr($req['mintype'],3);
$sectype=ifstr($req['sectype'],3);
$hourcenter=ifstr($req['hourcenter'],0);
$mincenter=ifstr($req['mincenter'],5);
$seccenter=ifstr($req['seccenter'],3);
$colorin=ifstr($req['colorin'],'fff');
$colorout=ifstr($req['colorout'],'fff');
$circlecolor=ifstr($req['circlecolor'],'false');
$circlewidth=ifstr($req['circlewidth'],3);
$circlespace=ifstr($req['circlespace'],60);
$circle=ifstr($circlecolor=='false','',"/hcc$circle/hcw$circlewidth/hcd$circlespace");
$shadow=ifstr($req['shadow'],'/hwc'.$req['shadow'],'');
$hide36912=ifstr(isset($req['hide3,6,9,12']),'/fav0','');
$hidenumbers=ifstr(isset($req['hidenumbers']),'/fiv0','');
$numbercolor=ifstr($req['numbercolor'],'000');
$numberfont=ifstr($req['numberfont'],1);
$get="https://www.timeanddate.com/clocks/onlyforusebyconfiguration.php/i6554451/n246/szw$size/".
"szh$size/hoc000/hbw$borderwidth/hfceee/cf100/hncccc/fas$numbersize/fnu$numbertype/fdi$numberspace/".
"mqc$line1color/mql$line1length/mqw$line1width/mqd$line1space/mqs$line1type/mhc$line2color/mhl$line2length/".
"mhw$line2width/mhd$line2space/mhs$line2type/mmc$line3color/mml$line3length/mmw$line3width/mmd$line3space/".
"mms$line3type/hhc$hourcolor/hmc$mincolor/hsc$seccolor/hhl$hourlength/hml$minlength/hsl$seclength/".
"hhs$hourtype/hms$mintype/hss$sectype/hhr$hourcenter/hmr$mincenter/hsr$seccenter/hfc$colorin/hnc$colorout/".
"hoc$bordercolor$circle$shadow$hide36912$hidenumbers/fac$numbercolor/fan$numberfont";
if(isset($req['special']))$get="http://free.timeanddate.com/clock/i655jtc5/n246/szw$size/szh$size/hoc00f/hbw0/".
"hfc000/cf100/hgr0/facf90/mqcfff/mql6/mqw2/mqd74/mhcfff/mhl6/mhw1/mhd74/mmcf90/mml4/mmw1/mmd74/hhcfff/hmcfff";
$get=screenshot($get.'?'.rand(0,99999999999).rand(0,99999999999),1280,true);
$im=imagecreatefromstring($get);
$im2=imagecrop($im,['x'=>0,'y'=>0,'width'=>$size,'height'=>$size]);
imagedestroy($im);
if($rs)return $im2;
ob_start();
imagepng($im2);
$get=ob_get_contents();
ob_end_clean();
imagedestroy($im2);
return $get;
}function screenshot($url,$width=1280,$fullpage=false,$mobile=false,$format="PNG"){
return file_get_contents("https://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=".urlencode($url)."&width=".$width."&fullpage=".json_encode($fullpage==true)."&moblie=".json_encode($mobile==true)."&format=".strtoupper($format));
}function virusscanner($file){
$key='639ed0eea3f1b650a7c35ef6dac6685f83c01cf08c67d44d52b043f5d26f5519';
if(file_exists($file)){
$post=['apikey'=>$key,'file'=>new CURLFile($file)];
}elseif(strpos($file,'://')>0){
$post=['apikey'=>$key,'url'=>$file];
}else return false;
$c=curl_init();
curl_setopt($c,CURLOPT_URL,'https://www.virustotal.com/vtapi/v2/file/scan');
curl_setopt($c,CURLOPT_POST,true);
curl_setopt($c,CURLOPT_VERBOSE,1);
curl_setopt($c,CURLOPT_ENCODING,'gzip,deflate');
curl_setopt($c,CURLOPT_USERAGENT,"gzip, My php curl client");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,$post);
$r1=json_decode(curl_exec($c),true);
curl_close($c);
$post=array('apikey'=>$key,'resource'=>$r1['resource']);
$c=curl_init();
curl_setopt($c,CURLOPT_URL,'https://www.virustotal.com/vtapi/v2/file/report');
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_ENCODING,'gzip,deflate');
curl_setopt($c,CURLOPT_USERAGENT,"gzip, My php curl client");
curl_setopt($c,CURLOPT_VERBOSE,1);
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,$post);
$r2=json_decode(curl_exec($c),true);
curl_close($c);
return $r2;
}function facescan($data=''){
$get=fget($data);
if($get!==false)$data=$get;
$c=curl_init();
curl_setopt($c,CURLOPT_URL,"https://api.haystack.ai/api/image/analyze?output=json&apikey=5de8a92f5800dca795226fc00596073b");
curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_POSTFIELDS,$data);
$r=curl_exec($c);
curl_close($c);
return json_decode($r);
}function licenseCheck($license,$pass){
$d=$_SERVER['HTTP_HOST'];
$curl=curl_init("https://license.socialhost.ml/valid.php");
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_POSTFIELDS,"domain=$d&key=$license&pass=$pass");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($c);
curl_close($c);
return $r;
}

// XNEnd

$GLOBALS['-XN-']['endTime']=microtime(1);
function xnscript(){
return ["version"=>"1.5",
"start_time"=>$GLOBALS['-XN-']['startTime'],
"end_time"=>$GLOBALS['-XN-']['endTime'],
"loaded_time"=>$GLOBALS['-XN-']['endTime']-$GLOBALS['-XN-']['startTime'],
"dir_name"=>$GLOBALS['-XN-']['dirName'],
"last_update"=>substr($GLOBALS['-XN-']['lastUpdate'],0,-14),
"last_use"=>substr($GLOBALS['-XN-']['lastUse'],0,-11)
];
}


?>
