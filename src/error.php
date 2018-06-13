<?php

class XNError extends Error {
  protected $message;
  
  static function show($sh = null) {
    if($sh===null)$GLOBALS['-XN-']['errorShow'] = ! $GLOBALS['-XN-']['errorShow'];
    else $GLOBALS['-XN-']['errorShow'] = $sh;
  }
  static function handlr($func) {
    $GLOBALS['-XN-']['errorHandlr'] = $func;
  }
  public function __construct($in, $text, $level = 0, $en = false) {
    $type    = [
                  "Warning",
                  "Notic",
                  "Log",
                  "Status",
                  "User Error",
                  "User Warning",
                  "User Notic",
                  "Recoverable Error",
                  "Syntax Error",
                  "Unexpected",
                  "Undefined",
                  "Anonimouse",
                  "System Error",
                  "Secury Error",
                  "Fatal Error",
                  "Arithmetic Error",
                  "Parse Error",
                  "Type Error"
               ][$level];
    $debug   = debug_backtrace();
    $th      = end($debug);
    $date    = date("ca");
    $console = "[$date]XN $type > $in : $text in {$th['file']} on line {$th['line']}\n";
    $message = "<br>\n[$date]<b>XN $type</b> &gt; <i>$in</i> : " .
               str_replace("\n","<br>",$text) .
               " in <b>{$th['file']}</b> on line <b>{$th['line']}</b>\n<br>";
    $this->HTMLMessage    = $message;
    $this->consoleMessage = $console;
    $this->message        = "XN $type > $in : $text";
    if(isset($GLOBALS['-XN-']['errorHandlr'])) {
      try{
        $GLOBALS['-XN-']['errorHandlr'] ($this);
      }catch(Error $e) { }
      catch(Expection $e) { }
      catch(XNError $e) { }
    }
    if($GLOBALS['-XN-']['errorShow'])echo $message;
    if($GLOBALS['-XN-']['errorShow']&&is_string($GLOBALS['-XN-']['errorShow']))fadd($GLOBALS['-XN-']['errorShow'],$console);
    if($en)exit;
  }
  public function __toString() {
    return $this->message;
  }
}

?>
