<?php
//created by Avid - white web tm
$GLOBALS['-XN-']=[];
$GLOBALS['-XN-']['starttime']=microtime(true);
function whiteweb(){
return ["telegram"=>"@white_web","website"=>"white-web.ir"];
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
}function str_progress($pro1,$pro2,$proc,$min,$max){
$con=round($proc/($max/$min));
$cou=$proc-$con;
return str_repeat("$pro1",$con).str_repeat("$pro2",$cou);
}class tlbot{
public $token,$api,$ch,$final,$data,$last,$runs=[],$pwr,$wh;
public function __construct($token=false){
if(!$token)$token=$GLOBALS['token'];
$this->token=$token;
$this->api="https://api.telegram.org/bot$token";
$this->pwr="https://api.pwrtelegram.xyz/bot$token";
$this->wh="https://api.white-web.ir/bot$token";
$this->ch=curl_init();
}public function update(){
$r=json_decode(file_get_contents("php://input"));
$this->data=$r;
return $r;
}public function makereq($method,$datas=[],$level=3){
$datas=(array)$datas;
if($level==1){
header("Content-Type: application/json");
$datas['method']=$method;
echo json_encode($datas);
$r=true;
}elseif($level==2){
$r=fclose(fopen("$this->api/$method?".http_build_query($datas),"r"));flush();
}elseif($level==3){
curl_setopt($this->ch,CURLOPT_URL,"$this->api/$method");
curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($this->ch,CURLOPT_POSTFIELDS,$datas);
$r=curl_exec($this->ch);
flush();
$r=json_decode($r);
}elseif($level==4){
$r=fclose(fopen("$this->pwr/$method?".http_build_query($datas),"r"));flush();
}elseif($level==5){
curl_setopt($this->ch,CURLOPT_URL,"$this->pwr/$method");
curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($this->ch,CURLOPT_POSTFIELDS,$datas);
$r=curl_exec($this->ch);
flush();
$r=json_decode($r);
}$datas['method']=$method;
$this->last=$datas;
if($this->runs[$method])$this->runs[$method]($r);
$this->final=$r;
return $r;
}public function deleterun($method){
unset($this->runs[$mehtod]);
}public function setrun($method,$fun){
$this->runs[$method]=$fun;
}public function close(){
curl_close($this->ch);
$this->token=null;
$this->api=null;
$this->ch=null;
$this->runs=null;
$this->final=null;
$this->data=null;
$this->last=null;
}public function reset(){
$this->final=null;
$this->data=null;
$this->last=null;
}public function create($type,$keys=[],$size=false,$arr=false){
if($arr){
if($type=="remove_keyboard"||$type===1)
return ["remove_keyboard"=>true];
elseif($type=="force_reply"||$type===2)
return ["force_reply"=>true];
elseif($type=="keyboard"||$type===3)
return ["keyboard"=>$keys,"resize_keyboard"=>$size];
elseif($type=="inline_keyboard"||$type===4)
return ["inline_keyboard"=>$keys];
}else{
if($type=="remove_keyboard"||$type===1)
return json_encode(["remove_keyboard"=>true]);
elseif($type=="force_reply"||$type===2)
return json_encode(["force_reply"=>true]);
elseif($type=="keyboard"||$type===3)
return json_encode(["keyboard"=>$keys,"resize_keyboard"=>$size]);
elseif($type=="inline_keyboard"||$type===4)
return json_encode(["inline_keyboard"=>$keys]);
}return false;
}public function get($chat){
return $this->makereq("getChat",[
"chat_id"=>$chat
]);
}public function me(){
return $this->makereq("getMe");
}public static function getbot($token){
return json_decode(file_get_contents("https://api.telegram.org/bot$token/getMe"))->result;
}public function webhook($webhook=false,$level=3){
if($webhook){
return $this->makereq("setWebhook",[
"url"=>$webhook
],$level);
}else{
return $this->makereq("getWebhookInfo");
}
}public function updates($offset,$limit,$timeout=0){
return $this->makereq("getUpdates",[
"offset"=>$offset,
"limit"=>$limit,
"timeout"=>$timeout
],3);
}public function profile($chat){
return $this->makereq("getUserProfilePhotos",[
"user_id"=>$chat
],3);
}public function ban($chat,$user,$level=3){
return $this->makereq("banChatMember",[
"chat_id"=>$chat,
"user_id"=>$user
],$level);
}public function unban($chat,$user,$level=3){
return $this->makereq("unbanChatMember",[
"chat_id"=>$chat,
"user_id"=>$user
],$level);
}public function action($chat,$action="typing",$level=3){
return $this->makereq("sendChatAction",[
"chat_id"=>$chat,
"action"=>$action
],$level);
}public function forward($to,$from,$message,$level=3){
return $this->makereq("forwardMessage",[
"chat_id"=>$to,
"from_chat_id"=>$from,
"message_id"=>$message
],$level);
}public function message($chat,$text,$level=3,$data1=null,$data2=null,$data3=null,$data4=null){
$r=["chat_id"=>$chat,"text"=>$text];
foreach([$data1,$data2,$data3,$data4] as $x){
if(is_numeric($x))$reply=$x;
elseif($x=="HTML"||$x=="MarkDown"&&$caption)$parse=$x;
elseif(is_array($x))$markup=json_encode($x);
elseif($x===false||$x===true)$notifi=$x;
}if($reply)$r['reply_to_message_id']=$reply;
if($parse)$r['parse_mode']=$parse;
if($markup)$r['reply_markup']=$markup;
if($notifi)$r['disable_notification']=$notifi;
return $this->makereq("sendMessage",$r,$level);
}public function media($media,$chat,$file,$level=3,$data1=null,$data2=null,$data3=null,$data4=null,$data5=null){
$media=strtolower($media);
$med=$media;
if($med=="video_note"||$med=="videonote"||$med=="video note"){
$media="videonote";$med="video_note";
}$r=["chat_id"=>$chat,"$med"=>$file];
foreach([$data1,$data2,$data3,$data4,$data5] as $x){
if(is_numeric($x))$reply=$x;
elseif($x=="HTML"||$x=="MarkDown"&&$caption)$parse=$x;
elseif(is_array($x))$markup=json_encode($x);
elseif($x===false||$x===true)$notifi=$x;
else $caption=$x;
}if($reply)$r['reply_to_message_id']=$reply;
if($parse)$r['parse_mode']=$parse;
if($markup)$r['reply_markup']=$markup;
if($caption)$r['caption']=$caption;
if($notifi)$r['disable_notification']=$notifi;
return $this->makereq("send$media",$r,$level);
}public function contact($chat,$number,$first,$level=3,$data1=null,$data2=null){
$r=["chat_id"=>$chat,"phone_number"=>$number,"first_name"=>$first];
foreach([$data1,$data2] as $x){
if(is_numeric($x))$reply=$x;
else $last=$x;
}if($reply)$r['reply_to_message_id']=$reply;
if($last)$r['last_name']=$last;
return $this->makereq("sendContact",$r,$level);
}public function file($id){
$path=$this->makereq("getFile",["file_id"=>$id],3)->result->file_path;
return file_get_contents("https://api.telegram.org/file/bot$this->token/$path");
}public function edit($chat,$message,$text,$level=3,$data1=null,$data2=null){
if($chat==false)$r=["inline_message_id"=>$message,"text"=>$text];
elseif($message==false)$r=["inline_message_id"=>$chat,"text"=>$text];
else $r=["chat_id"=>$chat,"text"=>$text,"message_id"=>$message];
foreach([$data1,$data2] as $x){
if(is_numeric($x))$reply=$x;
elseif(is_array($x))$markup=json_encode($x);
}if($reply)$r['reply_to_message_id']=$reply;
if($markup)$r['reply_markup']=$markup;
return $this->makereq("editMessageText",$r,$level);
}public function getFile($file){
return $this->makereq("getFile",["file_id"=>$file],3);
}public function download($path){
return file_get_contents("https://api.telegram.org/file/bot$this->token/$path");
}public static function istoken($token){
return preg_match('/$[0-9]{4,16}:[a-zA-Z0-9-_]{35}$/',$token);
}public static function ismodir($chat,$modir){
if($chat==$modir||in_array($chat,$modir))return true;
return false;
}public function callback($id,$text,$level=3,$data1=null,$data2=null,$data3=null){
$r=["callback_query_id"=>$id,"text"=>$text];
foreach([$data1,$data2,$data3] as $x){
if(is_numeric($x))$cache=$x;
elseif($x===true||$x===false)$alert=$x;
elseif(is_string($x))$url=$x;
}if($alert)$r['show_alert']=$alert;
if($url)$r['url']=$url;
if($cache)$r['cache_time']=$cache;
return $this->makereq("answerCallbackQuery",$r,$level);
}public function restrict($chat,$user,$time,$restrict,$level=3){
$r=["chat_id"=>$chat,"user_id"=>$user,"until_time"=>$time];
foreach($restrict as $x=>$y){
$r['can_'.$x]=($y==true);
}return $this->makereq("restrictChatMember",$r,$level);
}public function promote($chat,$user,$promote,$level=3){
$r=["chat_id"=>$chat,"user_id"=>$user];
foreach($promote as $x=>$y){
$r['can_'.$x]=$y;
}return $this->makereq("promoteChatMember",$r,$level);
}public function description($chat,$description,$level=3){
return $this->makereq("setChatDescription",[
"chat_id"=>$chat,
"description"=>$description
],$level);
}public function delphoto($chat,$level=3){
return $this->makereq("deleteChatPhoto",["chat_id"=>$chat],$level);
}public function inline($id,$res,$level=3,$data1=null,$data2=null,$data3=null,$data4=null){
$r=["inline_query_id"=>$id,"results"=>$res];
foreach([$data1,$data2,$data3,$data4] as $x){
if(is_numeric($x))$cache=$x;
elseif(is_string($x))$offset=$x;
elseif($x===true||$x===false)$personal=$x;
elseif(is_array($x)){
$spmp=$x[0];
$spmt=$x[1];
}}if($cache)$r['cahe_time']=$cache;
if($offset)$r['next_offset']=$offset;
if($personal)$r['is_personal']=$personal;
if($spmp&&$spmt){
$r['switch_pm_text']=$spmt;
$r['switch_pm_parameter']=$spmp;
}return $this->makereq("answerInlineQuery",$r,$level);
}public function game($chat,$game,$level=3,$data1=null,$data2=null,$data3=null){
$r=["chat_id"=>$chat,"game_short_name"=>$game];
foreach([$data1,$data2,$data3] as $x){
if(is_numeric($x))$reply=$x;
elseif($x===true||$x===false)$disable=$x;
elseif(is_array($x))$keyboard=$x;
}if($reply)$r['reply_to_message_id']=$reply;
if($disable)$r['disable_notification']=$disable;
if($keyboard)$r['reply_markup']=json_encode($keyboard);
return $this->markup("sendGame",$r,$level);
}public function entities($text,$entities=[],$type="MarkDown"){
if($type=="HTML"||$type=="html"){
foreach($entities as $entiti){
$str=mb_substr($text,$entiti->offset,$entiti->length,"UTF-16LE");
switch($entiti->type){
case 'code':
$str='<code>'.$str.'</code>';
break;
case 'bold':
$str='<b>'.$str.'<b>';
break;
case 'italic':
$str='<i>'.$str.'</i>';
break;
case 'text_link':
$str='<a href="'.$entiti->url.'">'.$str.'</a>';
break;
case 'text_mention':
$str='<a href="tg://user?id='.$entiti->user->id.'">'.$str.'</a>';
break;
}$text=substr_replace($text,$str,$entiti->offset,$entiti->length);
}$text=mb_substr($text,0,$entiti->offset,"UTF-16LE").$str.mb_substr($text,$entiti->offset+$entiti->length,strlen($text),"UTF-16LE");
}else{
foreach($entities as $entiti){
$str=mb_substr($text,$entiti->offset,$entiti->length,"UTF-16LE");
switch($entiti->type){
case 'code':
$str='`'.$str.'`';
break;
case 'bold':
$str='**'.$str.'**';
break;
case 'italic':
$str='__'.$str.'__';
break;
case 'text_link':
$str='('.$str.')['.$entiti->user->id.']';
break;
case 'text_mention':
$str='('.$str.')[tg://user?id='.$entiti->user->id.']';
break;
}$text=mb_substr($text,0,$entiti->offset,"UTF-16LE").$str.mb_substr($text,$entiti->offset+$entiti->length,strlen($text),"UTF-16LE");
}return $text;
}
}
}function mb_substr_replace($text,$str,$offset=0,$length=0,$op="UTF-8"){
return mb_substr($text,0,$offset,$op).$str.mb_substr($text,$offset+$length,strlen($text),$op);
}class arr {
public $array=[];
public $key=[];
public $value=[];
public $number=[];
public $back=[];
public $_this_;
public $count=0;
public $versa=[];
public function set($array=[]){
if(is_array($array)){
$this->array=$array;
$count=0;
foreach($array as $k=>$v){
$this->key['value'][$k]=$v;
$this->key['number'][$k]=$count;
$this->value['key'][$v]=$k;
$this->value['number'][$v]=$count;
$this->number['value'][]=$v;
$this->number['key'][]=$k;
$count++;}
$this->count=$count;
$this->versa=$this->versa($array,$this->number['key']);
return true;
}else{
return false;}}
public function reset(){
$this->back=$this->key['value'];
$this->set($this->array);}
public function __construct($array=[]){
$this->set($array);flush();
}public function keyfilter($key,$preg){
$arr=[];
foreach($this->array as $k=>$v){
if($preg){
if(!preg_match($key,$k)){
$arr[$k]=$v;
}}else{
if($k==$key){
$arr[$k]=$v;}}
}
$this->array=$arr;
$this->reset();
}
public function valfilter($val,$preg){
$arr=[];
foreach($this->array as $k=>$v){
if($preg){
if(!preg_match($val,$v)){
$arr[$k]=$v;
}}else{
if($v==$val){
$arr[$k]=$v;}}
}
$this->array=$arr;
$this->reset();
}
public function firstarr(){
return ['key'=>$this->number['key'][0],
'value'=>$this->number['value'][0],
'number'=>0];
}public function lastarr(){
return ['key'=>$this->number['key'][$this->count],
'value'=>$this->number['value'][$this->count],
'number'=>$this->count];
}public function rmfirst(){
unset($this->array[$this->number['key'][0]]);
$this->reset();
}public function rmlast(){
unset($this->array[$this->number['key'][$this->count]]);
$this->reset();
}public function back(){
$this->array=$this->back;
$this->reset();
}public function _add_(){$this->_this_ ++;}
public function _back_(){$this->_this_ --;}
public function _go_($go){$this->_this_=$go;}
public function _get_number(){return $this->_this_;}
public function _get_key(){return $this->number['key'][$this->_this_];}
public function _get_val(){return $this->number['value'][$this->_this_];}
public function _get_(){return [
'key'=>$this->number['key'][$this->_this_],
'value'=>$this->number['value'][$this->_this_],
'number'=>$this->_this_];}
public function _start_(){$this->_this_=0;}
public function _end_(){$this->_this_=$this->count;}
public function _set_val($c){$this->array[$this->number['key'][$this->_this_]]=$c;
$this->reset();}
public function _set_key($c){
$this->array[$c]=$this->array[$this->number['key'][$this->_this_]];
unset($this->array[$this->number['key'][$this->_this_]]);
$this->reset();}
public function setval($k,$v){$this->array[$k]=$v;$this->reset();}
public function setkey($k,$t){$this->array[$t]=$this->array[$k];unset($this->array[$k]);$this->reset();}
public function _rem_(){unset($this->array[$this->number['key'][$this->_this_]]);$this->reset();}
public function close(){
$this->array=null;
$this->key=null;
$this->value=null;
$this->number=null;
$this->back=null;
$this->_this_=null;
$this->count=null;
$this->versa=null;
}public function implode_key($chr=''){
$string='';
foreach($this->array as $k=>$v){
if(!$string){
$string=$k;
}else{
$string=$string.$chr.$k;
}}return $string;
}public function implode_val($chr=''){
$string='';
foreach($this->array as $k=>$v){
if(!$string){
$string=$v;
}else{
$string=$string.$chr.$v;
}}return $string;
}public function implode_all($chr1='',$chr2=''){
$string='';
foreach($this->array as $k=>$v){
if(!$string){
$string=$k.$chr2.$v;
}else{
$string=$string.$chr1.$k.$chr2.$v;
}}return $string;
}public function addElement($key,$val){
$this->array[$key]=$val;
$this->reset();
}public function addArray($array){
foreach($array as $k=>$v){
$this->array[$k]=$v;}
$this->reset();
}public function remElement($key){
unset($this->array[$key]);
$this->reset();
}public function remArray($array){
foreach($array as $k=>$v){
unset($this->array[$k]);}
$this->reset();
}public function randomkey(){
return $this->number['key'][rand(0,$this->count-1)];
}public function randomval(){
return $this->number['value'][rand(0,$this->count-1)];
}public function randomal(){
return ['key'=>$this->randomkey(),'value'=>$this->randomval()];
}public function json(){
return (object)$this->array;}
public function printthis(){
print_r($this->array);
}public function isKey($text){
if(isset($this->array[$text])){
return true;}
else{return false;}
}public function isVal($text){
if(isset($this->value['key'][$text])){
return true;}
else{return false;}
}public function type($text){
if(isset($this->array[$text])){
return 2;}
elseif(isset($this->value['key'][$text])){
return 1;}
else{return false;}
}public static function countAll($array='this'){
return count($this->getAll(false,$array));
}public static function getAll($sh=true,$array='this'){
if($array=='this'||$array==false)$array=$this->array;
$arr=[];
foreach($array as $k=>$v){
if(is_array($v)){
foreach($this->getAll($sh,$v) as $key=>$val){
$arr[$key]=$val;}
}else{
if($sh){$arr[$k][]=$v;}
else{$arr[$k]=$v;}
}}return $arr;
}public static function tokeyval($arr){
return $arr;
}public static function tovalkey($arr){
$ar=[];
foreach($arr as $k=>$v)$ar[$v]=$k;
return $ar;
}public static function tonumkey($arr){
$ar=[];$num=0;
foreach($arr as $k=>$v){
$num++;$ar[$num]=$k;
}return $ar;
}public static function tonumval($arr){
$ar=[];$num=0;
foreach($arr as $k=>$v){
$num++;$ar[$num]=$v;
}return $ar;
}public static function tokeynum($arr){
$ar=[];$num=1;
foreach($arr as $k=>$v){
$num++;$ar[$k]=$num;
}return $ar;
}public static function tovalnum($arr){
$ar=[];$num=1;
foreach($arr as $k=>$v){
$num++;$ar[$v]=$num;
}return $ar;
}public static function rev($arr){
return array_reverse($arr);
}public function str(){
return $this->string($this->array);
}public static function strArrayToJson($str){
return str_replace(['=>',']','['],[':','}','{'],$str);
}public static function strJsonToArray($str){
return str_replace([':','}','{'],['=>',']','['],$str);
}public static function ObjectToArray($object){
return (array)$object;
}public static function ArrayToObject($array){
return (object)$array;
}public static function toList($array){
return implode("\n",(array)$array);
}public static function fromList($str){
return explode("\n","$str");
}public static function string($array){
$as='[';$c=0;
foreach($array as $k=>$v){
if(is_array($v)){
if("$c"==json_encode($k,JSON_UNESCAPED_UNICODE))$as=$as.self::string($v).',';
else $as=$as.json_encode($k,JSON_UNESCAPED_UNICODE).'=>'.self::string($v).',';
}else{
if("$c"==json_encode($k,JSON_UNESCAPED_UNICODE))$as=$as.json_encode($v,JSON_UNESCAPED_UNICODE).',';
else $as=$as.json_encode($k,JSON_UNESCAPED_UNICODE).'=>'.json_encode($v,JSON_UNESCAPED_UNICODE).',';
}$c++;}$as=substr($as,0,-1).']';
return $as;}
public function ftext($text){
$this->set(json_decode($text,true));}
public function tojson($array){
if(!$array)$array=$this->array;
return (object)$array;}
private function versa($array,$keys){
$versa=[];
$c=count($array)-1;
while($c >= 0){
$versa[$keys[$c]]=$array[$key[$c]];
$c--;}
return $versa;
}public static function encode($arr=false,$start='{',$end='}',$repk='"',$tok='\\"',$repv='"',$tov='\\"',$bysk='"',$byek='"',$bysv='"',$byev='"',$bi=':',$ans="\n",$ane="\n",$an=",\n"){
if(!$arr)$arr=$this->array;
$r='';
foreach($arr as $k=>$v){
if(!$r)$r=$start.$ans.$bysk.str_replace($repk,$tok,$k).$byek.$bi.$bysv.str_replace($repv,$tov,$v).$byev;
else $r=$r.$an.$bysk.str_replace($repk,$tok,$k).$byek.$bi.$bysv.str_replace($repv,$tov,$v).$byev;
}$r=$r.$ane.$end;
return $r;
}public static function encodearr($arr=false,$options=[]){
return self::encode($arr,$options['start'],$options['end'],$options['key_replace_from'],$options['key_replace_to'],$options['value_replace_from'],$options['value_replace_to'],$options['start_key'],$options['end_key'],$options['start_value'],$options['end_value'],$options['key_and_value'],$options['start_and_key'],$options['value_and_end'],$options['value_and_key']);
}public function toObject(){
return (object)$this->array;
}public function toArray(){
return (array)$this->array;
}public static function clone($ar=false){
if(!$ar)$ar=$this->array;
if(is_array($ar))return (array)clone (object)$ar;
else return clone $ar;
}public static function frombase2($bs){
$ar=[];
foreach(self::rev(str_split($bs)) as $sb){
if($sb=='1')$ar[]=true;
else $ar[]=false;
}return $ar;
}public static function tobase2($ar){
$bs='';
foreach($ar as $ra){
if($ra)$bs=$bs.'1';
else $bs.'0';
}return $bs*1;
}public static function explode($bys,$str){
if(is_Array($bys)){
$r=[$str];
foreach($bys as $by){
$t=$r;unset($r);
foreach($t as $v){
foreach(explode($by,$v) as $k)$r[]=$k;
}}return $r;
}else return explode($bys,$str);
}public static function deleteType($type,$ar){
foreach($ar as $n=>$a){
if($a===$type)unset($ar[$n]);
}return $ar;
}public static function createAllVal($keys,$val){
foreach($keys as $n=>$key)$keys[$n]=$val;
return $keys;
}public static function count($ar){
return count((array)$ar);
}public static function getIndex($ar,$n){
$c=0;
foreach($ar as $k=>$v){
if($c==$n)return [$k,$v];
$n++;}return false;
}public static function lastIndex($ar){
$l=[];
foreach($ar as $k=>$v){
$l=[$k,$v];
}return $l;
}public static function firstIndex($ar){
return [key($ar),$ar[key($ar)]];
}public static function deleteIndex(&$ar,$n){
$c=0;$r='';
foreach($ar as $k=>$v){
if($c==$n){unset($ar[$n]);break;}
$c++;}
}public function runAll($fun){
foreach($this->array as $k=>$v)$this->array[$k]=$fun($k,$v);
$this->reset();
}public static function xmldecode($x){
return simplexml_load_string($x);
}public static function xmlencode($x){
$r='';
foreach((array)$x as $k=>$v){
$r="$r<$k>$v</$k>";
}return $r;
}public function toxml(){
return self::xmlencode($this->array);
}public function fromxml($x){
$this->array=self::xmldecode($x);
$this->reset();
return $this->array;
}public function fromjson($j){
$this->array=(array)$j;
$this->reset();
return $this->array;
}public function fromarray($a){
$this->array=$a;
$this->reset();
return $this->array;
}public function fromstring($s){
$this->array=json_decode($s,true);
$this->reset();
return $this->array;
}public function __toString(){
return implode('',$this->array);
}public function __destruct(){
unset($this->array);
unset($this->key);
unset($this->value);
unset($this->number);
unset($this->back);
unset($this->_this_);
unset($this->count);
unset($this->versa);
}public static function last($ar=false){
if(!$ar)$ar=$this->array;
return $ar[count($ar)-1];
}public static function numericforeach($ar,$fun){
for($i=0;$ar[$i]==true;$i++){
$fun($ar[$i],$i);
}
}public static function setlist($ar){
$arr=[];
foreach($ar as $v)$arr[]=$v;
return $arr;
}
}class fileObject_ALLFILESREADE{
public $file="";
public function __construct($file){
$this->file=$file;
}public function read(){
return file_get_contents($this->file);
}public function write($data){
return file_put_contents($this->file,$data);
}public function delete(){
return unlink($this->file);
}public function add($data){
return file_put_contents($this->file,file_get_contents($this->file).$data);
}public function size(){
return filesize($this->file);
}}function getfiles($dir,$fulname=false){
$files=scandir($dir);
$fis=[];
foreach($files as $file){
if($file=='.'){
if($fulname)$fis["$dir/."]=&$fis;
else $fis["."]=&$fis;}
elseif($file=='..'){
if($fulname)$fis["$dir/.."]="$dir/..";
else $fis[".."]="$dir/..";
}elseif(filetype("$dir/$file")=="dir"){
if($fulname)$fis["$dir/$file"]=getallfiles("$dir/$file");
else $fis["$file"]=getallfiles("$dir/$file");
}else{
if($fulname){
$fis["$dir/$file"]=new fileObject_ALLFILESREADE("$dir/$file");
}else{
$fis["$file"]=new fileObject_ALLFILESREADE("$dir/$file");
}}}return $fis;
}function actiondir($dir,$action,$pri=true){
if($pri){
$scan=scandir($dir);
if(substr($dir,-2,1)=='/')
$dir=substr_replace($dir,'',-2,1);
foreach($scan as $file){
if($file=='.'||$file=='..'){}
else $action((object)[
"address"=>"$dir/$file",
"dir"=>"$dir",
"file"=>"$file",
"type"=>filetype("$dir/$file")
]);
}}else{
$scan=scandir($dir);
if(substr($dir,-2,1)=='/')
$dir=substr_replace($dir,'',-2,1);
foreach($scan as $file){
$type=filetype("$dir/$file");
if($file=='.'||$file=='..'){}
elseif($type=="dir"){
$action((object)[
"address"=>"$dir/$file",
"dir"=>"$dir",
"file"=>"$file",
"type"=>"dir"
]);
actiondir("$dir/$file",$action,false);
}else{
$action([
"address"=>"$dir/$file",
"dir"=>"$dir",
"file"=>"$file",
"type"=>"file"
]);
}}
}
}function filesearch($dir,$file){
$files=scandir($dir);
$rs=[];
foreach($files as $f){
if($f=='.'||$f=='..'){}
elseif(filetype("$dir/$f")=="dir"){
foreach(filesearch("$dir/$f",$file) as $a){
$rs[]="$a";}
}elseif(strpos($f,$file)||$f==$file)$rs[]="$dir/$f";
}return $rs;
}function preg_filesearch($dir,$file){
$files=scandir($dir);
$rs=[];
foreach($files as $f){
if($f=='.'||$f=='..');
elseif(filetype("$dir/$f")=="dir"){
foreach(preg_filesearch("$dir/$f",$file) as $a){
$rs[]="$a";}
}elseif(preg_match($f,$file))$rs[]="$dir/$f";
}return $rs;
}function dirsize($dir){
$size=0;
actiondir($dir,function($ev) use ($size){$size+=filesize($ev['address']);},false);
return $size;
}function dircopy($dir,$to){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
mkdir($to);
foreach($s as $file){
if(filetype("$dir/$file")=='dir')dircopy("$dir/$file","$to/$file");
else copy("$dir/$file","$to/$file");
}
}function refereronethishost(){
return (parse_url($_SERVER['HTTP_REFERER'])['host']==$_SERVER['HTTP_HOST']);
}define("BASE255_CHARS","!#$%()*,.0123456789:;=@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_abcdefghijklmnopqrstuvwxyz{|}~¡¢£¤¥¦§¨©ª«¬®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎ");
define("BASE64_CHARS","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=");
define("BASE62_CHARS","0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
define("BASE38_CHARS","0123456789abcdefghijklmnopqrstuvwxyz");
define("UPPER_CHARS","ABCDEFGHIJKLMNOPQRSTUVWXYZ");
define("LOWER_CHARS","abcdefghijklmnopqrstuvwxyz");
define("BASE10_CHARS","0123456789");
define("ASCII_CHARS","@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~��������������������������������������������������������������������������������������������������������������������������������   !\"#$%&'()*+,-./0123456789:;<=>?\n");
function colors($g=false){
if($g)return ["Black"=>"000000","Night"=>"0C090A","Gunmetal"=>"2C3539","Midnight"=>"2B1B17","Charcoal"=>"34282C","DarkSlateGrey"=>"25383C","Oil"=>"3B3131","BlackCat"=>"413839","Iridium"=>"3D3C3A","BlackEel"=>"463E3F","BlackCow"=>"4C4646","GrayWolf"=>"504A4B","VampireGray"=>"565051","GrayDolphin"=>"5C5858","CarbonGray"=>"625D5D","AshGray"=>"666362","CloudyGray"=>"6D6968","SmokeyGray"=>"726E6D","Gray"=>"736F6E","Granite"=>"837E7C","BattleshipGray"=>"848482","GrayCloud"=>"B6B6B4","GrayGoose"=>"D1D0CE","MetallicSilver"=>"BCC6CC","BlueGray"=>"98AFC7","LightSlateGray"=>"6D7B8D","SlateGray"=>"657383","JetGray"=>"616D7E","MistBlue"=>"646D7E","MarbleBlue"=>"566D7E","SlateBlue"=>"737CA1","SteelBlue"=>"4863A0","BlueJay"=>"2B547E","DarkSlateBlue"=>"2B3856","MidnightBlue"=>"151B54","NavyBlue"=>"000080","BlueWhale"=>"342D7E","LapisBlue"=>"15317E","DenimDarkBlue"=>"151B8D","EarthBlue"=>"0000A0","CobaltBlue"=>"0020C2","BlueberryBlue"=>"0041C2","SapphireBlue"=>"2554C7","BlueEyes"=>"1569C7","RoyalBlue"=>"2B60DE","BlueOrchid"=>"1F45FC","BlueLotus"=>"6960EC","LightSlateBlue"=>"736AFF","WindowsBlue"=>"357EC7","GlacialBlueIce"=>"368BC1","SilkBlue"=>"488AC7","BlueIvy"=>"3090C7","BlueKoi"=>"659EC7","ColumbiaBlue"=>"87AFC7","BabyBlue"=>"95B9C7","LightSteelBlue"=>"728FCE","OceanBlue"=>"2B65EC","BlueRibbon"=>"306EFF","BlueDress"=>"157DEC","DodgerBlue"=>"1589FF","CornflowerBlue"=>"6495ED","SkyBlue"=>"6698FF","ButterflyBlue"=>"38ACEC","Iceberg"=>"56A5EC","CrystalBlue"=>"5CB3FF","DeepSkyBlue"=>"3BB9FF","DenimBlue"=>"79BAEC","LightSkyBlue"=>"82CAFA","DaySkyBlue"=>"82CAFF","JeansBlue"=>"A0CFEC","BlueAngel"=>"B7CEEC","PastelBlue"=>"B4CFEC","SeaBlue"=>"C2DFFF","PowderBlue"=>"C6DEFF","CoralBlue"=>"AFDCEC","LightBlue"=>"ADDFFF","RobinEggBlue"=>"BDEDFF","LightAquamarine"=>"93FFE8","ElectricBlue"=>"9AFEFF",
"Aquamarine"=>"7FFFD4","CyanorAqua"=>"00FFFF","TronBlue"=>"7DFDFE","BlueZircon"=>"57FEFF","BlueLagoon"=>"8EEBEC","Celeste"=>"50EBEC","BlueDiamond"=>"4EE2EC","TiffanyBlue"=>"81D8D0","CyanOpaque"=>"92C7C7","BlueHosta"=>"77BFC7","NorthernLightsBlue"=>"78C7C7","MediumTurquoise"=>"48CCCD","Turquoise"=>"43C6DB","Jellyfish"=>"46C7C7","Bluegreen"=>"7BCCB5","MacawBlueGreen"=>"43BFC7","LightSeaGreen"=>"3EA99F","DarkTurquoise"=>"3B9C9C","SeaTurtleGreen"=>"438D80","MediumAquamarine"=>"348781","GreenishBlue"=>"307D7E","GrayishTurquoise"=>"5E7D7E","BeetleGreen"=>"4C787E","Teal"=>"008080","SeaGreen"=>"4E8975","CamouflageGreen"=>"78866B","SageGreen"=>"848b79","HazelGreen"=>"617C58","VenomGreen"=>"728C00","FernGreen"=>"667C26","DarkForestGreen"=>"254117","MediumSeaGreen"=>"306754","MediumForestGreen"=>"347235","SeaweedGreen"=>"437C17","PineGreen"=>"387C44","JungleGreen"=>"347C2C","ShamrockGreen"=>"347C17","MediumSpringGreen"=>"348017","ForestGreen"=>"4E9258","GreenOnion"=>"6AA121","SpringGreen"=>"4AA02C","LimeGreen"=>"41A317","CloverGreen"=>"3EA055","GreenSnake"=>"6CBB3C","AlienGreen"=>"6CC417","GreenApple"=>"4CC417","YellowGreen"=>"52D017","KellyGreen"=>"4CC552","ZombieGreen"=>"54C571","FrogGreen"=>"99C68E","GreenPeas"=>"89C35C","DollarBillGreen"=>"85BB65","DarkSeaGreen"=>"8BB381","IguanaGreen"=>"9CB071","AvocadoGreen"=>"B2C248","PistachioGreen"=>"9DC209","SaladGreen"=>"A1C935","HummingbirdGreen"=>"7FE817","NebulaGreen"=>"59E817","StoplightGoGreen"=>"57E964","AlgaeGreen"=>"64E986","JadeGreen"=>"5EFB6E","Green"=>"00FF00","EmeraldGreen"=>"5FFB17","LawnGreen"=>"87F717","Chartreuse"=>"8AFB17","DragonGreen"=>"6AFB92","Mintgreen"=>"98FF98","GreenThumb"=>"B5EAAA","LightJade"=>"C3FDB8","TeaGreen"=>"CCFB5D","GreenYellow"=>"B1FB17","SlimeGreen"=>"BCE954","Goldenrod"=>"EDDA74",
"HarvestGold"=>"EDE275","SunYellow"=>"FFE87C","Mustard"=>"FFDB58","RubberDuckyYellow"=>"FFD801","BrightGold"=>"FDD017","Goldenbrown"=>"EAC117","MacaroniandCheese"=>"F2BB66","Saffron"=>"FBB917","Beer"=>"FBB117","Cantaloupe"=>"FFA62F","BeeYellow"=>"E9AB17","BrownSugar"=>"E2A76F","BurlyWood"=>"DEB887","DeepPeach"=>"FFCBA4","GingerBrown"=>"C9BE62","SchoolBusYellow"=>"E8A317","SandyBrown"=>"EE9A4D","FallLeafBrown"=>"C8B560","OrangeGold"=>"D4A017","Sand"=>"C2B280","CookieBrown"=>"C7A317","Caramel"=>"C68E17","Brass"=>"B5A642","Khaki"=>"ADA96E","Camelbrown"=>"C19A6B","Bronze"=>"CD7F32","TigerOrange"=>"C88141","Cinnamon"=>"C58917","BulletShell"=>"AF9B60","DarkGoldenrod"=>"AF7817","Copper"=>"B87333","Wood"=>"966F33","OakBrown"=>"806517","Moccasin"=>"827839","ArmyBrown"=>"827B60","Sandstone"=>"786D5F","Mocha"=>"493D26","Taupe"=>"483C32","Coffee"=>"6F4E37","BrownBear"=>"835C3B","RedDirt"=>"7F5217","Sepia"=>"7F462C","OrangeSalmon"=>"C47451","Rust"=>"C36241","RedFox"=>"C35817","Chocolate"=>"C85A17","Sedona"=>"CC6600","PapayaOrange"=>"E56717","HalloweenOrange"=>"E66C2C","PumpkinOrange"=>"F87217","ConstructionConeOrange"=>"F87431","SunriseOrange"=>"E67451","MangoOrange"=>"FF8040","DarkOrange"=>"F88017","Coral"=>"FF7F50","BasketBallOrange"=>"F88158","LightSalmon"=>"F9966B","Tangerine"=>"E78A61","DarkSalmon"=>"E18B6B","LightCoral"=>"E77471","BeanRed"=>"F75D59","ValentineRed"=>"E55451","ShockingOrange"=>"E55B3C","Red"=>"FF0000","Scarlet"=>"FF2400","RubyRed"=>"F62217","FerrariRed"=>"F70D1A","FireEngineRed"=>"F62817","LavaRed"=>"E42217","LoveRed"=>"E41B17","Grapefruit"=>"DC381F","ChestnutRed"=>"C34A2C","CherryRed"=>"C24641","Mahogany"=>"C04000","ChilliPepper"=>"C11B17","Cranberry"=>"9F000F","RedWine"=>"990012","Burgundy"=>"8C001A","Chestnut"=>"954535","BloodRed"=>"7E3517",
"Sienna"=>"8A4117","Sangria"=>"7E3817","Firebrick"=>"800517","Maroon"=>"810541","PlumPie"=>"7D0541","VelvetMaroon"=>"7E354D","PlumVelvet"=>"7D0552","RosyFinch"=>"7F4E52","Puce"=>"7F5A58","DullPurple"=>"7F525D","RosyBrown"=>"B38481","KhakiRose"=>"C5908E","PinkBow"=>"C48189","LipstickPink"=>"C48793","Rose"=>"E8ADAA","RoseGold"=>"ECC5C0","DesertSand"=>"EDC9AF","PigPink"=>"FDD7E4","CottonCandy"=>"FCDFFF","PinkBubblegum"=>"FFDFDD","MistyRose"=>"FBBBB9","Pink"=>"FAAFBE","LightPink"=>"FAAFBA","FlamingoPink"=>"F9A7B0","PinkRose"=>"E7A1B0","PinkDaisy"=>"E799A3","CadillacPink"=>"E38AAE","CarnationPink"=>"F778A1","BlushRed"=>"E56E94","HotPink"=>"F660AB","WatermelonPink"=>"FC6C85","VioletRed"=>"F6358A","DeepPink"=>"F52887","PinkCupcake"=>"E45E9D","PinkLemonade"=>"E4287C","NeonPink"=>"F535AA","Magenta"=>"FF00FF","DimorphothecaMagenta"=>"E3319D","BrightNeonPink"=>"F433FF","PaleVioletRed"=>"D16587","TulipPink"=>"C25A7C","MediumVioletRed"=>"CA226B","RoguePink"=>"C12869","BurntPink"=>"C12267","BashfulPink"=>"C25283","DarkCarnationPink"=>"C12283","Plum"=>"B93B8F","ViolaPurple"=>"7E587E","PurpleIris"=>"571B7E","PlumPurple"=>"583759","Indigo"=>"4B0082","PurpleMonster"=>"461B7E","PurpleHaze"=>"4E387E","Eggplant"=>"614051","Grape"=>"5E5A80","PurpleJam"=>"6A287E","DarkOrchid"=>"7D1B7E","PurpleFlower"=>"A74AC7","MediumOrchid"=>"B048B5","PurpleAmethyst"=>"6C2DC7","DarkViolet"=>"842DCE","Violet"=>"8D38C9","PurpleSageBush"=>"7A5DC7","LovelyPurple"=>"7F38EC","Purple"=>"8E35EF","AztechPurple"=>"893BFF","MediumPurple"=>"8467D7","JasminePurple"=>"A23BEC","PurpleDaffodil"=>"B041FF","TyrianPurple"=>"C45AEC","CrocusPurple"=>"9172EC","PurpleMimosa"=>"9E7BFF","HeliotropePurple"=>"D462FF","Crimson"=>"E238EC","PurpleDragon"=>"C38EC7","Lilac"=>"C8A2C8","BlushPink"=>"E6A9EC","Mauve"=>"E0B0FF","WisteriaPurple"=>"C6AEC7","BlossomPink"=>"F9B7FF","Thistle"=>"D2B9D3","Periwinkle"=>"E9CFEC","LavenderPinocchio"=>"EBDDE2","Lavenderblue"=>"E3E4FA"];
return ["air_force_blue_raf"=>["name"=>"AirForceBlue(Raf)","hex"=>"#5d8aa8","rgb"=>[93,138,168]],"air_force_blue_usaf"=>["name"=>"AirForceBlue(Usaf)","hex"=>"#00308f","rgb"=>[0,48,143]],"air_superiority_blue"=>["name"=>"AirSuperiorityBlue","hex"=>"#72a0c1","rgb"=>[114,160,193]],"alabama_crimson"=>["name"=>"AlabamaCrimson","hex"=>"#a32638","rgb"=>[163,38,56]],"alice_blue"=>["name"=>"AliceBlue","hex"=>"#f0f8ff","rgb"=>[240,248,255]],"alizarin_crimson"=>["name"=>"AlizarinCrimson","hex"=>"#e32636","rgb"=>[227,38,54]],"alloy_orange"=>["name"=>"AlloyOrange","hex"=>"#c46210","rgb"=>[196,98,16]],"almond"=>["name"=>"Almond","hex"=>"#efdecd","rgb"=>[239,222,205]],"amaranth"=>["name"=>"Amaranth","hex"=>"#e52b50","rgb"=>[229,43,80]],"amber"=>["name"=>"Amber","hex"=>"#ffbf00","rgb"=>[255,191,0]],"amber_sae_ece"=>["name"=>"Amber(Sae\/Ece)","hex"=>"#ff7e00","rgb"=>[255,126,0]],"american_rose"=>["name"=>"AmericanRose","hex"=>"#ff033e","rgb"=>[255,3,62]],"amethyst"=>["name"=>"Amethyst","hex"=>"#96c","rgb"=>[153,102,204]],"android_green"=>["name"=>"AndroidGreen","hex"=>"#a4c639","rgb"=>[164,198,57]],"anti_flash_white"=>["name"=>"Anti-FlashWhite","hex"=>"#f2f3f4","rgb"=>[242,243,244]],"antique_brass"=>["name"=>"AntiqueBrass","hex"=>"#cd9575","rgb"=>[205,149,117]],"antique_fuchsia"=>["name"=>"AntiqueFuchsia","hex"=>"#915c83","rgb"=>[145,92,131]],"antique_ruby"=>["name"=>"AntiqueRuby","hex"=>"#841b2d","rgb"=>[132,27,45]],"antique_white"=>["name"=>"AntiqueWhite","hex"=>"#faebd7","rgb"=>[250,235,215]],"ao_english"=>["name"=>"Ao(English)","hex"=>"#008000","rgb"=>[0,128,0]],"apple_green"=>["name"=>"AppleGreen","hex"=>"#8db600","rgb"=>[141,182,0]],"apricot"=>["name"=>"Apricot","hex"=>"#fbceb1","rgb"=>[251,206,177]],"aqua"=>["name"=>"Aqua","hex"=>"#0ff","rgb"=>[0,255,255]],
"aquamarine"=>["name"=>"Aquamarine","hex"=>"#7fffd4","rgb"=>[127,255,212]],"army_green"=>["name"=>"ArmyGreen","hex"=>"#4b5320","rgb"=>[75,83,32]],"arsenic"=>["name"=>"Arsenic","hex"=>"#3b444b","rgb"=>[59,68,75]],"arylide_yellow"=>["name"=>"ArylideYellow","hex"=>"#e9d66b","rgb"=>[233,214,107]],"ash_grey"=>["name"=>"AshGrey","hex"=>"#b2beb5","rgb"=>[178,190,181]],"asparagus"=>["name"=>"Asparagus","hex"=>"#87a96b","rgb"=>[135,169,107]],"atomic_tangerine"=>["name"=>"AtomicTangerine","hex"=>"#f96","rgb"=>[255,153,102]],"auburn"=>["name"=>"Auburn","hex"=>"#a52a2a","rgb"=>[165,42,42]],"aureolin"=>["name"=>"Aureolin","hex"=>"#fdee00","rgb"=>[253,238,0]],"aurometalsaurus"=>["name"=>"Aurometalsaurus","hex"=>"#6e7f80","rgb"=>[110,127,128]],"avocado"=>["name"=>"Avocado","hex"=>"#568203","rgb"=>[86,130,3]],"azure"=>["name"=>"Azure","hex"=>"#007fff","rgb"=>[0,127,255]],"azure_mist_web"=>["name"=>"AzureMist\/Web","hex"=>"#f0ffff","rgb"=>[240,255,255]],"baby_blue"=>["name"=>"BabyBlue","hex"=>"#89cff0","rgb"=>[137,207,240]],"baby_blue_eyes"=>["name"=>"BabyBlueEyes","hex"=>"#a1caf1","rgb"=>[161,202,241]],"baby_pink"=>["name"=>"BabyPink","hex"=>"#f4c2c2","rgb"=>[244,194,194]],"ball_blue"=>["name"=>"BallBlue","hex"=>"#21abcd","rgb"=>[33,171,205]],"banana_mania"=>["name"=>"BananaMania","hex"=>"#fae7b5","rgb"=>[250,231,181]],"banana_yellow"=>["name"=>"BananaYellow","hex"=>"#ffe135","rgb"=>[255,225,53]],"barn_red"=>["name"=>"BarnRed","hex"=>"#7c0a02","rgb"=>[124,10,2]],"battleship_grey"=>["name"=>"BattleshipGrey","hex"=>"#848482","rgb"=>[132,132,130]],"bazaar"=>["name"=>"Bazaar","hex"=>"#98777b","rgb"=>[152,119,123]],"beau_blue"=>["name"=>"BeauBlue","hex"=>"#bcd4e6","rgb"=>[188,212,230]],"beaver"=>["name"=>"Beaver","hex"=>"#9f8170","rgb"=>[159,129,112]],
"beige"=>["name"=>"Beige","hex"=>"#f5f5dc","rgb"=>[245,245,220]],"big_dip_o_ruby"=>["name"=>"BigDipO’Ruby","hex"=>"#9c2542","rgb"=>[156,37,66]],"bisque"=>["name"=>"Bisque","hex"=>"#ffe4c4","rgb"=>[255,228,196]],"bistre"=>["name"=>"Bistre","hex"=>"#3d2b1f","rgb"=>[61,43,31]],"bittersweet"=>["name"=>"Bittersweet","hex"=>"#fe6f5e","rgb"=>[254,111,94]],"bittersweet_shimmer"=>["name"=>"BittersweetShimmer","hex"=>"#bf4f51","rgb"=>[191,79,81]],"black"=>["name"=>"Black","hex"=>"#000","rgb"=>[0,0,0]],"black_bean"=>["name"=>"BlackBean","hex"=>"#3d0c02","rgb"=>[61,12,2]],"black_leather_jacket"=>["name"=>"BlackLeatherJacket","hex"=>"#253529","rgb"=>[37,53,41]],"black_olive"=>["name"=>"BlackOlive","hex"=>"#3b3c36","rgb"=>[59,60,54]],"blanched_almond"=>["name"=>"BlanchedAlmond","hex"=>"#ffebcd","rgb"=>[255,235,205]],"blast_off_bronze"=>["name"=>"Blast-OffBronze","hex"=>"#a57164","rgb"=>[165,113,100]],"bleu_de_france"=>["name"=>"BleuDeFrance","hex"=>"#318ce7","rgb"=>[49,140,231]],"blizzard_blue"=>["name"=>"BlizzardBlue","hex"=>"#ace5ee","rgb"=>[172,229,238]],"blond"=>["name"=>"Blond","hex"=>"#faf0be","rgb"=>[250,240,190]],"blue"=>["name"=>"Blue","hex"=>"#00f","rgb"=>[0,0,255]],"blue_bell"=>["name"=>"BlueBell","hex"=>"#a2a2d0","rgb"=>[162,162,208]],"blue_crayola"=>["name"=>"Blue(Crayola)","hex"=>"#1f75fe","rgb"=>[31,117,254]],"blue_gray"=>["name"=>"BlueGray","hex"=>"#69c","rgb"=>[102,153,204]],"blue_green"=>["name"=>"Blue-Green","hex"=>"#0d98ba","rgb"=>[13,152,186]],"blue_munsell"=>["name"=>"Blue(Munsell)","hex"=>"#0093af","rgb"=>[0,147,175]],"blue_ncs"=>["name"=>"Blue(Ncs)","hex"=>"#0087bd","rgb"=>[0,135,189]],"blue_pigment"=>["name"=>"Blue(Pigment)","hex"=>"#339","rgb"=>[51,51,153]],"blue_ryb"=>["name"=>"Blue(Ryb)","hex"=>"#0247fe","rgb"=>[2,71,254]],
"blue_sapphire"=>["name"=>"BlueSapphire","hex"=>"#126180","rgb"=>[18,97,128]],"blue_violet"=>["name"=>"Blue-Violet","hex"=>"#8a2be2","rgb"=>[138,43,226]],"blush"=>["name"=>"Blush","hex"=>"#de5d83","rgb"=>[222,93,131]],"bole"=>["name"=>"Bole","hex"=>"#79443b","rgb"=>[121,68,59]],"bondi_blue"=>["name"=>"BondiBlue","hex"=>"#0095b6","rgb"=>[0,149,182]],"bone"=>["name"=>"Bone","hex"=>"#e3dac9","rgb"=>[227,218,201]],"boston_university_red"=>["name"=>"BostonUniversityRed","hex"=>"#c00","rgb"=>[204,0,0]],"bottle_green"=>["name"=>"BottleGreen","hex"=>"#006a4e","rgb"=>[0,106,78]],"boysenberry"=>["name"=>"Boysenberry","hex"=>"#873260","rgb"=>[135,50,96]],"brandeis_blue"=>["name"=>"BrandeisBlue","hex"=>"#0070ff","rgb"=>[0,112,255]],"brass"=>["name"=>"Brass","hex"=>"#b5a642","rgb"=>[181,166,66]],"brick_red"=>["name"=>"BrickRed","hex"=>"#cb4154","rgb"=>[203,65,84]],"bright_cerulean"=>["name"=>"BrightCerulean","hex"=>"#1dacd6","rgb"=>[29,172,214]],"bright_green"=>["name"=>"BrightGreen","hex"=>"#6f0","rgb"=>[102,255,0]],"bright_lavender"=>["name"=>"BrightLavender","hex"=>"#bf94e4","rgb"=>[191,148,228]],"bright_maroon"=>["name"=>"BrightMaroon","hex"=>"#c32148","rgb"=>[195,33,72]],"bright_pink"=>["name"=>"BrightPink","hex"=>"#ff007f","rgb"=>[255,0,127]],"bright_turquoise"=>["name"=>"BrightTurquoise","hex"=>"#08e8de","rgb"=>[8,232,222]],"bright_ube"=>["name"=>"BrightUbe","hex"=>"#d19fe8","rgb"=>[209,159,232]],"brilliant_lavender"=>["name"=>"BrilliantLavender","hex"=>"#f4bbff","rgb"=>[244,187,255]],"brilliant_rose"=>["name"=>"BrilliantRose","hex"=>"#ff55a3","rgb"=>[255,85,163]],"brink_pink"=>["name"=>"BrinkPink","hex"=>"#fb607f","rgb"=>[251,96,127]],"british_racing_green"=>["name"=>"BritishRacingGreen","hex"=>"#004225","rgb"=>[0,66,37]],
"bronze"=>["name"=>"Bronze","hex"=>"#cd7f32","rgb"=>[205,127,50]],"brown_traditional"=>["name"=>"Brown(Traditional)","hex"=>"#964b00","rgb"=>[150,75,0]],"brown_web"=>["name"=>"Brown(Web)","hex"=>"#a52a2a","rgb"=>[165,42,42]],"bubble_gum"=>["name"=>"BubbleGum","hex"=>"#ffc1cc","rgb"=>[255,193,204]],"bubbles"=>["name"=>"Bubbles","hex"=>"#e7feff","rgb"=>[231,254,255]],"buff"=>["name"=>"Buff","hex"=>"#f0dc82","rgb"=>[240,220,130]],"bulgarian_rose"=>["name"=>"BulgarianRose","hex"=>"#480607","rgb"=>[72,6,7]],"burgundy"=>["name"=>"Burgundy","hex"=>"#800020","rgb"=>[128,0,32]],"burlywood"=>["name"=>"Burlywood","hex"=>"#deb887","rgb"=>[222,184,135]],"burnt_orange"=>["name"=>"BurntOrange","hex"=>"#c50","rgb"=>[204,85,0]],"burnt_sienna"=>["name"=>"BurntSienna","hex"=>"#e97451","rgb"=>[233,116,81]],"burnt_umber"=>["name"=>"BurntUmber","hex"=>"#8a3324","rgb"=>[138,51,36]],"byzantine"=>["name"=>"Byzantine","hex"=>"#bd33a4","rgb"=>[189,51,164]],"byzantium"=>["name"=>"Byzantium","hex"=>"#702963","rgb"=>[112,41,99]],"cadet"=>["name"=>"Cadet","hex"=>"#536872","rgb"=>[83,104,114]],"cadet_blue"=>["name"=>"CadetBlue","hex"=>"#5f9ea0","rgb"=>[95,158,160]],"cadet_grey"=>["name"=>"CadetGrey","hex"=>"#91a3b0","rgb"=>[145,163,176]],"cadmium_green"=>["name"=>"CadmiumGreen","hex"=>"#006b3c","rgb"=>[0,107,60]],"cadmium_orange"=>["name"=>"CadmiumOrange","hex"=>"#ed872d","rgb"=>[237,135,45]],"cadmium_red"=>["name"=>"CadmiumRed","hex"=>"#e30022","rgb"=>[227,0,34]],"cadmium_yellow"=>["name"=>"CadmiumYellow","hex"=>"#fff600","rgb"=>[255,246,0]],"caf_au_lait"=>["name"=>"CaféAuLait","hex"=>"#a67b5b","rgb"=>[166,123,91]],"caf_noir"=>["name"=>"CaféNoir","hex"=>"#4b3621","rgb"=>[75,54,33]],"cal_poly_green"=>["name"=>"CalPolyGreen","hex"=>"#1e4d2b","rgb"=>[30,77,43]],
"cambridge_blue"=>["name"=>"CambridgeBlue","hex"=>"#a3c1ad","rgb"=>[163,193,173]],"camel"=>["name"=>"Camel","hex"=>"#c19a6b","rgb"=>[193,154,107]],"cameo_pink"=>["name"=>"CameoPink","hex"=>"#efbbcc","rgb"=>[239,187,204]],"camouflage_green"=>["name"=>"CamouflageGreen","hex"=>"#78866b","rgb"=>[120,134,107]],"canary_yellow"=>["name"=>"CanaryYellow","hex"=>"#ffef00","rgb"=>[255,239,0]],"candy_apple_red"=>["name"=>"CandyAppleRed","hex"=>"#ff0800","rgb"=>[255,8,0]],"candy_pink"=>["name"=>"CandyPink","hex"=>"#e4717a","rgb"=>[228,113,122]],"capri"=>["name"=>"Capri","hex"=>"#00bfff","rgb"=>[0,191,255]],"caput_mortuum"=>["name"=>"CaputMortuum","hex"=>"#592720","rgb"=>[89,39,32]],"cardinal"=>["name"=>"Cardinal","hex"=>"#c41e3a","rgb"=>[196,30,58]],"caribbean_green"=>["name"=>"CaribbeanGreen","hex"=>"#0c9","rgb"=>[0,204,153]],"carmine"=>["name"=>"Carmine","hex"=>"#960018","rgb"=>[150,0,24]],"carmine_m_p"=>["name"=>"Carmine(M&P)","hex"=>"#d70040","rgb"=>[215,0,64]],"carmine_pink"=>["name"=>"CarminePink","hex"=>"#eb4c42","rgb"=>[235,76,66]],"carmine_red"=>["name"=>"CarmineRed","hex"=>"#ff0038","rgb"=>[255,0,56]],"carnation_pink"=>["name"=>"CarnationPink","hex"=>"#ffa6c9","rgb"=>[255,166,201]],"carnelian"=>["name"=>"Carnelian","hex"=>"#b31b1b","rgb"=>[179,27,27]],"carolina_blue"=>["name"=>"CarolinaBlue","hex"=>"#99badd","rgb"=>[153,186,221]],"carrot_orange"=>["name"=>"CarrotOrange","hex"=>"#ed9121","rgb"=>[237,145,33]],"catalina_blue"=>["name"=>"CatalinaBlue","hex"=>"#062a78","rgb"=>[6,42,120]],"ceil"=>["name"=>"Ceil","hex"=>"#92a1cf","rgb"=>[146,161,207]],"celadon"=>["name"=>"Celadon","hex"=>"#ace1af","rgb"=>[172,225,175]],"celadon_blue"=>["name"=>"CeladonBlue","hex"=>"#007ba7","rgb"=>[0,123,167]],
"celadon_green"=>["name"=>"CeladonGreen","hex"=>"#2f847c","rgb"=>[47,132,124]],"celeste_colour"=>["name"=>"Celeste(Colour)","hex"=>"#b2ffff","rgb"=>[178,255,255]],"celestial_blue"=>["name"=>"CelestialBlue","hex"=>"#4997d0","rgb"=>[73,151,208]],"cerise"=>["name"=>"Cerise","hex"=>"#de3163","rgb"=>[222,49,99]],"cerise_pink"=>["name"=>"CerisePink","hex"=>"#ec3b83","rgb"=>[236,59,131]],"cerulean"=>["name"=>"Cerulean","hex"=>"#007ba7","rgb"=>[0,123,167]],"cerulean_blue"=>["name"=>"CeruleanBlue","hex"=>"#2a52be","rgb"=>[42,82,190]],"cerulean_frost"=>["name"=>"CeruleanFrost","hex"=>"#6d9bc3","rgb"=>[109,155,195]],"cg_blue"=>["name"=>"CgBlue","hex"=>"#007aa5","rgb"=>[0,122,165]],"cg_red"=>["name"=>"CgRed","hex"=>"#e03c31","rgb"=>[224,60,49]],"chamoisee"=>["name"=>"Chamoisee","hex"=>"#a0785a","rgb"=>[160,120,90]],"champagne"=>["name"=>"Champagne","hex"=>"#fad6a5","rgb"=>[250,214,165]],"charcoal"=>["name"=>"Charcoal","hex"=>"#36454f","rgb"=>[54,69,79]],"charm_pink"=>["name"=>"CharmPink","hex"=>"#e68fac","rgb"=>[230,143,172]],"chartreuse_traditional"=>["name"=>"Chartreuse(Traditional)","hex"=>"#dfff00","rgb"=>[223,255,0]],"chartreuse_web"=>["name"=>"Chartreuse(Web)","hex"=>"#7fff00","rgb"=>[127,255,0]],"cherry"=>["name"=>"Cherry","hex"=>"#de3163","rgb"=>[222,49,99]],"cherry_blossom_pink"=>["name"=>"CherryBlossomPink","hex"=>"#ffb7c5","rgb"=>[255,183,197]],"chestnut"=>["name"=>"Chestnut","hex"=>"#cd5c5c","rgb"=>[205,92,92]],"china_pink"=>["name"=>"ChinaPink","hex"=>"#de6fa1","rgb"=>[222,111,161]],"china_rose"=>["name"=>"ChinaRose","hex"=>"#a8516e","rgb"=>[168,81,110]],"chinese_red"=>["name"=>"ChineseRed","hex"=>"#aa381e","rgb"=>[170,56,30]],"chocolate_traditional"=>["name"=>"Chocolate(Traditional)","hex"=>"#7b3f00","rgb"=>[123,63,0]],
"chocolate_web"=>["name"=>"Chocolate(Web)","hex"=>"#d2691e","rgb"=>[210,105,30]],"chrome_yellow"=>["name"=>"ChromeYellow","hex"=>"#ffa700","rgb"=>[255,167,0]],"cinereous"=>["name"=>"Cinereous","hex"=>"#98817b","rgb"=>[152,129,123]],"cinnabar"=>["name"=>"Cinnabar","hex"=>"#e34234","rgb"=>[227,66,52]],"cinnamon"=>["name"=>"Cinnamon","hex"=>"#d2691e","rgb"=>[210,105,30]],"citrine"=>["name"=>"Citrine","hex"=>"#e4d00a","rgb"=>[228,208,10]],"classic_rose"=>["name"=>"ClassicRose","hex"=>"#fbcce7","rgb"=>[251,204,231]],"cobalt"=>["name"=>"Cobalt","hex"=>"#0047ab","rgb"=>[0,71,171]],"cocoa_brown"=>["name"=>"CocoaBrown","hex"=>"#d2691e","rgb"=>[210,105,30]],"coffee"=>["name"=>"Coffee","hex"=>"#6f4e37","rgb"=>[111,78,55]],"columbia_blue"=>["name"=>"ColumbiaBlue","hex"=>"#9bddff","rgb"=>[155,221,255]],"congo_pink"=>["name"=>"CongoPink","hex"=>"#f88379","rgb"=>[248,131,121]],"cool_black"=>["name"=>"CoolBlack","hex"=>"#002e63","rgb"=>[0,46,99]],"cool_grey"=>["name"=>"CoolGrey","hex"=>"#8c92ac","rgb"=>[140,146,172]],"copper"=>["name"=>"Copper","hex"=>"#b87333","rgb"=>[184,115,51]],"copper_crayola"=>["name"=>"Copper(Crayola)","hex"=>"#da8a67","rgb"=>[218,138,103]],"copper_penny"=>["name"=>"CopperPenny","hex"=>"#ad6f69","rgb"=>[173,111,105]],"copper_red"=>["name"=>"CopperRed","hex"=>"#cb6d51","rgb"=>[203,109,81]],"copper_rose"=>["name"=>"CopperRose","hex"=>"#966","rgb"=>[153,102,102]],"coquelicot"=>["name"=>"Coquelicot","hex"=>"#ff3800","rgb"=>[255,56,0]],"coral"=>["name"=>"Coral","hex"=>"#ff7f50","rgb"=>[255,127,80]],"coral_pink"=>["name"=>"CoralPink","hex"=>"#f88379","rgb"=>[248,131,121]],"coral_red"=>["name"=>"CoralRed","hex"=>"#ff4040","rgb"=>[255,64,64]],"cordovan"=>["name"=>"Cordovan","hex"=>"#893f45","rgb"=>[137,63,69]],
"corn"=>["name"=>"Corn","hex"=>"#fbec5d","rgb"=>[251,236,93]],"cornell_red"=>["name"=>"CornellRed","hex"=>"#b31b1b","rgb"=>[179,27,27]],"cornflower_blue"=>["name"=>"CornflowerBlue","hex"=>"#6495ed","rgb"=>[100,149,237]],"cornsilk"=>["name"=>"Cornsilk","hex"=>"#fff8dc","rgb"=>[255,248,220]],"cosmic_latte"=>["name"=>"CosmicLatte","hex"=>"#fff8e7","rgb"=>[255,248,231]],"cotton_candy"=>["name"=>"CottonCandy","hex"=>"#ffbcd9","rgb"=>[255,188,217]],"cream"=>["name"=>"Cream","hex"=>"#fffdd0","rgb"=>[255,253,208]],"crimson"=>["name"=>"Crimson","hex"=>"#dc143c","rgb"=>[220,20,60]],"crimson_glory"=>["name"=>"CrimsonGlory","hex"=>"#be0032","rgb"=>[190,0,50]],"cyan"=>["name"=>"Cyan","hex"=>"#0ff","rgb"=>[0,255,255]],"cyan_process"=>["name"=>"Cyan(Process)","hex"=>"#00b7eb","rgb"=>[0,183,235]],"daffodil"=>["name"=>"Daffodil","hex"=>"#ffff31","rgb"=>[255,255,49]],"dandelion"=>["name"=>"Dandelion","hex"=>"#f0e130","rgb"=>[240,225,48]],"dark_blue"=>["name"=>"DarkBlue","hex"=>"#00008b","rgb"=>[0,0,139]],"dark_brown"=>["name"=>"DarkBrown","hex"=>"#654321","rgb"=>[101,67,33]],"dark_byzantium"=>["name"=>"DarkByzantium","hex"=>"#5d3954","rgb"=>[93,57,84]],"dark_candy_apple_red"=>["name"=>"DarkCandyAppleRed","hex"=>"#a40000","rgb"=>[164,0,0]],"dark_cerulean"=>["name"=>"DarkCerulean","hex"=>"#08457e","rgb"=>[8,69,126]],"dark_chestnut"=>["name"=>"DarkChestnut","hex"=>"#986960","rgb"=>[152,105,96]],"dark_coral"=>["name"=>"DarkCoral","hex"=>"#cd5b45","rgb"=>[205,91,69]],"dark_cyan"=>["name"=>"DarkCyan","hex"=>"#008b8b","rgb"=>[0,139,139]],"dark_electric_blue"=>["name"=>"DarkElectricBlue","hex"=>"#536878","rgb"=>[83,104,120]],"dark_goldenrod"=>["name"=>"DarkGoldenrod","hex"=>"#b8860b","rgb"=>[184,134,11]],
"dark_gray"=>["name"=>"DarkGray","hex"=>"#a9a9a9","rgb"=>[169,169,169]],"dark_green"=>["name"=>"DarkGreen","hex"=>"#013220","rgb"=>[1,50,32]],"dark_imperial_blue"=>["name"=>"DarkImperialBlue","hex"=>"#00416a","rgb"=>[0,65,106]],"dark_jungle_green"=>["name"=>"DarkJungleGreen","hex"=>"#1a2421","rgb"=>[26,36,33]],"dark_khaki"=>["name"=>"DarkKhaki","hex"=>"#bdb76b","rgb"=>[189,183,107]],"dark_lava"=>["name"=>"DarkLava","hex"=>"#483c32","rgb"=>[72,60,50]],"dark_lavender"=>["name"=>"DarkLavender","hex"=>"#734f96","rgb"=>[115,79,150]],"dark_magenta"=>["name"=>"DarkMagenta","hex"=>"#8b008b","rgb"=>[139,0,139]],"dark_midnight_blue"=>["name"=>"DarkMidnightBlue","hex"=>"#036","rgb"=>[0,51,102]],"dark_olive_green"=>["name"=>"DarkOliveGreen","hex"=>"#556b2f","rgb"=>[85,107,47]],"dark_orange"=>["name"=>"DarkOrange","hex"=>"#ff8c00","rgb"=>[255,140,0]],"dark_orchid"=>["name"=>"DarkOrchid","hex"=>"#9932cc","rgb"=>[153,50,204]],"dark_pastel_blue"=>["name"=>"DarkPastelBlue","hex"=>"#779ecb","rgb"=>[119,158,203]],"dark_pastel_green"=>["name"=>"DarkPastelGreen","hex"=>"#03c03c","rgb"=>[3,192,60]],"dark_pastel_purple"=>["name"=>"DarkPastelPurple","hex"=>"#966fd6","rgb"=>[150,111,214]],"dark_pastel_red"=>["name"=>"DarkPastelRed","hex"=>"#c23b22","rgb"=>[194,59,34]],"dark_pink"=>["name"=>"DarkPink","hex"=>"#e75480","rgb"=>[231,84,128]],"dark_powder_blue"=>["name"=>"DarkPowderBlue","hex"=>"#039","rgb"=>[0,51,153]],"dark_raspberry"=>["name"=>"DarkRaspberry","hex"=>"#872657","rgb"=>[135,38,87]],"dark_red"=>["name"=>"DarkRed","hex"=>"#8b0000","rgb"=>[139,0,0]],"dark_salmon"=>["name"=>"DarkSalmon","hex"=>"#e9967a","rgb"=>[233,150,122]],"dark_scarlet"=>["name"=>"DarkScarlet","hex"=>"#560319","rgb"=>[86,3,25]],
"dark_sea_green"=>["name"=>"DarkSeaGreen","hex"=>"#8fbc8f","rgb"=>[143,188,143]],"dark_sienna"=>["name"=>"DarkSienna","hex"=>"#3c1414","rgb"=>[60,20,20]],"dark_slate_blue"=>["name"=>"DarkSlateBlue","hex"=>"#483d8b","rgb"=>[72,61,139]],"dark_slate_gray"=>["name"=>"DarkSlateGray","hex"=>"#2f4f4f","rgb"=>[47,79,79]],"dark_spring_green"=>["name"=>"DarkSpringGreen","hex"=>"#177245","rgb"=>[23,114,69]],"dark_tan"=>["name"=>"DarkTan","hex"=>"#918151","rgb"=>[145,129,81]],"dark_tangerine"=>["name"=>"DarkTangerine","hex"=>"#ffa812","rgb"=>[255,168,18]],"dark_taupe"=>["name"=>"DarkTaupe","hex"=>"#483c32","rgb"=>[72,60,50]],"dark_terra_cotta"=>["name"=>"DarkTerraCotta","hex"=>"#cc4e5c","rgb"=>[204,78,92]],"dark_turquoise"=>["name"=>"DarkTurquoise","hex"=>"#00ced1","rgb"=>[0,206,209]],"dark_violet"=>["name"=>"DarkViolet","hex"=>"#9400d3","rgb"=>[148,0,211]],"dark_yellow"=>["name"=>"DarkYellow","hex"=>"#9b870c","rgb"=>[155,135,12]],"dartmouth_green"=>["name"=>"DartmouthGreen","hex"=>"#00703c","rgb"=>[0,112,60]],"davy_s_grey"=>["name"=>"Davy'SGrey","hex"=>"#555","rgb"=>[85,85,85]],"debian_red"=>["name"=>"DebianRed","hex"=>"#d70a53","rgb"=>[215,10,83]],"deep_carmine"=>["name"=>"DeepCarmine","hex"=>"#a9203e","rgb"=>[169,32,62]],"deep_carmine_pink"=>["name"=>"DeepCarminePink","hex"=>"#ef3038","rgb"=>[239,48,56]],"deep_carrot_orange"=>["name"=>"DeepCarrotOrange","hex"=>"#e9692c","rgb"=>[233,105,44]],"deep_cerise"=>["name"=>"DeepCerise","hex"=>"#da3287","rgb"=>[218,50,135]],"deep_champagne"=>["name"=>"DeepChampagne","hex"=>"#fad6a5","rgb"=>[250,214,165]],"deep_chestnut"=>["name"=>"DeepChestnut","hex"=>"#b94e48","rgb"=>[185,78,72]],"deep_coffee"=>["name"=>"DeepCoffee","hex"=>"#704241","rgb"=>[112,66,65]],
"deep_fuchsia"=>["name"=>"DeepFuchsia","hex"=>"#c154c1","rgb"=>[193,84,193]],"deep_jungle_green"=>["name"=>"DeepJungleGreen","hex"=>"#004b49","rgb"=>[0,75,73]],"deep_lilac"=>["name"=>"DeepLilac","hex"=>"#95b","rgb"=>[153,85,187]],"deep_magenta"=>["name"=>"DeepMagenta","hex"=>"#c0c","rgb"=>[204,0,204]],"deep_peach"=>["name"=>"DeepPeach","hex"=>"#ffcba4","rgb"=>[255,203,164]],"deep_pink"=>["name"=>"DeepPink","hex"=>"#ff1493","rgb"=>[255,20,147]],"deep_ruby"=>["name"=>"DeepRuby","hex"=>"#843f5b","rgb"=>[132,63,91]],"deep_saffron"=>["name"=>"DeepSaffron","hex"=>"#f93","rgb"=>[255,153,51]],"deep_sky_blue"=>["name"=>"DeepSkyBlue","hex"=>"#00bfff","rgb"=>[0,191,255]],"deep_tuscan_red"=>["name"=>"DeepTuscanRed","hex"=>"#66424d","rgb"=>[102,66,77]],"denim"=>["name"=>"Denim","hex"=>"#1560bd","rgb"=>[21,96,189]],"desert"=>["name"=>"Desert","hex"=>"#c19a6b","rgb"=>[193,154,107]],"desert_sand"=>["name"=>"DesertSand","hex"=>"#edc9af","rgb"=>[237,201,175]],"dim_gray"=>["name"=>"DimGray","hex"=>"#696969","rgb"=>[105,105,105]],"dodger_blue"=>["name"=>"DodgerBlue","hex"=>"#1e90ff","rgb"=>[30,144,255]],"dogwood_rose"=>["name"=>"DogwoodRose","hex"=>"#d71868","rgb"=>[215,24,104]],"dollar_bill"=>["name"=>"DollarBill","hex"=>"#85bb65","rgb"=>[133,187,101]],"drab"=>["name"=>"Drab","hex"=>"#967117","rgb"=>[150,113,23]],"duke_blue"=>["name"=>"DukeBlue","hex"=>"#00009c","rgb"=>[0,0,156]],"earth_yellow"=>["name"=>"EarthYellow","hex"=>"#e1a95f","rgb"=>[225,169,95]],"ebony"=>["name"=>"Ebony","hex"=>"#555d50","rgb"=>[85,93,80]],"ecru"=>["name"=>"Ecru","hex"=>"#c2b280","rgb"=>[194,178,128]],"eggplant"=>["name"=>"Eggplant","hex"=>"#614051","rgb"=>[97,64,81]],"eggshell"=>["name"=>"Eggshell","hex"=>"#f0ead6","rgb"=>[240,234,214]],
"egyptian_blue"=>["name"=>"EgyptianBlue","hex"=>"#1034a6","rgb"=>[16,52,166]],"electric_blue"=>["name"=>"ElectricBlue","hex"=>"#7df9ff","rgb"=>[125,249,255]],"electric_crimson"=>["name"=>"ElectricCrimson","hex"=>"#ff003f","rgb"=>[255,0,63]],"electric_cyan"=>["name"=>"ElectricCyan","hex"=>"#0ff","rgb"=>[0,255,255]],"electric_green"=>["name"=>"ElectricGreen","hex"=>"#0f0","rgb"=>[0,255,0]],"electric_indigo"=>["name"=>"ElectricIndigo","hex"=>"#6f00ff","rgb"=>[111,0,255]],"electric_lavender"=>["name"=>"ElectricLavender","hex"=>"#f4bbff","rgb"=>[244,187,255]],"electric_lime"=>["name"=>"ElectricLime","hex"=>"#cf0","rgb"=>[204,255,0]],"electric_purple"=>["name"=>"ElectricPurple","hex"=>"#bf00ff","rgb"=>[191,0,255]],"electric_ultramarine"=>["name"=>"ElectricUltramarine","hex"=>"#3f00ff","rgb"=>[63,0,255]],"electric_violet"=>["name"=>"ElectricViolet","hex"=>"#8f00ff","rgb"=>[143,0,255]],"electric_yellow"=>["name"=>"ElectricYellow","hex"=>"#ff0","rgb"=>[255,255,0]],"emerald"=>["name"=>"Emerald","hex"=>"#50c878","rgb"=>[80,200,120]],"english_lavender"=>["name"=>"EnglishLavender","hex"=>"#b48395","rgb"=>[180,131,149]],"eton_blue"=>["name"=>"EtonBlue","hex"=>"#96c8a2","rgb"=>[150,200,162]],"fallow"=>["name"=>"Fallow","hex"=>"#c19a6b","rgb"=>[193,154,107]],"falu_red"=>["name"=>"FaluRed","hex"=>"#801818","rgb"=>[128,24,24]],"fandango"=>["name"=>"Fandango","hex"=>"#b53389","rgb"=>[181,51,137]],"fashion_fuchsia"=>["name"=>"FashionFuchsia","hex"=>"#f400a1","rgb"=>[244,0,161]],"fawn"=>["name"=>"Fawn","hex"=>"#e5aa70","rgb"=>[229,170,112]],"feldgrau"=>["name"=>"Feldgrau","hex"=>"#4d5d53","rgb"=>[77,93,83]],"fern_green"=>["name"=>"FernGreen","hex"=>"#4f7942","rgb"=>[79,121,66]],
"ferrari_red"=>["name"=>"FerrariRed","hex"=>"#ff2800","rgb"=>[255,40,0]],"field_drab"=>["name"=>"FieldDrab","hex"=>"#6c541e","rgb"=>[108,84,30]],"fire_engine_red"=>["name"=>"FireEngineRed","hex"=>"#ce2029","rgb"=>[206,32,41]],"firebrick"=>["name"=>"Firebrick","hex"=>"#b22222","rgb"=>[178,34,34]],"flame"=>["name"=>"Flame","hex"=>"#e25822","rgb"=>[226,88,34]],"flamingo_pink"=>["name"=>"FlamingoPink","hex"=>"#fc8eac","rgb"=>[252,142,172]],"flavescent"=>["name"=>"Flavescent","hex"=>"#f7e98e","rgb"=>[247,233,142]],"flax"=>["name"=>"Flax","hex"=>"#eedc82","rgb"=>[238,220,130]],"floral_white"=>["name"=>"FloralWhite","hex"=>"#fffaf0","rgb"=>[255,250,240]],"fluorescent_orange"=>["name"=>"FluorescentOrange","hex"=>"#ffbf00","rgb"=>[255,191,0]],"fluorescent_pink"=>["name"=>"FluorescentPink","hex"=>"#ff1493","rgb"=>[255,20,147]],"fluorescent_yellow"=>["name"=>"FluorescentYellow","hex"=>"#cf0","rgb"=>[204,255,0]],"folly"=>["name"=>"Folly","hex"=>"#ff004f","rgb"=>[255,0,79]],"forest_green_traditional"=>["name"=>"ForestGreen(Traditional)","hex"=>"#014421","rgb"=>[1,68,33]],"forest_green_web"=>["name"=>"ForestGreen(Web)","hex"=>"#228b22","rgb"=>[34,139,34]],"french_beige"=>["name"=>"FrenchBeige","hex"=>"#a67b5b","rgb"=>[166,123,91]],"french_blue"=>["name"=>"FrenchBlue","hex"=>"#0072bb","rgb"=>[0,114,187]],"french_lilac"=>["name"=>"FrenchLilac","hex"=>"#86608e","rgb"=>[134,96,142]],"french_lime"=>["name"=>"FrenchLime","hex"=>"#cf0","rgb"=>[204,255,0]],"french_raspberry"=>["name"=>"FrenchRaspberry","hex"=>"#c72c48","rgb"=>[199,44,72]],"french_rose"=>["name"=>"FrenchRose","hex"=>"#f64a8a","rgb"=>[246,74,138]],"fuchsia"=>["name"=>"Fuchsia","hex"=>"#f0f","rgb"=>[255,0,255]],
"fuchsia_crayola"=>["name"=>"Fuchsia(Crayola)","hex"=>"#c154c1","rgb"=>[193,84,193]],"fuchsia_pink"=>["name"=>"FuchsiaPink","hex"=>"#f7f","rgb"=>[255,119,255]],"fuchsia_rose"=>["name"=>"FuchsiaRose","hex"=>"#c74375","rgb"=>[199,67,117]],"fulvous"=>["name"=>"Fulvous","hex"=>"#e48400","rgb"=>[228,132,0]],"fuzzy_wuzzy"=>["name"=>"FuzzyWuzzy","hex"=>"#c66","rgb"=>[204,102,102]],"gainsboro"=>["name"=>"Gainsboro","hex"=>"#dcdcdc","rgb"=>[220,220,220]],"gamboge"=>["name"=>"Gamboge","hex"=>"#e49b0f","rgb"=>[228,155,15]],"ghost_white"=>["name"=>"GhostWhite","hex"=>"#f8f8ff","rgb"=>[248,248,255]],"ginger"=>["name"=>"Ginger","hex"=>"#b06500","rgb"=>[176,101,0]],"glaucous"=>["name"=>"Glaucous","hex"=>"#6082b6","rgb"=>[96,130,182]],"glitter"=>["name"=>"Glitter","hex"=>"#e6e8fa","rgb"=>[230,232,250]],"gold_metallic"=>["name"=>"Gold(Metallic)","hex"=>"#d4af37","rgb"=>[212,175,55]],"gold_web_golden"=>["name"=>"Gold(Web)(Golden)","hex"=>"#ffd700","rgb"=>[255,215,0]],"golden_brown"=>["name"=>"GoldenBrown","hex"=>"#996515","rgb"=>[153,101,21]],"golden_poppy"=>["name"=>"GoldenPoppy","hex"=>"#fcc200","rgb"=>[252,194,0]],"golden_yellow"=>["name"=>"GoldenYellow","hex"=>"#ffdf00","rgb"=>[255,223,0]],"goldenrod"=>["name"=>"Goldenrod","hex"=>"#daa520","rgb"=>[218,165,32]],"granny_smith_apple"=>["name"=>"GrannySmithApple","hex"=>"#a8e4a0","rgb"=>[168,228,160]],"gray"=>["name"=>"Gray","hex"=>"#808080","rgb"=>[128,128,128]],"gray_asparagus"=>["name"=>"Gray-Asparagus","hex"=>"#465945","rgb"=>[70,89,69]],"gray_html_css_gray"=>["name"=>"Gray(Html\/CssGray)","hex"=>"#808080","rgb"=>[128,128,128]],"gray_x11_gray"=>["name"=>"Gray(X11Gray)","hex"=>"#bebebe","rgb"=>[190,190,190]],
"green_color_wheel_x11_green"=>["name"=>"Green(ColorWheel)(X11Green)","hex"=>"#0f0","rgb"=>[0,255,0]],"green_crayola"=>["name"=>"Green(Crayola)","hex"=>"#1cac78","rgb"=>[28,172,120]],"green_html_css_green"=>["name"=>"Green(Html\/CssGreen)","hex"=>"#008000","rgb"=>[0,128,0]],"green_munsell"=>["name"=>"Green(Munsell)","hex"=>"#00a877","rgb"=>[0,168,119]],"green_ncs"=>["name"=>"Green(Ncs)","hex"=>"#009f6b","rgb"=>[0,159,107]],"green_pigment"=>["name"=>"Green(Pigment)","hex"=>"#00a550","rgb"=>[0,165,80]],"green_ryb"=>["name"=>"Green(Ryb)","hex"=>"#66b032","rgb"=>[102,176,50]],"green_yellow"=>["name"=>"Green-Yellow","hex"=>"#adff2f","rgb"=>[173,255,47]],"grullo"=>["name"=>"Grullo","hex"=>"#a99a86","rgb"=>[169,154,134]],"guppie_green"=>["name"=>"GuppieGreen","hex"=>"#00ff7f","rgb"=>[0,255,127]],"halay_be"=>["name"=>"HalayàúBe","hex"=>"#663854","rgb"=>[102,56,84]],"han_blue"=>["name"=>"HanBlue","hex"=>"#446ccf","rgb"=>[68,108,207]],"han_purple"=>["name"=>"HanPurple","hex"=>"#5218fa","rgb"=>[82,24,250]],"hansa_yellow"=>["name"=>"HansaYellow","hex"=>"#e9d66b","rgb"=>[233,214,107]],"harlequin"=>["name"=>"Harlequin","hex"=>"#3fff00","rgb"=>[63,255,0]],"harvard_crimson"=>["name"=>"HarvardCrimson","hex"=>"#c90016","rgb"=>[201,0,22]],"harvest_gold"=>["name"=>"HarvestGold","hex"=>"#da9100","rgb"=>[218,145,0]],"heart_gold"=>["name"=>"HeartGold","hex"=>"#808000","rgb"=>[128,128,0]],"heliotrope"=>["name"=>"Heliotrope","hex"=>"#df73ff","rgb"=>[223,115,255]],"hollywood_cerise"=>["name"=>"HollywoodCerise","hex"=>"#f400a1","rgb"=>[244,0,161]],"honeydew"=>["name"=>"Honeydew","hex"=>"#f0fff0","rgb"=>[240,255,240]],"honolulu_blue"=>["name"=>"HonoluluBlue","hex"=>"#007fbf","rgb"=>[0,127,191]],
"hooker_s_green"=>["name"=>"Hooker'SGreen","hex"=>"#49796b","rgb"=>[73,121,107]],"hot_magenta"=>["name"=>"HotMagenta","hex"=>"#ff1dce","rgb"=>[255,29,206]],"hot_pink"=>["name"=>"HotPink","hex"=>"#ff69b4","rgb"=>[255,105,180]],"hunter_green"=>["name"=>"HunterGreen","hex"=>"#355e3b","rgb"=>[53,94,59]],"iceberg"=>["name"=>"Iceberg","hex"=>"#71a6d2","rgb"=>[113,166,210]],"icterine"=>["name"=>"Icterine","hex"=>"#fcf75e","rgb"=>[252,247,94]],"imperial_blue"=>["name"=>"ImperialBlue","hex"=>"#002395","rgb"=>[0,35,149]],"inchworm"=>["name"=>"Inchworm","hex"=>"#b2ec5d","rgb"=>[178,236,93]],"india_green"=>["name"=>"IndiaGreen","hex"=>"#138808","rgb"=>[19,136,8]],"indian_red"=>["name"=>"IndianRed","hex"=>"#cd5c5c","rgb"=>[205,92,92]],"indian_yellow"=>["name"=>"IndianYellow","hex"=>"#e3a857","rgb"=>[227,168,87]],"indigo"=>["name"=>"Indigo","hex"=>"#6f00ff","rgb"=>[111,0,255]],"indigo_dye"=>["name"=>"Indigo(Dye)","hex"=>"#00416a","rgb"=>[0,65,106]],"indigo_web"=>["name"=>"Indigo(Web)","hex"=>"#4b0082","rgb"=>[75,0,130]],"international_klein_blue"=>["name"=>"InternationalKleinBlue","hex"=>"#002fa7","rgb"=>[0,47,167]],"international_orange_aerospace"=>["name"=>"InternationalOrange(Aerospace)","hex"=>"#ff4f00","rgb"=>[255,79,0]],"international_orange_engineering"=>["name"=>"InternationalOrange(Engineering)","hex"=>"#ba160c","rgb"=>[186,22,12]],"international_orange_golden_gate_bridge"=>["name"=>"InternationalOrange(GoldenGateBridge)","hex"=>"#c0362c","rgb"=>[192,54,44]],"iris"=>["name"=>"Iris","hex"=>"#5a4fcf","rgb"=>[90,79,207]],"isabelline"=>["name"=>"Isabelline","hex"=>"#f4f0ec","rgb"=>[244,240,236]],"islamic_green"=>["name"=>"IslamicGreen","hex"=>"#009000","rgb"=>[0,144,0]],"ivory"=>["name"=>"Ivory","hex"=>"#fffff0","rgb"=>[255,255,240]],
"jade"=>["name"=>"Jade","hex"=>"#00a86b","rgb"=>[0,168,107]],"jasmine"=>["name"=>"Jasmine","hex"=>"#f8de7e","rgb"=>[248,222,126]],"jasper"=>["name"=>"Jasper","hex"=>"#d73b3e","rgb"=>[215,59,62]],"jazzberry_jam"=>["name"=>"JazzberryJam","hex"=>"#a50b5e","rgb"=>[165,11,94]],"jet"=>["name"=>"Jet","hex"=>"#343434","rgb"=>[52,52,52]],"jonquil"=>["name"=>"Jonquil","hex"=>"#fada5e","rgb"=>[250,218,94]],"june_bud"=>["name"=>"JuneBud","hex"=>"#bdda57","rgb"=>[189,218,87]],"jungle_green"=>["name"=>"JungleGreen","hex"=>"#29ab87","rgb"=>[41,171,135]],"kelly_green"=>["name"=>"KellyGreen","hex"=>"#4cbb17","rgb"=>[76,187,23]],"kenyan_copper"=>["name"=>"KenyanCopper","hex"=>"#7c1c05","rgb"=>[124,28,5]],"khaki_html_css_khaki"=>["name"=>"Khaki(Html\/Css)(Khaki)","hex"=>"#c3b091","rgb"=>[195,176,145]],"khaki_x11_light_khaki"=>["name"=>"Khaki(X11)(LightKhaki)","hex"=>"#f0e68c","rgb"=>[240,230,140]],"ku_crimson"=>["name"=>"KuCrimson","hex"=>"#e8000d","rgb"=>[232,0,13]],"la_salle_green"=>["name"=>"LaSalleGreen","hex"=>"#087830","rgb"=>[8,120,48]],"languid_lavender"=>["name"=>"LanguidLavender","hex"=>"#d6cadd","rgb"=>[214,202,221]],"lapis_lazuli"=>["name"=>"LapisLazuli","hex"=>"#26619c","rgb"=>[38,97,156]],"laser_lemon"=>["name"=>"LaserLemon","hex"=>"#fefe22","rgb"=>[254,254,34]],"laurel_green"=>["name"=>"LaurelGreen","hex"=>"#a9ba9d","rgb"=>[169,186,157]],"lava"=>["name"=>"Lava","hex"=>"#cf1020","rgb"=>[207,16,32]],"lavender_blue"=>["name"=>"LavenderBlue","hex"=>"#ccf","rgb"=>[204,204,255]],"lavender_blush"=>["name"=>"LavenderBlush","hex"=>"#fff0f5","rgb"=>[255,240,245]],"lavender_floral"=>["name"=>"Lavender(Floral)","hex"=>"#b57edc","rgb"=>[181,126,220]],"lavender_gray"=>["name"=>"LavenderGray","hex"=>"#c4c3d0","rgb"=>[196,195,208]],
"lavender_indigo"=>["name"=>"LavenderIndigo","hex"=>"#9457eb","rgb"=>[148,87,235]],"lavender_magenta"=>["name"=>"LavenderMagenta","hex"=>"#ee82ee","rgb"=>[238,130,238]],"lavender_mist"=>["name"=>"LavenderMist","hex"=>"#e6e6fa","rgb"=>[230,230,250]],"lavender_pink"=>["name"=>"LavenderPink","hex"=>"#fbaed2","rgb"=>[251,174,210]],"lavender_purple"=>["name"=>"LavenderPurple","hex"=>"#967bb6","rgb"=>[150,123,182]],"lavender_rose"=>["name"=>"LavenderRose","hex"=>"#fba0e3","rgb"=>[251,160,227]],"lavender_web"=>["name"=>"Lavender(Web)","hex"=>"#e6e6fa","rgb"=>[230,230,250]],"lawn_green"=>["name"=>"LawnGreen","hex"=>"#7cfc00","rgb"=>[124,252,0]],"lemon"=>["name"=>"Lemon","hex"=>"#fff700","rgb"=>[255,247,0]],"lemon_chiffon"=>["name"=>"LemonChiffon","hex"=>"#fffacd","rgb"=>[255,250,205]],"lemon_lime"=>["name"=>"LemonLime","hex"=>"#e3ff00","rgb"=>[227,255,0]],"licorice"=>["name"=>"Licorice","hex"=>"#1a1110","rgb"=>[26,17,16]],"light_apricot"=>["name"=>"LightApricot","hex"=>"#fdd5b1","rgb"=>[253,213,177]],"light_blue"=>["name"=>"LightBlue","hex"=>"#add8e6","rgb"=>[173,216,230]],"light_brown"=>["name"=>"LightBrown","hex"=>"#b5651d","rgb"=>[181,101,29]],"light_carmine_pink"=>["name"=>"LightCarminePink","hex"=>"#e66771","rgb"=>[230,103,113]],"light_coral"=>["name"=>"LightCoral","hex"=>"#f08080","rgb"=>[240,128,128]],"light_cornflower_blue"=>["name"=>"LightCornflowerBlue","hex"=>"#93ccea","rgb"=>[147,204,234]],"light_crimson"=>["name"=>"LightCrimson","hex"=>"#f56991","rgb"=>[245,105,145]],"light_cyan"=>["name"=>"LightCyan","hex"=>"#e0ffff","rgb"=>[224,255,255]],"light_fuchsia_pink"=>["name"=>"LightFuchsiaPink","hex"=>"#f984ef","rgb"=>[249,132,239]],"light_goldenrod_yellow"=>["name"=>"LightGoldenrodYellow","hex"=>"#fafad2","rgb"=>[250,250,210]],
"light_gray"=>["name"=>"LightGray","hex"=>"#d3d3d3","rgb"=>[211,211,211]],"light_green"=>["name"=>"LightGreen","hex"=>"#90ee90","rgb"=>[144,238,144]],"light_khaki"=>["name"=>"LightKhaki","hex"=>"#f0e68c","rgb"=>[240,230,140]],"light_pastel_purple"=>["name"=>"LightPastelPurple","hex"=>"#b19cd9","rgb"=>[177,156,217]],"light_pink"=>["name"=>"LightPink","hex"=>"#ffb6c1","rgb"=>[255,182,193]],"light_red_ochre"=>["name"=>"LightRedOchre","hex"=>"#e97451","rgb"=>[233,116,81]],"light_salmon"=>["name"=>"LightSalmon","hex"=>"#ffa07a","rgb"=>[255,160,122]],"light_salmon_pink"=>["name"=>"LightSalmonPink","hex"=>"#f99","rgb"=>[255,153,153]],"light_sea_green"=>["name"=>"LightSeaGreen","hex"=>"#20b2aa","rgb"=>[32,178,170]],"light_sky_blue"=>["name"=>"LightSkyBlue","hex"=>"#87cefa","rgb"=>[135,206,250]],"light_slate_gray"=>["name"=>"LightSlateGray","hex"=>"#789","rgb"=>[119,136,153]],"light_taupe"=>["name"=>"LightTaupe","hex"=>"#b38b6d","rgb"=>[179,139,109]],"light_thulian_pink"=>["name"=>"LightThulianPink","hex"=>"#e68fac","rgb"=>[230,143,172]],"light_yellow"=>["name"=>"LightYellow","hex"=>"#ffffe0","rgb"=>[255,255,224]],"lilac"=>["name"=>"Lilac","hex"=>"#c8a2c8","rgb"=>[200,162,200]],"lime_color_wheel"=>["name"=>"Lime(ColorWheel)","hex"=>"#bfff00","rgb"=>[191,255,0]],"lime_green"=>["name"=>"LimeGreen","hex"=>"#32cd32","rgb"=>[50,205,50]],"lime_web_x11_green"=>["name"=>"Lime(Web)(X11Green)","hex"=>"#0f0","rgb"=>[0,255,0]],"limerick"=>["name"=>"Limerick","hex"=>"#9dc209","rgb"=>[157,194,9]],"lincoln_green"=>["name"=>"LincolnGreen","hex"=>"#195905","rgb"=>[25,89,5]],"linen"=>["name"=>"Linen","hex"=>"#faf0e6","rgb"=>[250,240,230]],"lion"=>["name"=>"Lion","hex"=>"#c19a6b","rgb"=>[193,154,107]],
"little_boy_blue"=>["name"=>"LittleBoyBlue","hex"=>"#6ca0dc","rgb"=>[108,160,220]],"liver"=>["name"=>"Liver","hex"=>"#534b4f","rgb"=>[83,75,79]],"lust"=>["name"=>"Lust","hex"=>"#e62020","rgb"=>[230,32,32]],"magenta"=>["name"=>"Magenta","hex"=>"#f0f","rgb"=>[255,0,255]],"magenta_dye"=>["name"=>"Magenta(Dye)","hex"=>"#ca1f7b","rgb"=>[202,31,123]],"magenta_process"=>["name"=>"Magenta(Process)","hex"=>"#ff0090","rgb"=>[255,0,144]],"magic_mint"=>["name"=>"MagicMint","hex"=>"#aaf0d1","rgb"=>[170,240,209]],"magnolia"=>["name"=>"Magnolia","hex"=>"#f8f4ff","rgb"=>[248,244,255]],"mahogany"=>["name"=>"Mahogany","hex"=>"#c04000","rgb"=>[192,64,0]],"maize"=>["name"=>"Maize","hex"=>"#fbec5d","rgb"=>[251,236,93]],"majorelle_blue"=>["name"=>"MajorelleBlue","hex"=>"#6050dc","rgb"=>[96,80,220]],"malachite"=>["name"=>"Malachite","hex"=>"#0bda51","rgb"=>[11,218,81]],"manatee"=>["name"=>"Manatee","hex"=>"#979aaa","rgb"=>[151,154,170]],"mango_tango"=>["name"=>"MangoTango","hex"=>"#ff8243","rgb"=>[255,130,67]],"mantis"=>["name"=>"Mantis","hex"=>"#74c365","rgb"=>[116,195,101]],"mardi_gras"=>["name"=>"MardiGras","hex"=>"#880085","rgb"=>[136,0,133]],"maroon_crayola"=>["name"=>"Maroon(Crayola)","hex"=>"#c32148","rgb"=>[195,33,72]],"maroon_html_css"=>["name"=>"Maroon(Html\/Css)","hex"=>"#800000","rgb"=>[128,0,0]],"maroon_x11"=>["name"=>"Maroon(X11)","hex"=>"#b03060","rgb"=>[176,48,96]],"mauve"=>["name"=>"Mauve","hex"=>"#e0b0ff","rgb"=>[224,176,255]],"mauve_taupe"=>["name"=>"MauveTaupe","hex"=>"#915f6d","rgb"=>[145,95,109]],"mauvelous"=>["name"=>"Mauvelous","hex"=>"#ef98aa","rgb"=>[239,152,170]],"maya_blue"=>["name"=>"MayaBlue","hex"=>"#73c2fb","rgb"=>[115,194,251]],"meat_brown"=>["name"=>"MeatBrown","hex"=>"#e5b73b","rgb"=>[229,183,59]],
"medium_aquamarine"=>["name"=>"MediumAquamarine","hex"=>"#6da","rgb"=>[102,221,170]],"medium_blue"=>["name"=>"MediumBlue","hex"=>"#0000cd","rgb"=>[0,0,205]],"medium_candy_apple_red"=>["name"=>"MediumCandyAppleRed","hex"=>"#e2062c","rgb"=>[226,6,44]],"medium_carmine"=>["name"=>"MediumCarmine","hex"=>"#af4035","rgb"=>[175,64,53]],"medium_champagne"=>["name"=>"MediumChampagne","hex"=>"#f3e5ab","rgb"=>[243,229,171]],"medium_electric_blue"=>["name"=>"MediumElectricBlue","hex"=>"#035096","rgb"=>[3,80,150]],"medium_jungle_green"=>["name"=>"MediumJungleGreen","hex"=>"#1c352d","rgb"=>[28,53,45]],"medium_lavender_magenta"=>["name"=>"MediumLavenderMagenta","hex"=>"#dda0dd","rgb"=>[221,160,221]],"medium_orchid"=>["name"=>"MediumOrchid","hex"=>"#ba55d3","rgb"=>[186,85,211]],"medium_persian_blue"=>["name"=>"MediumPersianBlue","hex"=>"#0067a5","rgb"=>[0,103,165]],"medium_purple"=>["name"=>"MediumPurple","hex"=>"#9370db","rgb"=>[147,112,219]],"medium_red_violet"=>["name"=>"MediumRed-Violet","hex"=>"#bb3385","rgb"=>[187,51,133]],"medium_ruby"=>["name"=>"MediumRuby","hex"=>"#aa4069","rgb"=>[170,64,105]],"medium_sea_green"=>["name"=>"MediumSeaGreen","hex"=>"#3cb371","rgb"=>[60,179,113]],"medium_slate_blue"=>["name"=>"MediumSlateBlue","hex"=>"#7b68ee","rgb"=>[123,104,238]],"medium_spring_bud"=>["name"=>"MediumSpringBud","hex"=>"#c9dc87","rgb"=>[201,220,135]],"medium_spring_green"=>["name"=>"MediumSpringGreen","hex"=>"#00fa9a","rgb"=>[0,250,154]],"medium_taupe"=>["name"=>"MediumTaupe","hex"=>"#674c47","rgb"=>[103,76,71]],"medium_turquoise"=>["name"=>"MediumTurquoise","hex"=>"#48d1cc","rgb"=>[72,209,204]],"medium_tuscan_red"=>["name"=>"MediumTuscanRed","hex"=>"#79443b","rgb"=>[121,68,59]],
"medium_vermilion"=>["name"=>"MediumVermilion","hex"=>"#d9603b","rgb"=>[217,96,59]],"medium_violet_red"=>["name"=>"MediumViolet-Red","hex"=>"#c71585","rgb"=>[199,21,133]],"mellow_apricot"=>["name"=>"MellowApricot","hex"=>"#f8b878","rgb"=>[248,184,120]],"mellow_yellow"=>["name"=>"MellowYellow","hex"=>"#f8de7e","rgb"=>[248,222,126]],"melon"=>["name"=>"Melon","hex"=>"#fdbcb4","rgb"=>[253,188,180]],"midnight_blue"=>["name"=>"MidnightBlue","hex"=>"#191970","rgb"=>[25,25,112]],"midnight_green_eagle_green"=>["name"=>"MidnightGreen(EagleGreen)","hex"=>"#004953","rgb"=>[0,73,83]],"mikado_yellow"=>["name"=>"MikadoYellow","hex"=>"#ffc40c","rgb"=>[255,196,12]],"mint"=>["name"=>"Mint","hex"=>"#3eb489","rgb"=>[62,180,137]],"mint_cream"=>["name"=>"MintCream","hex"=>"#f5fffa","rgb"=>[245,255,250]],"mint_green"=>["name"=>"MintGreen","hex"=>"#98ff98","rgb"=>[152,255,152]],"misty_rose"=>["name"=>"MistyRose","hex"=>"#ffe4e1","rgb"=>[255,228,225]],"moccasin"=>["name"=>"Moccasin","hex"=>"#faebd7","rgb"=>[250,235,215]],"mode_beige"=>["name"=>"ModeBeige","hex"=>"#967117","rgb"=>[150,113,23]],"moonstone_blue"=>["name"=>"MoonstoneBlue","hex"=>"#73a9c2","rgb"=>[115,169,194]],"mordant_red_19"=>["name"=>"MordantRed19","hex"=>"#ae0c00","rgb"=>[174,12,0]],"moss_green"=>["name"=>"MossGreen","hex"=>"#addfad","rgb"=>[173,223,173]],"mountain_meadow"=>["name"=>"MountainMeadow","hex"=>"#30ba8f","rgb"=>[48,186,143]],"mountbatten_pink"=>["name"=>"MountbattenPink","hex"=>"#997a8d","rgb"=>[153,122,141]],"msu_green"=>["name"=>"MsuGreen","hex"=>"#18453b","rgb"=>[24,69,59]],"mulberry"=>["name"=>"Mulberry","hex"=>"#c54b8c","rgb"=>[197,75,140]],"mustard"=>["name"=>"Mustard","hex"=>"#ffdb58","rgb"=>[255,219,88]],
"myrtle"=>["name"=>"Myrtle","hex"=>"#21421e","rgb"=>[33,66,30]],"nadeshiko_pink"=>["name"=>"NadeshikoPink","hex"=>"#f6adc6","rgb"=>[246,173,198]],"napier_green"=>["name"=>"NapierGreen","hex"=>"#2a8000","rgb"=>[42,128,0]],"naples_yellow"=>["name"=>"NaplesYellow","hex"=>"#fada5e","rgb"=>[250,218,94]],"navajo_white"=>["name"=>"NavajoWhite","hex"=>"#ffdead","rgb"=>[255,222,173]],"navy_blue"=>["name"=>"NavyBlue","hex"=>"#000080","rgb"=>[0,0,128]],"neon_carrot"=>["name"=>"NeonCarrot","hex"=>"#ffa343","rgb"=>[255,163,67]],"neon_fuchsia"=>["name"=>"NeonFuchsia","hex"=>"#fe4164","rgb"=>[254,65,100]],"neon_green"=>["name"=>"NeonGreen","hex"=>"#39ff14","rgb"=>[57,255,20]],"new_york_pink"=>["name"=>"NewYorkPink","hex"=>"#d7837f","rgb"=>[215,131,127]],"non_photo_blue"=>["name"=>"Non-PhotoBlue","hex"=>"#a4dded","rgb"=>[164,221,237]],"north_texas_green"=>["name"=>"NorthTexasGreen","hex"=>"#059033","rgb"=>[5,144,51]],"ocean_boat_blue"=>["name"=>"OceanBoatBlue","hex"=>"#0077be","rgb"=>[0,119,190]],"ochre"=>["name"=>"Ochre","hex"=>"#c72","rgb"=>[204,119,34]],"office_green"=>["name"=>"OfficeGreen","hex"=>"#008000","rgb"=>[0,128,0]],"old_gold"=>["name"=>"OldGold","hex"=>"#cfb53b","rgb"=>[207,181,59]],"old_lace"=>["name"=>"OldLace","hex"=>"#fdf5e6","rgb"=>[253,245,230]],"old_lavender"=>["name"=>"OldLavender","hex"=>"#796878","rgb"=>[121,104,120]],"old_mauve"=>["name"=>"OldMauve","hex"=>"#673147","rgb"=>[103,49,71]],"old_rose"=>["name"=>"OldRose","hex"=>"#c08081","rgb"=>[192,128,129]],"olive"=>["name"=>"Olive","hex"=>"#808000","rgb"=>[128,128,0]],"olive_drab_7"=>["name"=>"OliveDrab#7","hex"=>"#3c341f","rgb"=>[60,52,31]],"olive_drab_web_olive_drab_3"=>["name"=>"OliveDrab(Web)(OliveDrab#3)","hex"=>"#6b8e23","rgb"=>[107,142,35]],
"olivine"=>["name"=>"Olivine","hex"=>"#9ab973","rgb"=>[154,185,115]],"onyx"=>["name"=>"Onyx","hex"=>"#353839","rgb"=>[53,56,57]],"opera_mauve"=>["name"=>"OperaMauve","hex"=>"#b784a7","rgb"=>[183,132,167]],"orange_color_wheel"=>["name"=>"Orange(ColorWheel)","hex"=>"#ff7f00","rgb"=>[255,127,0]],"orange_peel"=>["name"=>"OrangePeel","hex"=>"#ff9f00","rgb"=>[255,159,0]],"orange_red"=>["name"=>"Orange-Red","hex"=>"#ff4500","rgb"=>[255,69,0]],"orange_ryb"=>["name"=>"Orange(Ryb)","hex"=>"#fb9902","rgb"=>[251,153,2]],"orange_web_color"=>["name"=>"Orange(WebColor)","hex"=>"#ffa500","rgb"=>[255,165,0]],"orchid"=>["name"=>"Orchid","hex"=>"#da70d6","rgb"=>[218,112,214]],"otter_brown"=>["name"=>"OtterBrown","hex"=>"#654321","rgb"=>[101,67,33]],"ou_crimson_red"=>["name"=>"OuCrimsonRed","hex"=>"#900","rgb"=>[153,0,0]],"outer_space"=>["name"=>"OuterSpace","hex"=>"#414a4c","rgb"=>[65,74,76]],"outrageous_orange"=>["name"=>"OutrageousOrange","hex"=>"#ff6e4a","rgb"=>[255,110,74]],"oxford_blue"=>["name"=>"OxfordBlue","hex"=>"#002147","rgb"=>[0,33,71]],"pakistan_green"=>["name"=>"PakistanGreen","hex"=>"#060","rgb"=>[0,102,0]],"palatinate_blue"=>["name"=>"PalatinateBlue","hex"=>"#273be2","rgb"=>[39,59,226]],"palatinate_purple"=>["name"=>"PalatinatePurple","hex"=>"#682860","rgb"=>[104,40,96]],"pale_aqua"=>["name"=>"PaleAqua","hex"=>"#bcd4e6","rgb"=>[188,212,230]],"pale_blue"=>["name"=>"PaleBlue","hex"=>"#afeeee","rgb"=>[175,238,238]],"pale_brown"=>["name"=>"PaleBrown","hex"=>"#987654","rgb"=>[152,118,84]],"pale_carmine"=>["name"=>"PaleCarmine","hex"=>"#af4035","rgb"=>[175,64,53]],"pale_cerulean"=>["name"=>"PaleCerulean","hex"=>"#9bc4e2","rgb"=>[155,196,226]],"pale_chestnut"=>["name"=>"PaleChestnut","hex"=>"#ddadaf","rgb"=>[221,173,175]],
"pale_copper"=>["name"=>"PaleCopper","hex"=>"#da8a67","rgb"=>[218,138,103]],"pale_cornflower_blue"=>["name"=>"PaleCornflowerBlue","hex"=>"#abcdef","rgb"=>[171,205,239]],"pale_gold"=>["name"=>"PaleGold","hex"=>"#e6be8a","rgb"=>[230,190,138]],"pale_goldenrod"=>["name"=>"PaleGoldenrod","hex"=>"#eee8aa","rgb"=>[238,232,170]],"pale_green"=>["name"=>"PaleGreen","hex"=>"#98fb98","rgb"=>[152,251,152]],"pale_lavender"=>["name"=>"PaleLavender","hex"=>"#dcd0ff","rgb"=>[220,208,255]],"pale_magenta"=>["name"=>"PaleMagenta","hex"=>"#f984e5","rgb"=>[249,132,229]],"pale_pink"=>["name"=>"PalePink","hex"=>"#fadadd","rgb"=>[250,218,221]],"pale_plum"=>["name"=>"PalePlum","hex"=>"#dda0dd","rgb"=>[221,160,221]],"pale_red_violet"=>["name"=>"PaleRed-Violet","hex"=>"#db7093","rgb"=>[219,112,147]],"pale_robin_egg_blue"=>["name"=>"PaleRobinEggBlue","hex"=>"#96ded1","rgb"=>[150,222,209]],"pale_silver"=>["name"=>"PaleSilver","hex"=>"#c9c0bb","rgb"=>[201,192,187]],"pale_spring_bud"=>["name"=>"PaleSpringBud","hex"=>"#ecebbd","rgb"=>[236,235,189]],"pale_taupe"=>["name"=>"PaleTaupe","hex"=>"#bc987e","rgb"=>[188,152,126]],"pale_violet_red"=>["name"=>"PaleViolet-Red","hex"=>"#db7093","rgb"=>[219,112,147]],"pansy_purple"=>["name"=>"PansyPurple","hex"=>"#78184a","rgb"=>[120,24,74]],"papaya_whip"=>["name"=>"PapayaWhip","hex"=>"#ffefd5","rgb"=>[255,239,213]],"paris_green"=>["name"=>"ParisGreen","hex"=>"#50c878","rgb"=>[80,200,120]],"pastel_blue"=>["name"=>"PastelBlue","hex"=>"#aec6cf","rgb"=>[174,198,207]],"pastel_brown"=>["name"=>"PastelBrown","hex"=>"#836953","rgb"=>[131,105,83]],"pastel_gray"=>["name"=>"PastelGray","hex"=>"#cfcfc4","rgb"=>[207,207,196]],"pastel_green"=>["name"=>"PastelGreen","hex"=>"#7d7","rgb"=>[119,221,119]],
"pastel_magenta"=>["name"=>"PastelMagenta","hex"=>"#f49ac2","rgb"=>[244,154,194]],"pastel_orange"=>["name"=>"PastelOrange","hex"=>"#ffb347","rgb"=>[255,179,71]],"pastel_pink"=>["name"=>"PastelPink","hex"=>"#dea5a4","rgb"=>[222,165,164]],"pastel_purple"=>["name"=>"PastelPurple","hex"=>"#b39eb5","rgb"=>[179,158,181]],"pastel_red"=>["name"=>"PastelRed","hex"=>"#ff6961","rgb"=>[255,105,97]],"pastel_violet"=>["name"=>"PastelViolet","hex"=>"#cb99c9","rgb"=>[203,153,201]],"pastel_yellow"=>["name"=>"PastelYellow","hex"=>"#fdfd96","rgb"=>[253,253,150]],"patriarch"=>["name"=>"Patriarch","hex"=>"#800080","rgb"=>[128,0,128]],"payne_s_grey"=>["name"=>"Payne'SGrey","hex"=>"#536878","rgb"=>[83,104,120]],"peach"=>["name"=>"Peach","hex"=>"#ffe5b4","rgb"=>[255,229,180]],"peach_crayola"=>["name"=>"Peach(Crayola)","hex"=>"#ffcba4","rgb"=>[255,203,164]],"peach_orange"=>["name"=>"Peach-Orange","hex"=>"#fc9","rgb"=>[255,204,153]],"peach_puff"=>["name"=>"PeachPuff","hex"=>"#ffdab9","rgb"=>[255,218,185]],"peach_yellow"=>["name"=>"Peach-Yellow","hex"=>"#fadfad","rgb"=>[250,223,173]],"pear"=>["name"=>"Pear","hex"=>"#d1e231","rgb"=>[209,226,49]],"pearl"=>["name"=>"Pearl","hex"=>"#eae0c8","rgb"=>[234,224,200]],"pearl_aqua"=>["name"=>"PearlAqua","hex"=>"#88d8c0","rgb"=>[136,216,192]],"pearly_purple"=>["name"=>"PearlyPurple","hex"=>"#b768a2","rgb"=>[183,104,162]],"peridot"=>["name"=>"Peridot","hex"=>"#e6e200","rgb"=>[230,226,0]],"periwinkle"=>["name"=>"Periwinkle","hex"=>"#ccf","rgb"=>[204,204,255]],"persian_blue"=>["name"=>"PersianBlue","hex"=>"#1c39bb","rgb"=>[28,57,187]],"persian_green"=>["name"=>"PersianGreen","hex"=>"#00a693","rgb"=>[0,166,147]],"persian_indigo"=>["name"=>"PersianIndigo","hex"=>"#32127a","rgb"=>[50,18,122]],
"persian_orange"=>["name"=>"PersianOrange","hex"=>"#d99058","rgb"=>[217,144,88]],"persian_pink"=>["name"=>"PersianPink","hex"=>"#f77fbe","rgb"=>[247,127,190]],"persian_plum"=>["name"=>"PersianPlum","hex"=>"#701c1c","rgb"=>[112,28,28]],"persian_red"=>["name"=>"PersianRed","hex"=>"#c33","rgb"=>[204,51,51]],"persian_rose"=>["name"=>"PersianRose","hex"=>"#fe28a2","rgb"=>[254,40,162]],"persimmon"=>["name"=>"Persimmon","hex"=>"#ec5800","rgb"=>[236,88,0]],"peru"=>["name"=>"Peru","hex"=>"#cd853f","rgb"=>[205,133,63]],"phlox"=>["name"=>"Phlox","hex"=>"#df00ff","rgb"=>[223,0,255]],"phthalo_blue"=>["name"=>"PhthaloBlue","hex"=>"#000f89","rgb"=>[0,15,137]],"phthalo_green"=>["name"=>"PhthaloGreen","hex"=>"#123524","rgb"=>[18,53,36]],"piggy_pink"=>["name"=>"PiggyPink","hex"=>"#fddde6","rgb"=>[253,221,230]],"pine_green"=>["name"=>"PineGreen","hex"=>"#01796f","rgb"=>[1,121,111]],"pink"=>["name"=>"Pink","hex"=>"#ffc0cb","rgb"=>[255,192,203]],"pink_lace"=>["name"=>"PinkLace","hex"=>"#ffddf4","rgb"=>[255,221,244]],"pink_orange"=>["name"=>"Pink-Orange","hex"=>"#f96","rgb"=>[255,153,102]],"pink_pearl"=>["name"=>"PinkPearl","hex"=>"#e7accf","rgb"=>[231,172,207]],"pink_sherbet"=>["name"=>"PinkSherbet","hex"=>"#f78fa7","rgb"=>[247,143,167]],"pistachio"=>["name"=>"Pistachio","hex"=>"#93c572","rgb"=>[147,197,114]],"platinum"=>["name"=>"Platinum","hex"=>"#e5e4e2","rgb"=>[229,228,226]],"plum_traditional"=>["name"=>"Plum(Traditional)","hex"=>"#8e4585","rgb"=>[142,69,133]],"plum_web"=>["name"=>"Plum(Web)","hex"=>"#dda0dd","rgb"=>[221,160,221]],"portland_orange"=>["name"=>"PortlandOrange","hex"=>"#ff5a36","rgb"=>[255,90,54]],"powder_blue_web"=>["name"=>"PowderBlue(Web)","hex"=>"#b0e0e6","rgb"=>[176,224,230]],
"princeton_orange"=>["name"=>"PrincetonOrange","hex"=>"#ff8f00","rgb"=>[255,143,0]],"prune"=>["name"=>"Prune","hex"=>"#701c1c","rgb"=>[112,28,28]],"prussian_blue"=>["name"=>"PrussianBlue","hex"=>"#003153","rgb"=>[0,49,83]],"psychedelic_purple"=>["name"=>"PsychedelicPurple","hex"=>"#df00ff","rgb"=>[223,0,255]],"puce"=>["name"=>"Puce","hex"=>"#c89","rgb"=>[204,136,153]],"pumpkin"=>["name"=>"Pumpkin","hex"=>"#ff7518","rgb"=>[255,117,24]],"purple_heart"=>["name"=>"PurpleHeart","hex"=>"#69359c","rgb"=>[105,53,156]],"purple_html_css"=>["name"=>"Purple(Html\/Css)","hex"=>"#800080","rgb"=>[128,0,128]],"purple_mountain_majesty"=>["name"=>"PurpleMountainMajesty","hex"=>"#9678b6","rgb"=>[150,120,182]],"purple_munsell"=>["name"=>"Purple(Munsell)","hex"=>"#9f00c5","rgb"=>[159,0,197]],"purple_pizzazz"=>["name"=>"PurplePizzazz","hex"=>"#fe4eda","rgb"=>[254,78,218]],"purple_taupe"=>["name"=>"PurpleTaupe","hex"=>"#50404d","rgb"=>[80,64,77]],"purple_x11"=>["name"=>"Purple(X11)","hex"=>"#a020f0","rgb"=>[160,32,240]],"quartz"=>["name"=>"Quartz","hex"=>"#51484f","rgb"=>[81,72,79]],"rackley"=>["name"=>"Rackley","hex"=>"#5d8aa8","rgb"=>[93,138,168]],"radical_red"=>["name"=>"RadicalRed","hex"=>"#ff355e","rgb"=>[255,53,94]],"rajah"=>["name"=>"Rajah","hex"=>"#fbab60","rgb"=>[251,171,96]],"raspberry"=>["name"=>"Raspberry","hex"=>"#e30b5d","rgb"=>[227,11,93]],"raspberry_glace"=>["name"=>"RaspberryGlace","hex"=>"#915f6d","rgb"=>[145,95,109]],"raspberry_pink"=>["name"=>"RaspberryPink","hex"=>"#e25098","rgb"=>[226,80,152]],"raspberry_rose"=>["name"=>"RaspberryRose","hex"=>"#b3446c","rgb"=>[179,68,108]],"raw_umber"=>["name"=>"RawUmber","hex"=>"#826644","rgb"=>[130,102,68]],
"razzle_dazzle_rose"=>["name"=>"RazzleDazzleRose","hex"=>"#f3c","rgb"=>[255,51,204]],"razzmatazz"=>["name"=>"Razzmatazz","hex"=>"#e3256b","rgb"=>[227,37,107]],"red"=>["name"=>"Red","hex"=>"#f00","rgb"=>[255,0,0]],"red_brown"=>["name"=>"Red-Brown","hex"=>"#a52a2a","rgb"=>[165,42,42]],"red_devil"=>["name"=>"RedDevil","hex"=>"#860111","rgb"=>[134,1,17]],"red_munsell"=>["name"=>"Red(Munsell)","hex"=>"#f2003c","rgb"=>[242,0,60]],"red_ncs"=>["name"=>"Red(Ncs)","hex"=>"#c40233","rgb"=>[196,2,51]],"red_orange"=>["name"=>"Red-Orange","hex"=>"#ff5349","rgb"=>[255,83,73]],"red_pigment"=>["name"=>"Red(Pigment)","hex"=>"#ed1c24","rgb"=>[237,28,36]],"red_ryb"=>["name"=>"Red(Ryb)","hex"=>"#fe2712","rgb"=>[254,39,18]],"red_violet"=>["name"=>"Red-Violet","hex"=>"#c71585","rgb"=>[199,21,133]],"redwood"=>["name"=>"Redwood","hex"=>"#ab4e52","rgb"=>[171,78,82]],"regalia"=>["name"=>"Regalia","hex"=>"#522d80","rgb"=>[82,45,128]],"resolution_blue"=>["name"=>"ResolutionBlue","hex"=>"#002387","rgb"=>[0,35,135]],"rich_black"=>["name"=>"RichBlack","hex"=>"#004040","rgb"=>[0,64,64]],"rich_brilliant_lavender"=>["name"=>"RichBrilliantLavender","hex"=>"#f1a7fe","rgb"=>[241,167,254]],"rich_carmine"=>["name"=>"RichCarmine","hex"=>"#d70040","rgb"=>[215,0,64]],"rich_electric_blue"=>["name"=>"RichElectricBlue","hex"=>"#0892d0","rgb"=>[8,146,208]],"rich_lavender"=>["name"=>"RichLavender","hex"=>"#a76bcf","rgb"=>[167,107,207]],"rich_lilac"=>["name"=>"RichLilac","hex"=>"#b666d2","rgb"=>[182,102,210]],"rich_maroon"=>["name"=>"RichMaroon","hex"=>"#b03060","rgb"=>[176,48,96]],"rifle_green"=>["name"=>"RifleGreen","hex"=>"#414833","rgb"=>[65,72,51]],"robin_egg_blue"=>["name"=>"RobinEggBlue","hex"=>"#0cc","rgb"=>[0,204,204]],
"rose"=>["name"=>"Rose","hex"=>"#ff007f","rgb"=>[255,0,127]],"rose_bonbon"=>["name"=>"RoseBonbon","hex"=>"#f9429e","rgb"=>[249,66,158]],"rose_ebony"=>["name"=>"RoseEbony","hex"=>"#674846","rgb"=>[103,72,70]],"rose_gold"=>["name"=>"RoseGold","hex"=>"#b76e79","rgb"=>[183,110,121]],"rose_madder"=>["name"=>"RoseMadder","hex"=>"#e32636","rgb"=>[227,38,54]],"rose_pink"=>["name"=>"RosePink","hex"=>"#f6c","rgb"=>[255,102,204]],"rose_quartz"=>["name"=>"RoseQuartz","hex"=>"#aa98a9","rgb"=>[170,152,169]],"rose_taupe"=>["name"=>"RoseTaupe","hex"=>"#905d5d","rgb"=>[144,93,93]],"rose_vale"=>["name"=>"RoseVale","hex"=>"#ab4e52","rgb"=>[171,78,82]],"rosewood"=>["name"=>"Rosewood","hex"=>"#65000b","rgb"=>[101,0,11]],"rosso_corsa"=>["name"=>"RossoCorsa","hex"=>"#d40000","rgb"=>[212,0,0]],"rosy_brown"=>["name"=>"RosyBrown","hex"=>"#bc8f8f","rgb"=>[188,143,143]],"royal_azure"=>["name"=>"RoyalAzure","hex"=>"#0038a8","rgb"=>[0,56,168]],"royal_blue_traditional"=>["name"=>"RoyalBlue(Traditional)","hex"=>"#002366","rgb"=>[0,35,102]],"royal_blue_web"=>["name"=>"RoyalBlue(Web)","hex"=>"#4169e1","rgb"=>[65,105,225]],"royal_fuchsia"=>["name"=>"RoyalFuchsia","hex"=>"#ca2c92","rgb"=>[202,44,146]],"royal_purple"=>["name"=>"RoyalPurple","hex"=>"#7851a9","rgb"=>[120,81,169]],"royal_yellow"=>["name"=>"RoyalYellow","hex"=>"#fada5e","rgb"=>[250,218,94]],"rubine_red"=>["name"=>"RubineRed","hex"=>"#d10056","rgb"=>[209,0,86]],"ruby"=>["name"=>"Ruby","hex"=>"#e0115f","rgb"=>[224,17,95]],"ruby_red"=>["name"=>"RubyRed","hex"=>"#9b111e","rgb"=>[155,17,30]],"ruddy"=>["name"=>"Ruddy","hex"=>"#ff0028","rgb"=>[255,0,40]],"ruddy_brown"=>["name"=>"RuddyBrown","hex"=>"#bb6528","rgb"=>[187,101,40]],
"ruddy_pink"=>["name"=>"RuddyPink","hex"=>"#e18e96","rgb"=>[225,142,150]],"rufous"=>["name"=>"Rufous","hex"=>"#a81c07","rgb"=>[168,28,7]],"russet"=>["name"=>"Russet","hex"=>"#80461b","rgb"=>[128,70,27]],"rust"=>["name"=>"Rust","hex"=>"#b7410e","rgb"=>[183,65,14]],"rusty_red"=>["name"=>"RustyRed","hex"=>"#da2c43","rgb"=>[218,44,67]],"sacramento_state_green"=>["name"=>"SacramentoStateGreen","hex"=>"#00563f","rgb"=>[0,86,63]],"saddle_brown"=>["name"=>"SaddleBrown","hex"=>"#8b4513","rgb"=>[139,69,19]],"safety_orange_blaze_orange"=>["name"=>"SafetyOrange(BlazeOrange)","hex"=>"#ff6700","rgb"=>[255,103,0]],"saffron"=>["name"=>"Saffron","hex"=>"#f4c430","rgb"=>[244,196,48]],"salmon"=>["name"=>"Salmon","hex"=>"#ff8c69","rgb"=>[255,140,105]],"salmon_pink"=>["name"=>"SalmonPink","hex"=>"#ff91a4","rgb"=>[255,145,164]],"sand"=>["name"=>"Sand","hex"=>"#c2b280","rgb"=>[194,178,128]],"sand_dune"=>["name"=>"SandDune","hex"=>"#967117","rgb"=>[150,113,23]],"sandstorm"=>["name"=>"Sandstorm","hex"=>"#ecd540","rgb"=>[236,213,64]],"sandy_brown"=>["name"=>"SandyBrown","hex"=>"#f4a460","rgb"=>[244,164,96]],"sandy_taupe"=>["name"=>"SandyTaupe","hex"=>"#967117","rgb"=>[150,113,23]],"sangria"=>["name"=>"Sangria","hex"=>"#92000a","rgb"=>[146,0,10]],"sap_green"=>["name"=>"SapGreen","hex"=>"#507d2a","rgb"=>[80,125,42]],"sapphire"=>["name"=>"Sapphire","hex"=>"#0f52ba","rgb"=>[15,82,186]],"sapphire_blue"=>["name"=>"SapphireBlue","hex"=>"#0067a5","rgb"=>[0,103,165]],"satin_sheen_gold"=>["name"=>"SatinSheenGold","hex"=>"#cba135","rgb"=>[203,161,53]],"scarlet"=>["name"=>"Scarlet","hex"=>"#ff2400","rgb"=>[255,36,0]],"scarlet_crayola"=>["name"=>"Scarlet(Crayola)","hex"=>"#fd0e35","rgb"=>[253,14,53]],
"school_bus_yellow"=>["name"=>"SchoolBusYellow","hex"=>"#ffd800","rgb"=>[255,216,0]],"screamin_green"=>["name"=>"Screamin'Green","hex"=>"#76ff7a","rgb"=>[118,255,122]],"sea_blue"=>["name"=>"SeaBlue","hex"=>"#006994","rgb"=>[0,105,148]],"sea_green"=>["name"=>"SeaGreen","hex"=>"#2e8b57","rgb"=>[46,139,87]],"seal_brown"=>["name"=>"SealBrown","hex"=>"#321414","rgb"=>[50,20,20]],"seashell"=>["name"=>"Seashell","hex"=>"#fff5ee","rgb"=>[255,245,238]],"selective_yellow"=>["name"=>"SelectiveYellow","hex"=>"#ffba00","rgb"=>[255,186,0]],"sepia"=>["name"=>"Sepia","hex"=>"#704214","rgb"=>[112,66,20]],"shadow"=>["name"=>"Shadow","hex"=>"#8a795d","rgb"=>[138,121,93]],"shamrock_green"=>["name"=>"ShamrockGreen","hex"=>"#009e60","rgb"=>[0,158,96]],"shocking_pink"=>["name"=>"ShockingPink","hex"=>"#fc0fc0","rgb"=>[252,15,192]],"shocking_pink_crayola"=>["name"=>"ShockingPink(Crayola)","hex"=>"#ff6fff","rgb"=>[255,111,255]],"sienna"=>["name"=>"Sienna","hex"=>"#882d17","rgb"=>[136,45,23]],"silver"=>["name"=>"Silver","hex"=>"#c0c0c0","rgb"=>[192,192,192]],"sinopia"=>["name"=>"Sinopia","hex"=>"#cb410b","rgb"=>[203,65,11]],"skobeloff"=>["name"=>"Skobeloff","hex"=>"#007474","rgb"=>[0,116,116]],"sky_blue"=>["name"=>"SkyBlue","hex"=>"#87ceeb","rgb"=>[135,206,235]],"sky_magenta"=>["name"=>"SkyMagenta","hex"=>"#cf71af","rgb"=>[207,113,175]],"slate_blue"=>["name"=>"SlateBlue","hex"=>"#6a5acd","rgb"=>[106,90,205]],"slate_gray"=>["name"=>"SlateGray","hex"=>"#708090","rgb"=>[112,128,144]],"smalt_dark_powder_blue"=>["name"=>"Smalt(DarkPowderBlue)","hex"=>"#039","rgb"=>[0,51,153]],"smokey_topaz"=>["name"=>"SmokeyTopaz","hex"=>"#933d41","rgb"=>[147,61,65]],"smoky_black"=>["name"=>"SmokyBlack","hex"=>"#100c08","rgb"=>[16,12,8]],
"snow"=>["name"=>"Snow","hex"=>"#fffafa","rgb"=>[255,250,250]],"spiro_disco_ball"=>["name"=>"SpiroDiscoBall","hex"=>"#0fc0fc","rgb"=>[15,192,252]],"spring_bud"=>["name"=>"SpringBud","hex"=>"#a7fc00","rgb"=>[167,252,0]],"spring_green"=>["name"=>"SpringGreen","hex"=>"#00ff7f","rgb"=>[0,255,127]],"st_patrick_s_blue"=>["name"=>"St.Patrick'SBlue","hex"=>"#23297a","rgb"=>[35,41,122]],"steel_blue"=>["name"=>"SteelBlue","hex"=>"#4682b4","rgb"=>[70,130,180]],"stil_de_grain_yellow"=>["name"=>"StilDeGrainYellow","hex"=>"#fada5e","rgb"=>[250,218,94]],"stizza"=>["name"=>"Stizza","hex"=>"#900","rgb"=>[153,0,0]],"stormcloud"=>["name"=>"Stormcloud","hex"=>"#4f666a","rgb"=>[79,102,106]],"straw"=>["name"=>"Straw","hex"=>"#e4d96f","rgb"=>[228,217,111]],"sunglow"=>["name"=>"Sunglow","hex"=>"#fc3","rgb"=>[255,204,51]],"sunset"=>["name"=>"Sunset","hex"=>"#fad6a5","rgb"=>[250,214,165]],"tan"=>["name"=>"Tan","hex"=>"#d2b48c","rgb"=>[210,180,140]],"tangelo"=>["name"=>"Tangelo","hex"=>"#f94d00","rgb"=>[249,77,0]],"tangerine"=>["name"=>"Tangerine","hex"=>"#f28500","rgb"=>[242,133,0]],"tangerine_yellow"=>["name"=>"TangerineYellow","hex"=>"#fc0","rgb"=>[255,204,0]],"tango_pink"=>["name"=>"TangoPink","hex"=>"#e4717a","rgb"=>[228,113,122]],"taupe"=>["name"=>"Taupe","hex"=>"#483c32","rgb"=>[72,60,50]],"taupe_gray"=>["name"=>"TaupeGray","hex"=>"#8b8589","rgb"=>[139,133,137]],"tea_green"=>["name"=>"TeaGreen","hex"=>"#d0f0c0","rgb"=>[208,240,192]],"tea_rose_orange"=>["name"=>"TeaRose(Orange)","hex"=>"#f88379","rgb"=>[248,131,121]],"tea_rose_rose"=>["name"=>"TeaRose(Rose)","hex"=>"#f4c2c2","rgb"=>[244,194,194]],"teal"=>["name"=>"Teal","hex"=>"#008080","rgb"=>[0,128,128]],
"teal_blue"=>["name"=>"TealBlue","hex"=>"#367588","rgb"=>[54,117,136]],"teal_green"=>["name"=>"TealGreen","hex"=>"#00827f","rgb"=>[0,130,127]],"telemagenta"=>["name"=>"Telemagenta","hex"=>"#cf3476","rgb"=>[207,52,118]],"tenn_tawny"=>["name"=>"Tenné(Tawny)","hex"=>"#cd5700","rgb"=>[205,87,0]],"terra_cotta"=>["name"=>"TerraCotta","hex"=>"#e2725b","rgb"=>[226,114,91]],"thistle"=>["name"=>"Thistle","hex"=>"#d8bfd8","rgb"=>[216,191,216]],"thulian_pink"=>["name"=>"ThulianPink","hex"=>"#de6fa1","rgb"=>[222,111,161]],"tickle_me_pink"=>["name"=>"TickleMePink","hex"=>"#fc89ac","rgb"=>[252,137,172]],"tiffany_blue"=>["name"=>"TiffanyBlue","hex"=>"#0abab5","rgb"=>[10,186,181]],"tiger_s_eye"=>["name"=>"Tiger'SEye","hex"=>"#e08d3c","rgb"=>[224,141,60]],"timberwolf"=>["name"=>"Timberwolf","hex"=>"#dbd7d2","rgb"=>[219,215,210]],"titanium_yellow"=>["name"=>"TitaniumYellow","hex"=>"#eee600","rgb"=>[238,230,0]],"tomato"=>["name"=>"Tomato","hex"=>"#ff6347","rgb"=>[255,99,71]],"toolbox"=>["name"=>"Toolbox","hex"=>"#746cc0","rgb"=>[116,108,192]],"topaz"=>["name"=>"Topaz","hex"=>"#ffc87c","rgb"=>[255,200,124]],"tractor_red"=>["name"=>"TractorRed","hex"=>"#fd0e35","rgb"=>[253,14,53]],"trolley_grey"=>["name"=>"TrolleyGrey","hex"=>"#808080","rgb"=>[128,128,128]],"tropical_rain_forest"=>["name"=>"TropicalRainForest","hex"=>"#00755e","rgb"=>[0,117,94]],"true_blue"=>["name"=>"TrueBlue","hex"=>"#0073cf","rgb"=>[0,115,207]],"tufts_blue"=>["name"=>"TuftsBlue","hex"=>"#417dc1","rgb"=>[65,125,193]],"tumbleweed"=>["name"=>"Tumbleweed","hex"=>"#deaa88","rgb"=>[222,170,136]],"turkish_rose"=>["name"=>"TurkishRose","hex"=>"#b57281","rgb"=>[181,114,129]],"turquoise"=>["name"=>"Turquoise","hex"=>"#30d5c8","rgb"=>[48,213,200]],
"turquoise_blue"=>["name"=>"TurquoiseBlue","hex"=>"#00ffef","rgb"=>[0,255,239]],"turquoise_green"=>["name"=>"TurquoiseGreen","hex"=>"#a0d6b4","rgb"=>[160,214,180]],"tuscan_red"=>["name"=>"TuscanRed","hex"=>"#7c4848","rgb"=>[124,72,72]],"twilight_lavender"=>["name"=>"TwilightLavender","hex"=>"#8a496b","rgb"=>[138,73,107]],"tyrian_purple"=>["name"=>"TyrianPurple","hex"=>"#66023c","rgb"=>[102,2,60]],"ua_blue"=>["name"=>"UaBlue","hex"=>"#03a","rgb"=>[0,51,170]],"ua_red"=>["name"=>"UaRed","hex"=>"#d9004c","rgb"=>[217,0,76]],"ube"=>["name"=>"Ube","hex"=>"#8878c3","rgb"=>[136,120,195]],"ucla_blue"=>["name"=>"UclaBlue","hex"=>"#536895","rgb"=>[83,104,149]],"ucla_gold"=>["name"=>"UclaGold","hex"=>"#ffb300","rgb"=>[255,179,0]],"ufo_green"=>["name"=>"UfoGreen","hex"=>"#3cd070","rgb"=>[60,208,112]],"ultra_pink"=>["name"=>"UltraPink","hex"=>"#ff6fff","rgb"=>[255,111,255]],"ultramarine"=>["name"=>"Ultramarine","hex"=>"#120a8f","rgb"=>[18,10,143]],"ultramarine_blue"=>["name"=>"UltramarineBlue","hex"=>"#4166f5","rgb"=>[65,102,245]],"umber"=>["name"=>"Umber","hex"=>"#635147","rgb"=>[99,81,71]],"unbleached_silk"=>["name"=>"UnbleachedSilk","hex"=>"#ffddca","rgb"=>[255,221,202]],"united_nations_blue"=>["name"=>"UnitedNationsBlue","hex"=>"#5b92e5","rgb"=>[91,146,229]],"university_of_california_gold"=>["name"=>"UniversityOfCaliforniaGold","hex"=>"#b78727","rgb"=>[183,135,39]],"unmellow_yellow"=>["name"=>"UnmellowYellow","hex"=>"#ff6","rgb"=>[255,255,102]],"up_forest_green"=>["name"=>"UpForestGreen","hex"=>"#014421","rgb"=>[1,68,33]],"up_maroon"=>["name"=>"UpMaroon","hex"=>"#7b1113","rgb"=>[123,17,19]],"upsdell_red"=>["name"=>"UpsdellRed","hex"=>"#ae2029","rgb"=>[174,32,41]],"urobilin"=>["name"=>"Urobilin","hex"=>"#e1ad21","rgb"=>[225,173,33]],
"usafa_blue"=>["name"=>"UsafaBlue","hex"=>"#004f98","rgb"=>[0,79,152]],"usc_cardinal"=>["name"=>"UscCardinal","hex"=>"#900","rgb"=>[153,0,0]],"usc_gold"=>["name"=>"UscGold","hex"=>"#fc0","rgb"=>[255,204,0]],"utah_crimson"=>["name"=>"UtahCrimson","hex"=>"#d3003f","rgb"=>[211,0,63]],"vanilla"=>["name"=>"Vanilla","hex"=>"#f3e5ab","rgb"=>[243,229,171]],"vegas_gold"=>["name"=>"VegasGold","hex"=>"#c5b358","rgb"=>[197,179,88]],"venetian_red"=>["name"=>"VenetianRed","hex"=>"#c80815","rgb"=>[200,8,21]],"verdigris"=>["name"=>"Verdigris","hex"=>"#43b3ae","rgb"=>[67,179,174]],"vermilion_cinnabar"=>["name"=>"Vermilion(Cinnabar)","hex"=>"#e34234","rgb"=>[227,66,52]],"vermilion_plochere"=>["name"=>"Vermilion(Plochere)","hex"=>"#d9603b","rgb"=>[217,96,59]],"veronica"=>["name"=>"Veronica","hex"=>"#a020f0","rgb"=>[160,32,240]],"violet"=>["name"=>"Violet","hex"=>"#8f00ff","rgb"=>[143,0,255]],"violet_blue"=>["name"=>"Violet-Blue","hex"=>"#324ab2","rgb"=>[50,74,178]],"violet_color_wheel"=>["name"=>"Violet(ColorWheel)","hex"=>"#7f00ff","rgb"=>[127,0,255]],"violet_ryb"=>["name"=>"Violet(Ryb)","hex"=>"#8601af","rgb"=>[134,1,175]],"violet_web"=>["name"=>"Violet(Web)","hex"=>"#ee82ee","rgb"=>[238,130,238]],"viridian"=>["name"=>"Viridian","hex"=>"#40826d","rgb"=>[64,130,109]],"vivid_auburn"=>["name"=>"VividAuburn","hex"=>"#922724","rgb"=>[146,39,36]],"vivid_burgundy"=>["name"=>"VividBurgundy","hex"=>"#9f1d35","rgb"=>[159,29,53]],"vivid_cerise"=>["name"=>"VividCerise","hex"=>"#da1d81","rgb"=>[218,29,129]],"vivid_tangerine"=>["name"=>"VividTangerine","hex"=>"#ffa089","rgb"=>[255,160,137]],"vivid_violet"=>["name"=>"VividViolet","hex"=>"#9f00ff","rgb"=>[159,0,255]],"warm_black"=>["name"=>"WarmBlack","hex"=>"#004242","rgb"=>[0,66,66]],
"waterspout"=>["name"=>"Waterspout","hex"=>"#a4f4f9","rgb"=>[164,244,249]],"wenge"=>["name"=>"Wenge","hex"=>"#645452","rgb"=>[100,84,82]],"wheat"=>["name"=>"Wheat","hex"=>"#f5deb3","rgb"=>[245,222,179]],"white"=>["name"=>"White","hex"=>"#fff","rgb"=>[255,255,255]],"white_smoke"=>["name"=>"WhiteSmoke","hex"=>"#f5f5f5","rgb"=>[245,245,245]],"wild_blue_yonder"=>["name"=>"WildBlueYonder","hex"=>"#a2add0","rgb"=>[162,173,208]],"wild_strawberry"=>["name"=>"WildStrawberry","hex"=>"#ff43a4","rgb"=>[255,67,164]],"wild_watermelon"=>["name"=>"WildWatermelon","hex"=>"#fc6c85","rgb"=>[252,108,133]],"wine"=>["name"=>"Wine","hex"=>"#722f37","rgb"=>[114,47,55]],"wine_dregs"=>["name"=>"WineDregs","hex"=>"#673147","rgb"=>[103,49,71]],"wisteria"=>["name"=>"Wisteria","hex"=>"#c9a0dc","rgb"=>[201,160,220]],"wood_brown"=>["name"=>"WoodBrown","hex"=>"#c19a6b","rgb"=>[193,154,107]],"xanadu"=>["name"=>"Xanadu","hex"=>"#738678","rgb"=>[115,134,120]],"yale_blue"=>["name"=>"YaleBlue","hex"=>"#0f4d92","rgb"=>[15,77,146]],"yellow"=>["name"=>"Yellow","hex"=>"#ff0","rgb"=>[255,255,0]],"yellow_green"=>["name"=>"Yellow-Green","hex"=>"#9acd32","rgb"=>[154,205,50]],"yellow_munsell"=>["name"=>"Yellow(Munsell)","hex"=>"#efcc00","rgb"=>[239,204,0]],"yellow_ncs"=>["name"=>"Yellow(Ncs)","hex"=>"#ffd300","rgb"=>[255,211,0]],"yellow_orange"=>["name"=>"YellowOrange","hex"=>"#ffae42","rgb"=>[255,174,66]],"yellow_process"=>["name"=>"Yellow(Process)","hex"=>"#ffef00","rgb"=>[255,239,0]],"yellow_ryb"=>["name"=>"Yellow(Ryb)","hex"=>"#fefe33","rgb"=>[254,254,51]],"zaffre"=>["name"=>"Zaffre","hex"=>"#0014a8","rgb"=>[0,20,168]],"zinnwaldite_brown"=>["name"=>"ZinnwalditeBrown","hex"=>"#2c1608","rgb"=>[44,22,8]]];
}function phones(){
return [["code"=>"1876","brief"=>"JM","country"=>"Jamaica","format"=>"XXX XXXX","all"=>"1876XXXXXXX"],["code"=>"1869","brief"=>"KN","country"=>"Saint Kitts & Nevis","format"=>"XXX XXXX","all"=>"1869XXXXXXX"],["code"=>"1868","brief"=>"TT","country"=>"Trinidad & Tobago","format"=>"XXX XXXX","all"=>"1868XXXXXXX"],["code"=>"1784","brief"=>"VC","country"=>"Saint Vincent & the Grenadines","format"=>"XXX XXXX","all"=>"1784XXXXXXX"],["code"=>"1767","brief"=>"DM","country"=>"Dominica","format"=>"XXX XXXX","all"=>"1767XXXXXXX"],["code"=>"1758","brief"=>"LC","country"=>"Saint Lucia","format"=>"XXX XXXX","all"=>"1758XXXXXXX"],["code"=>"1721","brief"=>"SX","country"=>"Sint Maarten","format"=>"XXX XXXX","all"=>"1721XXXXXXX"],["code"=>"1684","brief"=>"AS","country"=>"American Samoa","format"=>"XXX XXXX","all"=>"1684XXXXXXX"],["code"=>"1671","brief"=>"GU","country"=>"Guam","format"=>"XXX XXXX","all"=>"1671XXXXXXX"],["code"=>"1670","brief"=>"MP","country"=>"Northern Mariana Islands","format"=>"XXX XXXX","all"=>"1670XXXXXXX"],["code"=>"1664","brief"=>"MS","country"=>"Montserrat","format"=>"XXX XXXX","all"=>"1664XXXXXXX"],["code"=>"1649","brief"=>"TC","country"=>"Turks & Caicos Islands","format"=>"XXX XXXX","all"=>"1649XXXXXXX"],["code"=>"1473","brief"=>"GD","country"=>"Grenada","format"=>"XXX XXXX","all"=>"1473XXXXXXX"],["code"=>"1441","brief"=>"BM","country"=>"Bermuda","format"=>"XXX XXXX","all"=>"1441XXXXXXX"],["code"=>"1345","brief"=>"KY","country"=>"Cayman Islands","format"=>"XXX XXXX","all"=>"1345XXXXXXX"],["code"=>"1340","brief"=>"VI","country"=>"US Virgin Islands","format"=>"XXX XXXX","all"=>"1340XXXXXXX"],["code"=>"1284","brief"=>"VG","country"=>"British Virgin Islands","format"=>"XXX XXXX","all"=>"1284XXXXXXX"],["code"=>"1268","brief"=>"AG","country"=>"Antigua & Barbuda","format"=>"XXX XXXX","all"=>"1268XXXXXXX"],
["code"=>"1264","brief"=>"AI","country"=>"Anguilla","format"=>"XXX XXXX","all"=>"1264XXXXXXX"],["code"=>"1246","brief"=>"BB","country"=>"Barbados","format"=>"XXX XXXX","all"=>"1246XXXXXXX"],["code"=>"1242","brief"=>"BS","country"=>"Bahamas","format"=>"XXX XXXX","all"=>"1242XXXXXXX"],["code"=>"998","brief"=>"UZ","country"=>"Uzbekistan","format"=>"XX XXXXXXX","all"=>"998XXXXXXXXX"],["code"=>"996","brief"=>"KG","country"=>"Kyrgyzstan","format"=>"XXX XXXXXX","all"=>"996XXXXXXXXX"],["code"=>"995","brief"=>"GE","country"=>"Georgia","format"=>"XXX XXX XXX","all"=>"995XXXXXXXXX"],["code"=>"994","brief"=>"AZ","country"=>"Azerbaijan","format"=>"XX XXX XXXX","all"=>"994XXXXXXXXX"],["code"=>"993","brief"=>"TM","country"=>"Turkmenistan","format"=>"XX XXXXXX","all"=>"993XXXXXXXX"],["code"=>"992","brief"=>"TJ","country"=>"Tajikistan","format"=>"XX XXX XXXX","all"=>"992XXXXXXXXX"],["code"=>"977","brief"=>"NP","country"=>"Nepal","format"=>"XX XXXX XXXX","all"=>"977XXXXXXXXXX"],["code"=>"976","brief"=>"MN","country"=>"Mongolia","format"=>"XX XX XXXX","all"=>"976XXXXXXXX"],["code"=>"975","brief"=>"BT","country"=>"Bhutan","format"=>"XX XXX XXX","all"=>"975XXXXXXXX"],["code"=>"974","brief"=>"QA","country"=>"Qatar","format"=>"XX XXX XXX","all"=>"974XXXXXXXX"],["code"=>"973","brief"=>"BH","country"=>"Bahrain","format"=>"XXXX XXXX","all"=>"973XXXXXXXX"],["code"=>"972","brief"=>"IL","country"=>"Israel","format"=>"XX XXX XXXX","all"=>"972XXXXXXXXX"],["code"=>"971","brief"=>"AE","country"=>"United Arab Emirates","format"=>"XX XXX XXXX","all"=>"971XXXXXXXXX"],["code"=>"970","brief"=>"PS","country"=>"Palestine","format"=>"XXX XX XXXX","all"=>"970XXXXXXXXX"],["code"=>"968","brief"=>"OM","country"=>"Oman","format"=>"XXXX XXXX","all"=>"968XXXXXXXX"],["code"=>"967","brief"=>"YE","country"=>"Yemen","format"=>"XXX XXX XXX","all"=>"967XXXXXXXXX"],
["code"=>"966","brief"=>"SA","country"=>"Saudi Arabia","format"=>"XX XXX XXXX","all"=>"966XXXXXXXXX"],["code"=>"965","brief"=>"KW","country"=>"Kuwait","format"=>"XXXX XXXX","all"=>"965XXXXXXXX"],["code"=>"964","brief"=>"IQ","country"=>"Iraq","format"=>"XXX XXX XXXX","all"=>"964XXXXXXXXXX"],["code"=>"963","brief"=>"SY","country"=>"Syria","format"=>"XXX XXX XXX","all"=>"963XXXXXXXXX"],["code"=>"962","brief"=>"JO","country"=>"Jordan","format"=>"X XXXX XXXX","all"=>"962XXXXXXXXX"],["code"=>"961","brief"=>"LB","country"=>"Lebanon ","format"=>false,"all"=>"961"],["code"=>"960","brief"=>"MV","country"=>"Maldives","format"=>"XXX XXXX","all"=>"960XXXXXXX"],["code"=>"886","brief"=>"TW","country"=>"Taiwan","format"=>"XXX XXX XXX","all"=>"886XXXXXXXXX"],["code"=>"880","brief"=>"BD","country"=>"Bangladesh ","format"=>false,"all"=>"880"],["code"=>"856","brief"=>"LA","country"=>"Laos","format"=>"XX XX XXX XXX","all"=>"856XXXXXXXXXX"],["code"=>"855","brief"=>"KH","country"=>"Cambodia ","format"=>false,"all"=>"855"],["code"=>"853","brief"=>"MO","country"=>"Macau","format"=>"XXXX XXXX","all"=>"853XXXXXXXX"],["code"=>"852","brief"=>"HK","country"=>"Hong Kong","format"=>"X XXX XXXX","all"=>"852XXXXXXXX"],["code"=>"850","brief"=>"KP","country"=>"North Korea ","format"=>false,"all"=>"850"],["code"=>"692","brief"=>"MH","country"=>"Marshall Islands ","format"=>false,"all"=>"692"],["code"=>"691","brief"=>"FM","country"=>"Micronesia ","format"=>false,"all"=>"691"],["code"=>"690","brief"=>"TK","country"=>"Tokelau ","format"=>false,"all"=>"690"],["code"=>"689","brief"=>"PF","country"=>"French Polynesia ","format"=>false,"all"=>"689"],["code"=>"688","brief"=>"TV","country"=>"Tuvalu ","format"=>false,"all"=>"688"],["code"=>"687","brief"=>"NC","country"=>"New Caledonia ","format"=>false,"all"=>"687"],
["code"=>"686","brief"=>"KI","country"=>"Kiribati ","format"=>false,"all"=>"686"],["code"=>"685","brief"=>"WS","country"=>"Samoa ","format"=>false,"all"=>"685"],["code"=>"683","brief"=>"NU","country"=>"Niue ","format"=>false,"all"=>"683"],["code"=>"682","brief"=>"CK","country"=>"Cook Islands ","format"=>false,"all"=>"682"],["code"=>"681","brief"=>"WF","country"=>"Wallis & Futuna ","format"=>false,"all"=>"681"],["code"=>"680","brief"=>"PW","country"=>"Palau ","format"=>false,"all"=>"680"],["code"=>"679","brief"=>"FJ","country"=>"Fiji ","format"=>false,"all"=>"679"],["code"=>"678","brief"=>"VU","country"=>"Vanuatu ","format"=>false,"all"=>"678"],["code"=>"677","brief"=>"SB","country"=>"Solomon Islands ","format"=>false,"all"=>"677"],["code"=>"676","brief"=>"TO","country"=>"Tonga ","format"=>false,"all"=>"676"],["code"=>"675","brief"=>"PG","country"=>"Papua New Guinea ","format"=>false,"all"=>"675"],["code"=>"674","brief"=>"NR","country"=>"Nauru ","format"=>false,"all"=>"674"],["code"=>"673","brief"=>"BN","country"=>"Brunei Darussalam","format"=>"XXX XXXX","all"=>"673XXXXXXX"],["code"=>"672","brief"=>"NF","country"=>"Norfolk Island ","format"=>false,"all"=>"672"],["code"=>"670","brief"=>"TL","country"=>"Timor-Leste ","format"=>false,"all"=>"670"],["code"=>"599","brief"=>"BQ","country"=>"Bonaire, Sint Eustatius & Saba ","format"=>false,"all"=>"599"],["code"=>"599","brief"=>"CW","country"=>"Curaçao ","format"=>false,"all"=>"599"],["code"=>"598","brief"=>"UY","country"=>"Uruguay","format"=>"X XXX XXXX","all"=>"598XXXXXXXX"],["code"=>"597","brief"=>"SR","country"=>"Suriname","format"=>"XXX XXXX","all"=>"597XXXXXXX"],["code"=>"596","brief"=>"MQ","country"=>"Martinique ","format"=>false,"all"=>"596"],["code"=>"595","brief"=>"PY","country"=>"Paraguay","format"=>"XXX XXX XXX","all"=>"595XXXXXXXXX"],
["code"=>"594","brief"=>"GF","country"=>"French Guiana ","format"=>false,"all"=>"594"],["code"=>"593","brief"=>"EC","country"=>"Ecuador","format"=>"XX XXX XXXX","all"=>"593XXXXXXXXX"],["code"=>"592","brief"=>"GY","country"=>"Guyana ","format"=>false,"all"=>"592"],["code"=>"591","brief"=>"BO","country"=>"Bolivia","format"=>"X XXX XXXX","all"=>"591XXXXXXXX"],["code"=>"590","brief"=>"GP","country"=>"Guadeloupe","format"=>"XXX XX XX XX","all"=>"590XXXXXXXXX"],["code"=>"509","brief"=>"HT","country"=>"Haiti ","format"=>false,"all"=>"509"],["code"=>"508","brief"=>"PM","country"=>"Saint Pierre & Miquelon ","format"=>false,"all"=>"508"],["code"=>"507","brief"=>"PA","country"=>"Panama","format"=>"XXXX XXXX","all"=>"507XXXXXXXX"],["code"=>"506","brief"=>"CR","country"=>"Costa Rica","format"=>"XXXX XXXX","all"=>"506XXXXXXXX"],["code"=>"505","brief"=>"NI","country"=>"Nicaragua","format"=>"XXXX XXXX","all"=>"505XXXXXXXX"],["code"=>"504","brief"=>"HN","country"=>"Honduras","format"=>"XXXX XXXX","all"=>"504XXXXXXXX"],["code"=>"503","brief"=>"SV","country"=>"El Salvador","format"=>"XXXX XXXX","all"=>"503XXXXXXXX"],["code"=>"502","brief"=>"GT","country"=>"Guatemala","format"=>"X XXX XXXX","all"=>"502XXXXXXXX"],["code"=>"501","brief"=>"BZ","country"=>"Belize ","format"=>false,"all"=>"501"],["code"=>"500","brief"=>"FK","country"=>"Falkland Islands ","format"=>false,"all"=>"500"],["code"=>"423","brief"=>"LI","country"=>"Liechtenstein ","format"=>false,"all"=>"423"],["code"=>"421","brief"=>"SK","country"=>"Slovakia","format"=>"XXX XXX XXX","all"=>"421XXXXXXXXX"],["code"=>"420","brief"=>"CZ","country"=>"Czech Republic","format"=>"XXX XXX XXX","all"=>"420XXXXXXXXX"],["code"=>"389","brief"=>"MK","country"=>"Macedonia","format"=>"XX XXX XXX","all"=>"389XXXXXXXX"],["code"=>"387","brief"=>"BA","country"=>"Bosnia & Herzegovina","format"=>"XX XXX XXX","all"=>"387XXXXXXXX"],
["code"=>"386","brief"=>"SI","country"=>"Slovenia","format"=>"XX XXX XXX","all"=>"386XXXXXXXX"],["code"=>"385","brief"=>"HR","country"=>"Croatia ","format"=>false,"all"=>"385"],["code"=>"382","brief"=>"ME","country"=>"Montenegro ","format"=>false,"all"=>"382"],["code"=>"381","brief"=>"RS","country"=>"Serbia","format"=>"XX XXX XXXX","all"=>"381XXXXXXXXX"],["code"=>"380","brief"=>"UA","country"=>"Ukraine","format"=>"XX XXX XX XX","all"=>"380XXXXXXXXX"],["code"=>"378","brief"=>"SM","country"=>"San Marino","format"=>"XXX XXX XXXX","all"=>"378XXXXXXXXXX"],["code"=>"377","brief"=>"MC","country"=>"Monaco","format"=>"XXXX XXXX","all"=>"377XXXXXXXX"],["code"=>"376","brief"=>"AD","country"=>"Andorra","format"=>"XX XX XX","all"=>"376XXXXXX"],["code"=>"375","brief"=>"BY","country"=>"Belarus","format"=>"XX XXX XXXX","all"=>"375XXXXXXXXX"],["code"=>"374","brief"=>"AM","country"=>"Armenia","format"=>"XX XXX XXX","all"=>"374XXXXXXXX"],["code"=>"373","brief"=>"MD","country"=>"Moldova","format"=>"XX XXX XXX","all"=>"373XXXXXXXX"],["code"=>"372","brief"=>"EE","country"=>"Estonia ","format"=>false,"all"=>"372"],["code"=>"371","brief"=>"LV","country"=>"Latvia","format"=>"XXX XXXXX","all"=>"371XXXXXXXX"],["code"=>"370","brief"=>"LT","country"=>"Lithuania","format"=>"XXX XXXXX","all"=>"370XXXXXXXX"],["code"=>"359","brief"=>"BG","country"=>"Bulgaria ","format"=>false,"all"=>"359"],["code"=>"358","brief"=>"FI","country"=>"Finland ","format"=>false,"all"=>"358"],["code"=>"357","brief"=>"CY","country"=>"Cyprus","format"=>"XXXX XXXX","all"=>"357XXXXXXXX"],["code"=>"356","brief"=>"MT","country"=>"Malta","format"=>"XX XX XX XX","all"=>"356XXXXXXXX"],["code"=>"355","brief"=>"AL","country"=>"Albania","format"=>"XX XXX XXXX","all"=>"355XXXXXXXXX"],["code"=>"354","brief"=>"IS","country"=>"Iceland","format"=>"XXX XXXX","all"=>"354XXXXXXX"],
["code"=>"353","brief"=>"IE","country"=>"Ireland","format"=>"XX XXX XXXX","all"=>"353XXXXXXXXX"],["code"=>"352","brief"=>"LU","country"=>"Luxembourg ","format"=>false,"all"=>"352"],["code"=>"351","brief"=>"PT","country"=>"Portugal","format"=>"X XXXX XXXX","all"=>"351XXXXXXXXX"],["code"=>"350","brief"=>"GI","country"=>"Gibraltar","format"=>"XXXX XXXX","all"=>"350XXXXXXXX"],["code"=>"299","brief"=>"GL","country"=>"Greenland","format"=>"XXX XXX","all"=>"299XXXXXX"],["code"=>"298","brief"=>"FO","country"=>"Faroe Islands","format"=>"XXX XXX","all"=>"298XXXXXX"],["code"=>"297","brief"=>"AW","country"=>"Aruba","format"=>"XXX XXXX","all"=>"297XXXXXXX"],["code"=>"291","brief"=>"ER","country"=>"Eritrea","format"=>"X XXX XXX","all"=>"291XXXXXXX"],["code"=>"290","brief"=>"SH","country"=>"Saint Helena","format"=>"XX XXX","all"=>"290XXXXX"],["code"=>"269","brief"=>"KM","country"=>"Comoros","format"=>"XXX XXXX","all"=>"269XXXXXXX"],["code"=>"268","brief"=>"SZ","country"=>"Swaziland","format"=>"XXXX XXXX","all"=>"268XXXXXXXX"],["code"=>"267","brief"=>"BW","country"=>"Botswana","format"=>"XX XXX XXX","all"=>"267XXXXXXXX"],["code"=>"266","brief"=>"LS","country"=>"Lesotho","format"=>"XX XXX XXX","all"=>"266XXXXXXXX"],["code"=>"265","brief"=>"MW","country"=>"Malawi","format"=>"77 XXX XXXX","all"=>"26577XXXXXXX"],["code"=>"264","brief"=>"NA","country"=>"Namibia","format"=>"XX XXX XXXX","all"=>"264XXXXXXXXX"],["code"=>"263","brief"=>"ZW","country"=>"Zimbabwe","format"=>"XX XXX XXXX","all"=>"263XXXXXXXXX"],["code"=>"262","brief"=>"RE","country"=>"Réunion","format"=>"XXX XXX XXX","all"=>"262XXXXXXXXX"],["code"=>"261","brief"=>"MG","country"=>"Madagascar","format"=>"XX XX XXX XX","all"=>"261XXXXXXXXX"],["code"=>"260","brief"=>"ZM","country"=>"Zambia","format"=>"XX XXX XXXX","all"=>"260XXXXXXXXX"],
["code"=>"258","brief"=>"MZ","country"=>"Mozambique","format"=>"XX XXX XXXX","all"=>"258XXXXXXXXX"],["code"=>"257","brief"=>"BI","country"=>"Burundi","format"=>"XX XX XXXX","all"=>"257XXXXXXXX"],["code"=>"256","brief"=>"UG","country"=>"Uganda","format"=>"XX XXX XXXX","all"=>"256XXXXXXXXX"],["code"=>"255","brief"=>"TZ","country"=>"Tanzania","format"=>"XX XXX XXXX","all"=>"255XXXXXXXXX"],["code"=>"254","brief"=>"KE","country"=>"Kenya","format"=>"XXX XXX XXX","all"=>"254XXXXXXXXX"],["code"=>"253","brief"=>"DJ","country"=>"Djibouti","format"=>"XX XX XX XX","all"=>"253XXXXXXXX"],["code"=>"252","brief"=>"SO","country"=>"Somalia","format"=>"XX XXX XXX","all"=>"252XXXXXXXX"],["code"=>"251","brief"=>"ET","country"=>"Ethiopia","format"=>"XX XXX XXXX","all"=>"251XXXXXXXXX"],["code"=>"250","brief"=>"RW","country"=>"Rwanda","format"=>"XXX XXX XXX","all"=>"250XXXXXXXXX"],["code"=>"249","brief"=>"SD","country"=>"Sudan","format"=>"XX XXX XXXX","all"=>"249XXXXXXXXX"],["code"=>"248","brief"=>"SC","country"=>"Seychelles","format"=>"X XX XX XX","all"=>"248XXXXXXX"],["code"=>"247","brief"=>"SH","country"=>"Saint Helena","format"=>"XXXX","all"=>"247XXXX"],["code"=>"246","brief"=>"IO","country"=>"Diego Garcia","format"=>"XXX XXXX","all"=>"246XXXXXXX"],["code"=>"245","brief"=>"GW","country"=>"Guinea-Bissau","format"=>"XXX XXXX","all"=>"245XXXXXXX"],["code"=>"244","brief"=>"AO","country"=>"Angola","format"=>"XXX XXX XXX","all"=>"244XXXXXXXXX"],["code"=>"243","brief"=>"CD","country"=>"Congo (Dem. Rep.)","format"=>"XX XXXXXXX","all"=>"243XXXXXXXXX"],["code"=>"242","brief"=>"CG","country"=>"Congo (Rep.)","format"=>"XX XXX XXXX","all"=>"242XXXXXXXXX"],["code"=>"241","brief"=>"GA","country"=>"Gabon","format"=>"X XX XX XX","all"=>"241XXXXXXX"],["code"=>"240","brief"=>"GQ","country"=>"Equatorial Guinea","format"=>"XXX XXX XXX","all"=>"240XXXXXXXXX"],
["code"=>"239","brief"=>"ST","country"=>"São Tomé & Príncipe","format"=>"XX XXXXX","all"=>"239XXXXXXX"],["code"=>"238","brief"=>"CV","country"=>"Cape Verde","format"=>"XXX XXXX","all"=>"238XXXXXXX"],["code"=>"237","brief"=>"CM","country"=>"Cameroon","format"=>"XXXX XXXX","all"=>"237XXXXXXXX"],["code"=>"236","brief"=>"CF","country"=>"Central African Rep.","format"=>"XX XX XX XX","all"=>"236XXXXXXXX"],["code"=>"235","brief"=>"TD","country"=>"Chad","format"=>"XX XX XX XX","all"=>"235XXXXXXXX"],["code"=>"234","brief"=>"NG","country"=>"Nigeria ","format"=>false,"all"=>"234"],["code"=>"233","brief"=>"GH","country"=>"Ghana ","format"=>false,"all"=>"233"],["code"=>"232","brief"=>"SL","country"=>"Sierra Leone","format"=>"XX XXX XXX","all"=>"232XXXXXXXX"],["code"=>"231","brief"=>"LR","country"=>"Liberia ","format"=>false,"all"=>"231"],["code"=>"230","brief"=>"MU","country"=>"Mauritius ","format"=>false,"all"=>"230"],["code"=>"229","brief"=>"BJ","country"=>"Benin","format"=>"XX XXX XXX","all"=>"229XXXXXXXX"],["code"=>"228","brief"=>"TG","country"=>"Togo","format"=>"XX XXX XXX","all"=>"228XXXXXXXX"],["code"=>"227","brief"=>"NE","country"=>"Niger","format"=>"XX XX XX XX","all"=>"227XXXXXXXX"],["code"=>"226","brief"=>"BF","country"=>"Burkina Faso","format"=>"XX XX XX XX","all"=>"226XXXXXXXX"],["code"=>"225","brief"=>"CI","country"=>"Côte d`Ivoire","format"=>"XX XXX XXX","all"=>"225XXXXXXXX"],["code"=>"224","brief"=>"GN","country"=>"Guinea","format"=>"XXX XXX XXX","all"=>"224XXXXXXXXX"],["code"=>"223","brief"=>"ML","country"=>"Mali","format"=>"XXXX XXXX","all"=>"223XXXXXXXX"],["code"=>"222","brief"=>"MR","country"=>"Mauritania","format"=>"XXXX XXXX","all"=>"222XXXXXXXX"],["code"=>"221","brief"=>"SN","country"=>"Senegal","format"=>"XX XXX XXXX","all"=>"221XXXXXXXXX"],
["code"=>"220","brief"=>"GM","country"=>"Gambia","format"=>"XXX XXXX","all"=>"220XXXXXXX"],["code"=>"218","brief"=>"LY","country"=>"Libya","format"=>"XX XXX XXXX","all"=>"218XXXXXXXXX"],["code"=>"216","brief"=>"TN","country"=>"Tunisia","format"=>"XX XXX XXX","all"=>"216XXXXXXXX"],["code"=>"213","brief"=>"DZ","country"=>"Algeria","format"=>"XXX XX XX XX","all"=>"213XXXXXXXXX"],["code"=>"212","brief"=>"MA","country"=>"Morocco","format"=>"XX XXX XXXX","all"=>"212XXXXXXXXX"],["code"=>"211","brief"=>"SS","country"=>"South Sudan","format"=>"XX XXX XXXX","all"=>"211XXXXXXXXX"],["code"=>"98","brief"=>"IR","country"=>"Iran","format"=>"XXX XXX XXXX","all"=>"98XXXXXXXXXX"],["code"=>"95","brief"=>"MM","country"=>"Myanmar ","format"=>false,"all"=>"95"],["code"=>"94","brief"=>"LK","country"=>"Sri Lanka","format"=>"XX XXX XXXX","all"=>"94XXXXXXXXX"],["code"=>"93","brief"=>"AF","country"=>"Afghanistan","format"=>"XXX XXX XXX","all"=>"93XXXXXXXXX"],["code"=>"92","brief"=>"PK","country"=>"Pakistan","format"=>"XXX XXX XXXX","all"=>"92XXXXXXXXXX"],["code"=>"91","brief"=>"IN","country"=>"India","format"=>"XXXXX XXXXX","all"=>"91XXXXXXXXXX"],["code"=>"90","brief"=>"TR","country"=>"Turkey","format"=>"XXX XXX XXXX","all"=>"90XXXXXXXXXX"],["code"=>"86","brief"=>"CN","country"=>"China","format"=>"XXX XXXX XXXX","all"=>"86XXXXXXXXXXX"],["code"=>"84","brief"=>"VN","country"=>"Vietnam ","format"=>false,"all"=>"84"],["code"=>"82","brief"=>"KR","country"=>"South Korea ","format"=>false,"all"=>"82"],["code"=>"81","brief"=>"JP","country"=>"Japan","format"=>"XX XXXX XXXX","all"=>"81XXXXXXXXXX"],["code"=>"66","brief"=>"TH","country"=>"Thailand","format"=>"X XXXX XXXX","all"=>"66XXXXXXXXX"],["code"=>"65","brief"=>"SG","country"=>"Singapore","format"=>"XXXX XXXX","all"=>"65XXXXXXXX"],["code"=>"64","brief"=>"NZ","country"=>"New Zealand ","format"=>false,"all"=>"64"],
["code"=>"63","brief"=>"PH","country"=>"Philippines","format"=>"XXX XXX XXXX","all"=>"63XXXXXXXXXX"],["code"=>"62","brief"=>"ID","country"=>"Indonesia ","format"=>false,"all"=>"62"],["code"=>"61","brief"=>"AU","country"=>"Australia","format"=>"XXX XXX XXX","all"=>"61XXXXXXXXX"],["code"=>"60","brief"=>"MY","country"=>"Malaysia ","format"=>false,"all"=>"60"],["code"=>"58","brief"=>"VE","country"=>"Venezuela","format"=>"XXX XXX XXXX","all"=>"58XXXXXXXXXX"],["code"=>"57","brief"=>"CO","country"=>"Colombia","format"=>"XXX XXX XXXX","all"=>"57XXXXXXXXXX"],["code"=>"56","brief"=>"CL","country"=>"Chile","format"=>"X XXXX XXXX","all"=>"56XXXXXXXXX"],["code"=>"55","brief"=>"BR","country"=>"Brazil","format"=>"XX XXXXX XXXX","all"=>"55XXXXXXXXXXX"],["code"=>"54","brief"=>"AR","country"=>"Argentina ","format"=>false,"all"=>"54"],["code"=>"53","brief"=>"CU","country"=>"Cuba","format"=>"XXXX XXXX","all"=>"53XXXXXXXX"],["code"=>"52","brief"=>"MX","country"=>"Mexico ","format"=>false,"all"=>"52"],["code"=>"51","brief"=>"PE","country"=>"Peru","format"=>"XXX XXX XXX","all"=>"51XXXXXXXXX"],["code"=>"49","brief"=>"DE","country"=>"Germany ","format"=>false,"all"=>"49"],["code"=>"48","brief"=>"PL","country"=>"Poland","format"=>"XX XXX XXXX","all"=>"48XXXXXXXXX"],["code"=>"47","brief"=>"NO","country"=>"Norway","format"=>"XXXX XXXX","all"=>"47XXXXXXXX"],["code"=>"46","brief"=>"SE","country"=>"Sweden","format"=>"XX XXX XXXX","all"=>"46XXXXXXXXX"],["code"=>"45","brief"=>"DK","country"=>"Denmark","format"=>"XXXX XXXX","all"=>"45XXXXXXXX"],["code"=>"44","brief"=>"GB","country"=>"United Kingdom","format"=>"XXXX XXXXXX","all"=>"44XXXXXXXXXX"],["code"=>"43","brief"=>"AT","country"=>"Austria ","format"=>false,"all"=>"43"],["code"=>"42","brief"=>"YL","country"=>"Y-land ","format"=>false,"all"=>"42"],
["code"=>"41","brief"=>"CH","country"=>"Switzerland","format"=>"XX XXX XXXX","all"=>"41XXXXXXXXX"],["code"=>"40","brief"=>"RO","country"=>"Romania","format"=>"XXX XXX XXX","all"=>"40XXXXXXXXX"],["code"=>"39","brief"=>"IT","country"=>"Italy ","format"=>false,"all"=>"39"],["code"=>"36","brief"=>"HU","country"=>"Hungary","format"=>"XXX XXX XXX","all"=>"36XXXXXXXXX"],["code"=>"34","brief"=>"ES","country"=>"Spain","format"=>"XXX XXX XXX","all"=>"34XXXXXXXXX"],["code"=>"33","brief"=>"FR","country"=>"France","format"=>"X XX XX XX XX","all"=>"33XXXXXXXXX"],["code"=>"32","brief"=>"BE","country"=>"Belgium","format"=>"XXX XX XX XX","all"=>"32XXXXXXXXX"],["code"=>"31","brief"=>"NL","country"=>"Netherlands","format"=>"X XX XX XX XX","all"=>"31XXXXXXXXX"],["code"=>"30","brief"=>"GR","country"=>"Greece","format"=>"XXX XXX XXXX","all"=>"30XXXXXXXXXX"],["code"=>"27","brief"=>"ZA","country"=>"South Africa","format"=>"XX XXX XXXX","all"=>"27XXXXXXXXX"],["code"=>"20","brief"=>"EG","country"=>"Egypt","format"=>"XX XXXX XXXX","all"=>"20XXXXXXXXXX"],["code"=>"7","brief"=>"KZ","country"=>"Kazakhstan","format"=>"XXX XXX XX XX","all"=>"7XXXXXXXXXX"],["code"=>"7","brief"=>"RU","country"=>"Russian Federation","format"=>"XXX XXX XXXX","all"=>"7XXXXXXXXXX"],["code"=>"1","brief"=>"PR","country"=>"Puerto Rico","format"=>"XXX XXX XXXX","all"=>"1XXXXXXXXXX"],["code"=>"1","brief"=>"DO","country"=>"Dominican Rep.","format"=>"XXX XXX XXXX","all"=>"1XXXXXXXXXX"],["code"=>"1","brief"=>"CA","country"=>"Canada","format"=>"XXX XXX XXXX","all"=>"1XXXXXXXXXX"],["code"=>"1","brief"=>"US","country"=>"USA","format"=>"XXX XXX XXXX","all"=>"1XXXXXXXXXX"]];
}function countrys(){
return [["code"=>"AFG","country"=>"Afghanistan","name"=>"افغانستان"],["code"=>"ALB","country"=>"Albania","name"=>"آلباني"],["code"=>"DZA","country"=>"Algeria","name"=>"الجزاير"],["code"=>"ASM","country"=>"American Samoa","name"=>"ساموآ"],["code"=>"AND","country"=>"Andorra","name"=>"آندورا"],["code"=>"AGO","country"=>"Angola","name"=>"آنگولا"],["code"=>"AIA","country"=>"Anguilla","name"=>"آنگويلا"],["code"=>"ATA","country"=>"Antarctica","name"=>"قاره ي جنوبگان"],["code"=>"ATG","country"=>"Antigua & Barbuda","name"=>"آنتگيوا و باربوداس"],["code"=>"ARG","country"=>"Argentina","name"=>"آرژانتين"],["code"=>"ARM","country"=>"Armenia","name"=>"آلباني"],["code"=>"ABW","country"=>"Aruba","name"=>"آروبا"],["code"=>"AUS","country"=>"Australia","name"=>"استراليا"],["code"=>"AUT","country"=>"Austria","name"=>"اطريش"],["code"=>"AZE","country"=>"Azerbaijan","name"=>"آذربايجان"],["code"=>"BHS","country"=>"Bahamas","name"=>"باهاما"],["code"=>"BHR","country"=>"Bahrain","name"=>"بحرين"],["code"=>"BGD","country"=>"Bangladesh","name"=>"بنگلادش"],["code"=>"BRB","country"=>"Barbados","name"=>"باربادوس"],["code"=>"BLR","country"=>"Belarus","name"=>"بلاروس"],["code"=>"BEL","country"=>"Belgium","name"=>"بلژيک"],["code"=>"BLZ","country"=>"Belize","name"=>"بليز"],["code"=>"BEN","country"=>"Benin","name"=>"بنين"],["code"=>"BMU","country"=>"Bermuda","name"=>"برمودا"],["code"=>"BTN","country"=>"Bhutan","name"=>"بوتان"],["code"=>"BOL","country"=>"Bolivia","name"=>"بوليوي"],["code"=>"BIH","country"=>"Bosnia & Herzegowina","name"=>"بوسني و هرزگوين"],["code"=>"BWA","country"=>"Botswana","name"=>"بوتسوانا"],["code"=>"BVT","country"=>"Bouvet Island","name"=>"جزيره بووت"],["code"=>"BRA","country"=>"Brazil","name"=>"برزيل"],["code"=>"IOT","country"=>"British Indian Ocean Terr.","name"=>"مستملکات انگلستان در قيانوس هند"],["code"=>"BRN","country"=>"Brunei Darussalam","name"=>"بروئني"],["code"=>"BGR","country"=>"Bulgaria","name"=>"بلغارستان"],
["code"=>"BFA","country"=>"Burkina Faso","name"=>"بورکينافاسو"],["code"=>"MMR","country"=>"Burma (Myanmar)","name"=>"برمه (ميانمار)"],["code"=>"BDI","country"=>"Burundi","name"=>"بروندي"],["code"=>"KHM","country"=>"Cambodia","name"=>"کامبوج"],["code"=>"CMR","country"=>"Cameroon","name"=>"کامرون"],["code"=>"CAN","country"=>"Canada","name"=>"کانادا"],["code"=>"CPV","country"=>"Cape Verde","name"=>"کيپورد"],["code"=>"CYM","country"=>"Cayman Islands","name"=>"شيلي"],["code"=>"CAF","country"=>"Central African Rep","name"=>"چمهوري آفريقاي مرکزي"],["code"=>"TCD","country"=>"Chad","name"=>"چاد"],["code"=>"CHL","country"=>"Chile","name"=>"شيلي"],["code"=>"CHN","country"=>"China","name"=>"چين"],["code"=>"CXR","country"=>"Christmas Island","name"=>"چزيره کريسمس"],["code"=>"CCK","country"=>"Cocos (Keeling) Isles","name"=>"چزيره کوکو"],["code"=>"COL","country"=>"Colombia","name"=>"کلمبيا"],["code"=>"COM","country"=>"Comoros","name"=>"کوموروس"],["code"=>"COG","country"=>"Congo","name"=>"کنگو"],["code"=>"COD","country"=>"Congo, The Democratic Rep","name"=>"چمهوري دموکراسي کنگو"],["code"=>"COK","country"=>"Cook Islands","name"=>"چزيره کوک"],["code"=>"CRI","country"=>"Costa Rica","name"=>"کاستاريکا"],["code"=>"HRV","country"=>"Croatia","name"=>"کرواسي"],["code"=>"CUB","country"=>"Cuba","name"=>"کوبا"],["code"=>"CYP","country"=>"Cyprus","name"=>"قبرس"],["code"=>"CZE","country"=>"Czech Republic","name"=>"جمهوري چک"],["code"=>"DNK","country"=>"Denmark","name"=>"دانمارک"],["code"=>"DJI","country"=>"Djibouti","name"=>"جي بوتي"],["code"=>"DMA","country"=>"Dominica","name"=>"دومينيکا"],["code"=>"DOM","country"=>"Dominican Republic","name"=>"جمهوري دومينيکن"],["code"=>"TMP","country"=>"East Timor","name"=>"تيمور شرقي"],["code"=>"ECU","country"=>"Ecuador","name"=>"اکوادور"],["code"=>"EGY","country"=>"Egypt","name"=>"مصر"],["code"=>"SLV","country"=>"El Salvador","name"=>"ال سالوادور"],
["code"=>"GNQ","country"=>"Equatorial Guinea","name"=>"گينه استوايي"],["code"=>"ERI","country"=>"Eritrea","name"=>"اريتره"],["code"=>"EST","country"=>"Estonia","name"=>"استوني"],["code"=>"ETH","country"=>"Ethiopia","name"=>"اتيوپي"],["code"=>"FLK","country"=>"Falkland Islands (Malvinas)","name"=>"جزاير فالکند"],["code"=>"FRO","country"=>"Faroe Islands","name"=>"جزاير فارو"],["code"=>"FJI","country"=>"Fiji","name"=>"فيجي"],["code"=>"FIN","country"=>"Finland","name"=>"فنلاند"],["code"=>"FRA","country"=>"France","name"=>"فرانسه"],["code"=>"FXX","country"=>"France, Metro","name"=>"فرانسه مترو"],["code"=>"GUF","country"=>"French Guiana","name"=>"گياباي فرانسه"],["code"=>"PYF","country"=>"French Polynesia","name"=>"پولنيزي فرانسه"],["code"=>"ATF","country"=>"French Southern Terr.","name"=>"جنوب فرانسه"],["code"=>"GAB","country"=>"Gabon","name"=>"گابن"],["code"=>"GMB","country"=>"Gambia","name"=>"گامبيا"],["code"=>"GEO","country"=>"Georgia","name"=>"جورجيا"],["code"=>"DEU","country"=>"Germany","name"=>"آلماني"],["code"=>"GHA","country"=>"Ghana","name"=>"غنا"],["code"=>"GIB","country"=>"Gibraltar","name"=>"جبل الطارق"],["code"=>"GRC","country"=>"Greece","name"=>"يونان"],["code"=>"GRL","country"=>"Greenland","name"=>"گرين لند"],["code"=>"GRD","country"=>"Grenada","name"=>"گرنادا"],["code"=>"GLP","country"=>"Guadeloupe","name"=>"گوادالوپ"],["code"=>"GUM","country"=>"Guam","name"=>"گوام"],["code"=>"GTM","country"=>"Guatemala","name"=>"گواتمالا"],["code"=>"GIN","country"=>"Guinea","name"=>"گينه"],["code"=>"GNB","country"=>"Guinea-Bissau","name"=>"گينه بيسائو"],["code"=>"GUY","country"=>"Guyana","name"=>"گيانا"],["code"=>"HTI","country"=>"Haiti","name"=>"هائيتي"],["code"=>"HMD","country"=>"Heard And Mc Donald Isles","name"=>"جزاير هرد و مک دونالد"],["code"=>"VAT","country"=>"Holy See (Vatican)","name"=>"واتيکان"],["code"=>"HND","country"=>"Honduras","name"=>"هندوراس"],["code"=>"HKG","country"=>"Hong Kong","name"=>"هونگ کونگ"],
["code"=>"HUN","country"=>"Hungary","name"=>"بلغارستان"],["code"=>"ISL","country"=>"Iceland","name"=>"ايسلند"],["code"=>"IND","country"=>"India","name"=>"هند"],["code"=>"IDN","country"=>"Indonesia","name"=>"اندونزي"],["code"=>"IRN","country"=>"Iran","name"=>"ايران"],["code"=>"IRQ","country"=>"Iraq","name"=>"عراق"],["code"=>"IRL","country"=>"Ireland","name"=>"ايرلند"],["code"=>"ITA","country"=>"Italy","name"=>"ايتاليا"],["code"=>"CIV","country"=>"Ivory Coast (Cote D'Ivoire)","name"=>"ساحل عاج"],["code"=>"JAM","country"=>"Jamaica","name"=>"جامائيکا"],["code"=>"JPN","country"=>"Japan","name"=>"ژاپن"],["code"=>"JOR","country"=>"Jordan","name"=>"اردن"],["code"=>"KAZ","country"=>"Kazakhstan","name"=>"قزاقستان"],["code"=>"KEN","country"=>"Kenya","name"=>"کنيا"],["code"=>"KIR","country"=>"Kiribati","name"=>"کيري باتي"],["code"=>"PRK","country"=>"Korea","name"=>"کره"],["code"=>"KOR","country"=>"Korea, Republic Of","name"=>"جمهوري کره"],["code"=>"KWT","country"=>"Kuwait","name"=>"کويت"],["code"=>"KGZ","country"=>"Kyrgyzstan","name"=>"قيرقيزستان"],["code"=>"LAO","country"=>"Laos","name"=>"لائوس"],["code"=>"LVA","country"=>"Latvia","name"=>"لتوني"],["code"=>"LBN","country"=>"Lebanon","name"=>"لبنان"],["code"=>"LSO","country"=>"Lesotho","name"=>"لسوتو"],["code"=>"LBR","country"=>"Liberia","name"=>"ليبريا"],["code"=>"LBY","country"=>"Libya","name"=>"ليبي"],["code"=>"LIE","country"=>"Liechtenstein","name"=>"ليختن اشتاين"],["code"=>"LTU","country"=>"Lithuania","name"=>"ليتواني"],["code"=>"LUX","country"=>"Luxembourg","name"=>"لوکزامبورگ"],["code"=>"MAC","country"=>"Macau","name"=>"ماکائو"],["code"=>"MKD","country"=>"Macedonia (Republic of)","name"=>"جمهوري مقدونيه"],["code"=>"MDG","country"=>"Madagascar","name"=>"ماداگاسکار"],["code"=>"MWI","country"=>"Malawi","name"=>"مالاوي"],["code"=>"MYS","country"=>"Malaysia","name"=>"مالزي"],["code"=>"MDV","country"=>"Maldives","name"=>"مالديو"],["code"=>"MLI","country"=>"Mali","name"=>"مالي"],
["code"=>"MLT","country"=>"Malta","name"=>"مالت"],["code"=>"MHL","country"=>"Marshall Islands","name"=>"جزاير مارشال"],["code"=>"MTQ","country"=>"Martinique","name"=>"مارتينيک"],["code"=>"MRT","country"=>"Mauritania","name"=>"موريتانيا"],["code"=>"MUS","country"=>"Mauritius","name"=>"موري تيوس"],["code"=>"MYT","country"=>"Mayotte","name"=>"ماريوت"],["code"=>"MEX","country"=>"Mexico","name"=>"مکزيک"],["code"=>"FSM","country"=>"Micronesia, Fed States","name"=>"دولت فدرال ميکرونزي"],["code"=>"MDA","country"=>"Moldova, Rep","name"=>"چمهوري مولداوي"],["code"=>"MCO","country"=>"Monaco","name"=>"موناکو"],["code"=>"MNG","country"=>"Mongolia","name"=>"مغولستان"],["code"=>"MSR","country"=>"Montserrat","name"=>"مونتسررات"],["code"=>"MAR","country"=>"Morocco","name"=>"مراکش (مغرب)"],["code"=>"MOZ","country"=>"Mozambique","name"=>"موزامبيک"],["code"=>"NAM","country"=>"Namibia","name"=>"ناميبيا"],["code"=>"NRU","country"=>"Nauru","name"=>"نورد"],["code"=>"NPL","country"=>"Nepal","name"=>"تپال"],["code"=>"NLD","country"=>"Netherlands","name"=>"هلند"],["code"=>"ANT","country"=>"Netherlands Antilles","name"=>"جزاير آنتيل"],["code"=>"NCL","country"=>"New Caledonia","name"=>"نيو اسکاتلند"],["code"=>"NZL","country"=>"New Zealand","name"=>"نيوزيلند"],["code"=>"NIC","country"=>"Nicaragua","name"=>"نيکاراگوئه"],["code"=>"NER","country"=>"Niger","name"=>"نيجر"],["code"=>"NGA","country"=>"Nigeria","name"=>"نيجريه"],["code"=>"NIU","country"=>"Niue","name"=>"نيو"],["code"=>"NFK","country"=>"Norfolk Island","name"=>"جزيره نورفولک"],["code"=>"MNP","country"=>"Northern Mariana Isles","name"=>"چزاير شمالي ماريانا"],["code"=>"NOR","country"=>"Norway","name"=>"نروژ"],["code"=>"OMN","country"=>"Oman","name"=>"عمان"],["code"=>"PAK","country"=>"Pakistan","name"=>"پاکستان"],["code"=>"PLW","country"=>"Palau","name"=>"پالو"],["code"=>"PAN","country"=>"Panama","name"=>"پاناما"],["code"=>"PNG","country"=>"Papua New Guinea","name"=>"پاپوا - گينه نو"],
["code"=>"PRY","country"=>"Paraguay","name"=>"پاراگوئه"],["code"=>"PER","country"=>"Peru","name"=>"پرو"],["code"=>"PHL","country"=>"Philippines","name"=>"فيليپين"],["code"=>"PCN","country"=>"Pitcairn","name"=>"پيتکايرن"],["code"=>"POL","country"=>"Poland","name"=>"لهستان"],["code"=>"PRT","country"=>"Portugal","name"=>"پرتغال"],["code"=>"PRI","country"=>"Puerto Rico","name"=>"پورتوريکو"],["code"=>"QAT","country"=>"Qatar","name"=>"قطر"],["code"=>"REU","country"=>"Reunion","name"=>"راونيون"],["code"=>"ROM","country"=>"Romania","name"=>"روماني"],["code"=>"RUS","country"=>"Russian Federation","name"=>"زوسيه"],["code"=>"RWA","country"=>"Rwanda","name"=>"رداندا"],["code"=>"KNA","country"=>"Saint Kitts & Nevis","name"=>"سنت کيتس و نويس"],["code"=>"LCA","country"=>"Saint Lucia","name"=>"سنت لويسا"],["code"=>"VCT","country"=>"Saint Vincent & Grenadines","name"=>"سنت ونسانت و گرنادين"],["code"=>"WSM","country"=>"Samoa","name"=>"ساموآ"],["code"=>"SMR","country"=>"San Marino","name"=>"سان ماريو"],["code"=>"STP","country"=>"Sao Tome & Principe","name"=>"سائوتوم و پرينسيپ"],["code"=>"SAU","country"=>"Saudi Arabia","name"=>"عربستان صعودي"],["code"=>"SEN","country"=>"Senegal","name"=>"سنگال"],["code"=>"SYC","country"=>"Seychelles","name"=>"سيشل"],["code"=>"SLE","country"=>"Sierra Leona","name"=>"سيرالئون"],["code"=>"SGP","country"=>"Singapore","name"=>"سنگاپور"],["code"=>"SVK","country"=>"Slovakia (Slovak Rep)","name"=>"جمهوري اسلواکي"],["code"=>"SVN","country"=>"Slovenia","name"=>"اسلودني"],["code"=>"SLB","country"=>"Solomon Islands","name"=>"جزاير سولومون"],["code"=>"SOM","country"=>"Somalia","name"=>"سومالي"],["code"=>"ZAF","country"=>"South Africa","name"=>"آفريقاي جنوبي"],["code"=>"SGS","country"=>"S Georgia & Sandwich Isles","name"=>"جزاير جورجيا و جزاير هاوائي"],["code"=>"ESP","country"=>"Spain","name"=>"اسپانيا"],["code"=>"LKA","country"=>"Sri Lanka","name"=>"سريلانکا"],["code"=>"SHN","country"=>"St. Helena","name"=>"سنت هلنا"],
["code"=>"SPM","country"=>"St. Pierre & Miquelon","name"=>"سنت پيرو ميکلون"],["code"=>"SDN","country"=>"Sudan","name"=>"سودان"],["code"=>"SUR","country"=>"Suriname","name"=>"سورينام"],["code"=>"SJM","country"=>"Svalbard & Jan Mayen Islands","name"=>"چزاير سوالبارد و يان ماين"],["code"=>"SWZ","country"=>"Swaziland","name"=>"سوازيلند"],["code"=>"SWE","country"=>"Sweden","name"=>"سوئد"],["code"=>"CHE","country"=>"Switzerland","name"=>"سوئيس"],["code"=>"SYR","country"=>"Syria","name"=>"سوريه"],["code"=>"TWN","country"=>"Taiwan","name"=>"تايوان"],["code"=>"TJK","country"=>"Tajikistan","name"=>"تاجيکستان"],["code"=>"TZA","country"=>"Tanzania","name"=>"تانزانيا"],["code"=>"THA","country"=>"Thailand","name"=>"تايلند"],["code"=>"TGO","country"=>"Togo","name"=>"توگو"],["code"=>"TKL","country"=>"Tokelau","name"=>"توکلو"],["code"=>"TON","country"=>"Tonga","name"=>"تونگا"],["code"=>"TTO","country"=>"Trinidad & Tobago","name"=>"ترينيداد توباگو"],["code"=>"TUN","country"=>"Tunisia","name"=>"تونس"],["code"=>"TUR","country"=>"Turkey","name"=>"ترکيه"],["code"=>"TKM","country"=>"Turkmenistan","name"=>"ترکمنستان"],["code"=>"TCA","country"=>"Turks & Caicos Islands","name"=>"جزاير تورکس و کايکوس"],["code"=>"TUV","country"=>"Tuvalu","name"=>"توواليو"],["code"=>"UGA","country"=>"Uganda","name"=>"اوگاندا"],["code"=>"UKR","country"=>"Ukraine","name"=>"اوکراين"],["code"=>"ARE","country"=>"United Arab Emirates","name"=>"امارات متحده عربي"],["code"=>"GBR","country"=>"United Kingdom","name"=>"انگلستان"],["code"=>"USA","country"=>"United States","name"=>"آمريکا"],["code"=>"URY","country"=>"Uruguay","name"=>"اروگوئه"],["code"=>"UZB","country"=>"Uzbekistan","name"=>"ازبکستان"],["code"=>"VUT","country"=>"Vanuatu","name"=>"وانواتو"],["code"=>"VEN","country"=>"Venezuela","name"=>"ونزوئلا"],["code"=>"VNM","country"=>"Viet Nam","name"=>"ويتنام"],["code"=>"VGB","country"=>"Virgin Isles (British)","name"=>"جزاير ويرجين (انگلستان)"],
["code"=>"VIR","country"=>"Virgin Isles (U.S.)","name"=>"جزاير ويرجين (آمريکا)"],["code"=>"WLF","country"=>"Wallis & Futuna Islands","name"=>"جزاير واليس و فورتونا"],["code"=>"ESH","country"=>"Western Sahara","name"=>"صحراي عربي"],["code"=>"YEM","country"=>"Yemen","name"=>"يمن"],["code"=>"YUG","country"=>"Yugoslavia","name"=>"يوگوسلاوي"],["code"=>"ZMB","country"=>"Zambia","name"=>"زامبيا"],["code"=>"ZWE","country"=>"Zimbabwe","name"=>"زيمباوه"]];
}function domains($s=true){
$d=["ac"=>["server"=>"nic.ac","country"=>"Ascension Island","type"=>"Country Code"],"academy"=>["server"=>"donuts.co","type"=>"Generic"],"accountants"=>["server"=>"donuts.co","type"=>"Generic"],"active"=>["server"=>"afilias-srs.net","type"=>"Generic"],"actor"=>["server"=>"unitedtld.com","type"=>"Generic"],"ae"=>["server"=>"aeda.net.ae","country"=>"United Arab Emirates","type"=>"Country Code"],"aero"=>["server"=>"aero","type"=>"Sponsored"],"af"=>["server"=>"nic.af","country"=>"Afghanistan","type"=>"Country Code"],"ag"=>["server"=>"nic.ag","country"=>"Antigua And Barbuda","type"=>"Country Code"],"agency"=>["server"=>"donuts.co","type"=>"Generic"],"ai"=>["server"=>"ai","country"=>"Anguilla","type"=>"Country Code"],"airforce"=>["server"=>"unitedtld.com","type"=>"Generic"],"am"=>["server"=>"amnic.net","country"=>"Armenia","type"=>"Country Code"],"archi"=>["server"=>"ksregistry.net","type"=>"Generic"],"army"=>["server"=>"rightside.co","type"=>"Generic"],"arpa"=>["server"=>"iana.org","type"=>"Infrastructure"],"as"=>["server"=>"nic.as","country"=>"American Samoa","type"=>"Country Code"],"asia"=>["server"=>"nic.asia","type"=>"Sponsored"],"associates"=>["server"=>"donuts.co","type"=>"Generic"],"at"=>["server"=>"nic.at","country"=>"Austria","type"=>"Country Code"],"attorney"=>["server"=>"rightside.co","type"=>"Generic"],"au"=>["server"=>"audns.net.au","country"=>"Australia","type"=>"Country Code"],"auction"=>["server"=>"donuts.co","type"=>"Generic"],"audio"=>["server"=>"uniregistry.net","type"=>"Generic"],"autos"=>["server"=>"afilias-srs.net","type"=>"Generic"],"aw"=>["server"=>"nic.aw","country"=>"Aruba","type"=>"Country Code"],"ax"=>["server"=>"ax","country"=>"\\u00c5land Islands","type"=>"Country Code"],"bar"=>["server"=>"nic.bar","type"=>"Generic"],
"bargains"=>["server"=>"donuts.co","type"=>"Generic"],"bayern"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"be"=>["server"=>"dns.be","country"=>"Belgium","type"=>"Country Code"],"beer"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"berlin"=>["server"=>"nic.berlin","type"=>"Generic"],"best"=>["server"=>"nic.best","type"=>"Generic"],"bg"=>["server"=>"register.bg","country"=>"Bulgaria","type"=>"Country Code"],"bike"=>["server"=>"donuts.co","type"=>"Generic"],"bio"=>["server"=>"ksregistry.net","type"=>"Generic"],"bi"=>["server"=>"1.nic.bi","country"=>"Burundi","type"=>"Country Code"],"black"=>["server"=>"afilias.net","type"=>"Generic"],"blackfriday"=>["server"=>"uniregistry.net","type"=>"Generic"],"blue"=>["server"=>"afilias.net","type"=>"Generic"],"biz"=>["server"=>"biz","type"=>"Generic Restricted"],"bj"=>["server"=>"nic.bj","country"=>"Benin","type"=>"Country Code"],"bmw"=>["server"=>"ksregistry.net","type"=>"Generic"],"bn"=>["server"=>"bn","country"=>"Brunei Darussalam","type"=>"Country Code"],"bo"=>["server"=>"nic.bo","country"=>"Bolivia","type"=>"Country Code"],"boutique"=>["server"=>"donuts.co","type"=>"Generic"],"br"=>["server"=>"registro.br","country"=>"Brazil","type"=>"Country Code"],"brussels"=>["server"=>"nic.brussels","type"=>"Generic"],"build"=>["server"=>"nic.build","type"=>"Generic"],"builders"=>["server"=>"donuts.co","type"=>"Generic"],"bw"=>["server"=>"nic.net.bw","country"=>"Botswana","type"=>"Country Code"],"bzh"=>["server"=>"-bzh.nic.fr","type"=>"Generic"],"ca"=>["server"=>"cira.ca","country"=>"Canada","type"=>"Country Code"],"cab"=>["server"=>"donuts.co","type"=>"Generic"],"camera"=>["server"=>"donuts.co","type"=>"Generic"],"camp"=>["server"=>"donuts.co","type"=>"Generic"],
"cancerresearch"=>["server"=>"nic.cancerresearch","type"=>"Generic"],"capetown"=>["server"=>"apetown-registry.net.za","type"=>"Generic"],"capital"=>["server"=>"donuts.co","type"=>"Generic"],"cards"=>["server"=>"donuts.co","type"=>"Generic"],"care"=>["server"=>"donuts.co","type"=>"Generic"],"career"=>["server"=>"nic.career","type"=>"Generic"],"careers"=>["server"=>"donuts.co","type"=>"Generic"],"cash"=>["server"=>"donuts.co","type"=>"Generic"],"cat"=>["server"=>"cat","type"=>"Sponsored"],"catering"=>["server"=>"donuts.co","type"=>"Generic"],"center"=>["server"=>"donuts.co","type"=>"Generic"],"ceo"=>["server"=>"nic.ceo","type"=>"Generic"],"cf"=>["server"=>"dot.cf","country"=>"Central African Republic","type"=>"Country Code"],"ch"=>["server"=>"nic.ch","country"=>"Switzerland","type"=>"Country Code"],"cheap"=>["server"=>"donuts.co","type"=>"Generic"],"christmas"=>["server"=>"uniregistry.net","type"=>"Generic"],"church"=>["server"=>"donuts.co","type"=>"Generic"],"ci"=>["server"=>"nic.ci","country"=>"Cote d\\u2019Ivoire","type"=>"Country Code"],"city"=>["server"=>"donuts.co","type"=>"Generic"],"cl"=>["server"=>"nic.cl","country"=>"Chile","type"=>"Country Code"],"claims"=>["server"=>"donuts.co","type"=>"Generic"],"cleaning"=>["server"=>"donuts.co","type"=>"Generic"],"clinic"=>["server"=>"donuts.co","type"=>"Generic"],"clothing"=>["server"=>"donuts.co","type"=>"Generic"],"club"=>["server"=>"nic.club","type"=>"Generic"],"cn"=>["server"=>"cnnic.cn","country"=>"China","type"=>"Country Code"],"co"=>["server"=>"nic.co","country"=>"Colombia","type"=>"Country Code"],"codes"=>["server"=>"donuts.co","type"=>"Generic"],"coffee"=>["server"=>"donuts.co","type"=>"Generic"],"college"=>["server"=>"centralnic.com","type"=>"Generic"],
"cologne"=>["server"=>"-fe1.pdt.cologne.tango.knipp.de","type"=>"Generic"],"com"=>["server"=>"verisign-grs.com","type"=>"Generic"],"community"=>["server"=>"donuts.co","type"=>"Generic"],"company"=>["server"=>"donuts.co","type"=>"Generic"],"computer"=>["server"=>"donuts.co","type"=>"Generic"],"condos"=>["server"=>"donuts.co","type"=>"Generic"],"construction"=>["server"=>"donuts.co","type"=>"Generic"],"consulting"=>["server"=>"unitedtld.com","type"=>"Generic"],"contractors"=>["server"=>"donuts.co","type"=>"Generic"],"cooking"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"cool"=>["server"=>"donuts.co","type"=>"Generic"],"country"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"credit"=>["server"=>"donuts.co","type"=>"Generic"],"creditcard"=>["server"=>"donuts.co","type"=>"Generic"],"cruises"=>["server"=>"donuts.co","type"=>"Generic"],"coop"=>["server"=>"nic.coop","type"=>"Sponsored"],"cuisinella"=>["server"=>"nic.cuisinella","type"=>"Generic"],"cx"=>["server"=>"nic.cx","country"=>"Christmas Island","type"=>"Country Code"],"cz"=>["server"=>"nic.cz","country"=>"Czech Republic","type"=>"Country Code"],"dance"=>["server"=>"unitedtld.com","type"=>"Generic"],"dating"=>["server"=>"donuts.co","type"=>"Generic"],"de"=>["server"=>"denic.de","country"=>"Germany","type"=>"Country Code"],"deals"=>["server"=>"donuts.co","type"=>"Generic"],"dental"=>["server"=>"donuts.co","type"=>"Generic"],"degree"=>["server"=>"unitedtld.com","type"=>"Generic"],"democrat"=>["server"=>"unitedtld.com","type"=>"Generic"],"dentist"=>["server"=>"unitedtld.com","type"=>"Generic"],"desi"=>["server"=>"ksregistry.net","type"=>"Generic"],"diamonds"=>["server"=>"donuts.co","type"=>"Generic"],"digital"=>["server"=>"donuts.co","type"=>"Generic"],
"direct"=>["server"=>"donuts.co","type"=>"Generic"],"directory"=>["server"=>"donuts.co","type"=>"Generic"],"discount"=>["server"=>"donuts.co","type"=>"Generic"],"dk"=>["server"=>"dk-hostmaster.dk","country"=>"Denmark","type"=>"Country Code"],"dm"=>["server"=>"nic.dm","country"=>"Dominica","type"=>"Country Code"],"domains"=>["server"=>"donuts.co","type"=>"Generic"],"durban"=>["server"=>"durban-registry.net.za","type"=>"Generic"],"dz"=>["server"=>"nic.dz","country"=>"Algeria","type"=>"Country Code"],"edu"=>["server"=>"educause.edu","type"=>"Sponsored"],"ec"=>["server"=>"nic.ec","country"=>"Ecuador","type"=>"Country Code"],"education"=>["server"=>"donuts.co","type"=>"Generic"],"ee"=>["server"=>"tld.ee","country"=>"Estonia","type"=>"Country Code"],"engineer"=>["server"=>"rightside.co","type"=>"Generic"],"email"=>["server"=>"donuts.co","type"=>"Generic"],"engineering"=>["server"=>"donuts.co","type"=>"Generic"],"enterprises"=>["server"=>"donuts.co","type"=>"Generic"],"equipment"=>["server"=>"donuts.co","type"=>"Generic"],"estate"=>["server"=>"donuts.co","type"=>"Generic"],"es"=>["server"=>"nic.es","country"=>"Spain","type"=>"Country Code"],"eu"=>["server"=>"eu","country"=>"Europe","type"=>"Country Code"],"eus"=>["server"=>"eus.coreregistry.net","type"=>"Generic"],"events"=>["server"=>"donuts.co","type"=>"Generic"],"exchange"=>["server"=>"donuts.co","type"=>"Generic"],"expert"=>["server"=>"donuts.co","type"=>"Generic"],"exposed"=>["server"=>"donuts.co","type"=>"Generic"],"fail"=>["server"=>"donuts.co","type"=>"Generic"],"farm"=>["server"=>"donuts.co","type"=>"Generic"],"feedback"=>["server"=>"centralnic.com","type"=>"Generic"],"fi"=>["server"=>"ficora.fi","country"=>"Finland","type"=>"Country Code"],"finance"=>["server"=>"donuts.co","type"=>"Generic"],
"financial"=>["server"=>"donuts.co","type"=>"Generic"],"fish"=>["server"=>"donuts.co","type"=>"Generic"],"fitness"=>["server"=>"donuts.co","type"=>"Generic"],"fishing"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"flights"=>["server"=>"donuts.co","type"=>"Generic"],"florist"=>["server"=>"donuts.co","type"=>"Generic"],"fo"=>["server"=>"nic.fo","country"=>"Faroe Islands","type"=>"Country Code"],"foo"=>["server"=>"domain-registry-l.google.com","type"=>"Generic"],"foundation"=>["server"=>"donuts.co","type"=>"Generic"],"fr"=>["server"=>"nic.fr","country"=>"France","type"=>"Country Code"],"frogans"=>["server"=>"-frogans.nic.fr","type"=>"Generic"],"fund"=>["server"=>"donuts.co","type"=>"Generic"],"furniture"=>["server"=>"donuts.co","type"=>"Generic"],"futbol"=>["server"=>"unitedtld.com","type"=>"Generic"],"gal"=>["server"=>"gal.coreregistry.net","type"=>"Generic"],"gallery"=>["server"=>"donuts.co","type"=>"Generic"],"gd"=>["server"=>"adamsnames.com","country"=>"Grenada","type"=>"Country Code"],"gent"=>["server"=>"nic.gent","type"=>"Generic"],"gg"=>["server"=>"gg","country"=>"Guernsey","type"=>"Country Code"],"gi"=>["server"=>"2.afilias-grs.net","country"=>"Gibraltar","type"=>"Country Code"],"gift"=>["server"=>"uniregistry.net","type"=>"Generic"],"gives"=>["server"=>"rightside.co","type"=>"Generic"],"gl"=>["server"=>"nic.gl","country"=>"Greenland","type"=>"Country Code"],"glass"=>["server"=>"donuts.co","type"=>"Generic"],"global"=>["server"=>"afilias-srs.net","type"=>"Generic"],"globo"=>["server"=>"gtlds.nic.br","type"=>"Generic"],"gop"=>["server"=>"-cl01.mm-registry.com","type"=>"Generic"],"gov"=>["server"=>"dotgov.gov","type"=>"Sponsored"],"graphics"=>["server"=>"donuts.co","type"=>"Generic"],
"gratis"=>["server"=>"donuts.co","type"=>"Generic"],"green"=>["server"=>"afilias.net","type"=>"Generic"],"gripe"=>["server"=>"donuts.co","type"=>"Generic"],"gs"=>["server"=>"nic.gs","country"=>"South Georgia And The South Sandwich Islands","type"=>"Country Code"],"guide"=>["server"=>"donuts.co","type"=>"Generic"],"guitars"=>["server"=>"uniregistry.net","type"=>"Generic"],"guru"=>["server"=>"donuts.co","type"=>"Generic"],"gy"=>["server"=>"registry.gy","country"=>"Guyana","type"=>"Country Code"],"hamburg"=>["server"=>"nic.hamburg","type"=>"Generic"],"haus"=>["server"=>"unitedtld.com","type"=>"Generic"],"healthcare"=>["server"=>"donuts.co","type"=>"Generic"],"hiphop"=>["server"=>"uniregistry.net","type"=>"Generic"],"hiv"=>["server"=>"afilias-srs.net","type"=>"Generic"],"hk"=>["server"=>"hkirc.hk","country"=>"Hong Kong","type"=>"Country Code"],"hn"=>["server"=>"2.afilias-grs.net","country"=>"Honduras","type"=>"Country Code"],"holdings"=>["server"=>"donuts.co","type"=>"Generic"],"holiday"=>["server"=>"donuts.co","type"=>"Generic"],"home"=>["server"=>"afilias-srs.net","type"=>"Generic"],"horse"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"host"=>["server"=>"centralnic.com","type"=>"Generic"],"house"=>["server"=>"donuts.co","type"=>"Generic"],"hr"=>["server"=>"dns.hr","country"=>"Croatia","type"=>"Country Code"],"ht"=>["server"=>"nic.ht","country"=>"Haiti","type"=>"Country Code"],"id"=>["server"=>"pandi.or.id","country"=>"Indonesia","type"=>"Country Code"],"ie"=>["server"=>"domainregistry.ie","country"=>"Ireland","type"=>"Country Code"],"il"=>["server"=>"isoc.org.il","country"=>"Israel","type"=>"Country Code"],"im"=>["server"=>"nic.im","country"=>"Isle of Man","type"=>"Country Code"],"immobilien"=>["server"=>"unitedtld.com","type"=>"Generic"],
"in"=>["server"=>"inregistry.net","country"=>"India","type"=>"Country Code"],"industries"=>["server"=>"donuts.co","type"=>"Generic"],"ink"=>["server"=>"centralnic.com","type"=>"Generic"],"institute"=>["server"=>"donuts.co","type"=>"Generic"],"insure"=>["server"=>"donuts.co","type"=>"Generic"],"int"=>["server"=>"iana.org","type"=>"Sponsored"],"international"=>["server"=>"donuts.co","type"=>"Generic"],"investments"=>["server"=>"donuts.co","type"=>"Generic"],"info"=>["server"=>"afilias.net","type"=>"Generic"],"io"=>["server"=>"nic.io","country"=>"British Indian Ocean Territory","type"=>"Country Code"],"iq"=>["server"=>"cmc.iq","country"=>"Iraq","type"=>"Country Code"],"ir"=>["server"=>"nic.ir","country"=>"Islamic Republic Of Iran","type"=>"Country Code"],"is"=>["server"=>"isnic.is","country"=>"Iceland","type"=>"Country Code"],"it"=>["server"=>"nic.it","country"=>"Italy","type"=>"Country Code"],"je"=>["server"=>"je","country"=>"Jersey","type"=>"Country Code"],"jobs"=>["server"=>"jobsverisign-grs.com","type"=>"Sponsored"],"joburg"=>["server"=>"joburg-registry.net.za","type"=>"Generic"],"jp"=>["server"=>"jprs.jp","country"=>"Japan","type"=>"Country Code"],"juegos"=>["server"=>"uniregistry.net","type"=>"Generic"],"kaufen"=>["server"=>"unitedtld.com","type"=>"Generic"],"ke"=>["server"=>"kenic.or.ke","country"=>"Kenia","type"=>"Country Code"],"kg"=>["server"=>"domain.kg","country"=>"Kyrgyzstan","type"=>"Country Code"],"ki"=>["server"=>"nic.ki","country"=>"Kiribati","type"=>"Country Code"],"kim"=>["server"=>"afilias.net","type"=>"Generic"],"kitchen"=>["server"=>"donuts.co","type"=>"Generic"],"kiwi"=>["server"=>"dot-kiwi.com","type"=>"Generic"],"koeln"=>["server"=>"-fe1.pdt.koeln.tango.knipp.de","type"=>"Generic"],"krd"=>["server"=>"aridnrs.net.au","type"=>"Generic"],
"kr"=>["server"=>"kr","country"=>"Republic Of Korea","type"=>"Country Code"],"kz"=>["server"=>"nic.kz","country"=>"Kazakhstan","type"=>"Country Code"],"la"=>["server"=>"nic.la","country"=>"People\\u2019s Democratic Republic Lao","type"=>"Country Code"],"lacaixa"=>["server"=>"nic.lacaixa","type"=>"Generic"],"land"=>["server"=>"donuts.co","type"=>"Generic"],"lawyer"=>["server"=>"rightside.co","type"=>"Generic"],"lease"=>["server"=>"donuts.co","type"=>"Generic"],"lgbt"=>["server"=>"afilias.net","type"=>"Generic"],"li"=>["server"=>"nic.li","country"=>"Liechtenstein","type"=>"Country Code"],"life"=>["server"=>"donuts.co","type"=>"Generic"],"lighting"=>["server"=>"donuts.co","type"=>"Generic"],"limited"=>["server"=>"donuts.co","type"=>"Generic"],"limo"=>["server"=>"donuts.co","type"=>"Generic"],"link"=>["server"=>"uniregistry.net","type"=>"Generic"],"loans"=>["server"=>"donuts.co","type"=>"Generic"],"london"=>["server"=>"-lon.mm-registry.com","type"=>"Generic"],"lotto"=>["server"=>"afilias.net","type"=>"Generic"],"lt"=>["server"=>"domreg.lt","country"=>"Lithuania","type"=>"Country Code"],"lu"=>["server"=>"dns.lu","country"=>"Luxembourg","type"=>"Country Code"],"luxe"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"luxury"=>["server"=>"nic.luxury","type"=>"Generic"],"lv"=>["server"=>"nic.lv","country"=>"Latvia","type"=>"Country Code"],"ly"=>["server"=>"nic.ly","country"=>"Libya","type"=>"Country Code"],"ma"=>["server"=>"iam.net.ma","country"=>"Morocco","type"=>"Country Code"],"maison"=>["server"=>"donuts.co","type"=>"Generic"],"management"=>["server"=>"donuts.co","type"=>"Generic"],"mango"=>["server"=>"mango.coreregistry.net","type"=>"Generic"],"market"=>["server"=>"rightside.co","type"=>"Generic"],"marketing"=>["server"=>"donuts.co","type"=>"Generic"],
"md"=>["server"=>"nic.md","country"=>"Republic Of Moldova","type"=>"Country Code"],"me"=>["server"=>"nic.me","country"=>"Montenegro","type"=>"Country Code"],"media"=>["server"=>"donuts.co","type"=>"Generic"],"meet"=>["server"=>"afilias.net","type"=>"Generic"],"melbourne"=>["server"=>"aridnrs.net.au","type"=>"Generic"],"menu"=>["server"=>"nic.menu","type"=>"Generic"],"mg"=>["server"=>"nic.mg","country"=>"Madagascar","type"=>"Country Code"],"miami"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"mini"=>["server"=>"ksregistry.net","type"=>"Generic"],"mk"=>["server"=>"marnet.mk","country"=>"The Former Yugoslav Republic Of Macedonia","type"=>"Country Code"],"ml"=>["server"=>"dot.ml","country"=>"Mali","type"=>"Country Code"],"mn"=>["server"=>"nic.mn","country"=>"Mongolia","type"=>"Country Code"],"mo"=>["server"=>"monic.mo","country"=>"Macao","type"=>"Country Code"],"mobi"=>["server"=>"dotmobiregistry.net","type"=>"Generic"],"moda"=>["server"=>"unitedtld.com","type"=>"Generic"],"monash"=>["server"=>"nic.monash","type"=>"Generic"],"mortgage"=>["server"=>"rightside.co","type"=>"Generic"],"moscow"=>["server"=>"nic.moscow","type"=>"Generic"],"motorcycles"=>["server"=>"afilias-srs.net","type"=>"Generic"],"mp"=>["server"=>"nic.mp","country"=>"Northern Mariana Islands","type"=>"Country Code"],"ms"=>["server"=>"nic.ms","country"=>"Montserrat","type"=>"Country Code"],"mu"=>["server"=>"nic.mu","country"=>"Mauritius","type"=>"Country Code"],"museum"=>["server"=>"museum","type"=>"Sponsored"],"mx"=>["server"=>"mx","country"=>"Mexico","type"=>"Country Code"],"my"=>["server"=>"mynic.my","country"=>"Malaysia","type"=>"Country Code"],"na"=>["server"=>"na-nic.com.na","country"=>"Namibia","type"=>"Country Code"],"name"=>["server"=>"nic.name","type"=>"Generic Restricted"],
"navy"=>["server"=>"rightside.co","type"=>"Generic"],"nc"=>["server"=>"nc","country"=>"New Caledonia","type"=>"Country Code"],"net"=>["server"=>"verisign-grs.com","type"=>"Generic"],"nf"=>["server"=>"nic.nf","country"=>"Norfolk Island","type"=>"Country Code"],"ng"=>["server"=>"nic.net.ng","country"=>"Nigeria","type"=>"Country Code"],"ngo"=>["server"=>"publicinterestregistry.net","type"=>"Generic"],"ninja"=>["server"=>"unitedtld.com","type"=>"Generic"],"nl"=>["server"=>"domain-registry.nl","country"=>"The Netherlands","type"=>"Country Code"],"no"=>["server"=>"norid.no","country"=>"Norway","type"=>"Country Code"],"nra"=>["server"=>"afilias-srs.net","type"=>"Generic"],"nrw"=>["server"=>"-fe1.pdt.nrw.tango.knipp.de","type"=>"Generic"],"nu"=>["server"=>"iis.nu","country"=>"Niue","type"=>"Country Code"],"nz"=>["server"=>"srs.net.nz","country"=>"New Zealand","type"=>"Country Code"],"om"=>["server"=>"registry.om","country"=>"Oman","type"=>"Country Code"],"onl"=>["server"=>"afilias-srs.net","type"=>"Generic"],"org"=>["server"=>"pir.org","type"=>"Generic"],"organic"=>["server"=>"afilias.net","type"=>"Generic"],"ovh"=>["server"=>"-ovh.nic.fr","type"=>"Generic"],"paris"=>["server"=>"-paris.nic.fr","type"=>"Generic"],"partners"=>["server"=>"donuts.co","type"=>"Generic"],"parts"=>["server"=>"donuts.co","type"=>"Generic"],"pe"=>["server"=>"kero.yachay.pe","country"=>"Peru","type"=>"Country Code"],"pf"=>["server"=>"registry.pf","country"=>"French Polynesia","type"=>"Country Code"],"photo"=>["server"=>"uniregistry.net","type"=>"Generic"],"photography"=>["server"=>"donuts.co","type"=>"Generic"],"photos"=>["server"=>"donuts.co","type"=>"Generic"],"physio"=>["server"=>"nic.physio","type"=>"Generic"],"pics"=>["server"=>"uniregistry.net","type"=>"Generic"],
"pictures"=>["server"=>"donuts.co","type"=>"Generic"],"pink"=>["server"=>"afilias.net","type"=>"Generic"],"pl"=>["server"=>"dns.pl","country"=>"Poland","type"=>"Country Code"],"place"=>["server"=>"donuts.co","type"=>"Generic"],"plumbing"=>["server"=>"donuts.co","type"=>"Generic"],"pm"=>["server"=>"nic.pm","country"=>"Saint Pierre and Miquelon","type"=>"Country Code"],"post"=>["server"=>"dotpostregistry.net","type"=>"Sponsored"],"pr"=>["server"=>"nic.pr","country"=>"Puerto Rico","type"=>"Country Code"],"press"=>["server"=>"centralnic.com","type"=>"Generic"],"pro"=>["server"=>"dotproregistry.net","type"=>"Generic-restricted"],"productions"=>["server"=>"donuts.co","type"=>"Generic"],"properties"=>["server"=>"donuts.co","type"=>"Generic"],"pt"=>["server"=>"dns.pt","country"=>"Portugal","type"=>"Country Code"],"pub"=>["server"=>"unitedtld.com","type"=>"Generic"],"pw"=>["server"=>"nic.pw","country"=>"Palau","type"=>"Country Code"],"qa"=>["server"=>"registry.qa","country"=>"Qatar","type"=>"Country Code"],"quebec"=>["server"=>"quebec.rs.corenic.net","type"=>"Generic"],"re"=>["server"=>"nic.re","country"=>"R\\u00e9union","type"=>"Country Code"],"recipes"=>["server"=>"donuts.co","type"=>"Generic"],"red"=>["server"=>"afilias.net","type"=>"Generic"],"rehab"=>["server"=>"rightside.co","type"=>"Generic"],"reise"=>["server"=>"nic.reise","type"=>"Generic"],"reisen"=>["server"=>"donuts.co","type"=>"Generic"],"rentals"=>["server"=>"donuts.co","type"=>"Generic"],"repair"=>["server"=>"donuts.co","type"=>"Generic"],"report"=>["server"=>"donuts.co","type"=>"Generic"],"republican"=>["server"=>"rightside.co","type"=>"Generic"],"rest"=>["server"=>"centralnic.com","type"=>"Generic"],"review"=>["server"=>"unitedtld.com","type"=>"Generic"],"rich"=>["server"=>"afilias-srs.net","type"=>"Generic"],
"rio"=>["server"=>"gtlds.nic.br","type"=>"Generic"],"ro"=>["server"=>"rotld.ro","country"=>"Romania","type"=>"Country Code"],"rocks"=>["server"=>"unitedtld.com","type"=>"Generic"],"rodeo"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"rs"=>["server"=>"rnids.rs","country"=>"Serbia","type"=>"Country Code"],"ru"=>["server"=>"tcinet.ru","country"=>"Russian Federation","type"=>"Country Code"],"ruhr"=>["server"=>"nic.ruhr","type"=>"Generic"],"sa"=>["server"=>"nic.net.sa","country"=>"Saudi Arabia","type"=>"Country Code"],"saarland"=>["server"=>"ksregistry.net","type"=>"Generic"],"sb"=>["server"=>"nic.net.sb","country"=>"Solomon Islands","type"=>"Country Code"],"sc"=>["server"=>"2.afilias-grs.net","country"=>"Seychelles","type"=>"Country Code"],"scb"=>["server"=>"nic.scb","type"=>"Generic"],"schmidt"=>["server"=>"nic.schmidt","type"=>"Generic"],"schule"=>["server"=>"donuts.co","type"=>"Generic"],"scot"=>["server"=>"scot.coreregistry.net","type"=>"Generic"],"se"=>["server"=>"iis.se","country"=>"Sweden","type"=>"Country Code"],"services"=>["server"=>"donuts.co","type"=>"Generic"],"sexy"=>["server"=>"uniregistry.net","type"=>"Generic"],"sg"=>["server"=>"sgnic.sg","country"=>"Singapore","type"=>"Country Code"],"sh"=>["server"=>"nic.sh","country"=>"Ascension And Tristan Da Cunha Saint Helena","type"=>"Country Code"],"shiksha"=>["server"=>"afilias.net","type"=>"Generic"],"shoes"=>["server"=>"donuts.co","type"=>"Generic"],"si"=>["server"=>"arnes.si","country"=>"Slovenia","type"=>"Country Code"],"singles"=>["server"=>"donuts.co","type"=>"Generic"],"sk"=>["server"=>"sk-nic.sk","country"=>"Slovakia","type"=>"Country Code"],"sm"=>["server"=>"nic.sm","country"=>"San Marino","type"=>"Country Code"],"sn"=>["server"=>"nic.sn","country"=>"Senegal","type"=>"Country Code"],
"so"=>["server"=>"nic.so","country"=>"Somalia","type"=>"Country Code"],"social"=>["server"=>"unitedtld.com","type"=>"Generic"],"software"=>["server"=>"rightside.co","type"=>"Generic"],"solar"=>["server"=>"donuts.co","type"=>"Generic"],"solutions"=>["server"=>"donuts.co","type"=>"Generic"],"soy"=>["server"=>"domain-registry-l.google.com","type"=>"Generic"],"space"=>["server"=>"nic.space","type"=>"Generic"],"spiegel"=>["server"=>"ksregistry.net","type"=>"Generic"],"st"=>["server"=>"nic.st","country"=>"Sao Tome And Principe","type"=>"Country Code"],"su"=>["server"=>"tcinet.ru","country"=>"Soviet Union","type"=>"Country Code"],"supplies"=>["server"=>"donuts.co","type"=>"Generic"],"supply"=>["server"=>"donuts.co","type"=>"Generic"],"support"=>["server"=>"donuts.co","type"=>"Generic"],"surf"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"surgery"=>["server"=>"donuts.co","type"=>"Generic"],"sx"=>["server"=>"sx","country"=>"Sint Maarten","type"=>"Country Code"],"sy"=>["server"=>"tld.sy","country"=>"Syrian Arab Republic","type"=>"Country Code"],"systems"=>["server"=>"donuts.co","type"=>"Generic"],"tattoo"=>["server"=>"uniregistry.net","type"=>"Generic"],"tax"=>["server"=>"donuts.co","type"=>"Generic"],"tc"=>["server"=>"meridiantld.net","country"=>"Turks And Caicos Islands","type"=>"Country Code"],"technology"=>["server"=>"donuts.co","type"=>"Generic"],"tel"=>["server"=>"nic.tel","type"=>"Sponsored"],"tf"=>["server"=>"nic.tf","country"=>"French Southern and Antarctic Lands","type"=>"Country Code"],"th"=>["server"=>"thnic.co.th","country"=>"Thailand","type"=>"Country Code"],"tienda"=>["server"=>"donuts.co","type"=>"Generic"],"tips"=>["server"=>"donuts.co","type"=>"Generic"],"tirol"=>["server"=>"nic.tirol","type"=>"Generic"],
"tk"=>["server"=>"dot.tk","country"=>"Tokelau","type"=>"Country Code"],"tl"=>["server"=>"nic.tl","country"=>"Timor-leste","type"=>"Country Code"],"tm"=>["server"=>"nic.tm","country"=>"Turkmenistan","type"=>"Country Code"],"tn"=>["server"=>"ati.tn","country"=>"Tunisia","type"=>"Country Code"],"to"=>["server"=>"tonic.to","country"=>"Tonga","type"=>"Country Code"],"today"=>["server"=>"donuts.co","type"=>"Generic"],"tools"=>["server"=>"donuts.co","type"=>"Generic"],"top"=>["server"=>"nic.top","type"=>"Generic"],"town"=>["server"=>"donuts.co","type"=>"Generic"],"toys"=>["server"=>"donuts.co","type"=>"Generic"],"tr"=>["server"=>"nic.tr","country"=>"Turkey","type"=>"Country Code"],"training"=>["server"=>"donuts.co","type"=>"Generic"],"travel"=>["server"=>"nic.travel","type"=>"Sponsored"],"tv"=>["server"=>"tvverisign-grs.com","country"=>"Tuvalu","type"=>"Country Code"],"tw"=>["server"=>"twnic.net.tw","country"=>"Taiwan","type"=>"Country Code"],"tz"=>["server"=>"tznic.or.tz","country"=>"United Republic Of Tanzania","type"=>"Country Code"],"ua"=>["server"=>"ua","country"=>"Ukraine","type"=>"Country Code"],"ug"=>["server"=>"co.ug","country"=>"Uganda","type"=>"Country Code"],"uk"=>["server"=>"nic.uk","country"=>"United Kingdom","type"=>"Country Code"],"university"=>["server"=>"donuts.co","type"=>"Generic"],"us"=>["server"=>"nic.us","country"=>"United States of America","type"=>"Country Code"],"uy"=>["server"=>"nic.org.uy","country"=>"Uruguay","type"=>"Country Code"],"uz"=>["server"=>"cctld.uz","country"=>"Uzbekistan","type"=>"Country Code"],"vacations"=>["server"=>"donuts.co","type"=>"Generic"],"vc"=>["server"=>"2.afilias-grs.net","country"=>"Saint Vincent And The Grenadines","type"=>"Country Code"],"ve"=>["server"=>"nic.ve","country"=>"Bolivarian Republic Of Venezuela","type"=>"Country Code"],
"vegas"=>["server"=>"afilias-srs.net","type"=>"Generic"],"ventures"=>["server"=>"donuts.co","type"=>"Generic"],"versicherung"=>["server"=>"nic.versicherung","type"=>"Generic"],"vet"=>["server"=>"rightside.co","type"=>"Generic"],"vg"=>["server"=>"ccksregistry.net","country"=>"British Virgin Islands","type"=>"Country Code"],"viajes"=>["server"=>"donuts.co","type"=>"Generic"],"villas"=>["server"=>"donuts.co","type"=>"Generic"],"vision"=>["server"=>"donuts.co","type"=>"Generic"],"vlaanderen"=>["server"=>"nic.vlaanderen","type"=>"Generic"],"vodka"=>["server"=>"-dub.mm-registry.com","type"=>"Generic"],"vote"=>["server"=>"afilias.net","type"=>"Generic"],"voting"=>["server"=>"voting.tld-box.at","type"=>"Generic"],"voto"=>["server"=>"afilias.net","type"=>"Generic"],"voyage"=>["server"=>"donuts.co","type"=>"Generic"],"vu"=>["server"=>"vunic.vu","country"=>"Vanuatu","type"=>"Country Code"],"wales"=>["server"=>"nic.wales","type"=>"Generic"],"wang"=>["server"=>"gtld.knet.cn","type"=>"Generic"],"watch"=>["server"=>"donuts.co","type"=>"Generic"],"website"=>["server"=>"nic.website","type"=>"Generic"],"wed"=>["server"=>"nic.wed","type"=>"Generic"],"wf"=>["server"=>"nic.wf","country"=>"Wallis and Futuna Islands","type"=>"Country Code"],"wien"=>["server"=>"nic.wien","type"=>"Generic"],"wiki"=>["server"=>"nic.wiki","type"=>"Generic"],"works"=>["server"=>"donuts.co","type"=>"Generic"],"ws"=>["server"=>"website.ws","country"=>"Samoa","type"=>"Country Code"],"wtc"=>["server"=>"nic.wtc","type"=>"Generic"],"wtf"=>["server"=>"donuts.co","type"=>"Generic"],"xxx"=>["server"=>"nic.xxx","type"=>"Sponsored"],"xyz"=>["server"=>"nic.xyz","type"=>"Generic"],"yachts"=>["server"=>"afilias-srs.net","type"=>"Generic"],"yt"=>["server"=>"nic.yt","country"=>"Mayotte","type"=>"Country Code"],"zm"=>["server"=>"nic.zm","country"=>"Zambia","type"=>"Country Code"],"zone"=>["server"=>"donuts.co","type"=>"Generic"]];
if($s)foreach($d as $k=>$v){
$i=explode('.',$v['server']);
if($i[2])unset($i[0]);
$d[$k]['server']=implode('.',$i);
}return $d;
}function hashalogs(){
return [["name"=>"md2","length"=>32,"hash"=>"8350e5a3e24c153df2275c9f80692773"],["name"=>"md4","length"=>32,"hash"=>"31d6cfe0d16ae931b73c59d7e0c089c0"],["name"=>"md5","length"=>32,"hash"=>"d41d8cd98f00b204e9800998ecf8427e"],["name"=>"sha1","length"=>40,"hash"=>"da39a3ee5e6b4b0d3255bfef95601890afd80709"],["name"=>"sha224","length"=>56,"hash"=>"d14a028c2a3a2bc9476102bb288234c415a2b01f828ea62ac5b3e42f"],["name"=>"sha256","length"=>64,"hash"=>"e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855"],["name"=>"sha384","length"=>96,"hash"=>"38b060a751ac96384cd9327eb1b1e36a21fdb71114be07434c0cc7bf63f6e1da274edebfe76f65fbd51ad2f14898b95b"],["name"=>"sha512\/224","length"=>56,"hash"=>"6ed0dd02806fa89e25de060c19d3ac86cabb87d6a0ddd05c333b84f4"],["name"=>"sha512\/256","length"=>64,"hash"=>"c672b8d1ef56ed28ab87c3622c5114069bdd3ad7b8f9737498d0c01ecef0967a"],["name"=>"sha512","length"=>128,"hash"=>"cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e"],["name"=>"sha3-224","length"=>56,"hash"=>"6b4e03423667dbb73b6e15454f0eb1abd4597f9a1b078e3f5b5a6bc7"],["name"=>"sha3-256","length"=>64,"hash"=>"a7ffc6f8bf1ed76651c14756a061d662f580ff4de43b49fa82d80a4b80f8434a"],["name"=>"sha3-384","length"=>96,"hash"=>"0c63a75b845e4f7d01107d852e4c2485c51a50aaaa94fc61995e71bbee983a2ac3713831264adb47fb6bd1e058d5f004"],["name"=>"sha3-512","length"=>128,"hash"=>"a69f73cca23a9ac5c8b567dc185a756e97c982164fe25859e0d1dcc1475c80a615b2123af1f5f94c11e3e9402c3ac558f500199d95b6d3e301758586281dcd26"],["name"=>"ripemd128","length"=>32,"hash"=>"cdf26213a150dc3ecb610f18f6b38b46"],["name"=>"ripemd160","length"=>40,"hash"=>"9c1185a5c5e9fc54612808977ee8f548b2258d31"],
["name"=>"ripemd256","length"=>64,"hash"=>"02ba4c4e5f8ecd1877fc52d64d30e37a2d9774fb1e5d026380ae0168e3c5522d"],["name"=>"ripemd320","length"=>80,"hash"=>"22d65d5661536cdc75c1fdf5c6de7b41b9f27325ebc61e8557177d705a0ec880151c3a32a00899b8"],["name"=>"whirlpool","length"=>128,"hash"=>"19fa61d75522a4669b44e39c1d2e1726c530232130d407f89afee0964997f7a73e83be698b288febcf88e3e03c4f0757ea8964e59b63d93708b138cc42a66eb3"],["name"=>"tiger128,3","length"=>32,"hash"=>"3293ac630c13f0245f92bbb1766e1616"],["name"=>"tiger160,3","length"=>40,"hash"=>"3293ac630c13f0245f92bbb1766e16167a4e5849"],["name"=>"tiger192,3","length"=>48,"hash"=>"3293ac630c13f0245f92bbb1766e16167a4e58492dde73f3"],["name"=>"tiger128,4","length"=>32,"hash"=>"24cc78a7f6ff3546e7984e59695ca13d"],["name"=>"tiger160,4","length"=>40,"hash"=>"24cc78a7f6ff3546e7984e59695ca13d804e0b68"],["name"=>"tiger192,4","length"=>48,"hash"=>"24cc78a7f6ff3546e7984e59695ca13d804e0b686e255194"],["name"=>"snefru","length"=>64,"hash"=>"8617f366566a011837f4fb4ba5bedea2b892f3ed8b894023d16ae344b2be5881"],["name"=>"snefru256","length"=>64,"hash"=>"8617f366566a011837f4fb4ba5bedea2b892f3ed8b894023d16ae344b2be5881"],["name"=>"gost","length"=>64,"hash"=>"ce85b99cc46752fffee35cab9a7b0278abb4c2d2055cff685af4912c49490f8d"],["name"=>"gost-crypto","length"=>64,"hash"=>"981e5f3ca30c841487830f84fb433e13ac1101569b9c13584ac483234cd656c0"],["name"=>"adler32","length"=>8,"hash"=>"00000001"],["name"=>"crc32","length"=>8,"hash"=>"00000000"],["name"=>"crc32b","length"=>8,"hash"=>"00000000"],["name"=>"fnv132","length"=>8,"hash"=>"811c9dc5"],["name"=>"fnv1a32","length"=>8,"hash"=>"811c9dc5"],["name"=>"fnv164","length"=>16,"hash"=>"cbf29ce484222325"],["name"=>"fnv1a64","length"=>16,"hash"=>"cbf29ce484222325"],
["name"=>"joaat","length"=>8,"hash"=>"00000000"],["name"=>"haval128,3","length"=>32,"hash"=>"c68f39913f901f3ddf44c707357a7d70"],["name"=>"haval160,3","length"=>40,"hash"=>"d353c3ae22a25401d257643836d7231a9a95f953"],["name"=>"haval192,3","length"=>48,"hash"=>"e9c48d7903eaf2a91c5b350151efcb175c0fc82de2289a4e"],["name"=>"haval224,3","length"=>56,"hash"=>"c5aae9d47bffcaaf84a8c6e7ccacd60a0dd1932be7b1a192b9214b6d"],["name"=>"haval256,3","length"=>64,"hash"=>"4f6938531f0bc8991f62da7bbd6f7de3fad44562b8c6f4ebf146d5b4e46f7c17"],["name"=>"haval128,4","length"=>32,"hash"=>"ee6bbf4d6a46a679b3a856c88538bb98"],["name"=>"haval160,4","length"=>40,"hash"=>"1d33aae1be4146dbaaca0b6e70d7a11f10801525"],["name"=>"haval192,4","length"=>48,"hash"=>"4a8372945afa55c7dead800311272523ca19d42ea47b72da"],["name"=>"haval224,4","length"=>56,"hash"=>"3e56243275b3b81561750550e36fcd676ad2f5dd9e15f2e89e6ed78e"],["name"=>"haval256,4","length"=>64,"hash"=>"c92b2e23091e80e375dadce26982482d197b1a2521be82da819f8ca2c579b99b"],["name"=>"haval128,5","length"=>32,"hash"=>"184b8482a0c050dca54b59c7f05bf5dd"],["name"=>"haval160,5","length"=>40,"hash"=>"255158cfc1eed1a7be7c55ddd64d9790415b933b"],["name"=>"haval192,5","length"=>48,"hash"=>"4839d0626f95935e17ee2fc4509387bbe2cc46cb382ffe85"],["name"=>"haval224,5","length"=>56,"hash"=>"4a0513c032754f5582a758d35917ac9adf3854219b39e3ac77d1837e"],["name"=>"haval256,5","length"=>64,"hash"=>"be417bb4dd5cfb76c7126f4f8eeb1553a449039307b1a3cd451dbfdc0fbbe330"]];
}function protocolscode(){
return ["ip","icmp","igmp","ggp","ipencap","st","tcp","cbt","egp","igp","bbn-rcc","nvp","pup","argus","emcon","xnet","chaos","udp","mux","dcn","hmp","prm","xns-idp","trunk-1","trunk-2","leaf-1","leaf-2","rdp","irtp","iso-tp4","netblt","mfe-nsp","merit-inp","dccp","3pc","idpr","xtp","ddp","idpr-cmtp","tp++","il","ipv6","sdrp","ipv6-route","ipv6-frag","idrp","rsvp","gre","dsr","bna","esp","ah","i-nlsp","swipe","narp","mobile","tlsp","skip","ipv6-icmp","ipv6-nonxt","ipv6-opts",62=>"cftp",64=>"sat-expak",65=>"kryptolan",66=>"rvd",67=>"ippc",69=>"sat-mon",70=>"visa",71=>"ipcv",72=>"cpnx",73=>"cphb",74=>"wsn",75=>"pvp",76=>"br-sat-mon",77=>"sun-nd",78=>"wb-mon",79=>"wb-expak",80=>"iso-ip",81=>"vmtp",82=>"secure-vmtp",83=>"vines",84=>"ttp",85=>"nsfnet-igp",86=>"dgp",87=>"tcf",88=>"eigrp",89=>"ospf",90=>"sprite-rpc",91=>"larp",92=>"mtp",93=>"ax.25",94=>"ipip",95=>"micp",96=>"scc-sp",97=>"etherip",98=>"encap",100=>"gmtp",101=>"ifmp",102=>"pnni",103=>"pim",104=>"aris",105=>"scps",106=>"qnx",107=>"a\/n",108=>"ipcomp",109=>"snp",110=>"compaq-peer",111=>"ipx-in-ip",112=>"vrrp",113=>"pgm",115=>"l2tp",116=>"ddx",117=>"iatp",118=>"stp",119=>"srp",120=>"uti",121=>"smp",122=>"sm",123=>"ptp",124=>"isis",125=>"fire",126=>"crtp",127=>"crdup",128=>"sscopmce",129=>"iplt",130=>"sps",131=>"pipe",132=>"sctp",133=>"fc",134=>"rsvp-e2e-ignore",136=>"udplite",137=>"mpls-in-ip",138=>"manet",139=>"hip",140=>"shim6"];
}function httpstatecodes(){
return [["code"=>"1xx","title"=>"Informational responses","description"=>"An informational response indicates that the request was received and understood. It is issued on a provisional basis while request processing continues. It alerts the client to wait for a final response. The message consists only of the status line and optional header fields, and is terminated by an empty line. As the HTTP\/1.0 standard did not define any 1xx status codes, servers must not[note 1] send a 1xx response to an HTTP\/1.0 compliant client except under experimental conditions.","codes"=>[["code"=>"100","title"=>"Continue","description"=>"The server has received the request headers and the client should proceed to send the request body (in the case of a request for which a body needs to be sent; for example, a POST request). Sending a large request body to a server after a request has been rejected for inappropriate headers would be inefficient. To have a server check the request's headers, a client must send Expect: 100-continue as a header in its initial request and receive a 100 Continue status code in response before sending the body. If the client receives an error code such as 403 (Forbidden) or 405 (Method Not Allowed) then it shouldn't send the request's body. The response 417 Expectation Failed indicates that the request should be repeated without the Expect header as it indicates that the server doesn't support expectations (this is the case, for example, of HTTP\/1.0 servers)."],["code"=>"101","title"=>"Switching Protocols","description"=>"The requester has asked the server to switch protocols and the server has agreed to do so."],["code"=>"102","title"=>"Processing","description"=>"A WebDAV request may contain many sub-requests involving file operations, requiring a long time to complete the request. This code indicates that the server has received and is processing the request, but no response is available yet. This prevents the client from timing out and assuming the request was lost."],["code"=>"103","title"=>"Early Hints","description"=>"Used to return some response headers before file HTTP message."]]],
["code"=>"2xx","title"=>"Success","description"=>"This class of status codes indicates the action requested by the client was received, understood and accepted.","codes"=>[["code"=>"200","title"=>"OK","description"=>"Standard response for successful HTTP requests. The actual response will depend on the request method used. In a GET request, the response will contain an entity corresponding to the requested resource. In a POST request, the response will contain an entity describing or containing the result of the action."],["code"=>"201","title"=>"Created","description"=>"The request has been fulfilled, resulting in the creation of a new resource."],["code"=>"202","title"=>"Accepted","description"=>"The request has been accepted for processing, but the processing has not been completed. The request might or might not be eventually acted upon, and may be disallowed when processing occurs."],["code"=>"203","title"=>"Non-Authoritative Information","description"=>"The server is a transforming proxy (e.g. a Web accelerator) that received a 200 OK from its origin, but is returning a modified version of the origin's response."],["code"=>"204","title"=>"No Content","description"=>"The server successfully processed the request and is not returning any content."],["code"=>"205","title"=>"Reset Content","description"=>"The server successfully processed the request, but is not returning any content. Unlike a 204 response, this response requires that the requester reset the document view."],["code"=>"206","title"=>"Partial Content","description"=>"The server is delivering only part of the resource (byte serving) due to a range header sent by the client. The range header is used by HTTP clients to enable resuming of interrupted downloads, or split a download into multiple simultaneous streams."],["code"=>"207","title"=>"Multi-Status","description"=>"The message body that follows is by default an XML message and can contain a number of separate response codes, depending on how many sub-requests were made."],["code"=>"208","title"=>"Already Reported","description"=>"The members of a DAV binding have already been enumerated in a preceding part of the (multistatus) response, and are not being included again."],["code"=>"226","title"=>"IM Used","description"=>"The server has fulfilled a request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance."]]],
["code"=>"3xx","title"=>"Redirection","description"=>"This class of status code indicates the client must take additional action to complete the request. Many of these status codes are used in URL redirection.","codes"=>[["code"=>"300","title"=>"Multiple Choices","description"=>"Indicates multiple options for the resource from which the client may choose (via agent-driven content negotiation). For example, this code could be used to present multiple video format options, to list files with different filename extensions, or to suggest word-sense disambiguation."],["code"=>"301","title"=>"Moved Permanently","description"=>"This and all future requests should be directed to the given URI."],["code"=>"302","title"=>"Found","description"=>"This is an example of industry practice contradicting the standard. The HTTP\/1.0 specification (RFC 1945) required the client to perform a temporary redirect (the original describing phrase was \"Moved Temporarily\"), but popular browsers implemented 302 with the functionality of a 303 See Other. Therefore, HTTP\/1.1 added status codes 303 and 307 to distinguish between the two behaviours. However, some Web applications and frameworks use the 302 status code as if it were the 303."],["code"=>"303","title"=>"See Other","description"=>"The response to the request can be found under another URI using the GET method. When received in response to a POST (or PUT\/DELETE), the client should presume that the server has received the data and should issue a new GET request to the given URI."],["code"=>"304","title"=>"Not Modified","description"=>"Indicates that the resource has not been modified since the version specified by the request headers If-Modified-Since or If-None-Match. In such case, there is no need to retransmit the resource since the client still has a previously-downloaded copy."],["code"=>"305","title"=>"Use Proxy","description"=>"The requested resource is available only through a proxy, the address for which is provided in the response. Many HTTP clients (such as Mozilla and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons."],["code"=>"306","title"=>"Switch Proxy","description"=>"No longer used. Originally meant \"Subsequent requests should use the specified proxy.\""],["code"=>"307","title"=>"Temporary Redirect","description"=>"In this case, the request should be repeated with another URI; however, future requests should still use the original URI. In contrast to how 302 was historically implemented, the request method is not allowed to be changed when reissuing the original request. For example, a POST request should be repeated using another POST request."],["code"=>"308","title"=>"Permanent Redirect","description"=>"The request and all future requests should be repeated using another URI. 307 and 308 parallel the behaviors of 302 and 301, but do not allow the HTTP method to change. So, for example, submitting a form to a permanently redirected resource may continue smoothly."]]],
["code"=>"4xx","title"=>"Client errors","description"=>"A user agent may carry out the additional action with no user interaction only if the method used in the second request is GET or HEAD. A user agent may automatically redirect a request. A user agent should detect and intervene to prevent cyclical redirects.","codes"=>[["code"=>"400","title"=>"Bad Request","description"=>"The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing)."],["code"=>"401","title"=>"Unauthorized","description"=>"Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. See Basic access authentication and Digest access authentication. 401 semantically means \"unauthenticated\", i.e. the user does not have the necessary credentials."],["code"=>"402","title"=>"Payment Required","description"=>"Note: Some sites issue HTTP 401 when an IP address is banned from the website (usually the website domain) and that specific address is refused permission to access a website."],["code"=>"403","title"=>"Forbidden","description"=>"Reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, as proposed for example by GNU Taler, but that has not yet happened, and this code is not usually used. Google Developers API uses this status if a particular developer has exceeded the daily limit on requests."],["code"=>"404","title"=>"Not Found","description"=>"The request was valid, but the server is refusing action. The user might not have the necessary permissions for a resource, or may need an account of some sort."],
["code"=>"405","title"=>"Method Not Allowed","description"=>"The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible."],["code"=>"406","title"=>"Not Acceptable","description"=>"A request method is not supported for the requested resource; for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource."],["code"=>"407","title"=>"Proxy Authentication Required","description"=>"The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request. See Content negotiation."],["code"=>"408","title"=>"Request Timeout","description"=>"The client must first authenticate itself with the proxy."],["code"=>"409","title"=>"Conflict","description"=>"The server timed out waiting for the request. According to HTTP specifications: \"The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time.\""],["code"=>"410","title"=>"Gone","description"=>"Indicates that the request could not be processed because of conflict in the request, such as an edit conflict between multiple simultaneous updates."],["code"=>"411","title"=>"Length Required","description"=>"Indicates that the resource requested is no longer available and will not be available again. This should be used when a resource has been intentionally removed and the resource should be purged. Upon receiving a 410 status code, the client should not request the resource in the future. Clients such as search engines should remove the resource from their indices. Most use cases do not require clients and search engines to purge the resource, and a \"404 Not Found\" may be used instead."],["code"=>"412","title"=>"Precondition Failed","description"=>"The request did not specify the length of its content, which is required by the requested resource."],["code"=>"413","title"=>"Payload Too Large","description"=>"The server does not meet one of the preconditions that the requester put on the request."],["code"=>"414","title"=>"URI Too Long","description"=>"The request is larger than the server is willing or able to process. Previously called \"Request Entity Too Large\"."],["code"=>"415","title"=>"Unsupported Media Type","description"=>"The URI provided was too long for the server to process. Often the result of too much data being encoded as a query-string of a GET request, in which case it should be converted to a POST request. Called \"Request-URI Too Long\" previously."],["code"=>"416","title"=>"Range Not Satisfiable","description"=>"The request entity has a media type which the server or resource does not support. For example, the client uploads an image as image\/svg+xml, but the server requires that images use a different format."],
["code"=>"417","title"=>"Expectation Failed","description"=>"The client has asked for a portion of the file (byte serving), but the server cannot supply that portion. For example, if the client asked for a part of the file that lies beyond the end of the file. Called \"Requested Range Not Satisfiable\" previously."],["code"=>"418","title"=>"I'm a teapot","description"=>"The server cannot meet the requirements of the Expect request-header field."],["code"=>"421","title"=>"Misdirected Request","description"=>"This code was defined in 1998 as one of the traditional IETF April Fools\\\r\n' jokes, in RFC 2324, Hyper Text Coffee Pot Control Protocol, and is not expected to be implemented by actual HTTP servers. The RFC specifies this code should be returned by teapots requested to brew coffee. This HTTP status is used as an Easter egg in some websites, including Google.com."],["code"=>"422","title"=>"Unprocessable Entity","description"=>"The request was directed at a server that is not able to produce a response. (for example because of a connection reuse)"],["code"=>"423","title"=>"Locked","description"=>"The request was well-formed but was unable to be followed due to semantic errors."],["code"=>"424","title"=>"Failed Dependency","description"=>"The resource that is being accessed is locked."],["code"=>"426","title"=>"Upgrade Required","description"=>"The request failed because it depended on another request and that request failed (e.g., a PROPPATCH)."],["code"=>"428","title"=>"Precondition Required","description"=>"The client should switch to a different protocol such as TLS\/1.0, given in the Upgrade header field."],["code"=>"429","title"=>"Too Many Requests","description"=>"The origin server requires the request to be conditional. Intended to prevent the 'lost update' problem, where a client GETs a resource's state, modifies it, and PUTs it back to the server, when meanwhile a third party has modified the state on the server, leading to a conflict.\""],["code"=>"431","title"=>"Request Header Fields Too Large","description"=>"The user has sent too many requests in a given amount of time. Intended for use with rate-limiting schemes."],["code"=>"451","title"=>"Unavailable For Legal Reasons","description"=>"The server is unwilling to process the request because either an individual header field, or all the header fields collectively, are too large."]]],
["code"=>"5xx","title"=>"Server errors","description"=>"This class of status code is intended for situations in which the error seems to have been caused by the client. Except when responding to a HEAD request, the server should include an entity containing an explanation of the error situation, and whether it is a temporary or permanent condition. These status codes are applicable to any request method. User agents should display any included entity to the user.","codes"=>[["code"=>"500","title"=>"Internal Server Error","description"=>"A generic error message, given when an unexpected condition was encountered and no more specific message is suitable."],["code"=>"501","title"=>"Not Implemented","description"=>"The server either does not recognize the request method, or it lacks the ability to fulfil the request. Usually this implies future availability (e.g., a new feature of a web-service API)."],["code"=>"502","title"=>"Bad Gateway","description"=>"The server was acting as a gateway or proxy and received an invalid response from the upstream server."],["code"=>"503","title"=>"Service Unavailable","description"=>"The server is currently unavailable (because it is overloaded or down for maintenance). Generally, this is a temporary state."],["code"=>"504","title"=>"Gateway Timeout","description"=>"The server was acting as a gateway or proxy and did not receive a timely response from the upstream server."],["code"=>"505","title"=>"HTTP Version Not Supported","description"=>"The server does not support the HTTP protocol version used in the request."],["code"=>"506","title"=>"Variant Also Negotiates","description"=>"Transparent content negotiation for the request results in a circular reference."],["code"=>"507","title"=>"Insufficient Storage","description"=>"The server is unable to store the representation needed to complete the request."],["code"=>"508","title"=>"Loop Detected","description"=>"The server detected an infinite loop while processing the request (sent in lieu of 208 Already Reported)."],["code"=>"510","title"=>"Not Extended","description"=>"Further extensions to the request are required for the server to fulfil it."],["code"=>"511","title"=>"Network Authentication Required","description"=>"The client needs to authenticate to gain network access. Intended for use by intercepting proxies used to control access to the network (e.g., \"captive portals\" used to require agreement to Terms of Service before granting full Internet access via a Wi-Fi hotspot)."]]],
["code"=>"Unofficial","title"=>"codes","description"=>"The server failed to fulfil a request.","codes"=>[["code"=>"103","title"=>"Checkpoint","description"=>"Used in the resumable requests proposal to resume aborted PUT or POST requests."],["code"=>"420","title"=>"Method Failure","description"=>"A deprecated response used by the Spring Framework when a method has failed."],["code"=>"420","title"=>"Enhance Your Calm","description"=>"Returned by version 1 of the Twitter Search and Trends API when the client is being rate limited; versions 1.1 and later use the 429 Too Many Requests response code instead."],["code"=>"450","title"=>"Blocked by Windows Parental Controls","description"=>"The Microsoft extension code indicated when Windows Parental Controls are turned on and are blocking access to the requested webpage."],["code"=>"498","title"=>"Invalid Token","description"=>"Returned by ArcGIS for Server. Code 498 indicates an expired or otherwise invalid token."],["code"=>"499","title"=>"Token Required","description"=>"Returned by ArcGIS for Server. Code 499 indicates that a token is required but was not submitted."],["code"=>"509","title"=>"Bandwidth Limit Exceeded","description"=>"The server has exceeded the bandwidth specified by the server administrator; this is often used by shared hosting providers to limit the bandwidth of customers."],["code"=>"530","title"=>"Site is frozen","description"=>"Used by the Pantheon web platform to indicate a site that has been frozen due to inactivity."],["code"=>"598","title"=>" Network read timeout error","description"=>"Used by some HTTP proxies to signal a network read timeout behind the proxy to a client in front of the proxy."]]],
["code"=>"See","title"=>"also","description"=>"Response status codes beginning with the digit \"5\" indicate cases in which the server is aware that it has encountered an error or is otherwise incapable of performing the request. Except when responding to a HEAD request, the server should include an entity containing an explanation of the error situation, and indicate whether it is a temporary or permanent condition. Likewise, user agents should display any included entity to the user. These response codes are applicable to any request method.","codes"=>[["code"=>"440","title"=>"Login Time-out","description"=>"The client's session has expired and must log in again."],["code"=>"449","title"=>"Retry With","description"=>"The server cannot honour the request because the user has not provided the required information."],["code"=>"451","title"=>"Redirect","description"=>"Used in Exchange ActiveSync when either a more efficient server is available or the server cannot access the users' mailbox. The client is expected to re-run the HTTP AutoDiscover operation to find a more appropriate server."]]],
["title"=>"Notes","description"=>"The following codes are not specified by any standard.","codes"=>[["code"=>"444","title"=>"No Response","description"=>"Used internally to instruct the server to return no information to the client and close the connection immediately."],["code"=>"494","title"=>"Request header too large","description"=>"Client sent too large request or too long header line."],["code"=>"495","title"=>"SSL Certificate Error","description"=>"An expansion of the 400 Bad Request response code, used when the client has provided an invalid client certificate."],["code"=>"496","title"=>"SSL Certificate Required","description"=>"An expansion of the 400 Bad Request response code, used when a client certificate is required but not provided."],["code"=>"497","title"=>"HTTP Request Sent to HTTPS Port","description"=>"An expansion of the 400 Bad Request response code, used when the client has made a HTTP request to a port listening for HTTPS requests."],["code"=>"499","title"=>"Client Closed Request","description"=>"Used when the client has closed the request before the server could send a response."]]],
["title"=>"References","description"=>"Microsoft's Internet Information Services web server expands the 4xx error space to signal errors with the client's request.","codes"=>[["code"=>"520","title"=>"Unknown Error","description"=>"The 520 error is used as a \"catch-all response for when the origin server returns something unexpected\", listing connection resets, large headers, and empty or invalid responses as common triggers."],["code"=>"521","title"=>"Web Server Is Down","description"=>"The origin server has refused the connection from Cloudflare."],["code"=>"522","title"=>"Connection Timed Out","description"=>"Cloudflare could not negotiate a TCP handshake with the origin server."],["code"=>"523","title"=>"Origin Is Unreachable","description"=>"Cloudflare could not reach the origin server; for example, if the DNS records for the origin server are incorrect."],["code"=>"524","title"=>"A Timeout Occurred","description"=>"Cloudflare was able to complete a TCP connection to the origin server, but did not receive a timely HTTP response."],["code"=>"525","title"=>"SSL Handshake Failed","description"=>"Cloudflare could not negotiate a SSL\/TLS handshake with the origin server."],["code"=>"526","title"=>"Invalid SSL Certificate","description"=>"Cloudflare could not validate the SSL\/TLS certificate that the origin server presented."],["code"=>"527","title"=>"Railgun Error","description"=>"Error 527 indicates that the request timed out or failed after the WAN connection had been established."]]],
["code"=>"External","title"=>"links","description"=>"The nginx web server software expands the 4xx error space to signal issues with the client's request."]];
}function HTTPsend($u,$d=[],$m="GET",$h=[]){
$m=strtolower($m);
$c=curl_init();
if($m=="put"){
curl_setopt($c,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($c,CURLOPT_POSTFIELDS,json_encode($d));
}if($m=="delete"){
curl_setopt($c,CURLOPT_CUSTOMREQUEST,"DELETE");
curl_setopt($c,CURLOPT_POSTFIELDS,json_encode($d));
}elseif($m=="get"){
$u=$u.'?'.http_build_query($d);
}elseif($m=="post"){
curl_setopt($c,CURLOPT_POST,true);
curl_setopt($c,CURLOPT_POSTFIELDS,$d);
}
curl_setopt($c,CURLOPT_URL,$u);
curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
curl_setopt($c,CURLOPT_USERAGENT,'White Web, web servise');
curl_setopt($c,CURLOPT_HTTPHEADER,$h);
$r=curl_exec($c);
curl_close($c);
return $r;
}
function licenseCheck($license,$pass){
$d=$_SERVER['HTTP_HOST'];
$curl=curl_init("https://license.socialhost.ml/valid.php");
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_POSTFIELDS,"domain=$d&key=$license&pass=$pass");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($c);
curl_close($c);
return $r;
}class AnimGif {
const DEFAULT_DURATION=10;
private $gif;
private $imgBuilt;
private $frameSources;
private $loop;
private $dis;
private $transparent_color;
private static $errors;
public function __construct(){
$this->reset();
self::$errors=array(
'ERR00'=>'Need at least 1 frames for an animation.',
'ERR01'=>'Resource is not a GIF image.',
'ERR02'=>'Only image resource variables, file paths, URLs or binary bitmap data are accepted.',
'ERR03'=>'Cannot make animation from animated GIF.',
'ERR04'=>'Loading from URLs is disabled by PHP.',
'ERR05'=>'Failed to load or invalid image (dir): "%s".',
);
}public function create($frames,$durations=self::DEFAULT_DURATION,$loop=0){
$last_duration=self::DEFAULT_DURATION;
$this->loop=($loop>-1)?$loop:0;
$this->dis=2;
if(!is_array($frames)){
$frames_dir=$frames;
if(@is_dir($frames_dir)){
if($frames=scandir($frames_dir)){
$frames=array_filter($frames,function($x){
return $x[0]!=".";
});
array_walk($frames,function(&$x, $i) use ($frames_dir){
$x="$frames_dir/$x";});
}}if(!is_array($frames)){
throw new \Exception(VERSION.': '.sprintf(self::$errors['ERR05'], $frames_dir));
}}assert(is_array($frames));
if(sizeof($frames)<1){
throw new \Exception(VERSION.': '.self::$errors['ERR00']);
}$i=0;
foreach($frames as $frame){
if(is_resource($frame)){
$resourceImg=$frame;
ob_start();
imagegif($frame);
$this->frameSources[]=ob_get_contents();
ob_end_clean();
if(substr($this->frameSources[$i],0,6)!='GIF87a'&&substr($this->frameSources[$i],0,6)!='GIF89a'){
throw new \Exception(VERSION.': '.$i.' '.self::$errors['ERR01']);
}}elseif(is_string($frame)){
if(@is_readable($frame)){
$bin=file_get_contents($frame);
}elseif(filter_var($frame,FILTER_VALIDATE_URL)){
if(ini_get('allow_url_fopen')){
$bin=@file_get_contents($frame);
}else{
throw new \Exception(VERSION.': '.$i.' '.self::$errors['ERR04']);
}}else{
$bin=$frame;
}if(!($bin&&($resourceImg=imagecreatefromstring($bin)))){
throw new \Exception(VERSION.': '.$i.' '.sprintf(self::$errors['ERR05'],substr($frame,0,200)));
}ob_start();
imagegif($resourceImg);
$this->frameSources[]=ob_get_contents();
ob_end_clean();
}else{
throw new \Exception(VERSION.': '.self::$errors['ERR02']);
}if($i==0){
$this->transparent_color=imagecolortransparent($resourceImg);
}for($j=(13+3*(2<<(ord($this->frameSources[$i]{10})&0x07))),$k=TRUE;$k;$j++){
switch($this->frameSources[$i]{$j}){
case '!':
if((substr($this->frameSources[$i],($j+3),8))=='NETSCAPE'){
throw new \Exception(VERSION.': '.self::$errors['ERR03'].' ('.($i+1).' source).');
}break;
case ';':
$k=false;
break;
}}unset($resourceImg);
++$i;
}$this->gifAddHeader();
for($i=0;$i<count($this->frameSources);$i++){
if(is_array($durations)){
$d=(empty($durations[$i])?$last_duration:$durations[$i]);
$last_duration=$d;
}else{
$d=$durations;
}$this->addGifFrames($i,$d);
}$this->gifAddFooter();
return $this;
}public function get(){
return $this->gif;
}public function save($filename){
return file_put_contents($filename,$this->gif);
}public function reset(){
$this->frameSources=null;
$this->gif='GIF89a';
$this->imgBuilt=false;
$this->loop=0;
$this->dis=2;
$this->transparent_color=-1;
}protected function gifAddHeader(){
$cmap=0;
if(ord($this->frameSources[0]{10})&0x80){
$cmap=3*(2<<(ord($this->frameSources[0]{10})&0x07));
$this->gif.=substr($this->frameSources[0],6,7);
$this->gif.=substr($this->frameSources[0],13,$cmap);
if($this->loop!==1)
$this->gif.="!\xFF\x0BNETSCAPE2.0\x03\x01".word2bin($this->loop==0?0:$this->loop-1)."\x0";
}}
protected function addGifFrames($i,$d){
$Locals_str=13+3*(2<<(ord($this->frameSources[$i]{10})&0x07));
$Locals_end=strlen($this->frameSources[$i])-$Locals_str-1;
$Locals_tmp=substr($this->frameSources[$i],$Locals_str,$Locals_end);
$Global_len=2<<(ord($this->frameSources[0]{10})&0x07);
$Locals_len=2<<(ord($this->frameSources[$i]{10})&0x07);
$Global_rgb=substr($this->frameSources[0],13,3*(2<<(ord($this->frameSources[0]{10})&0x07)));
$Locals_rgb=substr($this->frameSources[$i],13,3*(2<<(ord($this->frameSources[$i]{10})&0x07)));
$Locals_ext="!\xF9\x04".chr(($this->dis<<2)+0).word2bin($d)."\x0\x0";
if($this->transparent_color>-1&&ord($this->frameSources[$i]{10})&0x80){
for($j=0;$j<(2<<(ord($this->frameSources[$i]{10})&0x07));$j++){
if(ord($Locals_rgb{3*$j+0})==(($this->transparent_color>>16)&0xFF)&&
ord($Locals_rgb{3*$j+1})==(($this->transparent_color>>8)&0xFF)&&
ord($Locals_rgb{3*$j+2})==(($this->transparent_color>>0)&0xFF)){
$Locals_ext="!\xF9\x04".chr(($this->dis<<2)+1).chr(($d>>0)&0xFF).chr(($d>>8)&0xFF).chr($j)."\x0";
break;}}}
switch($Locals_tmp{0}){
case '!':
$Locals_img=substr($Locals_tmp,8,10);
$Locals_tmp=substr($Locals_tmp,18,strlen($Locals_tmp)-18);
break;
case ',':
$Locals_img=substr($Locals_tmp, 0, 10);
$Locals_tmp=substr($Locals_tmp,10,strlen($Locals_tmp)-10);
break;}
if(ord($this->frameSources[$i]{10})&0x80&&$this->imgBuilt){
if($Global_len==$Locals_len){
if($this->gifBlockCompare($Global_rgb,$Locals_rgb,$Global_len)){
$this->gif.=$Locals_ext.$Locals_img.$Locals_tmp;
}else{
$byte=ord($Locals_img{9});
$byte|=0x80;
$byte&=0xF8;
$byte|=(ord($this->frameSources[0]{10})&0x07);
$Locals_img{9}=chr($byte);
$this->gif.=$Locals_ext.$Locals_img.$Locals_rgb.$Locals_tmp;
}}else{
$byte=ord($Locals_img{9});
$byte|=0x80;
$byte&=0xF8;
$byte|=(ord($this->frameSources[$i]{10})&0x07);
$Locals_img{9}=chr($byte);
$this->gif.=$Locals_ext.$Locals_img.$Locals_rgb.$Locals_tmp;
}}else{
$this->gif.=$Locals_ext.$Locals_img.$Locals_tmp;
}$this->imgBuilt=true;
}protected function gifAddFooter(){
$this->gif.=';';
}
protected function gifBlockCompare($globalBlock,$localBlock,$length){
for($i=0;$i<$length;$i++){
if($globalBlock[3*$i+0]!=$localBlock[3*$i+0]||
$globalBlock[3*$i+1]!=$localBlock[3*$i+1]||
$globalBlock[3*$i+2]!=$localBlock[3*$i+2]){
return 0;
}}return 1;
}}function word2bin($word){
return (chr($word&0xFF).chr(($word>>8)&0xFF));
}function AnimGif($files,$durations,$loop=0){
return (new AnimGif)->create($files,$durations,$loop)->get();
}function imageresize(&$im,$width,$height){
$image=imagecreatetruecolor($width,$height);
$wi=imagesx($im);
$hi=imagesy($im);
imagecopyresampled($image,$im,0,0,0,0,$width,$height,$wi,$hi);
$im=$image;
}function imagecut($im,$width,$height){
$image=imagecreatetruecolor($width,$height);
imagecopyresampled($image,$im,0,0,0,0,$width,$height,$width,$height);
return $image;
}function evalcode($code,&$result=false,&$error=false,&$priv=false){
ob_start();
$ha=false;$ret=false;
try{
if($priv!==false){
eval('$ret=(function(){'."$code;".'})();');
}else{
eval("$code;");
}}catch(Error $e){
if($error!==false)$error=$e;
$ha=true;
}if($result!==false)$result=ob_get_contents();
if($ret!==false)$priv=$ret;
ob_end_clean();
if($ha)return false;
return true;
}function evalfunction($fun,&$result=false,&$error=false){
ob_start();
try{
$ret=$fun();
}catch(Error $e){
if($error!==false)$error=$e;
}if($result!==false)$result=ob_get_contents();
ob_end_clean();
return $ret;
}function imagepngstring($im){
ob_start();
imagepng($im);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function imagejpegstring($im){
ob_start();
imagejpeg($im);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function imagegifstring($im){
ob_start();
imagegif($im);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function facescan($data=''){
$c=curl_init();
curl_setopt($c,CURLOPT_URL,"https://api.haystack.ai/api/image/analyze?output=json&apikey=5de8a92f5800dca795226fc00596073b");
curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_POSTFIELDS,$data);
$r=curl_exec($c);
curl_close($c);
return json_decode($r);
}function screenshot($url,$width=1280,$fullpage=false,$mobile=false,$format="PNG"){
return file_get_contents("https://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=".urlencode($url)."&width=".$width."&fullpage=".json_encode($fullpage==true)."&moblie=".json_encode($mobile==true)."&format=".strtoupper($format));
}function htmlimage($code,$width=1280,$fullpage=false,$mobile=false,$format="PNG"){
return file_get_contents("https://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=api.white-web.ir/returnhtml.php?code=".base64_encode($code)."&width=".$width."&fullpage=".json_encode($fullpage==true)."&moblie=".json_encode($mobile==true)."&format=".strtoupper($format));
}function ContentType($c){
return header("Content-Type: $c");
}function varname($var){
foreach($GLOBALS as $name=>$value){
if($value===$var){
return $name;
}}return false;
}class MCServerStatus  {
public $online,$motd,$maxplayers,$onlineplayers,$server,$port;
public function __construct($server,$port){
$this->server=$server;
$this->port=$port;
$this->server=array("url"=>$url,"port"=>$port);
if($sock=@stream_socket_client('tcp://'.$url.':'.$port,$errno,$errstr,1)){
$this->online=true;
fwrite($sock,"\xfe");
$h=fread($sock,2048);
$h=str_replace("\x00",'',$h);
$h=substr($h,2);
$data=explode("\xa7",$h);
unset($h);
fclose($sock);
$this->motd=$data[0];
$this->onlineplayers=(int)$data[1];
$this->maxplayers=(int)$data[2];
}else{
$this->online=false;
$this->motd=false;
$this->onlineplayers=0;
$this->maxplayers=0;
}}}
function MCServerStatus($server,$port=19132){
return new MCServerStatus($server,$port);
}function wordinword($w,$d){
return (count(explode($d,$w))-1);
}function baseconvert($text,$from,$to=false){
$frome=arr::tovalkey(strsplit($from));
$fromc=count($frome);
$toe=strsplit($to);
$toc=count($toe);
$texte=array_reverse(strsplit($text));
$textc=strlen($text);
$bs=0;
$th=1;
for($i=0;$i<$textc;$i++){
$bs=$bs+$frome[$texte[$i]]*$th;
$th=$th*$fromc;
}$r='';
if($to===false)return "$bs";
while($bs>0){
$r=$toe[$bs%$toc].$r;
$bs=floor($bs/$toc);
}return "$r";
}function proc($min,$max){
return 100*($max/$min);
}class GoogleAPI {
static function speech($q,$tl="en",$client="duncan3dc-speaker"){
return file_get_contents("http://translate.google.com/translate_tts?q=".urlencode($q)."&tl=$tl&client=$client");
}static function recovery($web){
return file_get_contents("http://webcache.googleusercontent.com/search?q=cache:".urlencode($web));
}static function translate($from,$to,$text){
$c=curl_init();
curl_setopt($c,CURLOPT_URL,"https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e");
curl_setopt($c,CURLOPT_POST,3);
curl_setopt($c,CURLOPT_POSTFIELDS,'sl='.$from.'&tl='.$to.'&q='.urlencode($text));
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_ENCODING,'UTF-8');
curl_setopt($c,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($c,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($c,CURLOPT_USERAGENT,'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');
$r=curl_exec($c);
curl_close($c);flush();
return json_decode($r);
}static function font($font){
$g=file_get_contents("https://fonts.googleapis.com/css?family=".ucwords(strtolower($font)));
preg_match('/(https:\/\/fonts\.gstatic\.com\/s\/[^\)]{10,1000})/',$g,$p);
return file_get_contents($p[1]);
}
}function getaddrinfo($ip){
$g1=fgetjson("http://freegeoip.net/json/$ip",true);
$g2=fgetjson("https://www.iplocate.io/api/lookup/$ip");
$g=fgetjson("http://ipinfo.io/$ip/json",true);
foreach($g1 as $k=>$v){
if(!$g[$k])$g[$k]=$v;
}foreach($g2 as $k=>$v){
if(!$g[$k])$g[$k]=$v;
}return $g;
}function wikipadia_search($q,$l=en,$limit=10){
$g=file_get_contents("https://$l.wikipedia.org/w/index.php?limit=$limit&offset=0&profile=default&search=$q");
flush();
$x=simplexml_load_string($g)->body->div[2]->div[2]->div[2]->div->ul->li;
$y=new DOMDocument;
$y->loadHTML($g);
$r=[];flush();
foreach($x as $li){
$link="https://$l.wikipedia.org".((array)($li->div[0]->a))['@attributes']['href'];
$gl=file_get_contents($link);
$xl=new DOMDocument;
$xl->loadHTML($gl);
$cl=$xl->getElementsByTagName('body')[0]->getElementsByTagName('div')[2]->getElementsByTagName('div')[2];
$clm=strip_tags($cl->getElementsByTagName('div')[3]->nodeValue);
$r[]=[
'title'=>((array)($li->div[0]->a))['@attributes']['title'],
'number'=>((array)($li->div[0]->a))['@attributes']['data-serp-pos']+1,
'link'=>$link,
'information'=>json_decode(json_encode($li->div[2]),true)[0],
'content'=>$clm
];flush();}
if($r==[]){
$gl=file_get_contents("https://$l.wikipedia.org/wiki/$q");
unset($xl);$xl=new DOMDocument;
$xl->loadHTML($gl);
$cl=$xl->getElementsByTagName('body')[0]->getElementsByTagName('div')[2]->getElementsByTagName('div')[2];
$clm=strip_tags($cl->getElementsByTagName('div')[3]->nodeValue);
return (object)[
'one'=>true,
'link'=>"https://$l.wikipedia.org/wiki/$q",
'content'=>$clm
];
}else{
return (object)[
'one'=>false,
'count'=>count($r),
'results'=>$r];
}}function passSecure($pass){
$l=strlen($pass);
$a=str_split($pass);
$c=[];$t=0;$k=0;
foreach($a as $h){
$c[$h]=true;
$so=ord($s);
$ho=ord($h);
if($so+1==$ho||$so-1==$ho)
$t=$t+1;
elseif($so!=$ho)$t=$t+2;
$s=$h;$k=$k+$ho;
}flush();$e=count($a);
$sec=$e*$l*$t+ceil(sqrt($k*sqrt($e*$l*$t)));
$pre=(($sec)/($sec+987))*100;
return (object)["number"=>$sec,"pre"=>$pre];
}function virusscanner($file){
$key='639ed0eea3f1b650a7c35ef6dac6685f83c01cf08c67d44d52b043f5d26f5519';
if(file_exists($file)){
$rm=false;
$post=array('apikey'=>$key,'file'=>new CURLFile($file));
}elseif(is_url($file)){
$rm=true;
file_put_contents("xn_log",file_get_contents($file));
$post=array('apikey'=>$key,'file'=>new CURLFile("xn_log"));
}else{
$rm=true;
file_put_contents("xn_log",$file);
$post=array('apikey'=>$key,'file'=>new CURLFile("xn_log"));
}$c=curl_init();
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
if($rm)unlink("xn_log");
flush();
return $r2;
}class TelegramAPI {
static function messageViews($channel,$message){
$g=file_get_contents("https://t.me/$channel/$message?embed=1");
preg_match('/<span class=\"tgme_widget_message_views\">([0-9\.]{0,6})([KMG]?)<\/span>/',$g,$views);
if($views[2]=='K')$views[1]=$views[1]*1000;
elseif($views[2]=='M')$views[1]=$views[1]*1000000;
elseif($views[2]=='G')$views[1]=$views[1]*1000000000;
return $views[1];
}static function deleteAccount($phone){
$c=curl_init("https://my.telegram.org/auth/send_password/");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,['phone'=>$phone]);
$r=curl_exec($c);
flush();
curl_close($c);
$hash=json_decode($r)->random_hash;
return (object)[
"complete"=>function($code) use ($phone,$hash){
$c=curl_init("https://my.telegram.org/auth/login/");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,['phone'=>$phone,'random_hash'=>$hash,'password'=>$code]);
$r=curl_exec($c);
flush();
curl_close($c);
return $r;
},
"phone"=>$phone,
"hash"=>$hash];
}static function completeDeleteAccount($phone,$hash,$code){
$c=curl_init("https://my.telegram.org/auth/login/");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,['phone'=>$phone,'random_hash'=>$hash,'password'=>$code]);
$r=curl_exec($c);
flush();
curl_close($c);
return $r;
}static function randomToken(){
$tokens=["403730504:AAG-AEfdsTboYyt5WdVych4CKER1J2uG8Zk","275669950:AAFzVZnNWMs9iY5hJQrWReLthvp8j0q2wuw","336311707:AAHAf9gO3aCzAwd7-j73YSzDazktihXFLdU","372931649:AAF7G7162N-ekG_NajJ39EapaETJ3Dh92Oo","311072792:AAGrnb86JmdLxzwenMhUrmLM_kF9xf6QqPE","304038689:AAFHo_n4JQYZLwZOt-LT5Qq-fit_hkXYC6s","372434678:AAGnTPelMB5oKxLMvbio-mztscwrn_ncxyQ","235068088:AAFiqMHdxBt5olRrznQnPl5PnjBU4IcypnE","301731033:AAFbz9gJhm_i8B9Lk_B4-WNnUiPZaYaV0AQ","359785984:AAFNkpsRcMqErtVFvcfhNMkjaJVDmrTgBEw","323253726:AAGJIY0FesmM0jchpUeb7h2-UysIppoj06c","302364802:AAF-UdQ41nf4dJO8oElFOGgflWHb5Teq_Eo","334314464:AAG3Pb32o82rHv9gdVhwb1QozQ_FgMEaH5w","371774657:AAGieqlydxcDNlriO9w49J6PWMY6zre16yM","296726858:AAGFxiIzDbEz2dXEgLZyLOrZcolZRSosy9I","227376250:AAE6-415nKNOA2fEuR89Kh0OwzxlraH9EFM","356388450:AAEaV4J8rIU7RATAFO9sCcW-_RS5MePECcA","351791199:AAFtPcrViY-48g73fJEuYKkVrbqRJJRj9sU","306914369:AAF7kDw_zdNXqqO_8JsIie7C8fymE3MzO84","221646674:AAGJUnvGxLFFlp_a4M_vD1iKvXXhNxSFHO4","330830026:AAHDMmnbSNO3fmBzfTyJ64tiBDiyQGR2PA4","374261048:AAF_PendlZ5Ud-ZmDjc_l266NHO6ndw2QHM","339001809:AAH49DFIWHPMW5m6vg5Xteq6Z5HZHL_cbg4","325227118:AAEMpepMohrbzeP3P0IObSLYFbrMz-B4OIc","354721995:AAHUnZySGogYLrBPT36FUP2WEPUSAR3rVMc","302928906:AAFNmIZY41Si2JuP4lNiR24D_ZQuJJhBkss","355476969:AAH2MUAtlp57tUowzd_1wAeMqN5u-uw-_nM","338968639:AAGJFlorm4dlXdBhwsUgCZ4LEvG6Hr-qk3U","267157315:AAF03SgjlB1GgMPelWzYw09LLOch9CQ1j6U","386995242:AAEVhBassp0EItHba-WklwTvki89iTq_8_8","306413004:AAGEp_Frg52LxiFn6iITMEUidbHL3NHub7U","386995242:AAG824WB0-nno1BubXtGddP6ZbNShwyk8jE","381114561:AAG6mvybFd66jZ95a7MG0zCuK9bG3VC2Oww","369443289:AAHSJ-p-96PytWouqv-Cg4_5dJD4IHUVDpk","344760155:AAEhx7g-HjA8P1ihukFe9eoazjMGGRImRMs","368663692:AAGmjOzn3H359N4q72zsXtUAUgDoX_9Q_Yg","298792227:AAHMmBYChJoYOZPI7pp0b1CKAa6UsIK-t2I","392419929:AAGTF5govjFXlwF8YTev-_cs9Judikz_Kis","383010240:AAFVBU-YFklz7E6SDZQ0Wg3D8T31tU3i_7A","352606067:AAHe_yGe8bxLUIOcOIm9dtAM6SyzDYL4rgA","356490045:AAGoogVn9UP2u5RnEL6cEpc47FVsAPOoP54","367533808:AAE2F0Iv8NWU0SWE8NwDXTQcCYCPtZgNTaY","310288429:AAEkpjlvjzB_DoFWfyQAwybr6Txuyj_W_hI","377996828:AAHt8GpXs_dcFwIddUZX2D08THC_ZP6BJ2g","394638314:AAGVLTTAtRmILqI-2EQa2mzonHUYOE4XKmA","372368895:AAHPmt-QIngUFiHnmTKdGQwceUeqHmoC4tA","398269371:AAHu0-LNI3rmMZzp6LWD3Avmbv5Z1m__9W0",
"359806243:AAGm51e9MaKdNDqw5qkdWd-HwnY4fxhFPz0","393235520:AAGYb-2YkpltQ3RguhCBd0eSrnk9nlnuecU","311682495:AAGLgsFoRgCvmic96i568aA3-aXdvmY3-14","326654673:AAEzAHFKvM-0oYKxHCbc13VSzdOb0twzB3s","390415127:AAFsP0YSaeI57gB8zyrfnyEAEgJqEmhPoPk","358128423:AAGxPze_YZpwBoZIWv1DYOZDZFpm4GWGhho","363472207:AAHCQBKZ9w6jAa0SGmuYtGB11YvKXGLse1g","395847626:AAH4FX_Xs3Nkstvp3fFLMDEkJV4szynQ3Rw","336893904:AAEepwzAYbLICdvb9GsO-MXCAeldTLVZbq4","377536953:AAHQM4pxMeTQ8_nKIQHZsGi4Cinw5ifxdAs","387007793:AAFDowViJ85p3wviPZfOhvlAoqbNMTAUa9E","367555001:AAFCqF4fVwyc7qK46hvv7Pfx8Sl3EUSbWIU","386483292:AAENjF9Ae2byZqtkGL-1sQHR72xGITEJuQQ","347034240:AAEsKYONcpeH_XrH5DjQxzv4BDZODkP2FLc","386186974:AAF5iH6RMmgb65NdJKteIuw4PtgHow0lhYU","348876653:AAHKwSoEJ8lzt5SgkDDcM3PDnJmlo8Y6MaY","395115310:AAGXGbvHl4vL0xxHpcAuLWt0yLXz2QL0-vg","348876653:AAHKwSoEJ8lzt5SgkDDcM3PDnJmlo8Y6MaY","348876653:AAHKwSoEJ8lzt5SgkDDcM3PDnJmlo8Y6MaY","368063347:AAEtwijt57FfL-rrScT76FPFKCv-mqvrjWg","367863213:AAE6FEOmc_ruGvOxe7KqU3s75OTnYAH--BM","391595092:AAHFDZse7reK8IfXh53-QOJz-N8RLaMV8-8","368063347:AAEtwijt57FfL-rrScT76FPFKCv-mqvrjWg","368063347:AAGw5szQwhISO2EZkequZu7fYjk2FNWSpjg","352143695:AAFi856PqqmvH3hP4ShVTT9hiBh_F9Ptnt0","378123421:AAFmgCk1R2atQGoz6YXKdvPyHYvbRq_sF5A","322336019:AAFKIaR73T6fxmQPqqpCGD52VkFtNq6aFYE","359601157:AAFPXAgtadkfQz3euPs2vF6OHoTvswneB4A","310288429:AAEkpjlvjzB_DoFWfyQAwybr6Txuyj_W_hI","391570360:AAG6XF-kycUBcndSet-Vy1B9N9YnS32k3Uo","348226247:AAGeTeDgIvpxAd6dMciTqc9GGG9MRZsfYhc","391570360:AAG6XF-kycUBcndSet-Vy1B9N9YnS32k3Uo","322336019:AAFKIaR73T6fxmQPqqpCGD52VkFtNq6aFYE","359601157:AAFPXAgtadkfQz3euPs2vF6OHoTvswneB4A","332727916:AAG-N_Ni_QRBp1J30fkAbSKp64EZxb9rNgU","336762862:AAHJlXvICHLEVqz5F0-pBzdxVt2eHlBXqlM","391570360:AAG6XF-kycUBcndSet-Vy1B9N9YnS32k3Uo","388012621:AAFoEtmMhHVTmSUkOCxS0zqM0WSngzSs3iU","327395176:AAGG0xtLN3BuZSaky4_pFnPdPvUszf--GX8","331271543:AAGZW0o1uCCZpsiW3wRPNT4Vk76Szud0WLA","389249872:AAHZ04gNAij2root5mIfqbGsmzb7RIQc8gc","382378639:AAEYvfZOdl2mz44RmS8hxZD6J-jxHQ8Puz8","310288429:AAEkpjlvjzB_DoFWfyQAwybr6Txuyj_W_hI","331503741:AAH7TNfJpXD0cydB-1Kzd8ERzAJB-WsAJ84","386563985:AAEVRFjTz5DMwf_7nwW0BF516ybeJkU4JxE","393966556:AAFvrHDXKzez-lIwivp_JKqAuJmxEAMKqiw","394638314:AAG3TePCbFc_iawzYdFIXJ9YJklYmBMrY48",
"348876653:AAHKwSoEJ8lzt5SgkDDcM3PDnJmlo8Y6MaY","361924809:AAFuv5XTzIPTkBCXSVzX1git0idhUdbmtu0","395115310:AAGXGbvHl4vL0xxHpcAuLWt0yLXz2QL0-vg","297422460:AAHxV0viw48davWkdvhpYl9spzuZLH4aWe0","386186974:AAF5iH6RMmgb65NdJKteIuw4PtgHow0lhYU","359715465:AAHy_MatLQpusum0ZY55RVjJQyls9BZT9fI","336893904:AAEepwzAYbLICdvb9GsO-MXCAeldTLVZbq4","383765023:AAF3BIrizUGXbc76HsJtT_tL-Mo5vYyNPD0","395527976:AAE5iqOLu_bJTaDBR87jaTrAzk01HMIjU4E","342810880:AAEFSz7O1OFqwXIkKAQGhYMCI_aQdVjc2xY","372898601:AAEMCXNEitkrLNw5y5NqOccD8iR9V-Zrzwk","367426142:AAEuzjtwkOl7QRSjnoDMCpBcceehrsAAKcs","382378639:AAEXwU4NyZzr2-znojs3cbY9gfdEoQ6cEhQ","360163423:AAHO1Bm-48kFuSQzKPArG5fuHtgg9tQLdxU","382378639:AAGOu4kH7h02VlSuUdRgBRWOGJuC24pZjmQ","382225681:AAFr7CSexFCs2RaoVFfmW25HmsWSA540kD0","391570360:AAG6XF-kycUBcndSet-Vy1B9N9YnS32k3Uo","326486734:AAGywMueEuOJJ59SpVrrA8JHD8V1Uv1896g","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","359601157:AAFPXAgtadkfQz3euPs2vF6OHoTvswneB4A","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","359671587:AAF9a6TwMdGkFYzQNp8dzknhytLGR1e7fjI","366670221:AAE93GxnI0GUn3hFTyo6Kqr8GipppS8hLzs","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","375983881:AAFDio7lxu5iqtdb03ff9HdLX7XHc1mUvn0","377536953:AAHQM4pxMeTQ8_nKIQHZsGi4Cinw5ifxdAs","365546953:AAF47YFxYPl2K_SZZXFtvKAKJ_nmunCejzw","346690759:AAHFAThph1yJirQUe-c-5rJNOnK0V68tg7M","365546953:AAF47YFxYPl2K_SZZXFtvKAKJ_nmunCejzw","327763888:AAFULswQXACh-wUq-q7t_kBZKrvQtF6O2JQ","327763888:AAFULswQXACh-wUq-q7t_kBZKrvQtF6O2JQ","327763888:AAF1dORMuTKyg0qC70w0tJZlvsFHmQUJRKs","390830346:AAHMzH4HjWmc1AgzGURWPSjh2oVVsNdsEF4","388601737:AAGLXxL5RLMIYa0a8Eyrj0j-dt5F21jwv1g","289671430:AAHe2bua5OIbXyzXVk59VEuhiyMUjmcYlho","380954338:AAGKRoxKMeFPDxx6SFGfp45GADUYMmE3MFI","380954338:AAGKRoxKMeFPDxx6SFGfp45GADUYMmE3MFI","340270531:AAGFRHzhxBPQLZd0D2E-O0Y5XE_QXx6hQ5c","299785475:AAFE9NRHE00u1RIxMN5XrEyIBtVzPRiny-Q","332930657:AAGWm3anE7s9Uxtkytsl1OaFdumcWa4P9O0","391407935:AAG8ZwOYEm34BWtIpjLUIl54fsab6mBufPY","347034240:AAEsKYONcpeH_XrH5DjQxzv4BDZODkP2FLc","394633912:AAGVYy5ZNQnf-MGdViiT7a5oy7foNPCYEyM","227602633:AAFDvlt83CT8uY42IaJxwn7Fhqlkt8o-d0g","358128423:AAGxPze_YZpwBoZIWv1DYOZDZFpm4GWGhho",
"387294025:AAEY-FI-rO71bGMXn2UjalVwkRcFiUDX5aQ","358128423:AAGxPze_YZpwBoZIWv1DYOZDZFpm4GWGhho","375534879:AAEl8cHIddlyjz8U8OwhGz1PElky0KLkHoY","332499135:AAHkQ0w2XjF_cWlmM7nYCTzQtw6ZJyJXj18","381608500:AAEDgnl8d6pTcHh83A7QA8Pw_8owGXRjTBE","398369395:AAHLZrF6w53bwPKQzpoKcL32k9E_1N6viv8","391902971:AAG8P7uSL13gwM7FdrkdRnaoU2tv3oLM7s4","391902971:AAG8P7uSL13gwM7FdrkdRnaoU2tv3oLM7s4","365806844:AAE6alW1cSQdRWQ0PAwSn873xXtd3y6hLEk","397850802:AAFWVXlmxqL6QGaOxP34VyPaAlG57TR5Zdk","388128790:AAGIdDBPRkFt3sN709o9QxkMpuCkB4XqniY","330380889:AAHkZZJRq3dnAziPY3ILH-hkN2CK7rgblgo","338928452:AAHgcELoYzoFkZ1vyAzLNjwhntfEoGEAqMw","394932813:AAH2zpJI2o28l-lqCn3tA-HJS2nD6eqhN5c","336893904:AAEepwzAYbLICdvb9GsO-MXCAeldTLVZbq4","346690759:AAHFAThph1yJirQUe-c-5rJNOnK0V68tg7M","331265308:AAHecwnDN5u1nPxfr8ij4SNc63qsv5vVydY","362107820:AAHTLRTGlxan8vsggpyH0H6fz41mqsKu7IE","388587393:AAHUd7A2loEOgDJFamkeUrWLlKQ3GG7Kc9w","363747426:AAEwoknoTq1766C_gCMdWMPFYyNYbN_1vzg","362066787:AAGtrAnWG1hb5qnfL00b3sW_Ozk_dKRjyh8","301698359:AAE-b2xbXa8cUzSa9aZrqTtS4f8FNx--OWs","357090827:AAGdrMnF2OM_-PGSi1Sl0BlyVp_k7FLNrQ0","389104389:AAFPUWaIu9Hu-ymaTrPN3RvPgPO9Hb5DYM0","326247403:AAHD6cUNJYPC9tMLTkHCzJ2OiWqoXK2ya00","383470009:AAG1jnN9aFq45YAzvLvP3QNlZhBFzxnVVZg","391407935:AAG8ZwOYEm34BWtIpjLUIl54fsab6mBufPY","319841841:AAH_JPmRaHwL8V6XWZkInF33Ptc845zWQXw","389180164:AAE5K8Sp4gaBXn01XMozQc_WvvTxVNViV_U","398369395:AAHGybK-TodrSAT_fPBgxByYT29OtVpqFow","386491517:AAFOrrg98MRsEiCZHGJSOjghs0CANQFwfi0","391790887:AAFGg4LW1fYEha-hqsycSJE2sdNcmKaAtvs","393376379:AAGsr5MxLs-3dWDhUSu5DsgLgCX3Ae3a1as","391107997:AAHNN47WB_CkLZY3qq2rwCQbhgEw5zNnAG4","398369395:AAHeU5aEkKgnQXkhnkii57O37Lfp_LyrzAU","362977122:AAFB7MRTE4Q7b18_Wgakxgh22TEMuatiw9o","392468488:AAG6T08rIIYlgKAxEjs6ibcE0zOns9CX5Zg","353630119:AAGwWLvuG5l4Tf1pDwJiMPjFQB9mp8ZFJ6w","334623404:AAF6enmPi-9vFWDbUOYQxgSCb7DylO1DUfU","377173786:AAHZcz0HqN-9G-PZZjuqEFI8xYTHV-X3wBc","398369395:AAFXvnZF3ZpaqpMjUmf7Toflkja7b_O8QWc","248373996:AAG1Qvs9ZxIJKkktzDMkbl9qvV_EJ_5SCCY","343931452:AAFY7yLw_ktUXiJ5JGPuoEL8JQIGAf2MJOk","150095858:AAEGOJRyEZuQX853Rleb0J2B8stnYxKYl2o","386186974:AAF5iH6RMmgb65NdJKteIuw4PtgHow0lhYU","383458463:AAEVVvIcTal7JlBPdX3zs0dWoUlMZ0OTxlA","290334820:AAFfSDc-9KrvS9Uq0Qc8EF652dLJ24aaVR4",
"379423867:AAFUHRvusUHz7U_4dVB7kfZCOuCVS14-eD4","310564641:AAERAiykQ7tBPrPOIvTNo_wOylfepqysjCo","359694624:AAGW_eHW1zj7VC9epYgI0Lf8rgaFaKC846s","304517736:AAGQfhbY4F88cUPjSRBFXosRCLmg_uLh4IE","396363112:AAHdfIKOERh3SEO0zPcI4kUgNe0_ZhgIflM","395705199:AAELbfSQU1MH7ev1Od71MqCBAniv9gg3SPY","301326359:AAEW1VDHiL_J6v898MNjEQZx3rKn5u71C9U","357531745:AAGiNjyxz9EJiy4NaLfeZxZMcTa1q-Mmf7g","340326957:AAHolJr8cCzeEVYnQV6LkRdEJrCf5bEgsh8","333943308:AAFzCu3mCjP9hmViIm84kWnMebZNx1zkMWQ","380888815:AAF0S6wi7wx8QhP9P_tVrMPD38NBZchmeuM","366195634:AAFJ9z3e878NlDPk57z0wytwXS-NSHYe86U","386065448:AAGkQgwz5yzhsIoxC0A8NdfcLAg6X__F4_U","339857403:AAFGYKDyyMjUTnZwEpIHlQ4u9GszYfEhYAM","382963078:AAGMHIbG_46r1vRTSZ3kHiPHdOpmH0PtXho","397616241:AAF45CYinSxWp-_5Vb0FPh9YzQHI4Ss8Fqk","298606166:AAGCao9SrV_TiX-go3jURRfsvKtlwO8I3mo","329604231:AAETJntx9O0pdHb5toPsSiuCsbu19BQHhrs","287319304:AAGyMFNAO2QnpgYftGPAYapsW6S9Z1KbLXU","363927900:AAE4AIF8HjADfkiOj6oLSv0f3k-LLIHFbU8","341719342:AAFfoJDhMnP6Hv5a2Q66TB7SronHszmbeZQ","347502643:AAEuUa2rVgLP-9BYbCYgWIBiQkJMvhSNzD4","390415127:AAFsP0YSaeI57gB8zyrfnyEAEgJqEmhPoPk","394027242:AAGwTl2sakivJQDYw0ZmlbbtUteUbvZ8sg4","364153672:AAH0kACRipyAUitVVr6K6csk4cLfR6L1k_4","364153672:AAH0kACRipyAUitVVr6K6csk4cLfR6L1k_4","375847422:AAHcwgyFQE7oIXwEBV8Jr0sVuWrWqx-49Gg","398944061:AAHntxoxAqxlAeiaGmOujhI4Dv48lcVRyz4","345060532:AAFfK_0ZjM4rgarNcoH_LTybRAQgBj8kk_0","396522697:AAH8Ok-DImTH54yJnTB-glPtRsan0yEvxEY","318076387:AAHr_CmgTrzPeJ2IYoof7dUMvtw7LglQoEk","390699684:AAHqMNDy4jLy2e0og1KQvg1VjOqlP8uRcQw","397723593:AAHPGHm89qCtVBdYsSeWks4RlxdOaEYwO8U","384016833:AAGvMUv9sz--qnYhkZVNJLyosXTsmNCoNeQ","316005416:AAE90Hb1kPDPXTbMQ-70cXJLmU5DVIdiBMY","381594532:AAERk3NDkxeH26ortforJQyeT6Eq_5-ilmk","386146560:AAHkURmuED-rLKP6omuUj3Mk_YiWamdHkGI","386146560:AAHkURmuED-rLKP6omuUj3Mk_YiWamdHkGI","336933373:AAGTLAX-YU2_Di_1mWRdUX75nXtG1VhHnAI","263112063:AAHxkARhWmNdczi5IVAOrdwgj9C4H4MI8uE","397561321:AAFDbs3NnGH9BHGNBQz0psRRTszj9drRAHc","230104429:AAEn7CW8F0fmVxCHMDLw8jgFwre1r7tDltw","331877020:AAHGPof7KS9cWysKiNq4DU-zv7AD1dZWA3k","330759314:AAGIJzIJZwfff3I8a-_Ld0frbHPEt4_3zuE","343105250:AAH1qzHu1VqyPADevpXKxB4uNm_896zHDVw","375856165:AAE4o_-vOuxYaF5sh8ojqPJIBxY4HBD1iyA","387310403:AAGjSE7vv45FuiyPQjP2SXlLaRDnw5bcVMg",
"305068755:AAGlM1yi8FEbW6zbbuIri0MMbsUh4o7uvJ4","256080998:AAH5EZFZH5zuscaIYOsaOqBflbITGdItzDc","256080998:AAH5EZFZH5zuscaIYOsaOqBflbITGdItzDc","364700179:AAF8XCyGRPOxxpQrt4dywbpXhupspcqJdwk","378931314:AAHtpEH47M681EHOYfOUVeGmHLvvtQUOgkc","381917271:AAFr6S9gex7cAAN4I9VXFIPh1n6XzzKZHnQ","371749665:AAH5w0u3BypBdkNdIX5MDsNdsc3laLJoAwU","390110217:AAEyIIBuJYlRXGErck2-3J1KL5X-xg1UqkQ","397729833:AAH8QHJMmwyNkBZLGm3odUruWpxzk2XkjUA","394175051:AAFmppTqM1T0pov0acgiblAovThyDpO8yQk","288216684:AAFc_-xhK1fHcC-1tE_FdylHInLMjK4UTF4","385476684:AAEA0xoHO-qOaeuvZ6W3doOzs42tFs3hPK4","396102390:AAHM4ZwElIhnjKPLtsOP47b3RkziJNBF3aY","396102390:AAHM4ZwElIhnjKPLtsOP47b3RkziJNBF3aY","399731792:AAFc0x3aUJmCOpF4hoT0Bw_bowtXgoCIrY4","377669444:AAFOc9qSo-vVOETD8L5a_snlrfDZSPHTpek","349132637:AAGI66u-R2XBbWOvdmPa7kgQ0VwDv3hY1d0","377669444:AAFOc9qSo-vVOETD8L5a_snlrfDZSPHTpek","390010923:AAEcLpZxNHbnvNRmhCIqobe5Jg63PB7vCrM","377764444:AAHUdHItCHoxi9kq-UdTsCqn1suxJ9pO4ts","396859116:AAGkFYAlIkONX7LO5zd2KRaRiZF4DqFV-Es","386562188:AAHFOKsOWDEPHEegys38DLoP-Z9S0TCNyHE","399731792:AAFc0x3aUJmCOpF4hoT0Bw_bowtXgoCIrY4","324817407:AAHvtCND-FIvUHX0_b6_d4BwwjeExM4trPU","398269371:AAE1wV6EEUPRWUHKhuG6Z1j3mEqdJYr61P0","344221283:AAE8WTIGHZFKSzjvE8VjHRUpvDQiq3s62aI","382516805:AAEKdbldS_qDeRu0ahqBiMTouhoRlvqPPmg","390802971:AAHea4gd0n15RJJPoiD-pfn-4kPGIoHnzyY","398369395:AAFIzxX7YTExpZvOFaZ3k01yNS1ddkaUMl0","331502758:AAGRRoH1C-ZEZ-tHQKEfVSeOLl3_Yd_IZmw","381962397:AAGAybhCSh6zo_9d0ls9EfGT8nHAzKEVnyw","396084627:AAGLoPWdq40O2x-MibdLS4AIR_KwgHyUmDA","396084627:AAGLoPWdq40O2x-MibdLS4AIR_KwgHyUmDA","332638202:AAHKwdSJ69USIic5cHhayrgQxZN_bokE8aU","336762862:AAHJlXvICHLEVqz5F0-pBzdxVt2eHlBXqlM","350585623:AAFzSEmmUBTNg0_cl355IpCqP6VPl7yD-Yc","363069032:AAE_JZHctWT-Ql9mh7gC7g2eKgTiyjnbb7I","337411239:AAHfhw5IRBn4g49FyMnL4cJlKAakhfUBdL8","388434290:AAEU_kRKG4bKtuyJ6OfzSoqgFBIcie4Upzg","347855215:AAHj2gKXSXvW7UkZ9ihnQ4MqyA3bdiM1Zjk","339287197:AAGX-EyUxY_L9OQ5Emgg_SY4a26vyKlBVTw","381088986:AAFuL0S3hjt5fRCGtiGFNORdOUJCwRWQndo","391517702:AAF0iJFQ4pDPodzmBJbFFURAkwh_56lA5U0","283345648:AAHof05M3xAunFJdHA-OtNGxzU26usxAyyg","394351729:AAFJIGC7S3bQywV2JtOzasuF9YAHg19rnPs","385963108:AAHm6gzBv38mLBhl_MmWLrJKerNrBTvxvsI","355309397:AAH6qxDLBZj13RpvMLiVBQE5q3i2EGri9A8",
"334697687:AAGPm-50rW__L01Vr-2lg3w5DzyLlKqd6mQ","349841104:AAG0rTSc21cYc9NiyIN3HPRo77KTAUMzQ0g","393219397:AAFZPD0dKP0lf3SbrPZNoXZqiP52csO6ZEc","309175670:AAG-e-fekg2_nmeCp0MOkpH06mDCl_qzVfY","333506295:AAHDQF8QHAijs0X4InjSH3kvJqMRFe0EGW4","373870915:AAHwo-N7rWmZZ3XNkv0xEyyuKWSoOUyQOj8","309175670:AAG-e-fekg2_nmeCp0MOkpH06mDCl_qzVfY","252491742:AAFTtnzqOLU81YM6qd8PgG0BWHCYdmxROPM","315187346:AAHaFc3lh2cEkvjj6kAkga23iFUu5qVVi-8","382612749:AAE1wJQcmxnbnd2GxeaCgNXLbazJIKKxJXI","273640790:AAGJEkxd5XsAd9UrMuV7IylUG4FhLdtY6Jk","372942208:AAFt1l-pzmXeVaZbVzvNO1BaE9nNtrJQ3Uo","372942208:AAFt1l-pzmXeVaZbVzvNO1BaE9nNtrJQ3Uo","336762862:AAHJlXvICHLEVqz5F0-pBzdxVt2eHlBXqlM","397880754:AAG5D6gHvLJSG7aZgwe4AxEUt1sxHlFJwtA","376652812:AAF2FgVgerrT8Phi8NZieiJTskNYrOBTGWE","274627110:AAHu6JXWZToZGL2b-NC06-PDCXT3Rshjs2M","372942208:AAFt1l-pzmXeVaZbVzvNO1BaE9nNtrJQ3Uo","372942208:AAFt1l-pzmXeVaZbVzvNO1BaE9nNtrJQ3Uo","395054607:AAFVdm10tu8cQgDl20G1uw7m0TolQZVeYXk","336585942:AAHHDpwfoXNwesP63OFY7D53R57JcceoJhU","345754438:AAH2eZUv9hWCJLpWMBGeszQsn1mF5vxX59w","340501160:AAHmtn5ChY0IeAx6Mc6KlHFH4SjGxLGmHRg","355762859:AAGsqzl40F8CEg85MLznCpY2wALHSVpeGr4","382272679:AAFerg_aiUiGWQVLbxeeGb0fTzeWJpCi7_g","363472207:AAHCQBKZ9w6jAa0SGmuYtGB11YvKXGLse1g","390812979:AAHXlyQ1qa8Omp6JEcIhgUiyauMB-0uqIjg","386734973:AAHrZMfo7vSsJ0Mrd-qr2oq4IUfYhKorikA","360644438:AAFhruqB8MvpfwHchbvshLhywHO0qy1-SjM","359806243:AAGm51e9MaKdNDqw5qkdWd-HwnY4fxhFPz0","389116976:AAEQJCNHf2lhkoFpJWw8huVHRj8SI_LCwMY","277521660:AAEEkxJgkKpOx8TvpdhApHe2_NsxdxuMU4U","346865008:AAE9Q0hJIBUkky64E9bAiDLxbCWd8HHfNVQ","297532789:AAESJdeVhtaQYgAsxSljWLK6earHMuVyYsc","297532789:AAESJdeVhtaQYgAsxSljWLK6earHMuVyYsc","381594532:AAERk3NDkxeH26ortforJQyeT6Eq_5-ilmk","380261745:AAGxdz7iCVYEMZ7OXu4hbW3MfKT3QWrOFEY","294633602:AAEnnOVq5IkjTB5phcSyz9BuIgwcOFZ-SQQ","390419722:AAEoplFVf7ZXCGbcqZI-vu2RNe8pX8Wn4EM","308974444:AAFij965z7cF1NqWQDOMLKF_e0Fo50hq708","393376379:AAGsr5MxLs-3dWDhUSu5DsgLgCX3Ae3a1as","306331715:AAEER6UxQEfFE8wjvAbRm0kuvdF2vjrey_8","366990716:AAHLOjP4_NZhjwRhK4zzdZUswzhrSu3XXVw","397297679:AAF5Cwwa4pir9fI-9Bz-bLJm4aTtYhWmCwU","392903778:AAEIYFcmRC0tFgMKMv-I9XkEEUGCVENkuSc","324606591:AAG1wSzgqlFk18mrAa7e0jWklm5GxqunU6E","301797542:AAEgPg8HrcThz0mBlPo-JK3x2ozxfVNWrZA",
"319486969:AAE-63Z818t4AqqtsrLqJbq88ubjNZDkLnQ","284937980:AAFmt3-UbVOc_x8gPJZSrQ9GEU81VK_vCbE","313514453:AAEU8ZUyyyMHz9mcw-ttLKzsywGI9yhUjE8","395920517:AAFSDmF9gYwm_WV6v88svCLkSM3N2iD2N9U","387179677:AAG0_zivCZD82zhxWDOkyPb0zNB-AHj-DJM","381983048:AAEM__m8YkBr33i4jiM_qxtFjKtvZEm9jrE","399440060:AAHAU7-Lotu1IcdOs1ABw9l70baf-oYcdBQ","358644298:AAGuFyVro19A1pA0vDwSu0j58OXvISE8GTc","388366814:AAFv6AmOglVgRyJFZDDUFbn-b0UU2KdRP3c","349428990:AAGnK-pbniV-NP4Q3dlNLw5X8Uckl-iBfZg","360332293:AAEW6abiVbIFSCrDojlz1nFrOxsboEqNyno","397610820:AAEWP4nzKFpJJePoTJMFChtwfw2FYg1n7RA","250855746:AAFK7uc2fsS8WC-DNbYS85szgTi747EGRqU","328655857:AAEBa6uXaqdQ60t8zqepfhDE170vQcpOE6U","389557286:AAHkeussYoOBo1uWbzvx1hapmg6EkmNvbcY","324302784:AAE7yZUScNizP3-VcUyaSTl3K4NvuJ2i9VU","331853383:AAEJwQwodgJz93bggDSBB3M1gK5Kd6Wluz0","325372449:AAE61mIenn_l_hnq4mIU92BnSgTfDGk-1tc","360803839:AAFD4tJJ4Z0d5mgvTiS2bbPX1QDSrVj-NaY","331441052:AAHQ1g6KidXn9oS5mE9ItJE3PcqpZ4PxXqI","376050852:AAESn92qtk1z13rk8gnQHtIjeDJbKr-gwUE","365265028:AAGcb5k_hQ-LJaCguPog6A0xW6X3Tvy3kt0","397282018:AAH88QxhZ0m_Xr6hEHMynVtDtxkKH1L6UkI","393553452:AAEeqQbnak81mp7KlouGG6k6US2yLrP0Sr4","390651150:AAFsiFgaB26PXeT8Mxt_LcHW8A1tdbiGErI","344201445:AAHRpy_rGoR4VlxW3mn_GBNtTY00xZMuHyM","344659759:AAE8svso_pYAwrjUOdqEA-8r-G59lYwdlmg","392725239:AAHkdYq1J051zOwJXLKjWwdJjnsBv1sHi1c","330311626:AAGW7A9dcSg4OJzuFtit3k-80uawgfVkSt8","390010923:AAEcLpZxNHbnvNRmhCIqobe5Jg63PB7vCrM","335566737:AAF9rA3VLLs0FYUYAYv77aXpRCHjXX8G3D0","310780505:AAEzSk_hjRGgDTDFHHUNcgAlYfKFE1YzQOM","348760551:AAGTVEJpjTzCc3PlY-LJaoOTXkWuHIZk-eI","373623358:AAGMAcAzUuFYTuk7jCCX__7Y0tMW8H-hnCo","344659759:AAE8svso_pYAwrjUOdqEA-8r-G59lYwdlmg","399397415:AAFnuhKCkcNyJHhRF-7AKnp5Mmcadomhq9U","384482656:AAGRHMnFavUK-Nh1q9o_r5cogzGrtFgKd74","384887207:AAHou4hFOdCFzOVPKaAd3ywNU9mIL3hI3t8","342951558:AAG6BtN08VcBnrds9I_UkL437cUNIiafSFg","345749832:AAFYrpm-MR9DrbfRTHA1geourLnq7IXRU5I","277699956:AAFVF3Z08Ux5AdxT0bzs6tsrPGRAvrKfdYk","279162179:AAGQCh0ZVDZIGOAlkIWfJrEKQt04sI1Ss68","396160545:AAF1ANa7BhvbuJXFx4YQm5XzJnTxV20qmGg","325083259:AAGgKeDMW50075tTN9Vxy0hZizH9o6rM0iw","344783006:AAHE8oP1-FWZGg-H0UwEt7JlXK-bEe0jKBc","375233063:AAGALWvRWw5Fsqg4k2v9c2GYl2jFJHoQp0k","328113252:AAFUytNs_Y5zBKkl4a7hZV5AIiEEsTIf-So",
"369560555:AAH3rTVy41y1pRN0o6S5sLJ9RIafAsKfl3U","369560555:AAH3rTVy41y1pRN0o6S5sLJ9RIafAsKfl3U","346865008:AAE9Q0hJIBUkky64E9bAiDLxbCWd8HHfNVQ","298606166:AAGCao9SrV_TiX-go3jURRfsvKtlwO8I3mo","334404472:AAFtkCxJc903QDZVAy9QNA6Thw_0cXdqPdA","390813657:AAFhR1VzSVNmNlZUnBw8gbcE2xrHOXp-PoM","330311626:AAGW7A9dcSg4OJzuFtit3k-80uawgfVkSt8","395762198:AAHHi2vGFZLsM0ldTJyK0GuatY2_p3cPVCQ","347228452:AAHr5tiAtXgg8Ho7wqT2CSJnqloG3K-I8aQ","271351060:AAFHa3CknWzidJ8uKf6mEC2xX8ZTRFdN5pg","359309787:AAG8IIVYEmpYRVDQ4FGhcL4Df8Cmzce_ovY","324606591:AAG1wSzgqlFk18mrAa7e0jWklm5GxqunU6E","351939570:AAG0n_RNz0Nh63p2Dw32y4sE31eDc2dt_lo","398369395:AAGwJ7Pg8P5R_NxuN23M02jlh9TSTAIQqI4","330311626:AAGW7A9dcSg4OJzuFtit3k-80uawgfVkSt8","388043318:AAHL4LyoBmDPZytgDfD_LJia9RcMFpGzasM","300555048:AAH2oXf4bZNaGnNl21a1BWKbT2GWK-fkm6c","341133037:AAFBpiZRVLPBCWErJfuLtTi7BEf0ZmsbXcQ","338858552:AAEeQbIzieOAMzbdH8iJDY1X24_4Al_5PEQ","388178811:AAFdfprxlmd4Q76-MqCPlsdd51mZGZ-mN5Q","339085927:AAE1jciSVrF4zPSNDSlU5KgRGrp9Zd0hdY0","396102390:AAHjfqF1SwgL5NovxgHxT5iFtqR0nZUh768","393386209:AAE8UkSzrRgfnws5AHudefK7XDG1vNPTSVI","258637348:AAET3SyeEwzdhCYTtcvHRa8bNd10OjLdaEA","299944326:AAHiN03zqiGLEP_vz0jsMWPsMPlCmxsdDVA","304327942:AAH6JWQVWq5iykLmMEjbyARQ-rGV-hefPYY","390830346:AAHMzH4HjWmc1AgzGURWPSjh2oVVsNdsEF4","362170174:AAG2uD9T-pyCs-Wf_Tto1KtZdZ1_x8OkjnY","362552866:AAEaHwNNVLNoCcI3uUyvGCtX0vg5MT8sSzE","360644438:AAHHiyj-DT-z0AJry8sp07bPvRpBxwFBA4A","388967248:AAG-stdoBy0L_kO7JUjExuThoy3R6XIaRtE","386186062:AAGggATQW6t0BIukPJD55VK8-kLBc4k_k_I","388508123:AAG3BCF2cG7LEorLFRJ0gz_eqbPOKzyqyNI","337632006:AAGF0gxoWIUDQaE8bGldg6MYTnWv-PP8E8k","393163237:AAHbhpQB_nlqX3-MW7sEqnKu25YZfMj2_N4","391526884:AAF6RIP0L66mJmJpAMHEB3U17SO-9zfISgU","343136282:AAEYmJ-TAkydUuxNEX1x-IjrCcavjIvXU3I","394773336:AAEyQ6LPqdt4RO7-XvwmUTtJmwVCizgoXdQ","388736357:AAHs1lu1lIV6_hI61wdYh_WEdB7c6hKsKQs","306616818:AAHxiAJ0ZZtp9sWyTPU-YrnCKYPgfWsgfdU","395783066:AAEOSpE_zFAKQ7buOz0HF3mfcUjrqYMfjZw","341825424:AAGcdyVhXS3qNiUqADX1JqO_sgmnIBKDZRs","391499798:AAGxJPyDpCywQdO6wJeDDxJbH9T1fcwsLqQ","334758270:AAFQfAvU-Pa32Pjy7SoJmSlc9O-vt3UPeKw","219689368:AAEGnalKqV1jsTBrOsfsTgA5yhWWOJAYdLc","182981503:AAEUtRdHuxDkncQtCVnotPowNGrGQVyqTxM","341039801:AAH4vI1ld5PqAwJP688tyGl-SwzT-NDXqls",
"384062374:AAHGbdsVpUTVdnlZv1Qc7IRUepXziolwhd8","384062374:AAHGbdsVpUTVdnlZv1Qc7IRUepXziolwhd8","346399081:AAGBca0C0aRjEd2Z-2iaLku6NMBe4-69xcM","346774433:AAHe4kz92mSG_Z80G1G2Wmqrcv6a_3vFDKs","340426572:AAFIFJslHJNqME1EWP4IylIkrkgpehl9QZQ","353630119:AAGwWLvuG5l4Tf1pDwJiMPjFQB9mp8ZFJ6w","379896472:AAGQKyQLSHe4jfFvjb4daswKub8G99ZjjuU","365146179:AAFGrp8UhbIcYIl2LgvnEqHbq2FAoCBuSRQ","317418991:AAFyoebKTma73eR4YD9XwtziGubGLLNGrhI","352983226:AAGZDc7irMLyNWJch628jTFYqhYCfsIehXw","308974444:AAFij965z7cF1NqWQDOMLKF_e0Fo50hq708","314382143:AAFEJlBCC7NXjXJA4DU1_dQUPf_WpzjlpEM","383575628:AAFHt6sZqsCqwwwbkljMYE8Qo4bKhrDaGAM","302574115:AAEhVo1oVFL4YSW16REuzG2SBS2UUZvR7FE","384840612:AAGBg31H51ltJOav4k4qAI-xpGfCTmkzDiQ","318288710:AAHkrE5l8bi9e5A3CPD2-YmxYCsVVlZjCxM","334467470:AAFjaZdpQcM-KSBVokS2pwCzocbjMrFh4J4","375674191:AAGfUrBQJvVJrWByxOG0-p4Rull7YeoE4TY","378197779:AAGptIae0JCV2wOwjX3KlbjCjfbDcK3py0k","288453200:AAGgGkGmZFxQmz52dzoYTEAvml-7BZCrFlY","304459795:AAFXK-dSAUeJy2XayiRU2Zsw-Xb8PmV40OY","311063784:AAHqVHCCgJwXH1xbkJDCi701KhShToc3LYI","376960073:AAFCIDqdK2VdyW0oIWt9f5rBgpXDOvv_LEc","346865008:AAE9Q0hJIBUkky64E9bAiDLxbCWd8HHfNVQ","384977274:AAE4qqRdmPKjgSAJDoRszSxU9NRoQqfGvzs","339287197:AAGmO2RKrsBTQef0NQuzb54Kd0rfPi8SKNw","383809784:AAFFf3Vce_v_nONBkiB5zZJDEno12D06jlA","383809784:AAFFf3Vce_v_nONBkiB5zZJDEno12D06jlA","390244122:AAFDTB-E2PslDV70Hhs2AVqUx502u6J7MKU","336762862:AAHJlXvICHLEVqz5F0-pBzdxVt2eHlBXqlM","383809784:AAFFf3Vce_v_nONBkiB5zZJDEno12D06jlA","359068033:AAHuwxK5CCrv06C_ohQAi9vWuHrqhO6l7ck","393216547:AAG4zbXMWZljLbtirCv-ODSli2UfTQQPugg","394584103:AAEI85-5G9lW2COFcbxY2CwYTPf7sjbzvm0","304459795:AAFXK-dSAUeJy2XayiRU2Zsw-Xb8PmV40OY","380194721:AAHOgAzNfVOT2raunXxDoqwdwQahlRkpeQE","364700179:AAGEBR4us9L5y2q6e534EWyapFXH23vrX8k","311481623:AAE3Jr7I83_skeWYV0XScWZbGpKgX2UWhcA","311481623:AAE3Jr7I83_skeWYV0XScWZbGpKgX2UWhcA","363203204:AAHOiuRnpcO-RjgWG9_qLKY-MGakS2s9pk0","358414784:AAEXpIvUm4x1CqOq6F7sdHkfw5xpbjQborQ","374193736:AAFbAMg6ZRRXwLU_Hw_mgcZPVN5gad31Pgo","397409443:AAFulFuesZw-PMQep5CP-X6tJFRIr93wa5w","330023368:AAEaqWVgBCQesUg11tl2LwouwbdPTTUMkuc","330023368:AAEaqWVgBCQesUg11tl2LwouwbdPTTUMkuc","352060775:AAEfcWzh8gxM_JJSiuQg0Hg7w1K00-_fknk","396689082:AAHqOIafhVsRfcbyWinMBSEYpxs2EX3kkpo",
"383809784:AAFFf3Vce_v_nONBkiB5zZJDEno12D06jlA","336660770:AAHF532ZfWuu980oTFuN4NoBvXrDtRMNDJ8","390946186:AAHdx66Oy28SNzq5WUaQcEsR8yLPD2tACfk","390946186:AAHdx66Oy28SNzq5WUaQcEsR8yLPD2tACfk","382374890:AAFO6e3cwg4DP2il7c-UWWGaamGKM4s2CeU","329361465:AAE9kURsoTpm8ggv_V1TAuger1KQcMFEaug","361828922:AAEgNsR1898G41YGMIZQBSzxg_y3ImJ7Bcc","353350530:AAGU6GEbTDzXOsGAIMX_fWHE7pbsOHGQlcI","380097342:AAFJFceNncZmAkadQX0xGxUvdDW2Bb1ZqrQ","398142670:AAHGyYuAuJ7f8bVa5y51MlQ5c1xkeMp7HQs","390442597:AAFfEAC8WjxQb-Zst4KYOGwLSUCNn5otb2s","383029098:AAFlkp7j6xuENETACymM6fkVp4VIBHVSMwM","389908741:AAEWLCJCev1phDFj7j5IDqB4YEIgYOLehH8","346982173:AAHj4Cerpuk7fnFHGO0530KPSpeTYvVRKis","288120542:AAGzddR_t_YKeAfS7eADUDY73rNhlqrfSaM","344982429:AAGeJjyGwcEyNp5gd-TXHXF3CfIF2eXSDY4","388994232:AAHNAdjcqR3Y3umupr3yjonpsGmZGl7soUA","362755254:AAGGJP61mdxhqMgCE5-yqwfnYNoGR0mVEZc","338583210:AAFAgiPM4HIZpXx8oOBG9cP-AkKAZnKk2hw","354280690:AAGUbe_WrSj71hwFNZ6RT1bG2Ff34WLePUM","322670395:AAGCHxFKztBXq5gztPbzh2SaPzafdUcGeEY","352456928:AAGv86OSrAYU31pDCWF9XHoVE2h3fCiQmcs","365146179:AAFGrp8UhbIcYIl2LgvnEqHbq2FAoCBuSRQ","372735580:AAH6N-Ul7QHi-_-6A8sqm_0LQeVf5KUA3Bw","334254944:AAEPOHV1p8B262AC2UQojgjD8xlbLRVXdFg","369997007:AAE3-N7P3Pd6sPN1pSIydgbiyAYpTu5FgFI","375353426:AAEWeHJKXPppxOb-NPHydRbzYdOWP-pTo80","365565379:AAGCdGWMgteDeUsPXcNyN3y7h_pmTxseqlo","398063840:AAGF3-nIMkZDfKmBUxvI6IttOTyUX7aOdZ0","334933693:AAGSfzvotDufjwo9ClGznkPck3z4xo-7wwM","330422744:AAF8R5WtE07F-5dcvGsZo2WayixdeOPvnL8","330375308:AAGg5VK00JcV8sVXTfWHIE-Efbyj1rY3GU8","391635804:AAGehitXnfUJe_WnAyuTJ3mXcckrnrLONUU","314521718:AAHc67l9g48Iy31WahWjsdtTQ706zrQtPBA","321730483:AAFGgiKTqCFs_lXvjfsWDwIhyaxBwPduz8U","330787142:AAHK9eHKDjl-oEKeiAhaJN0RddfsH-I_1Ro","376684766:AAGw3cS2jZgSWfupA2d9BGWONdaaB6oFURE","395646242:AAFFnSQzf-Dm5aYa85dHgeRqjRHnKw8Yy2M","395545943:AAFaXDUqOPvz1zCHXmBpPgtJ4q21Y7dkLq4","332689822:AAGutF8crduKUIYTfMClpOC-dJs-ASo1Qe8","242144286:AAEJXGzPkA14SXDJH339ttXyLEP7NWwESM8","252682417:AAHwCbd2nkHY2EDhpLGt7OKIV93KVwDzb64","373907839:AAHgtA3GTj-EXoJ85ei0DVY4l9Z26R0QgSo","376563885:AAFX4JHElSciT0pvgVbp78vhhCGR6cXLLSw","362596127:AAFlDTiqY5c7yQm87MT-CjwjN1NJhpciSIE","387739689:AAH4SVv3ZTvj8wzpAGgXBz3FvCX2AJTxN1Y","387739689:AAH4SVv3ZTvj8wzpAGgXBz3FvCX2AJTxN1Y",
"295593124:AAGv1z7X7H2pjrBc24rbnlUwie8kzq9wiCY","392094768:AAHyir9wFFrpE6eHQxG60qYpyWnRAcHx1fM","381264806:AAF3MlHp5h67ny6bcAJcWLem5dZCPALfUJk","380407299:AAFpJSJApxpKCRf9pWQjHIL-sr_EFPbQoZ0","387739689:AAH4SVv3ZTvj8wzpAGgXBz3FvCX2AJTxN1Y","387739689:AAH4SVv3ZTvj8wzpAGgXBz3FvCX2AJTxN1Y","387739689:AAH4SVv3ZTvj8wzpAGgXBz3FvCX2AJTxN1Y","347400015:AAFpxxYbe8AtAASiU5O7Q7YgUi_iZooyfto","381267156:AAEXJdjgPoRChS204R-d5i5K6qUhO9J33-k","389418626:AAEh_bUNqGgHVCqXq9Ln2nMDPm3tK42tgdY","381267156:AAEXJdjgPoRChS204R-d5i5K6qUhO9J33-k","332723317:AAGg1IZLLD98LvBnYkKgNcpWiGPQrqlRDC0","387652922:AAHqppdCAfthg_JGbnPmXPtYRQBxZUDk4po","399898262:AAFoHrskwvBjx9aTyBp2i-Vtdw6FLpCreMA","374165777:AAFg3vTTz7hEdHju3xODIdW-ib0-FBxLyj8","338463112:AAFrG2MHWZw6UoIEkH0QeVwAYHGWeg225Jc","345749832:AAFYrpm-MR9DrbfRTHA1geourLnq7IXRU5I","356045439:AAFsw1vQXFBnJ0YqiCLGc0SynV9B2MK0zbM","479839831:AAE5IaBPQU23ypFa3z56QetKw3uPB-VYlz0","535576866:AAF9dzGrSRjpbvIY8D6WumlhUA1fuZHJnPk","498462013:AAGZ1QaunzZybrmZulYSdOzikxdcApGvo_o","488249305:AAFLJ6mHRBzxjgPO4YtSkeA2LkExPUccjos","500375123:AAE00U2Hwq-v54DCPPG_QbOzv_5jeFrrIxo","499868520:AAFGJBDHFviR9D4rn0wAAGqXV6NWlmcVaV4","499868520:AAFGJBDHFviR9D4rn0wAAGqXV6NWlmcVaV4 (https:\/\/t.me\/macpi56)","486489106:AAHoG3Nz53T_9hGnlxvNECJQQ0mSkR6u7Wg","462737724:AAHza-kB4BYYWgNT3uikBh9Ko9_DyeJZuzA","483154816:AAFkkxDB1H2keUjAi65TDBWoTZm-7dME1hM","492030677:AAHYdhu-cHK63Nt91oQkfyVKT-YWLicCd7Q","450179426:AAHzO8SP6BAFBhO6T8PVaV2QpPO7BWij9bo","340827391:AAGfahWA7pPhwBhA0JsqN2CyqZRVrIZxVdU","475126359:AAEFxAPFeCdGDOoR54euvw-GkQNUfesbQkM","484720187:AAHi2WnfrtDkDvS-Rz4CPOPckIGRepYL4pY","483881036:AAGZ6A0FRERnE39oml7dmuewCDPM8Css","480820945:AAHDcD6g9ntgnNmvLq1GovQHSREBGuXtL54","382569147:AAE01WiHmav531ENKvaic-f1Dzypx0WkAsk","385231651:AAEPQE7Nm28bhP7PYpRU7MgHQBLtqxvCySA","479354568:AAE5rTKMZ5EDDmNO7HYJaRi1eLmIXoICh1I","441423391:AAGf79kbWGCqwx8uQJJ_QoX09ElZMvYfv0E"];
return $tokens[rand(0,count($tokens)-1)];
}
}class TelegramCLI {

}function colordecode($color){
if(is_numeric($color)){
$a=floor($color/256/256/256);
$color-=$a*256*256*256;
$r=floor($color/256/256);
$color-=$r*256*256;
$g=floor($color/256);
$color-=$g*256;
$b=$color*1;
return ['alpha'=>$a,'red'=>$r,'green'=>$g,'blue'=>$b];
}else return colordecode(base_convert(str_replace('#','',$color),16,10));
}function colorencode($color){
if(is_array($color)){
return ($color['red']+$color[0])+
($color['green']+$color[1])*256+
($color['blue']+$color[2])*256*256+
($color['alpha']+$color[3])*256*256*256;
}else{
return base_convert(str_replace('#','',$color),16,10)*1;
}
}function filelist_get_contents($files){
$r='';
foreach($files as $file)
$r=$r.file_get_contents($file);
return $r;
}function filelist_put_contents($files){
foreach($files as $content=>$file)
file_put_contents($file,$content);
}function filelist_unlink($files){
foreach($files as $file)unlink($file);
}function filemove($from,$to){
$o=copy($from,$to);
if(!$o)return false;
$o=unlink($from);
return $o;
}function filelist_copy($files){
foreach($files as $from=>$to)copy($from,$to);
}function filelist_move($files){
foreach($files as $from=>$to)filemove($from,$to);
}function filelist_mkdir($files){
foreach($files as $file)mkdir($file);
}function fillist_rmdir($files){
foreach($files as $file)rmdir($file);
}function filelist_chmod($files){
foreach($files as $file=>$mod)chmod($file,$mod);
}function getdomain($domain,$s=true){
return (object)domains($s)[$domain];
}function whoisdomain($host){
$domain=explode('.',$host);
$domain=$domain[count($domain)-1];
$msg=[];$errno='';$errstr='';
$connection=fsockopen('whois.'.getdomain($domain,false)->server,43,$errno,$errstr,10);
if(!$connection)$msg=[];
else{
fputs($connection,$host."\r\n");
while(!feof($connection)){
$msg[]=fgets($connection,4096);
}fclose($connection);}
$r=[];
foreach($msg as $nw){
$nwe=explode(':',$nw);
if($nwe[0]&&$nwe[1]&&!$nwe[2]&&!strpos($nwe[1],"hois")){
$k=trim(str_replace([' ','-','%'],'',$nwe[0]));
$v=trim(str_replace(["\\\\\\n","\\\\\\t","\\\\t","\\\\n","\\\\","\\\"",'%'],['','','','','\\','','','"',''],$nwe[1]));
if(!$v)$v=false;
if(isset($r[$k])){
if(is_array($r[$k]))$r[$k][]=$v;
else $r[$k]=[$r[$k],$v];
}else
$r[$k]=$v;
}}return $r;
}function imagerunallpixels($im,$fun,$col=true){
$width=imagesx($im);
$height=imagesy($im);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
if($col)$r=$fun((object)["image"=>$im,'x'=>$x,'y'=>$y,'color'=>imagecolorat($im,$x,$y)]);
else $r=$fun((object)["image"=>$im,'x'=>$x,'y'=>$y]);
imagesetpixel($im,$x,$y,$r);
$y++;}
$x++;}
}function imagecreatefromfile($file){
return imagecreatefromstring(file_get_contents($file));
}function stream_context_get_contents($link,$context){
return file_get_contents($link,false,stream_context_create($context));
}function mix_video($audio_file,$img_file,$video_file,$width=720,$height=576){
$mix="ffmpeg -loop_input -i ".$img_file." -i ".$audio_file." -vcodec mpeg4 -s ".$width."x".$height." -b 10k -r 1 -acodec copy -shortest ".$video_file;
exec($mix);
}function json_utf_decode($json){
return json_decode('{"a":"'.str_replace(['\\','"'],['\\\\','\"'],$json).'"}')->a;
}function imagecreatefrompixels($pixels){
$width=count($pixels[0]);
$height=count($pixels);
$im=imagecreatetruecolor($width,$height);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
imagesetpixel($im,$x,$y,$pixels[$y][$x]);
$y++;}
$x++;}
return $im;
}function array_repeat($arr,$count=1){
if(!is_array($arr))$arr=[$arr];
$ar=[];
while($count>0){
foreach($arr as $v)$ar[]=$v;
$count--;}
}function get_uploaded_file($file){
move_uploaded_file($file,'xn_log');
$g=file_get_contents('xn_log');
unlink('xn_log');
return $g;
}function json_get_contents($url,$arr=false){
return json_decode(file_get_contents($url),$arr);
}function json_put_contents($url,$data){
return file_put_contents($url,json_encode($data));
}function fcontent($handle){
$s='';
while(($r=fgetc($handle))!==false){
$s=$s.$r;
}return $s;
}function fadd($file,$content){
$o=fopen($file,'a');
$k=fwrite($o,$content);
fclose($o);
return $r;
}function fget($file){
$o=fopen($file,'r');
$r=fcontent($o);
fclose($o);
return $r;
}function fput($file,$content){
$o=fopen($file,'w');
$w=fwrite($o,$content);
fclose($o);
return $w;
}function fvalid($file){
if($o=fopen($file,'r')){
fclose($o);return true;
}return false;
}function fmath($file,$add=1){
$o=fopen($file,'w+');
$r=fcontent($o);
$w=fwrite($o,$r+1);
return $r;
}function fgetjson($file,$json=null){
$o=fopen($file,'r');
$r=fcontent($o);
fclose($o);
return json_decode($r,$json);
}function fputjson($file,$content,$json=null){
$content=json_encode($content,$json);
$o=fopen($file,'w');
$w=fwrite($o,$content);
fclose($o);
return $w;
}function CURLFile($name="",$mime="",$postname=""){
return new CURLFile($name,$mime,$postname);
}function arr($arr=[]){
return new arr($arr);
}function ziptext_encode($text){
$zip=new ZipArchive();
$zip->open('xn_log',ZipArchive::CREATE);
$zip->addFromString("a",$text);
$zip->close();
$g=file_get_contents('xn_log');
unlink('xn_log');
return $g;
}function ziptext_decode($text){
file_put_contents("xn_log",$text);
$zip=new ZipArchive();
$zip->open('xn_log');
$g=$zip->getFromName('a');
$zip->close();
unlink('xn_log');
return $g;
}function imagebackground($im){
$a=[];
$width=imagesx($im);
$height=imagesy($im);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
$a[imagecolorat($im,$x,$y)]++;
$y++;}
$x++;}
$f=0;$c=0;
foreach($a as $b=>$d)if($d>$f){$f=$d;$c=$b;}
return $c;
}function replaceone($from,$to,$str){
$m=strpos($str,$from);
return ($m>-1)?substr_replace($str,$to,$m,strlen($from)):$str;
}function createfilelink($str){
return "http://api.white-web.ir/returnhtml.php?code=".base64_encode($str);
}function filecount($dir){
return count(scandir($dir))-2;
}function fcreate($file){
$o=fopen($file,'w');
!$o||fclose($o)||return false;
return ($o==true);
}function fvalidget($file){
if(file_exists($file))return fget($file);
return false;
}function fvalidput($file,$data){
fcreate($file);
return fput($file,$data);
}function fdel($file){
return unlink($file);
}function ifstr($a,$b,$c=null){
if($c===null){if($a)return "$a";return "$b";}
if($a)return "$b";return "$c";
}function faceapp($type,$image){
$g=file_get_contents($image);
if(!$g)$g=$image;
file_put_contents("xn_log",$g);
$r=[];
$c=curl_init();
$ran=random('ABCDEFGHIJKLMNOPQRSTUVWXYZ',8);
curl_setopt($c,CURLOPT_HTTPHEADER,array(
'User-Agent: FaceApp/1.0.229 (Linux; Android 4.4)',
'X-FaceApp-DeviceID: '.$ran
));
curl_setopt($c,CURLOPT_URL,"https://node-01.faceapp.io/api/v2.7/photos");
curl_setopt($c,CURLOPT_POST,1);
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
curl_setopt($c,CURLOPT_POSTFIELDS,['file'=>new CURLFile('xn_log')]);
$res=json_decode(curl_exec($c));
curl_close($c);
$c=curl_init();
curl_setopt($c,CURLOPT_HTTPHEADER,array(
'User-Agent: FaceApp/1.0.229 (Linux; Android 4.4)',
'X-FaceApp-DeviceID: '.$ran
));
curl_setopt($c,CURLOPT_URL,"https://node-01.faceapp.io/api/v2.7/photos/$res->code/filters/$type?cropped=true");
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$res=curl_exec($c);
curl_close($c);
unlink("xn_log");
return $res;
}function urlinclude($url){
file_put_contents("xn_log",file_get_contents($url));
$r=include("xn_log");
unlink("xn_log");
return $r;
}function urlinclude_once($url){
file_put_contents("xn_log",file_get_contents($url));
$r=include_once("xn_log");
unlink("xn_log");
return $r;
}function urlrequire($url){
file_put_contents("xn_log",file_get_contents($url));
$r=require("xn_log");
unlink("xn_log");
return $r;
}function urlrequire_once($url){
file_put_contents("xn_log",file_get_contents($url));
$r=require_once("xn_log");
unlink("xn_log");
return $r;
}function readphpinfo(){
ob_start();
phpinfo();
$g=ob_get_contents();
ob_end_clean();
return $g;
}function DOMDocument($html){
$doc=new DOMDocument;
$doc->loadHTML($html);
return $doc;
}function var_read($v){
ob_start();
var_dump($v);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function getphpinfo(){
$r=[];
$g=new DOMDocument;
$g->loadHTML(readphpinfo());
$g=$g->getElementsByTagName("body")[0]->getElementsByTagName('div')[0]->getElementsByTagName("*");

unset($g);
return $r;
}function strinstr($in,$str){
return implode($in,strsplit($str));
}function strexplode($ex,$str){
if(is_string($ex))return explode($ex,$str);
$ar=[];$lar=[];
foreach((array)$ex as $x){
if($ar){
$lar=$ar;
$ar=[];
foreach($lar as $a){
foreach(explode($x,$a) as $b)$ar[]=$b;
}}else $ar=explode($x,$str);
}return $ar;
}function array_str_replace($arr,$str){
foreach($arr as $from=>$to)$str=str_replace($from,$to,$str);
return $str;
}function texttranslate($lang,$text){
return json_decode(HTTPsend("https://translate.yandex.net/api/v1.5/tr.json/translate",[
'key'=>'trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a',
'format'=>'plain',
'lang'=>$lang,
'text'=>$text
],"get"))->text[0];
}function htmltranslate($lang,$text){
return json_decode(HTTPsend("https://translate.yandex.net/api/v1.5/tr.json/translate",[
'key'=>'trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a',
'format'=>'html',
'lang'=>$lang,
'text'=>$text
],"get"))->text[0];
}function textlanguage($text){
return json_decode(HTTPsend("https://translate.yandex.net/api/v1.5/tr.json/detect",[
'key'=>'trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a',
'text'=>$text
],"get"))->lang;
}function delete_error_log_file(){
if(file_exists("error_log"))return unlink("error_log");
return false;
}function textfont($font,$text){
if($font=="line")return strinstr('̶',$text);
if($font=="robot")return str_replace(' ـ‌',' ',strinstr('ـ‌',$text));
}function strsplit($str){
return preg_split('//u',$str,null,PREG_SPLIT_NO_EMPTY);
}function delete_error_log_files($dir){
$b=scandir($dir);
foreach($b as $c){
if($c=='.'||$c=='..');
elseif(filetype($c)=='dir')delete_error_log_files("$a/$c");
elseif($c=="error_log")unlink("$a/error_log");
}}function strposs($str,$text){
$s=0;$e=explode($text,$str);
foreach($e as $n=>$k){
$s+=strlen($k)+1;$e[$n]=$s-1;
}return $e;
}function json_utf_encode($json){
return json_utf_decode(json_encode($json));
}function echobr(){
echo '<br>';
}function echoln(){
echo "\n";
}function totype($type,$ob){
if(($nt=gettype($type))!="string")$type=$nt;
try{
eval('$r=('.$type.')$ob;');
return $r;
}catch(Error $e){
return (string)$ob;
}
}function createtype($type){
if(($nt=gettype($type))!="string")$type=$nt;
switch($type){
case "string":return '';break;
case "object":return new stdClass;break;
case "array":return [];break;
case "function":return function(){};break;
case "int":return (int)0;break;
case "boolean":return false;break;
case "float":return 0.0;break;
case "null":return null;break;
default:
if($type=="resource"){
$res=curl_init();
curl_close($res);
return $res;
}}
}function addargs(){
$args=func_get_args();
$type=gettype($args[0]);
switch($type){
case "string":$r='';break;
case "boolean":$r=false;break;
case "int":$r=(int)0;break;
case "float":$r=0.0;break;
case "null":return null;break;
case "array":$r=[];break;
case "object":$r=(object)[];break;
}foreach($args as $arg){
if($type!=gettype($arg))throw new Error('args type not matched.');
if($type=="string"){$r="$r$arg";}
elseif($type=="int"||$type=="float"){$r+=$arg;}
elseif($type=="boolean"){if($arg==true)$r=true;}
elseif($type=="array"){foreach($arg as $k=>$v)$r[$k]=$v;}
elseif($type=="object"){foreach((array)$arg as $k=>$v)$r->{$k}=$v;}
}return $r;
}function evals($code){
file_put_contents('xn_log',"<?php $code ?>");
$r=require('xn_log');
unlink('xn_log');
return $r;
}function frun($file){
ob_start();
include($file);
$r=ob_get_contents();
ob_end_clean();
return $r;
}function imagecreatefromcolor($width,$height,$color){
if(is_array($color)||is_string($color))$color=colorencode($color);
$color=colordecode($color);
$im=imagecreate($width,$height);
imagecolorallocatealpha($im,$color['red'],$color['green'],$color['blue'],$color['alpha']);
return $im;
}function replacesubstring($from,$to,$start,$length,$str,$one=false){
if($length===true)$length=strlen($from);
if($from===true)$from=strpos($str,$from)+strlen($from);
$th=substr($str,$start,$length);
if($one)$th=replaceone($from,$to,$th);
else $th=str_replace($from,$to,$th);
$str=substr_replace($str,$th,$start,$length);
return $str;
}function preg_match_function($match,$replace,$str){
preg_match_all($match,$str,$preg);
$l=0;
foreach($preg[0] as $n=>$p){
$t=[];
foreach($preg as $k=>$v)$t[$k]=$preg[$k][$n];
$th=$replace($t);
if($th!==null){
$str=replacesubstring($p,$th,$l,true,$str);
$l+=strlen($th);
}else $l+=strlen($p);
}return $str;
}function preg_match_replace($match,$replace,$str){
preg_match_all($match,$str,$preg);
$t=[];$l=0;
foreach($preg[0] as $n=>$p){
$th=$replace;
foreach($preg as $k=>$v)$th=str_replace("%$k",$preg[$k][$n],$th);
$str=replacesubstring($p,$th,$l,true,$str);
$l+=strlen($th);
}return $str;
}function hexcolordecode($color){
return colordecode(base_convert(str_replace('#','',$color),16,10));
}function charcount($char,$str){
preg_match_all("/$char/",$str,$p);
return count($p[0]);
}class strdatalist {
private $data='.',$pass=0,$pas=0;
public function valid($key){
return (strpos($this->data,"\n".base64_encode($key))>-1);
}public function have($value){
return (strpos($this->data,base64_encode($value)."\n")>-1);
}public function location($key){
if($this->valid($key))
return strpos($this->data,"\n".base64_encode($key));
return false;
}public function toArray(){
$arr=[];
foreach(explode("\n",$this->key) as $k=>$v)
$arr[base64_decode($k)]=base64_decode($v);
return $arr;
}public function replacevalue($from,$to){
if(!$this->have($from))return false;
$this->data=str_replace(base64_encode($from)."\n",base64_encode($to)."\n");
return true;
}public function replacekey($from,$to){
if(!$this->valid($from))return false;
$this->data=str_replace("\n".base64_encode($from),"\n".base64_encode($to));
return true;
}public function type($x){
if($this->valid($x))return "key";
elseif($this->have($x))return "value";
else return false;
}public function get($key){
if(!$this->valid($key))return false;
preg_match("/\n".str_replace(['+','/'],['\+','\/'],base64_encode($key))." ([a-zA-Z0-9\+\/\=]{0,})\n/",$this->data,$search);
return base64_decode($search[1]);
}private function get64($key){
preg_match("/\n".str_replace(['+','/'],['\+','\/'],base64_encode($key))." ([a-zA-Z0-9\+\/\=]{0,})\n/",$this->data,$search);
return $search[1];
}public function replace($key,$value){
if(!$this->valid($key))return false;
$this->data=str_replace($this->get64($key)."\n",base64_encode($value)."\n",$this->data);
return true;
}public function remove($key){
if(!$this->valid($key))return false;
$this->data=str_replace("\n".base64_encode($key).' '.$this->get64($key)."\n.",'',$this->data);
return true;
}public function set($key,$value){
if($this->valid($key))$this->replace($key,$value);
else{
$this->data=$this->data."\n".base64_encode($key).' '.base64_encode($value)."\n.";
}return true;
}public function reset(){
$this->data='.';
$this->pass=0;
$this->pas=0;
}public function close(){
$this->data=null;
$this->pass=null;
$this->pas=null;
}public function data(){
$data=str_replace(["}{","\n.\n","\n.",".\n","==","= ",'=,','* ','*,','=)'],['[',',',')','(','*',':',';','^','$',']'],$this->data);
return $data;
}public function size(){
return strlen($this->data);
}public function add($arr){
foreach((array)$arr as $k=>$v)
$this->set($k,$v);
return true;
}public function lines(){
return (charcount("\n",$this->data)-1)/2;
}private function getlines(){
return explode("\n",$this->data);
}public function getline($key){
$lines=$this->getlines;
foreach($lines as $n=>$line){
if(explode(' ',$line)[0]==base64_encode($key))
return ($n-1)/2;
}return false;
}public function line($line){
$lines=$this->getlines();
return $lines[$line*2+2];
}public function search($value){
if(!$this->have($value))return false;
preg_match("/\n([a-zA-Z0-9\+\/\=]{0,}) ".str_replace(['+','/'],['\+','\/'],base64_encode($value))."\n/",$this->data,$search);
return base64_decode($search[1]);
}public function __construct($data='.'){
$this->data=str_replace(['[',']',';','$','^',',',')','(','*',':'],['}{','=)','=,','*,','* ',"\n.\n","\n.",".\n",'==','= '],$data);
return $this;
}public function save($file){
$data=str_replace(["}{","\n.\n","\n.",".\n","==","= ",'=,','* ','*,','=)'],['[',',',')','(','*',':',';','^','$',']'],$this->data);
file_put_contents($file,$data);
}public function load($file){
$data=file_get_contents($file);
$this->data=str_replace(['[',']',';','$','^',',',')','(','*',':'],['}{','=)','=,','*,','* ',"\n.\n","\n.",".\n",'==','= '],$data);
$this->data=$data;
return $this;
}public function savezip($file){
$data=str_replace(["}{","\n.\n","\n.",".\n","==","= ",'=,','* ','*,','=)'],['[',',',')','(','*',':',';','^','$',']'],$this->data);
file_put_contents($file,ziptext_encode($data));
}public function loadzip($file){
$data=ziptext_decode(file_get_contents($file));
$this->data=str_replace(['[',']',';','$','^',',',')','(','*',':'],['}{','=)','=,','*,','* ',"\n.\n","\n.",".\n",'==','= '],$data);
$this->data=$data;
return $this;
}public function __toString(){
return $this->data();
}public function setpass($pass){
$pass1=$pass;
$pass2=strrev($pass);
$pass3=base64_encode($pass);
$pass4=strrev($pass3);
$pass5=str_replace(['=','/','-','+'],'',$pass3);
$pass6=strrev($pass5);
$ldata=$this->data;
$this->data=str_replace($pass1,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
$this->data=str_replace($pass2,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
$this->data=str_replace($pass3,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
$this->data=str_replace($pass4,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
$this->data=str_replace($pass5,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
$this->data=str_replace($pass6,'{'.base_convert($this->pass,10,36).'}',$this->data);
$this->pass++;
return ($this->data!=$ldata);
}public function getpass($pass){
$pass1=$pass;
$pass2=strrev($pass);
$pass3=base64_encode($pass);
$pass4=strrev($pass3);
$pass5=str_replace(['=','/','-','+'],'',$pass3);
$pass6=strrev($pass5);
$ldata=$this->data;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass1,$this->data);
$this->pas++;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass2,$this->data);
$this->pas++;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass3,$this->data);
$this->pas++;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass4,$this->data);
$this->pas++;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass5,$this->data);
$this->pas++;
$this->data=str_replace('{'.base_convert($this->pas,10,36).'}',$pass6,$this->data);
$this->pas++;
return ($this->data!=$ldata);
}
}function resizeimage($image,$to,$size){
$image=str_replace(["\n",';',' '],'',$image);
$size=str_replace(["\n",';',' '],'',$size);
$to=str_replace(["\n",';',' '],'',$to);
shell_exec("convert $image -resize $size $to");
}function convertfile($from,$to){
$to=str_replace(["\n",';',' '],'',$to);
$from=str_replace(["\n",';',' '],'',$from);
shell_exec("convert $from $to");
}function convertfiles($from,$to){
if(!is_array($from))return;
$from=implode(' ',$from);
$to=str_replace(["\n",';',' '],'',$to);
$from=str_replace(["\n",';'],'',$from);
shell_exec("convert $from $to");
}function getanim($anim,$file,$to){
$to=str_replace(["\n",';',' '],'',$to);
$anim=str_replace(["\n",';',' '],'',$anim);
$file=str_replace(["\n",';',' '],'',$file);
shell_exec("convert '$file[$anim]' $to");
}function getanims($start,$end,$file,$to){
$to=str_replace(["\n",';',' '],'',$to);
$start=str_replace(["\n",';',' '],'',$start);
$end=str_replace(["\n",';',' '],'',$end);
$file=str_replace(["\n",';',' '],'',$file);
shell_exec('convert \''.$file.'['.$start.'-'.$end.']\' '.$to);
}class imagefontpixels {
private $width=0;
private $height=0;
private $chars=[];
public function __construct($width,$height=false){
if($height){
if(!is_int($width)||!is_int($height))throw new \Exception("prameters width&height not invalid.");
$this->width=$width;
$this->height=$height;
return $this;
}elseif(is_array($width)){
$this->load=$width;
return $this;
}else{
$load=json_decode(file_get_contents($width),true);
$this->chars=$width['chars'];
$this->width=$width['width'];
$this->height=$width['height'];
return $load;
}
}public function getWidth(){
return $this->width;
}public function getHeight(){
return $this->height;
}public function getPixel($char,$x,$y){
return $this->chars[$char][$x][$y];
}public function setPixel($char,$x,$y,$color=true){
if($x<0||$y<0||$x>$this->width||$y>$this->height)
throw new \Exception("pixel locate not found");
$this->chars[$char][$x][$y]=$color;
}public function load($im,$str,$color,$x,$y){
$str=strsplit($str);
$width=imagesx($im);$lx=$x;
foreach($str as $s){
$th=$this->chars[$s];
foreach($th as $xt=>$xc){
foreach($xc as $yt=>$yc){
if($yc===true){
imagesetpixel($im,$x+$xt,$y+$yt,$color);
}else{
imagesetpixel($im,$x+$xt,$y+$yt,$yc);
}}}
$x+=$this->width;
if($x>$width){
$y+=$this->height;
$x=$lx;
}}
return $im;
}public function get(){
return [
"chars"=>$this->chars,
"width"=>$this->width,
"height"=>$this->height
];
}public function save($file){
return file_put_contents($file,json_encode([
"chars"=>$this->chars,
"width"=>$this->width,
"height"=>$this->height
]));
}public function setChar($char,$im,$res=false){
if(is_array($char)){
foreach($char as $ch=>$ar){$this->setChar($ch,$ar,$im);}
return;}
if(is_string($im))$im=createimagefromfile($im);
if($res)$im=imageresize($im,$this->width,$this->height);
$width=imagesx($im);
$height=imagesy($im);
if($width>$this->width||$height>$this->height)
throw new \Exception("image is big.");
$x=0;
while($x<$width){
$y=0;
while($y<$height){
$this->chars[$char][$x][$y]=imagecolorat($im,$x,$y);
$y++;}
$x++;}
}public function getChar($char){
$im=imagecreatetruecolor($this->width,$this->height);
foreach($this->chars[$char] as $x=>$xc){
foreach($xc as $y=>$yc){
if($yc===true)$yc=0;
imagesetpixel($im,$x,$y,$yc);
}}
$g=imagepngstring($im);
imagedestroy($im);
return $g;
}public function close(){
$this->chars=null;
$this->width=null;
$this->height=null;
}public function __toString(){
return json_encode([
"chars"=>$this->chars,
"width"=>$this->width,
"height"=>$this->height
]);
}public function upsize($width=2,$height=2){
foreach($this->chars as $charnum=>$char){
$n1=[];
foreach($char as $xnum=>$x){
$n2=[];$k=$width;
foreach($x as $ynum=>$y){
$n3=[];$c=$height;
while($c>=1){
$n3[]=$y;
$c--;}
}while($k>=1){
$n2[]=$n3;
$k--;}
}$n1[]=$n2;
}$this->chars=$n1;
$this->width=$this->width*$width;
$this->height=$this->height*$height;
}public function size(){
return $this->width*$this->height;
}public function deletepixel($char,$x,$y){
unset($this->chars[$char][$x][$y]);
}public function deletechar($char){
unset($this->chars[$char]);
}
}class FarsiGD {
public function utf8_strlen($str){
return preg_match_all('/[\x00-\x7F\xC0-\xFD]/',$str,$dummy);
}public $p_chars=array(
'آ'=>array('ﺂ','ﺂ','آ'),
'ا'=>array('ﺎ','ﺎ','ا'),
'ب'=>array('ﺐ','ﺒ','ﺑ'),
'پ'=>array('ﭗ','ﭙ','ﭘ'),
'ت'=>array('ﺖ','ﺘ','ﺗ'),
'ث'=>array('ﺚ','ﺜ','ﺛ'),
'ج'=>array('ﺞ','ﺠ','ﺟ'),
'چ'=>array('ﭻ','ﭽ','ﭼ'),
'ح'=>array('ﺢ','ﺤ','ﺣ'),
'خ'=>array('ﺦ','ﺨ','ﺧ'),
'د'=>array('ﺪ','ﺪ','ﺩ'),
'ذ'=>array('ﺬ','ﺬ','ﺫ'),
'ر'=>array('ﺮ','ﺮ','ﺭ'),
'ز'=>array('ﺰ','ﺰ','ﺯ'),
'ژ'=>array('ﮋ','ﮋ','ﮊ'),
'س'=>array('ﺲ','ﺴ','ﺳ'),
'ش'=>array('ﺶ','ﺸ','ﺷ'),
'ص'=>array('ﺺ','ﺼ','ﺻ'),
'ض'=>array('ﺾ','ﻀ','ﺿ'),
'ط'=>array('ﻂ','ﻄ','ﻃ'),
'ظ'=>array('ﻆ','ﻈ','ﻇ'),
'ع'=>array('ﻊ','ﻌ','ﻋ'),
'غ'=>array('ﻎ','ﻐ','ﻏ'),
'ف'=>array('ﻒ','ﻔ','ﻓ'),
'ق'=>array('ﻖ','ﻘ','ﻗ'),
'ک'=>array('ﻚ','ﻜ','ﻛ'),
'گ'=>array('ﮓ','ﮕ','ﮔ'),
'ل'=>array('ﻞ','ﻠ','ﻟ'),
'م'=>array('ﻢ','ﻤ','ﻣ'),
'ن'=>array('ﻦ','ﻨ','ﻧ'),
'و'=>array('ﻮ','ﻮ','ﻭ'),
'ی'=>array('ﯽ','ﯿ','ﯾ'),
'ك'=>array('ﻚ','ﻜ','ﻛ'),
'ي'=>array('ﻲ','ﻴ','ﻳ'),
'أ'=>array('ﺄ','ﺄ','ﺃ'),
'ؤ'=>array('ﺆ','ﺆ','ﺅ'),
'إ'=>array('ﺈ','ﺈ','ﺇ'),
'ئ'=>array('ﺊ','ﺌ','ﺋ'),
'ة'=>array('ﺔ','ﺘ','ﺗ')
);public $tahoma=array(
'ه'=>array('ﮫ','ﮭ','ﮬ')
);public $normal=array(
'ه'=>array('ﻪ','ﻬ','ﻫ')
);public $mp_chars = array('آ','ا','د','ذ','ر','ز','ژ','و','أ','إ','ؤ');
public $ignorelist = array('', 'ٌ', 'ٍ', 'ً', 'ُ', 'ِ', 'َ', 'ّ', 'ٓ', 'ٰ', 'ٔ', 'ﹶ', 'ﹺ', 'ﹸ', 'ﹼ', 'ﹾ', 'ﹴ', 'ﹰ', 'ﱞ', 'ﱟ', 'ﱠ', 'ﱡ', 'ﱢ', 'ﱣ');
public $openClose = array('>',')','}',']','<','(','{','[');
public $en_chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
public function persianText($str,$z="",$method='tahoma',$farsiNumber=true){
$en_str='';
$runWay='';
if ($method=='tahoma'){
$this->p_chars=array_merge($this->p_chars,$this->tahoma);
}else{
$this->p_chars=array_merge($this->p_chars,$this->normal);
}$str_len=$this->utf8_strlen($str);
preg_match_all("/./u",$str,$ar);
for($i=0;$i<$str_len;$i++){
$gatherNumbers=false;
$runWay=null;
$str1=$ar[0][$i];
if(in_array($ar[0][$i+1],$this->ignorelist)){
$str_next=$ar[0][$i+2];
if($i==2)
$str_back=$ar[0][$i-2];
if($i!=2)
$str_back=$ar[0][$i-1];
}elseif(!in_array($ar[0][$i-1],$this->ignorelist)){
$str_next=$ar[0][$i+1];
if($i!=0)
$str_back=$ar[0][$i-1];
}else{
if(isset($ar[0][$i+1])&&!empty($ar[0][$i+1])){
$str_next=$ar[0][$i+1];
}else{
$str_next=$ar[0][$i-1];
}if($i!=0)
$str_back=$ar[0][$i-2];
}if(!in_array($str1,$this->ignorelist)){
if(array_key_exists($str1,$this->p_chars)){
if(!$str_back||$str_back==" "||!array_key_exists($str_back,$this->p_chars)){
if(!array_key_exists($str_back,$this->p_chars)&&!array_key_exists($str_next,$this->p_chars))
$output=$str1.$output;
else $output=$this->p_chars[$str1][2].$output;
continue;
}elseif(array_key_exists($str_next,$this->p_chars)&&array_key_exists($str_back,$this->p_chars)){
if(in_array($str_back,$this->mp_chars)&&array_key_exists($str_next,$this->p_chars)){
$output=$this->p_chars[$str1][2].$output;
}else{
$output=$this->p_chars[$str1][1].$output;
}continue;
}elseif(array_key_exists($str_back,$this->p_chars)&&!array_key_exists($str_next,$this->p_chars)){
if(in_array($str_back,$this->mp_chars)){
$output=$str1.$output;
}else{
$output=$this->p_chars[$str1][0].$output;
}continue;
}}elseif($z=="fa"){
$number=array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩","۴","۵","۶","0","1","2","3","4","5","6","7","8","9");
switch($str1){
case ")":$str1="(";break;
case "(":$str1=")";break;
case "}":$str1="{";break;
case "{":$str1="}";break;
case "]":$str1="[";break;
case "[":$str1="]";break;
case ">":$str1="<";break;
case "<":$str1=">";break;
}
if(in_array($str1,$number)){
if($farsiNumber){
$num.=$this->fa_number($str1);
$runWay[]='1';
}else{
$num.=$str1;
$runWay[]='2';
}$str1="";
}if(!in_array($str_next,$number)){
if(in_array(strtolower($str1),$this->en_chars)or(($str1==' '||$str1=='.')&&$en_str!=''&&!in_array($str_next,$this->p_chars))){
$en_str.=$str1.$num;
$str1='';
$runWay[]='3';
}else{
if($en_str!=''){
if($i+1==$str_len){
$runWay[]='3.5';
$str1=$str1.$num;
}else{
$en_str.=$str1.$num;
$runWay[]='4';
}}else{
$str1=$str1.$num;
$runWay[]='5';
}}
$num = '';
}if($en_str!=''or($str1!=''&&$i==0&&(!array_key_exists($str_next,$this->p_chars)&&$str_next!=' '))||$gatherNumbers ){
if(!array_key_exists($str1,$this->p_chars)){
if(!array_key_exists($str_next,$this->p_chars)&&$str_next!=' '&&!in_array($str_next,$this->openClose)){
$en_str=$en_str.$str1;
$runWay[]='6';
}else{
if(in_array($ar[0][$i+2],$this->en_chars)){
$en_str=$en_str.$str1;
$runWay[]='7';
}else{
if($str_next==' '&&(in_array($ar[0][$i+2],$number)||in_array(strtolower($ar[0][$i+2]),$this->en_chars))){
$en_str=$en_str.$str1;
$runWay[]='8';
}else{
$output=$en_str . $output;
$en_str='';
$runWay[]='9';
}}}
}else{
if($num){
$en_str=$en_str.$num;
$runWay[]='10';
}else{
$output=$en_str.$str1.$output ;
$en_str='';
$runWay[]='11';
}}
}else{
if(in_array($str1,$number)&&$str_next=='.'&&in_array($ar[0][$i+2],$number)){
$en_str=$str1;
$runWay[]='12';
}else{
$output=$str1.$output;
$runWay[]='14';
}}
}else{
if(($str1=="،")or($str1=="؟")or($str1=="ء")or(array_key_exists($str_next,$this->p_chars)&&array_key_exists($str_back,$this->p_chars))or
($str1==" "&&array_key_exists($str_back,$this->p_chars))or($str1==" "&&array_key_exists($str_next,$this->p_chars))){
if($e_output){
$output=$e_output.$output;
$e_output="";
}$output=$str1.$output;
}else{
$e_output.=$str1;
if(array_key_exists($str_next,$this->p_chars)||$str_next==""){
$output=$e_output.$output;
$e_output="";
}}}
}else{
$output=$str1.$output;
}$str_next=null;
$str_back=null;
}if($en_str!=''){
$output=$en_str.$output;
}return $output;
}public function fa_number($num){
$AF=array(0=>"٠",1=>"١",2=>"٢",3=>"٣",4=>"۴",5=>"۵",6=>"۶",7=>"٧",8=>"٨",9=>"٩");
$af_date=NULL;
$chars=preg_split('//',$num,-1,PREG_SPLIT_NO_EMPTY);
foreach($chars as $key=>$val) {
$af_num=NULL;
switch($val){
case "0";$af_num=$AF[0];break;
case "1":$af_num=$AF[1];break;
case "2":$af_num=$AF[2];break;
case "3":$af_num=$AF[3];break;
case "4":$af_num=$AF[4];break;
case "5":$af_num=$AF[5];break;
case "6":$af_num=$AF[6];break;
case "7":$af_num=$AF[7];break;
case "8":$af_num=$AF[8];break;
case "9":$af_num=$AF[9];break;
default:$af_num=$val;
}$af_date.=$af_num;
}return $af_date;
}
}function FarsiGD($text){
return (new FarsiGD)->persianText($text);
}function imagegetallcolors($im){
$a=[];
$width=imagesx($im);
$height=imagesy($im);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
$a[imagecolorat($im,$x,$y)];
$y++;}
$x++;}
$r=[];
foreach($a as $b)$r[]=$b;
return $a;
}function imagechangecolor($im,$from,$to){
$width=imagesx($im);
$height=imagesy($im);
$x=0;
while($x<$width){
$y=0;
while($y<$height){
if(imagecolorat($im,$x,$y)==$from)
imagesetpixel($im,$x,$y,$to);
$y++;}
$x++;}
}function number_image_encode($im){
$width=imagesx($im);
$height=imagesy($im);
$r='';$x=0;
while($x<$width){
$k='';$y=0;
while($y<$height){
if(!$k)$k=base_convert(imagecolorat($im,$x,$y),10,8);
else $k=$k.'8'.base_convert(imagecolorat($im,$x,$y),10,8);
$y++;}
if(!$r)$r=$k;
else $r=$r.'9'.$k;
$x++;}
return "$r";
}function number_image_decode($im){
$p=[];
$r=explode('9',"$im");
foreach($r as $y=>$yc){
$k=explode('8',$yc);
$s=[];
foreach($k as $x=>$xc){
$s[]=base_convert($xc,8,10);
}$p[]=$s;
}$im=imagecreatetruecolor(count($p),count($p[0]));
foreach($p as $y=>$yc){
foreach($yc as $x=>$xc){
imagesetpixel($im,$y,$x,$xc);
}}
return $im;
}function strposss($text,$str){
$r=[];$t=0;
while($t>-1){
$t=strpos($text,$str);
$r[]=$t;
$text=substr_replace($text,'',$t,strlen($str));
}return $r;
}function preg_strpos($text,$str){
preg_match($str,$text,$r);
return strpos($text,$r[0]);
}function preg_strposs($text,$str){
$k=true;$r=[];
preg_match_all($str,$text,$s);
foreach($s as $a)$r[]=strpos($text,$b);
return $r;
}function getfunction($name){
return function()use($name){
$th=func_get_args();
return call_user_func_array($name,$th);
};
}function imageclone($im){
return imagecreatefromstring(imagepngstring($im));
}function imagebgcolor($im){
$im=imageclone($im);
imageresize($im,1,1);
return imagecolorat($im,1,1);
}function imagecreatetransparent($width,$height){
$im=imagecreatetruecolor($width,$height);
$g=imagecolorallocate($im,0,0,0);
imagecolortransparent($im,$g);
return $im;
}function is_url($file){
return (filter_var($file,FILTER_VALIDATE_URL)&&fvalid($file)&&!file_exists($file));
}function fsubget($file,$from=0,$to=-1){
$f=fopen($file,'r');
fseek($from);
$r='';
while(!feof($f)&&$to!=0){
$r=$r.fgetc($f);
$to--;
}fclose($r);
return $r;
}function fsubwrite($file,$from=0){
$o=fopen($file,'w');
fseek($o,$from);
$w=fwrite($o,$content);
fclose($o);
return $w;
}function fgetline($file){
$f=fopen($file,'r');
$r=[];
while(!feof($f))$r[]=fgets($f);
return $r;
}function fgetchar($file){
$f=fopen($file,'r');
$r=[];
while(!feof($f))$r[]=fgetc($f);
return $r;
}function fpos($file,$str){
$f=fopen($file,'r');
$s='';$m=0;$o=0;
while(!feof($f)&&$s!=$str){
$c=fgetc($f);
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o;
return -1;
}function mb_fgetc($file){
$l='';$s='';
while(mb_strlen($s)<2&&!feof($file)){
$l=$s;$s=$s.fgetc($file);
}fseek($file,-1,SEEK_CUR);
return $l;
}function mb_fpos($file,$str){
$f=fopen($file,'r');
$s='';$m=0;$o=0;
while(!feof($f)&&$s!=$str){
$c=mb_fgetc($f);
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o;
return -1;
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
}class xnplugin {
public $code='',$file,$max=1000;
private $roots=[],$command=[],$replace=[];
public function commandadd($name,$command){
$this->command[$name]=$command;
}public function commandlist($arr){
foreach($arr as $name=>$command)
$this->command[$name]=$command;
}public function commanddelete($name){
unset($this->command[$name]);
}public function commandget($name){
return $this->command[$name];
}public function replaceadd($from,$to){
$this->replace[$from]=$to;
}public function replacelist($arr){
foreach($arr as $from=>$to)
$this->command[$from]=$to;
}public function replacedelete($name){
unset($this->replace[$name]);
}public function replaceget($name){
return $this->replace[$name];
}
public function root($code){
foreach($this->roots as $regex=>$root){
$code=preg_match_function($regex,$root,$code);
}return $code;
}public function parse($code,$parses=false){
if(!$parses)$parses=$this->replace;
$code=str_replace(['[NULL]','[null]'],['',''],$code);
$ncode='';
while($ncode!=$code){
$ncode=$code;
$code=array_str_replace($parses,$code);
$code=$this->root($code);
}return $code;
}public function run($code,$varDT=false,$varDF=false,$varSV=false){
/*vars
DT : data
DF : define
SV : save
*/
$code=str_replace("\n\n","\n",ltrim($code));
if(!$varDT){$varDT=[];$countDT=0;}
else $countDT=count($varDT);
if(!$varDF)$varDF=[];
if($this->file&&!$varSV)$varSV=json_decode(file_get_contents($this->file),true);
if(!$varSV)$varSV=[];
$mat=true;
$code=str_replace('{}','{[NULL]}',$code);
while($mat&&instr($code,'{')){
$mat=preg_match_all('/([^\\\\]|^)\{(([^\{\}]|\\\\\{|\\\\\}){0,}[^\\\\]|)\}(\(([0-9]{1,})\)){0,1}/',$code,$pregs);
foreach($pregs[0] as $n=>$preg){
$limit=$pregs[5][$n];
if($limit>$this->max)$limit=$this->max;
elseif($limit<0)$limit=0;
elseif(!$limit)$limit=1;
$varDT[]=[$pregs[2][$n],$limit];
$countDT++;
$code=str_replace($preg," <VARDT-$countDT>",$code);
}}$this->code=$code;
$lines=explode("\n",$code);
foreach($lines as $number=>$line){
$args=explode(' ',$line);
$command=$args[0];
unset($args[0]);
$args=arr::setlist($args);
$line=preg_match_function('/\[LINE\-ARG-([0-9]{1,})\]/',function($e)use($args){
return $args[$e[1]];
},$line);
$th=[
"[LINE-NUMBER]"=>$number,
"[LINE-CODE]"=>$line,
"[LINE-LENGTH]"=>strlen($line),
"[LINE-ARGS]"=>count($args),
"[LINE-COMMAND]"=>$command,
"[LINE-TIME]"=>microtime(true)
];
$thDF=[];$thSV=[];$thDT=[];
foreach($varDF as $name=>$var)
$thDF["<VARDF-$name>"]=$var;
foreach($varSV as $name=>$var)
$thSV["<VARSV-$name>"]=$var;
foreach($varDT as $name=>$var)
$thDT["<VARDT-$name>"]=$var;
$th=addargs($th,$thDF,$thSV,$thDT);
$line=$this->parse($line,$th);
$lines[$number]=$line;
$varsadd=$this->line($command,$args,$varDT,$varDF,$varSV,$th);
$varDF=addargs($varDF,$varsadd[0]);
$varSV=addargs($varSV,$varsadd[1]);
}$lines=implode("\n",$lines);
$this->code=$lines;
if($this->file)file_put_contents($this->file,json_encode($varSV));
$this->varDF=$varDF;
$this->varSV=$varSV;
return $lines;
}public function readDT($tex,$varDT=false,$th=false){
if(!$varDT)$varDT=$this->varDT;
if(!$th)$th=$this->replaceplain;
$tex=str_replace(['<VARDT-','>'],'',$tex)-1;
$g=$varDT[$tex];
$g=str_replace(['\{','\}','\\\\'],['{','}','\\'],$g);
$g=$this->parse($g,$th);
return str_replace(['\<','\>','\[','\]','\(','\)'],['<','>','[',']','(',')'],$g);
}public function iftest($if){
if(strpos($if,' && ')>-1||strpos($if,' & ')>-1||strpos($if,' and ')>-1){
$e=strexplode([' && ',' & ',' and '],$if);
foreach($e as $b)if(!$this->iftest($b))return false;
}elseif(strpos($if,' || ')>-1||strpos($if,' | ')>-1||strpos($if,' or ')>-1){
$e=strexplode([' || ',' | ',' or '],$if);
foreach($e as $b)if($this->iftest($b))return true;
}elseif(strpos($if,' = ')>-1||strpos($if,' == ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]==$this->readDT($if[2])[0]);
}elseif(strpos($if,' ! ')>-1||strpos($if,' != ')>-1||strpos($if,' =! ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]!=$this->readDT($if[2])[0]);
}elseif(strpos($if,' > ')>-1||strpos($if,' !< ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]>$this->readDT($if[2])[0]);
}elseif(strpos($if,' < ')>-1||strpos($if,' !> ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]<$this->readDT($if[2])[0]);
}elseif(strpos($if,' >= ')>-1||strpos($if,' => ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]>=$this->readDT($if[2])[0]);
}elseif(strpos($if,' <= ')>-1||strpos($if,' =< ')>-1){
$if=explode(' ',$if);
return ($this->readDT($if[0])[0]<=$this->readDT($if[2])[0]);
}elseif(strpos($if,' in ')>-1||strpos($if,' ^ ')>-1){
$if=explode(' ',$if);
return (strpos($this->readDT($if[2])[0],$this->readDT($if[0])[0])>-1);
}return false;
}public function line($command,$args=[],$varDT=[],$varDF=[],$varSV=[],$th=false){
if(!$command)return [$varDF,$varSV];
$this->replaceplain=$th;
$this->varDT=$varDT;
$this->varDF=$varDF;
$this->varSV=$varSV;
if($command=='&set'){
$varSV[$this->readDT($args[0])[0]]=$this->readDT($args[1])[0];
}elseif($command=='$set'){
$varDF[$this->readDT($args[0])[0]]=$this->readDT($args[1])[0];
}elseif($command=='&add'){
$varSV[$this->readDT($args[0])[0]]=$varSV[$this->readDT($args[0])[0]].$this->readDT($args[1])[0];
}elseif($command=='$add'){
$varDF[$this->readDT($args[0])[0]]=$varDF[$this->readDT($args[0])[0]].$this->readDT($args[1])[0];
}elseif($command=='&get'){
if($args[0]=='SV'){
$varSV[$this->readDT($args[1])[0]]=$varSV[$this->readDT($args[2])[0]];
}else{
$varDF[$this->readDT($args[1])[0]]=$varSV[$this->readDT($args[2])[0]];
}
}elseif($command=='$get'){
if($args[0]=='SV'){
$varSV[$this->readDT($args[1])[0]]=$varDF[$this->readDT($args[2])[0]];
}else{
$varDF[$this->readDT($args[1])[0]]=$varDF[$this->readDT($args[2])[0]];
}
}elseif($command=='!get'){
if($args[0]=='SV'){
$varSV[$this->readDT($args[1])[0]]=$varDT[$args[2]][0];
}else{
$varDF[$this->readDT($args[1])[0]]=$varDT[$args[2]][0];
}
}elseif($command=='&del'){
unset($varSV[$this->readDT($args[0])[0]]);
}elseif($command=='$del'){
unset($varDF[$this->readDT($args[0])[0]]);
}elseif($command=='!del'){
unset($varDT[$this->readDT($args[0])[0]]);
}elseif($command=='open'){
parse_str($args[4],$data);
if($args[0]=='SV'){
$varSV[$this->readDT($args[1])[0]]=sendHTTP($this->readDT($args[2])[0],$this->readDT($args[3])[0],$data);
}else{
$varDF[$this->readDT($args[1])[0]]=sendHTTP($this->readDT($args[2])[0],$this->readDT($args[3])[0],$data);
}
}elseif($command=='if'){
$count=count($args)-1;
$ifr=$args;
$run='';
if($this->readDT($args[$count-1])[0]){
unset($ifr[$count-1]);
unset($ifr[$count]);
$ifr=implode(' ',$ifr);
if($this->iftest($ifr))$run=$this->readDT($args[$count-1]);
else $run=$this->readDT($args[$count]);
}else{
unset($ifr[$count]);
$ifr=implode(' ',$ifr);
if($this->iftest($ifr))$run=$this->readDT($args[$count]);
}while($run[1]>0){
$this->run($run[0]);
$run[1]--;}
}elseif($command=='run'){
if($args[0]=='in'){
$code=$this->readDT($args[1]);
$count=$code[1];
$code=$code[0];
while($count>0){
$this->run($code,$varDT,$varDF,$varSV);
$varDF=$this->varDF;
$varSV=$this->varSV;
$count--;}
}else{
$code=$this->readDT($args[0]);
$count=$code[1];
$code=$code[0];
while($count>0){
$this->run($code);
$count--;}
}}elseif($command=='reset'){
if($args[0]=='DF'){
$varDF=[];
}elseif($args[0]=='DT'){
$varDT=[];
}elseif($args[0]=='SV'){
$varSV=[];
}if($args[1]=='DF'){
$varDF=[];
}elseif($args[1]=='DT'){
$varDT=[];
}elseif($args[1]=='SV'){
$varSV=[];
}if($args[2]=='DF'){
$varDF=[];
}elseif($args[2]=='DT'){
$varDT=[];
}elseif($args[2]=='SV'){
$varSV=[];
}}if($this->command[$command]){
foreach($args as $n=>$arg){
if($arg=$this->readDT($arg)[0])$args[$n]=$arg;
}
$this->command[$command]($args,$varDT);
}return [$varDF,$varSV];
}public function addroot($root,$fun){
$this->roots[$root]=$fun;
}public function deleteroot($root){
unset($this->roots[$root]);
}public function getroot($root){
return $this->roots[$root];
}public function listroot($list){
foreach($list as $k=>$v)
$this->roots[$k]=$v;
}public function getroots(){
return $this->roots;
}public function getreplaces(){
return $this->replaces;
}public function getcommands(){
return $this->commands;
}public function getvars(){
return ["DT"=>$this->varDT,"DF"=>$this->varDF,"SV"=>$this->varSV];
}public function getreplaceplain(){
return $this->replaceplain;
}public function getcode(){
return $this->code;
}public function runplugin($code,$cl=true){
$this->code=$this->parse($code);
$g=$this->run($this->code);
if($cl)$this->close();
return $g;
}public function rundefult(){
$this->code=$this->parse($code);
return $this->run($this->code);
}public function close(){
$this->code=null;
$this->varDT=null;
$this->varDF=null;
$this->varSV=null;
$this->replaceplain=null;
$this->max=null;
}public function runcode($code,$file=false,$commands=false,$replaces=false,$roots=false){
if($file)$this->file=$file;
if($replaces)$this->replacelist($replaces);
if($commands)$this->commandlist($commands);
if($roots)$this->rootlist($roots);
return $this->runplugin($code,true);
}public function runfile($code,$file=false,$commands=false,$replaces=false,$roots=false){
if($file)$this->file=$file;
if($replaces)$this->replacelist($replaces);
if($commands)$this->commandlist($commands);
if($roots)$this->rootlist($roots);
return $this->runplugin(file_get_contents($code),true);
}public function auto(){
$replaces=[];
$commands=[];
$roots=[];

$xn->file="xnplugin.SVvars.json";
$this->replacelist($replaces);
$this->commandlist($commands);
$this->rootlist($roots);
}
}function charscount($string){
return count(strsplit($string));
}function image_string_encode($string){
$string=number_string_encode($string);
$string=split($string,7,7);
$count=count($string);
$width=floor(sqrt($count));
$height=ceil(sqrt($count))+1;
$im=imagecreatetruecolor($width,$height);
$x=0;$y=0;
foreach($string as $pixel){
imagesetpixel($im,$x,$y,$pixel+1);
$y++;
if($y>=$height){
$y=0;$x++;
}}
$r=imagepngstring($im);
imagedestroy($im);
return $r;
}function instr($str,$tx){
return strpos($str,$tx)>-1;
}function image_string_decode($image){
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
$r="$r$col";
}$y++;}
$x++;}
return number_string_decode($r);
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
$y++;
if($y>=$height){
$y=0;$x++;
}}
$r=imagepngstring($im);
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
$r="$r$col";
}$y++;}
$x++;}
return $r;
}function image_array_encode($string){
$string=number_array_encode($string);
$string=split($string,7,7);
$count=count($string);
$width=floor(sqrt($count));
$height=ceil(sqrt($count))+1;
$im=imagecreatetruecolor($width,$height);
$x=0;$y=0;
foreach($string as $pixel){
imagesetpixel($im,$x,$y,$pixel+1);
$y++;
if($y>=$height){
$y=0;$x++;
}}
$r=imagepngstring($im);
imagedestroy($im);
return $r;
}function image_array_decode($image){
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
$r="$r$col";
}$y++;}
$x++;}
return number_array_decode($r);
}function extern($lang,$code,&$output=61463516206679){
$lang=strtolower($lang);
switch($lang){
case "c":$format='c';break;
case "exe":$format='exe';break;
case "php":$format='php';
case 'apk':break;$format='apk';break;
case 'python':$format='py';break;
case 'py':$format='py';break;
case 'c++':$format='cpp';break;
case 'cpp':$format='cpp';break;
case 'lua':$format='lua';break;
case 'html':$format='html';break;
case 'htm':$format='htm';break;
case 'cs':$format='cs';break;
case 'c#':$format='cs';break;
case 'csharp':$format='cs';break;
case 'sh':$format='sh';break;
case 'shell':$format='sh';break;
case 'bash':$format='sh';break;
defult:return false;
}file_put_contents("xn_log.$format",$code);
$ec=($output==61463516206679);
$output=shell_exec("xn_log.$format");
if($ec)echo $output;
unlink("xn_log.$format");
return $output;
}function number_number_encode($text){
return str_replace([0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','l'],['a','b','c','d','e','f','g','h','i','j','l',8,2,0,6,1,7,4,3,9,5],$text);
}function number_number_decode($text){
return str_replace([8,2,0,6,1,7,4,3,9,5,'a','b','c','d','e','f','g','h','i','j','l'],['a','b','c','d','e','f','g','h','i','j','l',0,1,2,3,4,5,6,7,8,9],$text);
}function fileformat($file){
$file=explode('.',$file);
$file=$file[count($file)-1];
if(strstr($file,'/'))return '';
return $file;
}function filename($file){
$file=explode('/',$file);
return $file[count($file)-1];
}function fspeed($file){
return fclose(fopen($file,'r'));
}function getrequiest($s="array",$k=false,$r=true){
global $_SERVER;
global $_POST;
global $_GET;
if($_SERVER['REDIRECT_QUERY_STRING']&&$r){
$_GET=[];
$GET=explode('&',$_SERVER['REDIRECT_QUERY_STRING']);
foreach($GET as $v){
$v=explode('=',$v);
if(!$v[1])$_GET[$v[0]]=false;
else $_GET[$v[0]]=$v[1];
}}if($k)$method=strtoupper($k);
else $method=$_SERVER['REQUEST_METHOD'];
if($method=="GET"){
if($s=="string")return http_build_query($_SERVER['QUERY_STRING']);
if($s=="elements")return explode('&',$_SERVER['QUERY_STRING']);
if($s=="array")return $_GET;
return json_encode($_GET);
}elseif($method=="POST"){
if($s=="string")return http_build_query($_POST);
if($s=="elements")return explode('&',http_build_query($_POST));
if($s=="array")return $_POST;
return json_encode($_POST);
}elseif($method=="PUT"||$method=='DELETE'){
$_PUT=fget("php://input");
if($s=="string")return http_build_query(json_decode($_PUT,true));
if($s=="elements")return explode('&',http_build_query(json_decode($_PUT,true)));
if($s=="array")return json_decode($_PUT,true);
return $_PUT;
}return false;
}function connecttime($micro=true){
if($micro)return microtime(true)-$GLOBALS['_SERVER']['REQUEST_TIME_FLOAT'];
return time()-$GLOBALS['_SERVER']['REQUEST_TIME'];
}function dirdel($dir){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
foreach($s as $f){
if(filetype("$dir/$f")=='dir')dirdel("$dir/$f");
else unlink("$dir/$f");
}return rmdir($dir);
}function dirscan($dir){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
return $s;
}function getmicrotimerun($func){
$mc=microtime(true);
$func();
return microtime(true)-$mc;
}function clockanalogimage($req=[],$rs=false){
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
if($req['special'])$get="http://free.timeanddate.com/clock/i655jtc5/n246/szw$size/szh$size/hoc00f/hbw0/".
"hfc000/cf100/hgr0/facf90/mqcfff/mql6/mqw2/mqd74/mhcfff/mhl6/mhw1/mhd74/mmcf90/mml4/mmw1/mmd74/hhcfff/hmcfff";
$get=screenshot($get.'?'.rand(0,99999999999).rand(0,99999999999),1280,true);
$im=imagecreatefromstring($get);
$im2=imagecrop($im,['x'=>0,'y'=>0,'width'=>$size,'height'=>$size]);
imagedestroy($im);
if($rs)return $im2;
$get=imagepngstring($im2);
imagedestroy($im2);
return $get;
}function fgetvalid($file){
if(file_exists($file))return fget($file);
return false;
}function fputvalid($file,$con){
if(file_exists($file))return fput($file,$con);
return false;
}function faddvalid($file,$con){
if(file_exists($file))return fadd($file,$con);
return false;
}function fgetvalidjson($file,$par){
if(file_exists($file))return json_decode(fget($file,$con),$par);
return false;
}function fputvalidjson($file,$con){
if(file_exists($file))return fput($file,json_encode($con));
return false;
}function fgetdel($file){
$g=fget($file);
unlink($file);
return $g;
}function dirreset($dir){
dirdel($dir);
return mkdir($dir);
}class xndatahidde {
static function get($var){
return $GLOBALS['-XN-']['data'][$var];
}static function set($var,$con){
$GLOBALS['-XN-']['data'][$var]=$con;
}static function del($var){
unset($GLOBALS['-XN-']['data'][$var]);
}static function is($var){
return isset($GLOBALS['-XN-']['data'][$var]);
}
}function fcopy($from,$to){
$fm=fopen($from,'r');
$to=fopen($to,'w');
fwrite($to,fread($fm,filesize($from)));
fclose($fm);
return fclose($to);
}class CURLURL {
public function __construct($url,$name=false){
$get=fget($url);
if($name){
if($get)fput($name,$get);
else fput($name,$url);
}else{
if($get)fput($name="xn_log.fileuplode.".filename($url),$get);
else fput($name="xn_log.fileuplode",$url);
}$curl=new CURLFile($name);
foreach((array)$curl as $k=>$v)$this->{$k}=$v;
return $curl;
}public function __destruct(){
unlink($this->name);
unset($this->name);
unset($this->mime);
unset($this->postname);
}public function close(){
$this->__destruct();
}
}class TheEndClass {
public $func;
public function end($x=null){
exit($x);
}public function __construct($func=false){
if(!is_function($func))$func=function(){};
$this->func=$func;
}public function __destruct(){
($this->func)();
unset($this->func);
}}function base64url_decode($data){
return base64_decode(str_pad(strtr($data,'-_','+/'),strlen($data)%4,'=',STR_PAD_RIGHT));
}function base64url_encode($data){
return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
}function rle_decode($string){
$new='';
$last='';
$null=chr(0);
foreach(str_split($string) as $cur){
if($last===$null){
$new.=str_repeat($last,ord($cur));
$last='';
}else{
$new.=$last;
$last=$cur;
}}$string=$new.$last;
return $string;
}function rle_encode($string){
$new='';
$count=0;
$null=chr(0);
foreach(str_split($string) as $cur){
if($cur===$null){
$count++;
}else{
if($count>0){
$new.=$null.chr($count);
$count=0;
}$new.=$cur;
}}return $new;
}class getTime {
static function getTimeZone($zone){
if($zone=="defult")return 0;
$last=date_default_timezone_get();
$time1=strtotime(date('c a'));
date_default_timezone_set($zone);
$time2=strtotime(date('c a'));
date_default_timezone_set($last);
return $time2-$time1;
}static function convertTimeZone($time,$from,$to){
$time=$time-$this->getTimeZone($from);
$time=$time+$this->getTimeZone($to);
return $time;
}static function getDate($date){
if($date==1)return 0;
elseif($date==2)return -19603814400;
elseif($date==3)return -18262627200;
}static function convertDate($date,$from,$to){
$date=$date-$this->getDate($from);
$date=$date+$this->getDate($to);
return $date;
}static function convertDateTime($datetime,$fromdate,$todate,$fromzone,$tozone){
$datetime=$datetime-$this->getDate($fromdate)-$this->getTimeZone($fromzone);
$datetime=$datetime+$this->getDate($todate)+$this->getTimeZone($tozone);
return $timezone;
}static function time($from,$to,$micro=true){
return $this->convertTimeZone($micro?microtime(true):time(),$from,$to);
}static function date($date,$fromdate,$todate,$fromzone,$tozone,$micro=true){
$time=$this->convertDateTime(time(),$fromdate,$todate,$fromtime,$totime);
return date($date,($micro?microtime(true):time())+$time);
}static function startStamp($date){
return date($date,0);
}
}


$GLOBALS['-XN-']['loadtime']=microtime(true)-$GLOBALS['-XN-']['starttime'];
function xnscript(){
$g=file_get_contents(__FILE__);
$l=count(explode("\n",$g));
return (object)[
"verson"=>"6.1.8",
"size"=>strlen($g),
"lines"=>$l,
"starttime"=>$GLOBALS['-XN-']['starttime'],
"loadtime"=>$GLOBALS['-XN-']['loadtime']
];}
flush();?>
