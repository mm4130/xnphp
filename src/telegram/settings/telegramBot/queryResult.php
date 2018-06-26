<?php

namespace xn\Telegram\Settings\TelegramBot;

// $bot->queryResult
class queryResult {
  /* get result array
   * use :
   
   $bot->queryResult->get;
   */
  public $get = [];
  
  /* add a result
   * params : String type, // result type. for example "article"
              Double query_id,
              String title,
              Array input,
              Array args // optional args
   * use :
   
   $bot->queryResult->add( String , Double , String , Array , Array );
   */
  public function add($type, $id, $title, $input, $args = []) {
    $args["type"]                  = $type;
    $args["id"]                    = $id;
    $args["title"]                 = $title;
    $args["input_message_content"] = $input;
    $this->get[]                   = $args;
    return $this;
  }
  /* create message input
   * params : String text,
              String parse_mode = none,
              Boolean preview = false
   * use :
   
   $bot->queryResult->inputMessage( String , String , Boolean );
   */
  public function inputMessage($text, $parse = false, $preview = false) {
    $args = ["message_text" => $text];
    if($parse)   $args["parse_mode"]               = $parse;
    if($preview) $args["disable_web_page_preview"] = $preview;
    return $args;
  }
  /* create location input
   * params : Integer latitude,
              Integer longitude,
              Integer live_period
   * use :
   
   $bot->queryResult->inputLocation( Integer , Integer , Integer );
   */
  public function inputLocation($latitude, $longitude, $live = false) {
    $args = ["latitude" => $latitude, "longitude" => $longitude];
    if($live) $args['live_period'] = $live;
    return $args;
  }
  /* create venue input
   * params : Integer latitude,
              Integer longitude,
              String title,
              String address,
              Integer id = false
   * use :
   
   $bot->queryResult->inputVenue( Integer , Integer , String , Integer );
   */
  public function inputVenue($latitude, $longitude, $title, $address, $id = false) {
    $args = ["latitude" => $latitude, "longitude" => $longitude, "title" => $title, "address" => $address];
    if($id) $args["foursquare_id"] = $id;
    return $args;
  }
   /* get result array and reset
   * use :
   
   $bot->queryResult->get();
   */
  public function get(){
    $get=$this->get;
    $this->get=[];
    return $get;
  }
  /* reset query result
   * use :
   
   $bot->queryResult->reset();
   */
  public function reset(){
    $this->get=[];
  }
}

?>
