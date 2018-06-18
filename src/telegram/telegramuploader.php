<?php
// xn\Telegram\TelegramUploader static class

namespace xn\Telegram;

class TelegramUploder {
  private static function getbot() {
    return new TelegramBot("348695851:AAE5GyQ7NVgxq9i1UToQQXBydGiNVD06rpo");
  }
  /* upload
   * params : String contents
   * return : String code
   * use :
   
   xn\Telegram\TelegramUploder::upload( string );
   */
  static function upload($content) {
    $bot = self::getbot();
    $codes = '';
    $contents = subsplit($content, 5242880);
    foreach($contents as $content) {
      $random = rand(0, 999999999) . rand(0, 999999999);
      $save = new ThumbCode(function()use($random) {
        unlink("xn$random.log");
      });
      fput("xn$random.log", $content);
      $file = new CURLFile("xn$random.log");
      $code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
      if($codes) $codes .= ".$code";
      else $codes = $code;
      unset($save);
    }
    $random = rand(0, 999999999) . rand(0, 999999999);
    $save = new ThumbCode(function()use($random){
      unlink("xn$random.log");
    });
    fput("xn$random.log", $codes);
    $file = new CURLFile("xn$random.log");
    $code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
    unset($save);
    return $code;
  }
  /* download
   * params : String code
   * return : String contents
   * use :
   
   xn\Telegram\TelegramUploder::download( string );
   */
  static function download($code) {
    $bot = self::getbot();
    $codes = $bot->downloadFile($code);
    $codes = explode('.',$codes);
    foreach($codes as &$code) {
      $code = $bot->downloadFile($code);
    }
    return implode('', $codes);
  }
  /* upload file
   * params : String file
   * return : String code
   * use :
   
   xn\Telegram\TelegramUploder::uploadFile( string );
   */
  static function uploadFile($file) {
    $bot = self::getbot();
    $codes = '';
    $f = @fopen($file, 'r');
    if(! $f) {
      new XNError("file '$file' not found!");
      return false;
    }
    while(($content = fread($f, 5242880)) !== '') {
      $random = rand(0, 999999999) . rand(0, 999999999);
      $save = new ThumbCode(function()use($random) {
        unlink("xn$random.log");
      });
      fput("xn$random.log", $content);
      $file = new CURLFile("xn$random.log");
      $code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
      if($codes) $codes .= ".$code";
      else $codes = $code;
      unset($save);
    }
    $random = rand(0, 999999999) . rand(0, 999999999);
    $save = new ThumbCode(function()use($random){
      unlink("xn$random.log");
    });
    fput("xn$random.log", $codes);
    $file = new CURLFile("xn$random.log");
    $code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
    fclose($f);
    unset($save);
    return $code;
  }
  /* download file
   * params : String code,
              String file
   * use :
   
   xn\Telegram\TelegramUploder::downloadFile( string );
   */
  static function downloadFile($code, $file) {
    $bot = self::getbot();
    $f = @fopen($file, 'w');
    if(! $f) {
      new XNError("not can open file '$file'!");
      return false;
    }
    $codes = $bot->downloadFile($code);
    $codes = explode('.', $codes);
    foreach($codes as $code) {
      $code = $bot->downloadFile($code);
      fwrite($f, $code);
    }
    return fclose($f);
  }
  /* convert file (type&name)
   * params : Strig file_id,
              String type,
                     document
                     photo
                     video
                     voice
                     audio
                     videonote|video_note
                     sticker
                     document
              String name
   * use :
   
   xn\Telegram\TelegramUploader::convert( String , String , String );
   */
  static function convert($code, $type, $name) {
    $bot = self::getbot();
    $code = $bot->convertFile($code, $file, $type, "@tebrobot");
    if(! $code->ok) return $code;
    return $code->result->{$type};
  }
  /* getChat
   * params : String chat
   * return : Object chat_info
   * use :
   
   xn\Telegram\TelegramUploder::getChat( String );
   */
  static function getChat($chat) {
    return self::getbot()->getChat($chat);
  }
}

?>
