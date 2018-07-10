<?php

namespace xn\Telegram\Setting\TelegramBot\saveMsgs;

use xn;

// save messages for easy use
// $bot->msgs
class TelegramBotSaveMsgs {
  private $msgs = [];

 /* get message
  * params : String name
  * use :
  
  $bot->msgs->get( String );
  */
  public function get(string $name) {
    return isset($this->msgs[$name])? $this->msgs[$name]: false;
  }
  /* add message
   * params : String name,
              Mixed message
   * use :
   
   $bot->msgs->add( String , Mixed );
   */
  public function add(string $name, $message) {
    $message = xn\string::toString($message);
    $this->msgs[$name] = $message;
    return $this;
  }
  /* delete message
   * params : String name
   * use :
   
   $bot->msgs->delete( String );
   */
  public function delete(string $name) {
    if(isset($this->msgs[$name]))
    unset($this->msgs[$name]);
    return $this;
  }
  /* delete all messages
   * use :
   
   $bot->msgs->reset();
   */
  public function reset() {
    $this->msgs = [];
    return $this;
  }
  /* exists message name
   * params : String name
   * use :
   
   $bot->msgs->exists( String );
   */
  public function exists(string $name) {
    return isset($this->msgs[$name]);
  }
}

?>
