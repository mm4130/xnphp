<?php
// xn\Telergam\TelegramCode static class

namespace xn\Telegram;

class TelegramCode {
static function getFileType($file){
$file=base64_decode(strtr($file,'-_','+/'));
$type=[
0=>"thumb",
2=>"image",
5=>"document",
3=>"voice",
10=>"document",
4=>"video",
9=>"audio",
13=>"video_note",
8=>"sticker"
];$file=ord($file[0]);
return isset($type[$file])?$type[$file]:false;
}static function getMimeType($type,$mime_type="text/plan"){
return ["document"=>$mime_type,"audio"=>"audio/mp3","video"=>"video/mp4","vide_note"=>"video/mp4","voice"=>"audio/ogg","photo"=>"image/jpeg","sticker"=>"image/webp"][$type];
}static function getFormat($type,$format="txt"){
return ["document"=>$format,"audio"=>"mp3","video"=>"mp4","vide_note"=>"mp4","voice"=>"ogg","photo"=>"jpg","sticker"=>"webp"][$type];
}static function getJoinChat($code){
$code=base64_decode(strtr($code,'-_','+/'));
return base_convert(bin2hex(substr($code,4,4)),16,10);
}static function faketoken_random(){
$tokens=xndata("faketoken/random");
return $tokens[array_rand($tokens)];
}
}

?>
