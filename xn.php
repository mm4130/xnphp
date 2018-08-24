<?php // xn php v1.7
if(PHP_VERSION < 7.0) {
	throw new Error("<b>xn library</b> needs more than or equal to 7.0 version");
	exit;
}
$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = microtime(true);
$GLOBALS['-XN-']['dirName'] = substr(__FILE__, 0, strrpos(__FILE__, DIRECTORY_SEPARATOR));
$GLOBALS['-XN-']['dirNameDir'] = $GLOBALS['-XN-']['dirName'] . DIRECTORY_SEPARATOR;
$GLOBALS['-XN-']['isf'] = file_exists($GLOBALS['-XN-']['dirNameDir'] . "xn.php");
$GLOBALS['-XN-']['savememory'] = [];
define("XNVERSION", "1.7");
class ThumbCode {
	private $code = false;
	public function __construct($func){
		$this->code = $func;
	}
	public function __destruct(){
		if($this->code)($this->code)();
	}
	public function close(){
		$this->code = false;
	}
	public function clone(){
		return new ThumbCode($this->code);
	}
}
function thumbCode($func){
	return new ThumbCode($func);
}
function set_last_update_nter(){
	if($GLOBALS['-XN-']['isf']) {
		$file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
		$f = file_get_contents($file);
		$p = strpos($f, "{[LASTUPDATE]}");
		while($p > 0 && $f[$p--] != '"');
		if($p <= 0)return false;
		$h = '';
		$p+= 2;
		while($f[$p] != '{')$h.= $f[$p++];
		if(!is_numeric($h))return false;
		$f = str_replace("$h{[LASTUPDATE]}", microtime(true). "{[LASTUPDATE]}", $f);
		return file_put_contents($file, $f);
	}
}
function set_last_use_nter(){
	if($GLOBALS['-XN-']['isf']) {
		$file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
		$f = file_get_contents($file);
		$p = strpos($f, "{[LASTUSE]}");
		while($p > 0 && $f[$p--] != '"');
		if($p <= 0)return false;
		$h = '';
		$p+= 2;
		while($f[$p] != '{')$h.= $f[$p++];
		if(!is_numeric($h))return false;
		$f = str_replace("$h{[LASTUSE]}", microtime(true). "{[LASTUSE]}", $f);
		return file_put_contents($file, $f);
	}
}
function set_data_nter(){
	if($GLOBALS['-XN-']['isf']) {
		$data = base64_encode(json_encode($GLOBALS['DATA']));
		$file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
		$f = file_get_contents($file);
		$p = strpos($f, "{[DA" . "TA]}");
		while($p > 0 && $f[$p--] != '"');
		if($p <= 0)return false;
		$h = '';
		$p+= 2;
		while($f[$p] != '{')$h.= $f[$p++];
		$f = str_replace("$h{[DA" . "TA]}", "$data{[D" . "ATA]}", $f);
		return file_put_contents($file, $f);
	}
}
function xnupdate(){
	if(!function_exists("zlib_decode"))$code = file_get_contents("http://lib.xntm.ir/php/code.php");
	else $code = zlib_decode(file_get_contents("http://lib.xntm.ir/php/zlibcode.php"));
	if(!$code)$code = file_get_contents("https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php");
	file_put_contents("xn.php", $code);
	set_last_update_nter();
}
if(@$XNUPDATE === 2 || (@$XNUPDATE === 1 && substr($GLOBALS['-XN-']['lastUpdate'], 0, -14)+ 10000 <= time()))xnupdate();
$GLOBALS['-XN-']['errorShow'] = true;
$GLOBALS['-XN-']['errorTypeShow'] = [
	true,true,false,true,true,true,true,true,false,true,true,true,true,true,true,true,true,false
];
class XNError extends Error {
	protected $message;
	public $HTMLMessage, $consoleMessage, $type, $from;

	const NONE = 0;
	const EXIT = 1;
	const THROW = 2;

	const TYPES = [
		0  => "Notic            ",
		1  => "Warning          ",
		2  => "Log              ",
		3  => "Status           ",
		4  => "Recoverable Error",
		5  => "Syntax Error     ",
		6  => "Unexpected       ",
		7  => "Undefined        ",
		8  => "Anonimouse       ",
		9  => "System Error     ",
		10 => "Secury Error     ",
		11 => "Fatal Error      ",
		12 => "Arithmetic Error ",
		13 => "Parse Error      ",
		14 => "Type Error       ",
		15 => "Network Error    ",
		16 => "                 "
	];

	const NOTIC = 0;
	const WARNING = 1;
	const LOG = 2;
	const STATUS = 3;
	const RECOVERABLE = 4;
	const SYNTAX = 5;
	const UNEXPECTED = 6;
	const UNDEFINED = 7;
	const ANONIMOUSE = 8;
	const SYSTEM = 9;
	const SECURY = 10;
	const FATAL = 11;
	const ARITHMETIC = 12;
	const PARSE = 13;
	const TYPE = 14;
	const NETWORK = 15;
	const TRIM = 16;

	public static function show($sh = null,$type = false){
		if($sh === null){
			if($type === false)
				$GLOBALS['-XN-']['errorShow'] = !$GLOBALS['-XN-']['errorShow'];
			else $GLOBALS['-XN-']['errorTypeShow'][$type] = !$GLOBALS['-XN-']['errorTypeShow'][$type];
		}else{
			if($type === false)
				$GLOBALS['-XN-']['errorShow'] = (bool)$sh;
			else $GLOBALS['-XN-']['errorTypeShow'][$type] = (bool)$sh;
		}
	}
	public static function handler($func){
		$GLOBALS['-XN-']['errorhandler'] = $func;
	}
	public function __construct(string $from, string $text, int $level = 0, int $type = null){
		if((!@$GLOBALS['-XN-']['errorTypeShow'][$level] && $level != 16) && $type === null)return;
		$level = @self::TYPES[$level];
		if($GLOBALS['-XN-']['errorTypeShow'][16])
			$level = rtrim($level, ' ');
		$this->from = $from;
		$debug = debug_backtrace();
		$debug = end($debug);
		$date = date("Y-n-j G:i:s");
		$console = "\n[$date]XN $level > $from: $text in {$debug['file']} on line {$debug['line']}\n";
		$message = "<br/>[$date]<b>XN $level</b> &gt; <i>$from</i>: " . nl2br($text). " in <b>{$debug['file']}</b> on line <b>{$debug['line']}</b><br/>";
		$this->HTMLMessage = $message;
		$this->consoleMessage = $console;
		$this->message = "XN $type > $from: $text";
		if(isset($GLOBALS['-XN-']['errorhandler']))
			if(is_callable($GLOBALS['-XN-']['errorHandler']))($GLOBALS['-XN-']['errorhandler'])($this);
		$errorsh = $GLOBALS['-XN-']['errorShow'];
		if($errorsh && !$type){
			$headers = get_response_headers();
			if(!isset($headers['Content-type']) || strpos($headers['Content-type'], 'text/html') !== false)
				print $message;
			else
				print $console;
		}
		if($errorsh && is_string($errorsh) && (file_exists($errorsh) || touch($errorsh)))
			fadd($errorsh, $console);
		if($type !== null)
			switch($type){
				case self::NONE:
				break;
				case self::EXIT:
				exit;
				case self::THROW:
				throw $this;
			}
	}
	public function __toString(){
		return $this->message;
	}
}

// -----------------------------------------------------

function var_read(...$var){
	ob_start();
	var_dump(...$var);
	$r = ob_get_contents();
	ob_end_clean();
	return $r;
}
function swap(&$var1, &$var2){
	$var3 = $var1;
	$var1 = $var2;
	$var2 = $var3;
}
function varadd($to,...$args){
	$t = gettype($to);
	switch ($t) {
	case "NULL":
		return null;
		break;
	case "boolean":
		foreach($args as $arg) {
			if($arg)return true;
		}
		return false;
		break;
	case "integer":
	case "float":
	case "double":
		foreach($args as $arg) {
			$to+= $arg;
		}
		return $to;
		break;
	case "string":
		foreach($args as $arg) {
			$to.= $arg;
		}
		return $to;
		break;
	case "array":
		foreach($args as $arg) {
			$to = array_merge($to, $arg);
		}
		return $to;
		break;
	case "object":
		if(get_class($to) == "stdClass") {
			$to = (array)$to;
			foreach($args as $arg) {
				$to = array_merge($to, (array)$arg);
			}
			return (object)$to;
		}
		break;
	}
	throw new XNError("var_add", "unsuported type $t", XNError::TYPE);
}
function xneval($code, &$save = 5636347437634){
	$p = strpos($code, "<?");
	if($p === false || $p == - 1)$code = "<?php " . $code;
	$random = rand(0, 99999999). rand(0, 99999999);
	fput("xn$random.log", $code);
	$z = new thumbCode(
	function()use($random){
		unlink("xn$random.log");
	});
	if($save === 5636347437634) {
		$r = @require "xn$random.log";
	}
	else {
		ob_start();
		$r = @require "xn$random.log";
		if($save === true)$r = ob_get_contents();
		else $save = ob_get_contents();
		ob_end_clean();
	}
	return $r;
}
function theline(){
	$t = debug_backtrace();
	$t = end($t);
	return $t['line'];
}
function thefile(){
	$t = debug_backtrace();
	return end($t)['file'];
}
function thedir(){
	$t = debug_backtrace();
	return dirname(end($t)['file']);
}
function thefunction(){
	$t = debug_backtrace();
	if(!isset($t[1]))
		return false;
	return end($t)['function'];
}
function evale($codeiuefhuisegbfyusegfrusbgtys){
	extract($GLOBALS);
	eval($codeiuefhuisegbfyusegfrusbgtys);
	exit;
}
function evall($codeiuefhuisegbfyusegfrusbgtys){
	extract($GLOBALS);
	foreach(explode(";",$codeiuefhuisegbfyusegfrusbgtys) as $codeiuefhuisegbfyusegfrusbgtys)
		eval($codeiuefhuisegbfyusegfrusbgtys.';');
	exit;
}
function evalc($code){
	return eval('return ' . $code . ';');
}
function evald($code){
	return eval($code);
}
function evaln($namespace, $code){
	return xneval("<?php\nnamespace $namespace;\n$code");
}
function is_function($f){
	return function_exists($f) || $f instanceof Closure || $f instanceof XNClosure;
}
function is_closure($f){
	return $f instanceof Closure || $f instanceof XNClosure;
}
function is_stdclass($f){
	return $f instanceof stdClass;
}
function is_json($json){
	$obj = @json_decode($json);
	return $obj !== false && is_string($json) && (is_object($obj) || is_array($obj));
}
function is_xndata($xndata){
	return $xndata instanceof XNDataString || $xndata instanceof XNDataFile || $xndata instanceof XNDataURL || $xndata instanceof XNData;
}
function random($str, $leng = 1){
	if(is_string($str))$str = str_split($str);
	$r = '';
	$c = count($str)- 1;
	while($leng > 0) {
		$r = $r . $str[rand(0, $c)];
		--$leng;
	}
	return $r;
}
function xnsplit($str, $count = 1, $space = 1){
	$arr = [];
	$length = strlen($str);
	$str = str_split($str);
	$loc = 0;
	while($loc < $length) {
		$c = 0;
		$r = '';
		while($c < $count) {
			$r = $r . $str[$loc + $c];
			++$c;
		}
		$arr[] = $r;
		$loc+= $space;
	}
	return $arr;
}
function equal($a, $b, $c = '==', $d = 0){
	$ia = is_array($a) || is_object($a);
	$ib = is_array($b) || is_object($a);
	if($ia)$a = (array)$a;
	if($ib)$b = (array)$b;
	$z1 = true;
	$z2 = true;
	if($c[0] == '-' || $c[0] == '+' || $c[0] == '*' || $c[0] == '/' || $c[0] == '~') {
		if($ia) {
			if($c[0] == '-')
			foreach($a as &$x) {
				$t = gettype($x);
				--$x;
				settype($x, $t);
			}
			elseif($c[0] == '+')
			foreach($a as &$x) {
				$t = gettype($x);
				++$x;
				settype($x, $t);
			}
			elseif($c[0] == '*')
			foreach($a as &$x) {
				$t = gettype($x);
				$x*= $x;
				settype($x, $t);
			}
			elseif($c[0] == '/')
			foreach($a as &$x) {
				$t = gettype($x);
				$x = 1 / $x;
				settype($x, $t);
			}
			elseif($c[0] == '~')
			foreach($a as &$x) {
				$t = gettype($x);
				$x = ~ $x;
				settype($x, $t);
			}
		}
		else {
			$t = gettype($a);
			if($c[0] == '-')--$a;
			if($c[0] == '+')++$a;
			if($c[0] == '*')$a*= $a;
			if($c[0] == '/')$a = 1 / $a;
			if($c[0] == '~')$a = ~ $a;
			settype($a, $t);
		}
		$c = substr($c, 1);
	}
	$l = strlen($c)- 1;
	if(!isset($c[$l]));
	elseif($c[$l] == '+') {
		$z2 = true;
		$c = substr($c, 0, -1);
	}
	elseif($c[$l] == '-') {
		$z2 = false;
		$c = substr($c, 0, -1);
	}
	--$l;
	if(!isset($c[$l]));
	elseif($c[$l] == '+') {
		$z1 = true;
		$c = substr($c, 0, -1);
	}
	elseif($c[$l] == '-') {
		$z1 = false;
		$c = substr($c, 0, -1);
	}
	--$l;
	if(!isset($c[$l]));
	elseif($c[$l] == '/') {
		$c = substr($c, 0, -1);
		if(is_array($a) && $z1)
		foreach($a as &$x)$x = (string)$x;
		else $a = (string)$a;
		if(is_array($b) && $z2)
		foreach($b as &$x)$x = (string)$x;
		else $b = (string)$b;
	}
	if($c == '!===' || $c == '!>' || $c == '!<' || $c == '!>=' || $c == '!<=')$c = ['===' => '!==', '!<' => '>=', '!>' => '<=', '!>=' => '<', '!<=' => '>'][substr($c, 1)];
	if(is_numeric($c))$c = @['==', '!=', '>=', '<=', '>', '<', '!==', '==='][$c];
	$cv = $c[0] == '.' || $c[0] == '$' ? substr($c, 1): $c;
	if($c != '$' && $c != '.' && $cv != '==' && $cv != '!=' && $cv != '>=' && $cv != '<=' && $cv != '<' && $cv != '>' && $cv != '!==' && $cv != '===') {
		throw new XNError("equal", "equal action invalid", XNError::WARNING);
		return false;
	}
	$pp =
	function($a, $b)use($c){
		$ia = (is_string($a) || is_numeric($a));
		$ib = (is_string($b) || is_numeric($b));
		if($c[0] == '.' && is_array($b)) {
			return in_array($a, $b);
		}
		elseif($c[0] == '.' && $ia && $ib) {
			$p = strpos($a, $b);
			return $p !== false && $p != - 1;
		}
		elseif($c[0] == '$' && is_array($b)) {
			return isset($b[$a]);
		}
		elseif($c[0] == '$' && is_object($b)) {
			return isset($b->{$a}) || method_exists($b, $a);
		}
		$a = serialize($a);
		$b = serialize($b);
		return eval("return unserialize('$a'){$c}unserialize('$b');");
	};
	if($d === 1) {
		if($ia && $ib && $z1 && $z2) {
			foreach($a as $x) {
				foreach($b as $y) {
					if($r = $pp($x, $y))break;
				}
				if($r)return true;
			}
			return false;
		}
		if($ia && $z1) {
			foreach($a as $x) {
				if($pp($x, $b))return true;
			}
			return false;
		}
		if($ib && $z2) {
			foreach($b as $x) {
				if($pp($a, $x))return true;
			}
			return false;
		}
	}
	elseif($d === 0) {
		if($ia && $ib && $z1 && $z2) {
			foreach($a as $x) {
				foreach($b as $y) {
					if($r = $pp($x, $y))break;
				}
				if(!$r)return false;
			}
			return true;
		}
		if($ia && $z1) {
			foreach($a as $x) {
				if(!$pp($x, $b))return false;
			}
			return true;
		}
		if($ib && $z2) {
			foreach($b as $x) {
				if(!$pp($a, $x))return false;
			}
			return true;
		}
	}
	elseif($d === 2) {
		if($ia && $ib && $z1 && $z2) {
			foreach($a as $k => $x) {
				if($pp($x, $b[$k]))return true;
			}
			return false;
		}
		if($ia && $z1) {
			foreach($a as $x) {
				if($r = $pp($x, $b))return true;
			}
			return false;
		}
		if($ib && $z2) {
			foreach($b as $x) {
				if($r = $pp($a, $x))return true;
			}
			return false;
		}
	}
	elseif($d == 3) {
		if($ia && $ib && $z1 && $z2) {
			foreach($a as $k => $x) {
				if(!$pp($x, $b[$k]))return false;
			}
			return true;
		}
		if($ia && $z1) {
			foreach($a as $x) {
				if(!$pp($x, $b))return false;
			}
			return true;
		}
		if($ib && $z2) {
			foreach($b as $x) {
				if(!$pp($a, $x))return false;
			}
			return true;
		}
	}
	return $pp($a, $b);
}
function array_string($arr, $js = false){
	if(!is_array($arr) && !is_object($arr)) {
		throw new XNError("array_string", "can not convert " . gettype($arr). " to array string", XNError::TYPE);
		return false;
	}
	$r = '[';
	$p = 0;
	foreach((array)$arr as $k => $v) {
		if($r != '[')$r.= ',';
		if(is_array($v))$v = array_string($v, $js);
		if(is_numeric($k) && $k === $p) {
			$r.= json_encode($v, $js);
			++$p;
		}
		else $r.= json_encode($k, $js). '=>' . json_encode($v, $js);
	}
	$r.= ']';
	return $r;
}
function func_repeat($func, $c){
	$r = '';
	while($c > 0)$r.= $func($c--);
	return $r;
}
function ifstr($a, $b, $c = 87438975298754978){
	return $c == 87438975298754978 ? ($a ? "$a" : "$b"): $a ? "$b" : "$c";
}
function array_repeat($arr, $count = 1){
	for($c = 0; $c < $count; ++$c) {
		foreach($arr as $v)$arr[] = $v;
	}
	return $arr;
}
function array_settype($type, $arr){
	foreach($arr as &$v)settype($v, $type);
	return $arr;
}
function evals($str){
	return eval("return \"$str\";");
}
function findurls($s){
	preg_match_all('/([hH][tT][tT][pP][sS]{0,1}:\/\/)([a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)+)(:[0-9]{1,8}){0,1}(\/([^\/\?\# ])*)*(\#[^\n ]*){0,1}(\?[^\n\# ]*){0,1}(\#[^\n ]*){0,1}/', $s, $u);
	if(!isset($u[0][0]))return false;
	return $u[0];
}
function countin($str, $in){
	return count(explode($in, $str));
}
$GLOBALS['-XN-']['xndatafile'] = $GLOBALS['-XN-']['dirNameDir'] . 'xndata.xnd';
if(!file_exists($GLOBALS['-XN-']['xndatafile']))
	$GLOBALS['-XN-']['xndatafile'] = false;
function xndata_setfile($file){
	if(file_exists($file))
		$GLOBALS['-XN-']['xndatafile'] = $file;
	else
		return false;
	return true;
}
function xndata(string $name){
	$xnd = xndata::xn_data();
	return $xnd->value($name);
}
class TelegramBotKeyboard {
	private $btn = [], $button = [];
	public $resize = false, $onetime = false, $selective = false;
	public function size($size = null){
		if($size === null)$size = !$this->resize;
		$this->resize = $size == true;
		return $this;
	}
	public function onetime($onetime = null){
		if($onetime === null)$onetime = !$this->onetime;
		$this->onetime = $onetime == true;
		return $this;
	}
	public function selective($selective = null){
		if($selective === null)$selective = !$this->selective;
		$this->selective = $selective == true;
		return $this;
	}
	public function add($name, $type = ''){
		$btn = ["text" => $name];
		if($type == "contact")$btn["request_contact"] = true;
		elseif($type == "location")$btn["request_location"] = true;
		$this->btn[] = $btn;
		return $this;
	}
	public function line(){
		$this->button[] = $this->btn;
		$this->btn = [];
		return $this;
	}
	public function get($json = false){
		$this->button[] = $this->btn;
		$btn = ["keyboard" => $this->button];
		if($this->resize)$btn['resize_keyboard'] = true;
		if($this->onetime)$btn['one_time_keyboard'] = true;
		if($this->selective)$btn['selective'] = true;
		$this->button = [];
		$this->btn = [];
		$this->size = false;
		return $json ? json_encode($btn): $btn;
	}
	public function reset(){
		$this->button = [];
		$this->btn = [];
		$this->size = false;
		return $this;
	}
	public function parse(string $str,string $space = '||'){
		return ['keyboard' => array_map(function($x)use($space){
			return array_map(function($x){
				return ['text'=>$x];
			},explode($space,$x));
		},explode("\n",$str))];
	}
}
class TelegramBotInlineKeyboard {
	private $btn = [], $button = [];
	public $resize = false, $onetime = false, $selective = false;
	public function size($size = null){
		if($size === null)$size = !$this->resize;
		$this->resize = $size == true;
		return $this;
	}
	public function onetime($onetime = null){
		if($onetime === null)$onetime = !$this->onetime;
		$this->onetime = $onetime == true;
		return $this;
	}
	public function selective($selective = null){
		if($selective === null)$selective = !$this->selective;
		$this->selective = $selective == true;
		return $this;
	}
	public function add($name, $type, $data = ''){
		$btn = ["text" => $name];
		if($type == "pay")$data = true;
		elseif($type == "game")$type = "callback_game";
		elseif($type == "switch")$type = "switch_inline_query";
		elseif($type == "switch_current_chat")$type = "switch_inline_query_current_chat";
		elseif($type == "callback" || $type == "data")$type = "callback_data";
		elseif($type == "link")$type = "url";
		$btn[$type] = $data;
		$this->btn[] = $btn;
		return $this;
	}
	public function line(){
		$this->button[] = $this->btn;
		$this->btn = [];
		return $this;
	}
	public function get($json = false){
		$this->button[] = $this->btn;
		$btn = ["inline_keyboard" => $this->button];
		if($this->resize)$btn['resize_keyboard'] = true;
		if($this->onetime)$btn['one_time_keyboard'] = true;
		if($this->selective)$btn['selective'] = true;
		$this->button = [];
		$this->btn = [];
		$this->size = false;
		return $json ? json_encode($btn): $btn;
	}
	public function reset(){
		$this->button = [];
		$this->btn = [];
		$this->size = false;
		return $this;
	}
}
class TelegramBotQueryResult {
	public $get = [];
	public function add($type, $id, $title, $input, $args = []){
		$args["type"] = $type;
		$args["id"] = $id;
		$args["title"] = $title;
		$args["input_message_content"] = $input;
		$this->get[] = $args;
		return $this;
	}
	public function inputMessage($text, $parse = false, $preview = false){
		$args = ["message_text" => $text];
		if($parse)$args["parse_mode"] = $parse;
		if($preview)$args["disable_web_page_preview"] = $preview;
		return $args;
	}
	public function inputLocation($latitude, $longitude, $live = false){
		$args = ["latitude" => $latitude, "longitude" => $longitude];
		if($live)$args['live_period'] = $live;
		return $args;
	}
	public function inputVenue($latitude, $longitude, $title, $address, $id = false){
		$args = ["latitude" => $latitude, "longitude" => $longitude, "title" => $title, "address" => $address];
		if($id)$args["foursquare_id"] = $id;
		return $args;
	}
	public function get(){
		$get = $this->get;
		$this->get = [];
		return $get;
	}
	public function reset(){
		$this->get = [];
	}
}
class TelegramBotButtonSave {
	private $btns = [], $btn = [];
	public function get(string $name, $json = true){
		if($json)return @$this->btn[$name];
		return @$this->btns[$name];
	}
	public function add(string $name, $btn){
		if(is_array($btn))$btns = json_encode($btn);
		elseif(!is_json($btn))return false;
		else $btn = json_decode($btns = $btn);
		if(!isset($btns['inline_keyboard']) || !isset($btns['keyboard']) || !isset($btns['force_reply']) || !isset($btns['remove_keyboard']))return false;
		$this->btns = $btns;
		$this->btn = $btn;
		return $this;
	}
	public function delete(string $name){
		if(isset($this->btn[$name])) {
			unset($this->btn[$name]);
			unset($this->btns[$name]);
		}
		return $this;
	}
	public function reset(){
		$this->btn = [];
		$this->btns = [];
		return $this;
	}
	public function exists(string $name){
		return isset($this->btn[$name]);
	}
	public function parse(string $str,string $space = '||'){
		return array_map(function($x)use($space){
			return array_map(function($x){
				return ['text'=>$x];
			},explode($space,$x));
		},explode("\n",$str));
	}
}
class TelegramBotSaveMsgs {
	private $msgs = [];
	public function get(string $name){
		return isset($this->msgs[$name])? $this->msgs[$name] : false;
	}
	public function add(string $name, $message){
		$message = XNString::toString($message);
		$this->msgs[$name] = $message;
		return $this;
	}
	public function delete(string $name){
		if(isset($this->msgs[$name]))unset($this->msgs[$name]);
		return $this;
	}
	public function reset(){
		$this->msgs = [];
		return $this;
	}
	public function exists(string $name){
		return isset($this->msgs[$name]);
	}
}
class TelegramBotSends {
	private $bot;
	public $chat, $level;
	public function chat($chat){
		$this->chat;
		return $this;
	}
	public function level($level){
		$this->level = $level;
		return $this;
	}
	public function __construct($bot, $chat = null, $level = null){
		$this->bot = $bot;
		$this->chat = $chat;
		$this->level = $level;
	}
	public function __invoke($chat = null, $level = null){
		if($chat && $level) {
			$this->chat = $chat;
			$this->level = $level;
		}
		elseif($chat) {
			if($chat < 100)$this->level = $chat;
			else $this->chat = $chat;
		}
		return $this;
	}
	public function action($action){
		$this->bot->sendAction($this->chat, $action, $this->level);
		return $this;
	}
	public function typing(){
		$this->bot->sendAction($this->chat, "typing", $this->level);
		return $this;
	}
	public function msg($text, $args = []){
		$this->bot->sendMessage($this->chat, $text, $args, $this->level);
		return $this;
	}
	public function btnmsg($text, $btn, $args = []){
		$args['reply_markup'] = $btn;
		$this->bot->sendMessage($this->chat, $text, $args, $this->level);
		return $this;
	}
	public function media($type, $media, $args = []){
		$this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
		return $this;
	}
	public function mediamsg($type, $media, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
		return $this;
	}
	public function mediabtn($type, $media, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
		return $this;
	}
	public function mediamsgbtn($type, $media, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendMedia($this->chat, $type, $media, $args, $this->level);
		return $this;
	}
	public function photo($photo, $args = []){
		$this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
		return $this;
	}
	public function voice($voice, $args = []){
		$this->bot->sendVoice($this->chat, $voice, $args, $this->level);
		return $this;
	}
	public function video($video, $args = []){
		$this->bot->sendVideo($this->chat, $video, $args, $this->level);
		return $this;
	}
	public function audio($audio, $args = []){
		$this->bot->sendAudio($this->chat, $audio, $args, $this->level);
		return $this;
	}
	public function videonote($videonote, $args = []){
		$this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
		return $this;
	}
	public function sticker($sticker, $args = []){
		$this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
		return $this;
	}
	public function document($document, $args = []){
		$this->bot->sendDocument($this->chat, $document, $args, $this->level);
		return $this;
	}
	public function file($file, $args = []){
		$this->bot->sendFile($this->chat, $file, $args, $this->level);
		return $this;
	}
	public function photomsg($photo, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
		return $this;
	}
	public function voicemsg($voice, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendVoice($this->chat, $voice, $args, $this->level);
		return $this;
	}
	public function videomsg($video, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendVideo($this->chat, $video, $args, $this->level);
		return $this;
	}
	public function audiomsg($audio, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendAudio($this->chat, $audio, $args, $this->level);
		return $this;
	}
	public function videonotemsg($videonote, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
		return $this;
	}
	public function stickermsg($sticker, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
		return $this;
	}
	public function documentmsg($document, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendDocument($this->chat, $document, $args, $this->level);
		return $this;
	}
	public function filemsg($file, $caption, $args = []){
		$args['caption'] = $caption;
		$this->bot->sendFile($this->chat, $file, $args, $this->level);
		return $this;
	}
	public function photobtn($photo, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
		return $this;
	}
	public function voicebtn($voice, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendVoice($this->chat, $voice, $args, $this->level);
		return $this;
	}
	public function videobtn($video, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendVideo($this->chat, $video, $args, $this->level);
		return $this;
	}
	public function audiobtn($audio, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendAudio($this->chat, $audio, $args, $this->level);
		return $this;
	}
	public function videonotebtn($videonote, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
		return $this;
	}
	public function stickerbtn($sticker, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
		return $this;
	}
	public function documentbtn($document, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendDocument($this->chat, $document, $args, $this->level);
		return $this;
	}
	public function filebtn($file, $markup, $args = []){
		$args['reply_markup'] = $markup;
		$this->bot->sendFile($this->chat, $file, $args, $this->level);
		return $this;
	}
	public function photomsgbtn($photo, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendPhoto($this->chat, $photo, $args, $this->level);
		return $this;
	}
	public function voicemsgbtn($voice, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendVoice($this->chat, $voice, $args, $this->level);
		return $this;
	}
	public function videomsgbtn($video, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendVideo($this->chat, $video, $args, $this->level);
		return $this;
	}
	public function audiomsgbtn($audio, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendAudio($this->chat, $audio, $args, $this->level);
		return $this;
	}
	public function videonotemsgbtn($videonote, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendVideoNote($this->chat, $videonote, $args, $this->level);
		return $this;
	}
	public function stickermsgbtn($sticker, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendSticker($this->chat, $sticker, $args, $this->level);
		return $this;
	}
	public function documentmsgbtn($document, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendDocument($this->chat, $document, $args, $this->level);
		return $this;
	}
	public function filemsgbtn($file, $caption, $markup, $args = []){
		$args['caption'] = $caption;
		$args['reply_markup'] = $markup;
		$this->bot->sendFile($this->chat, $file, $args, $this->level);
		return $this;
	}
	public function uploadingPhoto(){
		$this->bot->sendUploadingPhoto($this->chat, $this->level);
		return $this;
	}
	public function uploadingAudio(){
		$this->bot->sendUploadingAudio($this->chat, $this->level);
		return $this;
	}
	public function uploadingVideo(){
		$this->bot->sendUploadingVideo($this->chat, $this->level);
		return $this;
	}
	public function uploadingDocument(){
		$this->bot->sendUploadingDocument($this->chat, $this->level);
		return $this;
	}
	public function uploadingVideoNote(){
		$this->bot->sendUploadingVideoNote($this->chat, $this->level);
		return $this;
	}
	public function findingLocation(){
		$this->bot->sendFindingLocation($this->chat, $this->level);
		return $this;
	}
	public function recordingAudio(){
		$this->bot->sendRecordingAudio($this->chat, $this->level);
		return $this;
	}
	public function recordingVideo(){
		$this->bot->sendRecordingVideo($this->chat, $this->level);
		return $this;
	}
	public function recordingVideoNote(){
		$this->bot->sendRecordingVideoNote($this->chat, $this->level);
		return $this;
	}
	public function delmsg($id){
		$this->bot->deleteMessage($this->chat, $id, $this->level);
		return $this;
	}
	public function editmsg($message,$id = false, $text, $args = []){
		if($id)
			$this->bot->editMessageText($this->chat, $message, $id, $text, $args, $this->level);
		else
			$this->bot->editInlineText($this->chat, $message, $text, $args, $this->level);
		return $this;
	}
	public function editmsgbtn($message,$id = false, $text, $keyboard, $args = []){
		$args['reply_markup'] = $keyboard;
		if($id)
			$this->bot->editMessageText($this->chat, $message, $id, $text, $args, $this->level);
		else
			$this->bot->editInlineText($this->chat, $message, $text, $args, $this->level);
		return $this;
	}
	public function editlive($message, $id, $live, $args = []){
		$this->bot->editMessageLiveLocation($this->chat, $message, $id, $live, $args, $this->level);
		return $this;
	}
	public function editcaption($message,$id = false, $text, $args = []){
		if($id)
			$this->bot->editMessageText($this->chat, $message, $id, $text, $args, $this->level);
		else
			$this->bot->editInlineText($this->chat, $message, $text, $args, $this->level);
		return $this;
	}
	public function editcaptionbtn($message,$id = false, $text, $keyboard, $args = []){
		$args['reply_markup'] = $keyboard;
		if($id)
			$this->bot->editMessageCaption($this->chat, $message, $id, $text, $args, $this->level);
		else
			$this->bot->editInlineCaption($this->chat, $message, $text, $args, $this->level);
		return $this;
	}
	public function editmedia($message,$id = false, $media, $args = []){
		if($id)
			$this->bot->editMessageMedia($this->chat, $message, $id, $media, $args, $this->level);
		else
			$this->bot->editInlineMedia($this->chat, $message, $media, $args, $this->level);
		return $this;
	}
	public function editmediabtn($message,$id = false, $media, $keyboard, $args = []){
		$args['reply_markup'] = $keyboard;
		if($id)
			$this->bot->editMessageMedia($this->chat, $message, $id, $media, $args, $this->level);
		else
			$this->bot->editInlineMedia($this->chat, $message, $media, $args, $this->level);
		return $this;
	}
}
class TelegramBot {
	public $data, $token, $final, $results = [], $sents = [], $save = true, $last, $parser = true, $variables = false, $notresponse = false, $autoaction = false, $handle = false;
	public $keyboard, $inlineKeyboard, $foreReply, $removeKeyboard, $queryResult, $menu, $send, $msgs;
	
	const KEYBOARD = 'keyboard';
	const INLINE_KEYBOARD = 'inline_keyboard';
	const REMOVE_KEYBOARD = 'remove_keyboard';
	const FORCE_REPLY = 'force_reply';
	const RESIZE_KEYBOARD = 'resize_keyboard';
	const BTN_TEXT = 'text';
	const BTN_DATA = 'callback_data';
	const BTN_SWITCH = 'switch_inline_query';
	const BTN_SWITCH_CURRENT = 'switch_inline_query_current_chat';


	public function send($chat = null, $level = null){
		return new TelegramBotSends($this, $chat, $level);
	}
	public function setToken($token = ''){
		$this->last = $this->token;
		$this->token = $token;
		return $this;
	}
	public function backToken(){
		$token = $this->token;
		$this->token = $this->last;
		$this->last = $token;
		return $this;
	}
	public function __construct($token = ''){
		$this->token = $token;
		$this->keyboard = new TelegramBotKeyboard;
		$this->inlineKeyboard = new TelegramBotInlineKeyboard;
		$this->queryResult = new TelegramBotQueryResult;
		$this->menu = new TelegramBotButtonSave;
		$this->send = new TelegramBotSends($this);
		$this->msgs = new TelegramBotSaveMsgs;
		$this->forceReply = ["force_reply" => true];
		$this->removeKeyboard = ["remove_keyboard" => true];
	}
	public function update($offset = - 1, $limit = 1, $timeout = 0){
		if(isset($this->data->message_id))return $this->data;
		elseif($this->data = json_decode(file_get_contents("php://input")))return $this->data;
		else $res = $this->data = $this->request("getUpdates", ["offset" => $offset, "limit" => $limit, "timeout" => $timeout], 3);
		return (object)$res;
	}
	public function request($method, $args = [], $level = 3){
		$args = $this->parse_args($method, $args);
		$res = false;
		$func = $this->handle;
		$handle = $func ? new ThumbCode(
		function()use(&$method, &$args, &$res, &$level, &$func){
			$func((object)["method" => $method, "arguments" => $args, "result" => $res, "level" => $level]);
		}): false;
		if($this->autoaction && isset($args['chat_id'])) {
			switch (strtolower($method)) {
			case "sendmessage":
				$action = "typing";
				break;
			case "sendphoto":
				$action = "upload_photo";
				break;
			case "sendvoice":
				$action = "record_audio";
				break;
			case "sendvideo":
				$action = "upload_video";
				break;
			case "sendvideonote":
				$action = "uplaod_video_note";
				break;
			case "sendaudio":
				$action = "upload_audio";
				break;
			case "senddocument":
				$action = "upload_document";
				break;
			default:
				$action = false;
				break;
			}
			if($action)$this->request("sendChatAction", ["chat_id" => $args['chat_id'], "action" => $action]);
		}
		if($level == 1) {
			$args['method'] = $method;
			print json_encode($args);
			$res = true;
		}
		elseif($level == 2) {
			$res = @fopen("https://api.telegram.org/bot$this->token/$method?" . http_build_query($args), 'r');
			if($res)fclose($res = true);
			else $res = false;
		}
		elseif($level == 3) {
			$c = curl_init("https://api.telegram.org/bot$this->token/$method");
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, $args);
			$res = json_decode(curl_exec($c));
			curl_close($c);
		}
		else return false;
		$args['method'] = $method;
		$args['level'] = $level;
		if($this->save) {
			$this->sents[] = $args;
			$this->results[] = $this->final = $res;
		}
		if($res === false)return false;
		if($res === true)return true;
		if(!$res) {
			$server = ["OUTPUT", "api.telegram.org", "api.telegram.org"][$level - 1];
			new XNError("TelegramBot", "can not Connect to $server", XNError::NETWORK);
			return false;
		}
		elseif(!$res->ok) {
			new XNError("TelegramBot", "$res->description [$res->error_code]", XNError::NOTIC);
			return $res;
		}
		return $res;
	}
	public function reset(){
		$this->final = null;
		$this->results = [];
		$this->sents = [];
		$this->data = null;
	}
	public function close(){
		$this->__destruct();
	}
	public function __destruct(){
		$this->final = null;
		$this->results = null;
		$this->sents = null;
		$this->data = null;
		$this->token = null;
		$this->inlineKeyboard = null;
		$this->keyboard = null;
		$this->forceReply = null;
		$this->removeKeyboard = null;
		$this->queryResult = null;
		$this->send = null;
		$this->menu = null;
		if($this->notresponse)($this->notresponse)();
	}
	public function sendMessage($chat, $text, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['text'] = $text;
		return $this->request("sendMessage", $args, $level);
	}
	public function sendMessages($chat, $text, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$texts = str_split($text, 4096);
		foreach($texts as $text) {
			$args['text'] = $text;
			$this->request("sendMessage", $args, $level);
		}
		return $this;
	}
	public function sendMessageRemoveKeyboard($chat, $text, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['text'] = $text;
		$args['reply_markup'] = json_encode(["remove_keyboard" => true]);
		return $this->request("sendMessage", $args, $level);
	}
	public function sendMessageForceReply($chat, $text, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['text'] = $text;
		$args['reply_markup'] = json_encode(['force_reply' => true]);
		return $this->request("sendMessage", $args, $level);
	}
	public function sendAction($chat, $action, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => $action], $level);
	}
	public function sendUploadingPhoto($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "upload_photo"], $level);
	}
	public function sendUploadingVideo($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "upload_video"], $level);
	}
	public function sendUploadingAudio($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "upload_audio"], $level);
	}
	public function sendUploadingDocument($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "upload_document"], $level);
	}
	public function sendUploadingVideoNote($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "upload_video_note"], $level);
	}
	public function sendFindingLocation($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "find_location"], $level);
	}
	public function sendRecordingVideo($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "record_video"], $level);
	}
	public function sendRecordingAudio($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "record_audio"], $level);
	}
	public function sendRecordingVideoNote($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "record_video_note"], $level);
	}
	public function sendTyping($chat, $level = 3){
		return $this->request("sendChatAction", ["chat_id" => $chat, "action" => "typing"], $level);
	}
	public function setWebhook($url = '', $args = [], $level = 3){
		$args['url'] = $url ? $url : '';
		return $this->request("setWebhook", $args, $level);
	}
	public function deleteWebhook($level = 3){
		return $this->request("setWebhook", [], $level);
	}
	public function getChat($chat, $level = 3){
		return $this->request("getChat", ["chat_id" => $chat], $level);
	}
	public function getMembersCount($chat, $level = 3){
		return $this->request("getChatMembersCount", ["chat_id" => $chat], $level);
	}
	public function getMember($chat, $user, $level = 3){
		return $this->request("getChatMember", ["chat_id" => $chat, "user_id" => $user], $level);
	}
	public function getProfile($user, $level = 3){
		$args['user_id'] = $user;
		$args['chat_id'] = $user;
		return $this->request("getUserProfilePhotos", $args, $level);
	}
	public function banMember($chat, $user, $time = false, $level = 3){
		$args = ["chat_id" => $chat, "user_id" => $user];
		if($time)$args['until_date'] = $time;
		return $this->request("kickChatMember", $args, $level);
	}
	public function unbanMember($chat, $user, $level = 3){
		return $this->request("unbanChatMember", ["chat_id" => $chat, "user_id" => $user], $level);
	}
	public function kickMember($chat, $user, $level = 3){
		return [$this->banMember($chat, $user, $level), $this->unbanMember($chat, $user, $level)];
	}
	public function getMe($level = 3){
		return $this->request("getMe", [], $level);
	}
	public function getWebhook($level = 3){
		return $this->request("getWebhookInfo", [], $level);
	}
	public function resrictMember($chat, $user, $args, $time = false, $level = 3){
		foreach($args as $key => $val)$args["can_$key"] = $val;
		$args['chat_id'] = $chat;
		$args['user_id'] = $user;
		if($time)$args['until_date'] = $time;
		return $this->request("resrictChatMember", $args, $level);
	}
	public function promoteMember($chat, $user, $args = [], $level = 3){
		foreach($args as $key => $val)$args["can_$key"] = $val;
		$args['chat_id'] = $chat;
		$args['user_id'] = $user;
		return $this->request("promoteChatMember", $args, $level);
	}
	public function exportInviteLink($chat, $level = 3){
		$this->request("exportChatInviteLink", ["chat_id" => $chat], $level);
	}
	public function setChatPhoto($chat, $photo, $level = 3){
		return $this->request("setChatPhoto", ["chat_id" => $chat, "photo" => $photo], $level);
	}
	public function deleteChatPhoto($chat, $level = 3){
		return $this->request("deleteChatPhoto", ["chat_id" => $chat], $level);
	}
	public function setTitle($chat, $title, $level = 3){
		return $this->request("setChatTitle", ["chat_id" => $chat, "title" => $title], $level);
	}
	public function setDescription($chat, $description, $level = 3){
		return $this->request("setChatDescription", ["chat_id" => $chat, "description" => $description], $level);
	}
	public function pinMessage($chat, $message, $disable = false, $level = 3){
		return $this->request("pinChatMessage", ["chat_id" => $chat, "message_id" => $message, "disable_notification" => $disable], $level);
	}
	public function unpinMessage($chat, $level = 3){
		return $this->request("unpinChatMessage", ["chat_id" => $chat], $level);
	}
	public function leaveChat($chat, $level = 3){
		return $this->request("leaveChat", ["chat_id" => $chat], $level);
	}
	public function getAdmins($chat, $level = 3){
		return $this->request("getChatAdministrators", ["chat_id" => $chat], $level);
	}
	public function setChatStickerSet($chat, $sticker, $level = 3){
		return $this->request("setChatStickerSet", ["chat_id" => $chat, "sticker_set_name" => $sticker], $level);
	}
	public function deleteChatStickerSet($chat, $level = 3){
		return $this->request("deleteChatStickerSet", ["chat_id" => $chat], $level);
	}
	public function answerCallback($id, $text, $args = [], $level = 3){
		$args['callback_query_id'] = $id;
		$args['text'] = $text;
		return $this->request("answerCallbackQuery", $args, $level);
	}
	public function editText($text, $args = [], $level = 3){
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editMessageText($chat, $msg, $text, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editInlineText($msg, $text, $args = [], $level = 3){
		$args['inline_message_id'] = $msg;
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editCaption($caption, $args = [], $level = 3){
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editMessageCaption($chat, $msg, $caption, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$arsg['message_id'] = $msg;
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editInlineCaption($msg, $caption, $args = [], $level = 3){
		$arsg['inline_message_id'] = $msg;
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editReplyMarkup($reply_makup, $args = [], $level = 3){
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMessageReplyMarkup($chat, $msg, $reply_makup, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editInlineReplyMarkup($msg, $reply_makup, $args = [], $level = 3){
		$args['inline_message_id'] = $msg;
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMedia($media, $args = [], $level = 3){
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editMessageMedia($chat, $message, $media, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $message;
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editInlineMedia($message, $media, $args = [], $level = 3){
		$args['inline_message_id'] = $message;
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editInlineKeyboard($reply_makup, $args = [], $level = 3){
		$args['reply_markup'] = ["inline_keyboard" => $reply_markup];
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMessageInlineKeyboard($chat, $msg, $reply_makup, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['reply_markup'] = ["inline_keyboard" => $reply_markup];
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editInlineInlineKeyboard($msg, $reply_makup, $args = [], $level = 3){
		$args['inline_message_id'] = $msg;
		$args['reply_markup'] = ["inline_keyboard" => $reply_markup];
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function deleteMessage($chat, $message, $level = 3){
		return $this->request("deleteMessage", ["chat_id" => $chat, "message_id" => $message], $level);
	}
	public function deleteMessages($chat, $messages, $level = 3){
		if($level > 5) {
			$level-= 5;
			$from = min(...$messages);
			$to = max(...$messages);
			for(; $from <= $to; ++$from)$this->request("deleteMessage", ["chat_id" => $chat, "message_id" => $from], $level);
		}
		else {
			foreach($messages as $message)$this->request("deleteMessage", ["chat_id" => $chat, "message_id" => $message], $level);
		}
	}
	public function sendMedia($chat, $type, $file, $args = [], $level = 3){
		$type = strtolower($type);
		if($type == "videonote")$type = "video_note";
		$args['chat_id'] = $chat;
		$args[$type] = $file;
		return $this->request("send" . str_replace('_', '', $type), $args, $level);
	}
	public function sendFile($chat, $file, $args = [], $level = 3){
		$type = XNTelegram::botfileid_info($file)['type'];
		if(!$type)return false;
		$args['chat_id'] = $chat;
		$args[$type] = $file;
		return $this->request("send" . str_replace('_', '', $type), $args, $level);
	}
	public function getStickerSet($name, $level = 3){
		return $this->request("getStickerSet", ["name" => $name], $level);
	}
	public function sendDocument($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['document'] = $file;
		return $this->request("sendDocument", $args, $level);
	}
	public function sendPhoto($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['photo'] = $file;
		return $this->request("sendPhoto", $args, $level);
	}
	public function sendVideo($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['video'] = $file;
		return $this->request("sendVideo", $args, $level);
	}
	public function sendAudio($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['audio'] = $file;
		return $this->request("sendAudio", $args, $level);
	}
	public function sendVoice($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['voice'] = $file;
		return $this->request("sendVoice", $args, $level);
	}
	public function sendSticker($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['sticker'] = $file;
		return $this->request("sendSticker", $args, $level);
	}
	public function sendVideoNote($chat, $file, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['video_note'] = $file;
		return $this->request("sendVideoNote", $args, $level);
	}
	public function uploadStickerFile($user, $file, $level = 3){
		return $this->request("uploadStickerFile", ["user_id" => $user, "png_sticker" => $file], $level);
	}
	public function createNewStickerSet($user, $name, $title, $args = [], $level = 3){
		$args['user_id'] = $user;
		$args['name'] = $name;
		$args['title'] = $title;
		return $this->request("createNewStickerSet", $args, $level);
	}
	public function addStickerToSet($user, $name, $args = [], $level = 3){
		$args['user_id'] = $user;
		$args['name'] = $name;
		return $this->request("addStickerToSet", $args, $level);
	}
	public function setStickerPositionInSet($sticker, $position, $level = 3){
		return $this->request("setStickerPositionInSet", ["sticker" => $sticker, "position" => $position], $level);
	}
	public function deleteStickerFromSet($sticker, $level = 3){
		return $this->request("deleteStickerFromSet", ["sticker" => $sticker], $level);
	}
	public function answerInline($id, $results, $args = [], $switch = [], $level = 3){
		$args['inline_query_id'] = $id;
		$args['results'] = is_array($results)? json_encode($results): $results;
		if($switch['text'])$args['switch_pm_text'] = $switch['text'];
		if($switch['parameter'])$args['switch_pm_parameter'] = $switch['parameter'];
		return $this->request("answerInlineQuery", $args, $level);
	}
	public function answerPreCheckout($id, $ok = true, $level = 3){
		if($ok === true)$args = ["pre_checkout_query_id" => $id, "ok" => true];
		else $args = ["pre_checkout_query_id" => $id, "ok" => false, "error_message" => $ok];
		return $this->request("answerPreCheckoutQuery", $args, $level);
	}
	public function setGameScore($user, $score, $args = [], $level = 3){
		$args['user_id'] = $user;
		$args['score'] = $score;
		return $this->request("setGameScore", $args, $level);
	}
	public function getGameHighScores($user, $args = [], $level = 3){
		$args['user_id'] = $user;
		return $this->request("getGameHighScores", $args, $level);
	}
	public function sendGame($chat, $name, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['name'] = $name;
		return $this->request("sendGame", $args, $level);
	}
	public function getFile($file, $level = 3){
		return $this->request("getFile", ["file_id" => $file], $level);
	}
	public function readFile($path, $level = 3, $speed = false){
		if($speed)$func = "fget";
		else $func = "file_get_contents";
		if($level == 3) {
			return ($func)("https://api.telegram.org/file/bot$this->token/$path");
		}
		else return false;
	}
	public function downloadFile($file, $level = 3){
		return $this->readFile($this->getFile($file, 3)->result->file_path, $level);
	}
	public function downloadFileProgress($file, $func, $al, $level = 3){
		$file = $this->request("getFile", ["file_id" => $file], $level);
		if(!$file->ok)return false;
		$size = $file->result->file_size;
		$path = $file->result->file_path;
		$time = microtime(true);
		if($level == 3) {
			return fgetprogress("https://api.telegram.org/file/bot$this->token/$path",
			function($data)use($size, $func, $time){
				$dat = strlen($data);
				$up = microtime(true)- $time;
				$speed = $dat / $up;
				$all = $size / $dat * $time - $time;
				$pre = 100 / ($size / $dat);
				return $func((object)["content" => $data, "downloaded" => $dat, "size" => $size, "time" => $up, "endtime" => $all, "speed" => $speed, "pre" => $pre]);
			}
			, $al);
		}
		else return false;
	}
	public function sendContact($chat, $phone, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['phone_number'] = $phone;
		return $this->request("sendContact", $args, $level);
	}
	public function sendVenue($chat, $latitude, $longitude, $title, $address, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['latitude'] = $latitude;
		$args['longitude'] = $longitude;
		$args['title'] = $title;
		$args['address'] = $address;
		return $this->request("sendVenue", $args, $level);
	}
	public function stopMessageLiveLocation($args, $level = 3){
		return $this->request("stopMessageLiveLocation", $args, $level);
	}
	public function editMessageLiveLocation($latitude, $longitude, $args = [], $level = 3){
		$args['latitude'] = $latitude;
		$args['longitude'] = $longitude;
		return $this->request("editMessageLiveLocation", $args, $level);
	}
	public function sendLocation($chat, $latitude, $longitude, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['latitude'] = $latitude;
		$args['longitude'] = $longitude;
		$this->request("sendLocation", $args, $level);
	}
	public function sendMediaGroup($chat, $media, $args = [], $level = 3){
		$args['chat_id'] = $chat;
		$args['media'] = json_encode($media);
		return $this->request("sendMediaGroup", $args, $level);
	}
	public function forwardMessage($chat, $from, $message, $disable = false, $level = 3){
		return $this->request("forwardMessage", ["chat_id" => $chat, "from_chat_id" => $from, "message_id" => $message, "disable_notification" => $disable], $level);
	}
	public function getAllMembers($chat){
		return json_decode(file_get_contents("http://xns.elithost.eu/getparticipants/?token=$this->token&chat=$chat"));
	}
	public function updateType($update = false){
		if(!$update)$update = $this->lastUpdate();
		if(isset($update->message))return "message";
		elseif(isset($update->callback_query))return "callback_query";
		elseif(isset($update->chosen_inline_result))return "chosen_inline_result";
		elseif(isset($update->inline_query))return "inline_query";
		elseif(isset($update->channel_post))return "channel_post";
		elseif(isset($update->edited_message))return "edited_message";
		elseif(isset($update->edited_channel_post))return "edited_channel_post";
		elseif(isset($update->shipping_query))return "shipping_query";
		elseif(isset($update->pre_checkout_query))return "pre_checkout_query";
		return "unknow_update";
	}
	public function getUpdateInType($update = false){
		return ($update ? $update : $this->lastUpdate())->{$this->updateType()};
	}
	public function readUpdates($func, $while = 0, $limit = 1, $timeout = 0){
		if($while == 0)$while = - 1;
		$offset = 0;
		while($while > 0 || $while < 0) {
			$updates = $this->update($offset, $limit, $timeout);
			if(isset($updates->message_id)) {
				if($offset == 0)$updates = (object)["result" => [$updates]];
				else return;
			}
			if(isset($updates->result)) {
				foreach($updates->result as $update) {
					$offset = $update->update_id + 1;
					if($func($update))return true;
				}
				--$while;
			}
		}
	}
	public function filterUpdates($filter = [], $func = false){
		if(in_array($this->updateType(), $filter)) {
			if($func)$func($this->data);
			exit();
		}
	}
	public function unfilterUpdates($filter = [], $func = false){
		if(!in_array($this->updateType(), $filter)) {
			if($func)$func($this->data);
			exit();
		}
	}
	public function getUser($update = false){
		$update = $this->getUpdateInType($update);
		if(!isset($update->chat))return (object)["chat" => $update->from, "from" => $update->from];
		return (object)["chat" => $update->chat, "from" => $update->from];
	}
	public function getDate($update = false){
		$update = $this->getUpdateInType($update);
		if(isset($update->date))return $update->date;
		return false;
	}
	public function getData($update = false){
		$update = $this->getUpdateInType($update);
		if(isset($update->text))return $update->text;
		if(isset($update->query))return $update->query;
		return false;
	}
	public function isChat($user, $update = false){
		$chat = $this->getUser($update)->chat->id;
		if(is_array($user) && in_array($chat, $user))return true;
		elseif($user == $chat)return true;
		return false;
	}
	public function lastUpdate(){
		$update = $this->update();
		if(isset($update->update_id))return $update;
		elseif(isset($update->result[0]->update_id))return $update->result[0];
		else return [];
	}
	public function getUpdates(){
		$update = $this->update(0, 999999999999, 0);
		if(isset($update->update_id))return [$update];
		elseif($update->result[0]->update_id)return $update->result;
		else return [];
	}
	public function lastUpdateId($update = false){
		if(!$update)$update = $this->update(-1, 1, 0);
		if($update->result[0]->update_id)return end($update->result)->update_id;
		elseif(isset($update->update_id))return $update->update_id;
		else return 0;
	}
	public function fileType($message = false){
		if(!$message && isset($this->lastUpdate()->message))$message = $this->lastUpdate()->message;
		elseif(!$message)return false;
		if(isset($message->photo))return "photo";
		if(isset($message->voice))return "voice";
		if(isset($message->audio))return "audio";
		if(isset($message->video))return "video";
		if(isset($message->sticker))return "sticker";
		if(isset($message->document))return "document";
		if(isset($message->video_note))return "videonote";
		return false;
	}
	public function fileInfo($message = false){
		if(!$message && isset($this->lastUpdate()->message))$message = $this->lastUpdate()->message;
		elseif(!$message)return false;
		if(isset($message->photo))return end($message->photo);
		if(isset($message->voice))return $message->voice;
		if(isset($message->audio))return $message->audio;
		if(isset($message->video))return $message->video;
		if(isset($message->sticker))return $message->sticker;
		if(isset($message->document))return $message->document;
		if(isset($message->video_note))return $message->video_note;
		return false;
	}
	public function isFile($message = false){
		if(!$message && isset($this->lastUpdate()->message))$message = $this->lastUpdate()->message;
		elseif(!$message)return false;
		if($message->text)return false;
		return true;
	}
	public function convertFile($chat, $file, $name, $type = "document", $level = 3){
		if(file_exists($name))$read = file_get_contents($name);
		else $read = false;
		file_put_contents($name, $this->downloadFile($file, $level));
		$r = $this->sendMedia($chat, $type, new CURLFile($name), $level);
		if($read !== false)file_put_contents($name, $read);
		else unlink($name);
		return $r;
	}
	public function toGFile($file){
		$file = base64url_decode($file);
		$token = base64url_decode($this->token);
		$file = chr(strlen($file)). $file;
		return base64url_encode($file . $token);
	}
	public function fromGFile($chat, $file, $name, $type = "document", $level = 3){
		$r = base64url_decode($file);
		$p = ord($r[0]);
		$file = substr($r, 1, $p);
		$token = substr($r, $p + 1);
		$bot = new TelegramBot($token);
		$get = false;
		if(file_exists($name))$get = file_get_contents($name);
		file_put_contents($name, $bot->downloadFile($file, $level));
		$bot->sendMedia($chat, $type, new CURLFile($name), $level);
		if($get)file_put_contents($name, $get);
		else unlink($name);
	}
	public function downloadGFile($file, $level = 3){
		$r = base64url_decode($file);
		$p = ord($r[0]);
		$file = substr($r, 1, $p);
		$token = substr($r, $p + 1);
		$bot = new TelegramBot($token);
		return $bot->downloadFile($file, $level);
	}
	public function sendUpdate($url, $update = false){
		if($update === false)$update = $this->update();
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($c, CURLOPT_POSTFIELDS, $update);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$r = curl_exec($c);
		curl_close($c);
		return $r;
	}
	public function sendMessageFromUpdate($chat, $update = false, $args = [], $level = 3){
		if($update)$update = $this->update->message;
		elseif(isset($update->message))$update = $update->message;
		$args['file'] = isset($args['file'])? $args['file'] : isset($args['document'])? $args['document'] : isset($args['video'])? $args['video'] : isset($args['voice'])? $args['voice'] : isset($args['video_note'])? $args['video_note'] : isset($args['audio'])? $args['audio'] : isset($args['sticker'])? $args['sticker'] : isset($args['photo_file_id'])? $args['photo_file_id'] : isset($args['document_file_id'])? $args['document_file_id'] : isset($args['video_file_id'])? $args['video_file_id'] : isset($args['voice_file_id'])? $args['voice_file_id'] : isset($args['video_note_file_id'])? $args['video_note_file_id'] : isset($args['audio_file_id'])? $args['audio_file_id'] : isset($args['sticker_file_id'])? $args['sticker_file_id'] : isset($args['photo_url'])? $args['photo_url'] : isset($args['document_url'])? $args['document_url'] : isset($args['video_url'])? $args['video_url'] : isset($args['voice_url'])? $args['voice_url'] : isset($args['video_note_url'])? $args['video_note_url'] : isset($args['audio_url'])? $args['audio_url'] : isset($args['sticker_url'])? $args['sticker_url'] : isset($args['file_id'])? $args['file_id'] : isset($args['photo'])? $args['photo'] : false;
		if($args['file']) {
			$args['photo'] = $args['document'] = $args['video'] = $args['voice'] = $args['video_note'] = $args['audio'] = $args['sticker'] = $args['photo_file_id'] = $args['document_file_id'] = $args['video_file_id'] = $args['voice_file_id'] = $args['video_note_file_id'] = $args['audio_file_id'] = $args['sticker_file_id'] = $args['photo_url'] = $args['document_url'] = $args['video_url'] = $args['voice_url'] = $args['video_note_url'] = $args['audio_url'] = $args['sticker_url'] = $args['file_id'] = $args['file'];
			if(isset($update->caption))$args['caption'] = isset($args['caption'])? $args['caption'] : $update->caption;
			if(isset($update->photo))return $this->sendPhoto($chat, isset($args['photo'])? $args['photo'] : end($update->photo)->file_id, $args, $level);
			if(isset($update->video))return $this->sendVideo($chat, isset($args['video'])? $args['video'] : $update->video->file_id, $args, $level);
			if(isset($update->voice))return $this->sendVoice($chat, isset($args['voice'])? $args['voice'] : $update->voice->file_id, $args, $level);
			if(isset($update->audio))return $this->sendAudio($chat, isset($args['audio'])? $args['audio'] : $update->audio->file_id, $args, $level);
			if(isset($update->video_note))return $this->sendVideoNote($chat, isset($args['video_note'])? $args['video_note'] : $update->video_note->file_id, $args, $level);
			if(isset($update->sticker))return $this->sendSticker($chat, isset($args['sticker'])? $args['sticker'] : $update->sticker->file_id, $args, $level);
			if(isset($update->document))return $this->sendDocument($chat, isset($args['document'])? $args['document'] : $update->document->file_id, $args, $level);
		}
		if(isset($update->text))return $this->sendMessage($chat, isset($args['text'])? $args['text'] : $update->text, $args, $level);
		if(isset($update->contact)) {
			$args['phone'] = isset($args['phone'])? $args['phone'] : isset($args['number'])? $args['number'] : isset($args['phone_number'])? $args['phone_number'] : false;
			$args['first_name'] = isset($args['first_name'])? $args['first_name'] : $update->contact->first_name;
			$args['last_name'] = isset($args['last_name'])? $args['last_name'] : isset($update->contact->last_name)? $update->contact->last_name : false;
			if($args['last_name'] === false)unset($args['last_name']);
			return $this->sendContact($chat, $args['phone'] ? $args['phone'] : $update->contact->phone_number, $args, $level);
		}
		if(isset($update->location)) {
			$latitude = isset($args['latitude'])? $args['latitude'] : $update->location->latitude;
			$longitude = isset($args['longitude'])? $args['longitude'] : $update->location->longitude;
			return $this->sendLocation($chat, $latitude, $longitude, $args, $level);
		}
		if(isset($update->venue)) {
			$latitude = isset($args['latitude'])? $args['latitude'] : $update->venue->latitude;
			$longitude = isset($args['longitude'])? $args['longitude'] : $update->venue->longitude;
			$address = isset($args['address'])? $args['address'] : $update->venue->address;
			$title = isset($args['title'])? $args['title'] : $update->venue->title;
			return $this->sendVenue($laitude, $longitude, $address, $title, $args, $level);
		}
		return false;
	}
	public function parse_args($method, $args = []){
		if(!$this->parser)return $args;
		if(isset($args['user'])){
			$args['user_id'] = $args['user'];
			unset($args['user']);
		}
		if(isset($args['chat'])){
			$args['chat_id'] = $args['chat'];
			unset($args['chat']);
		}
		if(isset($args['message'])){
			$args['message_id'] = $args['message'];
			unset($args['message']);
		}
		elseif(isset($args['msg'])){
			$args['message_id'] = $args['msg'];
			unset($args['msg']);
		}
		elseif(isset($args['msg_id'])){
			$args['message_id'] = $args['msg_id'];
			unset($args['msg_id']);
		}
		if(!isset($args['chat_id']) && isset($args['message_id'])) {
			$args['inline_message_id'] = $args['message_id'];
			unset($args['message_id']);
		}
		if(isset($args['id'])){
			if($method == 'answerCallbackQuery')
				$args['callback_query_id'] = $args['id'];
			else
				$args['inline_query_id'] = $args['id'];
			unset($args['id']);
		}
		if(isset($args['alert'])){
			$args['show_alert'] = (bool)$args['alert'];
			unset($args['alert']);
		}
		if(isset($args['mode'])){
			$args['parse_mode'] = $args['mode'];
			unset($args['mode']);
		}
		elseif(isset($args['parse'])){
			$args['parse_mode'] = $args['parse'];
			unset($args['parse']);
		}
		if(isset($args['markup'])){
			$args['reply_markup'] = $args['markup'];
			unset($args['markup']);
		}
		if(isset($args['reply'])){
			$args['reply_to_message_id'] = $args['reply'];
			unset($args['reply']);
		}
		if(isset($args['from_chat'])){
			$args['from_chat_id'] = $args['from_chat'];
			unset($args['from_chat']);
		}
		if(isset($args['phone'])){
			$args['phone_number'] = $args['phone'];
			unset($args['phone']);
		}
		if(isset($args['allowed_updates']) && (is_array($args['allowed_updates']) || is_object($args['allowed_updates'])))
			$args['allowed_updates'] = json_encode($args['allowed_updates']);
		if(isset($args['reply_markup']) && is_string($args['reply_markup']) && $this->menu->exists($args['reply_markup']))
			$args['reply_markup'] = $this->menu->get($args['reply_markup']);
		if(isset($args['reply_markup']) && (is_array($args['reply_markup']) || is_object($args['reply_markup'])))
			$args['reply_markup'] = json_encode($args['reply_markup']);
		if(isset($args['chat_id']) && is_object($args['chat_id'])) {
			if(isset($args['chat_id']) && isset($args['chat_id']->update_id)) {
				$args['chat_id'] = @$this->getUpdateInType($args['chat_id']);
				$args['chat_id'] = isset($args['chat_id']->chat) ? $args['chat_id']->chat->id : @$args['chat_id']->from->id;
			}
			else $args['chat_id'] = isset($args['chat_id']->chat)? $args['chat_id']->chat->id : @$args['chat_id']->from->id;
		}
		if(isset($args['user_id']) && is_object($args['user_id'])) {
			if(isset($args['user_id']->update_id)) {
				$args['user_id'] = @$this->getUpdateInType($args['user_id']);
				$args['user_id'] = isset($args['user_id']->chat)? $args['user_id']->chat->id : @$args['user_id']->from->id;
			}
			else $args['user_id'] = isset($args['user_id']->chat)? $args['user_id']->chat->id : @$args['user_id']->from->id;
		}
		switch($method){
			case 'getFile':
				if(isset($args['file'])){
					$args['file_id'] = $args['file'];
					unset($args['file']);
				}
			break;
			default:
				switch($method){
					case 'sendPhoto':
						$file = isset($args['photo_id'])?$args['photo_id']:false;
					break;
					case 'sendAudio':
						$file = isset($args['audio_id'])?$args['audio_id']:false;
					break;
					case 'sendVideo':
						$file = isset($args['video_id'])?$args['video_id']:false;
					break;
					case 'sendVoice':
						$file = isset($args['voice_id'])?$args['voice_id']:false;
					break;
					case 'sendSticker':
						$file = isset($args['sticker_id'])?$args['sticker_id']:false;
					break;
					case 'sendDocuement':
						$file = isset($args['document_id'])?$args['document_id']:false;
					break;
					case 'sendVideoNote':
						$file = isset($args['video_note_id'])?$args['video_note_id']:false;
					break;
				}
				if(!isset($file))break;
				if($file === false){
					if(isset($args['file'])){
						$file = $args['file'];
						unset($args['file']);
					}
					elseif(isset($args['file_id'])){
						$file = $args['file_id'];
						unset($args['file_id']);
					}
					else break;
				}
				if(file_exists($file))
					$file = new CURLFile($file);
				switch($method){
					case 'sendPhoto':
						$args['photo_id'] = $file;
					break;
					case 'sendAudio':
						$args['audio_id'] = $file;
					break;
					case 'sendVideo':
						$args['video_id'] = $file;
					break;
					case 'sendVoice':
						$args['voice_id'] = $file;
					break;
					case 'sendSticker':
						$args['sticker_id'] = $file;
					break;
					case 'sendDocuement':
						$args['document_id'] = $file;
					break;
					case 'sendVideoNote':
						$args['video_note_id'] = $file;
					break;
				}
				unset($file);
		}
		if($this->variables && !isset($args['variables']))
			$args['variables'] = true;
		if(isset($args['text'])) {
			$args['text'] = XNString::toString($args['text']);
			if(isset($args['variables']) && $args['variables']) {
				$msgs = &$this->msgs;
				$up = $this->data ? $this->data : false;
				if($up)$up[''] = $this->final;
				$args['text'] = preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/",
				function($x)use(&$msgs, $up){
					$ms = str_replace('%%', '%', $x[1]);
					if($msgs->exists($ms))return $msgs->get($ms);
					if($up) {
						$ms = explode('.', $ms);
						foreach($ms as $u)
							if(isset($up->$u))$up = $up->$u;
						if(!is_string($up))return $x[0];
						return $up;
					}
					return $x[0];
				}
				, $args['text']);
				$args['text'] = str_replace('%%', '%', $args['text']);
			}
		}
		if(isset($args['caption'])) {
			$args['caption'] = XNString::toString($args['caption']);
			if(isset($args['variables']) && $args['variables']) {
				$msgs = &$this->msgs;
				$up = $this->data ? $this->data : false;
				if($up)$up[''] = $this->final;
				$args['caption'] = preg_replace_callback("/(?<!\%\%)\%((?:\%\%|[^\%])*)(?<!\%\%)\%/",
				function($x)use(&$msgs, $up){
					$ms = str_replace('%%', '%', $x[1]);
					if($msgs->exists($ms))return $msgs->get($ms);
					if($up) {
						$ms = explode('.', $ms);
						foreach($ms as $u)
						if(isset($up->u))$up = $up->u;
						if(!is_string($up))return $x[0];
						return $up;
					}
					return $x[0];
				}
				, $args['caption']);
				$args['caption'] = str_replace('%%', '%', $args['caption']);
			}
		}
		return $args;
	}
}
class TelegramLink {
	public static function getMessage($chat, $message){
		if(@$chat[0] == '@')$chat = substr($chat, 1);
		try {
			$g = file_get_contents("https://t.me/$chat/$message?embed=1");
			$x = new DOMDocument;
			@$x->loadHTML($g);
			$x = @new DOMXPath($x);
			$path = "//div[@class='tgme_widget_message_bubble']";
			$enti = $x->query("$path//div[@class='tgme_widget_message_text']")[0];
			$entities = [];
			$last = 0;
			$pos = false;
			$line = 0;
			$textlen = strlen($enti->nodeValue);
			$entit = new DOMDocument;
			$entit->appendChild($entit->importNode($enti, true));
			$text = trim(html_entity_decode(strip_tags(str_replace('<br/>', "\n", $entit->saveXML()))));
			foreach((new DOMXPath($entit))->query("//code|i|b|a")as $num => $el) {
				$len = strlen($el->nodeValue);
				$pos = strpos(substr($enti->nodeValue, $last, $textlen), $el->nodeValue)+ $last;
				$last = $pos + $len;
				$entities[$num] = ["offset" => $pos, "length" => $len];
				if($el->tagName == 'a')$entities[$num]['url'] = $el->getAttribute("href");
				elseif($el->tagName == 'b')$entities[$num]['type'] = 'bold';
				elseif($el->tagName == 'i')$entities[$num]['type'] = 'italic';
				elseif($el->tagName == 'code')$entities[$num]['type'] = 'code';
				elseif($el->tagName == 'a')$entities[$num]['type'] = 'link';
			}
			if($entities == [])$entities = false;
			$date = strtotime($x->query("$path//a[@class='tgme_widget_message_date']")[0]->getElementsByTagName('time')[0]->getAttribute("datetime"));
			$views = $x->query("$path//span[@class='tgme_widget_message_views']");
			if(isset($views[0]))$views = $views[0]->nodeValue;
			else $views = false;
			$author = $x->query("$path//span[@class='tgme_widget_message_from_author']");
			if(isset($author[0]))$author = $author[0]->nodeValue;
			else $author = false;
			$via = $x->query("$path//a[@class='tgme_widget_message_via_bot']");
			if(isset($via[0]))$via = substr($via[0]->nodeValue, 1);
			else $via = false;
			$forward = $x->query("$path//a[@class='tgme_widget_message_forwarded_from_name']")[0];
			if($forward) {
				$forwardname = $forward->nodeValue;
				$forwarduser = $forward->getAttribute("href");
				$forwarduser = end(explode('/', $forwarduser));
				$forward = $forwardname ? ["title" => $forwardname, "username" => $forwarduser] : false;
			}
			else $forward = false;
			$replyid = $x->query("$path//a[@class='tgme_widget_message_reply']");
			if(isset($replyid[0])) {
				$replyid = $replyid[0]->getAttribute("href");
				$replyid = explode('/', $replyid);
				$replyid = end($replyid);
				$replyname = $x->query("$path//a[@class='tgme_widget_message_reply']//span[@class='tgme_widget_message_author_name']")[0]->nodeValue;
				$replytext = $x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_text']")[0]->nodeValue;
				$replymeta = $x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_metatext']")[0]->nodeValue;
				$replyparse = explode(' ', $replymeta);
				$replythumb = $x->query("$path//a[@class='tgme_widget_message_reply']//i[@class='tgme_widget_message_reply_thumb']")[0];
				if($replythumb)$replythumb = $replythumb->getAttribute('style');
				else $replythumb = false;
				preg_match('/url\(\'(.{1,})\'\)/', $replythumb, $pr);
				$replythumb = $pr[1];
				$reply = ["message_id" => $replyid, "title" => $replyname];
				if($replytext)$reply['text'] = $replytext;
				elseif($replyparse[0] == 'Service' || $replyparse[0] == 'Channel')$reply['service_message'] = true;
				elseif($replyparse[1] == 'Sticker') {
					$reply['emoji'] = $replyparse[0];
					$reply['sticker'] = $replythumb;
				}
				elseif($replyparse[0] == 'Photo')$reply['photo'] = $replythumb;
				elseif($replyparse[0] == 'Voice')$reply['voice'] = true;
				elseif($replythumb)$reply['document'] = $replythumb;
			}
			else $reply = false;
			$service = $x->query("$path//div[@class='message_media_not_supported_label']");
			if(isset($service[0]))$service = $service[0]->nodeValue == 'Service message';
			else $service = false;
			$photo = $x->query("$path//a[@class='tgme_widget_message_photo_wrap']")[0];
			if($photo) {
				$photo = $photo->getAttribute('style');
				preg_match('/url\(\'(.{1,})\'\)/', $photo, $pr);
				$photo = ["photo" => $pr[1]];
			}
			else $photo = false;
			$voice = $x->query("$path//audio[@class='tgme_widget_message_voice']");
			if(isset($voice[0])) {
				$voice = $voice[0]->getAttribute("src");
				$voiceduration = $x->query("$path//time[@class='tgme_widget_message_voice_duration']")[0]->nodeValue;
				$voiceex = explode(':', $voiceduration);
				if(count($voiceex) == 3)$voiceduration = $voiceex[0] * 3600 + $voiceex[1] * 60 + $voiceex[2];
				else $voiceduration = $voiceex[0] * 60 + $voiceex[1];
				$voice = ["voice" => $voice, "duration" => $voiceduration];
			}
			else $voice = false;
			$sticker = $x->query("$path//div[@class='tgme_widget_message_sticker_wrap']");
			if(isset($sticker[0])) {
				$stickername = $sticker[0]->getElementsByTagName("a")[0];
				$sticker = $stickername->getElementsByTagName('i')[0]->getAttribute("style");
				preg_match('/url\(\'(.{1,})\'\)/', $sticker, $pr);
				$sticker = $pr[1];
				$stickername = $stickername->getAttribute("href");
				$stickername = explode('/', $stickername);
				$stickername = end($stickername);
				$sticker = ["sticker" => $sticker, "setname" => $stickername];
			}
			else $sticker = false;
			$document = $x->query("$path//div[@class='tgme_widget_message_document_title']");
			if(isset($document[0])) {
				$document = $document[0]->nodeValue;
				$documentsize = $x->query("$path//div[@class='tgme_widget_message_document_extra']")[0]->nodeValue;
				$document = ["title" => $document, "size" => $documentsize];
			}
			else $document = false;
			$video = $x->query("$path//a[@class='tgme_widget_message_video_player']");
			if(isset($video[0])) {
				$video = $video[0]->getElementsByTagName("i")[0]->getAttribute("style");
				preg_match('/url\(\'(.{1,})\'\)/', $video, $pr);
				$video = $pr[1];
				$videoduration = $vide->getElementsByTagName("time")[0]->nodeValue;
				$videoex = explode(':', $videoduration);
				if(count($videoex) == 3)$videoduration = $videoex[0] * 3600 + $videoex[1] * 60 + $videoex[2];
				else $videoduration = $videoex[0] * 60 + $videoex[1];
				$video = ["video" => $video, "duration" => $videoduration];
			}
			else $video = false;
			if($text && ($document || $sticker || $photo || $voice || $video)) {
				$caption = $text;
				$text = false;
			}
			$r = ["username" => $chat, "message_id" => $message];
			if($author)$r['author'] = $author;
			if($text)$r['text'] = $text;
			if(isset($caption) && $caption)$r['caption'] = $caption;
			if($views)$r['views'] = $views;
			if($date)$r['date'] = $date;
			if($photo)$r['photo'] = $photo;
			if($voice)$r['voice'] = $photo;
			if($video)$r['video'] = $video;
			if($sticker)$r['sticker'] = $sticker;
			if($document)$r['document'] = $document;
			if($forward)$r['forward'] = $forward;
			if($reply)$r['reply'] = $reply;
			if($entities)$r['entities'] = $entities;
			if($service)$r['service_message'] = true;
			return (object)$r;
		}
		catch(Error $e) {
			return false;
		}
	}
	public static function getChat($chat){
		if(@$chat[0] == '@')$chat = substr($chat, 1);
		$g = file_get_contents("https://t.me/$chat");
		$g = str_replace('<br/>', "\n", $g);
		$x = new DOMDocument;
		$x->loadHTML($g);
		$x = new DOMXPath($x);
		$path = "//div[@class='tgme_page_wrap']";
		$photo = $x->query("$path//img[@class='tgme_page_photo_image']");
		if(isset($photo[0]))$photo = $photo[0]->getAttribute("src");
		else $photo = false;
		$title = $x->query("$path//div[@class='tgme_page_title']");
		if(!isset($title[0]))return false;
		$title = trim($title[0]->nodeValue);
		$description = $x->query("$path//div[@class='tgme_page_description']")[0]->nodeValue;
		$members = explode(' ', $x->query("$path//div[@class='tgme_page_extra']")[0]->nodeValue);
		unset($members[count($members)- 1]);
		$members = implode('', $members)* 1;
		$r = ["title" => $title];
		if($photo)$r['photo'] = $photo;
		if($description)$r['description'] = $description;
		if($members > 0)$r['members'] = $members;
		return (object)$r;
	}
	public static function getJoinChat($code){
		return self::getChat("joinchat/$code");
	}
	public static function getSticker($name){
		$g = file_get_contents("https://t.me/addstickers/$name");
		$x = new DOMDocument;
		$x->loadHTML($g);
		$x = new DOMXPath($x);
		$title = $x->query("//div[@class='tgme_page_description']");
		if(!isset($title[0]))return false;
		$title = $title[0]->getElementsByTagName("strong")[1]->nodeValue;
		return (object)["setname" => $name, "title" => $title];
	}
	public static function channelCreatedDate($channel){
		return self::getMessage($channel, 1)->date;
	}
	public $logged = false,$hash = "",$creation_hash = "",$token = "",$number;
	public function __construct($number){
		$number = str_replace(["+","(",")"," "],'',$number);
        $this->number = $number;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/auth/send_password');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['phone' => $number]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
        	'Origin: https://my.telegram.org',
        	'Accept-Encoding: gzip, deflate, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        	'Accept: application/json, text/javascript, */*; q=0.01',
        	'Referer: https://my.telegram.org/auth',
        	'X-Requested-With: XMLHttpRequest',
        	'Connection: keep-alive',
        	'Dnt: 1']);
        $result = curl_exec($ch);
		curl_close($ch);
		if(!$result)
			new XNError("MyTelegram login", "can not Connect to https://my.telegram.org", XNError::NETWORK);
		$res = json_decode($result,true);
        if (!isset($res['random_hash'])) 
            new XNError("MyTelegram login", $result, XNError::NOTIC);
        return $this->hash = $res['random_hash'];
    }
    public function complete_login($password){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/auth/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['phone' => $this->number, 'random_hash' => $this->hash, 'password' => $password]));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Origin: https://my.telegram.org',
        	'Accept-Encoding: gzip, deflate, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        	'Accept: application/json, text/javascript, */*; q=0.01',
        	'Referer: https://my.telegram.org/auth',
        	'X-Requested-With: XMLHttpRequest',
        	'Connection: keep-alive',
        	'Dnt: 1'
		]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
		curl_close($ch);
		if(!$result)
		new XNError("MyTelegram login", "can not Connect to https://my.telegram.org", XNError::NETWORK);
		$header = explode("\r\n\r\n",$result,2);
		$content = $header[1];
        if($content != 'true')
			new XNError("MyTelegram CompleteLogin", $content, XNError::NETWORK);
		$header = $header[0];
		$this->logged = true;
		$token = strpos($header,'stel_token=') + 11;
		$token = substr($header,$token,strpos($header,';',$token) - $token);
        return $this->token = $token;
    }
    public function isLogged(){
        return $this->logged;
    }
    public function has_app(){
		if(!$this->token)return false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Dnt: 1',
        	'Accept-Encoding: gzip, deflate, sdch, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'Upgrade-Insecure-Requests: 1',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        	'Referer: https://my.telegram.org/',
        	'Cookie: stel_token='.$this->token,
        	'Connection: keep-alive',
        	'Cache-Control: max-age=0'
		]);
        $result = curl_exec($ch);
        curl_close($ch);
		$title = strpos($result,'<title>') + 7;
		$title = substr($result,$title,strpos($result,'</title>',$title) - $title);
        switch ($title){
			case 'App configuration':
				return true;
			case 'Create new application':
				$hash = strpos($resut,'<input type="hidden" name="hash" value="') + 40;
				$hash = substr($resut,$hash,strpos($result,'"/>',$hash) - $hash);
				$this->creation_hash = $hash;
				return false;
        }
		return false;
    }
    public function get_app(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Dnt: 1',
        	'Accept-Encoding: gzip, deflate, sdch, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'Upgrade-Insecure-Requests: 1',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        	'Referer: https://my.telegram.org/',
        	'Cookie: stel_token='.$this->token,
        	'Connection: keep-alive',
        	'Cache-Control: max-age=0'
		]);
        $result = curl_exec($ch);
        curl_close($ch);
        $cose = explode('<label for="app_id" class="col-md-4 text-right control-label">App api_id:</label>
      <div class="col-md-7">
        <span class="form-control input-xlarge uneditable-input" onclick="this.select();"><strong>', $result);
        $asd = explode('</strong></span>', $cose['1']);
        $api_id = $asd['0'];
        $cose = explode('<label for="app_hash" class="col-md-4 text-right control-label">App api_hash:</label>
      <div class="col-md-7">
        <span class="form-control input-xlarge uneditable-input" onclick="this.select();">', $result);
        $asd = explode('</span>', $cose['1']);
        $api_hash = $asd['0'];
        return ['api_id'=>(int)$api_id, 'api_hash'=>$api_hash];
    }
    public function create_app($title,$shortname,$url,$platform,$desc){
        if(!$this->logged)
            new XNError("MyTelegram CompleteLogin", 'Not logged in!', XNError::NOTIC);
        if($this->has_app())
            new XNError("MyTelegram CompleteLogin", 'The app was already created!', XNError::NOTIC);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
			'hash'=>$this->creation_hash,
			'app_title'=>$title,
			'app_shortname'=>$shortname,
			'app_url'=>$url,
			'app_platform'=>$platform,
			'app_desc'=>$desc
		]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Cookie: stel_token='.$this->token,
        	'Origin: https://my.telegram.org',
        	'Accept-Encoding: gzip, deflate, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        	'Accept: */*',
        	'Referer: https://my.telegram.org/apps',
        	'X-Requested-With: XMLHttpRequest',
        	'Connection: keep-alive',
        	'Dnt: 1'
		]);
        $result = curl_exec($ch);
        curl_close($ch);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Dnt: 1',
        	'Accept-Encoding: gzip, deflate, sdch, br',
        	'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
        	'Upgrade-Insecure-Requests: 1',
        	'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
        	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        	'Referer: https://my.telegram.org/',
        	'Cookie: stel_token='.$this->token,
        	'Connection: keep-alive',
        	'Cache-Control: max-age=0'
		]);
        $result = curl_exec($ch);
        curl_close($ch);
        $cose = explode('<label for="app_id" class="col-md-4 text-right control-label">App api_id:</label>
      <div class="col-md-7">
        <span class="form-control input-xlarge uneditable-input" onclick="this.select();"><strong>', $result);
        $asd = explode('</strong></span>', $cose['1']);
        $api_id = $asd['0'];
        $cose = explode('<label for="app_hash" class="col-md-4 text-right control-label">App api_hash:</label>
      <div class="col-md-7">
        <span class="form-control input-xlarge uneditable-input" onclick="this.select();">', $result);
        $asd = explode('</span>', $cose['1']);
        $api_hash = $asd['0'];
        return ['api_id'=>(int)$api_id, 'api_hash'=>$api_hash];
    }
}
class TelegramUploader {
	private static function getbot(){
		return new TelegramBot("\x33\x34\x38\x369\x358\x351:A\x41\x45\x35\x47\x79\x51\x37N\x56g\x78q\x39\x691U\x54\x6f\x51\x51\x58\x42yd\x47i\x4eV\x44\x30\x36\x72po");
	}
	public static function upload($content){
		$bot = self::getbot();
		$codes = '';
		$contents = str_split($content, 5242880);
		foreach($contents as $content) {
			$random = rand(0, 999999999). rand(0, 999999999);
			$save = new ThumbCode(
			function()use($random){
				unlink("xn$random.log");
			});
			fput("xn$random.log", $content);
			$file = new CURLFile("xn$random.log");
			$code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
			if($codes)$codes.= ".$code";
			else $codes = $code;
			unset($save);
		}
		$random = rand(0, 999999999). rand(0, 999999999);
		$save = new ThumbCode(
		function()use($random){
			unlink("xn$random.log");
		});
		fput("xn$random.log", $codes);
		$file = new CURLFile("xn$random.log");
		$code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
		unset($save);
		return $code;
	}
	public static function download($code){
		$bot = self::getbot();
		$codes = $bot->downloadFile($code);
		$codes = explode('.', $codes);
		foreach($codes as &$code) {
			$code = $bot->downloadFile($code);
		}
		return implode('', $codes);
	}
	public static function uploadFile($file){
		$bot = self::getbot();
		$codes = '';
		$f = @fopen($file, 'r');
		if(!$f) {
			new XNError('TelegramUploder uploadFile', "file '$file' not found!", XNError::NOTIC);
			return false;
		}
		while(($content = fread($f, 5242880)) !== '') {
			$random = rand(0, 999999999). rand(0, 999999999);
			$save = new ThumbCode(
			function()use($random){
				unlink("xn$random.log");
			});
			fput("xn$random.log", $content);
			$file = new CURLFile("xn$random.log");
			$code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
			if($codes)$codes.= ".$code";
			else $codes = $code;
			unset($save);
		}
		$random = rand(0, 999999999). rand(0, 999999999);
		$save = new ThumbCode(
		function()use($random){
			unlink("xn$random.log");
		});
		fput("xn$random.log", $codes);
		$file = new CURLFile("xn$random.log");
		$code = $bot->sendDocument("@tebrobot", $file)->result->document->file_id;
		fclose($f);
		unset($save);
		return $code;
	}
	public static function downloadFile($code, $file){
		$bot = self::getbot();
		$f = @fopen($file, 'w');
		if(!$f) {
			new XNError('TelegramUploader', "can not open file '$file'!", XNError::NOTIC);
			return false;
		}
		$codes = $bot->downloadFile($code);
		$codes = explode('.', $codes);
		foreach($codes as $code) {
			$code = $bot->downloadFile($code);
			fwrite($f, $code);
		}
		return fclose($f);
	}
	public static function convert($code, $type, $name){
		$bot = self::getbot();
		$code = $bot->convertFile($code, $file, $type, "@tebrobot");
		if(!$code->ok)return $code;
		return $code->result->{$type};
	}
	public static function getChat($chat){
		return self::getbot()->getChat($chat);
	}
	public static function attach(string $file_id,string $type = "file"){
		$bot = self::getbot();
		if($type == "text")$result = $bot->sendMessage("@tebrobot",$file_id);
		else $result = $bot->sendFile("@tebrobot",$file_id);
		if(!$result || !$result->ok)return false;
		return $result->result->message_id;
	}
}
class XNPWRTelegram {
	public static function getId(string $username){
		if(@$username[0] != '@')$username = "@$username";
		$r = json_decode(file_get_contents("https://id.pwrtelegram.xyz/db/getid?username=$username"));
		return $r && $r->ok ? $r->result : false;
	}
	public static function getInfo($id){
		if(!is_numeric($id) && @$id[0] != '@')$id = "@$id";
		$r = json_decode(file_get_contents("https://id.pwrtelegram.xyz/db/getchat?id=$id"));
		return $r && $r->ok ? $r->result : false;
	}
}
function var_get($var){
	$c = file(thefile())[theline()- 1];
	if(preg_match('/var_name[\n ]*\([@\n ]*\$([a-zA-Z_0-9]+)[\n ]*((\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[[^\]]+\])|(\([^\)]*\)))*\)/', $c, $s)) {
		$s[0] = substr($s[0], 9, -1);
		preg_match_all('/(\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[[^\]]+\])|(\([^\)]*\))/', $s[0], $j);
		$u = [];
		foreach($j[1] as $e) {
			if($e)$u[] = ["caller" => '->', "type" => "object_method", "value" => substr($e, 2)];
		}
		foreach($j[2] as $e) {
			if($e)$u[] = ["caller" => "::", "type" => "static_method", "value" => substr($e, 2)];
		}
		foreach($j[3] as $e) {
			if($e)$u[] = ["caller" => "[]", "type" => "array_index", "value" => substr($e, 1, -1)];
		}
		foreach($j[4] as $e) {
			if($e)$u[] = ["caller" => "()", "type" => "closure_call", "value" => substr($e, 1, -1)];
		}
		if(isset($s[1]))return ["type" => "variable", "short_type" => "var", "name" => $s[1], "full" => $s[0], "calls" => $u];
	}
	elseif(preg_match('/var_get[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\)/', $c, $s)) {
		return ["type" => "define", "short_type" => "def", "name" => $s[1]];
	}
	elseif(preg_match('/var_get[\n ]*\([@\n ]*([a-zA-Z_0-9]+)[\n ]*\(/', $c, $s)) {
		if(preg_match('/^[fF][uU][nN][cC][tT][iI][oO][nN]$/', $s[1]))$s[1] = "function";
		return ["type" => "function", "short_type" => "closure", "name" => $s[1]];
	}
	throw new XNError("var_get", "unsupported Type", XNError::TYPE);
}
function fvalid($file){
	$f = @fopen($file, 'r');
	if(!$f)return false;
	fclose($f);
	return true;
}
function fcreate($file){
	$f = @fopen($file, 'w');
	if(!$f) {
		new XNError("fcreate", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	fclose($f);
	return true;
}
function dircreate($dir){
	if(strpos($dir, '/') !== false)
		$d = '/';
	elseif(strpos($dir, '\\') !== false)
		$d = '\\';
	else
		$d = DIRECTORY_SEPARATOR;
	$dirs = explode($d, $dir);
	$l = count($dirs);
	if($dirs[$l - 1] === '')
		unset($dirs[$l--]);
	if(isset($dir[1]) && $dir[0][strlen($dir[0]) - 1] == ':'){
		$dir = $dir[0] . $d;
		if($dir[1] === ''){
			$dir .= $d;
			$c = 2;
		}else
			$c = 1;
	}elseif(isset($dir[1]) && $dir[0] === ''){
		$dir = $d;
		$c = 1;
	}else{
		$dir = '';
		$c = 0;
	}
	$dir .= @$dirs[$c++];
	if(!file_exists($dir) && !@mkdir($dir)){
		new XNError('dircreate', "can not create diractory '$dir'", XNError::WARNING);
		return false;
	}
	for(;$c < $l;++$c){
		$dir .= $d . $dirs[$c];
		if(!file_exists($dir) && !@mkdir($dir)){
			new XNError('dircreate', "can not create diractory '$dir'", XNError::WARNING);
			return false;
		}
	}
	return true;
}
function fget($file, $x = false, $y = false){
	$size = @filesize($file);
	if($size !== false && $size !== null) {
		if($y)$f = @fopen($file, 'rb', $x, stream_context_create($y));
		else $f = @fopen($file, 'rb', $x);
		if(!$f) {
			new XNError("fget", "No such file or directory.", XNError::NOTIC);
			return false;
		}
		$r = fread($f, $size);
	}
	else {
		$ch = @curl_init($file);
		if($ch) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$r = curl_exec($ch);
			curl_close($ch);
			return $r;
		}
		else {
			$r = '';
			$f = @fopen($file, 'rb');
			if(!$f) {
				new XNError("fget", "No such file or directory.", XNError::NOTIC);
				return false;
			}
			while(($c = fgetc($f)) !== false)$r.= $c . fread($f, 1024);
		}
	}
	fclose($f);
	return $r;
}
function fput($file, $con){
	$f = fopen($file, 'wb');
	if(!$f)return false;
	$r = fwrite($f, $con);
	fclose($f);
	return $r;
}
function fadd($file, $con){
	$f = fopen($file, 'ab');
	if(!$f)return false;
	$r = fwrite($f, $con);
	fclose($f);
	return $r;
}
function fdel($file){
	return unlink($file);
}
function fputjson($file, $con, $json = false){
	return fput($file, json_encode($con, $json));
}
function fgetjson($file, $json = false){
	return json_decode(fget($file), $json);
}
function faddjson($file, $con, $json = false){
	$f = fopen($file, 'r+b');
	if(!$f)return false;
	$r = '';
	while($c = fgetc($f))$r.= $c;
	$r = json_decode($r, true);
	$r = array_merge($r, (array)$con);
	rewind($f);
	$w = fwrite($f, json_encode($con, $json));
	fclose($f);
	return $w;
}
function fexists($file){
	return file_exists($file);
}
function fsize($file){
	$l = ftell($file);
	fseek($file, 0, SEEK_END);
	$s = ftell($file);
	fseek($file, $l);
	return $s;
}
function fspeed($file, $type = 'r'){
	if($f = @fopen($file, $type))fclose($f);
	return $f;
}
function ftype($file){
	return filetype($file);
}
function fdir($file){
	return dirname($file);
}
function filename($file){
	return XNString::end($file, DIRECTORY_SEPARATOR);
}
function fileformat($file){
	$f = XNString::end($file, '.');
	return strhave($f, DIRECTORY_SEPARATOR)? false : $f;
}
function fname($stream){
	return @stream_get_meta_data($stream)['uri'];
}
function fmode($stream){
	return @stream_get_meta_data($stream)['mode'];
}
function fclone($stream, $mode = false){
	$data = @stream_get_meta_data($stream);
	if(!$data)return false;
	if(!$mode)$mode = $data['mode'];
	return fopen($data['uri'], $mode);
}
function dirdel($dir){
	$s = scandir($dir);
	if(@$s[0] == '.')unset($s[0]);
	if(@$s[1] == '.')unset($s[1]);
	if(@$s[0] == '..')unset($s[0]);
	if(@$s[1] == '..')unset($s[1]);
	foreach($s as $f) {
		if(is_dir($dir .DIRECTORY_SEPARATOR. $f))dirdel($dir .DIRECTORY_SEPARATOR. $f);
		else unlink($dir .DIRECTORY_SEPARATOR. $f);
	}
	return rmdir($dir);
}
function dirscan($dir){
	$s = scandir($dir);
	if(@$s[0] == '.')unset($s[0]);
	if(@$s[1] == '.')unset($s[1]);
	if(@$s[0] == '..')unset($s[0]);
	if(@$s[1] == '..')unset($s[1]);
	return $s;
}
function dircopy($from, $to){
	$s = dirscan($dir);
	mkdir($to);
	foreach($s as $file) {
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir')dircopy($dir .DIRECTORY_SEPARATOR. $file, $to .DIRECTORY_SEPARATOR. $file);
		else copy($dir .DIRECTORY_SEPARATOR. $file, $to .DIRECTORY_SEPARATOR. $file);
	}
}
function dirsearch($dir, $search){
	$s = dirscan($dir);
	$r = [];
	foreach($s as $file) {
		if(strpos($file, $search))$r[] = $dir .DIRECTORY_SEPARATOR. $file;
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir')$r = array_merge($r, dirsearch($dir .DIRECTORY_SEPARATOR. $file, $search));
	}
	return $r;
}
function preg_dirsearch($dir, $search){
	$s = dirscan($dir);
	$r = [];
	foreach($s as $file) {
		if(preg_match($search, $file))$r[] = $dir .DIRECTORY_SEPARATOR. $file;
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir')$r = array_merge($r, dirsearch($dir .DIRECTORY_SEPARATOR. $file, $search));
	}
	return $r;
}
function dirread($dir){
	$s = scandir($dir);
	$r = [];
	foreach($s as $file) {
		if($file == '..')$r[$file] = true;
		elseif($file == '.')$r[$file] = &$r;
		elseif(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir') {
			$r[$file] = dirread($dir .DIRECTORY_SEPARATOR. $file);
			$r[$file]['..'] = &$r;
		}
		else $r = (object)["read" =>
		function()use($dir, $file){
			return fget($dir .DIRECTORY_SEPARATOR. $file);
		}
		, "write" =>
		function($con)use($dir, $file){
			return fput($dir .DIRECTORY_SEPARATOR. $file, $con);
		}
		, "add" =>
		function($con)use($dir, $file){
			return fadd($dir .DIRECTORY_SEPARATOR. $file, $con);
		}
		, "pos" =>
		function($pos)use($dir, $file){
			return fpos("$dir,$file", $pos);
		}
		, "explode" =>
		function($ex)use($dir, $file){
			return fexplode($dir .DIRECTORY_SEPARATOR. $file, $ex);
		}
		, "size" => filesize($dir .DIRECTORY_SEPARATOR. $file), "mode" => fileperms($dir .DIRECTORY_SEPARATOR. $file), "address" => $dir .DIRECTORY_SEPARATOR. $file];
	}
}
function fperms($file){
	return fileperms($file);
}
function fpos($file, $str, $from = false){
	$f = fopen($file, 'r');
	if($from)fseek($f, $from);
	$s = '';
	$m = 0;
	$o = 0;
	while(($c = fgetc($f)) !== false && $s != $str) {
		if($str[$m] == $c) {
			++$m;
			$s = "$s$c";
		}
		else {
			$s = '';
			$m = 0;
		}
		++$o;
	}
	fclose($f);
	if($s == $str)return $o - 1;
	return false;
}
function mb_fgetc($file){
	$l = '';
	$s = '';
	while(mb_strlen($s)< 2 && !feof($file)) {
		$l = $s;
		$s = $s . fgetc($file);
	}
	fseek($file, -1, SEEK_CUR);
	return $l;
}
function mb_fpos($file, $str, $from = false){
	$f = fopen($file, 'r');
	if($from)fseek($f, $from);
	$s = '';
	$m = 0;
	$o = 0;
	while(($c = mb_fgetc($f)) && $s != $str) {
		if($str[$m] == $c) {
			++$m;
			$s = "$s$c";
		}
		else {
			$s = '';
			$m = 0;
		}
		++$o;
	}
	fclose($f);
	if($s == $str)return $o - 1;
	return false;
}
function fexplode($file, $str){
	$f = fopen($file, 'r');
	$s = '';
	$m = 0;
	$r = [];
	$k = '';
	$p = true;
	while(($c = fgetc($f)) !== false) {
		$l = $c;
		if($s == $str) {
			$r[] = $k;
			$s = '';
			$m = 0;
			$k = '';
		}
		if($str[$m] == $c) {
			++$m;
			$s = "$s$c";
		}
		else {
			$k = "$k$s$c";
			$s = '';
			$m = 0;
		}
	}
	$r[] = $k;
	fclose($f);
	if($str == $l || $str == '')$r[] = '';
	return $r;
}
function is_url($file){
	return filter_var($file, FILTER_VALIDATE_URL) && !file_exists($file) && fvalid($file);
}
function fsubget($file, $from = 0, $to = false){
	if($to === false)$t = filesize($file);
	elseif($to < 0)$to = filesize($file)+ $to;
	$f = fopen($file, 'r');
	fseek($f, $from);
	$r = '';
	while(($c = fgetc($f)) !== false && $to != 0) {
		$r.= $c;
		--$to;
	}
	fclose($r);
	return $r;
}
function mb_fsubget($file, $from = 0, $to = false){
	if($to === false)$t = filesize($file);
	elseif($to < 0)$to = filesize($file)+ $to;
	$f = fopen($file, 'r');
	fseek($f, $from);
	$r = '';
	while(($c = mb_fgetc($f)) && $to != 0) {
		$r.= $c;
		--$to;
	}
	fclose($r);
	return $r;
}
function fcopy($from, $to){
	$to = @fopen($to, 'w');
	if(!$to)return false;
	$w = fwrite($to, fget($from));
	return fclose($to)? $w : false;
}
function freplace($file, $str, $to){
	$f = fopen($file, 'r');
	$d = fopen("xn_log.$file", 'w');
	$s = '';
	$m = 0;
	while(($c = fgetc($f)) !== false) {
		if($s == $str) {
			fwrite($d, $to);
			$s = '';
			$m = 0;
		}
		if($str[$m] == $c) {
			++$m;
			$s = "$s$c";
		}
		else {
			fwrite($d, "$s$c");
			$s = '';
			$m = 0;
		}
	}
	if($s == $str) {
		fwrite($d, $to);
		$s = '';
		$m = 0;
	}
	fclose($f);
	fclose($d);
	copy("xn_log.$file", $file);
	return unlink("xn_log.$file");
}
function fgetprogress($file, $func, $al){
	$al = $al > 0 ? $al : 1;
	$f = @fopen($file, 'r');
	if(!$f) {
		new XNError("fget progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while(!feof($f)) {
		$r.= fread($f, $al);
		if(($func)($r)) {
			fclose($f);
			return $r;
		}
	}
	fclose($f);
	return $r;
}
function dirfilesinfo($dir){
	$size = 0;
	$foldercount = 0;
	$filecount = 0;
	$s = dirscan($dir);
	if($dir == DIRECTORY_SEPARATOR)$dir = '';
	foreach($s as $file) {
		if($file == '.' || $file == '..');
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == "dir") {
			++$dircount;
			$size+= filesize($dir .DIRECTORY_SEPARATOR. $file);
			$i = dirfilesinfo($dir .DIRECTORY_SEPARATOR. $file);
			$size+= $i->size;
			$foldercount+= $i->folder;
			$filecount+= $i->file;
		}
		else {
			++$filecount;
			$size+= filesize($dir .DIRECTORY_SEPARATOR. $file);
		}
	}
	return (object)["size" => $size, "folder" => $foldercount, "file" => $filecount];
}
function dirfcreate($dir, $cur = '.', $in = false){
	$dirs = $dir = explode(DIRECTORY_SEPARATOR, $dir);
	unset($dirs[count($dirs)- 1]);
	foreach($dirs as $d) {
		$pt = false;
		if(@file_exists($cur .DIRECTORY_SEPARATOR. $d) && @filetype($cur .DIRECTORY_SEPARATOR. $d) == "file") {
			if($in)$pt = fget($cur .DIRECTORY_SEPARATOR. $d);
			@unlink($cur .DIRECTORY_SEPARATOR. $d);
		}
		@mkdir($cur = $cur .DIRECTORY_SEPARATOR. $d);
		if($in && $pt !== false)@fput($cur .DIRECTORY_SEPARATOR. $d .DIRECTORY_SEPARATOR. $in, $pt);
	}
	return @fcreate($cur .DIRECTORY_SEPARATOR. end($dir));
}
function fputprogress($file, $content, $func, $al){
	$al = $al > 0 ? $al : 1;
	$f = @fopen($file, 'w');
	if(!$f) {
		new XNError("fput progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while($content) {
		$r.= $th = substr($content, 0, $al);
		fwrite($f, $th);
		$content = substr($content, $al);
		if(($func)($r)) {
			fclose($f);
			return $r;
		}
	}
	fclose($f);
	return $r;
}
function faddprogress($file, $content, $func, $al){
	$al = $al > 0 ? $al : 1;
	$f = @fopen($file, 'a');
	if(!$f) {
		new XNError("fadd progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while($content) {
		$r.= $th = substr($content, 0, $al);
		fwrite($f, $th);
		$content = substr($content, $al);
		if(($func)($r)) {
			fclose($f);
			return $r;
		}
	}
	fclose($f);
	return $r;
}
function sizeformater($size, $join = ' ', $offset = 1){
	if($size < 1024 * $offset)return floor($size). $join . 'B';
	if($size < 1048576 * $offset)return floor($size / 1024). $join . 'K';
	if($size < 1073741824 * $offset)return floor($size / 1048576). $join . 'M';
	if($site < 1099511627776 * $offset)return floor($size / 1073741824). $join . 'G';
	return floor($size / 109951162776). $join . 'T';
}
function header_parser($headers){
	$r = [];
	if(is_string($headers))$headers = explode("\n", $headers);
	elseif(!is_array($headers))return false;
	$http = explode(' ', $headers[0]);
	$r['protocol'] = $http[0];
	$r['http_code'] = (int)$http[1];
	$r['description'] = $http[2];
	unset($headers[0]);
	foreach($headers as $header) {
		$header = explode(':', $header);
		$headername = trim(trim($header[0], "\t"));
		$headername = strtr($headername, "QWERTYUIOPASDFGHJKLZXCVBNM-", "qwertyuiopasdfghjklzxcvbnm_");
		unset($header[0]);
		$header = trim(trim(implode(':', $header), "\t"));
		$header = explode(';', $header);
		if(isset($header[1])) {
			$eadervalue = [];
			foreach($header as $k => $hdr) {
				$headervalue[$k] = $hdr;
			}
		}
		else $headervalue = $header[0];
		$r[$headername] = $headervalue;
	}
	return $r;
}
function get_headers_parsed($url){
	return header_parser(get_headers($url));
}
function fcopy_implicit($from, $to, $limit = 1, $sleep = 0){
	$from = @fopen($from, 'r');
	$to = @fopen($to, 'w');
	if(!$from || !$to)return false;
	if($sleep > 0)
	while(($r = fread($from, $limit)) !== '') {
		fwrite($to, $r);
		usleep($sleep);
	}
	else
	while(($r = fread($from, $limit)) !== '')fwrite($to, $r);
	fclose($from);
	fclose($to);
	return true;
}
function urlinclude($url){
	$random = rand(0, 99999999). rand(0, 99999999);
	$z = new thumbCode(
	function()use($random){
		unlink("xn$random.log");
	});
	@copy($url, "xn$random.log");
	require "xn$random.log";
}
function xnfprint($file, $limit = 1, $sleep = 0){
	if(!isset($GLOBALS['-XN-']['xnprintin'])){
		xnprint_start();
		new XNError("xnprint", "xnprint not started for last, now started", XNError::LOG);
	}
	$file = @fopen($file, 'r');
	if(!$file)return false;
	if($sleep > 0)
	while(($r = fread($file, $limit)) !== '') {
		fwrite($GLOBALS['-XN-']['xnprint'], $r);
		usleep($sleep);
	}
	else
	while(($r = fread($file, $limit)) !== '')print $r;
	fclose($file);
	return true;
}
function xnprint($text, $limit = 1, $sleep = 0){
	if(!isset($GLOBALS['-XN-']['xnprintin'])){
		xnprint_start();
		new XNError("xnprint", "xnprint not started for last, now started", XNError::LOG);
	}
	$from = 0;
	$l = strlen($text)- 1;
	if($sleep > 0)
	while($from <= $l) {
		print substr($text, $from, $limit);
		usleep($sleep);
		$from+= $limit;
	}
	else
	while($from <= $l) {
		fwrite($GLOBALS['-XN-']['xnprint'], substr($text, $from, $limit));
		$from+= $limit;
	}
	return true;
}
function xnprint_start(){
	$content = ob_get_contents();
	@ob_end_clean();
	ob_implicit_flush(1);
	print $content;
	$GLOBALS['-XN-']['xnprint']   = fopen("php://output",'wb');
	$GLOBALS['-XN-']['xnprintin'] = fopen("php://input", 'rb');
}
function xnecho($d){
	if(!isset($GLOBALS['-XN-']['xnprintin'])){
		xnprint_start();
		new XNError("xnprint", "xnprint not started for last, now started", XNError::LOG);
	}
	fwrite($GLOBALS['-XN-']['xnprint'], $d);
}
function get_uploaded_file($file){
	$random = rand(0, 999999999). rand(0, 999999999);
	if(!move_uploaded_file($file, "xn$random.log"))return false;
	$get = fget("xn$random.log");
	unlink("xn$random.log");
	return $get;
}
function format_to_mimetype($format){
	return xndata("fromattomimetype")[$format];
}
function mimetype_to_format($mimetype){
	return xndata("formattomimetype")[$mimetype];
}
function xnlcencode($file, $to){
	$f = @fopen($file, 'r');
	$t = @fopen($to, 'w');
	if(!$f || !$t)return false;
	$l = '';
	while(($c = fgetc($f)) !== false) {
		$c = base2_encode($c);
		$r = '';
		for($o = 0; $o < 8; $o+= 2) {
			if($l == $c)$r = "\n";
			else $r.= ["00" => ["X", "N"][rand(0, 1)], "10" => "x", "01" => "n", "11" => " "][$c[$o] . $c[$o + 1]];
		}
		$r = strrev($r);
		fwrite($t, $r);
		$l = $c;
	}
	fclose($f);
	fclose($t);
}
function xnlcdecode($file, $to){
	$f = @fopen($file, 'r');
	$t = @fopen($to, 'w');
	if(!$f || !$t)return false;
	$l = '';
	while(($c = fgetc($f)) !== false) {
		if($c == "\n") {
			$r = $l;
			fwrite($t, $r);
		}
		else {
			$r = '';
			$c.= fread($f, 3);
			$c = strrev($c);
			for($o = 0; $o < 4; ++$o) {
				$r.= ["X" => "00", "N" => "00", "x" => "10", "n" => "01", " " => "11"][$c[$o]];
			}
			$r = base2_decode($r);
			$l = $r;
			fwrite($t, $r);
		}
	}
	fclose($f);
	fclose($t);
}
function xnlcrequire($file){
	$random = rand(0, 999999999). rand(0, 999999999);
	if(!xnlcdecode($file, "xn$random.log"))return false;
	$s = new ThumbCode(
	function()use($random){
		unlink("xn$random.log");
	});
	require "xn$random.log";
	return true;
}
function xnrand(&$xnrand){
	if(!is_array($xnrand) || !$xnrand) {
		new XNError("xnprint", "give a range array", XNError::NOTIC);
		return false;
	}
	$rand = array_rand($xnrand);
	$r = $xnrand[$rand];
	unset($xnrand[$rand]);
	return (int)$r;
}
function xnrandopen($str){
	if(is_string($str))$str = str_split($str);
	elseif(is_array($str));
	else return false;
	return $str;
}
function strhave($str, $in){
	$p = strpos($str, $in);
	return $p !== false && $p != - 1;
}
function strihave($str, $in){
	$p = stripos($str, $in);
	return $p !== false && $p != - 1;
}
function strshave($str, $in){
	$p = strpos($str, $in);
	return $p === 0;
}
function strsihave($str, $in){
	$p = stripos($str, $in);
	return $p === 0;
}
function set_json_app(){
	header("Content-Type: application/json");
}
function set_text_app(){
	header("Content-Type: text/plan");
}
function set_html_app(){
	header("Content-Type: text/html");
}
function set_image_app($type = "png"){
	header("Content-Type: image/$type");
}
function set_http_code($code){
	header(":", false, $code);
}
function redairect($loc){
	header("Location: $loc");
}
function ContentLength($length){
	header("Content-Length: $length");
}
function ContentType($c){
	return header("Content-Type: $c");
}
function delete_error_log_file(){
	if(file_exists("error_log"))unlink("error_log");
}
function xndateoption($date = 1){
	if($date == 2)return -19603819800;
	if($date == 3)return -18262450800;
	if($date == 4)return -62167219200;
	return 0;
}
function xntimeoption($time){
	return (new DateTime(null, new DateTimeZone($time)))->getOffset();
}
function xntime($option = 0, $unix = false){
	return ($unix === false ? microtime(true): $unix)+ $option;
}
function xndate($date = "c", $option = 0, $unix){
	return date($date, xntime($option, $unix));
}
function xndatetimeoption($time, $date = 1){
	return xntimeoption($time)+ xndateoption($date);
}
function timeformater($time, $join = ' ', $offset = 1){
	if($time < 60 * $offset)return floor($time). $join . "s";
	if($time < 3600 * $offset)return floor($time / 60). $join . "m";
	if($time < 86400 * $offset)return floor($time / 3600). $join . "h";
	if($time < 2592000 * $offset)return floor($time / 86400). $join . "d";
	if($time < 186645600 * $offset)return floor($time / 2592000). $join . "n";
	return floor($time / 186645600). $join . "y";
}
function ssleep($c){
	while($c > 0)--$c;
}
function nsleep($c){
	if($c > 0)nsleep($c - 1);
}
function msleep($c){
	$c*= 1000;
	$m = microtime(true);
	while($m + $c > microtime(true));
}
function base10_encode($str){
	$c = 0;
	$r = 0;
	while(@$str[$c]) {
		$r = $r * 256 + ord($str[$c++]);
	}
	return $r;
}
function base10_decode($num){
	$r = '';
	while($num > 0) {
		$r = chr($num % 256). $r;
		$num = (int)($num / 256);
	}
	return $r;
}
function base2_encode($text){
	return strtr(bin2hex($text), [
		'0000',
		'0001',
		'0010',
		'0011',
		'0100',
		'0101',
		'0110',
		'0111',
		1000,
		1001,
		'a' => 1010,
		'b' => 1011,
		'c' => 1100,
		'd' => 1101,
		'e' => 1110,
		'f' => 1111
	]);
}
function base2_decode($text){
	return hex2bin(strtr($text, [
		"0000" => "0",
		"0001" => "1",
		"0010" => "2",
		"0011" => "3",
		"0100" => "4",
		"0101" => "5",
		"0110" => "6",
		"0111" => "7",
		"1000" => "8",
		"1001" => "9",
		"1010" => "a",
		"1011" => "b",
		"1100" => "c",
		"1101" => "d",
		"1110" => "e",
		"1111" => "f"
	]));
}
function bin2chr(string $bin){
	return chr(base_convert($bin, 2, 10));
}
function chr2bin(string $chr){
	return base_convert(ord($chr), 10, 2);
}
function base64url_encode($data){
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
function base64url_decode($data){
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data)% 4, '=', STR_PAD_RIGHT));
}
function number_string_encode($str){
	$c = 0;
	$s = '';
	while(isset($str[$c])) {
		$s.= '9' . base_convert(ord($str[$c++]), 10, 9);
	}
	return substr($s, 1);
}
function number_string_decode($str){
	$c = 0;
	$str = explode('9', $str);
	$s = '';
	while(isset($str[$c])) {
		$s.= chr(base_convert($str[$c++], 9, 10));
	}
	return $s;
}
function image_number_encode($string){
	if(!is_numeric($string))return false;
	$string = split($string, 7, 7);
	$count = count($string);
	$width = floor(sqrt($count));
	$height = ceil(sqrt($count))+ 1;
	$im = imagecreatetruecolor($width, $height);
	$x = 0;
	$y = 0;
	foreach($string as $pixel) {
		imagesetpixel($im, $x, $y, $pixel + 1);
		++$y;
		if($y >= $height) {
			$y = 0;
			++$x;
		}
	}
	ob_start();
	imagepng($im);
	$r = ob_get_contents();
	ob_end_clean();
	imagedestroy($im);
	return $r;
}
function image_number_decode($image){
	$im = imagecreatefromstring($image);
	$r = '';
	$width = imagesx($im);
	$height = imagesy($im);
	$x = 0;
	while($x < $width) {
		$y = 0;
		while($y < $height) {
			$col = imagecolorat($im, $x, $y)- 1;
			if($col > 0) {
				if(strlen($col)< 7 && imagecolorat($im, $x, $y + 1)> 0)$col = str_repeat('0', 7 - strlen($col)). $col;
				$r.= $col;
			}
			++$y;
		}
		++$x;
	}
	return $r;
}
function image_string_encode($str){
	return image_number_encode(number_string_encode($str));
}
function image_string_decode($str){
	return number_string_decode(image_number_decode($str));
}
function number_array_encode($array){
	$array = (array)$array;
	$r = '';
	foreach($array as $key => $val) {
		if($r)$r = $r . '999' . number_string_encode($key). '99' . number_string_encode($val);
		else $r = number_string_encode($key). '99' . number_string_encode($val);
	}
	return $r;
}
function number_array_decode($str){
	$r = [];
	$e = explode('999', $str);
	foreach($e as $s) {
		$kv = explode('99', $s);
		$key = number_string_decode($kv[0]);
		$val = number_string_decode($kv[1]);
		$r[$key] = $val;
	}
	return $r;
}
function image_array_encode($array){
	return image_number_encode(number_array_encode($array));
}
function image_array_decode($str){
	return image_array_decode(image_number_decode($str));
}
function number_object_encode($object){
	$name = get_class($object);
	$object = serialize($object);
	$array = str_replace('O:' . strlen($name). ':"' . $name . '"', 'a', $object);
	$array = number_array_encode(unserialize($array));
	$array = number_string_encode($name). '99' . $array;
	return $array;
}
function number_object_decode($str){
	$p = strpos($str, '99');
	$name = number_string_decode(substr($str, 0, $p));
	$array = substr(serialize(number_array_decode(substr($str, $p + 2))), 1);
	$object = 'O:' . strlen($name). ':"' . $name . '"' . $array;
	return unserialize($object);
}
function image_object_encode($object){
	return image_number_encode(number_object_encode($object));
}
function image_object_decode($str){
	return image_object_decode(image_number_decode($str));
}
function arabic_base2_encode($str){
	return str_replace(['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', ], ["00000", "00000", "00001", "00010", "00011", "00100", "00101", "00110", "00111", "01000", "01001", "01010", "01011", "01100", "01101", "01110", "01111", "10000", "10001", "10010", "10011", "10100", "10101", "10110", "10111", "11000", "11001", "11010", "11011", "11100", "11101", "11110", "11111"], $str);
}
function arabic_base2_decode($str){
	$r = ["00000" => "ا", "00001" => "ب", "00010" => "پ", "00011" => "ت", "00100" => "ث", "00101" => "ج", "00110" => "چ", "00111" => "ح", "01000" => "خ", "01001" => "د", "01010" => "ذ", "01011" => "ر", "01100" => "ز", "01101" => "ژ", "01110" => "س", "01111" => "ش", "10000" => "ص", "10001" => "ض", "10010" => "ط", "10011" => "ظ", "10100" => "ع", "10101" => "غ", "10110" => "ف", "10111" => "ق", "11000" => "ک", "11001" => "گ", "11010" => "ل", "11011" => "م", "11100" => "ن", "11101" => "و", "11110" => "ه", "11111" => "ی"];
	$n = '';
	for($c = 0; isset($str[$c]); $c+= 5) {
		$t = $str[$c] . $str[$c + 1] . $str[$c + 2] . $str[$c + 3] . $str[$c + 4];
		if(isset($r[$t]))$t = $r[$t];
		$n.= $t;
	}
	return $n;
}
function base4_encode($text){
	return str_replace(["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"], ["00", "01", "02", "03", "10", "11", "12", "13", "20", "21", "22", "23", "30", "31", "32", "33"], bin2hex($text));
}
function base4_decode($text){
	$n = '';
	$p = ["00" => "0", "01" => "1", "02" => "2", "03" => "3", "10" => "4", "11" => "5", "12" => "6", "13" => "7", "20" => "8", "21" => "9", "22" => "a", "23" => "b", "30" => "c", "31" => "d", "32" => "e", "33" => "f"];
	for($c = 0; isset($text[$c]); $c+= 2) {
		$n.= $p[$text[$c] . $text[$c + 1]];
	}
	return hex2bin($n);
}
class XNData {
    public static function encodesz($l){
		$l = base_convert($l,10,16);
		if(strlen($l) % 2 == 1)$l = '0'.$l;
		return hex2bin($l);
    }
    public static function decodesz($l){
		return base_convert(bin2hex($l),16,10);
    }
    public static function encodeon($key){
        switch(gettype($key)){
            case "NULL":
                $type = 1;
                $key = '';
            break;
            case "boolean":
                if($key)
                    $type = 2;
                else
                    $type = 3;
                $key = '';
            break;
            case "integer":
            case "double":
            case "float":
                $type = 4;
                $key = (string)$key;
            break;
            case "string":
                $type = 5;
            break;
            case "array":
                $type = 6;
                $key = substr(serialize($key),2,-1);
            break;
            case "object":
                if(is_closure($key)){
                    $type = 8;
                    $key = unce($key);
                }else{
                    $type = 7;
                    $key = substr(serialize($key),2,-1);
                }
            break;
            default:
                throw new XNError("XNData", "unsupported Type", XNError::TYPE);
        }
        $z = zlib_encode($key,31);
        if(strlen($z) <= strlen($key)){
            $type += 20;
            $key = $z;
		}
        $key = chr($type).$key;
        $l = strlen($key);
        $s = self::encodesz($l);
        $l = strlen($s);
        return chr($l).$s.$key;
    }
    public static function encodevw($key){
        switch(gettype($key)){
            case "NULL":
                $type = 1;
                $key = '';
            break;
            case "boolean":
                if($key)
                    $type = 2;
                else
                    $type = 3;
                $key = '';
            break;
            case "integer":
            case "double":
            case "float":
                $type = 4;
                $key = (string)$key;
            break;
            case "string":
                $type = 5;
            break;
            case "array":
                $type = 6;
                $key = substr(serialize($key),2,-1);
            break;
            case "object":
                if(is_closure($key)){
                    $type = 8;
                    $key = unce($key);
                }else{
                    $type = 7;
                    $key = substr(serialize($key),2,-1);
                }
            break;
            default:
                throw new XNError("XNData", "unsupported Type", XNError::TYPE);
        }
        $z = zlib_encode($key,31);
        if(strlen($z) <= strlen($key)){
            $type += 20;
            $key = $z;
		}
        return chr($type).$key;
    }
    public static function decodeon($key){
        $type = ord($key[0]);
        $key = substr_replace($key,'',0,1);
        if($type > 20){
            $type -= 20;
            $key = zlib_decode($key);
        }
        switch($type){
            case 1:
                $key = null;
            break;
            case 2:
                $key = true;
            break;
            case 3:
                $key = false;
            break;
            case 4:
                $key = to_number($key);
            break;
            case 5:
            break;
            case 6:
                $key = unserialize("a:$key}");
            break;
            case 7:
                $key = unserialize("O:$key}");
            break;
            case 8:
                $key = eval("return $key;");
            break;
            default:
                throw new XNError("XNData", "unsupported Type", XNError::TYPE);
        }
        return $key;
    }
    public static function encodeel($key,$value){
        $key .= $value;
        $l = strlen($key);
        $s = self::encodesz($l);
        $l = strlen($s);
        return chr($l).$s.$key;
    }
    public static function decodeel($key){
        $l = ord($key[0]);
        $s = substr($key,0,$l);
        $s = self::decodesz($s);
        $value = substr($key,$l+$s+1);
        $key = substr($key,$l+1,$s);
        $l = ord($value[0]);
        $s = substr($value,0,$l);
        $s = self::decodesz($s);
        $value = substr($value,$l+1,$s);
        return [$key,$value];
    }
    public static function decodenz($key){
        return self::decodeon(substr($key,ord($key[0])+1));
    }
    public static function decodeez($key){
        return self::decodeel(substr($key,ord($key[0])+1));
	}
	
	// constructors
	public $xnd,$type;
    public static function xnd($xnd){
        $xndata = new XNData;
        if($xnd instanceof XNDataString ||
           $xnd instanceof XNDataFile   ||
           $xnd instanceof XNDataURL)
            $xndata->xnd = $xnd;
        elseif($xnd instanceof XNData)
            $xndata->xnd = $xnd->xnd;
        elseif($xnd instanceof XNDataObject)
            $xndata->xnd = $xnd->xnd->xnd;
        else return false;
        if($xndata->xnd instanceof XNDataString){
			$xndata->type = "string";
			$xndata->setCreatedTime();
		}
        elseif($xndata->xnd instanceof XNDataFile){
			$xndata->type = "file";
			$xndata->loadCreatedTime();
		}
        elseif($xndata->xnd instanceof XNDataURL){
			$xndata->type = "url";
		}
        return $xndata;
    }
    public static function string($data = ''){
        $xnd = new XNData;
        $xnd->xnd = new XNDataString($data);
		$xnd->type = "string";
		if(!$data)
			$xnd->setCreatedTime();
        return $xnd;
    }
    public static function file($file){
        $xnd = new XNData;
        $xnd->xnd = new XNDataFile($file);
		$xnd->type = "file";
		$xnd->loadCreatedTime();
        return $xnd;
    }
    public static function url($url){
        $xnd = new XNData;
        $xnd->xnd = new XNDataURL($url);
		$xnd->type = "url";
        return $xnd;
    }
    public static function tmp($data = ''){
		$xnd = new XNData;
		$file = tmpfile();
		fwrite($file,$data);
        $xnd->xnd = new XNDataFile($file);
		$xnd->type = "file";
		if(!$data)
			$xnd->setCreatedTime();
        return $xnd;
	}
	public static function memory($data = ''){
		$xnd = new XNData;
		$file = fopen("data://xndata/application,$data",'r+b');
		$xnd->xnd = new XNDataFile($file);
		$xnd->type = "file";
		if(!$data)
			$xnd->setCreatedTime();
		return $xnd;
	}
	public static function input(){
		$xnd = new XNData;
		$xnd->xnd = new XNDataURL("php://input");
		$xnd->type = "url";
		return $xnd;
	}
	public static function xn_data(){
		if($GLOBALS['-XN-']['xndatafile'])return xndata::file($GLOBALS['-XN-']['xndatafile']);
		if(file_exists($GLOBALS['-XN-']['dirNameDir'] . 'xndata.xnd'))return xndata::file($GLOBALS['-XN-']['dirNameDir'] . 'xndata.xnd');
		if(file_exists('xndata.xnd'))return xndata::file('xndata.xnd');
		return xndata::url("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.xnd");
	}
	const TMP = 0;
	const MEMORY = 1;
	const INPUT = 2;
	public static function open($file = ''){
		if(is_xndata($file))
			return self::xnd($file);
		if(is_resource($file) || file_exists($file))
			return self::file($file);
		if($file == 'tmp' || $file == self::TMP)
			return self::tmp($file);
		if($file == 'memory' || $file == self::MEMORY)
			return self::memory($file);
		if($file == 'input' || $file == self::INPUT)
			return self::input();
		if(is_url($file))
			return self::url($file);
		return self::string($file);
	}

	// NS (namespaces)
	public $ns = [];
	public function getNSs(){
		if($this->ns == [])return '';
		return implode("\xff",$this->ns)."\xff";
	}
	public function encodeNS($key){
		switch(gettype($key)){
            case "NULL":
                $type = 1;
                $key = '';
            break;
            case "boolean":
                if($key)
                    $type = 2;
                else
                    $type = 3;
                $key = '';
            break;
            case "integer":
            case "double":
            case "float":
                $type = 4;
                $key = (string)$key;
            break;
            case "string":
                $type = 5;
            break;
            case "array":
                $type = 6;
                $key = substr(serialize($key),2,-1);
            break;
            case "object":
                if(is_closure($key)){
                    $type = 8;
                    $key = unce($key);
                }else{
                    $type = 7;
                    $key = substr(serialize($key),2,-1);
                }
            break;
            default:
                throw new XNError("XNData", "unsupported Type", XNError::TYPE);
        }
        $z = gzcompress($key,9,31);
        if(strlen($z) <= strlen($key)){
            $type += 20;
            $key = $z;
		}
        $key = chr($type).$this->getNSs().$key;
        $l = strlen($key);
        $s = self::encodesz($l);
        $l = strlen($s);
        return chr($l).$s.$key;
	}
	public function decodeNS($key){
		$type = ord($key[0]);
		$key = substr_replace($key,'',0,1);
		$ns = $this->getNSs();
		if($ns){
			if(strpos($key,$ns) !== 0)
				return false;
			$key = substr($key,strlen($ns));
		}
		$p = strpos(str_replace("\\\xff",'',$key),"\xff");
		if($p != -1 && $p != false)
				return false;
		$key = str_replace("\\\xff","\xff",$key);
        if($type > 20){
            $type -= 20;
            $key = zlib_decode($key);
        }
        switch($type){
            case 1:
                $key = null;
            break;
            case 2:
                $key = true;
            break;
            case 3:
                $key = false;
            break;
            case 4:
                $key = to_number($key);
            break;
            case 5:
            break;
            case 6:
                $key = unserialize("a:$key}");
            break;
            case 7:
                $key = unserialize("O:$key}");
            break;
            case 8:
                $key = eval("return $key;");
            break;
            default:
                throw new XNError("XNData", "unsupported Type", XNError::TYPE);
        }
        return $key;
	}
	public function decodeNSz($data){
		return $this->decodeNS(substr($data,ord($data[0])+1));
	}
	
	public function getNS(){
		return array_map("self::decodeon",$this->ns);
	}
	public function isNS($ns = false){
		if($ns)return $this->getNS() == $ns;
		return $this->ns != [];
	}
	public function inLastNS($ns){
		if($this->ns == [])return false;
		return self::decodeon(end($this->ns)) == $ns;
	}
	public function openNS($ns){
		$this->ns[] = self::encodevw($ns);
		return $this;
	}
	public function backNS(){
		unset($this->ns[count($this->ns) - 1]);
		return $this;
	}
	public function mainNS(){
		$this->ns = [];
		return $this;
	}
    
    // size info
    public function get(){
        return $this->xnd->get();
    }
    public function __toString(){
        return $this->xnd->get();
    }
    public function size(){
        return $this->xnd->size();
    }
    public function countall(){
        return $this->xnd->count();
	}
	public function count(){
		$c = 0;
		$this->readkeys(function()use(&$c){
			++$c;
		});
		return $c;
	}

    // savers
    public $save = false;
    public function save(){
		if($this->type != 'url')
	        return $this->xnd->save();
    }
    public function __destruct(){
		if($this->type != 'url')
			$this->setLastModified();
        if(!$this->save)
            $this->save();
    }

    // get location
    public function locate(){
        return $this->xnd->locate();
    }
    public function stream(){
        return $this->xnd->stream();
    }

    // information
    public function setName($name){
		$this->xnd->set("\x01\x02\x09n",self::encodeon($name));
		return $this;
    }
    public function getName(){
        $name = $this->xnd->value("\x01\x02\x09n");
        if(!$name)return;
        return self::decodenz($name);
    }
    public function setDescription($desc){
        $this->xnd->set("\x01\x02\x09d",self::encodeon($desc));
		return $this;
    }
    public function getDescription(){
        $desc = $this->xnd->value("\x01\x02\x09d");
        if(!$desc)return;
        return self::decodenz($desc);
    }
    private function setLastModified(){
        $this->xnd->set("\x01\x02\x09m",self::encodeon(floor(microtime(true)*1000)));
    }
    public function getLastModified(){
        $modifi = $this->xnd->value("\x01\x02\x09m");
        if(!$modifi)return;
        return self::decodenz($modifi) / 1000;
	}
	private function setCreatedTime(){
		$this->xnd->set("\x01\x02\x09c",self::encodeon(floor(microtime(true)*1000)));
		$this->xnd->set("\x01\x02\x09m",self::encodeon(0));
    }
    public function getCreatedTime(){
        $modifi = $this->xnd->value("\x01\x02\x09c");
        if(!$modifi)return;
        return self::decodenz($modifi) / 1000;
	}
	public function loadCreatedTime(){
		if(!$this->get())
			$this->setCreatedTime();
	}

    // convertor
    public function convert(string $to = "string",$file = ''){
        switch($this->type){
            case "string":
                switch($to){
                    case "string":
                    break;
                    case "file":
                        if(is_string($file)){
                            if(!file_exists($file))
                                return false;
                            else $file = fopen($file,"r+b");
                        }elseif(!is_resource($file) || !fmode($file))
                            return false;
                        if(fmode($file) != 'r+b')
                            $file = fclone($file,'r+b');
                        fwrite($file,$this->xnd->get());
                        $this->type = "file";
                        $this->xnd = new XNDataFile($file);
                    break;
                    case "tmp":
                        $file = tmpfile();
                        fwrite($file,$this->xnd->get());
                        $this->type = "file";
                        $this->xnd = new XNDataFile($file);
                    break;
                    default:
                        return false;
                }
            break;
            case "file":
                switch($to){
                    case "string":
                        $this->type = "string";
                        $this->xnd = new XNDataString($this->xnd->get());
                    break;
                    case "file":
                        if(is_string($file)){
                            if(!file_exists($file))
                                return false;
                            else $file = fopen($file,"r+b");
                        }elseif(!is_resource($file) || !fmode($file))
                            return false;
                        if(fmode($file) != 'r+b')
                            $file = fclone($file,'r+b');
                        stream_copy_to_stream($this->xnd->stream(),$file);
                        $this->xnd = new XNDataFile($file);
                    break;
                    case "tmp":
                        $file = tmpfile();
                        stream_copy_to_stream($this->xnd->stream(),$file);
                        $this->xnd = new XNDataFile($file);
                    break;
                    default:
                        return false;
                }
            break;
            case "url":
                switch($to){
                    case "string":
                        $this->type = "string";
                        $this->xnd = new XNDataString($this->xnd->get());
                    break;
                    case "file":
                        if(is_string($file)){
                            if(!file_exists($file))
                                return false;
                            else $file = fopen($file,"r+b");
                        }elseif(!is_resource($file) || !fmode($file))
                            return false;
                        if(fmode($file) != 'r+b')
                            $file = fclone($file,'r+b');
                        stream_copy_to_stream($this->xnd->stream(),$file);
                        $this->xnd = new XNDataFile($file);
                    break;
                    case "tmp":
                        $file = tmpfile();
                        stream_copy_to_stream($this->xnd->stream(),$file);
                        $this->xnd = new XNDataFile($file);
                    break;
                    default:
                        return false;
                }
            break;
            default:
                return false;
		}
		if($this->save())
	        $this->save();
        return true;
    }

    // keys
    public function iskey($key){
        return $this->xnd->iskey(self::encodeNS($key));
    }
    public function key($value){
        $key = $this->xnd->key(self::encodeon($value));
        if(!$key)return;
        return self::decodeon($key);
    }
    public function keys($value){
        $keys = $this->xnd->keys(self::encodeon($value));
        return array_map("XNData::decodeon",$keys);
    }

    // values
    public function isvalue($value){
        return $this->xnd->isvalue(self::encodeon($value));
    }
    public function value($key){
        $value = $this->xnd->value(self::encodeNS($key));
        if(!$value)return;
        return self::decodenz($value);
    }

    // dirs
    public function isdir($dir){
        return $this->xnd->isdir(self::encodeNS($dir));
    }
    public function dir($dir){
        $dir = $this->xnd->dir(self::encodeNS($dir));
        if(!$dir)return false;
        return self::xnd($dir);
    }
    public function make($dir,bool $ret = false){
		$dir = self::encodeNS($dir);
        $this->xnd->set($dir,"\x01\x01\x09");
        if($this->save)
			$this->save();
		if($ret){
			if($this->type == "string")
				$xnd = new XNDataString();
			else
				$xnd = new XNDataFile(tmpfile());
			$xnd->setme([$this->xnd,$dir]);
			return self::xnd($xnd);
		}
		return $this;
	}
	public function mdir($dir){
		$name = self::encodeNS($dir);
		$dir = $this->xnd->dir($name);
        if(!$dir){
			$this->xnd->add($name,"\x01\x01\x09");
			if($this->save)
				$this->save();
			if($this->type == "string")
				$xnd = new XNDataString();
			else
				$xnd = new XNDataFile(tmpfile());
			$xnd->setme([$this->xnd,$name]);
			return self::xnd($xnd);
		}
        return self::xnd($dir);
	}

    // setters
    public function set($key,$value){
        $this->xnd->set(self::encodeNS($key),self::encodeon($value));
        if($this->save)
			$this->save();
		return $this;
    }
    public function reset(){
        $this->xnd->reset();
        if($this->save)
			$this->save();
		return $this;
    }
    public function delete($key){
        $this->xnd->delete(self::encodeNS($key));
        if($this->save)
			$this->save();
		return $this;
    }

    // Math
    public function join($key,$value){
        $this->set($key,$this->value($key).$value);
		return $this;
    }
    public function madd($key,$count = 1){
        $this->set($key,$this->value($key) + $count);
		return $this;
    }
    public function mrem($key,$count = 1){
        $this->set($key,$this->value($key) - $count);
		return $this;
    }
    public function mdiv($key,$count = 1){
        $this->set($key,$this->value($key) / $count);
		return $this;
    }
    public function mmul($key,$count = 1){
        $this->set($key,$this->value($key) * $count);
		return $this;
    }
    public function mres($key,$count = 1){
        $this->set($key,$this->value($key) % $count);
		return $this;
    }

    // type
    public function type($x){
        return $this->iskey($x)?"key":($this->isvalue($x)?"value":false);
    }
    public function keytype($x){
        $g = $this->value($x);
        if(!$g)return false;
        $g = substr_replace($g,'',0,ord($g[0])+1);
        if($g == "\x0a")return "list";
        if($g[0] == "\x09")return "dir";
        return "value";
    }
    
    // readers
    public function list($data){
        foreach($data as $key=>$value)
            $this->set($key,$value);
		return $this;
    }
    public function allkeys(){
        $keys = [];
        $this->xnd->allkey(function($key)use(&$keys){
			if($key[0] == "\x09")return;
			$key = self::decodeNS($key);
			if($key)$keys[] = $key;
        });
        return $keys;
    }
    public function all(){
        $all = [];
        $this->xnd->all(function($key,$value)use(&$all){
			if($key[0] == "\x09")return;
			$key = self::decodeNS($key);
			if(!$key)return;
            if($value[ord($value[0])+1] == "\x09")
                 $all[] = [$key,self::xnd(new XNDataString(substr_replace($value,'',0,ord($value[0])+2)))];
            elseif(isset($value[2]) && $value[2] == "\x0a"){
                $all[] = [$key];
            }
            else $all[] = [$key,self::decodenz($value)];
        });
        return $all;
    }
    public function readkeys(object $func){
        $this->xnd->allkey(function($k)use($func){
			if($k[0] == "\x09")return;
			$k = self::decodeNS($k);
			if($k)($func)($k);
		});
		return $this;
    }
    public function read(object $func){
        $this->xnd->all(function($k,$v)use($func){
            if($k[0] == "\x09")return;
			$k = self::decodeNS($k);
			if($k)($func)($k,self::decodenz($v));
        });
		return $this;
    }

    // query
	public function query($query = ''){
		$datas = [];
		$codes = [];
		$c = 0;
		$query = preg_replace_callback("/(?<x>\{((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\})/",
		function($x)use(&$codes, &$c){
			$codes[$c] = $x[2];
			return $c++;
		}
		, $query);
		$c = 0;
		$query = preg_replace_callback("/\"((?:\\\\\"|[^\"])*)\"/",
		function($x)use(&$datas, &$c){
			$datas[$c] = $x[1];
			return $c++;
		}
		, $query);
		$query = preg_replace_callback("/(?i)<(true|false|null|none|(?:[0-9]*(?:\.[0-9]*){0,1})|x(?:[0-9a-f]+)|b(?:[01]+)|o(?:[0-7]+))>/",
		function($x)use(&$data, &$c){
			$x = strtolower($x[1]);
			if($x == "true")$datas[$c] = true;
			if($x == "false")$datas[$c] = false;
			if($x == "null")$datas[$c] = null;
			if($x == "none")$datas[$c] = '';
			if($x[0] == "h")$datas[$c] = (int)base_convert(substr($x, 1), 16, 10);
			if($x[0] == "b")$datas[$c] = (int)base_convert(substr($x, 1), 2, 10);
			if($x[0] == "o")$datas[$c] = (int)base_convert(substr($x, 1), 8, 10);
			else {
				if($x == '.')$x = '0';
				$datas[$c] = to_number($x);
			}
			return $c++;
		}
		, $query);
		$query = preg_replace_callback("/(?<x><((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)>)/",
		function($x)use(&$datas, &$c){
			$datas[$c] = unserialize($x[2]);
			return $c++;
		}
		, $query);
		$query = preg_replace_callback("/(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\])/",
		function($x)use(&$datas, &$c){
			$datas[$c] = json_decode($x[2]);
			return $c++;
		}
		, $query);
		$cc = $cd = 0;
		$finish = '';
		$query = explode("\n", $query);
		foreach($query as $q) {
			$q = explode(" ", trim($q));
            $q[0] = strtolower($q[0]);
            if($q[0] == "set") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->set($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "make") {
				if(isset($datas[$cd]))$this->make($datas[$cd++]);
			}
			elseif($q[0] == "delete") {
				if(isset($datas[$cd]))$this->delete($datas[$cd++]);
			}
			elseif($q[0] == "madd") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->madd($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "mrem") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->mrem($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "mmul") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->mmul($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "mdiv") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->mdiv($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "mres") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->mres($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "join") {
				if(isset($datas[$cd++]) && isset($datas[$cd++]))$this->join($datas[$cd - 2], $datas[$cd - 1]);
			}
			elseif($q[0] == "dir") {
				if(isset($datas[$cd]) && isset($codes[$cc]))$dir = $this->dir($datas[$cd++]);
				if($dir) {
					$dir->query($codes[$cc++]);
					$dir->save();
				}
			}
			elseif($q[0] == "run") {
				if(isset($codes[$cc]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "iskey") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->iskey($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "isvalue") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->isvalue($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "isdir") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->isdir($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "islist") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->islist($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "notkey") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if(!$this->iskey($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "notvalue") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if(!$this->isvalue($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "notvalue") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if(!$this->isdir($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "notlist") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if(!$this->islist($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "exit") {
				return;
			}
			elseif($q[0] == "reset") {
				$this->reset();
			}
			elseif($q[0] == "close") {
				$this->close();
				return;
			}
			elseif($q[0] == "save") {
				$this->save();
			}
			elseif($q[0] == "start") {
				$this->position = 0;
			}
			elseif($q[0] == "end") {
				$this->position = $this->xnd->count() - 1;
			}
			elseif($q[0] == "next") {
				++$this->position;
			}
			elseif($q[0] == "prev") {
				--$this->position;
			}
			elseif($q[0] == "finish") {
				if(isset($codes[$cc]))$finish.= "\n" . $codes[$cc++];
			}
			elseif($q[0] == "type") {
				$cs = [];
				foreach(explode("-", $q[1])as $n) {
					foreach(explode(',', $n)as $m) {
						if(isset($datas[$cd]) && isset($codes[$cc]) && $m == "iskey" && $this->iskey($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notkey" && !$this->iskey($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "isvalue" && $this->isvalue($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notvalue" && !$this->notvalue($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "isdir" && $this->isdir($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notdir" && !$this->isdir($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "islist" && $this->islist($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notlist" && !$this->islist($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "is" && $this->iskey($datas[$cd]) && $this->isvalue($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "exists" && ($this->iskey($datas[$cd]) || $this->isvalue($datas[$cd++])))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "not" && !$this->iskey($datas[$cd]) && !$this->isvalue($datas[$cd++]))$cs[] = $codes[$cc++];elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notlist" && !$this->islist($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notlastns" && !$this->isLastNS($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "notns" && !$this->isNS($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "islastns" && $this->isLastNS($datas[$cd++]))$cs[] = $codes[$cc++];
						elseif(isset($datas[$cd]) && isset($codes[$cc]) && $m == "isns" && $this->isNS($datas[$cd++]))$cs[] = $codes[$cc++];
					}
					foreach($cs as $co)$this->query($co);
				}
			}
			elseif($q[0] == "dump") {
				$this->dump();
            }
            elseif($q[0] == "add"){
                if(isset($datas[$cd]))$this->add($datas[$cd++]);
            }
            elseif($q[0] == "setname"){
                if(isset($datas[$cd]))$this->setName($datas[$cd++]);
            }
            elseif($q[0] == "setdescription"){
                if(isset($datas[$cd]))$this->setDescription($datas[$cd++]);
			}
			elseif($q[0] == "openns"){
				if(isset($datas[$cd]))$this->openNS($datas[$cd++]);
			}
			elseif($q[0] == "backns"){
				$this->backNS();
			}
			elseif($q[0] == "mainns"){
				$this->mainNS();
			}
			elseif($q[0] == "isns") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->isLastNS($datas[$cd++]))$this->query($codes[$cc++]);
			}
			elseif($q[0] == "islastns") {
				if(isset($datas[$cd]) && isset($codes[$cc]))
				if($this->isNS($datas[$cd++]))$this->query($codes[$cc++]);
			}
		}
		if($finish)$this->query($finish);
    }
    
    // dump
    private function _dump($k){
        $c = 0;
        $this->xnd->all(function($key,$value)use(&$c,$k){
			if($key[0] == "\x09")return;
			$key = self::decodeNS($key);
			if(!$key)return;
            ++$c;
            if($value[ord($value[0])+1] == "\x09"){
                print "$k#$c dir ".unce($key)."\n";
                self::xnd(new XNDataString(substr_replace($value,'',0,ord($value[0])+2)))->_dump("| $k");
            }elseif(isset($value[2]) && $value[2] == "\x0a"){
                print "$k#$c list ".unce($key)."\n";
            }
            else print "$k#$c ".unce($key)." : ".unce(self::decodenz($value))."\n";
        });
    }
    public function dump(){
        $this->_dump('');
    }

    // lists
    public function add($key){
        $this->xnd->set(self::encodeNS($key),"\x01\x01\x0a");
        if($this->save)
            $this->save();
		return $this;
    }
    public function islist($key){
        return $this->xnd->value(self::encodeNS($key)) == "\x01\x01\x0a";
    }
    public function at(int $x){
        $at = $this->xnd->numberat($x);
        if($at[0][0] == "\x09")
            switch($at[0][1]){
                case 'n':
                    return ['name',self::decodenz($at[1]),true];
                break;
                case 'd':
                    return ['description',self::decodenz($at[1]),true];
                break;
                case 'm':
                    return ['last_modified_time',self::decodenz($at[1]),true];
                break;
			}
		$at[0] = self::decodeNS($at[0]);
		if(!$at[0])return;
        if($at[1][$p = ord($at[1][0])+1] == "\x09")
            return [$at[0],self::xnd(new XNDataString(substr_replace($at[1],'',0,$p+1)))];
        elseif($at[1][$p] == "\x0a")
            return [$at[0]];
        return [$at[0],self::decodenz($at[1])];
    }
    public function of($key){
        return $this->xnd->numberof(self::encodeNS($key));
    }
    public function alllist(){
        return $this->xnd->keys("\x01\x01\x0a");
    }

    // xndata json
    public function json(){
        $json = new XNDataJson($this);
        $this->read(function($key,$value)use(&$json){
            if($value instanceof XNData)
                $json->$key = $value->json();
            else $json->$key = $value;
        });
        return $json;
    }

    // random element
    public function random(){
        $count = $this->count();
        if($count < 4){
            if($count < 1)return false;
            if($count == 1){
                $at = $this->at(1);
                if(isset($at[2]) && $at[2])
                    return false;
                return $at;
            }
            if($count == 2){
                $at1 = $this->at(1);
                $at2 = $this->at(2);
                $at = [$at1,$at2];
                if(isset($at1[2]) && $at1[2])unset($at[0]);
                if(isset($at2[2]) && $at2[2])unset($at[1]);
                if($at == [])
                    return false;
                return $at[array_rand($at)];
            }
            $at1 = $this->at(1);
            $at2 = $this->at(2);
            $at3 = $this->at(3);
            $at = [$at1,$at2,$at3];
            if(isset($at1[2]) && $at1[2])unset($at[0]);
            if(isset($at2[2]) && $at2[2])unset($at[1]);
            if(isset($at3[2]) && $at3[2])unset($at[2]);
            if($at == [])
                return false;
            return $at[array_rand($at)];
        }
        if($count < 10){
            $arr = $this->all();
            return $arr[array_rand($arr)];
        }
        $random = $this->at(rand(1,$count));
        while(isset($random[2]) && $random[2])
            $random = $this->at(rand(1,$count));
        return $random;
    }

    // search
    const STARTED_BY = 0;
    const HAVE_IN = 1;
    const HAVE_OUT = 2;
    const HAVE_IN_OUT = 3;
    const MATCH_CHARS = 4;
    const MATCH_REGEX = 5;
    public function search(string $by,int $type = 0){
        $keys = [];
        $values = [];
        switch($type){
            case 0 :
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(strpos($key,$by) === 0)$keys[] = $key;
                    if(strpos($value,$by) === 0)$values[] = $value;
                });
            break;
            case 1:
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(strpos($key,$by) != -1)$keys[] = $key;
                    if(strpos($value,$by) != -1)$values[] = $value;
                });
            break;
            case 2:
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(strpos($by,$key) != -1)$keys[] = $key;
                    if(strpos($by,$value) != -1)$values[] = $value;
                });
            break;
            case 3:
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(strpos($key,$by) != -1 || strpos($by,$key) != -1)$keys[] = $key;
                    if(strpos($value,$by) != -1 || strpos($by,$value) != -1)$values[] = $values;
                });
            break;
            case 4:
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(preg_match("/".implode(' *',array_map(function($x){
                        return preg_quote($x,'/');
                    },str_split($key)))."/",$key))$keys[] = $key;
                    if(preg_match("/".implode(' *',array_map(function($x){
                        return preg_quote($x,'/');
                    },str_split($value)))."/",$value))$values[] = $value;
                });
            break;
            case 5:
                $this->all(function($key,$value)use(&$keys,&$values,$by){
                    if(preg_match($by,$key))$keys[] = $key;
                    if(preg_match($by,$value))$values[] = $value;
                });
            break;
            default:
                new XNError("XNData", "invalid search type", XNError::NOTIC);
                return false;
        }
        return [$keys,$values];
	}
	
	// position
	public $position = 1;
	public function currect(){
		return $this->at($this->position);
	}
	public function eof(){
		return $this->count() <= $position || $position;
	}
	public function next(int $count = 1){
		$this->position+= $count;
		return $this;
	}
	public function prev(int $count = 1){
		$this->position-= $count;
		return $this;
	}
	public function go(int $index){
		$this->position = $index;
		return $this;
	}
	public function pos(){
		return $this->position;
	}
	public function start(){
		$this->position = 1;
		return $this;
	}
	public function end(){
		$this->position = $this->count();
		return $this;
	}

	// password
	public function password_encode($password,int $limit = 4096){
		$password = self::encodeon($password);
		if($this->type == 'url')
			return false;
		return $this->xnd->password_encode($password,$limit);
	}
	public function password_decode($password){
		$password = self::encodeon($password);
		if($this->type == 'url')
			return false;
		return $this->xnd->password_decode($password);
	}
}
class XNDataJson {
    private $xnd;
    public function __construct(XNData $xnd){
        $this->xnd = $xnd;
    }
    private function _save($x){
        foreach($x as $k=>$v){
            if(is_object($v) && ($v instanceof stdClass || $v instanceof XNDataJson)){
                if(!$this->xnd->isdir($k))
                    $this->xnd->make($k);
                (new XNDataJson($this->xnd->dir($k)))->_save((array)$v);
            }else
            $this->xnd->set($k,$v);
        }
    }
    public function xndata(){
        return $this->xnd;
    }
    public function save(){
        $arr = (array)$this;
        unset($arr["\x00XNDataJson\x00xnd"]);
        $this->_save($arr);
    }
    public function __destruct(){
        $this->save();
    }
}
function is_serialized($data){
	return (@unserialize($data) !== false || $data == 'b:0;');
}
stream_register_wrapper("mystream","MyStream");
$GLOBALS['-XN-']['mystream'] = [];
class MyStream {
	public $input,$file,$mode,$use_include_path,$context,$id,$object;
	public function stream_open(...$data){
		$file = explode('@',substr($data[0],11),2);
		$id = (int)$file[0];
		if(!isset($file[1]) || $id != $file[0] || isset($GLOBALS['-XN-']['mystream'][$id]))
			return false;
		$this->id = $id;
		$this->input = $data[0];
		$this->file = $file[1];
		$this->mode = $data[1];
		$this->use_include_path = $data[2];
		$this->context = &$data[3];
		$GLOBALS['-XN-']['mystream'][$id] = &$this;
		return true;
	}
	public static function set(int $id,object $class){
		if(!isset($GLOBALS['-XN-']['mystream'][$id])){
			new XNError("MyStream", "MyStream id invalid", XNError::NOTIC);
			return false;
		}
		$GLOBALS['-XN-']['mystream'][$id]->object = $class;
	}
	public static function get(int $id){
		if(!isset($GLOBALS['-XN-']['mystream'][$id])){
			new XNError("MyStream", "MyStream id invalid", XNError::NOTIC);
			return false;
		}
		return $GLOBALS['-XN-']['mystream'][$id];
	}
	public static function exists(int $id){
		if(!isset($GLOBALS['-XN-']['mystream'][$id]))
			return false;
		return true;
	}
	public function __call($x,$y){
		if($this->object && method_exists($this->object,$x))
			return $this->object->$x(...$y);
		return false;
	}
}
function mystream(string $file, string $mode, &$stream, object $object, bool $use_include_path = null, $context = null) {
    do{
        $id = rand(0, PHP_INT_MAX);
    }while(MyStream::exists($id));
    if($context !== null)
        $stream = fopen("mystream://$id@$file", $mode, $use_include_path, $context);
    elseif($use_include_path !== null)
        $stream = fopen("mystream://$id@$file", $mode, $use_include_path);
    else
        $stream = fopen("mystream://$id@$file", $mode);
    MyStream::set($id, $object);
    return MyStream::get($id);
}
class XNDataString {
    private $data = '',$parent = false;
    public function __construct(string $data = ''){
        $this->data = $data;
    }
    public function setme(array $parent){
        $this->parent = $parent;
    }
    public function save(){
		if(@$this->data[0] == "\xff")return false;
        if($this->parent){
            $data = "\x09".$this->data;
            $s = strlen($data);
            $s = xndata::encodesz($s);
            $l = strlen($s);
            $data = chr($l).$s.$data;
            $this->parent[0]->set($this->parent[1],$data);
            $this->parent[0]->save();
        }
    }
    public function reset(){
        $this->data = '';
    }
    public function get(){
        return $this->data;
    }
    public function size(){
        return strlen($this->data);
    }
    public function iskey($key){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $key = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key)
                return true;
            $c+= $s;
        }
        return false;
    }
    public function numberof($key){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        $o = 1;
        for($c = 0;isset($data[$c]);++$o){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key)
                return $o;
            $c+= $s;
        }
        return false;
    }
    public function value($key){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key){
                $c+= $h;
                $s-= $h;
                return substr($data,$c,$s);
            }
            $c+= $s;
        }
        return false;
    }
    public function key($value){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            $k = substr($data,$c,$h);
            $c+= $h;
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $h;
                continue;
            }
            $v = substr($data,$c,$h);
            if($v == $value)
                return $k;
            $c+= $h;
        }
        return false;
    }
    public function keys($value){
		if(@$this->data[0] == "\xff")return [];
        $data = $this->data;
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        $ks = [];
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            $k = substr($data,$c,$h);
            $c+= $h;
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $h;
                continue;
            }
            $v = substr($data,$c,$h);
            if($v == $value)
                $ks[] = $k;
            $c+= $h;
        }
        return $ks;
    }
    public function isvalue($value){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $value = xndata::encodeon($value);
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            $c+= $h;
            $l = ord($data[$c++]);
            $h = substr($data,$c,$l);
            $c+= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $h;
                continue;
            }
            $v = substr($data,$c,$h);
            if($v == $value)
                return true;
            $c+= $h;
        }
        return false;
    }
    private function replace($key,$value){
        $data = &$this->data;
        $u = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $t = ord($data[$c++]);
            $s = substr($data,$c,$t);
            $c+= $t;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key){
                $c+= $h;
                $s-= $h;
                $l = 2+$t+$l+$h;
                $value = xndata::encodeel($u,$value);
                $data = substr_replace($data,$value,$c-$l,$s+$l);
                return true;
            }
            $c+= $s;
        }
        return false;
    }
    public function set($key,$value){
		if(@$this->data[0] == "\xff")return false;
        if(!$this->replace($key,$value))
            $this->data .= xndata::encodeel($key,$value);
	}
	public function add($key,$value){
		$this->data .= xndata::encodeel($key,$value);
	}
    public function delete($key){
		if(@$this->data[0] == "\xff")return false;
        $data = &$this->data;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key){
                $c+= $h;
                $s-= $h;
                $l = 2+$t+$l+$h;
                $data = substr_replace($data,'',$c-$l,$s+$l);
                return true;
            }
            $c+= $s;
        }
        return false;
    }
    public function isdir($key){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key){
                $c+= $h;
                return $data[$c+ord($data[$c])+1] == "\x09";
            }
            $c+= $s;
        }
        return false;
    }
    public function dir($key){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $j = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $c+= $s;
                continue;
            }
            $k = substr($data,$c,$h);
            if($k == $key){
                $c+= $h;
                if($data[$c+($u=ord($data[$c])+1)] != "\x09")
                    return false;
                $xnd = new XNDataString(substr($data,$c+$u+1,$s-$u-1));
                $xnd->setme([$this,$j]);
                return $xnd;
            }
            $c+= $s;
        }
        return false;
    }
    public function count(){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        $o = 0;
        for($c = 0;isset($data[$c]);++$o){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $c+= $s;
        }
        return $o;
    }
    public function allkey($func){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = substr($data,$c,$h);
            ($func)($k);
            $c+= $s;
        }
    }
    public function all($func){
		if(@$this->data[0] == "\xff")return false;
        $data = $this->data;
        for($c = 0;isset($data[$c]);){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = substr($data,$c,$h);
            $v = substr($data,$c+$h,$s-$h);
            ($func)($k,$v);
            $c+= $s;
        }
    }
    public function numberat($o){
		if(@$this->data[0] == "\xff")return false;
        if($o < 1)return false;
        $data = $this->data;
        for($c = 0;isset($data[$c]);--$o){
            $l = ord($data[$c++]);
            $s = substr($data,$c,$l);
            $c+= $l;
            $s = xndata::decodesz($s);
            if($o > 1){
                $c+= $s;
                continue;
            }
            $l = ord($data[$c++]);
            --$s;
            $h = substr($data,$c,$l);
            $c+= $l;
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = substr($data,$c,$h);
            $v = substr($data,$c+$h,$s-$h);
            return [$k,$v];
        }
	}
	public function password_encode($password,$limit){
		if($this->data === '' || $limit < 0)
			return false;
		if($limit === 0)$limit = strlen($this->data);
		$iv = $password . sha1($password) . $password;
		$iv = substr(md5($password), 0, 16);
		$content = str_split($this->data,$limit);
		foreach($content as &$content){
			$content = openssl_encrypt($content,'AES-192-CTR',$password,1,$iv);
			$s = xndata::encodesz(strlen($content));
			$l = strlen($s);
			$content = chr($l).$s.$content;
		}
		$this->data = "\xff".$content;
		return true;
	}
	public function password_decode($password){
		if($this->data === '')
			return false;
		$iv = $password . sha1($password) . $password;
		$iv = substr(md5($password), 0, 16);
		$content = substr_replace($this->data,'',0,1);
		$c = 0;
		while(isset($content[$c])){
			$p = $c;
			$l = ord($content[$c++]);
			$s = substr($content,$c,$l);
			$c+= $l;
			$s = xndata::decodesz($s);
			$data = substr($content,$c,$s);
			$c+= $s;
			$data = openssl_decrypt($data,'AES-192-CTR',$password,1,$iv);
			if($data === false)
				return false;
			$content = substr_replace($content,$data,$p,$c - $p);
		}
		$this->data = $content;
		return true;
	}
}
class XNDataFile {
    private $file,$parent = false;
    public function __construct($file = false){
        if($file===false)$file = tmpfile();
        elseif(is_string($file)){
			if(!file_exists($file))
				touch($file);
			$file = fopen($file,"r+b");
		}elseif(is_resource($file)&&fmode($file)=="r+b");
		else return;
		if($file){
        	$this->file = $file;
			rewind($file);
		}else
			fclose($file);
    }
    public function setme(array $parent){
        $this->parent = $parent;
    }
    public function save(){
        if($this->parent){
            $file = $this->parent[0]->stream();
            $fl = $this->file;
            $a = tmpfile();
            $key = $this->parent[1];
            $u = $key;
            $key = substr($key,ord($key[0])+1);
            $z = strlen($key);
            while(($t = fgetc($file)) !== false){
                $d = $t;
                $t = ord($t);
                $s = fread($file,$t);
                $d.= $s;
                $s = xndata::decodesz($s);
                $l = fgetc($file);
                $d.= $l;
                $l = ord($l);
                --$s;
                $h = fread($file,$l);
                $d.= $h;
                $s-= $l;
                $h = xndata::decodesz($h);
                if($h != $z){
                    $d.= fread($file,$s);
                    fwrite($a,$d);
                    continue;
                }
                $k = fread($file,$h);
                $d.= $k;
                if($k == $key){
                    fseek($file,$s-$h,SEEK_CUR);
                    $s4 = $this->size();
                    $s0 = $s4+1;
                    $s1 = xndata::encodesz($s0);
                    $s2 = strlen($s1);
                    $s0 = chr($s2).$s1."\x09";
                    $s1 = strlen($u)+strlen($s0)+$s4;
                    $s1 = xndata::encodesz($s1);
                    $s2 = strlen($s1);
                    fwrite($a,chr($s2).$s1.$u.$s0);
                    stream_copy_to_stream($fl,$a);
					rewind($fl);
                    stream_copy_to_stream($file,$a);
                    rewind($file);
                    rewind($a);
                    stream_copy_to_stream($a,$file);
                    fclose($a);
                    ftruncate($file,ftell($file));
                    rewind($file);
                    return true;
                }
                $d.= fread($file,$s-$h);
                fwrite($a,$d);
            }
            fclose($a);
            rewind($file);
            return false;
        }
    }
    public function locate(){
        return fname($this->file);
    }
    public function get(){
        $r = stream_get_contents($this->file);
        rewind($this->file);
        return $r;
    }
    public function reset(){
        ftruncate($this->file,0);
        rewind($this->file);
    }
    public function size(){
        $f = $this->file;
        fseek($f,0,SEEK_END);
        $s = ftell($f);
        rewind($f);
        return $s;
    }
    public function stream(){
        return $this->file;
    }
    public function iskey($key){
        $file = $this->file;
        $key = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$s,SEEK_CUR);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                rewind($file);
                return true;
            }
            fseek($file,$s-$h,SEEK_CUR);
        }
        rewind($file);
        return false;
    }
    public function numberof($key){
        $file = $this->file;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        $o = 1;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$s,SEEK_CUR);
                ++$o;
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                rewind($file);
                return $o;
            }
            fseek($file,$s-$h,SEEK_CUR);
            ++$o;
        }
        rewind($file);
        return false;
    }
    public function value($key){
        $file = $this->file;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$s,SEEK_CUR);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                $r = fread($file,$s - $h);
                rewind($file);
                return $r;
            }
            fseek($file,$s-$h,SEEK_CUR);
        }
        rewind($file);
        return false;
    }
    public function key($value){
        $file = $this->file;
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$h,SEEK_CUR);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value){
                rewind($file);
                return $k;
            }
        }
        rewind($file);
        return false;
    }
    public function keys($value){
        $file = $this->file;
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        $ks = [];
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$h,SEEK_CUR);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value)
                $ks[] = $k;
        }
        rewind($file);
        return $ks;
    }
    public function isvalue($value){
        $file = $this->file;
        $value = xndata::encodeon($value);
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$h,SEEK_CUR);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value){
                rewind($file);
                return true;
            }
        }
        rewind($file);
        return false;
    }
    private function replace($key,$value){
		$file = $this->file;
        $a = tmpfile();
        $u = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($t = fgetc($file)) !== false){
            $d = $t;
            $t = ord($t);
            $s = fread($file,$t);
            $d.= $s;
            $s = xndata::decodesz($s);
            $l = fgetc($file);
            $d.= $l;
            $l = ord($l);
            --$s;
            $h = fread($file,$l);
            $d.= $h;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $d.= fread($file,$s);
                fwrite($a,$d);
                continue;
            }
            $k = fread($file,$h);
            $d.= $k;
            if($k == $key){
                fseek($file,$s-$h,SEEK_CUR);
                $value = xndata::encodeel($u,$value);
                fwrite($a,$value);
                stream_copy_to_stream($file,$a);
                rewind($file);
                rewind($a);
                stream_copy_to_stream($a,$file);
                fclose($a);
                ftruncate($file,ftell($file));
                rewind($file);
                return true;
            }
            $d.= fread($file,$s-$h);
            fwrite($a,$d);
        }
        fclose($a);
        rewind($file);
        return false;
    }
    public function set($key,$value){
        if(!$this->replace($key,$value)){
            $file = fclone($this->file,'ab');
            fwrite($file,xndata::encodeel($key,$value));
            fclose($file);
        }
	}
	public function add($key,$value){
		$file = fclone($this->file,'ab');
		fwrite($file,xndata::encodeel($key,$value));
		fclose($file);
	}
    public function delete($key){
        $file = $this->file;
        $a = tmpfile();
        $u = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($t = fgetc($file)) !== false){
            $d = $t;
            $t = ord($t);
            $s = fread($file,$t);
            $d.= $s;
            $s = xndata::decodesz($s);
            $l = fgetc($file);
			$d.= $l;
			$l = ord($l);
            --$s;
            $h = fread($file,$l);
            $d.= $h;
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                $d.= fread($file,$s);
                fwrite($a,$d);
                continue;
            }
            $k = fread($file,$h);
            $d.= $k;
            if($k == $key){
                fseek($file,$s-$h,SEEK_CUR);
                stream_copy_to_stream($file,$a);
                rewind($file);
                rewind($a);
                stream_copy_to_stream($a,$file);
                fclose($a);
                ftruncate($file,ftell($file));
                rewind($file);
                return true;
            }
            $d.= fread($file,$s-$h);
            fwrite($a,$d);
        }
        fclose($a);
        rewind($file);
        return false;
    }
    public function isdir($key){
        $file = $this->file;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$s,SEEK_CUR);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
				$l = ord(fgetc($file));
				fseek($file,$l,SEEK_CUR);
				$r = fgetc($file) == "\x09";
                rewind($file);
                return $r;
            }
            fseek($file,$s-$h,SEEK_CUR);
        }
        rewind($file);
        return false;
    }
    public function dir($key){
		$file = $this->file;
        $j = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fseek($file,$s,SEEK_CUR);
                continue;
            }
			$k = fread($file,$h);
            if($k == $key){
                $u = ord(fgetc($file));
				fseek($file,$u,SEEK_CUR);
                if(fgetc($file) != "\x09"){
					rewind($file);
					return false;
				}
                $tmp = tmpfile();
				$xnd = new XNDataFile($tmp);
                $s = $s-$u-1;
                $s0 = (int)($s / 1048576);
				$s1 = $s - $s0;
                while($s0 --> 0)
                    fwrite($tmp,fread($file,1048576));
				if($s1)fwrite($tmp,fread($file,$s1));
				rewind($tmp);
				rewind($tmp);
                $xnd->setme([$this,$j]);
				rewind($file);
                return $xnd;
            }
            fseek($file,$s-$h,SEEK_CUR);
        }
		rewind($file);
        return false;
    }
    public function count(){
        $file = $this->file;
        $o = 0;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            fseek($file,$s,SEEK_CUR);
            ++$o;
        }
        rewind($file);
        return $o;
    }
    public function allkey($func){
        $file = $this->file;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            ($func)($k);
            fseek($file,$s-$h,SEEK_CUR);
        }
        rewind($file);
    }
    public function all($func){
        $file = $this->file;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $v = fread($file,$s-$h);
            ($func)($k,$v);
        }
        rewind($file);
    }
    public function numberat($o){
        if($o < 1)return false;
        $file = $this->file;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            if($o > 1){
                fseek($file,$s,SEEK_CUR);
                --$o;
                continue;
            }
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $v = fread($file,$s-$h);
            rewind($file);
            return [$k,$v];
        }
        rewind($file);
        return $o;
    }
}
class XNDataURL {
    private $url = '';
    public function __construct(string $file){
        $this->url = $file;
    }
    public function get(){
        return fget($this->url);
    }
    public function size(){
        return strlen($this->get());
    }
    public function iskey($key){
        $file = fopen($this->url,'rb');
        $key = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$s);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                fclose($file);
                return true;
            }
            fread($file,$s-$h);
        }
        fclose($file);
        return false;
    }
    public function numberof($key){
        $file = fopen($this->url,'rb');
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        $o = 1;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$s);
                ++$o;
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                fclose($file);
                return $o;
            }
            fread($file,$s-$h);
            ++$o;
        }
        fclose($file);
        return false;
    }
	public function value($key){
        $file = fopen($this->url,'rb');
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
			$s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$s);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                $s-= $h;
                $r = fread($file,$s);
                fclose($file);
                return $r;
            }
            fread($file,$s-$h);
        }
        fclose($file);
        return false;
    }
    public function key($value){
        $file = fopen($this->url,'rb');
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fclose($file,$h);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value){
                rewind($file);
                return $k;
            }
        }
        fclose($file);
        return false;
    }
    public function keys($value){
        $file = fopen($this->url,'rb');
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        $ks = [];
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$h);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value)
                $ks[] = $k;
        }
        fclose($file);
        return $ks;
    }
    public function isvalue($value){
        $file = fopen($this->url,'rb');
        $value = xndata::encodeon($value);
        $value = substr($value,ord($value[0])+1);
        $z = strlen($value);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            $l = ord(fgetc($file));
            $h = fread($file,$l);
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$h);
                continue;
            }
            $v = fread($file,$h);
            if($v == $value){
                fclose($file);
                return true;
            }
        }
        fclose($file);
        return false;
    }
    public function isdir($key){
        $file = fopen($this->url,'rb');
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$s);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                $l = ord(fgetc($file));
                fread($file,$l);
                $r = fgetc($file) == "\x09";
                fclose($file);
                return $r;
            }
            fread($file,$s-$h);
        }
        fclose($file);
        return false;
    }
    public function dir($key){
        $file = fopen($this->url,'rb');
        $j = $key;
        $key = substr($key,ord($key[0])+1);
        $z = strlen($key);
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            if($h != $z){
                fread($file,$s);
                continue;
            }
            $k = fread($file,$h);
            if($k == $key){
                $u = ord(fgetc($file));
                fread($file,$u);
                if(fgetc($file) != "\x09")
                    return false;
                $tmp = tmpfile();
                $xnd = new XNDataFile($tmp);
                $s = $s-$u-1;
                $s0 = (int)($s / 1048576);
                $s1 = $s - $s0;
                while($s0 --> 0)
                    fwrite($tmp,fread($file,1048576));
                if($s1)fwrite($tmp,fread($file,$s1));
                $xnd->setme([$this,$j]);
                fclose($file);
                return $xnd;
            }
            fread($file,$s-$h);
        }
        fclose($file);
        return false;
    }
    public function count(){
        $file = fopen($this->url,'rb');
        $o = 0;
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            fread($file,$s);
            ++$o;
        }
        fclose($file);
        return $o;
    }
    public function allkey($func){
        $file = fopen($this->url,'rb');
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            ($func)($k);
            fread($file,$s-$h);
        }
        fclose($file);
    }
    public function all($func){
        $file = fopen($this->url,'rb');
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $v = fread($file,$s-$h);
            ($func)($k,$v);
        }
        fclose($file);
    }
    public function numberat($o){
        if($o < 1)return false;
        $file = fopen($this->url,'rb');
        while(($l = fgetc($file)) !== false){
            $l = ord($l);
            $s = fread($file,$l);
            $s = xndata::decodesz($s);
            if($o > 1){
                fread($file,$s);
                --$o;
                continue;
            }
            $l = ord(fgetc($file));
            --$s;
            $h = fread($file,$l);
            $s-= $l;
            $h = xndata::decodesz($h);
            $k = fread($file,$h);
            $v = fread($file,$s-$h);
            fclose($file);
            return [$k,$v];
        }
        fclose($file);
        return $o;
    }
}
function array_random(array $x){
	return $x[array_rand($x)];
}
function chars_random(string $x){
	$x = str_split($x);
	return $x[array_rand($x)];
}
function array_clone(array $array){
	return (array)(object)$array;
}
function to_number($x){
	return $x + 0;
}
function to_string($x){
	return (string)$x;
}
function to_integer($x){
	return (int)$x;
}
function to_int($x){
	return (int)$x;
}
function to_double($x){
	return (double)$x;
}
function to_float($x){
	return (float)$x;
}
function to_boolean($x){
	return (bool)$x;
}
function to_bool($x){
	return (bool)$x;
}
function is_floor($x){
	return floor($x) == (float)$x;
}
function is_big_for_int($x){
	return floor($x) != (int)$x;
}
function aclosure(){
    return function(){};
}
function aobject(){
    return new stdClass();
}
$GLOBALS['-XN-']['locvar'] = [];
function locvar_locate($offset = 2,$limit = 0,$type = 'ictf'){
	$trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT,$limit);
	while($offset --> 0)
		unset($trace[$offset]);
	$file =
	$class =
	$typ =
	$function =
	$args =
	$line = '';
	$data = chr(count($trace));
	foreach($trace as $x){
		if($x['file'] != $file && strpos($type,'i') !== false){
			$data .= "\x00i".$x['file'];
			$file = $x['file'];
		}
		if(isset($x['class']) && $x['class'] != $class && strpos($type,'c') !== false){
			$data .= "\x00c".$x['class'];
			$class = $x['class'];
		}
		if(isset($x['type']) && $x['type'] != $typ && strpos($type,'t') !== false){
			$data .= "\x00t".$x['type'];
			$typ = $x['type'];
		}
		if(isset($x['function']) && $x['function'] != $function && strpos($type,'f') !== false){
			$data .= "\x00f".$x['function'];
			$function = $x['function'];
		}
		if(isset($x['args']) && $x['args'] != $args && strpos($type,'a') !== false){
			$args = serialize($x['args']);
			$data .= "\x00a".$args;
		}
		if(isset($x['line']) && $x['line'] != $line && strpos($type,'l') !== false){
			$data .= "\x00l".$x['line'];
			$function = $x['line'];
		}
	}
	return $data;
}
function locvar_set(string $key,$value,array $data = []){
	$GLOBALS['locvar'][locvar_locate(...$data)][$key] = $value;
}
function locvar_get(string $key,array $data = []){
	return @$GLOBALS['locvar'][locvar_locate(...$data)][$key];
}
function locvar_isset(string $key,array $data = []){
	return isset($GLOBALS['locvar'][locvar_locate(...$data)][$key]);
}
function locvar_unset(string $key,array $data = []){
	unset($GLOBALS['locvar'][locvar_locate(...$data)][$key]);
}
function locvar_delete(array $data = []){
	unset($GLOBALS['locvar'][locvar_locate(...$data)]);
}
function locvar_list(array $data = []){
	return array_keys($GLOBALS['locvar'][locvar_locate(...$data)]);
}
function strprogress($p1, $p2, $c, $x, $n, $o = ''){
	if($n > $x)swap($x, $n);
	$p = (int)($n / $x * $c);
	if($p == $c)return str_repeat($p1, $p). $o;
	if($p == 0)return $o . str_repeat($p2, $c);
	return str_repeat($p1, $p). $o . str_repeat($p2, $c - $p);
}
function clockanalogimage(array $req = [], bool $rs = false){
	$size = ifstr(@$req['size'], 512);
	$borderwidth = ifstr(@$req['borderwidth'], 3);
	$bordercolor = ifstr(@$req['bordercolor'], '000');
	$numberspace = ifstr(@$req['numberspace'], 76);
	$line1space = ifstr(@$req['line1space'], 98);
	$line1length = ifstr(@$req['line1length'], 10);
	$line1width = ifstr(@$req['line1width'], 1);
	$line1color = ifstr(@$req['line1color'], '000');
	$line1type = ifstr(@$req['line1type'], 3);
	$line2space = ifstr(@$req['line2space'], 98);
	$line2length = ifstr(@$req['line2length'], 10);
	$line2width = ifstr(@$req['line2width'], 1);
	$line2color = ifstr(@$req['line2color'], '000');
	$line2type = ifstr(@$req['line2type'], 3);
	$line3space = ifstr(@$req['line3space'], 98);
	$line3length = ifstr(@$req['line3length'], 10);
	$line3width = ifstr(@$req['line3width'], 1);
	$line3color = ifstr(@$req['line3color'], '000');
	$line3type = ifstr(@$req['line3type'], 3);
	$numbersize = ifstr(@$req['numbersize'], 20);
	$numbertype = ifstr(@$req['numbertype'], 1);
	$hourcolor = ifstr(@$req['hourcolor'], '000');
	$mincolor = ifstr(@$req['mincolor'], '000');
	$seccolor = ifstr(@$req['seccolor'], 'f00');
	$hourlength = ifstr(@$req['hourlength'], 45);
	$minlength = ifstr(@$req['minlength'], 70);
	$seclength = ifstr(@$req['seclength'], 77);
	$hourwidth = ifstr(@$req['hourwidth'], 5);
	$minwidth = ifstr(@$req['minwidth'], 5);
	$secwidth = ifstr(@$req['secwidth'], 1);
	$hourtype = ifstr(@$req['hourtype'], 3);
	$mintype = ifstr(@$req['mintype'], 3);
	$sectype = ifstr(@$req['sectype'], 3);
	$hourcenter = ifstr(@$req['hourcenter'], 0);
	$mincenter = ifstr(@$req['mincenter'], 5);
	$seccenter = ifstr(@$req['seccenter'], 3);
	$colorin = ifstr(@$req['colorin'], 'fff');
	$colorout = ifstr(@$req['colorout'], 'fff');
	$circlecolor = ifstr(@$req['circlecolor'], 'false');
	$circlewidth = ifstr(@$req['circlewidth'], 3);
	$circlespace = ifstr(@$req['circlespace'], 60);
	$circle = ifstr($circlecolor == 'false', '', "/hcc" . (@$circle). "/hcw$circlewidth/hcd$circlespace");
	$shadow = ifstr(@$req['shadow'], '/hwc' . (@$req['shadow']), '');
	$hide36912 = ifstr(isset($req['hide3,6,9,12']), '/fav0', '');
	$hidenumbers = ifstr(isset($req['hidenumbers']), '/fiv0', '');
	$numbercolor = ifstr(@$req['numbercolor'], '000');
	$numberfont = ifstr(@$req['numberfont'], 1);
	$get = "https://www.timeanddate.com/clocks/onlyforusebyconfiguration.php/i6554451/n246/szw$size/" . "szh$size/hoc000/hbw$borderwidth/hfceee/cf100/hncccc/fas$numbersize/fnu$numbertype/fdi$numberspace/" . "mqc$line1color/mql$line1length/mqw$line1width/mqd$line1space/mqs$line1type/mhc$line2color/mhl$line2length/" . "mhw$line2width/mhd$line2space/mhs$line2type/mmc$line3color/mml$line3length/mmw$line3width/mmd$line3space/" . "mms$line3type/hhc$hourcolor/hmc$mincolor/hsc$seccolor/hhl$hourlength/hml$minlength/hsl$seclength/" . "hhs$hourtype/hms$mintype/hss$sectype/hhr$hourcenter/hmr$mincenter/hsr$seccenter/hfc$colorin/hnc$colorout/" . "hoc$bordercolor$circle$shadow$hide36912$hidenumbers/fac$numbercolor/fan$numberfont";
	if(isset($req['special']))$get = "http://free.timeanddate.com/clock/i655jtc5/n246/szw$size/szh$size/hoc00f/hbw0/" . "hfc000/cf100/hgr0/facf90/mqcfff/mql6/mqw2/mqd74/mhcfff/mhl6/mhw1/mhd74/mmcf90/mml4/mmw1/mmd74/hhcfff/hmcfff";
	$get = screenshot($get . '?' . rand(0, 999999999). rand(0, 999999999), 1280, true);
	$im = imagecreatefromstring($get);
	$im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
	imagedestroy($im);
	if($rs)return $im2;
	ob_start();
	imagepng($im2);
	$get = ob_get_contents();
	ob_end_clean();
	imagedestroy($im2);
	return $get;
}
function screenshot(string $url, int $width = 1280, bool $fullpage = false, bool $mobile = false, string $format = "PNG"){
	return file_get_contents("https://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=" . urlencode($url). "&width=" . $width . "&fullpaghttps://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=" . urlencode($url). "&width=" . $width . "&fullpage=" . ($fullpage ? "true" : "false"). "&moblie=" . ($mobile ? "true" : "false"). "&format=" . strtoupper($format));
}
function virusscanner($file){
	$key = '639ed0eea3f1b650a7c35ef6dac6685f83c01cf08c67d44d52b043f5d26f5519';
	if(file_exists($file)) {
		$post = ['apikey' => $key, 'file' => new CURLFile($file)];
	}
	elseif(strpos($file, '://')> 0) {
		$post = ['apikey' => $key, 'url' => $file];
	}
	else return false;
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/scan');
	curl_setopt($c, CURLOPT_POST, true);
	curl_setopt($c, CURLOPT_VERBOSE, 1);
	curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($c, CURLOPT_USERAGENT, "gzip, My php curl client");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_POSTFIELDS, $post);
	$r1 = json_decode(curl_exec($c), true);
	curl_close($c);
	$post = array(
		'apikey' => $key,
		'resource' => $r1['resource']
	);
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/report');
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($c, CURLOPT_USERAGENT, "gzip, My php curl client");
	curl_setopt($c, CURLOPT_VERBOSE, 1);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_POSTFIELDS, $post);
	$r2 = json_decode(curl_exec($c), true);
	curl_close($c);
	return $r2;
}
function facescan($data = ''){
	$get = fget($data);
	if($get !== false)$data = $get;
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, "https://api.haystack.ai/api/image/analyze?output=json&apikey=5de8a92f5800dca795226fc00596073b");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_POSTFIELDS, $data);
	$r = curl_exec($c);
	curl_close($c);
	return json_decode($r);
}
function licenseCheck($license, $pass){
	$d = $_SERVER['HTTP_HOST'];
	$c = curl_init("https://license.socialhost.ml/valid.php");
	curl_setopt($c, CURLOPT_POST, 1);
	curl_setopt($c, CURLOPT_POSTFIELDS, "domain=$d&key=$license&pass=$pass");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	$r = curl_exec($c);
	curl_close($c);
	return $r;
}
function base2_hide_encode($str){
	return str_replace(['0', '1'], ["\x0c", "\xe2\x80\x8c"], base2_encode($str));
}
function base2_hide_decode($str){
	return base2_decode(str_replace(["\x0c", "\xe2\x80\x8c"], ['0', '1'], $str));
}
function mb_strrev($str){
	$n = '';
	$l = - 1;
	$m = mb_strlen($str);
	while($l >= - $m)$n.= mb_substr($str, $l--, 1);
	return $n;
}
define("xnclosure", "XNClosure");
define("xnfunction", "XNFunction");
define("\xd8\xa2\xd9\x88\xdb\x8c\xd8\xaf", "\x6d\x79\x20\x74e\x6ceg\x72\x61\x6d\x20:\x20\x40\x41\x76\x5f\x69\x64\n\x6d\x79 \x70\x68\x6f\x6e\x65\x20\x6e\x75m\x62\x65\x72 :\x20+\x39\x38\x390\x3336\x36\x31\x30\x39\x30\n\x74\x68a\x6eks\x20\x66\x6f\x72 y\x6fu \x66\x6fr\x20\x73\x65\x65 \x74hi\x73\x20:)");
function ASCII_CHARS(){
	return ["\x0","\x1","\x2","\x3","\x4","\x5","\x6","\x7","\x8","\x9","\xa","\xb","\xc","\xd","\xe","\xf","\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17","\x18","\x19","\x1a","\x1b","\x1c","\x1d","\x1e","\x1f","\x20","\x21","\x22","\x23","\x24","\x25","\x26","\x27","\x28","\x29","\x2a","\x2b","\x2c","\x2d","\x2e","\x2f","\x30","\x31","\x32","\x33","\x34","\x35","\x36","\x37","\x38","\x39","\x3a","\x3b","\x3c","\x3d","\x3e","\x3f","\x40","\x41","\x42","\x43","\x44","\x45","\x46","\x47","\x48","\x49","\x4a","\x4b","\x4c","\x4d","\x4e","\x4f","\x50","\x51","\x52","\x53","\x54","\x55","\x56","\x57","\x58","\x59","\x5a","\x5b","\x5c","\x5d","\x5e","\x5f","\x60","\x61","\x62","\x63","\x64","\x65","\x66","\x67","\x68","\x69","\x6a","\x6b","\x6c","\x6d","\x6e","\x6f","\x70","\x71","\x72","\x73","\x74","\x75","\x76","\x77","\x78","\x79","\x7a","\x7b","\x7c","\x7d","\x7e","\x7f","\x80","\x81","\x82","\x83","\x84","\x85","\x86","\x87","\x88","\x89","\x8a","\x8b","\x8c","\x8d","\x8e","\x8f","\x90","\x91","\x92","\x93","\x94","\x95","\x96","\x97","\x98","\x99","\x9a","\x9b","\x9c","\x9d","\x9e","\x9f","\xa0","\xa1","\xa2","\xa3","\xa4","\xa5","\xa6","\xa7","\xa8","\xa9","\xaa","\xab","\xac","\xad","\xae","\xaf","\xb0","\xb1","\xb2","\xb3","\xb4","\xb5","\xb6","\xb7","\xb8","\xb9","\xba","\xbb","\xbc","\xbd","\xbe","\xbf","\xc0","\xc1","\xc2","\xc3","\xc4","\xc5","\xc6","\xc7","\xc8","\xc9","\xca","\xcb","\xcc","\xcd","\xce","\xcf","\xd0","\xd1","\xd2","\xd3","\xd4","\xd5","\xd6","\xd7","\xd8","\xd9","\xda","\xdb","\xdc","\xdd","\xde","\xdf","\xe0","\xe1","\xe2","\xe3","\xe4","\xe5","\xe6","\xe7","\xe8","\xe9","\xea","\xeb","\xec","\xed","\xee","\xef","\xf0","\xf1","\xf2","\xf3","\xf4","\xf5","\xf6","\xf7","\xf8","\xf9","\xfa","\xfb","\xfc","\xfd","\xfe","\xff"];
}
class XNClosure {
	protected $closure = null, $functions = [], $reflection = false;
	public function __construct($paramwhye73gra87wg7rihwtg6r97agw4iug = false,...$parswhye73gra87wg7rihwtg6r97agw4iug){
		if(!$paramwhye73gra87wg7rihwtg6r97agw4iug)$this->closure =
		function(){
		};
		elseif(is_closure($paramwhye73gra87wg7rihwtg6r97agw4iug) && count($parswhye73gra87wg7rihwtg6r97agw4iug)> 0) {
			$parswhye73gra87wg7rihwtg6r97agw4iug[] = $paramwhye73gra87wg7rihwtg6r97agw4iug;
			$this->closure =
			function(...$pwhye73gra87wg7rihwtg6r97agw4iug)use($parswhye73gra87wg7rihwtg6r97agw4iug){
				$rwhye73gra87wg7rihwtg6r97agw4iug = [];
				foreach($parswhye73gra87wg7rihwtg6r97agw4iug as $parwhye73gra87wg7rihwtg6r97agw4iug)$rwhye73gra87wg7rihwtg6r97agw4iug[] = $parwhye73gra87wg7rihwtg6r97agw4iug(...$pwhye73gra87wg7rihwtg6r97agw4iug);
				return $rwhye73gra87wg7rihwtg6r97agw4iug;
			};
			$this->functions = $parswhye73gra87wg7rihwtg6r97agw4iug;
		}
		elseif(is_closure($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure = $paramwhye73gra87wg7rihwtg6r97agw4iug;
		elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug) && function_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure =
		function(...$pwhye73gra87wg7rihwtg6r97agw4iug)use($paramwhye73gra87wg7rihwtg6r97agw4iug){
			return ($paramwhye73gra87wg7rihwtg6r97agw4iug)(...$pwhye73gra87wg7rihwtg6r97agw4iug);
		};
		elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug) && file_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure =
		function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){
			return require_once $paramwhye73gra87wg7rihwtg6r97agw4iug;
		};
		elseif(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug) || is_int($paramwhye73gra87wg7rihwtg6r97agw4iug) || is_bool($paramwhye73gra87wg7rihwtg6r97agw4iug) || (is_object($paramwhye73gra87wg7rihwtg6r97agw4iug) && !method_exists($paramwhye73gra87wg7rihwtg6r97agw4iug, "__invoke")) || (is_array($paramwhye73gra87wg7rihwtg6r97agw4iug) && !isset($paramwhye73gra87wg7rihwtg6r97agw4iug['code']) && !isset($paramwhye73gra87wg7rihwtg6r97agw4iug['file'])))$this->closure =
		function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){
			return $paramwhye73gra87wg7rihwtg6r97agw4iug;
		};
		elseif(is_object($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->closure =
		function()use($paramwhye73gra87wg7rihwtg6r97agw4iug){
			return $paramwhye73gra87wg7rihwtg6r97agw4iug();
		};
		elseif(is_array($paramwhye73gra87wg7rihwtg6r97agw4iug)) {
			$uwhye73gra87wg7rihwtg6r97agw4iug = $pwhye73gra87wg7rihwtg6r97agw4iug = '';
			$pwhye73gra87wg7rihwtg6r97agw4iug = implode(',', $paramwhye73gra87wg7rihwtg6r97agw4iug['parameter']);
			if(isset($paramwhye73gra87wg7rihwtg6r97agw4iug['static']) && count($paramwhye73gra87wg7rihwtg6r97agw4iug['static'])> 0) {
				foreach($paramwhye73gra87wg7rihwtg6r97agw4iug['static'] as $keywhye73gra87wg7rihwtg6r97agw4iug => &$valwhye73gra87wg7rihwtg6r97agw4iug) {
					if(!strhave($keywhye73gra87wg7rihwtg6r97agw4iug, '$'))$keywhye73gra87wg7rihwtg6r97agw4iug = '$' . $keywhye73gra87wg7rihwtg6r97agw4iug;
					$uwhye73gra87wg7rihwtg6r97agw4iug.= ",&$keywhye73gra87wg7rihwtg6r97agw4iug";
					$ {
						$keywhye73gra87wg7rihwtg6r97agw4iug
					} = &$valwhye73gra87wg7rihwtg6r97agw4iug;
				}
				$uwhye73gra87wg7rihwtg6r97agw4iug = substr($uwhye73gra87wg7rihwtg6r97agw4iug, 1);
			}
			if(!isset($paramwhye73gra87wg7rihwtg6r97agw4iug['code']))$paramwhye73gra87wg7rihwtg6r97agw4iug['code'] = '';
			if(isset($paramwhye73gra87wg7rihwtg6r97agw4iug['file']))$paramwhye73gra87wg7rihwtg6r97agw4iug['code'].= fget($paramwhye73gra87wg7rihwtg6r97agw4iug['file']);
			$funcwhye73gra87wg7rihwtg6r97agw4iug = "function($pwhye73gra87wg7rihwtg6r97agw4iug)";
			if($uwhye73gra87wg7rihwtg6r97agw4iug)$funcwhye73gra87wg7rihwtg6r97agw4iug.= "use($uwhye73gra87wg7rihwtg6r97agw4iug)";
			if(@$paramwhye73gra87wg7rihwtg6r97agw4iug['type'])$funcwhye73gra87wg7rihwtg6r97agw4iug.= ":" . $paramwhye73gra87wg7rihwtg6r97agw4iug['type'];
			$funcwhye73gra87wg7rihwtg6r97agw4iug.= "{
" . $paramwhye73gra87wg7rihwtg6r97agw4iug['code'] . "
}";
			$this->closure = eval("return $funcwhye73gra87wg7rihwtg6r97agw4iug;");
		}
		if(is_string($paramwhye73gra87wg7rihwtg6r97agw4iug) && function_exists($paramwhye73gra87wg7rihwtg6r97agw4iug))$this->reflection = new ReflectionFunction($paramwhye73gra87wg7rihwtg6r97agw4iug);
		else $this->reflection = new ReflectionFunction($this->closure);
	}
	public function __toString(){
		return array_read(($this->closure)());
	}
	public function __invoke(...$p){
		return ($this->closure)(...$p);
	}
	public function closure($p = false){
		$closure = $this->closure;
		if($p)$this->__construct($p);
		return $closure;
	}
	public function call(...$p){
		return ($this->closure)(...$p);
	}
	public function callArray(array $p){
		if(!is_array($p))return ($this->closure)(...$p);
		return ($this->closure)(...$p);
	}
	public function repeatCall(int $c,...$p){
		while($c-- > 0)($this->closure)(...$p);
	}
	public function repeatCallArray(int $c, array $p){
		while($c-- > 0)$this->callArray($p);
	}
	public function __clone(){
		return new XNClosure($this->closure);
	}
	public function clone(){
		return new XNClosure($this->closure);
	}
	public function parameters(){
		$pars = $this->reflection->getParameters();
		$p = [];
		foreach($pars as $c=>$par) {
			$parr = (array)$par;
			$p[$c] = ["name" => $parr['name']];
			if($par->isDefaultValueAvailable())$p[$c]["default"] = $par->getDefaultValue();
			if($par->hasType())$p[$c]["type"] = $par->getType()->__toString();
			$p[$c]["optional"] = $par->isOptional();
			$p[$c]["variadic"] = $par->isVariadic();
			$p[$c]["passed"] = $par->isPassedByReference();
		}
		return $p;
	}
	public function staticVariables(){
		return $this->reflection->getStaticVariables();
	}
	public function hasReturnType(){
		return $this->reflection->hasReturnType();
	}
	public function getReturnType(){
		if(!$this->reflection->hasReturnType())return false;
		return $this->reflection->getReturnType()->__toString();
	}
	public function parametersCount(){
		return $this->reflection->getNumberOfParameters();
	}
	public function requiredParametersCount(){
		return $this->reflection->getNumberOfRequiredParameters();
	}
	public function getFileName(){
		return $this->reflection->getFileName();
	}
	public function getStartLine(){
		return $this->reflection->getStartLine();
	}
	public function getEndLine(){
		return $this->reflection->getEndLine();
	}
	public function isVariadic(){
		return $this->reflection->isVariadic();
	}
	public function isDisabled(){
		return $this->reflection->isDisabled();
	}
	public function close(){
		$this->closure = null;
		$this->reflection = null;
		$this->functions = null;
	}
	public function getFull(){
		$code = unce($this->closure);
		if($code == XNSERIALIZE_CLOSURE_ERROR)return false;
		return $code;
	}
	public function getCode(){
		$code = unce($this->closure);
		if($code == XNSERIALIZE_CLOSURE_ERROR)return false;
		$start = strpos($code, '{');
		$end = strrpos($code, '}');
		return substr($code, $start + 1, $end - $start - 2);
	}
	public function eval($variables = false){
		$code = $this->getCode();
		if(!$code)return false;
		if($variables == false)$variables = &$GLOBALS;
		foreach($variables as $var => &$val)$$var = &$val;
		return eval($code);
	}
	public function getRunCode($variables = false){
		$code = $this->getCode();
		if(!$code)return false;
		if($variables == false)$variables = $GLOBALS;
		foreach($variables as $key => $val)
		if($key == "GLOBALS" || is_closure($val))unset($variables[$key]);
		$code = "extract(unserialize('" . str_replace(["\\", "'"], ["\\\\", "\\'"], serialize($variables)). "'));\n$code";
		return $code;
	}
	public function changeCode($cod){
		$code = unce($this->closure);
		if($code == XNSERIALIZE_CLOSURE_ERROR)return false;
		$start = strpos($code, '{');
		$end = strrpos($code, '}');
		$codewhye73gra87wg7rihwtg6r97agw4iug = substr_replace($code, $cod, $start + 1, $end - $start - 2);
		$stcwhye73gra87wg7rihwtg6r97agw4iug = $this->reflection->getStaticVariables();
		$func = (
		function()use(&$stcwhye73gra87wg7rihwtg6r97agw4iug, $codewhye73gra87wg7rihwtg6r97agw4iug){
			foreach($stcwhye73gra87wg7rihwtg6r97agw4iug as $namewhye73gra87wg7rihwtg6r97agw4iug => &$valwhye73gra87wg7rihwtg6r97agw4iug)$$namewhye73gra87wg7rihwtg6r97agw4iug = &$valwhye73gra87wg7rihwtg6r97agw4iug;
			return eval("return $codewhye73gra87wg7rihwtg6r97agw4iug;");
		})();
		$this->closure($func);
	}
}
function XNClosure(...$param){
	return new XNClosure(...$param);
}
function XNFunction(...$param){
	return new XNClosure(...$param);
}
function ende_code($d){
	for($c = 0; isset($d[$c]); ++$c)$d[$c] = chr(255 - ord($d[$c]));
	return $d;
}
function chrget(int $chr){
	$chr%= 256;
	return $chr < 0 ? $chr + 256 : $chr;
}
function lowing_str_encode(string $str){
	$l = XNString::min($str)- 1;
	if($l <= 0)return "$str\x00";
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(ord($str[$c])- $l);
	return $str . chr($l);
}
function lowing_str_decode(string $str){
	$l = ord(substr($str, -1));
	$str = substr($str, 0, -1);
	if($l == 0)return $str;
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(ord($str[$c])+ $l);
	return $str;
}
function upping_str_encode(string $str){
	$l = 255 - XNString::max($str);
	if($l <= 0)return "$str\x00";
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(ord($str[$c])+ $l);
	return $str . chr($l);
}
function upping_str_decode(string $str){
	$l = ord(substr($str, -1));
	$str = substr($str, 0, -1);
	if($l == 0)return $str;
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(ord($str[$c])- $l);
	return $str;
}
function str_offset(string $str, string $algo = "x+y"){
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(chrget((int)eval("return " . str_replace(['x', 'y'], [ord($str[$c]), $c], $algo). ";")));
	return $str;
}
function str_roffset(string $str, string $algo = "x+y"){
	$l = strlen($str);
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(chrget((int)eval("return " . str_replace(['x', 'y'], [ord($str[$c]), $l - $c], $algo). ";")));
	return $str;
}
function str_foffset(string $str, string $algo = "x+y"){
	return str_roffset(str_offset($str, $algo), $algo);
}
function str_koffset_encode(string $str, string $key = "\x01"){
	$algo = 'x';
	for($c = 0; isset($key[$c]); ++$c)$algo.= '+' . ord($key[$c]). '*y';
	return str_foffset($str, $algo);
}
function str_koffset_decode(string $str, string $key = "\x01"){
	$algo = 'x';
	for($c = 0; isset($key[$c]); ++$c)$algo.= '-' . ord($key[$c]). '*y';
	return str_foffset($str, $algo);
}
function remote_addr_encode(string $r){
	return pack('c*', explode('.', $r));
}
function remote_addr_decode(string $r){
	return implode('.', unpack('c*', $r));
}
function xncrypt($str, $k = ''){
	$h = substr(crypt($str, md5($k)), 2);
	$h.= substr(crypt($str, md5($str . $h)), 2);
	$c = md5(md5(gettype($k))). md5(md5(gettype($str)));
	$c = strrev($c . hash("md2", $h). substr(base2_encode($c), 2, 2). $h . $c);
	$md5 = strrev(md5(strrev($c . $str . $c)));
	$sha256 = hash("sha256", hex2bin($md5). base64_encode($str). strrev($c));
	$a = 674237347234 % (strlen($str)+ 1);
	$b = 843874507548 % (strlen($str)+ 1);
	$hash = md5($k . strrev(base64_decode($sha256)). substr($md5, $a, $b));
	$hash = hash("md4", $hash). md5(hash("md4", $c . strrev($md5). $hash . $k));
	$hash.= md5(hex2bin($md5). base64_decode($md5). bin2hex($str));
	$hash.= md5(strlen($str) * strlen($k) + 12)[(4798879548975 % (strlen($str)+ 1 + strlen($k))) % 32];
	$hash.= substr(md5($md5 . $c . $str . $k . $md5 . $hash . $sha256 . $a . $b . $str . $k . strlen($str)), 4, 3);
	return $hash;
}
define("SET_BYTES_RIGHT",1);
define("SET_BYTES_LEFT",2);
function set_bytes(string $data,int $count,string $by = "\0",int $type = 2){
	$l = strlen($data);
	if($l % $count == 0)return $data;
	if($type == 1){
		return $data.str_repeat($by,$count - $l % $count);
	}else{
		return str_repeat($by,$count - $l % $count).$data;
	}
}
define("XNSERIALIZE_CLOSURE_ERROR", 46984309873349);
define("XNSERIALIZE_TYPE_INVALID", 80430598870934);
function unce($data){
	switch (gettype($data)) {
	case 'NULL':
		return 'NULL';
		break;
	case 'boolean':
		if($data)return 'true';
		return 'false';
		break;
	case 'string':
		return '"' . str_replace(['"', '\\'], ["\\\"", '\\\\'], $data). '"';
		break;
	case 'integer':
	case 'double':
		return "$data";
		break;
	case 'array':
		$arr = '[';
		$c = 0;
		foreach($data as $k => $v) {
			if($k === $c) {
				$arr.= unce($v). ',';
				++$c;
			}
			else $arr.= unce($k). '=>' . unce($v). ',';
		}
		if($arr == '[')return '[]';
		return substr($arr, 0, -1). ']';
		break;
	case 'object':
		if(is_stdclass($data)) {
			$arr = '{';
			foreach($data as $k => $v) {
				$arr.= unce($k). ':' . unce($v). ',';
			}
			if($arr == '{')return '{}';
			return substr($arr, 0, -1). '}';
		}
		elseif(is_closure($data)) {
			if($data instanceof XNClosure)$data = $data->closure();
			$r = new ReflectionFunction($data);
			$pare = $r->getParameters();
			$pars = [];
			foreach($pare as $k => $p) {
				$pars[$k] = ' *';
				if($p->hasType())$pars[$k].= $p->getType()->__toString(). ' *';
				if($p->isVariadic())$pars[$k].= '\.\.\. *';
				$pars[$k].= '\&{0,1} *\$' . $p->getName(). ' *';
				if($p->isDefaultValueAvailable())$pars[$k].= '= *' . preg_unce($p->getDefaultValue()). ' *';
			}
			$pars = implode(',', $pars);
			$sts = $r->getStaticVariables();
			$stc = [];
			foreach($sts as $k => $v)$stc[] = " *\&{0,1} *\\$$k *";
			if($stc === [])$stc = '';
			else $stc = ' *use\(' . implode(',', $stc). '\)';
			$typa = '';
			if($r->hasReturnType())$typa = " *: *$type";
			$name = $r->getName();
			$name = $name[0] == '{' ? '' : $name;
			$file = file($r->getFileName());
			$file = implode('', array_slice($file, $r->getStartLine()- 1, $r->getEndLine()- $r->getStartLine()+ 1));
			$m = preg_match("/function *$name\($pars\)$stc$typa *\{/", $file, $pa);
			if(!$m) {
				return XNSERIALIZE_CLOSURE_ERROR;
			}
			$po = strpos($file, $pa[0]);
			$file = substr($file, $po + strlen($pa[0]));
			$x = 0;
			$a = false;
			$b = '';
			for($o = 0; isset($file[$o]); ++$o) {
				if($x < 0)break;
				if(!$a) {
					if($file[$o] == '{')++$x;
					elseif($file[$o] == '}')--$x;
					elseif($file[$o] == '"' || $file[$o] == "'") {
						$a = true;
						$b = $file[$o];
					}
				}
				else {
					if($file[$o] == $b)$a = false;
				}
			}
			--$o;
			$file = substr($file, 0, $o);
			return $pa[0] . $file . '}';
		}
	}
}
function preg_unce($data){
	switch (gettype($data)) {
	case 'NULL':
		return '[nN][uU][lL][lL]';
		break;
	case 'boolean':
		if($data)return '[tT][rR][uU][eE]';
		return '[fF][aA][lL][sS][eE]';
		break;
	case 'string':
		return '[\"\\\']\Q' . str_replace('\E', '\E\\\E\Q', $data). '\E[\"\\\']';
		break;
	case 'integer':
	case 'double':
		return "$data";
		break;
	case 'array':
		$arr = '\[ *';
		$c = 0;
		foreach($data as $k => $v) {
			if($k === $c) {
				$arr.= preg_unce($v). ' *\,';
				++$c;
			}
			else $arr.= preg_unce($k). ' *\=\> *' . preg_unce($v). ' *\, *';
		}
		if($arr == '\[ *')return '\[ *\]';
		return substr($arr, 0, -4). '\]';
		break;
	case 'object':
		if(is_stdclass($data)) {
			$arr = '\{ *';
			foreach($data as $k => $v) {
				$arr.= preg_unce($k). ' *: *' . preg_unce($v). ' *\, *';
			}
			if($arr == '\{ *')return '\{ *\}';
			return substr($arr, 0, -4). '\}';
		}
		elseif(is_closure($data)) {
			$r = new ReflectionFunction($data);
			$pare = $r->getParameters();
			$pars = [];
			foreach($pare as $k => $p) {
				$pars[$k] = ' *';
				if($p->hasType())$pars[$k].= $p->getType()->__toString(). ' *';
				if($p->isVariadic())$pars[$k].= '\.\.\. *';
				$pars[$k].= '\&{0,1} *\$' . $p->getName(). ' *';
				if($p->isDefaultValueAvailable())$pars[$k].= '= *' . preg_unce($p->getDefaultValue()). ' *';
			}
			$pars = implode(',', $pars);
			$sts = $r->getStaticVariables();
			$stc = [];
			foreach($sts as $k => $v)$stc[] = " *\&{0,1} *\\$$k *";
			if($stc === [])$stc = '';
			else $stc = ' *use\(' . implode(',', $stc). '\)';
			$typa = '';
			if($r->hasReturnType())$typa = " *: *$type";
			$name = $r->getName();
			$name = $name[0] == '{' ? '' : $name;
			$file = file($r->getFileName());
			$file = implode('', array_slice($file, $r->getStartLine()- 1, $r->getEndLine()- $r->getStartLine()+ 1));
			$m = preg_match("/function *$name\($pars\)$stc$typa *\{/", $file, $pa);
			if(!$m) {
				return XNSERIALIZE_CLOSURE_ERROR;
			}
			$po = strpos($file, $pa[0]);
			$file = substr($file, $po + strlen($pa[0]));
			$x = 0;
			$a = false;
			$b = '';
			for($o = 0; isset($file[$o]); ++$o) {
				if($x < 0)break;
				if(!$a) {
					if($file[$o] == '{')++$x;
					elseif($file[$o] == '}')--$x;
					elseif($file[$o] == '"' || $file[$o] == "'") {
						$a = true;
						$b = $file[$o];
					}
				}
				else {
					if($file[$o] == $b)$a = false;
				}
			}
			--$o;
			$file = substr($file, 0, $o);
			$file = str_replace(['\\', '/', '[', ']', '{', '}', '(', ')', '.', '$', '^', ',', '?', '<', '>', '+', '*', '&', '|', '!', '-', '#'], ['\\\\', '\/', '\[', '\]', '\{', '\}', '\(', '\)', '\.', '\$', '\^', '\,', '\?', '\<', '\>', '\+', '\*', '\&', '\|', '\!', '\-', '\#'], $file);
			return "function *$name\($pars\)$stc$typa *\{ *$file *\}";
		}
	}
}
function xnsize_encode($l){
	$arr = [];
	while($l > 0) {
		$arr[] = $l & 0xff;
		$l >>= 8;
	}
	$size = pack("c*",...$arr);
	return chr(strlen($size)). $size;
}
function xnsize_decode(string $str){
	$size = ord($str[0]);
	$size = substr($str, 1, $size);
	$arr = unpack("c*", $size);
	$size = 0;
	for($c = 1; isset($arr[$c]); ++$c)$size = $size * 255 + $arr[$c];
	return (int)$size;
}
function xnserialize(...$datas){
	$dall = '';
	foreach($datas as $data) {
		$type = gettype($data);
		switch ($type) {
		case "NULL":
			$dtype = 1;
			$data = '';
			break;
		case "boolean":
			if($data)$dtype = 2;
			else $dtype = 3;
			$data = '';
			break;
		case "string":
			$dtype = 4;
			$data = xnsize_encode(strlen($data)). $data;
			break;
		case "integer":
			$dtype = 5;
			$data = chr(strlen($data)). $data;
			break;
		case "double":
			$dtype = 6;
			$m = strlen($data)- strpos($data, '.')- 1;
			$data*= 10 ** $m;
			$data = chr(strlen($data)). chr($m). $data;
			break;
		case "array":
			$dtype = 7;
			$d = [];
			foreach($data as $k => $v) {
				$d[] = $k;
				$d[] = $v;
			}
			$data = xnserialize(...$d);
			$data = xnsize_encode(strlen($data)). $data;
			break;
		case "object":
			if(is_stdclass($data)) {
				$dtype = 8;
				$data = (array)$data;
				$d = [];
				foreach($data as $k => $v) {
					$d[] = $k;
					$d[] = $v;
				}
				$data = xnserialize(...$d);
				$data = xnsize_encode(strlen($data)). $data;
			}
			elseif(is_closure($data)) {
				$dtype = 9;
				$r = new ReflectionFunction($data);
				$pare = $r->getParameters();
				$pars = [];
				$par = '';
				foreach($pare as $k => $p) {
					$t = '';
					$pars[$k] = ' *';
					if($p->hasType()) {
						$t = $p->getType()->__toString(). ';';
						$pars[$k].= $p->getType()->__toString(). ' *';
					}
					if($p->isVariadic()) {
						$t = '.' . $t;
						$pars[$k].= '\.\.\. *';
					}
					if($p->isPassedByReference())$t.= '&';
					$t.= $p->getName();
					$pars[$k].= '\&{0,1} *\$' . $p->getName(). ' *';
					if($p->isDefaultValueAvailable()) {
						$t.= ':' . xnserialize($p->getDefaultValue());
						$pars[$k].= '= *' . preg_unce($p->getDefaultValue()). ' *';
					}
					$par.= xnsize_encode(strlen($t)). $t;
				}
				$par = xnsize_encode(strlen($par)). $par;
				$pars = implode(',', $pars);
				$sts = $r->getStaticVariables();
				$stc = [];
				foreach($sts as $k => $v)$stc[] = " *\&{0,1} *\\$$k *";
				if($stc === [])$stc = '';
				else $stc = ' *use\(' . implode(',', $stc). '\)';
				$sts = substr(xnserialize($sts), 1);
				if($sts == "\x00")$sts = "\x01\x01\x01";
				$typa = '';
				if($r->hasReturnType()) {
					$type = $r->getReturnType();
					$typa = " *: *$type";
					$type = xnsize_encode(strlen($type)). $type;
				}
				else $type = "\x01\x01\x01";
				$name = $r->getName();
				$name = $name[0] == '{' ? '' : $name;
				$file = file($r->getFileName());
				$file = implode('', array_slice($file, $r->getStartLine()- 1, $r->getEndLine()- $r->getStartLine()+ 1));
				$m = preg_match("/function *$name\($pars\)$stc$typa *\{/", $file, $pa);
				return XNSERIALIZE_CLOSURE_ERROR;
				$po = strpos($file, $pa[0]);
				$file = substr($file, $po + strlen($pa[0]));
				$x = 0;
				$a = false;
				$b = '';
				for($o = 0; isset($file[$o]); ++$o) {
					if($x < 0)break;
					if(!$a) {
						if($file[$o] == '{')++$x;
						elseif($file[$o] == '}')--$x;
						elseif($file[$o] == '"' || $file[$o] == "'") {
							$a = true;
							$b = $file[$o];
						}
					}
					else {
						if($file[$o] == $b)$a = false;
					}
				}
				--$o;
				$file = substr($file, 0, $o);
				$file = xnsize_encode(strlen($file)). $file;
				if($file == "\x00")$file = "\x01\x01\x01";
				$data = $par . $sts . $type . $file;
				$data = xnsize_encode(strlen($data)). $data;
			}
			else {
				$dtype = 10;
				$name = get_class($data);
				$data = (array)$data;
				$d = [];
				foreach($data as $k => $v) {
					$d[] = $k;
					$d[] = $v;
				}
				$data = xnserialize(...$d);
				$data = xnsize_encode(strlen($name)). $name . $data;
				$data = xnsize_encode(strlen($data)). $data;
			}
			break;
		default:
			return XNSERIALIZE_TYPE_INVALID;
		}
		$dall.= chr($dtype). $data;
	}
	return $dall;
}
function xnunserialize($datas){
	$u = strlen($datas);
	$dall = [];
	for($c = 0; $c < $u;) {
		$type = ord($datas[$c++]);
		switch ($type) {
		case 1:
			$data = null;
			break;
		case 2:
			$data = true;
			break;
		case 3:
			$data = false;
			break;
		case 4:
			$l = ord($datas[$c++]);
			$size = substr($datas, $c, $l);
			$size = xnsize_decode(chr($l). $size);
			$c+= $l;
			$data = substr($datas, $c, $size);
			$c+= $size;
			break;
		case 5:
			$l = ord($datas[$c++]);
			$data = (int)substr($datas, $c, $l);
			$c+= $l;
			break;
		case 6:
			$l = ord($datas[$c++]);
			$m = ord($datas[$c++]);
			$data = (double)substr($datas, $c, $l);
			$c+= $l;
			break;
		case 7:
			$l = ord($datas[$c++]);
			$size = substr($datas, $c, $l);
			$size = xnsize_decode(chr($l). $size);
			$c+= $l;
			$data = substr($datas, $c, $size);
			$c+= $size;
			$d = xnunserialize($data);
			$data = [];
			for($o = 0; isset($d[$o]); $o+= 2)$data[$d[$o]] = $d[$o + 1];
			break;
		case 8:
			$l = ord($datas[$c++]);
			$size = substr($datas, $c, $l);
			$size = xnsize_decode(chr($l). $size);
			$c+= $l;
			$data = substr($datas, $c, $size);
			$c+= $size;
			$d = xnunserialize($data);
			$data = [];
			for($o = 0; isset($d[$o]); $o+= 2)$data[$d[$o]] = $d[$o + 1];
			$data = (object)$data;
			break;
		case 9:
			$l = ord($datas[$c++]);
			$size = substr($datas, $c, $l);
			$size = xnsize_decode(chr($l). $size);
			$c+= $l;
			$data = substr($datas, $c, $size);
			$c+= $size;
			$cl = 0;
			$parl = ord($data[$cl++]);
			$pars = substr($data, $cl, $parl);
			$cl+= $parl;
			$pars = xnsize_decode(chr($parl). $pars);
			$par = substr($data, $cl, $pars);
			$cl+= $pars;
			$stcl = ord($data[$cl++]);
			$stcs = substr($data, $cl, $stcl);
			$cl+= $stcl;
			$stcs = xnsize_decode(chr($stcl). $stcs);
			$stc = substr($data, $cl, $stcs);
			$cl+= $stcs;
			$typl = ord($data[$cl++]);
			$typs = substr($data, $cl, $typl);
			$cl+= $typl;
			$typs = xnsize_decode(chr($typl). $typs);
			$typ = substr($data, $cl, $typs);
			$cl+= $typs;
			$fill = ord($data[$cl++]);
			$fils = substr($data, $cl, $fill);
			$cl+= $fill;
			$fils = xnsize_decode(chr($fill). $fils);
			$fil = substr($data, $cl, $fils);
			$cl+= $fils;
			$pars = [];
			if($par != "\x01") {
				$ll = strlen($par);
				$pv = 0;
				for($cl = 0; $cl < $ll; ++$pv) {
					$pl = ord($par[$cl++]);
					$ps = substr($par, $cl, $pl);
					$cl+= $pl;
					$ps = xnsize_decode(chr($pl). $ps);
					$p = substr($par, $cl, $ps);
					$cl+= $ps;
					$kc = 0;
					$pars[$pv] = '';
					if($p[0] == '.') {
						++$kc;
						$pars[$pv].= '...';
					}
					if(strhave($p, ';')) {
						$ps = strpos($p, ';');
						$pt = substr($p, $kc, $ps - $kc);
						$kc+= $ps + 1;
						$pars[$pv] = $pt . ' ' . $pars[$pv];
					}
					if($p[$kc] == '&') {
						$pars[$pv].= '&';
						++$kc;
					}
					if(strhave($p, ':')) {
						$ps = strpos($p, ':');
						$pn = substr($p, $kc, $ps - $kc);
						$pu = substr($p, $ps + 1);
						$kc+= $ps + 1;
						$pars[$pv].= '$' . $pn . '=' . unce(xnunserialize($pu));
					}
					else $pars[$pv].= '$' . substr($p, $kc);
				}
			}
			$pars = implode(',', $pars);
			$stcs = xnunserialize("\x07" . xnsize_encode(strlen($stc)). $stc);
			$stc = [];
			foreach($stcs as $k => $v)$stc[] = "$$k";
			$stc = implode(',', $stc);
			if($stc)$stc = "use($stc)";
			$type = $typ == "\x01" ? '' : ':' . $typ;
			$file = $fil == "\x01" ? '' : $fil;
			$func = "function($pars)$stc$type{
$file
}";
			$data = (
			function()use($stcs, $func){
				foreach($stcs as $k => $v)$$k = $v;
				return eval("return $func;");
			})();
			break;
		case 10:
			$l = ord($datas[$c++]);
			$size = substr($datas, $c, $l);
			$c+= $l;
			$size = xnsize_decode(chr($l). $size);
			$data = substr($datas, $c, $size);
			$c+= $size;
			$pc = 0;
			$l = ord($data[$pc++]);
			$size = substr($data, $pc, $l);
			$pc+= $l;
			$size = xnsize_decode(chr($l). $size);
			$name = substr($data, $pc, $size);
			$pc+= $size;
			$data = substr($data, $pc);
			$d = xnunserialize($data);
			$data = [];
			for($o = 0; isset($d[$o]); $o+= 2)$data[$d[$o]] = $d[$o + 1];
			$data = serialize((object)$data);
			$data = replace_first("8:\"stdClass\"", strlen($name). ":\"$name\"", $data);
			$data = unserialize($data);
			break;
		case 11:
			break;
		default:
			return XNSERIALIZE_TYPE_INVALID;
		}
		$dall[] = $data;
	}
	if(count($dall) == 1)return $dall[0];
	return $dall;
}
function xnserialize_error_name($error){
	if($error === XNSERIALIZE_TYPE_INVALID)return "XNSERIALIZE_TYPE_INVALID";
	if($error === XNSERIALIZE_CLOSURE_ERROR)return "XNSERIALIZE_CLOSURE_ERROR";
	return false;
}
function set_class_var(object &$class, string $type = "public", $key, $value){
	$name = get_class($class);
	$class = (array)$class;
	if($type == "public");
	elseif($type == "private")$key = "\x00a\x00$key";
	elseif($type == "protected")$key = "\x00*\x00$key";
	$class[$key] = $value;
	$class = serialize((object)$class);
	$class = replace_first("8:\"stdClass\"", strlen($name). ":\"$name\"", $class);
	$class = unserialize($class);
}
function delete_class_var(object &$class, string $type = "public", $key){
	$name = get_class($class);
	$class = (array)$class;
	if($type == "public");
	elseif($type == "private")$key = "\x00a\x00$key";
	elseif($type == "protected")$key = "\x00*\x00$key";
	unset($class[$key]);
	$class = serialize((object)$class);
	$class = replace_first("8:\"stdClass\"", strlen($name). ":\"$name\"", $class);
	$class = unserialize($class);
}
function get_class_all_vars(object $class){
	$name = get_class($class);
	$class = (array)$class;
	$vars = ['public' => [], "private" => [], "protected" => []];
	foreach($class as $k => $v) {
		if(@$k[1] == '')$vars['public'][$k] = $v;
		elseif($k[1] == 'a' && $k[0] == "\x00")$vars['private'][substr($k, 3)] = $v;
		elseif($k[1] == '*' && $k[0] == "\x00")$vars['protected'][substr($k, 3)] = $v;
		else $vars['public'][$k] = $v;
	}
	return $vars;
}
function get_class_var(object $class, string $type, $key){
	$name = get_class($class);
	$class = (array)$class;
	if($type == "public");
	elseif($type == "private")$key = "\x00a\x00$key";
	elseif($type == "protected")$key = "\x00*\x00$key";
	return $class[$key];
}
function convert_class(object &$class, string $to){
	$name = get_class($class);
	$class = serialize($class);
	$name = strlen($name). ":\"$name\"";
	$to = strlen($to). ":\"$to\"";
	$class = replace_first($name, $to, $class);
	$class = unserialize($class);
}
function get_class_var_type(object $class, $key){
	$name = get_class($class);
	$class = (array)$class;
	return isset($class["$key"])? "public" : isset($class["\x00a\x00$key"])? "private" : isset($class["\x00*\x00$key"])? "protected" : false;
}
function class_var_exists(object $class, $key){
	return get_class_var_type($class, $key) !== false;
}
class XNNumber {
	// consts variables
	public static function PI($l = - 1){
		$pi = xndata("pi");
		if($l < 0)return $pi;
		if($l <= 2)return substr($pi, 0, 1);
		return substr($pi, 0, $l + 2);
	}
	public static function PHI($l = - 1){
		$phi = xndata("phi");
		if($l < 0)return $phi;
		if($l <= 2)return substr($phi, 0, 1);
		return substr($phi, 0, $l + 2);
	}
	// validator
	public static function is_number($a){
		return is_numeric($a);
	}
	// system functions
	public static function ickeck($a){
		$b = false;
		for($c = 0; isset($a[$c]); ++$c){
			$h = $a[$c];
			if($h == '.' && $c > 0 && isset($a[$c + 1])){
				if($b){
					if(strlen($a)> 20)$a = substr($a, 0, 12). '...' . substr($a, -5);
					new XNError("XNNumber", "invalid number \"$a\".", XNError::ARITHMETIC);
					return false;
				}
				$b = true;
			}
			elseif($a !== 0 && $a !== 1 && $a !== 2 && $a !== 3 && $a !== 4 && $a !== 5 && $a !== 6 && $a !== 7 && $a !== 8 && $a !== 9){
				if(strlen($a)> 20)$a = substr($a, 0, 12). '...' . substr($a, -5);
				new XNError("XNNumber", "invalid number \"$a\".", XNError::ARITHMETIC);
				return false;
			}
		}
		return true;
	}
	public static function _check($a){
		if(!is_numeric($a)){
			if(strlen($a)> 20)$a = substr($a, 0, 12). '...' . substr($a, -5);
			new XNError("XNNumber", "invalid number \"$a\".", XNError::ARITHMETIC);
			return false;
		}
		return true;
	}
	public static function _view($a){
		if($a[0] == '-')return true;
		return false;
	}
	public static function abs($a){
		if($a[0] == '-' || $a[0] == '+')return substr($a, 1);
		return $a;
	}
	public static function _change($a){
		if($a == 0)return '0';
		if($a[0] == '-')return substr($a, 1);
		if($a[0] == '+')return '-' . substr($a, 1);
		return '-' . $a;
	}
	public static function _get0($a){
		$a = ltrim($a, '0');
		return $a ? $a : '0';
	}
	public static function _get1($a){
		$a = rtrim($a, '0');
		return $a ? $a : '0';
	}
	public static function _get2($a){
		$a = self::_mo($a);
		$a[1] = isset($a[1])? $a[1] : '0';
		$a[0] = self::_get0($a[0]);
		$a[1] = self::_get1($a[1]);
		if($a[0] && $a[1])return "{$a[0]}.{$a[1]}";
		if($a[1])return "0.{$a[1]}";
		if($a[0])return "{$a[0]}";
		return "0";
	}
	public static function _get3($a){
		if(self::_view($a))return '-' . self::_get2(self::abs($a));
		return self::_get2(self::abs($a));
	}
	public static function _get($a){
		if(!self::_check($a))return false;
		return self::_get3($a);
	}
	public static function _set0($a, $b){
		$l = strlen($b)- strlen($a);
		if($l <= 0)return $a;
		else return str_repeat('0', $l). $a;
	}
	public static function _set1($a, $b){
		$l = strlen($b)- strlen($a);
		if($l <= 0)return $a;
		else return $a . str_repeat('0', $l);
	}
	public static function _set2($a, $b){
		$a = self::_mo($a);
		$b = self::_mo($b);
		if(!isset($a[1]) && isset($b[1])) {
			$a[1] = '0';
		}
		if(isset($a[1]))$a[1] = self::_set1($a[1], @$b[1]);
		$a[0] = self::_set0($a[0], $b[0]);
		if(!isset($a[1]))return "{$a[0]}";
		return "{$a[0]}.{$a[1]}";
	}
	public static function _set3($a, $b){
		if(self::_view($a) && self::_view($b))return '-' . self::_set2(self::abs($a), self::abs($b));
		if(!self::_view($a) && self::_view($b))return self::_set2(self::abs($a), self::abs($b));
		if(self::_view($a) && !self::_view($b))return '-' . self::_set2(self::abs($a), self::abs($b));
		return self::_set2(self::abs($a), self::abs($b));
	}
	public static function _set($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::_set3($a, $b);
	}
	public static function _full($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::_set(self::_get($a), self::_get($b));
	}
	public static function _setfull(&$a, &$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		$a = self::_get($a);
		$b = self::_get($b);
		$a = self::_set($a, $b);
		$b = self::_set($b, $a);
	}
	public static function _mo($a){
		return explode('.', $a);
	}
	public static function _lm($a){
		return strpos($a, '.');
	}
	public static function _im($a){
		$p = self::_lm($a);
		return $p !== false && $p != - 1;
	}
	public static function _nm($a){
		return str_replace('.', '', $a);
	}
	public static function _st($a, $b){
		if(!isset($a[$b]) || $b == 0)return $a;
		return substr_replace($a, '.', $b, 0);
	}
	public static function _iz($a){
		$a = $a[strlen($a)- 1];
		return $a == '0' || $a == '2' || $a == '4' || $a == '6' || $a == '8';
	}
	public static function _if($a){
		$a = $a[strlen($a)- 1];
		return $a == '1' || $a == '3' || $a == '5' || $a == '7' || $a == '9';
	}
	public static function _so($a, $b){
		$l = strlen($a)% $b;
		if($l == 0)return $a;
		else return str_repeat('0', $b - $l). $a;
	}
	public static function _pl($a){
		$l = '0';
		while($a != $l) {
			$l = $a;
			$a = str_replace(['--', '-+', '+-', '++'], ['+', '-', '-', '+'], $a);
		}
		return $a;
	}
	// retry calc functions
	public static function _powTen0($a, $b){
		$p = self::_lm($a);
		$i = $p === false || $p == - 1;
		$a = self::_nm($a);
		$l = strlen($a);
		if($i)$s = strlen($a)+ $b;
		else $s = $p + $b;
		if($s == $l)return $a;
		if($s > $l)return $a . str_repeat('0', $s - $l);
		if($s == 0)return "0.$a";
		if($s < 0)return "0." . str_repeat('0', abs($s)). $a;
		return substr_replace($a, ".", $s, 0);
	}
	public static function _powTen1($a, $b){
		if(self::_view($a))return '-' . self::_powTen0(self::abs($a), $b);
		return self::_powTen0(self::abs($a), $b);
	}
	public static function powTen($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::_get(self::_powTen1($a, $b));
	}
	public static function _mulTwo0($a){
		$a = str_split($a, 13);
		$c = count($a)- 1;
		$o = 0;
		while($c >= 0) {
			$a[$c]*= 2;
			$a[$c]+= $o;
			$o = $k = 0;
			while(@$a[$c - $k] > 9999999999999) {
				$o = 1;
				$a[$c - $k]-= 10000000000000;
				++$k;
			}
			$a[$c] = self::_so($a[$c], 13);
			--$c;
		}
		return implode('', $a);
	}
	public static function _mulTwo1($a){
		$a = self::_mo($a);
		$a[0] = self::_so($a[0], 13);
		$a[0] = self::_mulTwo0("0000000000000{$a[0]}");
		if(isset($a[1])) {
			$l = strlen($a[1]);
			$a[1] = self::_so($a[1], 13);
			$a[1] = self::_mulTwo0("0000000000000{$a[1]}");
			$a[2] = substr($a[1], 0, -$l);
			$a[1] = substr($a[1], -$l);
			if($a[2] > 0)$a[0] = self::_add0("0000000000000{$a[0]}", "0000000000000" . str_repeat('0', strlen($a[0])- 1). '1');
			return "{$a[0]}.{$a[1]}";
		}
		return $a[0];
	}
	public static function _mulTwo2($a){
		if(self::_view($a))return '-' . self::_mulTwo1(self::abs($a));
		return self::_mulTwo1(self::abs($a));
	}
	public static function mulTwo($a){
		if(!self::_check($a))return false;
		return self::_get3(self::_mulTwo2(self::_get3($a)));
	}
	public static function _divTwo0($a){
		$s = '';
		$c = 0;
		$k = false;
		while(isset($a[$c])) {
			$h = substr($a, $c, 14);
			$b = floor($h / 2);
			$b = $k ? $b + 50000000000000 : $b;
			$s.= self::_so($b, 14);
			if($h % 2 == 1)$k = true;
			$c+= 14;
		}
		if($k)$s.= '5';
		return $s;
	}
	public static function _divTwo1($a){
		$p = self::_lm($a);
		$a = self::_nm($a);
		if($p === false || $p == - 1)$p = strlen($a);
		$l = strlen($a);
		$a = self::_so($a, 14);
		$p+= strlen($a)- $l;
		$a = self::_divTwo0($a);
		return self::_st($a, $p);
	}
	public static function _divTwo2($a){
		if(self::_view($a))return '-' . self::_divTwo1(self::abs($a));
		return self::_divTwo1(self::abs($a));
	}
	public static function divTwo($a,bool $limit = false){
		if($limit)
			return self::floor(self::_get(self::_divTwo2(self::_get($a))));
		return self::_get(self::_divTwo2(self::_get($a)));
	}
	public static function _powTwo0($a){
		$a = str_split($a, 1);
		$x = false;
		$c = $d = count($a)- 1;
		$k = 0;
		while($c >= 0) {
			$y = '';
			$e = $d;
			$s = 0;
			while($e >= 0) {
				$t = $a[$c] * $a[$e] + $s;
				$s = floor($t / 10);
				$t-= $s * 10;
				$y = $t . $y;
				--$e;
			}
			--$c;
			$t = $s . $y . ($k ? str_repeat('0', $k): '');
			$x = $x ? self::add($x, $t): $t;
			++$k;
		}
		return $x;
	}
	public static function _powTwo1($a){
		$p = self::_lm($a);
		if(!$p)return self::_powTwo0($a);
		$p = strlen($a)- $p - 1;
		$p*= 2;
		$a = self::_nm($a);
		$a = '0' . self::_powTwo0($a);
		return self::_st($a, strlen($a)- $p);
	}
	public static function _powTwo2($a){
		return self::_powTwo1(self::abs($a));
	}
	public static function powTwo($a){
		if(!self::_check($a))return false;
		return self::_get3(self::_powTwo2(self::_get3($a)));
	}
	// set functions
	public static function floor($a){
		if(!self::_check($a))return false;
		return explode('.', "$a")[0];
	}
	public static function ceil($a){
		if(!self::_check($a))return false;
		$a = explode('.', "$a");
		return isset($a[1])? self::add($a[0], '1'): $a[0];
	}
	public static function round($a){
		if(!self::_check($a))return false;
		$a = explode('.', "$a");
		return isset($a[1]) && $a[1][0] >= 5 ? self::add($a[0], '1'): $a[0];
	}
	public static function is_floor($a){
		return strpos($a,'.') > 0;
	}
	// calc functions
	public static function _add0($a, $b){
		$a = str_split($a, 13);
		$b = str_split($b, 13);
		$c = count($a)- 1;
		while($c >= 0) {
			$a[$c]+= $b[$c];
			$k = 0;
			while(isset($a[$c - $k]) && $a[$c - $k] > 9999999999999) {
				$a[$c - $k - 1]+= 1;
				$a[$c - $k]-= 10000000000000;
				++$k;
			}
			$a[$c] = self::_so($a[$c], 13);
			--$c;
		}
		return implode('', $a);
	}
	public static function _add1($a, $b){
		$a = "0000000000000$a";
		$b = "0000000000000$b";
		$o = self::_lm($a);
		$p = $o + (13 - (strlen($a)- 1)% 13);
		$a = self::_so(self::_nm($a), 13);
		$b = self::_so(self::_nm($b), 13);
		if($o !== false && $o !== - 1)return self::_st(self::_add0($a, $b), $p);
		return self::_add0($a, $b);
	}
	public static function _add2($a, $b){
		if(self::_view($a) && self::_view($b))return '-' . self::_add1(self::abs($a), self::abs($b));
		if(self::_view($a) && !self::_view($b))return self::rem(self::abs($b), self::abs($a));
		if(!self::_view($a) && self::_view($b))return self::rem(self::abs($a), self::abs($b));
		return self::_add1(self::abs($a), self::abs($b));
	}
	public static function add($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) <= 13 && strlen($b) <= 13)
			return (string)($a + $b);
		self::_setfull($a, $b);
		if($a == 0)return $b;
		if($b == 0)return $a;
		if($a == $b)return self::mulTwo($a);
		return self::_get3(self::_add2($a, $b));
	}
	public function _rem0($a, $b){
		$a = str_split($a, 13);
		$b = str_split($b, 13);
		$c = count($a)- 1;
		while($c >= 0) {
			$a[$c]-= $b[$c];
			$k = 0;
			while(isset($a[$c - $k - 1]) && $a[$c - $k] < 0) {
				$a[$c - $k - 1]-= 1;
				$a[$c - $k]+= 10000000000000;
				++$k;
			}
			$a[$c] = self::_so($a[$c], 13);
			--$c;
		}
		return implode('', $a);
	}
	public static function _rem1($a, $b){
		$o = self::_lm($a);
		$p = $o + (13 - (strlen($a)- 1)% 13);
		$a = self::_so(self::_nm($a), 13);
		$b = self::_so(self::_nm($b), 13);
		if($o !== false && $o !== - 1)return self::_st(self::_rem0($a, $b), $p);
		return self::_rem0($a, $b);
	}
	public static function _rem2($a, $b){
		if(self::_view($a) && self::_view($b))return '-' . self::_rem1(self::abs($a), self::abs($b));
		if(self::_view($a) && !self::_view($b))return '-' . self::_add1(self::abs($a), self::abs($b));
		if(!self::_view($a) && self::_view($b))return self::_add1(self::abs($a), self::abs($b));
		return self::_rem1(self::abs($a), self::abs($b));
	}
	public static function _rem3($a, $b){
		if($a < $b) {
			return '-' . self::_rem2($b, $a);
		}
		return self::_rem2($a, $b);
	}
	public static function rem($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) <= 13 && strlen($b) <= 13)
			return (string)($a - $b);
		self::_setfull($a, $b);
		$r = $a == 0 ? self::_change($b): $b == 0 ? $a : self::_rem3($a, $b);
		return self::_pl(self::_get3($r));
	}
	public static function _mul0($a, $b){
		$a = str_split($a, 1);
		$b = str_split($b, 1);
		$x = false;
		$c = $d = count($a)- 1;
		$k = 0;
		while($c >= 0) {
			$y = '';
			$e = $d;
			$s = 0;
			while($e >= 0) {
				$t = $a[$c] * $b[$e] + $s;
				$s = floor($t / 10);
				$t%= 10;
				$y = $t . $y;
				--$e;
			}
			--$c;
			$t = $s . $y . ($k ? str_repeat('0', $k): '');
			$x = $x ? self::add($x, $t): $t;
			++$k;
		}
		return $x;
	}
	public static function _mul1($a, $b){
		$ap = self::_lm($a);
		$bp = self::_lm($b);
		if(!$ap)return self::_mul0($a, $b);
		$ap = strlen($a)- $ap - 1;
		$bp = strlen($b)- $bp - 1;
		$p = $ap + $bp;
		$a = self::_nm($a);
		$b = self::_nm($b);
		$a = '0' . self::_mul0($a, $b);
		return self::_st($a, strlen($a)- $p);
	}
	public static function _mul2($a, $b){
		if(self::_view($a) && self::_view($b))return self::_mul1(self::abs($a), self::abs($b));
		if(!self::_view($a) && self::_view($b))return '-' . self::_mul1(self::abs($a), self::abs($b));
		if(self::_view($a) && !self::_view($b))return '-' . self::_mul1(self::abs($a), self::abs($b));
		return self::_mul1(self::abs($a), self::abs($b));
	}
	public static function mul($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) + strlen($b) <= 12)
			return (string)($a * $b);
		self::_setfull($a, $b);
		if($a == 0 || $b == 0)return '0';
		if($a == 1)return "$b";
		if($b == 1)return "$a";
		if($a == 2)return self::mulTwo($b);
		if($b == 2)return self::mulTwo($a);
		if($a == $b)return self::powTwo($a);
		return self::_get3(self::_mul2($a, $b));
	}
	public static function _rand0($a){
		$rand = "0.";
		$b = floor($a / 9);
		for($c = 0; $c < $b; ++$c) {
			$rand.= self::_so(rand(0, 999999999), 9);
		}
		if($a % 9 == 0)return $rand;
		return $rand . self::_so(rand(0, str_repeat('9', $a % 9)), $a % 9);
	}
	public static function _rand1($a, $b){
		$c = self::rem($a, $b);
		$d = self::_rand0(strlen($a));
		return self::add(self::floor(self::mul(self::add($c, '1'), $d)), $b);
	}
	public static function _rand2($a, $b){
		$p = self::_lm($a);
		if(!$p)return self::_rand1($a, $b);
		$p = strlen($a)- $p - 1;
		$a = self::_nm($a);
		$b = self::_nm($b);
		$a = '0' . self::_rand1($a, $b);
		return self::_st($a, strlen($a)- $p);
	}
	public static function _rand3($b, $a){
		if($a > $b)return self::_rand2($a, $b);
		return self::_rand2($b, $a);
	}
	public static function _rand4($a, $b){
		if(self::_view($a) && self::_view($b))return '-' . self::_rand3(self::abs($a), self::abs($b));
		if(!self::_view($a) && self::_view($b)) {
			return self::_change(self::rem(self::_rand3('0', self::add(self::abs($a), self::abs($b))), $a));
		}
		if(self::_view($a) && !self::_view($b)) {
			return self::_change(self::rem(self::_rand3('0', self::add(self::abs($a), self::abs($b))), $b));
		}
		return self::_rand3(self::abs($a), self::abs($b));
	}
	public static function rand($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		self::_setfull($a, $b);
		$r = $a == $b ? $a : self::_rand4($a, $b);
		return self::_get($r);
	}
	public static function lcg(int $length = 100){
		return self::_get(self::_rand0($length));
	}
	public static function _div0($a, $b){
		if($b > $a)return 0;
		if(($c = self::mulTwo($b))> $a)return 1;
		if(($d = self::mul($b, '3'))> $a)return 2;
		if(($c = self::mulTwo($c))> $a)return 3;
		if(self::mul($b, '5')> $a)return 4;
		if(self::mulTwo($d)> $a)return 5;
		if(self::mul($b, '7')> $a)return 6;
		if(self::mulTwo($c)> $a)return 7;
		if(self::mul($b, '9')> $a)return 8;
		return 9;
	}
	public static function _div1($a, $b, $o = 0){
		$a = str_split($a, 1);
		$p = $r = $i = $d = '0';
		$c = count($a);
		while($i < $c) {
			$d.= $a[$i];
			if($d >= $b) {
				$p = self::_div0($d, $b);
				$d = self::rem($d, self::mul($p, $b));
				$r.= $p;
			}
			else $r.= '0';
			++$i;
		}
		if($d == 0 || $o <= 0)return $r;
		$r.= '.';
		while($d > 0 && $o > 0) {
			$d.= '0';
			if($d >= $b) {
				$p = self::_div0($d, $b);
				$d = self::rem($d, self::mul($p, $b));
				$r.= $p;
			}
			else $r.= '0';
			--$o;
		}
		return $r;
	}
	public static function _div2($a, $b, $c = 0){
		$a = self::_nm($a);
		$b = self::_nm($b);
		if($c < 0)$c = 0;
		return self::_div1($a, $b, $c);
	}
	public static function _div3($a, $b, $c = 0){
		if(self::_view($a) && self::_view($b))return self::_div2(self::abs($a), self::abs($b), $c);
		if(self::_view($a) && !self::_view($b))return '-' . self::_div2(self::abs($a), self::abs($b), $c);
		if(!self::_view($a) && self::_view($b))return '-' . self::_div2(self::abs($a), self::abs($b), $c);
		return self::_div2(self::abs($a), self::abs($b), $c);
	}
	public static function div($a, $b, $c = 0){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		self::_setfull($a, $b);
		if($b == 0) {
			new XNError("XNNumber", "not can div by Ziro", XNError::ARITHMETIC);
			return false;
		}
		if($a == 0)return '0';
		if($b == 1)return "$a";
		if($a == $b)return '1';
		return self::_get2(self::_div3($a, $b, $c));
	}
	public static function _mod0($a, $b){
		$a = str_split($a, 1);
		$p = $r = $i = $d = '0';
		$c = count($a);
		while($i < $c) {
			$d.= $a[$i];
			if($d >= $b) {
				$p = self::_div0($d, $b);
				$d = self::rem($d, self::mul($p, $b));
				$r.= $p;
			}
			else $r.= '0';
			++$i;
		}
		return $d;
	}
	public static function _mod1($a, $b){
		$a = self::floor($a);
		$b = self::floor($b);
		return self::_mod0($a, $b);
	}
	public static function _mod2($a, $b){
		if(self::_view($a))return '-' . self::_mod1(self::abs($a), self::abs($b));
		return self::_mod1(self::abs($a), self::abs($b));
	}
	public static function mod($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		self::_setfull($a, $b);
		if($b == 0) {
			new XNError("XNNumber", "not can div by Ziro", XNError::ARITHMETIC);
			return false;
		}
		if($a == 0 || $b == 1 || $a == $b)return '0';
		return self::_get(self::_mod2($a, $b));
	}
	public static function _powFloor($a,$b,int $limit = 10){
		$b = self::floor($b);
		if($a == 1 || $a == 0)
			return $a;
		if($a == -1)
			return self::_iz($b)?'1':'-1';
		if($b == 0)
			return '1';
		if($b == 1)
			return $a;
		if(self::_iz($b))
			return self::powFloor(self::powTwo($a),self::divTwo($b));
		else
			return self::mul(self::powFloor(self::powTwo($a),self::divTwo($b)),$a);
	}
	public static function powFloor($a,$b,int $limit = 10){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) * $b <= 10)
			return (string)($a ** $b);
		if($b < 0)
			return self::div('1',self::_powFloor($a,substr_replace($b,'',0,1)),$limit);
		return self::_powFloor($a,$b,$limit);
	}
	// algo functions
	public static function fact($a){
		if(!self::_check($a))return false;
		if($a <= 1)return 1;
		if($a <= 16)return (string)XNMath::fact($a);
		$r = '1';
		while($a > 0) {
			$r = self::mul($r, $a);
			$a = self::rem($a, '1');
		}
		return $r;
	}
	public static function rmod($x, $y){
		return self::rem($x, self::mul(self::div($x, $y, 0), $y));
	}
	public static function gcd($a, $b){
		return $b ? self::gcd($b, self::mod($a, $b)): $a;
	}
	public static function time(){
		$time = microtime();
		return self::_get(substr($time,11).'.'.substr($time,2,8));
	}
	public static function sin($x, int $limpi = 15, int $while = 30, int $limit = 10){
		$x = self::rmod($x, self::mulTwo(self::PI($limpi)));
		$res = '0';
		$pow = $x;
		$fact = '1';
		for($i = 1;$i < $while;++$i){
			$res = self::add($res,self::div($pow,$fact,$limit));
			$pow = self::mul($pow,self::_change(self::powTwo($x)));
			$fact = self::mul($fact,self::mul(self::mulTwo($i),self::add(self::mulTwo($i),1)));
		}
		return $res;
	}
	public static function max(...$numbers){
		return max($numbers);
	}
	public static function min(...$numbers){
		return min($numbers);
	}
	// binary functions
	public static function xor($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::xor(self::base_convert($a,10,2),self::base_convert($b,10,2)),2);
	}
	public static function and($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::and(self::base_convert($a,10,2),self::base_convert($b,10,2)),2);
	}
	public static function or($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::or(self::base_convert($a,10,2),self::base_convert($b,10,2)),2);
	}
	public static function lshift($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::lshift(self::base_convert($a,10,2),$b),2);
	}
	public static function rshift($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::rshift(self::base_convert($a,10,2),$b),2);
	}
	public static function shift($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(XNBinary::shift(self::base_convert($a,10,2),$b),2);
	}
	// convertor functions
	public static function to_number($a = '0'){
		if(!self::_check($a))return false;
		return $a * 1;
	}
	public static function toXNNumber($a = 0){
		if($a == NAN || $a == INF) {
			if(strlen($a)> 20)$a = substr($a, 0, 12). '...' . substr($a, -5);
			new XNError("XNNumber", "the '$a' not is a number",	XNError::ARITHMETIC);
			return false;
		}
		$a = explode('E', $a);
		if(!isset($a[1]))return "{$a[0]}";
		$a = self::powTen($a[0], $a[1]);
		return $a;
	}
	public static function init($number, $init = 10){
		return self::base_convert($number, $init, 10);
	}
	// parser functions
	public static function baseconvert($text, $from = false, $to = false){
		if(is_string($from) && strtolower($from) == "ascii")return self::baseconvert(bin2hex($text), "0123456789abcdef", $to);
		if(is_string($to) && strtolower($to) == "ascii"){
			$r = self::baseconvert($text, $from, "0123456789abcdef");
			if(strlen($r) % 2 == 1)$r = '0'.$r;
			return hex2bin($r);
		}
		$text = (string)$text;
		if(!is_array($from))$fromel = str_split($from);
		else $fromel = $from;
		if($from == $to)return $text;
		$frome = [];
		foreach($fromel as $key => $value) {
			$frome[$value] = $key;
		}
		unset($fromel);
		$fromc = count($frome);
		if(!is_array($to))$toe = str_split($to);
		else $toe = $to;
		$toc = count($toe);
		$texte = array_reverse(str_split($text));
		$textc = count($texte);
		$bs = '0';
		$th = '1';
		if($from === false) {
			$bs = $text;
		}
		else {
			for($i = 0; $i < $textc; ++$i) {
				$bs = self::add($bs, self::mul(@$frome[$texte[$i]], $th));
				$th = self::mul($th, $fromc);
			}
		}
		$r = '';
		if($to === false)return "$bs";
		while($bs > 0) {
			$r = $toe[self::mod($bs, $toc)] . $r;
			$bs = self::floor(self::div($bs, $toc));
		}
		return "$r";
	}
	public static function base_convert($str, $from, $to = 10){
		if($from == 1) {
			$str = (string)strlen($str);
			$from = 10;
		}
		if($from == $to)return $str;
		if($from <= 36 && is_numeric($from))$str = strtolower($str);
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/=";
		$from = strtolower($from) == "ascii" ? "ascii" : substr($chars, 0, $from);
		$to = strtolower($to) == "ascii" ? "ascii" : substr($chars, 0, $to);
		$to = $to == "0123456789" ? false : $to;
		$from = $from == "0123456789" ? false : $from;
		return self::baseconvert($str, $from, $to);
	}
	// calc function
	public static function calc(string $x,array $variables = []){
	}
}
class XNNumberObject {
	public $number = '0';
	public function __construct(string $number,$base = 10){
		if($base == 10)
			$number = XNNumber::toXNNumber($number);
		else $number = XNNumber::init($number,$base);
		if(XNNumber::_check($number))
			$this->number = $number;
	}
	public function __toString(){
		return $this->number;
	}
	public function get(){
		return $this->number;
	}
	public function __call($method,$pars){
		foreach($pars as &$par)
			if(is_object($par) && $par instanceof XNNumberObject)
				$par = $par->number;
		return new XNNumberObject(XNNumber::$method($this->number,...$pars));
	}
}
function xnnumber(string $number,$base = 10){
	$l = $number;
	if($base == 10)
		$number = XNNumber::toXNNumber($number);
	else $number = XNNumber::init($number,$base);
	if(XNNumber::_check($number))
		return new XNNumberObject($number,10);
	elseif($l)
		return XNNumber::calc($l);
	return false;
}
class XNBinary {
	// validator
	public static function is_binary($a){
		return preg_match('/^[01]+$/', $a);
	}
	// system functions
	public static function _check($a){
		if(!self::is_binary($a)) {
			if(strlen($a)> 20)$a = substr($a, 0, 12). '...' . substr($a, -5);
			new XNError("XNNumber", "invalid binary \"$a\".", XNError::ARITHMETIC);
			return false;
		}
		return true;
	}
	public static function _set($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		$l = strlen($b)- strlen($a);
		if($l <= 0)return $a;
		else return str_repeat('0', $l). $a;
	}
	public static function _setall(&$a, &$b){
		$a = self::_set($a, $b);
		if($a === false)return false;
		$b = self::_set($b, $a);
		if($b === false)return false;
		return true;
	}
	public function _get($a){
		if(!self::_check($a))return false;
		$a = ltrim($a, '0');
		return $a ? $a : '0';
	}
	public function _setfull(&$a, &$b){
		$a = self::_get($a);
		if($a === false)return false;
		$b = self::_get($b);
		if($b === false)return false;
		self::_setall($a, $b);
		return true;
	}
	public function _getfull(&$a){
		$a = self::_get($a);
		if($a === false)return false;
		return true;
	}
	// parser functions
	// calc functions
	public static function xor($a, $b){
		if(!self::_setfull($a, $b))return false;
		for($c = 0; isser($a[$c]); ++$c)$a[$c] = ($a[$c] == $b[$c]) ? '0' : '1';
		return $a;
	}
	public static function add($a, $b){
		if(!self::_setfull($a, $b))return false;
		if($a == 0)return $b;
		if($b == 0)return $a;
		$a = "0$a";
		$b = "0$b";
		$l = strlen($a);
		for($c = 0; $c < $l; ++$c) {
			$a[$c] = $a[$c] + $b[$c];
			$w = 0;
			while($a[$c - $w] == 2) {
				$a[$c - $w - 1] = $a[$c - $w - 1] + 1;
				$a[$c - $w] = 0;
				++$w;
			}
		}
		if($a[0] == '0')$a = substr($a, 1);
		return self::_get($a);
	}
	public static function rem($a, $b){
		if(!self::_setfull($a, $b))return false;
		if($b > $a)swap($a, $b);
		if($b == 0)return $a;
		if($a == $b)return 0;
		$l = strlen($a);
		$a = str_split($a);
		for($c = 0; $c < $l; ++$c) {
			$a[$c] = $a[$c] - $b[$c];
			$w = 0;
			while($a[$c - $w] == - 1) {
				$k = 1;
				while($a[$c - $w - $k] == 0) {
					$a[$c - $w - $k] = 1;
					++$k;
				}
				$a[$c - $w - $k] = 0;
				$a[$c - $w] = 1;
				++$w;
			}
		}
		return self::_get(implode('', $a));
	}
	public static function mul($a, $b){
		if(!self::_setfull($a, $b))return false;
		$g = str_repeat('0', strlen($a));
		if(!self::_setfull($a, $b))return false;
		if($a == 0 || $b == 0)return '0';
		$l = strlen($a);
		for($x = 0; $x < $l; ++$x) {
			$r = '';
			for($y = 0; $y < $l; ++$y)$r.= $a[$x] * $b[$y];
			if($x > 0)$r.= str_repeat('0', $x);
			$g = self::add($g, $r);
		}
		return self::_get($g);
	}
	public static function div($a, $b){
		if(!self::_getfull($a))return false;
		if(!self::_getfull($b))return true;
		if($b > $a)swap($a, $b);
		return strlen($a) - strlen($b);
	}
	public static function rshift($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		$a = substr($a, 0, -$shift);
		return $a !== '' ? '0' : $a;
	}
	public static function lshift($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		return $a . str_repeat('0', $shift);
	}
	public static function shift($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		if($shift < 0){
			$a = substr($a, 0, -$shift);
			return $a !== '' ? '0' : $a;
		}
		return $a . str_repeat('0', $shift);
	}
	public static function and($a, $b){
		if(!self::_setfull($a, $b))return false;
		for($c = 0;isset($a[$c]);++$c){
			if($a[$c] === '1' && $b[$c] === '1');
			else $a[$c] = '0';
		}
		return self::_get($a);
	}
	public static function or($a, $b){
		if(!self::_setfull($a, $b))return false;
		$l = strlen($a);
		for($c = 0;isset($a[$c]);++$c){
			if($a[$c] === '1' || $b[$c] === '1')
				$a[$c] = '1';
			else $a[$c] = '0';
		}
		return self::_get($a);
	}
	// convertors
	public function toInt($a){
		return (int)base_convert($a, 2, 10);
	}
	public function to_number($a){
		return XNNumber::base_convert($a, 2, 10);
	}
	public function to_string($a){
		return base2_decode(set_bytes($a, 8));
	}
	public function bytes($a){
		return base2_decode(set_bytes($a, 8));
	}
	public function init($a, $init = 2){
		return XNNumber::base_convert($a, $init, 2);
	}
}
class XNStringPosition {
	public $string = '', $position = 0, $length = 0;
	public function __construct(string $str, int $from = 0){
		$this->string = $str;
		$this->position = $from;
		$this->length = strlen($str);
	}
	public function current(){
		return $this->string[$this->position];
	}
	public function next(){
		return $this->string[++$this->position];
	}
	public function prev(){
		return $this->string[--$this->position];
	}
	public function end(){
		$this->string[$this->position = $this->length - 1];
	}
	public function start(){
		return $this->string[$this->position = 0];
	}
	public function go(int $to){
		return $this->string[$this->position = $to];
	}
	public function set(string $c){
		$this->string[$this->position] = $c[0];
	}
}
class XNStringBinaryPosition {
	public $binary = '', $position = 0, $length = 0, $size = 0;
	public function __construct(string $str, int $size, int $from = 0){
		$this->binary = base2_encode($str);
		$this->position = $from;
		$this->length = strlen($str)* 8;
		$this->size = $size;
	}
	public function get(){
		$this->binary = set_bytes($this->binary, 8, '0');
		$length = $this->length % 8;
		$length = $length ? 8 - $length : 0;
		$this->length+= $length;
		return base2_decode($this->binary);
	}
	public function __toString(){
		return $this->get();
	}
	public function current(){
		return base_convert(substr($this->binary, $this->position, $this->size), 2, 10);
	}
	public function next(){
		return base_convert(substr($this->binary, $this->position+= $this->size, $this->size), 2, 10);
	}
	public function prev(){
		return base_convert(substr($this->binary, $this->position-= $this->size, $this->size), 2, 10);
	}
	public function end(){
		$this->position = $this->length - 1;
	}
	public function start(){
		return base_convert(substr($this->binary, $this->position = 0, $this->size), 2, 10);
	}
	public function go(int $to){
		return base_convert(substr($this->binary, $this->position = $to, $this->size), 2, 10);
	}
	public function set(string $c){
		$c = set_bytes($c, $this->size, '0');
		$this->binary = substr_replace($this->binary, $c, $this->position, $this->size);
	}
	public function getBlocksCount(){
		return ceil($this->length / $this->size);
	}
}
class XNString {
	// parser functions
	public static function parse(string $str){
		$str = str_replace(['\n','\r','\t','\v'],["\n","\r","\t","\v"],$str);
		$str = preg_replace_callback_array([
			'/(?<!\\\\)\\\\x[0-9a-fA-F]{1,2}/' => function($x){
				return chr(base_convert(substr($x[0],2),16,10));
			},
			'/(?<!\\\\)\\\\x\{([0-9a-fA-F]*)\}/' => function($x){
				if(strlen($x[1]) % 2 == 1)
					$x[1] = '0'.$x[1];
				return hex2bin($x[1]);
			},
			'/(?<!\\\\)\\\\[0-7]{1,3}/' => function($x){
				return chr(base_convert(substr($x[0],2),8,10));
			},
			'/(?<!\\\\)\\\\\{([0-7]*)\}/' => function($x){
				return XNString::number2ascii(octdec($x[1]));
			},
			'/(?<!\\\\)\\\\b[01]{1,8}/' => function($x){
				return chr(base_convert(substr($x[0],2),2,10));
			},
			'/(?<!\\\\)\\\\b\{([01]*)\}/' => function($x){
				$x[1] = set_bytes($x[1],8,'0',SET_BYTES_LEFT);
				return base2_decode($x[1]);
			},
			'/(?<!\\\\)\\\\b64\{([0-9a-zA-Z\+\/=-_]*)\}/' => function($x){
				return base64url_decode($x[1]);
			},
			'/(?<!\\\\)#(?<x>\{((?:\\\\\{|\\\\\}|\g<x>|[^\{\}])*)\})/' => function($x){
				return eval($x[2].';');
			},
			'/(?<!\\\\)&(?<x>\{((?:\\\\\{|\\\\\}|\g<x>|[^\{\}])*)\})/' => function($x){
				extract($GLOBALS);
				return eval($x[2].';');
			},
			'/(?<!\\\\)\$([^`~0-9!@#\$%^&*\(\)_\+\{\}\[\]<>\/\\\\\'";:\|!\.,][^`~!@#\$%^&*\(\)_\+\{\}\[\]<>\/\\\\\'";:\|!\.,]*)/' => function($x){
				return @$GLOBALS[$x[1]];
			},
			'/(?<!\\\\)\$(?<x>\{((?:\\\\\{|\\\\\}|\g<x>|[^\{\}])*)\})/' => function($x){
				return @$GLOBALS[$x[2]];
			},
			'/(?<!\\\\)\\\\e(?<x>\{((?:\\\\\{|\\\\\}|\g<x>|[^\{\}])*)\})/' => function($x){
				return getenv($x[2]);
			},
			'/(?<!\\\\)\\\\e([a-zA-Z0-9_-]+)/' => function($x){
				return getenv($x[1]);
			},
			'/(?<!\\\\)%(?<x>\{((?:\\\\\{|\\\\\}|\g<x>|[^\{\}])*)\})/' => function($x){
				return eval('return '.$x[2].';');
			}
		],$str);
		$str = str_replace(['\e','\\\\'],["\e",'\\'],$str);
		return $str;
	}
	public static function lshift(string $str, int $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, $shift, $l - 1). substr($str, 0, $shift);
	}
	public static function rshift(string $str, int $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, $l - $shift, $l - 1). substr($str, 0, $l - $shift);
	}
	public static function usedchars(string $str){
		return array_unique(str_split($str));
	}
	public static function max(...$chars){
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = - 1;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))> $l)$l = $h;
		return $l;
	}
	public static function min(...$chars){
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = 256;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))< $l)$l = $h;
		return $l;
	}
	public static function range(...$chars){
		return range(self::min(...$chars),self::max(...$chars));
	}
	public static function end(string $str, string $im){
		return substr($str, strrpos($str, $im)+ 1);
	}
	public static function start(string $str, string $im){
		return substr($str, 0, strpos($str, $im));
	}
	public static function noend(string $str, string $im){
		return substr($str, 0, strrpos($str, $im));
	}
	public static function nostart(string $str, string $im){
		return substr($str, strpos($str, $im)+ 1);
	}
	public static function endi(string $str, string $im){
		return substr($str, strripos($str, $im)+ 1);
	}
	public static function starti(string $str, string $im){
		return substr($str, 0, stripos($str, $im));
	}
	public static function noendi(string $str, string $im){
		return substr($str, 0, strripos($str, $im));
	}
	public static function nostarti(string $str, string $im){
		return substr($str, stripos($str, $im)+ 1);
	}
	public static function char(string $str, int $x){
		return @$str[$x];
	}
	public static function islength(string $str, int $x){
		return isset($str[$x - 1]);
	}
	public static function position(string $str, int $from = 0){
		return new XNStringPosition($str, $from);
	}
	public static function binaryPosition(string $str, int $size = 8, int $from = 0){
		return new XNStringBinaryPosition($str, $size, $from);
	}
	public static function endchar(string $str){
		return $str[strlen($str)- 1];
	}
	public static function startby(string $str, string $by){
		return strpos($str, $by) === 0;
	}
	public static function endby(string $str, string $by){
		return strrpos($str, $by) === strlen($str)- strlen($by);
	}
	public static function startiby(string $str, string $by){
		return stripos($str, $by) === 0;
	}
	public static function endiby(string $str, string $by){
		return strripos($str, $by) === strlen($str)- strlen($by);
	}
	public static function match(string $str, string $by){
		return $str == $by;
	}
	public static function matchi(string $str, string $by){
		return strtolower($str) == strtolower($by);
	}
	public static function toString($str = 20571922739462){
		if($str === 20571922739462)return '';
		switch (gettype($str)) {
		case "NULL":
			return 'NULL';
		case "boolean":
			if($str)return 'true';
			return 'false';
		case "string":
			return $str;
		case "double":
		case "int":
			return "$str";
		case "array":
			return unce($str);
		}
		throw new XNError("XNString", "unsupported Type", XNError::TYPE);
	}
	public static function toregex(string $str){
		return str_replace("\Q\E", '', "\Q" . str_replace('\E', '\E\\\E\Q', $str). "\E");
	}
	// calc functions
	public static function xorn(string $a, string $b){
		$al = strlen($a);
		$bl = strlen($b);
		$l = max($al, $bl);
		$n = '';
		for($i = 0; $i < $l; ++$i) {
			if(!isset($a[$i]) || !isset($b[$i]) || $a[$i] != $b[$i])$n.= '1';
			else $n.= '0';
		}
		return $n;
	}
	public static function xor(string $a, string $b){
		return base2_decode(set_bytes(self::xorn($a, $b), 8, '0'));
	}
	public static function bxor(string $a, string $b){
		return XNBinary::toString(XNBinary:: xor (base2_encode($a), base2_encode($b)));
	}
	public static function badd(string $a, string $b){
		return XNBinary::toString(XNBinary::add(base2_encode($a), base2_encode($b)));
	}
	public static function brem(string $a, string $b){
		return XNBinary::toString(XNBinary::rem(base2_encode($a), base2_encode($b)));
	}
	public static function bmul(string $a, string $b){
		return XNBinary::toString(XNBinary::mul(base2_encode($a), base2_encode($b)));
	}
	public static function bdiv(string $a, string $b){
		return XNBinary::toString(XNBinary::div(base2_encode($a), base2_encode($b)));
	}
}
class XNStr extends XNString {
}
function xnstr(string $str){
	return XNString::parse($str);
}
function sha512($str){
	return hash("sha512", $str);
}
function sha256($str){
	return hash("sha256", $str);
}
function md4($str){
	return hash("md4", $str);
}
function md2($str){
	return hash("md2", $str);
}
function sha224($str){
	return hash("sha224", $str);
}
function sha384($str){
	return hash("sha384", $str);
}
function hashs($str){
	$n = '';
	$s = hash_algos();
	foreach($s as $h)$n.= hash($h, $str);
	return $n;
}
function hasha($str){
	$s = hash_algos();
	foreach($s as $h)$str = hash($h, $str);
	return $str;
}
function script_runtime(){
	return microtime(true)- $_SERVER['REQUEST_TIME_FLOAT'];
}
function userip(){
	if(@$_SERVER['HTTP_CLIENT_IP'])return $_SERVER['HTTP_CLIENT_IP'];
	elseif(@$_SERVER['HTTP_X_FORWARDED'])return $_SERVER['HTTP_X_FORWARDED'];
	elseif(@$_SERVER['HTTP_X_FORWARDED_FOR'])return $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif(@$_SERVER['REMOTE_ADDR'])return $_SERVER['REMOTE_ADDR'];
	else return "127.0.0.1";
}
function save_memory($file = false){
	if($file)fput($file, xnserialize($GLOBALS));
	else $GLOBALS['-XN-']['savememory'] = $GLOBALS;
}
function back_memory($file = false){
	if($file && file_exists($file))$GLOBALS = xnunserialize(fget($file));
	elseif(!$file)$GLOBALS = $GLOBALS['-XN-']['savememory'];
}
function boolnumber($x, int $bbv = 12){
	$tree = XNMath::tree($x);
	$strs = [];
	foreach($tree as $num) {
		if(isset($strs[$num]))++$strs[$num][1];
		else {
			$n = $num;
			$s = [];
			if($n == 0) {
				$r = rand(1, rand(1, rand(1, rand(1, $bbv))));
				$r = $r < 1 ? 1 : $r;
				if($r % 2 == 1)++$r;
				$s[] = str_repeat('!', $r). '[]';
			}
			else
			while($n > 0) {
				$r = rand(1, rand(1, rand(1, rand(1, $bbv))));
				$r = $r < 1 ? 1 : $r;
				$n-= $r % 2;
				$s[] = ($r ? str_repeat('!', $r): ''). '[]';
			}
			$s = implode('+', $s);
			$strs[$num] = ["($s)", 1];
		}
	}
	$s = [];
	foreach($strs as $num) {
		if($num[1] == 1)$s[] = $num[0];
		else $s[] = $num[0] . '**(' . boolnumber($num[1]). ')';
	}
	$s = implode("*", $s);
	return preg_replace('/\((\([^\(\)]+\))\)/', '$1', $s);
}
function boolstring($str){
	if(!$str)return '';
	return "chr(" . implode(").chr(", array_map("boolnumber", array_values(unpack("c*", $str)))). ")";
}
function distance_positions($x1, $y1, $x2, $y2){
	return rad2deg(acos((sin(deg2rad($x1))* sin(deg2rad($x2)))+ (cos(deg2rad($x1))* cos(deg2rad($x2))* cos(deg2rad($y1 - $y2)))))* 111189.57696;
}
function is_regex($x){
	return @preg_match($x, null) !== false;
}
function is_ereg($x){
	return @ereg($x, null) !== false;
}
function getmd5xn(){
	if($GLOBALS['-XN-']['isf']) {
		$get = file_get_contents($GLOBALS['-XN-']['dirNameDir'] . "xn.php");
		$get = str_replace(["\"" . $GLOBALS['-XN-']['lastUse'] . "\";", "\"" . $GLOBALS['-XN-']['lastUse'] . "\";", "\"" . $GLOBALS['-XN-']['DATA'] . "\";"], '', $get);
		return md5($get);
	}
	else return '';
}
function xnscript(){
	return ["version" => "1.7", "start_time" => $GLOBALS['-XN-']['startTime'], "end_time" => $GLOBALS['-XN-']['endTime'], "loaded_time" => $GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime'], "dir_name" => $GLOBALS['-XN-']['dirName']];
}
class XNMath {
	const PI = 3.1415926535898;
	const PHI = 1.6180339887498;
	const G = 9.80665;
	const E = 2.718281828459;
	public static function fact($n){
		$n = (int)$n;
		$r = 1;
		if($n >= 171)return INF;
		while($n > 0) {
			$r*= $n--;
		}
		return $r;
	}
	public static function gcd($a, $b){
		return $b > 0 ? self::gcd($b, $a % $b): $a;
	}
	public static function factors($x){
		if($x == 0)return [INF];
		$r = [];
		$y = ($x = $x < 0 ? -$x : $x) ** 0.5;
		for($c = 1; $c <= $y; ++$c)
		if($x % $c == 0) {
			$r[] = $c;
			if($c != $y)$r[] = $x / $c;
		}
		sort($r);
		return $r;
	}
	public static function discriminant($a, $b, $c){
		return $b ** 2 - (4 * $a * $c);
	}
	public static function native($x){
		$x = $x < 0 ? -$x : $x;
		if($x == 0)return 0;
		$y = (int)$x ** 0.5;
		for($c = 2; $c <= $y; ++$c)
		if($x % $c == 0)return $c;
		return $x;
	}
	public static function natives($x){
		$x = $x < 0 ? -$x : $x;
		if($x == 0)return [0];
		$r = [];
		for($c = 1; $c <= $x; ++$c)
		if($x % $c == 0)$r[] = $c;
		return $r;
	}
	public static function tree($x){
		if($x == 0)return [0];
		$r = [$l = self::native($x)];
		while(($x/= $l)> 1)$r[] = $l = self::native($x);
		return $r;
	}
	public static function nominal($x, $y){
		return (($x + 1) ** (1 / $y)- 1)* $y;
	}
	public static function pnan($x){
		if($x == 0)return [];
		$a = [1];
		for($c = 2;$c < $x;++$c)
			if(self::gcd($x,$c) == 1)
				$a[] = $c;
		return $a;
	}
	public static function pnt($x){
		return self::native($x) == $x;
	}
	public static function pnpnt($x){
		$a = [];
		for($c = 2;$c < $x;++$c)
			if(self::native($c) == $c)
				$a[] = $c;
		return $a;
	}
	public static function cpnt($x){
		$a = 0;
		for($c = 2;$c < $x;++$c)
			if(self::native($c) == $c)
				++$a;
		return $a;
	}
	public static function phi($x){
		if($x == 0)return 0;
		$n = 1;
		for($c = 2;$c < $x;)
			if(self::gcd($x,$c++) == 1)
				++$n;
		return $n;
	}
	public static function nphi($x){
		if($x == 0)return 0;
		for($c = 2;$c < $x;)
			if(self::gcd($x,$c++) == 1)
				return $c;
		return false;
	}
	public static function pnphi($x){
		if($x == 0)return 0;
		$n = [];
		for($c = 2;$c < $x;)
			if(self::gcd($x,$c++) == 1)
				$n[] = $c;
		return $n;
	}
	public static function posmod($a, $b){
		$a %= $b;
		return $a < 0 ? $a + ($b < 0 ? -$b : $b) : $a;
	}
	public static function rmod($a, $b){
		return $a - floor($a / $b) * $y;
	}
	public static function rposmod($a, $b){
		$a -= floor($a/$b);
		return $a < 0 ? $a + ($b < 0 ? -$b : $b) : $a;
	}
	public static function ascii2number($x){
		return base_convert(bin2hex($x),16,10) + 0;
	}
	public static function number2ascii($x){
		$x = base_convert($x,10,16);
		if(strlen($x) % 2 == 1)$x = '0'.$x;
		return hex2bin($x);
	}
	public static function baseconvert($text, $from = false, $to = false){
		if(is_string($from) && strtolower($from) == "ascii")return self::baseconvert(bin2hex($text), "0123456789abcdef", $to);
		if(is_string($to) && strtolower($to) == "ascii"){
			$r = self::baseconvert($text, $from, "0123456789abcdef");
			if(strlen($r) % 2 == 1)$r = '0'.$r;
			return hex2bin($r);
		}
		$text = (string)$text;
		if(!is_array($from))$fromel = str_split($from);
		else $fromel = $from;
		if($from == $to)return $text;
		$frome = [];
		foreach($fromel as $key => $value) {
			$frome[$value] = $key;
		}
		unset($fromel);
		$fromc = count($frome);
		if(!is_array($to))$toe = str_split($to);
		else $toe = $to;
		$toc = count($toe);
		$texte = array_reverse(str_split($text));
		$textc = count($texte);
		$bs = 0;
		$th = 1;
		if($from === false) {
			$bs = $text;
		}
		else {
			for($i = 0; $i < $textc; ++$i) {
				$bs = $bs + @$frome[$texte[$i]] * $th;
				$th = $th * $fromc;
			}
		}
		$r = '';
		if($to === false)return $bs;
		while($bs > 0) {
			$r = $toe[$bs % $toc] . $r;
			$bs = floor($bs / $toc);
		}
		return $r;
	}
	public static function base_convert($str, $from, $to = 10){
		if($from == 1) {
			$str = (string)strlen($str);
			$from = 10;
		}
		if($from == $to)return $str;
		if($from <= 36 && is_numeric($from))$str = strtolower($str);
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/=";
		$from = strtolower($from) == "ascii" ? "ascii" : substr($chars, 0, $from);
		$to = strtolower($to) == "ascii" ? "ascii" : substr($chars, 0, $to);
		$to = $to == "0123456789" ? false : $to;
		$from = $from == "0123456789" ? false : $from;
		return self::baseconvert($str, $from, $to);
	}
}
function unce_dump(...$vars){
	foreach($vars as $var)print unce($var);
}
function string_dump(...$vars){
	foreach($vars as $var)print XNStr::toString($var);
}
function is_match_files($f1, $f2, $limit = 262144){
	$f1 = @fopen($f1, 'r');
	$f2 = @fopen($f2, 'r');
	if(!$f1 || !$f2) {
		new XNError("is_match_files", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	while(($r1 = fread($f1, $limit)) !== '' && ($r2 = fread($f2, $limit)))
	if($r1 !== $r2)return false;
	return true;
}
function filestate(string $filename){
    $f = @fopen($filename, 'r');
    if(!$f){
        new XNError("filestate", "No such file or directory.", XNError::NOTIC);
        return false;
    }
    $s = fstat($f);
    fclose($f);
    return $s;
}
class BrainFuck {
	public $homes = [0], $home = 0, $output = '', $input = '', $position = - 1;
	private function __construct(string $code, string $input = ''){
		$this->input = $input;
		$this->code($code);
	}
	private function code(string $code){
		$homes = &$this->homes;
		$home = &$this->home;
		$output = &$this->output;
		$input = &$this->input;
		$position = &$this->position;
		for($c = 0; isset($code[$c]); ++$c)switch ($code[$c]) {
		case "+":
			$homes[$home]++;
			break;
		case "-":
			$homes[$home]--;
			break;
		case ">":
			$home++;
			if(!isset($homes[$home]))$homes[$home] = 0;
			break;
		case "<":
			$home--;
			if(!isset($homes[$home]))$homes[$home] = 0;
			break;
		case "[":
			$q = '';
			for($x = 1; isset($code[++$c]) && $x > 0;) {
				if($code[$c] == '[')++$x;
				elseif($code[$c] == ']')--$x;
				$q.= $code[$c];
			}
			$c--;
			$q = substr($q, 0, -1);
			while($homes[$home] % 256 != 0)$this->code($q);
			break;
		case ".":
			$output.= chr($homes[$home]);
			break;
		case ",":
			$homes[$home] = ord(isset($input[$position])? $input[++$position] : $input[$position--]);
			break;
		}
	}
	public static function run(string $code, string $input = ''){
		return (new BrainFuck($code, $input))->output;
	}
	public static function file(string $file, string $input = ''){
		$code = @file_get_contents($file);
		if($code === false)return false;
		return (new BrainFuck($code, $input))->output;
	}
	public static function create(string $string){
		$string = str_split($string);
		$l = '';
		$r = '';
		foreach($string as $x) {
			$x = ord($x);
			if($x == $l)$r.= ".";
			elseif($x == 0)$r.= "[-].";
			elseif($l && $l > $x && $l - $x < $x)$r.= str_repeat("-", $l - $x). '.';
			elseif($l && $l < $x && $x - $l < $x)$r.= str_repeat("+", $x - $l). '.';
			else $r.= "[-]" . str_repeat('+', $x). '.';
			$l = $x;
		}
		if(strpos($r, "[-]") === 0)return substr($r, 3);
		return $r;
	}
}
class Finder {
	const TOKEN = "/[0-9]{4,20}:AA[GFHE][a-zA-Z0-9-_]{32}/";
	const NUMBER = "/[0-9]+(?:\.[0-9]+){0,1}|\.[0-9]+|[0-9]+\./";
	const HEX = "/[0-9a-fA-F]+/";
	const BINARY = "/[01]+/";
	const LINK = "/(?:[a-zA-Z0-9]+:\/\/){0,1}(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))(?:\?[^ \n\r\t\/\\#]*){0,1}(?:#[^ \n\r\t\/]*){0,1}/";
	const EMAIL = "/(?:[^ \n\r\t\/\\#?@]+)@(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})/";
	const FILE_NAME = "/[^ \n\r\t\/\\#@?]+/";
	const DIRACTORY_NAME = "/(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))/";
	public static function get_emojis_regex(){
		return xndata("emoji-regex");
	}
	public static function get_emojis_array(){
		return explode("/",xndata("emoji"));
	}
	public static function exists($str, $regex){
		return preg_match($regex, $str);
	}
	public static function find($str, $regex){
		if(!preg_match($regex, $str, $find))return false;
		return $find[0];
	}
	public static function search($str, $regex){
		if(!preg_match_all($regex, $str, $search))return false;
		return $search[0];
	}
	public static function token_exists($str){
		return self::exists($str, self::TOKEN);
	}
	public static function token_find($str){
		return self::find($str, self::TOKEN);
	}
	public static function token_search($str){
		return self::search($str, self::TOKEN);
	}
	public static function number_exists($str){
		return self::exists($str, self::NUMBER);
	}
	public static function number_find($str){
		return self::find($str, self::NUMBER);
	}
	public static function number_search($str){
		return self::search($str, self::NUMBER);
	}
	public static function hex_exists($str){
		return self::exists($str, self::HEX);
	}
	public static function hex_find($str){
		return self::find($str, self::HEX);
	}
	public static function hex_search($str){
		return self::search($str, self::HEX);
	}
	public static function binary_exists($str){
		return self::exists($str, self::BINARY);
	}
	public static function binary_find($str){
		return self::find($str, self::BINARY);
	}
	public static function binary_search($str){
		return self::search($str, self::BINARY);
	}
	public static function link_exists($str){
		return self::exists($str, self::LINK);
	}
	public static function link_find($str){
		return self::find($str, self::LINK);
	}
	public static function link_search($str){
		return self::search($str, self::LINK);
	}
	public static function email_exists($str){
		return self::exists($str, self::EMAIL);
	}
	public static function email_find($str){
		return self::find($str, self::EMAIL);
	}
	public static function email_search($str){
		return self::search($str, self::EMAIL);
	}
	public static function file_name_exists($str){
		return self::exists($str, self::FILE_NAME);
	}
	public static function file_name_find($str){
		return self::find($str, self::FILE_NAME);
	}
	public static function file_name_search($str){
		return self::search($str, self::FILE_NAME);
	}
	public static function diractory_name_exists($str){
		return self::exists($str, self::DIRACTORY_NAME);
	}
	public static function diractory_name_find($str){
		return self::find($str, self::DIRACTORY_NAME);
	}
	public static function diractory_name_search($str){
		return self::search($str, self::DIRACTORY_NAME);
	}
	public static function emoji_exists($str){
		return self::exists($str, self::get_emojis_regex());
	}
	public static function emoji_find($str){
		return self::find($str, self::get_emojis_regex());
	}
	public static function emoji_search($str){
		return self::search($str, self::get_emojis_regex());
	}
}
function get_type($x){
	return is_object($x)?get_class($x):gettype($x);
}
define('UNICODE_CHARS',0,true);
define('UNICODE_ALL',1,true);
define('UNICODE_UTF',2,true);
function unicode_encode(string $str,string $charset = 'ISO-8861-1',int $type = 2){
	$str = str_replace('\u','\\\u',$str);
	$str = iconv($charset,'gbk',$str);
	preg_match_all('/[\x80-\xff]?./',$str,$chars);
	foreach($chars[0] as &$c){
		$c = iconv('gbk','UTF-8',$c);
		switch(strlen($c)) {
			case 1:
				$n = ord($c[0]);
			break;
			case 2:
				$n = (ord($c[0]) & 0x3f) << 6;
				$n += ord($c[1]) & 0x3f;
			break;
			case 3:
				$n = (ord($c[0]) & 0x1f) << 12;
				$n += (ord($c[1]) & 0x3f) << 6;
				$n += ord($c[2]) & 0x3f;
			break;
			case 4:
				$n = (ord($c[0]) & 0x0f) << 18;
				$n += (ord($c[1]) & 0x3f) << 12;
				$n += (ord($c[2]) & 0x3f) << 6;
				$n += ord($c[3]) & 0x3f;
			break;
		}
		switch($type){
			case UNICODE_CHARS:
				$c = XNMath::number2ascii($n);
			break;
			case UNICODE_ALL:
				$c = '\u'.str_pad(strtoupper(base_convert($n,10,16)),4,'0',STR_PAD_LEFT);
			break;
			case UNICODE_UTF:
				if(strlen($c) != 1)
					$c = '\u'.str_pad(strtoupper(base_convert($n,10,16)),4,'0',STR_PAD_LEFT);
			break;
		}
	}
	return implode('',$chars[0]);
}
function unicode_decode(string $str){
	return str_replace('\\\u','\u',preg_replace_callback('/(?<!\\\\)\\\\u([0-9a-fA-F]{4})/',function($x){
		$c = base_convert($x[1], 16, 10);
		$str = "";
		if($c < 0x80)
			$str .= chr($c);
		else if($c < 0x800) {
			$str .= chr(0xC0 | $c>>6);
			$str .= chr(0x80 | $c & 0x3F);
		} else if($c < 0x10000) {
			$str .= chr(0xE0 | $c>>12);
			$str .= chr(0x80 | $c>>6 & 0x3F);
			$str .= chr(0x80 | $c & 0x3F);
		} else if($c < 0x200000) {
			$str .= chr(0xF0 | $c>>18);
			$str .= chr(0x80 | $c>>12 & 0x3F);
			$str .= chr(0x80 | $c>>6 & 0x3F);
			$str .= chr(0x80 | $c & 0x3F);
		}
		return $str;
	},$str));
}
/*if(!extension_loaded('json') && (!isset($EXTENSION_LOADING) || !is_callable($EXTENSION_LOADING) || !($EXTENSION_LOADING)('json'))){
	define('JSON_HEX_TAG'				 ,1);
	define('JSON_HEX_AMP'				 ,2);
	define('JSON_HEX_APOS'				 ,4);
	define('JSON_HEX_QUOT'				 ,8);
	define('JSON_FORCE_OBJECT'			 ,16);
	define('JSON_NUMERIC_CHECK'			 ,32);
	define('JSON_UNESCAPED_SLASHES'		 ,64);
	define('JSON_PRETTY_PRINT'			 ,128);
	define('JSON_UNESCAPED_UNICODE'		 ,256);
	define('JSON_PARTIAL_OUTPUT_ON_ERROR',512);
	define('JSON_PRESERVE_ZERO_FRACTION' ,1024);

	define('JSON_ERROR_NONE'				 ,0);
	define('JSON_ERROR_DEPTH'				 ,1);
	define('JSON_ERROR_STATE_MISMATCH'		 ,2);
	define('JSON_ERROR_CTRL_CHAR'			 ,3);
	define('JSON_ERROR_SYNTAX'				 ,4);
	define('JSON_ERROR_UTF8'				 ,5);
	define('JSON_ERROR_RECURSION'			 ,6);
	define('JSON_ERROR_INF_OR_NAN'			 ,7);
	define('JSON_ERROR_UNSUPPORTED_TYPE'	 ,8);
	define('JSON_ERROR_INVALID_PROPERTY_NAME',9);
	define('JSON_ERROR_UTF16'				 ,10);
	
	define('JSON_OBJECT_AS_ARRAY' ,1);
	define('JSON_BIGINT_AS_STRING',2);
	define('JSON_PARSE_JAVASCRIPT',4);*/

	$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_NONE;
	function xnjson_last_error():int {
		return $GLOBALS['-XN-']['jsonerror'];
	}
	function xnjson_last_error_msg():string {
		return [
			'No error',
			'Maximum stack depth exceeded',
			'Invalid or malformed JSON',
			'Control character error, possibly incorrectly encoded',
			'Syntax error',
			'Malformed UTF-8 characters, possibly incorrectly encoded',
			'One or more recursive references in the value to be encoded',
			'Inf and NaN cannot be JSON encoded',
			'A value of a type that cannot be encoded was given',
			'A property name that cannot be encoded was given',
			'Malformed UTF-16 characters, possibly incorrectly encoded'
		][$GLOBALS['-XN-']['jsonerror']];
	}
	function _xnjson_encode($value, $options = 0, $depth = 512){
		if($value === null)
			return 'null';
		if($value === false)
			return 'false';
		if($value === true)
			return 'true';
		switch(gettype($value)){
			case 'string':
				if($options & JSON_NUMERIC_CHECK && is_numeric($value)){
					return ($value + 0).'';
				}
				if(~$options & JSON_UNESCAPED_UNICODE)
					$value = unicode_encode($value);
				$value = '"'.str_replace(['\\','"',"\n","\r","\t"],['\\\\','\"','\n','\r','\t'],$value).'"';
				if($options & JSON_HEX_TAG)
					$value = str_replace(['<','>'],['\u003C','\u003E'],$value);
				if($options & JSON_HEX_AMP)
					$value = str_replace('&','\u0026',$value);
				if($options & JSON_HEX_APOS)
					$value = str_replace("'",'\u0027',$value);
				if($options & JSON_HEX_QUOT)
					$value = str_replace('"','\u0022',$value);
				if(~$options & JSON_UNESCAPED_SLASHES)
					$value = str_replace('/','\/',$value);
				return $value;
			break;
			case 'integer':
			case 'double':
			case 'float':
				if(is_infinite($value) || is_nan($value)){
					$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_INF_OR_NAN;
					if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return false;
					return '0';
				}
				if($options & JSON_PRESERVE_ZERO_FRACTION && !is_int($value))
					return $value.'.0';
				return (string)$value;
			break;
			case 'array':
			case 'object':
				if($depth <= 0){
					$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_INF_OR_NAN;
					if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return false;
				}
				if($options & JSON_PRETTY_PRINT){
					if(is_array($value) && ~$options & JSON_FORCE_OBJECT){
						$str = "[\n    ";
						$c = 0;
						foreach($value as $key => $val){
							if($key == $c++)
								$str .= str_replace("\n","\n    ",_xnjson_encode($val,$options,$depth - 1)) . ",\n    ";
							else{
								$str = '';
								break;
							}
							if($GLOBALS['-XN-']['jsonerror'] > 0 && ~$options & JSON_FORCE_OBJECT)return false;
						}
					}else $str = '';
					if($str){
						if($str == "[\n    ")
							$str = '[]';
						else
							$str = substr_replace($str,"\n]",-6,6);
						return $str;
					}
					if(is_object($value))
						$value = get_object_vars($value);
					$str = "{\n    ";
					foreach($value as $key => $val){
						$str .= _xnjson_encode((string)$key,$options,$depth - 1) . ': ' . str_replace("\n","\n    ",_xnjson_encode($val,$options,$depth - 1)) . ",\n    ";
						if($GLOBALS['-XN-']['jsonerror'] > 0 && ~$options & JSON_FORCE_OBJECT)return false;
					}
					if($str == "{\n    ")
						$str = '{}';
					else
						$str = substr_replace($str,"\n}",-6,6);
					return $str;
				}
				if(is_array($value) && ~$options & JSON_FORCE_OBJECT){
					$str = '[';
					$c = 0;
					foreach($value as $key => $val){
						if($key == $c++)
							$str .= _xnjson_encode($val,$options,$depth - 1) . ',';
						else{
							$str = '';
							break;
						}
						if($GLOBALS['-XN-']['jsonerror'] > 0 && ~$options & JSON_FORCE_OBJECT)return false;
					}
				}else $str = '';
				if($str){
					if($str == '[')
						$str = '[]';
					else $str[strlen($str) - 1] = ']';
					return $str;
				}
				if(is_object($value))
					$value = get_object_vars($value);
				$str = '{';
				foreach($value as $key => $val){
					$str .= _xnjson_encode((string)$key,$options,$depth - 1) . ':' . _xnjson_encode($val,$options,$depth - 1) . ',';
					if($GLOBALS['-XN-']['jsonerror'] > 0 && ~$options & JSON_FORCE_OBJECT)return false;
				}
				if($str == '{')
					$str = '{}';
				else
					$str[strlen($str) - 1] = '}';
				return $str;
			break;
			default:
				$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_UNSUPPORTED_TYPE;
				if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return false;
				return '';
		}
	}
	function xnjson_encode($value, $options = 0, $depth = 512){
		$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_NONE;
		return _xnjson_encode($value, $options, $depth);
	}
	function _xnjson_decode($value, bool $assoc = false, int $depth = 512, int $options = 0){
		if($value == 'null')
			return null;
		if($value == 'false')
			return false;
		if($value == 'true')
			return true;
		if($value[0] == '"'){
			$value = unicode_decode(substr($value,1,-1));
			$value = str_replace(['\"','\/','\\\\'],['"','/','\\'],$value);
			echo $value;
		}
	}
	function xnjson_decode($value, bool $assoc = false, int $depth = 512, int $options = 0){
		$GLOBALS['-XN-']['jsonerror'] = JSON_ERROR_NONE;
		return _xnjson_decode($value, $assoc, $depth, $options);
	}
//}
function xnmicrotime(){
	$time = explode(" ", microtime());
	return $time[1] . substr($time[0], 2, -2);
}
function militime(){
	return floor(microtime(true)* 1000);
}
class AXBA {
	public $time = 0;
	public function setTime(int $time = null){
		if(!$time)$time = 0;
		$this->time = $time;
	}
	public function encrypt($message, $key = '.', $limt = 1){
		$limt = $limt < 1 ? 1 : $limt > 255 ? 255 : $limt;
		$lk = strlen($key);
		$now = "";
		$time = $this->time;
		if(!$time)$time = 0;
		if($time)$nw = floor(microtime(true)* 1000 / $time);
		$ntime = $time;
		while($time > 0) {
			$now.= chr($time % 256);
			$time = floor($time / 256);
		}
		$time = $ntime;
		$now = chr(strlen($now)). $now;
		if(!$message)return $now;
		for($c = 0; isset($message[$c]); ++$c) {
			$lim = $limt;
			$h = $ck = ord($key[$c % $lk]);
			for($p = 0; $p < $lk; ++$p)$h+= $c < $p ? ord($key[$lk % ($p * 2 - $c + 1)- 1]): ord($key[$lk % ($c * $p + 1)- 1]);
			$h+= 7 + $c;
			while($lim > 0) {
				$h+= ord($key[$h % $lk]);
				$h-= ord($key[$h % $lk]);
				$h+= ord($key[(ord($key[$h % $lk])* (--$ck))% $lk]);
				$h-= ord($key[(ord($key[$h % $lk])* (--$ck))% $lk]);
				if($time) {
					$h-= $time;
					$h+= floor((54723587387253 % $time) ** (78942389724972 % $time / $time));
					$h-= 78945897423879 % 79483978437298 % $nw;
					$h+= 48738970039781 % floor((27384879234873 % $nw) ** (84731894397898 % $nw / $nw));
					$h+= 84398423897894 % floor(($nw * $time) ** 0.1 + 1);
					$h = floor($h / ($nw % 100));
				}
				--$lim;
			}
			$h+= 74328897548798 % (++$ck ? $ck : ++$ck);
			$h+= 87942397087943 % $ck;
			$h+= 47388973497853 % $ck;
			$h+= 89470974982370 % $ck;
			$h-= 10472109438758 % $ck;
			$h+= 19874623974023 % $ck;
			$h+= 24731088438908 % (ord($key[$h % $lk])+ 1);
			$h+= 12093802101304 % $ck;
			$h+= 89743879349718 % (ord($key[$h % $lk])+ 1);
			$h+= 11948904100409 % (ord($key[$h % $lk])+ 1);
			$h-= 12409721374821 % (ord($key[$h % $lk])+ 1);
			$h+= 94874870929741 % (ord($key[$h % $lk])+ 1);
			$h+= 84731090791187 % (ord($key[$h % $lk])+ 1);
			$h+= 89412879328432 % (ord($key[$h % $lk])+ 1);
			$h+= 5370912357253 % (ord($key[$h % $lk])+ 1);
			$h+= 2 % $ck;
			$now.= chr($h + ord($message[$c]));
		}
		return $now . chr($limt);
	}
	public function decrypt($message, $key = '.'){
		if(!$message)return '';
		$limt = ord($message[strlen($message)- 1]);
		$size = ord($message[0]);
		$stime = strrev(substr($message, 1, $size));
		$message = substr($message, $size + 1, -1);
		$time = 0;
		if($stime)
		for($c = 0; isset($stime[$c]); ++$c)$time = $time * 256 + ord($stime[$c]);
		if($time)$nw = floor(microtime(true)* 1000 / $time);
		$lm = strlen($message);
		$lk = strlen($key);
		$now = "";
		for($c = 0; isset($message[$c]); ++$c) {
			$lim = $limt;
			$h = $ck = ord($key[$c % $lk]);
			for($p = 0; $p < $lk; ++$p)$h+= $c < $p ? ord($key[$lk % ($p * 2 - $c + 1)- 1]): ord($key[$lk % ($c * $p + 1)- 1]);
			$h+= 7 + $c;
			while($lim > 0) {
				$h+= ord($key[$h % $lk]);
				$h-= ord($key[$h % $lk]);
				$h+= ord($key[(ord($key[$h % $lk])* (--$ck))% $lk]);
				$h-= ord($key[(ord($key[$h % $lk])* (--$ck))% $lk]);
				if($time) {
					$h-= $time;
					$h+= floor((54723587387253 % $time) ** (78942389724972 % $time / $time));
					$h-= 78945897423879 % 79483978437298 % $nw;
					$h+= 48738970039781 % floor((27384879234873 % $nw) ** (84731894397898 % $nw / $nw));
					$h+= 84398423897894 % floor(($nw * $time) ** 0.1 + 1);
					$h = floor($h / ($nw % 100));
				}
				--$lim;
			}
			$h+= 74328897548798 % (++$ck ? $ck : ++$ck);
			$h+= 87942397087943 % $ck;
			$h+= 47388973497853 % $ck;
			$h+= 89470974982370 % $ck;
			$h-= 10472109438758 % $ck;
			$h+= 19874623974023 % $ck;
			$h+= 24731088438908 % (ord($key[$h % $lk])+ 1);
			$h+= 12093802101304 % $ck;
			$h+= 89743879349718 % (ord($key[$h % $lk])+ 1);
			$h+= 11948904100409 % (ord($key[$h % $lk])+ 1);
			$h-= 12409721374821 % (ord($key[$h % $lk])+ 1);
			$h+= 94874870929741 % (ord($key[$h % $lk])+ 1);
			$h+= 84731090791187 % (ord($key[$h % $lk])+ 1);
			$h+= 89412879328432 % (ord($key[$h % $lk])+ 1);
			$h+= 5370912357253 % (ord($key[$h % $lk])+ 1);
			$h+= 2 % $ck;
			$now.= chr(256 - $h + ord($message[$c]));
		}
		return $now;
	}
	public function encrypts($message, $key = '.', $salt = '.', $limt = 1){
		$datak = $this->encrypt($this->encrypt($key, $key), ($x = hex2bin(md5($key))). $salt . $x);
		$data = $this->encrypt($message, $datak . substr($key . $salt, 0, 1), $limt);
		$data = $this->encrypt($data, ($x = hex2bin(md5($salt))). $key . $x, $limt);
		return $this->encrypt($data, $key . $salt . hex2bin(hash("sha512", $salt . $key)), $limt);
	}
	public function decrypts($message, $key = '.', $salt = '.'){
		$data = $this->decrypt($message, $key . $salt . hex2bin(hash("sha512", $salt . $key)));
		$data = $this->decrypt($data, ($x = hex2bin(md5($salt))). $key . $x);
		$datak = $this->encrypt($this->encrypt($key, $key), ($x = hex2bin(md5($key))). $salt . $x);
		return $this->decrypt($data, $datak . substr($key . $salt, 0, 1));
	}
	public function hash($message, $key = '.', $limt = 1){
		if(!$message)return '';
		$lm = strlen($message);
		$lk = strlen($key);
		$now = '';
		$limt = $limt < 1 ? 1 : $limt > 1024 ? 1024 : $limt;
		$nums = [78794387352807, 90419884196858, 63750863186045, 18653108265983, 69874169879018, 39057981265091, 74971307548601, 97406498316490, 82164014098649, 28650984759864, 35837092374096, 12986498327592, 37402349837409, 74016407076498, 26598739087498, 26389473120097, 40917452386395, 7927409812109, 34907193498891, 29831793479102, 7491049734872, 35862399847019, 34750165665666, 66013931094893, 10949814013570, 17518461387471, 3947186581039, 47091384910740, 15979729703810, 98387472985437, 40597130458785, 56410297802670, 35398431069813, 63109563546056, 48695480695406, 84816084869548, 96438659438959, 87315098740985, 7894877408048, 78487787854875, 87864586438965, 86318984681605, 6408968109473, 34902762748394, 71390483624650, 93749078249732, 40958017598637, 4109498625070, 49789109571097, 13897420975982, 64109759275808, 79347018279483, 65987819084986, 59748961809471, 74295274095968, 27504295617050, 27356982572470, 95703126501935, 48686915079309, 71748938795079, 39071867994797, 2685697019074, 6910650257406, 19750284091506, 91074099025790, 97349173509757, 91473490701947, 90319797050731, 7410975970079, 19070951907497, 31709542709597, 1079497593597, 9739750907329, 7597027097095, 7490797214970, ];
		$time = $this->time;
		for($c = 0; $c < $lm; ++$c) {
			$lim = $limt;
			$h = ord($message[$c]);
			$a = $h + 1;
			$h+= floor($h ** 1.1 / ($h ** 0.7 + 1)+ $lm ** 0.5);
			while($lim > 0) {
				$h+= $a;
				$a = ord($message[$h % $lm]);
				$a+= ord($message[$a % $lm]);
				$a+= ord($key[$h % $lk]);
				$a+= ord($key[$a % $lk]);
				$a+= $nums[abs($h % 75)] % (!$h ? $h + 1 : $h);
				$a+= $nums[abs($a % 75)] % (!$a ? $a + 1 : $a);
				$a+= $nums[abs($h % 75)] % (!$h ? $h + 1 : $h);
				$a+= $nums[abs($a % 75)] % (!$a ? $a + 1 : $a);
				$a+= $a % ($c + 1)+ $c;
				if($time)$a+= floor(microtime(true)* 1000 / $time)% 256 + $time % ($c + 1)+ $c % $time + $h % $time;
				--$lim;
			}
			$h+= $a - 3;
			$h+= $nums[$c % 75] % ($c + 1)+ $c;
			$now.= chr($h);
		}
		return $now;
	}
	public function dblencrypt($message, $key, $limt = 1){
		$message = $this->encrypt($message, $key, $limt);
		$x1 = floor(strlen($message)/ 2);
		$x2 = strlen($message)- $x1;
		$y1 = floor(strlen($key)/ 2);
		$y2 = strlen($key)- $y1;
		$crypt1 = $this->encrypt(substr($message, $x1, $x2), substr($key, 0, $y1), $limt);
		$crypt2 = $this->encrypt(substr($message, 0, $x1), substr($key, $y1, $y2), $limt);
		$crypt2 = $this->encrypt($crypt2, $crypt1, $limt);
		$crypt1 = $this->encrypt($crypt1, $key, $limt);
		return [$crypt1, $crypt2];
	}
	public function dbldecrypt($crypt1, $crypt2, $key){
		$y1 = floor(strlen($key)/ 2);
		$y2 = strlen($key)- $y1;
		$crypt1 = $this->decrypt($crypt1, $key);
		$crypt2 = $this->decrypt($crypt2, $crypt1);
		$crypt1 = $this->decrypt($crypt1, substr($key, 0, $y1));
		$crypt2 = $this->decrypt($crypt2, substr($key, $y1, $y2));
		return $this->decrypt($crypt2 . $crypt1, $key);
	}
}
class XNObject {
	private $var = null, $call = [], $static = [], $destruct = null, $wakeup = null, $tostring = null, $callmethod = null, $callstatic = null, $invoke = null, $clone = null;
	public function var(&$var){
		$this->var = &$var;
		$var = $this;
	}
	public function __construct(&$var = null){
		if($var)$this->from($var);
	}
	public function from(&$object){
		$object = serialize((object)$object);
		$object = replace_first("8:\"stdClass\"", "8:\"XNObject\"", $object);
		$object = unserialize($object);
		$object->var($object);
		return $object;
	}
	public function set(string $var, string $type, $value){
		set_class_var($this->var, $type, $var, $value);
	}
	public function get(string $var){
		return get_class_var($this->var, get_class_var_type($this->var, $var), $var);
	}
	public function type(string $var){
		return get_class_var_type($this->var, $var);
	}
	public function setMethod(string $method, object $value){
		$this->call[$method] = $value;
	}
	public function setStaticMethod(string $method, object $value){
		$this->static[$method] = $value;
	}
	public function setDestruct(object $value){
		$this->destruct = $value;
	}
	public function setWakeup(object $value){
		$this->wakeup = $value;
	}
	public function setTostring(object $value){
		$this->tostring = $value;
	}
	public function setInvoke(object $value){
		$this->invoke = $value;
	}
	public function setClone(object $value){
		$this->clone = $value;
	}
	public function clone(){
		return $this->__clone();
	}
	public function __destruct(){
		if($this->destruct)($this->destruct)();
	}
	public function __toString(){
		if($this->tostring)($this->tostring)();
		return function_exists("json_encode")?json_encode($this):unce($this);
	}
	public function __clone(){
		if($this->clone)
		if(($r = ($this->clone)()) && is_object($r))return $r;
		$object = $this->object;
		return new XNObject($object);
	}
	public function __call($x, $y){
		if($this->callmethod)$r = ($this->callmethod)($x, $y);
		if(isset($this->call[$x]))return ($this->call[$x])(...$y);
		if(isset($r))return $r;
	}
	public static function __callStatic($x, $y){
		if($this->callstatic)$r = ($this->callstatic)($x, $y);
		if(isset($this->static[$x]))return ($this->static[$x])(...$y);
		if(isset($r))return $r;
	}
	public function all(){
		return get_class_all_vars($this->var);
	}
}
function xnobject($object = null){
	return $object = new XNObject($object);
}
class XNCode {
	private $code, $errorfile = "error_log", $wait = false, $proc, $pipes, $php = "php", $timer = 0, $global = false, $response;
	public function setCode($code){
		if($code instanceof XNClosure) {
			$code = $code->getCode();
		}
		elseif($code instanceof Closure) {
			$code = (new XNClosure($code))->getCode();
		}
		elseif(is_string($code) && (file_exists($code) || filter_var($code, FILTER_VALIDATE_URL))) {
			$code = file_get_contents($code);
		}
		elseif(is_string($code));
		else {
			new XNError("XNCode", "Invalid Code or Closure or File", XNError::WARNING);
			return false;
		}
		$this->code = $code;
		return true;
	}
	public function getCode($code){
		return $this->code;
	}
	public function __construct($code = ''){
		$this->setCode($code);
	}
	public function setPHPConsole($php = "php"){
		if(!XNStr::endiby($php, "php.exe") && $php != 'php')return false;
		$this->php = $php;
		return true;
	}
	public function addCode($code){
		$last = $this->code;
		$this->setCode($code);
		$code = $this->code;
		$this->code = "$last;$code";
	}
	public function timer($time){
		if(!is_numeric($time))return false;
		$this->timer = $time;
		return true;
	}
	public function global(bool $global = null){
		if($global === null)$global = !$this->global;
		$this->global = $global;
	}
	private function setErrorFile(string $file = ''){
		$this->errorfile = $file;
	}
	private function compile(){
		$code = $this->code;
		if($this->global) {
			$variables = array_clone($GLOBALS);
			foreach($variables as $key => $val)
			if($key == "GLOBALS" || $key == "-XN-")unset($variables[$key]);
			foreach($variables as $key => $val) {
				if(is_object($val))$val = "unserialize(base64_decode('" . base64_encode(serialize($val)). "'))";
				else $val = unce($val);
				$code = "\${'" . str_replace(["\\", "'"], ["\\\\", "\\'"], $key). "'}=$val;\n$code";
			}
		}
		if($this->timer) {
			$code = "usleep({$this->timer});\n$code";
		}
		$code = "<?php\n$code\n?>";
		return $code;
	}
	private function open(){
		$proc = proc_open($this->php, $this->errorfile ? [["pipe", "r"], ["pipe", "w"], ["file", $this->errorfile, "a"]] : [["pipe", "r"], ["pipe", "w"]], $pipes, ".", ["PARENT_XNCODE" => __FILE__]);
		$this->proc = $proc;
		$this->pipes = $pipes;
		return $pipes;
	}
	public function run(){
		if($this->proc) {
			new XNError("XNCode", "you last runned the Code", XNError::NOTIC);
			return false;
		}
		$pipes = $this->open();
		fwrite($pipes[0], $this->compile());
		return true;
	}
	public function close(){
		if($this->proc) {
			fclose($this->pipes[0]);
			fclose($this->pipes[1]);
			if(!$this->wait)proc_terminate($this->proc, 15);
			proc_close($this->proc);
			$this->proc = null;
			$this->response = null;
		}
	}
	public function __destruct(){
		if(!$this->proc);
		elseif($this->wait)$this->close();
		else {
			fclose($this->pipes[0]);
			fclose($this->pipes[1]);
		}
	}
	public function response(){
		if(!$this->proc) {
			new XNError("XNCode", "code not runned", XNError::NOTIC);
			return false;
		}
		return $this->response ? $this->response : $this->response = stream_get_contents($this->pipes[1]);
	}
	public function wait($timeout = 0){
		if(!$this->proc) {
			new XNError("XNCode", "code not runned", XNError::NOTIC);
			return false;
		}
		if(!$timeout) {
			while(fgets($this->pipes[1]) !== false);
		}
		else {
			$end = time()+ $timeout;
			while(fgets($this->pipes[1]) !== false && time()<= $end);
			if(time()> $end)return null;
		}
		return true;
	}
	public function stop(){
		proc_terminate($this->proc);
	}
}
if(!isset($_SERVER['argv']))$_SERVER['argv'] = [__FILE__];
if(!isset($_SERVER['argc']))$_SERVER['argc'] = 1;
function rextester($type, $code, $input = ''){
	$language = $type;
	$type = strtolower($type);
	if($type == "ada")$type = 39;
	elseif($type == "nasm" || $type == "assemboly" || $type = "asm")$type = 15;
	elseif(strhave($type, "bash") || $type == "shell")$type = 38;
	elseif($type == "csharp" || $type == "c#")$type = 1;
	elseif((strhave($type, "cpp") || strhave($type, "c++")) && strhave($type, "gcc"))$type = 7;
	elseif((strhave($type, "cpp") || strhave($type, "c++")) && strhave($type, "clang"))$type = 27;
	elseif(((strhave($type, "cpp") || strhave($type, "c++")) && (strhave($type, "vc++") || strhave($type, "visual"))) || $type == "vc++")$type = 28;
	elseif((strhave($type, "cpp") || strhave($type, "c")) && strhave($type, "gcc"))$type = 6;
	elseif((strhave($type, "cpp") || strhave($type, "c")) && strhave($type, "clang"))$type = 26;
	elseif((strhave($type, "cpp") || strhave($type, "c")) && (strhave($type, "vc") || strhave($type, "visual")))$type = 29;
	elseif($type == "common lisp" || $type == "clisp" || $type == "lisp")$type = 18;
	elseif($type == "d")$type = 30;
	elseif($type == "elixir")$type = 41;
	elseif($type == "erlang")$type = 40;
	elseif($type == "fsharp" || $type == "f#")$type = 3;
	elseif($type == "fortran")$type = 45;
	elseif($type == "go")$type = 20;
	elseif($type == "haskell")$type = 11;
	elseif($type == "java")$type = 4;
	elseif($type == "javascript" || $type == "js")$type = 17;
	elseif($type == "kotlin")$type = 43;
	elseif($type == "lua")$type = 14;
	elseif($type == "mysql" || $type == "sqlite" || $type = "sqlit" || $type == "sqli" || $type == "sql" || $type == "mysqlite" || $type == "mysqlit" || $type == "mysqli")$type = 33;
	elseif($type == "node.js" || $type == "nodejs")$type = 23;
	elseif($type == "ocaml")$type = 42;
	elseif($type == "octave")$type = 25;
	elseif($type == "objective-c" || $type == "objectivec")$type = 10;
	elseif($type == "oracle")$type = 35;
	elseif($type == "pascal")$type = 9;
	elseif($type == "prel")$type = 13;
	elseif($type == "php")$type = 8;
	elseif($type == "postgresql")$type = 34;
	elseif($type == "prolog")$type = 19;
	elseif($type == "python" || $type == "py")$type = 5;
	elseif($type == "python3" || $type == "py3" || $type == "python 3" || $type == "py 3")$type = 24;
	elseif($type == "r")$type = 31;
	elseif($type == "ruby")$type = 12;
	elseif($type == "scala")$type = 21;
	elseif($type == "scheme")$type = 22;
	elseif($type == "sqlserver" || $type = "sql server")$type = 16;
	elseif($type == "swift")$type = 37;
	elseif($type == "tcl")$type = 32;
	elseif($type == "visual basic" || $type == "visualbasic" || $type = "basic" || $type == "vbnet" || $type == "vb.net")$type = 2;
	elseif($type == "brainfuck")$type = 44;
	else $type = false;
	if($type) {
		$link = "http://rextester.com/rundotnet/api";
		$curl = curl_init($link);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, ["LanguageChoice" => $type, "Program" => $code, "Input" => $input, "CompilerArgs" => ""]);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}
define("XNDEFINE_TEXT",           1);
define("XNDEFINE_REGEX",          2);
define("XNDEFINE_INPUT",          3);
define("XNDEFINE_REGEX_CALLBACK", 4);
define("XNDEFINE_INPUT_CALLBACK", 5);
define("XNDEFINE_ITEXT",          6);
define("XNDEFINE_IINPUT",         7);
define("XNDEFINE_CONST",          8);
define("XNDEFINE_ALL",            1);
define("XNDEFINE_STRING",         2);
define("XNDEFINE_NUMBER",         3);
define("XNDEFINE_CODE",           4);
$GLOBALS['-XN-']['xndefine'] = zlib_encode(file_get_contents(thefile()),31);
function xndefine(string $from, string $to, int $type = 1, int $locate = 1){
	$source = gzuncompress($GLOBALS['-XN-']['xndefine']);
	$source = implode("\n", array_slice(explode("\n", $source), theline()));
	if($type == XNDEFINE_CONST) {
		define($from, $to);
		return;
	}
	switch ($locate) {
	case XNDEFINE_ALL:
		switch ($type) {
		case XNDEFINE_TEXT:
			$source = str_replace($from, $to, $source);
			break;
		case XNDEFINE_REGEX:
			$source = preg_replace_callback($from, $to, $source);
			break;
		case XNDEFINE_REGEX_CALLBACK:
			$source = preg_replace($from, $to, $source);
			break;
		case XNDEFINE_ITEXT:
			$source = str_ireplace($from, $to, $source);
			break;
		default:
			return false;
			break;
		}
		if(!$source)return false;
		$GLOBALS['-XN-']['xndefine'] = $source;
		eval($source);
		exit;
		break;
	case XNDEFINE_STRING:
		$source = preg_replace_callback("/(?<!\\\\)(?:\'(?:\\\'|[^\'])*(?<!\\\\)\'|\"(?:\\\\\"|[^\"])*(?<!\\\\)\")/",
		function($x)use($from, $to, $type){
			$source = substr($x[0], 1, -1);
			$x = $x[0][0];
			switch ($type) {
			case XNDEFINE_TEXT:
				$source = str_replace($from, $to, $source);
				break;
			case XNDEFINE_REGEX:
				$source = preg_replace_callback($from, $to, $source);
				break;
			case XNDEFINE_REGEX_CALLBACK:
				$source = preg_replace($from, $to, $source);
				break;
			case XNDEFINE_ITEXT:
				$source = str_ireplace($from, $to, $source);
				break;
			default:
				return false;
				break;
			}
			if(!$source)return false;
			return $x . $source . $x;
		}
		, $source);
		if(!$source)return false;
		$GLOBALS['-XN-']['xndefine'] = $source;
		eval($source);
		exit;
		break;
	case XNDEFINE_CODE:
		$saves = [];
		$source = preg_replace_callback("/(?i)(?:(?<!\\\\)(?:\'(?:\\\'|[^\'])*(?<!\\\\)\'|\"(?:\\\\\"|[^\"])*(?<!\\\\)\")|(?:[0-9]+\.[0-9]+|[0-9]+\.|\.[0-9]+|[0-9]+)|(?:0[xb][0-9]+)|true|false|null)/",
		function($x)use(&$saves, $source){
			$saves[] = [strpos($source, $x[0]), $x[0]];
			return '';
		}
		, $source);
		if(!$source)return false;
		$lsl = strlen($source);
		switch ($type) {
		case XNDEFINE_TEXT:
			$source = str_replace($from, $to, $source);
			break;
		case XNDEFINE_REGEX:
			$source = preg_replace_callback($from, $to, $source);
			break;
		case XNDEFINE_REGEX_CALLBACK:
			$source = preg_replace($from, $to, $source);
			break;
		case XNDEFINE_ITEXT:
			$source = str_ireplace($from, $to, $source);
			break;
		default:
			return false;
			break;
		}
		if(!$source)return false;
		foreach($saves as $save)$source = substr_replace($source, $save[1], $save[0] + strlen($source)- $lsl, 0);
		$GLOBALS['-XN-']['xndefine'] = $source;
		eval($source);
		exit;
		break;
	default:
		if(is_string($locate)) {
			$source = preg_replace_callback($locate,
			function($x)use($from, $to, $type){
				$x = $x[0];
				switch ($type) {
				case XNDEFINE_TEXT:
					$x = str_replace($from, $to, $x);
					break;
				case XNDEFINE_REGEX:
					$x = preg_replace_callback($from, $to, $x);
					break;
				case XNDEFINE_REGEX_CALLBACK:
					$x = preg_replace($from, $to, $x);
					break;
				case XNDEFINE_ITEXT:
					$x = str_ireplace($from, $to, $x);
					break;
				default:
					return false;
					break;
				}
				if(!$x)return false;
				return $x;
			}
			, $source);
			if(!$source)return false;
			$GLOBALS['-XN-']['xndefine'] = $source;
			eval($source);
			exit;
		}
		return false;
		break;
	}
	return true;
}
$GLOBALS['-XN-']['push'] = [];
$GLOBALS['-XN-']['pushed'] = 0;
$GLOBALS['-XN-']['poped'] = 0;
function push($x){
	$GLOBALS['-XN-']['push'][$GLOBALS['-XN-']['pushed']++] = $x;
}
function ppush($x){
	$GLOBALS['-XN-']['push'][$GLOBALS['-XN-']['pushed'] ? $GLOBALS['-XN-']['pushed'] - 1 : 0] = $x;
}
function pop(){
	$var = $GLOBALS['-XN-']['push'][$GLOBALS['-XN-']['poped']];
	unset($GLOBALS['-XN-']['push'][$GLOBALS['-XN-']['poped']++]);
	return $var;
}
function ppop(){
	$var = $GLOBALS['-XN-']['push'][$GLOBALS['-XN-']['poped']];
	return $var;
}
function replace_count($from, $to, $str, $count = 0){
	if($count == 0)$count = strlen($count);
	if($count < 0)$count = strlen($count)+ $count;
	$from = '/' . preg_quote($from, '/'). '/';
	return preg_replace($from, $to, $str, $count);
}
function replace_first($from, $to, $str){
	return substr_replace($str, $to, strpos($str, $from), strlen($from));
}
function ireplace_first($from, $to, $str){
	return substr_replace($str, $to, stripos($str, $from), strlen($from));
}
function replace_last($from, $to, $str){
	return substr_replace($str, $to, strrpos($str, $from), strlen($from));
}
function ireplace_last($from, $to, $str){
	return substr_replace($str, $to, strripos($str, $from), strlen($from));
}
function bytexor($x, $y){
	return chr(ord($x)^ ord($y));
}function content_stream(string $string,string $mode,string $mime_type = "text/plan",bool $use_include_path = false,resource $context = null){
    if($context)return fopen("data://$mime_type,$string",$mode,$use_include_path,$context);
    return fopen("data://$mime_type,$string",$mode,$use_include_path);
}
function unbug(&$x){};
class ShortLink {
  public function uto_add($link){
    $curl = curl_init("http://u.to/");
    $data = "a=add&url=".urlencode($link);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_HTTPHEADER,[
      "Content-Length: ".strlen($data),
      "Content-Type: application/x-www-form-urlencoded",
    ]);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    $res = curl_exec($curl);
    curl_close($curl);
    preg_match_all("/http:\/\/u.to\/([^\"']+)/",$res,$res);
    return end($res[0]);
  }
  public function uto_del($id){
    $curl = curl_init("http://u.to/");
    $id = substr($id,strrpos($id,'/')+1);
    $data = "a=delURL&uid=$id";
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_HTTPHEADER,[
      "Content-Length: ".strlen($data),
      "Content-Type: application/x-www-form-urlencoded",
    ]);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    $res = curl_exec($curl);
    curl_close($curl);
    return true;
  }
  
}
function get_request_title($file = false){
	$method = getenv('REQUEST_METHOD');
	if(!$method)$method = "GET";
	$http = getenv('SERVER_PROTOCOL');
	if(!$http)$http = "HTTP/1.0";
	$uri = $file?$file:getenv('REQUEST_URI');
	if(!$uri)$uri = '/';
	return "$method $uri $http";
}
function get_request_headers_list(){
	global $_SERVER;
	$headers = [];
	foreach($_SERVER as $header=>$value){
		if(strpos($header,"HTTP_") !== 0)continue;
			strtr(ucwords(strtr(strtolower(substr($header,5)),'_',' ')),' ','-').": ".$value;
	}
	return $headers;
}
function get_request_headers_string(){
	global $_SERVER;
	$headers = '';
	foreach($_SERVER as $header=>$value){
		if(strpos($header,"HTTP_") !== 0)continue;
		$headers .= strtr(ucwords(strtr(strtolower(substr($header,5)),'_',' ')),' ','-').": ".$value."\r\n";
	}
	return $headers;
}
function get_request_headers(){
	global $_SERVER;
	$headers = [];
	foreach($_SERVER as $header=>$value){
		if(strpos($header,"HTTP_") !== 0)continue;
		$headers[strtr(ucwords(strtr(strtolower(substr($header,5)),'_',' ')),' ','-')] = $value;
	}
	return $headers;
}
function get_request_query(bool $array = false){
	global $_REQUEST;
	$query = @getenv("QUERY_STRING");
	if(!$query && $_REQUEST){
	  if($array)return $_REQUEST;
	  $query = http_build_query($_REQUEST);
	}
	if($array)
		return parse_str($query);
	return $query;
}
function get_request_string($file = false){
	return get_request_title($file)."\r\n".get_request_headers_string()."\r\n".get_request_query();
}
function http_response_status(int $code = null){
	if(!$code)$code = http_response_code();
	$codes = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		419 => 'Authentication Timeout',
		420 => 'Enhance Your Calm',
		420 => 'Method Failure',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		424 => 'Method Failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		444 => 'No Response',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		451 => 'Redirect',
		451 => 'Unavailable For Legal Reasons',
		494 => 'Request Header Too Large',
		495 => 'Cert Error',
		496 => 'No Cert',
		497 => 'HTTP to HTTPS',
		499 => 'Client Closed Request',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		598 => 'Network read timeout error',
		599 => 'Network connect timeout error',
	];
	return isset($codes[$code])?$codes[$code]:'Unknown';
}
function get_response_title(){
	$protocol = getenv('SERVER_PROTOCOL');
	if(!$protocol)
		$protocol = 'HTTP/1.0';
	$code = http_response_code();
	$status = http_response_status();
	return "$protocol $code $status";
}
function get_response_headers(){
	$arr = [];
	$hdrs = headers_list();
	foreach($hdrs as $hdr){
		$hdr = explode(':',$hdr);
		$arr[$hdr[0]] = trim($hdr[1]);
	}
	return $arr;
}
function get_response_headers_list(){
	return headers_list();
}
function get_response_headers_string(){
	return implode("\r\n",headers_list());
}
function get_response_content(){
	return ob_get_contents();
}
function get_response_string(){
	return get_response_title()."\r\n".get_response_headers_string()."\r\n\r\n".get_response_content();
}
class Cryptor {
	public static function rot13(string $str){
		return strtr($str,[
			"a"=>"n","b"=>"o","c"=>"p","d"=>"q",
			"e"=>"r","f"=>"s","g"=>"t","h"=>"u",
			"i"=>"v","j"=>"w","k"=>"x","l"=>"y",
			"m"=>"z","n"=>"a","o"=>"b","p"=>"c",
			"q"=>"d","r"=>"e","s"=>"f","t"=>"g",
			"u"=>"h","v"=>"i","w"=>"j","x"=>"k",
			"y"=>"l","z"=>"m","A"=>"N","B"=>"O",
			"C"=>"P","D"=>"Q","E"=>"R","F"=>"S",
			"G"=>"T","H"=>"U","I"=>"V","J"=>"W",
			"K"=>"X","L"=>"Y","M"=>"Z","N"=>"A",
			"O"=>"B","P"=>"C","Q"=>"D","R"=>"E",
			"S"=>"F","T"=>"G","U"=>"H","V"=>"I",
			"W"=>"J","X"=>"K","Y"=>"L","Z"=>"M"
		]);
	}
}
class binaryString {
	private $binery = "",$position = 0;
	public function __construct(string $str){
		$this->binary = base2_encode($str);
	}
	public function bfput(int $size = 8){
		if($size < 1)$size = 1;
		$length = strlen($this->binary);
		$this->binary .= '1'.str_repeat('0',($size - ($length + 1) % $size) % $size);
	}
	public function bfunput(){
		$this->binary = substr_replace(rtrim($this->binary,'0'),'',-1,1);
	}
	public function blocks(int $size = 0){
		$bfputed = false;
		if(strlen($this->binary) % $size != 0){
			$this->bfput($size);
			$bfputed = true;
		}
		$blocks = str_split($this->binary,$size);
		if($bfputed)$this->bfunput();
		return $blocks;
	}
	public function get(int $size = 0){
		$blocks = $this->blocks($size);
		return array_merge(function($block){
			return base_convert($block,2,10);
		},$blocks);
	}
	public function string(){
		$bfputed = false;
		if(strlen($this->binary) % 8 != 0){
			$this->bfput(8);
			$bfputed = true;
		}
		$string = base2_decode($this->binary);
		if($bfputed)$this->bfunput();
		return $string;
	}
	public function __toString(){
		return $this->string();
	}
	public function write(string $binary){
		$this->binary = substr_replace($this->binary,$binary,$this->position,strlen($binary));
	}
	public function put(string $binary,int $size){
		$this->binary = substr_replace($this->binary,$binary,$this->position,$size);
	}
	public function read(int $size = 8){
		return substr($this->binary,$this->position,$size);
	}
	const SET = 0;
	const CUR = 1;
	const END = 2;
	public function seek(int $size,int $type = 0){
		switch($type){
			case 0:
				$this->position = $size;
			break;
			case 1:
				$this->position += $size;
			break;
			case 2:
				$this->position = strlen($this->binary) - $size;
			break;
			default:
				return new XNError("binaryString seekable", "seekable type not found", XNError::NOTIC);
		}
	}
	public function seekc(int $size,int $type = 0){
		return $this->seek($size * 8,$type);
	}
	public function tell(){
		return $this->position;
	}
	public function size(int $size = 8){
		return ceil(strlen($this->binary) / $size);
	}
	public function writec(string $data){
		$this->write(base2_encode($data));
	}
	public function putc(string $data,int $size){
		$this->put(base2_encode($data),$size);
	}
	public function readc(int $size = 1){
		$read = $this->read($size * 8);
		if(strlen($read) % 8 != 0){
			$read .= '1'.str_repeat('0',(8 - (strlen($read) + 1) % 8) % 8);
		}
		return base2_decode($read);
	}
	public function writei(int $data){
		$this->binary = substr_replace($this->binary,base_convert($data,10,2),$this->position,floor(log($data,2)));
	}
	public function puti(int $data,int $size){
		$this->binary = substr_replace($this->binary,base_convert($data,10,2),$this->position,$size);
	}
	public function readi(int $size = 1){
		return base_convert($this->read($size),2,10);
	}
	public function next(){
		++$this->position;
	}
	public function prev(){
		--$this->position;
	}
	public function current(){
		return @$this->binary[$this->position];
	}
	public function nextc(){
		$this->position += 8;
	}
	public function prevc(){
		$this->postion -= 8;
	}
	public function currentc(){
		return $this->readc(8);
	}
	public function getb(){
		return @$this->binary[$this->position++];
	}
	public function getc(){
		$read = $this->readc();
		$this->position += 8;
		return $read;
	}
	public function eof(){
		return $this->position < 0 || $this->position >= strlen($this->binary);
	}
	public function geti(int $size = 8){
		$read = $this->readi($size);
		$this->position += $size;
		return $read;
	}
	public function run(object $func,int $size = 8){
		$n = strlen($this->binary);
		for($c = 0;$c < $l = $n;++$c){
			($func)($this->read($size),$this);
			$n = strlen($this->binary);
			$this->position += $n - $l + $size;
		}
	}
	public function runc(object $func,int $size = 1){
		$n = strlen($this->binary);
		for($c = 0;$c < $l = $n;++$c){
			($func)($this->readc($size),$this);
			$n = strlen($this->binary);
			$this->position += $n - $l + $size;
		}
	}
	public function runi(object $func,int $size = 8){
		$n = strlen($this->binary);
		for($c = 0;$c < $l = $n;++$c){
			($func)($this->readi($size),$this);
			$n = strlen($this->binary);
			$this->position += $n - $l + $size;
		}
	}
	public function delete(int $size = 8){
		$this->binary = substr_replace($this->binary,'',$this->position,$size);
	}
	public function rewind(){
		$this->position = 0;
	}
	public function end(){
		$this->position = strlen($this->binary);
	}
	public function reverse(int $size = 8){
		if($size == 1)$this->binary = strrev($this->binary);
		else $this->binary = implode('',array_reverse(str_split($this->binary,$size)));
	}
}
class ImageReader {
	public $im;
	public function __construct($image){
		$this->im = $image;
	}
	public function equal(array $i2){
		$i1 = $this->im;
		$w = imagesx($i1);
		$h = imagesy($i1);
		$w2 = count($i2);
		$h2 = count(@$i2[0]);
		if($w != $w2 || $h != $h2)return false;
		for($x = 0;$x < $w;++$x)
			for($y = 0;$y < $h;++$y)
				if(imagecolorat($i1,$x,$y) != @$i2[$x][$y])
					return false;
	  return true;
	}
	public function pixels(int $x = 0,int $y = 0,int $w = 0,int $h = 0){
		$i = $this->im;
		$iw = imagesx($i);
		$ih = imagesy($i);
		if($w <= 0)$w += $iw;
		if($h <= 0)$h += $ih;
		if($x < 0 || $y < 0 || $w < 0 || $h < 0 || $x+$w > $iw || $y+$h > $ih)
			return false;
		$pixels = [];
		$x0 = 0;
		for($w0 = $w;$w0 > 0;--$w0){
			$pixels[$x0] = [];
			$y0 = 0;
			for($h0 = $h;$h0 > 0;--$h0){
				$pixels[$x0][$y0] = imagecolorat($i,$x0+$x,$y0+$y);
				++$y0;
			}
			++$x0;
		}
		return $pixels;
	}
	public function search(array $i2){
		$i1 = $this->im;
		$w = imagesx($i1);
		$h = imagesy($i1);
		$w2 = count($i2);
		$h2 = count(@$i2[0]);
		if($w < $w2 || $h < $h2)return false;
			for($x = 0;$x < $w-$w2+1;++$x)
				for($y = 0;$y < $h-$h2+1;++$y)
					if($this->pixels($x,$y,$w2,$h2) == $i2)
						return [$x,$y];
		return false;
	}
	public function setpixels(array $i,int $a = 0,int $b = 0){
		$i = $this->im;
		foreach($i as $x=>$p)
			foreach($p as $y=>$c)
				imagesetpixel($i,$x+$a,$y+$b,$c);
	}
	public function resize($w,$h){
		$m = imagecreatetruecolor($w,$h);
		$i = $this->im;
		imagecopyresampled($m,$i,0,0,0,0,$w,$h,imagesx($i),imagesy($i));
		return $m;
	}
	public function size(){
		return [imagesx($this->im),imagesy($this->y)];
	}
}

function xnfread($stream,int $length = 1){
	if(!is_resource($stream))return false;
	$read = '';
	for($c = 0;($l = strlen($read)) < $length;++$c){
		$get = stream_get_contents($stream,$length - $l);
		if($get === false || ($get === '' && $c < 0))
			return $read;
		elseif($get === '')--$c;
		else $read .= $get;
	}
	return $read;
}
function xnfgetc($stream){
	if(!is_resource($stream))return false;
	$r = fgetc($stream);
	while($r === '')
		$r = fgetc($stream);
	return $r;
}
function xnfgets($stream){
	if(!is_resource($stream))return false;
	$r = fgets($stream);
	while($r === '' && $r[strlen($r) - 1] !== "\n"){
		if($r === false)
			return false;
		$r .= fgets($stream);
	}
	return $r;
}
function xnfgetss($stream){
	if(!is_resource($stream))return false;
	$r = fgetss($stream);
	while($r === '' && $r[strlen($r) - 1] !== "\n"){
		if($r === false)
			return false;
		$r .= fgetss($stream);
	}
	return $r;
}
function xnfwrite($stream,$content = '',int $length = -1){
	if($length == -1)
		$length = strlen($content);
	else $content = substr($content,$length);
	if($length === 0)return 0;
	$w = fwrite($stream,$content,$length);
	if($w === false)return false;
	for($c = 0;$w < $length;++$c){
		$n = fwrite($stream,substr($content,$w),$length - $w);
		if($n === false || ($n === 0 && $c < 0))
			return $n === false && $w == 0?false:$n;
		elseif($n === 0)
			--$c;
		else $w += $n;
	}
	return $w;
}
if(!extension_loaded("sockets")){
	define('AF_INET',	  0);
	define('AF_INET6',	  1);
	define('SOCK_STREAM', 2);
	define('SOL_SOCKET',  3);
	define('SO_RCVTIMEO', 4);
	define('SO_SNDTIMEO', 5);
}
function socket_connect_socks5($socket,string $username = '',string $password = ''){
	if(!is_resource($socket))return false;
	if($username && $password)
		fwrite($socket,"\x05\x02\x00\x02");
	else fwrite($socket,"\x05\x01\x00");
	$version = ord(fgetc($socket));
	$method = ord(fgetc($socket));
	if($version != 5){
		new XNError("socket_connect_socks5", "Wrong SOCKS5 version: $version", XNError::NOTIC);
		return false;
	}
	if($method == 5){
		fwrite($socket,"\x01".chr(strlen($username)).$username.chr(strlen($password)).$password);
		$version = ord(fgetc($socket));
		if($version !== 1){
			new XNError("socket_connect_socks5", "Wrong authorized SOCKS version: $version", XNError::NOTIC);
			return false;
		}
		$result = ord(fgetc($socket));
		if($result !== 0){
			new XNError("socket_connect_socks5", "Wrong authorization status: $version", XNError::NOTIC);
			return false;
		}
	}elseif($method !== 0){
		new XNError("socket_connect_socks5", "Wrong method: $method", XNError::NOTIC);
		return false;
	}
	$data = "\x05\x01\x00";
	$server = explode(":",stream_socket_get_name($socket,true));
	$port = (int)$server[1];
	$server = $server[0];
	if(filter_var($server,FILTER_VALIDATE_IP)){
		$ip = inet_pton($server);
		$data .= (strlen($ip) == 4?"\x01":"\x04").$ip;
	}else
		$data .= "\x03".chr(strlen($server)).$server;
	$data .= chr($port/256).chr($port);
	fwrite($socket,$data);
	$version = ord(fgetc($socket));
	if($version != 5){
		new XNError("socket_connect_socks5", "Wrong SOCKS5 version: $version", XNError::NOTIC);
		return false;
	}
	$rep = ord(fgetc($socket));
	if($rep !== 0){
		new XNError("socket_connect_socks5", "Wrong SOCKS5 rep: $rep", XNError::NOTIC);
		return false;
	}
	$rsv = ord(fgetc($socket));
	if($rsv !== 0){
		new XNError("socket_connect_socks5", "Wrong socks5 final RSV: $rsv", XNError::NOTIC);
		return false;
	}
	switch(ord(fgetc($socket))){
		case 1:
			$ip = inet_ntop(fread($socket,4));
		break;
		case 4:
			$ip = inet_ntop(fread($socket,16));
		break;
		case 3:
			$ip = fread($socket,ord(fgetc($socket)));
		break;
	}
	$port = ord(fgetc($socket)) * 256 + ord(fgetc($socket));
	return "$ip:$port";
}
function xnloop(int $loop = -1,string $file = null,bool $wait = false,bool $close = false){
	if(!$file)$file = thefile();
	$file = to_web_visibly($file);
	$headers = get_request_headers();
	if(!isset($headers["Xnloop-Now"]) && $loop != -1)
		$headers["Xnloop-Now"] = $loop;
	elseif(isset($headers["Xnloop-Now"])){
		--$headers["Xnloop-Now"];
		if($headers["Xnloop-Now"] == 0)
			return 2;
	}
	$loop = @fsockopen((getenv('HTTPS') ? 'tls' : 'tcp').'://'.getenv('SERVER_NAME'),getenv('SERVER_PORT'));
	if(!$loop){
		new XNError("xnloop", "Can not looping file $file", XNError::WARNING);
		return false;
	}
	$header = '';
	foreach($headers as $key=>$value)
		$header .= "$key: $value\r\n";
	fwrite($loop,get_request_title()."\r\n".$header."\r\n".get_request_query());
	if($wait){
		fgetc($loop);
		fclose($loop);
	}
	if($close)
		exit;
	return 1;
}
function publicdir(){
	return substr(thefile(),0,-strlen(getenv('REQUEST_URI')));
}
function to_web_visibly($file){
	$home = str_ireplace("file://",'',publicdir());
	$file = str_ireplace("file://",'',$file);
	if(strpos($file,$home) === 0)
		$file = substr($file,strlen($home));
	return strtr($file,'\\','/');
}
function get_web_file($file = false){
	if(!$file)$file = getenv('REQUEST_URI');
	return (getenv('HTTPS') ? 'https' : 'http').'://'.getenv('SERVER_NAME').':'.getenv('SERVER_PORT').'/'.to_web_visibly($file);
}
function rle_decode(string $string){
    $new = '';
    $last = '';
    foreach(str_split($string) as $cur) {
        if($last === "\0") {
            $new .= str_repeat($last, ord($cur));
            $last = '';
        }else{
            $new .= $last;
            $last = $cur;
        }
    }
    return $new . $last;
}
function rle_encode(string $string){
	$new = '';
	$count = 0;
	foreach(str_split($string) as $cur) {
		if($cur === "\0")
			++$count;
		else{
			if($count > 0) {
				$new .= "\x00".chr($count);
				$count = 0;
			}
			$new .= $cur;
		}
	}
	return $new;
}
function open_class(string $name,&$return = 53348987487374,...$input){
	$class = unserialize("O:".strlen($name).":\"$name\":0:{}");
	if($input === [] && $return === 53348987487374)
		return $class;
	$return = call_constructor($class,...$input);
	return $class;
}
function call_constructor(object $class,...$methods){
	if(method_exists($class,"__construct"))
		return $class->__construct(...$methods);
}
function is_xnnumber($x){
	return $x instanceof XNNumberObject;
}
function is_incomplete_class($x){
	return $x instanceof __PHP_Incomplete_Class;
}
define("SYS_64BIT",PHP_INT_SIZE === 8);
define("SYS_32BIT",PHP_INT_SIZE === 4);
define("SYS_16BIT",PHP_INT_SIZE === 2);
define("SYS_8BIT" ,PHP_INT_SIZE === 1);
function xnprompt_line(string $q = null,int $max = 0){
	if(!isset($GLOBALS['-XN-']['xnprintin'])){
		xnprint_start();
		new XNError("xnprint", "xnprint not started for last, now started", XNError::LOG);
	}
	if($max > 0)$line = rtrim(fgets($GLOBALS['-XN-']['xnprintin'], $max));
	else $line = rtrim(fgets($GLOBALS['-XN-']['xnprintin']));
	return $line;
}
function xnprompt(string $q = null,int $max = 0){
	if(!isset($GLOBALS['-XN-']['xnprintin'])){
		xnprint_start();
		new XNError("xnprint", "xnprint not started for last, now started", XNError::LOG);
	}
	if($max > 0)$read = rtrim(fread($GLOBALS['-XN-']['xnprintin'], $max));
	else $read = rtrim(fgetc($GLOBALS['-XN-']['xnprintin']));
	return $read;
}
function flseek($handle,int $seek,int $type = 0){
	if(!is_resource($handle))
		return false;
	switch($type){
		case 0:
			fseek($handle,0);
			while($seek --> 0)
				fgets($handle);
		break;
		case 1:
			while($seek --> 0)
				fgets($handle);
		break;
		case 2:
			fseek($handle,0);
			$l = [];
			$c = 0;
			while(fgets($handle) !== '')
				$l[++$c] = ftell($handle);
			if(isset($l[$c - $seek]))
				fseek($handle,$l[$c - $seek]);
			elseif($c - $seek < 0)
				fseek($handle,0);
			else
				fseek($handle,0,SEEK_END);
		break;
		default:
			new XNError("binaryString seekable","seekable type not found");
			return false;
	}
}
function create_stream_content(string $content, string $mime_type = "text/plan", string $mode = 'rw+b'){
	return fopen("data://$mime_type," . urlencode($content),$mode);
}
function go_line(int $x){
	$code = implode("\n",array_slice(explode("\n",zlib_decode($GLOBALS['-XN-']['xndefine'])),$x));
	evale($code);
}
define("XNBLOCK",'',true);
function xnblock($block){
	$code = zlib_decode($GLOBALS['-XN-']['xndefine']);
	$block = stripos($code,"XNBLOCK> $block");
	if($block === false)
		return false;
	$code = substr($code,$block + strlen("XNBLOCK> $block"));
	evall($code);
}
function get_source(){
	return zlib_decode($GLOBALS['-XN-']['xndefine']);
}
if(file_exists('.autoinclude.php'))
	try{
		require '.autoinclude.php';
	}catch(Error $e){}
function substring(string $str, int $from, int $to = null){
	if($to === null)
		return substr($str, $from);
	return substr($str, $from, $to - $from);
}
define('MAKEPASS_INFO',1);
define('MAKEPASS_TIME',2);
define('MAKEPASS_LINE',4);
define('MAKEPASS_TRACE',8);
define('MAKEPASS_FILE',16);
define('MAKEPASS_RANDOM',32);
define('MAKEPASS_ENV',64);
define('MAKEPASS_USER',128);
define('MAKEPASS_HOST',256);
define('MAKEPASS_GLOBAL',512);
define('MAKEPASS_DABLLE',1024);
define('MAKEPASS_AGENT',2048);
define('MAKEPASS_QUERY',4096);

define('MAKEPASS_BYTE',0);
define('MAKEPASS_BINARY',1);
define('MAKEPASS_BASE64',2);
define('MAKEPASS_BASE64_URL',3);
define('MAKEPASS_HEX',4);

function makepass(int $length = 64, int $type = 4, int $make = 32, string $salt = ''){
	if($type < 0 || $type > 4 || $make > 8191 || $make < 1 || $length < 1)
		return false;
	$data = '$';
	if($make & MAKEPASS_INFO)
		$data .= php_uname() . $salt . php_sapi_name() . $salt;
	if($make & MAKEPASS_TIME)
		$data .= time() . $salt . '1' . md5(date('ca')) . $salt;
	if($make & MAKEPASS_LINE)
		$data .= theline() . (PHP_INT_MAX % theline()) . $salt . (theline() % (PHP_INT_MAX % theline() * 2));
	if($make & MAKEPASS_TRACE)
		$data .= serialize(debug_backtrace()) . $salt;
	if($make & MAKEPASS_FILE)
		$data .= strrev(md5_file(thefile()) . $salt . thefile()) . $salt;
	if($make & MAKEPASS_RANDOM)
		$data .= random_bytes($length) . $salt;
	if($make & MAKEPASS_ENV)
		$data .= serialize(@$GLOBALS['_SERVER']) . $salt . serialize(@$GLOBALS['_ENV']) . $salt;
	if($make & MAKEPASS_USER)
		$data .= getenv('REMOTE_ADDR') . $salt . strrev(getenv('REMOTE_ADDR'));
	if($make & MAKEPASS_HOST)
		$data .= getenv('HOST_NAME') . $salt . getenv('SERVER_ADMIN') . getenv('HTTP_HOST') . $salt;
	if($make & MAKEPASS_GLOBAL)
		$data .= serialize($GLOBALS) . $salt;
	if($make & MAKEPASS_AGENT)
		$data .= getenv('HTT_USER_AGENT') . $salt;
	if($make & MAKEPASS_QUERY)
		$data .= md5(getenv('REQUEST_METHOD')) . $salt . getenv('QUERY_STRING');
	if($make & MAKEPASS_DABLLE)
		$data .= md5($data) . $salt . hex2bin(sha1($data));
	$a = sha1($data) . md5(substr($data,0,$length)) . sha1(md5($data) . $salt);
	$b = xncrypt($a . $data,$data . $salt);
	$c = strrev(hex2bin(hashs($a . $b . $data . $salt . md5($data))));
	$c = hex2bin(md5($c . $a . $b)) . $c;
	if($type == 0);
	elseif($type == 1)
		$c = base2_encode($c);
	elseif($type == 2)
		$c = base64_encode($c);
	elseif($type == 3)
		$c = base64url_encode($c);
	else
		$c = bin2hex($c);
	return substr($c,0,$length);
}
class XNTelegram {
    public $session = [], $settings = [], $history = [], $socket;

    // constants
    const VERSION = '1';
    const VERSION_CODE = 1;

    const XNSERIALIZATION = 1;
    const SERIALIZATION_COMPRESS = 2;
    const SERIALIZATION_BASE64 = 4;
    
    const SESSION_FLUSH = 1;
    const SESSION_CONNECT = 2;
    const SESSION_SELF = 4;
    const SESSION_TIMER = 8;
    const SESSION_APPDATA = 16;
    const SESSION_LOGIN = 32;
    const SESSION_SETTINGS = 64;

    const FLUSH_LEVEL_1 = 1;
    const FLUSH_LEVEL_2 = 2;

    const NUMBER_LEVEL_1 = 1;
    const NUMBER_LEVEL_2 = 2;
    const NUMBER_LEVEL_3 = 3;

    // crypt
    public function aescalc($msg, $auth, $to_server = true){
        $x = $to_server ? 0 : 8;
        $a = hash('sha256', $msg.substr($auth, $x, 36), true);
        $b = hash('sha256', substr($key, 40 + $x, 36).$msg, true);
        $key = substr($a, 0, 8).substr($b, 8, 16).substr($a, 24, 8);
        $iv = substr($b, 0, 8).substr($a, 8, 16).substr($b, 24, 8);
        return [$key, $iv];
    }
    public function old_aescalc($msg, $auth, $to_server = true){
        $x = $to_server ? 0 : 8;
        $a = sha1($msg.substr($auth, $x, 32), true);
        $b = sha1(substr($auth, 32 + $x, 16).$msg.substr($auth, 48 + $x, 16), true);
        $c = sha1(substr($auth, 64 + $x, 32).$msg, true);
        $d = sha1($msg.substr($auth, 96 + $x, 32), true);
        $key = substr($a, 0, 8).substr($b, 8, 12).substr($c, 4, 12);
        $iv = substr($a, 8, 12).substr($b, 0, 8).substr($c, 16, 4).substr($d, 0, 8);
        return [$key, $iv];
    }
    
    // elements parser
    public $elements = [], $flush = [];
    public function load_elements(){
        $file = $this->settings['flush']['elements_file'];
        if($file && file_exists($file) && ($data = file_get_contents($file)) && ($data = json_decode($file)));
        elseif(($data = file_get_contents('https://core.telegram.org/schema/json')) && ($data = json_decode($file)));
        else
            throw new XNError("XNTelegram loadElements", "can not Connect to https://core.telegram.org", XNError::NETWORK);
        $this->elements = $data;
        if($file && !file_exists($file) && touch($file))
            file_put_contents($file,json_encode($data));
        if($this->settings['flush']['flush'])
            $this->flush_elements($this->settings['flush']['level']);
    }
    public function flush_elements(int $level){
        if($level < 1 && $level > 2){
            new XNError("XNTelegram flushElements", "invalid flush level", XNError::NOTIC);
            return false;
        }
        $flush = &$this->flush;
        if($file = $this->settings['flush']['file']){
            if($file && file_exists($file) && ($data = file_get_contents($file)) && ($data = json_decode($data,true)) && isset($data['methods']) && isset($data['predicates'])){
                $flush['methods'] = (array)$data['methods'];
                $flush['predicates'] = (array)$data['predicates'];
                if(isset($data['ids'])){
                    $flush['ids'] = (array)$data['ids'];
                }
                return;
            }
        }
        $elements = $this->elements;
        $flush['methods'] = [];
        $flush['predicates'] = [];
        if($level == 2){
            foreach($elements['methods'] as &$method){
                $flush['methods'][$method['method']] = &$method;
                $flush['ids'][$method['id']] = &$method;
            }
            foreach($elements['constructors'] as &$cpnstructor){
                $flush['predicates'][$constructor['predicate']] = &$constructor;
                $flush['ids'][$constructor['id']] = &$constructor;
            }
        }else{
            foreach($elements['methods'] as &$method)
                $flush['methods'][$method['method']] = &$method;
            foreach($elements['constructors'] as &$cpnstructor)
                $flush['predicates'][$constructor['predicate']] = &$constructor;
        }
        if($file && (file_exists($file) || (!file_exists($file) && touch($file))))
            file_put_contents($file,json_encode($flush));
    }

    // finders
    const METHOD = 1;
    const CONSTRUCTOR = 2;
    const PREDICATE = 3;
    const AUTO = 4;
    public function find_id(string $id, int $type = 4){
        if(!is_numeric($id))
            $id = XNMath::ascii2string($id);
        if(isset($flush['ids'])){
            if(isset($flush['ids'][$id]))
                return $flush['ids'][$id];
            return false;
        }
        if($type == 1){
            foreach($this->elements['methods'] as $method)
                if($method['id'] == $id)
                    return $method;
            return false;
        }
        if($type == 2 || $type == 3){
            foreach($this->elements['constructors'] as $constructor)
                if($constructor['id'] == $id)
                    return $constructor;
            return false;
        }
        if($type == 4){
            foreach($this->elements['methods'] as $method)
                if($method['id'] == $id)
                    return $method;
            foreach($this->elements['constructors'] as $constructor)
                    if($constructor['id'] == $id)
                        return $constructor;
            return false;
        }
        return false;
    }
    public function find_method(string $method){
        if(isset($flush['methods'])){
            if(isset($flush['methods'][$method]))
                return $flush['methods'][$method];
            return false;
        }
        foreach($this->elements['methods'] as $m)
            if($m['method'] == $method)
                return $m;
        return false;
    }
    public function find_predicate(string $predicate){
        if(isset($flush['predicates'])){
            if(isset($flush['predicates'][$predicate]))
                return $flush['predicates'][$predicate];
            return false;
        }
        foreach($this->elements['constructors'] as $constructor)
            if($constructor['predicate'] == $predicate)
                return $constructor;
        return false;
    }

    // conding
    public function type_encode(string $type,$content){
        $p = strpos($type, '<');
        if($p !== false){
            $sub = substr($type, $p + 1, -1);
            $type = substr($type, 0, $p);
        }
        if(is_array($content) && isset($content[0])){
            $content['_'] = $content[0];
            unset($content[0]);
        }
        switch($type){
            case 'int':
                if(!is_numeric($content)){
                    new XNError('XNTelegram toInt', 'number invalid', XNError::TYPE);
                    return "\0\0\0\0";
                }
                return pack('l', $content);
            case 'int128':
                if(strlen($content) !== 16){
                    new XNError('XNTelegram toInt128', 'content length invalid', XNError::NOTIC);
                    $content = str_pad(substr($content, 0, 16), 16, "\0");
                }
                return (string)$content;
            case 'int256':
                if(strlen($content) !== 32){
                    new XNError('XNTelegram toInt256', 'content length invalid', XNError::NOTIC);
                    $content = str_pad(substr($content, 0, 32), 32, "\0");
                }
                return (string)$content;
            case 'int512':
                if(strlen($content) !== 64){
                    new XNError('XNTelegram toInt512', 'content length invalid', XNError::NOTIC);
                    return str_pad(substr($content, 0, 64), 64, "\0", STR_PAD_LEFT);
                }
                return (string)$content;
            case '#':
                if(!is_numeric($content)){
                    new XNError('XNTelegram toInt', 'number invalid', XNError::TYPE);
                    return "\0\0\0\0";
                }
                return pack('V', $content);
            case 'double':
                if(!is_numeric($content)){
                    new XNError('XNTelegram toDouble', 'double invalid', XNError::TYPE);
                    return "\0\0\0\0\0\0\0\0";
                }
                return pack('d', $content);
            case 'long':
                if(is_string($content) && strlen($content) == 8)
                    return $content;
                elseif(is_string($content)){
                    new XNError('XNTelegram toLong', 'long length invalid', XNError::TYPE);
                    return str_pad(substr($content, 0, 8), 8, "\0", STR_PAD_LEFT);
                }
                if(!is_numeric($content)){
                    new XNError('XNTelegram toLong', 'long invalid', XNError::TYPE);
                    return "\0\0\0\0\0\0\0\0";
                }
                if(SYS_64BIT)
                    return pack('q',$content);
                switch($this->settings['number']){
                    case 1:
                        return pack('l',$content) . "\0\0\0\0";
                    case 2:
                        return strrev(str_pad(xnmath::number2ascii($content), 8, "\0", STR_PAD_RIGTH));
                    case 3:
                        return strrev(str_pad(xnnumber::base_convert($content, 10, 'ascii'), 8, "\0", STR_PAD_RIGTH));
                }
            case 'bytes':
                if(is_array($content) && isset($content['_']) && $content['_'] == 'bytes')
                    $content = base64_decode($content['bytes']);
            case 'string':
                $l = strlen($content);
                if($l < 254)
                    return chr($l) . $content . str_repeat("\0", XNMath::posmod(-$l - 1, 4));
                else
                    return "\xed" . substr(pack('l', $l), 0, 3) . $content . str_repeat("\0", XNMath::posmod(-$l, 4));
            case 'Bool':
                return pack('l', $this->find_predicate((bool)$content ? 'boolTrue' : 'boolFalse')['id']);
            case '!X':
                return $content;
            case 'Vector':
                $data = pack('l', $this->find_predicate('vector')['id']);
            case 'vector':
                if(!isset($data))
                    $data = '';
                if(!is_array($content)){
                    new XNError("XNTelegram toVector","Array invalid", XNError::TYPE);
                    return $data . "\0";
                }
                $data .= pack('l', count($content));
                foreach($content as $now)
                    $data .= $this->type_encode($sub, $now);
                return $data;
            case 'Object':
                if(is_string($content))
                    return $content;
            break;
            case 'gzip_packed':
                return $this->encode_type('string', gzencode((string)$content, 9, 31));
        }
    }
    public function type_write($stream,$type,$content){
        if(!is_resource($stream))
            return false;
        return fwrite($stream,$this->type_encode($type,$content));
    }
    public function type_read($stream,$type = false){
        if(!$type)
            $type = $this->find_id($id = unpack('l', fread($stream,4))[1]);
        if($type === false)
            throw new XNError("XNTelegram id@" . bin2hex($id), 'invalid type id', XNError::TYPE);
        $p = strpos($type, '<');
        if($p !== false){
            $sub = substr($type, $p + 1, -1);
            $type = substr($type, 0, $p);
        }
        switch($type){
            case 'int':
                return unpack('l', fread($stream, 4))[1];
            case 'int128':
                return fread($stream, 16);
            case 'int256':
                return fread($stream, 32);
            case 'int512':
                return fread($stream, 64);
            case '#':
                return unpack('V', fread($stream, 4))[1];
            case 'double':
                return unpack('d', fread($stream, 8))[1];
            case 'bytes':
            case 'string':
                $l = ord(fgetc($stream));
                if($l >= 254){
                    $l = unpack('V', fgetc($stream) . "\0")[1];
                    $str = fread($stream, $l);
                    $res = XNMath::posmod(-$l, 4);
                    if($res > 0)
                        fseek($stream, $res, SEEK_CUR);
                }else{
                    $str = $l > 0 ? fread($stream, $l) : '';
                    $res = XNMath::posmod(-$l - 1, 4);
                    if($res > 0)
                        fseek($stream, $res, SEEK_CUR);
                }
                return $type == 'bytes' ? ['bytes', 'bytes' => base64_encode($str)] : $str;
            case 'gzip_packed':
                return gzdecode($this->type_read($stream, 'string'));
            case 'Vector':
                fseek($stream, 4, SEEK_CUR);
            case 'vector':
                $count = unpack('V', fread($stream, 4))[1];
                $res = [];
                while($count --> 0)
                    $res[] = $this->type_read($stream, $sub);
                return $res;
            case 'Bool':
                return $this->find_id(unpack('l', fread($stream, 4))[1])['predicate'] == 'boolTrue';
            case 'long':
                $content = fread($stream, 8);
                if(SYS_64BIT)
                    return unpack('q', $content)[1];
                switch($this->settings['number']){
                    case 1:
                        return unpack('l', substr($content, 0, 4))[1] * 4294967296;
                    case 2:
                        return xnmath::ascii2number(strrev($content));
                    break;
                    case 3:
                        return xnnumber::base_convert(strrev($content), 'ascii', 10);
                }
        }
    }
    public function type_read_all($stream, array $input){
        if(!is_resource($stream))
            return false;
        $res = [];
        foreach($input as $key => $content)
            $res[$key] = $this->type_read($stream, $content);
        return $res;
    }
    public function type_decode($content, $type = false){
        $stream = create_stream_content($content, 'text/plan', 'rb');
        $result = $this->type_read($stream, $type);
        fclose($stream);
        return $result;
    }
    public function type_decode_all($content, $type = false){
        $stream = create_stream_content($content, 'text/plan', 'rb');
        $result = $this->type_read_all($stream, $type);
        fclose($stream);
        return $result;
    }
    public function type_encode_all(array $input){
        $res = '';
        foreach($input as $content)
            if(isset($content[1]))
                $res .= $this->type_encode($content[0], $content[1]);
        return $res;
    }
    public function type_write_all($stream,array $input){
        if(!is_resource($stream))
            return false;
        return fwrite($stream, $this->type_encode_all($input));
    }

    // peer id
    public function to_supergroup($id){
        return -($id + 10 ** floor(log($id, 10) + 3));
    }
    public function from_supergroup($id){
        return 10 ** floor(log(-$id, 10) - 3) - $id;
    }
    public function is_supergroup($id){
        $id = log(-$id, 10);
        return ($id - (int)$id) * 1000 < 10;
    }
    public function get_info($content){
        if(is_array($id)){
            if(isset($id[0])){
                $id['_'] = $id[0];
                unset($id[0]);
            }
            switch($id['_']){
                case 'inputUserSelf':
                case 'inputPeerSelf':
                case 'self':
                    $id = $this->session['self']['id'];
                break;
                case 'inputPeerUser':
                case 'inputUser':
                case 'peerUser':
                    $id = $id['user_id'];
                case 'userFull':
                    $id = $id['user']['id'];
                break;
                case 'user':
                    $id = $id['id'];
                break;
                case 'inputPeerChat':
                case 'inputChat':
                case 'peerChat':
                    $id = -$id['chat_id'];
                break;
                case 'chatFull':
                case 'chat':
                    $id = -$id['id'];
                break;
                case 'inputPeerChannel':
                case 'inputChannel':
                case 'peerChannel':
                    $id = $this->to_supergroup($id['channel_id']);
                break;
                case 'channelFull':
                case 'channel':
                    $id = $this->to_supergroup($id['id']);
                break;
                default:
            }
        }
        if(is_string($id) && $id !== ''){
            if($id[0] == 'c')
                $id = $this->to_supergroup(substr($id, 1));
            elseif($id[0] == 'h')
                $id = -substr($id, 1);
            elseif($id[0] == 'u')
                $id = substr($id, 1) + 0;
            else $id += 0;
        }
    }

    // robot api
    public function fileid_decode(string $id){
        $id = rle_decode(base64url_decode($id));
        if($id[strlen($id) - 1] != "\x02")
            return false;
        $file = substr($id, 4);
        $id = unpack('l', substr($id, 0, 4))[1];
        $files = [
            0 => [
                "thumb", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long',
                    'volume_id' => 'long',
                    'secret' => 'long',
                    'local_id' => 'int'
                ]
            ],
            2 => [
                "photo", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long',
                    'volume_id' => 'long',
                    'secret' => 'long',
                    'local_id' => 'int'
                ]
            ],
            3 => [
                "voice", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            4 => [
                "video", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            5 => [
                "document", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            8 => [
                "sticker", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            9 => [
                "audio", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            10 => [
                "gif", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ],
            12 => [
                "video_note", [
                    'dc_id' => 'int',
                    'id' => 'long',
                    'access_hash' => 'long'
                ]
            ]
        ];
        if(!isset($files[$id]))
            return false;
        $id = $files[$id];
        $name = $id[0];
        $file = $this->type_decode_all($file, $id[1]);
        return [$name, $file];
    }

    // settings
    public function parse_settings(array $settings = []){
        $settings = [
            "serialization" => self::SERIALIZATION_COMPRESS,
            "session" => [
                "serialization" => self::SESSION_FLUSH + self::SESSION_CONNECT + self::SESSION_SELF + self::SESSION_TIMER + self::SESSION_APPDATA + self::SESSION_LOGIN + self::SESSION_SETTINGS,
            ],
            "time" => [
                "run" => microtime(true),
                "create" => microtime(true),
                "serialized" => 0,
                "unserialized" => 0,
                "logined" => 0
            ],
            "number" => self::NUMBER_LEVEL_3
        ];
        $this->settings = $settings;
    }
}

function getcookie($cookie){
    global $_COOKIE;
    return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : false;
}
function keyindex($index, $array){
    $array = array_keys($array);
    return isset($array[$index]) ? $array[$index] : null;
}
function valueindex($index, $array){
    $array = array_values($array);
    return isset($array[$index]) ? $array[$index] : null;
}
function keynumber($key, $array){
    return array_search($key, array_keys($array));
}
function valuenumber($value, $array){
    return array_search($value, array_values($array));
}
function object2array(object $object){
    $object = (array)$object;
    foreach($object as &$x)
        if(is_object($x))
            $x = object2array($x);
    return $object;
}
function array2object(array $array){
    $array = (object)$array;
    foreach($array as &$x)
        if(is_array($x))
            $x = array2object($x);
    return $array;
}
function length($input){
	switch(gettype($input)){
		case 'array':
			return count($input);
		case 'object':
			return count((array)$input);
		case 'string':
		case 'integer':
		case 'float':
		case 'double':
			return strlen($input);
		default:
			return 0;
	}
}
function fulllength($input){
	switch(gettype($input)){
		case 'object':
			$c = strlen(get_class($input));
			foreach((array)$input as $x => $y)
				$c += (is_bool($x) || $x === null ? 0 : strlen($x)) + fulllength($y);
			return $c;
		case 'array':
			$c = 0;
			foreach($input as $x => $y)
				$c += (is_bool($x) || $x === null ? 0 : strlen($x)) + fulllength($y);
			return $c;
		case 'string':
		case 'integer':
		case 'float':
		case 'double':
			return strlen($input);
		default:
			return 0;
	}
}
function destruct_call(object $object){
	return method_exists($object, '__destruct') ? $object->__destruct() : null;
}
class ReadOutResult {
	public $output, $time, $return, $closure, $arguments;
	public function __toString(){
		return $this->output;
	}
	public function __set($x,$y){
		return $this->$x;
	}
	public function __get($x){
		return $this->$x;
	}
}
function readout(object $func,...$params){
	ob_start();
	$mc = microtime(true);
	$ret = ($func)(...$params);
	$mc = microtime(true)-$mc;
	$res = ob_end_clean($func);
	ob_end_clean();
	$read = new ReadOutResult;
	$read->output = $res;
	$read->time = $mc;
	$read->return = $ret;
	$read->closure = $func;
	$read->arguments = $params;
	return $read;
}
function lsort(&$array){
    $arr = $ar = [];
    foreach($array as $x => $y)
        $arr[$x] = length($y).'-'.$x;
    sort($arr);
    foreach($arr as $x => $y)
        $ar[$x] = $array[substr($y, strpos($y, '-') + 1)];
    $array = $ar;
}
function array_add($array){
	$res = 0;
	foreach($array as $x)
		$res += $x;
	return $res;
}
function array_mul($array){
	$res = 1;
	foreach($array as $x)
		$res *= $x;
	return $res;
}
function println(string $data){
	if(!($out = ob_get_contents()) || $out[strlen($out) - 1] == "\n")
		print $data;
	print "\n" . $data;
}
function echoln(string ...$datas){
	foreach($datas as $data){
		if(!($out = ob_get_contents()) || $out[strlen($out) - 1] == "\n")
			print $data;
		print "\n" . $data;
	}
}

/* tbot game cheat
https://tbot.xyz/api/getHighScores
data : string
https://tbot.xyz/api/setScore
data : string
score : integer
*/
/*$ch = curl_init('https://tbot.xyz/api/setScore');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=eyJ1IjoyMDk2NDk1MDEsIm4iOiJBdmlkICIsImciOiJMdW1iZXJKYWNrIiwiY2kiOiI4MjA0MDkyMzY4MTYzNjA4OTM3IiwiaSI6IkJBQUFBSDEwQVFBckJkYThYMTZ2bzJ0a3Y3ZyJ9MTQ4N2UzMTg3NmIxYTE2MDE0YmM4YzVkNzg0YTRjY2Y%3D&score=666');
curl_exec($ch);
curl_close($ch);*/

$GLOBALS['-XN-']['endTime'] = microtime(true);

/*

source links:
	http://yon.ir/xnphp
	http://b2n.ir/x
	http://l1l.ir/xnphp
	http://llli.ir/xn
	http://fi9.ir/xnphp

*/
?>
