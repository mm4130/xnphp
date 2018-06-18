<?php
// xn\Telegram\TelegramLink static class

namespace xn\Telegram;

class TelegramLink {
  /* getMessage
   * params : String channel_username,
              Integer message_id
   * return : Object message_info
   * use :
   
   xn\Telegram\TelegramLink::getMessage( String , Integer );
   */
  static function getMessage($chat, $message) {
    if(@$chat[0] == '@') $chat=substr($chat, 1);
    try {
      $g = file_get_contents("https://t.me/$chat/$message?embed=1");
      $x = new DOMDocument;
      @$x->loadHTML($g);
      $x        = @new DOMXPath($x);
      $path     = "//div[@class='tgme_widget_message_bubble']";
      $enti     = $x->query("$path//div[@class='tgme_widget_message_text']")[0];
      $entities = [];
      $last     = 0;
      $pos      = false;
      $line     = 0;
      $textlen  = strlen($enti->nodeValue);
      $entit    = new DOMDocument;
      $entit->appendChild($entit->importNode($enti, true));
      $text = trim(html_entity_decode(strip_tags(str_replace('<br/>', "\n",$entit->saveXML()))));
      foreach((new DOMXPath($entit))->query("//code|i|b|a") as $num => $el) {
        $len  = strlen($el->nodeValue);
        $pos  = strpos(substr($enti->nodeValue, $last, $textlen), $el->nodeValue) + $last;
        $last = $pos + $len;
        $entities[$num] = [
          "offset" => $pos,
          "length" => $len
        ];
        if($el->tagName == 'a')
          $entities[$num]['url'] = $el->getAttribute("href");
        elseif($el->tagName == 'b')    $entities[$num]['type'] = 'bold';
        elseif($el->tagName == 'i')    $entities[$num]['type'] = 'italic';
        elseif($el->tagName == 'code') $entities[$num]['type'] = 'code';
        elseif($el->tagName == 'a')    $entities[$num]['type'] = 'link';
      }
      if($entities == []) $entities = false;
      $date  = strtotime($x->query("$path//a[@class='tgme_widget_message_date']")[0]->getElementsByTagName('time')[0]->getAttribute("datetime"));
      $views = $x->query("$path//span[@class='tgme_widget_message_views']");
      if(isset($views[0])) $views = $views[0]->nodeValue;
      else $views = false;
      $author = $x->query("$path//span[@class='tgme_widget_message_from_author']");
      if(isset($author[0])) $author = $author[0]->nodeValue;
      else $author = false;
      $via = $x->query("$path//a[@class='tgme_widget_message_via_bot']");
      if(isset($via[0])) $via = substr($via[0]->nodeValue, 1);
      else $via = false;
      $forward = $x->query("$path//a[@class='tgme_widget_message_forwarded_from_name']")[0];
      if($forward) {
        $forwardname = $forward->nodeValue;
        $forwarduser = $forward->getAttribute("href");
        $forwarduser = end(explode('/',$forwarduser));
        $forward     = $forwardname? [
          "title"    => $forwardname,
          "username" => $forwarduser
        ]: false;
      }
      else $forward  = false;
      $replyid = $x->query("$path//a[@class='tgme_widget_message_reply']");
      if(isset($replyid[0])) {
        $replyid    = $replyid[0]->getAttribute("href");
        $replyid    = explode('/', $replyid);
        $replyid    = end($replyid);
        $replyname  = $x->query("$path//a[@class='tgme_widget_message_reply']//span[@class='tgme_widget_message_author_name']")[0]->nodeValue;
        $replytext  = $x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_text']")[0]->nodeValue;
        $replymeta  = $x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_metatext']")[0]->nodeValue;
        $replyparse = explode(' ', $replymeta);
        $replythumb = $x->query("$path//a[@class='tgme_widget_message_reply']//i[@class='tgme_widget_message_reply_thumb']")[0];
        if($replythumb)$replythumb = $replythumb->getAttribute('style');
        else $replythumb = false;
        preg_match('/url\(\'(.{1,})\'\)/', $replythumb, $pr);
        $replythumb = $pr[1];
        $reply      = [
          "message_id" => $replyid,
          "title"      => $replyname
        ];
        if($replytext) $reply['text'] = $replytext;
        elseif($replyparse[0] == 'Service' || $replyparse[0] == 'Channel') $reply['service_message'] = true;
        elseif($replyparse[1] == 'Sticker') {
          $reply['emoji']   = $replyparse[0];
          $reply['sticker'] = $replythumb;
        }
        elseif($replyparse[0] == 'Photo') $reply['photo']    = $replythumb;
        elseif($replyparse[0] == 'Voice') $reply['voice']    = true;
        elseif($replythumb)               $reply['document'] = $replythumb;
      }
      else $reply = false;
      $service = $x->query("$path//div[@class='message_media_not_supported_label']");
      if(isset($service[0])) $service = $service[0]->nodeValue == 'Service message';
      else $service = false;
      $photo = $x->query("$path//a[@class='tgme_widget_message_photo_wrap']")[0];
      if($photo) {
        $photo = $photo->getAttribute('style');
        preg_match('/url\(\'(.{1,})\'\)/', $photo, $pr);
        $photo = ["photo" => $pr[1]];
      }
      else $photo = false;
      $voice = $x->query("$path//audio[@class='tgme_widget_message_voice']");
      if(isset($voice[0])) {
        $voice         = $voice[0]->getAttribute("src");
        $voiceduration = $x->query("$path//time[@class='tgme_widget_message_voice_duration']")[0]->nodeValue;
        $voiceex       = explode(':',$voiceduration);
        if(count($voiceex) == 3) $voiceduration = $voiceex[0] * 3600 + $voiceex[1] * 60 + $voiceex[2];
        else $voiceduration = $voiceex[0] * 60 + $voiceex[1];
        $voice = [
          "voice"    => $voice,
          "duration" => $voiceduration
        ];
      }
      else $voice = false;
      $sticker = $x->query("$path//div[@class='tgme_widget_message_sticker_wrap']");
      if(isset($sticker[0])) {
        $stickername = $sticker[0]->getElementsByTagName("a")[0];
        $sticker     = $stickername->getElementsByTagName('i')[0]->getAttribute("style");
        preg_match('/url\(\'(.{1,})\'\)/', $sticker, $pr);
        $sticker     = $pr[1];
        $stickername = $stickername->getAttribute("href");
        $stickername = explode('/', $stickername);
        $stickername = end($stickername);
        $sticker     = [
          "sticker"  => $sticker,
          "setname"  => $stickername
        ];
      }
      else $sticker  = false;
      $document       = $x->query("$path//div[@class='tgme_widget_message_document_title']");
      if(isset($document[0])) {
        $document     = $document[0]->nodeValue;
        $documentsize = $x->query("$path//div[@class='tgme_widget_message_document_extra']")[0]->nodeValue;
        $document     = [
          "title"     => $document,
          "size"      => $documentsize
        ];
      }
      else $document  = false;
      $video           = $x->query("$path//a[@class='tgme_widget_message_video_player']");
      if(isset($video[0])) {
        $video         = $video[0]->getElementsByTagName("i")[0]->getAttribute("style");
        preg_match('/url\(\'(.{1,})\'\)/', $video, $pr);
        $video         = $pr[1];
        $videoduration = $vide->getElementsByTagName("time")[0]->nodeValue;
        $videoex       = explode(':',$videoduration);
        if(count($videoex) == 3) $videoduration = $videoex[0] * 3600 + $videoex[1] * 60 + $videoex[2];
        else $videoduration = $videoex[0] * 60 + $videoex[1];
        $video         = [
          "video"      => $video,
          "duration"   => $videoduration
        ];
      }
      else $video      = false;
      if($text && ($document || $sticker || $photo || $voice || $video)) {
        $caption = $text;
        $text    = false;
      }
      $r = [
        "username"   => $chat,
        "message_id" => $message
      ];
      if($author) $r['author'] = $author;
      if($text)   $r['text']   = $text;
      if(isset($caption) && $caption) $r['caption'] = $caption;
      if($views)    $r['views']           = $views;
      if($date)     $r['date']            = $date;
      if($photo)    $r['photo']           = $photo;
      if($voice)    $r['voice']           = $photo;
      if($video)    $r['video']           = $video;
      if($sticker)  $r['sticker']         = $sticker;
      if($document) $r['document']        = $document;
      if($forward)  $r['forward']         = $forward;
      if($reply)    $r['reply']           = $reply;
      if($entities) $r['entities']        = $entities;
      if($service)  $r['service_message'] = true;
      return (object)$r;
    }catch(Error $e) {
      return false;
    }
  }
  /* getChat
   * params : Strign channel_username
   * return : Object info
   
   xn\Telegram\TelegramLink::getChat( String );
   */
  static function getChat($chat) {
    if(@$chat[0] == '@') $chat = substr($chat, 1);
    $g = file_get_contents("https://t.me/$chat");
    $g = str_replace('<br/>', "\n", $g);
    $x = new DOMDocument;
    $x->loadHTML($g);
    $x = new DOMXPath($x);
    $path = "//div[@class='tgme_page_wrap']";
    $photo = $x->query("$path//img[@class='tgme_page_photo_image']");
    if(isset($photo[0]))
      $photo = $photo[0]->getAttribute("src");
    else $photo = false;
    $title = $x->query("$path//div[@class='tgme_page_title']");
    if(! isset($title[0])) return false;
    $title=trim($title[0]->nodeValue);
    $description=$x->query("$path//div[@class='tgme_page_description']")[0]->nodeValue;
    $members = explode(' ', $x->query("$path//div[@class='tgme_page_extra']")[0]->nodeValue)[0];
    $r = ["title" => $title];
    if($photo)         $r['photo']       = $photo;
    if($description)   $r['description'] = $description;
    if($members > 0)   $r['members']     = $members * 1;
    return (object)$r;
  }
  static function getJoinChat($code) {
    return self::getChat("joinchat/$code");
  }
  /* getStiker
   * params : String set_name
   * return : Object info
   * use :
   
   xn\Telegram\TelegramLink::getSticker( String );
   */
  static function getSticker($name) {
    $g = file_get_contents("https://t.me/addstickers/$name");
    $x = new DOMDocument;
    $x->loadHTML($g);
    $x = new DOMXPath($x);
    $title = $x->query("//div[@class='tgme_page_description']");
    if(!isset($title[0])) return false;
    $title = $title[0]->getElementsByTagName("strong")[1]->nodeValue;
    return (object)[
      "setname" => $name,
      "title"   => $title
    ];
  }
  /* get channelCreatedDate
   * params : String channel_username
   * return : Double time
   * use :
   
   xn\Telegram\TelegramLink::channelCreateDate( String );
   */
  public function channelCreatedDate($channel) {
    return self::getMessage($channel, 1)["date"];
  }
}

?>
