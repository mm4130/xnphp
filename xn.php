<?php // xn php v1.7
if(PHP_VERSION<7.0){
throw new Error("<b>xn library</b> needs more than or equal to 7.0 version");
exit;
}$GLOBALS['-XN-']=[];
$GLOBALS['-XN-']['startTime']=microtime(true);
$GLOBALS['-XN-']['dirName']=substr(__FILE__,0,strrpos(__FILE__,DIRECTORY_SEPARATOR));
$GLOBALS['-XN-']['dirNameDir']=$GLOBALS['-XN-']['dirName'].DIRECTORY_SEPARATOR;
$GLOBALS['-XN-']['isf']=file_exists($GLOBALS['-XN-']['dirNameDir']."xn.php");
$GLOBALS['-XN-']['savememory']=&$GLOBALS;
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
if($GLOBALS['-XN-']['isf']){
$file=$GLOBALS['-XN-']['dirNameDir'].'xn.php';
$f=file_get_contents($file);
$p=strpos($f,"{[LASTUPDATE]}");
while($p>0&&$f[$p--]!='"');
if($p<=0)return false;
$h='';
$p+=2;
while($f[$p]!='{')$h.=$f[$p++];
if(!is_numeric($h))return false;
$f=str_replace("$h{[LASTUPDATE]}",microtime(true)."{[LASTUPDATE]}",$f);
return file_put_contents($file,$f);
}
}function set_last_use_nter(){
if($GLOBALS['-XN-']['isf']){
$file=$GLOBALS['-XN-']['dirNameDir'].'xn.php';
$f=file_get_contents($file);
$p=strpos($f,"{[LASTUSE]}");
while($p>0&&$f[$p--]!='"');
if($p<=0)return false;
$h='';
$p+=2;
while($f[$p]!='{')$h.=$f[$p++];
if(!is_numeric($h))return false;
$f=str_replace("$h{[LASTUSE]}",microtime(true)."{[LASTUSE]}",$f);
return file_put_contents($file,$f);
}
}function set_data_nter(){
if($GLOBALS['-XN-']['isf']){
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
}
}function xnupdate(){
copy("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php",$GLOBALS['-XN-']['dirNameDir']."xn.php");
set_last_update_nter();
}if(@$XNUPDATE===2||(@$XNUPDATE===1&&substr($GLOBALS['-XN-']['lastUpdate'],0,-14)+10000<=time()))xnupdate();
$GLOBALS['-XN-']['errorShow']=true;
class XNError extends Error {
protected $message,$from;
public static function show($sh=null){
if($sh===null)$GLOBALS['-XN-']['errorShow']=!$GLOBALS['-XN-']['errorShow'];
else $GLOBALS['-XN-']['errorShow']=$sh;
}public static function handler($func){
$GLOBALS['-XN-']['errorhandler']=$func;
}public function __construct($in,$text,$level=0,$en=false){
$type=["Warning","Notic","Lag","Status","User Error","User Warning","User Notic","Recoverable Error","Syntax Error","Unexpected","Undefined","Anonimouse","System Error","Secury Error","Fatal Error","Arithmetic Error","Parse Error","Type Error"][$level];
$this->from=$in;
$debug=debug_backtrace();
$th=end($debug);
$date=date("ca");
$console="[$date]XN $type > $in : $text in {$th['file']} on line {$th['line']}\n";
$message="<br>\n[$date]<b>XN $type</b> &gt; <i>$in</i> : ".str_replace("\n","<br>",$text)." in <b>{$th['file']}</b> on line <b>{$th['line']}</b>\n<br>";
$this->HTMLMessage=$message;
$this->consoleMessage=$console;
$this->message="XN $type > $in : $text";
if(isset($GLOBALS['-XN-']['errorhandler'])){
try{
if(is_function($GLOBALS['-XN-']['errorHandler']))
$GLOBALS['-XN-']['errorhandler']($this);
}catch(Error $e){
}catch(Expection $e){
}catch(XNError $e){
}}
if($GLOBALS['-XN-']['errorShow'])print $message;
if($GLOBALS['-XN-']['errorShow']&&is_string($GLOBALS['-XN-']['errorShow']))fadd($GLOBALS['-XN-']['errorShow'],$console);
if($en)exit;
}public function __toString(){
return $this->message;
}public function getFrom(){
return $this->from;
}
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
if($save===true)$r=ob_get_contents();
else $save=ob_get_contents();
ob_end_clean();
}return $r;
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
}function thefunction(){
$t=debug_backtrace();
$t=end($t);
return $t['function'];
}function printsc($k=true){
$t=debug_backtrace();
$l=file($t[0]['file']);
$p=$t[0]['line'];
if($k)while(isset($l[$p][1])&&$l[$p][0].$l[$p][1]=='#>'){
print evalc(substr($l[$p++],2));
}else while(isset($l[$p][1])&&$l[$p][0].$l[$p][1]=='#>'){
print substr($l[$p++],2);
}
}function evalg($codeiuefhuisegbfyusegfrusbgtys){
foreach($GLOBALS as $xiuefhuisegbfyusegfrusbgtys=>&$yiuefhuisegbfyusegfrusbgtys)
$$xiuefhuisegbfyusegfrusbgtys=&$yiuefhuisegbfyusegfrusbgtys;
return eval($codeiuefhuisegbfyusegfrusbgtys);
}function evalc($code){
return eval('return '.$code.';');
}function evald($code){
return eval($code);
}function evaln($namespace,$code){
return xneval("<?php\nnamespace $namespace;\n$code");
}function evalp($code){
return xneval("<?php\n$code");
}function is_function($f){
return (is_string($f)&&function_exists($f))||(is_object($f)&&($f instanceof Closure||$f instanceof XNClosure));
}function is_closure($f){
return is_object($f)&&($f instanceof Closure||$f instanceof XNClosure);
}function is_stdClass($f){
return is_object($f)&&($f instanceof stdClass);
}function is_json($json){
$obj=@json_decode($json);
return $obj!==false&&is_string($json)&&(is_object($obj)||is_array($obj));
}function is_xndata($xndata){
return $xndata instanceof XNDataString||$xndata instanceof XNDataFile||$xndata instanceof XNDataURL||$xndata instanceof XNData;
}function random($str,$leng=1){
if(is_string($str))$str=str_split($str);
$r='';$c=count($str)-1;
while($leng>0){
$r=$r.$str[rand(0,$c)];
--$leng;}
return $r;
}function xnsplit($str,$count=1,$space=1){
$arr=[];
$length=strlen($str);
$str=str_split($str);
$loc=0;
while($loc<$length){
$c=0;
$r='';
while($c<$count){
$r=$r.$str[$loc+$c];
++$c;}
$arr[]=$r;
$loc+=$space;}
return $arr;
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
--$x;
settype($x,$t);
}elseif($c[0]=='+')foreach($a as &$x){
$t=gettype($x);
++$x;
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
if($c[0]=='-')--$a;
if($c[0]=='+')++$a;
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
}--$l;
if(!isset($c[$l]));
elseif($c[$l]=='+'){
$z1=true;
$c=substr($c,0,-1);
}elseif($c[$l]=='-'){
$z1=false;
$c=substr($c,0,-1);
}--$l;
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
if(is_numeric($k)&&$k===$p){
$r.=json_encode($v,$js);
++$p;
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
for($c=0;$c<$count;++$c){
foreach($arr as $v)$arr[]=$v;
}return $arr;
}function array_settype($type,$arr){
foreach($arr as &$v)settype($v,$type);
return $arr;
}function evals($str){
return eval("return \"$str\";");
}function findurls($s){
preg_match_all('/([hH][tT][tT][pP][sS]{0,1}:\/\/)([a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)+)(:[0-9]{1,8}){0,1}(\/([^\/\?\# ])*)*(\#[^\n ]*){0,1}(\?[^\n\# ]*){0,1}(\#[^\n ]*){0,1}/',$s,$u);
if(!isset($u[0][0]))return false;
return $u[0];
}function countin($str,$in){
return count(explode($in,$str));
}function xndata($name){
if(file_exists($GLOBALS['-XN-']['dirNameDir'].'xndata.xnd'))
$xnd=new XNDataFile($GLOBALS['-XN-']['dirNameDir'].'xndata.xnd');
else $xnd=new XNDataURL("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.xnd");
$value=$xnd->value($name);
$xnd->close();
return $value;
}class TelegramBotKeyboard {
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
public $get=[];
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
}public function get(){
$get=$this->get;
$this->get=[];
return $get;
}public function reset(){
$this->get=[];
}
}class TelegramBotButtonSave {
private $btns=[],$btn=[];
public function get(string $name,$json=true){
if($json)return @$this->btn[$name];
return @$this->btns[$name];
}public function add(string $name,$btn){
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
}public function delete(string $name){
if(isset($this->btn[$name])){
unset($this->btn[$name]);
unset($this->btns[$name]);
}return $this;
}public function reset(){
$this->btn=[];
$this->btns=[];
return $this;
}public function exists(string $name){
return isset($this->btn[$name]);
}
}class TelegramBotSaveMsgs {
private $msgs=[];
public function get(string $name){
return isset($this->msgs[$name])?$this->msgs[$name]:false;
}public function add(string $name,$message){
$message = XNString::toString($message);
$this->msgs[$name]=$message;
return $this;
}public function delete(string $name){
if(isset($this->msgs[$name]))
unset($this->msgs[$name]);
return $this;
}public function reset(){
$this->msgs=[];
return $this;
}public function exists(string $name){
return isset($this->msgs[$name]);
}
}class TelegramBotSends {
private $bot;
public $chat,$level;
public function chat($chat){
$this->chat;
return $this;
}public function level($level){
$this->level=$level;
return $this;
}public function __construct($bot,$chat=null,$level=null){
$this->bot=$bot;
$this->chat=$chat;
$this->level=$level;
}public function __invoke($chat=null,$level=null){
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
$this->bot->sendPhoto($this->chat,$photo,$args,$this->level);
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
$this->bot->sendPhoto($this->chat,$photo,$args,$this->level);
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
$args['reply_markup']=$markup;
$this->bot->sendPhoto($this->chat,$photo,$args,$this->level);
return $this;
}public function voicebtn($voice,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function videobtn($video,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audiobtn($audio,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonotebtn($videonote,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function stickerbtn($sticker,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function documentbtn($document,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function filebtn($file,$markup,$args=[]){
$args['reply_markup']=$markup;
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}public function photomsgbtn($photo,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendPhoto($this->chat,$photo,$args,$this->level);
return $this;
}public function voicemsgbtn($voice,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendVoice($this->chat,$voice,$args,$this->level);
return $this;
}public function videomsgbtn($video,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendVideo($this->chat,$video,$args,$this->level);
return $this;
}public function audiomsgbtn($audio,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendAudio($this->chat,$audio,$args,$this->level);
return $this;
}public function videonotemsgbtn($videonote,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendVideoNote($this->chat,$videonote,$args,$this->level);
return $this;
}public function stickermsgbtn($sticker,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendSticker($this->chat,$sticker,$args,$this->level);
return $this;
}public function documentmsgbtn($document,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendDocument($this->chat,$document,$args,$this->level);
return $this;
}public function filemsgbtn($file,$caption,$markup,$args=[]){
$args['caption']=$caption;
$args['reply_markup']=$markup;
$this->bot->sendFile($this->chat,$file,$args,$this->level);
return $this;
}public function uploadingPhoto(){
$this->bot->sendUploadingPhoto($this->chat,$this->level);
return $this;
}public function uploadingAudio(){
$this->bot->sendUploadingAudio($this->chat,$this->level);
return $this;
}public function uploadingVideo(){
$this->bot->sendUploadingVideo($this->chat,$this->level);
return $this;
}public function uploadingDocument(){
$this->bot->sendUploadingDocument($this->chat,$this->level);
return $this;
}public function uploadingVideoNote(){
$this->bot->sendUploadingVideoNote($this->chat,$this->level);
return $this;
}public function findingLocation(){
$this->bot->sendFindingLocation($this->chat,$this->level);
return $this;
}public function recordingAudio(){
$this->bot->sendRecordingAudio($this->chat,$this->level);
return $this;
}public function recordingVideo(){
$this->bot->sendRecordingVideo($this->chat,$this->level);
return $this;
}public function recordingVideoNote(){
$this->bot->sendRecordingVideoNote($this->chat,$this->level);
return $this;
}public function delmsg($id){
$this->bot->deleteMessage($this->chat,$id,$this->level);
return $this;
}
}class TelegramBot {
public $data,$token,$final,$results=[],$sents=[],$save=true,$last,$parser=true,$variables=false,$notresponse=false,$autoaction=false,$handle=false;
public $keyboard,$inlineKeyboard,$foreReply,$removeKeyboard,$queryResult,$menu,$send,$msgs;
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
$this->msgs=new TelegramBotSaveMsgs;
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
return (object)$res;
}public function request($method,$args=[],$level=3){
$args=$this->parse_args($args);
$res=false;
$func=$this->handle;
$handle=$func?new ThumbCode(function()use(&$method,&$args,&$res,&$level,&$func){
$func((object)["method"=>$method,"arguments"=>$args,"result"=>$res,"level"=>$level]);
}):false;
if($this->autoaction&&isset($args['chat_id'])){
switch(strtolower($method)){
case "sendmessage":
$action="typing";
break;case "sendphoto":
$action="upload_photo";
break;case "sendvoice":
$action="record_audio";
break;case "sendvideo":
$action="upload_video";
break;case "sendvideonote":
$action="uplaod_video_note";
break;case "sendaudio":
$action="upload_audio";
break;case "senddocument":
$action="upload_document";
break;default:
$action=false;
break;
}if($action)
$this->request("sendChatAction",[
"chat_id"=>$args['chat_id'],
"action"=>$action
]);
}
if($level==1){
$args['method']=$method;
print json_encode($args);
$res=true;
}elseif($level==2){
$res=@fopen("https://api.telegram.org/bot$this->token/$method?".http_build_query($args),'r');
if($res)fclose($res=true);
else $res=false;
}elseif($level==3){
$c=curl_init("https://api.telegram.org/bot$this->token/$method");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,$args);
$res=json_decode(curl_exec($c));
curl_close($c);
}elseif($level==4){
$res=@fopen("https://api.pwrtelegram.xyz/bot$this->token/$method?".http_build_query($args),'r');
if($res)fclose($res=true);
else $res=false;
}elseif($level==5){
$c=curl_init("https://api.pwrtelegram.xyz/bot$this->token/$method");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,$args);
$res=json_decode(curl_exec($c));
curl_close($c);
}else return false;
$args['method']=$method;
$args['level']=$level;
if($this->save){
$this->sents[]=$args;
$this->results[]=$this->final=$res;
}if($res===false)return false;
if($res===true)return true;
if(!$res){
$server = ["OUTPUT","api.telegram.org","api.telegram.org","api.pwrtelegram.xyz","api.pwrtelegram.xyz"][$level-1];
new XNError("TelegramBot","network error for Connect to $server",1);
return false;
}elseif(!$res->ok){
new XNError("TelegramBot","$res->description [$res->error_code]",1);
return $res;
}return $res;
}public function reset(){
$this->final=null;
$this->results=[];
$this->sents=[];
$this->data=null;
}public function close(){
$this->__destruct();
}public function __destruct(){
$this->final=null;
$this->results=null;
$this->sents=null;
$this->data=null;
$this->token=null;
$this->inlineKeyboard=null;
$this->keyboard=null;
$this->forceReply=null;
$this->removeKeyboard=null;
$this->queryResult=null;
$this->send=null;
$this->menu=null;
if($this->notresponse)($this->notresponse)();
}public function sendMessage($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$args['text']=$text;
return $this->request("sendMessage",$args,$level);
}public function sendMessages($chat,$text,$args=[],$level=3){
$args['chat_id']=$chat;
$texts=str_split($text,4096);
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
}public function sendUploadingPhoto($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"upload_photo"
],$level);
}public function sendUploadingVideo($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"upload_video"
],$level);
}public function sendUploadingAudio($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"upload_audio"
],$level);
}public function sendUploadingDocument($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"upload_document"
],$level);
}public function sendUploadingVideoNote($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"upload_video_note"
],$level);
}public function sendFindingLocation($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"find_location"
],$level);
}public function sendRecordingVideo($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"record_video"
],$level);
}public function sendRecordingAudio($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"record_audio"
],$level);
}public function sendRecordingVideoNote($chat,$level=3){
return $this->request("sendChatAction",[
"chat_id"=>$chat,
"action"=>"record_video_note"
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
}public function resrictMember($chat,$user,$args,$time=false,$level=3){
foreach($args as $key=>$val)$args["can_$key"]=$val;
$args['chat_id']=$chat;
$args['user_id']=$user;
if($time)$args['until_date']=$time;
return $this->request("resrictChatMember",$args,$level);
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
for(;$from<=$to;++$from)
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
}public function getAllMembers($chat){
return json_decode(file_get_contents("http://xns.elithost.eu/getparticipants/?token=$this->token&chat=$chat"));
}public function updateType($update=false){
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
}--$while;}}
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
}public function convertFile($chat,$file,$name,$type="document",$level=3){
if(file_exists($name))$read=file_get_contents($name);
else $read=false;
file_put_contents($name,$this->downloadFile($file,$level));
$r=$this->sendMedia($chat,$type,new CURLFile($name),$level);
if($read!==false)file_put_contents($name,$read);
else unlink($name);
return $r;
}public function toGFile($file){
$file=base64url_decode($file);
$token=base64url_decode($this->token);
$file=chr(strlen($file)).$file;
return base64url_encode($file.$token);
}public function fromGFile($chat,$file,$name,$type="document",$level=3){
$r=base64url_decode($file);
$p=ord($r[0]);
$file=substr($r,1,$p);
$token=substr($r,$p+1);
$bot=new TelegramBot($token);
$get=false;
if(file_exists($name))$get=file_get_contents($name);
file_put_contents($name,$bot->downloadFile($file,$level));
$bot->sendMedia($chat,$type,new CURLFile($name),$level);
if($get)file_put_contents($name,$get);
else unlink($name);
}public function downloadGFile($file,$level=3){
$r=base64url_decode($file);
$p=ord($r[0]);
$file=substr($r,1,$p);
$token=substr($r,$p+1);
$bot=new TelegramBot($token);
return $bot->downloadFile($file,$level);
}public function sendUpdate($url,$update=false){
if($update===false)$update=$this->update();
$c=curl_init($url);
curl_setopt($c,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($c,CURLOPT_POSTFIELDS,$update);
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($c);
curl_close($c);
return $r;
}public function sendMessageFromUpdate($chat,$update=false,$args=[],$level=3){
if($update)$update=$this->update->message;
elseif(isset($update->message))$update=$update->message;
$args['file']=isset($args['file'])?$args['file']:
              isset($args['document'])?$args['document']:
              isset($args['video'])?$args['video']:
              isset($args['voice'])?$args['voice']:
              isset($args['video_note'])?$args['video_note']:
              isset($args['audio'])?$args['audio']:
              isset($args['sticker'])?$args['sticker']:
              isset($args['photo_file_id'])?$args['photo_file_id']:
              isset($args['document_file_id'])?$args['document_file_id']:
              isset($args['video_file_id'])?$args['video_file_id']:
              isset($args['voice_file_id'])?$args['voice_file_id']:
              isset($args['video_note_file_id'])?$args['video_note_file_id']:
              isset($args['audio_file_id'])?$args['audio_file_id']:
              isset($args['sticker_file_id'])?$args['sticker_file_id']:
              isset($args['photo_url'])?$args['photo_url']:
              isset($args['document_url'])?$args['document_url']:
              isset($args['video_url'])?$args['video_url']:
              isset($args['voice_url'])?$args['voice_url']:
              isset($args['video_note_url'])?$args['video_note_url']:
              isset($args['audio_url'])?$args['audio_url']:
              isset($args['sticker_url'])?$args['sticker_url']:
              isset($args['file_id'])?$args['file_id']:
              isset($args['photo'])?$args['photo']:false;
if($args['file']){
$args['photo']=
$args['document']=
$args['video']=
$args['voice']=
$args['video_note']=
$args['audio']=
$args['sticker']=
$args['photo_file_id']=
$args['document_file_id']=
$args['video_file_id']=
$args['voice_file_id']=
$args['video_note_file_id']=
$args['audio_file_id']=
$args['sticker_file_id']=
$args['photo_url']=
$args['document_url']=
$args['video_url']=
$args['voice_url']=
$args['video_note_url']=
$args['audio_url']=
$args['sticker_url']=
$args['file_id']=
$args['file'];
if(isset($update->caption))$args['caption']=isset($args['caption'])?$args['caption']:$update->caption;
if(isset($update->photo))return $this->sendPhoto($chat,isset($args['photo'])?$args['photo']:end($update->photo)->file_id,$args,$level);
if(isset($update->video))return $this->sendVideo($chat,isset($args['video'])?$args['video']:$update->video->file_id,$args,$level);
if(isset($update->voice))return $this->sendVoice($chat,isset($args['voice'])?$args['voice']:$update->voice->file_id,$args,$level);
if(isset($update->audio))return $this->sendAudio($chat,isset($args['audio'])?$args['audio']:$update->audio->file_id,$args,$level);
if(isset($update->video_note))return $this->sendVideoNote($chat,isset($args['video_note'])?$args['video_note']:$update->video_note->file_id,$args,$level);
if(isset($update->sticker))return $this->sendSticker($chat,isset($args['sticker'])?$args['sticker']:$update->sticker->file_id,$args,$level);
if(isset($update->document))return $this->sendDocument($chat,isset($args['document'])?$args['document']:$update->document->file_id,$args,$level);
}if(isset($update->text))return $this->sendMessage($chat,isset($args['text'])?$args['text']:$update->text,$args,$level);
if(isset($update->contact)){
$args['phone']=isset($args['phone'])?$args['phone']:isset($args['number'])?$args['number']:isset($args['phone_number'])?$args['phone_number']:false;
$args['first_name']=isset($args['first_name'])?$args['first_name']:$update->contact->first_name;
$args['last_name']=isset($args['last_name'])?$args['last_name']:isset($update->contact->last_name)?$update->contact->last_name:false;
if($args['last_name']===false)unset($args['last_name']);
return $this->sendContact($chat,$args['phone']?$args['phone']:$update->contact->phone_number,$args,$level);
}if(isset($update->location)){
$latitude=isset($args['latitude'])?$args['latitude']:$update->location->latitude;
$longitude=isset($args['longitude'])?$args['longitude']:$update->location->longitude;
return $this->sendLocation($chat,$latitude,$longitude,$args,$level);
}if(isset($update->venue)){
$latitude=isset($args['latitude'])?$args['latitude']:$update->venue->latitude;
$longitude=isset($args['longitude'])?$args['longitude']:$update->venue->longitude;
$address=isset($args['address'])?$args['address']:$update->venue->address;
$title=isset($args['title'])?$args['title']:$update->venue->title;
return $this->sendVenue($laitude,$longitude,$address,$title,$args,$level);
}return false;
}public function parse_args($args=[]){
if(!$this->parser)return $args;
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
if(isset($args['parse']))$args['parse_mode']=$args['parse'];
if(isset($args['markup']))$args['reply_markup']=$args['markup'];
if(isset($args['reply']))$args['reply_to_message_id']=$args['reply'];
if(isset($args['from_chat']))$args['from_chat_id']=$args['from_chat'];
$args['file']=isset($args['file'])?$args['file']:
              isset($args['document'])?$args['document']:
              isset($args['video'])?$args['video']:
              isset($args['voice'])?$args['voice']:
              isset($args['video_note'])?$args['video_note']:
              isset($args['audio'])?$args['audio']:
              isset($args['sticker'])?$args['sticker']:
              isset($args['photo_file_id'])?$args['photo_file_id']:
              isset($args['document_file_id'])?$args['document_file_id']:
              isset($args['video_file_id'])?$args['video_file_id']:
              isset($args['voice_file_id'])?$args['voice_file_id']:
              isset($args['video_note_file_id'])?$args['video_note_file_id']:
              isset($args['audio_file_id'])?$args['audio_file_id']:
              isset($args['sticker_file_id'])?$args['sticker_file_id']:
              isset($args['photo_url'])?$args['photo_url']:
              isset($args['document_url'])?$args['document_url']:
              isset($args['video_url'])?$args['video_url']:
              isset($args['voice_url'])?$args['voice_url']:
              isset($args['video_note_url'])?$args['video_note_url']:
              isset($args['audio_url'])?$args['audio_url']:
              isset($args['sticker_url'])?$args['sticker_url']:
              isset($args['file_id'])?$args['file_id']:
              isset($args['photo'])?$args['photo']:false;
if($args['file']){
$gettype=TelegramCode::getFileType($args['file']);
if(is_string($args['file'])&&
   $gettype!==false&&
   file_exists($args['file']))
   $args['file']=new CURLFile($args['file']);
$args['photo']=
$args['document']=
$args['video']=
$args['voice']=
$args['video_note']=
$args['audio']=
$args['sticker']=
$args['photo_file_id']=
$args['document_file_id']=
$args['video_file_id']=
$args['voice_file_id']=
$args['video_note_file_id']=
$args['audio_file_id']=
$args['sticker_file_id']=
$args['photo_url']=
$args['document_url']=
$args['video_url']=
$args['voice_url']=
$args['video_note_url']=
$args['audio_url']=
$args['sticker_url']=
$args['file_id']=
$args['file'];
}if(isset($args['phone']))$args['phone_number']=$args['phone'];
if(isset($args['allowed_updates'])&&(is_array($args['allowed_updates'])||is_object($args['allowed_updates'])))
$args['allowed_updates']=json_encode($args['allowed_updates']);
if(isset($args['reply_markup'])&&is_string($args['reply_markup'])&&$this->menu->exists($args['reply_markup']))
$args['reply_markup']=$this->menu->get($args['reply_markup']);
if(isset($args['reply_markup'])&&(is_array($args['reply_markup'])||is_object($args['reply_markup'])))
$args['reply_markup']=json_encode($args['reply_markup']);
if(isset($args['chat_id'])&&is_object($args['chat_id'])){
if(isset($args['chat_id'])&&isset($args['chat_id']->update_id)){
$args['chat_id']=@$this->getUpdateInType($args['chat_id']);
$args['chat_id']=isset($args['chat_id']->chat)?$args['chat_id']->chat->id:@$args['chat_id']->from->id;
}else $args['chat_id']=isset($args['chat_id']->chat)?$args['chat_id']->chat->id:@$args['chat_id']->from->id;
}if(isset($args['user_id'])&&is_object($args['user_id'])){
if(isset($args['user_id']->update_id)){
$args['user_id']=@$this->getUpdateInType($args['user_id']);
$args['user_id']=isset($args['user_id']->chat)?$args['user_id']->chat->id:@$args['user_id']->from->id;
}else $args['user_id']=isset($args['user_id']->chat)?$args['user_id']->chat->id:@$args['user_id']->from->id;
}if($this->variables&&!isset($args['variables']))$args['variables']=true;
if(isset($args['text'])){
$args['text']=XNString::toString($args['text']);
if(isset($args['variables'])&&$args['variables']){
$msgs=&$this->msgs;
$up=$this->data?$this->data:false;
if($up)$up['']=$this->final;
$args['text']=preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/",function($x)use(&$msgs,$up){
$ms=str_replace('%%','%',$x[1]);
if($msgs->exists($ms))return $msgs->get($ms);
if($up){
$ms=explode('.',$ms);
foreach($ms as $u)
if(isset($up->$u))
$up=$up->$u;
if(!is_string($up))return $x[0];
return $up;
}return $x[0];
},$args['text']);
$args['text']=str_replace('%%','%',$args['text']);
}
}if(isset($args['caption'])){
$args['caption']=XNString::toString($args['caption']);
if(isset($args['variables'])&&$args['variables']){
$msgs=&$this->msgs;
$up=$this->data?$this->data:false;
if($up)$up['']=$this->final;
$args['caption']=preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/",function($x)use(&$msgs,$up){
$ms=str_replace('%%','%',$x[1]);
if($msgs->exists($ms))return $msgs->get($ms);
if($up){
$ms=explode('.',$ms);
foreach($ms as $u)
if(isset($up->u))
$up=$up->u;
if(!is_string($up))return $x[0];
return $up;
}return $x[0];
},$args['caption']);
$args['caption']=str_replace('%%','%',$args['caption']);
}
}
return $args;
}
}

class TelegramLink {
public static function getMessage($chat,$message){
if(@$chat[0]=='@')$chat=substr($chat,1);
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
}public static function getChat($chat){
if(@$chat[0]=='@')$chat=substr($chat,1);
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
}public static function getJoinChat($code){
return self::getChat("joinchat/$code");
}public static function getSticker($name){
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
}public function channelCreatedDate($channel){
return self::getMessage($channel,1)["date"];
}
}

class TelegramCode {
public static function getFileType($file){
$file=base64_decode(strtr($file,'-_','+/'));
$type=[
0=>"thumb",
2=>"image",
5=>"document",
3=>"voice",
10=>"document",
4=>"video",
9=>"audio",
13=>"video_note",
8=>"sticker"
];$file=ord($file[0]);
return isset($type[$file])?$type[$file]:false;
}public static function getMimeType($type,$mime_type="text/plan"){
return ["document"=>$mime_type,"audio"=>"audio/mp3","video"=>"video/mp4","vide_note"=>"video/mp4","voice"=>"audio/ogg","photo"=>"image/jpeg","sticker"=>"image/webp"][$type];
}public static function getFormat($type,$format="txt"){
return ["document"=>$format,"audio"=>"mp3","video"=>"mp4","vide_note"=>"mp4","voice"=>"ogg","photo"=>"jpg","sticker"=>"webp"][$type];
}public static function getJoinChat($code){
$code=base64_decode(strtr($code,'-_','+/'));
return base_convert(bin2hex(substr($code,4,4)),16,10);
}public static function faketoken_random(){
$tokens=xndata("faketoken/random");
return $tokens[array_rand($tokens)];
}
}

class TelegramUploader {
private static function getbot(){
return new TelegramBot("348695851:AAE5GyQ7NVgxq9i1UToQQXBydGiNVD06rpo");
}public static function upload($content){
$bot=self::getbot();
$codes='';
$contents=str_split($content,5242880);
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
}public static function download($code){
$bot=self::getbot();
$codes=$bot->downloadFile($code);
$codes=explode('.',$codes);
foreach($codes as &$code){
$code=$bot->downloadFile($code);
}return implode('',$codes);
}public static function uploadFile($file){
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
}public static function downloadFile($code,$file){
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
}public static function convert($code,$type,$name){
$bot=self::getbot();
$code=$bot->convertFile($code,$file,$type,"@tebrobot");
if(!$code->ok)return $code;
return $code->result->{$type};
}public static function getChat($chat){
return self::getbot()->getChat($chat);
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
}--$while;}
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
return $this->messagesRequest("getChats",[
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
}public function getNearesDc($level=2){
return $this->helpRequest("getNearesDc",[],$level);
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
}public static function getId(string $username){
if(@$username[0]!='@')$username="@$username";
$r=json_decode(file_get_contents("https://id.pwrtelegram.xyz/db/getid?username=$username"));
return $r&&$r->ok?$r->result:false;
}public static function getInfo($id){
if(!is_numeric($id)&&@$id[0]!='@')$id="@$id";
$r=json_decode(file_get_contents("https://id.pwrtelegram.xyz/db/getchat?id=$id"));
return $r&&$r->ok?$r->result:false;
}
}

class XNTelegramCrypt {
public function aes_calculate($msg,$auth,$to=true){
$x=$to?0:8;
$a=hash('sha256',$msg.substr($auth,$x,36),true);
$b=hash('sha256',substr($auth,40+$x,36).$msg,true);
$key=substr($a,0,8).substr($b,8,16).substr($a,24,8);
$iv=substr($b,0,8).substr($a,8,16).substr($b,24,8);
return [$key,$iv];
}public function old_aes_calculate($msg,$auth,$to=true){
$x=$to?0:8;
$a=sha1($msg.substr($auth,$x,32),true);
$b=sha1(substr($auth,32+$x,16).$msg.substr($auth,48+$x,16),true);
$c=sha1(substr($auth,64+$x,32).$msg,true);
$d=sha1($msg.substr($auth,96+$x,32),true);
$key=substr($a,0,8).substr($b,8,12).substr($c,4,12);
$iv=substr($a,8,12).substr($b,0,8).substr($c,16,4).substr($d,0,8);
return [$key,$iv];
}public function ige_encrypt($msg,$key,$iv){
$cipher = new \phpseclib\Crypt\AES('ige');
$cipher->setKey($key);
$cipher->setIV($iv);
return @$cipher->encrypt($msg);
}public function ctr_encrypt($msg,$key,$iv){
$cipher = new \phpseclib\Crypt\AES('ctr');
$cipher->setKey($key);
$cipher->setIV($iv);
return @$cipher->encrypt($msg);
}public function ige_decrypt($msg,$key,$iv){
$cipher = new \phpseclib\Crypt\AES('ige');
$cipher->setKey($key);
$cipher->setIV($iv);
return @$cipher->decrypt($msg);
}
}
class XNTelegram {
private $servers = [];
}function var_get($var){
$c=file($GLOBALS['-XN-']['sourcefile'])[theline()-1];
if(preg_match('/var_name[\n ]*\([@\n ]*\$([a-zA-Z_0-9]+)[\n ]*((\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[[^\]]+\])|(\([^\)]*\)))*\)/',$c,$s)){
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
}if(isset($s[1]))return ["type"=>"variable",
"short_type"=>"var",
"name"=>$s[1],
"full"=>$s[0],
"calls"=>$u];
}elseif(preg_match('/var_get[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\)/',$c,$s)){
return ["type"=>"define",
"short_type"=>"def",
"name"=>$s[1]
];
}elseif(preg_match('/var_get[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\(/',$c,$s)){
if(preg_match('/^[fF][uU][nN][cC][tT][iI][oO][nN]$/',$s[1]))$s[1]="function";
return ["type"=>"function",
"short_type"=>"closure",
"name"=>$s[1]
];
}new XNError("var_get","type invalid",1);
return false;
}function fvalid($file){
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
}function fget($file,$x=false,$y=false){
$size=@filesize($file);
if($size!==false&&$size!==null){
if($y)$f=@fopen($file,'r',$x,stream_context_create($y));
else $f=@fopen($file,'r',$x);
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
$l=ftell($file);
fseek($file,0,SEEK_END);
$s=ftell($file);
fseek($file,$l);
return $s;
}function fspeed($file,$type='r'){
if($f=@fopen($file,$type))fclose($f);
return $f;
}function ftype($file){
return filetype($file);
}function fdir($file){
return dirname($file);
}function filename($file){
return XNString::end($file,DIRECTORY_SEPARATOR);
}function fileformat($file){
$f=XNString::end($file,'.');
return strhave($f,DIRECTORY_SEPARATOR)?false:$f;
}function fname($stream){
return stream_get_meta_data($stream)['uri'];
}function dirdel($dir){
$s=scandir($dir);
if(@$s[0]=='.')unset($s[0]);
if(@$s[1]=='.')unset($s[1]);
if(@$s[0]=='..')unset($s[0]);
if(@$s[1]=='..')unset($s[1]);
foreach($s as $f){
if(is_dir("$dir/$f"))dirdel("$dir/$f");
else unlink("$dir/$f");
}return rmdir($dir);
}function dirscan($dir){
$s=scandir($dir);
if(@$s[0]=='.')unset($s[0]);
if(@$s[1]=='.')unset($s[1]);
if(@$s[0]=='..')unset($s[0]);
if(@$s[1]=='..')unset($s[1]);
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
++$m;$s="$s$c";
}else{
$s='';$m=0;
}++$o;
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
++$m;$s="$s$c";
}else{
$s='';$m=0;
}++$o;
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
++$m;$s="$s$c";
}else{
$k="$k$s$c";
$s='';$m=0;
}}$r[]=$k;
fclose($f);
if($str==$l||$str=='')$r[]='';
return $r;
}function is_url($file){
return filter_var($file,FILTER_VALIDATE_URL)&&!file_exists($file)&&fvalid($file);
}function fsubget($file,$from=0,$to=false){
if($to===false)$t=filesize($file);
elseif($to<0)$to=filesize($file)+$to;
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=fgetc($f))!==false&&$to!=0){
$r.=$c;
--$to;
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
--$to;
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
++$m;$s="$s$c";
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
++$dircount;
$size+=filesize("$dir/$file");
$i=dirfilesinfo("$dir/$file");
$size+=$i->size;
$foldercount+=$i->folder;
$filecount+=$i->file;
}else{
++$filecount;
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
for($o=0;$o<4;++$o){
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
}function set_json_app(){
header("Content-Type: application/json");
}function set_text_app(){
header("Content-Type: text/plan");
}function set_html_app(){
header("Content-Type: text/html");
}function set_http_code($code){
header(":",false,$code);
}function redairect($loc){
header("Location: $loc");
}function ContentLength($length){
header("Content-Length: $length");
}function ContentType($c){
return header("Content-Type: $c");
}function delete_error_log_file(){
if(file_exists("error_log"))unlink("error_log");
}function xndateoption($date=1){
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
while($c>0)--$c;
}function nsleep($c){
if($c>0)nsleep($c-1);
}function msleep($c){
$c*=1000;
$m=microtime(true);
while($m+$c>microtime(true));
}function base10_encode($str){
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
return str_replace([
"0","1","2","3","4","5","6","7",
"8","9","a","b","c","d","e","f"
],[
"0000","0001","0010","0011",
"0100","0101","0110","0111",
"1000","1001","1010","1011",
"1100","1101","1110","1111"
],bin2hex($text));
}function base2_decode($text){
$n='';
$p=[
"0000"=>"0",
"0001"=>"1",
"0010"=>"2",
"0011"=>"3",
"0100"=>"4",
"0101"=>"5",
"0110"=>"6",
"0111"=>"7",
"1000"=>"8",
"1001"=>"9",
"1010"=>"a",
"1011"=>"b",
"1100"=>"c",
"1101"=>"d",
"1110"=>"e",
"1111"=>"f"
];for($c=0;isset($text[$c]);){
$n.=$p[$text[$c++].$text[$c++].$text[$c++].$text[$c++]];
}return hex2bin($n);
}function base64url_encode($data){
return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
}function base64url_decode($data){
return base64_decode(str_pad(strtr($data,'-_','+/'),strlen($data)%4,'=',STR_PAD_RIGHT));
}function baseconvert($text,$from,$to=false){
if(is_string($from)&&strtoupper($from)=="ASCII")return baseconvert(bin2hex($text),"0123456789abcdef",$to);
if(is_string($to  )&&strtoupper($to)  =="ASCII")return hex2bin(baseconvert($text,$from,"0123456789abcdef"));
$text=(string)$text;
if(!is_array($from))$fromel=str_split($from);
else $fromel=$from;
$frome=[];
foreach($fromel as $key=>$value){
$frome[$value]=$key;
}unset($fromel);
$fromc=count($frome);
if(!is_array($to))$toe=str_split($to);
else $toe=$to;
$toc=count($toe);
$texte=array_reverse(str_split($text));
$textc=count($texte);
$bs=0;
$th=1;
for($i=0;$i<$textc;++$i){
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
}function image_number_encode($string){
if(!is_numeric($string))return false;
$string=split($string,7,7);
$count=count($string);
$width=floor(sqrt($count));
$height=ceil(sqrt($count))+1;
$im=imagecreatetruecolor($width,$height);
$x=0;$y=0;
foreach($string as $pixel){
imagesetpixel($im,$x,$y,$pixel+1);
++$y;
if($y>=$height){
$y=0;++$x;
}}
ob_start();
imagepng($im);
$r=ob_get_contents();
ob_end_clean();
imagedestroy($im);
return $r;
}function image_number_decode($image){
$im=imagecreatefromstring($image);
$r='';
$width=imagesx($im);
$height=imagesy($im);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
$col=imagecolorat($im,$x,$y)-1;
if($col>0){
if(strlen($col)<7&&imagecolorat($im,$x,$y+1)>0)
$col=str_repeat('0',7-strlen($col)).$col;
$r.=$col;
}++$y;}
++$x;}
return $r;
}function image_string_encode($str){
return image_number_encode(number_string_encode($str));
}function image_string_decode($str){
return number_string_decode(image_number_decode($str));
}function number_array_encode($array){
$array=(array)$array;
$r='';
foreach($array as $key=>$val){
if($r)$r=$r.'999'.number_string_encode($key).'99'.number_string_encode($val);
else $r=number_string_encode($key).'99'.number_string_encode($val);
}return $r;
}function number_array_decode($str){
$r=[];
$e=explode('999',$str);
foreach($e as $s){
$kv=explode('99',$s);
$key=number_string_decode($kv[0]);
$val=number_string_decode($kv[1]);
$r[$key]=$val;
}return $r;
}function image_array_encode($array){
return image_number_encode(number_array_encode($array));
}function image_array_decode($str){
return image_array_decode(image_number_decode($str));
}function number_object_encode($object){
$name=get_class($object);
$object=serialize($object);
$array=str_replace('O:'.strlen($name).':"'.$name.'"','a',$object);
$array=number_array_encode(unserialize($array));
$array=number_string_encode($name).'99'.$array;
return $array;
}function number_object_decode($str){
$p=strpos($str,'99');
$name=number_string_decode(substr($str,0,$p));
$array=substr(serialize(number_array_decode(substr($str,$p+2))),1);
$object='O:'.strlen($name).':"'.$name.'"'.$array;
return unserialize($object);
}function image_object_encode($object){
return image_number_encode(number_object_encode($object));
}function image_object_decode($str){
return image_object_decode(image_number_decode($str));
}function arabic_base2_encode($str){
return str_replace([
'آ','ا','ب','پ','ت','ث','ج','چ','ح',
'خ','د','ذ','ر','ز','ژ','س','ش',
'ص','ض','ط','ظ','ع','غ','ف','ق',
'ک','گ','ل','م','ن','و','ه','ی',
],[
"00000","00000","00001","00010","00011",
"00100","00101","00110","00111",
"01000","01001","01010","01011",
"01100","01101","01110","01111",
"10000","10001","10010","10011",
"10100","10101","10110","10111",
"11000","11001","11010","11011",
"11100","11101","11110","11111"
],$str);
}function arabic_base2_decode($str){
$r=[
"00000"=>"ا","00001"=>"ب",
"00010"=>"پ","00011"=>"ت",
"00100"=>"ث","00101"=>"ج",
"00110"=>"چ","00111"=>"ح",
"01000"=>"خ","01001"=>"د",
"01010"=>"ذ","01011"=>"ر",
"01100"=>"ز","01101"=>"ژ",
"01110"=>"س","01111"=>"ش",
"10000"=>"ص","10001"=>"ض",
"10010"=>"ط","10011"=>"ظ",
"10100"=>"ع","10101"=>"غ",
"10110"=>"ف","10111"=>"ق",
"11000"=>"ک","11001"=>"گ",
"11010"=>"ل","11011"=>"م",
"11100"=>"ن","11101"=>"و",
"11110"=>"ه","11111"=>"ی"
];$n='';
for($c=0;isset($str[$c]);$c+=5){
$t=$str[$c].$str[$c+1].$str[$c+2].$str[$c+3].$str[$c+4];
if(isset($r[$t]))$t=$r[$t];
$n.=$t;
}return $n;
}function base4_encode($text){
return str_replace([
"0","1","2","3","4","5","6","7",
"8","9","a","b","c","d","e","f"
],[
"00","01","02","03",
"10","11","12","13",
"20","21","22","23",
"30","31","32","33"
],bin2hex($text));
}function base4_decode($text){
$n='';
$p=[
"00"=>"0",
"01"=>"1",
"02"=>"2",
"03"=>"3",
"10"=>"4",
"11"=>"5",
"12"=>"6",
"13"=>"7",
"20"=>"8",
"21"=>"9",
"22"=>"a",
"23"=>"b",
"30"=>"c",
"31"=>"d",
"32"=>"e",
"33"=>"f"
];for($c=0;isset($text[$c]);$c+=2){
$n.=$p[$text[$c].$text[$c+1]];
}return hex2bin($n);
}
class XNDataMath {
private $xnd;
public function __construct($xnd){
$this->xnd=$xnd;
}public function add($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)+$count);
return $this->xnd;
}public function rem($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)-$count);
return $this->xnd;
}public function div($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)/$count);
return $this->xnd;
}public function mul($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)*$count);
return $this->xnd;
}public function pow($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)**$count);
return $this->xnd;
}public function rect($key,$count=1){
$this->xnd->set($key,$this->xnd->value($key)%$count);
return $this->xnd;
}public function calc($key,$calc){
$this->xnd->set($key,XNCalc::calc($calc,['x'=>$this->xnd->value($key)]));
return $this->xnd;
}public function join($key,$data){
$this->xnd->set($key,$this->xnd->value($key).$data);
return $this->xnd;
}
}class XNDataProMath {
private $xnd;
public function __construct($xnd){
$this->xnd=$xnd;
}public function add($key,$count=1){
$this->xnd->set($key,XNNumber::add($this->xnd->value($key),$count));
return $this->xnd;
}public function rem($key,$count=1){
$this->xnd->set($key,XNNumber::rem($this->xnd->value($key),$count));
return $this->xnd;
}public function mul($key,$count=1){
$this->xnd->set($key,XNNumber::mul($this->xnd->value($key),$count));
return $this->xnd;
}public function div($key,$count=1){
$this->xnd->set($key,XNNumber::div($this->xnd->value($key),$count));
return $this->xnd;
}public function rect($key,$count=1){
$this->xnd->set($key,XNNumber::rect($this->xnd->value($key),$count));
return $this->xnd;
}public function pow($key,$count=1){
$this->xnd->set($key,XNNumber::pow($this->xnd->value($key),$count));
return $this->xnd;
}public function calc($key,$calc){
$this->xnd->set($key,XNNumber::calc($calc,['x'=>$this->xnd->value($key)]));
return $this->xnd;
}
}function gzserialize($data,$level=5){
return gzencode(serialize($data),$level);
}function gzunserialize($data){
return unserialize(gzdecode($data));
}
class XNDataString {
private $data,$parent=false;
public $math,$proMath,$auto=true;
public function __construct($data=',',$parent=false){
if(@$data[0]!==',')$data=',';
$this->data=$data;
$this->parent=$parent;
$this->math=new XNDataMath($this);
$this->proMath=new XNDataProMath($this);
}public function convert($file){
fput($file,$this->data);
return new XNDataFile($file);
}public function reset(){
$this->data=',';
return $this;
}public function get(){
return $this->data;
}public function __destruct(){
$this->save();
}public function save(){
if($this->parent){
$this->direncode();
$here=&$this->parent[0];
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$key=$here->encode($this->parent[1]);
$ssff=strlen($this->data);
$ssk=strlen($key);
$ssf=base_convert($ssff+2,10,16);
if(strlen($ssf)%2==1)$ssf="0$ssf";
$ssf=base64url_encode(hex2bin($ssf));
$ssz=$ssff+strlen($ssf)+$ssk+4;
$ssz=base_convert($ssz,10,16);
if(strlen($ssz)%2==1)$ssz="0$ssz";
$ssz=base64url_encode(hex2bin($ssz));
$el2=$ssz.';'.$key.'.'.$ssf.':{'.$this->data.'}';
$ky=';'.$key.'.';
$p=strpos($here->data,$ky)+strlen($ky);
$size='';
while(($h=$here->data[$p++])!==':')$size.=$h;
$sizee=$size;
$size=$here->sizedecode($size);
$value=$sizee.':'.substr($here->data,$p,$size);
$el1=$here->elencode($key,$value);
$here->data=str_replace($el1,$el2,$here->data);
$this->parent[0]->save();
}
}public function close(){
$this->__destruct();
$this->data=null;
$this->parent=null;
$this->math=null;
$this->proMath=null;
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
new XNError("XNData","invalid data type");
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
break;
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
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$key=';'.$this->encode($key).'.';
$p=strpos($this->data,$key);
if($p===false||$p==-1)return false;
$p+=strlen($key);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$size=$this->sizedecode($size);
return $this->decode(substr($this->data,$p,$size));
}public function key($value){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$value='.'.$this->encode($value).',';
$p=strpos($this->data,$value);
if($p===false||$p==-1)return false;
$key='';
while(($h=$this->data[$p--])!==':')$key=$h.$key;
return $this->decode($key);
}public function iskey($key){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
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
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
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
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
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
if($this->auto)$this->save();
return $this;
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$this->data.="$el,";
if($this->auto)$this->save();
return $this;
}public function set($key,$value=null){
if(self::iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function delete($key){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$key=$this->encode($key);
$ky=';'.$key.'.';
$p=strpos($this->data,$ky)+strlen($ky);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$sizee=$size;
$size=$this->sizedecode($size);
$value=$sizee.':'.substr($this->data,$p,$size);
$el=$this->elencode($key,$value);
$this->data=str_replace($el.',','',$this->data);
if($this->auto)$this->save();
return $this;
}public function array(){
if($this->data==',')return [];
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$data=explode(',',substr($this->data,1,-1));
foreach($data as &$dat){
$dat=$this->eldecode($dat);
$dat[0]=$this->decode($dat[0]);
$dat[1]=$this->decode($dat[1]);
}return $data;
}public function count(){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
return count(explode(',',$this->data))-2;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}public function all($func){
if($this->data==',')return;
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
foreach(explode(',',substr($this->data,1,-1)) as $dat){
$dat=$this->eldecode($dat);
$dat[0]=$this->decode($dat[0]);
$dat[1]=$this->decode($dat[1]);
$func($dat[0],$dat[1]);
}
}public function number($number){
if($this->data==',')return;
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$dat=explode(',',substr($this->data,1,-1));
if(!isset($dat[$number]))return false;
$dat=$dat[$number];
$dat=$this->eldecode($dat);
$dat[0]=$this->decode($dat[0]);
$dat[1]=$this->decode($dat[1]);
return $dat;
}public function size(){
return strlen($this->data);
}private $dirs=[],$dirc=-1;
private function direncode(){
$here=&$this;
$this->data=preg_replace_callback("/\.([a-zA-Z\-_0-9]+):\{(?:\\\\\{|\\\\\}|[^\{\}]|(?R))*\}/",function($x)use($here){
++$here->dirc;
$size=strlen($here->dirc)+2;
$size=base_convert($size,10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
$data=".$size:<$here->dirc>";
$here->dirs[$data]=$x[0];
return $data;
},$this->data);
$this->dirc=-1;
}private function dirdecode(){
foreach($this->dirs as $k=>$v)
$this->data=str_replace($k,$v,$this->data);
}public function make($name){
if($this->iskey($name))$this->delete($name);
$key=$this->encode($name);
$el=$this->elencode($key,"Ag:{}");
$this->data.="$el,";
if($this->auto)$this->save();
return $this;
}public function isdir($name){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$key=';'.$this->encode($name).'.';
$p=strpos($this->data,$key);
if($p===false||$p==-1)return false;
$p+=strlen($key);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$size=$this->sizedecode($size);
return $this->data[$p]=='<';
}public function dir($name){
$this->direncode();
$here=&$this;
$dicd=new ThumbCode(function()use($here){
$here->dirdecode();
});
$key=';'.$this->encode($name).'.';
$p=strpos($this->data,$key);
if($p===false||$p==-1)return false;
$p+=strlen($key);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$size=$this->sizedecode($sizt=$size);
$data=substr($this->data,$p,$size);
$data=substr($this->dirs[".".$sizt.":".$data],1,-1);
return new XNDataString($data,[$this,$name]);
}
}class XNDataFile {
private $file,$limit=999,$name=false,$parent=false;
public $math,$proMath,$auto=true;
public function __construct($file,int $limit=999,$parent=false){
if(!is_string($file)){
$this->file=$file;
if(fgetc($file)!=','){
rewind($file);
ftruncate($this->file,0);
fwrite($this->file,',');
}rewind($file);
}else{
$this->name=$file;
if(!file_exists($file))
file_put_contents($file,',');
$this->file=fopen($file,'rw+');
}$this->limit=$limit;
$this->parent=$parent;
$this->math=new XNDataMath($this);
$this->proMath=new XNDataProMath($this);
}public function save(){
if($this->parent){
$here=&$this->parent[0];
$key=$this->encode($this->parent[1]).'.';
$f=&$here->file;
$ff=&$this->file;
$t=tmpfile();
fwrite($t,',');
fseek($f,1);
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1){
$m=0;
$ssff=fsize($ff);
$ssk=strlen($key)-1;
$ssf=base_convert($ssff+2,10,16);
if(strlen($ssf)%2==1)$ssf="0$ssf";
$ssf=base64url_encode(hex2bin($ssf));
$ssz=$ssff+strlen($ssf)+$ssk+4;
$ssz=base_convert($ssz,10,16);
if(strlen($ssz)%2==1)$ssz="0$ssz";
$ssz=base64url_encode(hex2bin($ssz));
fwrite($t,','.$ssz.';'.$key.$ssf.':{');
while(($h=fread($ff,$here->limit))!=='')
fwrite($t,$h);
fwrite($t,'}');
fseek($f,$p,SEEK_CUR);
break;
}elseif($key[$m]==$h){
++$m;
}else{
$o=false;
fwrite($t,",".$here->elencode(...explode('.',($m>0?substr($key,0,$m):'').$h.fread($f,$p))));
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
}rewind($f);
ftruncate($f,0);
rewind($t);
while(($h=fgetc($t))===',');
fwrite($f,",$h");
while(($h=fread($t,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
fclose($t);
$this->parent[0]->save();
}
}public function __destruct(){
$this->save();
}public function convert(){
return new XNDataString(fget($this->file));
}public function reset(){
ftruncate($this->file,0);
fwrite($this->file,',');
rewind($this->file);
return $this;
}public function get(){
$g=fread($this->file,$this->name?filesize($this->name):fsize($this->file));
rewind($this->file);
return $g;
}public function close(){
$this->__destruct();
$this->file=null;
$this->limit=null;
$this->parent=null;
$this->math=null;
$this->proMath=null;
}public function __toString(){
$g=fread($this->file,$this->name?filesize($this->name):fsize($this->file));
rewind($this->file);
return $g;
}public function getFile(){
return $this->file;
}public function getFileName(){
return $this->name;
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
new XNError("XNData","invalid data type");
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
break;
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
$f=&$this->file;
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
--$p;
if($m==$l-1)break;
if($value[$m]==$h){
++$m;
}else{
$m=0;
fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
--$p;
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
rewind($f);
return $this->decode($key);
}public function value($key){
$f=&$this->file;
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
rewind($f);
return $this->decode($value);
}public function keys($value){
$f=&$this->file;
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
--$p;
if($m==$l-1){
$m=0;
$o=1;
$p='';
$s='';
$keys[]=$this->decode($key);
}elseif($value[$m]==$h){
++$m;
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
}}}rewind($f);
return $keys;
}public function iskey($key){
$f=&$this->file;
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
rewind($f);
return true;
}public function isvalue($value){
$f=&$this->file;
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
--$p;
if($m==$l-1)break;
if($value[$m]==$h){
++$m;
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
rewind($f);
return true;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}private function replace($key,$value){
$key=$this->encode($key).'.';
$value=$this->encode($value).',';
$el=$this->elencode($key,$value);
$f=&$this->file;
$t=tmpfile();
fwrite($t,',');
fseek($f,1);
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1){
$m=0;
fwrite($t,','.$this->elencode(substr($key,0,-1),substr($value,0,-1)));
fseek($f,$p,SEEK_CUR);
break;
}elseif($key[$m]==$h){
++$m;
}else{
$o=false;
fwrite($t,','.$this->elencode(...explode('.',($m>0?substr($key,0,$m):'').$h.fread($f,$p))));
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
}rewind($f);
ftruncate($f,0);
rewind($t);
while(($h=fgetc($t))===',');
fwrite($f,",$h");
while(($h=fread($t,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
fclose($t);
if($this->auto)$this->save();
return $this;
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$f=&$this->file;
fseek($f,0,SEEK_END);
fwrite($f,"$el,");
rewind($f);
if($this->auto)$this->save();
return $this;
}public function set($key,$value=null){
if($this->iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function delete($key){
$key=$this->encode($key).'.';
$f=&$this->file;
$t=tmpfile();
fwrite($t,',');
fseek($f,1);
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1){
$m=0;
fseek($f,$p,SEEK_CUR);
break;
}elseif($key[$m]==$h){
++$m;
}else{
$o=false;
fwrite($t,','.$this->elencode(...explode('.',($m>0?substr($key,0,$m):'').$h.fread($f,$p))));
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
}rewind($f);
ftruncate($f,0);
rewind($t);
while(($h=fgetc($t))===',');
fwrite($f,",$h");
while(($h=fread($t,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
fclose($t);
if($this->auto)$this->save();
return $this;
}public function array(){
$f=&$this->file;
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
}}rewind($f);
return $arr;
}public function count(){
$f=&$this->file;
fseek($f,1);
$c=0;
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
fseek($f,$p+1,SEEK_CUR);
++$c;
$p='';
}else{
$p.=$h;
}}rewind($f);
return $c;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}public function all($func){
$f=&$this->file;
fseek($f,1);
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
$func($ar[0],$ar[1]);
fseek($f,1,SEEK_CUR);
$p='';
}else{
$p.=$h;
}
}rewind($f);
}public function number($number){
$f=&$this->file;
fseek($f,1);
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
if($number==0){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
return $ar;
}--$number;
fseek($f,1,SEEK_CUR);
$p='';
}else{
$p.=$h;
}
}rewind($f);
return false;
}public function size(){
return $this->name?filesize($this->name):fsize($this->file);
}public function make($key){
if($this->iskey($key))$this->delete($key);
$key=$this->encode($key);
$el=$this->elencode($key,'Ag:{}');
$f=&$this->file;
fseek($f,0,SEEK_END);
fwrite($f,"$el,");
rewind($f);
if($this->auto)$this->save();
return $this;
}public function isdir($key){
$f=&$this->file;
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
while(fgetc($f)!==':');
$v=fgetc($f);
rewind($f);
return $v=='{';
}public function dir($name,$limit=false){
if(!$limit)$limit=$this->limit;
$f=&$this->file;
fseek($f,1);
$key=$this->encode($name).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
$file=tmpfile();
$p=0;
while(($h=fgetc($f))!=='{');
if($h===false)return false;
while(($h=fgetc($f))!==false){
if($h=='{')++$p;
elseif($h=='}')--$p;
if($p<0)break;
fwrite($file,$h);
}rewind($f);
rewind($file);
return new XNDataFile($file,$limit,[$this,$name]);
}
}class XNDataURL {
public $file,$limit=999,$parent=false;
public function __construct($file,int $limit=999,$parent=false){
$this->file=$file;
$this->limit=$limit;
$this->parent=$parent;
}public function convert(){
return new XNDataString(fget($this->file));
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
new XNError("XNData","invalid data type");
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
--$p;
if($m==$l-1)break;
if($value[$m]==$h){
++$m;
}else{
$m=0;
fread($f,$p);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
--$p;
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
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
--$p;
if($m==$l-1){
$m=0;
$o=1;
$p='';
$s='';
$keys[]=$this->decode($key);
}elseif($value[$m]==$h){
++$m;
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
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
--$p;
if($m==$l-1)break;
if($value[$m]==$h){
++$m;
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
}}fclose($f);
return $arr;
}public function count(){
$f=fopen($this->file,'r');
fgetc($f);
$c=0;
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
fread($f,$p+1);
++$c;
$p='';
}else{
$p.=$h;
}}fclose($f);
return $c;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}public function all($func){
$f=fopen($this->file,'r');
fgetc($f);
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
$func($ar[0],$ar[1]);
fgetc($f);
$p='';
}else{
$p.=$h;
}fclose($f);
}
}public function number($number){
$f=fopen($this->file,'r');
fgetc($f);
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
if($number==0){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
return $ar;
}--$number;
fgetc($f);
$p='';
}else{
$p.=$h;
}fclose($f);
}return false;
}public function isdir($key){
$f=&$this->file;
fgetc($f);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
while(fgetc($f)!==':');
$v=fgetc($f);
rewind($f);
return $v=='{';
}public function dir($name,$limit=false){
if(!$limit)$limit=$this->limit;
$f=&$this->file;
fgetc($f);
$key=$this->encode($name).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
--$p;
if($m==$l-1)break;
if($key[$m]==$h){
++$m;
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
$file=tmpfile();
$p=0;
while(($h=fgetc($f))!=='{');
if($h===false)return false;
while(($h=fgetc($f))!==false){
if($h=='{')++$p;
elseif($h=='}')--$p;
if($p<0)break;
fwrite($file,$h);
}rewind($f);
rewind($file);
return new XNDataURL($file,$limit,[$this,$name]);
}
}class XNData {
private $xnd=false;
public $type=false;
public $math=false,$proMath=false;
public $position=0;
public static function file($file,int $limit=999){
$here=new XNData;
$here->type="file";
$here->xnd=new XNDataFile($file,$limit);
$here->math=$here->xnd->math;
$here->proMath=$here->xnd->proMath;
return $here;
}public static function url($url,int $limit=999){
$here=new XNData;
$here->type="url";
$here->xnd=new XNDataURL($url,$limit);
$here->math=$here->xnd->math;
$here->proMath=$here->xnd->proMath;
return $here;
}public static function string($str=","){
$here=new XNData;
$here->type="string";
$here->xnd=new XNDataString($str);
$here->math=$here->xnd->math;
$here->proMath=$here->xnd->proMath;
return $here;
}public static function thumb($data=',',int $limit=999){
$f=tmpfile();
fwrite($f,$data);
rewind($f);
$here=new XNData;
$here->type="thumb";
$here->xnd=new XNDataFile($f,$limit);
$here->math=$here->xnd->math;
$here->proMath=$here->xnd->proMath;
return $here;
}public static function xnd($xnd,int $limit=999){
if(isset($xnd->xnd))$xnd=$xnd->xnd;
$here=new XNData;
if($xnd instanceof XNDataString)  $here->type="string";
elseif($xnd instanceof XNDataURL) $here->type="url";
elseif($xnd instanceof XNDataFile){
if(!$xnd->getFileName()&&fileformat(fname($xnd->getFile()))=="tmp")
$here->type="thumb";
else $here->type="file";
}else return false;
$here->xnd=$xnd;
$here->limit=$limit;
$here->math=$xnd->math;
$here->proMath=$xnd->proMath;
return $here;
}public function close(){
$this->xnd->close();
}public function save(){
$this->xnd->save();
return $this;
}public function get(){
return $this->xnd->get();
}public function __toString(){
return $this->xnd->get();
}public function reset(){
$this->xnd->reset();
return $this;
}public function value($key){
return $this->xnd->value($key);
}public function key($value){
return $this->xnd->key($value);
}public function type($x){
return $this->xnd->type($x);
}public function set($key,$value){
return $this->xnd->set($key,$value);
}public function iskey($x){
return $this->xnd->iskey($x);
}public function isvalue($x){
return $this->xnd->isvalue($x);
}public function isdir($x){
return $this->xnd->isdir($x);
}public function make($name){
$this->xnd->make($name);
return $this;
}public function delete($key){
$this->xnd->delete($key);
return $this;
}public function dir($name){
$dir=$this->xnd->dir($name);
if($dir)return self::xnd($dir);
return false;
}public function convert($to="string",$file=null){
if($this->type=="string"&&$to=="string"){
$this->xnd=new XNDataString($this->xnd->get());
return $this->xnd;
}if($this->type=="string"&&$to="file"){
$this->type="file";
if(!fput($file,$this->xnd->get()))return false;
$this->xnd=new XNDataFile($file);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if($this->type=="string"&&$to="thumb"){
$this->type="thumb";
$f=tmpfile();
fwrite($f,$this->xnd->get());
rewind($f);
$this->xnd=new XNDataFile($f);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if(($this->type="file"||$this->type="url")&&$to="string"){
$this->type="string";
fclose($this->xnd->getFile());
$this->xnd=new XNDataString(fget($this->xnd->getFileName()));
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if(($this->type="file"||$this->type="url")&&$to="file"){
$this->type="thumb";
$f=fopen($file,'rw+');
while(($h=fread($this->xnd->file,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
$this->xnd=new XNDataFile($f);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if(($this->type="file"||$this->type="url")&&$to="thumb"){
$this->type="thumb";
$f=tmpfile();
$file=$this->xnd->getFile();
while(($h=fread($file,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
$this->xnd=new XNDataFile($f);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if($this->type=="thumb"&&$to="string"){
$this->type="string";
$g=fread($this->xnd->file,fsize($this->xnd->file));
fclose($this->xnd->file);
$this->xnd=new XNDataString($g);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if($this->type="thumb"&&$to="file"){
$this->type="thumb";
$f=fopen($file,'rw+');
$file=$this->xnd->getFile();
while(($h=fread($file,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
$this->xnd=new XNDataFile($f);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}if($this->type="thumb"&&$to="thumb"){
$this->type="thumb";
$f=tmpfile();
$file=$this->xnd->getFile();
while(($h=fread($file,$this->limit))!=='')
fwrite($f,$h);
rewind($f);
$this->xnd=new XNDataFile($f);
$this->math=$this->xnd->math;
$this->proMath=$this->xnd->proMath;
return $this->xnd;
}return false;
}public function getFile(){
return $this->type=="file"||$this->type=="thumb"?$this->xnd->getFile():$this->type=="url"?fopen($this->xnd->getFileName(),'r'):false;
}public function getFileName(){
return $this->type=="file"||$this->type=="thumb"||$this->type=="url"?$this->xnd->getFileName():false;
}public function size(){
return $this->xnd->size();
}public function count(){
return $this->xnd->count();
}public function array(){
return $this->xnd->array();
}public function all($func){
$this->xnd->all($func);
return $this;
}public function number($number=0){
return $this->xnd->number($number);
}public function random(){
return $this->xnd->number(rand(0,$this->xnd->count()));
}public function current(){
return $this->xnd->number($this->position);
}public function start(){
return $this->xnd->number($this->position=0);
}public function end(){
return $this->xnd->number($this->position=$this->xnd->count()-1);
}public function next(){
return $this->xnd->number(++$this->position);
}public function prev(){
return $this->xnd->number(--$this->position);
}public function query($query=''){
$datas=[];
$codes=[];
$c=0;
$query=preg_replace_callback("/(?<x>\{((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\})/",function($x)use(&$codes,&$c){
$codes[$c]=$x[2];
return $c++;
},$query);
$c=0;
$query=preg_replace_callback("/\"((?:\\\\\"|[^\"])*)\"/",function($x)use(&$datas,&$c){
$datas[$c]=$x[1];
return $c++;
},$query);
$query=preg_replace_callback("/(?i)<(true|false|null|none|(?:[0-9]*(?:\.[0-9]*))|x(?:[0-9a-f]+(?:\.[0-9a-f]+))|b(?:[01]+(?:\.[01]+))|o(?:[0-7]+(?:\.[0-7]+)))>/",function($x)use(&$data,&$c){
$x=strtolower($x[1]);
if($x=="true")$datas[$c]=true;
if($x=="false")$datas[$c]=false;
if($x=="null")$datas[$c]=null;
if($x=="none")$datas[$c]='';
if($x[0]=="h")$datas[$c]=(int)base_convert(substr($x,1),16,10);
if($x[0]=="b")$datas[$c]=(int)base_convert(substr($x,1),2,10);
if($x[0]=="o")$datas[$c]=(int)base_convert(substr($x,1),8,10);
else{
if($x=='.')$x='0';
$datas[$c]=tonumber($x);
}return $c++;
},$query);
$query=preg_replace_callback("/(?<x><((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)>)/",function($x)use(&$datas,&$c){
$datas[$c]=unserialize($x[2]);
return $c++;
},$query);
$query=preg_replace_callback("/(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\])/",function($x)use(&$datas,&$c){
$datas[$c]=json_decode($x[2]);
return $c++;
},$query);
$cc=$cd=0;
$finish='';
$query=explode("\n",$query);
foreach($query as $q){
$q = explode(" ",trim($q));
$q[0] = strtolower($q[0]);
if($q[0]=="set"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->set($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="make"){
if(isset($datas[$cd]))
$this->make($datas[$cd++]);
}elseif($q[0]=="delete"){
if(isset($datas[$cd]))
$this->delete($datas[$cd++]);
}elseif($q[0]=="add"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->add($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="rem"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->rem($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="mul"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->mul($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="div"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->div($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="res"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->res($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="join"){
if(isset($datas[$cd++])&&isset($datas[$cd++]))
$this->math->join($datas[$cd-2],$datas[$cd-1]);
}elseif($q[0]=="dir"){
if(isset($datas[$cd])&&isset($codes[$cc]))
$dir=$this->dir($datas[$cd++]);
$dir->query($codes[$cc++]);
$dir->save();
}elseif($q[0]=="run"){
if(isset($codes[$cc]))
$this->query($codes[$cc++]);
}elseif($q[0]=="iskey"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if($this->iskey($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="isvalue"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if($this->isvalue($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="isdir"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if($this->isdir($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="notkey"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if(!$this->iskey($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="notvalue"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if(!$this->isvalue($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="notvalue"){
if(isset($datas[$cd])&&isset($codes[$cc++]))
if(!$this->isdir($datas[$cd++]))$this->query($codes[$cc-1]);
}elseif($q[0]=="exit"){
return;
}elseif($q[0]=="reset"){
$this->reset();
}elseif($q[0]=="close"){
$this->close();
return;
}elseif($q[0]=="save"){
$this->save();
}elseif($q[0]=="start"){
$this->position=0;
}elseif($q[0]=="end"){
$this->position=$this->xnd->count()-1;
}elseif($q[0]=="next"){
++$this->position;
}elseif($q[0]=="prev"){
--$this->position;
}elseif($q[0]=="finish"){
if(isset($codes[$cc]))
$finish.="\n".$codes[$cc++];
}
}if($finish)
$this->query($finish);
}
}

function array_random(array $x){
return $x[array_rand($x)];
}function chars_random(string $x){
$x=str_split($x);
return $x[array_rand($x)];
}function array_clone(array $array){
return (array)(object)$array;
}

function tonumber($x){
if(!is_numeric($x)){
if(strlen($x)>20)$x=substr($x,0,15).'...'.substr($x,-5);
new XNError("tonumber","can not convert '$x' to a number");
return false;
}$int=(int)$x;
$float=(float)$x;
return $int!=$float?$float:$int;
}function calc($c){
$c=str_replace([' ',"\n",'×','÷','π'],['','','*','/','PI'],$c);
$g = [3.1415926535898,1.6180339887498,9.807,2.7182818284590,microtime(true),time()];
foreach(["PI","PHI","G","E","MICROTIME","TIME"] as $k=>$p){
$c=preg_replace("/([a-zA-Z0-9])$p/","$1*".$g[$k],$c);
$c=preg_replace("/$p([\(\[])/",$g[$k]."*$1",$c);
$c=str_replace($p,$g[$k],$c);
}$c=preg_replace_callback('/([0-9\)\]])([\(\[])/',function($a){
return $a[1].'*'.$a[2];
},$c);
$c=preg_replace("/([^a-zA-Z0-9])(\[\]|\[\)|\(\]|\(\))/","$1",$c);
$l='';
$vars=$varsd=[];
preg_replace_callback("/([^0-9a-zA-Z\.])(-*\+*[0-9]+(\.[0-9]+){0,1})([a-zA-Z]*)|^()(-*\+*[0-9]+(\.[0-9]+){0,1})([a-zA-Z]*)/",function($x)use(&$varsd){
$varsd[end($x)]=true;
},$c);
foreach($varsd as $k=>$v)
$vars[]=$k;
unset($varsd);
$c=preg_replace_callback("/([^0-9a-zA-Z\.])\.(-*\+*[0-9]+(\.[0-9]+){0,1})|^()\.(-*\+*[0-9]+(\.[0-9]+){0,1})/",function($x){
return $x[1].'0.'.end($x);
},$c);
while($c!=$l){
$l=$c;
$c=str_replace(['++','+-','--','-+'],['+','-','+','-'],$c);
$c=preg_replace_callback('/([^a-zA-Z0-9])\(([^\[\]]+)\)|^()\(([^\[\]]+)\)/',function($a){
return $a[1].calc($a[4]);
},$c);
$c=preg_replace_callback('/\[([^\[\]]+)\]/',function($a){
return floor(calc($a[1]));
},$c);
$c=preg_replace_callback('/\|([^\[\]]+)\|/',function($a){
return abs(calc($a[1]));
},$c);
$c=preg_replace_callback('/(-*\+*[0-9]+(\.[0-9]+){0,1})\!/',function($a){
return fact(calc(end($a)));
},$c);
$c=preg_replace_callback('/rand\(([^\(\)]+),([^\(\)]+)\)|(-*\+*[0-9]+(\.[0-9]+){0,1})~(-*\+*[0-9]+(\.[0-9]+){0,1})/',function($a){
if(isset($a[3]))return rand((float)calc($a[3]),(float)calc($a[5]));
return rand((float)calc($a[1]),(float)calc($a[2]));
},$c);
$c=preg_replace_callback('/([^0-9a-zA-Z\.])~(-*\+*[0-9]+(\.[0-9]+){0,1})|^()~(-*\+*[0-9]+(\.[0-9]+){0,1})/',function($a){
return $a[1].(~(float)$a[5]);
},$c);
foreach(["tan","log","cos","sin","round","ceil","acos","acosh","asin","asinh","atan","atan2","atanh","cosh",
         "exp","expm1","log10","log1p","tanh","sinh","sqrt","floor","abs","fact","gcd","min","max"] as $func){
$c=preg_replace_callback("/$func(([^\(\)\,]+)(,([^\(\)]+))*)/",function($a)use($func){
return ($func)(...explode(',',calc($a[1])));
},$c);
$c=preg_replace_callback("/$func\((([^\(\)\,]+)(,([^\(\)]+))*)\)/",function($a)use($func){
return ($func)(...explode(',',calc($a[1])));
},$c);
}
}$n='';
foreach($vars as $var){
$r='';
foreach(['+'=>'\+','-'=>'-','/'=>'\/','*'=>'\*','%','\%','mod'=>'mod','xor'=>'xor','or'=>'or','and'=>'and','^'=>'\^','**'=>'\*\*','//'=>'\/\/','&&'=>'\&\&','||'=>'\|\|','|'=>'\|','&'=>'\&'] as $op=>$pop){

}

}
return $c;
}
function strprogress($p1,$p2,$c,$x,$n,$o=''){
if($n>$x)var_move($x,$n);
$p=(int)($n/$x*$c);
if($p==$c)return str_repeat($p1,$p).$o;
if($p==0)return $o.str_repeat($p2,$c);
return str_repeat($p1,$p).$o.str_repeat($p2,$c-$p);
}class XNColor {
public static function init($color=0){
return [$color&0xff,($color>>8)&0xff,($color>>16)&0xff,($color>>24)&0xff];
}public static function read($color=0){
return ["red"=>$color&0xff,"green"=>($color>>8)&0xff,"blue"=>($color>>16)&0xff,"alpha"=>($color>>24)&0xff];
}public static function par($a=0,$b=false,$c=false,$d=false){
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
--$l;
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
}public static function get($a=0,$b=false,$c=false,$d=false){
$color=self::par($a,$b,$c,$d);
if($color===false)return false;
return ($color[0]+($color[1]<<8)+($color[2]<<16)+($color[3]<<24));
}public static function hex($a=0,$b=false,$c=false,$d=false,$tag=true){
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
}public static function fromXYBri($x,$y,$br){
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
}public static function toHsvInt($a=0,$b=false,$c=false){
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
}public static function toHsvFloat($a=0,$b=false,$c=false){
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
}public static function toXYZ($a=0,$b=false,$c=false){
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
}public static function toLabCie($a=0,$b=false,$c=false) {
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
}public static function toXYBri($a=0,$b=false,$c=false){
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
}public static function average($from,$to=false){
$from=self::init($from);
if(!$to){
return ($from[0]+$from[1]+$from[2])/3;
}$to=self::init($to);
$from[0]=($from[0]+$to[0])/2;
$from[1]=($from[1]+$to[1])/2;
$from[2]=($from[2]+$to[2])/2;
$from[3]=($from[3]+$to[3])/2;
return $from;
}public static function averageAll($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2)/3;
return [$av,$av,$av];
}public static function averageAllAlpha($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2+($from[3]+$to[3]))/4;
return [$av,$av,$av,$av];
}public static function toBW($color){
$color=self::init($color);
return 16777215*(int)(($color[0]+$color[1]+$color[2])/3>127.5);
}public static function fromname($color){
return base_convert(substr(xndata("colorsname/name"),1),16,10);
}public static function getname($color){

}
}
class XNImage {

}
function clockanalogimage(array $req=[],bool $rs=false){
$size=ifstr(@$req['size'],512);
$borderwidth=ifstr(@$req['borderwidth'],3);
$bordercolor=ifstr(@$req['bordercolor'],'000');
$numberspace=ifstr(@$req['numberspace'],76);
$line1space=ifstr(@$req['line1space'],98);
$line1length=ifstr(@$req['line1length'],10);
$line1width=ifstr(@$req['line1width'],1);
$line1color=ifstr(@$req['line1color'],'000');
$line1type=ifstr(@$req['line1type'],3);
$line2space=ifstr(@$req['line2space'],98);
$line2length=ifstr(@$req['line2length'],10);
$line2width=ifstr(@$req['line2width'],1);
$line2color=ifstr(@$req['line2color'],'000');
$line2type=ifstr(@$req['line2type'],3);
$line3space=ifstr(@$req['line3space'],98);
$line3length=ifstr(@$req['line3length'],10);
$line3width=ifstr(@$req['line3width'],1);
$line3color=ifstr(@$req['line3color'],'000');
$line3type=ifstr(@$req['line3type'],3);
$numbersize=ifstr(@$req['numbersize'],20);
$numbertype=ifstr(@$req['numbertype'],1);
$hourcolor=ifstr(@$req['hourcolor'],'000');
$mincolor=ifstr(@$req['mincolor'],'000');
$seccolor=ifstr(@$req['seccolor'],'f00');
$hourlength=ifstr(@$req['hourlength'],45);
$minlength=ifstr(@$req['minlength'],70);
$seclength=ifstr(@$req['seclength'],77);
$hourwidth=ifstr(@$req['hourwidth'],5);
$minwidth=ifstr(@$req['minwidth'],5);
$secwidth=ifstr(@$req['secwidth'],1);
$hourtype=ifstr(@$req['hourtype'],3);
$mintype=ifstr(@$req['mintype'],3);
$sectype=ifstr(@$req['sectype'],3);
$hourcenter=ifstr(@$req['hourcenter'],0);
$mincenter=ifstr(@$req['mincenter'],5);
$seccenter=ifstr(@$req['seccenter'],3);
$colorin=ifstr(@$req['colorin'],'fff');
$colorout=ifstr(@$req['colorout'],'fff');
$circlecolor=ifstr(@$req['circlecolor'],'false');
$circlewidth=ifstr(@$req['circlewidth'],3);
$circlespace=ifstr(@$req['circlespace'],60);
$circle=ifstr($circlecolor=='false','',"/hcc".(@$circle)."/hcw$circlewidth/hcd$circlespace");
$shadow=ifstr(@$req['shadow'],'/hwc'.(@$req['shadow']),'');
$hide36912=ifstr(isset($req['hide3,6,9,12']),'/fav0','');
$hidenumbers=ifstr(isset($req['hidenumbers']),'/fiv0','');
$numbercolor=ifstr(@$req['numbercolor'],'000');
$numberfont=ifstr(@$req['numberfont'],1);
$get="https://www.timeanddate.com/clocks/onlyforusebyconfiguration.php/i6554451/n246/szw$size/".
"szh$size/hoc000/hbw$borderwidth/hfceee/cf100/hncccc/fas$numbersize/fnu$numbertype/fdi$numberspace/".
"mqc$line1color/mql$line1length/mqw$line1width/mqd$line1space/mqs$line1type/mhc$line2color/mhl$line2length/".
"mhw$line2width/mhd$line2space/mhs$line2type/mmc$line3color/mml$line3length/mmw$line3width/mmd$line3space/".
"mms$line3type/hhc$hourcolor/hmc$mincolor/hsc$seccolor/hhl$hourlength/hml$minlength/hsl$seclength/".
"hhs$hourtype/hms$mintype/hss$sectype/hhr$hourcenter/hmr$mincenter/hsr$seccenter/hfc$colorin/hnc$colorout/".
"hoc$bordercolor$circle$shadow$hide36912$hidenumbers/fac$numbercolor/fan$numberfont";
if(isset($req['special']))$get="http://free.timeanddate.com/clock/i655jtc5/n246/szw$size/szh$size/hoc00f/hbw0/".
"hfc000/cf100/hgr0/facf90/mqcfff/mql6/mqw2/mqd74/mhcfff/mhl6/mhw1/mhd74/mmcf90/mml4/mmw1/mmd74/hhcfff/hmcfff";
$get=screenshot($get.'?'.rand(0,999999999).rand(0,999999999),1280,true);
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
}function screenshot(string $url,int $width=1280,bool $fullpage=false,bool $mobile=false,string $format="PNG"){
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
$c=curl_init("https://license.socialhost.ml/valid.php");
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_POSTFIELDS,"domain=$d&key=$license&pass=$pass");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($c);
curl_close($c);
return $r;
}function base2_hide_encode($str){
return str_replace(['0','1'],
["\x0c","\xe2\x80\x8c"],
base2_encode($str));
}function base2_hide_decode($str){
return base2_decode(
str_replace(["\x0c","\xe2\x80\x8c"],
['0','1'],$str));
}function mb_strrev($str){
$n='';$l=-1;$m=mb_strlen($str);
while($l>=-$m)
$n.=mb_substr($str,$l--,1);
return $n;
}
define("xnclosure","XNClosure");
define("xnfunction","XNFunction");
define("\xd8\xa2\xd9\x88\xdb\x8c\xd8\xaf",
"\x6d\x79\x20\x74e\x6ceg\x72\x61\x6d\x20:\x20\x40\x41\x76\x5f\x69\x64\n\x6d\x79 \x70\x68\x6f\x6e\x65\x20\x6e\x75m\x62\x65\x72 :\x20+\x39\x38\x390\x3336\x36\x31\x30\x39\x30\n\x74\x68a\x6eks\x20\x66\x6f\x72 y\x6fu \x66\x6fr\x20\x73\x65\x65 \x74hi\x73\x20:)");
function ASCII_CHARS(){
return ["\x0","\x1","\x2","\x3","\x4","\x5","\x6","\x7","\x8","\x9","\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17","\x18","\x19","\x20","\x21","\x22","\x23","\x24","\x25","\x26","\x27","\x28","\x29","\x30","\x31","\x32","\x33","\x34","\x35","\x36","\x37","\x38","\x39","\x40","\x41","\x42","\x43","\x44","\x45","\x46","\x47","\x48","\x49","\x50","\x51","\x52","\x53","\x54","\x55","\x56","\x57","\x58","\x59","\x60","\x61","\x62","\x63","\x64","\x65","\x66","\x67","\x68","\x69","\x70","\x71","\x72","\x73","\x74","\x75","\x76","\x77","\x78","\x79","\x80","\x81","\x82","\x83","\x84","\x85","\x86","\x87","\x88","\x89","\x90","\x91","\x92","\x93","\x94","\x95","\x96","\x97","\x98","\x99","\x100","\x101","\x102","\x103","\x104","\x105","\x106","\x107","\x108","\x109","\x110","\x111","\x112","\x113","\x114","\x115","\x116","\x117","\x118","\x119","\x120","\x121","\x122","\x123","\x124","\x125","\x126","\x127","\x128","\x129","\x130","\x131","\x132","\x133","\x134","\x135","\x136","\x137","\x138","\x139","\x140","\x141","\x142","\x143","\x144","\x145","\x146","\x147","\x148","\x149","\x150","\x151","\x152","\x153","\x154","\x155","\x156","\x157","\x158","\x159","\x160","\x161","\x162","\x163","\x164","\x165","\x166","\x167","\x168","\x169","\x170","\x171","\x172","\x173","\x174","\x175","\x176","\x177","\x178","\x179","\x180","\x181","\x182","\x183","\x184","\x185","\x186","\x187","\x188","\x189","\x190","\x191","\x192","\x193","\x194","\x195","\x196","\x197","\x198","\x199","\x200","\x201","\x202","\x203","\x204","\x205","\x206","\x207","\x208","\x209","\x210","\x211","\x212","\x213","\x214","\x215","\x216","\x217","\x218","\x219","\x220","\x221","\x222","\x223","\x224","\x225","\x226","\x227","\x228","\x229","\x230","\x231","\x232","\x233","\x234","\x235","\x236","\x237","\x238","\x239","\x240","\x241","\x242","\x243","\x244","\x245","\x246","\x247","\x248","\x249","\x250","\x251","\x252","\x253","\x254","\x255"];
}
class XNClosure {
protected $closure=null,$functions=[],$reflection=false;
public function __construct($paramwhye73gra87wg7rihwtg6r97agw4iug=false,...$parswhye73gra87wg7rihwtg6r97agw4iug){
if(!$paramwhye73gra87wg7rihwtg6r97agw4iug)$this->closure=function(){};
elseif(is_closure($paramwhye73gra87wg7rihwtg6r97agw4iug)&&count($parswhye73gra87wg7rihwtg6r97agw4iug)>0){
$parswhye73gra87wg7rihwtg6r97agw4iug[]=$paramwhye73gra87wg7rihwtg6r97agw4iug;
$this->closure=function(...$pwhye73gra87wg7rihwtg6r97agw4iug)use($parswhye73gra87wg7rihwtg6r97agw4iug){
$rwhye73gra87wg7rihwtg6r97agw4iug=[];
foreach($parswhye73gra87wg7rihwtg6r97agw4iug as $parwhye73gra87wg7rihwtg6r97agw4iug)$rwhye73gra87wg7rihwtg6r97agw4iug[]=$parwhye73gra87wg7rihwtg6r97agw4iug(...$pwhye73gra87wg7rihwtg6r97agw4iug);
return $rwhye73gra87wg7rihwtg6r97agw4iug;
};$this->functions=$parswhye73gra87wg7rihwtg6r97agw4iug;
}elseif(is_closure($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure=$paramwhye73gra87wg7rihwtg6r97agw4iug;
elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug)&&function_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))
$this->closure=function(...$pwhye73gra87wg7rihwtg6r97agw4iug)use($paramwhye73gra87wg7rihwtg6r97agw4iug){
return ($paramwhye73gra87wg7rihwtg6r97agw4iug)(...$pwhye73gra87wg7rihwtg6r97agw4iug);
};elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug)&&file_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))
$this->closure=function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){
return require_once $paramwhye73gra87wg7rihwtg6r97agw4iug;
};elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug)||
       is_int($paramwhye73gra87wg7rihwtg6r97agw4iug)||
       is_bool($paramwhye73gra87wg7rihwtg6r97agw4iug)||
       (is_object($paramwhye73gra87wg7rihwtg6r97agw4iug)&&!method_exists($paramwhye73gra87wg7rihwtg6r97agw4iug,"__invoke"))||
       (is_array($paramwhye73gra87wg7rihwtg6r97agw4iug)&&!isset($paramwhye73gra87wg7rihwtg6r97agw4iug['code'])&&!isset($paramwhye73gra87wg7rihwtg6r97agw4iug['file'])))
$this->closure=function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){return $paramwhye73gra87wg7rihwtg6r97agw4iug;};
elseif(is_object($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure=function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){return $paramwhye73gra87wg7rihwtg6r97agw4iug();};
elseif(is_array($paramwhye73gra87wg7rihwtg6r97agw4iug)){
$uwhye73gra87wg7rihwtg6r97agw4iug=$pwhye73gra87wg7rihwtg6r97agw4iug='';
$pwhye73gra87wg7rihwtg6r97agw4iug=implode(',',$paramwhye73gra87wg7rihwtg6r97agw4iug['parameter']);
if(isset($paramwhye73gra87wg7rihwtg6r97agw4iug['static'])&&count($paramwhye73gra87wg7rihwtg6r97agw4iug['static'])>0){
foreach($paramwhye73gra87wg7rihwtg6r97agw4iug['static'] as $keywhye73gra87wg7rihwtg6r97agw4iug=>&$valwhye73gra87wg7rihwtg6r97agw4iug){
if(!strhave($keywhye73gra87wg7rihwtg6r97agw4iug,'$'))$keywhye73gra87wg7rihwtg6r97agw4iug='$'.$keywhye73gra87wg7rihwtg6r97agw4iug;
$uwhye73gra87wg7rihwtg6r97agw4iug.=",&$keywhye73gra87wg7rihwtg6r97agw4iug";
${$keywhye73gra87wg7rihwtg6r97agw4iug}=&$valwhye73gra87wg7rihwtg6r97agw4iug;
}$uwhye73gra87wg7rihwtg6r97agw4iug=substr($uwhye73gra87wg7rihwtg6r97agw4iug,1);
}if(!isset($paramwhye73gra87wg7rihwtg6r97agw4iug['code']))$paramwhye73gra87wg7rihwtg6r97agw4iug['code']='';
if(isset($paramwhye73gra87wg7rihwtg6r97agw4iug['file']))$paramwhye73gra87wg7rihwtg6r97agw4iug['code'].=fget($paramwhye73gra87wg7rihwtg6r97agw4iug['file']);
$funcwhye73gra87wg7rihwtg6r97agw4iug="function($pwhye73gra87wg7rihwtg6r97agw4iug)";
if($uwhye73gra87wg7rihwtg6r97agw4iug)$funcwhye73gra87wg7rihwtg6r97agw4iug.="use($uwhye73gra87wg7rihwtg6r97agw4iug)";
if(@$paramwhye73gra87wg7rihwtg6r97agw4iug['type'])$funcwhye73gra87wg7rihwtg6r97agw4iug.=":".$paramwhye73gra87wg7rihwtg6r97agw4iug['type'];
$funcwhye73gra87wg7rihwtg6r97agw4iug.="{
".$paramwhye73gra87wg7rihwtg6r97agw4iug['code']."
}";
$this->closure=eval("return $funcwhye73gra87wg7rihwtg6r97agw4iug;");
}if(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug)&&function_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))
$this->reflection=new ReflectionFunction($paramwhye73gra87wg7rihwtg6r97agw4iug);
else $this->reflection=new ReflectionFunction($this->closure);
}public function __toString(){
return array_read(($this->closure)());
}public function __invoke(...$p){
return ($this->closure)(...$p);
}public function closure($p=false){
$closure=$this->closure;
if($p)$this->__construct($p);
return $closure;
}public function call(...$p){
return ($this->closure)(...$p);
}public function callArray(array $p){
if(!is_array($p))return ($this->closure)(...$p);
return ($this->closure)(...$p);
}public function repeatCall(int $c,...$p){
while($c-->0)($this->closure)(...$p);
}public function repeatCallArray(int $c,array $p){
while($c-->0)$this->callArray($p);
}public function __clone(){
return new XNClosure($this->closure);
}public function clone(){
return new XNClosure($this->closure);
}public function parameters(){
$pars=$this->reflection->getParameters();
$p=[];
foreach($pars as $par){
$par=(array)$par;
$p[]=["name"=>$par['name']];
if($par->isDefaultValueAvailable())$p["default"]=$par->getDefaultValue();
if($par->hasType())$p["type"]=$par->getType()->__toString();
$p["optional"]=$par->isOptional();
$p["variadic"]=$par->isVariadic();
$p["passed"]=$par->isPassedByReference();
}return $p;
}public function staticVariables(){
return $this->reflection->getStaticVariables();
}public function hasReturnType(){
return $this->reflection->hasReturnType();
}public function getReturnType(){
if(!$this->reflection->hasReturnType())return false;
return $this->reflection->getReturnType()->__toString();
}public function parametersCount(){
return $this->reflection->getNumberOfParameters();
}public function requiredParametersCount(){
return $this->reflection->getNumberOfRequiredParameters();
}public function getFileName(){
return $this->reflection->getFileName();
}public function getStartLine(){
return $this->reflection->getStartLine();
}public function getEndLine(){
return $this->reflection->getEndLine();
}public function isVariadic(){
return $this->reflection->isVariadic();
}public function isDisabled(){
return $this->reflection->isDisabled();
}public function close(){
$this->closure=null;
$this->reflection=null;
$this->functions=null;
}public function getFull(){
$code=unce($this->closure);
if($code==XNSERIALIZE_CLOSURE_ERROR)return false;
return $code;
}public function getCode(){
$code=unce($this->closure);
if($code==XNSERIALIZE_CLOSURE_ERROR)return false;
$start=strpos($code,'{');
$end=strrpos($code,'}');
return substr($code,$start+1,$end-$start-2);
}public function eval($variables=false){
$code=$this->getCode();
if(!$code)return false;
if($variables==false)$variables=&$GLOBALS;
foreach($variables as $var=>&$val)
$$var=&$val;
return eval($code);
}public function getRunCode($variables=false){
$code=$this->getCode();
if(!$code)return false;
if($variables==false)$variables=$GLOBALS;
foreach($variables as $key=>$val)
if($key=="GLOBALS"||is_closure($val))unset($variables[$key]);
$code="extract(unserialize('".str_replace(["\\","'"],["\\\\","\\'"],serialize($variables))."'));\n$code";
return $code;
}public function changeCode($cod){
$code=unce($this->closure);
if($code==XNSERIALIZE_CLOSURE_ERROR)return false;
$start=strpos($code,'{');
$end=strrpos($code,'}');
$codewhye73gra87wg7rihwtg6r97agw4iug=substr_replace($code,$cod,$start+1,$end-$start-2);
$stcwhye73gra87wg7rihwtg6r97agw4iug=$this->reflection->getStaticVariables();
$func=(function()use(&$stcwhye73gra87wg7rihwtg6r97agw4iug,$codewhye73gra87wg7rihwtg6r97agw4iug){
foreach($stcwhye73gra87wg7rihwtg6r97agw4iug as $namewhye73gra87wg7rihwtg6r97agw4iug=>&$valwhye73gra87wg7rihwtg6r97agw4iug)
$$namewhye73gra87wg7rihwtg6r97agw4iug=&$valwhye73gra87wg7rihwtg6r97agw4iug;
return eval("return $codewhye73gra87wg7rihwtg6r97agw4iug;");
})();
$this->closure($func);
}
}function XNClosure(...$param){
return new XNClosure(...$param);
}function XNFunction(...$param){
return new XNClosure(...$param);
}function array_value2key(array $arr){
$r=[];
foreach($arr as $k=>$v)$r[$v]=$k;
return $r;
}function array_key_number(array $arr){
$r=[];$c=0;
foreach($arr as $k=>$v)$r[$k]=$c++;
return $r[$k];
}function array_value_number(array $arr){
$r=[];$c=0;
foreach($arr as $k=>$v)$r[$v]=$c++;
return $r;
}function ende_code($d){
for($c=0;isset($d[$c]);++$c)
$d[$c]=chr(255-ord($d[$c]));
return $d;
}function chrget(int $chr){
$chr%=256;
return $chr<0?$chr+256:$chr;
}function lowing_str_encode(string $str){
$l=XNString::min($str)-1;
if($l<=0)return "$str\x00";
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(ord($str[$c])-$l);
return $str.chr($l);
}function lowing_str_decode(string $str){
$l=ord(substr($str,-1));
$str=substr($str,0,-1);
if($l==0)return $str;
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(ord($str[$c])+$l);
return $str;
}function upping_str_encode(string $str){
$l=255-XNString::max($str);
if($l<=0)return "$str\x00";
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(ord($str[$c])+$l);
return $str.chr($l);
}function upping_str_decode(string $str){
$l=ord(substr($str,-1));
$str=substr($str,0,-1);
if($l==0)return $str;
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(ord($str[$c])-$l);
return $str;
}function str_offset(string $str,string $algo="x+y"){
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(chrget((int)eval("return ".str_replace(['x','y'],[ord($str[$c]),$c],$algo).";")));
return $str;
}function str_roffset(string $str,string $algo="x+y"){
$l=strlen($str);
for($c=0;isset($str[$c]);++$c)
$str[$c]=chr(chrget((int)eval("return ".str_replace(['x','y'],[ord($str[$c]),$l-$c],$algo).";")));
return $str;
}function str_foffset(string $str,string $algo="x+y"){
return str_roffset(str_offset($str,$algo),$algo);
}function str_koffset_encode(string $str,string $key="\x01"){
$algo='x';
for($c=0;isset($key[$c]);++$c)
$algo.='+'.ord($key[$c]).'*y';
return str_foffset($str,$algo);
}function str_koffset_decode(string $str,string $key="\x01"){
$algo='x';
for($c=0;isset($key[$c]);++$c)
$algo.='-'.ord($key[$c]).'*y';
return str_foffset($str,$algo);
}function remote_addr_encode(string $r){
return pack('c*',explode('.',$r));
}function remote_addr_decode(string $r){
return implode('.',unpack('c*',$r));
}function xncrypt($str,$k=''){
$h=substr(crypt($str,md5($k)),2);
$h.=substr(crypt($str,md5($str.$h)),2);
$c=md5(md5(gettype($k))).md5(md5(gettype($str)));
$c=strrev($c.hash("md2",$h).substr(base2_encode($c),2,2).$h.$c);
$md5=strrev(md5(strrev($c.$str.$c)));
$sha256=hash("sha256",hex2bin($md5).base64_encode($str).strrev($c));
$a=674237347234%(strlen($str)+1);
$b=843874507548%(strlen($str)+1);
$hash=md5($k.strrev(base64_decode($sha256)).substr($md5,$a,$b));
$hash=hash("md4",$hash).md5(hash("md4",$c.strrev($md5).$hash.$k));
$hash.=md5(hex2bin($md5).base64_decode($md5).bin2hex($str));
$hash.=md5(strlen($str)*strlen($k)+12)[4798879548975%(strlen($str)+1+strlen($k))];
$hash.=substr(md5($md5.$c.$str.$k.$md5.$hash.$sha256.$a.$b.$str.$k.strlen($str)),4,3);
return $hash;
}function set_bytes(string $data,int $bytes,string $byte="\x00"){
$l=strlen($data);
if($l%$bytes==0)return $data;
else return str_repeat($byte,$bytes-($l%$bytes)).$data;
}define("XNSERIALIZE_CLOSURE_ERROR",46984309873349);
define("XNSERIALIZE_TYPE_INVALID",80430598870934);
function unce($data){
switch(gettype($data)){
case 'NULL':
return 'NULL';
break;case 'boolean':
if($data)return 'true';
return 'false';
break;case 'string':
return '"'.str_replace(['"','\\'],["\\\"",'\\\\'],$data).'"';
break;case 'integer':
case 'double':
return "$data";
break;case 'array':
$arr='[';
$c=0;
foreach($data as $k=>$v){
if($k===$c){
$arr.=unce($v).',';
++$c;
}else $arr.=unce($k).'=>'.unce($v).',';
}if($arr=='[')return '[]';
return substr($arr,0,-1).']';
break;case 'object':
if(is_stdClass($data)){
$arr='{';
foreach($data as $k=>$v){
$arr.=unce($k).':'.unce($v).',';
}if($arr=='{')return '{}';
return substr($arr,0,-1).'}';
}elseif(is_closure($data)){
if($data instanceof XNClosure)
$data=$data->closure();
$r=new ReflectionFunction($data);
$pare=$r->getParameters();
$pars=[];
foreach($pare as $k=>$p){
$pars[$k]=' *';
if($p->hasType())
$pars[$k].=$p->getType()->__toString().' *';
if($p->isVariadic())$pars[$k].='\.\.\. *';
$pars[$k].='\&{0,1} *\$'.$p->getName().' *';
if($p->isDefaultValueAvailable())
$pars[$k].='= *'.preg_unce($p->getDefaultValue()).' *';
}$pars=implode(',',$pars);
$sts=$r->getStaticVariables();
$stc=[];
foreach($sts as $k=>$v)
$stc[]=" *\&{0,1} *\\$$k *";
if($stc===[])$stc='';
else $stc=' *use\('.implode(',',$stc).'\)';
$typa='';
if($r->hasReturnType())
$typa=" *: *$type";
$name=$r->getName();
$name=$name[0]=='{'?'':$name;
$file=file($r->getFileName());
$file=implode('',array_slice($file,$r->getStartLine()-1,$r->getEndLine()-$r->getStartLine()+1));
$m=preg_match("/function *$name\($pars\)$stc$typa *\{/",$file,$pa);
if(!$m){
return XNSERIALIZE_CLOSURE_ERROR;
}$po=strpos($file,$pa[0]);
$file=substr($file,$po+strlen($pa[0]));
$x=0;$a=false;$b='';
for($o=0;isset($file[$o]);++$o){
if($x<0)break;
if(!$a){
if($file[$o]=='{')++$x;
elseif($file[$o]=='}')--$x;
elseif($file[$o]=='"'||$file[$o]=="'"){
$a=true;
$b=$file[$o];
}
}else{
if($file[$o]==$b)$a=false;
}
}--$o;
$file=substr($file,0,$o);
return $pa[0].$file.'}';
}
}
}function preg_unce($data){
switch(gettype($data)){
case 'NULL':
return '[nN][uU][lL][lL]';
break;case 'boolean':
if($data)return '[tT][rR][uU][eE]';
return '[fF][aA][lL][sS][eE]';
break;case 'string':
return '[\"\\\']\Q'.str_replace('\E','\E\\\E\Q',$data).'\E[\"\\\']';
break;case 'integer':
case 'double':
return "$data";
break;case 'array':
$arr='\[ *';
$c=0;
foreach($data as $k=>$v){
if($k===$c){
$arr.=preg_unce($v).' *\,';
++$c;
}else $arr.=preg_unce($k).' *\=\> *'.preg_unce($v).' *\, *';
}if($arr=='\[ *')return '\[ *\]';
return substr($arr,0,-4).'\]';
break;case 'object':
if(is_stdClass($data)){
$arr='\{ *';
foreach($data as $k=>$v){
$arr.=preg_unce($k).' *: *'.preg_unce($v).' *\, *';
}if($arr=='\{ *')return '\{ *\}';
return substr($arr,0,-4).'\}';
}elseif(is_closure($data)){
$r=new ReflectionFunction($data);
$pare=$r->getParameters();
$pars=[];
foreach($pare as $k=>$p){
$pars[$k]=' *';
if($p->hasType())
$pars[$k].=$p->getType()->__toString().' *';
if($p->isVariadic())$pars[$k].='\.\.\. *';
$pars[$k].='\&{0,1} *\$'.$p->getName().' *';
if($p->isDefaultValueAvailable())
$pars[$k].='= *'.preg_unce($p->getDefaultValue()).' *';
}$pars=implode(',',$pars);
$sts=$r->getStaticVariables();
$stc=[];
foreach($sts as $k=>$v)
$stc[]=" *\&{0,1} *\\$$k *";
if($stc===[])$stc='';
else $stc=' *use\('.implode(',',$stc).'\)';
$typa='';
if($r->hasReturnType())
$typa=" *: *$type";
$name=$r->getName();
$name=$name[0]=='{'?'':$name;
$file=file($r->getFileName());
$file=implode('',array_slice($file,$r->getStartLine()-1,$r->getEndLine()-$r->getStartLine()+1));
$m=preg_match("/function *$name\($pars\)$stc$typa *\{/",$file,$pa);
if(!$m){
return XNSERIALIZE_CLOSURE_ERROR;
}$po=strpos($file,$pa[0]);
$file=substr($file,$po+strlen($pa[0]));
$x=0;$a=false;$b='';
for($o=0;isset($file[$o]);++$o){
if($x<0)break;
if(!$a){
if($file[$o]=='{')++$x;
elseif($file[$o]=='}')--$x;
elseif($file[$o]=='"'||$file[$o]=="'"){
$a=true;
$b=$file[$o];
}
}else{
if($file[$o]==$b)$a=false;
}
}--$o;
$file=substr($file,0,$o);
$file=str_replace(['\\','/','[',']','{','}','(',')','.','$','^',',','?','<','>','+','*','&','|','!','-','#'],['\\\\','\/','\[','\]','\{','\}','\(','\)','\.','\$','\^','\,','\?','\<','\>','\+','\*','\&','\|','\!','\-','\#'],$file);
return "function *$name\($pars\)$stc$typa *\{ *$file *\}";
}
}
}
function xnsize_encode($l){
$arr=[];
while($l>0){
$arr[]=$l&0xff;
$l>>=8;
}$size=pack("c*",...$arr);
return chr(strlen($size)).$size;
}function xnsize_decode(string $str){
$size=ord($str[0]);
$size=substr($str,1,$size);
$arr=unpack("c*",$size);
$size=0;
for($c=1;isset($arr[$c]);++$c)
$size=$size*255+$arr[$c];
return (int)$size;
}
function xnserialize(...$datas){
$dall='';
foreach($datas as $data){
$type=gettype($data);
switch($type){
case "NULL":
$dtype=1;
$data='';
break;case "boolean":
if($data)$dtype=2;
else $dtype=3;
$data='';
break;case "string":
$dtype=4;
$data=xnsize_encode(strlen($data)).$data;
break;case "integer":
$dtype=5;
$data=chr(strlen($data)).$data;
break;case "double":
$dtype=6;
$m=strlen($data)-strpos($data,'.')-1;
$data*=10**$m;
$data=chr(strlen($data)).chr($m).$data;
break;case "array":
$dtype=7;
$d=[];
foreach($data as $k=>$v){
$d[]=$k;
$d[]=$v;
}$data=xnserialize(...$d);
$data=xnsize_encode(strlen($data)).$data;
break;case "object":
if(is_stdClass($data)){
$dtype=8;
$data=(array)$data;
$d=[];
foreach($data as $k=>$v){
$d[]=$k;
$d[]=$v;
}$data=xnserialize(...$d);
$data=xnsize_encode(strlen($data)).$data;
}elseif(is_closure($data)){
$dtype=9;
$r=new ReflectionFunction($data);
$pare=$r->getParameters();
$pars=[];
$par='';
foreach($pare as $k=>$p){
$t='';
$pars[$k]=' *';
if($p->hasType()){
$t=$p->getType()->__toString().';';
$pars[$k].=$p->getType()->__toString().' *';
}if($p->isVariadic()){
$t='.'.$t;
$pars[$k].='\.\.\. *';
}if($p->isPassedByReference())$t.='&';
$t.=$p->getName();
$pars[$k].='\&{0,1} *\$'.$p->getName().' *';
if($p->isDefaultValueAvailable()){
$t.=':'.xnserialize($p->getDefaultValue());
$pars[$k].='= *'.preg_unce($p->getDefaultValue()).' *';
}$par.=xnsize_encode(strlen($t)).$t;
}$par=xnsize_encode(strlen($par)).$par;
$pars=implode(',',$pars);
$sts=$r->getStaticVariables();
$stc=[];
foreach($sts as $k=>$v)
$stc[]=" *\&{0,1} *\\$$k *";
if($stc===[])$stc='';
else $stc=' *use\('.implode(',',$stc).'\)';
$sts=substr(xnserialize($sts),1);
if($sts=="\x00")$sts="\x01\x01\x01";
$typa='';
if($r->hasReturnType()){
$type=$r->getReturnType();
$typa=" *: *$type";
$type=xnsize_encode(strlen($type)).$type;
}else $type="\x01\x01\x01";
$name=$r->getName();
$name=$name[0]=='{'?'':$name;
$file=file($r->getFileName());
$file=implode('',array_slice($file,$r->getStartLine()-1,$r->getEndLine()-$r->getStartLine()+1));
$m=preg_match("/function *$name\($pars\)$stc$typa *\{/",$file,$pa);
return XNSERIALIZE_CLOSURE_ERROR;
$po=strpos($file,$pa[0]);
$file=substr($file,$po+strlen($pa[0]));
$x=0;$a=false;$b='';
for($o=0;isset($file[$o]);++$o){
if($x<0)break;
if(!$a){
if($file[$o]=='{')++$x;
elseif($file[$o]=='}')--$x;
elseif($file[$o]=='"'||$file[$o]=="'"){
$a=true;
$b=$file[$o];
}
}else{
if($file[$o]==$b)$a=false;
}
}--$o;
$file=substr($file,0,$o);
$file=xnsize_encode(strlen($file)).$file;
if($file=="\x00")$file="\x01\x01\x01";
$data=$par.$sts.$type.$file;
$data=xnsize_encode(strlen($data)).$data;
}else{
$dtype=10;
$name=get_class($data);
$data=(array)$data;
$d=[];
foreach($data as $k=>$v){
$d[]=$k;
$d[]=$v;
}$data=xnserialize(...$d);
$data=xnsize_encode(strlen($name)).$name.$data;
$data=xnsize_encode(strlen($data)).$data;
}break;default:
return XNSERIALIZE_TYPE_INVALID;
}$dall.=chr($dtype).$data;
}return $dall;
}function xnunserialize($datas){
$u=strlen($datas);
$dall=[];
for($c=0;$c<$u;){
$type=ord($datas[$c++]);
switch($type){
case 1:$data=null;break;
case 2:$data=true;break;
case 3:$data=false;break;
case 4:
$l=ord($datas[$c++]);
$size=substr($datas,$c,$l);
$size=xnsize_decode(chr($l).$size);
$c+=$l;
$data=substr($datas,$c,$size);
$c+=$size;
break;case 5:
$l=ord($datas[$c++]);
$data=(int)substr($datas,$c,$l);
$c+=$l;
break;case 6:
$l=ord($datas[$c++]);
$m=ord($datas[$c++]);
$data=(double)substr($datas,$c,$l);
$c+=$l;
break;case 7:
$l=ord($datas[$c++]);
$size=substr($datas,$c,$l);
$size=xnsize_decode(chr($l).$size);
$c+=$l;
$data=substr($datas,$c,$size);
$c+=$size;
$d=xnunserialize($data);
$data=[];
for($o=0;isset($d[$o]);$o+=2)
$data[$d[$o]]=$d[$o+1];
break;case 8:
$l=ord($datas[$c++]);
$size=substr($datas,$c,$l);
$size=xnsize_decode(chr($l).$size);
$c+=$l;
$data=substr($datas,$c,$size);
$c+=$size;
$d=xnunserialize($data);
$data=[];
for($o=0;isset($d[$o]);$o+=2)
$data[$d[$o]]=$d[$o+1];
$data=(object)$data;
break;case 9:
$l=ord($datas[$c++]);
$size=substr($datas,$c,$l);
$size=xnsize_decode(chr($l).$size);
$c+=$l;
$data=substr($datas,$c,$size);
$c+=$size;
$cl=0;
$parl=ord($data[$cl++]);
$pars=substr($data,$cl,$parl);
$cl+=$parl;
$pars=xnsize_decode(chr($parl).$pars);
$par=substr($data,$cl,$pars);
$cl+=$pars;
$stcl=ord($data[$cl++]);
$stcs=substr($data,$cl,$stcl);
$cl+=$stcl;
$stcs=xnsize_decode(chr($stcl).$stcs);
$stc=substr($data,$cl,$stcs);
$cl+=$stcs;
$typl=ord($data[$cl++]);
$typs=substr($data,$cl,$typl);
$cl+=$typl;
$typs=xnsize_decode(chr($typl).$typs);
$typ=substr($data,$cl,$typs);
$cl+=$typs;
$fill=ord($data[$cl++]);
$fils=substr($data,$cl,$fill);
$cl+=$fill;
$fils=xnsize_decode(chr($fill).$fils);
$fil=substr($data,$cl,$fils);
$cl+=$fils;
$pars=[];
if($par!="\x01"){
$ll=strlen($par);
$pv=0;
for($cl=0;$cl<$ll;++$pv){
$pl=ord($par[$cl++]);
$ps=substr($par,$cl,$pl);
$cl+=$pl;
$ps=xnsize_decode(chr($pl).$ps);
$p=substr($par,$cl,$ps);
$cl+=$ps;
$kc=0;
$pars[$pv]='';
if($p[0]=='.'){
++$kc;
$pars[$pv].='...';
}if(strhave($p,';')){
$ps=strpos($p,';');
$pt=substr($p,$kc,$ps-$kc);
$kc+=$ps+1;
$pars[$pv]=$pt.' '.$pars[$pv];
}if($p[$kc]=='&'){
$pars[$pv].='&';
++$kc;
}if(strhave($p,':')){
$ps=strpos($p,':');
$pn=substr($p,$kc,$ps-$kc);
$pu=substr($p,$ps+1);
$kc+=$ps+1;
$pars[$pv].='$'.$pn.'='.unce(xnunserialize($pu));
}else $pars[$pv].='$'.substr($p,$kc);
}
}$pars=implode(',',$pars);
$stcs=xnunserialize("\x07".xnsize_encode(strlen($stc)).$stc);
$stc=[];
foreach($stcs as $k=>$v)
$stc[]="$$k";
$stc=implode(',',$stc);
if($stc)$stc="use($stc)";
$type=$typ=="\x01"?'':':'.$typ;
$file=$fil=="\x01"?'':$fil;
$func="function($pars)$stc$type{
$file
}";
$data=(function()use($stcs,$func){
foreach($stcs as $k=>$v)
$$k=$v;
return eval("return $func;");
})();
break;case 10:
$l=ord($datas[$c++]);
$size=substr($datas,$c,$l);
$c+=$l;
$size=xnsize_decode(chr($l).$size);
$data=substr($datas,$c,$size);
$c+=$size;
$pc=0;
$l=ord($data[$pc++]);
$size=substr($data,$pc,$l);
$pc+=$l;
$size=xnsize_decode(chr($l).$size);
$name=substr($data,$pc,$size);
$pc+=$size;
$data=substr($data,$pc);
$d=xnunserialize($data);
$data=[];
for($o=0;isset($d[$o]);$o+=2)
$data[$d[$o]]=$d[$o+1];
$data=serialize((object)$data);
$data=replaceone("8:\"stdClass\"",strlen($name).":\"$name\"",$data);
$data=unserialize($data);
break;case 11:

break;default:
return XNSERIALIZE_TYPE_INVALID;
}$dall[]=$data;
}if(count($dall)==1)return $dall[0];
return $dall;
}function xnserialize_error_name($error){
if($error===XNSERIALIZE_TYPE_INVALID) return "XNSERIALIZE_TYPE_INVALID";
if($error===XNSERIALIZE_CLOSURE_ERROR)return "XNSERIALIZE_CLOSURE_ERROR";
return false;
}
function set_class_var(object &$class,string $type="public",$key,$value){
$name=get_class($class);
$class=(array)$class;
if    ($type=="public")   ;
elseif($type=="private")  $key="\x00a\x00$key";
elseif($type=="protected")$key="\x00*\x00$key";
$class[$key]=$value;
$class=serialize((object)$class);
$class=replaceone("8:\"stdClass\"",strlen($name).":\"$name\"",$class);
$class=unserialize($class);
}function delete_class_var(object &$class,string $type="public",$key){
$name=get_class($class);
$class=(array)$class;
if    ($type=="public")   ;
elseif($type=="private")  $key="\x00a\x00$key";
elseif($type=="protected")$key="\x00*\x00$key";
unset($class[$key]);
$class=serialize((object)$class);
$class=replaceone("8:\"stdClass\"",strlen($name).":\"$name\"",$class);
$class=unserialize($class);
}function get_class_all_vars(object $class){
$name=get_class($class);
$class=(array)$class;
$vars=['public'=>[],"private"=>[],"protected"=>[]];
foreach($class as $k=>$v){
if(@$k[1]=='')$vars['public'][$k]=$v;
elseif($k[1]=='a'&&$k[0]=="\x00")$vars['private'][substr($k,3)]=$v;
elseif($k[1]=='*'&&$k[0]=="\x00")$vars['protected'][substr($k,3)]=$v;
else $vars['public'][$k]=$v;
}return $vars;
}function get_class_var(object $class,string $type,$key){
$name=get_class($class);
$class=(array)$class;
if    ($type=="public")   ;
elseif($type=="private")  $key="\x00a\x00$key";
elseif($type=="protected")$key="\x00*\x00$key";
return $class[$key];
}function convert_class(object &$class,string $to){
$name=get_class($class);
$class=serialize($class);
$name=strlen($name).":\"$name\"";
$to=strlen($to).":\"$to\"";
$class=replaceone($name,$to,$class);
$class=unserialize($class);
}function get_class_var_type(object $class,$key){
$name=get_class($class);
$class=(array)$class;
return isset($class["$key"])?"public":
       isset($class["\x00a\x00$key"])?"private":
       isset($class["\x00*\x00$key"])?"protected":
       false;
}function class_var_exists(object $class,$key){
return get_class_var_type($class,$key)!==false;
}
class XNNumber {
// consts variables
public static function PI($l=-1){
$pi=xndata("pi");
if($l<0)return $pi;
if($l==0)return substr($pi,1);
return substr($pi,0,$l+2);
}public static function PHI($l=-1){
$phi=xndata("phi");
if($l<0)return $phi;
if($l==0)return substr($phi,1);
return substr($phi,0,$l+2);
}
// validator
public static function is_number($a){
return is_numeric($a);
}
// system functions
public static function _check($a){
if(!is_numeric($a)){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNNumber","invalid number \"$a\".");
return false;
}return true;
}public static function _view($a){
if($a[0]=='-')return true;
return false;
}public static function abs($a){
if($a[0]=='-'||$a[0]=='+')return substr($a,1);
return $a;
}public static function _change($a){
if($a==0)return '0';
if($a[0]=='-')return substr($a,1);
if($a[0]=='+')return '-'.substr($a,1);
return '-'.$a;
}public static function _get0($a){
$a=ltrim($a,'0');
return $a?$a:'0';
}public static function _get1($a){
$a=rtrim($a,'0');
return $a?$a:'0';
}public static function _get2($a){
$a=self::_mo($a);
$a[1]=isset($a[1])?$a[1]:'0';
$a[0]=self::_get0($a[0]);
$a[1]=self::_get1($a[1]);
if($a[0]&&$a[1])return "{$a[0]}.{$a[1]}";
if($a[1])return "0.{$a[1]}";
if($a[0])return "{$a[0]}";
return "0";
}public static function _get3($a){
if(self::_view($a))return '-'.self::_get2(self::abs($a));
return self::_get2(self::abs($a));
}public static function _get($a){
if(!self::_check($a))return false;
return self::_get3($a);
}public static function _set0($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return str_repeat('0',$l).$a;
}public static function _set1($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return $a.str_repeat('0',$l);
}public static function _set2($a,$b){
$a=self::_mo($a);
$b=self::_mo($b);
if(!isset($a[1])&&isset($b[1])){
$a[1]='0';
}if(isset($a[1]))$a[1]=self::_set1($a[1],@$b[1]);
$a[0]=self::_set0($a[0],$b[0]);
if(!isset($a[1]))return "{$a[0]}";
return "{$a[0]}.{$a[1]}";
}public static function _set3($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_set2(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
                                      return     self::_set2(self::abs($a),self::abs($b));
}public static function _set($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set3($a,$b);
}public static function _full($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set(self::_get($a),self::_get($b));
}public static function _setfull(&$a,&$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
$a=self::_get($a);
$b=self::_get($b);
$a=self::_set($a,$b);
$b=self::_set($b,$a);
}public static function _mo($a){
return explode('.',$a);
}public static function _lm($a){
return strpos($a,'.');
}public static function _im($a){
$p=self::_lm($a);
return $p!==false&&$p!=-1;
}public static function _nm($a){
return str_replace('.','',$a);
}public static function _st($a,$b){
if(!isset($a[$b])||$b==0)return $a;
return substr_replace($a,'.',$b,0);
}public static function _iz($a){
$a=$a[strlen($a)-1];
return $a=='0'||$a=='2'||$a=='4'||$a=='6'||$a=='8';
}public static function _if($a){
$a=$a[strlen($a)-1];
return $a=='1'||$a=='3'||$a=='5'||$a=='7'||$a=='9';
}public static function _so($a,$b){
$l=strlen($a)%$b;
if($l==0)return $a;
else return str_repeat('0',$b-$l).$a;
}public static function _pl($a){
$l='0';
while($a!=$l){
$l=$a;
$a=str_replace(['--','-+','+-','++'],['+','-','-','+'],$a);
}return $a;
}
// retry calc functions
public static function _powTen0($a,$b){
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
}public static function _powTen1($a,$b){
if(self::_view($a))return '-'.self::_powTen0(self::abs($a),$b);
return self::_powTen0(self::abs($a),$b);
}public static function powTen($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_get(self::_powTen1($a,$b));
}public static function _mulTwo0($a){
$a=str_split($a,13);
$c=count($a)-1;
while($c>=0){
$a[$c]*=2;
$k=0;
while(@$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
++$k;
}$a[$c]=self::_so($a[$c],13);
--$c;
}return implode('',$a);
}public static function _mulTwo1($a){
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
}public static function _mulTwo2($a){
if(self::_view($a))return '-'.self::_mulTwo1(self::abs($a));
return self::_mulTwo1(self::abs($a));
}public static function mulTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_mulTwo2(self::_get3($a)));
}public static function _divTwo0($a){
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
}public static function _divTwo1($a){
$p=self::_lm($a);
$a=self::_nm($a);
if($p===false||$p==-1)$p=strlen($a);
$l=strlen($a);
$a=self::_so($a,14);
$p+=strlen($a)-$l;
$a=self::_divTwo0($a);
return self::_st($a,$p);
}public static function _divTwo2($a){
if(self::_view($a))return '-'.self::_divTwo1(self::abs($a));
return self::_divTwo1(self::abs($a));
}public static function divTwo($a){
return self::_get(self::_divTwo2(self::_get($a)));
}public static function _powTwo0($a){
$a=str_split($a,1);
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
--$e;
}--$c;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
++$k;
}return $x;
}public static function _powTwo1($a){
$p=self::_lm($a);
if(!$p)return self::_powTwo0($a);
$p=strlen($a)-$p-1;
$p*=2;
$a=self::_nm($a);
$a='0'.self::_powTwo0($a);
return self::_st($a,strlen($a)-$p);
}public static function _powTwo2($a){
return self::_powTwo1(self::abs($a));
}public static function powTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_powTwo2(self::_get3($a)));
}
// set functions
public static function floor($a){
if(!self::_check($a))return false;
return explode('.',"$a")[0];
}public static function ceil($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])?self::add($a[0],'1'):$a[0];
}public static function round($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])&&$a[1][0]>=5?self::add($a[0],'1'):$a[0];
}
// calc functions
public static function _add0($a,$b){
$a=str_split($a,13);
$b=str_split($b,13);
$c=count($a)-1;
while($c>=0){
$a[$c]+=$b[$c];
$k=0;
while(isset($a[$c-$k])&&$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
++$k;
}$a[$c]=self::_so($a[$c],13);
--$c;
}return implode('',$a);
}public static function _add1($a,$b){
$a="0000000000000$a";
$b="0000000000000$b";
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_add0($a,$b),$p);
return self::_add0($a,$b);
}public static function _add2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return     self::rem  (self::abs($b),self::abs($a));
if(!self::_view($a)&& self::_view($b))return     self::rem  (self::abs($a),self::abs($b));
                                      return     self::_add1(self::abs($a),self::abs($b));
}public static function add($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($a==0)return $b;
if($b==0)return $a;
if($a==$b)return self::mulTwo($a);
return self::_get3(self::_add2($a,$b));
}public function _rem0($a,$b){
$a=str_split($a,13);
$b=str_split($b,13);
$c=count($a)-1;
while($c>=0){
$a[$c]-=$b[$c];
$k=0;
while(isset($a[$c-$k-1])&&$a[$c-$k]<0){
$a[$c-$k-1]-=1;
$a[$c-$k]+=10000000000000;
++$k;
}$a[$c]=self::_so($a[$c],13);
--$c;
}return implode('',$a);
}public static function _rem1($a,$b){
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_rem0($a,$b),$p);
return self::_rem0($a,$b);
}public static function _rem2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_rem1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_add1(self::abs($a),self::abs($b));
                                      return     self::_rem1(self::abs($a),self::abs($b));
}public static function _rem3($a,$b){
if($a<$b){
return '-'.self::_rem2($b,$a);
}return self::_rem2($a,$b);
}public static function rem($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==0?self::_change($b):
   $b==0?$a:
   self::_rem3($a,$b);
return self::_pl(self::_get3($r));
}public static function _mul0($a,$b){
$a=str_split($a,1);
$b=str_split($b,1);
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
$t%=10;
$y=$t.$y;
--$e;
}--$c;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
++$k;
}return $x;
}public static function _mul1($a,$b){
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
}public static function _mul2($a,$b){
if( self::_view($a)&& self::_view($b))return     self::_mul1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
                                      return     self::_mul1(self::abs($a),self::abs($b));
}public static function mul($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($a==0||$b==0)return '0';
if($a==1)return "$b";
if($b==1)return "$a";
if($a==2)return self::mulTwo($b);
if($b==2)return self::mulTwo($a);
if($a==$b)return self::powTwo($a);
return self::_get3(self::_mul2($a,$b));
}public static function _rand0($a){
$rand="0.";
$b=floor($a/9);
for($c=0;$c<$b;++$c){
$rand.=self::_so(rand(0,999999999),9);
}if($a%9==0)return $rand;
return $rand.self::_so(rand(0,str_repeat('9',$a%9)),$a%9);
}public static function _rand1($a,$b){
$c=self::rem($a,$b);
$d=self::_rand0(strlen($a));
return self::add(self::floor(self::mul(self::add($c,'1'),$d)),$b);
}public static function _rand2($a,$b){
$p=self::_lm($a);
if(!$p)return self::_rand1($a,$b);
$p=strlen($a)-$p-1;
$a=self::_nm($a);
$b=self::_nm($b);
$a='0'.self::_rand1($a,$b);
return self::_st($a,strlen($a)-$p);
}public static function _rand3($b,$a){
if($a>$b)return self::_rand2($a,$b);
return self::_rand2($b,$a);
}public static function _rand4($a,$b){
if(self::_view($a)&&self::_view($b))return '-'.self::_rand3(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$a));
}if(self::_view($a)&&!self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$b));
}return self::_rand3(self::abs($a),self::abs($b));
}public static function rand($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==$b?$a:
   self::_rand4($a,$b);
return self::_get($r);
}public static function _div0($a,$b){
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
}public static function _div1($a,$b,$o=0){
$a=str_split($a,1);
$p=$r=$i=$d='0';
$c=count($a);
while($i<$c){
$d.=$a[$i];
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
++$i;
}if($d==0||$o<=0)return $r;
$r.='.';
while($d>0&&$o>0){
$d.='0';
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
--$o;
}return $r;
}public static function _div2($a,$b,$c=0){
$a=self::_nm($a);
$b=self::_nm($b);
if($c<0)$c=0;
return self::_div1($a,$b,$c);
}public static function _div3($a,$b,$c=0){
if( self::_view($a)&& self::_view($b))return     self::_div2(self::abs($a),self::abs($b),$c);
if( self::_view($a)&&!self::_view($b))return '-'.self::_div2(self::abs($a),self::abs($b),$c);
if(!self::_view($a)&& self::_view($b))return '-'.self::_div2(self::abs($a),self::abs($b),$c);
                                      return     self::_div2(self::abs($a),self::abs($b),$c);
}public static function div($a,$b,$c=0){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($b==0){
new XNError("XNNumber","not can div by Ziro");
return false;
}if($a==0)return '0';
if($b==1)return "$a";
if($a==$b)return '1';
return self::_get2(self::_div3($a,$b,$c));
}public static function _mod0($a,$b){
$a=str_split($a,1);
$p=$r=$i=$d='0';
$c=count($a);
while($i<$c){
$d.=$a[$i];
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::mul($p,$b));
$r.=$p;
}else $r.='0';
++$i;
}return $d;
}public static function _mod1($a,$b){
$a=self::_nm($a);
$b=self::_nm($b);
return self::_mod0($a,$b);
}public static function _mod2($a,$b){
if(self::_view($a))return '-'.self::_mod1(self::abs($a),self::abs($b));
                   return     self::_mod1(self::abs($a),self::abs($b));
}public static function mod($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
if($b==0){
new XNError("XNNumber","not can div by Ziro");
return false;
}if($a==0||$b==1||$a==$b)return '0';
return self::_get(self::_mod2($a,$b));
}
// algo functions
public static function fact($a){
if(!self::_check($a))return false;
if($a<=1)return 1;
$r='1';
while($a>0){
$r=self::mul($r,$a);
$a=self::rem($a,'1');
}return $r;
}public static function gcd($a,$b){
return $b?self::gcd($b,self::mod($a,$b)):$a;
}public static function sin($x,$limit=1,$offset=10){
if(!self::_check($x))return false;
$limit=$limit<0?
       self::mul($x,'0.1'):
       self::mul($x,$limit/10);
$a='0';
$b=$x;
$c='1';
for($i='0';$i<$limit;$i=self::add($i,'1')){
$a=self::add($a,self::div($b,$c,$offset));
$b=self::mul($b,self::_change(self::powTwo($x,$x)));
$g=self::mulTwo(self::add($i,1));
$c=self::mul($c,self::mul($g,self::add($g,1)));
}return $a;
}
// convertor functions
public static function toNumber($a='0'){
if(!self::_check($a))return false;
return $a*1;
}public static function toXNNumber($a=0){
if($a==NAN||$a==INF){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNNumber","the '$a' not is a number");
return false;
}$a=explode('E',$a);
if(!isset($a[1]))return "{$a[0]}";
$a=self::powTen($a[0],$a[1]);
return $a;
}public static function init($number,$init=10){
return self::base_convert($number,$init,10);
}
// parser functions
public static function baseconvert($text,$from=false,$to=false){
if(is_string($from)&&strtoupper($from)=="ASCII")return self::baseconvert(bin2hex($text),"0123456789abcdef",$to);
if(is_string($to  )&&strtoupper($to)  =="ASCII")return hex2bin(self::baseconvert($text,$from,"0123456789abcdef"));
$text=(string)$text;
if(!is_array($from))$fromel=str_split($from);
else $fromel=$from;
if($from==$to)return $text;
$frome=[];
foreach($fromel as $key=>$value){
$frome[$value]=$key;
}unset($fromel);
$fromc=count($frome);
if(!is_array($to))$toe=str_split($to);
else $toe=$to;
$toc=count($toe);
$texte=array_reverse(str_split($text));
$textc=count($texte);
$bs=0;
$th=1;
for($i=0;$i<$textc;++$i){
$bs=self::add($bs,self::mul(@$frome[$texte[$i]],$th));
$th=self::mul($th,$fromc);
}$r='';
if($to===false)return "$bs";
while($bs>0){
$r=$toe[self::mod($bs,$toc)].$r;
$bs=self::floor(self::div($bs,$toc));
}return "$r";
}public function base_convert($str,$from,$to=10){
if($from==$to)return $str;
$chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/";
$from=$from=="ASCII"?ASCII_CHARS():substr($chars,0,$from);
$to=$from=="ASCII"?ASCII_CHARS():substr($chars,0,$to);
$to=$to=="0123456789"?false:$to;
return self::baseconvert($str,$from,$to);
}
}
class XNBinary {
// validator
public static function is_binary($a){
return preg_match('/^[01]+$/',$a);
}
// system functions
public static function _check($a){
if(!self::is_binary($a)){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNBinary","invalid binary \"$a\".");
return false;
}return true;
}public static function _set($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return str_repeat('0',$l).$a;
}public static function _setall(&$a,&$b){
$a=self::_set($a,$b);
if($a===false)return false;
$b=self::_set($b,$a);
if($b===false)return false;
return true;
}public function _get($a){
if(!self::_check($a))return false;
$a=ltrim($a,'0');
return $a?$a:'0';
}public function _setfull(&$a,&$b){
$a=self::_get($a);
if($a===false)return false;
$b=self::_get($b);
if($b===false)return false;
self::_setall($a,$b);
return true;
}public function _getfull(&$a){
$a=self::_get($a);
if($a===false)return false;
return true;
}
// parser functions

// calc functions
public static function xor($a,$b){
if(!self::_setfull($a,$b))return false;
$l=strlen($a);
for($c=0;$c<$l;++$c)
$a[$c].=$a[$c]==$b[$c]?'1':'0';
return $a;
}public static function add($a,$b){
if(!self::_setfull($a,$b))return false;
if($a==0)return $b;
if($b==0)return $a;
$a="0$a";
$b="0$b";
$l=strlen($a);
for($c=0;$c<$l;++$c){
$a[$c]=$a[$c]+$b[$c];
$w=0;
while($a[$c-$w]==2){
$a[$c-$w-1]=$a[$c-$w-1]+1;
$a[$c-$w]=0;
++$w;
}
}if($a[0]=='0')$a=substr($a,1);
return self::_get($a);
}public static function rem($a,$b){
if(!self::_setfull($a,$b))return false;
if($b>$a)var_move($a,$b);
if($b==0)return $a;
if($a==$b)return 0;
$l=strlen($a);
$a=str_split($a);
for($c=0;$c<$l;++$c){
$a[$c]=$a[$c]-$b[$c];
$w=0;
while($a[$c-$w]==-1){
$k=1;
while($a[$c-$w-$k]==0){
$a[$c-$w-$k]=1;
++$k;
}$a[$c-$w-$k]=0;
$a[$c-$w]=1;
++$w;
}
}return self::_get(implode('',$a));
}public static function mul($a,$b){
$g=str_repeat('0',strlen($a)+strlen($b));
if(!self::_setfull($a,$b))return false;
if($a==0||$b==0)return '0';
$l=strlen($a);
for($x=0;$x<$l;++$x){
$r='';
for($y=0;$y<$l;++$y)
$r.=$a[$x]*$b[$y];
if($x>0)$r.=str_repeat('0',$x);
$g=self::add($g,$r);
}return self::_get($g);
}public static function div($a,$b){
if(!self::_getfull($a))return false;
if(!self::_getfull($b))return true;
if($b>$a)var_move($a,$b);
return strlen($a)-strlen($b);
}public static function rshift($a,$shift=1){
if(!self::_getfull($a))return false;
if($shift==0)return $a;
return substr($a,-$shift);
}public static function lshift($a,$shift=1){
if(!self::_getfull($a))return false;
if($shift==0)return $a;
return $a.str_repeat('0',$shift);
}
// convertors
public function toInt($a){
return (int)base_convert($a,2,10);
}public function toNumber($a){
return XNNumber::base_convert($a,2,10);
}public function toString($a){
return base2_decode(set_bytes($a,8));
}public function init($a,$init=2){
return XNNumber::base_convert($a,$init,2);
}
}
class XNStringPosition {
public $string='',$position=0,$length=0;
public function __construct(string $str,int $from=0){
$this->string=$str;
$this->position=$from;
$this->length=strlen($str);
}public function current(){
return $this->string[$this->position];
}public function next(){
return $this->string[++$this->position];
}public function prev(){
return $this->string[--$this->position];
}public function end(){
$this->string[$this->position=$this->length-1];
}public function start(){
return $this->string[$this->position=0];
}public function go(int $to){
return $this->string[$this->position=$to];
}public function set(string $c){
$this->string[$this->position]=$c[0];
}
}class XNStringBinaryPosition {
public $binary='',$position=0,$length=0,$size=0;
public function __construct(string $str,int $size,int $from=0){
$this->binary=base2_encode($str);
$this->position=$from;
$this->length=strlen($str)*8;
$this->size=$size;
}public function get(){
$this->binary=set_bytes($this->binary,8,'0');
$length=$this->length%8;
$length=$length?8-$length:0;
$this->length+=$length;
return base2_decode($this->binary);
}public function __toString(){
return $this->get();
}public function current(){
return base_convert(substr($this->binary,$this->position,$this->size),2,10);
}public function next(){
return base_convert(substr($this->binary,$this->position+=$this->size,$this->size),2,10);
}public function prev(){
return base_convert(substr($this->binary,$this->position-=$this->size,$this->size),2,10);
}public function end(){
$this->position=$this->length-1;
}public function start(){
return base_convert(substr($this->binary,$this->position=0,$this->size),2,10);
}public function go(int $to){
return base_convert(substr($this->binary,$this->position=$to,$this->size),2,10);
}public function set(string $c){
$c=set_bytes($c,$this->size,'0');
$this->binary=substr_replace($this->binary,$c,$this->position,$this->size);
}public function getBlocksCount(){
return ceil($this->length/$this->size);
}
}
class XNString {
// parser functions
public static function lshift(string $str,int $shift=1){
$l=strlen($str);
$shift=$shift<0?1:$shift%$l;
return substr($str,$shift,$l-1).substr($str,0,$shift);
}public static function rshift(string $str,int $shift=1){
$l=strlen($str);
$shift=$shift<0?1:$shift%$l;
return substr($str,$l-$shift,$l-1).substr($str,0,$l-$shift);
}public static function usedchars(string $str){
return array_unique(str_split($str));
}public static function max(...$chars){
if(isset($chars[0][1]))$chars=str_split($chars[0]);
elseif(is_array(@$chars[0]))$chars=$chars[0];
$chars=array_unique($chars);
$l=-1;
for($c=0;isset($chars[$c]);++$c)
if(($h=ord($chars[$c]))>$l)$l=$h;
return $l;
}public static function min(...$chars){
if(isset($chars[0][1]))$chars=str_split($chars[0]);
elseif(is_array(@$chars[0]))$chars=$chars[0];
$chars=array_unique($chars);
$l=256;
for($c=0;isset($chars[$c]);++$c)
if(($h=ord($chars[$c]))<$l)$l=$h;
return $l;
}public static function end(string $str,string $im){
return substr($str,strrpos($str,$im)+1);
}public static function start(string $str,string $im){
return substr($str,0,strpos($str,$im));
}public static function noend(string $str,string $im){
return substr($str,0,strrpos($str,$im));
}public static function nostart(string $str,string $im){
return substr($str,strpos($str,$im)+1);
}public static function endi(string $str,string $im){
return substr($str,strripos($str,$im)+1);
}public static function starti(string $str,string $im){
return substr($str,0,stripos($str,$im));
}public static function noendi(string $str,string $im){
return substr($str,0,strripos($str,$im));
}public static function nostarti(string $str,string $im){
return substr($str,stripos($str,$im)+1);
}public static function char(string $str,int $x){
return @$str[$x];
}public static function islength(string $str,int $x){
return isset($str[$x-1]);
}public static function position(string $str,int $from=0){
return new XNStringPosition($str,$from);
}public static function binaryPosition(string $str,int $size=8,int $from=0){
return new XNStringBinaryPosition($str,$size,$from);
}public static function endchar(string $str){
return $str[strlen($str)-1];
}public static function startby(string $str,string $by){
return strpos($str,$by)===0;
}public static function endby(string $str,string $by){
return strrpos($str,$by)===strlen($str)-strlen($by);
}public static function startiby(string $str,string $by){
return stripos($str,$by)===0;
}public static function endiby(string $str,string $by){
return strripos($str,$by)===strlen($str)-strlen($by);
}public static function match(string $str,string $by){
return $str==$by;
}public static function matchi(string $str,string $by){
return strtolower($str)==strtolower($by);
}public static function toString($str=20571922739462){
if($str===20571922739462)return '';
switch(gettype($str)){
case "NULL":
return 'NULL';
case "boolean":
if($str)return 'true';
return 'false';
case "string":
return $str;
case "double":
case "int":
return "$str";
case "array":
return unce($str);
}new XNError("XNString::toString","argumant type not found");
return false;
}public static function toregex(string $str){
return str_replace("\Q\E",'',"\Q".str_replace('\E','\E\\\E\Q',$str)."\E");
}public static function toiregex(string $str){
return str_replace("\Q\E",'',"\Q".str_replace([
"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r",
"s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I",
"J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"
],[
'\E[aA]\Q','\E[bB]\Q','\E[cC]\Q','\E[dD]\Q','\E[eE]\Q','\E[fF]\Q','\E[gG]\Q','\E[hH]\Q',
'\E[iI]\Q','\E[jJ]\Q','\E[kK]\Q','\E[lL]\Q','\E[mM]\Q','\E[nN]\Q','\E[oO]\Q','\E[pP]\Q',
'\E[qQ]\Q','\E[rR]\Q','\E[sS]\Q','\E[tT]\Q','\E[uU]\Q','\E[vV]\Q','\E[wW]\Q','\E[xX]\Q',
'\E[yY]\Q','\E[zZ]\Q','\E[aA]\Q','\E[bB]\Q','\E[cC]\Q','\E[dD]\Q','\E[eE]\Q','\E[fF]\Q',
'\E[gG]\Q','\E[hH]\Q','\E[iI]\Q','\E[jJ]\Q','\E[kK]\Q','\E[lL]\Q','\E[mM]\Q','\E[nN]\Q',
'\E[oO]\Q','\E[pP]\Q','\E[qQ]\Q','\E[rR]\Q','\E[sS]\Q','\E[tT]\Q','\E[uU]\Q','\E[vV]\Q',
'\E[wW]\Q','\E[xX]\Q','\E[yY]\Q','\E[zZ]\Q'
],$str)."\E");
}
// calc functions
public static function xorn(string $a,string $b){
$al=strlen($a);
$bl=strlen($b);
$l=max($al,$bl);
$n='';
for($i=0;$i<$l;++$i){
if(!isset($a[$i])||!isset($b[$i])||$a[$i]!=$b[$i])
$n.='1';
else $n.='0';
}return $n;
}public static function xor(string $a,string $b){
return base2_decode(set_bytes(self::xorn($a,$b),8,'0'));
}public static function bxor(string $a,string $b){
return XNBinary::toString(XNBinary::xor(base2_encode($a),base2_encode($b)));
}public static function badd(string $a,string $b){
return XNBinary::toString(XNBinary::add(base2_encode($a),base2_encode($b)));
}public static function brem(string $a,string $b){
return XNBinary::toString(XNBinary::rem(base2_encode($a),base2_encode($b)));
}public static function bmul(string $a,string $b){
return XNBinary::toString(XNBinary::mul(base2_encode($a),base2_encode($b)));
}public static function bdiv(string $a,string $b){
return XNBinary::toString(XNBinary::div(base2_encode($a),base2_encode($b)));
}
}
class XNStr extends XNString {
}function sha512($str){
return hash("sha512",$str);
}function sha256($str){
return hash("sha256",$str);
}function md4($str){
return hash("md4",$str);
}function md2($str){
return hash("md2",$str);
}function sha224($str){
return hash("sha224",$str);
}function sha384($str){
return hash("sha384",$str);
}function hashs($str){
$n='';
$s=hash_algos();
foreach($s as $h)
$n.=hash($h,$str);
return $n;
}function hasha($str){
$s=hash_algos();
foreach($s as $h)
$str=hash($h,$str);
return $str;
}

function script_runtime(){
return microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'];
}function userip(){
if(@$_SERVER['HTTP_CLIENT_IP'])
return $_SERVER['HTTP_CLIENT_IP'];
elseif(@$_SERVER['HTTP_X_FORWARDED'])
return $_SERVER['HTTP_X_FORWARDED'];
elseif(@$_SERVER['HTTP_X_FORWARDED_FOR'])
return $_SERVER['HTTP_X_FORWARDED_FOR'];
elseif(@$_SERVER['REMOTE_ADDR'])
return $_SERVER['REMOTE_ADDR'];
else return "127.0.0.1";
}function save_memory($file=false){
if($file)fput($file,xnserialize($GLOBALS));
else $GLOBALS['-XN-']['savememory']=$GLOBALS;
}function back_memory($file=false){
if($file&&file_exists($file))$GLOBALS=xnunserialize(fget($file));
elseif(!$file)$GLOBALS=$GLOBALS['-XN-']['savememory'];
}function boolnumber($x,int $bbv=12){
$tree=XNMath::tree($x);
$strs=[];
foreach($tree as $num){
if(isset($strs[$num]))++$strs[$num][1];
else{
$n=$num;
$s=[];
if($n==0){
$r=rand(1,rand(1,rand(1,rand(1,$bbv))));
$r=$r<1?1:$r;
if($r%2==1)++$r;
$s[]=str_repeat('!',$r).'[]';
}else while($n>0){
$r=rand(1,rand(1,rand(1,rand(1,$bbv))));
$r=$r<1?1:$r;
$n-=$r%2;
$s[]=($r?str_repeat('!',$r):'').'[]';
}$s=implode('+',$s);
$strs[$num]=["($s)",1];
}}$s=[];
foreach($strs as $num){
if($num[1]==1)$s[]=$num[0];
else $s[]=$num[0].'**('.boolnumber($num[1]).')';
}$s=implode("*",$s);
return preg_replace('/\((\([^\(\)]+\))\)/','$1',$s);
}function boolstring($str){
if(!$str)return '';
return "chr(".implode(").chr(",array_map("boolnumber",array_values(unpack("c*",$str)))).")";
}function distance_positions($x1,$y1,$x2,$y2){
return rad2deg(acos((sin(deg2rad($x1))*sin(deg2rad($x2)))+(cos(deg2rad($x1))*cos(deg2rad($x2))*cos(deg2rad($y1-$y2)))))*111189.57696;
}function is_regex($x){
return @preg_match($x,null)!==false;
}function is_ereg($x){
return @ereg($x,null)!==false;
}

function getmd5xn(){
if($GLOBALS['-XN-']['isf']){
$get=file_get_contents($GLOBALS['-XN-']['dirNameDir']."xn.php");
$get=str_replace(["\"".$GLOBALS['-XN-']['lastUse']."\";","\"".$GLOBALS['-XN-']['lastUse']."\";","\"".$GLOBALS['-XN-']['DATA']."\";"],'',$get);
return md5($get);
}else return '';
}function xnscript(){
return ["version"=>"1.7",
"start_time"=>$GLOBALS['-XN-']['startTime'],
"end_time"=>$GLOBALS['-XN-']['endTime'],
"loaded_time"=>$GLOBALS['-XN-']['endTime']-$GLOBALS['-XN-']['startTime'],
"dir_name"=>$GLOBALS['-XN-']['dirName'],
"required_file"=>$GLOBALS['-XN-']['requirefile'],
"required_line"=>$GLOBALS['-XN-']['requireline'],
"source_file"=>$GLOBALS['-XN-']['sourcefile'],
"source_line"=>$GLOBALS['-XN-']['sourceline']
];
}class XNMath {
const PI  = 3.1415926535898;
const PHI = 1.6180339887498;
const G   = 9.80665        ;
public static function fact($n){
$n=(int)$n;
$r=1;
if($n>=171)return INF;
while($n>0){
$r*=$n--;
}return $r;
}public static function gcd($a,$b){
return $b?self::gcd($b,$a%$b):$a;
}public static function factors($x){
if($x==0)return [INF];
$r=[];
$y=($x=$x<0?-$x:$x)**0.5;
for($c=1;$c<=$y;++$c)
if($x%$c==0){
$r[]=$c;
if($c!=$y)
$r[]=$x/$c;
}sort($r);
return $r;
}public static function discriminant($a,$b,$c){
return $b**2-(4*$a*$c);
}public static function native($x){
$x=$x<0?-$x:$x;
if($x==0)return 0;
$y=(int)$x**0.5;
for($c=2;$c<=$y;++$c)
if($x%$c==0)return $c;
return $x;
}public static function natives($x){
$x=$x<0?-$x:$x;
if($x==0)return [0];
$r=[];
$y=(int)$x**0.5;
for($c=2;$c<=$y;++$c)
if($x%$c==0)$r[]=$c;
return $r;
}public static function tree($x){
if($x==0)return [0];
$r=[$l=self::native($x)];
while(($x/=$l)>1)
$r[]=$l=self::native($x);
return $r;
}public static function nominal($x,$y){
return (($x+1)**(1/$y)-1)*$y;
}
}function unce_dump(...$vars){
foreach($vars as $var)print unce($var);
}function string_dump(...$vars){
foreach($vars as $var)print XNStr::toString($var);
}function is_match_files($f1,$f2,$limit=262144){
$f1=@fopen($f1,'r');
$f2=@fopen($f2,'r');
if(!$f1||!$f2){
new XNError("Files","No such file or directory.",1);
return false;
}while(($r1=fread($f1,$limit))!==''&&($r2=fread($f2,$limit)))
if($r1!==$r2)return false;
return true;
}class BrainFuck {
public $homes=[0],$home=0,$output='',$input='',$position=-1;
private function __construct(string $code,string $input=''){
$this->input=$input;
$this->code($code);
}private function code(string $code){
$homes=&$this->homes;
$home=&$this->home;
$output=&$this->output;
$input=&$this->input;
$position=&$this->position;
for($c=0;isset($code[$c]);++$c)
switch($code[$c]){
case "+":
$homes[$home]++;
break;case "-":
$homes[$home]--;
break;case ">":
$home++;
if(!isset($homes[$home]))
$homes[$home]=0;
break;case "<":
$home--;
if(!isset($homes[$home]))
$homes[$home]=0;
break;case "[":
$q='';
for($x=1;isset($code[++$c])&&$x>0;){
if($code[$c]=='[')++$x;
elseif($code[$c]==']')--$x;
$q.=$code[$c];
}$c--;
$q=substr($q,0,-1);
while($homes[$home]%256!=0)
$this->code($q);
break;case ".":
$output.=chr($homes[$home]);
break;case ",":
$homes[$home]=ord(isset($input[$position])?$input[++$position]:$input[$position--]);
break;
}
}public static function run(string $code,string $input=''){
return (new BrainFuck($code,$input))->output;
}public static function file(string $file,string $input=''){
$code=@file_get_contents($file);
if($code===false)return false;
return (new BrainFuck($code,$input))->output;
}public static function create(string $string){
$string=str_split($string);
$l='';
$r='';
foreach($string as $x){
$x=ord($x);
if($x==$l)
$r.=".";
elseif($x==0)
$r.="[-].";
elseif($l&&$l>$x&&$l-$x<$x)
$r.=str_repeat("-",$l-$x).'.';
elseif($l&&$l<$x&&$x-$l<$x)
$r.=str_repeat("+",$x-$l).'.';
else $r.="[-]".str_repeat('+',$x).'.';
$l=$x;
}if(strpos($r,"[-]")===0)
return substr($r,3);
return $r;
}
}class Finder {
const TOKEN = "/[0-9]{4,20}:AA[GFHE][a-zA-Z0-9-_]{32}/";
const NUMBER = "/[0-9]+(?:\.[0-9]+){0,1}|\.[0-9]+|[0-9]+\./";
const HEX = "/[0-9a-fA-F]+/";
const BINARY = "/[01]+/";
const LINK = "/(?:[a-zA-Z0-9]+:\/\/){0,1}(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))(?:\?[^ \n\r\t\/\\#]*){0,1}(?:#[^ \n\r\t\/]*){0,1}/";
const EMAIL = "/(?:[^ \n\r\t\/\\#?@]+)@(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})/";
const FILE_NAME = "/[^ \n\r\t\/\\#@?]+/";
const DIRACTORY_NAME = "/(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))/";
public static function exists($str,$regex){
return preg_match($regex,$str);
}public static function find($str,$regex){
if(!preg_match($regex,$str,$find))return false;
return $find[0];
}public static function search($str,$regex){
if(!preg_match_all($regex,$str,$search))return false;
return $search[0];
}public static function token_exists($str){
return self::exists($str,self::TOKEN);
}public static function token_find($str){
return self::find($str,self::TOKEN);
}public static function token_search($str){
return self::search($str,self::TOKEN);
}public static function number_exists($str){
return self::exists($str,self::NUMBER);
}public static function number_find($str){
return self::find($str,self::NUMBER);
}public static function number_search($str){
return self::search($str,self::NUMBER);
}public static function hex_exists($str){
return self::exists($str,self::HEX);
}public static function hex_find($str){
return self::find($str,self::HEX);
}public static function hex_search($str){
return self::search($str,self::HEX);
}public static function binary_exists($str){
return self::exists($str,self::BINARY);
}public static function binary_find($str){
return self::find($str,self::BINARY);
}public static function binary_search($str){
return self::search($str,self::BINARY);
}public static function link_exists($str){
return self::exists($str,self::LINK);
}public static function link_find($str){
return self::find($str,self::LINK);
}public static function link_search($str){
return self::search($str,self::LINK);
}public static function email_exists($str){
return self::exists($str,self::EMAIL);
}public static function email_find($str){
return self::find($str,self::EMAIL);
}public static function email_search($str){
return self::search($str,self::EMAIL);
}public static function file_name_exists($str){
return self::exists($str,self::FILE_NAME);
}public static function file_name_find($str){
return self::find($str,self::FILE_NAME);
}public static function file_name_search($str){
return self::search($str,self::FILE_NAME);
}public static function diractory_name_exists($str){
return self::exists($str,self::DIRACTORY_NAME);
}public static function diractory_name_find($str){
return self::find($str,self::DIRACTORY_NAME);
}public static function diractory_name_search($str){
return self::search($str,self::DIRACTORY_NAME);
}
}class XNJson {
public static function encode($data){
return unce($data);
}public static function decode($data){
if($data=="NULL")return null;
if($data=="true")return true;
if($data=="false")return false;
if($data[0]=='"')return str_replace(["\\\"","\\\\"],["\"","\\"],substr($data,1,-1));
if($data[0]=="f")return evalg("return $data;");
if($data[0]=="["){

}if($data[0]=="{"){

}
}public static function encodeFile($file,$data){
return file_put_contents($file,self::ecode($data));
}public static function decodeFile($file){
return self::decode(file_get_contents($file));
}
}function xnmicrotime(){
$time=explode(" ",microtime());
return $time[1].substr($time[0],2,-2);
}function militime(){
return floor(microtime(true)*1000);
}class AXBA {
public $time=0;
public function setTime(int $time=null){
if(!$time)$time=0;
$this->time=$time;
}public function encrypt($message,$key='.',$limt=1){
$limt=$limt<1?1:$limt>255?255:$limt;
$lk=strlen($key);
$now="";
$time=$this->time;
if(!$time)$time=0;
if($time)$nw=floor(microtime(true)*1000/$time);
$ntime=$time;
while($time>0){
$now.=chr($time%256);
$time=floor($time/256);
}$time=$ntime;
$now=chr(strlen($now)).$now;
if(!$message)return $now;
for($c=0;isset($message[$c]);++$c){
$lim=$limt;
$h=$ck=ord($key[$c%$lk]);
for($p=0;$p<$lk;++$p)
$h+=$c<$p?ord($key[$lk%($p*2-$c+1)-1]):ord($key[$lk%($c*$p+1)-1]);
$h+=7+$c;
while($lim>0){
$h+=ord($key[$h%$lk]);
$h-=ord($key[$h%$lk]);
$h+=ord($key[(ord($key[$h%$lk])*(--$ck))%$lk]);
$h-=ord($key[(ord($key[$h%$lk])*(--$ck))%$lk]);
if($time){
$h-=$time;
$h+=floor((54723587387253%$time)**(78942389724972%$time/$time));
$h-=78945897423879%79483978437298%$nw;
$h+=48738970039781%floor((27384879234873%$nw  )**(84731894397898%$nw  /$nw));
$h+=84398423897894%floor(($nw*$time)**0.1+1);
$h=floor($h/($nw%100));
}--$lim;
}$h+=74328897548798%(++$ck?$ck:++$ck);
$h+=87942397087943%$ck;
$h+=47388973497853%$ck;
$h+=89470974982370%$ck;
$h-=10472109438758%$ck;
$h+=19874623974023%$ck;
$h+=24731088438908%(ord($key[$h%$lk])+1);
$h+=12093802101304%$ck;
$h+=89743879349718%(ord($key[$h%$lk])+1);
$h+=11948904100409%(ord($key[$h%$lk])+1);
$h-=12409721374821%(ord($key[$h%$lk])+1);
$h+=94874870929741%(ord($key[$h%$lk])+1);
$h+=84731090791187%(ord($key[$h%$lk])+1);
$h+=89412879328432%(ord($key[$h%$lk])+1);
$h+= 5370912357253%(ord($key[$h%$lk])+1);
$h+=2%$ck;
$now.=chr($h+ord($message[$c]));
}return $now.chr($limt);
}public function decrypt($message,$key='.'){
if(!$message)return '';
$limt=ord($message[strlen($message)-1]);
$size=ord($message[0]);
$stime=strrev(substr($message,1,$size));
$message=substr($message,$size+1,-1);
$time=0;
if($stime)
for($c=0;isset($stime[$c]);++$c)
$time=$time*256+ord($stime[$c]);
if($time)$nw=floor(microtime(true)*1000/$time);
$lm=strlen($message);
$lk=strlen($key);
$now="";
for($c=0;isset($message[$c]);++$c){
$lim=$limt;
$h=$ck=ord($key[$c%$lk]);
for($p=0;$p<$lk;++$p)
$h+=$c<$p?ord($key[$lk%($p*2-$c+1)-1]):ord($key[$lk%($c*$p+1)-1]);
$h+=7+$c;
while($lim>0){
$h+=ord($key[$h%$lk]);
$h-=ord($key[$h%$lk]);
$h+=ord($key[(ord($key[$h%$lk])*(--$ck))%$lk]);
$h-=ord($key[(ord($key[$h%$lk])*(--$ck))%$lk]);
if($time){
$h-=$time;
$h+=floor((54723587387253%$time)**(78942389724972%$time/$time));
$h-=78945897423879%79483978437298%$nw;
$h+=48738970039781%floor((27384879234873%$nw  )**(84731894397898%$nw  /$nw));
$h+=84398423897894%floor(($nw*$time)**0.1+1);
$h=floor($h/($nw%100));
}--$lim;
}$h+=74328897548798%(++$ck?$ck:++$ck);
$h+=87942397087943%$ck;
$h+=47388973497853%$ck;
$h+=89470974982370%$ck;
$h-=10472109438758%$ck;
$h+=19874623974023%$ck;
$h+=24731088438908%(ord($key[$h%$lk])+1);
$h+=12093802101304%$ck;
$h+=89743879349718%(ord($key[$h%$lk])+1);
$h+=11948904100409%(ord($key[$h%$lk])+1);
$h-=12409721374821%(ord($key[$h%$lk])+1);
$h+=94874870929741%(ord($key[$h%$lk])+1);
$h+=84731090791187%(ord($key[$h%$lk])+1);
$h+=89412879328432%(ord($key[$h%$lk])+1);
$h+= 5370912357253%(ord($key[$h%$lk])+1);
$h+=2%$ck;
$now.=chr(256-$h+ord($message[$c]));
}return $now;
}public function encrypts($message,$key='.',$salt='.',$limt=1){
$datak=$this->encrypt($this->encrypt($key,$key),($x=hex2bin(md5($key))).$salt.$x);
$data=$this->encrypt($message,$datak.substr($key.$salt,0,1),$limt);
$data=$this->encrypt($data,($x=hex2bin(md5($salt))).$key.$x,$limt);
return $this->encrypt($data,$key.$salt.hex2bin(hash("sha512",$salt.$key)),$limt);
}public function decrypts($message,$key='.',$salt='.'){
$data=$this->decrypt($message,$key.$salt.hex2bin(hash("sha512",$salt.$key)));
$data=$this->decrypt($data,($x=hex2bin(md5($salt))).$key.$x);
$datak=$this->encrypt($this->encrypt($key,$key),($x=hex2bin(md5($key))).$salt.$x);
return $this->decrypt($data,$datak.substr($key.$salt,0,1));
}public function hash($message,$key='.',$limt=1){
if(!$message)return '';
$lm=strlen($message);
$lk=strlen($key);
$now='';
$limt=$limt<1?1:$limt>1024?1024:$limt;
$nums=[
  78794387352807, 90419884196858, 63750863186045, 18653108265983, 69874169879018,
  39057981265091, 74971307548601, 97406498316490, 82164014098649, 28650984759864,
  35837092374096, 12986498327592, 37402349837409, 74016407076498, 26598739087498,
  26389473120097, 40917452386395, 7927409812109 , 34907193498891, 29831793479102,
  7491049734872 , 35862399847019, 34750165665666, 66013931094893, 10949814013570,
  17518461387471, 3947186581039 , 47091384910740, 15979729703810, 98387472985437,
  40597130458785, 56410297802670, 35398431069813, 63109563546056, 48695480695406,
  84816084869548, 96438659438959, 87315098740985, 7894877408048 , 78487787854875,
  87864586438965, 86318984681605, 6408968109473 , 34902762748394, 71390483624650,
  93749078249732, 40958017598637, 4109498625070 , 49789109571097, 13897420975982,
  64109759275808, 79347018279483, 65987819084986, 59748961809471, 74295274095968,
  27504295617050, 27356982572470, 95703126501935, 48686915079309, 71748938795079,
  39071867994797, 2685697019074 , 6910650257406 , 19750284091506, 91074099025790,
  97349173509757, 91473490701947, 90319797050731, 7410975970079 , 19070951907497,
  31709542709597, 1079497593597 , 9739750907329 , 7597027097095 , 7490797214970 ,
];
$time=$this->time;
for($c=0;$c<$lm;++$c){
$lim=$limt;
$h=ord($message[$c]);
$a=$h+1;
$h+=floor($h**1.1/($h**0.7+1)+$lm**0.5);
while($lim>0){
$h+=$a;
$a =ord($message[$h%$lm]);
$a+=ord($message[$a%$lm]);
$a+=ord($key[$h%$lk]);
$a+=ord($key[$a%$lk]);
$a+=$nums[abs($h%75)]%(!$h?$h+1:$h);
$a+=$nums[abs($a%75)]%(!$a?$a+1:$a);
$a+=$nums[abs($h%75)]%(!$h?$h+1:$h);
$a+=$nums[abs($a%75)]%(!$a?$a+1:$a);
$a+=$a%($c+1)+$c;
if($time)$a+=floor(microtime(true)*1000/$time)%256+$time%($c+1)+$c%$time+$h%$time;
--$lim;
}$h+=$a-3;
$h+=$nums[$c%75]%($c+1)+$c;
$now.=chr($h);
}return $now;
}public function dblencrypt($message,$key,$limt=1){
$message=$this->encrypt($message,$key,$limt);
$x1=floor(strlen($message)/2);
$x2=strlen($message)-$x1;
$y1=floor(strlen($key)/2);
$y2=strlen($key)-$y1;
$crypt1=$this->encrypt(substr($message,$x1,$x2),substr($key,0,$y1),$limt);
$crypt2=$this->encrypt(substr($message,0,$x1),substr($key,$y1,$y2),$limt);
$crypt2=$this->encrypt($crypt2,$crypt1,$limt);
$crypt1=$this->encrypt($crypt1,$key,$limt);
return [$crypt1,$crypt2];
}public function dbldecrypt($crypt1,$crypt2,$key){
$y1=floor(strlen($key)/2);
$y2=strlen($key)-$y1;
$crypt1=$this->decrypt($crypt1,$key);
$crypt2=$this->decrypt($crypt2,$crypt1);
$crypt1=$this->decrypt($crypt1,substr($key,0,$y1));
$crypt2=$this->decrypt($crypt2,substr($key,$y1,$y2));
return $this->decrypt($crypt2.$crypt1,$key);
}
}class BrainFuck2 {
private $memory=[[1,0],[1,0],[1,0],[1,[1,0]],[1,[1,0]],[1,0]],$save=[],$locate=0;
private function addone(){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}++$memory;
}private function remone(){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}--$memory;
}private function setmemory($num){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}$memory=@(int)$num;
}private function setmemorys($str){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[$memory[0]][0]))
$memory=&$memory[$memory[0]];
else break;
}$str=str_split($str);
foreach($str as $k=>$s)
$memory[$k+1]=ord($s);
}private function resetMemorys($c){
$memory=&$this->memory[$this->locate];
$l=$this->getcount()-$c;
$l=$l<0?0:$l;
while($l--){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}$memory=$this->createnew($c);
}private function printmemory($c){
$memory=$this->memory[$this->locate];
$l=$this->getcount()-$c;
$l=$l<0?0:$l;
while($l--){
if(isset($memory[0]))
$memory=$memory[$memory[0]];
else break;
}$memory=$this->getsub([0,$memory]);
foreach($memory as $m)
echo chr($m);
}private function printnumber(){
echo $this->getMemoryOne();
}private function savelocate(){
$save=[$this->locate];
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0])){
$save[]=$memory[0];
$memory=&$memory[$memory[0]];
}else break;
}$this->save=$save;
}private function setsave($type){
if(!isset($this->save[0]))return false;
$save=&$this->save;
$memory2=&$this->memory;
foreach($save as $i)
$memory2=&$memory2[$i];
$c=$this->getcount()-1;
if($c){
$save=$this->save;
$memory2=&$this->memory2[$this->save[0]];
while($c--)
$memory2=&$memory2[$memory2[0]];
if($type==22){
$this->locate=$save[0];
unset($save[0]);
foreach($save as $i){
$memory2[0]=$i[0];
$memory2=&$memory2[$i[0]];
}return;
}unset($save[0]);
foreach($save as $i)
$memory2=&$memory2[$i];
}$save=&$memory2;
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}switch($type){
case 0:
$memory=$save;
break;case 1:
$memory+=$save;
break;case 2:
$memory-=$save;
break;case 3:
$memory*=$save;
break;case 4:
$memory=$memory/$save;
break;case 5:
$memory%=$save;
break;case 6:
$memory=$memory**$save;
break;case 7:
$memory=$memory**(1/$save);
break;case 8:
$memory=$memory & $save;
break;case 9:
$memory=$memory|$save;
break;case 10:
$memory=$memory&&$save?1:0;
break;case 11:
$memory=$memory||$save?1:0;
break;case 12:
$memory=$memory>$save?1:0;
break;case 13:
$memory=$memory<$save?1:0;
break;case 14:
$memory=$memory>=$save?1:0;
break;case 15:
$memory=$memory<=$save?1:0;
break;case 16:
$meomry=$memory==$save?1:0;
break;case 17:
$memory=$memory!=$save?1:0;
break;case 18:
$memory=$memory>>$save;
break;case 19:
$memory=$memory<<$save;
break;case 20:
$memory=$memory^$save;
break;case 21:
$memory=$memory xor $save;
break;case 23:
$o=$memory;
$memory=$save;
$save=$o;
break;case 24:
$save=$memory;
break;case 25:
$memory=&$save;
break;case 26:
$save=&$memory;
break;
}
}private function nextmemory($c=1){
$p=$this->getcount();
if($p==$c){
$memory=&$this->memory[$this->locate];
++$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}elseif($p>$c){
$memory=&$this->memory[$this->locate];
$o=$p-$c;
while($o--){
if(isset($memory[$memory[0]][0]))
$memory=&$memory[$memory[0]];
else break;
}++$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}else{
$memory=&$this->memory[$this->locate];
$o=$c-$p;
while($o--)
$memory=[1,$memory];
++$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}
}private function prevmemory($c=1){
$p=$this->getcount();
if($p==$c){
$memory=&$this->memory[$this->locate];
--$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}elseif($p>$c){
$memory=&$this->memory[$this->locate];
$o=$p-$c;
while($o--){
if(isset($memory[$memory[0]][0]))
$memory=&$memory[$memory[0]];
else break;
}--$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}else{
$memory=&$this->memory[$this->locate];
$o=$c-$p;
while($o--)
$memory=[1,$memory];
--$memory[0];
if(!isset($memory[$memory[0]]))
$memory[$memory[0]]=$this->createnew($c-2);
}
}private function memoryReset(){
$this->memory[$this->locate]=[1,0];
}

private function getsub($array){
$arr=[];
unset($array[0]);
foreach($array as $a){
if(is_array($a))$arr[]=$this->getsub($a);
else $arr[]=[$a];
}return array_values(array_merge(...$arr));
return array_values($arr);
}private function getsubs($array){
$arr=[];
unset($array[0]);
foreach($array as $a){
if(is_array($a))$arr[]=$this->getsub($a);
else $arr[]=[$a];
}return array_values($arr);
}private function getcount(){
$memory=&$this->memory[$this->locate];
$p=0;
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
++$p;
}return $p;
}private function getstring($arr){
$str='';
foreach($arr as $a)
$str.=chr($a);
return $str;
}private function createnew($c){
$array=[1];
if($c>0)$array[]=$this->createnew(--$c);
elseif($c==0)$array[]=0;
else return 0;
return $array;
}

public function getMemory(){
return $this->memory[0];
}public function getMemoryOne(){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[0]))
$memory=&$memory[$memory[0]];
else break;
}return $memory;
}public function getMemoryString($c=1){
$memory=$this->memory[$this->locate];
$l=$this->getcount()-$c;
$l=$l<0?0:$l;
while($l--){
if(isset($memory[0]))
$memory=$memory[$memory[0]];
else break;
}$memory=$this->getsub([0,$memory]);
return $this->getstring($memory);
}public function getInput(){
return $this->memory[1];
}public function getInputString(){
$input=$this->memory[1];
$input=$this->getsub($input);
return $this->getstring($input);
}public function getOutput(){
return $this->memory[2];
}public function getOutputString(){
$output=$this->memory[2];
$output=$this->getsub($output);
return $this->getstring($output);
}public function getReturn(){
return $this->memory[2];
}public function getReturnString(){
$return=$this->memory[2];
$return=$this->getsub($return);
return $this->getstring($return);
}public function getNames(){
return $this->memory[3];
}public function getNamesString(){
$names=$this->memory[3];
$names=$this->getsubs($names);
foreach($names as &$name)
$name=$this->getstring($name);
return $names;
}public function getDefines(){
return $this->memory[4];
}public function getDefinesString(){
$defines=$this->memory[4];
$defines=$this->getsubs($defines);
foreach($defines as &$define)
$define=$this->getstring($define);
return $defines;
}private function setEndMemory(){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[$memory[0]][0]))
$memory=&$memory[$memory[0]];
else break;
}$memory[0]=count($memory)-1;
}private function getEndMemory(){
$memory=&$this->memory[$this->locate];
while(true){
if(isset($memory[$memory[0]][0]))
$memory=&$memory[$memory[0]];
else break;
}return $memory[0];
}

public function setInput(string $input=''){
$input=str_split($input);
$memory=[$this->memory[1][0]];
foreach($input as $c)
$memory[]=ord($c);
$this->memory[1]=$memory;
}public function setOuput(string $output=''){
$output=str_split($output);
$memory=[$this->memory[2][0]];
foreach($output as $c)
$memory[]=ord($c);
$this->memory[2]=$memory;
}public function setReturn(string $return=''){
$return=str_split($return);
$memory=[$this->memory[5][0]];
foreach($return as $c)
$memory[]=ord($c);
$this->memory[5]=$memory;
}

private
$lcs=false,
$exit=true,
$gkp=[false,0,1,1,0,1],
$ssv=0,
$fto='',
$sve=0,
$code='';

public function subrun(&$code){
$lcs=&$this->lcs;
$exit=&$this->exit;
$gkp=&$this->gkp;
$ssv=&$this->ssv;
$fto=&$this->fto;
$sve=&$this->sve;
$ext=true;
$phy=0;
$tui="";
for($c=0;(isset($code[$c])&&$exit&&$ext)||$gkp[0];++$c){
if($gkp[0]){
if(--$gkp[2]<0){
if(--$gkp[3]<0){
$code=substr_replace($code,'',$gkp[4]+$gkp[1],$c-$gkp[4]-$gkp[1]);
$c=$gkp[4];
$gkp=[false,0,1,1,$c,1];
}else{
$c=$gkp[4]+$gkp[1];
$gkp[2]=$gkp[5];
}
}
}if(!isset($code[$c]))continue;
switch($code[$c]){
case "+":
$this->addone();
break;case "-":
$this->remone();
break;case "&":
$this->savelocate();
break;case "'":
$p='';
while($code[++$c]!="'")
$p.=$code[$c];
$this->setmemory($p);
break;case '"':
$p='';
while($code[++$c]!='"')
$p.=$code[$c]!='\\'?$code[$c]:$code[++$c]=='"'||$code[$c]=='\\'?$code[$c]:'\\'.$code[$c];
$this->setmemorys($p);
break;case ",":
$this->printnumber();
break;case ".":
$p=0;
while(@$code[++$c]=='.')++$p;
--$c;
$this->printmemory($p);
break;case "":
$this->locate=0;
break;case 1:
$this->locate=1;
break;case 2:
$this->locate=2;
break;case 3:
$this->locate=3;
break;case 4:
$this->locate=4;
break;case 5:
$this->locate=5;
break;case ">":
$p=1;
while(@$code[++$c]=='>')++$p;
--$c;
$this->nextmemory($p);
break;case "<":
$p=1;
while(@$code[++$c]=='<')++$p;
--$c;
$this->prevmemory($p);
break;case "a":
$this->setsave(0);
break;case "b":
$this->setsave(1);
break;case "c":
$this->setsave(2);
break;case "d":
$this->setsave(3);
break;case "e":
$this->setsave(4);
break;case "f":
$this->setsave(5);
break;case "g":
$this->setsave(6);
break;case "h":
$this->setsave(7);
break;case "i":
$this->setsave(8);
break;case "j":
$this->setsave(9);
break;case "k":
$this->setsave(10);
break;case "l":
$this->setsave(11);
break;case "m":
$this->setsave(12);
break;case "n":
$this->setsave(13);
break;case "o":
$this->setsave(14);
break;case "p":
$this->setsave(15);
break;case "q":
$this->setsave(16);
break;case "r":
$this->setsave(17);
break;case "s":
$this->setsave(18);
break;case "t":
$this->setsave(19);
break;case "u":
$this->setsave(20);
break;case "v":
$this->setsave(21);
break;case "w":
$this->setsave(22);
break;case "x":
$this->setsave(23);
break;case "y":
$this->setsave(24);
break;case "z":
$this->setmemory(0);
break;case "_":
$c=$this->getMemoryOne();
break;case "$":
$this->setReturn(substr($code,$c,$this->getMemoryOne()));
break;case "[":
case "{":
$start=$code[$c]=='['?1:0;
$d='';
$p=1;
while($p>0){
$d.=$h=$code[++$c];
if($h=='{'||$h=='[')++$p;
elseif($h=='}'||$h==']')--$p;
}$end=$code[$c]==']'?1:0;
$d=substr($d,0,-1);
if($start){
if($end){
while($this->getMemoryOne())
$this->subrun($d);
}else{
if($this->getMemoryOne())
$this->subrun($d);
}
}else{
if($end){
do{
$this->subrun($d);
}while($this->getMemoryOne());
}else $this->subrun($d);
}
break;case "@":
$this->setMemory(rand(0,999999999));
break;case "#":
$this->subrun(ord($this->getMemoryOne()));
break;case "!":
$this->setReturn(str_replace('.','',microtime(true)));
break;case "?":
if(@$code[$c+1]=='?'){
$co=&$this->code;
$l='';
$names=$this->getNamesString();
$defines=$this->getDefinesString();
$x=substr($co,0,$c);
$y=substr($co,$c);
while($l!=$co){
$l=$co;
foreach($names as $num=>$name){
if($name=="\x00")continue;
$define=@$defines[$num];
$z=strlen($x);
$x=str_replace($name,$define,$x);
$c+=strlen($x)-$z;
$y=str_replace($name,$define,$y);
$co=$x.$y;
}
}++$c;
}else{
$l='';
$names=$this->getNamesString();
$defines=$this->getDefinesString();
$x=substr($code,0,$c);
$y=substr($code,$c);
while($l!=$code){
$l=$code;
foreach($names as $num=>$name){
if($name=="\x00")continue;
$define=@$defines[$num];
$z=strlen($x);
$x=str_replace($name,$define,$x);
$c+=strlen($x)-$z;
$y=str_replace($name,$define,$y);
$code=$x.$y;
}
}
}
break;case "(":
$memory=$this->memory[$this->locate];
$d='';
$p=1;
while($p>0){
$d.=$h=$code[++$c];
if($h=='(')++$p;
if($h==')')--$p;
}$d=substr($d,0,-1);
$this->subrun($d);
$this->memory[$this->locate]=$memory;
break;case "^":
$this->memoryReset();
break;case ")":
$memory=$this->memory;
$d='';
$p=1;
while($p>0){
$d.=$h=$code[++$c];
if($h==')')++$p;
if($h=='(')--$p;
}$d=substr($d,0,-1);
$this->subrun($d);
$this->memory=$memory;
break;case "^":
$this->memoryReset();
break;case "|":
if($lcs===false)$lcs=$this->locate;
else{
$this->locate=$lcs;
$lcs=false;
}
break;case ";":
$exit=false;
break;case "~":
$exi=false;
break;case ":":
$this->runFile($this->getMemoryString());
break;case "`":
$this->setmemory(ord(@$code[++$c]));
break;case "A":
$this->setmemory(!$this->getMemoryOne());
break;case "B":
$this->setmemory(~$this->getMemoryOne());
break;case "C":
$this->setmemory($this->getMemoryString());
break;case "D":
$this->setmemorys((string)$this->getMemoryOne());
break;case "E":
$this->setEndMemory();
break;case "F":
$this->setmemory($this->getEndMemory());
break;case "%":
$this->setmemory($this->locate);
break;case "G":
$gkp[1]=$this->getMemoryOne();
break;case "H":
$gkp[5]=$gkp[2]=$this->getMemoryOne();
break;case "I":
$gkp[3]=$this->getMemoryOne();
break;case "J":
if($gkp[0])$gkp[0]=false;
else $gkp[0]=true;
$gkp[4]=$c+1;
$c+=$gkp[1];
break;case "K":
$ssv=$this->getMemoryOne();
break;case "L":
$this->setmemory($ssv);
break;case "M":
$fto=$this->getMemoryString();
break;case "N":
$this->setmemorys($fto);
break;case "O":
$this->subrun(@$code[$c+$this->getMemoryOne()]);
break;case "P":
$this->setmemory($c);
break;case "Q":
$sve=$c;
break;case "R":
$c=$sve;
break;case "S":
$this->setmemory(strlen($code));
break;case "T":
$code=$this->getMemoryString();
break;case "U":
$this->setmemory(rand(0,1));
break;case "V":
$phy=$this->getMemoryOne();
break;case "W":
$this->setmemory($phy);
break;case "X":
$tui=$this->getMemoryString();
break;case "Y":
$this->setmemorys($tui);
break;case "Z":
$c=0;
break;case "*":
$this->setsave(25);
break;case 6:
$this->setsave(26);
break;case 7:
return;
break;case 8:
$this->setmemory($this->getcount());
break;case 9:
if(isset($this->modules[$o=$this->getMemoryOne()]))
$this->subrun($this->modules[$o]);
break;case "/":
$p=0;
while(@$code[++$c]=='.')++$p;
--$c;
$this->resetMemorys($p);
break;case "\x01":
$this->setmemory(ord(@$code[$this->getMemoryOne()]));
break;case "\x02":
$this->setmemory(isset($code[$this->getMemoryOne()])?1:0);
break;case "]":
$memory=$this->getMemoryOne();
$d='';
$p=1;
while($p>0){
$d.=$h=$code[++$c];
if($h==']')++$p;
if($h=='[')--$p;
}$d=substr($d,0,-1);
$this->subrun($d);
$this->setmemory($memory);
}
}return $this->getOutputString();
}public function run($code){
$x="";
$y="";
foreach($GLOBALS as $key=>$val)if(is_string($val)||is_numeric($val)){
$x.='"${'.str_replace(["\\","\""],["\\\\","\\\""],$key).'}">>';
$y.='"\"'.str_replace(["\\","\""],["\\\\","\\\""],$val).'\"">>';
}if(isset($GLOBALS['_GET']))
foreach($GLOBALS['_GET'] as $key=>$val)
if(is_string($val)||is_numeric($val)){
$x.='"GET{'.str_replace(["\\","\""],["\\\\","\\\""],$key).'}">>';
$y.='"\"'.str_replace(["\\","\""],["\\\\","\\\""],$val).'\"">>';
}if(isset($GLOBALS['_POST']))
foreach($GLOBALS['_POST'] as $key=>$val)
if(is_string($val)||is_numeric($val)){
$x.='"POST{'.str_replace(["\\","\""],["\\\\","\\\""],$key).'}">>';
$y.='"\"'.str_replace(["\\","\""],["\\\\","\\\""],$val).'\"">>';
}if(isset($GLOBALS['_COOKIE']))
foreach($GLOBALS['_COOKIE'] as $key=>$val)
if(is_string($val)||is_numeric($val)){
$x.='"COOKIE{'.str_replace(["\\","\""],["\\\\","\\\""],$key).'}">>';
$y.='"\"'.str_replace(["\\","\""],["\\\\","\\\""],$val).'\"">>';
}$this->modules[1]="|3{$x}4{$y}|??";
$this->code=&$code;
$this->subrun($code);
}public function __construct(string $code='',string $input=''){
$this->setInput($input);
return $this->run($code);
}public function runFile(string $file,string $input=''){
$this->setInput($input);
$f=@file_get_contents($file);
if($f===false)return false;
return $this->run($f);
}

private $modules=[
"+9+9+9",
'',
'|3"print ">>"echo">>"view">>4"+++G--J..//">>"..//">>",">>|??',
'|3"rand">>"math">>"equal">>"add">>"rem">>"mul">>"div">>"res">>"pow">>"sqr">>4"&>@fxz<">>"&>">>"ax<">>"bx<">>"cx<">>"dx<">>"xex<">>"xfx<">>"xgx<">>"(xhx)<">>|??',
"|3\"ch.by\">>\"ch.next\">>\"ch.prev\">>\"ch.the\">>\"ch.get\">>\"ch.exists\">>\"ch.set\">>\"ch.add\">>4\">P&<b>'13'&<b\x01\">>\"P>'9'&<b\x01\">>\"P-\x01\">>\"P\">>\"\x01\">>\"\x02\">>\"_\">>\">P&<b>'12'&<b_\">>|??"
];

}function brainfuck2($code,$input=''){
return (new BrainFuck2($code,$input))->getOutputString();
}class XNObject {
private $var        = null,
        $call       = [],
        $static     = [],
        $destruct   = null,
        $wakeup     = null,
        $tostring   = null,
        $callmethod = null,
        $callstatic = null,
        $invoke     = null,
        $clone      = null;
public function var(&$var){
$this->var=&$var;
$var=$this;
}public function __construct(&$var=null){
if($var)$this->from($var);
}public function from(&$object){
$object=serialize((object)$object);
$object=replaceone("8:\"stdClass\"","8:\"XNObject\"",$object);
$object=unserialize($object);
$object->var($object);
return $object;
}public function set(string $var,string $type,$value){
set_class_var($this->var,$type,$var,$value);
}public function get(string $var){
return get_class_var($this->var,get_class_var_type($this->var,$var),$var);
}public function type(string $var){
return get_class_var_type($this->var,$var);
}public function setMethod(string $method,object $value){
$this->call[$method]=$value;
}public function setStaticMethod(string $method,object $value){
$this->static[$method]=$value;
}public function setDestruct(object $value){
$this->destruct=$value;
}public function setWakeup(object $value){
$this->wakeup=$value;
}public function setTostring(object $value){
$this->tostring=$value;
}public function setInvoke(object $value){
$this->invoke=$value;
}public function setClone(object $value){
$this->clone=$value;
}public function clone(){
return $this->__clone();
}public function __destruct(){
if($this->destruct)($this->destruct)();
}public function __toString(){
if($this->tostring)($this->tostring)();
}public function __clone(){
if($this->clone)if(($r=($this->clone)())&&is_object($r))return $r;
$object=$this->object;
return new XNObject($object);
}public function __call($x,$y){
if($this->callmethod)$r=($this->callmethod)($x,$y);
if(isset($this->call[$x]))
return ($this->call[$x])(...$y);
if(isset($r))return $r;
}public static function __callStatic($x,$y){
if($this->callstatic)$r=($this->callstatic)($x,$y);
if(isset($this->static[$x]))
return ($this->static[$x])(...$y);
if(isset($r))return $r;
}public function all(){
return get_class_all_vars($this->var);
}
}
function xnobject($object=null){
return $object=new XNObject($object);
}class XNCode {
private $code,$errorfile="error_log",$wait=false,$proc,$pipes,$php="php",$timer=0,$global=false,$response;
public function setCode($code){
if($code instanceof XNClosure){
$code = $code->getCode();
}elseif($code instanceof Closure){
$code = (new XNClosure($code))->getCode();
}elseif(is_string($code)&&(file_exists($code)||filter_var($code,FILTER_VALIDATE_URL))){
$code = file_get_contents($code);
}elseif(is_string($code));
else{
new XNError("XNCode","Invalid Code or Closure or File");
return false;
}$this->code = $code;
return true;
}public function getCode($code){
return $this->code;
}public function __construct($code=''){
$this->setCode($code);
}public function setPHPConsole($php="php"){
if(!XNStr::endiby($php,"php.exe")&&$php!='php')return false;
$this->php=$php;
return true;
}public function addCode($code){
$last = $this->code;
$this->setCode($code);
$code = $this->code;
$this->code = "$last;$code";
}public function timer($time){
if(!is_numeric($time))return false;
$this->timer=$time;
return true;
}public function global(bool $global=null){
if($global===null)$global=!$this->global;
$this->global=$global;
}private function setErrorFile(string $file=''){
$this->errorfile=$file;
}private function compile(){
$code = $this->code;
if($this->global){
$variables=array_clone($GLOBALS);
foreach($variables as $key=>$val)
if($key=="GLOBALS"||$key=="-XN-")unset($variables[$key]);
foreach($variables as $key=>$val){
if(is_object($val))$val="unserialize(base64_decode('".base64_encode(serialize($val))."'))";
else $val=unce($val);
$code="\${'".str_replace(["\\","'"],["\\\\","\\'"],$key)."'}=$val;\n$code";
}
}if($this->timer){
$code="usleep({$this->timer});\n$code";
}$code="<?php\n$code\n?>";
return $code;
}private function open(){
$proc=proc_open($this->php,$this->errorfile?[
["pipe","r"],
["pipe","w"],
["file",$this->errorfile,"a"]
]:[
["pipe","r"],
["pipe","w"]
],$pipes,".",["PARENT_XNCODE"=>__FILE__]);
$this->proc=$proc;
$this->pipes=$pipes;
return $pipes;
}public function run(){
if($this->proc){
new XNError("XNCode","you last runned the Code");
return false;
}$pipes=$this->open();
fwrite($pipes[0],$this->compile());
return true;
}public function close(){
if($this->proc){
fclose($this->pipes[0]);
fclose($this->pipes[1]);
if(!$this->wait)proc_terminate($this->proc,15);
proc_close($this->proc);
$this->proc=null;
$this->response=null;
}
}public function __destruct(){
if(!$this->proc);
elseif($this->wait)
$this->close();
else{
fclose($this->pipes[0]);
fclose($this->pipes[1]);
}
}public function response(){
if(!$this->proc){
new XNError("XNCode","code not runned");
return false;
}return $this->response?$this->response:$this->response=stream_get_contents($this->pipes[1]);
}public function wait($timeout=0){
if(!$this->proc){
new XNError("XNCode","code not runned");
return false;
}if(!$timeout){
while(fgets($this->pipes[1])!==false);
}else{
$end=time()+$timeout;
while(fgets($this->pipes[1])!==false&&time()<=$end);
if(time()>$end)return null;
}return true;
}public function stop(){
proc_terminate($this->proc);
}
}if(!isset($argv))
$argv=[__FILE__];
if(!isset($argc))
$argc=1;
if(!isset($_SERVER['argv']))
$_SERVER['argv']=[__FILE__];
if(!isset($_SERVER['argc']))
$_SERVER['argc']=1;
function rextester($type,$code,$input=''){
$language=$type;
$type=strtolower($type);
if($type=="ada")$type=39;
elseif($type=="nasm"||$type=="assemboly"||$type="asm")$type=15;
elseif(strhave($type,"bash")||$type=="shell")$type=38;
elseif($type=="csharp"||$type=="c#")$type=1;
elseif((strhave($type,"cpp")||strhave($type,"c++"))&&strhave($type,"gcc"))$type=7;
elseif((strhave($type,"cpp")||strhave($type,"c++"))&&strhave($type,"clang"))$type=27;
elseif(((strhave($type,"cpp")||strhave($type,"c++"))&&(strhave($type,"vc++")||strhave($type,"visual")))||$type=="vc++")$type=28;
elseif((strhave($type,"cpp")||strhave($type,"c"))&&strhave($type,"gcc"))$type=6;
elseif((strhave($type,"cpp")||strhave($type,"c"))&&strhave($type,"clang"))$type=26;
elseif((strhave($type,"cpp")||strhave($type,"c"))&&(strhave($type,"vc")||strhave($type,"visual")))$type=29;
elseif($type=="common lisp"||$type=="clisp"||$type=="lisp")$type=18;
elseif($type=="d")$type=30;
elseif($type=="elixir")$type=41;
elseif($type=="erlang")$type=40;
elseif($type=="fsharp"||$type=="f#")$type=3;
elseif($type=="fortran")$type=45;
elseif($type=="go")$type=20;
elseif($type=="haskell")$type=11;
elseif($type=="java")$type=4;
elseif($type=="javascript"||$type=="js")$type=17;
elseif($type=="kotlin")$type=43;
elseif($type=="lua")$type=14;
elseif($type=="mysql"||$type=="sqlite"||$type="sqlit"||$type=="sqli"||$type=="sql"||$type=="mysqlite"||$type=="mysqlit"||$type=="mysqli")$type=33;
elseif($type=="node.js"||$type=="nodejs")$type=23;
elseif($type=="ocaml")$type=42;
elseif($type=="octave")$type=25;
elseif($type=="objective-c"||$type=="objectivec")$type=10;
elseif($type=="oracle")$type=35;
elseif($type=="pascal")$type=9;
elseif($type=="prel")$type=13;
elseif($type=="php")$type=8;
elseif($type=="postgresql")$type=34;
elseif($type=="prolog")$type=19;
elseif($type=="python"||$type=="py")$type=5;
elseif($type=="python3"||$type=="py3"||$type=="python 3"||$type=="py 3")$type=24;
elseif($type=="r")$type=31;
elseif($type=="ruby")$type=12;
elseif($type=="scala")$type=21;
elseif($type=="scheme")$type=22;
elseif($type=="sqlserver"||$type="sql server")$type=16;
elseif($type=="swift")$type=37;
elseif($type=="tcl")$type=32;
elseif($type=="visual basic"||$type=="visualbasic"||$type="basic"||$type=="vbnet"||$type=="vb.net")$type=2;
elseif($type=="brainfuck")$type=44;
else $type=false;
if($type){
$link="http://rextester.com/rundotnet/api";
$curl = curl_init($link);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_POSTFIELDS,[
  "LanguageChoice"=>$type,
  "Program"=>$code,
  "Input"=>$input,
  "CompilerArgs"=>""
]);
$res = curl_exec($curl);
curl_close($curl);
return $res;
}
}

$GLOBALS['-XN-']['requirefile']=debug_backtrace();
$GLOBALS['-XN-']['sourcefile']=end($GLOBALS['-XN-']['requirefile']);
$GLOBALS['-XN-']['sourceline']=$GLOBALS['-XN-']['sourcefile']['line'];
$GLOBALS['-XN-']['sourcefile']=$GLOBALS['-XN-']['sourcefile']['file'];
$GLOBALS['-XN-']['requirefile']=$GLOBALS['-XN-']['requirefile'][0];
$GLOBALS['-XN-']['requireline']=$GLOBALS['-XN-']['requirefile']['line'];
$GLOBALS['-XN-']['requirefile']=$GLOBALS['-XN-']['requirefile']['file'];

$GLOBALS['-XN-']['endTime']=microtime(true);
?>
