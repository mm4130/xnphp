<?php
/* class : ThumbCode
 * function : thumbCode
 * params : Closure $func()
 * return : Object
 * use : Save the output in memory. When this part of the memory is destroyed, your code will run.
   
   $var = xn\thumbCode(function(){
     //codes...
   });
*/

namespace xn;

class ThumbCode {
  private $code = false;
  public function __construct($func) {
    $this->code = $func;
  }
  public function __destruct() {
    if($this->code) ($this->code)();
  }
  public function close(){
    $this->code = false;
  }
  public function clone() {
    return new ThumbCode($this->code);
  }
  }
function thumbCode($func) {
  return new ThumbCode($func);
}
?>
