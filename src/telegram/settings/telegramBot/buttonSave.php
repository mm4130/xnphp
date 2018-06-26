<?php

namespace xn\Telegram\Settings\TelegramBot;

// save buttons list for easy use
// $bot->menu
class buttonSave {
  private $btns = [],
          $btn  = [];
  
 /* get a menu
  * params : String name,
             Boolean json = true
  * use :
  
  $bot->menu->get( String , Boolean );
  */
  public function get($name, $json = true) {
    if($json) return @$this->btn[$name];
    return @$this->btns[$name];
  }
  /* add a menu
   * params : String name,
              Array|Json keyboard
   * use :
   
   $bot->menu->add( String , Array|Json );
   */
  public function add($name, $btn) {
    if(is_array($btn)) $btns = json_encode($btn);
    elseif(!is_json($btn)) return false;
    else $btn = json_decode($btns = $btn);
    if(!isset($btns['inline_keyboard']) ||
       !isset($btns['keyboard'])        ||
       !isset($btns['force_reply'])     ||
       !isset($btns['remove_keyboard']))
       return false;
    $this->btns=$btns;
    $this->btn=$btn;
    return $this;
  }
  /* delete menu
   * params : String name
   * use :
   
   $bot->menu->delete( String );
   */
  public function delete($name) {
    if(isset($this->btn[$name])) {
      unset($this->btn [$name]);
      unset($this->btns[$name]);
    }
    return $this;
  }
  /* delete all menus
   * use :
   
   $bot->menu->reset();
   */
  public function reset() {
    $this->btn  = [];
    $this->btns = [];
  }
}

?>
