<?php

namespace xn\Telegram\Settings\TelegramBot;

// $bot->keyboard
class keyboard {
  private $btn    = [],
          $button = [];
  public $resize    = false,
         $onetime   = false,
         $selective = false;
  
  /* set keyboard resize
   * params : Boolean|NULL size
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->size( Boolean|NULL );
   */
  public function size($size = null) {
    if($size === null) $size =! $this->resize;
    $this->resize = $size == true;
    return $this;
  }
  /* set keyboard onetime
   * params : Boolean|NULL onetime
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->onetime( Boolean|NULL );
   */
  public function onetime($onetime = null) {
    if($onetime===null) $onetime = ! $this->onetime;
    $this->onetime = $onetime == true;
    return $this;
  }
  /* set keyborad selective
   * params : Boolean|NULL selective
                      true  : on
                      false : off
                      null  : toggle
   * use :
   
   $bot->keyboard->selective( Boolean|NULL );
   */
  public function selective($selective = null) {
    if($selective === null) $selective = ! $this->selective;
    $this->selective = $selective == true;
    return $this;
  }
  /* add a button on keyboard
   * params : String name,
              String type = normal,
   * types :
     contact  -> button for request give use contact
     location -> button for request give use location
   * use :
   
   $bot->keyboard->add( String , String );
   */
  public function add($name, $type = '') {
    $btn = ["text" => $name];
    if    ($type == "contact" ) $btn["request_contact"]  = true;
    elseif($type == "location") $btn["request_location"] = true;
    $this->btn[] = $btn;
    return $this;
  }
  /* go to next line in keyboard
   * use :
   
   $bot->keyboard->line();
   */
  public function line() {
    $this->button[] = $this->btn;
    $this->btn=[];
    return $this;
  }
  /* end and return keyboard
   * params : Boolean json
   * use :
   
   $bot->keyboard->get( Boolean );
   */
  public function get($json = false) {
    $this->button[] = $this->btn;
    $btn = ["keyboard" => $this->button];
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
   
   $bot->keyboard->reset();
   */
  public function reset() {
    $this->button = [];
    $this->btn    = [];
    $this->size   = false;
    return $this;
  }
}

?>
