<?php

// Created by ...
// xn plugin telegram v2.1

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
}class TelegramBot {
public $data,$token,$final,$results=[],$sents=[],$save=true,$last;
public $keyboard,$inlineKeyboard,$foreReply,$removeKeyboard,$queryResult;
public function setToken($token=''){
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
if(isset($args['reply_markup'])&&is_array($args['reply_markup']))
$args['reply_markup']=json_encode($args['reply_markup']);
return $this->request("sendMessage",$args,$level);
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
}public function setWebhook($args=[],$level=3){
if(!isset($args['url'])||!$args['url'])$args['url']='';
if(is_array($args['allowed_updates']))
$args['allowed_updates']=json_encode($args['allowed_updates']);
return $this->request("setWebhook",$args,$level);
}public function getChat($chat,$level=3){
return $this->request("getChat",[
"chat_id"=>$chat
],$level);
}public function getMembersCount($chat,$level=3){
return $this->request("getMembersCount",[
"chat_id"=>$chat
],$level);
}public function getMember($chat,$user,$level=3){
return $this->request("getMember",[
"chat_id"=>$chat,
"user_id"=>$user
],$level);
}public function getProfile($user){
$args['user_id']=$user;
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
}public function getAdministrators($chat,$level=3){
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
}public function editCaption($caption,$args=[],$level=3){
$args['caption']=$caption;
return $this->request("editMessageCaption",$args,$level);
}public function editReplyMarkup($reply_makup,$args=[],$level=3){
$args['reply_markup']=json_encode($reply_markup);
return $this->request("editMessageReplyMarkup",$args,$level);
}public function editInlineKeyboard($reply_makup,$args=[],$level=3){
$args['reply_markup']=json_encode(["inline_keyboard"=>$reply_markup]);
return $this->request("editMessageReplyMarkup",$args,$level);
}public function deleteMessage($chat,$message,$level=3){
return $this->request("deleteMessage",[
"chat_id"=>$chat,
"message_id"=>$message
],$level);
}public function sendMedia($chat,$type,$file,$args=[],$level=3){
$type=strtolower($type);
if($type=="videonote")$type="video_note";
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
$func($this->data);exit();
}}public function getUser($update=false){
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
];return $type[ord($file[0])];
}static function getJoinChatId($code){
$code=base64_decode(strtr($code,'-_','+/'));
return base_convert(bin2hex(substr($code,4,4)),16,10);
}
}

class PWRTelegram {
public $token,$phone;
public function setUser($phone=''){
$phone=str_replace(['+',' ','(',')','.',','],'',$phone);
if(is_numeric($phone))$this->phone=$phone;
else $this->token=$phone;
}public function checkAPI(){
$ch=curl_init("https://api.pwrtelegram.xyz");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_exec($ch);
$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
return $code==400||$code==200;
}public function __construct($phone=''){
$phone=str_replace(['+',' ','(',')','.',','],'',$phone);
if(is_numeric($phone))$this->phone=$phone;
else $this->token=$phone;
}public function request($method,$args=[],$level=2){
if($level==1){
$r=fclose(fopen("https://api.pwrtelegram.xyz/user$this->token/$method?".http_build_query($args),"r"));
}elseif($level==2){
$ch=curl_init("https://api.pwrtelegram.xyz/user$this->token/$method");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$args);
$r=json_decode(curl_exec($ch));
curl_close($ch);
}elseif($level==3){
$r=json_decode(file_get_contents("https://api.pwrtelegram.xyz/user$this->token/$method?".http_build_query($args)));
}elseif($level==4){
$r=json_decode(fget("https://api.pwrtelegram.xyz/user$this->token/$method?".http_build_query($args)));
}else{
new XNError("PWRTelegram","invalid level type");
return false;
}if($r===false)return false;
if($r===true)return true;
if($r===null){
new XNError("PWRTelegram","PWRTelegram api is offlined");
return null;
}if(!$r->ok){
new XNError("PWRTelegram","$r->description [$r->error_code]",1);
return $r;
}return $r;
}public function login($level=2){
$r=$this->request("phonelogin",[
"phone"=>$this->token
],$level);
$this->token=$r;
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

class XNTelegram {
// Soon ...
}



?>
