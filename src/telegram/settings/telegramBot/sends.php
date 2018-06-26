<?php

namespace xn\Telegram\Settings\TelegramBot;

/* fast sending
 $bot->send
 * params : Double chat_id
            Integer level
 
 * set chat :
 $bot->send->chat( chat );
 
 * set level :
 $bot->send->level( level );
 
 * set All :
 ($bot->send)( chat , level );
 
 * build private :
 $bot->send( chat , level );
 */
class sends {
  private $bot;
  public $chat,
         $level;
  
  // set chat
  public function chat($chat) {
    $this->chat;
    return $this;
  }
  // set level
  public function level($level) {
    $this->level = $level;
    return $this;
  }
  public function __construct($bot, $chat = null, $level = null) {
    $this->bot   = $bot;
    $this->chat  = $chat;
    $this->level = $level;
  }
  // set chat & level
  public function __wakeup($chat = null, $level = null) {
    if($chat && $level) {
      $this->chat  = $chat;
      $this->level = $level;
    }
    elseif($chat) {
      if($chat < 100)$this->level = $chat;
      else           $this->chat  = $chat;
    }
    return $this;
  }
  // sendChatAction
  public function action($action) {
    $this->bot->sendAction($this->chat, $action, $this->level);
    return $this;
  }
 // sendChatAction:Typing
  public function typing() {
    $this->bot->sendAction($this->chat, "typing", $this->level);
    return $this;
  }
  // sendMessage
  public function msg($text, $args = []) {
    $this->bot->sendMessage($this->chat, $text, $args, $this->level);
    return $this;
  }
  // sendMessage + reply_murkup
  public function btnmsg($text, $btn, $args = []) {
    $args['reply_markup'] = $btn;
    $this->bot->sendMessage($this->chat, $text, $args, $this->level);
    return $this;
  }
  // sendMedia
  public function media($type, $media, $args = []) {
    $this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
    return $this;
  }
  // sendMedia + caption
  public function mediamsg($type, $media, $caption, $args = []) {
    $args['caption'] = $caption;
    $this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
    return $this;
  }
  // sendMedia + reply_markup
  public function mediabtn($type, $media, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
    return $this;
  }
  // sendMedia + caption + reply_markup
  public function mediamsgbtn($type, $media, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
    return $this;
  }
  // sendPhoto
  public function photo($photo, $args = []) {
    $this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
    return $this;
  }
  // sendVoice
  public function voice($voice, $args = []) {
    $this->bot->sendVoice($this->chat, $voice, $args, $this->level);
    return $this;
  }
  // sendVideo
  public function video($video, $args = []) {
    $this->bot->sendVideo($this->chat, $video, $args, $this->level);
    return $this;
  }
  // sendAudio
  public function audio($audio, $args = []) {
    $this->bot->sendAudio($this->chat, $audio, $args, $this->level);
    return $this;
  }
  // sendVideoNote
  public function videonote($videonote, $args = []) {
    $this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
    return $this;
  }
  // sendSticker
  public function sticker($sticker, $args = []) {
    $this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
    return $this;
  }
  // sendDocument
  public function document($document, $args = []) {
    $this->bot->sendDocument($this->chat, $document, $args, $this->level);
    return $this;
  }
  // sendFile
  public function file($file, $args = []) {
    $this->bot->sendFile($this->chat, $file, $args, $this->level);
    return $this;
  }
  // sendPhoto + caption
  public function photomsg($photo, $caption, $args = []) {
    $args['caption'] = $caption;
    $this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
    return $this;
  }
  // sendVoice + caption
  public function voicemsg($voice, $caption,$args = []) {
    $args['caption'] = $caption;
    $this->bot->sendVoice($this->chat, $voice, $args, $this->level);
    return $this;
  }
  // sendVideo + caption
  public function videomsg($video, $caption, $args = []) {
    $args['caption'] = $caption;
    $this->bot->sendVideo($this->chat, $video, $args, $this->level);
    return $this;
  }
  // sendAudio + caption
  public function audiomsg($audio, $caption, $args = []) {
    $args['caption'] = $caption;
    $this->bot->sendAudio($this->chat, $audio, $args, $this->level);
    return $this;
  }
  // sendVideoNote + caption
  public function videonotemsg($videonote, $caption, $args=[]) {
    $args['caption'] = $caption;
    $this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
    return $this;
  }
  // sendSticker + caption
  public function stickermsg($sticker, $caption, $args=[]) {
    $args['caption'] = $caption;
    $this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
    return $this;
  }
  // sendDocument + caption
  public function documentmsg($document, $caption, $args=[]) {
    $args['caption'] = $caption;
    $this->bot->sendDocument($this->chat, $document, $args, $this->level);
    return $this;
  }
  // sendFile + caption
  public function filemsg($file, $caption, $args = []) {
    $args['caption'] = $caption;
    $this->bot->sendFile($this->chat, $file, $args, $this->level);
    return $this;
  }
  // sendPhoto + caption
  public function photobtn($photo, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendPhoto($this->chat, $photo, $args, $$this->level);
    return $this;
  }
  // sendVoice + reply_markup
  public function voicebtn($voice, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendVoice($this->chat, $voice, $args, $this->level);
    return $this;
  }
  // sendVideo + reply_markup
  public function videobtn($video, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendVideo($this->chat, $video, $args, $this->level);
    return $this;
  }
  // sendAudio + reply_markup
  public function audiobtn($audio, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendAudio($this->chat, $audio, $args, $this->level);
    return $this;
  }
  // sendVideoNote + reply_markup
  public function videonotebtn($videonote, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
    return $this;
  }
  // sendSticker + reply_markup
  public function stickerbtn($sticker, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
    return $this;
  }
  // sendDocument + reply_markup 
  public function documentbtn($document, $markup, $args = []) {
    $args['reply_markup'] = $markup;
    $this->bot->sendDocument($this->chat, $document, $args, $this->level);
    return $this;
  }
  // sendFile + reply_markup
  public function filebtn($file, $markup, $args=[]) {
    $args['reply_markup'] = $markup;
    $this->bot->sendFile($this->chat, $file, $args, $this->level);
    return $this;
  }
  // sendPhoto + caption + reply_markup
  public function photomsgbtn($photo, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendPhoto($this->chat, $photo, $args, $$this->level);
    return $this;
  }
  // sendVoice + caption + reply_markup
  public function voicemsgbtn($voice, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendVoice($this->chat, $voice, $args, $this->level);
    return $this;
  }
  // sendVideo + caption + reply_markup
  public function videomsgbtn($video, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendVideo($this->chat, $video, $args, $this->level);
    return $this;
  }
  // sendAudio + caption + reply_markup
  public function audiomsgbtn($audio, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendAudio($this->chat, $audio, $args, $this->level);
    return $this;
  }
  // sendVideoNote + caption + reply_markup
  public function videonotemsgbtn($videonote, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
    return $this;
  }
  // sendSticker + caption + reply_markup
  public function stickermsgbtn($sticker, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
    return $this;
  }
 // sendDocumeny + caption + reply_markup
  public function documentmsgbtn($document, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendDocument($this->chat, $document, $args, $this->level);
    return $this;
  }
  // sendFile + caption + reply_markup
  public function filemsgbtn($file, $caption, $markup, $args = []) {
    $args['caption']      = $caption;
    $args['reply_markup'] = $markup;
    $this->bot->sendFile($this->chat, $file, $args, $this->level);
    return $this;
  }
  // sendChatAction:upload_photo
  public function uploadingPhoto() {
    $this->bot->sendUploadingPhoto($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:upload_audio
  public function uploadingAudio() {
    $this->bot->sendUploadingAudio($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:uploadVideo
  public function uploadingVideo() {
    $this->bot->sendUploadingVideo($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:upload_document
  public function uploadingDocument() {
    $this->bot->sendUploadingDocument($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:upload_video_note
  public function uploadingVideoNote() {
    $this->bot->sendUploadingVideoNote($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:find_location
  public function findingLocation() {
    $this->bot->sendFindingLocation($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:record_audop
  public function recordingAudio() {
    $this->bot->sendRecordingAudio($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:record_video
  public function recordingVideo() {
    $this->bot->sendRecordingVideo($this->chat, $this->level);
    return $this;
  }
  // sendChatAction:record_video_note
  public function recordingVideoNote() {
    $this->bot->sendRecordingVideoNote($this->chat, $this->level);
    return $this;
  }
  // deleteMessage
  public function delmsg($id) {
    $this->bot->deleteMessage($this->chat, $id, $this->level);
    return $this;
  }
}

?>
