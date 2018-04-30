<?php
require "xn.php";
// this working by Webhook

$token = "TOKEN";
$channel = "CHANNEL";

$bot = new TelegramBot($token);
$update = $bot->update();
$type = $bot->updateType();

if($type == "message"){
  $update = $update->message;
  if($update->text == "/start"){
    $bot->sendAction($update->chat->id);
    $bot->sendMessage($update->chat->id,"Hello {$update->from->first_name}!\nWelcom to robot.");
  }else{
    $bot->forwardMessage($update->chat->id,$channel,$update->message_id);
    $bot->forwardMessage($channel,$update->chat->id,$bot->final->result->message_id);
  }
}
$bot->close();

?>
