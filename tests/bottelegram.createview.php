<?php
require "xn.php";
// this working by Webhook

$token = "TOKEN";
$channel = "CHANNEL";

$bot = new TelegramBot($token);
$update = $bot->update();
$type = $bot->updateType();

if($type == "message"){
  
}

$bot->close();

?>
