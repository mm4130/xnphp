<?php
// xn\Telergam\TelegramCode static class

namespace xn\Telegram;

class TelegramCode {
  /* getFileType from file_id (not need token)
   * params : Strign file_id
   * return : String type
                     thumb
                     image
                     document
                     voice
                     video
                     audio
                     video_note
                     sticker
   * use :
   
   xn\Telegram\TelegramCode::getFileType( String );
   */
  static function getFileType($file) {
    $file = base64_decode(strtr($file, '-_', '+/'));
    $type = [
      0   => "thumb"     ,
      2   => "image"     ,
      5   => "document"  ,
      3   => "voice"     ,
      10  => "document"  ,
      4   => "video"     ,
      9   => "audio"     ,
      13  => "video_note",
      8   => "sticker"
    ];
    $file = ord($file[0]);
    return isset($type[$file])? $type[$file]: false;
  }
  /* get mime_type
   * params : String type,
              String document_mime_type
   * return : String mime_type
   * use :
   
   xn\Telegram\TelegramCode::getMimeType( String , String );
   */
  static function getMimeType($type, $mime_type = "text/plan") {
    return [
            "document"  => $mime_type,
            "audio"     => "audio/mp3",
            "video"     => "video/mp4",
            "vide_note" => "video/mp4",
            "voice"     => "audio/ogg",
            "photo"     => "image/jpeg",
            "sticker"   => "image/webp"
           ][$type];
  }
  /* get file_format
   * params : String type,
              String document_file_format
   * return : String file_format
   * use :
   
   xn\Telegram\TelegramCode::getFormat( String , String );
   */
  static function getFormat($type, $format = "txt") {
    return [
            "document"  => $format,
            "audio"     => "mp3",
            "video"     => "mp4",
            "vide_note" => "mp4",
            "voice"     => "ogg",
            "photo"     => "jpg",
            "sticker"   => "webp"
           ][$type];
  }
  /* getJoimChat id
   * params : String joinChatCode // http://t.me/joinchat/JOIN_CHAT_CODE
   * return : Integer ID ->
                    group : -ID
               supergroup : -100ID
                  channel : -100ID
   * use :
   
   xn\Telegram\TelegramCode::getJoinChat( String );
   */
  static function getJoinChat($code) {
    $code = base64_decode(strtr($code, '-_', '+/'));
    return base_convert(bin2hex(substr($code, 4, 4)), 16, 10);
  }
  /* fake token (random)
   * return : String token
   * use :
   
   xn\Telegram\TelegramCode::faketoken_random();
   */
  static function faketoken_random() {
    $tokens = xndata("faketoken/random");
    return $tokens[array_rand($tokens)];
  }
}

?>
