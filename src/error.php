<?php

/* XNError for show errors
 * params : String from, (from method/function/....)
            String message,
            Integer error_level = 0,
            Boolean end_code = false
 * errors level :
   0 : Warning
   1 : Notic
   2 : Lag
   3 : Status
   4 : User Error
   5 : User Warning
   6 : User Notic
   7 : Recoverable Error
   8 : Syntax Error
   9 : Unexpected
   10 : Undefined
   11 : Anonimouse
   12 : System Error
   13 : Secury Error
   14 : Fatal Error
   15 : Arithmetic Error
   16 : Parse Error
   17 : Type Error
 * use : 
   
   new XNError( String , String , Integer , Boolean );
 ! you can use throwing :
   
   throw new XNError( String , String , Integer , Boolean );
  ! xn always use this Class for display errors
 */
class XNError extends Error {
  // public $HTMLMessage
  // public $consoleMessage
  protected $message;
  
  /* setting show errors (static method)
   * params : Boolean|NULL|String show
                           true : show xnerrors in output
                           false : not show xnerrors in output
                           null : (Assumption) toggle show xnerrors in output
                           String file : show errors and save in file
   * use :
   
   XNError::show( Boolean|NULL|String );
   */
  static function show($sh = null) {
    if($sh===null)$GLOBALS['-XN-']['errorShow'] = ! $GLOBALS['-XN-']['errorShow'];
    else $GLOBALS['-XN-']['errorShow'] = $sh;
  }
  /* errors handler (static method)
   * params : Object(Closure) handle
   * closure input : Object(XNError) error
   * use :
   
   XNError::handler( function( Object(XNError) ){
     // codes
   }
   */
  static function handler($func) {
    $GLOBALS['-XN-']['errorHandler'] = $func;
  }
  public function __construct($in, $text, $level = 0, $en = false) {
    $type    = [
                  "Warning",
                  "Notic",
                  "Lag",
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
    if(isset($GLOBALS['-XN-']['errorHandler'])) {
      try{
        if(is_function($GLOBALS['-XN-']['errorHandler']))
          $GLOBALS['-XN-']['errorHandler'] ($this);
      }catch(Error $e) { }
      catch(Expection $e) { }
      catch(XNError $e) { }
    }
    if($GLOBALS['-XN-']['errorShow'])echo $message;
    if($GLOBALS['-XN-']['errorShow']&&is_string($GLOBALS['-XN-']['errorShow']))fadd($GLOBALS['-XN-']['errorShow'],$console);
    if($en)exit;
  }
  // give Error message
  public function __toString() {
    return $this->message;
  }
}

?>
