<?php

namespace xn\Telegram\Setting\TelegramBot\saveMsgs;

class TelegramBotSaveMsgs {
private $msgs=[];
public function get(string $name){
return isset($this->msgs[$name])?$this->msgs[$name]:false;
}public function add(string $name,$message){
$message = XNString::toString($message);
$this->msgs[$name]=$message;
return $this;
}public function delete(string $name){
if(isset($this->msgs[$name]))
unset($this->msgs[$name]);
return $this;
}public function reset(){
$this->msgs=[];
return $this;
}public function exists(string $name){
return isset($this->msgs[$name]);
}

?>
