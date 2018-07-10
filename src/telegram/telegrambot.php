<?php
/* TelegramBot Class
 * params : String token = ''
 * use : you can make a API robot on telegram by BotFather API_KEY (token)
 
 $bot = new xn\Telegram\TelegramBot( string );
 */

namespace xn\Telegram;

use xn;

require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "button.php";
require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "inlineButton.php";
require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "queryResult.php";
require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "saveButtons.php";
require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "sends.php";
require $GLOBALS['-XN-']['dirNameDir'] . "telegram" . DIRECTORY_SEPARATOR . "settings" . DIRECTORY_SEPARATOR . "telegramBot" . DIRECTORY_SEPARATOR . "saveMsgs.php";

/* TelegramBot for APIs bot
 * params : String token = ""
 * use :
 
 $bot = new xn\Telegram\TelegramBot( String );
 */
class TelegramBot {
  public $data,
      // $bot->token : get token
         $token,
      // $final : last returned from request
         $final,
      // $results : all resturns from requests
         $results = [],
      // $sents : all requests method and args
         $sents = [],
      /* $save
       * If it is False, less memory is consumed and $results ,$sents ,$data methods disabled
       * use :
       
         $bot->save = false; // inactive
         $bot->save = true ; // active
       */
         $save = true,
         $last,
      /* $parser
       * If it is True, parsing args is working
       * use :
       
         $bot->last = false; // inactive
         $bot->last = true ; // active
       */
         $parser = true,
      /* on/off variables
         you can use variables in text and caption
       * variables :
         * messages variables :
             use for messages save by method $bot->msgs
           * use:
             %message%
           * example :
             admin username is @%amin_username%
         * updates variables :
             use for give a text from update
           * use:
             %method1.method2. ...%
           * example :
             hello %message.new_chat_member.first_name% welcom to group %message.chat.title% :)
         * a % char :
             %%
           * example :
             hello %%message.from.first_name%%%%
           * return :
             hello %message.from.first_name%%
       * enable variables :
         $bot->variables = true;
       * disable variables :
         $bot->variables = false;
       */
         $variables = false,
      /* run callback for when the job is finished but no request has been executed.
       * use :
         
         $bot->noresponce = function(){
           // codes...
         }
       * disable :
         $bot->noresponce = false;
       */
         $notresponse = false,
      /* auto action for some requests
       * enable :
         $bot->autoaction = true;
       * disable :
         $bot->autoaction = false;
       * list for actions :
         sendMessage   -> typing
         sendPhoto     -> upload_photo
         sendVideo     -> upload_video
         sendAudio     -> upload_audio
         sendDocument  -> upload_document
         sendVoice     -> record_audio
         sendVideoNote -> upload_video_note
       */
         $autoaction = false,
      /* handle callback for all requests
       * args : Object data {
                         method    , // request method
                         arguments , // input args
                         result    , // result returned
                         level       // level request
                       }
       * save function :
         $bot->handle = function( data ){
           // codes...
         }
       * disable :
         $bot->handle = false;
       */
         $handle = false;
  public $keyboard,
         $inlineKeyboard,
         $foreReply,
         $removeKeyboard,
         $queryResult,
         $menu,
         $send,
         $msgs;
  
  // require send object
  public function send($chat = null, $level = null) {
    return new TelegramBotSends($this, $chat, $level);
  }
  // set token (and save last token)
  public function setToken($token = '') {
    $this->last = $this->token;
    $this->token = $token;
    return $this;
  }
  // back last token to defult token
  public function backToken() {
    $token = $this->token;
    $this->token = $this->last;
    $this->last = $token;
    return $this;
  }
  public function __construct($token = '') {
    $this->token = $token;
    
    $this->keyboard       = new xn\Telegram\Settings\TelegramBot\keyboard;
    $this->inlineKeyboard = new xn\Telegram\Settings\TelegramBot\inlineKeyboard;
    $this->queryResult    = new xn\Telegram\Settings\TelegramBot\queryResult;
    $this->menu           = new xn\Telegram\Settings\TelegramBot\buttonSave;
    $this->send           = new xn\Telegram\Settings\TelegramBot\sends($this);
    $this->msgs           = new xn\Telegram\Settings\TelegramBot\saveMsgs;
    $this->forceReply     = ["force_reply" => true];
    $this->removeKeyboard = ["remove_keyboard" => true];
  }
  /* give update(s)
   * webhook :
     * return : Object of webhook Updates
     * use :
     
     $bot->update();
   * get Updates :
     * params : Integer offset = -1,
                Integer limit = 1,
                Integer timeout = 0
     * return : Array of Result of getUpdates
     * use :
     
     $bot->update( Integer , Integer , Integer );
   */
  public function update($offset = -1, $limit = 1, $timeout = 0) {
    if(isset($this->data->message_id)) return $this->data;
    elseif($this->data = json_decode(file_get_contents("php://input")))return $this->data;
    else $res = $this->data = $this->request("getUpdates", [
        "offset"  => $offset,
        "limit"   => $limit,
        "timeout" => $timeout
    ], 3);
    return (object)$res;
  }
  
  /* request to telegram APIs
   * params : String method,
              Array args = [],
              Integer level = 3
   * levels for (All) requests :
     1 : very fast & just last request & limited for webhook / not returning
     2 : fast / not returning
     3 : normal / object returning
     4 : PWRTelegram / fast / not returning
     5 : PWRTelegram / normal / object returning
   * use :
   
   $bot->request( String , Array , Level );
   */
  public function request($method, $args = [], $level = 3) {
    $args   = $this->parse_args($args);
    $res    = false;
    $func   = $this->handle;
    $handle = $func?
      new ThumbCode( function() use(&$method, &$args, &$res, &$level, &$func) {
        $func((object) [
          "method"    => $method,
          "arguments" => $args,
          "result"    => $res,
          "level"     => $level
        ]);
      }): false;
    if($this->autoaction && isset($args['chat_id'])) {
    switch(strtolower($method)) {
      case "sendmessage":
        $action = "typing";
      break;
      case "sendphoto":
        $action = "upload_photo";
      break;
      case "sendvoice":
        $action = "record_audio";
      break;
      case "sendvideo":
        $action = "upload_video";
      break;
      case "sendvideonote":
        $action = "uplaod_video_note";
      break;
      case "sendaudio":
        $action = "upload_audio";
      break;
      case "senddocument":
        $action = "upload_document";
      break;
      default:
        $action = false;
      break;
    }
    if($action)
      $this->request("sendChatAction", [
        "chat_id" => $args['chat_id'],
        "action"  => $action
      ]);
    }
    if($level == 1) {
      $args['method'] = $method;
      print json_encode($args);
      $res = true;
    }
    elseif($level == 2) {
      $res = @fopen("https://api.telegram.org/bot$this->token/$method?".http_build_query($args),'r');
      if($res)fclose($res = true);
      else $res = false;
    }
    elseif($level == 3) {
      $curl = curl_init("https://api.telegram.org/bot$this->token/$method");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
      $res = json_decode(curl_exec($curl));
      curl_close($curl);
    }
    elseif($level == 4) {
      $res = @fopen("https://api.pwrtelegram.xyz/bot$this->token/$method?".http_build_query($args),'r');
      if($res)fclose($res = true);
      else $res = false;
    }
    elseif($level == 5) {
      $curl = curl_init("https://api.pwrtelegram.xyz/bot$this->token/$method");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
      $res = json_decode(curl_exec($curl));
      curl_close($curl);
    }
    else
      return false;
    $args['method'] = $method;
    $args['level']  = $level;
    if($this->save) {
      $this->sents[]   = $args;
      $this->results[] = $this->final = $res;
    }
    if($res === false)return false;
    if($res === true)return true;
    if(! $res) {
      $server = [
        "OUTPUT",
        "api.telegram.org",
        "api.telegram.org",
        "api.pwrtelegram.xyz",
        "api.pwrtelegram.xyz"
      ] [$level - 1];
      new XNError("TelegramBot","network error for Connect to $server", 1);
      return false;
    }
    elseif(! $res->ok) {
      new XNError("TelegramBot", "$res->description [$res->error_code]", 1);
      return $res;
    }
    return $res;
  }
  // reset history for requests
  public function reset() {
    $this->final   = null;
    $this->results = [];
    $this->sents   = [];
    $this->data    = null;
  }
  // ending the work
  public function close() {
    $this->__destruct();
  }
  public function __destruct() {
    $this->final          = null;
    $this->results        = null;
    $this->sents          = null;
    $this->data           = null;
    $this->token          = null;
    $this->inlineKeyboard = null;
    $this->keyboard       = null;
    $this->forceReply     = null;
    $this->removeKeyboard = null;
    $this->queryResult    = null;
    $this->send           = null;
    $this->menu           = null;
    if($this->notresponse) ($this->notresponse) ();
  }

  // sendMessage
  public function sendMessage($chat, $text, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['text']    = $text;
    return $this->request("sendMessage", $args, $level);
  }
  // sendMessage(s)
  public function sendMessages($chat, $text, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $texts = subsplit($text, 4096);
    foreach($texts as $text) {
      $args['text'] = $text;
      $this->request("sendMessage", $args, $level);
    }
    return $this;
  }
  // sendMessage + reply_markup:remove_keyboard
  public function sendMessageRemoveKeyboard($chat, $text, $args = [],$level = 3) {
    $args['chat_id'] = $chat;
    $args['text']    = $text;
    $args['reply_markup'] = json_encode(["remove_keyboard" => true]);
    return $this->request("sendMessage", $args, $level);
  }
  // sendMessage + reply_markup:force_reply
  public function sendMessageForceReply($chat, $text, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['text']    = $text;
    $args['reply_markup'] = json_encode(['force_reply' => true]);
    return $this->request("sendMessage", $args, $level);
  }
  // sendChatAction
  public function sendAction($chat, $action, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => $action
    ], $level);
  }
  // sendChatAction:upload_photo
  public function sendUploadingPhoto($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "upload_photo"
    ], $level);
  }
  // sendChatAction:upload_video
  public function sendUploadingVideo($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "upload_video"
    ], $level);
  }
  // sendChatAction:upload_audio
  public function sendUploadingAudio($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "upload_audio"
    ], $level);
  }
  // sendChatAction:upload_document
  public function sendUploadingDocument($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "upload_document"
    ], $level);
  }
  // sendChatAction:upload_video_note
  public function sendUploadingVideoNote($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "upload_video_note"
    ], $level);
  }
  // sendChatAction:find_location
  public function sendFindingLocation($chat, $level = 3) {
    return $this->request("sendChatAction", [
       "chat_id" => $chat,
       "action"  => "find_location"
    ], $level);
  }
  // sendChatAction:record_video
  public function sendRecordingVideo($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "record_video"
    ], $level);
  }
  // sendChatAction:record_audio
  public function sendRecordingAudio($chat, $level = 3) {
     return $this->request("sendChatAction", [
       "chat_id" => $chat,
       "action"  => "record_audio"
    ], $level);
  }
  // sendChatAction:record_video_note
  public function sendRecordingVideoNote($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "record_video_note"
    ], $level);
  }
  // sendChatAction:typing
  public function sendTyping($chat, $level = 3) {
    return $this->request("sendChatAction", [
      "chat_id" => $chat,
      "action"  => "typing"
    ], $level);
  }
  // setWebhook
  public function setWebhook($url = '', $args = [], $level = 3) {
    $args['url'] = $url? $url: '';
    return $this->request("setWebhook", $args, $level);
  }
  // deleteWebhook
  public function deleteWebhook($level = 3) {
    return $this->request("setWebhook", [], $level);
  }
  // getChat
  public function getChat($chat,$level = 3) {
    return $this->request("getChat", [
      "chat_id" => $chat
    ], $level);
  }
  // getChatMembersCount
  public function getMembersCount($chat, $level = 3) {
    return $this->request("getChatMembersCount", [
      "chat_id" => $chat
    ], $level);
  }
  // getChatMember
  public function getMember($chat, $user, $level = 3) {
    return $this->request("getChatMember", [
      "chat_id" => $chat,
      "user_id" => $user
    ], $level);
  }
  // getUserProfilePhotos
  public function getProfile($user, $level = 3) {
    $args['user_id'] = $user;
    $args['chat_id'] = $user;
    return $this->request("getUserProfilePhotos", $args, $level);
  }
  // kickChatMember
  public function banMember($chat, $user, $time = false, $level = 3) {
    $args = [
      "chat_id" => $chat,
      "user_id" => $user
    ];
    if($time) $args['until_date'] = $time;
    return $this->request("kickChatMember", $args, $level);
  }
  // unbanChatMember
  public function unbanMember($chat, $user, $level = 3) {
    return $this->request("unbanChatMember", [
      "chat_id" => $chat,
      "user_id" => $user
    ], $level);
  }
  // kickChatMember + unbanChatMember
  public function kickMember($chat, $user, $level = 3) {
    return [$this->banMember($chat, $user, $level), $this->unbanMember($chat,$user,$level)];
  }
  // getMe
  public function getMe($level = 3) {
    return $this->request("getMe", [], $level);
  }
  // getWebhookInfo
  public function getWebhook($level = 3) {
    return $this->request("getWebhookInfo", [], $level);
  }
  // restrictChatMember
  public function restrictMember($chat, $user, $args, $time = false, $level = 3) {
    foreach($args as $key => $val) $args["can_$key"] = $val;
    $args['chat_id'] = $chat;
    $args['user_id'] = $user;
    if($time) $args['until_date'] = $time;
    return $this->request("restrictChatMember", $args, $level);
  }
  // promoteChatMember
  public function promoteMember($chat, $user, $args = [], $level = 3) {
    foreach($args as $key => $val) $args["can_$key"] = $val;
    $args['chat_id'] = $chat;
    $args['user_id'] = $user;
    return $this->request("promoteChatMember", $args, $level);
  }
  //  exportChatInviteLink
  public function exportInviteLink($chat, $level = 3) {
    $this->request("exportChatInviteLink", [
      "chat_id" => $chat
    ], $level);
  }
  // setChatPhoto
  public function setChatPhoto($chat, $photo, $level = 3) {
    return $this->request("setChatPhoto", [
      "chat_id" => $chat,
      "photo"   => $photo
    ], $level);
  }
  // deleteChatPhoto
  public function deleteChatPhoto($chat, $level = 3) {
    return $this->request("deleteChatPhoto", [
      "chat_id" => $chat
    ], $level);
  }
  // setChatTitle
  public function setTitle($chat, $title, $level = 3) {
    return $this->request("setChatTitle", [
      "chat_id" => $chat,
      "title"   => $title
    ], $level);
  }
  // setChatDescription
  public function setDescription($chat, $description, $level = 3) {
    return $this->request("setChatDescription", [
      "chat_id"     => $chat,
      "description" => $description
    ], $level);
  }
  // pinChatMember
  public function pinMessage($chat, $message, $disable = false, $level = 3) {
    return $this->request("pinChatMessage", [
      "chat_id"              => $chat,
      "message_id"           => $message,
      "disable_notification" => $disable
    ], $level);
  }
  // unpinChatMessage
  public function unpinMessage($chat, $level = 3) {
    return $this->request("unpinChatMessage", [
      "chat_id" => $chat
    ], $level);
  }
  // leaveChat
  public function leaveChat($chat, $level = 3) {
    return $this->request("leaveChat",[
      "chat_id" => $chat 
    ], $level);
  }
  // getChatAdministrators
  public function getAdmins($chat, $level = 3) {
    return $this->request("getChatAdministrators", [
      "chat_id" => $chat
    ], $level);
  }
  // setChatStickerSet
  public function setChatStickerSet($chat, $sticker, $level = 3) {
    return $this->request("setChatStickerSet", [
      "chat_id"          => $chat,
      "sticker_set_name" => $sticker
    ] ,$level);
  }
  // deleteChatStickerSet
  public function deleteChatStickerSet($chat, $level = 3) {
    return $this->request("deleteChatStickerSet", [
      "chat_id" => $chat
    ], $level);
  }
  // answerCallbackQuery
  public function answerCallback($id, $text, $args = [], $level = 3) {
    $args['callback_query_id'] = $id;
    $args['text']              = $text;
    return $this->request("answerCallbackQuery", $args, $level);
  }
  // editMessageText
  public function editText($text, $args = [], $level= 3 ) {
    $args['text'] = $text;
    return $this->request("editMessageText", $args, $level);
  }
  // editMessageText->not inline message
  public function editMessageText($chat, $msg, $text, $args = [], $level = 3) {
    $args['chat_id']    = $chat;
    $args['message_id'] = $msg;
    $args['text']       = $text;
    return $this->request("editMessageText", $args, $level);
  }
  // editMessageText->inline message
  public function editInlineText($msg, $text, $args = [], $level = 3) {
    $args['inline_message_id'] = $msg;
    $args['text']              = $text;
    return $this->request("editMessageText", $args, $level);
  }
  // editMessageCaption
  public function editCaption($caption, $args = [], $level = 3) {
    $args['caption'] = $caption;
    return $this->request("editMessageCaption", $args, $level);
  }
  // editMessageCaption->not inline message
  public function editMessageCaption($chat, $msg, $caption, $args = [], $level = 3) {
    $args['chat_id']    = $chat;
    $arsg['message_id'] = $msg;
    $args['caption']    = $caption;
    return $this->request("editMessageCaption", $args, $level);
  }
  // editMessageCaption->inline message
  public function editInlineCaption($msg, $caption, $args = [], $level = 3) {
    $arsg['inline_message_id'] = $msg;
    $args['caption']           = $caption;
    return $this->request("editMessageCaption", $args, $level);
  }
  // editMessaegReplyMarkup
  public function editReplyMarkup($reply_makup, $args = [], $level = 3) {
    $args['reply_markup'] = $reply_markup;
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // editMessageReplyMarkup->not inline message
  public function editMessageReplyMarkup($chat, $msg, $reply_makup, $args = [], $level = 3) {
    $args['chat_id']      = $chat;
    $args['message_id']   = $msg;
    $args['reply_markup'] = $reply_markup;
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // editMessageReplyMarkup->inline message
  public function editInlineReplyMarkup($msg, $reply_makup, $args = [], $level = 3) {
    $args['inline_message_id'] = $msg;
    $args['reply_markup']      = $reply_markup;
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // editMessageReplyMarkup:inline_keyboard
  public function editInlineKeyboard($reply_makup, $args = [], $level = 3) {
    $args['reply_markup'] = ["inline_keyboard" => $reply_markup];
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // editMessageReplyMarkup:inline_keyboard->not inline message
  public function editMessageInlineKeyboard($chat, $msg, $reply_makup, $args = [], $level = 3) {
    $args['chat_id']      = $chat;
    $args['message_id']   = $msg;
    $args['reply_markup'] = ["inline_keyboard" => $reply_markup];
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // editMessageReplyMarkup:inline_keyboard->inline message
  public function editInlineInlineKeyboard($msg, $reply_makup, $args = [], $level = 3) {
    $args['inline_message_id'] = $msg;
    $args['reply_markup']      = ["inline_keyboard" => $reply_markup];
    return $this->request("editMessageReplyMarkup", $args, $level);
  }
  // deleteMessage
  public function deleteMessage($chat, $message, $level = 3) {
    return $this->request("deleteMessage", [
      "chat_id"    => $chat,
      "message_id" => $message
    ], $level);
  }
  /* deleteMessage(s)
   * range :
     $bot->deleteMessages( chat , [ min ID , max ID ] , level+5 );
   * ids :
     $bot->deleteMessages( chat , [ ... IDs ] , level );
   */
  public function deleteMessages($chat, $messages, $level = 3) {
    if($level > 5) {
      $level -= 5;
      $from   = min(... $messages);
      $to     = max(... $messages);
      for(; $from <= $to; $from++)
        $this->request("deleteMessage", [
          "chat_id"    => $chat,
          "message_id" => $from
        ], $level);
    }
    else{
      foreach($messages as $message)
        $this->request("deleteMessage", [
          "chat_id"    => $chat,
          "message_id" => $message
        ], $level);
    }
  }
  // sendFile->$type type
  public function sendMedia($chat, $type, $file, $args = [], $level = 3) {
    $type = strtolower($type);
    if($type == "videonote") $type = "video_note";
    $args['chat_id'] = $chat;
    $args[$type]     = $file;
    return $this->request("send" . str_replace('_', '', $type), $args, $level);
  }
  // sendFile:file_id->auto type
  public function sendFile($chat, $file, $args = [], $level = 3) {
    $type = TelegramCode::getFileType($file);
    if(! $type) return false;
    $args['chat_id'] = $chat;
    $args[$type]     = $file;
    return $this->request("send" . str_replace('_', '', $type), $args, $level);
  }
  // getStivkerSet
  public function getStickerSet($name, $level = 3) {
    return $this->request("getStickerSet", [
      "name" => $name
    ], $level);
  }
  // sendDocument
  public function sendDocument($chat, $file, $args = [], $level = 3) {
    $args['chat_id']  = $chat;
    $args['document'] = $file;
    return $this->request("sendDocument", $args, $level);
  }
  // sendPhoto
  public function sendPhoto($chat, $file, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['photo']   = $file;
    return $this->request("sendPhoto", $args, $level);
  }
  // sendVideo
  public function sendVideo($chat, $file, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['video']   = $file;
    return $this->request("sendVideo", $args, $level);
  }
  // sendAudio
  public function sendAudio($chat, $file, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['audio']   = $file;
    return $this->request("sendAudio", $args, $level);
  }
  // sendVoice
  public function sendVoice($chat, $file, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['voice']   = $file;
    return $this->request("sendVoice", $args, $level);
  }
  // sendSticker
  public function sendSticker($chat, $file, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['sticker'] = $file;
    return $this->request("sendSticker", $args, $level);
  }
  // sendVideoNote
  public function sendVideoNote($chat, $file, $args = [], $level = 3) {
    $args['chat_id']    = $chat;
    $args['video_note'] = $file;
    return $this->request("sendVideoNote", $args, $level);
  }
  // uploadStickerFile
  public function uploadStickerFile($user, $file, $level = 3) {
    return $this->request("uploadStickerFile", [
      "user_id"     => $user,
      "png_sticker" => $file
    ], $level);
  }
  // createNesStickerSet
  public function createNewStickerSet($user, $name, $title, $args = [], $level = 3) {
    $args['user_id'] = $user;
    $args['name']    = $name;
    $args['title']   = $title;
    return $this->request("createNewStickerSet", $args, $level);
  }
  // addStickerToSet
  public function addStickerToSet($user, $name, $args = [], $level = 3) {
    $args['user_id'] = $user;
    $args['name']    = $name;
    return $this->request("addStickerToSet", $args, $level);
  }
  // setStickerPositionInSet
  public function setStickerPositionInSet($sticker, $position, $level = 3) {
    return $this->request("setStickerPositionInSet", [
      "sticker"  => $sticker,
      "position" => $position
    ], $level);
  }
  // deleteStickerFromSet
  public function deleteStickerFromSet($sticker, $level = 3) {
    return $this->request("deleteStickerFromSet", [
      "sticker" => $sticker
    ], $level);
  }
  // answerInlineQuery
  public function answerInline($id, $results, $args = [], $switch = [], $level = 3) {
    $args['inline_query_id'] = $id;
    $args['results']         = is_array($results)? json_encode($results): $results;
    if($switch['text'])     $args['switch_pm_text']      = $switch[ 'text'];
    if($switch['parameter'])$args['switch_pm_parameter'] = $switch['parameter'];
    return $this->request("answerInlineQuery", $args, $level);
  }
  // answerPreCheckoutQuery
  public function answerPreCheckout($id, $ok = true, $level = 3) {
    if($ok === true) $args = [
        "pre_checkout_query_id" => $id,
        "ok"                    => true
      ];
    else $args = [
        "pre_checkout_query_id" => $id,
        "ok"                    => false,
        "error_message"         => $ok
      ];
    return $this->request("answerPreCheckoutQuery", $args, $level);
  }
  // setGameScore
  public function setGameScore($user, $score, $args = [], $level = 3) {
    $args['user_id'] = $user;
    $args['score']   = $score;
    return $this->request("setGameScore", $args, $level);
  }
  // setGameHighScores
  public function getGameHighScores($user, $args = [], $level = 3) {
    $args['user_id'] = $user;
    return $this->request("getGameHighScores", $args, $level);
  }
  // sendGame
  public function sendGame($chat, $name, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['name']    = $name;
    return $this->request("sendGame", $args, $level);
  }
  // getFile
  public function getFile($file, $level = 3) {
    return $this->request("getFile", [
       "file_id" => $file
    ], $level);
  }
  // sendContact
  public function sendContact($chat, $phone, $args = [], $level = 3) {
    $args['chat_id']      = $chat;
    $args['phone_number'] = $phone;
    return $this->request("sendContact", $args, $level);
  }
  // sendVenue
  public function sendVenue($chat, $latitude, $longitude, $title, $address, $args = [], $level = 3) {
    $args['chat_id']   = $chat;
    $args['latitude']  = $latitude;
    $args['longitude'] = $longitude;
    $args['title']     = $title;
    $args['address']   = $address;
    return $this->request("sendVenue", $args, $level);
  }
  // stopMessageLiveLocation
  public function stopMessageLiveLocation($args, $level = 3) {
    return $this->request("stopMessageLiveLocation", $args, $level);
  }
  // editMessageLiveLocation
  public function editMessageLiveLocation($latitude, $longitude, $args = [], $level = 3) {
   $args['latitude']  = $latitude;
   $args['longitude'] = $longitude;
   return $this->request("editMessageLiveLocation", $args, $level);
  }
  // sendLocation
  public function sendLocation($chat, $latitude, $longitude, $args = [], $level = 3) {
   $args['chat_id']   = $chat;
   $args['latitude']  = $latitude;
   $args['longitude'] = $longitude;
   $this->request("sendLocation", $args, $level);
  }
  // sendMediaGroup
  public function sendMediaGroup($chat, $media, $args = [], $level = 3) {
    $args['chat_id'] = $chat;
    $args['media']   = json_encode($media);
    return $this->request("sendMediaGroup", $args, $level);
  }
  // forwardMessage
  public function forwardMessage($chat, $from, $message, $disable = false, $level = 3) {
    return $this->request("forwardMessage", [
      "chat_id"              => $chat,
      "from_chat_id"         => $from,
      "message_id"           => $message,
      "disable_notification" => $disable
    ], $level);
  }

  /* download file by file_path
   * params : String file_path,
              Integer level = 3,
                      3 : Telegram
                      5 : PWRTelegram
              Boolean speed = false
   * use :
   
     $file_path = $bot->getFile($file_id)->result->file_path;
     $file_contents = $bot->readFile($file_path);
   */
  public function readFile($path, $level = 3, $speed = false) {
    if($speed) $func = "fget";
    else $func = "file_get_contents";
    if($level == 3) {
      return ($func) ("https://api.telegram.org/file/bot$this->token/$path");
    }elseif($level == 5) {
      return ($func) ("https://api.pwrtelegram.xyz/file/bot$this->token/$path");
    }else return false;
  }
  /* auto download file_id
   * params : String file_id,
              Integer level = 3
                      3 : Telegram
                      5 : PWRTelegram
   * use :
   
   $bot->downloadFile( Strign , Level );
   */
  public function downloadFile($file, $level = 3) {
    return $this->readFile($this->getFile($file, 3)->result->file_path, $level);
  }
  /* downloadFileProgress
   * params : String file_id,
              Object(Closure) action(input params),
              Integer offset_download, // for example run for any 20kb(20480000) downloading
              Integer level = 3
                      3 : Telegram
                      5 : PWRTelegram
   * input params : Object data {
                      String data, // downloaded data
                      Integer downloaded, // downloaded size (b)
                      Integer size, // file size (b)
                      Double time, // time spent for download
                      Double endtime, // time remaining download
                      Double speed, // download every second (b/s)
                      Double pre // downloaded percentage
                    }
   * use :
     
     $bot->downloadFileProgress( String , Object(Closure) , Integer , Integer );
   */
  public function downloadFileProgress($file, $func, $al, $level = 3) {
    $file=$this->request("getFile", [
      "file_id" => $file
    ], $level);
    if(! $file->ok) return false;
    $size = $file->result->file_size;
    $path = $file->result->file_path;
    $time = microtime(true);
    if($level == 3) {
      return fgetprogress("https://api.telegram.org/file/bot$this->token/$path", function($data)use($size, $func, $time) {
        $dat   = strlen($data);
        $up    = microtime(true) - $time;
        $speed = $dat / $up;
        $all   = $size / $dat * $time - $time;
        $pre   = 100 / ($size / $dat);
        return $func((object)["content"    => $data,
                              "downloaded" => $dat,
                              "size"       => $size,
                              "time"       => $up,
                              "endtime"    => $all,
                              "speed"      => $speed,
                              "pre"        => $pre
                             ]);
      }, $al);
    }elseif($level == 5) {
      return fgetprogress("https://api.pwrtelegram.xyz/file/bot$this->token/$path",function($data)use($size, $func, $time) {
        $dat   = strlen($data);
        $up    = microtime(true) - $time;
        $speed = $dat / $up;
        $all   = $size / $dat * $time - $time;
        $pre   = $size / $dat * 100;
        return $func((object)["content"    => $data,
                              "downloaded" => $dat,
                              "size"       => $size,
                              "time"       => $up,
                              "endtime"    => $all,
                              "speed"      => $speed,
                              "pre"        => $pre
                             ]);
      }, $al);
    }else return false;
  }

  /* get all chat members
   * params : Integer chat_id
   * use :
   
   $bot->getAllMembers( Integer );
   */
  public function getAllMembers($chat){
    return json_decode(file_get_contents("http://xns.elithost.eu/getparticipants/?token=$this->token&chat=$chat"));
  }

  /* type for upadate :
     message
     callback_query
     chosen_inline_result
     inline_query
     chanel_post
     edited_channel_post
     shipping_query
     pre_checkout_query
     unknow_update
   */
  public function updateType($update=false){
    if(! $update) $update = $this->lastUpdate();
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
  }
  /* update in type
   $bot->update()->{$bot->updateType()} === $bot->getUpdateInType()
   */
  public function getUpdateInType($update=false){
    return ($update? $update: $this->lastUpdate())->{$this->updateType()};
  }
  /* read updates ( by getUpdates method )
   * you can use this for server (not need ip)
   * params : Object(Closure) updates_action(update),
              Integer while   = 0, // 0 = INF
              Integer limit   = 1, // 0 = INF
              Integer timeout = 0  // 0 = INF
   * use :
   
     $bot->readUpdates( Object(Closure) , Integer , Integer , Integer );
   */
  public function readUpdates($func, $while = 0, $limit = 1, $timeout = 0) {
    if($while == 0) $while = -1;
    $offset = 0;
    while($while > 0 || $while < 0) {
      $updates = $this->update($offset, $limit, $timeout);
      if(isset($updates->message_id)) {
        if($offset == 0)$updates = (object)["result" => [$updates]];
      else return;
    }
    if(isset($updates->result)) {
      foreach($updates->result as $update) {
          $offset = $update->update_id + 1;
          if($func($update))return true;
        }
      $while--;
      }
    }
  }
  // update is in filter updates -> exit;
  public function filterUpdates($filter = [], $func = false) {
    if(in_array($this->updateType(), $filter)) {
      if($func)$func($this->data);
      exit();
    }
  }
  // update not is in unfilter updates -> exit;
  public function unfilterUpdates($filter = [], $func = false) {
    if(!in_array($this->updateType(), $filter)) {
      if($func)$func($this->data);
      exit();
    }
  }
  // seach user&chat in update
  public function getUser($update = false) {
    $update = $this->getUpdateInType($update);
    if(! isset($update->chat)) return (object)[
      "chat" => $update->from,
      "from" => $update->from
    ];
    return (object)[
      "chat" => $update->chat,
      "from" => $update->from
    ];
  }
  // search date in update
  public function getDate($update = false) {
    $update = $this->getUpdateInType($update);
    if(isset($update->date)) return $update->date;
    return false;
  }
  // search data in update
  public function getData($update = false) {
    $update = $this->getUpdateInType($update);
    if(isset($update->text))  return $update->text;
    if(isset($update->query)) return $update->query;
    return false;
  }
  // search chat_id and equal to your input
  public function isChat($user, $update = false) {
    $chat = $this->getUser($update)->chat->id;
    if(is_array($user) && in_array($chat, $user)) return true;
    elseif($user == $chat)return true;
    return false;
  }
  // return last Update
  public function lastUpdate() {
    $update = $this->update();
    if(isset($update->update_id))return $update;
    elseif(isset($update->result[0]->update_id)) return $update->result[0];
    else return [];
  }
  // return all Updates
  public function getUpdates() {
    $update = $this->update(0, 999999999999, 0);
    if(isset($update->update_id)) return [$update];
    elseif($update->result[0]->update_id) return $update->result;
    else return [];
  }
  // return lastUpdateId
  public function lastUpdateId($update = false){
    if(! $update) $update = $this->update(-1, 1, 0);
    if($update->result[0]->update_id)
      return end($update->result)->update_id;
    elseif(isset($update->update_id))
      return $update->update_id;
    else return 0;
  }
  /* search file in message and return file type :
     photo
     voice
     audio
     video
     sticker
     document
     videonote
     false // message not have file
   */
  public function fileType($message = false) {
    if(!     $message && isset($this->lastUpdate()->message)) $message = $this->lastUpdate()->message;
    elseif(! $message)               return false;
    if(isset($message->photo))       return "photo";
    if(isset($message->voice))       return "voice";
    if(isset($message->audio))       return "audio";
    if(isset($message->video))       return "video";
    if(isset($message->sticker))     return "sticker";
    if(isset($message->document))    return "document";
    if( isset($message->video_note)) return "videonote";
    return false;
  }
  /* search file and return file Object
     false -> message not have file
   */
  public function fileInfo($message = false) {
    if(!     $message && isset($this->lastUpdate()->message))$message = $this->lastUpdate()->message;
    elseif(! $message)              return false;
    if(isset($message->photo))      return end($message->photo);
    if(isset($message->voice))      return $message->voice;
    if(isset($message->audio))      return $message->audio;
    if(isset($message->video))      return $message->video;
    if(isset($message->sticker))    return $message->sticker;
    if(isset($message->document))   return $message->document;
    if(isset($message->video_note)) return $message->video_note;
    return false;
  }
  // return exists file in message
  public function isFile($message = false) {
    if(!     $message && isset($this->lastUpdate()->message))$message = $this->lastUpdate()->message;
    elseif(! $message)       return false;
    if(      $message->text) return false;
    return true;
  }
  /* convert file (type and name)
   * params : String file_id,
              String file_type,
                     document
                     photo
                     video
                     video_note|videonote
                     voice
                     audio
                     sticker
              String file_name
              String chat_id, // thumb or destination
              Integer level = 3
                      3 : Telegram
                      5 : PWRTelegram
   * use :
   
   $bot->convertFile( String , String , String , String , Level );
   */
  public function convertFile($file, $type, $name, $chat, $level = 3) {
    if(file_exists($name)) $read = file_get_contents($name);
    else $read = false;
    file_put_contents($name, $this->downloadFile($file));
    $r = $this->sendMedia($chat, $type, new CURLFile($name));
    unlink($name);
    if($read !== false) file_put_contents($name, $read);
    return $r;
  }
  /* send updates to more Scripts ( Webhook Webservice )
   * params : String url, // script address
              Object update
   * use :
   
   $bot->sendUpdate( String , Update );
   */
  public function sendUpdate($url, $update = false) {
    if($update === false) $update = $this->update();
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_CUSTOMREQUEST,  "PUT");
    curl_setopt($c, CURLOPT_POSTFIELDS,     $update);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $r = curl_exec($c);
    curl_close($c);
    return $r;
  }

  /* parse and shorting args
   * file                     -> for file id or file name(upload) or file url for all files type
   * chat                     -> chat_id
   * user                     -> user_id
   * message or msg or msg_id -> message_id or inline_message_id
   * id                       -> for callback_query_id or inline_query_id
   * mode or parse            -> parse_mode
   * markup                   -> reply_markup
   * reply                    -> reply_to_message_id
   * from_chat                -> from_chat_id
   * phone                    -> phone_number
   + auto json_encode for allowed_updates and reply_markup
   */
  private function parse_args($args = []) {
    if(! $this->parser) return $args;
    if(  isset($args['user'])) $args['user_id'] = $args['user'];
    if(  isset($args['chat'])) $args['chat_id'] = $args['chat'];
    if(  isset($args['message'] )) $args['message_id'] = $args['message'];
    if(  isset($args['msg']     )) $args['message_id'] = $args['msg'];
    if(  isset($args['msg_id']  )) $args['message_id'] = $args['msg_id'];
    if(! isset($args['chat_id']  ) && isset($args['message_id'])) {
      $args['inline_message_id'] = $args['message_id'];
      unset($args['message_id']);
    }
    if(isset($args['id']))        $args['callback_query_id']   = $args['inline_query_id'] = $args['id'];
    if(isset($args['mode']))      $args['parse_mode']          = $args['mode'];
    if(isset($args['parse']))     $args['parse_mode']          = $args['parse'];
    if(isset($args['markup']))    $args['reply_markup']        = $args['markup'];
    if(isset($args['reply']))     $args['reply_to_message_id'] = $args['reply'];
    if(isset($args['from_chat'])) $args['from_chat_id']        = $args['from_chat'];
    $args['file'] = isset($args['file'])               ? $args['file']               :
                    isset($args['document'])           ? $args['document']           :
                    isset($args['video'])              ? $args['video']              :
                    isset($args['voice'])              ? $args['voice']              :
                    isset($args['video_note'])         ? $args['video_note']         :
                    isset($args['audio'])              ? $args['audio']              :
                    isset($args['sticker'])            ? $args['sticker']            :
                    isset($args['photo_file_id'])      ? $args['photo_file_id']      :
                    isset($args['document_file_id'])   ? $args['document_file_id']   :
                    isset($args['video_file_id'])      ? $args['video_file_id']      :
                    isset($args['voice_file_id'])      ? $args['voice_file_id']      :
                    isset($args['video_note_file_id']) ? $args['video_note_file_id'] :
                    isset($args['audio_file_id'])      ? $args['audio_file_id']      :
                    isset($args['sticker_file_id'])    ? $args['sticker_file_id']    :
                    isset($args['photo_url'])          ? $args['photo_url']          :
                    isset($args['document_url'])       ? $args['document_url']       :
                    isset($args['video_url'])          ? $args['video_url']          :
                    isset($args['voice_url'])          ? $args['voice_url']          :
                    isset($args['video_note_url'])     ? $args['video_note_url']     :
                    isset($args['audio_url'])          ? $args['audio_url']          :
                    isset($args['sticker_url'])        ? $args['sticker_url']        :
                    isset($args['file_id'])            ? $args['file_id']            :
                    isset($args['photo'])              ? $args['photo']              :
                    false;
    if($args['file']) {
      $gettype = TelegramCode::getFileType($args['file']);
      if(is_string($args['file']) &&
        $gettype !== false        &&
        file_exists($args['file']))
          $args['file'] = new CURLFile($args['file']);
      $args['photo']              =
      $args['document']           =
      $args['video']              =
      $args['voice']              =
      $args['video_note']         =
      $args['audio']              =
      $args['sticker']            =
      $args['photo_file_id']      =
      $args['document_file_id']   =
      $args['video_file_id']      =
      $args['voice_file_id']      =
      $args['video_note_file_id'] =
      $args['audio_file_id']      =
      $args['sticker_file_id']    =
      $args['photo_url']          =
      $args['document_url']       =
      $args['video_url']          =
      $args['voice_url']          =
      $args['video_note_url']     =
      $args['audio_url']          =
      $args['sticker_url']        =
      $args['file_id']            =
      $args['file']               ;
    }
    if(isset($args['phone'])) $args['phone_number'] = $args['phone'];
    if(isset($args['allowed_updates']) && (is_array($args['allowed_updates']) || is_object($args['allowed_updates'])))
      $args['allowed_updates'] = json_encode($args['allowed_updates']);
    if(isset($args['reply_markup']) && is_string($args['reply_markup']) && $this->menu->exists($args['reply_markup']))
      $args['reply_markup'] = $this->menu->get($args['reply_markup']);
    if(isset($args['reply_markup']) && (is_array($args['reply_markup']) || is_object($args['reply_markup'])))
      $args['reply_markup'] = json_encode($args['reply_markup']);
    if(isset($args['chat_id']) && is_object($args['chat_id'])) {
      if(isset($args['chat_id']) && isset($args['chat_id']->update_id)) {
        $args['chat_id'] = @$this->getUpdateInType($args['chat_id']);
        $args['chat_id'] = isset($args['chat_id']->chat)?
           $args['chat_id']->chat->id:
          @$args['chat_id']->from->id;
      }
      else
        $args['chat_id'] = isset($args['chat_id']->chat)?
          $args['chat_id']->chat->id:
         @$args['chat_id']->from->id;
    }
    if(isset($args['user_id']) && is_object($args['user_id'])) {
      if(isset($args['user_id']->update_id)) {
        $args['user_id'] = @$this->getUpdateInType($args['user_id']);
        $args['user_id'] = isset($args['user_id']->chat)?
          $args['user_id']->chat->id:
         @$args['user_id']->from->id;
      }
      else
        $args['user_id'] = isset($args['user_id']->chat)?
          $args['user_id']->chat->id:
         @$args['user_id']->from->id;
    }
    if($this->variables && ! isset($args['variables'])) $args['variables'] = true;
      if(isset($args['text'])) {
        $args['text'] = xn\str::toString($args['text']);
        if(isset($args['variables']) && $args['variables']) {
          $msgs = &$this->msgs;
          $up   = $this->data? $this->data: false;
          $args['text'] = preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/", function($x) use(&$msgs, $up) {
            $ms = str_replace('%%', '%', $x[1]);
            if($msgs->exists($ms)) return $msgs->get($ms);
            if($up) {
              $ms = explode('.', $ms);
            foreach($ms as $u)
              if(isset($up->$u))
                $up = $up->$u;
            if(! is_string($up)) return $x[0];
            return $up;
          }
          return $x[0];
        }, $args['text']);
      $args['text'] = str_replace('%%', '%', $args['text']);
    }
    }
    if(isset($args['caption'])) {
      $args['caption'] = xn\str::toString($args['caption']);
      if(isset($args['variables']) && $args['variables']) {
        $msgs = &$this->msgs;
        $up   = $this->data? $this->data: false;
        $args['caption'] = preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/",function($x) use(&$msgs, $up) {
          $ms = str_replace('%%', '%', $x[1]);
          if($msgs->exists($ms)) return $msgs->get($ms);
          if($up) {
            $ms = explode('.', $ms);
            foreach($ms as $u)
              if(isset($up->u))
                $up = $up->u;
            if(! is_string($up)) return $x[0];
            return $up;
          }
          return $x[0];
        }, $args['caption']);
        $args['caption'] = str_replace('%%', '%', $args['caption']);
      }
    }
    return $args;
  }
}

?>
