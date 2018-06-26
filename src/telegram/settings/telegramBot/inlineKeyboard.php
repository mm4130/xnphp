<?php

namespace xn\Telegram\Settings\TelegramBot;

// $bot->inlineKeyboard
class inlineKeyboard {
  private $btn    = [],
          $button = [];
  public $resize    = false,
         $onetime   = false,
         $selective = false;
  
  /* set keyboard resize
   ! this method can't use in inline_keyboard
   * params : Boolean|NULL size
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->size( Boolean|NULL );
   */
  public function size($size = null) {
    if($size === null) $size = ! $this->resize;
    $this->resize = $size == true;
    return $this;
  }
  /* set keyboard onetime
   * params : Boolean|NULL onetime
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->onetime( Boolean|NULL );
   */
  public function onetime($onetime = null) {
    if($onetime === null) $onetime = ! $this->onetime;
    $this->onetime = $onetime == true;
    return $this;
  }
  /* set keyborad selective
   * params : Boolean|NULL selective
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->inlineKeyboard->selective( Boolean|NULL );
   */
  public function selective($selective = null) {
    if($selective === null) $selective = ! $this->selective;
    $this->selective = $selective == true;
    return $this;
  }
  /* add a button on keyboard
   * params : String name,
              String type = normal,
              String data = '' // data for callback_data
   * types :
     pay : payment button
     game : game callback
     switch : switch inline query
     switch_current_chat : switch inline query current chat
     callback|data : callback data
     link : linked button
   * use :
   
   $bot->inlineKeyboard->add( String , String , String );
   */
  public function add($name, $type, $data='') {
    $btn = ["text" => $name];
    if    ($type == "pay")                         $data = true;
    elseif($type == "game")                        $type = "callback_game";
    elseif($type == "switch")                      $type = "switch_inline_query";
    elseif($type == "switch_current_chat")         $type = "switch_inline_query_current_chat";
    elseif($type == "callback" || $type == "data") $type = "callback_data";
    elseif($type == "link")                        $type = "url";
    $btn[$type] = $data;
    $this->btn[]=$btn;
    return $this;
  }
  /* go to next line in keyboard
   * use :
   
   $bot->inlineKeyboard->line();
   */
  public function line() {
    $this->button[] = $this->btn;
    $this->btn = [];
    return $this;
  }
  /* end and return keyboard
   * params : Boolean json
   * use :
   
   $bot->inlineKeyboard->get( Boolean );
   */
  public function get($json = false) {
    $this->button[] = $this->btn;
    $btn = ["inline_keyboard" => $this->button];
    if($this->resize)    $btn['resize_keyboard']   = true;
    if($this->onetime)   $btn['one_time_keyboard'] = true;
    if($this->selective) $btn['selective']         = true;
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $json? json_encode($btn): $btn;
  }
  /* reset keyboard 
   * use :
   
   $bot->inlineKeyboard->reset();
   */
  public function reset() {
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $this;
  }
}

?>
