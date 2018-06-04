<?php

require "xn.php";
ContentType("application/json");
xnprint_start();

$bot = new TelegramBot("1234:XXXXX");
$bot->unfilterUpdates(["message"]);
$update = $bot->update()->message;
$bot->save = false;

$channel = "@XXXX";
$msg = $update->message_id;
$chat = $update->chat->id;
$text = $update->text;

if($text == "/start"){
  $bot->sendTyping($chat);
  $bot->sendMessage($chat,"Hello dear!\nWelcome.");
}else{
  $id = $bot->forwardMessage($channel,$chat,$msg);
  $id = $id->result->message_id;
  $bot->forwardMessage($chat,$channel,$id);
}
$bot->close();

?>
