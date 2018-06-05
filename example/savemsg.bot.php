<?php

require "xn.php";

$bot = new TelegramBot("TOKEN");
$username = "BOT USERNAME";
$update = $bot->update();
$bot->save = true;

$datafile = "data.xnj";
if(!file_exists($datafile)){
  $xnj = new XNJsonFile($datafile)
  $xnj->set("code",1);
}else{
  $xnj = new XNJsonFile($datafile);
}

if(isset($update->message)){
  $update = $update->message;
  $text = $update->text;
  $chat = $update->chat->id;
  
  if($text == "/start"){
    $bot->sendTyping($chat);
    $bot->sendMessage($chat,"Hello dear!\nWelcom.");
  }elseif(strpos($text,"/start ") === 0){
    $start = substr($text,7);
    if(!$xnj->iskey("msg-$start"){
      $bot->sendTyping($chat);
      $bot->sendMessage($chat,"message not Found!");
    }else{
      $args = $xnj->value("msg-$start");
      switch($args['type']){
      case "file":
        $bot->sendFile($chat,$args['file']);
      break;
      case "text":
        $bot->sendMessage($chat,$args['text']);
      break;
      case "caption":
        $bot->sendFile($chat,$args['file'],[
          "caption"=>$args["caption"]
        ]);
      break;
      case "contact":
        $bot->request("sendContact",$args['data']);
      break;
      case "location":
        $bot->request("sendLocation",$args['data']);
      break;
      case "venue":
        $bot->request("sendVenue",$args['data']);
      break;
      }
    }
  }else{
    $args = [];
    if($bot->isFile()){
      if(isset($update->caption)){
        $args['type'] = 'caption';
        $args['caption'] = $update->caption;
      }else $args['type'] = 'file';
      $args['file'] = $bot->fileInfo()->file_id;
    }elseif($text){
      $args['type'] = "text";
      $args['text'] = $text;
    }elseif(isset($update->contact)){
      $args['type'] = "contact";
      $args['data'] = $update->contact;
    }elseif(isset($update->location)){
      $args['type'] = "location";
      $args['data'] = $update->contact;
    }elseif(isset($update->venue)){
      $args['type'] = "venue";
      $args['data'] = $update->venue;
    }
    $code = $xnj->value("code");
    $xnj->math->add("code",rand(1,99));
    $code = baseconvert("$code","0123456789","qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIO_PASDFGHJKLZXCVBNM.-");
    $xnj->set("msg-$code",$args);
    $bot->sendTyping($chat);
    $bot->sendMessage($chat,"your message link:
    http://telegram.me/$username?start=$code");
  }
}
$bot->close();
$datafile->close();

?>
