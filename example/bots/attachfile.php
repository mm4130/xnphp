<?php

require "xn.php";

$bot = new TelegramBot("TOKEN");

$update = $bot->update;
$bot->unfilterUpdates(["message"]);
$update = $update->message;

$bot->save = false;
$bot->variables = true;
$bot->autoaction = true;

$text = @$update->text;
$chat = $update->chat;

$data = xndata::file("attachfile.txt");

if($text == "/start")
    $bot->sendMessage($chat->id,"Hello %message.first_name%!\nWelcom to Attach robot.\nYou can send File and go to Attaching Message");
elseif($data->iskey($chat->id) && $text){
    $bot->sendMessage($chat->id,"[\x0c](http://t.me/tebrobot/".$data->value($chat->id).$text,["mode"=>"MarkDown"]);
    $data->delete($chat->id);
}
else{
    $bot->sendMessage($chat->id,"OK!\nnow send to me a text message for attaching on that");
    if($text)
        $data->set($chat->id,TelegramUploader::attach($text,"text"));
    $data->set($chat->id,TelegramUploader::attach($bot->getFileInfo()->file_id));
}

?>
