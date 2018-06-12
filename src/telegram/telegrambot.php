<?php
/* TelegramBot Class
 * params : String token = ''
 * use : you can make a API robot on telegram by BotFather API_KEY (token)
 
 $bot = new xn\Telegram\TelegramBot( string );
 */

namespace xn\Telegram;

// $bot->keyboard
class TelegramBotKeyboard {
  private $btn    = [],
          $button = [];
  public $resize    = false,
         $onetime   = false,
         $selective = false;
  
  /* set keyboard resize
   * params : Boolean|NULL size
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->size( Boolean|NULL );
   */
  public function size($size = null) {
    if($size === null) $size =! $this->resize;
    $this->resize = $size == true;
    return $this;
  }
  /* set keyboard onetime
   * params : Boolean|NULL onetime
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->onetime( Boolean|NULL );
   */
  public function onetime($onetime = null) {
    if($onetime===null) $onetime = ! $this->onetime;
    $this->onetime = $onetime == true;
    return $this;
  }
  /* set keyborad selective
   * params : Boolean|NULL selective
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->selective( Boolean|NULL );
   */
  public function selective($selective = null) {
    if($selective === null) $selective = ! $this->selective;
    $this->selective = $selective == true;
    return $this;
  }
  /* add a button on keyboard
   * params : String name,
              String type = normal,
   * types :
     contact  -> button for request give use contact
     location -> button for request give use location
   * use :
   
   $bot->keyboard->add( String , String );
   */
  public function add($name, $type = '') {
    $btn = ["text" => $name];
    if    ($type == "contact" ) $btn["request_contact"]  = true;
    elseif($type == "location") $btn["request_location"] = true;
    $this->btn[] = $btn;
    return $this;
  }
  /* go to next line in keyboard
   * use :
   
   $bot->keyboard->line();
   */
  public function line() {
    $this->button[] = $this->btn;
    $this->btn=[];
    return $this;
  }
  /* end and return keyboard
   * params : Boolean json
   * use :
   
   $bot->keyboard->get( Boolean );
   */
  public function get($json = false) {
    $this->button[] = $this->btn;
    $btn = ["keyboard" => $this->button];
    if($this->resize)    $btn['resize_keyboard']   = true;
    if($this->onetime)   $btn['one_time_keyboard'] = true;
    if($this->selective) $btn['selective']         = true;
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $json? json_encode($btn): $btn;
  }
  /* reset keyboard 
   * use :
   
   $bot->keyboard->reset();
   */
  public function reset() {
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $this;
  }
}

// $bot->inlineKeyboard
class TelegramBotInlineKeyboard {
  private $btn    = [],
          $button = [];
  public $resize    = false,
         $onetime   = false,
         $selective = false;
  
  /* set keyboard resize
   ! this method can't use in inline_keyboard
   * params : Boolean|NULL size
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->size( Boolean|NULL );
   */
  public function size($size = null) {
    if($size === null) $size = ! $this->resize;
    $this->resize = $size == true;
    return $this;
  }
  /* set keyboard onetime
   * params : Boolean|NULL onetime
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->onetime( Boolean|NULL );
   */
  public function onetime($onetime = null) {
    if($onetime === null) $onetime = ! $this->onetime;
    $this->onetime = $onetime == true;
    return $this;
  }
  /* set keyborad selective
   * params : Boolean|NULL selective
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->selective( Boolean|NULL );
   */
  public function selective($selective = null) {
    if($selective === null) $selective = ! $this->selective;
    $this->selective = $selective == true;
    return $this;
  }
  /* add a button on keyboard
   * params : String name,
              String type = normal,
              String data = '' // data for callback_data
   * types :
     pay : payment button
     game : game callback
     switch : switch inline query
     switch_current_chat : switch inline query current chat
     callback|data : callback data
     link : linked button
   * use :
   
   $bot->inlineKeyboard->add( String , String , String );
   */
  public function add($name, $type, $data='') {
    $btn = ["text" => $name];
    if    ($type == "pay")                         $data = true;
    elseif($type == "game")                        $type = "callback_game";
    elseif($type == "switch")                      $type = "switch_inline_query";
    elseif($type == "switch_current_chat")         $type = "switch_inline_query_current_chat";
    elseif($type == "callback" || $type == "data") $type = "callback_data";
    elseif($type == "link")                        $type = "url";
    $btn[$type] = $data;
    $this->btn[]=$btn;
    return $this;
  }
  /* go to next line in keyboard
   * use :
   
   $bot->inlineKeyboard->line();
   */
  public function line() {
    $this->button[] = $this->btn;
    $this->btn = [];
    return $this;
  }
  /* end and return keyboard
   * params : Boolean json
   * use :
   
   $bot->inlineKeyboard->get( Boolean );
   */
  public function get($json = false) {
    $this->button[] = $this->btn;
    $btn = ["inline_keyboard" => $this->button];
    if($this->resize)    $btn['resize_keyboard']   = true;
    if($this->onetime)   $btn['one_time_keyboard'] = true;
    if($this->selective) $btn['selective']         = true;
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $json? json_encode($btn): $btn;
  }
  /* reset keyboard 
   * use :
   
   $bot->inlineKeyboard->reset();
   */
  public function reset() {
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $this;
  }
}

// $bot->queryResult
class TelegramBotQueryResult {
  /* get result array
   * use :
   
   $bot->queryResult->get;
   */
  public $get = [];
  
  /* add a result
   * params : String type, // result type. for example "article"
              Double query_id,
              String title,
              Array input,
              Array args // optional args
   * use :
   
   $bot->queryResult->add( String , Double , String , Array , Array );
   */
  public function add($type, $id, $title, $input, $args = []) {
    $args["type"]                  = $type;
    $args["id"]                    = $id;
    $args["title"]                 = $title;
    $args["input_message_content"] = $input;
    $this->get[]                   = $args;
    return $this;
  }
  /* create message input
   * params : String text,
              String parse_mode = none,
              Boolean preview = false
   * use :
   
   $bot->queryResult->inputMessage( String , String , Boolean );
   */
  public function inputMessage($text, $parse = false, $preview = false) {
    $args = ["message_text" => $text];
    if($parse)   $args["parse_mode"]               = $parse;
    if($preview) $args["disable_web_page_preview"] = $preview;
    return $args;
  }
  /* create location input
   * params : Integer latitude,
              Integer longitude,
              Integer live_period
   * use :
   
   $bot->queryResult->inputLocation( Integer , Integer , Integer );
   */
  public function inputLocation($latitude, $longitude, $live = false) {
    $args = ["latitude" => $latitude, "longitude" => $longitude];
    if($live) $args['live_period'] = $live;
    return $args;
  }
  /* create venue input
   * params : Integer latitude,
              Integer longitude,
              String title,
              String address,
              Integer id = false
   * use :
   
   $bot->queryResult->inputVenue( Integer , Integer , String , Integer );
   */
  public function inputVenue($latitude, $longitude, $title, $address, $id = false) {
    $args = ["latitude" => $latitude, "longitude" => $longitude, "title" => $title, "address" => $address];
    if($id) $args["foursquare_id"] = $id;
    return $args;
  }
   /* get result array and reset
   * use :
   
   $bot->queryResult->get();
   */
  public function get(){
    $get=$this->get;
    $this->get=[];
    return $get;
  }
  /* reset query result
   * use :
   
   $bot->queryResult->reset();
   */
  public function reset(){
    $this->get=[];
  }
}

// save buttons list for easy use
// $bot->menu
class TelegramBotButtonSave {
  private $btns = [],
          $btn  = [];
  
 /* get a menu
  * params : String name,
             Boolean json = true
  * use :
  
  $bot->menu->get( String , Boolean );
  */
  public function get($name, $json = true) {
    if($json) return @$this->btn[$name];
    return @$this->btns[$name];
  }
  /* add a menu
   * params : String name,
              Array|Json keyboard
   * use :
   
   $bot->menu->add( String , Array|Json );
   */
  public function add($name, $btn) {
    if(is_array($btn)) $btns = json_encode($btn);
    elseif(!is_json($btn)) return false;
    else $btn = json_decode($btns = $btn);
    if(!isset($btns['inline_keyboard']) ||
       !isset($btns['keyboard'])        ||
       !isset($btns['force_reply'])     ||
       !isset($btns['remove_keyboard']))
       return false;
    $this->btns=$btns;
    $this->btn=$btn;
    return $this;
  }
  /* delete menu
   * params : String name
   * use :
   
   $bot->menu->delete( String );
   */
  public function delete($name) {
    if(isset($this->btn[$name])) {
      unset($this->btn [$name]);
      unset($this->btns[$name]);
    }
    return $this;
  }
  /* delete all menus
   * use :
   
   $bot->menu->reset();
   */
  public function reset() {
    $this->btn  = [];
    $this->btns = [];
  }
}

// $bot->send
class TelegramBotSends {
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
}public function getAllMembers($chat){
return json_decode(file_get_contents("http://xns.elithost.eu/getparticipants/?token=$this->token&chat=$chat"));
}public $remove_keyboard=["remove_keyboard"=>true];
public $force_reply=["force_reply"=>true];
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
}public function sendUpdate($url,$update=false){
if($update===false)$update=$this->update();
$c=curl_init($url);
curl_setopt($c,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($c,CURLOPT_POSTFIELDS,$update);
curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($c);
curl_close($c);
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
if(isset($args['reply_markup'])&&(is_array($args['reply_markup'])||is_object($args['reply_markup'])))
$args['reply_markup']=json_encode($args['reply_markup']);
if(is_object($args['chat_id'])){
if(isset($args['chat_id'])&&isset($args['chat_id']->update_id)){
$args['chat_id']=@$this->getUpdateInType($args['chat_id']);
$args['chat_id']=isset($args['chat_id']->chat)?$args['chat_id']->chat->id:@$args['chat_id']->from->id;
}else $args['chat_id']=isset($args['chat_id']->chat)?$args['chat_id']->chat->id:@$args['chat_id']->from->id;
}if(isset($args['user_id'])&&is_object($args['user_id'])){
if(isset($args['user_id']->update_id)){
$args['user_id']=@$this->getUpdateInType($args['user_id']);
$args['user_id']=isset($args['user_id']->chat)?$args['user_id']->chat->id:@$args['user_id']->from->id;
}else $args['user_id']=isset($args['user_id']->chat)?$args['user_id']->chat->id:@$args['user_id']->from->id;
}if(isset($args['text'])){
if(is_array($args['text']))$args['text']=array_read($args['text']);
if(is_object($args['text']))$args['text']=var_read($args['text']);
}if(isset($args['caption'])){
if(is_array($args['caption']))$args['caption']=array_read($args['caption']);
if(is_object($args['caption']))$args['caption']=var_read($args['caption']);
}
return $args;
}
}

?>
