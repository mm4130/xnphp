<?php // xn php v2.3

if(defined('XNVERSION')){
	trigger_error('library before loaded', E_USER_WARNING);
	return;
}

class __xnlib_data {
	static $startTime;
	static $endTime;
	static $startMemory;
	static $endMemory;
	static $dirname;
	static $dirnamedir;
	static $source = false;
	static $cwdtmpfiles = array();

	static $jsonerror;
	static $errorShow = true;
	static $errorTypeShow = array(
		true, true, false, true, true, true, true, true, false, true, true, true, true, true, true, true, true
	);
	static $errorHandler;
	static $lastError;
	static $xndataFile;
	static $xnblock = 0;

	static $push = array();
	static $pushed = 0;

	static $installedJson;
	static $installedHex;
	static $installedBase64;
	static $installedMbstr;
	static $installedIconv;
	static $installedHash;
	static $installedHashHmac;
	static $installedHashHkdf;
	static $installedHashPbkdf2;
	static $installedBcmath;
	static $installedMhash;
	static $installedCrypt;
	static $installedRandomDiv;
	static $installedUrlcoding;
	static $installedUtf8coding;
	static $installedMtrand;
}
class xnlib {
	const version = 2.3;

	static $tmp;
	static $isMobile  = false;
	static $browserType = false;
	static $userAgent = false;
	static $SoftWare = false;
	static $isIphone = false;
	static $isIE = false;
	static $isApache = false;
	static $isNginx = false;
	static $isIIS = false;
	static $isIIS7 = false;

	static $requestTime = 0;
	static $loadTime = 0;
	static $memoryUsage = 0;
	static $random = 0;

	static $query = '';
	static $method = '';
	static $PUT = '';
	static $GET = '';
	static $POST = '';
	static $link = '';
	static $server = '';
	static $remoter = '';

	static $includeAt = array();

	public static function memlimitfree(){
		return get_memory_limit() - memory_get_peak_usage();
	}
	public static function memof($var){
		$mem = memory_get_usage();
		$tmp = unserialize(serialize($var));
		return memory_get_usage() - $mem;
	}
	public static function runtime(){
		return microtime(true) - xnlib::$requestTime;
	}
	private static function _sizeof($var){
		if(count(func_get_args()) > 1){
			$parent = func_get_arg(1);
			if(in_array($var, $parent, true))return 24;
			$parent[] = $var;
		}else $parent = array($var);
		switch(gettype($var)){
			case 'object':
				$c = strlen(get_class($var));
				foreach((array)$var as $x => $y)
					$c += (is_bool($x) || $x === null ? 1 : strlen($x)) + self::_sizeof($y, $parent);
				return $c;
			case 'array':
				$c = 0;
				foreach($var as $x => $y)
					$c += (is_bool($x) || $x === null ? 1 : strlen($x)) + self::_sizeof($y, $parent);
				return $c;
			case 'string':
			case 'integer':
			case 'float':
			case 'double':
				return strlen($var);
			default:
				return 1;
		}
	}
	public static function sizeof($var){
		return self::_sizeof($var);
	}
}

__xnlib_data::$startTime =  microtime(true);
__xnlib_data::$startMemory = memory_get_usage();
__xnlib_data::$dirname = __DIR__;

xnlib::$includeAt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
if(!isset(xnlib::$includeAt[0])){
	trigger_error('Can not run directly', E_USER_WARNING);
	exit;
}
xnlib::$includeAt = xnlib::$includeAt[0];
__xnlib_data::$installedJson = function_exists('json_encode') && function_exists('json_decode') && function_exists('json_last_error') && function_exists('json_last_error_msg');
__xnlib_data::$installedHex = function_exists('hex2bin') && function_exists('bin2hex');
__xnlib_data::$installedBase64 = function_exists('base64_encode') && function_exists('base64_decode');
__xnlib_data::$installedMbstr = extension_loaded('mbstring');
__xnlib_data::$installedIconv = extension_loaded('iconv');
__xnlib_data::$installedHash = function_exists('hash') && function_exists('hash_algos');
__xnlib_data::$installedHashHmac = function_exists('hash_hmac') && function_exists('hash_hmac_algos');
__xnlib_data::$installedHashHkdf = __xnlib_data::$installedHashHmac && function_exists('hash_hkdf');
__xnlib_data::$installedHashPbkdf2 = __xnlib_data::$installedHashHmac && function_exists('hash_pbkdf2');
__xnlib_data::$installedBcmath = extension_loaded('bcmath');
__xnlib_data::$installedMhash = extension_loaded('mhash');
__xnlib_data::$installedCrypt = function_exists('crypt');
__xnlib_data::$installedRandomDiv = function_exists('random_bytes') && function_exists('random_int');
__xnlib_data::$installedUrlcoding = function_exists('url_encode') && function_exists('url_decode');
__xnlib_data::$installedUtf8coding = function_exists('utf8_encode') && function_exists('utf8_decode');
__xnlib_data::$installedMtrand = function_exists('mt_rand');

if(!defined('PHP_INT_MIN'))define('PHP_INT_MIN', ~PHP_INT_MAX);
define("XNVERSION", "2.3");

xnlib::$random = rand(PHP_INT_MIN, PHP_INT_MAX);
xnlib::$method = getenv('REQUEST_METHOD');
xnlib::$PUT = file_get_contents('php://input');
xnlib::$query = getenv('QUERY_STRING');
if(xnlib::$query === false){
	if(isset($_GET) && $_GET !== array())xnlib::$query = http_build_query($_GET);
	elseif(isset($_POST) && $_POST !== array())xnlib::$query = http_build_query($_POST);
	elseif(isset($_REQUEST))xnlib::$query = http_build_query($_REQUEST);
	else xnlib::$query = '';
}
if(xnlib::$query === '')
	xnlib::$query = xnlib::$PUT;
if(xnlib::$method === 'GET')
	xnlib::$GET = xnlib::$query;
elseif(xnlib::$method === 'POST')
	xnlib::$POST = xnlib::$query;

xnlib::$userAgent = getenv('HTTP_USER_AGENT');
xnlib::$SoftWare  = getenv('SERVER_SOFTWARE');
xnlib::$requestTime = isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] :
	(isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : __xnlib_data::$startTime);
if(xnlib::$userAgent){
	xnlib::$isMobile =  strpos(xnlib::$userAgent, 'Mobile')	 !== false ||
						strpos(xnlib::$userAgent, 'Android')	!== false ||
						strpos(xnlib::$userAgent, 'Silk/')	  !== false ||
						strpos(xnlib::$userAgent, 'Kindle')	 !== false ||
						strpos(xnlib::$userAgent, 'BlackBerry') !== false ||
						strpos(xnlib::$userAgent, 'Opera Mini') !== false ||
						strpos(xnlib::$userAgent, 'Opera Mobi') !== false;
	if(strpos(xnlib::$userAgent, 'Lynx') !== false)xnlib::$browserType = 'Lynx';
	elseif(strpos(xnlib::$userAgent, 'Edge') !== false)xnlib::$browserType = 'Edge';
	elseif(stripos(xnlib::$userAgent, 'chrome') !== false)xnlib::$browserType = 'Chrome';
	elseif(stripos(xnlib::$userAgent, 'safari') !== false)xnlib::$browserType = 'Safari';
	elseif(strpos(xnlib::$userAgent, 'Win') !== false && (strpos(xnlib::$userAgent, 'MSIE') !== false || strpos(xnlib::$userAgent, 'Trident') !== false))xnlib::$browserType = 'WINIE';
	elseif(strpos(xnlib::$userAgent, 'MSIE') !== false && strpos(xnlib::$userAgent, 'Mac') !== false)xnlib::$browserType = 'MACIE';
	elseif(strpos(xnlib::$userAgent, 'Gecko') !== false)xnlib::$browserType = 'Gecko';
	elseif(strpos(xnlib::$userAgent, 'Opera') !== false)xnlib::$browserType = 'Opera';
	elseif(strpos(xnlib::$userAgent, 'Nav') !== false && strpos(xnlib::$userAgent, 'Mozilla/4.') !== false)xnlib::$browserType = 'NS4';
}
if(xnlib::$browserType == 'Safari' && stripos(xnlib::$userAgent, 'mobile' ) !== false)xnlib::$isIphone = true;
if(xnlib::$browserType == 'MACIE' || xnlib::$browserType == 'WINIE')xnlib::$isIE = true;
if(strpos(xnlib::$SoftWare, 'Apache') !== false || strpos(xnlib::$SoftWare, 'LiteSpeed') !== false)xnlib::$isApache = true;
if(strpos(xnlib::$SoftWare, 'nginx') !== false)xnlib::$isNginx = true;
if(!xnlib::$isApache && (strpos(xnlib::$SoftWare, 'Microsoft-IIS') !== false || strpos(xnlib::$SoftWare, 'ExpressionDevServer') !== false))xnlib::$isIIS = true;
if(xnlib::$isIIS && (int)substr(xnlib::$SoftWare, strpos(xnlib::$SoftWare, 'Microsoft-IIS/') + 14) >= 7)xnlib::$isIIS7 = true;

if(!function_exists('call_user_method_array')){
	eval('function call_user_method_array($method,$class,$params){return $class::$method(...$params);};');
}
if(!function_exists('call_user_method')){
	eval('function call_user_method($method,$class,...$params){return $class::$method(...$params);};');
}
if(!function_exists('call_user_func')){
	eval('function call_user_func($func,...$params){if(is_array($func)){$funct=$func[0];unset($func[0]);foreach($func as $f)$funct=$funct->$f;$func=$funct;}return $func(...$params);}');
}
if(!function_exists('call_user_func_array')){
	eval('function call_user_func_array($func,$params){if(is_array($func)){$funct=$func[0];unset($func[0]);foreach($func as $f)$funct=$funct->$f;$func=$funct;}return $func(...$params);}');
}

class ThumbCode {
	private $code = false;
	public function __construct($func){
		$this->code = $func;
	}
	public function __destruct(){
		if($this->code){
			$code = $this->code;
			$code();
		}
	}
	public function close(){
		$this->code = false;
	}
	public function __clone(){
		return new ThumbCode($this->code);
	}
}
function xnupdate($data = null){
	$code = @gzinflate(file_get_contents('http://xntm.ir/lib/download.php?#php'));
	if(!$code){
		$code = file_get_contents('https://raw.githubusercontent.com/xnlib/xnphp/master/xn.php');
		if($data === true)
			$datas = file_get_contents('https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.xnd.php');
	}elseif($data === true)
		$datas = gzinflate(file_get_contents('http://xntm.ir/lib/download.php?#phpdata'));
	file_put_contents('xn.php', $code);
	if($data === true)
		file_put_contents('xndata.xnd', $datas);
	if(file_exists('xn.min.php'))
		file_put_contents('xn.min.php', compress_php_src($code));
	return $data === true ? strlen($code) + code($datas) : strlen($code);
}

/* ---------- Equalization PHP Version ---------- */

/*
\Throwable
├── \Exception (implements \Throwable)
│   ├── \LogicException (extends \Exception)
│   │   ├── \BadFunctionCallException (extends \LogicException)
│   │   │   └── \BadMethodCallException (extends \BadFunctionCallException)
│   │   ├── \DomainException (extends \LogicException)
│   │   ├── \InvalidArgumentException (extends \LogicException)
│   │   ├── \LengthException (extends \LogicException)
│   │   └── \OutOfRangeException (extends \LogicException)
│   └── \RuntimeException (extends \Exception)
│	   ├── \OutOfBoundsException (extends \RuntimeException)
│	   ├── \OverflowException (extends \RuntimeException)
│	   ├── \RangeException (extends \RuntimeException)
│	   ├── \UnderflowException (extends \RuntimeException)
│	   └── \UnexpectedValueException (extends \RuntimeException)
└── \Error (implements \Throwable)
	├── \XNError (extends \Error)
	├── \AssertionError (extends \Error)
	├── \ParseError (extends \Error)
	├── \TypeError (extends \Error)
	│   └── \ArgumentCountError (extends \TypeError)
	└── \ArithmeticError (extends \Error)
		└── \DivisionByZeroError extends \ArithmeticError)
		*/
if(!class_exists('Error'				   )) {class Error extends Exception {}}
if(!class_exists('LogicException'		   )) {class LogicException extends Exception {}}
if(!class_exists('BadFunctionCallException')) {class BadFunctionCallException extends LogicException {}}
if(!class_exists('BadMethodCallException'  )) {class BadMethodCallException extends BadFunctionCallException {}}
if(!class_exists('DomainException'		   )) {class DomainException extends LogicException {}}
if(!class_exists('InvalidArgumentException')) {class InvalidArgumentException extends LogicException {}}
if(!class_exists('LengthException'		   )) {class LengthException extends LogicException {}}
if(!class_exists('OutOfRangeException'	   )) {class OutOfRangeExcpetion extends LogicException {}}
if(!class_exists('RuntimeException'		   )) {class RuntimeException extends Exception {}}
if(!class_exists('OutOfBoundException'	   )) {class OutOfBoundException extends RuntimeException {}}
if(!class_exists('OverflowException'	   )) {class OverflowException extends RuntimeException {}}
if(!class_exists('RangeException'		   )) {class RangeException extends RuntimeException {}}
if(!class_exists('UnderflowException'	   )) {class UnderflowException extends RuntimeException {}}
if(!class_exists('UnexpectedValueException')) {class UnexceptedValueException extends RuntimeException {}}
if(!class_exists('AssertionError'		   )) {class AssertionError extends Error {}}
if(!class_exists('ParseError'			   )) {class ParseError extends Error {}}
if(!class_exists('TypeError'			   )) {class TypeError extends Error {}}
if(!class_exists('ArgumentCountError'	   )) {class ArgumentCountError extends TypeError {}}
if(!class_exists('ArithmeticError'		   )) {class ArithmeticError extends Error {}}
if(!class_exists('DivisionByZeroError'	   )) {class DivisionByZeroError extends ArithmeticError {}}

if(!defined('JSON_HEX_TAG'				  ))define('JSON_HEX_TAG'				 ,1);
if(!defined('JSON_HEX_AMP'				  ))define('JSON_HEX_AMP'				 ,2);
if(!defined('JSON_HEX_APOS'				  ))define('JSON_HEX_APOS'				 ,4);
if(!defined('JSON_HEX_QUOT'				  ))define('JSON_HEX_QUOT'				 ,8);
if(!defined('JSON_FORCE_OBJECT'			  ))define('JSON_FORCE_OBJECT'			 ,16);
if(!defined('JSON_NUMERIC_CHECK'		  ))define('JSON_NUMERIC_CHECK'			 ,32);
if(!defined('JSON_UNESCAPED_SLASHES'	  ))define('JSON_UNESCAPED_SLASHES'		 ,64);
if(!defined('JSON_PRETTY_PRINT'			  ))define('JSON_PRETTY_PRINT'			 ,128);
if(!defined('JSON_UNESCAPED_UNICODE'	  ))define('JSON_UNESCAPED_UNICODE'		 ,256);
if(!defined('JSON_PARTIAL_OUTPUT_ON_ERROR'))define('JSON_PARTIAL_OUTPUT_ON_ERROR',512);
if(!defined('JSON_PRESERVE_ZERO_FRACTION' ))define('JSON_PRESERVE_ZERO_FRACTION' ,1024);

if(!defined('JSON_ERROR_NONE'				  ))define('JSON_ERROR_NONE'				 ,0);
if(!defined('JSON_ERROR_DEPTH'				  ))define('JSON_ERROR_DEPTH'				 ,1);
if(!defined('JSON_ERROR_STATE_MISMATCH'		  ))define('JSON_ERROR_STATE_MISMATCH'		 ,2);
if(!defined('JSON_ERROR_CTRL_CHAR'			  ))define('JSON_ERROR_CTRL_CHAR'			 ,3);
if(!defined('JSON_ERROR_SYNTAX'				  ))define('JSON_ERROR_SYNTAX'				 ,4);
if(!defined('JSON_ERROR_UTF8'				  ))define('JSON_ERROR_UTF8'				 ,5);
if(!defined('JSON_ERROR_RECURSION'			  ))define('JSON_ERROR_RECURSION'			 ,6);
if(!defined('JSON_ERROR_INF_OR_NAN'			  ))define('JSON_ERROR_INF_OR_NAN'			 ,7);
if(!defined('JSON_ERROR_UNSUPPORTED_TYPE'	  ))define('JSON_ERROR_UNSUPPORTED_TYPE'	 ,8);
if(!defined('JSON_ERROR_INVALID_PROPERTY_NAME'))define('JSON_ERROR_INVALID_PROPERTY_NAME',9);
if(!defined('JSON_ERROR_UTF16'				  ))define('JSON_ERROR_UTF16'				 ,10);

if(!defined('JSON_OBJECT_AS_ARRAY' ))define('JSON_OBJECT_AS_ARRAY' ,1);
if(!defined('JSON_BIGINT_AS_STRING'))define('JSON_BIGINT_AS_STRING',2);
if(!defined('JSON_PARSE_JAVASCRIPT'))define('JSON_PARSE_JAVASCRIPT',4);

__xnlib_data::$jsonerror = JSON_ERROR_NONE;

if(!function_exists('intdiv')){
	function intdiv($dividend, $divisor){
		if($divisor === 0)
			throw new DivisionByZeroError('Division by zero');
		return (int)($dividend / $divisor);
	}
}

class XNError extends Exception {
	protected $message;
	public $HTMLMessage, $consoleMessage, $type, $from;

	const TNONE = 0;
	const TEXIT = 1;
	const TTHROW = 2;

	public static $TYPES = array(
		0  => "Notic			",
		1  => "Warning			",
		2  => "Log				",
		3  => "Status			",
		4  => "Recoverable Error",
		5  => "Syntax Error		",
		6  => "Unexpected		",
		7  => "Undefined		",
		8  => "Anonimouse		",
		9  => "System Error		",
		10 => "Secury Error		",
		11 => "Fatal Error		",
		12 => "Arithmetic Error ",
		13 => "Parse Error		",
		14 => "Type Error		",
		15 => "Network Error	",
		16 => "					"
	);

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
				__xnlib_data::$errorShow = !__xnlib_data::$errorShow;
			else __xnlib_data::$errorTypeShow[$type] = !__xnlib_data::$errorTypeShow[$type];
		}else{
			if($type === false)
				__xnlib_data::$errorShow = $sh;
			else __xnlib_data::$errorTypeShow[$type] = $sh;
		}
	}
	public static function handler($func){
		__xnlib_data::$errorHandler = $func;
	}
	public function __construct($from, $text, $level = null, $type = null){
		if((!@__xnlib_data::$errorTypeShow[$level] && $level != self::TRIM) && $type === null)return;
		$level = @self::$TYPES[$level];
		if(__xnlib_data::$errorTypeShow[self::TRIM])
			$level = rtrim($level, " \t");
		$this->from = $from;
		$debug = debug_backtrace();
		$debug = end($debug);
		$this->file = $debug['file'];
		$this->line = $debug['line'];
		$console = "XN $level > $from: $text in {$debug['file']} on line {$debug['line']}";
		$message = "<b>XN $level</b> &gt; <i>$from</i>: " . nl2br($text). " in <b>{$debug['file']}</b> on line <b>{$debug['line']}</b><br />";
		$this->HTMLMessage = $message;
		$this->consoleMessage = $console;
		$this->message = "XN $level > $from: $text";
		__xnlib_data::$lastError = $this->message;
		if(__xnlib_data::$errorHandler !== null)
			if(is_callable(__xnlib_data::$errorHandler))__xnlib_data::$errorHandler($this);
		$errorsh = __xnlib_data::$errorShow;
		if($errorsh && !$type && show_errors()){
			$headers = get_response_headers();
			if((!isset($headers['Content-type']) || strpos($headers['Content-type'], 'text/html') !== false) && (!isset($headers['Content-Type']) || strpos($headers['Content-Type'], 'text/html') !== false))
				printbr($message);
			else
				printlnbr($console);
		}
		if($errorsh && is_string($errorsh) && (file_exists($errorsh) || touch($errorsh)))
			faddln($errorsh, $console);
		if($type !== null)
			switch($type){
				case self::TNONE:
					break;
				case self::TEXIT:
					exit;
				case self::TTHROW:
					throw $this;
			}
	}
	public function __toString(){
		return $this->message;
	}
	public static function lasterror(){
		return __xnlib_data::$lastError !== null ? __xnlib_data::$lastError : false;
	}
}


// -----------------------------------------------------

function var_read(){
	ob_start();
	call_user_func_array('var_dump', func_get_args());
	return ob_get_clean();
}
function swap(&$var1, &$var2){
	list($var2, $var1) = array($var1, $var2);
}
function swap3(&$var1, &$var2, &$var3){
	list($var3, $var1, $var2) = array($var1, $var2, $var3);
}
function swap4(&$var1, &$var2, &$var3, &$var4){
	list($var4, $var1, $var2, $var3) = array($var1, $var2, $var3, $var4);
}
function dblswap(&$var1, &$var2, &$var3, &$var4){
	list($var2, $var1, $var4, $var3) = array($var1, $var2, $var3, $var4);
}
function theline(){
	$t = debug_backtrace();
	return array_value(end($t), 'line');
}
function thelinecall(){
	$t = debug_backtrace();
	if(!isset($t[1]))
		return false;
	return $t[1]['line'];
}
function thelinecode(){
	return array_value(explode("\n", getsource()), theline() - 1);
}
function getlinecode($line){
	return @array_value(explode("\n", getsource()), $line - 1);
}
function thefile(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['file']))
			return $t[$c]['file'];
	return false;
}
function in_eval(){
	$t = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
	return substr($t[0]['file'], -16) == ' : eval()\'d code';
}
define('THEFILE', thefile());
function thedir(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['file']))
			return dirname($t[$c]['file']);
	return false;
}
define('THEDIR', thedir());
function thefunction(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['function']))
			return $t[$c]['function'];
	return false;
}
function theclass(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['class']))
			return $t[$c]['class'];
	return false;
}
function theargs(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['args']))
			return $t[$c]['args'];
	return false;
}
function themethod(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['type'])){
			if($t[$c]['type'] == '->')
				return 'object';
			return 'static';
		}
	return false;
}
function thethis(){
	$t = debug_backtrace();
	$c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['object']))
			return $t[$c]['object'];
	return false;
}
function thecallcode(){
	$t = debug_backtrace();
	$u = $c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['args']))
			$args = $t[$c]['args'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['function']))
			$func = $t[$c]['function'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['class']))
			$clas = $t[$c]['class'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['type']))
			$type = $t[$c]['type'];
	if(!isset($func) || !isset($args))
		return false;	
	$list = array();
	foreach($args as $arg)
		$list[] = unce($arg);
	$list = $func . '(' . implode(', ', $list) . ')';
	if(isset($type) && isset($clas))
		$list = $clas . $type . $list;
	return $list;
}
function recall($object = null){
	$t = debug_backtrace();
	$u = $c = count($t);
	while(--$c >= 0)
		if(isset($t[$c]['args']))
			$args = $t[$c]['args'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['function']))
			$func = $t[$c]['function'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['class']))
			$clas = $t[$c]['class'];
	$c = $u;
	while(--$c >= 0)
		if(isset($t[$c]['type']))
			$type = $t[$c]['type'];
	if(!isset($func) || !isset($args))
		return false;
	$list = array();
	foreach($args as $arg)
		$list[] = unce($arg);
	$list = $func . '(' . implode(', ', $list) . ')';
	if(isset($type) && isset($clas)){
		if($type == '->'){
			if(!is_object($object))
				return false;
			return eval('return $object->' . $list . ';');
		}else
			return eval("return $clas::$list;");
	}
	return eval("return $list;");
}
function thecolumn(){
	$t = debug_backtrace(1, 2);
	$args = $t[0]['args'];
	$list = array();
	foreach($args as $arg)
		$list[] = preg_unce($arg);
	$list = '/thecolumn[ \n\r\t]*([ \n\r\t]*' . implode('[ \n\r\t]*,[ \n\r\t]*', $list) . '[ \n\r\t]*)/i';
	$line = thelinecode();
	preg_match($list, $line, $match);
	return $match === array() ? false : strpos($line, $match[0]);
}
function thelastcolumn(){
	$t = debug_backtrace(1, 2);
	$args = $t[0]['args'];
	$list = array();
	foreach($args as $arg)
		$list[] = preg_unce($arg);
	$list = '/thelastcolumn[ \n\r\t]*([ \n\r\t]*' . implode('[ \n\r\t]*,[ \n\r\t]*', $list) . '[ \n\r\t]*)/i';
	$line = thelinecode();
	preg_match($list, $line, $match);
	return $match === array() ? false : strpos($line, $match[0]) + strlen($match[0]);
}
function thebreakcolumn(){
	$t = debug_backtrace(1, 2);
	$args = $t[0]['args'];
	$list = array();
	foreach($args as $arg)
		$list[] = preg_unce($arg);
	$list = '/thebreakcolumn[ \n\r\t]*([ \n\r\t]*' . implode('[ \n\r\t]*,[ \n\r\t]*', $list) . '[ \n\r\t]*)(?:.|\n)*;{0,1}/i';
	$line = thelinecode();
	preg_match($list, $line, $match);
	return $match === array() ? false : strpos($line, $match[0]) + strlen($match[0]);
}
function evale(){
	if(func_num_args() === 0)
		throw new Error('Fatal error: Uncaught ArgumentCountError: Too few arguments to function evale(), 0 passed');
	extract($GLOBALS);
	eval(func_get_arg(0));
	exit;
}
function evalc($code){
	return eval('return ' . $code . ';');
}
function evald($code){
	return eval($code);
}
function evalf($file){
	return ($get = file_get_contents($file)) !== false ? eval($get) : false;
}
function evali($code){
	include "data://application/php,$code";
}
function evalio($code){
	include_once "data://application/php,$code";
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
	return is_string($json) && ($json == 'null' || @xncrypt::jsondecode($json, true) !== null);
}
function is_xndata($xndata){
	return $xndata instanceof XNDataString || $xndata instanceof XNDataFile || $xndata instanceof XNDataURL || $xndata instanceof XNData;
}
function array_repeat($array, $count){
	if($count < 1){
		$c = -1;
		foreach($array as $k => $v)
			if(++$c == $k)
				unset($array[$k]);
			else
				return $array;
	}
	return eval('return array_merge(' . substr(str_repeat('$array,', $count), 0, -1) . ');');
}
function evals($str){
	return eval("return \"$str\";");
}
__xnlib_data::$xndataFile = __xnlib_data::$dirname .DIRECTORY_SEPARATOR. 'xndata.xnd';
if(!file_exists(__xnlib_data::$xndataFile))
	__xnlib_data::$xndataFile = null;
function xndata_setfile($file){
	if(file_exists($file))
		__xnlib_data::$xndataFile = $file;
	else
		return false;
	return true;
}
function xndata($name, $length = null){
	$xnd = xndata::xn_data();
	return $xnd->value($name, $length);
}
class TelegramBot {
	public $data,
		   $token,
		   $final,
		   $results = array(),
		   $sents = array(),
		   $save = true,
		   $last,
		   $parser = true,
		   $notresponse = false,
		   $autoaction = false,
		   $handle = false;
	
	const KEYBOARD = 'keyboard';
	const INLINE_KEYBOARD = 'inline_keyboard';
	const REMOVE_KEYBOARD = 'remove_keyboard';
	const FORCE_REPLY = 'force_reply';
	const RESIZE_KEYBOARD = 'resize_keyboard';
	const BTN_TEXT = 'text';
	const BTN_URL = 'url';
	const BTN_DATA = 'callback_data';
	const BTN_SWITCH = 'switch_inline_query';
	const BTN_SWITCH_CURRENT = 'switch_inline_query_current_chat';
	const HTML = 'HTML';
	const MARK_DOWN = 'MarkDown';

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
	}
	public function isTelegram(){
		return in_array(getenv('REMOTE_ADDR'), array(
			'149.154.0.0',
			'149.154.0.1',
			'149.154.0.2',
			'149.154.0.3',
			'149.154.0.4',
			'149.154.0.5',
			'149.154.0.6',
			'149.154.0.7',
			'149.154.0.8',
			'149.154.0.9',
			'149.154.0.10',
			'149.154.0.11',
			'149.154.0.12',
			'149.154.0.13',
			'149.154.0.14',
			'149.154.0.15',
			'149.154.0.16',
			'149.154.167.200',
			'149.154.167.201',
			'149.154.167.202',
			'149.154.167.203',
			'149.154.167.204',
			'149.154.167.205',
			'149.154.167.206',
			'149.154.167.207',
			'149.154.167.208',
			'149.154.167.209',
			'149.154.167.210',
			'149.154.167.211',
			'149.154.167.212',
			'149.154.167.213',
			'149.154.167.214',
			'149.154.167.215',
			'149.154.167.216'
		));
	}
	public function checkTelegram(){
		if(!$this->isTelegram())
			exit;
	}
	public function update($offset = -1, $limit = 1, $timeout = 0){
		if(isset($this->data) && xnlib::$PUT)return $this->data;
		elseif($this->data = xnlib::$PUT)return $this->data = xncrypt::jsondecode($this->data);
		else $res = $this->data = $this->request("getUpdates", array("offset" => $offset, "limit" => $limit, "timeout" => $timeout), 3);
		return (object)$res;
	}
	public function dataUpdate(){
		return $this->data ? $this->data : $this->update();
	}
	public function request($method, $args = array(), $level = 3){
		$args = $this->parse_args($method, $args);
		$res = false;
		$func = $this->handle;
		$handle = $func ? new ThumbCode(
		function()use(&$method, &$args, &$res, &$level, &$func){
			$func((object)array(
				"method" => $method,
				"arguments" => $args,
				"result" => $res,
				"level" => $level
			));
		}) : false;
		if($this->autoaction && isset($args['chat_id'])) {
			switch(strtolower($method)) {
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
			}
			if($action)
				$this->request("sendChatAction", array(
					"chat_id" => $args['chat_id'],
					"action" => $action
				));
		}
		if($level == 1) {
			$args['method'] = $method;
			print xncrypt::jsonencode($args);
			ob_flush();
			$res = true;
		}elseif($level == 2) {
			$res = @fopen("https://api.telegram.org/bot{$this->token}/$method?" . http_build_query($args), 'r');
			if($res)fclose($res = true);
			else $res = false;
		}elseif($level == 3) {
			$c = curl_init("https://api.telegram.org/bot{$this->token}/$method");
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, $args);
			$res = xncrypt::jsondecode(curl_exec($c));
			curl_close($c);
		}elseif($level == 4) {
			$sock = fsockopen('ssl://api.telegram.org', 443);
			fwrite($sock, "GET /bot{$this->token}/$method HTTP/1.1\r\nHost: api.telegram.org\r\nConnection: close\r\n");
			$query = http_build_query($args);
			fwrite($sock, "Content-Type: application/x-www-urlencoded\r\nContent-Length: " . strlen($query) . "\r\n\r\n" . $query);
			$res = true;
		}else return false;
		$args['method'] = $method;
		$args['level'] = $level;
		if($this->save) {
			$this->sents[] = $args;
			$this->results[] = $this->final = $res;
		}
		if($res === false)return false;
		if($res === true)return true;
		if(!$res) {
			$server = array_value(array("OUTPUT", "api.telegram.org", "api.telegram.org"), $level - 1);
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
		$this->results = array();
		$this->sents = array();
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
		if($this->notresponse){
			$notr = $this->notresponse;
			$notr();
		}
	}
	public function sendMessage($chat, $text, $args = array(), $level = 3){
		if(strlen($text) > 4096){
			$args['chat_id'] = $chat;
			$texts = str_split($text, 4096);
			foreach($texts as $text) {
				$args['text'] = $text;
				$this->request("sendMessage", $args, $level);
			}
			return true;
		}
		$args['chat_id'] = $chat;
		$args['text'] = $text;
		return $this->request("sendMessage", $args, $level);
	}
	public function sendAction($chat, $action, $level = 3){
		return $this->request("sendChatAction", array("chat_id" => $chat, "action" => $action), $level);
	}
	public function sendTyping($chat, $level = 3){
		return $this->request("sendChatAction", array("chat_id" => $chat, "action" => "typing"), $level);
	}
	public function setWebhook($url = '', $args = array(), $level = 3){
		$args['url'] = $url ? $url : '';
		return $this->request("setWebhook", $args, $level);
	}
	public function deleteWebhook($level = 3){
		return $this->request("setWebhook", array(), $level);
	}
	public function getChat($chat, $level = 3){
		return $this->request("getChat", array("chat_id" => $chat), $level);
	}
	public function getMembersCount($chat, $level = 3){
		return $this->request("getChatMembersCount", array("chat_id" => $chat), $level);
	}
	public function getMember($chat, $user, $level = 3){
		return $this->request("getChatMember", array("chat_id" => $chat, "user_id" => $user), $level);
	}
	public function getProfile($user, $level = 3){
		$args['user_id'] = $user;
		$args['chat_id'] = $user;
		return $this->request("getUserProfilePhotos", $args, $level);
	}
	public function banMember($chat, $user, $time = false, $level = 3){
		$args = array("chat_id" => $chat, "user_id" => $user);
		if($time)$args['until_date'] = $time;
		return $this->request("kickChatMember", $args, $level);
	}
	public function unbanMember($chat, $user, $level = 3){
		return $this->request("unbanChatMember", array("chat_id" => $chat, "user_id" => $user), $level);
	}
	public function kickMember($chat, $user, $level = 3){
		return array($this->banMember($chat, $user, $level), $this->unbanMember($chat, $user, $level));
	}
	public function getMe($level = 3){
		return $this->request("getMe", array(), $level);
	}
	public function getWebhook($level = 3){
		return $this->request("getWebhookInfo", array(), $level);
	}
	public function resrictMember($chat, $user, $args, $time = false, $level = 3){
		foreach($args as $key => $val)$args["can_$key"] = $val;
		$args['chat_id'] = $chat;
		$args['user_id'] = $user;
		if($time)$args['until_date'] = $time;
		return $this->request("resrictChatMember", $args, $level);
	}
	public function promoteMember($chat, $user, $args = array(), $level = 3){
		foreach($args as $key => $val)$args["can_$key"] = $val;
		$args['chat_id'] = $chat;
		$args['user_id'] = $user;
		return $this->request("promoteChatMember", $args, $level);
	}
	public function exportInviteLink($chat, $level = 3){
		$this->request("exportChatInviteLink", array("chat_id" => $chat), $level);
	}
	public function setChatPhoto($chat, $photo, $level = 3){
		return $this->request("setChatPhoto", array("chat_id" => $chat, "photo" => $photo), $level);
	}
	public function deleteChatPhoto($chat, $level = 3){
		return $this->request("deleteChatPhoto", array("chat_id" => $chat), $level);
	}
	public function setTitle($chat, $title, $level = 3){
		return $this->request("setChatTitle", array("chat_id" => $chat, "title" => $title), $level);
	}
	public function setDescription($chat, $description, $level = 3){
		return $this->request("setChatDescription", array("chat_id" => $chat, "description" => $description), $level);
	}
	public function pinMessage($chat, $message, $disable = false, $level = 3){
		return $this->request("pinChatMessage", array("chat_id" => $chat, "message_id" => $message, "disable_notification" => $disable), $level);
	}
	public function unpinMessage($chat, $level = 3){
		return $this->request("unpinChatMessage", array("chat_id" => $chat), $level);
	}
	public function leaveChat($chat, $level = 3){
		return $this->request("leaveChat", array("chat_id" => $chat), $level);
	}
	public function getAdmins($chat, $level = 3){
		return $this->request("getChatAdministrators", array("chat_id" => $chat), $level);
	}
	public function setChatStickerSet($chat, $sticker, $level = 3){
		return $this->request("setChatStickerSet", array("chat_id" => $chat, "sticker_set_name" => $sticker), $level);
	}
	public function deleteChatStickerSet($chat, $level = 3){
		return $this->request("deleteChatStickerSet", array("chat_id" => $chat), $level);
	}
	public function answerCallback($id, $text, $args = array(), $level = 3){
		$args['callback_query_id'] = $id;
		$args['text'] = $text;
		return $this->request("answerCallbackQuery", $args, $level);
	}
	public function editText($text, $args = array(), $level = 3){
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editMessageText($chat, $msg, $text, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editInlineText($msg, $text, $args = array(), $level = 3){
		$args['inline_message_id'] = $msg;
		$args['text'] = $text;
		return $this->request("editMessageText", $args, $level);
	}
	public function editCaption($caption, $args = array(), $level = 3){
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editMessageCaption($chat, $msg, $caption, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$arsg['message_id'] = $msg;
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editInlineCaption($msg, $caption, $args = array(), $level = 3){
		$arsg['inline_message_id'] = $msg;
		$args['caption'] = $caption;
		return $this->request("editMessageCaption", $args, $level);
	}
	public function editReplyMarkup($reply_makup, $args = array(), $level = 3){
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMessageReplyMarkup($chat, $msg, $reply_makup, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editInlineReplyMarkup($msg, $reply_makup, $args = array(), $level = 3){
		$args['inline_message_id'] = $msg;
		$args['reply_markup'] = $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMedia($media, $args = array(), $level = 3){
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editMessageMedia($chat, $message, $media, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $message;
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editInlineMedia($message, $media, $args = array(), $level = 3){
		$args['inline_message_id'] = $message;
		$args['media'] = $media;
		return $this->request("editMessageMedia",$args,$level);
	}
	public function editKeyboard($reply_makup, $args = array(), $level = 3){
		$args['reply_markup'] = is_array($reply_markup) ? isset($reply_markup['inline_keyboard']) ?
			$reply_markup : array("inline_keyboard" => $reply_markup) : $reply_markup;
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editMessageKeyboard($chat, $msg, $reply_makup, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['message_id'] = $msg;
		$args['reply_markup'] = array("inline_keyboard" => $reply_markup);
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function editInlineKeyboard($msg, $reply_makup, $args = array(), $level = 3){
		$args['inline_message_id'] = $msg;
		$args['reply_markup'] = array("inline_keyboard" => $reply_markup);
		return $this->request("editMessageReplyMarkup", $args, $level);
	}
	public function deleteMessage($chat, $message, $level = 3){
		if(is_array($message)){
			$now = $this->getMessage();
			if($now === false)$now = 0;
			foreach($message as $msg)
				$this->request("deleteMessage", array(
					"chat_id"	 => $chat,
					"message_id" => $msg < 0 ? abs($now + $msg) : $msg
				), $level);
			return true;
		}return $this->request("deleteMessage", array(
			"chat_id"	 => $chat,
			"message_id" => $message
		), $level);
	}
	public function sendMedia($chat, $type, $file, $args = array(), $level = 3){
		$type = strtolower($type);
		if($type == "videonote")$type = "video_note";
		$args['chat_id'] = $chat;
		$args[$type] = $file;
		return $this->request("send" . str_replace('_', '', $type), $args, $level);
	}
	public function sendFile($chat, $file, $args = array(), $level = 3){
		$type = array_value(XNTelegram::botfileid_info($file), 'type');
		if(!$type)return false;
		$args['chat_id'] = $chat;
		$args[$type] = $file;
		return $this->request("send" . str_replace('_', '', $type), $args, $level);
	}
	public function getStickerSet($name, $level = 3){
		return $this->request("getStickerSet", array("name" => $name), $level);
	}
	public function sendDocument($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['document'] = $file;
		return $this->request("sendDocument", $args, $level);
	}
	public function sendPhoto($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['photo'] = $file;
		return $this->request("sendPhoto", $args, $level);
	}
	public function sendVideo($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['video'] = $file;
		return $this->request("sendVideo", $args, $level);
	}
	public function sendAudio($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['audio'] = $file;
		return $this->request("sendAudio", $args, $level);
	}
	public function sendVoice($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['voice'] = $file;
		return $this->request("sendVoice", $args, $level);
	}
	public function sendSticker($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['sticker'] = $file;
		return $this->request("sendSticker", $args, $level);
	}
	public function sendVideoNote($chat, $file, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['video_note'] = $file;
		return $this->request("sendVideoNote", $args, $level);
	}
	public function uploadStickerFile($user, $file, $level = 3){
		return $this->request("uploadStickerFile", array("user_id" => $user, "png_sticker" => $file), $level);
	}
	public function createNewStickerSet($user, $name, $title, $args = array(), $level = 3){
		$args['user_id'] = $user;
		$args['name'] = $name;
		$args['title'] = $title;
		return $this->request("createNewStickerSet", $args, $level);
	}
	public function addStickerToSet($user, $name, $args = array(), $level = 3){
		$args['user_id'] = $user;
		$args['name'] = $name;
		return $this->request("addStickerToSet", $args, $level);
	}
	public function setStickerPositionInSet($sticker, $position, $level = 3){
		return $this->request("setStickerPositionInSet", array("sticker" => $sticker, "position" => $position), $level);
	}
	public function deleteStickerFromSet($sticker, $level = 3){
		return $this->request("deleteStickerFromSet", array("sticker" => $sticker), $level);
	}
	public function answerInline($id, $results, $args = array(), $switch = array(), $level = 3){
		$args['inline_query_id'] = $id;
		$args['results'] = is_array($results) ? xncrypt::jsonencode($results): $results;
		if($switch['text'])$args['switch_pm_text'] = $switch['text'];
		if($switch['parameter'])$args['switch_pm_parameter'] = $switch['parameter'];
		return $this->request("answerInlineQuery", $args, $level);
	}
	public function answerPreCheckout($id, $ok = true, $level = 3){
		if($ok === true)$args = array("pre_checkout_query_id" => $id, "ok" => true);
		else $args = array("pre_checkout_query_id" => $id, "ok" => false, "error_message" => $ok);
		return $this->request("answerPreCheckoutQuery", $args, $level);
	}
	public function setGameScore($user, $score, $args = array(), $level = 3){
		$args['user_id'] = $user;
		$args['score'] = $score;
		return $this->request("setGameScore", $args, $level);
	}
	public function getGameHighScores($user, $args = array(), $level = 3){
		$args['user_id'] = $user;
		return $this->request("getGameHighScores", $args, $level);
	}
	public function sendGame($chat, $name, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['name'] = $name;
		return $this->request("sendGame", $args, $level);
	}
	public function getFile($file, $level = 3){
		return $this->request("getFile", array("file_id" => $file), $level);
	}
	public function readFile($path, $level = 3, $speed = false){
		if($speed)$func = "fget";
		else $func = "file_get_contents";
		if($level == 3) {
			return $func("https://api.telegram.org/file/bot$this->token/$path");
		}
		else return false;
	}
	public function downloadFile($file, $level = 3){
		return $this->readFile($this->getFile($file, 3)->result->file_path, $level);
	}
	public function downloadFileProgress($file, $func, $al, $level = 3){
		$file = $this->request("getFile", array("file_id" => $file), $level);
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
				return $func((object)array("content" => $data, "downloaded" => $dat, "size" => $size, "time" => $up, "endtime" => $all, "speed" => $speed, "pre" => $pre));
			}
			, $al);
		}
		else return false;
	}
	public function sendContact($chat, $phone, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['phone_number'] = $phone;
		return $this->request("sendContact", $args, $level);
	}
	public function sendVenue($chat, $latitude, $longitude, $title, $address, $args = array(), $level = 3){
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
	public function editMessageLiveLocation($latitude, $longitude, $args = array(), $level = 3){
		$args['latitude'] = $latitude;
		$args['longitude'] = $longitude;
		return $this->request("editMessageLiveLocation", $args, $level);
	}
	public function sendLocation($chat, $latitude, $longitude, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['latitude'] = $latitude;
		$args['longitude'] = $longitude;
		$this->request("sendLocation", $args, $level);
	}
	public function sendMediaGroup($chat, $media, $args = array(), $level = 3){
		$args['chat_id'] = $chat;
		$args['media'] = xncrypt::jsonencode($media);
		return $this->request("sendMediaGroup", $args, $level);
	}
	public function forwardMessage($chat, $from, $message, $disable = false, $level = 3){
		return $this->request("forwardMessage", array("chat_id" => $chat, "from_chat_id" => $from, "message_id" => $message, "disable_notification" => $disable), $level);
	}
	public function getAllMembers($chat){
		return xncrypt::jsondecode(file_get_contents("http://xns.elithost.eu/getparticipants/?token=$this->token&chat=$chat"));
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
		return "unknown_update";
	}
	public function getUpdateInType($update = false){
		$update = $update ? $update : $this->data;
		return $update ? $update->{$this->updateType($update)} : false;
	}
	public function readUpdates($func, $while = 0, $limit = 1, $timeout = 0){
		if($while == 0)$while = - 1;
		$offset = 0;
		while($while > 0 || $while < 0) {
			$updates = $this->update($offset, $limit, $timeout);
			if(isset($updates->message_id)) {
				if($offset == 0)$updates = (object)array("result" => array($updates));
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
	public function filterUpdates($filter = array(), $func = false){
		if(in_array($this->updateType(), $filter)) {
			if($func)$func($this->data);
			exit();
		}
	}
	public function unfilterUpdates($filter = array(), $func = false){
		if(!in_array($this->updateType(), $filter)) {
			if($func)$func($this->data);
			exit();
		}
	}
	public function getUser($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->message))return (object)array('chat' => $update->message->chat, 'from' => $update->message->from);
		if(isset($update->chat))return (object)array('chat' => $update->chat, 'from' => $update->from);
		if(isset($update->from))return (object)array('chat' => $update->from, 'from' => $update->from);
		return false;
	}
	public function getMessage($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->message_id))return $update->message_id;
		if(isset($update->message))return $update->message->message_id;
		return false;
	}
	public function getDate($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->date))return $update->date;
		if(isset($update->message))return $update->message->date;
		return false;
	}
	public function getData($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->text))return $update->text;
		if(isset($update->query))return $update->query;
		if(isset($update->caption))return $update->caption;
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
		else return array();
	}
	public function getUpdates(){
		$update = $this->update(0, 999999999999, 0);
		if(isset($update->update_id))return array($update);
		elseif($update->result[0]->update_id)return $update->result;
		else return array();
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
		if(isset($message->thumb_nail))return "thumb_nail";
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
		if(isset($message->thumb_nail))return $message->thumb_nail;
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
	public function sendUpdate($url, $update = false){
		if($update === false)$update = $this->dataUpdate();
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($c, CURLOPT_POSTFIELDS, $update);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$r = curl_exec($c);
		curl_close($c);
		return $r;
	}
	public function requestFromUpdate($chat, $update = false, $args = array(), $level = 3){
		if(!$update)$update = $this->lastUpdate()->message;
		elseif(isset($update->message))$update = $update->message;
		if(!isset($update->message_id))return false;
		if(isset($update->photo)){$method = 'sendPhoto';$obj = $update->photo;}
		elseif(isset($update->video)){$method = 'sendVideo';$obj = $update->video;}
		elseif(isset($update->voice)){$method = 'sendVoice';$obj = $update->voice;}
		elseif(isset($update->audio)){$method = 'sendAudio';$obj = $update->audio;}
		elseif(isset($update->video_note)){$method = 'sendVideoNote';$obj = $update->video_note;}
		elseif(isset($update->sticker)){$method = 'sendSticker';$obj = $update->sticker;}
		elseif(isset($update->document)){$method = 'sendDocument';$obj = $update->document;}
		elseif(isset($update->text)){$method = 'sendMessage';$obj = $update;}
		elseif(isset($update->contact)){$method = 'sendContact';$obj = $update->contact;}
		elseif(isset($update->location)){$method = 'sendLocation';$obj = $update->location;}
		elseif(isset($update->venue)){$method = 'sendVenue';$obj = $update->venue;}
		else return false;
		if(isset($update->caption))$args['caption'] = isset($args['caption']) ? $args['caption'] : $update->caption;
		if($chat !== '' && $chat !== 'chat')$args['chat'] = $chat;
		elseif($chat === 'from')$args['chat'] = $update->from->id;
		else $args['chat'] = $update->chat->id;
		$args = $this->parse_args($method, $args);
		$args['file_id'] = isset($args['file_id']) ? $args['file_id'] : $obj->file_id;
		if($method == 'sendContact'){
			$args['phone_number'] = isset($args['phone_number']) ? $args['phone_number'] : $obj->phone_number;
			$args['first_name'] = isset($args['first_name'])? $args['first_name'] : $obj->first_name;
			$args['last_name'] = isset($args['last_name']) ? $args['last_name'] : (isset($update->last_name) ? $update->last_name : false);
			if($args['last_name'] === false)unset($args['last_name']);
		}elseif($method == 'sendLocation'){
			$args['latitude'] = isset($args['latitude']) ? $args['latitude'] : $obj->latitude;
			$args['longitude'] = isset($args['longitude']) ? $args['longitude'] : $obj->longitude;
		}elseif($method == 'sendVenue'){
			$args['latitude'] = isset($args['latitude']) ? $args['latitude'] : $obj->latitude;
			$args['longitude'] = isset($args['longitude']) ? $args['longitude'] : $obj->longitude;
			$args['address'] = isset($args['address']) ? $args['address'] : $obj->address;
			$args['title'] = isset($args['title']) ? $args['title'] : $obj->title;
		}return $this->requset($method, $args, $level);
	}
	public function parse_args($method, $args = array()){
		if(!$this->parser)return $args;
		$method = strtolower($method);
		array_key_alias($args, 'user_id', 'user');
		array_key_alias($args, 'chat_id', 'chat', 'peer');
		array_key_alias($args, 'message_id', 'message', 'msg', 'msg_id');
		if(!isset($args['chat_id']))
			array_key_alias($args, 'inline_message_id', 'message_id');
		if($method == 'answercallbackquery')
			array_key_alias($args, 'callback_query_id', 'id');
		elseif($method == 'answerinlinequery')
			array_key_alias($args, 'inline_query_id', 'id');
		elseif(isset($args['id']))
			unset($args['id']);
		array_key_alias($args, 'show_alert', 'alert');
		array_key_alias($args, 'parse_mode', 'parse', 'mode');
		array_key_alias($args, 'reply_markup', 'markup');
		array_key_alias($args, 'reply_to_message_id', 'reply_to_message', 'reply_to_msg_id', 'reply_to_msg', 'reply_to', 'reply');
		array_key_alias($args, 'from_chat_id', 'from_chat');
		array_key_alias($args, 'phone_number', 'phone');
		if(isset($args['allowed_updates']) && (is_array($args['allowed_updates']) || is_object($args['allowed_updates'])))
			$args['allowed_updates'] = xncrypt::jsonencode($args['allowed_updates']);
		if(isset($args['reply_markup']) && is_string($args['reply_markup']) && $this->menu->exists($args['reply_markup']))
			$args['reply_markup'] = $this->menu->get($args['reply_markup']);
		if(isset($args['reply_markup']) && (is_array($args['reply_markup']) || is_object($args['reply_markup'])))
			$args['reply_markup'] = xncrypt::jsonencode($args['reply_markup']);
		switch($method){
			case 'getFile':
				array_key_alias($args, 'file_id', 'file');
			break;
			default:
				switch($method){
					case 'sendphoto': $argname = 'photo_id'; break;
					case 'sendaudio': $argname = 'audio_id'; break;
					case 'sendvideo': $argname = 'video_id'; break;
					case 'sendvoice': $argname = 'voice_id'; break;
					case 'sendsticker': $argname = 'sticker_id'; break;
					case 'senddocuement': $argname = 'document_id'; break;
					case 'sendvideonote': $argname = 'video_note_id'; break;
					default: break 2;
				}
				array_key_alias($args, 'file', $argname, 'file_id');
				if(isset($args['file'])){
					$file = $args['file'];
					unset($args['file']);
				}else break;
				if(is_string($file) && file_exists($file))
					$file = new CURLFile($file);
				$args[$argname] = $file;
		}
		$user = $this->getUser();
		if($user !== false){
			if(isset($args['chat_id']) && ($args['chat_id'] == 'chat' || $args['chat_id'] === ''))
				$args['chat_id'] = $this->getUser()->chat->id;
			elseif(isset($args['chat_id']) && $args['chat_id'] == 'user')
				$args['chat_id'] = $this->getUser()->from->id;
			if(isset($args['from_chat_id']) && ($args['from_chat_id'] == 'chat' || $args['from_chat_id'] === ''))
				$args['from_chat_id'] = $this->getUser()->chat->id;
			elseif(isset($args['from_chat_id']) && $args['from_chat_id'] == 'user')
				$args['from_chat_id'] = $this->getUser()->from->id;
			if(isset($args['user_id']) && $args['user_id'] == 'chat')
				$args['user_id'] = $this->getUser()->chat->id;
			elseif(isset($args['user_id']) && ($args['user_id'] == 'user' || $args['user_id'] === ''))
				$args['user_id'] = $this->getUser()->from->id;
		}$msg = $this->getMessage();
		if($msg !== false){
			if(isset($args['message_id']) && ($args['message_id'] == 'message' || $args['message_id'] === ''))
				$args['message_id'] = $msg;
			if(isset($args['reply_to_message_id']) && ($args['reply_to_message_id'] == 'message' || $args['reply_to_message_id'] === ''))
				$args['reply_to_message_id'] = $msg;
		}return $args;
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
			$enti = array_value($x->query("$path//div[@class='tgme_widget_message_text']"), 0);
			$entities = array();
			$last = 0;
			$pos = false;
			$line = 0;
			$entit = new DOMDocument;
			$entit->appendChild($entit->importNode($enti, true));
			$text = trim(html_entity_decode(strip_tags(str_replace('<br/>', "\n", $entit->saveXML()))));
			$tmp = new DOMXPath($entit);
			foreach($tmp->query("//code|i|b|a") as $num => $el) {
				$len = strlen($el->nodeValue);
				$pos = strpos(substr($enti->nodeValue, $last), $el->nodeValue) + $last;
				$last = $pos + $len;
				$entities[$num] = array("offset" => $pos, "length" => $len);
				if($el->tagName == 'a')$entities[$num]['url'] = $el->getAttribute("href");
				elseif($el->tagName == 'b')$entities[$num]['type'] = 'bold';
				elseif($el->tagName == 'i')$entities[$num]['type'] = 'italic';
				elseif($el->tagName == 'code')$entities[$num]['type'] = 'code';
				elseif($el->tagName == 'a')$entities[$num]['type'] = 'link';
			}
			if($entities == array())$entities = false;
			$date = strtotime(array_value(array_value($x->query("$path//a[@class='tgme_widget_message_date']"), 0)->getElementsByTagName('time'), 0)->getAttribute("datetime"));
			$views = $x->query("$path//span[@class='tgme_widget_message_views']");
			if(isset($views[0]))$views = $views[0]->nodeValue;
			else $views = false;
			$author = $x->query("$path//span[@class='tgme_widget_message_from_author']");
			if(isset($author[0]))$author = $author[0]->nodeValue;
			else $author = false;
			$via = $x->query("$path//a[@class='tgme_widget_message_via_bot']");
			if(isset($via[0]))$via = substr($via[0]->nodeValue, 1);
			else $via = false;
			$forward = array_value($x->query("$path//a[@class='tgme_widget_message_forwarded_from_name']"), 0);
			if($forward) {
				$forwardname = $forward->nodeValue;
				$forwarduser = $forward->getAttribute("href");
				$forwarduser = end(explode('/', $forwarduser));
				$forward = $forwardname ? array("title" => $forwardname, "username" => $forwarduser) : false;
			}
			else $forward = false;
			$replyid = $x->query("$path//a[@class='tgme_widget_message_reply']");
			if(isset($replyid[0])) {
				$replyid = $replyid[0]->getAttribute("href");
				$replyid = explode('/', $replyid);
				$replyid = end($replyid);
				$replyname = array_value($x->query("$path//a[@class='tgme_widget_message_reply']//span[@class='tgme_widget_message_author_name']"), 0)->nodeValue;
				$replytext = array_value($x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_text']"), 0)->nodeValue;
				$replymeta = array_value($x->query("$path//a[@class='tgme_widget_message_reply']//div[@class='tgme_widget_message_metatext']"), 0)->nodeValue;
				$replyparse = explode(' ', $replymeta);
				$replythumb = array_value($x->query("$path//a[@class='tgme_widget_message_reply']//i[@class='tgme_widget_message_reply_thumb']"), 0);
				if($replythumb)$replythumb = $replythumb->getAttribute('style');
				else $replythumb = false;
				preg_match('/url\(\'(.{1,})\'\)/', $replythumb, $pr);
				$replythumb = $pr[1];
				$reply = array("message_id" => $replyid, "title" => $replyname);
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
			$photo = array_value($x->query("$path//a[@class='tgme_widget_message_photo_wrap']"), 0);
			if($photo) {
				$photo = $photo->getAttribute('style');
				preg_match('/url\(\'(.{1,})\'\)/', $photo, $pr);
				$photo = array("photo" => $pr[1]);
			}
			else $photo = false;
			$voice = $x->query("$path//audio[@class='tgme_widget_message_voice']");
			if(isset($voice[0])) {
				$voice = $voice[0]->getAttribute("src");
				$voiceduration = array_value($x->query("$path//time[@class='tgme_widget_message_voice_duration']"), 0)->nodeValue;
				$voiceex = explode(':', $voiceduration);
				if(count($voiceex) == 3)$voiceduration = $voiceex[0] * 3600 + $voiceex[1] * 60 + $voiceex[2];
				else $voiceduration = $voiceex[0] * 60 + $voiceex[1];
				$voice = array("voice" => $voice, "duration" => $voiceduration);
			}
			else $voice = false;
			$sticker = $x->query("$path//div[@class='tgme_widget_message_sticker_wrap']");
			if(isset($sticker[0])) {
				$stickername = array_value($sticker[0]->getElementsByTagName("a"), 0);
				$sticker = array_value($stickername->getElementsByTagName('i'), 0)->getAttribute("style");
				preg_match('/url\(\'(.{1,})\'\)/', $sticker, $pr);
				$sticker = $pr[1];
				$stickername = $stickername->getAttribute("href");
				$stickername = explode('/', $stickername);
				$stickername = end($stickername);
				$sticker = array("sticker" => $sticker, "setname" => $stickername);
			}
			else $sticker = false;
			$document = $x->query("$path//div[@class='tgme_widget_message_document_title']");
			if(isset($document[0])) {
				$document = $document[0]->nodeValue;
				$documentsize = array_value($x->query("$path//div[@class='tgme_widget_message_document_extra']"), 0)->nodeValue;
				$document = array("title" => $document, "size" => $documentsize);
			}
			else $document = false;
			$video = $x->query("$path//a[@class='tgme_widget_message_video_player']");
			if(isset($video[0])) {
				$video = array_value($video[0]->getElementsByTagName("i"), 0)->getAttribute("style");
				preg_match('/url\(\'(.{1,})\'\)/', $video, $pr);
				$video = $pr[1];
				$videoduration = array_value($vide->getElementsByTagName("time"), 0)->nodeValue;
				$videoex = explode(':', $videoduration);
				if(count($videoex) == 3)$videoduration = $videoex[0] * 3600 + $videoex[1] * 60 + $videoex[2];
				else $videoduration = $videoex[0] * 60 + $videoex[1];
				$video = array("video" => $video, "duration" => $videoduration);
			}
			else $video = false;
			if($text && ($document || $sticker || $photo || $voice || $video)) {
				$caption = $text;
				$text = false;
			}
			$r = array("username" => $chat, "message_id" => $message);
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
			return (array)$r;
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
		$description = array_value($x->query("$path//div[@class='tgme_page_description']"), 0);
		$members = explode(' ', array_value($x->query("$path//div[@class='tgme_page_extra']"), 0)->nodeValue);
		unset($members[count($members)- 1]);
		$members = (int)implode('', $members);
		$r = array("title" => $title);
		if($photo)$r['photo'] = $photo;
		if(isset($description->nodeValue))$r['description'] = $description->nodeValue;
		if($members > 0)$r['members'] = $members;
		return (array)$r;
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
		$title = array_value($title[0]->getElementsByTagName("strong"), 1)->nodeValue;
		return (object)array("setname" => $name, "title" => $title);
	}
	public static function channelCreatedDate($channel){
		return self::getMessage($channel, 1)->date;
	}
	public $logged = false,$hash = "",$creation_hash = "",$token = "",$number;
	public function __construct($number){
		$number = str_replace(array("+","(",")"," "),'',$number);
		$this->number = $number;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/auth/send_password');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('phone' => $number));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER,array(
			'Origin: https://my.telegram.org',
			'Accept-Encoding: gzip, deflate, br',
			'Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4',
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
			'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
			'Accept: application/json, text/javascript, */*; q=0.01',
			'Referer: https://my.telegram.org/auth',
			'X-Requested-With: XMLHttpRequest',
			'Connection: keep-alive',
			'Dnt: 1'));
		$result = curl_exec($ch);
		curl_close($ch);
		if(!$result)
			new XNError("MyTelegram login", "can not Connect to https://my.telegram.org", XNError::NETWORK);
		$res = xncrypt::jsondecode($result,true);
		if(!isset($res['random_hash'])) 
			new XNError("MyTelegram login", $result, XNError::NOTIC);
		return $this->hash = $res['random_hash'];
	}
	public function complete_login($password){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/auth/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('phone' => $this->number, 'random_hash' => $this->hash, 'password' => $password)));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
		));
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
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
		));
		$result = curl_exec($ch);
		curl_close($ch);
		$title = strpos($result,'<title>') + 7;
		$title = substr($result,$title,strpos($result,'</title>',$title) - $title);
		switch($title){
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
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
		));
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
		return array('api_id'=>(int)$api_id, 'api_hash'=>$api_hash);
	}
	public function create_app($title,$shortname,$url,$platform,$desc){
		if(!$this->logged)
			new XNError("MyTelegram CompleteLogin", 'Not logged in!', XNError::NOTIC);
		if($this->has_app())
			new XNError("MyTelegram CompleteLogin", 'The app was already created!', XNError::NOTIC);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps/create');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'hash'=>$this->creation_hash,
			'app_title'=>$title,
			'app_shortname'=>$shortname,
			'app_url'=>$url,
			'app_platform'=>$platform,
			'app_desc'=>$desc
		));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
		));
		$result = curl_exec($ch);
		curl_close($ch);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://my.telegram.org/apps');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
		));
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
		return array('api_id'=>(int)$api_id, 'api_hash'=>$api_hash);
	}
}
function var_get($var){
	$c = array_value(file(thefile()), theline() - 1);
	if(preg_match('/var_get[\n ]*\([@\n array_value(]*\$([a-zA-Z_0-9]+), \n )*((\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[array(^\)]+\])|(\([^\)]*\)))*\)/', $c, $s)) {
		$s[0] = substr($s[0], 9, -1);
		preg_match_all('/(\-\>[a-zA-Z0-9_]+)|(\:\:[a-zA-Z0-9_]+)|(\[array(^\)]+\])|(\([^\)]*\))/', $s[0], $j);
		$u = array();
		foreach($j[1] as $e)
			if($e)$u[] = array("caller" => '->', "type" => "object_method", "value" => substr($e, 2));
		foreach($j[2] as $e)
			if($e)$u[] = array("caller" => "::", "type" => "static_method", "value" => substr($e, 2));
		foreach($j[3] as $e)
			if($e)$u[] = array("caller" => "[]", "type" => "array_index", "value" => substr($e, 1, -1));
		foreach($j[4] as $e)
			if($e)$u[] = array("caller" => "()", "type" => "closure_call", "value" => substr($e, 1, -1));
		if(isset($s[1]))return array("type" => "variable", "short_type" => "var", "name" => $s[1], "full" => $s[0], "calls" => $u);
	}
	elseif(preg_match('/var_get[\n ]*\([@\n array_value(]*([a-zA-Z_0-9]+), \n )*\)/', $c, $s)) {
		return array("type" => "define", "short_type" => "def", "name" => $s[1]);
	}
	elseif(preg_match('/var_get[\n ]*\([@\n array_value(]*([a-zA-Z_0-9]+), \n )*\(/', $c, $s)) {
		if(preg_match('/^[fF][uU][nN][cC][tT][iI][oO][nN]$/', $s[1]))$s[1] = "function";
		return array("type" => "function", "short_type" => "closure", "name" => $s[1]);
	}
	new XNError("var_get", "unsupported Type", XNError::TYPE, XNError::TTHROW);
}
function spl_object_count(){
	return spl_object_id(new StdClass) - 1;
}
function fvalid($file){
	$f = fopen($file, 'r');
	if(!$f)return false;
	fclose($f);
	return true;
}
function fcreate($file){
	$f = fopen($file, 'w');
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
function curl_get($file){
	$ch = @curl_init($file);
	if($ch) {
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$r = curl_exec($ch);
		curl_close($ch);
		if($size === null || $size === true || $size === false || $size == -1)
			return substr($r, $offset);
		return substr($r, $offset, $size < 0 ? $size + 1 : $size);
	}return $ch;
}
function fget($file, $size = -1, $offset = 0,$x = false, $y = false){
	if($size === null || $size === true || $size === false)
		$size = filesize($file);
	if($y)$f = fopen($file, 'rb', $x, stream_context_create($y));
	else $f = fopen($file, 'rb', $x);
	if(!$f) {
		new XNError("fget", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	return stream_get_contents($f, $size, $offset);
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
function fsempty($file){
	if(!is_stream($file))
		return false;
	if(($g = fgetc($file)) === false)
		return true;
	fseek($file, -1, SEEK_CUR);
	return false;
}
function array_array_merge(){
	$arrays = func_get_args();
	$arr = array();
	foreach($arrays as $key => $array){
		if(!is_array($array))
			$arr[$key] = $array;
		else
			foreach($array as $k => $v){
				if(isset($arr[$k]))
					$arr[$k] = array_merge($arr[$k], $v);
				else
					$arr[$k] = $v;
			}
	}
	return $arr;
}
function freset($file){
	$f = fopen($file, 'w');
	if(!$f)return false;
	ftruncate($f, 0);
	return fclose($f);
}
function file_size($file){
	$f = fopen($file, 'r');
	if(!$f)return false;
	fseek($f, 0, SEEK_END);
	$s = ftell($f);
	fclose($f);
	return $s;
}
function filename($file){
	return substr($file, -1) == DIRECTORY_SEPARATOR;
}
function fileformat($file){
	$format = strrpos($file, '.');
	if($format === false)return false;
	return substr($file, $format + 1);
}
function fcmp($file1, $file2){
	$stream1 = fopen($file1, 'r');
	if(!$stream1)return false;
	$stream2 = fopen($file2, 'r');
	if(!$stream2)return false;
	while(feof($stream1) || feof($stream2))
		if(fread($stream1, 1048576) !== fread($stream2, 1048576))
			return false;
	return true;
}
function filewait($file, $limit = 1){
	$f = fopen($file, 'r');
	if(!$f)return false;
	xnstring::wait($f, $limit);
	fclose($f);
	return $s;
}
function get_resource_id($resource){
	return array_search($resource, get_resources());
}
function dirdel($dir){
	$s = dirscan($dir);
	foreach($s as $f) {
		if(is_dir($dir .DIRECTORY_SEPARATOR. $f))dirdel($dir .DIRECTORY_SEPARATOR. $f);
		else unlink($dir .DIRECTORY_SEPARATOR. $f);
	}
	return rmdir($dir);
}
function dirscan($dir){
	$s = scandir($dir);
	if(isset($s[0])){
		if($s[0] == '.' || $s[0] == '..')unset($s[0]);
		if(isset($s[1]))
			if($s[1] == '.' || $s[1] == '..')unset($s[1]);
	}
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
	$r = array();
	foreach($s as $file) {
		if(strpos($file, $search))$r[] = $dir .DIRECTORY_SEPARATOR. $file;
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir')$r = array_merge($r, dirsearch($dir .DIRECTORY_SEPARATOR. $file, $search));
	}
	return $r;
}
function preg_dirsearch($dir, $search){
	$s = dirscan($dir);
	$r = array();
	foreach($s as $file) {
		if(preg_match($search, $file))$r[] = $dir .DIRECTORY_SEPARATOR. $file;
		if(filetype($dir .DIRECTORY_SEPARATOR. $file) == 'dir')$r = array_merge($r, dirsearch($dir .DIRECTORY_SEPARATOR. $file, $search));
	}
	return $r;
}
function is_url($file){
	return filter_var($file, FILTER_VALIDATE_URL) && !file_exists($file) && fvalid($file);
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
	$f = fopen($file, 'r');
	if(!$f) {
		new XNError("fget progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while(!feof($f)) {
		$r.= fread($f, $al);
		if($func($r)) {
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
	return (object)array("size" => $size, "folder" => $foldercount, "file" => $filecount);
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
	$f = fopen($file, 'w');
	if(!$f) {
		new XNError("fput progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while($content) {
		$r.= $th = substr($content, 0, $al);
		fwrite($f, $th);
		$content = substr($content, $al);
		if($func($r)) {
			fclose($f);
			return $r;
		}
	}
	fclose($f);
	return $r;
}
function faddprogress($file, $content, $func, $al){
	$al = $al > 0 ? $al : 1;
	$f = fopen($file, 'a');
	if(!$f) {
		new XNError("fadd progress", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$r = '';
	while($content) {
		$r.= $th = substr($content, 0, $al);
		fwrite($f, $th);
		$content = substr($content, $al);
		if($func($r)) {
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
	$r = array();
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
			$eadervalue = array();
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
	$from = fopen($from, 'r');
	$to = fopen($to, 'w');
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
function get_uploaded_file($file){
	$random = rand(0, 999999999). rand(0, 999999999);
	if(!move_uploaded_file($file, "xn$random.log"))return false;
	$get = fget("xn$random.log");
	unlink("xn$random.log");
	return $get;
}
function rand_pop(&$range){
	$key = array_rand($range);
	$value = $range[$key];
	unset($range[$key]);
	return $value;
}
function set_json_app($json = "application/json"){
	header("Content-Type: $json");
}
function set_text_app($type = "plan"){
	header("Content-Type: " . (strpos($type, 'text/') === 0 ? $type : "text/$type"));
}
function set_html_app(){
	header("Content-Type: text/html");
}
function set_image_app($type = "png"){
	header("Content-Type: " . (strpos($type, 'image/') === 0 ? $type : "image/$type"));
}
function set_audio_app($type = "mp3"){
	header("Content-Type: " . (strpos($type, 'audio/') === 0 ? $type : "audio/$type"));
}
function set_pdf_app(){
	header("Content-Type: application/pdf");
}
function set_video_app($type = "mp4"){
	header("Content-Type: " . (strpos($type, 'video/') === 0 ? $type : "video/$type"));
}
function set_http_code($code){
	header(":", false, $code);
}
function redirect($loc){
	header("Location: $loc");
}
function redirect_referesh(){
	header("Location: " . getenv('REQUEST_URI'));	
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
function dateopt($date = 1){
	if($date == 2)return -19603819800; // jalaly
	if($date == 3)return -18262450800; // ghamary
	if($date == 4)return -62167219200; // time 0000-01-01T00:00:00+00:00am
	return 0; // $data == 1            // miladi
}
function timeopt($time){
	$tmp = new DateTimeZone($time);
	$tmp = new DateTime(null, $tmp);
	return $tmp->getOffset();
}
function datetimeopt($time, $date = 1){
	return timeopt($time) + dateopt($date);
}
function timeformater($time, $join = ' ', $offset = 1){
	if($time < 60 * $offset)return floor($time). $join . "s";
	if($time < 3600 * $offset)return floor($time / 60). $join . "m";
	if($time < 86400 * $offset)return floor($time / 3600). $join . "h";
	if($time < 2592000 * $offset)return floor($time / 86400). $join . "d";
	if($time < 186645600 * $offset)return floor($time / 2592000). $join . "n";
	return floor($time / 186645600). $join . "y";
}
function msleep($seconds, $microseconds){
	$st = explode(' ', microtime(), 2);
	$st[1] = (int)substr($st, 2) + $microseconds;
	$st[0] = (int)$st[0] + $seconds;
	do{
		$mc = explode(' ', microtime(), 2);
		$mc[1] = (int)substr($mc, 2);
	}while($mc[0] < $st[0] && $mc[1] < $st[1]);
}
function is_serialized($data){
	return (@unserialize($data) !== false || $data == 'b:0;');
}
function chars_random($x){
	$x = str_split($x);
	return $x[array_rand($x)];
}
function array_clone($array){
	return (array)(object)$array;
}
function is_floor($x){
	return floor($x) == (float)$x;
}
function is_big_for_int($x){
	return floor($x) != (int)$x;
}
function screenshot($url, $width = 1280, $fullpage = false, $mobile = false, $format = "PNG"){
	return file_get_contents("https://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=" . urlencode($url). "&width=" . $width . "&fullpaghttps://thumbnail.ws/get/thumbnail/?apikey=ab45a17344aa033247137cf2d457fc39ee4e7e16a464&url=" . urlencode($url). "&width=" . $width . "&fullpage=" . ($fullpage ? "true" : "false"). "&moblie=" . ($mobile ? "true" : "false"). "&format=" . $format);
}
function windows_width2height($width){
	return $width * 1.7786458333333333;
}
function windows_height2height($width){
	return $width * 0.5622254758418741;
}
function ASCII_CHARS(){
	return array(
		"\x0", "\x1", "\x2", "\x3", "\x4", "\x5", "\x6", "\x7", "\x8", "\x9", "\xa", "\xb", "\xc", "\xd", "\xe", "\xf",
		"\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17","\x18","\x19","\x1a","\x1b","\x1c","\x1d","\x1e","\x1f",
		"\x20","\x21","\x22","\x23","\x24","\x25","\x26","\x27","\x28","\x29","\x2a","\x2b","\x2c","\x2d","\x2e","\x2f",
		"\x30","\x31","\x32","\x33","\x34","\x35","\x36","\x37","\x38","\x39","\x3a","\x3b","\x3c","\x3d","\x3e","\x3f",
		"\x40","\x41","\x42","\x43","\x44","\x45","\x46","\x47","\x48","\x49","\x4a","\x4b","\x4c","\x4d","\x4e","\x4f",
		"\x50","\x51","\x52","\x53","\x54","\x55","\x56","\x57","\x58","\x59","\x5a","\x5b","\x5c","\x5d","\x5e","\x5f",
		"\x60","\x61","\x62","\x63","\x64","\x65","\x66","\x67","\x68","\x69","\x6a","\x6b","\x6c","\x6d","\x6e","\x6f",
		"\x70","\x71","\x72","\x73","\x74","\x75","\x76","\x77","\x78","\x79","\x7a","\x7b","\x7c","\x7d","\x7e","\x7f",
		"\x80","\x81","\x82","\x83","\x84","\x85","\x86","\x87","\x88","\x89","\x8a","\x8b","\x8c","\x8d","\x8e","\x8f",
		"\x90","\x91","\x92","\x93","\x94","\x95","\x96","\x97","\x98","\x99","\x9a","\x9b","\x9c","\x9d","\x9e","\x9f",
		"\xa0","\xa1","\xa2","\xa3","\xa4","\xa5","\xa6","\xa7","\xa8","\xa9","\xaa","\xab","\xac","\xad","\xae","\xaf",
		"\xb0","\xb1","\xb2","\xb3","\xb4","\xb5","\xb6","\xb7","\xb8","\xb9","\xba","\xbb","\xbc","\xbd","\xbe","\xbf",
		"\xc0","\xc1","\xc2","\xc3","\xc4","\xc5","\xc6","\xc7","\xc8","\xc9","\xca","\xcb","\xcc","\xcd","\xce","\xcf",
		"\xd0","\xd1","\xd2","\xd3","\xd4","\xd5","\xd6","\xd7","\xd8","\xd9","\xda","\xdb","\xdc","\xdd","\xde","\xdf",
		"\xe0","\xe1","\xe2","\xe3","\xe4","\xe5","\xe6","\xe7","\xe8","\xe9","\xea","\xeb","\xec","\xed","\xee","\xef",
		"\xf0","\xf1","\xf2","\xf3","\xf4","\xf5","\xf6","\xf7","\xf8","\xf9","\xfa","\xfb","\xfc","\xfd","\xfe","\xff"
	);
}
function mustbe($input, $type, $return = null){
	$types = explode('|', $type);
	foreach($types as $type){
		$type = explode(':', $type, 2);
		switch(strtolower($type[0])){
			case 'bool':
			case 'boolean':
				if(($type === true || $type === false) && (!isset($type[1]) ||
					(($type[1] = strtolower($type[1])) == 'true' && $input === true) ||
					 ($type[1] == 'false' && $input === false)))
					return true;
			break;
			case 'callable':
				if(is_callable($input))
					return true;	
			break;
			case 'file':
				if(is_string($input) && $input !== '' && is_file($input))
					return true;
			break;
			case 'dir':
				if(is_string($input) && $input !== '' && is_dir($input))
					return true;
			break;
			case 'function':
				if(is_string($input) && $input !== '' && function_exists($input))
					return true;
			break;
			case 'class':
				if(is_string($input) && $input !== '' && class_exists($input))
					return true;
			break;
			case 'interface':
				if(is_string($input) && $input !== '' && interface_exists($input))
					return true;
			break;
			case 'trait':
				if(is_string($input) && $input !== '' && trait_exists($input))
					return true;
			break;
			case 'json':
				if(is_json($input))
					return true;
			break;
			case 'serialized':
				if(is_serialized($input))
					return true;
			break;
			case 'regex':
				if(is_regex($input))
					return true;
			break;
			case 'empty':
				if(empty($input))
					return true;
			break;
			case 'str':
			case 'string':
				if(is_string($input))
					return true;
			break;
			case 'int':
			case 'integer':
				if(is_int($input)   && (!isset($type[1]) || $input == $type[1]))
					return true;
			break;
			case 'float':
			case 'double':
				if(is_float($input) && (!isset($type[1]) || $input == $type[1]))
					return true;
			break;
			case 'numeric':
				if(is_numeric($input) && (!isset($type[1]) || $input == $type[1]))
					return $input;
			break;
			case 'null':
			case 'nill':
			case 'nul':
			case 'nil':
				if($input === null)
					return true;
			break;
			case 'closure':
				if(is_callable($input) && $input instanceof Closure)
					return true;
			break;
			case 'array':
				if(is_array($input))
					return true;
			break;
			case 'resource':
				if(is_resource($input) && (!isset($type[1]) || strtolower(get_resource_type($input)) == strtolower($type[1])))
					return true;
			break;
			case 'object':
				if(is_object($input)   && (!isset($type[1]) || strtolower(get_class($input)) == strtolower($type[1])))
					return true;
			break;
			case 'method':
				if(isset($type[1]) &&
					((is_object($input) && method_exists($input, $type[1])) ||
					 (is_string($input) && class_exists($input) && in_array($type[1], get_class_methods($input)))))
					return true;
			break;
			case 'iterator':
			case 'iterable':
			case 'traversable':
				if(is_iterable($input))
					return true;
		}
	}
	if($return !== true){
		$trace = array_value(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2), 1);
		$now = gettype($input);
		switch($now){
			case 'object':
				$now .= ':' . get_class($input);
			break;
			case 'resource':
				$now .= ':' . get_resource_type($input);
		}
		throw new TypeError('Argument ' . (array_search($input, $trace['args'], true) + 1) . ' passed to ' . $trace['function'] .
			'() must be an ' . implode(' or ', $types) . ', ' . $now . ' given, called in ' . $trace['file'] . ' on line ' . $trace['line']);
	}
	return false;
}
function canbe($input, $type, $return = null){
	$types = explode('|', $type);
	foreach($types as $type){
		$type = explode(':', $type, 2);
		switch(strtolower($type[0])){
			case 'bool':
			case 'boolean':
				if(!isset($type[1]) ||
					(($type[1] = strtolower($type[1])) == 'true' && $input === true) ||
					 ($type[1] == 'false' && $input === false))
					return $input == true;
			break;
			case 'callable':
				if(is_callable($input))
					return $input;
			break;
			case 'file':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && is_file($input = (string)$input))
					return $input;
			break;
			case 'dir':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && is_dir($input = (string)$input))
					return $input;
			break;
			case 'function':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && function_exists($input = (string)$input))
					return $input;
			break;
			case 'class':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && class_exists($input = (string)$input))
					return $input;
			break;
			case 'interface':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && interface_exists($input = (string)$input))
					return $input;
			break;
			case 'trait':
				if((is_string($input) || is_int($input) || is_float($input) || is_bool($input)) && $input != '' && trait_exists($input = (string)$input))
					return $input;
			break;
			case 'json':
				if(is_json($input))
					return true;
			break;
			case 'serialized':
				if(is_serialized($input))
					return true;
			break;
			case 'regex':
				if(is_regex($input))
					return true;
			break;
			case 'empty':
				if(empty($input))
					return true;
			break;
			case 'str':
			case 'string':
				if(is_string($input) || is_int($input) || is_float($input) || is_bool($input))
					return (string)$input;
			break;
			case 'int':
			case 'integer':
				if(is_numeric($input) && (!isset($type[1]) || $input == $type[1]))
					return (int)$input;
			break;
			case 'float':
			case 'double':
				if(is_numeric($input) && (!isset($type[1]) || $input == $type[1]))
					return (float)$input;
			break;
			case 'number':
			case 'numeric':
				if(is_numeric($input) && (!isset($type[1]) || $input == $type[1]))
					return $input;
			break;
			case 'null':
			case 'nill':
			case 'nul':
			case 'nil':
				if($input === null)
					return null;
			break;
			case 'closure':
				if(is_callable($input) && $input instanceof Closure)
					return $input;
			break;
			case 'array':
				if(is_array($input))
					return $input;
			break;
			case 'resource':
				if(is_resource($input) && (!isset($type[1]) || strtolower(get_resource_type($input)) == strtolower($type[1])))
					return $input;
			break;
			case 'object':
				if(is_object($input)   && (!isset($type[1]) || strtolower(get_class($input)) == strtolower($type[1])))
					return $input;
			case 'method':
				if(isset($type[1]) &&
					((is_object($input) && method_exists($input, $type[1])) ||
					 (is_string($input) && class_exists($input) && in_array($type[1], get_class_methods($input)))))
					return $input;
			break;
			case 'iterator':
			case 'iterable':
			case 'traversable':
				if(is_iterable($input))
					return true;
		}
	}
	if($return !== true){
		$trace = array_value(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2), 1);
		$now = gettype($input);
		switch($now){
			case 'object':
				$now .= ':' . get_class($input);
			break;
			case 'resource':
				$now .= ':' . get_resource_type($input);
		}
		throw new TypeError('Argument ' . (array_search($input, $trace['args'], true) + 1) . ' passed to ' . $trace['function'] .
			'() must be an ' . implode(' or ', $types) . ', ' . $now . ' given, called in ' . $trace['file'] . ' on line ' . $trace['line']);
	}
	return false;
}
function get_callable_outer($callable){
	if(is_string($callable) && strpos($callable, '::') !== false)
		$callable = explode('::', $callable);
	if(is_array($callable)){
		if(!isset($callable[1]) || !isset($callable[0]) || !class_exists($callable[0]) || !method_exists($callable[0], $callable[1]))return false;
		$reflection = new ReflectionMethod($class = $callable[0], $callable = $callable[1]);
	}elseif(!is_string($callable))
		return unce($callable);
	else{
		if(!function_exists($callable))return false;
		$reflection = new ReflectionFunction($callable);
	}
	$file = $reflection->getFileName();
	if($file === false){
		if(isset($class))
			return "function(){
				return call_user_method_array(\$this, \"$callable\", func_get_args());
			}";
		return "function(){
			return call_user_func_array(\"$callable\", func_get_args());
		}";
	}
	$code = implode('', array_slice(file($file), $start = $reflection->getStartLine() - 1, $reflection->getEndLine() - $start));
	preg_match('/function[ \n\r\t]+' . $callable . '[ \n\r\t]*\(/i', $code, $match);
	$code = substr($code, strpos($code, $match[0]));
	$i = 1;
	$q = false;
	for($c = strpos($code, '{', 10) + 1;$i !== 0 && isset($code[$c]);++$c){
		if($q == 1){
			if($code[$c] == '\\')++$c;
			elseif($code[$c] == '"')$q = false;
			continue;
		}if($q == 2){
			if($code[$c] == '\\')++$c;
			elseif($code[$c] == "'")$q = false;
			continue;
		}if($q == 3){
			if($code[$c] == '\\')++$c;
			elseif($code[$c] == '`')$q = false;
			continue;
		}
		if($code[$c] == '"')$q = 1;
		elseif($code[$c] == "'")$q = 2;
		elseif($code[$c] == '`')$q = 3;
		elseif($code[$c] == '{')++$i;
		elseif($code[$c] == '}')--$i;
	}
	if($i == 1)
		return false;
	return substr($code, 0, $c);
}
function get_callable_inner($callable){
	$outer = get_callable_outer($callable);
	return trim(substr($outer, strpos($outer, '{') + 1, -1));
}
function closure_of_callable($callable){
	if(!is_string($callable))
		return eval('return ' . unce($callable) . ';');
	$code = get_callable_outer($callable);
	$code = substr_replace($code, '', strpos($code, $callable), strlen($callable));
	return eval("return $code;");
}
function get_callable_args($callable){
	if(is_string($callable) && strpos($callable, '::') !== false)
		$callable = explode('::', $callable);
	if(!($callable instanceof ReflectionFunction))
		$callable = new ReflectionFunction($callable);
	$pars = $callable->getParameters();
	$p = array();
	foreach($pars as $c => $par) {
		$parr = (array)$par;
		$p[$c] = array("name" => $parr['name']);
		if(method_exists($par, 'isDefaultValueAvailable') && $par->isDefaultValueAvailable())$p[$c]["default"] = $par->getDefaultValue();
		if(method_exists($par, 'hasType') && $par->hasType())$p[$c]["type"] = $par->getType()->__toString();
		$p[$c]["optional"] = $par->isOptional();
		$p[$c]["variadic"] = method_exists($par, 'isVariadic') && $par->isVariadic();
		$p[$c]["passed"] = $par->isPassedByReference();
	}
	return $p;
}
function get_callable_arg($callable, $arg){
	if(is_string($callable) && strpos($callable, '::') !== false)
		$callable = explode('::', $callable);
	if(!($callable instanceof ReflectionFunction))
		$callable = new ReflectionFunction($callable);
	$par = $callable->getParameters();
	if(!isset($par[$arg]))return false;
	$par = $par[$arg];
	$p = array();
	$parr = (array)$par;
	$p = array("name" => $parr['name']);
	if(method_exists($par, 'isDefaultValueAvailable') && $par->isDefaultValueAvailable())$p["default"] = $par->getDefaultValue();
	if(method_exists($par, 'hasType') && $par->hasType())$p["type"] = $par->getType()->__toString();
	$p["optional"] = $par->isOptional();
	$p["variadic"] = method_exists($par, 'isVariadic') && $par->isVariadic();
	$p["passed"] = $par->isPassedByReference();
	return $p;
}
function call_class_constructor($classname){
	if(is_object($classname))
		$classname = get_class($classname);
	$params = func_get_args();
	unset($params[0]);
	$args = $params === array() ? '' : '$params[' . implode('],$params[', array_keys($params)) . ']';
	eval('$object = new ' . $classname . '(' . $args . ');');
	return $object;
}
function call_class_constructor_array($classname, $params = array()){
	if(is_object($classname))
		$classname = get_class($classname);
	$args = '$params[' . implode('],$params[', array_keys($params)) . ']';
	eval('$object = new ' . $classname . '(' . $args . ');');
	return $object;
}
function chrget($chr){
	$chr%= 256;
	return $chr < 0 ? $chr + 256 : $chr;
}
define("SET_BYTES_RIGHT",1);
define("SET_BYTES_LEFT",2);
function set_bytes($data,$count,$by = "\0",$type = 2){
	$l = strlen($data);
	if($l % $count == 0)return $data;
	if($type == 1)
		return $data.str_repeat($by,$count - $l % $count);
	else
		return str_repeat($by,$count - $l % $count).$data;
}
function unce($data){
	switch(gettype($data)) {
	case 'NULL':
		return 'NULL';
		break;
	case 'boolean':
		if($data)return 'true';
		return 'false';
		break;
	case 'string':
		return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $data). '"';
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
			$pars = array();
			foreach($pare as $k => $p) {
				$pars[$k] = ' *';
				if(method_exists($p, 'hasType') && $p->hasType())$pars[$k].= $p->getType()->__toString(). ' *';
				if(method_exists($p, 'isVariadic') && $p->isVariadic())$pars[$k].= '\.\.\. *';
				$pars[$k].= '\&{0,1} *\$' . $p->getName(). ' *';
				if(method_exists($p, 'isDefaultValueAvailable') && $p->isDefaultValueAvailable())$pars[$k].= '= *' . preg_unce($p->getDefaultValue()). ' *';
			}
			$pars = implode(',', $pars);
			$sts = $r->getStaticVariables();
			$stc = array();
			foreach($sts as $k => $v)$stc[] = " *\&{0,1} *\\$$k *";
			if($stc === array())$stc = '';
			else $stc = ' *use\(' . implode(',', $stc). '\)';
			$typa = '';
			if(method_exists($r, 'hasReturnType') && $r->hasReturnType())$typa = " *: *$type";
			$name = $r->getName();
			$name = $name[0] == '{' ? '' : $name;
			$file = file($r->getFileName());
			if($file === false)
				return false;
			$file = implode('', array_slice($file, $start = $r->getStartLine() - 1, $r->getEndLine() - $start));
			$m = preg_match("/function *$name\($pars\)$stc$typa *\{/", $file, $pa);
			if(!$m)
				return false;
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
	switch(gettype($data)) {
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
			$pars = array();
			foreach($pare as $k => $p) {
				$pars[$k] = ' *';
				if(method_exists($p, 'hasType') && $p->hasType())$pars[$k].= $p->getType()->__toString(). ' *';
				if(method_exists($p, 'isVariadic') && $p->isVariadic())$pars[$k].= '\.\.\. *';
				$pars[$k].= '\&{0,1} *\$' . $p->getName(). ' *';
				if(method_exists($p, 'isDefaultValueAvailable') && $p->isDefaultValueAvailable())$pars[$k].= '= *' . preg_unce($p->getDefaultValue()). ' *';
			}
			$pars = implode(',', $pars);
			$sts = $r->getStaticVariables();
			$stc = array();
			foreach($sts as $k => $v)$stc[] = " *\&{0,1} *\\$$k *";
			if($stc === array())$stc = '';
			else $stc = ' *use\(' . implode(',', $stc). '\)';
			$typa = '';
			if($r->hasReturnType())$typa = " *: *$type";
			$name = $r->getName();
			$name = $name[0] == '{' ? '' : $name;
			$file = file($r->getFileName());
			$file = implode('', array_slice($file, $start = $r->getStartLine() - 1, $r->getEndLine() - $start));
			$m = preg_match("/function *$name\($pars\)$stc$typa *\{/", $file, $pa);
			if(!$m)
				return false;
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
			$file = str_replace(array('\\', '/', '[', ']', '{', '}', '(', ')', '.', '$', '^', ',', '?', '<', '>', '+', '*', '&', '|', '!', '-', '#'),
				array('\\\\', '\/', '\[', '\]', '\{', '\}', '\(', '\)', '\.', '\$', '\^', '\,', '\?', '\<', '\>', '\+', '\*', '\&', '\|', '\!', '\-', '\#'), $file);
			return "function *$name\($pars\)$stc$typa *\{ *$file *\}";
		}
	}
}
function roman2number($str){
	$number = 0;
	$values = array(
		'M' => 1000,
		'D' => 500,
		'C' => 100,
		'L' => 50,
		'X' => 10,
		'V' => 5,
		'I' => 1
	);
	$str = strtr($str, array(
		'CM' => 'DCCCC',
		'CD' => 'CCCC',
		'XC' => 'LXXXX',
		'XL' => 'XXXX',
		'IX' => 'VIIII',
		'IV' => 'IIII'
	));
	foreach($values as $r => $n)
		$number += $n * substr_count($str, $r);
	return $number;
}
function number2roman($number){
	if($number > 4999 || $number < 0)
		return false;
	$str = '';
	$values = array(
		'M' => 1000,
		'D' => 500,
		'C' => 100,
		'L' => 50,
		'X' => 10,
		'V' => 5,
		'I' => 1
	);
	foreach($values as $r => $n) {
		$str .= str_repeat($r, floor($number / $n));
		$number = $number % $n;
	}
	return strtr($str, array(
		'DCCCC' => 'CM',
		'CCCC' => 'CD',
		'LXXXX' => 'XC',
		'XXXX' => 'XL',
		'VIIII' => 'IX',
		'IIII' => 'IV'
	));
}
class XNString {
	public static function rtl($str, $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, $shift, $l - 1) . substr($str, 0, $shift);
	}
	public static function rtr($str, $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, $l - $shift, $l - 1) . substr($str, 0, $l - $shift);
	}
	public static function usedchars($str){
		return array_unique(str_split($str));
	}
	public static function maxchar(){
		$chars = func_get_args();
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = - 1;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))> $l)$l = $h;
		return $l;
	}
	public static function minchar(){
		$chars = func_get_args();
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = 256;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))< $l)$l = $h;
		return $l;
	}
	public static function usefulldict($string){
		return array_keys(xncrypt::huffmandict($string));
	}
	public static function range(){
		$chars = func_get_args();
		return range(call_user_func_array(array('XNString', 'minchar'), $chars),call_user_func_array(array('XNString', 'maxchar'), $chars));
	}
	public static function toString($str){
		switch(gettype($str)) {
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
			case "object":
				return unce($str);
		}
		new XNError("XNString", "unsupported Type", XNError::TYPE, XNError::TTHROW);
	}
	public static function toregex($str){
		return str_replace("\Q\E", '', "\Q" . str_replace('\E', '\E\\\E\Q', $str). "\E");
	}
	const NUMBER_RANGE = '0123456789';
	const FLOAT_RANGE = '0123456789.';
	const ROMAN_RANGE = 'MDCLXVI';
	const ALPHBA_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const LOWER_RANGE = 'abcdefghijklmnopqrstuvwxyz';
	const UPPER_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const FA_ALPHBA_RANGE = 'ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی';
	const AR_ALPHBA_RANGE = 'ابتثجحخدذرزسشصضطظعغفقکلمنوهی';
	const SPACE_RANGE = "\n\r\t ";
	const NUL_BYTE = "\0";
	const ASCII_RANGE = "\0\1\2\3\4\5\6\7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f" . 
		"\x20\x21\x22\x23\x24\x25\x26\x27\x28\x29\x2a\x2b\x2c\x2d\x2e\x2f\x30\x31\x32\x33\x34\x35\x36\x37\x38\x39\x3a\x3b\x3c\x3d\x3e\x3f" .
		"\x40\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4a\x4b\x4c\x4d\x4e\x4f\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5a\x5b\x5c\x5d\x5e\x5f" .
		"\x60\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6a\x6b\x6c\x6d\x6e\x6f\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7a\x7b\x7c\x7d\x7e\x7f" .
		"\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8a\x8b\x8c\x8d\x8e\x8f\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9a\x9b\x9c\x9d\x9e\x9f" .
		"\xa0\xa1\xa2\xa3\xa4\xa5\xa6\xa7\xa8\xa9\xaa\xab\xac\xad\xae\xaf\xb0\xb1\xb2\xb3\xb4\xb5\xb6\xb7\xb8\xb9\xba\xbb\xbc\xbd\xbe\xbf" .
		"\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf" .
		"\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf7\xf8\xf9\xfa\xfb\xfc\xfd\xfe\xff";
	const BITFLAGS_RANGE = "\0\1\2\4\x8\x10\x20\x40\x80";
	const CONTROL_RANGE = "\0\1\2\3\4\5\6\7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x7f";
	const BLANK_RANGE = " \t";
	const BASE2_RANGE = '01';
	const HEXA_RANGE = '0123456789abcdefABCDEF';
	const HEX_RANGE  = '0123456789abcdef';
	const HEXU_RANGE = '0123456789ABCDEF';
	const BASE4_RANGE = '0123';
	const BASE64_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	const BASE64T_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	const BASE64URL_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
	const BCRYPT64_RANGE = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	const BASE32_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789=';
	const BASE128_RANGE = '!#$%()*,.0123456789:;=@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_abcdefghijklmnopqrstuvwxyz{|}~¡¢£¤¥¦§¨©ª«¬®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎ';
	const URLACCEPT_RANGE = '-.0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	const ALPHBA_NUMBERS_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	const WORD_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
	const GM_USERNAME_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-_';
	const TG_USERNAME_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
	const TG_TOKEN_REGEX = "/[0-9]{4,16}:AA[GFHE][a-zA-Z0-9-_]{32}/";
	const TG_USERNAME_REGEX = "/[a-zA-Z](?:[a-zA-Z0-9]|(?<!_)_){4,31}(?<!_)/";
	const NUMBER_REGEX = "/[0-9]+(?:\.[0-9]+){0,1}|\.[0-9]+|[0-9]+\./";
	const HEX_REGEX = "/[0-9a-fA-F]+/";
	const BINARY_REGEX = "/[01]+/";
	const LINK_REGEX = "/(?:[a-zA-Z0-9]+:\/\/){0,1}(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))(?:\?[^ \n\r\t\/\\#]*){0,1}(?:#[^ \n\r\t\/]*){0,1}/";
	const EMAIL_REGEX = "/(?:[^ \n\r\t\/\\#?@]+)@(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})/";
	const FILENAME_REGEX = "/[^ \n\r\t\/\\#@?]+/";
	const DIRACTORY_REGEX = "/(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))/";
	public static function charinrange($char, $range){
		if($char === '')return false;
		return strpos($range, $char[0]) !== false;
	}
	public static function strinrange($str, $range){
		for($c = 0;isset($str[$c]);++$c)
			if(strpos($range, $str[$c]) === false)
				return false;
		return true;
	}
	public static function getinrange($str, $range){
		$string = '';
		for($c = 0;isset($str[$c]);++$c)
			if(strpos($range, $str[$c]) !== false)
				$string .= $str[$c];
		return $string;
	}
	public static function random($range, $length = 1){
		if($length < 1)$length = 1;
		if(is_string($range))
			$range = str_split($range);
		if(is_object($range))
			$range = (array)$range;
		if(!is_array($range))
			return false;
		$str = '';
		while(--$length >= 0)
			$str .= $range[array_rand($range)];
		return $str;
	}

	// calc functions
	public static function xorn($a, $b){
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
	public static function xor_chars($chars, $string){
		$str = '';
		for($i = 0;isset($chars[$i]);++$i)
			if(strpos($string, $chars[$i]) === false)
				$str .= $chars[$i];
		return $str;
	}
	private static function mequal(&$a, &$b){
		$la = strlen($a);
		$lb = strlen($b);
		if($lb - $la > 0)$a = str_repeat("\0", $lb - $la) . $a;
		if($la - $lb > 0)$b = str_repeat("\0", $la - $lb) . $b;
		return max($la, $lb);
	}
	public static function add($a, $b){
		$c = '';
		$l = self::mequal($a, $b);
		$p = 0;
		while(--$l >= 0){
			$p += ord($a[$l]) + ord($b[$l]);
			$c = chr($p) . $c;
			$p = floor($p / 256);
		}
		if($p != 0)$c = chr($p) . $c;
		$c = ltrim($c, "\0");
		return $c === '' ? "\0" : $c;
	}
	public static function sub($a, $b){
		$c = '';
		$l = self::mequal($a, $b);
		$p = 0;
		while(--$l >= 0){
			$p += ord($a[$l]) - ord($b[$l]);
			$c = chr($p) . $c;
			$p = floor($p / 256);
		}
		if($p != 0)$c = ~(chr(-$p) . $c);
		$c = ltrim($c, "\0");
		return $c === '' ? "\0" : $c;
	}
	public static function mul($a, $b){
		$c = '';
		$l = self::mequal($a, $b);
		$p = 0;
		while(--$l >= 0){
			$p += ord($a[$l]) * ord($b[$l]);
			$c = chr($p) . $c;
			$p = floor($p / 256);
		}
		if($p != 0)$c = chr($p) . $c;
		$c = ltrim($c, "\0");
		return $c === '' ? "\0" : $c;
	}
	public static function div($a, $b){
		return xncrypt::bindecode(xnbinary::div(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function mod($a, $b){
		return xncrypt::bindecode(xnbinary::mod(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function xorx($a, $b){
		self::mequal($a, $b);
		return $a ^ $b;
	}
	public static function andx($a, $b){
		self::mequal($a, $b);
		return $a & $b;
	}
	public static function orx($a, $b){
		self::mequal($a, $b);
		return $a | $b;
	}
	public static function resx($a, $b){
		self::mequal($a, $b);
		return $b | ($a ^ $b);
	}
	public static function shr($a, $x){
		return xncrypt::bindecode(xnbinary::shr(xncrypt::binencode($a), $x));
	}
	public static function shl($a, $x){
		return xncrypt::bindecode(xnbinary::shl(xncrypt::binencode($a), $x));
	}
	public static function shift($a, $x){
		return xncrypt::bindecode(xnbinary::shift(xncrypt::binencode($a), $x));
	}
	public static function brtr($a, $x){
		return xncrypt::bindecode(xnbinary::rtr(xncrypt::binencode($a), $x));
	}
	public static function brtl($a, $x){
		return xncrypt::bindecode(xnbinary::rtl(xncrypt::binencode($a), $x));
	}
	public static function brev($a, $x){
		return xncrypt::bindecode(strrev(xncrypt::binencode($a)));
	}
	public static function neg($x){
		return xncrypt::bindecode(xnbinary::neg(ltrim(xncrypt::binencode($a), '0')));
	}

	// split functions
	public static function splitrev($string, $length = null){
		if($length === null)return strrev($string);
		return implode('', array_map('strrev', str_split($string, $length)));
	}
	public static function revsplit($string, $length = null){
		if($length === null)return $string;
		return implode('', array_reverse(str_split($string, $length)));
	}
	public static function revsplitrev($string, $length = null){
		if($length === null)return strrev($string);
		return implode('', array_reverse(array_map('strrev', str_split($string, $length))));
	}
	public static function rsplit($string, $length = 1){
		$l = $length - strlen($str) % $length;
		if($l == $length)
			return str_split($str, $length);
		return array_merge(array(substr($str, 0, $l)), str_split(substr($str, $l), $length));
	}
	public static function rea($string){
		for($i = 1; isset($string[$i]); ++$i)
			$string = self::splitrev($string, $i);
		return $string;
	}
	public static function unrea($string){
		for($i = strlen($string) - 1; $i > 0; --$i)
			$string = self::splitrev($string, $i);
		return $string;
	}
	public static function split($str, $count = 1, $space = 1){
		$arr = array();
		$length = strlen($str);
		$str = str_split($str);
		$loc = 0;
		while($loc < $length) {
			$c = 0;
			$r = '';
			while($c < $count && $loc + $c < $length) {
				$r = $r . $str[$loc + $c];
				++$c;
			}
			$arr[] = $r;
			$loc+= $space;
		}
		return $arr;
	}
	public static function equlen($string1, $string2){
		$l1 = strlen($string1);
		$l2 = strlen($string2);
		return substr(str_repeat($string2, ceil($l1 / $l2)), 0, $l1);
	}
	public static function subrep($string, $length){
		$l = strlen($string);
		return substr(str_repeat($string, ceil($length / $l)), 0, $length);
	}
	public static function translit($string){
		return xncrypt::dictencode($string, xncrypt::dictget(xndata("encoding-translit")));
	}
	public static function toupper($string){
		return xncrypt::dictencode($string, xncrypt::dictget(xndata("encoding_upperlower")));
	}
	public static function tolower($string){
		return xncrypt::dictdecode($string, xncrypt::dictget(xndata("encoding_upperlower")));
	}
	public static function replace($from, $to, $string, $count = 0){
		if($count <= 0)$count += strlen($string);
		$l = strlen($from);
		do{
			$pos = stripos($string, $from);
			if($pos === false)break;
			$string = substr_replace($string, $to, $pos, $l);
		}while(--$count >= 0);
		return $string;
	}
	public static function ireplace($from, $to, $string, $count = 0){
		if($count <= 0)$count += strlen($string);
		$l = strlen($from);
		do{
			$pos = strrpos($string, $from);
			if($pos === false)break;
			$string = substr_replace($string, $to, $pos, $l);
		}while(--$count >= 0);
		return $string;
	}
	public static function rreplace($from, $to, $string, $count = 0){
		if($count <= 0)$count += strlen($string);
		$l = strlen($from);
		do{
			$pos = strpos($string, $from);
			if($pos === false)break;
			$string = substr_replace($string, $to, $pos, $l);
		}while(--$count >= 0);
		return $string;
	}
	public static function rireplace($from, $to, $string, $count = 0){
		if($count <= 0)$count += strlen($string);
		$l = strlen($from);
		do{
			$pos = strripos($string, $from);
			if($pos === false)break;
			$string = substr_replace($string, $to, $pos, $l);
		}while(--$count >= 0);
		return $string;
	}
}
function userip(){
	$env = array(getenv('HTTP_CLIENT_IP'), getenv('HTTP_X_FORWARDED'), getenv('HTTP_X_FORWARDED_FOR'), getenv('REMOTE_ADDR'));
	if($env[0])return $env[0];
	if($env[1])return $env[1];
	if($env[2])return $env[2];
	if($env[3])return $env[3];
	return '127.0.0.1';
}
function boolnumber($x, $bbv = 12){
	$tree = XNMath::tree($x);
	$strs = array();
	foreach($tree as $num) {
		if(isset($strs[$num]))++$strs[$num][1];
		else {
			$n = $num;
			$s = array();
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
				$s[] = ($r ? str_repeat('!', $r): '') . '[]';
			}
			$s = implode('+', $s);
			$strs[$num] = array("($s)", 1);
		}
	}
	$s = array();
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
function is_regex($x){
	return @preg_match($x, null) !== false;
}
function is_ereg($x){
	return @ereg($x, null) !== false;
}
function filestate($filename){
	$f = fopen($filename, 'r');
	if(!$f){
		new XNError("filestate", "No such file or directory.", XNError::NOTIC);
		return false;
	}
	$s = fstat($f);
	fclose($f);
	return $s;
}
class BrainFuck {
	public $homes = array(0), $home = 0, $output = '', $input = '', $position = - 1;
	public function __construct($code = null, $input = null){
		$this->input = $input !== null ? $input : '';
		if($code !== null)$this->code($code);
	}
	public function code($code){
		$homes = &$this->homes;
		$home = &$this->home;
		$output = &$this->output;
		$input = &$this->input;
		$position = &$this->position;
		for($c = 0; isset($code[$c]); ++$c)
			switch($code[$c]) {
				case "+":
					++$homes[$home];
				break;
				case "-":
					--$homes[$home];
				break;
				case ">":
					++$home;
					if(!isset($homes[$home]))$homes[$home] = 0;
				break;
				case "<":
					--$home;
					if(!isset($homes[$home]))$homes[$home] = 0;
				break;
				case "[":
					$q = '';
					for($x = 1; isset($code[++$c]) && $x > 0;) {
						if($code[$c] == '[')++$x;
						elseif($code[$c] == ']')--$x;
						$q.= $code[$c];
					}
					--$c;
					$q = substr($q, 0, -1);
					while($homes[$home] % 256 != 0)$this->code($q);
				break;
				case ".":
					$output.= chr($homes[$home]);
				break;
				case ",":
					$homes[$home] = ord(isset($input[$position])? $input[++$position] : $input[$position--]);
			}
		return $this->output;
	}
	public static function run($code, $input = null){
		$tmp = new BrainFuck($code, $input);
		return $tmp->output;
	}
	public static function file($file, $input = null){
		$code = @file_get_contents($file);
		if($code === false)return false;
		$tmp = new BrainFuck($code, $input);
		return $tmp->output;
	}
}
define('UNICODE_CHARS',0);
define('UNICODE_ALL',1);
define('UNICODE_UTF',2);
function unicode_encode($str,$charset = 'UTF-8',$type = 2){
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
function unicode_decode($str){
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
define("XNDEFINE_TEXT",		   1);
define("XNDEFINE_REGEX",		  2);
define("XNDEFINE_INPUT",		  3);
define("XNDEFINE_REGEX_CALLBACK", 4);
define("XNDEFINE_INPUT_CALLBACK", 5);
define("XNDEFINE_ITEXT",		  6);
define("XNDEFINE_IINPUT",		 7);
define("XNDEFINE_CONST",		  8);
define("XNDEFINE_ALL",			1);
define("XNDEFINE_STRING",		 2);
define("XNDEFINE_NUMBER",		 3);
define("XNDEFINE_CODE",		   4);
function xndefine($from, $to, $type = 1, $locate = 1){
	$source = implode("\n", array_slice(explode("\n", getsource()), theline()));
	if($type == XNDEFINE_CONST) {
		define($from, $to);
		return;
	}
	switch($locate) {
	case XNDEFINE_ALL:
		switch($type) {
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
		__xnlib_data::$source = $source;
		eval($source);
		exit;
		break;
	case XNDEFINE_STRING:
		$source = preg_replace_callback("/(?<!\\\\)(?:\'(?:\\\'|[^\'])*(?<!\\\\)\'|\"(?:\\\\\"|[^\"])*(?<!\\\\)\")/",
		function($x)use($from, $to, $type){
			$source = substr($x[0], 1, -1);
			$x = $x[0][0];
			switch($type) {
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
		__xnlib_data::$source = $source;
		eval($source);
		exit;
		break;
	case XNDEFINE_CODE:
		$saves = array();
		$source = preg_replace_callback("/(?i)(?:(?<!\\\\)(?:\'(?:\\\'|[^\'])*(?<!\\\\)\'|\"(?:\\\\\"|[^\"])*(?<!\\\\)\")|(?:[0-9]+\.[0-9]+|[0-9]+\.|\.[0-9]+|[0-9]+)|(?:0[xb][0-9]+)|true|false|null)/",
		function($x)use(&$saves, $source){
			$saves[] = array(strpos($source, $x[0]), $x[0]);
			return '';
		}
		, $source);
		if(!$source)return false;
		$lsl = strlen($source);
		switch($type) {
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
		__xnlib_data::$source = $source;
		eval($source);
		exit;
		break;
	default:
		if(is_string($locate)) {
			$source = preg_replace_callback($locate,
			function($x)use($from, $to, $type){
				$x = $x[0];
				switch($type) {
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
			__xnlib_data::$source = $source;
			eval($source);
			exit;
		}
		return false;
		break;
	}
	return true;
}
function push($x){
	__xnlib_data::$push[$p = __xnlib_data::$pushed++] = $x;
	return $p;
}
function ppush($x){
	__xnlib_data::$push[$p = __xnlib_data::$pushed === 0 ? 0 : __xnlib_data::$pushed - 1] = $x;
	return $p;
}
function pop(){
	return __xnlib_data::$push[__xnlib_data::$pushed === 0 ? 0 : --__xnlib_data::$pushed];
}
function ppop(){
	return __xnlib_data::$push[__xnlib_data::$pushed];
}
function stack_usage(){
	return __xnlib_data::$pushed;
}
function stack_locate(){
	return count(__xnlib_data::$push);
}
function stack_reset(){
	__xnlib_data::$push = array();
	__xnlib_data::$pushed = 0;
}
function stack_delete(){
	$l = count(__xnlib_data::$push);
	if($l === 0)return false;
	unset(__xnlib_data::$push[$l - 1]);
	return true;
}
function stack_gcc(){
	$c = __xnlib_data::$pushed;
	while(isset(__xnlib_data::$push[++$c]))
		unset(__xnlib_data::$push[$c]);
	return $c - __xnlib_data::$pushed - 1;
}
function get_request_url(){
	$port = getenv('SERVER_PORT');
	return (getenv('HTTPS') ? 'https' : 'http') . '://' . getenv('SERVER_NAME') .
	($port == 80 || $port == 443 ? '' : ':' . $port) . getenv('REQUEST_URI');
}
function gethostip(){
	return gethostbyname(gethostname());
}
function getserverip(){
	return gethostbyname(getenv('SERVER_NAME'));
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
	$headers = array();
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
	$headers = array();
	foreach($_SERVER as $header=>$value){
		if(strpos($header,"HTTP_") !== 0)continue;
		$headers[strtr(ucwords(strtr(strtolower(substr($header,5)),'_',' ')),' ','-')] = $value;
	}
	return $headers;
}
function get_request_query($array = null){
	global $_REQUEST;
	$query = getenv("QUERY_STRING");
	if(!$query && $_REQUEST !== array()){
	  if($array)return $_REQUEST;
	  $query = http_build_query($_REQUEST);
	}elseif(!$query && xnlib::$PUT)
		$query = xnlib::$PUT;
	if($array){
		parse_str($query, $query);
		return $query;
	}
	return $query;
}
function get_request_string($file = false){
	return get_request_title($file)."\r\n".get_request_headers_string()."\r\n".get_request_query();
}
function http_response_status($code = null){
	if(!$code)$code = http_response_code();
	$codes = XNNet::http_status_code();
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
	$arr = array();
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
function str_rot($s, $n = 13){
	$letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
	$n = (int)$n % 26;
	if(!$n)return $s;
	if($n < 0)$n += 26;
	if($n == 13)return str_rot13($s);
	$rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
	return strtr($s, $letters, $rep);
}
function xnfread($stream,$length = 1){
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
function xnfwrite($stream,$content = '',$length = -1){
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
function socket_connect_socks5($socket, $address, $port, $username = null,$password = null){
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
function xnloop($loop = -1,$file = null,$wait = null,$close = null){
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
function xnloope($loop = null, $file = null, $wait = null, $close = null){
	register_shutdown_function(function()use($loop, $file, $wait, $close){
		xnloop($loop, $file, $wait, $close);
	});
}
function publicdir(){
	return substr(thefile(), 0, -strlen(getenv('SCRIPT_NAME')));
}
if(!defined("PUBLICDIR"))define('PUBLICDIR', publicdir());
function to_web_visibly($file){
	$home = str_ireplace("file://", '', publicdir());
	$file = str_ireplace("file://", '', $file);
	if($file === '')
		$file = '/';
	elseif(strpos($file, $home) === 0)
		$file = substr($file,strlen($home));
	return strtr($file,'\\','/');
}
function thelink($replacement = null){
	if($replacement === null)$replacement = null;
	if(!isset($replacement['scheme']))
		$replacement['scheme'] = getenv('HTTPS') ? 'https' : 'http';
	else
		$replacement['scheme'] = strtolower($replacement['scheme']);
	if(!isset($replacement['host']))
		$replacement['host'] = getenv('SERVER_NAME');
	if(!isset($replacement['port']))
		$replacement['port'] = getenv('SERVER_PORT');
	if(($replacement['scheme'] == 'http' && $replacement['port'] == 80) || ($replacement['scheme'] == 'https' && $replacement['port'] == 443))
		$replacement['port'] = false;
	if(!isset($replacement['path']))
		$replacement['path'] = getenv('SCRIPT_NAME');
	else
		$replacement['path'] = '/' . rtrim(to_web_visibly($replacement['path']), '/');
	if(!isset($replacement['query']) || $replacement['query'] === true)
		$replacement['query'] = getenv('QUERY_STRING');
	elseif(is_array($replacement['query']))
		$replacement['query'] = http_build_query($replacement['query']);
	return $replacement['scheme'] . '://' . $replacement['host'] . ($replacement['port'] ? ':' . $replacement['port'] : '')
		. $replacement['path'] . ($replacement['query'] ? '?' . $replacement['query'] : '');
}
xnlib::$link = thelink();
function open_class($name,&$return = 53348987487374){
	$input = func_get_args();
	unset($input[0], $input[1]);
	$class = unserialize("O:".strlen($name).":\"$name\":0:{}");
	if($input === array() && $return === 53348987487374)
		return $class;
	$input[1] = $class;
	$return = call_user_func_array('call_constructor', $input);
	return $class;
}
function call_constructor($class){
	$args = func_get_args($class);
	unset($args[0]);
	if(method_exists($class,"__construct"))
		return call_user_method_array('__construct', $class, $args);
}
function is_incomplete_class($x){
	return $x instanceof __PHP_Incomplete_Class;
}
define("PHP_INT_BITS", PHP_INT_SIZE * 8);
function create_stream_content($content, $mime_type = 'text/plain', $mode = 'rw+b'){
	return fopen("data://$mime_type," . urlencode($content),$mode);
}
function goline($x, $seek = null){
	$source = explode("\n", getsource());
	if($seek === 1)$x += theline();
	elseif($seek === 2)$x = count($source) - $x;
	else --$x;
	$code = implode("\n",array_slice($source,$x));
	evale($code);
}
function getsource(){
	if(__xnlib_data::$source === false)
		__xnlib_data::$source = file_get_contents(thefile());
	return __xnlib_data::$source;
}
if(file_exists('autoinclude.php')){
	try{
		require 'autoinclude.php';
	}catch(Error $e){
		$msg = $e->getMessage() . ' in line ' . $e->getLine() . ' on file ' . $e->getFile();
		$msg = "\n\t" . str_replace("\n", "\n\t", $msg) . "\n";
		new XNError("XNAutoLoadFile", "error {$msg}");
	}
}
if(file_exists(__xnlib_data::$dirname . DIRECTORY_SEPARATOR . 'autoincludeall.php')){
	try{
		require __xnlib_data::$dirname . DIRECTORY_SEPARATOR . 'autoincludeall.php';
	}catch(Error $e){
		$msg = $e->getMessage() . ' in line ' . $e->getLine() . ' on file ' . $e->getFile();
		$msg = "\n\t" . str_replace("\n", "\n\t", $msg) . "\n";
		new XNError("XNAutoLoadFileAll", "error {$msg}");
	}
}
function substring($str, $from, $to = null){
	if($to === null)
		return substr($str, $from);
	return substr($str, $from, $to - $from);
}
function server_ipv6(){
	if(defined('SERVER_IPV6'))
		return SERVER_IPV6;
	$r = (bool)@file_get_contents('http://v6.ipv6-test.com/api/myip.php');
	define('SERVER_IPV6', $r);
	return $r;
}
function is_ipv4($ip){
	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}
function is_ipv6($ip){
	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
}
xnlib::$remoter = getenv('REMOTE_ADDR');
xnlib::$remoter = (is_ipv6(xnlib::$remoter) ? '[' . xnlib::$remoter . ']' : xnlib::$remoter) . ':' . getenv('REMOTE_PORT');
xnlib::$server = getenv('SERVER_ADDR');
xnlib::$server = (is_ipv6(xnlib::$server) ? '[' . xnlib::$server . ']' : xnlib::$server) . ':' . getenv('SERVER_PORT');

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
function object2array($object){
	$object = (array)$object;
	foreach($object as &$x)
		if(is_object($x))
			$x = object2array($x);
	return $object;
}
function array2object($array){
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
function destruct_call($object){
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
function readout($func){
	$params = func_get_args();
	unset($params[0]);
	ob_start();
	$mc = microtime(true);
	$ret = call_user_func_array($func, $params);
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
function lsort(array &$array){
	array_multisort(array_map('strlen', $array), SORT_ASC, SORT_STRING, $array);
	$array = array_reverse($array);
}
function lrsort(array &$array){
	array_multisort(array_map('strlen', $array), SORT_ASC, SORT_STRING, $array);
}
function lksort(array &$array){
	array_multisort(array_map('strlen', array_keys($array)), SORT_ASC, SORT_STRING, $array);
	$array = array_reverse($array);
}
function lrksort(array &$array){
	array_multisort(array_map('strlen', array_keys($array)), SORT_ASC, SORT_STRING, $array);
}
function array_settype(array &$array, $type){
	foreach($array as &$x)
		settype($x, $type);
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
function array_all_bool($array){
	foreach($array as $x)
		if(!$x)
			return false;
	return true;
}
function array_one_bool($array){
	foreach($array as $x)
		if($x)
			return true;
	return false;
}
function println($data = "\n"){
	if(($out = ob_get_contents()) !== '' && $out[strlen($out) - 1] == "\n")
		print $data;
	else print "\n" . $data;
}
function printbr($data = '<br/>'){
	if(($out = ob_get_contents()) !== '' && preg_match('/<[ \n\r\t]*br[ \n\r\t]*\/{0,1}[ \n\r\t]*>$/', $out))
		print $data;
	else print "<br/>" . $data;
}
function printlnbr($data){
	$headers = get_response_headers();
	if((!isset($headers['Content-type']) || strpos($headers['Content-type'], 'text/html') !== false) && (!isset($headers['Content-Type']) || strpos($headers['Content-Type'], 'text/html') !== false))
		printbr($data);
	else
		println($data);
}
function echoln(){
	$datas = func_get_args();
	foreach($datas as $data){
		if(($out = ob_get_contents()) !== '' && $out[strlen($out) - 1] == "\n")
			print $data;
		else print "\n" . $data;
	}
}
function cwdtmpfile(){
	do{
		$file = 'php.'.rand(0, 999999999).rand(0, 999999999).rand(0, 999999999).'.tmp';
	}while(file_exists($file));
	__xnlib_data::$cwdtmpfiles[] = new ThumbCode(function()use($file){
		unlink($file);
	});
	return fopen($file,'r+b');
}
function filemime($file){
	$finfo = finfo_open(FILEINFO_MIME);
	$mime = finfo_file($finfo, $file);
	finfo_close($finfo);
	return $mime;
}
function buffermime($buffer){
	$finfo = finfo_open(FILEINFO_MIME);
	$mime = finfo_buffer($finfo, $buffer);
	finfo_close($finfo);
	return $mime;
}
function _dirvar($var, $now){
	if((!is_array($now) && !is_object($now)) || $var === array())
		return $now;
	if(is_object($now))
		$now = (array)$now;
	$now = @$now[current($var)];
	unset($var[key($var)]);
	return _dirvar($var, $now);
}
function dirvar($var){
	$var = explode('/', trim(strtr($var, '.', '/'),'/'));
	return _dirvar($var, $GLOBALS);
}
function is_base64($content){
	return rtrim(base64_encode(base64_decode($content)), '=') == rtrim($content, '=');
}
function is_base2($content){
	return (int)strtr($content, '1', '0') === 0;
}
function is_base2int($content){
	return (int)strtr($content, '1', '0') === 0 && strpos($content, '.') === false;
}

if(!defined('DATE_ATOM'   ))define('DATE_ATOM'   ,'Y-m-d\TH:i:sP'   );
if(!defined('DATE_COOKIE' ))define('DATE_COOKIE' ,'l, d-M-Y H:i:s T');
if(!defined('DATE_ISO8601'))define('DATE_ISO8601','Y-m-d\TH:i:sO'   );
if(!defined('DATE_RFC822' ))define('DATE_RFC822' ,'D, d M y H:i:s O');
if(!defined('DATE_RFC850' ))define('DATE_RFC850' ,'l, d-M-y H:i:s T');
if(!defined('DATE_RFC1036'))define('DATE_RFC1036','D, d M y H:i:s O');
if(!defined('DATE_RFC1123'))define('DATE_RFC1123','D, d M Y H:i:s O');
if(!defined('DATE_RFC2822'))define('DATE_RFC2822','D, d M Y H:i:s O');
if(!defined('DATE_RFC3339'))define('DATE_RFC3339','Y-m-d\TH:i:sP'   );
if(!defined('DATE_RSS'	  ))define('DATE_RSS'	 ,'D, d M Y H:i:s O');
if(!defined('DATE_W3C'	  ))define('DATE_W3C'	 ,'Y-m-d\TH:i:sP'   );

define('MAX_NUMBER', 1.7976931348623e+308);

function printEncoding($content){
	$accept = getenv('HTTP_ACCEPT_ENCODING');
	if(strpos($accept, 'gzip') !== false){
		header('Content-Encoding: gzip');
		print gzencode($content, 9, 31);
		return 'gzip';
	}
	if(strpos($accept, 'deflate') !== false){
		header('Content-Encoding: deflate');
		print gzdeflate($content, 9, 31);
		return 'deflate';
	}
	print $content;
	return 'none';
}
function is_stream($stream){
	return is_resource($stream) && strtolower(get_resource_type($stream)) == 'stream';
}
function is_gd($gd){
	return is_resource($gd) && strtolower(get_resource_type($gd)) == 'gd';
}
function imagecreatefromfile($file){
	return imagecreatefromstring(file_get_contents($file));
}
function number2rgb($number){
	return array($number & 0xff, $number << 8 & 0xff, $number << 16 & 0xff, $number << 24 & 0xff);
}
function imagepixelsarray($image, $x = null, $y = null, $width = null, $height = null){
	if(!is_gd($image))
		return false;
	$pixels = array();
	if($x == -1 || $x === null)$x = 0;
	if($y == -1 || $y === null)$y = 0;
	if($width == -1 || $width === null)$width = imagesx($image) - $x;
	if($height == -1 || $height === null)$height = imagesy($image) - $y;
	for($a = 0;$a < $width;++$a){
		$pixels[$a] = array();
		for($b = 0;$b < $height;++$b)
			$pixels[$a][$b] = imagecolorat($image, $a + $x, $b + $y);
	}
	return $pixels;
}
function imagepixelsstring($image, $x = null, $y = null, $width = null, $height = null){
	if(!is_gd($image))
		return false;
	$pixels = array();
	if($x == -1 || $x === null)$x = 0;
	if($y == -1 || $y === null)$y = 0;
	if($width == -1 || $width === null)$width = imagesx($image) - $x;
	if($height == -1 || $height === null)$height = imagesy($image) - $y;
	for($a = 0;$a < $height;++$a){
		$pixels[$a] = array();
		for($b = 0;$b < $width;++$b)
			$pixels[$a][$b] = imagecolorat($image, $b + $x, $a + $y);
	}
	return $pixels;
}
function imagefrompixels($pixels, $width = null, $height = null){
	if($pixels === array()){
		if($width === null)$width = 1;
		if($height === null)$height = 1;
	}else{
		if($width === null)
			$width = call_user_func_array('max', array_map(function($x){
				if($x === array())return 1;
				return call_user_func_array('max', array_keys($x));
			}, $pixels));
		if($height === null)
			$height = call_user_func_array('max', array_keys($pixels));
	}
	$image = imagecreatetruecolor($width, $height);
	foreach($pixels as $y => $height)
		foreach($height as $x => $color)
			imagesetpixel($image, $x, $y, $color);
	return $image;
}
function imageresize($image, $width = null, $height = null, $crop = null){
	if(!is_gd($image)){
		new XNError('imageresize', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	$w = imagesx($image);
	$h = imagesy($image);
	if($width == -1 || $width === null)$width = $w - $x;
	if($height == -1 || $height === null)$height = $h - $y;
	if($crop === 0){
		$r =  $w / $h - $width / $height;
		$r = $r < 0 ? -$r : $r;
		if($w < $h)
			$width = ceil($width - ($width * $r));
		else
			$height = ceil($height - ($height * $r));
	}elseif($crop == 1){
		$r =  $w / $h - $width / $height;
		$r = $r < 0 ? -$r : $r;
		if($w < $h)
			$height = ceil($height + ($height * $r));
		else
			$width = ceil($width + ($width * $r));
	}elseif($crop == 2){
		$r = $w / $h;
		$width = $r * $height;
		$height = $width * $r;
	}
	$im = imagecreatetruecolor($width, $height);
	imagecopyresampled($im, $image, 0, 0, 0, 0, $width, $height, $w, $h);
	return $im;
}
function imageaveragecolor($image){
	if(!is_gd($image)){
		new XNError('imageaveragecolor', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	$w = imagesx($image);
	$h = imagesy($image);
	$im = imagecreatetruecolor(1, 1);
	imagecopyresampled($im, $image, 0, 0, 0, 0, 1, 1, $w, $h);
	$color = imagecolorat($im, 0, 0);
	imagedestroy($im);
	return $color;
}
function imagebackgroundcolor($image, $size = null){
	if(!is_gd($image)){
		new XNError('imagebackgroundcolor', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	$w = imagesx($image);
	$h = imagesy($image);
	if($size >= 1){
		$size = 1 / $size;
		$w = ceil($w * $size);
		$h = ceil($h * $size);
		$image = imageresize($image, $w, $h);
		$dest = null;
	}
	for($x = 0;$x < $w;++$x)
		for($y = 0;$y < $h;++$y)
			$colors[$c = imagecolorat($image, $x, $y)] = isset($colors[$c]) ? $colors[$c] + 1 : 1;
	if(isset($dest))
		imagedestroy($image);
	return array_search(call_user_func_array('max', $colors), $colors);
}
function imagebmpstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagebmpstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagebmp($image);
	elseif($filters === null)imagebmp($image, true, $quality);
	else imagebmp($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagewebpstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagewebpstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagewebp($image);
	elseif($filters === null)imagewebp($image, true, $quality);
	else imagewebp($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagexbmstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagexbmstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagexbm($image);
	elseif($filters === null)imagexbm($image, true, $quality);
	else imagexbm($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagegdstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagegdstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagegd($image);
	elseif($filters === null)imagegd($image, true, $quality);
	else imagegd($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagegd2string($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagegd2string', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagegd2($image);
	elseif($filters === null)imagegd2($image, true, $quality);
	else imagegd2($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagejpegstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagejpegstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagejpeg($image);
	elseif($filters === null)imagejpeg($image, true, $quality);
	else imagejpeg($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagegifstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagegifstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagegif($image);
	elseif($filters === null)imagegif($image, true, $quality);
	else imagegif($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagepngstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagepngstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagepng($image);
	elseif($filters === null)imagepng($image, true, $quality);
	else imagepng($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagewbmpstring($image, $quality = null, $filters = null){
	if(!is_gd($image)){
		new XNError('imagewbmpstring', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	ob_start();
	if($quality === null)imagewbmp($image);
	elseif($filters === null)imagewbmp($image, true, $quality);
	else imagewbmp($image, true, $quality, $filters);
	return ob_get_clean();
}
function imagecreatefromcolor($width, $height, $color){
	$image = imagecreatetruecolor($width, $height);
	imagefill($image, 0, 0, $color);
	return $image;
}
function imagerandx($image){
	if(!is_gd($image)){
		new XNError('imagerandx', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	return rand(0, imagesx($image) - 1);
}
function imagerandy($image){
	if(!is_gd($image)){
		new XNError('imagerandy', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	return rand(0, imagesy($image) - 1);
}
function imageborderpolygon($image, $point, $count, $color, $background){
	if(!is_gd($image)){
		new XNError('imageborderploygon', 'image gd resource invalid', XNError::WARNING);
		return false;
	}
	if(!imagefilledpolygon($image, $point, $count, $background))
		return false;
	if(!imagepolygon($image, $point, $count, $color))
		return false;
	return true;
}
class XNTelegram {
	public $session = array(), $settings = array(), $history = array(), $elements = array(), $flush = array(), $socket;

	// constants
	const VERSION = '1';
	const VERSION_CODE = 1;

	const SERIALIZATION_COMPRESS = 1;
	const SERIALIZATION_BASE64 = 2;
	
	const SESSION_FLUSH = 1;
	const SESSION_CONNECT = 2;
	const SESSION_SELF = 4;
	const SESSION_TIMER = 8;
	const SESSION_APPDATA = 16;
	const SESSION_LOGIN = 32;
	const SESSION_SETTINGS = 64;
	
	public function __construct($settings = null){
		if($settings !== null)
			$this->parse_settings($settings);
	}

	// crypt
	public function aescalc($msg, $auth, $to_server = true){
		$x = $to_server ? 0 : 8;
		$a = xncrypt::hash('sha256', $msg.substr($auth, $x, 36), true);
		$b = xncrypt::hash('sha256', substr($key, 40 + $x, 36).$msg, true);
		$key = substr($a, 0, 8).substr($b, 8, 16).substr($a, 24, 8);
		$iv = substr($b, 0, 8).substr($a, 8, 16).substr($b, 24, 8);
		return array($key, $iv);
	}
	public function old_aescalc($msg, $auth, $to_server = true){
		$x = $to_server ? 0 : 8;
		$a = sha1($msg.substr($auth, $x, 32), true);
		$b = sha1(substr($auth, 32 + $x, 16).$msg.substr($auth, 48 + $x, 16), true);
		$c = sha1(substr($auth, 64 + $x, 32).$msg, true);
		$d = sha1($msg.substr($auth, 96 + $x, 32), true);
		$key = substr($a, 0, 8).substr($b, 8, 12).substr($c, 4, 12);
		$iv = substr($a, 8, 12).substr($b, 0, 8).substr($c, 16, 4).substr($d, 0, 8);
		return array($key, $iv);
	}
	
	// elements parser
	public function load_elements(){
		if(isset($this->settings['elements']['file']) && is_string($this->settings['elements']['file']) && ($file = $this->settings['elements']['file']) &&
			file_exists($file) && ($data = file_get_contents($file)) && ($data = xncrypt::jsondecode($data, true)));
		elseif(isset($this->settings['elements']['file']) && is_array($this->settings['elements']['file']) && $data = $this->settings['elements']['file']);
		elseif(($data = file_get_contents('https://core.telegram.org/scheme/json')) && ($data = xncrypt::jsondecode($data, true))){
			foreach($data['methods'] as &$method){
				$pars = array();
				foreach($method['params'] as $param)
					$pars[$param['name']] = $param['type'];
				$method['params'] = $pars;
			}
			foreach($data['constructors'] as &$constructor){
				$pars = array();
				foreach($constructor['params'] as $param)
					$pars[$param['name']] = $param['type'];
				$constructor['params'] = $pars;
			}
		}else throw new XNError("XNTelegram loadElements", "can not Connect to https://core.telegram.org", XNError::NETWORK);
		if(isset($data['flush']))
			unset($data['flush']);
		$this->elements = $data;
		if(isset($this->settings['elements']['flush']) && $this->settings['elements']['flush'])
			return $this->flush_elements(isset($this->settings['elements']['level']) ? $this->settings['elements']['level'] : 1);
		if(isset($file) && is_string($file) && !file_exists($file) && touch($file)){
			if($this->flush !== array())
				$data['flush'] = $this->flush;
			file_put_contents($file, xncrypt::jsonencode($data));
		}
	}
	public function flush_elements($level){
		$elements = $this->elements;
		if(($level < 1 && $level > 2) || $elements === array()){
			new XNError("XNTelegram flushElements", "invalid flush level", XNError::NOTIC);
			return false;
		}
		$flush = &$this->flush;
		if(isset($this->settings['elements']['file']) && $file = $this->settings['elements']['file']){
			if($file && ((is_array($file)  && isset($file['flush']) && $data = $file['flush']) ||
				(file_exists($file) && ($data = file_get_contents($file)) && $data = xncrypt::jsondecode($data,true))) &&
				isset($data['methods']) && isset($data['predicates'])){
				$flush['methods'] = (array)$data['methods'];
				$flush['predicates'] = (array)$data['predicates'];
				if(isset($data['ids']))
					$flush['ids'] = (array)$data['ids'];
				return;
			}
		}
		$flush['methods'] = array();
		$flush['predicates'] = array();
		if($level == 2){
			$flush['ids'] = array();
			foreach($elements['methods'] as &$method){
				$flush['methods'][$method['method']] = &$method;
				$flush['ids'][$method['id']] = &$method;
			}
			foreach($elements['constructors'] as &$constructor){
				$flush['predicates'][$constructor['predicate']] = &$constructor;
				$flush['ids'][$constructor['id']] = &$constructor;
			}
		}else{
			foreach($elements['methods'] as &$method)
				$flush['methods'][$method['method']] = &$method;
			foreach($elements['constructors'] as &$constructor)
				$flush['predicates'][$constructor['predicate']] = &$constructor;
		}
		if(isset($file) && is_string($file) && ((!file_exists($file) && touch($file)) ||
			(isset($this->settings['elements']['update']) && $this->settings['elements']['update']))){
			$data = $elements;
			$data['flush'] = $flush;
			file_put_contents($file, xncrypt::jsonencode($data));
		}
	}

	// finders
	const METHOD = 1;
	const CONSTRUCTOR = 2;
	const PREDICATE = 3;
	const AUTO = 4;
	public function find_id($id, $type = null){
		if($id === null)$id = 4;
		if(isset($this->flush['ids'])){
			if(isset($this->flush['ids'][$id]))
				return $this->flush['ids'][$id];
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
	public function find_method($method){
		if(isset($this->flush['methods'])){
			if(isset($this->flush['methods'][$method]))
				return $this->flush['methods'][$method];
			return false;
		}
		foreach($this->elements['methods'] as $m)
			if($m['method'] == $method)
				return $m;
		return false;
	}
	public function find_predicate($predicate){
		if(isset($this->flush['predicates'])){
			if(isset($this->flush['predicates'][$predicate]))
				return $this->flush['predicates'][$predicate];
			return false;
		}
		foreach($this->elements['constructors'] as $constructor)
			if($constructor['predicate'] == $predicate)
				return $constructor;
		return false;
	}

	// conding
	public function type_encode($type,$content){
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
				if(PHP_INT_SIZE === 8)
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
					return chr($l) . $content . str_repeat("\0", XNMath::umod(-$l - 1, 4));
				else
					return "\xed" . substr(pack('l', $l), 0, 3) . $content . str_repeat("\0", XNMath::umod(-$l, 4));
			case 'Bool':
				return pack('l', array_value($this->find_predicate((bool)$content ? 'boolTrue' : 'boolFalse'), 'id'));
			case '!X':
				return $content;
			case 'Vector':
				$data = pack('l', array_value($this->find_predicate('vector'), 'id'));
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
		$method = $this->find_method($type);
		$data = pack('N', $method['id']);
		foreach($method['params'] as $name => $param)
			$data .= $this->type_encode($param, @$content[$name]);
		return $data;
	}
	public function type_write($stream,$type,$content){
		if(!is_resource($stream))
			return false;
		return fwrite($stream,$this->type_encode($type,$content));
	}
	public function type_read($stream,$type = false){
		if(feof($stream) || !is_resource($stream))return null;
		if(!$type)
			$type = $this->find_id($id = array_value(unpack('N', fread($stream, 4)), 1));
		else{
			$type = $this->find_method($name = $type);
			if($type === false)
				$type = array(
					'id' => '0',
					'method' => $name,
					'params' => array(),
					'type' => $name
				);
		}
		if($type === false)
			new XNError("XNTelegram id@" . bin2hex($id), 'invalid type id', XNError::TYPE, XNError::TTHROW);
		$p = strpos($type['method'], '<');
		if($p !== false){
			$sub = substr($type, $p + 1, -1);
			$type['method'] = substr($type['method'], 0, $p);
		}
		switch($type['method']){
			case 'int':
				return array_value(unpack('l', fread($stream, 4)), 1);
			case 'int128':
				return fread($stream, 16);
			case 'int256':
				return fread($stream, 32);
			case 'int512':
				return fread($stream, 64);
			case '#':
				return array_value(unpack('V', fread($stream, 4)), 1);
			case 'double':
				return array_value(unpack('d', fread($stream, 8)), 1);
			case 'bytes':
			case 'string':
				$l = ord(fgetc($stream));
				if($l >= 254){
					$l = array_value(unpack('V', fgetc($stream) . "\0"), 1);
					$str = fread($stream, $l);
					$res = XNMath::umod(-$l, 4);
					if($res > 0)
						fseek($stream, $res, SEEK_CUR);
				}else{
					$str = $l > 0 ? fread($stream, $l) : '';
					$res = XNMath::umod(-$l - 1, 4);
					if($res > 0)
						fseek($stream, $res, SEEK_CUR);
				}
				return $type == 'bytes' ? array('bytes', 'bytes' => base64_encode($str)) : $str;
			case 'gzip_packed':
				return gzdecode($this->type_read($stream, 'string'));
			case 'Vector':
				fseek($stream, 4, SEEK_CUR);
			case 'vector':
				$count = array_value(unpack('V', fread($stream, 4)), 1);
				$res = array();
				while(--$count >= 0)
					$res[] = $this->type_read($stream, $sub);
				return $res;
			case 'Bool':
				return array_value($this->find_id(array_value(unpack('l', fread($stream, 4)), 1)), 'predicate') == 'boolTrue';
			case 'long':
				$content = fread($stream, 8);
				if(PHP_INT_SIZE === 8)
					return array_value(unpack('q', $content), 1);
				switch($this->settings['number']){
					case 0:
						return $content;
					case 1:
						return array_value(unpack('l', substr($content, 0, 4)), 1) * 4294967296;
					case 2:
						return xnmath::ascii2number(strrev($content));
					break;
					case 3:
						return xnnumber::base_convert(strrev($content), 'ascii', 10);
				}
		}
		$content = array();
		foreach($type['params'] as $name => $param)
			$content[$name] = $this->type_read($stream, $param);
		return $content;
	}
	public function type_decode($string,$type = false, $offset = 0){
		if(!isset($string[$c]))return null;
		if(!$type){
			$type = $this->find_id($id = array_value(unpack('N', substr($string, $c, 4)), 1));
			$c += 4;
		}else{
			$type = $this->find_method($name = $type);
			if($type === false)
				$type = array(
					'id' => '0',
					'method' => $name,
					'params' => array(),
					'type' => $name
				);
		}
		if($type === false)
			new XNError("XNTelegram id@" . bin2hex($id), 'invalid type id', XNError::TYPE, XNError::TTHROW);
		$p = strpos($type['method'], '<');
		if($p !== false){
			$sub = substr($type, $p + 1, -1);
			$type['method'] = substr($type['method'], 0, $p);
		}
		switch($type['method']){
			case 'int':
				return array(array_value(unpack('l', substr($string, $c, 4)), 1), $c + 4);
			case 'int128':
				return array(substr($string, $c, 16), $c + 16);
			case 'int256':
				return array(substr($string, $c, 32), $c + 32);
			case 'int512':
				return array(substr($string, $c, 64), $c + 64);
			case '#':
				return array(array_value(unpack('V', substr($string, $c, 4)), 1), $c + 4);
			case 'double':
				return array(array_value(unpack('d', substr($string, $c, 8)), 1), $c + 8);
			case 'bytes':
			case 'string':
				$l = ord($string[$c++]);
				if($l >= 254){
					$l = array_value(unpack('V', $string[$c++] . "\0"), 1);
					$str = substr($string, $c, $l);
					$res = XNMath::umod(-$l, 4);
				}else{
					$str = $l > 0 ? substr($string, $c, $l) : '';
					$res = XNMath::umod(-$l - 1, 4);
				}
				return array($type == 'bytes' ? array('bytes', 'bytes' => base64_encode($str)) : $str, $c + $l);
			case 'gzip_packed':
				return array(gzdecode($this->type_decode(substr($string, $c), 'string')), strlen($string));
			case 'Vector':
				$c += 4;
			case 'vector':
				$count = array_value(unpack('V', substr($string, $c, 4)), 1);
				$c += 4;
				$res = array();
				while(--$count >= 0){
					$r = $this->type_decode(substr($string, $c), $sub);
					$c+= $r[1];
					$res[] = $r[0];
				}
				return array($res, $c);
			case 'Bool':
				return array(array_value($this->find_id(array_value(unpack('l', substr($string, $c, 4)), 1)), 'predicate') == 'boolTrue', $c + 4);
			case 'long':
				$content = substr($string, $c, 8);
				if(PHP_INT_SIZE === 8)
					return array_value(unpack('q', $content), 1);
				switch($this->settings['number']){
					case 0:
						return array($content, $c + 8);
					case 1:
						return array(array_value(unpack('l', substr($content, 0, 4)), 1) * 0xffffffff, $c + 8);
					case 2:
						return array(xnmath::ascii2number(strrev($content)), $c + 8);
					break;
					case 3:
						return array(xnnumber::base_convert(strrev($content), 'ascii', 10), $c + 8);
				}
		}
		$content = array();
		foreach($type['params'] as $name => $param){
			$content[$name] = $this->type_decode($string, $param, $c);
			$c = $content[$name][1];
			$content[$name] = $content[$name][0];
		}
		return $content;
	}
	public function type_read_all($stream, $input){
		if(!is_resource($stream))
			return false;
		if(!is_array($input))
			return $this->type_read_all($stream, explodek(array('/', ':'), $input));
		$res = array();
		foreach($input as $key => $content)
			$res[$key] = $this->type_read($stream, $content);
		return $res;
	}
	public function type_decode_all($content, $input){
		if(!is_array($input))
			return $this->type_decode_all($content, explodek(array('/', ':'), $input));
		$res = array();
		$c = 0;
		foreach($input as $key => $data){
			$res[$key] = $this->type_decode(substr($content, $c), $data);
			$c += $res[$key][1];
			$res[$key] = $res[$key][0];
		}
		return $res;
	}
	public function type_encode_all($input){
		if(!is_array($input))
			return $this->type_encode_all(explodek(array('/', ':'), $input));
		$res = '';
		foreach($input as $content)
			if(isset($content[1]))
				$res .= $this->type_encode($content[0], $content[1]);
		return $res;
	}
	public function type_write_all($stream, $input){
		if(!is_array($input))
			return $this->type_write_all($stream, explodek(array('/', ':'), $input));
		if(!is_resource($stream))
			return false;
		return fwrite($stream, $this->type_encode_all($input));
	}

	// peer id
	public function to_supergroup($id){
		return -($id + pow(10, floor(log($id, 10) + 3)));
	}
	public function from_supergroup($id){
		return pow(10, floor(log(-$id, 10) - 3)) - $id;
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
	public function fileid_decode($id){
		$id = xncrypt::rledecode(xncrypt::base64urldecode($id));
		if($id[strlen($id) - 1] != "\x02")
			return false;
		$file = substr($id, 4);
		$id = array_value(unpack('l', substr($id, 0, 4)), 1);
		$files = array(
			0  => array("thumb"		, 'dc_id:int/id:long/access_hash:long/volume_id:long/secret:long/local_id:int'),
			2  => array("photo"		, 'dc_id:int/id:long/access_hash:long/volume_id:long/secret:long/local_id:int'),
			3  => array("voice"		, 'dc_id:int/id:long/access_hash:long'),
			4  => array("video"		, 'dc_id:int/id:long/access_hash:long'),
			5  => array("document"	, 'dc_id:int/id:long/access_hash:long'),
			8  => array("sticker"	, 'dc_id:int/id:long/access_hash:long'),
			9  => array("audio"		, 'dc_id:int/id:long/access_hash:long'),
			10 => array("gif"		, 'dc_id:int/id:long/access_hash:long'),
			12 => array("video_note", 'dc_id:int/id:long/access_hash:long')
		);
		if(!isset($files[$id]))
			return false;
		$id = $files[$id];
		$name = $id[0];
		$file = $this->type_decode_all($file, $id[1]);
		return array($name, $file);
	}

	// settings
	const RESULT_DEFUALT_MODEL = 0;
	const RESULT_XN_MODEL	  = 1;
	const RESULT_BOTAPI_MODEL  = 2;

	public function parse_settings($options = array()){
		try{
			$model = php_uname('s');
		}catch(Exception $e) {
			$model = 'Web server';
		}
		try{
			$version = php_uname('r');
		}catch(Exception $e) {
			$version = phpversion();
		}
		if($lang = getenv('LANG'))
			$lang = array_value(explode('_', $lang, 2), 0);
		elseif($lang = getenv('HTTP_ACCEPT_LANGUAGE'))
			$lang = substr($lang, 0, 2);
		else
			$lang = 'en';
		$settings = array(
			'serialization' => self::SERIALIZATION_COMPRESS,
			'session' => array(
				'serialization' => self::SESSION_FLUSH + self::SESSION_CONNECT + self::SESSION_SELF + self::SESSION_TIMER + self::SESSION_APPDATA + self::SESSION_LOGIN + self::SESSION_SETTINGS,
				'password' => false,
				'file' => 'xntelegram' . getenv('REMOTE_ADDTR') . '.session',
				'mode' => 600
			),
			'time' => array(
				'last_modified' => microtime(true),
				'created' => microtime(true),
				'serialized' => 0,
				'unserialized' => 0,
				'logined' => 0
			),
			'number' => 3,
			'subdomains' => array(
			),
			'rsa_keys' => array(
			"-----BEGIN RSA PUBLIC KEY-----\nMIIBCgKCAQEAwVACPi9w23mF3tBkdZz+zwrzKOaaQdr01vAbU4E1pvkfj4sqDsm6\nlyDONS789sVoD/xCS9Y0hkkC3gtL1tSfTlgCMOOul9lcixlEKzwKENj1Yz/s7daS\nan9tqw3bfUV/nqgbhGX81v/+7RFAEd+RwFnK7a+XYl9sluzHRyVVaTTveB2GazTw\nEfzk2DWgkBluml8OsubvfraX3bkHZJTKX4EQSjBbbdJ2ZXIsRrYOXfaA+xayEGB+\n8hdlLmAjbCVfaigxX0CDqWeR1yFL9kwd9P0NsZRPsmoqVwMbMu7mStFai6aIhc3n\nSlv8kg9qv1m6XHVQY3PnEw+QQtqSIXklHwIDAQAB\n-----END RSA PUBLIC KEY-----",
			"-----BEGIN RSA PUBLIC KEY-----\nMIIBCgKCAQEAxq7aeLAqJR20tkQQMfRn+ocfrtMlJsQ2Uksfs7Xcoo77jAid0bRt\nksiVmT2HEIJUlRxfABoPBV8wY9zRTUMaMA654pUX41mhyVN+XoerGxFvrs9dF1Ru\nvCHbI02dM2ppPvyytvvMoefRoL5BTcpAihFgm5xCaakgsJ/tH5oVl74CdhQw8J5L\nxI/K++KJBUyZ26Uba1632cOiq05JBUW0Z2vWIOk4BLysk7+U9z+SxynKiZR3/xdi\nXvFKk01R3BHV+GUKM2RYazpS/P8v7eyKhAbKxOdRcFpHLlVwfjyM1VlDQrEZxsMp\nNTLYXb6Sce1Uov0YtNx5wEowlREH1WOTlwIDAQAB\n-----END RSA PUBLIC KEY-----",
			"-----BEGIN RSA PUBLIC KEY-----\nMIIBCgKCAQEAsQZnSWVZNfClk29RcDTJQ76n8zZaiTGuUsi8sUhW8AS4PSbPKDm+\nDyJgdHDWdIF3HBzl7DHeFrILuqTs0vfS7Pa2NW8nUBwiaYQmPtwEa4n7bTmBVGsB\n1700/tz8wQWOLUlL2nMv+BPlDhxq4kmJCyJfgrIrHlX8sGPcPA4Y6Rwo0MSqYn3s\ng1Pu5gOKlaT9HKmE6wn5Sut6IiBjWozrRQ6n5h2RXNtO7O2qCDqjgB2vBxhV7B+z\nhRbLbCmW0tYMDsvPpX5M8fsO05svN+lKtCAuz1leFns8piZpptpSCFn7bWxiA9/f\nx5x17D7pfah3Sy2pA+NDXyzSlGcKdaUmwQIDAQAB\n-----END RSA PUBLIC KEY-----",
			"-----BEGIN RSA PUBLIC KEY-----\nMIIBCgKCAQEAwqjFW0pi4reKGbkc9pK83Eunwj/k0G8ZTioMMPbZmW99GivMibwa\nxDM9RDWabEMyUtGoQC2ZcDeLWRK3W8jMP6dnEKAlvLkDLfC4fXYHzFO5KHEqF06i\nqAqBdmI1iBGdQv/OQCBcbXIWCGDY2AsiqLhlGQfPOI7/vvKc188rTriocgUtoTUc\n/n/sIUzkgwTqRyvWYynWARWzQg0I9olLBBC2q5RQJJlnYXZwyTL3y9tdb7zOHkks\nWV9IMQmZmyZh/N7sMbGWQpt4NMchGpPGeJ2e5gHBjDnlIf2p1yZOYeUYrdbwcS0t\nUiggS4UeE8TzIuXFQxw7fzEIlmhIaq3FnwIDAQAB\n-----END RSA PUBLIC KEY-----"
			),
			'connection' => array(
				'ipv6' => false,
				'timeout' => 2,
				'proxy' => false,
				'dc' => 1
			),
			'datacenters' => array(
				array(
					'subdomain' => 'pluto'
				),
				array(
					'subdomain' => 'venus'
				),
				array(
					'subdomain' => 'aurora'
				),
				array(
					'subdomain' => 'vesta'
				),
				array(
					'subdomain' => 'flota'
				),
				array(
					'main' => true,
					'ipv4' => '149.154.175.50',
					'ipv6' => '2001:0b28:f23d:f001:0000:0000:0000:000a',
					'port' => 443
				),
				array(
					'main' => true,
					'ipv4' => '149.154.167.51',
					'ipv6' => '2001:067c:04e8:f002:0000:0000:0000:000a',
					'port' => 443
				),
				array(
					'main' => true,
					'ipv4' => '149.154.175.100',
					'ipv6' => '2001:0b28:f23d:f003:0000:0000:0000:000a',
					'port' => 443
				),
				array(
					'main' => true,
					'ipv4' => '149.154.167.91',
					'ipv6' => '2001:067c:04e8:f004:0000:0000:0000:000a',
					'port' => 443
				),
				array(
					'main' => true,
					'ipv4' => '149.154.171.5',
					'ipv6' => '2001:0b28:f23f:f005:0000:0000:0000:000a',
					'port' => 443
				),
				array(
					'main' => false,
					'ipv4' => '149.154.175.10',
					'ipv6' => '2001:0b28:f23d:f001:0000:0000:0000:000e',
					'port' => 443
				),
				array(
					'main' => false,
					'ipv4' => '149.154.167.40',
					'ipv6' => '2001:067c:04e8:f002:0000:0000:0000:000e',
					'port' => 443
				),
				array(
					'main' => false,
					'ipv4' => '149.154.175.117',
					'ipv6' => '2001:0b28:f23d:f003:0000:0000:0000:000e',
					'port' => 443
				)
			),
			'maxtries' => array(
				'connect' => 1
			),
			'app' => array(
				'id' => 6,
				'hash' => '',
				'device_model' => $model,
				'system_version' => $version,
				'app_version' => 'Unicorn',
				'lang' => $lang
			),
			'result_model' => 1,
			'elements' => array(
			)
		);
		$auto = isset($options['auto']) ? $options['auto'] : array();
		if(isset($options['auto']))unset($options['auto']);
		$settings = array_replace_recursive($settings, $options);
		$settings['dc'] = array();
		if(isset($settings['session']['file']) && file_exists($settings['session']['file']) && is_numeric($settings['session']['mode']))
			chmod($settings['session']['file'], $settings['session']['mode']);
		$this->settings = $settings;
		if($this->elements === array() && (!isset($auto['loadelements']) || $auto['loadelements']))$this->load_elements();
		if($this->dcs === array() && (!isset($auto['connect']) || $auto['connect']))$this->dc_connect('all');
	}

	// Data Center
	private $dcs = array();
	public function dc_connect($num = -1){
		if($num === 'all'){
			foreach($this->settings['datacenters'] as $num => $dc)
				$this->dc_connect($num);
			return true;
		}
		$dc = isset($this->settings['datacenters'][$num]) ? $this->settings['datacenters'][$num] : $this->settings['datacenters'][$this->settings['connection']['dc']];
		if(isset($dc['subdomain']))
			$address = array('tcp://' . $dc['subdomain'] . '.web.telegram.org', '/api');
		else
			$address = array(($dc['port'] === 443 ? 'ssl' : 'tcp') . '://' . (isset($dc['ipv6']) && $this->settings['connection']['ipv6'] ?
				'[' . $dc['ipv6'] . ']' : $dc['ipv4']), $dc['main'] ? '/apiw1' : '/apiw_test1');
		$c = 0;
		do{
			$ping = microtime(true);
			$socket = @fsockopen($address[0], isset($dc['port']) ? $dc['port'] : 80, $errno, $errstr, $this->settings['connection']['timeout']);
		}while(++$c < $this->settings['maxtries']['connect'] && $socket === false);
		if($socket === false)return false;
		$ping = microtime(true) - $ping;
		$dc['address'] = $address[0];
		$dc['path'] = $address[1];
		$dc['socket'] = $socket;
		$dc['tries'] = $c;
		$dc['ping'] = $ping;
		$this->dcs[$num] = $dc;
		return $num;
	}
}

function obfuscated2_create_random(&$crypted = 48384387897423){
	do{
		$random = xncrypt::randbytes(64);
	}while(in_array(substr($random, 0, 4), array('PVrG', 'GET ', 'POST', 'HEAD', "\xee\xee\xee\xee")) || $random[0] == "\xef" || substr($random, 4, 4) == "\0\0\0\0");
	$random[56] = $random[57] = $random[58] = $random[59] = "\xef";
	if($crypted !== 48384387897423)
		$crypted = substr_replace($random, substr(openssl_encrypt($random, 'aes-256-ctr', substr($random, 8, 32), 1, substr($random, 40, 16)), 56, 8), 56, 8);
	return $random;
}
function obfuscated2_get_info($random){
	$reversed = strrev(substr($random, 8, 48));
	return array(
		'algo' => 'aes-256-ctr',
		'encryption' => array(
			'key' => substr($random, 8, 32),
			'iv'  => substr($random, 40, 16)
		),
		'decryption' => array(
			'key' => substr($reversed, 0, 32),
			'iv'  => substr($reversed, 32, 16)
		)
	);
}
function obfuscated2_get_crypted($random){
	return substr_replace($random, substr(openssl_encrypt($random, 'aes-256-ctr', substr($random, 8, 32), 1, substr($random, 40, 16)), 56, 8), 56, 8);
}
function obfuscated2_socket_connect($socket, $crypted){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, $crypted);
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, $crypted);
	return false;
}
function tcpabridged_socket_connect($socket){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, "\xef");
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, "\xef");
	return false;
}
function tcpintermediate_socket_connect($socket){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, "\xee\xee\xee\xee");
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, "\xee\xee\xee\xee");
	return false;
}
function tcpabridged_write_message($socket, $message){
	$l = strlen($message) / 4;
	if($len < 127)
		$message = chr($l) . $message;
	else
		$message = chr(127) . substr(pack('V', $l), 0, 3) . $message;
	if(!is_resource($socket))
		return $message;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, $message);
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, $message);
	return false;
}
function obfuscated2_write_message($socket, $random, $message){
	$l = strlen($message) / 4;
	if($len < 127)
		$message = chr($l) . $message;
	else
		$message = chr(127) . substr(pack('V', $l), 0, 3) . $message;
	$message = openssl_encrypt($message, 'aes-256-ctr', substr($random, 8, 32), 1, substr($random, 40, 16));
	if(!is_resource($socket))
		return $message;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, $message);
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, $message);
	return false;
}
function tcpfull_write_message($socket, $message, $out_seq_no = 0){
	if($out_seq_no <= 0)$out_seq_no = 0;
	$message = pack('VV', strlen($message) + 12, $out_seq_no) . $message;
	$message.= strrev(hash('crc32b', $message, true));
	if(!is_resource($socket))
		return $message;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, $message);
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, $message);
	return false;
}
function tcpintermediate_write_message($socket, $message){
	$message = pack('V', strlen($message)) . $message;
	if(!is_resource($socket))
		return $message;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, $message);
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, $message);
	return false;
}
function tcpfull_read_message($socket, int &$in_seq_no = null){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		$pl = fread($socket, 4);
	elseif(get_resource_type($socket) == 'socket')
		$pl = socket_read($socket, 4);
	else return false;
	$l = array_value(unpack('V', $pl), 1);
	if(get_resource_type($socket) == 'stream')
		$p = fread($socket, $l - 4);
	elseif(get_resource_type($socket) == 'socket')
		$p = socket_read($socket, $l - 4);
	if(strrev(hash('crc32b', $pl . ($m = substr($p, 0, -4)), true)) !== substr($p, -4))
		new XNError('tcpfull_read_message', 'CRC32 was not correct!', XNError::WARNING, XNError::TTHROW);
	if(get_resource_type($socket) == 'stream')
		$in_seq_no = fread($socket, 4);
	elseif(get_resource_type($socket) == 'socket')
		$in_seq_no = socket_read($socket, 4);
	$in_seq_no = array_value(unpack('V', $in_seq_no), 1);
	return $m;
}
function tcpintermediate_read_message($socket){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fread($socket, array_value(unpack('V', fread($socket, 4)), 1));
	if(get_resource_type($socket) == 'socket')
		return socket_read($socket, array_value(unpack('V', socket_read($socket, 4)), 1));
	return false;
}
function tcpabridged_read_message($socket){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream'){
		$l = ord(fgetc($socket));
		return fread($l < 127 ? $l << 2 : array_value(unpack('V', fread($socket, 3) . "\0"), 1) << 2);
	}if(get_resource_type($socket) == 'socket'){
		$l = ord(socket_read($socket, 1));
		return socket_read($l < 127 ? $l << 2 : array_value(unpack('V', socket_read($socket, 3) . "\0"), 1) << 2);
	}return false;
}
function explodee($delimiters, $string, $limit = null){
	if($limit === null)$limit = PHP_INT_MAX;
	if(!isset($delimiters[1]) && isset($delimiters[0]))
		return explode($delimiters[0], $string, $limit);
	elseif(!isset($delimiters[0]) && !is_array($delimiters))
		return $string;
	elseif(!isset($delimiters[0]))
		$delimiters = array_values($delimiters);
	$arr = explode($delimiters[0], $string, $limit);
	unset($delimiters[0]);
	$delimiters = array_values($delimiters);
	foreach($arr as &$str)
		$str = explodee($delimiters, $str, $limit);
	return $arr;
}
function explodek($delimiters, $string, $limit = null){
	if($limit === null)$limit = PHP_INT_MAX;
	if(!isset($delimiters[1]) && isset($delimiters[0]))
		return array_keyval(explode($delimiters[0], $string, $limit));
	elseif(!isset($delimiters[0]) && !is_array($delimiters))
		return $string;
	elseif(!isset($delimiters[0]))
		$delimiters = array_values($delimiters);
	$arr = explode($delimiters[0], $string, $limit);
	unset($delimiters[0]);
	$delimiters = array_values($delimiters);
	foreach($arr as &$str)
		$str = explodek($delimiters, $str, $limit);
	return call_user_func_array('array_merge', $arr);
}
function revexplode($delimiter, $string, $limit = null){
	return array_reverse(array_map('strrev', explode(strrev($delimiter), strrev($string), $limit === null ? PHP_INT_MAX : $limit)));
}
function array_keyval($array, $ending = null){
	$arr = array();
	for($c = 0;isset($array[$c + 1]);)
		$arr[$array[$c++]] = $array[$c++];
	if($ending !== false && isset($array[$c]))
		$arr[] = $array[$c];
	return $arr;
}
function dump(){
	return call_user_func_array('var_dump', func_get_args());
}
function func_alias($from, $to){
	eval("function $to(){
		return call_user_func_array('$from', func_get_args());
	}");
}
if(!is_function('class_alias')){
	function class_alias($from, $to){
		eval("class $to extends $from {}");
	}
}
function array_key_alias(&$array, $key){
	foreach(array_slice(func_get_args(), 2) as $arg){
		if(isset($array[$arg]) && $array[$arg] !== null)
			$res = $array[$arg];
		unset($array[$arg]);
	}
	if(isset($res))
		$array[$key] = $res;
	else return false;
	return true;
}
function treelow_encode($str){
	$tree = str_split($str);
	foreach($tree as &$value){
		$value = xnmath::tree(ord($value));
		$value = array_merge(array(count($value)), $value);
	}
	$tree = call_user_func_array('array_merge', $tree);
	return implode('', array_map('chr', $tree));
}
function treelow_decode($str){
	$str = array_map('ord', str_split($str));
	$data = '';
	for($c = 0;isset($str[$c]);){
		$data .= chr(array_mul(array_slice($str, $c + 1, $str[$c])));
		$c += $str[$c] + 1;
	}
	return $data;
}
function array2_search($key, $array){
	foreach($array as $x)
		if(in_array($key, $x))
			return $x;
	return false;
}
function power_func($x, $n, $i, $f) {
	$y = $i;
	while(true) {
		if($n % 2 == 1)
			$y = $f($y, $x);
		$n = floor($n / 2);
		if($n == 0)
			break;
		$x = $f($x, $x);
	}
	return $y;
}
function implodet($gule, $pieces){
	return $pieces === array() ? $gule : $gule . implode($gule, $pieces) . $gule;
}
function explodet($delimiter, $string, $limit = null){
	if(substr($string, 0, $l = strlen($delimiter)) == $delimiter)
		$string = substr($string, $l);
	if(substr($string, -$l) == $delimiter)
		$string = substr($string, 0, -$l);
	return explode($delimiter, $string, $limit !== null ? $limit : PHP_INT_MAX);
}
function striml($str, $trimer){
	$l = strlen($trimer);
	while(substr($str, 0, $l) == $trimer)
		$str = substr($str, $l);
	return $str;
}
function strimr($str, $trimer){
	$l = strlen($trimer);
	while(substr($str, -$l) == $trimer)
		$str = substr($str, 0, $l);
	return $str;
}
function strim($str, $trimer){
	return strimr(striml($str, $trimer), $trimer);
}
function array_value($array, $key, $preserve = null){
	if(is_array($key)){
		$values = array();
		if($preserve === true)
			foreach($key as $i)
				$values[] = isset($array[$i]) ? $array[$i] : null;
		else
			foreach($key as $i)
				$values[$i] = isset($array[$i]) ? $array[$i] : null;
		return $values;
	}
	return isset($array[$key]) ? $array[$key] : null;
}
function array_key($array, $value, $offset = 0, $strict = null){
	return array_search($value, array_slice($array, $offset), $strict === true);
}
function array_val2key($array){
	return array_combine($array, array_keys($array));
}
function array_val2keys($array){
	$arr = array();
	$keys = array_keys($array);
	foreach(array_values($array) as $num => $value){
		if(!isset($arr[$value]))
			$arr[$value] = array($keys[$num]);
		else
			$arr[$value][] = $keys[$num];
	}
	return $arr;
}
function array_key_at($key, $array){
	return array_value(array_keys($array), $key);
}
function array_value_at($value, $array){
	return array_value(array_values($array), $value);
}
function array_key_of($key, $array){
	return array_search($key, array_keys($array));
}
function array_value_of($value, $array){
	return array_search($value, array_values($array));
}
function array_reverse_values($array){
	return array_combine(array_keys($array), array_reverse($array));
}
function array_reverse_keys($array){
	return array_combine(array_reverse(array_keys($array)), $array);
}
function implodes($array){
	return implode('', $array);
}
function pkcs1_generate_symmetric_key($password, $iv, $length, $raw = null){
	$key = '';
	$iv = substr($iv, 0, 8);
	while(!isset($key[$length - 1]))
		$key .= md5($key . $password . $iv, $raw !== false);
	return substr($key, 0, $length);
}
function putty_generate_symmetric_key($password, $length, $raw = null){
	$key = '';
	$seq = 0;
	while(!isset($key[$length - 1]))
		$key .= sha1(pack('Na*', $seq++, $password), $raw !== false);
	return substr($key, 0, $length);
}
define('DIRECTORY_NSEPARATOR', DIRECTORY_SEPARATOR == '/' ? '\\' : '/');
function absolute_file($file){
	if(($file[0] !== '/') && ($file[1] !== ':') && !in_array(substr($file, 0, 4), array('phar', 'http')))
		$file = getcwd() . '/' . $file;
	elseif(strpos($file, DIRECTORY_SEPARATOR) !== false && strpos($file, DIRECTORY_NSEPARATOR) !== false)
		return strtr(DIRECTORY_NSEPARATOR, DIRECTORY_SEPARATOR, $file);
	return $file;
}
function socket_write_title_header($socket, $method, $path, $http_version){
	if(!is_resource($socket))
		return false;
	$path = !isset($path[0]) || $path[0] != '/' ? '/' . $path : $path;
	$path = str_replace('%2F', '/', urlencode($path));
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, strtoupper($method) . ' ' . $path . ' ' . strtoupper($http_version) . "\r\n");
	elseif(get_resource_type($socket) == 'socket')
		return socket_write($socket, strtoupper($method) . ' ' . $path . ' ' . strtoupper($http_version) . "\r\n");
	return false;
}
function header_keyval_parse($key, $value){
	$key = ucwords(strtr(strtolower($key), ' _', '-'), '-');
	if(is_object($value))
		$value = (array)$value;
	if(is_array($value) && isset($value[0]) && is_array($value[0]))
		$value = implode('; ', array_map(function($x){
			return str_replace('; =', '; ', http_build_query($x, '=', '; '));
		}, $value)) . ';';
	elseif(is_array($value) && isset($value[0]))
		$value = implode('; ', $value) . ';';
	elseif(is_array($value))
		$value = '';
	elseif(is_bool($value))
		$value = $value ? 'true' : 'false';
	elseif(is_null($value))
		$value = '';
	return "$key: $value";
}
function socket_write_header($socket, $key, $value){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, header_keyval_parse($key, $value) . "\r\n");
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, header_keyval_parse($key, $value) . "\r\n");
	return false;
}
function socket_write_headers($socket, $headers){
	if(!is_resource($socket))
		return false;
	$res = 0;
	foreach($headers as $key=>$value)
		if(($r = socket_write_header($socket, $key, $value)) === false)
			return false;
		else $res += $r;
	return $res;
}
function socket_write_end_header($socket){
	if(!is_resource($socket))
		return false;
	if(get_resource_type($socket) == 'stream')
		return fwrite($socket, "\r\n");
	if(get_resource_type($socket) == 'socket')
		return socket_write($socket, "\r\n");
	return false;
}
function socket_write_connection($socket, $connection){
	return socket_write_header($socket, 'Connection', $connection);
}
function socket_write_host($socket, $host){
	return socket_write_header($socket, 'Host', $host);
}
function socket_write_origin($socket, $origin){
	return socket_write_header($socket, 'Origin', $origin);
}
function socket_write_content_type($socket, $length, $type){
	return socket_write_headers($socket, array(
		'Content-Type' => $type,
		'Content-Length' => $length
	));
}
function strposs($hystack, $needle, $offset = null, $limit = null){
	if($limit === null)$limit = PHP_INT_MAX;
	if($offset === null)$offset = 0;
	$pos = array();
	$l = strlen($needle);
	while(--$limit >= 0){
		$offset = strpos($hystack, $needle, $offset);
		if($offset === false)
			break;
		$pos[] = $offset;
		$offset += $l;
	}
	return $pos;
}
function strposmin($hystack, $needles, $offset = null){
	return min(array_map(function($needle)use($hystack, $offset){
		return strpos($hystack, $needle, $offset);
	}, $needles));
}
function strposmax($hystack, $needles, $offset = null){
	return max(array_map(function($needle)use($hystack, $offset){
		return strpos($hystack, $needle, $offset);
	}, $needles));
}
function strposl($hystack, $needle, $lastof, $offset = null){
	if($offset === null)$offset = 0;
	if($lastof === '')
		return strpos($hystack, $needle, $offset);
	$last = strpos($hystack, $lastof, $offset);
	if($last === false)
		return strpos($hystack, $needle, $offset);
	$nl = strlen($needle);
	$ll = strlen($lastof);
	$last += $ll;
	$offset -= $nl;
	do{
		$offset = strpos($hystack, $needle, $offset + $nl);
		if($offset === false)
			return false;
	}while($offset < $last);
	return $offset;
}
function strposll($hystack, $needle, $lastof, $lastlastof, $offset = null){
	if($offset === null)$offset = 0;
	if($lastof === '')
		return strpos($hystack, $needle, $offset);
	$last = strposl($hystack, $lastof, $lastlastof, $offset);
	if($last === false)
		return strpos($hystack, $needle, $offset);
	return strposl($hystack, $needle, $lastof, $last + strlen($lastlastof));
}
function strposn($hystack, $needle, $none, $offset = null, $right = null){
	if($offset === null)$offset = 0;
	if($none === '')
		return strpos($hystack, $needle, $offset);
	$ns = $right === true ? $needle . $none : $none . $needle;
	$nl = strlen($none);
	$dl = strlen($needle);
	$k = $needle == $none ? 1 : 0;
	$offset -= $dl;
	do{
		$np = strpos($hystack, $ns, $offset + $dl);
		if($np === false)
			return strpos($hystack, $needle, $offset + $dl + $k);
		$offset = strpos($hystack, $needle, $offset + $dl + $k);
		if($offset === false)
			return false;
	}while($offset == $np + $nl || ($needle == $none && $offset == $np));
	return $offset;
}
function strposnn($hystack, $needle, $none, $nonenone, $offset = null, $right = null){
	if($offset === null)$offset = 0;
	if($none === '')
		return strpos($hystack, $needle, $offset);
	$ns = $right === true ? $needle . $none : $none . $needle;
	$nl = strlen($none);
	$dl = strlen($needle);
	$offset -= $dl;
	do{
		$np = strposn($hystack, $none, $nonenone, $offset + $dl);
		if($np === false)
			return strpos($hystack, $needle, $offset + $dl);
		$offset = strpos($hystack, $needle, $offset + $dl);
		if($offset === false)
			return false;
	}while($offset == $np + $nl);
	return $offset;
}
function pregpos($pattern, $subject, $offset = null){
	if(!preg_match($pattern, $subject, $match, 0, $offset !== null ? $offset : 0))
		return false;
	return strpos($subject, $match[0], $offset);
}
function preg_test($pattern, $subject, array &$matches = array(), $flags = null){
	if(!preg_match($pattern, $subject, $match, $flags !== null ? $flags : 0))
		return false;
	if($subject == $match[0]){
		$matches = $match;
		return true;
	}
	$matches = array();
	return false;
}
function array2closure($array){
	return function($key)use($array){
		return $array[$key];
	};
}
function array_tree($array){
	$tree = array();
	$last = null;
	$now = &$tree;
	foreach($array as $x){
		if(!is_array($x)){
			return false;
		}
		foreach($x as $k=>$y){
			if(!isset($x[$k + 1])){
				if(!isset($now[$y]) && array_search($y, $now) === false)
					$now[] = $y;
			}else{
				if(!isset($now[$y])){
					if(($s = array_search($y, $now)) !== false)
						unset($now[$s]);
					$now[$y] = array();
				}
				$now = &$now[$y];
			}
		}
		$now = &$tree;
	}
	return $tree;
}
if(function_exists('dl')){
	function loadlib($n, $f = null) {
		return extension_loaded($n) || dl(((PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '') . ($f ? $f : $n) . '.' . PHP_SHLIB_SUFFIX);
	}
}
function set_memory_limit($limit = null){
	return ini_set('memory_limit', $limit !== null ? $limit : '256M');
}
function get_memory_limit(){
	$mem = ini_get('memory_limit');
	if($mem[-1] === 'M')$mem = substr($mem, 0, -1) * 1048576;
	return $mem + 0;
}
function get_time_limit(){
	return ini_get('max_execution_time');
}
function is_enable_dl(){
	return !ini_get('enable_dl');
}
function is_safe_mode(){
	return (bool)ini_get('safe_mode');
}
function get_extension_dir(){
	return ini_get('extension_dir');
}
function str_replace_loop($from, $to, $string){
	do {
		$prev = $string;
		$string = str_replace($from, $to, $string);
	}while($prev != $string);
	return $string;
}
class VideoCaption {
	public $info = array(), $frames = array(), $format = true;
	public function setFrame($from, $length, $caption){
		$this->frames[] = array($from, $from + $length, $caption);
	}
	public function append($from, $to, $caption){
		$this->frames[] = array($from, $to, $caption);
	}
	public function getFrame($time){
		foreach($this->frames as $frame)
			if($frame[0] <= $time && $frame[1] > $time)
				return array(
					'start' => $frame[0],
					'stop' => $frame[1],
					'caption' => $frame[2]
				);
		return null;
	}
	public function setZoom($zoom = null){
		if($zoom < 0)
			$zoom *= -1;
		foreach($this->frames as &$frame){
			$frame[0] *= $zoom;
			$frame[1] *= $zoom;
		}
	}
	public function setInfo($info, $content){
		$this->info[$info] = $content;
	}
	public function getInfo($info){
		return isset($this->info[$info]) ? $this->info[$info] : false;
	}
	public function TimeFormat($time, $srt = null){
		return str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . ':' .
			str_pad(floor($time / 60) % 60, 2, '0', STR_PAD_LEFT) . ':' .
			str_pad($time % 60, 2, '0', STR_PAD_LEFT) .
			($srt === true ? ',' : ($srt === null ? '.' : ':')) . floor(($time - floor($time)) * 1001);
	}
	public function TimeUnformat($time){
		if(strpos($time, ',') > 0)
			$time = explode(',', $time, 2);
		elseif(strpos($time, '.') > 0)
			$time = explode('.', $time, 2);
		else
			$time = explode(':', $time, 2);
		$i = $time[1];
		$time = explode(':', $time[0], 3);
		$time = $time[0] * 3600 + $time[1] * 60 + $time[2];
		return (float)"$time.$i";
	}
	private function FrameFormat($from, $to, $srt = null, $ctl = null){
		return self::TimeFormat($from, $srt) . ($ctl === null ? ' --> ' : ($ctl === true ? ',' : ':')) . self::TimeFormat($to, $srt);
	}
	private function FrameUnformat($time){
		if(strpos($time, ' --> ') > 0)
			$time = explode(' --> ', $time, 2);
		elseif(strpos($time, ',') > 0)
			$time = explode(',', $time, 2);
		else
			$time = explode(':', $time, 2);
		return array($this->TimeUnformat($time[0]), $this->TimeUnformat($time[1]));
	}
	private function StringFormat($string, $type = null){
		if(!$this->format)
			return $string;
		if($type === null || $type === 1)
			return str_replace("\n", "\r\n", $string);
		if($type == 2)
			return str_replace("\n", '[BR]', $string);
		if($type == 3)
			return str_replace_loop("\n\n", "\n", $string);
		if($type == 4)
			return str_replace("\n", '|', $string);
	}
	private function StringUnformat($string, $type = null){
		if(!$this->format)
			return $string;
		if($type === null || $type === 1)
			return str_replace("\r\n", "\n", $string);
		if($type == 2)
			return str_replace('[BR]', "\n", $string);
		if($type == 3)
			return $string;
		if($type == 4)
			return str_replace('|', "\n", $string);
	}

	public function toSRT($file = null){
		$caption = "\xef\xbb\xbf";
		$n = 0;
		foreach($this->frames as $frame)
			$caption .= (++$n) . "\r\n" . $this->FrameFormat($frame[0], $frame[1], true) . "\r\n" . $this->StringFormat($frame[2]) . "\r\n\r\n";
		if($file === null)
			return $caption;
		return file_put_contents($file, $caption);
	}
	public function isSRT($caption){
		return substr($caption, 0, 3) == "\xef\xbb\xbf" && substr($caption, 3, 6) != 'WEBVTT';
	}
	public function fromSRT($caption){
		if(!$this->isSRT($caption))
			return false;
		$caption = explode("\r\n\r\n", substr($caption, 3));
		$n = 0;
		for($k = 0;isset($caption[$k]);++$k){
			$line = $caption[$k];
			if($line === '')
				continue;
			$line = explode("\r\n", $line, 3);
			if(!isset($line[2])){
				$caption[$k + 1] = substr($caption[$k + 1], 1);
				$line[2] = '';
			}
			$time = $this->FrameUnformat($line[1]);
			$content = $this->StringUnformat($line[2]);
			$this->append($time[0], $time[1], $content);
		}
		return true;
	}
	public function fromSRTFile($file){
		$file = file_get_contents($file);
		if($file === false)
			return false;
		return $this->fromSRT($file);
	}

	public function toVTT($file = null){
		$caption = "\xef\xbb\xbfWEBVTT";
		foreach($this->frames as $frame)
			$caption .= "\n\n" . $this->FrameFormat($frame[0], $frame[1]) . "\n" . $this->StringFormat($frame[2], 3);
		if($file === null)
			return $caption;
		return file_put_contents($file, $caption);
	}
	public function isVTT($caption){
		return substr($caption, 0, 9) == "\xef\xbb\xbfWEBVTT";
	}
	public function fromVTT($caption){
		if(!$this->isVTT($caption))
			return false;
		$caption = explode("\n\n", substr($caption, 9));
		for($k = 0;isset($caption[$k]);++$k){
			$line = $caption[$k];
			if($line === '')
				continue;
			$line = explode("\n", $line, 2);
			$time = $this->FrameUnformat($line[0]);
			if(!isset($line[1])){
				$caption[$k + 1] = substr($caption[$k + 1], 1);
				$content = '';
			}else $content = $line[1];
			$this->setFrame($time[0], $time[1], $content);
		}
		return true;
	}
	public function fromVTTFile($file){
		$file = file_get_contents($file);
		if($file === false)
			return false;
		return $this->fromVTT($file);
	}

	public function toSUB2($file = null){
		$caption = "\xef\xbb\xbf";
		foreach($this->frames as $k => $frame){
			$caption .= '{' . floor($frame[0]) . '}{' . floor($frame[1]) . '}' . $this->StringFormat($frame[2], 4);
			if(isset($this->frames[$k + 1]))
				$caption .= "\n";
		}
		return $caption;
	}
	public function isSUB2($caption){
		return $caption == "\xef\xbb\xbf" || substr($caption, 0, 4) == "\xef\xbb\xbf{";
	}
	public function fromSUB2($caption){
		if(!$this->isSUB2($caption))
			return false;
		$caption = explode("\n", substr($caption, 3));
		foreach($caption as $line){
			if($line === '' || $line[0] != '{')
				continue;
			$line = explode('}{', substr($line, 1), 2);
			$time = array($line[0], substr($line[1], 0, $p = strpos($line[1], '}')));
			$content = substr($line[1], $p + 1);
			$this->setFrame($time[0], $time[1], $content);
		}
		return true;
	}
	public function fromSUB2File($file){
		$file = file_get_contents($file);
		if($file === false)
			return false;
		return $this->fromSUB2($file);
	}

	public function existsformat($format){
		return method_exists($this, 'is' . strtoupper($format));
	}
	public function isformat($format, $caption){
		if(!$this->existsformat($format))
			return null;
		return call_user_method('is' . strtoupper($format), $this, $caption);
	}
	public function toformat($format){
		if(!$this->existsformat($format))
			return null;
		return call_user_method('to' . strtoupper($format), $this);
	}
	public function fromformat($format, $caption){
		if(!$this->existsformat($format))
			return null;
		return call_user_method('from' . strtoupper($format), $this, $caption);
	}
	public function isfileformat($format, $file){
		if(!$this->existsformat($format))
			return null;
		$file = file_get_contents($file);
		if($file === false)
			return false;
		return call_user_method('is' . strtoupper($format), $this, $file);
	}
	public function tofileformat($format, $file){
		if(!$this->existsformat($format))
			return null;
		return call_user_method('to' . strtoupper($format), $this, $file);
	}
	public function fromfileformat($format, $caption){
		if(!$this->existsformat($format))
			return null;
		return call_user_method('from' . strtoupper($format) . 'File', $this, $caption);
	}

	public function getCaption($caption){
		if(file_exists($caption))
			$caption = file_get_contents($caption);
		if($this->isSRT($caption))
			return 'SRT';
		if($this->isVTT($caption))
			return 'VTT';
		if($this->isSUB2($caption))
			return 'SUB2';
		return false;
	}
	public function fromCaption($caption){
		if(file_exists($caption))
			$caption = file_get_contents($caption);
		return $this->fromformat($this->getCaption($caption), $caption);
	}
	public function toCaption($format, $file = null){
		if($file === null)
			return $this->tofileformat($format, $file);
		return $this->toformat($format);
	}
}
function vcaption_convert($from, $format, $to = null){
	$vc = new VideoCaption;
	if(!$vc->fromCaption($from))
		return false;
	return $vc->toCaption($format, $to);
}
function vcaption_get($caption){
	$tmp = new VideoCaption;
	return $tmp->getCaption($caption);
}
function compress_php_src($src){
	$IW = array(
		T_CONCAT_EQUAL,			 // .=
		T_DOUBLE_ARROW,			 // =>
		T_BOOLEAN_AND,			  // &&
		T_BOOLEAN_OR,			   // ||
		T_IS_EQUAL,				 // ==
		T_IS_NOT_EQUAL,			 // != or <>
		T_IS_SMALLER_OR_EQUAL,	  // <=
		T_IS_GREATER_OR_EQUAL,	  // >=
		T_INC,					  // ++
		T_DEC,					  // --
		T_PLUS_EQUAL,			   // +=
		T_MINUS_EQUAL,			  // -=
		T_MUL_EQUAL,				// *=
		T_DIV_EQUAL,				// /=
		T_IS_IDENTICAL,			 // ===
		T_IS_NOT_IDENTICAL,		 // !==
		T_DOUBLE_COLON,			 // ::
		T_PAAMAYIM_NEKUDOTAYIM,	 // ::
		T_OBJECT_OPERATOR,		  // ->
		T_DOLLAR_OPEN_CURLY_BRACES, // ${
		T_AND_EQUAL,				// &=
		T_MOD_EQUAL,				// %=
		T_XOR_EQUAL,				// ^=
		T_OR_EQUAL,				 // |=
		T_SL,					   // <<
		T_SR,					   // >>
		T_SL_EQUAL,				 // <<=
		T_SR_EQUAL,				 // >>=
	);
	if(is_file($src))
		if(!$src = file_get_contents($src))
			return false;
	$tokens = token_get_all($src);	
	$new = "";
	$c = sizeof($tokens);
	$iw = false; // ignore whitespace
	$ih = false; // in HEREDOC
	$ls = "";	// last sign
	$ot = null;  // open tag
	for($i = 0; $i < $c; $i++){
		$token = $tokens[$i];
		if(is_array($token)){
			list($tn, $ts) = $token; // tokens: number, string, line
			$tname = token_name($tn);
			if($tn == T_INLINE_HTML){
				$new .= $ts;
				$iw = false;
			}else{
				if($tn == T_OPEN_TAG){
					if(strpos($ts, " ") || strpos($ts, "\n") || strpos($ts, "\t") || strpos($ts, "\r"))
						$ts = rtrim($ts);
					$ts .= " ";
					$new .= $ts;
					$ot = T_OPEN_TAG;
					$iw = true;
				}elseif($tn == T_OPEN_TAG_WITH_ECHO){
					$new .= $ts;
					$ot = T_OPEN_TAG_WITH_ECHO;
					$iw = true;
				}elseif($tn == T_CLOSE_TAG){
					if($ot == T_OPEN_TAG_WITH_ECHO)
						$new = rtrim($new, "; ");
					else
						$ts = " ".$ts;
					$new .= $ts;
					$ot = null;
					$iw = false;
				}elseif(in_array($tn, $IW)){
					$new .= $ts;
					$iw = true;
				}elseif($tn == T_CONSTANT_ENCAPSED_STRING || $tn == T_ENCAPSED_AND_WHITESPACE){
					if($ts[0] == '"')
						$ts = addcslashes($ts, "\n\t\r");
					$new .= $ts;
					$iw = true;
				}elseif($tn == T_WHITESPACE){
					$nt = @$tokens[$i+1];
					if(!$iw && (!is_string($nt) || $nt == '$') && !in_array($nt[0], $IW))
						$new .= " ";
					$iw = false;
				}elseif($tn == T_START_HEREDOC){
					$new .= "<<<S\n";
					$iw = false;
					$ih = true; // in HEREDOC
				}elseif($tn == T_END_HEREDOC){
					$new .= "S;";
					$iw = true;
					$ih = false; // in HEREDOC
					for($j = $i+1; $j < $c; $j++) {
						if(is_string($tokens[$j]) && $tokens[$j] == ";"){
							$i = $j;
							break;
						}elseif($tokens[$j][0] == T_CLOSE_TAG)
							break;
					}
				}elseif($tn == T_COMMENT || $tn == T_DOC_COMMENT){
					$iw = true;
				}else{
					$new .= $ts;
					$iw = false;
				}
			}
			$ls = "";
		}else{
			if(($token != ";" && $token != ":") || $ls != $token) {
				$new .= $token;
				$ls = $token;
			}
			$iw = true;
		}
	}
	return $new;
}
function string_rand($string){
	return rand(0, strlen($string) - 1);
}
function string_random($string){
	return $string[rand(0, strlen($string) - 1)];
}
function string_randoms($string, $count){
	$random = '';
	$c = strlen($string) - 1;
	while(--$count >= 0)
		$random .= $string[rand(0, $c)];
	return $random;
}
function func_last_arg(){
	$args = array_value(array_value(debug_backtrace(1, 2), 1), 'args');
	return end($args);
}
function func_get_params($offset = null, $length = null){
	$trace = debug_backtrace(1, 2);
	if(!isset($trace[1])){
		trigger_error('func_get_params():  Called from the global scope - no function context', E_USER_WARNING);
		return;
	}
	return $length === null ? array_slice($trace[1]['args'], $offset === null ? 0 : $offset) :
		array_slice($trace[1]['args'], $offset === null ? 0 : $offset, $length);
}
class XNAPK {
	private $file, $icons, $content = array('ns' => array()), $xml, $length, $data, $manifest, $line, $dictionary;
	public function dictionary(){
		if($this->dictionary !== null)
			return $this->dictionary;
		return xncrypt::jsondecode(xndata('apk-dictionary'), true);
	}
	public function flush_dictionary(){
		if($this->dictionary !== null)
			return;
		$this->dictionary = xncrypt::jsondecode(xndata('apk-dictionary'), true);
	}
	public function __construct($file){
		if(!file_exists($file))
			new XNError('XNApk', "No such Apk file '$file'", XNError::WARNING, XNError::TTHROW);
		$this->file = new ZipArchive;
		if($this->file->open($file) !== true)
			new XNError('XNApk', "can not open Apk file '$file'", XNError::WARNING, XNError::TTHROW);
	}
	public function getApkArchive(){
		return $this->file;
	}
	public function parseAll(){
		$this->getIcons();
		$this->parseManifest();
	}
	public function getIcons(){
		if($this->icons !== null)
			return $this->icons;
		$files = array(
			'ic_launcher.png',
			'icon.png',
			'app_icon.png',
			'ic_launcher_auto_media.png',
			'ic_launcher_auto_messaging.png'
		);
		$paths = array(
			array('res/mipmap-xxxhdpi', 192),
			array('res/drawable-xxxhdpi', 192),
			array('res/drawable-xxhdpi', 144),
			array('res/mipmap-xxhdpi', 144),
			array('res/drawable-xxhdpi-v0', 144),
			array('res/drawable-xxhdpi-v1', 144),
			array('res/drawable-xxhdpi-v2', 144),
			array('res/drawable-xxhdpi-v3', 144),
			array('res/drawable-xxhdpi-v4', 144),
			array('res/drawable-xxhdpi-v5', 144),
			array('res/mipmap-xhdpi', 96),
			array('res/drawable-xhdpi', 96),
			array('res/drawable-xhdpi-v0', 96),
			array('res/drawable-xhdpi-v1', 96),
			array('res/drawable-xhdpi-v2', 96),
			array('res/drawable-xhdpi-v3', 96),
			array('res/drawable-xhdpi-v4', 96),
			array('res/drawable-xhdpi-v5', 96),
			array('res/mipmap-hdpi', 72),
			array('res/drawable-hdpi', 72),
			array('res/drawable-hdpi-v0', 72),
			array('res/drawable-hdpi-v1', 72),
			array('res/drawable-hdpi-v2', 72),
			array('res/drawable-hdpi-v3', 72),
			array('res/drawable-hdpi-v4', 72),
			array('res/drawable-hdpi-v5', 72),
			array('res/mipmap-mdpi', 48),
			array('res/drawable-mdpi', 48),
			array('res/drawable-mdpi-v0', 48),
			array('res/drawable-mdpi-v1', 48),
			array('res/drawable-mdpi-v2', 48),
			array('res/drawable-mdpi-v3', 48),
			array('res/drawable-mdpi-v4', 48),
			array('res/drawable-mdpi-v5', 48),
			array('res/drawable-ldpi', 36),
			array('res/drawable', 72),
		);
		$this->icons = array();
		foreach($paths as $path)
			foreach($files as $file){
				$file = $path[0] . '/' . $file;
				if(($get = $this->file->getFromName($file)) !== false)
					$this->icons[] = array(
						'path' => $file,
						'size' => $path[1],
						'icon' => $get
					);
			}
		return $this->icons;
	}
	public function existsIcons(){
		return $this->getIcons() !== array();
	}
	public function parseManifest(){
		if(is_array($this->manifest))
			return $this->manifest;
		$xml = $this->file->getFromName('AndroidManifest.xml');
		return $this->manifest = $parse = $this->parseXML($xml);
	}
	public function parseXML($xml){
		$this->data = new StdClass;
		$this->xml = $xml;
		$this->length = strlen($xml);
		$parse = $this->_parseXML();
		$this->xml =
		$this->length;
		return $parse;
	}
	private function _parseXML(){
		$type = array_value(unpack('V', substr($this->xml, 0, 4)), 1);
		$size = array_value(unpack('V', substr($this->xml, 4, 4)), 1);
		if($size < 8 || $size > $this->length)
			new XNError('parseXML', 'Block Size Error', XNError::WARNING, XNError::TTHROW);
		$left = $this->length - $size;
		$props = false;
		$o = 8;
		switch($type) {
			case 0x00080003:
				$props = array(
					'line' => 0,
					'tag'  => '<?xml version="1.0" encoding="utf-8"?>'
				);
			break;
			case 0x001C0001:
				$this->data->stringCount = array_value(unpack('V', substr($this->xml, $o, 4)), 1);
				$this->data->styleCount = array_value(unpack('V', substr($this->xml, $o + 4, 4)), 1);
				$strOffset = array_value(unpack('V', substr($this->xml, $o + 12, 4)), 1);
				$styOffset = array_value(unpack('V', substr($this->xml, $o + 16, 4)), 1);
				$o += 20;
				$strListOffset = $this->data->stringCount <= 0 ? null : unpack('V*', substr($this->xml, $o, $this->data->stringCount * 4));
				$o += $this->data->stringCount * 4;
				$styListOffset = $this->data->styleCount <= 0 ? null : unpack('V*', substr($this->xml, $o, $this->data->styleCount * 4));
				$o += $this->data->styleCount * 4;
				$this->data->stringTab = $this->data->stringCount > 0 ? $this->getStringTab($strOffset, $strListOffset) : array();
				$this->data->styleTab = $this->data->styleCount > 0 ? $this->getStringTab($styOffset, $styListOffset) : array();
				$o = $size;
			break;
			case 0x00080180:
				$count = $size / 4 - 2;
				$this->resourceIDs = $count <= 0 ? null : unpack('V*', substr($this->xml, $o, $count * 4));
				$o += $count * 4;
			break;
			case 0x00100100:
				$prefix = array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1);
				$uri = array_value(unpack('V', substr($this->xml, $o + 12, 4)), 1);
				$o += 16;
				if(empty($this->data->cur_ns)) {
					$this->data->cur_ns = array();
					$this->data->ns[] = &$this->data->cur_ns;
				}
				$this->data->cur_ns[$uri] = $prefix;
			break;
			case 0x00100101:
				$prefix = array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1);
				$uri = array_value(unpack('V', substr($this->xml, $o + 12, 4)), 1);
				$o += 16;
				if(empty($this->data->cur_ns)) break;
				unset($this->data->cur_ns[$uri]);
			break;
			case 0x00100102:
				$line = array_value(unpack('V', substr($this->xml, $o, 4)), 1);
				$o += 8;
				$attrs = array();
				$props = array(
					'line'  => $line,
					'ns'	=> $this->getNameSpace(array_value(unpack('V', substr($this->xml, $o, 4)), 1)),
					'name'  => $this->getString(array_value(unpack('V', substr($this->xml, $o + 4, 4)), 1)),
					'flag'  => array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1),
					'count' => array_value(unpack('v', substr($this->xml, $o + 12, 2)), 1),
					'id'	=> array_value(unpack('v', substr($this->xml, $o + 14, 2)), 1) - 1,
					'class' => array_value(unpack('v', substr($this->xml, $o + 16, 2)), 1) - 1,
					'style' => array_value(unpack('v', substr($this->xml, $o + 18, 2)), 1) - 1,
					'attrs' => &$attrs
				);
				$o += 20;
				$props['ns_name'] = $props['ns'] . $props['name'];
				for($i = 0; $i < $props['count']; $i++) {
					$a = array(
						'ns'	   => $this->getNameSpace(array_value(unpack('V', substr($this->xml, $o, 4)), 1)),
						'name'	   => $this->getString(array_value(unpack('V', substr($this->xml, $o + 4, 4)), 1)),
						'val_str'  => array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1),
						'val_type' => array_value(unpack('V', substr($this->xml, $o + 12, 4)), 1),
						'val_data' => array_value(unpack('V', substr($this->xml, $o + 16, 4)), 1)
					);
					$o += 20;
					$a['ns_name'] = $a['ns'] . $a['name'];
					$a['val_type'] >>= 24;
					$attrs[] = $a;
				}
				$tag = "<{$props['ns_name']}";
				foreach($this->data->cur_ns as $uri => $prefix) {
					$uri = $uri > -1 && $uri < $this->data->stringCount ? $this->data->stringTab[$uri] : '';
					$prefix = $prefix > -1 && $prefix < $this->data->stringCount ? $this->data->stringTab[$prefix] : '';
					$tag .= " xmlns:{$prefix}=\"{$uri}\"";
				}
				foreach($props['attrs'] as $a) {
					$tag .= " {$a['ns_name']}=\"" .
					$this->getAttributeValue($a) .
					'"';
				}
				$tag .= '>';
				$props['tag'] = $tag;
				unset($this->data->cur_ns);
				$this->data->cur_ns = array();
				$this->data->ns[] = &$this->data->cur_ns;
				$left = -1;
			break;
			case 0x00100103:
				$line = array_value(unpack('V', substr($this->xml, $o, 4)), 1);
				$props = array(
					'line' => $line,
					'ns'   => $this->getNameSpace(array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1)),
					'name' => $this->getString(array_value(unpack('V', substr($this->xml, $o + 12, 4)), 1))
				);
				$o += 16;
				$props['ns_name'] = $props['ns'] . $props['name'];
				$props['tag'] = "</{$props['ns_name']}>";
				if(count($this->data->ns) > 1) {
					array_pop($this->data->ns);
					unset($this->data->cur_ns);
					$this->data->cur_ns = array_pop($this->data->ns);
					$this->data->ns[] = &$this->data->cur_ns;
				}
			break;
			case 0x00100104:
				$props = array(
					'tag' => $this->getString(array_value(unpack('V', substr($this->xml, $o + 8, 4)), 1))
				);
				$o += 20;
			break;
			default:
				new XNError('parseXML', 'Block Type Error', XNError::WARNING, XNError::TTHROW);
		}
		$this->xml = substr($this->xml, $o);
		$this->length -= $o;
		$child = array();
		while($this->length > $left) {
			$c = $this->_parseXML();
			if($props && $c)
				$child[] = $c;
			if($left == -1 && $c['type'] == 0x00100103) {
				$left = $this->length;
				break;
			}
		}
		if($this->length != $left)
			new XNError('parseXML', 'Block Overflow Error', XNError::WARNING, XNError::TTHROW);
		if(!$props)
			return false;
		$props['type'] = $type;
		$props['size'] = $size;
		$props['child'] = $child;
		return $props;
	}
	private function getStringTab($base, $list) {
		$tab = array();
		foreach($list as $off) {
			$off+= $base;
			$len = array_value(unpack('v', substr($this->xml, $off, 2)), 1);
			$off+= 2;
			$mask= ($len >> 0x8) & 0xFF;
			$len = $len & 0xFF;
			if($len == $mask) {
				if($off + $len > $this->length)
					new XNError('getStringTab', 'String Table Overflow', XNError::WARNING, XNError::TTHROW);
				$tab[] = substr($this->xml, $off, $len);
			}else{
				if($off + $len * 2 > $this->length)
					new XNError('getStringTab', 'String Table Overflow', XNError::WARNING, XNError::TTHROW);
				$str = substr($this->xml, $off, $len * 2);
				$tab[] = mb_convert_encoding($str, 'UTF-8', 'UCS-2LE');
			}
		}
		return $tab;
	}
	private	function getNameSpace($uri) {
		for($i = count($this->data->ns); $i > 0;) {
			$ns = $this->data->ns[--$i];
			if(isset($ns[$uri])) {
				$ns = $ns[$uri] > -1 && $ns[$uri] < $this->data->stringCount ? $this->data->stringTab[$ns[$uri]] : '';
				if(!empty($ns))
					$ns .= ':';
				return $ns;
			}
		}
		return '';
	}
	private	function getString($id) {
		return $id > -1 && $id < $this->data->stringCount ? $this->data->stringTab[$id] : '';
	}
	public function getAttribute($path, $name) {
		$r = $this->getElement($path);
		if(is_null($r))return null;
		if(isset($r['attrs']))
			foreach($r['attrs'] as $a)
				if($a['ns_name'] == $name)
					return $this->getAttributeValue($a);
	}
	private function getAttributeValue($a) {
		$type = &$a['val_type'];
		$data = &$a['val_data'];
		switch($type) {
			case 3:
				return $a['val_str'] > -1 && $a['val_str'] < $this->data->stringCount ? $this->data->stringTab[$a['val_str']] : '';
			case 2:
				return sprintf('?%s%08X', ($data >> 24 == 1) ? 'android:' : '', $data);
			case 1:
				return sprintf('@%s%08X', ($data >> 24 == 1) ? 'android:' : '', $data);
			case 17:
				return sprintf('0x%08X', $data);
			case 18:
				return ($data != 0 ? 'true' : 'false');
			case 28:
			case 29:
			case 30:
			case 31:
				return sprintf('#%08X', $data);
			case 5:
				return xnmath::complex2float($data) . array_value(array("%", "%p", "", "", "", "", "", ""), $data & 15);
			case 6:
				return xnmath::complex2float($data) . array_value(array("%", "%p", "", "", "", "", "", ""), $data & 15);
			case 4:
				return xnmath::int2float($data);
		}
		if($type >= 16 && $type < 28)
			return (string)$data;
		return sprintf('<0x%X, type 0x%02X>', $data, $type);
	}
	private function getElement($path) {
		$ps = explode('/', $path);
		$r = $this->parseManifest();
		foreach($ps as $v) {
			if(preg_match('/([^\[]+)\[([0-9]+)\]$/', $v, $ms)) {
				$v = $ms[1];
				$off = $ms[2];
			} else
				$off = 0;
			foreach($r['child'] as $c) {
				if($c['type'] == 0x00100102 && $c['ns_name'] == $v) {
					if($off == 0) {
						$r = $c;
						continue 2;
					}
					else
						$off--;
				}
			}
			return null;
		}
		return $r;
	}
	public function decompaileXML($xml){
		if(strtolower(substr($xml, 0, 5)) == '<?xml')
			return $xml;
		return $this->getXML($this->parseXML($xml));
	}
	public function decompaileXMLFile($xml){
		$xml = $this->file->getFromName($xml);
		if($xml === false)
			return false;
		if(strtolower(substr($xml, 0, 5)) == '<?xml')
			return $xml;
		return $this->getXML($this->parseXML($xml));
	}
	public function getXML($node = null, $lv = -1) {
		$xml = '';
		if($lv == -1)
			$node = $this->parseManifest();
		if(!$node)
			return $xml;
		if($node['type'] == 0x00100103)$lv--;
		$xml = ($node['line'] == 0 || $node['line'] == $this->line) ? '' : "\n" . str_repeat('  ', $lv);
		$xml.= $node['tag'];
		$this->line = $node['line'];
		foreach($node['child'] as $c)
			$xml .= $this->getXML($c, $lv + 1);
		return trim($xml);
	}
	public function getAppName() {
		return $this->getAttribute('manifest/application', 'android:name');
	}
	public function getVersionName() {
		return $this->getAttribute('manifest', 'android:versionName');
	}
	public function getVersionCode() {
		return $this->getAttribute('manifest', 'android:versionCode');
	}
	public function getDebuggable() {
		return $this->getAttribute('manifest/application', 'android:debuggable') == 'true';
	}
	public function getAllowBackup() {
		return $this->getAttribute('manifest/application', 'android:allowBackup') == 'true';
	}
	public function getLargeHeap() {
		return $this->getAttribute('manifest/application', 'android:largeHeap') == 'true';
	}
	public function getPackageName() {
		return $this->getAttribute('manifest', 'package');
	}
	public function getUsesPermissionsDictionary() {
		$collection = array();
		$dictionary = $this->dictionary();
		$permissions = array();
		for($i = 0; true; ++$i) {
			$item = $this->getAttribute("manifest/uses-permission[{$i}]", 'android:name');
			if(!$item)break;
			$permission = isset($dictionary[$item]) ? isset($dictionary[$item]['description']) ? $dictionary[$item]['description'] : "" : "";
			$collection[$permission] = $permission !== '' ? isset($dictionary[$permission]) ? $dictionary[$permission] : '' : $dictionary;
		}
		return $collection;
	}
	public function getUsesPermissions() {
		$collection = array();
		$dictionary = $this->dictionary();
		for($i = 0; true; ++$i) {
			$item = $this->getAttribute("manifest/uses-permission[{$i}]", 'android:name');
			if(!$item)break;
			$collection[$item] = isset($dictionary[$item]) ? isset($dictionary[$item]['description']) ? $dictionary[$item]['description'] : "" : "";
		}
		return $collection;
	}
	public function hasUsePermission($permission){
		$permission = strtolower($permission);
		for($i = 0; true; ++$i) {
			$item = $this->getAttribute("manifest/uses-permission[{$i}]", 'android:name');
			if(!$item)break;
			if(strtolower($item) == $permission)
				return true;
		}
		return false;
	}
	public function getUsesFeature() {
		$collection = array();
		for($i = 0; true; $i += 1) {
			$item_name = $this->getAttribute("manifest/uses-feature[{$i}]", 'android:name');
			if(!$item_name) break;
			$item_requirement = $this->getAttribute("manifest/uses-feature[{$i}]", 'android:required');
			array_push($collection, array(
				"name"		=> $item_name,
				"is_required" => $item_requirement
			));
		}
		return $collection;
	}
	public function getUsesSDKMin() {
	  return $this->getAttribute('manifest/uses-sdk', 'android:minSdkVersion');
	}
	public function getUsesSDKTarget() {
	  return $this->getAttribute('manifest/uses-sdk', 'android:targetSdkVersion');
	}
	public function getApplicationMetaData(){
		$collection = array();
		for($i = 0; true; $i += 1) {
			$item_name = $this->getAttribute("manifest/application/meta-data[{$i}]", 'android:name');
			$item_value = $this->getAttribute("manifest/application/meta-data[{$i}]", 'android:value');
			if(!$item_name)break;
			if(!$item_value)
				$item_value = '';
			$collection[$item_name] = $item_value;
		}
		return $collection;
	}
}
function xml_beauty($xml){
	$xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
	$token   = strtok($xml, "\n");
	$result  = '';
	$pad	 = 0; 
	$matches = array();
	while($token !== false){
		if(preg_match('/.+<\/\w[^>]*>$/', $token, $matches))
			$indent = 0;
		elseif(preg_match('/^<\/\w/', $token, $matches)){
			$pad--;
			$indent = 0;
		}elseif(preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches))
			$indent = 1;
		else
			$indent = 0;
		$line	= str_pad($token, strlen($token) + $pad, ' ', STR_PAD_LEFT);
		$result .= $line . "\n";
		$token   = strtok("\n");
		$pad	+= $indent;
	}
	return $result;
}
function wss_secaccept($key, $magic = null){
	return sha1($key . ($magic ? $magic : '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'), true);
}
function wss_makekey(){
	return xncrypt::randbytes(20);
}
define("WSS_CONTINUATION", 0);
define("WSS_TEXT", 1);
define("WSS_BINARY", 2);
define("WSS_CLOSE", 8);
define("WSS_PING", 9);
define("WSS_PONG", 10);
define("WSS_BINARY_BLOB", "\x81");
define("WSS_BINARY_TEXT", "\x82");
function wss_encode($data, $opcode = 1, $masked = null, $final = null){
	$l = strlen($data);
	$head = (bool)$final ? '1' : '0';
	$head .= '000' . sprintf('%04b', $opcode);
	$head .= (bool)$masked ? '1' : '0';
	if($l > 65535) {
		$head .= decbin(127);
		$head .= sprintf('%064b', $l);
	}elseif($l > 125) {
		$head .= decbin(126);
		$head .= sprintf('%016b', $l);
	}else
		$head .= sprintf('%07b', $l);
	$frame = '';
	foreach(str_split($head, 8) as $binstr)
		$frame .= chr(bindec($binstr));
	$mask = '';
	if($masked) {
		for($i = 0;$i < 4;++$i)
			$mask .= chr(rand(0, 255));
		$frame .= $mask;
	}
	for($i = 0;$i < $l;++$i)
		$frame .= ($masked === true) ? $data[$i] ^ $mask[$i % 4] : $data[$i];
	return $frame;
}
function wss_write($socket, $data, $opcode = 1, $masked = null, $final = null){
	if(!is_resource($socket))
		throw new XNError('wss_write', 'Invalid socket', XNError::WARNING);
	return fwrite($socket, wss_encode($data, $opcode, $masked, $final));
}
function wss_receive($socket){
	if(!is_resource($socket))
		throw new XNError('wss_receive', 'Invalid socket', XNError::WARNING);
	$data = fread($socket, 2);
	if($data === false)
		throw new XNError('wss_receive', 'Could not receive data', XNError::WARNING);
	if(strlen($data) === 1)
		$data .= fgetc($socket);
	if($data === false || strlen($data) < 2)
		throw new XNError('wss_receive', 'Could not receive data', XNError::WARNING);
	$final = ord($data[0]) & 1 << 7;
	$rsv1 = ord($data[0]) & 1 << 6;
	$rsv2 = ord($data[0]) & 1 << 5;
	$rsv3 = ord($data[0]) & 1 << 4;
	$opcode = ord($data[0]) & 31;
	$masked = ord($data[1]) >> 7;
	$payload = '';
	$length = ord($data[1]) & 127;
	if($length > 125) {
		$temp = $length === 126 ? fread($socket, 2) : fread($socket, 8);
		if($temp === false)
			throw new XNError('wss_receive', 'Could not receive data', XNError::WARNING);
		$length = '';
		for($i = 0;$i < strlen($temp);++$i)
			$length .= sprintf('%08b', ord($temp[$i]));
		$length = bindec($length);
	}
	$mask = '';
	if($masked) {
		$mask = fread($socket, 4);
		if($mask === false)
			throw new XNError('wss_receive', 'Could not receive mask data', XNError::WARNING);
	}
	if($length > 0) {
		$temp = stream_get_contents($socket);
		if($masked)
			for($i = 0;$i < $length;++$i)
				$payload .= $temp[$i] ^ $mask[$i % 4];
		else
			$payload = $temp;
	}
	if($opcode === WSS_CLOSE)
		throw new XNError('wss_receive', 'Client disconnect', XNError::NETWORK);
	return $final ? $payload : $payload . wss_receive($socket);
}
function preg_string($pattern, $subject, $flags = null, $offset = 0){
	preg_match_all($pattern, $subject, $matches, $flags, $offset);
	return implode('', $matches[0]);
}
function preg_range_list($list){
	if($list[0] == '^'){
		$not = true;
		$list = substr($list, 1);
	}else
		$not = false;
	$list = preg_replace_callback("/\\\\\\\\|\\\\-|(?:.|\n)-(?:.|\n)/", function($range){
		if($range[0] == '\\\\')
			return '\\';
		if($range[0] == '\\-')
			return '-';
		return implode('', range($range[0][0], $range[0][2]));
	}, $list);
	if($not)
		$list = xnstring::xor_chars(xnstring::ASCII_RANGE, $list);
	return str_split($list);
}
function preg_range_repeat($list, $from = 0, $to = null){
	if($to === null)$to = count($list);
	if($from < 0 || $to < 0)return false;
	if($to < $from)$to = 0;
	$ranges = array();
	while($from++ <= $to){
		if($from == 1)
			continue;
		$range = $list;
		for($i = 1;$i < $from - 1;++$i){
			$arr = array();
			foreach($range as $r)
				foreach($list as $c)
					$arr[] = $r . $c;
			$range = $arr;
		}
		$ranges[] = $range;
	}
	if($ranges === array())
		return array();
	return call_user_func_array('array_merge', $ranges);
}
function preg_range($pattern){
	if($pattern === '')
		return array();
	if(in_array($pattern[0], array('/', '#', '|')) && ($p = strrpos($pattern, $pattern[0])) !== 0){
		$flags = substr($pattern, $p);
		$pattern = substr($pattern, 1, $p - 1);
	}else $flags = '';
	$i = strpos($flags, 'i') !== false;
	$range = array('');
	preg_replace_callback("/(?:\[(?:\\\]|[^\]])+\]|(?<x>\((?:\g<x>|\\\\\)|\[(?:\\\]|[^\]])+\]|[^\)])*\))|".
		"(?:\\\\\\\\|\\\\[0-7]{1,3}|\\\\x[0-9a-fA-F]{1,2}|\\\\b[01]{1,8}|\\\\u[0-9a-fA-F]{1,4}|\\\\[^x0-9bnrtveu]|".
		"\\\\.|.|\s))(?:\{(?:[0-9]+|[0-9]+,[0-9]+|,[0-9]+|[0-9]+,)\}|)|".
		"\|(?:.|\n)*|\+(?:.|\n)*|\*(?:.|\n)*|\^(?:.|\n)*/", function($block)use(&$range, $i){
		$block = $block[0];
		switch($block){
			case '\\\\':
				$list = array('\\');
			break;
			case '\"':
				$list = array('"');
			break;
			case "\\'":
				$list = array("'");
			break;
			case '\n':
				$list = array("\n");
			break;
			case '\r':
				$list = array("\r");
			break;
			case '\t':
				$list = array("\t");
			break;
			case '\e':
				$list = array("\e");
			break;
			case '\v':
				$list = array("\v");
			break;
			case '\f':
				$list = array("\f");
			break;
			case '\s':
				$list = array(' ', "\n", "\r", "\t");
			break;
			case '\S':
				$list = str_split(str_replace(array(' ', "\n", "\r", "\t"), '', xnstring::ASCII_RANGE));
			break;
			case '\d':
				$list = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
			break;
			case '\D':
				$list = str_split(str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), '', xnstring::ASCII_RANGE));
			break;
			case '\w':
				$list = str_split(xnstring::WORD_RANGE);
			break;
			case '\W':
				$list = str_split(str_replace(str_split(xnstring::WORD_RANGE), '', xnstring::ASCII_RANGE));
			break;
			case '.':
				$list = ASCII_CHARS();
			break;
			case '\R':
				$list = array("\r\n");
			break;
			case '\h':
				$list = array(' ', "\t");
			break;
			case '\H':
				$list = str_split(str_replace(array(' ', "\t"), '', xnstring::ASCII_RANGE));
			break;
			case '\K':
				$range = array('');
			break;
			case '\L':
				$list = str_split(xnstring::LOWER_RANGE);
			break;
			case '\U':
				$list = str_split(xnstring::UPPER_RANGE);
			break;
			case '[[:alnum:]]':
				$list = str_split(xnstring::WORD_RANGE);
			break;
			case '[[:alpha:]]':
				$list = str_split(xnstring::ALPHBA_RANGE);
			break;
			case '[[:ascii:]]':
				$list = ASCII_CHARS();
			break;
			case '[[:blank:]]':
				$list = array(' ', "\t");
			break;
			case '[[:cntrl:]]':
				$list = str_split(xnstring::CONTROL_RANGE);
			break;
			default:
				switch($block[0]){
					case '\\':
						$p = strpos($block, '{');
						if(is_numeric($block[1]))
							$list = array(chr(octdec(substr($block, 1, $p === false ? strlen($block) - 1 : $p))));
						elseif($block[1] == 'x')
							$list = array(chr(hexdec(substr($block, 2, $p === false ? strlen($block) - 2 : $p))));
						elseif($block[1] == 'b')
							$list = array(chr(bindec(substr($block, 2, $p === false ? strlen($block) - 2 : $p))));
						elseif($block[1] == 'u')
							$list = array(xncrypt::jsondecode('"' . substr($block, 0, $p === false ? strlen($block) : $p) . '"'));
						else $list = array($block[1]);
					break;
					case '|':
						$range = array_merge($range, preg_range(substr($block, 1)));
					break;
					case '+':
						$list = preg_range(substr($block, 1));
						$arr = array();
						foreach($range as $r)
							foreach($list as $c)
								$arr[] = $r . $c;
						$range = array_merge($range, $arr);
					break;
					case '*':
						$list = preg_range(substr($block, 1));
						$arr = array();
						foreach($range as $r)
							foreach($list as $c)
								$arr[] = $r . $c;
						$range = array_merge($range, $list, $arr);
					break;
					case '^':
						$list = preg_range(substr($block, 1));
						$arr = array();
						foreach($range as $r)
							if(array_search($list, $r) === false)
								$arr[] = $r;
						$range = $arr;
					break;
					case '[':
						if($block[strlen($block) - 1] == '}'){
							$p = strrpos($block, ']');
							$repeat = explode(',', substr($block, $p + 2, -1), 2);
							$block = substr($block, 1, $p - 1);
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = null;
							else
								$repeat[1] = (int)$repeat[1];
						}else{
							$block = substr($block, 1, -1);
							$repeat = array(1, 1);
						}
						$list = preg_range_repeat(preg_range_list($block), $repeat[0], $repeat[1]);
						$arr = array();
						foreach($range as $r)
							foreach($list as $c)
								$arr[] = $r . $c;
						$range = $arr;
					return '';
					case '(':
						if($block[strlen($block) - 1] == '}'){
							$p = strrpos($block, ')');
							$repeat = explode(',', substr($block, $p + 2, -1), 2);
							$block = substr($block, 1, $p - 1);
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = null;
							else
								$repeat[1] = (int)$repeat[1];
						}else{
							$block = substr($block, 1, -1);
							$repeat = array(1, 1);
						}
						$list = preg_range_repeat(preg_range($block), $repeat[0], $repeat[1]);
						$arr = array();
						foreach($range as $r)
							foreach($list as $c)
								$arr[] = $r . $c;
						$range = $arr;
					return '';
					default:
						if(isset($block[3]) && $block[1] == '{'){
							$repeat = explode(',', substr($block, 2, -1));
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = null;
							else
								$repeat[1] = (int)$repeat[1];
						}else
							$repeat = array(1, 1);
						$block = $i ? array_unique(array(strtolower($block), strtoupper($block))) : array($block);
						$list = preg_range_repeat($block, $repeat[0], $repeat[1]);
						$arr = array();
						foreach($range as $r)
							foreach($list as $c)
								$arr[] = $r . $c;
						$range = $arr;
					return '';
				}
		}
		if(isset($list)){
			if($block[strlen($block) - 1] == '}'){
				$p = strrpos($block, '{');
				$repeat = explode(',', substr($block, $p + 1, -1));
				if($repeat[0] === '')
					$repeat[0] = 0;
				else $repeat[0] = (int)$repeat[0];
				if(!isset($repeat[1]))
					$repeat[1] = $repeat[0];
				elseif($repeat[1] === '')
					$repeat[1] = null;
				else
					$repeat[1] = (int)$repeat[1];
			}else
				$repeat = array(1, 1);
			$list = preg_range_repeat($list, $repeat[0], $repeat[1]);
			$arr = array();
			foreach($range as $r)
				foreach($list as $c)
					$arr[] = $r . $c;
			$range = $arr;
		}
		return '';
	}, $pattern);
	if($range === array(''))
		return array();
	return $range;
}
function preg_rand($pattern){
	if($pattern === '')
		return array();
	if(in_array($pattern[0], array('/', '#', '|')) && ($p = strrpos($pattern, $pattern[0])) !== 0){
		$flags = substr($pattern, $p);
		$pattern = substr($pattern, 1, $p - 1);
	}else $flags = '';
	$i = strpos($flags, 'i') !== false;
	$rand = '';
	preg_replace_callback("/(?:\[(?:\\\]|[^\]])+\]|(?<x>\((?:\g<x>\\\\\)|\[(?:\\\]|[^\]])+\]|[^\)])*\))|".
		"(?:\\\\\\\\|\\\\[0-7]{1,3}|\\\\x[0-9a-fA-F]{1,2}|\\\\b[01]{1,8}|\\\\u[0-9a-fA-F]{1,4}|\\\\[^x0-9bnrtveu]|".
		"\\\\.|.|\s))(?:\{(?:[0-9]+|[0-9]+,[0-9]+|,[0-9]+|[0-9]+,)\}|)|".
		"\|(?:.|\n)*|\+(?:.|\n)*|\*(?:.|\n)*/", function($block)use(&$rand, $i){
		$block = $block[0];
		switch($block){
			case '\\\\':
				$list = array('\\');
			break;
			case '\"':
				$list = array('"');
			break;
			case "\\'":
				$list = array("'");
			break;
			case '\n':
				$list = array("\n");
			break;
			case '\r':
				$list = array("\r");
			break;
			case '\t':
				$list = array("\t");
			break;
			case '\e':
				$list = array("\e");
			break;
			case '\v':
				$list = array("\v");
			break;
			case '\f':
				$list = array("\f");
			break;
			case '\s':
				$list = array(' ', "\n", "\r", "\t");
			break;
			case '\S':
				$list = str_split(str_replace(array(' ', "\n", "\r", "\t"), '', xnstring::ASCII_RANGE));
			break;
			case '\d':
				$list = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
			break;
			case '\D':
				$list = str_split(str_replace(array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'), '', xnstring::ASCII_RANGE));
			break;
			case '\w':
				$list = str_split(xnstring::WORD_RANGE);
			break;
			case '\W':
				$list = str_split(str_replace(str_split(xnstring::WORD_RANGE), '', xnstring::ASCII_RANGE));
			break;
			case '.':
				$list = ASCII_CHARS();
			break;
			case '\R':
				$list = array("\r\n");
			break;
			case '\h':
				$list = array(' ', "\t");
			break;
			case '\H':
				$list = str_split(str_replace(array(' ', "\t"), '', xnstring::ASCII_RANGE));
			break;
			case '\K':
				$range = array('');
			break;
			case '\L':
				$list = str_split(xnstring::LOWER_RANGE);
			break;
			case '\U':
				$list = str_split(xnstring::UPPER_RANGE);
			break;
			case '[[:alnum:]]':
				$list = str_split(xnstring::WORD_RANGE);
			break;
			case '[[:alpha:]]':
				$list = str_split(xnstring::ALPHBA_RANGE);
			break;
			case '[[:ascii:]]':
				$list = ASCII_CHARS();
			break;
			case '[[:blank:]]':
				$list = array(' ', "\t");
			break;
			case '[[:cntrl:]]':
				$list = str_split(xnstring::CONTROL_RANGE);
			break;
			default:
				switch($block[0]){
					case '\\':
						$p = strpos($block, '{');
						if(is_numeric($block[1]))
							$list = array(chr(octdec(substr($block, 1, $p === false ? strlen($block) - 1 : $p))));
						elseif($block[1] == 'x')
							$list = array(chr(hexdec(substr($block, 2, $p === false ? strlen($block) - 2 : $p))));
						elseif($block[1] == 'b')
							$list = array(chr(bindec(substr($block, 2, $p === false ? strlen($block) - 2 : $p))));
						elseif($block[1] == 'u')
							$list = array(xncrypt::jsondecode('"' . substr($block, 0, $p === false ? strlen($block) : $p) . '"'));
						else $list = array($block[1]);
					break;
					case '|':
						$rand .= preg_rand(substr($block, 1));
					break;
					case '+':
						if(rand(0, 1) === 1)
							$rand .= preg_rand(substr($block, 1));
					break;
					case '*':
						switch(rand(0, 2)){
							case 0:
								$rand = preg_rand(substr($block, 1));
							break;
							case 1:
								$rand.= preg_rand(substr($block, 1));
						}
					break;
					case '[':
						if($block[strlen($block) - 1] == '}'){
							$p = strrpos($block, ']');
							$repeat = explode(',', substr($block, $p + 2, -1), 2);
							$block = substr($block, 1, $p - 1);
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = null;
							else
								$repeat[1] = (int)$repeat[1];
						}else{
							$block = substr($block, 1, -1);
							$repeat = array(1, 1);
						}
						$list = preg_range_list($block);
						$l = count($list);
						if($repeat[1] === null)$repeat[1] = $l;
						$l = floor(rand($repeat[0] * $l, $repeat[1] * $l) / $l);
						for($c = 0;$c++ < $l;)
							$rand .= $list[array_rand($list)];
					return '';
					case '(':
						if($block[strlen($block) - 1] == '}'){
							$p = strrpos($block, ')');
							$repeat = explode(',', substr($block, $p + 2, -1), 2);
							$block = substr($block, 1, $p - 1);
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = 1;
							else
								$repeat[1] = (int)$repeat[1];
						}else{
							$block = substr($block, 1, -1);
							$repeat = array(1, 1);
						}
						$l = rand($repeat[0], $repeat[1]);
						for($c = 0;$c++ < $l;)
							$rand .= preg_rand($block);
					return '';
					default:
						if(isset($block[3]) && $block[1] == '{'){
							$repeat = explode(',', substr($block, 2, -1));
							if($repeat[0] === '')
								$repeat[0] = 0;
							else $repeat[0] = (int)$repeat[0];
							if(!isset($repeat[1]))
								$repeat[1] = $repeat[0];
							elseif($repeat[1] === '')
								$repeat[1] = 1;
							else
								$repeat[1] = (int)$repeat[1];
						}else
							$repeat = array(1, 1);
						$block = $i ? array_unique(array(strtolower($block), strtoupper($block))) : array($block);
						$l = rand($repeat[0], $repeat[1]);
						for($c = 0;$c++ < $l;)
							$rand .= $block[array_rand($block)];
					return '';
				}
		}
		if(isset($list)){
			if($block[strlen($block) - 1] == '}'){
				$p = strrpos($block, '{');
				$repeat = explode(',', substr($block, $p + 1, -1));
				if($repeat[0] === '')
					$repeat[0] = 0;
				else $repeat[0] = (int)$repeat[0];
				if(!isset($repeat[1]))
					$repeat[1] = $repeat[0];
				elseif($repeat[1] === '')
					$repeat[1] = null;
				else
					$repeat[1] = (int)$repeat[1];
			}else
				$repeat = array(1, 1);
			$list = preg_range_repeat($list, $repeat[0], $repeat[1]);
			$arr = array();
			foreach($range as $r)
				foreach($list as $c)
					$arr[] = $r . $c;
			$range = $arr;
		}
		return '';
	}, $pattern);
	$rand = preg_split("/(?<!\\\\)\|/", $rand);
	return $rand[array_rand($rand)];
}
function get_timeout_time(){
	return (isset(xnlib::$requestTime) ? xnlib::$requestTime : __xnlib_data::$startTime) + ini_get('max_execution_time');
}
define('M_DEG', M_PI / 180);
define('M_RAD', 180 / M_PI);
function show_errors($type = null){
	if($type === null)$type = E_USER_NOTICE;
	ob_start();
	trigger_error('', $type);
	return (bool)ob_get_clean();
}
define('MINECRAFTPE_SERVER_PORT', 19132);
define('MINECRAFTPC_SERVER_PORT', 25565);
function minecraft_get_challenge($socket){
	if(!is_stream($socket))return false;
	$command = "\xfe\xfd\x9\1\2\3\4";
	fwrite($socket, $command, 7);
	$data = fread($socket, 4096);
	if(strlen($data) < 5 || $data[0] != $command[2])
		return false;
	return (int)substr($data, 5, -1);
}
function minecraft_get_status($socket, $challenge = null){
	if(!is_stream($socket))return false;
	if($challenge === null)$challenge = pack('N', minecraft_get_challenge($socket));
	if($challenge === false)return false;
	$command = "\xfe\xfd\0\1\2\3\4$challenge\0\0\0\0";
	fwrite($socket, $command);
	$data = fread($socket, 4096);
	if(strlen($data) < 5 || $data[0] != $command[2])
		return false;
	$last = '';
	$info = array();
	$data = substr($data, 11);
	$data = explode("\x00\x00\x01player_\x00\x00", $data);
	$players = substr($data[1], 0, -2);
	$data	= explode("\0", $data[0]);
	$keys = array(
		'hostname'   => 'hostname',
		'hostip'	 => 'hostip',
		'hostport'   => 'hostport',
		'version'	=> 'version',
		'plugins'	=> 'plugins',
		'gametype'   => 'gametype',
		'game_id'	=> 'gamename',
		'numplayers' => 'numplayers',
		'maxplayers' => 'maxplayers',
		'map'		=> 'map',
		'whitelist' => 'whitelist'
	);
	foreach($data as $key => $val){
		if(~$key & 1){
			if(!array_key_exists($val, $keys)){
				$last = false;
				continue;
			}
			$info[$last = $keys[$val]] = '';
		}elseif($last !== false)
			$info[$last] = mb_convert_encoding($val, 'UTF-8');
	}
	$info['numplayers']	 = (int)$info['numplayers'];
	$info['maxplayers' ] = (int)$info['maxplayers'];
	$info['hostport' ]   = (int)$info['hostport'];
	if($info['version'][0] == 'v')
		$info['version'] = substr($info['version'], 1);
	if(isset($info['whitelist']))
		$info['whitelist'] = $info['whitelist'] == 'on';
	if($info['plugins']){
		$data = explode(': ', $info['plugins'], 2);
		$info['rawplugins'] = $info['plugins'];
		$info['software']   = $data[0];
		if(count($data) == 2)
			$info['plugins'] = explode('; ', $data[1]);
	}else $info['software'] = 'Vanilla';
	if(!empty($players))
		$info['players'] = explode("\0", $players);
	return $info;
}
define('VEC_ZIROBIT_LEFT', 1);
define('VEC_ZIROBIT_RIGHT', 2);
define('VEC_FLOORBIT_LEFT', 3);
define('VEC_FLOORBIT_RIGHT', 4);
define('VEC_BITS', 5);
function vec($input, $from = null, $length = null, $replacement = null, $zirobit = null){
	if($from === null)$from = strlen($input) * 8;
	elseif($from < 0)$from += strlen($input) * 8;
	if($length === null)$length = strlen($input) * 8;
	elseif($length < 0)$length += strlen($input) * 8;
	if($replacement === null){
		$input = substr(xncrypt::binencode(substr($input, $x = floor($from / 8), -floor(-($length + $from) / 8))), $from - $x, $length);
		return bindec($input);
	}
	$input = xncrypt::binencode($input);
	if(is_int($replacement))
		$input = substr_replace($input, str_pad(decbin($replacement), $length, '0', STR_PAD_LEFT), $from, $length);
	elseif(is_bool($replacement))
		$input = substr_replace($input, '', $from, $length);
	else $input = substr_replace($input, xncrypt::binencode($replacement), $from, $length);
	if($zirobit == 5)return $input;
	$l = strlen($input) % 8;
	if($l === 0)return xncrypt::bindecode($input);
	if($zirobit == 2)
		return xncrypt::bindecode($input . str_repeat('0', 8 - $l));
	if($zirobit == 3)
		return xncrypt::bindecode(substr($input, $l));
	if($zirobit == 4)
		return xncrypt::bindecode(substr($input, 0, -$l));
	return xncrypt::bindecode(str_repeat('0', 8 - $l) . $input);
}
function is_passed_key($key, $array){
	if(!isset($array[$key]))return false;
	return (bool)preg_match(is_numeric($key) ? '/\[(?:' . floor($key) . "|\\\"$key\\\")\]=>\n  &/" :
		'/\[' . preg_quote(unce($key), '/') . "\]=>\n  &/", var_read($array));
}
function unrepeat($input, $limit = 0){
	if($limit < 0)$limit = 0;
	$lim = $limit === 0 ? 1 : $limit;
	$l = strlen($input);
	$output = '';
	while($input !== ''){
		for($i = floor($l / 2); $i >= $lim; --$i){
			if(substr($input, 0, $i) == substr($input, $i, $i)){
				$input = substr_replace($input, '', $i, $i);
				++$i;
			}
		}
		if($limit === 0)return $input;
		$output .= $input[0];
		$input = substr($input, 1);
	}
	return $output;
}

// ---------- XN Mathology ---------- //
class XNMath {
	const PI = 3.1415926535898;
	const PHI = 1.6180339887498;
	const G = 9.80665;
	const E = 2.718281828459;
	const AVOGADRO = 6.0221415E23;
	public static function average(){
		$nums = func_get_args();
		$c = count($nums);
		return array_add($nums) / $c;
	}
	public static function averagesqrt(){
		$nums = func_get_args();
		$c = count($nums);
		return pow(array_mul($nums), 1 / $c);
	}
	public static function pre($x, $y){
		return $x === 0 ? 0 : 100 / ($y / $x);
	}
	public static function map($a, $b, $c, $d, $e){
		if($b == $c)
			return $b;
		return ($a / ($c - $b)) * ($e - $d) + $d;
	}
	public static function fact($n){
		$n = (int)$n;
		$r = 1;
		if($n >= 171)return INF;
		while($n > 0)
			$r*= $n--;
		return $r;
	}
	public static function gcd($a, $b){
		return $b > 0 ? self::gcd($b, $a % $b) : $a;
	}
	public static function lcm($a, $b){
		return $a * $b / self::gcd($a, $b);
	}
	public static function infdiv($a, $b){
		if($a == 0 && $b == 0)return 0;
		if($b == 0)return INF;
		return $a / $b;
	}
	public static function floord($a, $x){
		if($a == floor($a))
			return $a;
		return floor($a) + substr($a - floor($a), 0, $x + 2);
	}
	public static function factors($x){
		if($x == 0)return array(INF);
		$r = array();
		$y = sqrt(($x = $x < 0 ? -$x : $x));
		for($c = 1; $c <= $y; ++$c)
		if($x % $c == 0) {
			$r[] = $c;
			if($c != $y)$r[] = $x / $c;
		}
		sort($r);
		return $r;
	}
	public static function discriminant($a, $b, $c){
		return pow($b, 2) - (4 * $a * $c);
	}
	public static function native($x){
		if($x < 0)$x = -$x;
		if($x == 0)return 0;
		$y = (int)sqrt($x);
		for($c = 2; $c <= $y; ++$c)
		if($x % $c == 0)return $c;
		return $x;
	}
	public static function natives($x){
		if($x < 0)$x = -$x;
		if($x == 0)return array(0);
		$r = array();
		for($c = 1; $c <= $x; ++$c)
		if($x % $c == 0)$r[] = $c;
		return $r;
	}
	public static function tree($x){
		if($x == 0)return array(0);
		$r = array($l = self::native($x));
		while(($x /= $l) > 1)$r[] = $l = self::native($x);
		return $r;
	}
	public static function nominal($x, $y){
		return (pow(($x + 1), (1 / $y)) - 1) * $y;
	}
	public static function pnan($x){
		if($x == 0)return array();
		$a = array(1);
		for($c = 2;$c < $x;++$c)
			if(self::gcd($x,$c) == 1)
				$a[] = $c;
		return $a;
	}
	public static function isprime($x){
		if($x < 0)$x = -$x;
		if($x == 0 || $x == 1)return false;
		$y = (int)sqrt($x);
		for($c = 2; $c <= $y; ++$c)
		if($x % $c == 0)return false;
		return true;
	}
	public static function pnprime($x){
		if($x < 0){
			$a = array();
			for($c = 2; $c < $x; ++$c)
				if(self::isprime($c))
					$a[] = -$c;
			return $a;
		}
		$a = array();
		for($c = 2; $c < $x; ++$c)
			if(self::isprime($c))
				$a[] = $c;
		return $a;
	}
	public static function prand($x = -0xffff, $y = 0xffff){
		if($y < $x)swap($x, $y);
		$r = rand($x, $y);
		for($i = 0; true; ++$i)
			if($r - $i >= $x){
				if(self::isprime($r - $i))
					return $r - $i;
			}elseif($r + $i <= $y){
				if(self::isprime($r + $i))
					return $r + $i;
			}else return false;
	}
	public static function nearprime($r){
		for($i = 0; true; ++$i)
			if(self::isprime($r - $i))
				return $r - $i;
			elseif(self::isprime($r + $i))
				return $r + $i;
	}
	public static function prevprime($x){
		while(--$x)
			if(self::isprime($x))
				return $x;
	}
	public static function nextprime($x){
		while(++$x)
			if(self::isprime($x))
				return $x;
	}
	public static function cprime($x){
		$a = 0;
		for($c = 2;$c < $x;++$c)
			if(self::isprime($c))
				++$a;
		return $a;
	}
	public static function phi($x){
		if($x == 0)return 0;
		$n = 1;
		for($c = 2; $c < $x; ++$c)
			if(self::gcd($x, $c) == 1)
				++$n;
		return $n;
	}
	public static function nphi($x){
		if($x == 0)return 0;
		for($c = 2; $c < $x; ++$c)
			if(self::gcd($x, $c) == 1)
				return $c;
		return false;
	}
	public static function pnphi($x){
		if($x == 0)return 0;
		$n = array();
		for($c = 2; $c < $x; ++$c)
			if(self::gcd($x, $c) == 1)
				$n[] = $c;
		return $n;
	}
	public static function umod($a, $b){
		$a %= $b;
		return $a < 0 ? $a + ($b < 0 ? -$b : $b) : $a;
	}
	public static function fumod($a, $b){
		$a -= floor($a / $b);
		return $a < 0 ? $a + ($b < 0 ? -$b : $b) : $a;
	}
	public static function distpositions($x1, $y1, $x2, $y2){
		return rad2deg(acos((sin(deg2rad($x1)) * sin(deg2rad($x2))) + (cos(deg2rad($x1)) * cos(deg2rad($x2)) * cos(deg2rad($y1 - $y2))))) * 111189.57696;
	}
	public static function distpoints($x1, $y1, $x2, $y2){
		return hypot($x2 - $x1, $y2 - $y1);
	}
	public static function onebits($x){
		if($x == 0)return 0;
		if($x < 0)$x = -$x;
		$y = 0;
		$l = floor(log($x, 2));
		while($l > 0)
			$y += ($x >> $l--) & 1;
		return $y + 1;
	}
	public static function zerobits($x){
		if($x == 0)return 1;
		if($x < 0)$x = -$x;
		$y = 0;
		$c = $l = floor(log($x, 2));
		while($l > 0)
			$y += ($x >> $l--) & 1;
		return $c - $y + 1;
	}
	public static function bitscount($x){
		return strlen(decbin($x < 0 ? -$x : $x));
	}
	public static function brev($bin, $len = null){
		if($bin === 0)return 0;
		$clone = $bin;
		$bin = 0;
		$count = 0;
		if($len === null)
			while($clone > 0) {
				++$count;
				$bin <<= 1;
				$bin |= $clone & 0x1;
				$clone >>= 1;
			}
		else
			while($count < $len) {
				++$count;
				$bin <<= 1;
				$bin |= $clone & 0x1;
				$clone >>= 1;
			}
		return (int)$bin;
	}
	public static function bin($x){
		$l = strlen(decbin(PHP_INT_MAX));
		return $x < 0 ? '1' . xnbinary::neg(str_pad(decbin(~$x), $l, '0', STR_PAD_LEFT)) : str_pad(decbin($x), $l, '0', STR_PAD_LEFT);
	}
	public static function unbin($x){
		if($x === '' || !is_binary($x))return false;
		return $x[0] === '1' ? ~bindec(xnbinary::neg(substr($x, 1))) : bindec(substr($x, 1));
	}
	public static function neg($x){
		return bindec(xnbinary::neg(decbin($x)));
	}
	public static function isneg($x){
		$x = (string)$x;
		return $x[0] === '-';
	}
	public static function bmax($x){
		return (1 << strlen(decbin($x < 0 ? -$x : $x))) - 1;
	}
	public static function res($x, $y){
		return $y | ($x ^ $y);
	}
	public static function bset($number, $pos, $b = null){
		return (((($number >> ($pos + 1)) << 1) | ($b !== false ? 1 : 0)) << $pos) | ($number & ((1 << $pos) - 1));
	}
	public static function bget($number, $pos){
		return (($number >> $pos) & 1) === 1;
	}
	public static function bdel($number, $pos){
		return (($number >> ($pos + 1)) << $pos) | ($number & ((1 << $pos) - 1));
	}
	public static function bappend($number, $pos, $b = null){
		return (((($number >> $pos) << 1) | ($b !== false ? 1 : 0)) << $pos) | ($number & ((1 << $pos) - 1));
	}
	public static function bswap($number, $p1, $p2){
		list($p1, $p2) = array(min($p1, $p2), max($p1, $p2));
		return self::bset(self::bset($number, $p1, (($number >> $p2) & 1) === 1), $p2, (($number >> $p1) & 1) === 1);
	}
	public static function bneg($number, $pos){
		return self::bset($number, $pos, (($number >> $pos) & 1) === 0);
	}
	public static function blad($number, $pos){
		$l = strlen(decbin($number < 0 ? -$number : $number));
		return ($number >> $pos) | (($number & ((1 << $pos) - 1)) << ($l - $pos));
	}
	public static function bsub($number, $start, $length = null){
		return $length === null ? $number >> $start : ($number >> $start) & ((1 << $length) - 1);
	}
	public static function bsubdel($number, $start, $length = null){
		return $length === null ? $number & ((1 << $start) - 1) : $number & ((1 << $start) - 1) | (($number >> ($start + $length)) << $start);
	}
	public static function brep($number, $to, $start, $length = null){
		if($length === 0)$length = strlen(decbin($to < 0 ? -$to : $to));
		$l = strlen(decbin($number < 0 ? -$number : $number));
		return $length === null ? ($number & ((1 << $start) - 1)) | ($to << $start) : ($number & ((1 << $start) - 1)) |
			(($to & ((1 << $length) - 1)) << $start) | (($number & ((1 << ($l - $start - $length)) - 1)) << ($start + $length));
	}
	public static function bput($number, $to, $start, $length = null){
		$tl = strlen(decbin($to < 0 ? -$to : $to));
		if($length === 0)$length = $tl;
		$l = strlen(decbin($number < 0 ? -$number : $number));
		return $length === null ? ($number & ((1 << $start) - 1)) | ($to << $start) | (($number & ((1 << ($l - $start)) - 1)) << ($start + $tl)) :
			($number & ((1 << $start) - 1)) | (($to & ((1 << $length) - 1)) << $start) | (($number & ((1 << ($l - $start - $length)) - 1)) << $start);
	}
	public static function wrev($number){
		return self::unbin(array_map('strrev', str_split(self::bit($number), 2)));
	}
	public static function clamp($x, $y, $z){
		return max($y, min($x, $z));
	}
	public static function revbits($x){
		return base_convert(strrev(base_convert($x, 10, 2)), 2, 10);
	}
	public static function number2ascii($x){
		$x = base_convert($x,10,16);
		if(strlen($x) % 2 == 1)$x = '0'.$x;
		return hex2bin($x);
	}
	public static function ascii2number($x){
		return base_convert(bin2hex($x), 16, 10);
	}
	public static function heightpos($x1, $y1, $x2, $y2){
		return hypot($x2 - $x1, $y2 - $y1);
	}
	public static function digitsadd($x){
		if(strlen($x = floor($x)) === 1)return $x;
		return self::digitsadd(array_mul(str_split($x)));
	}
	public static function decimals($x){
		return array_value((float)explode('.', $x . '.0', 3), 1);
	}
	public static function decimal($x){
		return $x < 0 ? $x ^ -1 : $x;
	}
	public static function bezier($x, $y, $z){
		return (((1 - 3 * $z + 3 * $y) * $x + (3 * $z - 6 * $y)) * $x + 3 * $y) * $x;
	}
	public static function slope($x, $y, $z){
		return 3 * (1 - 3 * $z + 3 * $y) * $x * $x + 5 * ($z - 2 * $y) * $x + 3 * $y;
	}
	public static function treeadd($x){
		return array_add(self::tree($x));
	}
	public static function hypot($x, $y, $d = 90){
		return sqrt(pow($x, 2) + pow($y, 2) - 2 * $x * $y * cos(deg2rad($d)));
	}
	public static function shl64($x, $shift){
		return ($x << $shift) | (($x >> (64 - $shift)) & ((1 << $shift) - 1));
	}
	public static function shrl($x, $shift){
		$y = floor(log($x, 2));
		while(--$shift >= 0 && $y > 0)
			if(($z = 2 << --$y) <= $x)
				$x -= $z;
		return $x;
	}
	public static function shrlt($x, $shift){
		$y = floor(log($x, 2));
		while($shift > 0 && $y > 0)
			if(($z = 2 << --$y) <= $x){
				$x -= $z;
				--$shift;
			}
		return $x;
	}
	public static function nmod($x, $y){
		if($x % $y === 0)
			return 0;
		return $y - $x % $y;
	}
	public function littleEndianWord($arr, $off){
		return (($arr[$off + 3] << 24 & 0xff000000 | $arr[$off + 2] << 16 & 0xff0000 | $arr[$off + 1] << 8 & 0xff00 | $arr[$off] & 0xFF)
			<< ((PHP_INT_SIZE - 4) << 3)) >> ((PHP_INT_SIZE - 4) << 3);
	}
	public function littleEndianShort($arr, $off){
		return (($arr[$off + 1] << 8 & 0xff00 | $arr[$off] & 0xFF) << ((PHP_INT_SIZE - 2) << 3)) >> ((PHP_INT_SIZE - 2) << 3);
	}
	public function int2float($v) {
		$x = ($v & ((1 << 23) - 1)) + (1 << 23) * ($v >> 31 | 1);
		$exp = ($v >> 23 & 0xFF) - 127;
		return $x * pow(2, $exp - 23);
	}
	public function complex2float($data){
		return (float)($data & 0xFFFFFF00) * array_value(array(0.00390625, 3.051758E-005, 1.192093E-007, 4.656613E-010), ($data>>4) & 3);
	}
	public function rl64($x, $shift){
		return ($x << $shift) | (($x >> (64 - $shift)) & ((1 << $shift) - 1));
	}
	public static function rl32($x, $shift){
		if($shift < 32)
			list($hi, $lo) = $x;
		else {
			$shift-= 32;
			list($lo, $hi) = $x;
		}
		return array(
			($hi << $shift) | (($lo >> (32 - $shift)) & (1 << $shift) - 1),
			($lo << $shift) | (($hi >> (32 - $shift)) & (1 << $shift) - 1)
		);
	}
	public static function shru($a, $b){
		return $b == 0 ? $a : ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ($b - 1));
	}
	public static function datdc($num){
		if(strpos($num, '.') === false)return 0;
		$e = array_value(explode('.', $num, 2), 1);
		return $e == 0 ? 0 : strlen($e);
	}
	public static function datget($num){
		if(strpos($num, '.') === false)return 0;
		$e = array_value(explode('.', $num, 2), 1);
		return $e == 0 ? 0 : $e + 0;
	}
	public static function triangle($number, $pow = 2){
		if($pow === 0)return 1;
		elseif($pow < 0)return 1 / self::triangle($number, -$pow);
		$n = $number;
		for($i = 1;$i < $pow;++$i)
			$n *= $number + $i;
		return $n / self::fact($pow);
	}
	public static function shrrr($a, $b){
		return ($a & 0xffffffff) >> ($b & 0x1f);
	}
	public static function shrr($a, $b){
		return ($a & 0x80000000 ? $a | 0xffffffff00000000 : $v & 0xffffffff) >> ($b & 0x1f);
	}
	public static function shll($a, $b){
		return ($t = ($a & 0xffffffff) << ($b & 0x1f)) & 0x80000000 ? $t | 0xffffffff00000000 : $t & 0xffffffff;
	}
	public static function safeint($x){
		if(is_int($x) || (php_uname('m') & "\xDF\xDF\xDF") != 'ARM')
			return $x;
		return (fmod($x, 0x80000000) & 0x7FFFFFFF) | ((fmod(floor($x / 0x80000000), 2) & 1) << 31);
	}
	public static function rtr($x, $y){
		$l = strlen(decbin($x < 0 ? -$x : $x));
		return ($x >> $y) | (($x & ((1 << $y) - 1)) << $l - $y);
	}
	public static function rtl($x, $y){
		$l = strlen(decbin($x < 0 ? -$x : $x));
		return (($x & ((1 << $l - $y) - 1)) << $y) | ($x >> $l - $y);
	}
	public static function zerotrim($x){
		if($x == 0)return 0;
		while(($x & 1) === 0)$x >>= 1;
		return $x;
	}
	public static function onetrim($x){
		while(($x & 1) === 1)$x >>= 1;
		return $x;
	}
	public static function tentrim($x){
		if($x === 0)return 0;
		while(fmod($x, 10) === 0.0)$x /= 10;
		return $x;
	}
	public static function twotrim($x){
		if($x === 0)return 0;
		while(fmod($x, 2) === 0.0)$x /= 2;
		return $x;
	}
	public static function mdsrem($a, $b){
		for($i = 0; $i < 8; ++$i) {
			$t = 0xff & ($b >> 24);
			$b = ($b << 8) | (0xff & ($a >> 24));
			$a <<= 8;
			$u = $t << 1;
			if($t & 0x80)$u ^= 0x14d;
			$b ^= $t ^ ($u << 16);
			$u ^= 0x7fffffff & ($t >> 1);
			if($t & 0x01)$u ^= 0xa6;
			$b ^= ($u << 24) | ($u << 8);
		}
		return array(
			0xff & $b >> 24,
			0xff & $b >> 16,
			0xff & $b >>  8,
			0xff & $b
		);
	}

	// random functions
	public static function randfloat($min = 0, $max = 1){
		$log = pow(10, max(self::datdc($min), self::datdc($max)));
		return xncrypt::randint($min * $log, $max * $log) / $log;
	}
	public static function randint($min = 0, $max = 1){
		return xncrypt::randint(floor($min) & PHP_INT_MIN, floor($max) & PHP_INT_MAX);
	}
	public static function randattract($min = 0, $max = 1, $attr = 0){
		if($max < $min)swap($min, $max);
		if($attr === 0)return self::randfloat($min, $max);
		$res = self::randfloat($min, $max);
		if($attr < 0){
			while(++$attr <= 0)
				$res = self::randfloat($min, $res);
			return $res;
		}while(--$attr >= 0)
			$res = self::randfloat($res, $max);
		return $res;
	}
	public static function randzoom($min = 0, $max = 1, $zoom = 0, $attr = 0){
		$res = self::randfloat($min, $max);
		if($attr === 0)return $res;
		return self::randattract($res < $zoom ? $min : $max, $zoom, $attr);
	}
	public static function randattractzoom($min = 0, $max = 1, $zoom = 0, $attr = 0){
		$res = self::randattract($min, $max, $attr);
		if($attr === 0)return $res;
		return self::randattract($res < $zoom ? $min : $max, $zoom, $attr);
	}
	public static function randbool($attr = 0){
		if($attr === 0)return rand(0, 1) === 1;
		if($attr < 0)return rand(0, -$attr) === 0;
		return rand(0, $attr) !== 0;
	}
	public static function randround($x, $attr = 1){
		$res = 0;
		while($x >= $attr){
			$res += rand(0, $attr * 2);
			$x -= $attr;
		}
		$res += rand(0, $x);
		return $res;
	}

	// base functions
	public static function baseconvert($text, $from = false, $to = false){
		if(is_string($from) && strtolower($from) == "ascii")return self::baseconvert(bin2hex($text), xnstring::HEX_RANGE, $to);
		if(is_string($to) && strtolower($to) == "ascii"){
			$r = self::baseconvert($text, $from, xnstring::HEX_RANGE);
			if(strlen($r) % 2 == 1)$r = '0'.$r;
			return hex2bin($r);
		}
		$text = (string)$text;
		if(!is_array($from))$fromel = str_split($from);
		else $fromel = $from;
		if($from == $to)return $text;
		$frome = array();
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
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/+=';
		$from = strtolower($from) == "ascii" ? "ascii" : substr($chars, 0, $from);
		$to = strtolower($to) == "ascii" ? "ascii" : substr($chars, 0, $to);
		$to = $to == "0123456789" ? false : $to;
		$from = $from == "0123456789" ? false : $from;
		return self::baseconvert($str, $from, $to);
	}
	public static function strdec($str){
		return hexdec(xncrypt::hexencode($str));
	}
	public static function decstr($dec){
		return xncrypt::hexdecode(dechex($dec));
	}

	// calc function
	private static function calcarg($calc, $offset = null){
		preg_match('/(?:(?:(?<x>\((?:\g<x>|\\\\\(|\\\\\)|[^\)])*\))|\\\\\"|[^\"])*\"|[^,])+/', $calc, $match, 0, $offset === null ? 0 : $offset);
		return isset($match[0]) ? self::calc($match[0]) : 0;
	}
	public static function calc($calc){
		$calc = preg_replace_callback('/\"(?:\\\\\\\\|\\\\\"|[^\"])*\"|\'[+-<>\[\]]*\'|(?:0|0o|o)[0-7]+|(?:0x|x)[0-9a-f]+|(?:0b|b)[01]+|\.[0-9]+|[0-9]+\./i', function($x){
			if($x[0][-1] == '.')return substr($x[0], 0, -1);
			switch($x[0][0]){
				case '.':
					return '0' . $x[0];
				case '"':
					return self::strdec(substr($x[0], 1, -1));
				case "'":
					return self::strdec(brainfuck::run(substr($x[0], 1, -1) . '.'));
				case 'o':
					return octdec(substr($x[0], 1));
				case 'x':
					return hexdec(substr($x[0], 1));
				case 'b':
					return bindec(substr($x[0], 1));
				case '0':
					switch($x[0][1]){
						case 'o':
							return octdec(substr($x[0], 2));
						case 'x':
							return hexdec(substr($x[0], 2));
						case 'b':
							return bindec(substr($x[0], 2));
						default:
							return octdec(substr($x[0], 1));
					}
			}
		}, $calc);
		$calc = str_replace(array('<=>', ' ', "\n", "\r", "\t"), array('^', '', '', '', ''), $calc);
		do{
			$calc = str_replace(array('--', '-+', '+-', '++'), array('+', '-', '-', '+'), $prev = $calc);
		}while($prev != $calc);
		$calc = preg_replace('/(?!<[a-zA-Z])([0-9])(\(|\[|[a-zA-Z])/', '$1*$2', $calc);
		do{
			$end = isset($calc[-1]) ? $calc[-1] : '';
			if($end === '+' || $end === '-')
				$calc .= '1';
			$calc = preg_replace_callback('/(?<![a-zA-Z])\((?:\\\\\(|\\\\\)|\"(?:\\\\|\\\"|[^\"])*\"|[^\(\)])*\)/', function($x){
				return self::calc(substr($x[0], 1, -1));
			}, $prev = $calc);
			$calc = preg_replace_callback('/(?<![a-zA-Z])\[(?:\\\\\[|\\\\\]|\"(?:\\\\|\\\"|[^\"])*\"|[^\[\]])*\]/', function($x){
				return floor(self::calc(substr($x[0], 1, -1), 0));
			}, $calc);
			$calc = preg_replace_callback('/(abs|acos|acosh|asin|asinh|atan|atan2|atanh|base|ceil|cos|cot|csc|deg|exp|expm1|floor|fmod|fumod|hypot|lcg|log|log10|log1p|max|min|pi|phi|rad|rand|round|sec|sin|sinh|sqrt|tan|tanh)(?:(?<x>\((?:\g<x>|\\\\\(|\\\\\)|[^\)])*\))|(?:\g<x>|\\\\\(|\\\\\)|[^\)])*)/', function($x)use($precision){
				$args = substr($x[2], 1, -1);
				switch($x[1]){
					case 'abs':
						return abs(self::calcarg($args));
					case 'acos':
						return acos(self::calcarg($args));
					case 'acosh':
						return acosh(self::calcarg($args));
					case 'asin':
						return asin(self::calcarg($args));
					case 'asinh':
						return asinh(self::calcarg($args));
					case 'atan':
						return atan(self::calcarg($args));
					case 'atan2':
						$arg = self::calcarg($args);
						return atan2($arg, self::calcarg($args, strlen($arg) + 1));
					case 'atanh':
						return atanh(self::calcarg($args));
					case 'base':
						$arg1 = self::calcarg($args);
						$len  = strlen($arg1) + 1;
						$arg2 = self::calcarg($args, $len);
						$arg3 = self::calcarg($args, strlen($arg2) + $len + 1);
						if($arg2 === '')$arg2 = '10';
						if($arg3 === '')$arg3 = '10';
						return base_convert($arg1, $arg2, $arg3);
					case 'ceil':
						return ceil(self::calcarg($args));
					case 'cos':
						return cos(self::calcarg($args));
					case 'cot':
						return 1 / tan(self::calcarg($args));
					case 'csc':
						return 1 / cos(self::calcarg($args));
					case 'deg':
						return rad2deg(self::calcarg($args));
					case 'exp':
						return exp(self::calcarg($args));
					case 'expm1':
						return expm1(self::calcarg($args));
					case 'floor':
						return floor(self::calcarg($args));
					case 'fmod':
						return self::fmod(self::calcarg($args));
					case 'fumod':
						return self::fumod(self::calcarg($args));
					case 'hypot':
						$arg = self::calcarg($args);
						return hypot($arg, self::calcarg($args, strlen($arg) + 1), $precision);
					case 'lcg':
						return lcg_value();
					case 'log':
						$arg = self::calcarg($args);
						return log($arg, self::calcarg($args, strlen($arg) + 1));
					case 'ln':
						return log(self::calcarg($args));
					case 'max':
					case 'min':
						$arg = array();
						$now = self::calcarg($args);
						$len = strlen($now) + 1;
						while($len !== 1){
							$arg[] = $now;
							$now = self::calcarg($args, $len);
							$len += strlen($now) + 1;
						}
						return call_user_func_array($x[1], $arg);
					case 'nmod':
						return self::nmod(self::calcarg($args));
					case 'pi':
						return M_PI;
					case 'phi':
						return self::PHI;
					case 'rad':
						return deg2rad(self::calcarg($args));
					case 'rand':
						$arg = self::calcarg($args);
						return rand($arg, self::calcarg($args, strlen($arg) + 1));
					case 'round':
						return round(self::calcarg($args));
					case 'sec':
						return 1 / sin(self::calcarg($args));
					case 'sin':
						return sin(self::calcarg($args));
					case 'sinh':
						return sinh(self::calcarg($args));
					case 'sqrt':
						return sqrt(self::calcarg($args));
					case 'tan':
						return tan(self::calcarg($args));
					case 'tanh':
						return tanh(self::calcarg($args));
					case 'umod':
						return self::umod(self::calcarg($args));
				}
			}, $calc);
			foreach(array(
				array(1, '~'),
				array(1, '\*\*', '\*\/', '\*%'),
				array(1, '\*', '\/', '%'),
				array(1, '\+', '-'),
				array(1, '_'),
				array(1, '>>', '<<', '<>>', '<<>', '<>'),
				array(1, '&', '\|', '^', '=>', '=<'),
				array(2, '!', '~'),
				array(3, '!', '~'),
				array(1, '&&', '\|\|', '==', '!=', '<=', '>=', '<', '>'),
			) as $signs){
				$regex = implode('|', array_slice($signs, 1));
				switch($signs[0]){
					case 1:
						$calc = preg_replace_callback("/(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)($regex)(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)/", function($x)use($precision){
							switch($x[2]){
								case '~':
									return rand((int)$x[1], (int)$x[3]);
								case '**':
									return pow((float)$x[1], (float)$x[3]);
								case '*/':
									return pow((float)$x[1], 1 / (float)$x[3]);
								case '*%':
									return pow((float)$x[1], (int)$x[3]);
								case '*':
									return ((float)$x[1]) * ((float)$x[3]);
								case '/':
									return ((float)$x[1]) / ((float)$x[3]);
								case '%':
									return ((float)$x[1]) % ((float)$x[3]);
								case '+':
									return ((float)$x[1]) + ((float)$x[3]);
								case '-':
									return ((float)$x[1]) - ((float)$x[3]);
								case '_':
									return strpos($x[3], '.') === false ? $x[1] . $x[3] : floor($x[1]) . $x[3];
								case '>>':
									return ((int)$x[1]) >> ((int)$x[3]);
								case '<<':
									return ((int)$x[1]) << ((int)$x[3]);
								case '<>>':
									return self::rtr((int)$x[1], (int)$x[3]);
								case '<<>':
									return self::rtl((int)$x[1], (int)$x[3]);
								case '&':
									return ((int)$x[1]) & ((int)$x[3]);
								case '|':
									return ((int)$x[1]) | ((int)$x[3]);
								case '^':
									return ((int)$x[1]) ^ ((int)$x[3]);
								case '=>':
									return self::res((int)$x[1], (int)$x[3]);
								case '=<':
									return self::res((int)$x[3], (int)$x[1]);
								case '&&':
									return $x[1] == 0 || $x[3] == 0 ? 0 : 1;
								case '||':
									return $x[1] == 0 && $x[3] == 0 ? 0 : 1;
								case '==':
									return $x[1] == $x[3] ? 1 : 0;
								case '!=':
									return $x[1] != $x[3] ? 1 : 0;
								case '<=':
									return $x[1] <= $x[3] ? 1 : 0;
								case '>=':
									return $x[1] >= $x[3] ? 1 : 0;
								case '>':
									return $x[1] > $x[3] ? 1 : 0;
								case '<':
									return $x[1] < $x[3] ? 1 : 0;
							}
						}, $calc);
					break;
					case 2:
						$calc = preg_replace_callback("/(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)($regex)/", function($x){
							switch($x[2]){
								case '!':
									return self::fact($x[1]);
								case '~':
									return self::revb($x[1]);
							}
						}, $calc);
					break;
					case 3:
						$calc = preg_replace_callback("/($regex)(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)/", function($x){
							switch($x[2]){
								case '!':
									return $x[2] == 0 ? 1 : 0;
								case '~':
									return ~$x[1];
							}
						}, $calc);
				}
			}
		}while($prev != $calc);
		if($calc === '')return 0;
		return (float)$calc;
	}
}
class XNNumber {
	// consts variables
	public static function PI($l = null){
		return xndata("pi", $l === null || $l < 0 ? null : ($l === 0 ? 1 : $l + 2));
	}
	public static function PHI($l = null){
		return xndata("phi", $l === null || $l < 0 ? null : ($l === 0 ? 1 : $l + 2));
	}
	public static function E($l = null){
		return xndata("e", $l === null || $l < 0 ? null : ($l === 0 ? 1 : $l + 2));
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
		return '0';
	}
	public static function _get3($a){
		if(self::_view($a))
			return '-' . self::_get2(self::abs($a));
		return self::_get2(self::abs($a));
	}
	public static function _get($a){
		if(!self::_check($a))return false;
		return self::_get3($a);
	}
	public static function _set0($a, $b){
		$l = strlen($b)- strlen($a);
		if($l <= 0)return $a;
		return str_repeat('0', $l). $a;
	}
	public static function _set1($a, $b){
		$l = strlen($b)- strlen($a);
		if($l <= 0)return $a;
		return $a . str_repeat('0', $l);
	}
	public static function _set2($a, $b){
		$a = self::_mo($a);
		$b = self::_mo($b);
		if(!isset($a[1]) && isset($b[1]))
			$a[1] = '0';
		if(isset($a[1]))$a[1] = self::_set1($a[1], @$b[1]);
		$a[0] = self::_set0($a[0], $b[0]);
		if(!isset($a[1]))return "{$a[0]}";
		return "{$a[0]}.{$a[1]}";
	}
	public static function _set3($a, $b){
		if(self::_view($a) && self::_view($b)) return '-' . self::_set2(self::abs($a), self::abs($b));
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
		return explode('.', $a, 2);
	}
	public static function _lm($a){
		return strpos($a, '.');
	}
	public static function _im($a){
		$p = self::_lm($a);
		return $p !== false && $p != -1;
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
			$a = str_replace(array('--', '-+', '+-', '++'), array('+', '-', '-', '+'), $a);
		}
		return $a;
	}
	public static function _th($a){
		return self::_im($a) ? array_value(self::_mo($a), 1) : '0';
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
	public static function divTwo($a,$limit = null){
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
		if(self::_view($a) && strpos($a, '.') > 0)
			return '-' . self::add(self::floor(self::abs($a)), '1');
		return array_value(explode('.', $a), 0);
	}
	public static function ceil($a){
		if(!self::_check($a))return false;
		if(self::_view($a) && strpos($a, '.') > 0)
			return '-' . self::add(self::ceil(self::abs($a)), '1');
		$a = explode('.', $a);
		return isset($a[1])? self::add($a[0], '1'): $a[0];
	}
	public static function round($a){
		if(!self::_check($a))return false;
		if(self::_view($a) && strpos($a, '.') > 0)
			return '-' . self::add(self::round(self::abs($a)), '1');
		$a = explode('.', $a);
		return isset($a[1]) && $a[1][0] >= 5 ? self::add($a[0], '1'): $a[0];
	}
	public static function is_floor($a){
		return strpos($a, '.') < 1;
	}
	public static function floord($a, $x){
		if(($p = self::_lm($a)) === false)
			return $a;
		return self::_get(substr($a, 0, $p + $x + 1));
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
		if(self::_view($a) && !self::_view($b))return self::sub(self::abs($b), self::abs($a));
		if(!self::_view($a) && self::_view($b))return self::sub(self::abs($a), self::abs($b));
		return self::_add1(self::abs($a), self::abs($b));
	}
	public static function add($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) <= 13 && strlen($b) <= 13)
			return (string)($a + $b);
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c = strlen($a) - $p;
			if(($p = strpos($b, '.')) !== false)
				$c = max($c, strlen($b) - $p);
			return self::_get3(bcadd($a, $b, $c));
		}
		self::_setfull($a, $b);
		if($a == 0)return $b;
		if($b == 0)return $a;
		if($a == $b)return self::mulTwo($a);
		return self::_get3(self::_add2($a, $b));
	}
	public static function _sub0($a, $b){
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
	public static function _sub1($a, $b){
		$o = self::_lm($a);
		$p = $o + (13 - (strlen($a)- 1)% 13);
		$a = self::_so(self::_nm($a), 13);
		$b = self::_so(self::_nm($b), 13);
		if($o !== false && $o !== - 1)return self::_st(self::_sub0($a, $b), $p);
		return self::_sub0($a, $b);
	}
	public static function _sub2($a, $b){
		if(self::_view($a) && self::_view($b))return '-' . self::_sub1(self::abs($a), self::abs($b));
		if(self::_view($a) && !self::_view($b))return '-' . self::_add1(self::abs($a), self::abs($b));
		if(!self::_view($a) && self::_view($b))return self::_add1(self::abs($a), self::abs($b));
		return self::_sub1(self::abs($a), self::abs($b));
	}
	public static function _sub3($a, $b){
		if($a < $b) {
			return '-' . self::_sub2($b, $a);
		}
		return self::_sub2($a, $b);
	}
	public static function sub($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) <= 13 && strlen($b) <= 13)
			return (string)($a - $b);
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c = strlen($a) - $p;
			if(($p = strpos($b, '.')) !== false)
				$c = max($c, strlen($b) - $p);
			return self::_get3(bcsub($a, $b, $c));
		}
		self::_setfull($a, $b);
		$r = $a == 0 ? self::_change($b): $b == 0 ? $a : self::_sub3($a, $b);
		return self::_pl(self::_get3($r));
	}
	public static function _mul0($a, $b){
		$a = str_split($a, 1);
		$b = str_split($b, 1);
		$x = false;
		$c = $d = count($a) - 1;
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
		$ap = strlen($a) - $ap - 1;
		$bp = strlen($b) - $bp - 1;
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
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c+= strlen($a) - $p;
			if(($p = strpos($b, '.')) !== false)
				$c+= strlen($b) - $p;
			return self::_get3(bcmul($a, $b, $c));
		}
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
		$c = self::sub($a, $b);
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
		if(!self::_view($a) && self::_view($b))
			return self::_change(self::sub(self::_rand3('0', self::add(self::abs($a), self::abs($b))), $a));
		if(self::_view($a) && !self::_view($b))
			return self::_change(self::sub(self::_rand3('0', self::add(self::abs($a), self::abs($b))), $b));
		return self::_rand3(self::abs($a), self::abs($b));
	}
	public static function rand($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		self::_setfull($a, $b);
		$r = $a == $b ? $a : self::_rand4($a, $b);
		return self::_get($r);
	}
	public static function lcg($length = null){
		return self::div('1', self::_rand0($length !== null ? $length + 1 : 100), $length !== null ? $length : 99);
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
	public static function _div1($a, $b, $o = -1){
		$a = str_split($a, 1);
		$p = $r = $i = $d = '0';
		$c = count($a);
		while($i < $c) {
			$d.= $a[$i];
			if($d >= $b) {
				$p = self::_div0($d, $b);
				$d = self::sub($d, self::mul($p, $b));
				$r.= $p;
			}
			else $r.= '0';
			++$i;
		}
		if($d == 0 || $o == 0)return $r;
		$r .= '.';
		while($d > 0 && $o != 0) {
			$d.= '0';
			if($d >= $b) {
				$p = self::_div0($d, $b);
				$d = self::sub($d, self::mul($p, $b));
				$r.= $p;
			}
			else $r.= '0';
			--$o;
		}
		return $r;
	}
	public static function _div2($a, $b, $c = -1){
		$a = self::_nm($a);
		$b = self::_nm($b);
		if($c < 0)$c = 0;
		return self::_div1($a, $b, $c);
	}
	public static function _div3($a, $b, $c = -1){
		if(self::_view($a) && self::_view($b))return self::_div2(self::abs($a), self::abs($b), $c);
		if(self::_view($a) && !self::_view($b))return '-' . self::_div2(self::abs($a), self::abs($b), $c);
		if(!self::_view($a) && self::_view($b))return '-' . self::_div2(self::abs($a), self::abs($b), $c);
		return self::_div2(self::abs($a), self::abs($b), $c);
	}
	public static function div($a, $b, $c = -1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		self::_setfull($a, $b);
		if($b == 0) {
			new XNError("XNNumber", "not can div by Ziro", XNError::ARITHMETIC);
			return false;
		}
		if(__xnlib_data::$installedBcmath){
			if($c == -1){
				$c = 1;
				if(($p = strpos($a, '.')) !== false)
					$c+= strlen($a) - $p;
				if(($p = strpos($b, '.')) !== false)
					$c+= strlen($b) - $p;
			}
			return self::_get3(bcdiv($a, $b, $c));
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
				$d = self::sub($d, self::mul($p, $b));
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
		if(__xnlib_data::$installedBcmath)
			return self::_get3(bcmod($a, $b, 0));
		if($a == 0 || $b == 1 || $a == $b)return '0';
		return self::_get(self::_mod2($a, $b));
	}
	public static function _powFloor($a,$b){
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
	public static function powFloor($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(strlen($a) * $b <= 10)
			return (string)(pow($a, $b));
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c+= strlen($a) - $p;
			$c*= $b;
			return self::_get3(bcpow($a, $b, $c));
		}
		if($b < 0)
			return self::div('1',self::_powFloor($a,substr_replace($b,'',0,1)));
		return self::_powFloor($a,$b);
	}
	// algo functions
	public static function fact($a){
		if(!self::_check($a))return false;
		if($a <= 1)return 1;
		if($a <= 16)return (string)XNMath::fact($a);
		$r = '1';
		while($a > 0) {
			$r = self::mul($r, $a);
			$a = self::sub($a, '1');
		}
		return $r;
	}
	public static function fmod($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if($b == 0) {
			new XNError("XNNumber", "not can div by Ziro", XNError::ARITHMETIC);
			return false;
		}
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c+= strlen($a) - $p;
			if(($p = strpos($b, '.')) !== false)
				$c+= strlen($b) - $p;
			return self::_get3(bcmod($a, $b, $c));
		}
		return self::sub($a, self::mul(self::div($a, $b, 0), $b));
	}
	public static function umod($x, $y){
		$x = self::mod($x, $y);
		if($x === false)return false;
		if($x < 0)return self::add($x, $y);
		return $x;
	}
	public static function fumod($x, $y){
		if(!self::_check($x))return false;
		if(!self::_check($y))return false;
		if(__xnlib_data::$installedBcmath){
			$c = 0;
			if(($p = strpos($a, '.')) !== false)
				$c+= strlen($a) - $p;
			if(($p = strpos($b, '.')) !== false)
				$c+= strlen($b) - $p;
			$x = self::_get3(bcmod($a, $b, 0));
		}else $x = self::sub($x, self::mul(self::div($x, $y, 0), $y));
		if($x < 0)return self::add($x, $y);
		return $x;
	}
	public static function nmod($x, $y){
		$z = self::mod($x, $y);
		if($z === false)return false;
		if($z == 0)return '0';
		return self::sub($x, $z);
	}
	public static function gcd($a, $b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return $b ? self::gcd($b, self::mod($a, $b)) : $a;
	}
	public static function lcm($a, $b){
		$gcd = self::gcd($a, $b);
		if($gcd === false)return false;
		return self::div(self::mul($a, $b), $gcd);
	}
	public static function time(){
		$time = microtime();
		return self::_get(substr($time, 11) . '.' . substr($time, 2, 8));
	}
	public static function sqrt($n, $limit = 15){
		if(__xnlib_data::$installedBcmath)
			return self::_get3(bcsqrt($n, $limit));
		$x = $n;
		$y = '1';
		while($x != $y) {
			$x = self::div(self::add($x, $y), '2', $limit);
			$y = self::div($n, $x, $limit);
		}
		return $x;
	}
	public static function max($first){
		return max(is_array($first) ? $first : func_get_args());
	}
	public static function min($first){
		return min(is_array($first) ? $first : func_get_args());
	}
	public static function average(){
		$nums = func_get_args();
		$num = $nums[0];
		for($c = 1;isset($nums[$c]);)
			$num = self::add($num, $nums[$c++]);
		return self::div($num, $c);
	}
	public static function discriminant($a, $b, $c){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(!self::_check($c))return false;
		return self::powTwo($b) - self::mul(self::mul($a, $c), 4);
	}
	public static function decimals($x){
		if(!self::_check($x))return false;
		return array_value(explode('.', $x . '.0', 3), 1);
	}
	public static function hypot($x, $y){
		if(!self::_check($x))return false;
		if(!self::_check($y))return false;
		return self::sqrt(self::add(self::powFloor($x, '2'), self::powFloor($y, '2')));
	}
	public static function powf($x, $y){
		if(!self::_check($x))return false;
		if(!self::_check($y))return false;
		return self::powFloor($x, $y);
	}
	public static function sin($a, $c = 14){
		if(!self::_check($a))return false;
		$a = self::fmod($a, $c > 12 && ($p = self::PI($c)) ? self::mul($p, 2) : 2 * M_PI);
		$p = 1;
		$d = '1';
		$x = $a;
		$l = '';
		for($i = 2; $l != $x; $i += 2){
			$l = $x;
			$p += 2;
			$d = self::mul($d, -$i * ($i + 1));
			$x = self::add($x, self::div(self::powFloor($a, $p), $d, $c));
		}
		return $x;
	}
	public static function cos($a, $c = 14){
		if(!self::_check($a))return false;
		$a = self::fmod($a, $c > 12 && ($p = self::PI($c)) ? self::mul($p, 2) : 2 * M_PI);
		$p = 0;
		$d = '1';
		$x = 1;
		$l = '';
		for($i = 1; $l != $x; $i += 2){
			$l = $x;
			$p += 2;
			$d = self::mul($d, -$i * ($i + 1));
			$x = self::add($x, self::div(self::powFloor($a, $p), $d, $c));
		}
		return $x;
	}
	public static function rad($x, $c = 15){
		if(!self::_check($a))return false;
		return self::mul($x, xndata('degree', $c));
	}
	public static function deg($x, $c = 15){
		if(!self::_check($a))return false;
		return self::mul($x, xndata('radian', $c));
	}
	public static function tan($a, $c = 15){
		if(!self::_check($a))return false;
		return self::div(self::sin($a), self::cos($a), $c);
	}
	public static function cot($a, $c = 15){
		if(!self::_check($a))return false;
		return self::div(self::cos($a), self::sin($a), $c);
	}
	public static function sec($a, $c = 15){
		if(!self::_check($a))return false;
		return self::div('1', self::sin($a), $c);
	}
	public static function csc($a, $c = 15){
		if(!self::_check($a))return false;
		return self::div('1', self::cos($a), $c);
	}
	public static function exp($a, $c = 10){
		if(!self::_check($a))return false;
		$d = '1';
		$x = self::add($a, '1');
		$l = '';
		for($i = 2;$l != $x;++$i){
			$l = $x;
			$d = self::mul($d, $i);
			$x = self::add($x, self::div(self::powFloor($a, $i), $d, $c));
		}
		return $x;
	}
	public static function ln($a, $c = 12){
		if(!self::_check($a))return false;
		$p = 1;
		$y = self::div(self::sub($a, '1'), self::add($a, '1'), $c + 2);
		$x = "$y";
		$l = '';
		for($i = 0;$l != $x;++$i){
			$l = $x;
			$p+= 2;
			$x = self::add($x, self::div(self::powFloor($y, $p), $p, $c));
		}
		return self::mul($x, '2');
	}
	public static function log($a, $b, $c = 12){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::div(self::ln($a, $c), self::ln($b, $c), $c);
	}
	public static function pow($a, $b, $c = null){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if(($c === null && strlen($a) * $b <= 10) || strlen($a) * $b <= 12 - $c)
			return (string)(pow($a, $b));
		if(strpos($b, '.') === false)
			return self::powFloor($a, $b);
		$b1 = self::floor($b);
		$b2 = '0.' . self::_th($b);
		$b = $b1 == 0 ? '1' : self::powFloor($a, $b1);
		$r = $b2 == 0 ? '1' : self::exp(self::mul(self::ln($a, $c), $b2), $c);
		return self::mul($r, $b);
	}
	public static function sinh($a, $c = 14){
		if(!self::_check($a))return false;
		$x = self::exp($a, $c);
		$y = self::div('1', $x, $c);
		return self::div(self::add($x, $y), '2', $c);
	}
	public static function cosh($a, $c = 14){
		if(!self::_check($a))return false;
		$x = self::exp($a, $c);
		$y = self::div('1', $x, $c);
		return self::div(self::sub($x, $y), '2', $c);
	}
	public static function tanh($a, $c = 14){
		if(!self::_check($a))return false;
		$x = self::exp($a, $c);
		$y = self::div('1', $x, $c);
		return self::div(self::sub($x, $y), self::add($x, $y), $c);
	}
	public static function atan($a, $c = 14){
		if(!self::_check($a))return false;
		if($a < 0)return '-' . self::atan(self::abs($a), $c);
		$a = self::fmod($a, $c > 12 && ($p = self::PI($c)) ? self::div($p, 2) : M_PI / 2);
		$p = 1;
		$n = 1;
		$x = "$a";
		$l = '';
		for($i = 0;$l != $x;++$i){
			$l = $x;
			$p+= 2;
			$n*=-1;
			$x = self::add($x, self::div(self::powFloor($x, $p), ($n == -1 ? "-$p" : $p), $c));
		}
		return $x;
	}
	public static function triangle($a, $p = 2, $c = 14){
		if(!self::_check($a))return false;
		elseif($p === 0)return '1';
		elseif($p < 0)return self::div('1', self::triangle($a, -$p, $c), $c);
		$n = $a;
		for($i = 1;$i < $p;++$i)
			$n = self::mul($n, self::add($a, $i));
		return self::div($n, self::fact($p), $c);
	}
	public static function atan2($y, $x, $c = 12){
		if(!self::_check($y))return false;
		if(!self::_check($x))return false;
		if($x > 0)return self::atan(self::div($y, $x, $c), $c);
		if($x < 0 && $y >= 0)return self::add(self::atan(self::div($y, $x, $c), $c), ($c > 12 && ($p = self::PI($c)) ? $p : M_PI));
		if($x < 0 && $y < 0) return self::sub(self::atan(self::div($y, $x, $c), $c), ($c > 12 && ($p = self::PI($c)) ? $p : M_PI));
		if($x == 0 && $y > 0)return $c > 12 && ($p = self::PI($c)) ? self::div($p, 2) : M_PI / 2;
		if($x == 0 && $y < 0)return '-' . ($c > 12 && ($p = self::PI($c)) ? self::div($p, 2) : M_PI / 2);
		return '0';
	}
	// binary functions
	public static function xorx($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(strrev(strrev(self::base_convert($a,10,'ascii')) ^ strrev(self::base_convert($b,10,'ascii'))),'ascii');
	}
	public static function andx($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(strrev(strrev(self::base_convert($a,10,'ascii')) & strrev(self::base_convert($b,10,'ascii'))),'ascii');
	}
	public static function orx($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(strrev(strrev(self::base_convert($a,10,'ascii')) | strrev(self::base_convert($b,10,'ascii'))),'ascii');
	}
	public static function shl($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::mul(self::floor($a), self::powFloor('2', $b));
	}
	public static function shr($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::div(self::floor($a), self::powFloor('2', $b), 0);
	}
	public static function rtl($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(xnbinary::rtl(self::base_convert($a,10,2),$b),2);
	}
	public static function rtr($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(xnbinary::rtr(self::base_convert($a,10,2),$b),2);
	}
	public static function shift($a,$b = 1){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::mul(self::floor($a), self::powFloor('2', $b));
	}
	public static function resx($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		return self::init(xnbinary::resx(self::base_convert($a,10,2),self::base_convert($b,10,2)),2);
	}
	public static function cmp($a,$b){
		if(!self::_check($a))return false;
		if(!self::_check($b))return false;
		if($a == $b)return '0';
		if($a > $b)return '1';
		return '-1';
	}
	public static function neg($x){
		if(!self::_check($x))return false;
		return self::init(xnbinary::neg(self::base_convert($x,10,2)),2);
	}
	public static function negb($x){
		if(!self::_check($x))return false;
		return self::init(strrev(~strrev(self::base_convert($x,10,'ascii'))),'ascii');
	}
	public static function revb($x){
		if(!self::_check($x))return false;
		return self::init(strrev(self::base_convert($x,10,2)),2);
	}
	// convertor functions
	public static function tonumber($a = '0'){
		if(!self::_check($a))return false;
		return (float)$a;
	}
	public static function toXNNumber($a = 0){
		if(is_nan($a) || is_infinite($a)) {
			new XNError("XNNumber", "the $a not is a number", XNError::ARITHMETIC);
			return false;
		}
		$a = explode('E', $a);
		if(!isset($a[1]))return "{$a[0]}";
		$a = self::powTen($a[0], $a[1]);
		return $a;
	}
	public static function big($x){
		if(!is_numeric($x))
			return false;
		$code = thelinecode();
		$code = substr($code, stripos($code, 'big(') + 10, -1);
		if($code[0] === '"' || $code[0] === "'")
			$c = 1;
		else
			$c = 0;
		$num = '';
		while(is_numeric('0' . ltrim($num) . '0') && isset($code[$c]))
			$num .= $code[$c++];
		$num = substr(ltrim($num), 0, -1);
		if(!is_numeric($num))
			return false;
		return self::_get($num);
	}
	public static function init($number, $init = 10){
		return self::base_convert($number, $init, 10);
	}
	public static function bindec($bin){
		return self::base_convert($bin, 2, 10);
	}
	public static function octdec($oct){
		return self::base_convert($oct, 8, 10);
	}
	public static function hexdec($hex){
		return self::base_convert($hex, 16, 10);
	}
	public static function decbin($number){
		return self::base_convert($number, 10, 2);
	}
	public static function octbin($number){
		return self::base_convert($number, 10, 8);
	}
	public static function hexbin($number){
		return self::base_convert($number, 10, 16);
	}
	public static function decstr($number){
		return self::base_convert($number, 10, 'ascii');
	}
	public static function strdec($string){
		return self::base_convert($string, 'ascii', 10);
	}
	// parser functions
	public static function baseconvert($text, $from = false, $to = false){
		if(is_string($to))$to = strtolower($to);
		if(is_string($from)){
			$from = strtolower($from);
			if($from == 'ascii' && ($to == '01' || $to == array('0', '1')))
				return xncrypt::bindecode($text);
			if($from == 'ascii' && ($to == '01234' || $to == array('0', '1', '2', '3', '4')))
				return xncrypt::base4decode($text);
			if($from == 'ascii' && ($to == xnstring::HEX_RANGE || $to == array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c')))
				return xncrypt::hexdecode($text);
			if($from == 'ascii')
				return self::baseconvert(xncrypt::hexencode($text), xnstring::HEX_RANGE, $to);
		}if(is_string($to)){
			if($to == 'ascii' && ($from == '01' || $from == array('0', '1')))
				return ltrim(xncrypt::binencode($text), '0');
			if($to == 'ascii' && ($from == '01234' || $from == array('0', '1', '2', '3', '4')))
				return ltrim(xncrypt::base4encode($text), '0');
			if($to == 'ascii' && ($from == xnstring::HEX_RANGE || $from == array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c')))
				return ltrim(xncrypt::hexencode($text), '0');
			if($to == 'ascii'){
				$r = self::baseconvert($text, $from, xnstring::HEX_RANGE);
				if(strlen($r) % 2 == 1)$r = '0'.$r;
				return xncrypt::hexdecode($r);
			}
		}
		$text = (string)$text;
		if(!is_array($from))$fromel = str_split($from);
		else $fromel = $from;
		if($from == $to)return $text;
		$frome = array();
		foreach($fromel as $key => $value)
			$frome[$value] = $key;
		unset($fromel);
		$fromc = count($frome);
		if(!is_array($to))$toe = str_split($to);
		else $toe = $to;
		$toc = count($toe);
		$texte = array_reverse(str_split($text));
		$textc = count($texte);
		$bs = '0';
		$th = '1';
		if($from === false)
			$bs = $text;
		else
			for($i = 0; $i < $textc; ++$i) {
				$bs = self::add($bs, self::mul(@$frome[$texte[$i]], $th));
				$th = self::mul($th, $fromc);
			}
		$r = '';
		if($to === false)return $bs === '' ? '0' : "$bs";
		while($bs > 0) {
			$r = $toe[self::mod($bs, $toc)] . $r;
			$bs = self::floor(self::div($bs, $toc));
		}
		return $r === '' ? $toe[0] : "$r";
	}
	public static function base_convert($str, $from, $to = 10){
		if($from == 1) {
			$str = (string)strlen($str);
			$from = 10;
		}
		if($from == $to)return $str;
		if($from <= 36 && is_numeric($from))$str = strtolower($str);
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/+=';
		$from = strtolower($from) == "ascii" ? "ascii" : substr($chars, 0, $from);
		$to = strtolower($to) == "ascii" ? "ascii" : substr($chars, 0, $to);
		$to = $to == '0123456789' ? false : $to;
		$from = $from == '0123456789' ? false : $from;
		return self::baseconvert($str, $from, $to);
	}
	public static function unsign($x){
		return self::init(xnmath::bin($x), 2);
	}
	// calc function
	private static function calcarg($calc, $offset = null){
		preg_match('/(?:(?:(?<x>\((?:\g<x>|\\\\\(|\\\\\)|[^\)])*\))|\\\\\"|[^\"])*\"|[^,])+/', $calc, $match, 0, $offset === null ? 0 : $offset);
		return isset($match[0]) ? self::calc($match[0]) : '';
	}
	public static function calc($calc, $precision = 15){
		$calc = preg_replace_callback('/\"(?:\\\\\\\\|\\\\\"|[^\"])*\"|\'[+-<>\[\]]*\'|(?:0|0o|o)[0-7]+|(?:0x|x)[0-9a-f]+|(?:0b|b)[01]+|\.[0-9]+|[0-9]+\./i', function($x){
			if($x[0][-1] == '.')return substr($x[0], 0, -1);
			switch($x[0][0]){
				case '.':
					return '0' . $x[0];
				case '"':
					return self::strdec(substr($x[0], 1, -1));
				case "'":
					return self::strdec(brainfuck::run(substr($x[0], 1, -1) . '.'));
				case 'o':
					return self::octdec(substr($x[0], 1));
				case 'x':
					return self::hexdec(substr($x[0], 1));
				case 'b':
					return self::bindec(substr($x[0], 1));
				case '0':
					switch($x[0][1]){
						case 'o':
							return self::octdec(substr($x[0], 2));
						case 'x':
							return self::hexdec(substr($x[0], 2));
						case 'b':
							return self::bindec(substr($x[0], 2));
						default:
							return self::octdec(substr($x[0], 1));
					}
			}
		}, $calc);
		$calc = str_replace(array('<=>', ' ', "\n", "\r", "\t"), array('^', '', '', '', ''), $calc);
		do{
			$calc = str_replace(array('--', '-+', '+-', '++'), array('+', '-', '-', '+'), $prev = $calc);
		}while($prev != $calc);
		$calc = preg_replace('/(?!<[a-zA-Z])([0-9])(\(|\[|[a-zA-Z])/', '$1*$2', $calc);
		do{
			$end = isset($calc[-1]) ? $calc[-1] : '';
			if($end === '+' || $end === '-')
				$calc .= '1';
			$calc = preg_replace_callback('/(?<![a-zA-Z])\((?:\\\\\(|\\\\\)|\"(?:\\\\|\\\"|[^\"])*\"|[^\(\)])*\)/', function($x){
				return self::calc(substr($x[0], 1, -1));
			}, $prev = $calc);
			$calc = preg_replace_callback('/(?<![a-zA-Z])\[(?:\\\\\[|\\\\\]|\"(?:\\\\|\\\"|[^\"])*\"|[^\[\]])*\]/', function($x){
				return self::floor(self::calc(substr($x[0], 1, -1), 0));
			}, $calc);
			$calc = preg_replace_callback('/(abs|acos|acosh|asin|asinh|atan|atan2|atanh|base|ceil|cos|cot|csc|deg|exp|expm1|floor|fmod|fumod|hypot|lcg|log|log10|log1p|max|min|pi|phi|rad|rand|round|sec|sin|sinh|sqrt|tan|tanh)(?:(?<x>\((?:\g<x>|\\\\\(|\\\\\)|[^\)])*\))|(?:\g<x>|\\\\\(|\\\\\)|[^\)])*)/', function($x)use($precision){
				$args = substr($x[2], 1, -1);
				switch($x[1]){
					case 'abs':
						return self::abs(self::calcarg($args));
					case 'acos':
						return self::acos(self::calcarg($args), $precision);
					case 'acosh':
						return self::acosh(self::calcarg($args), $precision);
					case 'asin':
						return self::asin(self::calcarg($args), $precision);
					case 'asinh':
						return self::asinh(self::calcarg($args), $precision);
					case 'atan':
						return self::atan(self::calcarg($args), $precision);
					case 'atan2':
						$arg = self::calcarg($args);
						return self::atan2($arg, self::calcarg($args, strlen($arg) + 1), $precision);
					case 'atanh':
						return self::atanh(self::calcarg($args), $precision);
					case 'base':
						$arg1 = self::calcarg($args);
						$len  = strlen($arg1) + 1;
						$arg2 = self::calcarg($args, $len);
						$arg3 = self::calcarg($args, strlen($arg2) + $len + 1);
						if($arg2 === '')$arg2 = '10';
						if($arg3 === '')$arg3 = '10';
						return self::base_convert($arg1, $arg2, $arg3);
					case 'ceil':
						return self::ceil(self::calcarg($args));
					case 'cos':
						return self::cos(self::calcarg($args), $precision);
					case 'cot':
						return self::cot(self::calcarg($args), $precision);
					case 'csc':
						return self::csc(self::calcarg($args), $precision);
					case 'deg':
						return self::deg(self::calcarg($args), $precision);
					case 'exp':
						return self::exp(self::calcarg($args), $precision);
					case 'expm1':
						return self::expm1(self::calcarg($args), $precision);
					case 'floor':
						return self::floor(self::calcarg($args));
					case 'fmod':
						return self::fmod(self::calcarg($args));
					case 'fumod':
						return self::fumod(self::calcarg($args));
					case 'hypot':
						$arg = self::calcarg($args);
						return self::hypot($arg, self::calcarg($args, strlen($arg) + 1), $precision);
					case 'lcg':
						$arg = self::calcarg($args);
						if($arg === '')$arg = null;
						return self::lcg($arg, $precision);
					case 'log':
						$arg = self::calcarg($args);
						return self::log($arg, self::calcarg($args, strlen($arg) + 1), $precision);
					case 'ln':
						return self::ln(self::calcarg($args), $precision);
					case 'max':
					case 'min':
						$arg = array();
						$now = self::calcarg($args);
						$len = strlen($now) + 1;
						while($len !== 1){
							$arg[] = $now;
							$now = self::calcarg($args, $len);
							$len += strlen($now) + 1;
						}
						return call_user_func_array($x[1], $arg);
					case 'nmod':
						return self::nmod(self::calcarg($args));
					case 'pi':
						return self::PI(self::calcarg($args), $precision);
					case 'phi':
						return self::PHI(self::calcarg($args), $precision);
					case 'rad':
						return self::rad(self::calcarg($args), $precision);
					case 'rand':
						$arg = self::calcarg($args);
						return self::rand($arg, self::calcarg($args, strlen($arg) + 1));
					case 'round':
						return self::round(self::calcarg($args));
					case 'sec':
						return self::sec(self::calcarg($args), $precision);
					case 'sin':
						return self::sin(self::calcarg($args), $precision);
					case 'sinh':
						return self::sinh(self::calcarg($args), $precision);
					case 'sqrt':
						return self::sqrt(self::calcarg($args), $precision);
					case 'tan':
						return self::tan(self::calcarg($args), $precision);
					case 'tanh':
						return self::tanh(self::calcarg($args), $precision);
					case 'umod':
						return self::umod(self::calcarg($args));
				}
			}, $calc);
			foreach(array(
				array(1, '~'),
				array(1, '\*\*', '\*\/', '\*%'),
				array(1, '\*', '\/', '%'),
				array(1, '\+', '-'),
				array(1, '_'),
				array(1, '>>', '<<', '<>>', '<<>', '<>'),
				array(1, '&', '\|', '^', '=>', '=<'),
				array(2, '!', '~'),
				array(3, '!', '~'),
				array(1, '&&', '\|\|', '==', '!=', '<=', '>=', '<', '>'),
			) as $signs){
				$regex = implode('|', array_slice($signs, 1));
				switch($signs[0]){
					case 1:
						$calc = preg_replace_callback("/(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)($regex)(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)/", function($x)use($precision){
							switch($x[2]){
								case '~':
									return self::rand($x[1], $x[3]);
								case '**':
									return self::pow($x[1], $x[3], $precision);
								case '*/':
									return self::pow($x[1], self::div('1', $x[3], $precision), $precision);
								case '*%':
									return self::powFloor($x[1], $x[3]);
								case '*':
									return self::mul($x[1], $x[3]);
								case '/':
									return self::div($x[1], $x[3], $precision);
								case '%':
									return self::mod($x[1], $x[3]);
								case '+':
									return self::add($x[1], $x[3]);
								case '-':
									return self::sub($x[1], $x[3]);
								case '_':
									return self::_im($x[3]) ? $x[1] . $x[3] : self::floor($x[1]) . $x[3];
								case '>>':
									return self::shr($x[1], $x[3]);
								case '<<':
									return self::shl($x[1], $x[3]);
								case '<>>':
									return self::rtr($x[1], $x[3]);
								case '<<>':
									return self::rtl($x[1], $x[3]);
								case '&':
									return self::andx($x[1], $x[3]);
								case '|':
									return self::orx($x[1], $x[3]);
								case '^':
									return self::xorx($x[1], $x[3]);
								case '=>':
									return self::resx($x[1], $x[3]);
								case '=<':
									return self::resx($x[3], $x[1]);
								case '&&':
									return $x[1] == 0 || $x[3] == 0 ? '0' : '1';
								case '||':
									return $x[1] == 0 && $x[3] == 0 ? '0' : '1';
								case '==':
									return $x[1] == $x[3] ? '1' : '0';
								case '!=':
									return $x[1] != $x[3] ? '1' : '0';
								case '<=':
									return $x[1] <= $x[3] ? '1' : '0';
								case '>=':
									return $x[1] >= $x[3] ? '1' : '0';
								case '>':
									return $x[1] > $x[3] ? '1' : '0';
								case '<':
									return $x[1] < $x[3] ? '1' : '0';
							}
						}, $calc);
					break;
					case 2:
						$calc = preg_replace_callback("/(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)($regex)/", function($x){
							switch($x[2]){
								case '!':
									return self::fact($x[1]);
								case '~':
									return self::revb($x[1]);
							}
						}, $calc);
					break;
					case 3:
						$calc = preg_replace_callback("/($regex)(-{0,1}[0-9]+\.[0-9]+|-{0,1}[0-9]+)/", function($x){
							switch($x[2]){
								case '!':
									return $x[2] == '0' ? '1' : '0';
								case '~':
									return self::neg($x[1]);
							}
						}, $calc);
				}
			}
		}while($prev != $calc);
		if($calc === '')return '0';
		return $calc;
	}
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
			new XNError("XNBinary", "invalid binary \"$a\".", XNError::ARITHMETIC);
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
	public static function xorx($a, $b){
		if(!self::_setfull($a, $b))return false;
		for($c = 0; isset($a[$c]); ++$c)$a[$c] = ($a[$c] == $b[$c]) ? '0' : '1';
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
	public static function sub($a, $b){
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
	public static function shr($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		$a = substr($a, 0, -$shift);
		return $a === '' || $a === false ? '0' : $a;
	}
	public static function shl($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		return $a . str_repeat('0', $shift);
	}
	public static function shift($a, $shift = 1){
		if(!self::_getfull($a))return false;
		if($shift == 0)return $a;
		if($shift < 0){
			$a = substr($a, 0, -$shift);
			return $a === '' || $a === false ? '0' : $a;
		}
		return $a . str_repeat('0', $shift);
	}
	public static function rtr($a, $rotate = 1){
		if(!self::_getfull($a))return false;
		if($rotate == 0)return $a;
		return substr($a, -$rotate) . substr($a, 0, -$rotate);
	}
	public static function rtl($a, $rotate = 1){
		if(!self::_getfull($a))return false;
		if($rotate == 0)return $a;
		return substr($a, $rotate) . substr($a, 0, $rotate);
	}
	public static function andx($a, $b){
		if(!self::_setfull($a, $b))return false;
		for($c = 0;isset($a[$c]);++$c){
			if($a[$c] === '1' && $b[$c] === '1');
			else $a[$c] = '0';
		}
		return self::_get($a);
	}
	public static function orx($a, $b){
		if(!self::_setfull($a, $b))return false;
		$l = strlen($a);
		for($c = 0;isset($a[$c]);++$c){
			if($a[$c] === '1' || $b[$c] === '1')
				$a[$c] = '1';
			else $a[$c] = '0';
		}
		return self::_get($a);
	}
	public static function resx($a, $b){
		if(!self::_setfull($a, $b))return false;
		$l = strlen($a);
		for($c = 0;isset($a[$c]);++$c){
			if($a[$c] === '1' && $b[$c] === '0')
				$a[$c] = '0';
			else $a[$c] = '1';
		}
		return self::_get($a);
	}
	public static function neg($x){
		return strtr($x, '01', '10');
	}

	// convertors
	public static function init($a, $init = 2){
		return xnnumber::base_convert($a, $init, 2);
	}
}

/* ---------- XN Cryptography ---------- */
class XNCrypt {
	protected static $crc8table = array(
		0x00, 0x07, 0x0E, 0x09, 0x1C, 0x1B, 0x12, 0x15, 0x38, 0x3F, 0x36, 0x31,
		0x24, 0x23, 0x2A, 0x2D, 0x70, 0x77, 0x7E, 0x79, 0x6C, 0x6B, 0x62, 0x65,
		0x48, 0x4F, 0x46, 0x41, 0x54, 0x53, 0x5A, 0x5D, 0xE0, 0xE7, 0xEE, 0xE9,
		0xFC, 0xFB, 0xF2, 0xF5, 0xD8, 0xDF, 0xD6, 0xD1, 0xC4, 0xC3, 0xCA, 0xCD,
		0x90, 0x97, 0x9E, 0x99, 0x8C, 0x8B, 0x82, 0x85, 0xA8, 0xAF, 0xA6, 0xA1,
		0xB4, 0xB3, 0xBA, 0xBD, 0xC7, 0xC0, 0xC9, 0xCE, 0xDB, 0xDC, 0xD5, 0xD2,
		0xFF, 0xF8, 0xF1, 0xF6, 0xE3, 0xE4, 0xED, 0xEA, 0xB7, 0xB0, 0xB9, 0xBE,
		0xAB, 0xAC, 0xA5, 0xA2, 0x8F, 0x88, 0x81, 0x86, 0x93, 0x94, 0x9D, 0x9A,
		0x27, 0x20, 0x29, 0x2E, 0x3B, 0x3C, 0x35, 0x32, 0x1F, 0x18, 0x11, 0x16,
		0x03, 0x04, 0x0D, 0x0A, 0x57, 0x50, 0x59, 0x5E, 0x4B, 0x4C, 0x45, 0x42,
		0x6F, 0x68, 0x61, 0x66, 0x73, 0x74, 0x7D, 0x7A, 0x89, 0x8E, 0x87, 0x80,
		0x95, 0x92, 0x9B, 0x9C, 0xB1, 0xB6, 0xBF, 0xB8, 0xAD, 0xAA, 0xA3, 0xA4,
		0xF9, 0xFE, 0xF7, 0xF0, 0xE5, 0xE2, 0xEB, 0xEC, 0xC1, 0xC6, 0xCF, 0xC8,
		0xDD, 0xDA, 0xD3, 0xD4, 0x69, 0x6E, 0x67, 0x60, 0x75, 0x72, 0x7B, 0x7C,
		0x51, 0x56, 0x5F, 0x58, 0x4D, 0x4A, 0x43, 0x44, 0x19, 0x1E, 0x17, 0x10,
		0x05, 0x02, 0x0B, 0x0C, 0x21, 0x26, 0x2F, 0x28, 0x3D, 0x3A, 0x33, 0x34,
		0x4E, 0x49, 0x40, 0x47, 0x52, 0x55, 0x5C, 0x5B, 0x76, 0x71, 0x78, 0x7F,
		0x6A, 0x6D, 0x64, 0x63, 0x3E, 0x39, 0x30, 0x37, 0x22, 0x25, 0x2C, 0x2B,
		0x06, 0x01, 0x08, 0x0F, 0x1A, 0x1D, 0x14, 0x13, 0xAE, 0xA9, 0xA0, 0xA7,
		0xB2, 0xB5, 0xBC, 0xBB, 0x96, 0x91, 0x98, 0x9F, 0x8A, 0x8D, 0x84, 0x83,
		0xDE, 0xD9, 0xD0, 0xD7, 0xC2, 0xC5, 0xCC, 0xCB, 0xE6, 0xE1, 0xE8, 0xEF,
		0xFA, 0xFD, 0xF4, 0xF3
	);
	protected static $crc16table = array(
		0x0000,	0xC0C1, 0xC181, 0x0140, 0xC301, 0x03C0, 0x0280, 0xC241,
		0xC601, 0x06C0, 0x0780, 0xC741, 0x0500, 0xC5C1, 0xC481, 0x0440,
		0xCC01, 0x0CC0, 0x0D80, 0xCD41, 0x0F00, 0xCFC1, 0xCE81, 0x0E40,
		0x0A00, 0xCAC1, 0xCB81, 0x0B40, 0xC901, 0x09C0, 0x0880, 0xC841,
		0xD801, 0x18C0, 0x1980, 0xD941, 0x1B00, 0xDBC1, 0xDA81, 0x1A40,
		0x1E00, 0xDEC1, 0xDF81, 0x1F40, 0xDD01, 0x1DC0, 0x1C80, 0xDC41,
		0x1400, 0xD4C1, 0xD581, 0x1540, 0xD701, 0x17C0, 0x1680, 0xD641,
		0xD201, 0x12C0, 0x1380, 0xD341, 0x1100, 0xD1C1, 0xD081, 0x1040,
		0xF001, 0x30C0, 0x3180, 0xF141, 0x3300, 0xF3C1, 0xF281, 0x3240,
		0x3600, 0xF6C1, 0xF781, 0x3740, 0xF501, 0x35C0, 0x3480, 0xF441,
		0x3C00, 0xFCC1, 0xFD81, 0x3D40, 0xFF01, 0x3FC0, 0x3E80, 0xFE41,
		0xFA01, 0x3AC0, 0x3B80, 0xFB41, 0x3900, 0xF9C1, 0xF881, 0x3840,
		0x2800, 0xE8C1, 0xE981, 0x2940, 0xEB01, 0x2BC0, 0x2A80, 0xEA41,
		0xEE01, 0x2EC0, 0x2F80, 0xEF41, 0x2D00, 0xEDC1, 0xEC81, 0x2C40,
		0xE401, 0x24C0, 0x2580, 0xE541, 0x2700, 0xE7C1, 0xE681, 0x2640,
		0x2200, 0xE2C1, 0xE381, 0x2340, 0xE101, 0x21C0, 0x2080, 0xE041,
		0xA001, 0x60C0, 0x6180, 0xA141, 0x6300, 0xA3C1, 0xA281, 0x6240,
		0x6600, 0xA6C1, 0xA781, 0x6740, 0xA501, 0x65C0, 0x6480, 0xA441,
		0x6C00, 0xACC1, 0xAD81, 0x6D40, 0xAF01, 0x6FC0, 0x6E80, 0xAE41,
		0xAA01, 0x6AC0, 0x6B80, 0xAB41, 0x6900, 0xA9C1, 0xA881, 0x6840,
		0x7800, 0xB8C1, 0xB981, 0x7940, 0xBB01, 0x7BC0, 0x7A80, 0xBA41,
		0xBE01, 0x7EC0, 0x7F80, 0xBF41, 0x7D00, 0xBDC1, 0xBC81, 0x7C40,
		0xB401, 0x74C0, 0x7580, 0xB541, 0x7700, 0xB7C1, 0xB681, 0x7640,
		0x7200, 0xB2C1, 0xB381, 0x7340, 0xB101, 0x71C0, 0x7080, 0xB041,
		0x5000, 0x90C1, 0x9181, 0x5140, 0x9301, 0x53C0, 0x5280, 0x9241,
		0x9601, 0x56C0, 0x5780, 0x9741, 0x5500, 0x95C1, 0x9481, 0x5440,
		0x9C01, 0x5CC0, 0x5D80, 0x9D41, 0x5F00, 0x9FC1, 0x9E81, 0x5E40,
		0x5A00, 0x9AC1, 0x9B81, 0x5B40, 0x9901, 0x59C0, 0x5880, 0x9841,
		0x8801, 0x48C0, 0x4980, 0x8941, 0x4B00, 0x8BC1, 0x8A81, 0x4A40,
		0x4E00, 0x8EC1, 0x8F81, 0x4F40, 0x8D01, 0x4DC0, 0x4C80, 0x8C41,
		0x4400, 0x84C1, 0x8581, 0x4540, 0x8701, 0x47C0, 0x4680, 0x8641,
		0x8201, 0x42C0, 0x4380, 0x8341, 0x4100, 0x81C1, 0x8081, 0x4040
	);
	protected static $crc32table = array(
		0x00000000, 0x77073096, 0xEE0E612C, 0x990951BA, 0x076DC419, 0x706AF48F,
		0xE963A535, 0x9E6495A3, 0x0EDB8832, 0x79DCB8A4, 0xE0D5E91E, 0x97D2D988,
		0x09B64C2B, 0x7EB17CBD, 0xE7B82D07, 0x90BF1D91, 0x1DB71064, 0x6AB020F2,
		0xF3B97148, 0x84BE41DE, 0x1ADAD47D, 0x6DDDE4EB, 0xF4D4B551, 0x83D385C7,
		0x136C9856, 0x646BA8C0, 0xFD62F97A, 0x8A65C9EC, 0x14015C4F, 0x63066CD9,
		0xFA0F3D63, 0x8D080DF5, 0x3B6E20C8, 0x4C69105E, 0xD56041E4, 0xA2677172,
		0x3C03E4D1, 0x4B04D447, 0xD20D85FD, 0xA50AB56B, 0x35B5A8FA, 0x42B2986C,
		0xDBBBC9D6, 0xACBCF940, 0x32D86CE3, 0x45DF5C75, 0xDCD60DCF, 0xABD13D59,
		0x26D930AC, 0x51DE003A, 0xC8D75180, 0xBFD06116, 0x21B4F4B5, 0x56B3C423,
		0xCFBA9599, 0xB8BDA50F, 0x2802B89E, 0x5F058808, 0xC60CD9B2, 0xB10BE924,
		0x2F6F7C87, 0x58684C11, 0xC1611DAB, 0xB6662D3D, 0x76DC4190, 0x01DB7106,
		0x98D220BC, 0xEFD5102A, 0x71B18589, 0x06B6B51F, 0x9FBFE4A5, 0xE8B8D433,
		0x7807C9A2, 0x0F00F934, 0x9609A88E, 0xE10E9818, 0x7F6A0DBB, 0x086D3D2D,
		0x91646C97, 0xE6635C01, 0x6B6B51F4, 0x1C6C6162, 0x856530D8, 0xF262004E,
		0x6C0695ED, 0x1B01A57B, 0x8208F4C1, 0xF50FC457, 0x65B0D9C6, 0x12B7E950,
		0x8BBEB8EA, 0xFCB9887C, 0x62DD1DDF, 0x15DA2D49, 0x8CD37CF3, 0xFBD44C65,
		0x4DB26158, 0x3AB551CE, 0xA3BC0074, 0xD4BB30E2, 0x4ADFA541, 0x3DD895D7,
		0xA4D1C46D, 0xD3D6F4FB, 0x4369E96A, 0x346ED9FC, 0xAD678846, 0xDA60B8D0,
		0x44042D73, 0x33031DE5, 0xAA0A4C5F, 0xDD0D7CC9, 0x5005713C, 0x270241AA,
		0xBE0B1010, 0xC90C2086, 0x5768B525, 0x206F85B3, 0xB966D409, 0xCE61E49F,
		0x5EDEF90E, 0x29D9C998, 0xB0D09822, 0xC7D7A8B4, 0x59B33D17, 0x2EB40D81,
		0xB7BD5C3B, 0xC0BA6CAD, 0xEDB88320, 0x9ABFB3B6, 0x03B6E20C, 0x74B1D29A,
		0xEAD54739, 0x9DD277AF, 0x04DB2615, 0x73DC1683, 0xE3630B12, 0x94643B84,
		0x0D6D6A3E, 0x7A6A5AA8, 0xE40ECF0B, 0x9309FF9D, 0x0A00AE27, 0x7D079EB1,
		0xF00F9344, 0x8708A3D2, 0x1E01F268, 0x6906C2FE, 0xF762575D, 0x806567CB,
		0x196C3671, 0x6E6B06E7, 0xFED41B76, 0x89D32BE0, 0x10DA7A5A, 0x67DD4ACC,
		0xF9B9DF6F, 0x8EBEEFF9, 0x17B7BE43, 0x60B08ED5, 0xD6D6A3E8, 0xA1D1937E,
		0x38D8C2C4, 0x4FDFF252, 0xD1BB67F1, 0xA6BC5767, 0x3FB506DD, 0x48B2364B,
		0xD80D2BDA, 0xAF0A1B4C, 0x36034AF6, 0x41047A60, 0xDF60EFC3, 0xA867DF55,
		0x316E8EEF, 0x4669BE79, 0xCB61B38C, 0xBC66831A, 0x256FD2A0, 0x5268E236,
		0xCC0C7795, 0xBB0B4703, 0x220216B9, 0x5505262F, 0xC5BA3BBE, 0xB2BD0B28,
		0x2BB45A92, 0x5CB36A04, 0xC2D7FFA7, 0xB5D0CF31, 0x2CD99E8B, 0x5BDEAE1D,
		0x9B64C2B0, 0xEC63F226, 0x756AA39C, 0x026D930A, 0x9C0906A9, 0xEB0E363F,
		0x72076785, 0x05005713, 0x95BF4A82, 0xE2B87A14, 0x7BB12BAE, 0x0CB61B38,
		0x92D28E9B, 0xE5D5BE0D, 0x7CDCEFB7, 0x0BDBDF21, 0x86D3D2D4, 0xF1D4E242,
		0x68DDB3F8, 0x1FDA836E, 0x81BE16CD, 0xF6B9265B, 0x6FB077E1, 0x18B74777,
		0x88085AE6, 0xFF0F6A70, 0x66063BCA, 0x11010B5C, 0x8F659EFF, 0xF862AE69,
		0x616BFFD3, 0x166CCF45, 0xA00AE278, 0xD70DD2EE, 0x4E048354, 0x3903B3C2,
		0xA7672661, 0xD06016F7, 0x4969474D, 0x3E6E77DB, 0xAED16A4A, 0xD9D65ADC,
		0x40DF0B66, 0x37D83BF0, 0xA9BCAE53, 0xDEBB9EC5, 0x47B2CF7F, 0x30B5FFE9,
		0xBDBDF21C, 0xCABAC28A, 0x53B39330, 0x24B4A3A6, 0xBAD03605, 0xCDD70693,
		0x54DE5729, 0x23D967BF, 0xB3667A2E, 0xC4614AB8, 0x5D681B02, 0x2A6F2B94,
		0xB40BBE37, 0xC30C8EA1, 0x5A05DF1B, 0x2D02EF8D
	);
	protected static $crc32bzip2table = array(
		0x00000000, 0x04C11DB7, 0x09823B6E, 0x0D4326D9, 0x130476DC, 0x17C56B6B,
		0x1A864DB2, 0x1E475005, 0x2608EDB8, 0x22C9F00F, 0x2F8AD6D6, 0x2B4BCB61,
		0x350C9B64, 0x31CD86D3, 0x3C8EA00A, 0x384FBDBD, 0x4C11DB70, 0x48D0C6C7,
		0x4593E01E, 0x4152FDA9, 0x5F15ADAC, 0x5BD4B01B, 0x569796C2, 0x52568B75,
		0x6A1936C8, 0x6ED82B7F, 0x639B0DA6, 0x675A1011, 0x791D4014, 0x7DDC5DA3,
		0x709F7B7A, 0x745E66CD, 0x9823B6E0, 0x9CE2AB57, 0x91A18D8E, 0x95609039,
		0x8B27C03C, 0x8FE6DD8B, 0x82A5FB52, 0x8664E6E5, 0xBE2B5B58, 0xBAEA46EF,
		0xB7A96036, 0xB3687D81, 0xAD2F2D84, 0xA9EE3033, 0xA4AD16EA, 0xA06C0B5D,
		0xD4326D90, 0xD0F37027, 0xDDB056FE, 0xD9714B49, 0xC7361B4C, 0xC3F706FB,
		0xCEB42022, 0xCA753D95, 0xF23A8028, 0xF6FB9D9F, 0xFBB8BB46, 0xFF79A6F1,
		0xE13EF6F4, 0xE5FFEB43, 0xE8BCCD9A, 0xEC7DD02D, 0x34867077, 0x30476DC0,
		0x3D044B19, 0x39C556AE, 0x278206AB, 0x23431B1C, 0x2E003DC5, 0x2AC12072,
		0x128E9DCF, 0x164F8078, 0x1B0CA6A1, 0x1FCDBB16, 0x018AEB13, 0x054BF6A4,
		0x0808D07D, 0x0CC9CDCA, 0x7897AB07, 0x7C56B6B0, 0x71159069, 0x75D48DDE,
		0x6B93DDDB, 0x6F52C06C, 0x6211E6B5, 0x66D0FB02, 0x5E9F46BF, 0x5A5E5B08,
		0x571D7DD1, 0x53DC6066, 0x4D9B3063, 0x495A2DD4, 0x44190B0D, 0x40D816BA,
		0xACA5C697, 0xA864DB20, 0xA527FDF9, 0xA1E6E04E, 0xBFA1B04B, 0xBB60ADFC,
		0xB6238B25, 0xB2E29692, 0x8AAD2B2F, 0x8E6C3698, 0x832F1041, 0x87EE0DF6,
		0x99A95DF3, 0x9D684044, 0x902B669D, 0x94EA7B2A, 0xE0B41DE7, 0xE4750050,
		0xE9362689, 0xEDF73B3E, 0xF3B06B3B, 0xF771768C, 0xFA325055, 0xFEF34DE2,
		0xC6BCF05F, 0xC27DEDE8, 0xCF3ECB31, 0xCBFFD686, 0xD5B88683, 0xD1799B34,
		0xDC3ABDED, 0xD8FBA05A, 0x690CE0EE, 0x6DCDFD59, 0x608EDB80, 0x644FC637,
		0x7A089632, 0x7EC98B85, 0x738AAD5C, 0x774BB0EB, 0x4F040D56, 0x4BC510E1,
		0x46863638, 0x42472B8F, 0x5C007B8A, 0x58C1663D, 0x558240E4, 0x51435D53,
		0x251D3B9E, 0x21DC2629, 0x2C9F00F0, 0x285E1D47, 0x36194D42, 0x32D850F5,
		0x3F9B762C, 0x3B5A6B9B, 0x0315D626, 0x07D4CB91, 0x0A97ED48, 0x0E56F0FF,
		0x1011A0FA, 0x14D0BD4D, 0x19939B94, 0x1D528623, 0xF12F560E, 0xF5EE4BB9,
		0xF8AD6D60, 0xFC6C70D7, 0xE22B20D2, 0xE6EA3D65, 0xEBA91BBC, 0xEF68060B,
		0xD727BBB6, 0xD3E6A601, 0xDEA580D8, 0xDA649D6F, 0xC423CD6A, 0xC0E2D0DD,
		0xCDA1F604, 0xC960EBB3, 0xBD3E8D7E, 0xB9FF90C9, 0xB4BCB610, 0xB07DABA7,
		0xAE3AFBA2, 0xAAFBE615, 0xA7B8C0CC, 0xA379DD7B, 0x9B3660C6, 0x9FF77D71,
		0x92B45BA8, 0x9675461F, 0x8832161A, 0x8CF30BAD, 0x81B02D74, 0x857130C3,
		0x5D8A9099, 0x594B8D2E, 0x5408ABF7, 0x50C9B640, 0x4E8EE645, 0x4A4FFBF2,
		0x470CDD2B, 0x43CDC09C, 0x7B827D21, 0x7F436096, 0x7200464F, 0x76C15BF8,
		0x68860BFD, 0x6C47164A, 0x61043093, 0x65C52D24, 0x119B4BE9, 0x155A565E,
		0x18197087, 0x1CD86D30, 0x029F3D35, 0x065E2082, 0x0B1D065B, 0x0FDC1BEC,
		0x3793A651, 0x3352BBE6, 0x3E119D3F, 0x3AD08088, 0x2497D08D, 0x2056CD3A,
		0x2D15EBE3, 0x29D4F654, 0xC5A92679, 0xC1683BCE, 0xCC2B1D17, 0xC8EA00A0,
		0xD6AD50A5, 0xD26C4D12, 0xDF2F6BCB, 0xDBEE767C, 0xE3A1CBC1, 0xE760D676,
		0xEA23F0AF, 0xEEE2ED18, 0xF0A5BD1D, 0xF464A0AA, 0xF9278673, 0xFDE69BC4,
		0x89B8FD09, 0x8D79E0BE, 0x803AC667, 0x84FBDBD0, 0x9ABC8BD5, 0x9E7D9662,
		0x933EB0BB, 0x97FFAD0C, 0xAFB010B1, 0xAB710D06, 0xA6322BDF, 0xA2F33668,
		0xBCB4666D, 0xB8757BDA, 0xB5365D03, 0xB1F740B4
	);
	protected static $verhoeffmul = array(
		array(0,1,2,3,4,5,6,7,8,9),
		array(1,2,3,4,0,6,7,8,9,5),
		array(2,3,4,0,1,7,8,9,5,6),
		array(3,4,0,1,2,8,9,5,6,7),
		array(4,0,1,2,3,9,5,6,7,8),
		array(5,9,8,7,6,0,4,3,2,1),
		array(6,5,9,8,7,1,0,4,3,2),
		array(7,6,5,9,8,2,1,0,4,3),
		array(8,7,6,5,9,3,2,1,0,4),
		array(9,8,7,6,5,4,3,2,1,0),
	);
	protected static $verhoeffper = array(
		array(0,1,2,3,4,5,6,7,8,9),
		array(1,5,7,6,2,8,3,0,9,4),
		array(5,8,0,3,7,9,6,1,4,2),
		array(8,9,1,6,0,4,3,5,2,7),
		array(9,4,5,3,1,2,6,8,7,0),
		array(4,2,8,6,5,7,3,9,0,1),
		array(2,7,9,3,8,0,6,4,1,5),
		array(7,0,4,6,9,1,3,2,5,8),
	);
	protected static $verhoeffinv = array(0,4,3,2,1,5,6,7,8,9);
	protected static $dammmatrix = array(
		array(0, 3, 1, 7, 5, 9, 8, 6, 4, 2),
		array(7, 0, 9, 2, 1, 5, 4, 8, 6, 3),
		array(4, 2, 0, 6, 8, 7, 1, 3, 5, 9),
		array(1, 7, 5, 0, 9, 8, 3, 4, 2, 6),
		array(6, 1, 2, 3, 0, 4, 5, 9, 7, 8),
		array(3, 6, 7, 4, 2, 0, 9, 5, 8, 1),
		array(5, 8, 6, 9, 7, 2, 0, 1, 3, 4),
		array(8, 9, 4, 5, 3, 6, 2, 0, 1, 7),
		array(9, 4, 3, 8, 6, 1, 7, 2, 0, 5),
		array(2, 5, 8, 1, 4, 3, 6, 7, 9, 0),
	);
	protected static $pearsonT = array(
		0x62, 0x06, 0x55, 0x96, 0x24, 0x17, 0x70, 0xa4, 0x87, 0xcf, 0xa9, 0x05, 0x1a, 0x40, 0xa5, 0xdb,
		0x3d, 0x14, 0x44, 0x59, 0x82, 0x3f, 0x34, 0x66, 0x18, 0xe5, 0x84, 0xf5, 0x50, 0xd8, 0xc3, 0x73,
		0x5a, 0xa8, 0x9c, 0xcb, 0xb1, 0x78, 0x02, 0xbe, 0xbc, 0x07, 0x64, 0xb9, 0xae, 0xf3, 0xa2, 0x0a,
		0xed, 0x12, 0xfd, 0xe1, 0x08, 0xd0, 0xac, 0xf4, 0xff, 0x7e, 0x65, 0x4f, 0x91, 0xeb, 0xe4, 0x79,
		0x7b, 0xfb, 0x43, 0xfa, 0xa1, 0x00, 0x6b, 0x61, 0xf1, 0x6f, 0xb5, 0x52, 0xf9, 0x21, 0x45, 0x37,
		0x3b, 0x99, 0x1d, 0x09, 0xd5, 0xa7, 0x54, 0x5d, 0x1e, 0x2e, 0x5e, 0x4b, 0x97, 0x72, 0x49, 0xde,
		0xc5, 0x60, 0xd2, 0x2d, 0x10, 0xe3, 0xf8, 0xca, 0x33, 0x98, 0xfc, 0x7d, 0x51, 0xce, 0xd7, 0xba,
		0x27, 0x9e, 0xb2, 0xbb, 0x83, 0x88, 0x01, 0x31, 0x32, 0x11, 0x8d, 0x5b, 0x2f, 0x81, 0x3c, 0x63,
		0x9a, 0x23, 0x56, 0xab, 0x69, 0x22, 0x26, 0xc8, 0x93, 0x3a, 0x4d, 0x76, 0xad, 0xf6, 0x4c, 0xfe,
		0x85, 0xe8, 0xc4, 0x90, 0xc6, 0x7c, 0x35, 0x04, 0x6c, 0x4a, 0xdf, 0xea, 0x86, 0xe6, 0x9d, 0x8b,
		0xbd, 0xcd, 0xc7, 0x80, 0xb0, 0x13, 0xd3, 0xec, 0x7f, 0xc0, 0xe7, 0x46, 0xe9, 0x58, 0x92, 0x2c,
		0xb7, 0xc9, 0x16, 0x53, 0x0d, 0xd6, 0x74, 0x6d, 0x9f, 0x20, 0x5f, 0xe2, 0x8c, 0xdc, 0x39, 0x0c,
		0xdd, 0x1f, 0xd1, 0xb6, 0x8f, 0x5c, 0x95, 0xb8, 0x94, 0x3e, 0x71, 0x41, 0x25, 0x1b, 0x6a, 0xa6,
		0x03, 0x0e, 0xcc, 0x48, 0x15, 0x29, 0x38, 0x42, 0x1c, 0xc1, 0x28, 0xd9, 0x19, 0x36, 0xb3, 0x75,
		0xee, 0x57, 0xf0, 0x9b, 0xb4, 0xaa, 0xf2, 0xd4, 0xbf, 0xa3, 0x4e, 0xda, 0x89, 0xc2, 0xaf, 0x6e,
		0x2b, 0x77, 0xe0, 0x47, 0x7a, 0x8e, 0x2a, 0xa0, 0x68, 0x30, 0xf7, 0x67, 0x0f, 0x0b, 0x8a, 0xef
	);
	protected static $keccakrndc = array(
		array(0x00000000, 0x00000001), array(0x00000000, 0x00008082), array(0x80000000, 0x0000808a), array(0x80000000, 0x80008000),
		array(0x00000000, 0x0000808b), array(0x00000000, 0x80000001), array(0x80000000, 0x80008081), array(0x80000000, 0x00008009),
		array(0x00000000, 0x0000008a), array(0x00000000, 0x00000088), array(0x00000000, 0x80008009), array(0x00000000, 0x8000000a),
		array(0x00000000, 0x8000808b), array(0x80000000, 0x0000008b), array(0x80000000, 0x00008089), array(0x80000000, 0x00008003),
		array(0x80000000, 0x00008002), array(0x80000000, 0x00000080), array(0x00000000, 0x0000800a), array(0x80000000, 0x8000000a),
		array(0x80000000, 0x80008081), array(0x80000000, 0x00008080), array(0x00000000, 0x80000001), array(0x80000000, 0x80008008)
	);
	protected static $md2s = array(
		41,  46,  67,  201, 162, 216, 124, 1,   61,  54,  84,  161, 236, 240, 6,
		19,  98,  167, 5,   243, 192, 199, 115, 140, 152, 147, 43,  217, 188,
		76,  130, 202, 30,  155, 87,  60,  253, 212, 224, 22,  103, 66,  111, 24,
		138, 23,  229, 18,  190, 78,  196, 214, 218, 158, 222, 73,  160, 251,
		245, 142, 187, 47,  238, 122, 169, 104, 121, 145, 21,  178, 7,   63,
		148, 194, 16,  137, 11,  34,  95,  33,  128, 127, 93,  154, 90,  144, 50,
		39,  53,  62,  204, 231, 191, 247, 151, 3,   255, 25,  48,  179, 72,  165,
		181, 209, 215, 94,  146, 42,  172, 86,  170, 198, 79,  184, 56,  210,
		150, 164, 125, 182, 118, 252, 107, 226, 156, 116, 4,   241, 69,  157,
		112, 89,  100, 113, 135, 32,  134, 91,  207, 101, 230, 45,  168, 2,   27,
		96,  37,  173, 174, 176, 185, 246, 28,  70,  97,  105, 52,  64,  126, 15,
		85,  71,  163, 35,  221, 81,  175, 58,  195, 92,  249, 206, 186, 197,
		234, 38,  44,  83,  13,  110, 133, 40,  132, 9,   211, 223, 205, 244, 65,
		129, 77,  82,  106, 220, 55,  200, 108, 193, 171, 250, 36,  225, 123,
		8,   12,  189, 177, 74,  120, 136, 149, 139, 227, 99,  232, 109, 233,
		203, 213, 254, 59,  0,   29,  57,  242, 239, 183, 14,  102, 88,  208, 228,
		166, 119, 114, 248, 235, 117, 75,  10,  49,  68,  80,  180, 143, 237,
		31,  26,  219, 153, 141, 51,  159, 17,  131, 20
	);

	private static function keccakf64(&$st, $rounds) {
		$keccakf_rotc = array(1, 3, 6, 10, 15, 21, 28, 36, 45, 55, 2, 14, 27, 41, 56, 8, 25, 43, 62, 18, 39, 61, 20, 44);
		$keccakf_piln = array(10, 7, 11, 17, 18, 3, 5, 16, 8, 21, 24, 4, 15, 23, 19, 13, 12,2, 20, 14, 22, 9, 6, 1);
		$keccakf_rndc = self::$keccakrndc;
		$bc = array();
		for($round = 0; $round < $rounds; ++$round) {
			for($i = 0; $i < 5; ++$i)
				$bc[$i] = array(
					$st[$i][0] ^ $st[$i + 5][0] ^ $st[$i + 10][0] ^ $st[$i + 15][0] ^ $st[$i + 20][0],
					$st[$i][1] ^ $st[$i + 5][1] ^ $st[$i + 10][1] ^ $st[$i + 15][1] ^ $st[$i + 20][1]
				);
			for($i = 0; $i < 5; ++$i) {
				$t = array(
					$bc[($i + 4) % 5][0] ^ (($bc[($i + 1) % 5][0] << 1) | ($bc[($i + 1) % 5][1] >> 31)) & (0xffffffff),
					$bc[($i + 4) % 5][1] ^ (($bc[($i + 1) % 5][1] << 1) | ($bc[($i + 1) % 5][0] >> 31)) & (0xffffffff)
				);
				for($j = 0; $j < 25; $j += 5)
					$st[$j + $i] = array(
						$st[$j + $i][0] ^ $t[0],
						$st[$j + $i][1] ^ $t[1]
					);
			}
			$t = $st[1];
			for($i = 0; $i < 24; ++$i) {
				$j = $keccakf_piln[$i];
				$bc[0] = $st[$j];
				$n = $keccakf_rotc[$i];
				$hi = $t[0];
				$lo = $t[1];
				if($n >= 32) {
					$n -= 32;
					$hi = $t[1];
					$lo = $t[0];
				}
				$st[$j] = array(
					(($hi << $n) | ($lo >> (32 - $n))) & (0xffffffff),
					(($lo << $n) | ($hi >> (32 - $n))) & (0xffffffff)
				);
				$t = $bc[0];
			}
			for($j = 0; $j < 25; $j += 5) {
				for($i = 0; $i < 5; ++$i)
					$bc[$i] = $st[$j + $i];
				for($i = 0; $i < 5; ++$i)
					$st[$j + $i] = array(
						$st[$j + $i][0] ^ ~$bc[($i + 1) % 5][0] & $bc[($i + 2) % 5][0],
						$st[$j + $i][1] ^ ~$bc[($i + 1) % 5][1] & $bc[($i + 2) % 5][1]
					);
			}
			$st[0] = array(
				$st[0][0] ^ $keccakf_rndc[$round][0],
				$st[0][1] ^ $keccakf_rndc[$round][1]
			);
		}
	}
	private static function keccak64($in_raw, $capacity, $outputlength, $suffix, $raw_output) {
		$capacity /= 8;
		$inlen = strlen($in_raw);
		$rsiz = 200 - 2 * $capacity;
		$rsizw = $rsiz / 8;
		$st = array();
		for($i = 0; $i < 25; ++$i)
			$st[] = array(0, 0);
		for($in_t = 0; $inlen >= $rsiz; $inlen -= $rsiz, $in_t += $rsiz) {
			for($i = 0; $i < $rsizw; ++$i) {
				$t = unpack('V*', substr($in_raw, $i * 8 + $in_t, 8));
				$st[$i] = array(
					$st[$i][0] ^ $t[2],
					$st[$i][1] ^ $t[1]
				);
			}
			self::keccakf64($st, 24);
		}
		$temp = substr($in_raw, $in_t, $inlen);
		$temp = str_pad($temp, $rsiz, "\0", STR_PAD_RIGHT);
		$temp[$inlen] = chr($suffix);
		$temp[$rsiz - 1] = chr(ord($temp[$rsiz - 1]) | 0x80);
		for($i = 0; $i < $rsizw; ++$i) {
			$t = unpack('V*', substr($temp, $i * 8, 8));
			$st[$i] = array(
				$st[$i][0] ^ $t[2],
				$st[$i][1] ^ $t[1]
			);
		}
		self::keccakf64($st, 24);
		$out = '';
		for($i = 0; $i < 25; ++$i)
			$out .= $t = pack('V*', $st[$i][1], $st[$i][0]);
		$r = substr($out, 0, $outputlength / 8);
		return $raw_output ? $r : bin2hex($r);
	}
	private static function keccakf32(&$st, $rounds) {
		$keccakf_rotc = array(1, 3, 6, 10, 15, 21, 28, 36, 45, 55, 2, 14, 27, 41, 56, 8, 25, 43, 62, 18, 39, 61, 20, 44);
		$keccakf_piln = array(10, 7, 11, 17, 18, 3, 5, 16, 8, 21, 24, 4, 15, 23, 19, 13, 12,2, 20, 14, 22, 9, 6, 1);
		$keccakf_rndc = array(
			array(0x0000, 0x0000, 0x0000, 0x0001), array(0x0000, 0x0000, 0x0000, 0x8082), array(0x8000, 0x0000, 0x0000, 0x0808a), array(0x8000, 0x0000, 0x8000, 0x8000),
			array(0x0000, 0x0000, 0x0000, 0x808b), array(0x0000, 0x0000, 0x8000, 0x0001), array(0x8000, 0x0000, 0x8000, 0x08081), array(0x8000, 0x0000, 0x0000, 0x8009),
			array(0x0000, 0x0000, 0x0000, 0x008a), array(0x0000, 0x0000, 0x0000, 0x0088), array(0x0000, 0x0000, 0x8000, 0x08009), array(0x0000, 0x0000, 0x8000, 0x000a),
			array(0x0000, 0x0000, 0x8000, 0x808b), array(0x8000, 0x0000, 0x0000, 0x008b), array(0x8000, 0x0000, 0x0000, 0x08089), array(0x8000, 0x0000, 0x0000, 0x8003),
			array(0x8000, 0x0000, 0x0000, 0x8002), array(0x8000, 0x0000, 0x0000, 0x0080), array(0x0000, 0x0000, 0x0000, 0x0800a), array(0x8000, 0x0000, 0x8000, 0x000a),
			array(0x8000, 0x0000, 0x8000, 0x8081), array(0x8000, 0x0000, 0x0000, 0x8080), array(0x0000, 0x0000, 0x8000, 0x00001), array(0x8000, 0x0000, 0x8000, 0x8008)
		);
		$bc = array();
		for($round = 0; $round < $rounds; ++$round) {
			for($i = 0; $i < 5; $i++)
				$bc[$i] = array(
					$st[$i][0] ^ $st[$i + 5][0] ^ $st[$i + 10][0] ^ $st[$i + 15][0] ^ $st[$i + 20][0],
					$st[$i][1] ^ $st[$i + 5][1] ^ $st[$i + 10][1] ^ $st[$i + 15][1] ^ $st[$i + 20][1],
					$st[$i][2] ^ $st[$i + 5][2] ^ $st[$i + 10][2] ^ $st[$i + 15][2] ^ $st[$i + 20][2],
					$st[$i][3] ^ $st[$i + 5][3] ^ $st[$i + 10][3] ^ $st[$i + 15][3] ^ $st[$i + 20][3]
				);
			for($i = 0; $i < 5; ++$i) {
				$t = array(
					$bc[($i + 4) % 5][0] ^ ((($bc[($i + 1) % 5][0] << 1) | ($bc[($i + 1) % 5][1] >> 15)) & (0xffff)),
					$bc[($i + 4) % 5][1] ^ ((($bc[($i + 1) % 5][1] << 1) | ($bc[($i + 1) % 5][2] >> 15)) & (0xffff)),
					$bc[($i + 4) % 5][2] ^ ((($bc[($i + 1) % 5][2] << 1) | ($bc[($i + 1) % 5][3] >> 15)) & (0xffff)),
					$bc[($i + 4) % 5][3] ^ ((($bc[($i + 1) % 5][3] << 1) | ($bc[($i + 1) % 5][0] >> 15)) & (0xffff))
				);
				for($j = 0; $j < 25; $j += 5)
					$st[$j + $i] = array(
						$st[$j + $i][0] ^ $t[0],
						$st[$j + $i][1] ^ $t[1],
						$st[$j + $i][2] ^ $t[2],
						$st[$j + $i][3] ^ $t[3]
					);
			}
			$t = $st[1];
			for($i = 0; $i < 24; ++$i) {
				$j = $keccakf_piln[$i];
				$bc[0] = $st[$j];
				$n = $keccakf_rotc[$i] >> 4;
				$m = $keccakf_rotc[$i] % 16;
				$st[$j] = array(
					((($t[(0+$n) %4] << $m) | ($t[(1+$n) %4] >> (16-$m))) & (0xffff)),
					((($t[(1+$n) %4] << $m) | ($t[(2+$n) %4] >> (16-$m))) & (0xffff)),
					((($t[(2+$n) %4] << $m) | ($t[(3+$n) %4] >> (16-$m))) & (0xffff)),
					((($t[(3+$n) %4] << $m) | ($t[(0+$n) %4] >> (16-$m))) & (0xffff))
				);
				$t = $bc[0];
			}
			for($j = 0; $j < 25; $j += 5) {
				for($i = 0; $i < 5; ++$i)
					$bc[$i] = $st[$j + $i];
				for($i = 0; $i < 5; ++$i)
					$st[$j + $i] = array(
						$st[$j + $i][0] ^ ~$bc[($i + 1) % 5][0] & $bc[($i + 2) % 5][0],
						$st[$j + $i][1] ^ ~$bc[($i + 1) % 5][1] & $bc[($i + 2) % 5][1],
						$st[$j + $i][2] ^ ~$bc[($i + 1) % 5][2] & $bc[($i + 2) % 5][2],
						$st[$j + $i][3] ^ ~$bc[($i + 1) % 5][3] & $bc[($i + 2) % 5][3]
					);
			}
			$st[0] = array(
				$st[0][0] ^ $keccakf_rndc[$round][0],
				$st[0][1] ^ $keccakf_rndc[$round][1],
				$st[0][2] ^ $keccakf_rndc[$round][2],
				$st[0][3] ^ $keccakf_rndc[$round][3]
			);
		}
	}
	private static function keccak32($in_raw, $capacity, $outputlength, $suffix, $raw_output) {
		$capacity /= 8;
		$inlen = strlen($in_raw);
		$rsiz = 200 - 2 * $capacity;
		$rsizw = $rsiz / 8;
		$st = array();
		for($i = 0; $i < 25; ++$i)
			$st[] = array(0, 0, 0, 0);
		for($in_t = 0; $inlen >= $rsiz; $inlen -= $rsiz, $in_t += $rsiz) {
			for($i = 0; $i < $rsizw; ++$i) {
				$t = unpack('v*', substr($in_raw, $i * 8 + $in_t, 8));
				$st[$i] = array(
					$st[$i][0] ^ $t[4],
					$st[$i][1] ^ $t[3],
					$st[$i][2] ^ $t[2],
					$st[$i][3] ^ $t[1]
				);
			}
			self::keccakf32($st, 24);
		}
		$temp = substr($in_raw, $in_t, $inlen);
		$temp = str_pad($temp, $rsiz, "\0", STR_PAD_RIGHT);
		$temp[$inlen] = chr($suffix);
		$temp[$rsiz - 1] = chr((int) $temp[$rsiz - 1] | 0x80);
		for($i = 0; $i < $rsizw; ++$i) {
			$t = unpack('v*', substr($temp, $i * 8, 8));
			$st[$i] = array(
				$st[$i][0] ^ $t[4],
				$st[$i][1] ^ $t[3],
				$st[$i][2] ^ $t[2],
				$st[$i][3] ^ $t[1]
			);
		}
		self::keccakf32($st, 24);
		$out = '';
		for($i = 0; $i < 25; $i++)
			$out .= $t = pack('v*', $st[$i][3],$st[$i][2], $st[$i][1], $st[$i][0]);
		$r = substr($out, 0, $outputlength / 8);
		return $raw_output ? $r: bin2hex($r);
	}
	private static function keccak($in_raw, $capacity, $outputlength, $suffix, $raw){
		return PHP_INT_SIZE === 8
			? self::keccak64($in_raw, $capacity, $outputlength, $suffix, $raw)
			: self::keccak32($in_raw, $capacity, $outputlength, $suffix, $raw);
	}
	private static function sha3_pad($padLength, $padType){
		switch($padType){
			case 3:
				$temp = "\x1F" . str_repeat("\0", $padLength - 1);
				$temp[$padLength - 1] = $temp[$padLength - 1] | "\x80";
				return $temp;
			default:
				return $padLength == 1 ? "\x86" : "\x06" . str_repeat("\0", $padLength - 2) . "\x80";
		}
	}
	private static function sha3_32($p, $c, $r, $d, $padType){
		$block_size = $r >> 3;
		$padLength = $block_size - (strlen($p) % $block_size);
		$num_ints = $block_size >> 2;
		$p.= self::sha3_pad($padLength, $padType);
		$n = strlen($p) / $r;
		$s = array(
			array(array(0, 0), array(0, 0), array(0, 0), array(0, 0), array(0, 0)),
			array(array(0, 0), array(0, 0), array(0, 0), array(0, 0), array(0, 0)),
			array(array(0, 0), array(0, 0), array(0, 0), array(0, 0), array(0, 0)),
			array(array(0, 0), array(0, 0), array(0, 0), array(0, 0), array(0, 0)),
			array(array(0, 0), array(0, 0), array(0, 0), array(0, 0), array(0, 0))
		);
		$p = str_split($p, $block_size);
		foreach($p as $pi) {
			$pi = unpack('V*', $pi);
			$x = $y = 0;
			for($i = 1; $i <= $num_ints; $i += 2) {
				$s[$x][$y][0]^= $pi[$i + 1];
				$s[$x][$y][1]^= $pi[$i];
				if(++$y == 5) {
					$y = 0;
					++$x;
				}
			}
			self::sha3proc32($s);
		}
		$z = '';
		$i = $j = 0;
		while(strlen($z) < $d) {
			$z.= pack('V2', $s[$i][$j][1], $s[$i][$j++][0]);
			if($j == 5) {
				$j = 0;
				++$i;
				if($i == 5) {
					$i = 0;
					self::sha3proc32($s);
				}
			}
		}
		return $z;
	}
	private static function sha3proc32(&$s){
		$ro = array(
			array( 0,  1, 62, 28, 27),
			array(36, 44,  6, 55, 20),
			array( 3, 10, 43, 25, 39),
			array(41, 45, 15, 21,  8),
			array(18,  2, 61, 56, 14)
		);
		$rc = array(
			array(0, 1),
			array(0, 32898),
			array(-2147483648, 32906),
			array(-2147483648, -2147450880),
			array(0, 32907),
			array(0, -2147483647),
			array(-2147483648, -2147450751),
			array(-2147483648, 32777),
			array(0, 138),
			array(0, 136),
			array(0, -2147450871),
			array(0, -2147483638),
			array(0, -2147450741),
			array(-2147483648, 139),
			array(-2147483648, 32905),
			array(-2147483648, 32771),
			array(-2147483648, 32770),
			array(-2147483648, 128),
			array(0, 32778),
			array(-2147483648, -2147483638),
			array(-2147483648, -2147450751),
			array(-2147483648, 32896),
			array(0, -2147483647),
			array(-2147483648, -2147450872)
		);
		for($round = 0; $round < 24; ++$round) {
			$parity = $rotated = array();
			for($i = 0; $i < 5; $i++) {
				$parity[] = array(
					$s[0][$i][0] ^ $s[1][$i][0] ^ $s[2][$i][0] ^ $s[3][$i][0] ^ $s[4][$i][0],
					$s[0][$i][1] ^ $s[1][$i][1] ^ $s[2][$i][1] ^ $s[3][$i][1] ^ $s[4][$i][1]
				);
				$rotated[] = XNMath::rl32($parity[$i], 1);
			}
			$temp = array(
				array($parity[4][0] ^ $rotated[1][0], $parity[4][1] ^ $rotated[1][1]),
				array($parity[0][0] ^ $rotated[2][0], $parity[0][1] ^ $rotated[2][1]),
				array($parity[1][0] ^ $rotated[3][0], $parity[1][1] ^ $rotated[3][1]),
				array($parity[2][0] ^ $rotated[4][0], $parity[2][1] ^ $rotated[4][1]),
				array($parity[3][0] ^ $rotated[0][0], $parity[3][1] ^ $rotated[0][1])
			);
			for($i = 0; $i < 5; ++$i)
				for($j = 0; $j < 5; ++$j) {
					$s[$i][$j][0]^= $temp[$j][0];
					$s[$i][$j][1]^= $temp[$j][1];
				}
			$st = $s;
			for($i = 0; $i < 5; ++$i)
				for($j = 0; $j < 5; ++$j)
					$st[(2 * $i + 3 * $j) % 5][$j] = XNMath::rl32($s[$j][$i], $ro[$j][$i]);
			for($i = 0; $i < 5; ++$i) {
				$s[$i][0] = array(
					$st[$i][0][0] ^ (~$st[$i][1][0] & $st[$i][2][0]),
					$st[$i][0][1] ^ (~$st[$i][1][1] & $st[$i][2][1])
				);
				$s[$i][1] = array(
					$st[$i][1][0] ^ (~$st[$i][2][0] & $st[$i][3][0]),
					$st[$i][1][1] ^ (~$st[$i][2][1] & $st[$i][3][1])
				);
				$s[$i][2] = array(
					$st[$i][2][0] ^ (~$st[$i][3][0] & $st[$i][4][0]),
					$st[$i][2][1] ^ (~$st[$i][3][1] & $st[$i][4][1])
				);
				$s[$i][3] = array(
					$st[$i][3][0] ^ (~$st[$i][4][0] & $st[$i][0][0]),
					$st[$i][3][1] ^ (~$st[$i][4][1] & $st[$i][0][1])
				);
				$s[$i][4] = array(
					$st[$i][4][0] ^ (~$st[$i][0][0] & $st[$i][1][0]),
					$st[$i][4][1] ^ (~$st[$i][0][1] & $st[$i][1][1])
				);
			}
			$s[0][0][0]^= $rc[$round][0];
			$s[0][0][1]^= $rc[$round][1];
		}
	}
	private static function sha3_64($p, $c, $r, $d, $padType){
		$block_size = $r >> 3;
		$padLength = $block_size - (strlen($p) % $block_size);
		$num_ints = $block_size >> 2;
		$p.= self::sha3_pad($padLength, $padType);
		$n = strlen($p) / $r;
		$s = array(
			array(0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0),
			array(0, 0, 0, 0, 0)
		);
		$p = str_split($p, $block_size);
		foreach($p as $pi) {
			$pi = unpack('P*', $pi);
			$x = $y = 0;
			foreach($pi as $subpi) {
				$s[$x][$y++]^= $subpi;
				if($y == 5) {
					$y = 0;
					++$x;
				}
			}
			self::sha3proc64($s);
		}
		$z = '';
		$i = $j = 0;
		while(strlen($z) < $d) {
			$z.= pack('P', $s[$i][$j++]);
			if($j == 5) {
				$j = 0;
				++$i;
				if($i == 5) {
					$i = 0;
					self::sha3proc64($s);
				}
			}
		}
		return $z;
	}
	private static function sha3proc64(&$s){
		$ro = array(
			array( 0,  1, 62, 28, 27),
			array(36, 44,  6, 55, 20),
			array( 3, 10, 43, 25, 39),
			array(41, 45, 15, 21,  8),
			array(18,  2, 61, 56, 14)
		);
		$rc = array(
			1,
			32898,
			-9223372036854742902,
			-9223372034707259392,
			32907,
			2147483649,
			-9223372034707259263,
			-9223372036854743031,
			138,
			136,
			2147516425,
			2147483658,
			2147516555,
			-9223372036854775669,
			-9223372036854742903,
			-9223372036854743037,
			-9223372036854743038,
			-9223372036854775680,
			32778,
			-9223372034707292150,
			-9223372034707259263,
			-9223372036854742912,
			2147483649,
			-9223372034707259384
		);
		for($round = 0; $round < 24; ++$round) {
			$parity = array();
			for($i = 0; $i < 5; ++$i)
				$parity[] = $s[0][$i] ^ $s[1][$i] ^ $s[2][$i] ^ $s[3][$i] ^ $s[4][$i];
			$tmp = array(
				$parity[4] ^ XNMath::rl64($parity[1], 1),
				$parity[0] ^ XNMath::rl64($parity[2], 1),
				$parity[1] ^ XNMath::rl64($parity[3], 1),
				$parity[2] ^ XNMath::rl64($parity[4], 1),
				$parity[3] ^ XNMath::rl64($parity[0], 1)
			);
			for($i = 0; $i < 5; ++$i)
				for($j = 0; $j < 5; ++$j)
					$s[$i][$j]^= $tmp[$j];
			$st = $s;
			for($i = 0; $i < 5; ++$i)
				for($j = 0; $j < 5; ++$j)
					$st[(2 * $i + 3 * $j) % 5][$j] = XNMath::rl64($s[$j][$i], $ro[$j][$i]);
			for($i = 0; $i < 5; ++$i)
				$s[$i] = array(
					$st[$i][0] ^ (~$st[$i][1] & $st[$i][2]),
					$st[$i][1] ^ (~$st[$i][2] & $st[$i][3]),
					$st[$i][2] ^ (~$st[$i][3] & $st[$i][4]),
					$st[$i][3] ^ (~$st[$i][4] & $st[$i][0]),
					$st[$i][4] ^ (~$st[$i][0] & $st[$i][1])
				);
			$s[0][0]^= $rc[$round];
		}
	}
	private static function sha3($p, $c, $r, $d, $padType, $raw){
		return PHP_INT_SIZE === 8
			? ($raw === true ? self::sha3_64($p, $c, $r, $d, $padType) : bin2hex(self::sha3_64($p, $c, $r, $d, $padType)))
			: ($raw === true ? self::sha3_32($p, $c, $r, $d, $padType) : bin2hex(self::sha3_32($p, $c, $r, $d, $padType)));
	}
	public static function crc32($data){
		if(function_exists('crc32'))return crc32($data);
		$c = 0xffffffff;
		for($i = 0; isset($data[$i]); ++$i)
			$c = self::$crc32table[($c ^ ord($data[$i])) & 0xff] ^ (($c >> 8) & 0xffffff);
		return $c ^ 0xffffffff;
	}
	public static function crc16($data){
		$c = 0;
		for($i = 0; isset($data[$i]); ++$i)
			$c = self::$crc16table[($c ^ ord($data[$i])) & 0xff] ^ ($c >> 8);
		return $c;
	}
	public static function crc8($data){
		$c = 0;
		for($i = 0; isset($data[$i]); ++$i)
			$c = self::$crc8table[($c ^ ord($data[$i])) & 0xff] ^ ($c >> 8);
		return $c;
	}
	public static function crc32bzip2($data){
		$c = 0xffffffff;
		for($i = 0; isset($data[$i]); ++$i)
			$c = self::$crc32bzip2table[(($c >> 24) ^ ord($data[$i])) & 0xff] ^ ($c << 8);
		return $c ^ 0xffffffff;
	}
	public static function adler32($data){
		$a = 1;
		$b = 0;
		for($i = 0; isset($data[$i]); ++$i){
			$a = ($a + ord($data[$i])) % 65521;
			$b = ($b + $a) % 65521;
		}
		return ($b << 16) | $a;
	}
	public static function tdesktop_md5($data, $raw = null){
		$data = implode('', array_map('strrev', str_split(md5($data, true), 2)));
		return $raw === true ? $data : bin2hex($data);
	}
	public static function bsd($data){
		$sum = 0;
		for($i = 0; isset($data[$i]); ++$i)
			$sum = (($sum >> 1) + (($sum & 1) << 15) + chr($data[$i])) & 0xffff;
		return $sum;
	}
	public static function xor8($data){
		$lrc = 0;
		for($i = 0; isset($data[$i]); ++$i)
			$lrc = ($lrc + ord($data[$i])) & 0xff;
		return (($lrc ^ 0xff) + 1) & 0xff;
	}
	public static function luhn($data){
		$data .= '0';
		$sum = 0;
		$i = strlen($data);
		$odd = $i % 2;
		while(--$i >= 0) {
			$h = ord($data[$i]);
			$sum += $h;
			$odd === ($i % 2) ? $h > 4 ? ($sum += $h - 9) : ($sum += $h) : null;
		}
		return (10 - ($sum % 10)) % 10;
	}
	public static function verhoeff($number){
		$ck = 0;
		$l = strlen($number);
		$i = $l - 1;
		while(--$i >= 0)
			$ck = self::$verhoeffmul[$ck][self::$verhoeffper[($l - $i) % 8][(int)$number[$i]]];
		return self::$verhoeffinv[$ck];
	}
	public static function damm($number){
		$interim = 0;
		for($i = 0; isset($data[$i]); ++$i)
			$interim = self::$dammmatrix[$interim][(int)$data[$i]];
		return $interim;
	}
	private static function pearson16($data){
		$hash = array();
		$data = array_values(unpack('C*', $data));
		for($j = 0; $j < 8; ++$j) {
			$h = self::$pearsonT[($data[0] + $j) & 0xff];
			for($i = 1; isset($data[$i]); ++$i)
			   $h = self::$pearsonT[$h ^ $data[$i]];
			$hash[$j] = $h;
		}
		return chr($hash[0]) . chr($hash[1]) . chr($hash[2]) . chr($hash[3]) .
			   chr($hash[4]) . chr($hash[5]) . chr($hash[6]) . chr($hash[7]);
	}
	private static function md2($m){
		$length = strlen($m);
		$pad = 16 - ($length & 0xf);
		$m .= str_repeat(chr($pad), $pad);
		$length |= 0xf;
		$c = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		$l = "\0";
		for($i = 0; $i < $length; $i += 16)
			for($j = 0; $j < 16; ++$j)
				$l = $c[$j] = chr(self::$md2s[ord($m[$i + $j] ^ $l)] ^ ord($c[$j]));
		$m .= $c;
		$length += 16;
		$x = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		for($i = 0; $i < $length; $i += 16) {
			for($j = 0; $j < 16; ++$j) {
				$x[$j + 16] = $m[$i + $j];
				$x[$j + 32] = $x[$j + 16] ^ $x[$j];
			}
			$t = "\0";
			for($j = 0; $j < 18; ++$j) {
				for($k = 0; $k < 48; ++$k)
					$x[$k] = $t = $x[$k] ^ chr(self::$md2s[ord($t)]);
				$t = chr(ord($t) + $j);
			}
		}
		return $x;
	}
	public static function mybb($plaintext, $salt = '', $raw = null){
		return md5(md5($salt) . md5($plaintext), $raw);
	}
	public static function hash($algo, $data, $raw = null){
		$algo = strtolower($algo);
		if(__xnlib_data::$installedMhash && defined('MHASH_' . strtoupper($algo)))
			return $raw === true ? mhash(constant('MHASH_' . strtoupper($algo)), $data) : self::hexencode(mhash(constant('MHASH_' . strtoupper($algo)), $data));
		if(__xnlib_data::$installedHash && in_array($algo, hash_algos()))
			return hash($algo, $data, $raw === true);
		switch($algo){
			case 'adler32':
				return $raw === true ? pack('N', self::adler32($data)) : self::hexencode(pack('N', self::adler32($data)));
			case 'crc8':
				return $raw === true ? chr(self::crc8($data)) : self::hexencode(chr(self::crc8($data)));
			case 'crc16':
				return $raw === true ? pack('n', self::crc16($data)) : self::hexencode(pack('n', self::crc16($data)));
			case 'crc32b':
				return $raw === true ? pack('i', self::crc32($data)) : self::hexencode(pack('i', self::crc32($data)));
			case 'crc32':
				return $raw === true ? pack('i', self::crc32bzip2($data)) : self::hexencode(pack('i', self::crc32bzip2($data)));
			case 'bsd':
				return $raw === true ? pack('n', self::bsd($data)) : bin2hex(pack('n', self::bsd($data)));
			case 'xor8':
				return $raw === true ? chr(self::xor8($data)) : bin2hex(chr(self::xor8($data)));
			case 'keccak224':
				return self::keccak($data, 224, 224, 1, $raw === true);
			case 'keccak256':
				return self::keccak($data, 256, 256, 1, $raw === true);
			case 'keccak384':
				return self::keccak($data, 384, 384, 1, $raw === true);
			case 'keccak512':
				return self::keccak($data, 512, 512, 1, $raw === true);
			case 'shake128':
				return self::keccak($data, 128, 256, 0x1f, $raw === true);
			case 'shake256':
				return self::keccak($data, 256, 512, 0x1f, $raw === true);
			case 'sha3-224':
				return substr(self::sha3($data, 448, 1152, 28, 2, $raw === true), 0, -8);
			case 'sha3-224-full':
				return self::sha3($data, 448, 1152, 28, 2, $raw === true);
			case 'sha3-256':
				return self::sha3($data, 512, 1088, 32, 2, $raw === true);
			case 'sha3-384':
				return self::sha3($data, 768, 832, 48, 2, $raw === true);
			case 'sha3-512':
				return self::sha3($data, 1024, 576, 64, 2, $raw === true);
			case 'pearson16':
				return $raw === true ? self::pearson16($data) : self::hexencode(self::pearson16($data));
			case 'md2':
				return $raw === true ? substr(self::md2($data), 0, 16) : self::hexencode(substr(self::md2($data), 0, 16));
			case 'md2-full':
				return $raw === true ? self::md2($data) : self::hexencode(self::md2($data));
			case 'ntlm':
				return self::hash('md4', self::iconv($data, 'utf-8', 'utf-16le'), $raw === true);
			case 'mysql5':
				return self::hash('sha1', self::hash('sha1', $data, true), $raw === true);
			case 'add-chars':
				return $raw === true ? chr(array_add(unpack('C*', $data))) : str_pad(dechex(array_add(unpack('C*', $data)) & 0xff), 2, '0', STR_PAD_LEFT);
		}
		if(in_array($algo, self::crcalgos())){
			$length = self::crcalgo($algo);
			$length = ceil($length['length'] / 8);
			switch($length){
				case 1:
					return $raw === true ? chr(self::crc($algo, $data)) : bin2hex(chr(self::crc($algo, $data)));
				case 2:
					return $raw === true ? pack('n', self::crc($algo, $data)) : bin2hex(pack('n', self::crc($algo, $data)));
				case 3:
					return $raw === true ? substr(pack('i', self::crc($algo, $data)), 0, -1) : bin2hex(substr(pack('i', self::crc($algo, $data)), 0, -1));
				case 4:
					return $raw === true ? pack('i', self::crc($algo, $data)) : bin2hex(pack('i', self::crc($algo, $data)));
			}
		}
		new XNError('XNCrypt::hash', "Unknown hashing algorithm: $algo", XNError::WARNING);
		return false;
	}
	public static function hash_repeat($algo, $data, $length = null, $raw = null){
		if($length === null)$length = strlen($data);
		$hash = self::hash($algo, $data, $raw);
		while(strlen($hash) < $length){
			$hash .= self::hash($algo, $hash . $data, $raw);
		}
		return substr($hash, 0, $length);
	}
	public static function hash_length($algo, $raw = null){
		$algos = array(
			"md2" => 16,		 "md4" => 16,		 "md5" => 16,
			"sha1" => 20,		"sha224" => 28,	  "sha256" => 32,
			"sha384" => 48,	  "sha512/224" => 28,  "sha512/256" => 32,
			"sha512" => 64,	  "sha3-224" => 28,	"sha3-256" => 32,
			"sha3-384" => 48,	"sha3-512" => 64,	"ripemd128" => 16,
			"ripemd160" => 20,   "ripemd256" => 32,   "ripemd320" => 40,
			"whirlpool" => 64,   "tiger128,3" => 16,  "tiger160,3" => 20,
			"tiger192,3" => 24,  "tiger128,4" => 16,  "tiger160,4" => 20,
			"tiger192,4" => 24,  "snefru" => 32,	  "snefru256" => 32,
			"gost" => 32,		"gost-crypto" => 32, "adler32" => 4,
			"crc32" => 4,		"crc32b" => 4,	   "crc16" => 2,
			"crc8" => 1,		 "bsd" => 2,		  "pearson" => 8,
			"fnv132" => 4,	   "fnv1a32" => 4,
			"fnv164" => 8,	   "fnv1a64" => 8,	  "joaat" => 4,
			"haval128,3" => 16,  "haval160,3" => 20,  "haval192,3" => 24,
			"haval224,3" => 28,  "haval256,3" => 32,  "haval128,4" => 16,
			"haval160,4" => 20,  "haval192,4" => 24,  "haval224,4" => 28,
			"haval256,4" => 32,  "haval128,5" => 16,  "haval160,5" => 20,
			"haval192,5" => 24,  "haval224,5" => 28,  "haval256,5" => 32,
			"keccak224" => 56,   "keccak256" => 64,   "keccak384" => 96,
			"keccak512" => 128,  "shake128"  => 64,   "shake256"  => 128,
			"ntlm" => 16,        "mysql5" => 20
		);
		if(!isset($algos[$algo]))return false;
		$length = $algos[$algo];
		if($raw === null)return $length;
		if($raw === true)return $length * 2;
		$algos = array(
			"md2" => 1,		"md4" => 4,		"md5" => 4,
			"sha256" => 2,	 "sha512/256" => 4, "sha512" => 2,
			"ripemd128" => 4,  "ripemd256" => 2,  "whirlpool" => 1,
			"tiger128,3" => 4, "tiger128,4" => 4, "snefru" => 1,
			"snefru256" => 1,  "gost" => 1,	   "gost-crypto" => 1,
			"haval128,3" => 8, "haval256,3" => 4, "haval128,4" => 8,
			"haval256,4" => 4, "haval128,5" => 8, "haval256,5" => 4,
			"ntlm" => 4
		);
		if(!isset($algos[$algo]))return null;
		return $length * $algos[$algo];
	}
	public static function hash_algos(){
		return array(
			"md2",		"md4",		"md5",		 "sha1",		"sha224",
			"sha256",	 "sha384",	 "sha512/224",  "sha512/256",  "sha512",
			"sha3-224",   "sha3-256",   "sha3-384",	"sha3-512",	"ripemd128",
			"ripemd160",  "ripemd256",  "ripemd320",   "whirlpool",   "tiger128,3",
			"tiger160,3", "tiger192,3", "tiger128,4",  "tiger160,4",  "tiger192,4",
			"snefru",	 "snefru256",  "gost",		"gost-crypto", "adler32",
			"crc32",	  "crc32b",	 "crc16",	   "crc8",		"bsd",
			"pearson",	"fnv132",
			"fnv1a32",	"fnv164",	  "fnv1a64",	"joaat",	   "haval128,3",
			"haval160,3", "haval192,3", "haval224,3",  "haval256,3",  "haval128,4",
			"haval160,4", "haval192,4", "haval224,4",  "haval256,4",  "haval128,5",
			"haval160,5", "haval192,5", "haval224,5",  "haval256,5",  "keccak224",
			"keccak256",  "keccak384",  "keccak512",   "shake128",	"shake256",
			"ntlm",       "mysql5"
		);
	}
	public static function hash_hmac_algos(){
		return array(
			"md2",		"md4",		"md5",		"sha256",	 "sha512/256",
			"sha512",	 "ripemd128",  "ripemd256",  "whirlpool",  "tiger128,3",
			"tiger128,4", "snefru",	 "snefru256",  "gost",	   "gost-crypto",
			"haval128,3", "haval256,3", "haval128,4", "haval256,4", "haval128,5",
			"haval256,5", "ntlm"
		);
	}
	public static function hash_hmac($algo, $data, $key, $raw = null) {
		if(__xnlib_data::$installedHashHmac && in_array($algo, hash_hmac_algos()))
			return hash_hmac($algo, $data, $key, $raw === true);
		if(__xnlib_data::$installedMhash && defined('MHASH_' . strtoupper($algo)))
			return $raw === true ? mhash(constant('MHASH_' . strtoupper($algo)), $data, $key) :
				bin2hex(mhash(constant('MHASH_' . strtoupper($algo)), $data, $key));
		$b = self::hash_length($algo);
		if($b === false){
			trigger_error("XNCrypt::hash_hmac(): Unknown hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($b === null){
			trigger_error("XNCrypt::hash_hmac(): Non-cryptographic hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if(strlen($key) > $b)
			$key = self::hash($algo, $key, true);
		$key = str_pad($key, $b, "\0");
		$ipad = str_pad('', $b, "\x36");
		$opad = str_pad('', $b, "\x5c");
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		return self::hash($algo, $k_opad . self::hash($algo, $k_ipad . $data, true), $raw === true);
	}
	public static function hash_hkdf($algo, $ikm, $length = 0, $info = '', $salt = '', $hex = null){
		if(__xnlib_data::$installedHashHkdf && __xnlib_data::$installedHashHmac && in_array($algo, hash_hmac_algos()))
			return $hex === true ? bin2hex(hash_hkdf($algo, $ikm, $length, $info, $salt)) : hash_hkdf($algo, $ikm, $length, $info, $salt);
		if($length < 0){
			trigger_error("XNCrypt::hash_hkdf(): Length must be greater than or equal to 0: $length", E_USER_WARNING);
			return false;
		}
		$size = self::hash_length($algo);
		if($size === false){
			trigger_error("XNCrypt::hash_hkdf(): Unknown hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($size === null){
			trigger_error("XNCrypt::hash_hkdf(): Non-cryptographic hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($length > $size * 255){
			trigger_error("XNCrypt::hash_hkdf(): Length must be less than or equal to " . ($size * 255) . ": $length", E_USER_WARNING);
			return false;
		}
		if($length === 0)
			$length = $size;
		if($salt === '')
			$salt = str_repeat("\0", $size);
		$prk = self::hash_hmac($algo, $ikm, $salt, true);
		$okm = '';
		for($keyBlock = '', $blockIndex = 1; !isset($okm[$length - 1]); ++$blockIndex){
			$keyBlock = self::hash_hmac($algo, $keyBlock . $info . chr($blockIndex), $prk, true);
			$okm .= $keyBlock;
		}
		return substr($hex === true ? bin2hex($okm) : $okm, 0, $length);
	}
	public static function hash_pbkdf1($algo, $password, $salt, $iterations, $length = 0, $raw = null){
		if($length < 0){
			trigger_error("XNCrypt::hash_pbkdf1(): Length must be greater than or equal to 0: $length", E_USER_WARNING);
			return false;
		}
		$size = self::hash_length($algo, $raw === true);
		if($size === false){
			trigger_error("XNCrypt::hash_pbkdf1(): Unknown hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($size === null){
			trigger_error("XNCrypt::hash_pbkdf1(): Non-cryptographic hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($length == 0)
			$length = $size;
		$tmp = $password . $salt;
		for($i = 0; $i < $iterations; ++$i)
			$tmp = self::hash($algo, $tmp, true);
		return substr($raw === true ? $tmp : bin2hex($tmp), 0, $length);
	}
	public static function hash_pbkdf2($algo, $password, $salt, $iterations, $length = 0, $raw = null){
		if(__xnlib_data::$installedHashPbkdf2 && __xnlib_data::$installedHashHmac && in_array($algo, hash_hmac_algos()))
			return hash_pbkdf2($algo, $password, $salt, $iterations, $length, $raw === true);
		if($length < 0){
			trigger_error("XNCrypt::hash_pbkdf2(): Length must be greater than or equal to 0: $length", E_USER_WARNING);
			return false;
		}
		$size = self::hash_length($algo, $raw === true);
		if($size === false){
			trigger_error("XNCrypt::hash_pbkdf2(): Unknown hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($size === null){
			trigger_error("XNCrypt::hash_pbkdf2(): Non-cryptographic hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($length == 0)
			$length = $size;
		$output = '';
		$block_count = ceil($length / $size);
		for($block = 1; $block <= $block_count; ++$block) {
			$key1 = $key2 = self::hash_hmac($algo, $salt . pack('N', $block), $password, true);
			for($iteration = 1; $iteration < $iterations; ++$iteration)
				$key2 ^= $key1 = self::hash_hmac($algo, $key1, $password, true);
			$output .= $key2;
		}
		return substr($raw === true ? $output : bin2hex($output), 0, $length);
	}
	public static function hash_schneier($algo, $password, $salt, $iterations, $length = 0, $raw = null){
		if($length < 0){
			trigger_error("XNCrypt::hash_schneier(): Length must be greater than or equal to 0: $length", E_USER_WARNING);
			return false;
		}
		$saltlen = strlen($salt);
		if($saltlen > PHP_INT_MAX - 4) {
			trigger_error("XNCrypt::hash_schneier(): Supplied salt is too long, max of PHP_INT_MAX - 4 bytes: $saltlen supplied", E_USER_WARNING);
			return false;
		}
		$size = self::hash_length($algo, $raw === true);
		if($size === false){
			trigger_error("XNCrypt::hash_schneier(): Unknown hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($size === null){
			trigger_error("XNCrypt::hash_schneier(): Non-cryptographic hashing algorithm: $algo", E_USER_WANING);
			return false;
		}
		if($length == 0)
			$length = $size;
		$tmp = self::hash($algo, $password . $salt, true);
		for($i = 2; $i <= $iterations; ++$i)
			$tmp = self::hash($algo, $tmp . $password . $salt, true);
		return substr($raw === true ? $tmp : bin2hex($tmp), 0, $length);
	}
	public static function hash_equal($hash, $data, $algo = 'auto'){
		if(ctype_xdigit($hash))$raw = false;
		else $raw = true;
		if($algo == 'auto'){
			foreach(self::hash_algos() as $algo)
				if(self::hash($algo, $data, $raw) == $hash)
					return $algo;
			return false;
		}
		if(self::hash($algo, $data, $raw) == $hash)
			return $algo;
		return false;
	}

	public static function crctable($poly, $bitlen = 32, $revin = null, $revout = null){
		$mask = (((1 << ($bitlen - 1)) - 1) << 1) | 1;
		$highBit = 1 << ($bitlen - 1);
		$crctab = array();
		for($i = 0; $i < 256; ++$i) {
			$crc = $i;
			if($revin === true)
				$crc = xnmath::brev($crc, 8);
			$crc <<= $bitlen - 8;
			for($j = 0; $j < 8; ++$j) {
				$bit = $crc & $highBit;
				$crc <<= 1;
				if($bit)
					$crc ^= $poly;
			}
			if($revout === true)
				$crc = xnmath::brev($crc, $bitlen);
			$crc &= $mask;
			$crctab[] = $crc;
		}
		return $crctab;
	}
	public static function crcalgo($algo){
		$algo = strtolower($algo);
		$algos = array(
			'crc1'			    => array(0x1, 0x1, 0x0, 0x0, false, false),
			'crc4'			    => array(0x3, 0x4, 0x0, 0x0, true, true),
			'crc4/itu'		    => array(0x3, 0x4, 0x0, 0x0, true, true),
			'crc4/interlaken'   => array(0x3, 0x4, 0xf, 0xf, false, false),
			'crc8'			    => array(0x7,  0x8, 0x0,  0x0,  false, false),
			'crc8/cdma2000'	 	=> array(0x9b, 0x8, 0xff, 0x0,  false, false),
			'crc8/darc'		    => array(0x39, 0x8, 0x0,  0x0,  true, true),
			'crc8/dvb-s2'	    => array(0xd5, 0x8, 0x0,  0x0,  false, false),
			'crc8/ebu'		    => array(0x1d, 0x8, 0xff, 0x0,  true, true),
			'crc8/i-code'	    => array(0x1d, 0x8, 0xfd, 0x0,  false, false),
			'crc8/itu'		    => array(0x7,  0x8, 0x0,  0x55, false, false),
			'crc8/maxim'		=> array(0x31, 0x8, 0x0,  0x0,  true, true),
			'crc8/rohc'		    => array(0x7,  0x8, 0xff, 0x0,  true, true),
			'crc8/wcdma'		=> array(0x9b, 0x8, 0x0,  0x0,  true, true),
			'crc8/autosar'	    => array(0x2f, 0x8, 0xff, 0xff, false, false),
			'crc8/bluetooth'	=> array(0xa7, 0x8, 0x0,  0x0,  true, true),
			'crc8/gsma'		    => array(0x1d, 0x8, 0x0,  0x0,  false, false),
			'crc8/gsmb'		    => array(0x49, 0x8, 0x0,  0xff, false, false),
			'crc8/lte'		    => array(0x9b, 0x8, 0x0,  0x0,  false, false),
			'crc8/opensafety'   => array(0x2f, 0x8, 0x0,  0x0,  false, false),
			'crc8/sae-j1850'	=> array(0x1d, 0x8, 0xff, 0xff, false, false),
			'crc16'			    => array(0x8005, 0x10, 0x0,	0x0,	true, true),
			'crc16/arc'		    => array(0x8005, 0x10, 0x0,	0x0,	true, true),
			'crc16/aug-ccitt'   => array(0x1021, 0x10, 0x1d0f, 0x0,	false, false),
			'crc16/buypass'	    => array(0x8005, 0x10, 0x0,	0x0,	false, false),
			'crc16/ccitt-false' => array(0x1021, 0x10, 0xffff, 0x0,	false, false),
			'crc16/cdma2000'	=> array(0xc867, 0x10, 0xffff, 0x0,	false, false),
			'crc16/cms'		    => array(0x8005, 0x10, 0xffff, 0x0,	false, false),
			'crc16/dds'		    => array(0x8005, 0x10, 0x800d, 0x0,	false, false),
			'crc16/dect-r'	    => array(0x589,  0x10, 0x0,	0x1,	false, false),
			'crc16/dect-x'	    => array(0x589,  0x10, 0x0,	0x0,	false, false),
			'crc16/dnp'		    => array(0x3d65, 0x10, 0x0,	0xffff, true, true),
			'crc16/en-13757'	=> array(0x3d65, 0x10, 0x0,	0xffff, false, false),
			'crc16/genibus'	    => array(0x1021, 0x10, 0xffff, 0xffff, false, false),
			'crc16/gsm'		    => array(0x1021, 0x10, 0x0,	0xffff, false, false),
			'crc16/kermit'	    => array(0x1021, 0x10, 0x0,	0x0,	true, true),
			'crc16/lj1200'	    => array(0x6f63, 0x10, 0x0,	0x0,	false, false),
			'crc16/maxim'	    => array(0x8005, 0x10, 0x0,	0xffff, true, true),
			'crc16/mcrf4xx'	    => array(0x1021, 0x10, 0xffff, 0x0,	true, true),
			'crc16/modbus'	    => array(0x8005, 0x10, 0xffff, 0x0,	true, true),
			'crc16/opensafetya' => array(0x5935, 0x10, 0x0,	0x0,	false, false),
			'crc16/opensafetyb' => array(0x755b, 0x10, 0x0,	0x0,	false, false),
			'crc16/profibus'	=> array(0x1dcf, 0x10, 0xffff, 0xffff, false, false),
			'crc16/ps2ff'	    => array(0x1021, 0x10, 0x1d0f, 0x0,	false, false),
			'crc16/riello'	    => array(0x1021, 0x10, 0xb2aa, 0x0,	true, true),
			'crc16/t10-dif'	    => array(0x8bb7, 0x10, 0x0,	0x0,	false, false),
			'crc16/teledisk'	=> array(0xa097, 0x10, 0x0,	0x0,	false, false),
			'crc16/tms37157'	=> array(0x1021, 0x10, 0x89ec, 0x0,	true, true),
			'crc16/usb'		 	=> array(0x8005, 0x10, 0xffff, 0xffff, true, true),
			'crc16/x-25'		=> array(0x1021, 0x10, 0xffff, 0xffff, true, true),
			'crc16/xmodem'	    => array(0x1021, 0x10, 0x0,	0x0,	false, false),
			'crca'			    => array(0x1021, 0x10, 0xc6c6, 0x0,	true, true),
			'crc24'			 	=> array(0x864cfb, 0x18, 0xb704ce, 0x0,	  false, false),
			'crc24/flexraya'	=> array(0x5d6dcb, 0x18, 0xfedcba, 0x0,	  false, false),
			'crc24/flexrayb'	=> array(0x5d6dcb, 0x18, 0xabcdef, 0x0,	  false, false),
			'crc24/interlaken'  => array(0x328b63, 0x18, 0xffffff, 0xffffff, false, false),
			'crc24/ltea'		=> array(0x864cfb, 0x18, 0x0,	  0x0,	  false, false),
			'crc24/lteb'		=> array(0x800063, 0x18, 0x0,	  0x0,	  false, false),
			'crc32'			    => array(0x4c11db7,  0x20, 0xffffffff, 0xffffffff, true, true),
			'crc32c'			=> array(0x1edc6f41, 0x20, 0xffffffff, 0xffffffff, true, true),
			'crc32d'			=> array(0xa833982b, 0x20, 0xffffffff, 0xffffffff, true, true),
			'crc32q'			=> array(0x814141ab, 0x20, 0x0,		0x0,		false, false),
			'crc32/bzip2'	    => array(0x4c11db7,  0x20, 0xffffffff, 0xffffffff, false, false),
			'crc32/jamcrc'	    => array(0x4c11db7,  0x20, 0xffffffff, 0x0,		true, true),
			'crc32/mpeg-2'	    => array(0x4c11db7,  0x20, 0xffffffff, 0x0,		false, false),
			'crc32/posix'	    => array(0x4c11db7,  0x20, 0x0,		0xffffffff, false, false),
			'crc32/xfer'		=> array(0xaf,	   0x20, 0x0,		0x0,		false, false),
			'crc32/autosar'		=> array(0xf4acfb13, 0x20, 0xffffffff, 0xffffffff, true, true)
		);
		if(!isset($algos[$algo]))
			return false;
		$algo = $algos[$algo];
		return array(
			'polynomial' => $algo[0],
			'length'	 => $algo[1],
			'init'	     => $algo[2],
			'xorout'	 => $algo[3],
			'refin'	     => $algo[4],
			'refout'	 => $algo[5]
		);
	}
	public static function crcalgos(){
		return array(
			"crc1",			  "crc4",			  "crc4/itu",	   "crc4/interlaken",
			"crc8",			  "crc8/cdma2000",	 "crc8/darc",	  "crc8/dvb-s2",
			"crc8/ebu",		  "crc8/i-code",	   "crc8/itu",	   "crc8/maxim",
			"crc8/rohc",		 "crc8/wcdma",		"crc8/autosar",   "crc8/bluetooth",
			"crc8/gsma",		 "crc8/gsmb",		 "crc8/lte",	   "crc8/opensafety",
			"crc8/sae-j1850",	"crc16",			 "crc16/arc",	  "crc16/aug-ccitt",
			"crc16/buypass",	 "crc16/ccitt-false", "crc16/cdma2000", "crc16/cms",
			"crc16/dds",		 "crc16/dect-r",	  "crc16/dect-x",   "crc16/dnp",
			"crc16/en-13757",	"crc16/genibus",	 "crc16/gsm",	  "crc16/kermit",
			"crc16/lj1200",	  "crc16/maxim",	   "crc16/mcrf4xx",  "crc16/modbus",
			"crc16/opensafetya", "crc16/opensafetyb", "crc16/profibus", "crc16/ps2ff",
			"crc16/riello",	  "crc16/t10-dif",	 "crc16/teledisk", "crc16/tms37157",
			"crc16/usb",		 "crc16/x-25",		"crc16/xmodem",   "crca",
			"crc24",			 "crc24/flexraya",	"crc24/flexrayb", "crc24/interlaken",
			"crc24/ltea",		"crc24/lteb",		"crc32",		  "crc32c",
			"crc32d",			"crc32q",			"crc32/bzip2",	"crc32/jamcrc",
			"crc32/mpeg-2",	  "crc32/posix",	   "crc32/xfer",	 "crc32/autosar"
		);
	}
	private static function _crc($algo, $data, $crc = null){
		$mask = (((1 << ($algo['length'] - 1)) - 1) << 1) | 1;
		$high = 1 << ($algo['length'] - 1);
		if($crc === null)$crc = $algo['init'];
		elseif($algo['refout'] === true)
			$crc = xnmath::brev($crc, $algo['length']);
		for($i = 0; isset($data[$i]); ++$i) {
			$char = ord($data[$i]);
			if($algo['refin'] === true)
				$char = xnmath::brev($char, 8);
			for($j = 0x80; $j > 0; $j >>= 1) {
				$bit = $crc & $high;
				$crc <<= 1;
				if($char & $j)
					$bit ^= $high;
				if($bit)
					$crc ^= $algo['polynomial'];
			}
		}
		if($algo['refout'] === true)
			$crc = xnmath::brev($crc, $algo['length']);
		$crc ^= $algo['xorout'];
		return $crc & $mask;
	}
	public static function crc($algo, $data = '123456789', $crc = null){
		$algo = self::crcalgo($algo);
		if($algo === false){
			new XNError('XNCrypt::crc', "Unknown CRC hashing algorithm", XNError::WARNING);
			return false;
		}
		return self::_crc($algo, $data, $crc);
	}
	public static function crcfile($algo, $file, $crc = null){
		if(!is_file($file)){
			new XNError('XNCrypt::crcfile', 'No such file', XNError::WARNING);
			return false;
		}
		$algo = self::crcalgo($algo);
		if($algo === false){
			new XNError('XNCrypt::crcfile', "Unknown CRC hashing algorithm", XNError::WARNING);
			return false;
		}
		$file = fopen($file, 'r');
		$mem = xnlib::memlimitfree() / 5;
		do{
			$read = fread($file, $mem);
			$crc = self::_crc($algo, $read, $crc);
		}while(strlen($read) == $mem);
		return $crc;
	}

	public static function to64itoa($b2, $b1, $b0, $n){
		$w = ($b2 << 16) | ($b1 << 8) | $b0;
		$range = xnstring::BCRYPT64_RANGE;
		$buf = '';
		while(--$n >= 0){
			$buf .= $range[$w & 0x3f];
			$w >>= 6;
		}
		return $buf;
	}
	public static function md5crypt($password, $salt = null, $rounds = 1000, $magic = '$1$'){
		$roundsmagick = $rounds !== null;
		if($salt !== null) {
			$mglen = strlen($magic);
			if(substr($salt, 0, $mglen) == $magic)
				$salt = substr($salt, $mglen, strlen($salt));
			$salt = substr($salt, 0, 8);
		}else{
			$salt = '';
			mt_srand((int)(microtime(true) * 10000000));
			while(strlen($salt) < 8)
				$salt .= self::$itoa64[mt_rand(0, 63)];
		}
		$ctx = $password . $magic . $salt;
		$final = md5($password . $salt . $password, true);
		$passlen = strlen($password);
		for($pl = $passlen; $pl > 0; $pl -= 16)
		   $ctx .= substr($final, 0, $pl > 16 ? 16 : $pl);
		for($i = $passlen; $i > 0; $i >>= 1)
			if($i & 1)
				$ctx .= "\0";
			else
				$ctx .= $password[0];
		$final = md5($ctx, true);
		for($i = 0; $i < $rounds; ++$i) {
			$ctx1 = '';
			if($i & 1)
				$ctx1 .= $password;
			else
				$ctx1 .= substr($final, 0, 16);
			if($i % 3)
				$ctx1 .= $salt;
			if($i % 7)
				$ctx1 .= $password;
			if($i & 1)
				$ctx1 .= substr($final, 0, 16);
			else
				$ctx1 .= $password;
			$final = md5($ctx1, true);
		}
		$final = array_map('ord', $final);
		return $magic . $salt . ($roundsmagick ? "\$rounds=$rounds$" : '$') .
			self::to64itoa($final[0], $final[6], $final[12], 4).
			self::to64itoa($final[1], $final[7], $final[13], 4).
			self::to64itoa($final[2], $final[8], $final[14], 4).
			self::to64itoa($final[3], $final[9], $final[15], 4).
			self::to64itoa($final[4], $final[10], $final[5], 4).
			self::to64itoa(0, 0, $final[11], 2);
	}
	public static function md5apachecrypt($password, $salt = null){
		return self::md5unixcrypt($password, $salt, '$apr1$');
	}
	function sha256crypt($key, $salt = null, $rounds = 5000){
		$roundsmagick = $rounds !== null;
		if($salt !== null)
			$salt = substr($salt, 0, 16);
		else{
			$salt = '';
			mt_srand((int)(microtime(true) * 10000000));
			while(strlen($salt) < 16)
				$salt .= self::$itoa64[mt_rand(0, 63)];
		}
		$saltlen = strlen($salt);
		$keylen = strlen($key);
		$ctx = $key . $salt;
		$altres = self::hash('sha256', $key . $salt . $key, true);
		for($i = $keylen; $i > 32; $i -= 32)
			$ctx .= $altres;
		$ctx .= substr($altres, 0, $i);
		for($i = $keylen; $i > 0; $i >>= 1)
			if(($i & 1) != 0)
				$ctx .= $altres;
			else
				$ctx .= $key;
		$altres = self::hash('sha256', $ctx, true);
		$altctx = $keylen === 0 ? '' : str_repeat($key, $keylen);
		$res = self::hash('sha256', $altctx, true);
		$p = '';
		for($i = $keylen; $i >= 32; $i -= 32)
			$p .= $res;
		$p .= substr($res, 0, $i);
		$altctx = str_repeat($salt, ord(substr($altres, 0, 1)) + 16);
		$res = self::hash('sha256', $altctx, true);
		$s = '';
		for($i = $saltlen; $i >= 32; $i -= 32)
			$s .= $res;
		$s .= substr($res, 0, $i);
		for($i = 0; $i < $rounds; ++$i) {
			$ctx = '';
			if(($i & 1) !== 0)
				$ctx .= $p;
			else
				$ctx .= $altres;
			if($i % 3 != 0)
				$ctx .= $s;
			if($i % 7 != 0)
				$ctx .= $p;
			if(($i & 1) != 0)
				$ctx .= $altres;
			else
				$ctx .= $p;
			$altres = self::hash('sha256', $ctx, true);
		}
		$chars = array_map('ord', str_split($altres));
		return  '$5$' . $salt . ($roundsmagick ? "\$rounds=$rounds$" : '$') .
				self::to64itoa($chars[0], $chars[10], $chars[20], 4).
				self::to64itoa($chars[21], $chars[1], $chars[11], 4).
				self::to64itoa($chars[12], $chars[22], $chars[2], 4).
				self::to64itoa($chars[3], $chars[13], $chars[23], 4).
				self::to64itoa($chars[24], $chars[4], $chars[14], 4).
				self::to64itoa($chars[15], $chars[25], $chars[5], 4).
				self::to64itoa($chars[6], $chars[16], $chars[26], 4).
				self::to64itoa($chars[27], $chars[7], $chars[17], 4).
				self::to64itoa($chars[18], $chars[28], $chars[8], 4).
				self::to64itoa($chars[9], $chars[19], $chars[29], 4).
				self::to64itoa(0, $chars[31], $chars[30], 3);
	}
	public static function crypt($str, $salt = null){
		if(__xnlib_data::$installedCrypt)
			if($salt === null)
				return crypt($str);
			else return crypt($str, $salt);
	}

	public static function hexencode($string){
		if(__xnlib_data::$installedHex)
			return bin2hex($string);
		return array_value(npack('H*', $string), 1);
	}
	public static function hexdecode($string){
		$l = strlen($string);
		if($l % 2 === 1)$string = '0' . $string;
		if(__xnlib_data::$installedHex)
			return hex2bin($string);
		return pack('H*', $string);
	}
	public static function hexstrlen($string){
		return ceil(strlen($string) / 2);
	}
	public static function binencode($string){
		if(__xnlib_data::$installedHex)
				return strtr(bin2hex($string), array(
					'0000', '0001', '0010', '0011',
					'0100', '0101', '0110', '0111',
					'1000', '1001',
					'a' => '1010', 'b' => '1011', 'c' => '1100',
					'd' => '1101', 'e' => '1110', 'f' => '1111'
				));
		$bin = '';
		for($i = 0; isset($string[$i]); ++$i)
			$bin = substr(decbin(ord($string[$i]) | 256), 1);
		return $bin;
	}
	public static function bindecode($string){
		$l = strlen($string);
		if($l % 8 !== 0)$string = str_repeat('0', 8 - $l % 8) . $string;
		if(__xnlib_data::$installedHex)
			return hex2bin(strtr($string, array(
				"0000" => "0", "0001" => "1", "0010" => "2", "0011" => "3",
				"0100" => "4", "0101" => "5", "0110" => "6", "0111" => "7",
				"1000" => "8", "1001" => "9", "1010" => "a", "1011" => "b",
				"1100" => "c", "1101" => "d", "1110" => "e", "1111" => "f"
			)));
		$bin = '';
		for($i = 0; isset($string[$i]); $i += 8)
			$bin .= chr(bindec(substr($string, $i, 8)));
		return $bin;
	}
	public static function binstrlen($string){
		return ceil(strlen($string) / 8);
	}
	public static function base4encode($string){
		if(__xnlib_data::$installedHex)
				return strtr(bin2hex($string), array(
					'00', '01', '02', '03',
					'10', '11', '12', '13',
					'20', '21',
					'a' => '22', 'b' => '23', 'c' => '30',
					'd' => '31', 'e' => '32', 'f' => '33'
				));
		$bin = '';
		for($i = 0; isset($string[$i]); ++$i)
			$bin = substr(base_convert(ord($string[$i]) | 256, 10, 4), 1);
		return $bin;
	}
	public static function base4decode($string){
		$l = strlen($string);
		if($l % 4 !== 0)$string = str_repeat('0', 4 - $l % 4) . $string;
		if(__xnlib_data::$installedHex)
			return hex2bin(strtr($string, array(
				"00" => "0", "01" => "1", "02" => "2", "03" => "3",
				"10" => "4", "11" => "5", "12" => "6", "13" => "7",
				"20" => "8", "21" => "9", "22" => "a", "23" => "b",
				"30" => "c", "31" => "d", "32" => "e", "33" => "f"
			)));
		$bin = '';
		for($i = 0; isset($string[$i]); $i += 4)
			$bin .= chr(base_convert(substr($string, $i, 4), 4, 10));
		return $bin;
	}
	public static function base4strlen($string){
		return ceil(strlen($string) / 4);
	}
	public static function octencode($string){
		$oct = '';
		for($i = 0; isset($string[$i]); $i += 3){
			$i1 = isset($string[$i + 1]);
			$a1 = ord($string[$i]);
			$a2 = $i1 ? ord($string[$i + 1]) : 0;
			$oct .= $a1 >> 5;
			$oct .= ($a1 >> 2) & 7;
			$oct .= (($a1 & 3) << 1) | ($a2 >> 7);
			if($i1){
				$i2 = isset($string[$i + 2]);
				$a3 = $i2 ? ord($string[$i + 2]) : 0;
				$oct .= ($a2 >> 4) & 7;
				$oct .= ($a2 >> 1) & 7;
				$oct .= (($a2 & 1) << 2) | ($a3 >> 6);
				if($i2){
					$oct .= ($a3 >> 3) & 7;
					$oct .= $a3 & 7;
				}
			}
		}
		return $oct;
	}
	public static function octdecode($string){
		$l = strlen($string);
		if($l % 8 !== 0){
			$l = $l % 8;
			if($l < 4){
				if($l !== 3)$string .= str_repeat('0', 3 - $l) . $string;
			}elseif($l < 7){
				if($l !== 6)$string .= str_repeat('0', 6 - $l) . $string;
			}else $string .= str_repeat('0', 8 - $l);
		}
		$bin = '';
		for($i = 0; isset($string[$i]); $i += 8){
			$i1 = isset($string[$i + 3]);
			$bin .= chr(($string[$i] << 5) | ($string[$i + 1] << 2) | ($string[$i + 2] >> 1));
			if($i1){
				$i2 = isset($string[$i + 6]);
				$bin .= chr((($string[$i + 3] | (($string[$i + 2] & 1) << 3)) << 4) | ($string[$i + 4] << 1) | ($string[$i + 5] >> 2));
				if($i2)
					$bin .= chr((($string[$i + 6] | (($string[$i + 5] & 3) << 3)) << 3) | $string[$i + 7]);
			}
		}
		return $bin;
	}
	public static function octstrlen($string){
		return ceil(strlen($string) / 8 * 3);
	}
	public static function base64encode($string){
		if(__xnlib_data::$installedBase64)return base64_encode($string);
		$s = xnstring::BASE64T_RANGE;
		$res = '';
		for($i = 0; isset($string[$i]); $i += 3){
			$i1 = isset($string[$i + 1]);
			$a1 = ord($string[$i]);
			$a2 = $i1 ? ord($string[$i + 1]) : 0;
			$res .= $s[$a1 >> 2];
			$res .= $s[(($a1 & 3) << 4) | ($a2 >> 4)];
			if($i1){
				$i2 = isset($string[$i + 2]);
				$a3 = $i2 ? ord($string[$i + 2]) : 0;
				$res .= $s[(($a2 & 15) << 2) | ($a3 >> 6)];
				if($i2)
					$res .= $s[$a3 & 63];
				else $res .= '=';
			}else $res .= '==';
		}
	}
	public static function base64decode($string){
		$l = strlen($string);
		if($l % 4 !== 0)$string .= str_repeat('=', 4 - $l % 4);
		if(__xnlib_data::$installedBase64)return base64_decode($string);
		$s = xnstring::BASE64T_RANGE;
		$bin = '';
		for($i = 0; isset($string[$i]); $i += 4){
			$a1 = strpos($s, $string[$i]);
			$a2 = strpos($s, $string[$i + 1]);
			$bin .= ord(($a1 << 2) | ($a2 >> 4));
			if($string[$i + 2] != '='){
				$a3 = strpos($s, $string[$i + 2]);
				$bin .= ord((($a2 & 15) << 4) | ($a3 >> 2));
				if($string[$i + 3] != '='){
					$a4 = strpos($s, $string[$i + 3]);
					$bin .= ord((($a3 & 3) << 6) | $a4);
				}
			}
		}
		return $bin;
	}
	public static function base64strlen($string){
		return ceil(strlen($string) / 4 * 3);
	}
	public static function bcrypt64encode($string){
		return strtr(rtrim(self::base64encode($string), '='), xnstring::BASE64T_RANGE, xnstring::BCRYPT64_RANGE);
	}
	public static function bcrypt64decode($string){
		return self::base64decode(strtr($string, xnstring::BCRYPT64_RANGE, xnstring::BASE64T_RANGE));
	}
	public static function base64urlencode($string){
		return rtrim(strtr(self::base64encode($string), '+/', '-_'), '=');
	}
	public static function base64urldecode($string){
		return self::base64decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
	}
	public static function base32encode($string){
		$s = xnstring::BASE32_RANGE;
		$res = '';
		for($i = 0; isset($string[$i]); $i += 5){
			$i1 = isset($string[$i + 1]);
			$a1 = ord($string[$i]);
			$res .= $s[$a1 >> 3];
			if($i1){
				$i2 = isset($string[$i + 2]);
				$a2 = ord($string[$i + 1]);
				$res .= $s[(($a1 & 7) << 2) | ($a2 >> 6)];
				$res .= $s[($a2 >> 1) & 0x1f];
				if($i2){
					$i3 = isset($string[$i + 3]);
					$a3 = ord($string[$i + 2]);
					$res .= $s[(($a2 & 1) << 4) | ($a3 >> 4)];
					if($i3){
						$i4 = isset($string[$i + 4]);
						$a4 = ord($string[$i + 3]);
						$res .= $s[(($a3 & 0xf) << 1) | ($a4 >> 7)];
						$res .= $s[($a4 >> 2) & 0x1f];
						if($i4){
							$a5 = ord($string[$i + 4]);
							$res .= $s[(($a4 & 3) << 3) | ($a5 >> 5)];
							$res .= $s[$a5 & 0x1f];
						}else $res .= $s[($a4 & 3) << 3] . '=';
					}else $res .= $s[($a3 & 0xf) << 1] . '===';
				}else $res .= $s[($a2 & 1) << 4] . '====';
			}else $res .= $s[($a1 & 7) << 2] . '======';
		}
		return $res;
	}
	public static function base32decode($string){
		$l = strlen($string);
		if($l % 8 !== 0)$string .= str_repeat('=', 8 - $l % 8);
		$s = xnstring::BASE32_RANGE;
		$bin = '';
		for($i = 0; isset($string[$i]); $i += 8){
			$a1 = strpos($s, $string[$i]);
			$a2 = strpos($s, $string[$i + 1]);
			$bin .= chr(($a1 << 3) | ($a2 >> 2));
			if($string[$i + 2] != '='){
				$a3 = strpos($s, $string[$i + 2]);
				$a4 = strpos($s, $string[$i + 3]);
				$bin .= chr((($a2 & 3) << 6) | ($a3 << 1) | ($a4 >> 4));
				if($string[$i + 4] != '='){
					$a5 = strpos($s, $string[$i + 4]);
					$bin .= chr((($a4 & 0xf) << 4) | ($a5 >> 1));
					if($string[$i + 5] != '='){
						$a6 = strpos($s, $string[$i + 5]);
						$a7 = strpos($s, $string[$i + 6]);
						$bin .= chr((($a5 & 1) << 7) | ($a6 << 2) | ($a7 >> 3));
						if($string[$i + 6] != '='){
							$a8 = strpos($s, $string[$i + 7]);
							$bin .= chr((($a7 & 7) << 5) | $a8);
						}
					}
				}
			}
		}
		return $bin;
	}
	public static function base32strlen($string){
		return ceil(strlen($string) / 8 * 5);
	}
	public static function rleencode($string){
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
	public static function rledecode($string){
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
	public static function datline($string){
		$datline = array();
		for($i = 0; isset($string[$i]); ++$i)
			$datline[] = strtr(decbin(ord($string[$i])), '01', '.-');
		return implode(' ', $datline);
	}
	public static function undatline($datline){
		$datline = explode(' ', $datline);
		$string = '';
		for($i = 0; isset($datline[$i]); ++$i)
			$string .= chr(bindec(strtr($datline[$i], '.-', '01')));
		return $string;
	}
	public static function datlinestrlen($datline){
		if($datline == '')return 0;
		return substr_count($datline, ' ') + 1;
	}
	public static function urlencode($string, $space = false){
		if(__xnlib_data::$installedUrlcoding)
			if($space === true)
				return str_replace(array('+', '%2B'), array('%20', '+'), urlencode($string));
			else return urlencode($string);
		$url = '';
		for($i = 0; isset($string[$i]); ++$i){
			if(strpos(xnstring::URLACCEPT_RANGE, $string[$i]) !== false)
				$url .= $string[$i];
			elseif($string[$i] === ' ' && $space !== true)$url .= '+';
			elseif($string[$i] === '+' && $space === true)$url .= '+';
			else{
				$c = ord($string[$i]);
				$url .= $c < 16 ? '%0' . strtoupper(dechex($c)) : '%' . strtoupper(dechex($c));
			}
		}
		return $url;
	}
	public static function urldecode($url, $space = false){
		if(__xnlib_data::$installedUrlcoding)return urldecode($url);
		$string = '';
		for($i = 0; isset($url[$i]); ++$i){
			if($url[$i] == '%'){
				$string .= chr(hexdec(substr($url, $i + 1, 2)));
				$i += 2;
			}elseif($url[$i] == '+' && $space !== true)$string .= ' ';
			else $string .= $url[$i];
		}
		return $string;
	}
	public static function fullurlencode($string, $space = false){
		if($string === '')return '';
		$string = '%' . implode('%', str_split(self::hexencode($string), 2));
		return $space === true ? $string : str_replace('%20', '+', $string);
	}

	public static function sizeencode($l){
		$arr = array("c*");
		while($l > 0) {
			$arr[] = $l & 0xff;
			$l >>= 8;
		}
		$size = call_user_func_array('pack',$arr);
		return chr(strlen($size)) . $size;
	}
	public static function sizedecode($str){
		$size = ord($str[0]);
		$size = substr($str, 1, $size);
		$arr = unpack("c*", $size);
		$size = 0;
		for($c = 1; isset($arr[$c]); ++$c)$size = $size * 255 + $arr[$c];
		return (int)$size;
	}
	private static function serializetag($obj, $i){
		$c = 1;
		$b = false;
		$l = $i;
		while($c !== 0){
			++$i;
			if($b === true){
				if($obj[$i] == '"')$b = false;
				elseif($obj[$i] == '\\')++$i;
			}
			elseif($obj[$i] == '{')++$c;
			elseif($obj[$i] == '}')--$c;
			elseif($obj[$i] == '"')$b = true;
		}
		return $i - $l + 1;
	}
	private static function unserializetag($obj, $tag, $i){
		$c = 1;
		$b = false;
		$l = $i;
		while($c !== 0){
			++$i;
			if($b === true){
				if($obj[$i] == '"')$b = false;
				elseif($obj[$i] == '\\')++$i;
			}
			elseif($obj[$i] == $tag[0])++$c;
			elseif($obj[$i] == $tag[1] && $tag == '[]')--$c;
			elseif($obj[$i] == $tag[1]){--$c;$b = true;}
			elseif($obj[$i] == '"' || $obj[$i] == 'p' || $obj[$i] == 'P' || $obj[$i] == '}' || $obj[$i] == '>')$b = true;
		}
		++$i;
		if($tag != '[]')
			while($obj[$i++] != '"')
				if($obj[$i] == '\\')++$i;
		return $i - $l;
	}
	private static function serializeobj($obj, $cls = null){
		if($obj == 'N;')return 'N';
		if($obj == 'b:0;')return 'f';
		if($obj == 'b:1;')return 't';
		if($obj == 'a:0:{}')return '|';
		if($obj[0] == 'i'){
			$num = substr($obj, 2, -1);
			$nm  = $num < 0 ? ~self::hexdecode(base_convert(-$num, 10, 16)) : self::hexdecode(base_convert($num, 10, 16));
			return 'i' . ($num < 0 ? chr(256 - strlen($nm)) : chr(strlen($nm))) . $nm;
		}
		if($obj[0] == 'd'){
			$num = substr($obj, 2, -1);
			$e = explode('E', $num, 2);
			$num = $e[0];
			$e = pack('v', isset($e[1]) ? $e[1] * 1 : 0);
			$d = strpos($num, '.');
			$d = chr($d === false ? 0 : $d);
			$num = str_replace('.', '', $num);
			$nm  = $num < 0 ? ~self::hexdecode(base_convert(-$num, 10, 16)) : self::hexdecode(base_convert($num, 10, 16));
			return 'd' . $d . $e . ($num < 0 ? chr(256 - strlen($nm)) : chr(strlen($nm))) . $nm;
		}
		if($obj[0] == 's')
			return '"' . str_replace(array('\\', '"'), array('\\\\', '\"'), substr($obj, strpos($obj, ':', 3) + 2, -2)) . '"';
		if($obj[0] == 'R')
			return 'R' . self::sizeencode((float)substr($obj, 2, -1));
		if($obj[0] == 'r')
			return 'r' . self::sizeencode((float)substr($obj, 2, -1));
		if($obj[0] == 'x' || $obj[0] == 'm')
			return $obj[0] . self::serializeobj(substr($obj, 2));
		if($obj[0] == 'O')
			return '{' . self::serializeobj(substr($obj, strpos($obj, '{', ($p = strpos($obj, ':', 2)) + ($l = substr($obj, 2, $p - 2) + 2) + 3)), $t = substr($obj, $p + 2, $l - 2)) . '}'
				. ($t == 'stdClass' ? '' : $t) . '"';
		if($obj[0] == 'C')
			return '<' . self::serializeobj(substr($obj, strpos($obj, '{', ($p = strpos($obj, ':', 2)) + ($l = substr($obj, 2, $p - 2) + 2) + 3)), $t = substr($obj, $p + 2, $l - 2)) . '>'
				. ($t == 'ArrayIterator' ? '' : $t) . '"';
		if($obj[0] == 'a')
			return '[' . self::serializeobj(substr($obj, strpos($obj, '{', 4))) . ']';
		if($obj == '{}')return '';
		if($obj[0] == '{'){
			$res = '';
			for($i = 1; $obj[$i] != '}';){
				if($obj[$i] == 'x' || $obj[$i] == 'm'){
					$res .= $obj[$i];
					$i += 2;
				}elseif(in_array($obj[$i], array('N', 'b', 'i', 'd', 'R', 'r'))){
					$res .= self::serializeobj($r = substr($obj, $i, ($p = strpos($obj, ';', $i)) - $i + 1));
					$i = $p + 1;
				}elseif($obj[$i] == 's'){
					$l = substr($obj, $i + 2, ($p = strpos($obj, ':', $i + 2)) - $i - 2);
					$str = substr($obj, $p + 1, $l + 2);
					if($cls !== null){
						if(strpos($str, "\"\0*\0") === 0)$str = 'P' . substr($str, 4);
						if(strpos($str, "\"\0$cls\0") === 0)$str = 'p' . substr($str, strlen($cls) + 3);
					}$res .= $str;
					$i = $p + $l + 4;
				}elseif(substr($obj, $i, 6) == 'a:0:{}'){
					$res .= '|';
					$i += 6;
				}elseif($obj[$i] == 'a'){
					$i = strpos($obj, '{', $i + 4);
					$l = self::serializetag($obj, $i);
					$res .= '[' . self::serializeobj(substr($obj, $i, $l)) . ']';
					$i += $l;
				}elseif($obj[$i] == 'O'){
					$t = substr($obj, ($p = strpos($obj, ':', $i + 3)) + 2, $l = (int)substr($obj, $i + 2, $p - $i));
					$i = strpos($obj, '{', $p + $l + 5);
					$l = self::serializetag($obj, $i);
					$res .= '{' . self::serializeobj(substr($obj, $i, $l), $t) . '}' . ($t == 'stdClass' ? '' : $t) . '"';
					$i += $l;
				}elseif($obj[$i] == 'C'){
					$t = substr($obj, ($p = strpos($obj, ':', $i + 3)) + 2, $l = (int)substr($obj, $i + 2, $p - $i));
					$i = strpos($obj, '{', $p + $l + 5);
					$l = self::serializetag($obj, $i);
					$res .= '<' . self::serializeobj(substr($obj, $i, $l), $t) . '>' . ($t == 'ArrayIterator' ? '' : $t) . '"';
					$i += $l;
				}elseif($obj[$i] == ';')++$i;
			}
			if(strpos($res, "i\1\0") === 0)
				return substr($res, 3);
			return $res;
		}
	}
	public static function unserializeobj($obj, $cls = null){
		if($obj == 'N')return 'N;';
		if($obj == 'f')return 'b:0;';
		if($obj == 't')return 'b:1;';
		if($obj == '|')return 'a:0:{}';
		if($obj == ';')return '';
		if($obj[0] == 'i'){
			$l = ord($obj[1]);
			$num = substr($obj, 2, $l > 127 ? 256 - $l : $l);
			return 'i:' . ((int)($l > 127 ? -base_convert(self::hexencode($num), 16, 10) : base_convert(self::hexencode($num), 16, 10))) . ';';
		}
		if($obj[0] == 'd'){
			$d = ord($obj[1]);
			$e = array_value(unpack('v', $obj[2] . $obj[3]), 1);
			$l = ord($obj[4]);
			$num = substr($obj, 5, $l > 127 ? 256 - $l : $l);
			$num = $l > 127 ? -base_convert(self::hexencode($num), 16, 10) : base_convert(self::hexencode($num), 16, 10);
			if($d != 0)$num = substr_replace($num, '.', $d, 0);
			return 'd:' . ((float)$num * pow(10, $e)) . ';';
		}
		if($obj[0] == 'p' && $cls === null)$cls = 'stdClass';
		if($obj[0] == '"' || $obj[0] == 'P' || $obj[0] == 'p')
			$obj = $obj[0] . str_replace(array('\"', '\\\\'), array('"', '\\'), substr($obj, 1, -1)) . '"';
		if($obj[0] == '"')
			return 's:' . (strlen($obj) - 2) . ':"' . substr($obj, 1, -1) . '";';
		if($obj[0] == 'P')
			return 's:' . (strlen($obj) + 1) . ":\"\0*\0" . substr($obj, 1, -1) . '";';
		if($obj[0] == 'p')
			return 's:' . (strlen($obj) + strlen($cls)) . ":\"\0$cls\0" . substr($obj, 1, -1) . '";';
		if($obj[0] == 'R')
			return 'R:' . self::sizedecode(substr($obj, 1)) . ';';
		if($obj[0] == 'r')
			return 'r:' . self::sizedecode(substr($obj, 1)) . ';';
		if($obj[0] == 'x' || $obj[0] == 'm')
			return $obj[0] . ':' . (in_array($obj[1], array('[', '{', '<')) ? self::unserializeobj(substr($obj, 1), $cls) . ';' : self::unserializeobj(substr($obj, 1), $cls));
		if($obj[0] == '['){
			$obj = ':' . substr($obj, 1, -1);
			return 'a:' . self::unserializeobj($obj);
		}
		if($obj[0] == '{'){
			$l = strrpos($obj, '}', 1) + 1;
			$cls = substr($obj, $l, -1);
			if($cls === '')$cls = 'stdClass';
			$obj = self::unserializeobj(':' . substr($obj, 1, $l - 2), $cls);
			return 'O:' . strlen($cls) . ':"' . $cls . '":' . $obj;
		}
		if($obj[0] == '<'){
			$l = strrpos($obj, '>', 1) + 1;
			$cls = substr($obj, $l, -1);
			if($cls === '')$cls = 'ArrayIterator';
			$obj = self::unserializeobj(';' . substr($obj, 1, $l - 2), $cls);
			return 'C:' . strlen($cls) . ':"' . $cls . '":' . $obj;
		}
		if($obj[0] == ':' || $obj[0] == ';'){
			if($obj[0] == ';')$C = true;
			else $C = false;
			$res = '';
			$c = 0;
			for($i = 1; isset($obj[$i]); ++$c){
				if(in_array($obj[$i], array('N', 'f', 't', '|', ';')))
					$res .= self::unserializeobj($obj[$i++]);
				elseif($obj[$i] == 'i'){
					$l = ord($obj[$i + 1]);
					if($l > 127)$l = 256 - $l;
					$res .= self::unserializeobj(substr($obj, $i, $l + 2));
					$i += $l + 2;
				}elseif($obj[$i] == 'd'){
					$l = ord($obj[$i + 4]);
					if($l > 127)$l = 256 - $l;
					$res .= self::unserializeobj(substr($obj, $i, $l + 5));
					$i += $l + 5;
				}elseif($obj[$i] == '"' || $obj[$i] == 'p' || $obj[$i] == 'P'){
					$l = $i;
					while($obj[++$i] != '"')
						if($obj[$i] == '\\')++$i;
					++$i;
					$res .= self::unserializeobj(substr($obj, $l, $i - $l), $cls);
				}elseif($obj[$i] == 'x' || $obj[$i] == 'm'){
					if(isset($res[-1]) && $res[-1] != ';')$res .= ';';
					$res .= $obj[$i++] . ':';
					--$c;
				}elseif($obj[$i] == 'r' || $obj[$i] == 'R'){
					$res .= $obj[$i] . ':';
					$res .= self::sizedecode(substr($obj, $i + 1, $p = ord($obj[$i + 1]) + 1)) . ';';
					$i += $p + 1;
				}elseif($obj[$i] == '['){
					$l = self::unserializetag($obj, '[]', $i);
					$res .= self::unserializeobj(substr($obj, $i, $l));
					$i += $l;
				}elseif($obj[$i] == '{'){
					$l = self::unserializetag($obj, '{}', $i);
					$res .= self::unserializeobj(substr($obj, $i, $l));
					$i += $l;
				}elseif($obj[$i] == '<'){
					$l = self::unserializetag($obj, '<>', $i);
					$res .= self::unserializeobj(substr($obj, $i, $l));
					$i += $l;
				}
			}
			return $C ? strlen($res) . ':{' . $res . '}' : ($c % 2 === 1 ? (($c + 1) / 2) . ':{i:0;' . $res . '}' : ($c / 2) . ':{' . $res . '}');
		}
	}
	public static function serialize($input){
		return self::serializeobj(serialize($input));
	}
	public static function unserialize($input){
		return @unserialize(self::unserializeobj($input));
	}
	public static function isserialize($input){
		return $input === 'f' || self::unserialize($input) !== false;
	}

	public static function huffmanfill($data, $value = ''){
		if(!is_array($data[0][1]))
			$array = array($data[0][1] => $value . '0');
		else
			$array = self::huffmanfill($data[0][1], $value . '0');
		if(isset($data[1]))
			if(!is_array($data[1][1]))
				$array[$data[1][1]] = $value . '1';
			else
				$array += self::huffmanfill($data[1][1], $value . '1');
		return $array;
	}
	public static function huffmandictlen($dict){
		$max = -1;
		$min = -1;
		foreach($dict as $res){
			$res = strlen($res);
			if($max < $res)$max = $res;
			if($min === -1 || $min > $res)$min = $res;
		}
		return array($max, $min);
	}
	public static function huffmanentry(string &$value, $dict, $dictlen){
		$length = strlen($value);
		for($i = $dictlen[1]; $i <= $dictlen[0]; ++$i) {
			$need = substr($value, 0, $i);
			foreach($dict as $key => $val)
				if($need === $val) {
					$value = substr($value, $i);
					return $key;
				}
		}
		return null;
	}
	public static function huffmandict($data){
		$occ = array();
		while(isset($data[0])) {
			$occ[] = array(substr_count($data, $data[0]), $data[0]);
			$data = str_replace($data[0], '', $data);
		}
		sort($occ);
		while(count($occ) > 1) {
			$row1 = array_shift($occ);
			$row2 = array_shift($occ);
			$occ[] = array($row1[0] + $row2[0], array($row1, $row2));
			sort($occ);
		}
		return self::huffmanfill(is_array($occ[0][1]) ? $occ[0][1] : $occ);
	}
	public static function huffmanencode($data, $dict = null){
		if($data === '')
			return '';
		if($dict === null)
			$dict = self::huffmandict($data);
		$bin = '';
		for($i = 0; isset($data[$i]); ++$i)
			if(isset($dict[$data[$i]]))
				$bin .= $dict[$data[$i]];
		$spl = str_split(1 . $bin . 1, 8);
		$bin = '';
		foreach($spl as $c)
			$bin .= chr(bindec(str_pad($c, 8, '0')));
		return $bin;
	}
	public static function huffmandecode($data, $dict){
		if($data === '')
			return '';
		$bin = '';
		$l = strlen($data);
		$res = '';
		$dictlen = self::huffmandictlen($dict);
		for($i = 0; $i < $l; ++$i) {
			$decbin = str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
			if($i === 0)
				$decbin = substr($decbin, strpos($decbin, '1') + 1);
			if($i + 1 == $l)
				$decbin = substr($decbin, 0, strrpos($decbin, '1'));
			$bin .= $decbin;
			while(($c = self::huffmanentry($bin, $dict, $dictlen)) !== null)
				$res .= $c;
		}
		return $res;
	}
	function huffmantable($length, $n, $max = 15) {
		$count = array();
		$symbol = array();
		$error = 0;

		for($l = 0; $l <= $max; ++$l)
			$count[$l] = 0;
		for($sym = 0; $sym < $n; ++$sym)
			++$count[$length[$sym]];
		if($count[0] != $n) {
			$left = 1;
			for($l = 1; $l <= $max; ++$l) {
				$left <<= 1;
				$left -= $count[$l];
				if($left < 0) {
					$error = $left;
					break;
				}
			}
			if($left >= 0) {
				$offs = array(1 => 0);
				for($l = 1; $l < $max; ++$l)
					$offs[$l + 1] = $offs[$l] + $count[$l];
				for($sym = 0; $sym < $n; ++$sym)
					if($length[$sym] != 0)
						$symbol[$offs[$length[$sym]]++] = $sym;
				$error = $left;
			}
		}
		return array(
			'count'  => $count,
			'symbol' => $symbol,
			'error'  => $error
		);
	}

	public static function xorcrypt($string, $key){
		return $string ^ xnstring::equlen($string, $key);
	}
	public static function xorhash($string, $length = 1){
		$hash = substr($string, 0, $length);
		for($i = $length; isset($string[$i]); $i += $length)
			$hash ^= str_pad(substr($string, $i, $length), $length, "\0");
		return $hash;
	}

	protected static $iconvcharset = array(
		'iso-8859-1' => array(
			'utf-8'	=> 'iso88591utf8',
			'utf-16be' => 'iso88591utf16be',
			'utf-16le' => 'iso88591utf16le',
			'utf-16'   => 'iso88591utf16'
		),
		'utf-8' => array(
			'iso-8859-1' => 'utf8iso88591',
			'utf-16be'   => 'utf8utf16be',
			'utf-16le'   => 'utf8utf16le',
			'utf-16'	 => 'utf8utf16',
		),
		'utf-16le' => array(
			'iso-8859-1' => 'utf16leiso88591',
			'utf-8'	  => 'utf16leutf8',
			'utf-16be'   => 'utf16lebe'
		),
		'utf-16be' => array(
			'iso-8859-1' => 'utf16beiso88591',
			'utf-8'	  => 'utf16beutf8',
			'utf-16le'   => 'utf16bele'
		)
	);
	public static function UI8SI8($number){
		return $number < 0x80 ? $number : $number - 0x100;
	}
	public static function UI16SI16($number){
		return $number < 0x8000 ? $number : $number - 0x10000;
	}
	public static function UI32SI32($number){
		return $number < 0x80000000 ? $number : $number - 0x100000000;
	}
	public static function UI64SI64($number){
		return $number < 0x8000000000000000 ? 0x7fffffffffffffff  - $number : $number;
	}
	public static function UIBCD8($number){
		return ($number >> 4) * 10 + ($number & 0xf);
	}
	public static function strbe($number, $min = 1, $sync = null, $signed = null){
		if($number < 0)
			new XNError('xncrypt::strbe', 'number must be greater than or equal to 0', XNError::WARNING, XNError::TTHROW);
		$mask = $sync || $signed ? 0x7F : 0xFF;
		$str = '';
		if($signed === true)
			$number = $number & (0x80 << (8 * ($min - 1)));
		while($number != 0) {
			$quot = ($number / ($mask + 1));
			$str = chr(ceil(($quot - floor($quot)) * $mask)) . $str;
			$number = floor($quot);
		}
		return str_pad($str, $min, "\0", STR_PAD_LEFT);
	}
	public static function strle($number, $min = null, $sync = null) {
		$str = '';
		while($number > 0) {
			if($sync === true) {
				$str .= chr($number & 127);
				$number >>= 7;
			}else{
				$str .= chr($number & 255);
				$number >>= 8;
			}
		}
		return str_pad($str, $min, "\0", STR_PAD_RIGHT);
	}
	public static function intbe($word, $sync = null, $signed = null){
		$res = 0;
		$l = strlen($word);
		if($l === 0)
			return false;
		for($i = 0; $i < $l; ++$i)
			if($sync === true)
				$res += (ord($word[$i]) & 0x7F) * pow(2, ($l - 1 - $i) * 7);
			else
				$res += ord($word[$i]) * pow(256, ($l - 1 - $i));
		if($signed === true && $sync !== true)
			if($bytewordlen <= PHP_INT_SIZE) {
				$mask = 0x80 << (8 * ($l - 1));
				if($res & $mask)
					$res = 0 - ($res & ($mask - 1));
			}else
				new XNError('intbe', 'Cannot have signed integers larger than '.(8 * PHP_INT_SIZE).'-bits ('.$l.')', XNError::WARNING, XNError::TTHROW);
		return $res;
	}
	public static function intle($word, $signed = null){
		return self::intbe(strrev($word), false, $signed === true);
	}
	public static function intutf8($int){
		if($int < 128)
			return chr($int);
		if($int < 2048)
			return chr(($int >>   6) | 0xC0)
				 . chr(($int & 0x3F) | 0x80);
		if($int < 65536)
			return chr(($int >>  12) | 0xE0)
				 . chr(($int >>   6) | 0xC0)
				 . chr(($int & 0x3F) | 0x80);
		return chr(($int >>  18) | 0xF0)
			 . chr(($int >>  12) | 0xC0)
			 . chr(($int >>   6) | 0xC0)
			 . chr(($int & 0x3F) | 0x80);
	}
	public static function iso88591utf8($string, $bom = null){
		if(__xnlib_data::$installedUtf8coding)
			if($bom === true)
				return "\xEF\xBB\xBF" . utf8_encode($string);
			else return utf8_encode($string);
		if($bom === true)
			$utf8 = "\xEF\xBB\xBF";
		else $utf8 = '';
		for($i = 0; isset($string[$i]); ++$i)
			$utf8 .= self::intutf8(ord($string[$i]));
		return $utf8;
	}
	public static function iso88591utf16be($string, $bom = null){
		if($bom === true)
			$utf16be = "\xFE\xFF";
		else $utf16be = '';
		for($i = 0; isset($string[$i]); ++$i)
			$utf16be .= "\0" . $string[$i];
		return $utf16be;
	}
	public static function iso88591utf16le($string, $bom = null){
		if($bom === true)
			$utf16be = "\xFF\xFE";
		else $utf16be = '';
		for($i = 0; isset($string[$i]); ++$i)
			$utf16be .= $string[$i] . "\0";
		return $utf16be;
	}
	public static function iso88591utf16($string){
		return self::iso88591utf16le($string, true);
	}
	public static function utf8iso88591($string){
		if(substr($string, 0, 3) == "\xEF\xBB\xBF")$string = substr($string, 3);
		if(__xnlib_data::$installedUtf8coding)
			return utf8_decode($string);
		$iso88591 = '';
		$i = 0;
		while(isset($string[$i])) {
			$cur = ord($string[$i]);
			if(($cur | 0x07) == 0xF7) {
				$char = (($cur				 & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif(($cur | 0x0F) == 0xEF) {
				$char = (($cur				 & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif(($cur | 0x1F) == 0xDF) {
				$char = (($cur				 & 0x1F) <<  6) &
						 (ord($string[$i + 1]) & 0x3F);
				$i += 2;
			}elseif(($cur | 0x7F) == 0x7F){
				$char = $cur;
				++$i;
			}else{
				$char = false;
				++$i;
			}
			if($char !== false)
				$iso88591 .= $char < 256 ? chr($char) : '?';
		}
		return $iso88591;
	}
	public static function utf8utf16be($string, $bom = null){
		if(substr($string, 0, 3) == "\xEF\xBB\xBF")$string = substr($string, 3);
		if($bom === true)
			$utf16be = "\xFE\xFF";
		else $utf16be = '';
		$i = 0;
		while(isset($string[$i])) {
			if((ord($string[$i]) | 0x07) == 0xF7) {
				$char = ((ord($string[$i	]) & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif((ord($string[$i]) | 0x0F) == 0xEF) {
				$char = ((ord($string[$i	]) & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif((ord($string[$i]) | 0x1F) == 0xDF) {
				$char = ((ord($string[$i	]) & 0x1F) <<  6) &
						 (ord($string[$i + 1]) & 0x3F);
				$i += 2;
			}elseif((ord($string[$i]) | 0x7F) == 0x7F)
				$char = ord($string[$i++]);
			else{
				$char = false;
				++$i;
			}
			if($char !== false)
				$utf16be .= $char < 65536 ? self::strbe($char, 2) : "\0?";
		}
		return $utf16be;
	}
	public static function utf8utf16le($string, $bom = null){
		if(substr($string, 0, 3) == "\xEF\xBB\xBF")$string = substr($string, 3);
		if($bom === true)
			$utf16le = "\xFF\xFE";
		else $utf16le = '';
		$i = 0;
		while(isset($string[$i])) {
			if((ord($string[$i]) | 0x07) == 0xF7) {
				$char = ((ord($string[$i	]) & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif((ord($string[$i]) | 0x0F) == 0xEF) {
				$char = ((ord($string[$i	]) & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif((ord($string[$i]) | 0x1F) == 0xDF) {
				$char = ((ord($string[$i	]) & 0x1F) <<  6) &
						 (ord($string[$i + 1]) & 0x3F);
				$i += 2;
			}elseif((ord($string[$i]) | 0x7F) == 0x7F)
				$char = ord($string[$i++]);
			else{
				$char = false;
				$i += 1;
			}
			if($char !== false)
				$utf16le .= $char < 65536 ? self::strle($char, 2) : "?\0";
		}
		return $utf16le;
	}
	public static function utf8utf16($string){
		return self::utf8utf16le($string, true);
	}
	public static function utf16bele($string, $bom = null){
		if(strpos($string, "\xFE\xFF") === 0)$string = substr($string, 2);
		$string = implode('', array_map('strrev', str_split($string, 2)));
		return $bom === true ? "\xFF\xFE" . $string : $string;
	}
	public static function utf16lebe($string, $bom = null){
		if(strpos($string, "\xFF\xFE") === 0)$string = substr($string, 2);
		$string = implode('', array_map('strrev', str_split($string, 2)));
		return $bom === true ? "\xFE\xFF" . $string : $string;
	}
	public static function utf16beiso88591($string){
		if(substr($string, 0, 2) == "\xFE\xFF")
			$string = substr($string, 2);
		$iso88591 = '';
		for($i = 0; isset($string[$i]); $i += 2) {
			$char = array_value(unpack('N', substr($string, $i, 2)), 1);
			$iso88591 .= $char < 256 ? chr($char) : '?';
		}
		return $iso88591;
	}
	public static function utf16leiso88591($string){
		if(substr($string, 0, 2) == "\xFF\xFE")
			$string = substr($string, 2);
		$iso88591 = '';
		for($i = 0; isset($string[$i]); $i += 2) {
			$char = self::intle(substr($string, $i, 2));
			$iso88591 .= $char < 256 ? chr($char) : '?';
		}
		return $iso88591;
	}
	public static function utf16beutf8($string){
		if(substr($string, 0, 2) == "\xFE\xFF")
			$string = substr($string, 2);
		$utf8 = '';
		for($i = 0; isset($string[$i]); $i += 2)
			$utf8 .= self::intutf8(array_value(unpack('N', substr($string, $i, 2)), 1));
		return $utf8;
	}
	public static function utf16leutf8($string){
		if(substr($string, 0, 2) == "\xFF\xFE")
			$string = substr($string, 2);
		$utf8 = '';
		for($i = 0; isset($string[$i]); $i += 2)
			$utf8 .= self::intutf8(self::intle(substr($string, $i, 2)));
		return $utf8;
	}
	public static function bomget($string){
		if(substr($string, 0, 3) == "\xEF\xBB\xBF")return 'utf-8';
		if(substr($string, 0, 2) == "\xFE\xFF")return 'utf-16be';
		if(substr($string, 0, 2) == "\xFF\xFE")return 'utf-16le';
		return 'iso-8859-1';
	}
	public static function dictget($dict){
		if($dict === null)return false;
		if(substr($dict, 0, 6) == 'ascii:'){
			$ascii = ASCII_CHARS();
			$chars = array_slice($ascii, 0, $c = ord($dict[6]));
			$chars = array_combine($chars, $chars);
			$length = 1;
			for($i = 7; isset($dict[$i]); $i += $length){
				if(isset($dict[$i + 2]) && $dict[$i] == "\0" && $dict[$i + 2] == "\0"){
					$length = ord($dict[$i + 1]);
					$i += 3;
				}
				$chars[$ascii[$c++]] = substr($dict, $i, $length);
			}
			return $chars;
		}
		if($dict[0] == ':'){
			$chars = array();
			$lto = $lfrom = 1;
			for($i = 1; isset($dict[$i]); $i += $lto){
				if(isset($dict[$i + 2]) && $dict[$i] == "\0" && $dict[$i + 2] == "\0"){
					$lfrom = ord($dict[$i + 1]);
					$i += 3;
				}
				$from = substr($dict, $i, $lfrom);
				$i += $lfrom;
				if(isset($dict[$i + 2]) && $dict[$i] == "\0" && $dict[$i + 2] == "\0"){
					$lto = ord($dict[$i + 1]);
					$i += 3;
				}
				$to = substr($dict, $i, $lto);
				$chars[$from] = $to;
			}
			return $chars;
		}
		return false;
	}
	public static function dictencode($string, $dict){
		lsort($dict);
		return strtr($string, $dict);
	}
	public static function dictdecode($string, $dict){
		lsort($dict);
		return strtr($string, array_combine($dict, array_keys($dict)));
	}
	public static function iconv($string, $from, $to = null){
		if($to === null){
			$to = $from;
			$from = 'auto';
		}
		if(strtolower($from) == 'auto')
			$from = self::bomget($string);
		$fromu = strtoupper($from);
		$tou = strtoupper($to);
		if(__xnlib_data::$installedIconv)
			$res = @iconv($fromu, $tou, $string);
		if($res !== false)return $res;
		if(__xnlib_data::$installedMbstr)
			$res = @mb_convert_encoding($string, $tou, $fromu);
		if($res !== false)return $res;
		$from = strtolower($from);
		$to = strtolower($to);
		$func = isset(self::$iconvcharset[$from][$to]) ? self::$iconvcharset[$from][$to] : false;
		if($func === false){
			if(in_array($from, array('utf-8', 'utf-16', 'utf-16be', 'utf-16le', 'utf-7')))
				$string = self::iconv($string, $from, $from = 'iso-8859-1');
			if(in_array($to, array('utf-8', 'utf-16', 'utf-16be', 'utf-16le', 'utf-7'))){
				$toe = $to;
				$to = 'iso-8859-1';
			}
			$fromdict = xndata("charset-$from");
			$todict = xndata("charset-$to");
			if($fromdict === null){
				new XNError('xncrypt::iconv', "Unknown encoding charset $from", XNError::WARNING, XNError::TTHROW);
				return false;
			}
			if($todict === null){
				new XNError('xncrypt::iconv', "Unknown encoding charset $to", XNError::WARNING, XNError::TTHROW);
				return false;
			}
			if(count($fromdict) == count($todict) && array_keys($fromdict) === array_keys($todict))
				return self::dictencode($string, array_combine(array_values($from), array_values($to)));
			$string = self::dictencode(self::dictdecode($string, $fromdict), $todict);
			if(isset($toe))
				return self::iconv($string, 'iso-8859-1', $toe);
			return $string;
		}
		list($func1, $func2) = explode('/', $func . '/');
		if($func2 !== '')
			return self::$func2(self::$func1($string));
		return self::$func1($string);
	}

	const KEYBOARD_CONVERTER_AKAN 	  = "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nɛ\nw\ne\nr\nt\ny\nu\ni\no\np\nq\nc\na\ns\nd\nf\ng\nh\nj\nk\nl\n;\n'\n\\\nz\nx\nɔ\nv\nb\nn\nm\n,\n.\n/\n~\n!\n@\n#\n$\n%\n^\n&\n*\n(\n)\n_\n+\nƐ\nW\nE\nR\nT\nY\nU\nI\nO\nP\nQ\nC\nA\nS\nD\nF\nG\nH\nJ\nK\nL\n:\n\"\n|\nZ\nX\nƆ\nV\nB\nN\nM\n<\n>\n?";
	const KEYBOARD_CONVERTER_ALBANIAN = "\\\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nq\nw\ne\nr\nt\nz\nu\ni\no\np\nç\n@\na\ns\nd\nf\ng\nh\nj\nk\nl\në\n[\n]\ny\nx\nc\nv\nb\nn\nm\n,\n.\n/\n|\n!\n\"\n#\n$\n%\n^\n&\n*\n(\n)\n_\n+\nQ\nW\nE\nR\nT\nZ\nU\nI\nO\nP\nÇ\n'\nA\nS\nD\nF\nG\nH\nJ\nK\nL\nË\n{\n}\nY\nX\nC\nV\nB\nN\nM\n;\n:\n?";
	const KEYBOARD_CONVERTER_ARABIC   = "ذ\n١\n٢\n٣\n٤\n٥\n٦\n٧\n٨\n٩\n٠\n-\n=\nض\nص\nث\nق\nف\nغ\nع\nه\nخ\nح\nج\nد\nش\nس\nي\nب\nل\nا\nت\nن\nم\nك\nط\n\\\nئ\nء\nؤ\nر\nلا\nى\nة\nو\nز\nظ\nّ\n!\n@\n#\n$\n%\n^\n&\n*\n)\n(\n_\n+\nَ\nً\nُ\nٌ\nﻹ\nإ\n’\n÷\n×\n؛\n>\n<\nِ\nٍ\n]\n[\nلأ\nأ\nـ\n،\n/\n:\n\"\n|\n~\nْ\n{\n}\nلآ\nآ\n‘\n,\n.\n؟";
	const KEYBOARD_CONVERTER_AZERI	= "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nq\nü\ne\nr\nt\ny\nu\ni\no\np\nö\nğ\na\ns\nd\nf\ng\nh\nj\nk\nl\nı\nə\n\\\nz\nx\nc\nv\nb\nn\nm\nç\nş\n.\n~\n!\n\"\nⅦ\n;\n%\n:\n?\n*\n(\n)\n_\n+\nQ\nÜ\nE\nR\nT\nY\nU\nİ\nO\nP\nÖ\nĞ\nA\nS\nD\nF\nG\nH\nJ\nK\nL\nI\nƏ\n/\nZ\nX\nC\nV\nB\nN\nM\nÇ\nŞ\n,";
	const KEYBOARD_CONVERTER_BANGLA   = "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\nৃ\nৌ\nৈ\nা\nী\nূ\nব\nহ\nগ\nদ\nজ\nড\n়\nো\nে\n্\nি\nু\nপ\nর\nক\nত\nচ\nট\n\\\n\nং\nম\nন\nব\nল\nস\n,\n.\nয\n~\n!\n\n\n\n\n\n\n\n(\n)\nঃ\nঋ\nঔ\nঐ\nআ\nঈ\nঊ\nভ\nঙ\nঘ\nধ\nঝ\nঢ\nঞ\nও\nএ\nঅ\nই\nউ\nফ\n\nখ\nথ\nছ\nঠ\n|\n\nঁ\nণ\n\n\n\nশ\nষ\n{\nয়";
	const KEYBOARD_CONVERTER_COPTIC   = "̈\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n·\n⸗\nⲑ\nⲱ\nⲉ\nⲣ\nⲧ\nⲯ\nⲩ\nⲓ\nⲟ\nⲡ\n[\n]\nⲁ\nⲥ\nⲇ\nⲫ\nⲅ\nⲏ\nϫ\nⲕ\nⲗ\n;\nʼ\ǹⲍ\nⲝ\nⲭ\nϣ\nⲃ\nⲛ\nⲙ\n,\n.\ń\n̑\n̄\n̆\nʹ\n͵\ṅ\ṇ\nⳤ\n*\n(\n)\n-\n̅\nⲐ\nⲰ\nⲈ\nⲢ\nⲦ\nⲮ\nⲨ\nⲒ\nⲞ\nⲠ\n{\n}\nⲀ\nⲤ\nⲆ\nⲪ\nⲄ\nⲎ\nϪ\nⲔ\nⲖ\n:\n⳿\n|\nⲌ\nⲜ\nⲬ\nϢ\nⲂ\nⲚ\nⲘ\n<\n>\n⳾";
	const KEYBOARD_CONVERTER_CROATIAN = "ş\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n'\n+\nq\nw\ne\nr\nt\nz\nu\ni\no\np\nš\nđ\na\ns\nd\nf\ng\nh\nj\nk\nl\nč\nć\nž\ny\nx\nc\nv\nb\nn\nm\n,\n.\n-\nÄ\n!\n\"\n#\n$\n%\n&\n/\n(\n)\n=\n?\n*\nQ\nW\nE\nR\nT\nZ\nU\nI\nO\nP\nŠ\nĐ\nA\nS\nD\nF\nG\nH\nJ\nK\nL\nČ\nĆ\nŽ\nY\nX\nC\nV\nB\nN\nM\n;\n:\n_";
	const KEYBOARD_CONVERTER_ENGLISH  = "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nq\nw\ne\nr\nt\ny\nu\ni\no\np\n[\n]\na\ns\nd\nf\ng\nh\nj\nk\nl\n;\n'\n\\\nz\nx\nc\nv\nb\nn\nm\n,\n.\n/\n~\n!\n@\n#\n$\n%\n^\n&\n*\n(\n)\n_\n+\nQ\nW\nE\nR\nT\nY\nU\nI\nO\nP\n{\n}\nA\nS\nD\nF\nG\nH\nJ\nK\nL\n:\n\"\n|\nZ\nX\nC\nV\nB\nN\nM\n<\n>\n?";
	const KEYBOARD_CONVERTER_FARSI	= "`‍‍‍\n۱\n۲\n۳\n۴\n۵\n۶\n۷\n۸\n۹\n۰\n-\n=\nض\nص\nث\nق\nف\nغ\nع\nه\nخ\nح\nج\nچ\nش\nس\nی\nب\nل\nا\nت\nن\nم\nک\nگ\n\\\nظ\nط\nز\nر\nذ\nد\nپ\nو\n.\n/\n~\n!\n٫\n؍\n﷼\n٪n\×\n٬\n٭\n(\n)\n_\n+\nْ\nٌ\nٍ\nً\nُ\nِ\nَ\nّ\n[\n]\n{\n}\nؤ\nُ\nي\nإ\nأ\nآ\nة\n«\n»\n:\n؛\n|\nك\nٓ\nژ\nٰ\n‌\nٔ\nء\n<\n>\n؟";
	const KEYBOARD_CONVERTER_FRENCH   = "²\n&\né\n\"\n'\n(\n-\nè\n_\nç\nà\n)\n=\na\nz\ne\nr\nt\ny\nu\ni\no\np\nâ\n$\nq\ns\nd\nf\ng\nh\nj\nk\nl\nm\nù\n*\nw\nx\nc\nv\nb\nn\n,\n;\n:\n!\n\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n°\n+\nA\nZ\nE\nR\nT\nY\nU\nI\nO\nP\nÄ\n£\nQ\nS\nD\nF\nG\nH\nJ\nK\nL\nM\n%\nµ\nW\nX\nC\nV\nB\nN\n?\n.\n/\n§";
	const KEYBOARD_CONVERTER_GEORGIAN = "„\n!\n?\n№\n§\n%\n:\n.\n;\n,\n/\n–\n=\nღ\nჯ\nუ\nკ\nე\nნ\nგ\nშ\nწ\nზ\nხ\nც\nფ\nძ\nვ\nთ\nა\nპ\nრ\nო\nლ\nდ\nჟ\n(\nჭ\nჩ\nყ\nს\nმ\nი\nტ\nქ\nბ\nჰ\n“\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n+\nღ\nჯ\nუ\nკ\nე\nნ\nგ\nშ\nწ\nზ\nხ\nც\nფ\nძ\nვ\nთ\nა\nპ\nრ\nო\nლ\nდ\nჟ\n)\nჭ\nჩ\nყ\nს\nმ\nი\nტ\nქ\nბ\nჰ";
	const KEYBOARD_CONVERTER_GREEK	= "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\n;\nς\nε\nρ\nτ\nυ\nθ\nι\nο\nπ\n[\n]\nα\nσ\nδ\nφ\nγ\nη\nξ\nκ\nλ\nέ\n'\n\\\nζ\nχ\nψ\nω\nβ\nν\nμ\n,\n.\n/\n~\n!\n@\n#\n$\n%\n^\n&\n*\n(\n)\n_\n+\n:\n\nΕ\nΡ\nΤ\nΥ\nΘ\nΙ\nΟ\nΠ\n{\n}\nΑ\nΣ\nΔ\nΦ\nΓ\nΗ\nΞ\nΚ\nΛ\nΪ\n\n\"\n|\nΖ\nΧ\nΨ\nΩ\nΒ\nΝ\nΜ\n<\n>\n?";
	const KEYBOARD_CONVERTER_GUJARATI = "\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\nૌ\nૈ\nા\nી\nૂ\nબ\nહ\nગ\nદ\nજ\nડ\n઼\nો\nે\n્\nિ\nુ\nપ\nર\nક\nત\nચ\nટ\nૉ\n\nં\nમ\nન\nવ\nલ\nસ\n,\n.\nય\n\nઍ\nૅ\n\n\n\n\n\n\n(\n)\nઃ\nઋ\nઔ\nઐ\nઆ\nઈ\nઊ\nભ\nઙ\nઘ\nધ\nઝ\nઢ\nઞ\nઓ\nએ\nઅ\nઇ\nઉ\nફ\n\nખ\nથ\nછ\nઠ\nઑ\n\nઁ\nણ\n\n\nળ\nશ\nષ\n।\n";
	const KEYBOARD_CONVERTER_HEBREW   = ";\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\n/\n'\nק\nר\nא\nט\nו\nן\nם\nפ\n]\n[\nש\nד\nג\nכ\nע\nי\nח\nל\nך\nף\n,\\\nז\nס\nב\nה\nנ\nמ\nצ\nת\nץ\n.\n~\n!\n@\n#\n$\n%\n^\n&\n*\n)\n(\n_\n+\n<\n>\nקּ\nרּ\nאּ\nטּ\nוּ\nןּ\nּ\nפּ\n}\n{\nשּ\nדּ\nגּ\nכּ\n׳\nיּ\n״\nלּ\nךּ\n:\n\"\n|\nזּ\nסּ\nבּ\nהּ\nנּ\nמּ\nצּ\nתּ\n׆\n?";
	const KEYBOARD_CONVERTER_HINDI	= "\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\nृ\nौ\nै\nा\nी\nू\nब\nह\nग\nद\nज\nड\n़\nो\nे\n्\nि\nु\nप\nर\nक\nत\nच\nट\nॉ\n\nं\nम\nन\nव\nल\nस\n,\n.\nय\n\nऍ\nॅ\n#\n$\n\n\n\n\n(\n)\nः\nऋ\nऔ\nऐ\nआ\nई\nऊ\nभ\nङ\nघ\nध\nझ\nढ\nञ\nओ\nए\nअ\nइ\nउ\nफ\nऱ\nख\nथ\nछ\nठ\nऑ\n\nँ\nण\n\n\nळ\nश\nष\n।\nय़";
	const KEYBOARD_CONVERTER_JAPANESE = "ろ\nぬ\nふ\nあ\nう\nえ\nお\nや\nゆ\nよ\nわ\nほ\nへ\nた\nて\nい\nす\nか\nん\nな\nに\nら\nせ\n゛\n゜\nち\nと\nし\nは\nき\nく\nま\nの\nり\nれ\nけ\nむ\nつ\nさ\nそ\nひ\nこ\nみ\nも\nね\nる\nめ\n\n\nぁ\nぅ\nぇ\nぉ\nゃ\nゅ\nょ\nを\nー\n\n\n\nぃ\n\n\n\n\n\n\n\n「\n」\n\n\n\n\n\n\n\n\n\n\n\n\nっ\n\n\n\n\n\n\n、\n。\n・";
	const KEYBOARD_CONVERTER_KAZAKH   = "(\n\"\nә\nі\nң\nғ\n,\n.\nү\nұ\nқ\nө\nһ\nй\nц\nу\nк\nе\nн\nг\nш\nщ\nз\nх\nъ\nф\nы\nв\nа\nп\nр\nо\nл\nд\nж\nэ\n\\\nя\nч\nс\nм\nи\nт\nь\nб\nю\n№\n)\n!\nӘ\nІ\nҢ\nҒ\n;\n:\nҮ\nҰ\nҚ\nӨ\nҺ\nЙ\nЦ\nУ\nК\nЕ\nН\nГ\nШ\nЩ\nЗ\nХ\nЪ\nФ\nЫ\nВ\nА\nП\nР\nО\nЛ\nД\nЖ\nЭ\n/\nЯ\nЧ\nС\nМ\nИ\nТ\nЬ\nБ\nЮ\n?";
	const KEYBOARD_CONVERTER_KHMER	= "«\n១\n២\n៣\n៤\n៥\n៦\n៧\n៨\n៩\n០\nគ\nធ\nឆ\nឹ\nេ\nរ\nត\nយ\nុ\nិ\nោ\nផ\nៀ\nឨ\nា\nស\nដ\nថ\nង\nហ\n្\nក\nល\nើ\n់\nឮ\nឋ\nខ\nច\nវ\nប\nន\nម\nំុ\n។\n៊​\n»\n!\nៗ\n\"\n៛\n%\n៌\n័\n៏\n(\n)\n៝\nឪ\nឈ\nឺ\nែ\nឬ\nទ\nួ\nូ\nី\nៅ\nភ\nឿ\nឧ\nាំ\nៃ\nឌ\nធ\nឣ\nះ\nញ\nគ\nឡ\nោៈ\n៉\nឭ\nឍ\nឃ\nជ\nេះ\nព\nណ\nំ\nុះ\n៕\n?";
	const KEYBOARD_CONVERTER_KOREAN   = "`\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nㅂ\nㅈ\nㄷ\nㄱ\nㅅ\nㅛ\nㅕ\nㅑ\nㅐ\nㅔ\n[\n]\nㅁ\nㄴ\nㅇ\nㄹ\nㅎ\nㅗ\nㅓ\nㅏ\nㅣ\n;\n'\n\\\nㅋ\nㅌ\nㅊ\nㅍ\nㅠ\nㅜ\nㅡ\n,\n.\n/\n~\n!\n@\n#\n$\n%\n^\n&\n*\n(\n)\n_\n+\nㅃ\nㅉ\nㄸ\nㄲ\nㅆ\n\n\n\nㅒ\nㅖ\n{\n}\n\n\n\n\n\n\n\n\n\n:\n\"\n|\n\n\n\n\n\n\n\n<\n>\n?";
	const KEYBOARD_CONVERTER_LAO	  = "*\nຢ\nຟ\nໂ\nຖ\nຸ\n\nູຄ\nຕ\nຈ\nຂ\nຊ\nໍ\nົ\nໄ\nຳ\nພ\nະ\nິ\nີ\nຮ\nນ\nຍ\nບ\nລ\nັ\nຫ\nກ\nດ\nເ\n້\n່\nາ\nສ\nວ\nງ\n“\nຜ\nປ\nແ\nອ\nຶ\nື\nທ\nມ\nໃ\nຝ\n/\n໑\n໒\n໓\n໔\n໌\nຼ\n໕\n໖\n໗\n໘\n໙\nໍ່\nົ້\n໐\nຳ້\n_\n+\nິ້\nີ້\nຣ\nໜ\nຽ\n-\nຫຼ\nັ້\n;\n.\n,\n:\n໊\n໋\n!\n?\n%\n=\n”\n₭\n(\nຯ\nx\nຶ້\nື້\nໆ\nໝ\n$\n)";
	const KEYBOARD_CONVERTER_MALAYALAM= "ൊ\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\nൃ\nൌ\nൈ\nാ\nീ\nൂ\nബ\nഹ\nഗ\nദ\nജ\nഡ\nർ\nോ\nേ\n്\nിു\nപ\nര\nക\nത\nച\nട\n\nെ\nം\nമ\nന\nവ\nല\nസ\n,\n.\nയ\nഒ\n\n\n\n\n\n\n\n\n(\n)\nഃ\nഋ\nഔ\nഐ\nആ\nഈ\nഊ\nഭ\nങ\nഘ\nധ\nഝ\nഢ\nഞ\nഓ\nഏ\nഅ\nഇ\nഉ\nഫ\nറ\nഖ\nഥ\nഛ\nഠ\n\nഎ\n\nണ\n\nഴ\nള\nശ\nഷ\n\n\n";
	const KEYBOARD_CONVERTER_RUSSIAN  = "ё\n1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n-\n=\nй\nц\nу\nк\nе\nн\nг\nш\nщ\nз\nх\nъ\nф\nы\nв\nа\nп\nр\nо\nл\nд\nж\nэ\n\\\nя\nч\nс\nм\nи\nт\nь\nб\nю\n.\n!\n\"\n№\n;\n%\n:\n?\n*\n(\n)\n_\n+\nЙ\nЦ\nУ\nК\nЕ\nН\nГ\nШ\nЩ\nЗ\nХ\nЪ\nФ\nЫ\nВ\nА\nП\nР\nО\nЛ\nД\nЖ\nЭ\n/\nЯ\nЧ\nС\nМ\nИ\nТ\nЬ\nБ\nЮ\n,";
	private static function kcglks($lang){
		switch($lang){
			case 'AK': case "AKAN": return self::KEYBOARD_CONVERTER_AKAN; break;
			case 'AL': case "ALBANIAN": return self::KEYBOARD_CONVERTER_ALBANIAN; break;
			case 'AR': case "ARABIC": return self::KEYBOARD_CONVERTER_ARABIC; break;
			case 'AZ': case "AZERI": return self::KEYBOARD_CONVERTER_AZERI; break;
			case 'BA': case "BANGLA": return self::KEYBOARD_CONVERTER_BANGLA; break;
			case 'CO': case "COPTIC": return self::KEYBOARD_CONVERTER_COPTIC; break;
			case 'CR': case "CROATIAN": return self::KEYBOARD_CONVERTER_CROATIAN; break;
			case 'EN': case "ENGLISH": return self::KEYBOARD_CONVERTER_ENGLISH; break;
			case 'FA': case "FARSI": return self::KEYBOARD_CONVERTER_FARSI; break;
			case 'FR': case "FRENCH": return self::KEYBOARD_CONVERTER_FRENCH; break;
			case 'GE': case "GEORGIAN": return self::KEYBOARD_CONVERTER_GEORGIAN; break;
			case 'GR': case "GREEK": return self::KEYBOARD_CONVERTER_GREEK; break;
			case 'GU': case "GUJARATI": return self::KEYBOARD_CONVERTER_GUJARATI; break;
			case 'HE': case "HEBREW": return self::KEYBOARD_CONVERTER_HEBREW; break;
			case 'HI': case "HINDI": return self::KEYBOARD_CONVERTER_HINDI; break;
			case 'JA': case "JAPANESE": return self::KEYBOARD_CONVERTER_JAPANESE; break;
			case 'KA': case "KAZAKH": return self::KEYBOARD_CONVERTER_KAZAKH; break;
			case 'KH': case "KHMER": return self::KEYBOARD_CONVERTER_KHMER; break;
			case 'KO': case "KOREAN": return self::KEYBOARD_CONVERTER_KOREAN; break;
			case 'LA': case "LAO": return self::KEYBOARD_CONVERTER_LAO; break;
			case 'MA': case "MALAYALAM": return self::KEYBOARD_CONVERTER_MALAYALAM; break;
			case 'RU': case "RUSSIAN": return self::KEYBOARD_CONVERTER_RUSSIAN; break;
			default: return false;
		}
	}
	public static function keyconv($text, $from, $to){
		$from = strtoupper($from);
		$to = strtoupper($to);
		$from = self::kcglks($from);
		if($from === false){
			new XNError('XNCrypt::keyconv', 'Invalid from keyboard language', XNError::WANING);
			return false;
		}
		$to = self::kcglks($to);
		if($to === false){
			new XNError('XNCrypt::keyconv', 'Invalid to keyboard language', XNError::WANING);
			return false;
		}
		return str_replace(explode("\n", $from), explode("\n", $to), $text);
	}
	public static function keyget($text){
		$len = strlen($text);
		$coding = '';
		foreach(array('AK', 'AL', 'AR', 'AZ', 'BA', 'CO', 'CR', 'EN', 'FA', 'FR', 'GE',
					  'GR', 'GU', 'HE', 'HI', 'JA', 'KA', 'KH', 'KO', 'LA', 'MA', 'RU') as $lang)
			if(($now = strlen(str_replace(explode("\n", self::kcglks($lang)), '', $text))) < $len){
				$len = $now;
				$coding = $lang;
			}
		return $coding === '' ? false : $coding;
	}

	function json_last_error() {
		if(__xnlib_data::$installedJson)
			return json_last_error();
		return __xnlib_data::$jsonerror;
	}
	function json_last_error_msg() {
		if(__xnlib_data::$installedJson)
			return json_last_error_msg();
		return array_value(array(
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
		), __xnlib_data::$jsonerror);
	}
	private static function _jsonencode($value, $options = 0, $depth = 512){
		if($value === null)
			return 'null';
		if($value === false)
			return 'false';
		if($value === true)
			return 'true';
		switch(gettype($value)){
			case 'string':
				if($options & JSON_NUMERIC_CHECK && is_numeric($value))
					return ($value + 0) . '';
				if(~$options & JSON_UNESCAPED_UNICODE)
					$value = unicode_encode($value);
				$value = '"' . str_replace(array('\\', '"', "\n", "\r", "\t"), array('\\\\', '\"', '\n', '\r', '\t'), $value) . '"';
				if($options & JSON_HEX_TAG)
					$value = str_replace(array('<', '>'), array('\u003C', '\u003E'), $value);
				if($options & JSON_HEX_AMP)
					$value = str_replace('&', '\u0026', $value);
				if($options & JSON_HEX_APOS)
					$value = str_replace("'", '\u0027', $value);
				if($options & JSON_HEX_QUOT)
					$value = str_replace('"', '\u0022', $value);
				if(~$options & JSON_UNESCAPED_SLASHES)
					$value = str_replace('/', '\/', $value);
				return $value;
			break;
			case 'integer':
			case 'double':
			case 'float':
				if(is_infinite($value) || is_nan($value)){
					__xnlib_data::$jsonerror = JSON_ERROR_INF_OR_NAN;
					if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
					return '0';
				}
				if($options & JSON_PRESERVE_ZERO_FRACTION && !is_int($value))
					return $value . '.0';
				return (string)$value;
			break;
			case 'array':
			case 'object':
				if($depth <= 0){
					__xnlib_data::$jsonerror = JSON_ERROR_INF_OR_NAN;
					if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
				}
				if($options & JSON_PRETTY_PRINT){
					if(is_array($value) && ~$options & JSON_FORCE_OBJECT){
						$str = "[\n	";
						$c = 0;
						foreach($value as $key => $val){
							if($key == $c++)
								$str .= str_replace("\n","\n	",self::_jsonencode($val, $options, $depth - 1)) . ",\n	";
							else{
								$str = '';
								break;
							}
							if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return null;
						}
					}else $str = '';
					if($str){
						if($str == "[\n	")
							$str = '[]';
						else
							$str = substr_replace($str,"\n]",-6,6);
						return $str;
					}
					if(is_object($value))
						$value = (array)$value;
					$str = "{\n	";
					foreach($value as $key => $val){
						if($key[0] == "\0")continue;
						$str .= self::_jsonencode((string)$key, $options, $depth - 1) . ': ' . str_replace("\n", "\n	", self::_jsonencode($val,$options,$depth - 1)) . ",\n	";
						if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return null;
					}
					if($str == "{\n	")
						$str = '{}';
					else
						$str = substr_replace($str, "\n}", -6, 6);
					return $str;
				}
				if(is_array($value) && ~$options & JSON_FORCE_OBJECT){
					$str = '[';
					$c = 0;
					foreach($value as $key => $val){
						if($key == $c++)
							$str .= self::_jsonencode($val,$options,$depth - 1) . ',';
						else{
							$str = '';
							break;
						}
						if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return null;
					}
				}else $str = '';
				if($str){
					if($str == '[')
						$str = '[]';
					else $str[strlen($str) - 1] = ']';
					return $str;
				}
				if(is_object($value))
					$value = (array)$value;
				$str = '{';
				foreach($value as $key => $val){
					if($key[0] == "\0")continue;
					$str .= self::_jsonencode((string)$key,$options,$depth - 1) . ':' . self::_jsonencode($val,$options,$depth - 1) . ',';
					if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return null;
				}
				if($str == '{')
					$str = '{}';
				else
					$str[strlen($str) - 1] = '}';
				return $str;
			break;
			default:
				__xnlib_data::$jsonerror = JSON_ERROR_UNSUPPORTED_TYPE;
				if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
				return '';
		}
	}
	public static function jsonencode($value, $options = 0, $depth = 512){
		if(__xnlib_data::$installedJson)
			return json_encode($value, $options, $depth);
		__xnlib_data::$jsonerror = JSON_ERROR_NONE;
		return self::_jsonencode($value, $options, $depth);
	}
	private static function _jsondecode($value, $assoc = false, $depth = 512, $options = 0){
		if($value == 'null')
			return null;
		if($value == 'false')
			return false;
		if($value == 'true')
			return true;
		if(is_numeric($value))
			return (float)$value;
		if($value[0] == '"'){
			$l = strlen($value);
			if($value[$l - 1] !== '"' || preg_match("/(?<!\\\\)\"/", $value = substr($value, 1, -1))){
				__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
				return null;
			}
			$value = unicode_decode($value);
			if(preg_match("/(?<!\\\\)\\\\u/", $value)){
				__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
				return null;
			}
			return str_replace(array('\"','\/','\\\\'),array('"','/','\\'),$value);
		}
		if($options & JSON_PARSE_JAVASCRIPT && $value[0] == "'"){
			$l = strlen($value);
			if($value[$l - 1] !== "'" || preg_match("/(?<!\\\\)'/", $value = substr($value, 1, -1))){
				__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
				return null;
			}
			$value = unicode_decode($value);
			if(preg_match("/(?<!\\\\)\\\\u/", $value)){
				__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
				return null;
			}
			return str_replace(array("\\'",'\/','\\\\'),array("'",'/','\\'),$value);
		}
		if($value[0] == '['){
			if($depth <= 0){
				__xnlib_data::$jsonerror = JSON_ERROR_INF_OR_NAN;
				if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
			}
			$value = substr($value, 1, -1);
			$poses = array();
			$prev = $pos = 0;
			preg_replace_callback("/\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\[\]])*)\])|(?<y>\{((?:\g<y>|\\\\\{|\\\\\}|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\{\}])*)\})|,/",
				function($x)use(&$poses, &$pos, &$prev, $value){
					$pos = strpos($value, $x[0], $pos) + strlen($x[0]);
					if($x[0] == ','){
						$poses[] = substr($value, $prev, $pos - 1 - $prev);
						$prev = $pos;
					}
					return '';
				}, $value);
			$pos = substr($value, $prev);
			if($pos !== '')
				$poses[] = $pos;
			foreach($poses as &$pos){
				$pos = self::_jsondecode(trim($pos), $assoc, $depth - 1, $options);
				if($pos === null)return null;
			}
			return $poses;
		}
		if($value[0] == '{'){
			if($depth <= 0){
				__xnlib_data::$jsonerror = JSON_ERROR_INF_OR_NAN;
				if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
			}
			$value = substr($value, 1, -1);
			$poses = array();
			$prev = $pos = 0;
			preg_replace_callback("/\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\[\]])*)\])|(?<y>\{((?:\g<y>|\\\\\{|\\\\\}|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\{\}])*)\})|,|:/",
				function($x)use(&$poses, &$pos, &$prev, $value){
					$pos = strpos($value, $x[0], $pos) + strlen($x[0]);
					if($x[0] == ','){
						$poses[] = array(',', substr($value, $prev, $pos - 1 - $prev));
						$prev = $pos;
					}elseif($x[0] == ':'){
						$poses[] = array(':', substr($value, $prev, $pos - 1 - $prev));
						$prev = $pos;
					}
					return '';
				}, $value);
			$pos = substr($value, $prev);
			if($pos !== '')
				$poses[] = array(',', $pos);
			if(count($pos) % 2 === 0 || (isset($poses[0]) && $poses[0][0] == ',')){
				__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
				return null;
			}
			foreach($poses as $k=>&$pos){
				if(isset($poses[$k - 1]) && $poses[$k - 1][0] == $pos[0]){
					__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
					return null;
				}
				if($pos[1] == 'null')
					$pos[1] = null;
				else{
					$pos[1] = self::_jsondecode(trim($pos[1]), $assoc, $depth - 1, $options);
					if($pos[1] === null)return null;
				}
			}
			if($options & JSON_OBJECT_AS_ARRAY || $assoc === true){
				$obj = array();
				for($i = 0;isset($poses[$i]);$i += 2)
					$obj[$poses[$i][1]] = $poses[$i + 1][1];
			}else{
				$obj = new stdClass;
				for($i = 0;isset($poses[$i]);$i += 2)
					$obj->{$poses[$i][1]} = $poses[$i + 1][1];
			}
			return $obj;
		}
		__xnlib_data::$jsonerror = JSON_ERROR_SYNTAX;
		return null;
	}
	public static function jsondecode($value, $assoc = false, $depth = 512, $options = 0){
		if(__xnlib_data::$installedJson)
			return json_decode($value, $assoc, $depth, $options);
		__xnlib_data::$jsonerror = JSON_ERROR_NONE;
		return self::_jsondecode($value, $assoc, $depth, $options);
	}

	public static function twomulencode($text){
		for($i = 0; isset($text[$i]); ++$i){
			$c = ord($text[$i]) * 2;
			$text[$i] = chr($c > 255 ? $c + 1 : $c);
		}
		return $text;
	}
	public static function twomuldecode($text){
		for($i = 0; isset($text[$i]); ++$i){
			$c = ord($text[$i]);
			$text[$i] = chr($c % 2 === 1 ? ($c + 255) / 2 : $c / 2);
		}
		return $text;
	}
	public static function addpos($text, $mul = 1, $add = 1){
		for($i = 0; isset($text[$i]); ++$i)
			$text[$i] = chr(ord($text[$i]) + $i * $mul + $add);
		return $text;
	}
	public static function subpos($text, $mul = 1, $add = 1){
		for($i = 0; isset($text[$i]); ++$i)
			$text[$i] = chr(ord($text[$i]) - $i * $mul - $add);
		return $text;
	}
	public static function twobyteabs($text){
		for($i = 0; isset($text[$i + 1]); $i += 2)
			$text[$i + 1] = chr(abs(ord($text[$i + 1]) - ord($text[$i])));
		return $text;
	}
	public static function bytesize($string, $from = 8, $to = 8){
		if($from == $to)return $string;
		$string = str_split(self::binencode($string), $to);
		if($from > $to){
			foreach($string as &$block)
				$block = str_repeat('0', $from - $to) . $block;
			$string = implode('', $string);
			$c = strlen($string);
			if($c % 8 !== 0)
				$string .= str_repeat('0', 8 - $c % 8);
		}else{
			$c = count($string);
			if($c * ($to - $from) % 8 !== 0)
				$string[$c - 1] = substr($string[$c - 1], 0, $c * ($to - $from) % 8);
			foreach($string as &$block)
				$block = substr($block, $to - $from);
			$string = implode('', $string);
		}
		return self::bindecode($string);
	}
	public static function xorall($string){
		$l = strlen($string);
		if($l <= 1)return $string;
		for($c = 0; $c < $l; ++$c)
			for($i = 0; $i < $l; ++$i)
				if($i == $l - 1)
					$string[$i] = $string[$i] ^ $string[0];
				else $string[$i] = $string[$i] ^ $string[$i + 1];
		return $string;
	}
	public static function unxorall($string){
		$l = strlen($string);
		if($l <= 1)return $string;
		for($c = 0; $c < $l; ++$c)
			for($i = $l - 1; $i >= 0; --$i)
				if($i == $l - 1)
					$string[$i] = $string[$i] ^ $string[0];
				else
					$string[$i] = $string[$i] ^ $string[$i + 1];
		return $string;
	}
	public static function addall($string){
		$l = strlen($string);
		if($l <= 1)return $string;
		for($c = 0; $c < $l; ++$c)
			for($i = 0; $i < $l; ++$i)
				if($i == $l - 1)
					$string[$i] = chr(ord($string[$i]) + ord($string[0]));
				else $string[$i] = chr(ord($string[$i]) + ord($string[$i + 1]));
		return $string;
	}
	public static function unaddall($string){
		$l = strlen($string);
		if($l <= 1)return $string;
		for($c = 0; $c < $l; ++$c)
			for($i = $l - 1; $i >= 0; --$i)
				if($i == $l - 1)
					$string[$i] = chr(ord($string[$i]) - ord($string[0]));
				else
					$string[$i] = chr(ord($string[$i]) - ord($string[$i + 1]));
		return $string;
	}
	public static function minimize($string){
		$min = xnstring::minchar($string);
		for($i = 0; isset($string[$i]); ++$i)
			$string[$i] = chr(ord($string[$i]) - $min);
		return chr($min) . $string;
	}
	public static function maximize($string){
		$max = 255 - xnstring::maxchar($string);
		for($i = 0; isset($string[$i]); ++$i)
			$string[$i] = chr(ord($string[$i]) + $max);
		return chr(255 - $max) . $string;
	}
	public static function unminimize($string){
		$min = ord($string[0]);
		$string = substr($string, 1);
		for($i = 0; isset($string[$i]); ++$i)
			$string[$i] = chr(ord($string[$i]) + $min);
		return $string;
	}
	public static function unmaximize($string){
		$max = 255 - ord($string[0]);
		$string = substr($string, 1);
		for($i = 0; isset($string[$i]); ++$i)
			$string[$i] = chr(ord($string[$i]) - $max);
		return $string;
	}

	protected static $tws = array(
		array(
			0x49, 0xf7, 0xcc, 0xce, 0x86, 0x50, 0x79, 0x38, 0x2f, 0xa1, 0xd0, 0x36, 0x1b, 0xdd, 0x63, 0x9e,
			0x16, 0x42, 0xc2, 0x8e, 0x94, 0x3e, 0xd7, 0x40, 0x88, 0x56, 0x20, 0x6a, 0x0f, 0x46, 0x6f, 0xca,
			0xd4, 0xcd, 0xbe, 0xb6, 0xa6, 0x98, 0x09, 0xf8, 0x1f, 0x11, 0x60, 0x04, 0x96, 0xa2, 0x8c, 0xb3,
			0x67, 0xd3, 0xc7, 0x59, 0xb4, 0x2b, 0x53, 0x62, 0x73, 0xa0, 0x84, 0x5d, 0xba, 0xed, 0xaa, 0xc8,
			0xf0, 0xbb, 0x15, 0x92, 0x41, 0x39, 0x87, 0x02, 0x6d, 0xeb, 0x3a, 0x9f, 0xaf, 0x3f, 0xe9, 0x8d,
			0xea, 0xb8, 0xa9, 0xde, 0xe4, 0x4e, 0x01, 0x33, 0xf4, 0x3d, 0xb5, 0x22, 0x0a, 0xf2, 0x7b, 0xfa,
			0x61, 0x3b, 0x17, 0xc9, 0xe0, 0xcf, 0xf9, 0x25, 0x35, 0x68, 0x5b, 0x4d, 0x03, 0x43, 0x2a, 0x0c,
			0x7a, 0x99, 0xc4, 0x6b, 0xe2, 0x0e, 0xe5, 0x29, 0x71, 0x13, 0x93, 0x48, 0x89, 0x12, 0xdf, 0x5c,
			0x2d, 0xfe, 0xe1, 0x47, 0x65, 0x8f, 0xcb, 0x9b, 0xf6, 0xbd, 0x58, 0x66, 0xee, 0x4c, 0x4b, 0x5f,
			0xb1, 0xe6, 0xdb, 0x2e, 0xe7, 0x75, 0xbc, 0x30, 0xab, 0x85, 0x45, 0x97, 0x8b, 0x74, 0xc6, 0x6c,
			0xad, 0x32, 0xa3, 0x2c, 0xd5, 0x24, 0x76, 0x0d, 0x64, 0x10, 0xd1, 0xbf, 0xc1, 0x1a, 0x82, 0xff,
			0xd6, 0x6e, 0x05, 0x91, 0xc5, 0xd2, 0xae, 0xe8, 0xec, 0x51, 0x21, 0xa7, 0x72, 0x06, 0xb9, 0x83,
			0x5e, 0x70, 0x95, 0x57, 0xc3, 0x80, 0x1d, 0xf1, 0x0b, 0xd9, 0xfd, 0x18, 0x27, 0xa8, 0xb2, 0x44,
			0x23, 0xa5, 0x7d, 0x90, 0x7c, 0x19, 0x7f, 0xda, 0xb0, 0xc0, 0x9d, 0x00, 0x55, 0x69, 0x08, 0x37,
			0xa4, 0x34, 0xfb, 0x1c, 0x4a, 0x7e, 0x78, 0x4f, 0xdc, 0x9c, 0xac, 0x5a, 0x31, 0x52, 0x77, 0x07,
			0x54, 0xf3, 0xef, 0x9a, 0x1e, 0x14, 0x28, 0x26, 0xf5, 0x3c, 0xd8, 0x8a, 0xb7, 0xe3, 0x81, 0xfc
		), array(
			0xfd, 0x18, 0xca, 0x71, 0x3a, 0xdb, 0x13, 0x06, 0xee, 0x4a, 0xa2, 0xd4, 0x22, 0x60, 0x50, 0xb9,
			0x8e, 0xb7, 0x11, 0xf8, 0x5e, 0x83, 0xff, 0x5a, 0xc1, 0x6b, 0x4d, 0x8f, 0xef, 0x6e, 0x6c, 0x74,
			0x07, 0xbb, 0x54, 0x53, 0x1a, 0xce, 0x21, 0x51, 0x2f, 0x28, 0x63, 0x7e, 0x31, 0xd7, 0x38, 0x86,
			0x12, 0xf3, 0xb1, 0x8d, 0xe5, 0x58, 0xd3, 0xdf, 0x4f, 0x8c, 0x8a, 0x2c, 0xf1, 0xab, 0xbc, 0xd1,
			0xfc, 0xec, 0x5b, 0x20, 0x7f, 0xeb, 0x39, 0xd8, 0xa9, 0xc5, 0xe9, 0xb6, 0x78, 0x70, 0xbf, 0x29,
			0xd5, 0x19, 0xa6, 0x9a, 0x49, 0x2e, 0x6d, 0x00, 0x1e, 0xc0, 0x97, 0xe3, 0xd6, 0x7b, 0xb5, 0x09,
			0xb8, 0xd2, 0x24, 0xa4, 0x7c, 0xdd, 0x1f, 0x57, 0xfa, 0xf6, 0x3c, 0xb4, 0x32, 0x25, 0x34, 0x48,
			0x59, 0xcf, 0x85, 0xa8, 0x44, 0x6a, 0x1c, 0x10, 0xe0, 0xc8, 0x5c, 0x9b, 0x79, 0x90, 0x77, 0xa0,
			0x46, 0xfb, 0xae, 0x1d, 0x26, 0x96, 0xa5, 0xc7, 0x0d, 0x0f, 0xe8, 0x36, 0xe1, 0x9e, 0x45, 0xea,
			0x0e, 0xcd, 0x98, 0x87, 0xbe, 0x76, 0x82, 0xf2, 0x92, 0x75, 0xe4, 0x0b, 0x27, 0xc4, 0xac, 0xd9,
			0xa1, 0x81, 0xe7, 0x1b, 0x02, 0xb3, 0x73, 0x2a, 0x2d, 0x80, 0x89, 0xde, 0xcc, 0x68, 0x37, 0x66,
			0x0a, 0xad, 0x65, 0x9d, 0xf4, 0x61, 0x7d, 0x67, 0x9f, 0x91, 0xc6, 0x3d, 0x8b, 0x30, 0xc3, 0xb0,
			0x17, 0xf5, 0xba, 0x41, 0x56, 0x3e, 0xdc, 0x15, 0x16, 0xc9, 0x6f, 0xed, 0x55, 0x35, 0xe2, 0x33,
			0xa3, 0x4e, 0x5f, 0x5d, 0x3b, 0x9c, 0x43, 0xfe, 0x72, 0x84, 0x0c, 0xbd, 0x08, 0x04, 0x14, 0xaf,
			0x01, 0x03, 0x94, 0x62, 0xaa, 0x42, 0xa7, 0xf0, 0x64, 0x52, 0xf7, 0xb2, 0x23, 0xcb, 0xe6, 0x95,
			0x93, 0x2b, 0xf9, 0x47, 0x4b, 0x05, 0x69, 0xda, 0xd0, 0x99, 0x7a, 0x3f, 0x88, 0xc2, 0x40, 0x4c
		), array(
			0x6d, 0x42, 0xdd, 0xfb, 0x69, 0x37, 0xc1, 0x7d, 0xb5, 0x43, 0x0f, 0x9f, 0xf3, 0x83, 0x65, 0x82,
			0x63, 0xfe, 0xc5, 0x97, 0xb2, 0xca, 0x49, 0xf6, 0x72, 0xd0, 0x30, 0x2d, 0x53, 0xcc, 0xac, 0x56,
			0x86, 0x92, 0x51, 0x22, 0x70, 0xd9, 0x52, 0x11, 0x5e, 0x9e, 0x5d, 0x2a, 0x9d, 0x62, 0xd7, 0x8c,
			0xb8, 0x05, 0x75, 0xbe, 0x01, 0x39, 0x4e, 0x67, 0x44, 0x87, 0xa3, 0xaa, 0xc2, 0xee, 0x18, 0x09,
			0x74, 0xd6, 0x16, 0x98, 0x3c, 0xfd, 0xdf, 0x48, 0x00, 0xc9, 0x21, 0x60, 0xd5, 0x5f, 0xd3, 0x54,
			0xcf, 0xaf, 0x38, 0x85, 0xa4, 0x4b, 0x1b, 0x27, 0xe7, 0xf4, 0xec, 0x8a, 0xc4, 0x0e, 0x2b, 0x77,
			0x76, 0x31, 0x7f, 0x1a, 0xb6, 0x13, 0x7e, 0xf2, 0x94, 0xb0, 0xf1, 0x6c, 0x4a, 0x0b, 0xae, 0xad,
			0xd1, 0x1d, 0xbd, 0x6b, 0x3f, 0xda, 0xb3, 0x07, 0x12, 0x66, 0x7b, 0x9c, 0x17, 0x61, 0x40, 0x41,
			0x96, 0xd8, 0x15, 0x03, 0x9a, 0x10, 0x88, 0x79, 0x26, 0x89, 0xcb, 0x8d, 0x47, 0x59, 0xc3, 0x34,
			0xef, 0x91, 0x24, 0x04, 0xe4, 0xc6, 0xe9, 0xfa, 0xe5, 0xa5, 0xa6, 0x2e, 0x32, 0x5a, 0x64, 0x28,
			0x36, 0xf7, 0x93, 0x0d, 0xe1, 0x06, 0xba, 0xd2, 0x5b, 0x23, 0x73, 0xfc, 0xc0, 0x90, 0xd4, 0x1e,
			0xe6, 0xf8, 0x46, 0x08, 0x71, 0xe2, 0x14, 0x20, 0x02, 0xff, 0xc7, 0xde, 0x8e, 0xb1, 0x99, 0x8f,
			0xf0, 0x55, 0xa0, 0x6f, 0xdc, 0x8b, 0x7c, 0x2c, 0xe3, 0x7a, 0xb9, 0x1f, 0x6a, 0xce, 0xa9, 0xf5,
			0x3e, 0xb4, 0x29, 0x9b, 0xa1, 0x0a, 0xa7, 0xa8, 0x4c, 0x5c, 0x3b, 0x4d, 0x95, 0x25, 0xea, 0xbb,
			0x45, 0xbf, 0x57, 0xe8, 0x58, 0x19, 0x68, 0x81, 0xab, 0x3a, 0x50, 0x84, 0x78, 0xbc, 0x4f, 0xeb,
			0xdb, 0x1c, 0xb7, 0x80, 0xc8, 0x2f, 0x3d, 0xcd, 0xf9, 0x0c, 0x6e, 0xed, 0xe0, 0x35, 0xa2, 0x33
		), array(
			0xdb, 0x56, 0x47, 0x6c, 0x2b, 0xb2, 0xbd, 0xef, 0xde, 0x26, 0x5c, 0xc8, 0x6f, 0xa7, 0x75, 0x1c,
			0xa9, 0x29, 0x7d, 0x79, 0xf5, 0x42, 0x10, 0x62, 0xcb, 0xd5, 0xad, 0x0c, 0xe3, 0xc6, 0xf4, 0x28,
			0x1a, 0xba, 0x5b, 0xd0, 0xa5, 0x67, 0xf7, 0xcc, 0xf6, 0x77, 0x6e, 0x35, 0xa3, 0x80, 0x93, 0x08,
			0x97, 0xec, 0xa1, 0x57, 0xe1, 0x68, 0x0b, 0xdf, 0x07, 0x45, 0x4a, 0x61, 0xf9, 0x59, 0x15, 0x4d,
			0x17, 0x44, 0x11, 0x6d, 0xcf, 0x9a, 0x1d, 0x83, 0x7b, 0x00, 0xe4, 0x8e, 0x8d, 0x6b, 0x55, 0xe7,
			0x05, 0xb9, 0xed, 0x36, 0xf0, 0xdc, 0x19, 0xc3, 0x8a, 0x33, 0xeb, 0x6a, 0x7f, 0x3b, 0xc0, 0x8f,
			0x2a, 0x60, 0x37, 0x0e, 0xa8, 0x84, 0x8b, 0x30, 0x69, 0xdd, 0x1b, 0x73, 0x9f, 0x48, 0xb1, 0x1e,
			0xc1, 0x78, 0xbc, 0x38, 0x9d, 0x95, 0xa6, 0xee, 0xe6, 0x06, 0x70, 0x5e, 0xd4, 0xd2, 0xe5, 0xd6,
			0xc5, 0xfe, 0xae, 0xbf, 0x3a, 0x99, 0x04, 0x46, 0x18, 0x7c, 0xfb, 0x9c, 0x2e, 0x4f, 0x13, 0x85,
			0xd3, 0xb3, 0x43, 0x7a, 0x14, 0xc2, 0x2c, 0x9b, 0x25, 0x71, 0xf3, 0x87, 0xe9, 0xda, 0x0f, 0x4b,
			0x39, 0x09, 0x2d, 0xa2, 0xe0, 0xd1, 0x24, 0xbb, 0xcd, 0x52, 0x3e, 0x98, 0xea, 0xa0, 0xb6, 0x4c,
			0xd8, 0x90, 0xce, 0x2f, 0x34, 0x5a, 0x23, 0xfc, 0x51, 0xbe, 0x3c, 0x41, 0x96, 0x89, 0x22, 0xab,
			0xd9, 0xac, 0x12, 0xc4, 0x72, 0xb4, 0x9e, 0x32, 0x3f, 0x63, 0x1f, 0x86, 0x02, 0x21, 0x03, 0x65,
			0x0a, 0xaa, 0xb5, 0x31, 0x20, 0xa4, 0xb0, 0x16, 0xfa, 0xc9, 0xd7, 0x92, 0xe8, 0x0d, 0x53, 0x7e,
			0x64, 0x82, 0x74, 0xfd, 0x54, 0x76, 0x91, 0x94, 0xb7, 0x4e, 0x50, 0x49, 0xb8, 0x3d, 0x8c, 0xf2,
			0x40, 0xc7, 0x5d, 0xf1, 0x58, 0xf8, 0x88, 0x01, 0x27, 0x66, 0x5f, 0xe2, 0xff, 0xca, 0x81, 0xaf
		), array(
			0x57, 0xe0, 0xa4, 0xe1, 0xdd, 0xf5, 0x07, 0x20, 0xdc, 0x5f, 0xb0, 0x9b, 0xda, 0x88, 0x90, 0x89,
			0x77, 0x12, 0x30, 0x06, 0xde, 0xc7, 0xc8, 0xc0, 0x01, 0x51, 0x24, 0xa3, 0x76, 0x83, 0x58, 0x66,
			0x43, 0x26, 0x0c, 0xec, 0x62, 0x6d, 0x84, 0x9c, 0x29, 0x4f, 0xa7, 0xf1, 0x3b, 0xa8, 0x55, 0x28,
			0xbd, 0x2c, 0x6c, 0xcf, 0x6e, 0xcd, 0x8b, 0xae, 0x2e, 0x46, 0x04, 0xd4, 0x6a, 0xbb, 0xc5, 0xfb,
			0xfe, 0xc3, 0xe5, 0xd6, 0x74, 0x8e, 0x80, 0xf3, 0x6f, 0x54, 0x09, 0xf4, 0xff, 0x1a, 0xd1, 0x38,
			0x0e, 0x27, 0xe9, 0x23, 0x22, 0xcc, 0xc4, 0x67, 0x35, 0x70, 0x17, 0x42, 0x7a, 0xd3, 0x14, 0xd2,
			0x0d, 0xb5, 0xe3, 0x2a, 0xe8, 0xb2, 0xaf, 0xb7, 0xad, 0xf6, 0x75, 0x19, 0x1e, 0x56, 0x1d, 0xca,
			0x4d, 0x03, 0xd8, 0xa6, 0x1f, 0x99, 0x95, 0x7e, 0x4c, 0x7c, 0xfa, 0x5d, 0x64, 0xb6, 0x2b, 0x44,
			0xa9, 0xa1, 0x96, 0x15, 0xd9, 0x72, 0x2f, 0x93, 0xfc, 0xaa, 0x3a, 0xbc, 0x39, 0x33, 0x10, 0x1b,
			0x7d, 0xb9, 0x98, 0xf0, 0xe2, 0xef, 0x85, 0x5a, 0x92, 0xf9, 0x53, 0x7b, 0xd5, 0xb3, 0x8d, 0xb8,
			0x7f, 0xa0, 0x0a, 0xd0, 0x63, 0x86, 0x52, 0xe6, 0x73, 0x48, 0xe4, 0x3d, 0x9e, 0xb1, 0x82, 0xdf,
			0xbf, 0x32, 0xeb, 0xa5, 0x6b, 0x5e, 0x4b, 0x11, 0x60, 0x0f, 0xc2, 0x21, 0x3e, 0xdb, 0x94, 0x4e,
			0x59, 0x18, 0xfd, 0xbe, 0x9d, 0x49, 0xba, 0x87, 0x79, 0xc9, 0x02, 0xed, 0xac, 0x91, 0x25, 0x71,
			0xf8, 0x3f, 0x61, 0x36, 0x0b, 0x50, 0x5c, 0x2d, 0x47, 0x9f, 0xf7, 0x05, 0xc6, 0x65, 0xab, 0x37,
			0x78, 0x8c, 0xce, 0x5b, 0x9a, 0x34, 0xee, 0xa2, 0x8a, 0x4a, 0x8f, 0x45, 0x41, 0xcb, 0x08, 0x1c,
			0xe7, 0x3c, 0x97, 0x31, 0xb4, 0xc1, 0x69, 0xea, 0x13, 0xf2, 0x68, 0x81, 0x40, 0x00, 0xd7, 0x16
		), array(
			0x48, 0x34, 0xb8, 0x83, 0x93, 0x31, 0xa5, 0x77, 0xb3, 0x3f, 0xd5, 0x6d, 0xf9, 0xa3, 0x5d, 0x0a,
			0x85, 0x27, 0x78, 0x65, 0xb6, 0x82, 0x42, 0x7c, 0x3e, 0xe5, 0x63, 0x56, 0xf1, 0x71, 0xaf, 0xcb,
			0xb7, 0x4a, 0x23, 0xa9, 0x92, 0xdd, 0x88, 0x57, 0x9f, 0xd2, 0x2b, 0x5e, 0xc7, 0x1b, 0x9b, 0xf5,
			0x1a, 0x61, 0x9c, 0xff, 0x8f, 0xfd, 0xa0, 0x05, 0x52, 0x35, 0xe9, 0xda, 0x44, 0xf6, 0xd0, 0x74,
			0x7e, 0x7f, 0x01, 0x09, 0x38, 0xe0, 0xb2, 0x8c, 0x47, 0x16, 0x6c, 0x55, 0xd8, 0xdb, 0x36, 0xee,
			0xea, 0x22, 0x26, 0x1c, 0x4f, 0xc1, 0x1f, 0xe2, 0xe4, 0x8d, 0x9d, 0xa8, 0xd9, 0x2a, 0x28, 0x4d,
			0x4b, 0x7d, 0x2d, 0x10, 0x9e, 0x0e, 0x79, 0x37, 0xe6, 0x04, 0xcc, 0x73, 0x6b, 0x00, 0xfa, 0xc3,
			0x24, 0xb4, 0x18, 0xaa, 0x40, 0x32, 0x60, 0x5f, 0xec, 0x87, 0xc9, 0x7a, 0xc6, 0x07, 0x66, 0x62,
			0xf3, 0xe7, 0x0f, 0x0d, 0xeb, 0x53, 0x20, 0x39, 0x86, 0x89, 0x5b, 0xc5, 0x2f, 0x8b, 0xbc, 0xbf,
			0xad, 0x91, 0x21, 0xa2, 0x68, 0xdc, 0x80, 0x13, 0x43, 0xbe, 0x84, 0xd3, 0x7b, 0x2c, 0x29, 0x0b,
			0xc2, 0xd4, 0xfe, 0x3a, 0x54, 0x99, 0x9a, 0xd6, 0xd7, 0xce, 0x3b, 0xe8, 0x1e, 0x6f, 0x6e, 0x51,
			0x69, 0xbd, 0x14, 0x76, 0xd1, 0x08, 0x64, 0xf2, 0x30, 0xca, 0xa6, 0xdf, 0xed, 0x72, 0x33, 0xe1,
			0xac, 0x06, 0x3c, 0x8e, 0x5c, 0x12, 0x95, 0xba, 0xf4, 0x49, 0x15, 0x8a, 0x1d, 0xf7, 0xcd, 0x50,
			0x19, 0x70, 0xa7, 0x4e, 0xae, 0x4c, 0x41, 0x2e, 0x81, 0x25, 0x75, 0xf0, 0xc4, 0x02, 0xbb, 0x46,
			0xfc, 0xa4, 0xb5, 0xc8, 0x94, 0x98, 0xb0, 0x58, 0xe3, 0x96, 0xde, 0xef, 0x5a, 0xfb, 0x3d, 0x90,
			0xc0, 0x6a, 0x67, 0x0c, 0x59, 0xcf, 0x17, 0xa1, 0xb1, 0xf8, 0x97, 0x03, 0xab, 0x45, 0x11, 0xb9
		)
	);
	public static function twisting($string, $iv = 0){
		$l = strlen($string);
		if($iv === true){
			$rounds = $l;
			$iv = 0;
		}else{
			$rounds = $iv + 1;
			$iv &= 0xff;
		}
		$tws = self::$tws;
		if($l === 0)return '';
		if($l === 1)return chr($tws[1][$tws[2][ord($string)] ^ $iv]);
		$string = array_values(unpack('C*', $string));
		for($c = 0; $c < $rounds; ++$c)
			for($i = 0; $i < $l; ++$i){
				if($i == 0)$h = $string[$i] ^ $string[$l - 1];
				else $h = $string[$i] ^ $string[$i - 1];
				$h = $tws[0][$h];
				if($i == $l - 1)$h ^= $tws[1][$string[0]];
				else $h ^= $tws[1][$string[$i + 1]];
				if($l - $i - 1 !== $i)
					$h += $tws[4][$string[$l - $i - 1]];
				$string[$i] = $tws[2][($h & 0xff) ^ $iv];
			}
		array_unshift($string, 'C*');
		return call_user_func_array('pack', $string);
	}
	public static function untwisting($string, $iv = 0){
		$l = strlen($string);
		if($iv === true){
			$rounds = $l;
			$iv = 0;
		}else{
			$rounds = $iv + 1;
			$iv &= 0xff;
		}
		$tws = self::$tws;
		if($l === 0)return '';
		if($l === 1)return chr($tws[5][$tws[4][ord($string)] ^ $iv]);
		$twisted = $string;
		$string = array_values(unpack('C*', $string));
		for($c = 0; $c < $rounds; ++$c)
			for($i = $l - 1; $i >= 0; --$i){
				$h = $tws[5][$string[$i]] ^ $iv;
				if($l - $i - 1 !== $i)
					$h -= $tws[4][$string[$l - $i - 1]];
				if($h < 0)$h += 256;
				if($i == $l - 1)$h ^= $tws[1][$string[0]];
				else $h ^= $tws[1][$string[$i + 1]];
				$h = $tws[3][$h];
				if($i == 0)$string[$i] = $h ^ $string[$l - 1];
				else $string[$i] = $h ^ $string[$i - 1];
			}
		array_unshift($string, 'C*');
		return call_user_func_array('pack', $string);
	}

	public static function increment($string){
		for($i = 4; isset($string[$i]); $i += 4) {
			$tmp = substr($string, -$i, 4);
			switch($tmp) {
				case "\xFF\xFF\xFF\xFF":
					$var = substr_replace($var, "\x00\x00\x00\x00", -$i, 4);
				break;
				case "\x7F\xFF\xFF\xFF":
					$var = substr_replace($var, "\x80\x00\x00\x00", -$i, 4);
					return $var;
				default:
					$tmp = unpack('N', $tmp);
					$var = substr_replace($var, pack('N', $tmp + 1), -$i, 4);
					return $var;
			}
		}
		$remainder = strlen($string) % 4;
		if($remainder == 0)
			return $string;
		$tmp = unpack('N', str_pad(substr($string, 0, $remainder), 4, "\0", STR_PAD_LEFT));
		$tmp = substr(pack('N', $tmp[1] + 1), -$remainder);
		return substr_replace($string, $tmp, 0, $remainder);
	}
	public static function endianness($x){
		$r = '';
		for($i = strlen($x) - 1; $i >= 0; --$i) {
			$b = ord($x[$i]);
			$p1 = ($b * 0x0802) & 0x22110;
			$p2 = ($b * 0x8020) & 0x88440;
			$r .= chr((($p1 | $p2) * 0x10101) >> 16);
		}
		return $r;
	}

	protected static $bfs = array(
		array(
			0xd1310ba6, 0x98dfb5ac, 0x2ffd72db, 0xd01adfb7, 0xb8e1afed, 0x6a267e96, 0xba7c9045, 0xf12c7f99,
			0x24a19947, 0xb3916cf7, 0x0801f2e2, 0x858efc16, 0x636920d8, 0x71574e69, 0xa458fea3, 0xf4933d7e,
			0x0d95748f, 0x728eb658, 0x718bcd58, 0x82154aee, 0x7b54a41d, 0xc25a59b5, 0x9c30d539, 0x2af26013,
			0xc5d1b023, 0x286085f0, 0xca417918, 0xb8db38ef, 0x8e79dcb0, 0x603a180e, 0x6c9e0e8b, 0xb01e8a3e,
			0xd71577c1, 0xbd314b27, 0x78af2fda, 0x55605c60, 0xe65525f3, 0xaa55ab94, 0x57489862, 0x63e81440,
			0x55ca396a, 0x2aab10b6, 0xb4cc5c34, 0x1141e8ce, 0xa15486af, 0x7c72e993, 0xb3ee1411, 0x636fbc2a,
			0x2ba9c55d, 0x741831f6, 0xce5c3e16, 0x9b87931e, 0xafd6ba33, 0x6c24cf5c, 0x7a325381, 0x28958677,
			0x3b8f4898, 0x6b4bb9af, 0xc4bfe81b, 0x66282193, 0x61d809cc, 0xfb21a991, 0x487cac60, 0x5dec8032,
			0xef845d5d, 0xe98575b1, 0xdc262302, 0xeb651b88, 0x23893e81, 0xd396acc5, 0x0f6d6ff3, 0x83f44239,
			0x2e0b4482, 0xa4842004, 0x69c8f04a, 0x9e1f9b5e, 0x21c66842, 0xf6e96c9a, 0x670c9c61, 0xabd388f0,
			0x6a51a0d2, 0xd8542f68, 0x960fa728, 0xab5133a3, 0x6eef0b6c, 0x137a3be4, 0xba3bf050, 0x7efb2a98,
			0xa1f1651d, 0x39af0176, 0x66ca593e, 0x82430e88, 0x8cee8619, 0x456f9fb4, 0x7d84a5c3, 0x3b8b5ebe,
			0xe06f75d8, 0x85c12073, 0x401a449f, 0x56c16aa6, 0x4ed3aa62, 0x363f7706, 0x1bfedf72, 0x429b023d,
			0x37d0d724, 0xd00a1248, 0xdb0fead3, 0x49f1c09b, 0x075372c9, 0x80991b7b, 0x25d479d8, 0xf6e8def7,
			0xe3fe501a, 0xb6794c3b, 0x976ce0bd, 0x04c006ba, 0xc1a94fb6, 0x409f60c4, 0x5e5c9ec2, 0x196a2463,
			0x68fb6faf, 0x3e6c53b5, 0x1339b2eb, 0x3b52ec6f, 0x6dfc511f, 0x9b30952c, 0xcc814544, 0xaf5ebd09,
			0xbee3d004, 0xde334afd, 0x660f2807, 0x192e4bb3, 0xc0cba857, 0x45c8740f, 0xd20b5f39, 0xb9d3fbdb,
			0x5579c0bd, 0x1a60320a, 0xd6a100c6, 0x402c7279, 0x679f25fe, 0xfb1fa3cc, 0x8ea5e9f8, 0xdb3222f8,
			0x3c7516df, 0xfd616b15, 0x2f501ec8, 0xad0552ab, 0x323db5fa, 0xfd238760, 0x53317b48, 0x3e00df82,
			0x9e5c57bb, 0xca6f8ca0, 0x1a87562e, 0xdf1769db, 0xd542a8f6, 0x287effc3, 0xac6732c6, 0x8c4f5573,
			0x695b27b0, 0xbbca58c8, 0xe1ffa35d, 0xb8f011a0, 0x10fa3d98, 0xfd2183b8, 0x4afcb56c, 0x2dd1d35b,
			0x9a53e479, 0xb6f84565, 0xd28e49bc, 0x4bfb9790, 0xe1ddf2da, 0xa4cb7e33, 0x62fb1341, 0xcee4c6e8,
			0xef20cada, 0x36774c01, 0xd07e9efe, 0x2bf11fb4, 0x95dbda4d, 0xae909198, 0xeaad8e71, 0x6b93d5a0,
			0xd08ed1d0, 0xafc725e0, 0x8e3c5b2f, 0x8e7594b7, 0x8ff6e2fb, 0xf2122b64, 0x8888b812, 0x900df01c,
			0x4fad5ea0, 0x688fc31c, 0xd1cff191, 0xb3a8c1ad, 0x2f2f2218, 0xbe0e1777, 0xea752dfe, 0x8b021fa1,
			0xe5a0cc0f, 0xb56f74e8, 0x18acf3d6, 0xce89e299, 0xb4a84fe0, 0xfd13e0b7, 0x7cc43b81, 0xd2ada8d9,
			0x165fa266, 0x80957705, 0x93cc7314, 0x211a1477, 0xe6ad2065, 0x77b5fa86, 0xc75442f5, 0xfb9d35cf,
			0xebcdaf0c, 0x7b3e89a0, 0xd6411bd3, 0xae1e7e49, 0x00250e2d, 0x2071b35e, 0x226800bb, 0x57b8e0af,
			0x2464369b, 0xf009b91e, 0x5563911d, 0x59dfa6aa, 0x78c14389, 0xd95a537f, 0x207d5ba2, 0x02e5b9c5,
			0x83260376, 0x6295cfa9, 0x11c81968, 0x4e734a41, 0xb3472dca, 0x7b14a94a, 0x1b510052, 0x9a532915,
			0xd60f573f, 0xbc9bc6e4, 0x2b60a476, 0x81e67400, 0x08ba6fb5, 0x571be91f, 0xf296ec6b, 0x2a0dd915,
			0xb6636521, 0xe7b9f9b6, 0xff34052e, 0xc5855664, 0x53b02d5d, 0xa99f8fa1, 0x08ba4799, 0x6e85076a
		), array(
			0x4b7a70e9, 0xb5b32944, 0xdb75092e, 0xc4192623, 0xad6ea6b0, 0x49a7df7d, 0x9cee60b8, 0x8fedb266,
			0xecaa8c71, 0x699a17ff, 0x5664526c, 0xc2b19ee1, 0x193602a5, 0x75094c29, 0xa0591340, 0xe4183a3e,
			0x3f54989a, 0x5b429d65, 0x6b8fe4d6, 0x99f73fd6, 0xa1d29c07, 0xefe830f5, 0x4d2d38e6, 0xf0255dc1,
			0x4cdd2086, 0x8470eb26, 0x6382e9c6, 0x021ecc5e, 0x09686b3f, 0x3ebaefc9, 0x3c971814, 0x6b6a70a1,
			0x687f3584, 0x52a0e286, 0xb79c5305, 0xaa500737, 0x3e07841c, 0x7fdeae5c, 0x8e7d44ec, 0x5716f2b8,
			0xb03ada37, 0xf0500c0d, 0xf01c1f04, 0x0200b3ff, 0xae0cf51a, 0x3cb574b2, 0x25837a58, 0xdc0921bd,
			0xd19113f9, 0x7ca92ff6, 0x94324773, 0x22f54701, 0x3ae5e581, 0x37c2dadc, 0xc8b57634, 0x9af3dda7,
			0xa9446146, 0x0fd0030e, 0xecc8c73e, 0xa4751e41, 0xe238cd99, 0x3bea0e2f, 0x3280bba1, 0x183eb331,
			0x4e548b38, 0x4f6db908, 0x6f420d03, 0xf60a04bf, 0x2cb81290, 0x24977c79, 0x5679b072, 0xbcaf89af,
			0xde9a771f, 0xd9930810, 0xb38bae12, 0xdccf3f2e, 0x5512721f, 0x2e6b7124, 0x501adde6, 0x9f84cd87,
			0x7a584718, 0x7408da17, 0xbc9f9abc, 0xe94b7d8c, 0xec7aec3a, 0xdb851dfa, 0x63094366, 0xc464c3d2,
			0xef1c1847, 0x3215d908, 0xdd433b37, 0x24c2ba16, 0x12a14d43, 0x2a65c451, 0x50940002, 0x133ae4dd,
			0x71dff89e, 0x10314e55, 0x81ac77d6, 0x5f11199b, 0x043556f1, 0xd7a3c76b, 0x3c11183b, 0x5924a509,
			0xf28fe6ed, 0x97f1fbfa, 0x9ebabf2c, 0x1e153c6e, 0x86e34570, 0xeae96fb1, 0x860e5e0a, 0x5a3e2ab3,
			0x771fe71c, 0x4e3d06fa, 0x2965dcb9, 0x99e71d0f, 0x803e89d6, 0x5266c825, 0x2e4cc978, 0x9c10b36a,
			0xc6150eba, 0x94e2ea78, 0xa5fc3c53, 0x1e0a2df4, 0xf2f74ea7, 0x361d2b3d, 0x1939260f, 0x19c27960,
			0x5223a708, 0xf71312b6, 0xebadfe6e, 0xeac31f66, 0xe3bc4595, 0xa67bc883, 0xb17f37d1, 0x018cff28,
			0xc332ddef, 0xbe6c5aa5, 0x65582185, 0x68ab9802, 0xeecea50f, 0xdb2f953b, 0x2aef7dad, 0x5b6e2f84,
			0x1521b628, 0x29076170, 0xecdd4775, 0x619f1510, 0x13cca830, 0xeb61bd96, 0x0334fe1e, 0xaa0363cf,
			0xb5735c90, 0x4c70a239, 0xd59e9e0b, 0xcbaade14, 0xeecc86bc, 0x60622ca7, 0x9cab5cab, 0xb2f3846e,
			0x648b1eaf, 0x19bdf0ca, 0xa02369b9, 0x655abb50, 0x40685a32, 0x3c2ab4b3, 0x319ee9d5, 0xc021b8f7,
			0x9b540b19, 0x875fa099, 0x95f7997e, 0x623d7da8, 0xf837889a, 0x97e32d77, 0x11ed935f, 0x16681281,
			0x0e358829, 0xc7e61fd6, 0x96dedfa1, 0x7858ba99, 0x57f584a5, 0x1b227263, 0x9b83c3ff, 0x1ac24696,
			0xcdb30aeb, 0x532e3054, 0x8fd948e4, 0x6dbc3128, 0x58ebf2ef, 0x34c6ffea, 0xfe28ed61, 0xee7c3c73,
			0x5d4a14d9, 0xe864b7e3, 0x42105d14, 0x203e13e0, 0x45eee2b6, 0xa3aaabea, 0xdb6c4f15, 0xfacb4fd0,
			0xc742f442, 0xef6abbb5, 0x654f3b1d, 0x41cd2105, 0xd81e799e, 0x86854dc7, 0xe44b476a, 0x3d816250,
			0xcf62a1f2, 0x5b8d2646, 0xfc8883a0, 0xc1c7b6a3, 0x7f1524c3, 0x69cb7492, 0x47848a0b, 0x5692b285,
			0x095bbf00, 0xad19489d, 0x1462b174, 0x23820e00, 0x58428d2a, 0x0c55f5ea, 0x1dadf43e, 0x233f7061,
			0x3372f092, 0x8d937e41, 0xd65fecf1, 0x6c223bdb, 0x7cde3759, 0xcbee7460, 0x4085f2a7, 0xce77326e,
			0xa6078084, 0x19f8509e, 0xe8efd855, 0x61d99735, 0xa969a7aa, 0xc50c06c2, 0x5a04abfc, 0x800bcadc,
			0x9e447a2e, 0xc3453484, 0xfdd56705, 0x0e1e9ec9, 0xdb73dbd3, 0x105588cd, 0x675fda79, 0xe3674340,
			0xc5c43465, 0x713e38d8, 0x3d28f89e, 0xf16dff20, 0x153e21e7, 0x8fb03d4a, 0xe6e39f2b, 0xdb83adf7
		), array(
			0xe93d5a68, 0x948140f7, 0xf64c261c, 0x94692934, 0x411520f7, 0x7602d4f7, 0xbcf46b2e, 0xd4a20068,
			0xd4082471, 0x3320f46a, 0x43b7d4b7, 0x500061af, 0x1e39f62e, 0x97244546, 0x14214f74, 0xbf8b8840,
			0x4d95fc1d, 0x96b591af, 0x70f4ddd3, 0x66a02f45, 0xbfbc09ec, 0x03bd9785, 0x7fac6dd0, 0x31cb8504,
			0x96eb27b3, 0x55fd3941, 0xda2547e6, 0xabca0a9a, 0x28507825, 0x530429f4, 0x0a2c86da, 0xe9b66dfb,
			0x68dc1462, 0xd7486900, 0x680ec0a4, 0x27a18dee, 0x4f3ffea2, 0xe887ad8c, 0xb58ce006, 0x7af4d6b6,
			0xaace1e7c, 0xd3375fec, 0xce78a399, 0x406b2a42, 0x20fe9e35, 0xd9f385b9, 0xee39d7ab, 0x3b124e8b,
			0x1dc9faf7, 0x4b6d1856, 0x26a36631, 0xeae397b2, 0x3a6efa74, 0xdd5b4332, 0x6841e7f7, 0xca7820fb,
			0xfb0af54e, 0xd8feb397, 0x454056ac, 0xba489527, 0x55533a3a, 0x20838d87, 0xfe6ba9b7, 0xd096954b,
			0x55a867bc, 0xa1159a58, 0xcca92963, 0x99e1db33, 0xa62a4a56, 0x3f3125f9, 0x5ef47e1c, 0x9029317c,
			0xfdf8e802, 0x04272f70, 0x80bb155c, 0x05282ce3, 0x95c11548, 0xe4c66d22, 0x48c1133f, 0xc70f86dc,
			0x07f9c9ee, 0x41041f0f, 0x404779a4, 0x5d886e17, 0x325f51eb, 0xd59bc0d1, 0xf2bcc18f, 0x41113564,
			0x257b7834, 0x602a9c60, 0xdff8e8a3, 0x1f636c1b, 0x0e12b4c2, 0x02e1329e, 0xaf664fd1, 0xcad18115,
			0x6b2395e0, 0x333e92e1, 0x3b240b62, 0xeebeb922, 0x85b2a20e, 0xe6ba0d99, 0xde720c8c, 0x2da2f728,
			0xd0127845, 0x95b794fd, 0x647d0862, 0xe7ccf5f0, 0x5449a36f, 0x877d48fa, 0xc39dfd27, 0xf33e8d1e,
			0x0a476341, 0x992eff74, 0x3a6f6eab, 0xf4f8fd37, 0xa812dc60, 0xa1ebddf8, 0x991be14c, 0xdb6e6b0d,
			0xc67b5510, 0x6d672c37, 0x2765d43b, 0xdcd0e804, 0xf1290dc7, 0xcc00ffa3, 0xb5390f92, 0x690fed0b,
			0x667b9ffb, 0xcedb7d9c, 0xa091cf0b, 0xd9155ea3, 0xbb132f88, 0x515bad24, 0x7b9479bf, 0x763bd6eb,
			0x37392eb3, 0xcc115979, 0x8026e297, 0xf42e312d, 0x6842ada7, 0xc66a2b3b, 0x12754ccc, 0x782ef11c,
			0x6a124237, 0xb79251e7, 0x06a1bbe6, 0x4bfb6350, 0x1a6b1018, 0x11caedfa, 0x3d25bdd8, 0xe2e1c3c9,
			0x44421659, 0x0a121386, 0xd90cec6e, 0xd5abea2a, 0x64af674e, 0xda86a85f, 0xbebfe988, 0x64e4c3fe,
			0x9dbc8057, 0xf0f7c086, 0x60787bf8, 0x6003604d, 0xd1fd8346, 0xf6381fb0, 0x7745ae04, 0xd736fccc,
			0x83426b33, 0xf01eab71, 0xb0804187, 0x3c005e5f, 0x77a057be, 0xbde8ae24, 0x55464299, 0xbf582e61,
			0x4e58f48f, 0xf2ddfda2, 0xf474ef38, 0x8789bdc2, 0x5366f9c3, 0xc8b38e74, 0xb475f255, 0x46fcd9b9,
			0x7aeb2661, 0x8b1ddf84, 0x846a0e79, 0x915f95e2, 0x466e598e, 0x20b45770, 0x8cd55591, 0xc902de4c,
			0xb90bace1, 0xbb8205d0, 0x11a86248, 0x7574a99e, 0xb77f19b6, 0xe0a9dc09, 0x662d09a1, 0xc4324633,
			0xe85a1f02, 0x09f0be8c, 0x4a99a025, 0x1d6efe10, 0x1ab93d1d, 0x0ba5a4df, 0xa186f20f, 0x2868f169,
			0xdcb7da83, 0x573906fe, 0xa1e2ce9b, 0x4fcd7f52, 0x50115e01, 0xa70683fa, 0xa002b5c4, 0x0de6d027,
			0x9af88c27, 0x773f8641, 0xc3604c06, 0x61a806b5, 0xf0177a28, 0xc0f586e0, 0x006058aa, 0x30dc7d62,
			0x11e69ed7, 0x2338ea63, 0x53c2dd94, 0xc2c21634, 0xbbcbee56, 0x90bcb6de, 0xebfc7da1, 0xce591d76,
			0x6f05e409, 0x4b7c0188, 0x39720a3d, 0x7c927c24, 0x86e3725f, 0x724d9db9, 0x1ac15bb4, 0xd39eb8fc,
			0xed545578, 0x08fca5b5, 0xd83d7cd3, 0x4dad0fc4, 0x1e50ef5e, 0xb161e6f8, 0xa28514d9, 0x6c51133c,
			0x6fd5c7e7, 0x56e14ec4, 0x362abfce, 0xddc6c837, 0xd79a3234, 0x92638212, 0x670efa8e, 0x406000e0
		), array(
			0x3a39ce37, 0xd3faf5cf, 0xabc27737, 0x5ac52d1b, 0x5cb0679e, 0x4fa33742, 0xd3822740, 0x99bc9bbe,
			0xd5118e9d, 0xbf0f7315, 0xd62d1c7e, 0xc700c47b, 0xb78c1b6b, 0x21a19045, 0xb26eb1be, 0x6a366eb4,
			0x5748ab2f, 0xbc946e79, 0xc6a376d2, 0x6549c2c8, 0x530ff8ee, 0x468dde7d, 0xd5730a1d, 0x4cd04dc6,
			0x2939bbdb, 0xa9ba4650, 0xac9526e8, 0xbe5ee304, 0xa1fad5f0, 0x6a2d519a, 0x63ef8ce2, 0x9a86ee22,
			0xc089c2b8, 0x43242ef6, 0xa51e03aa, 0x9cf2d0a4, 0x83c061ba, 0x9be96a4d, 0x8fe51550, 0xba645bd6,
			0x2826a2f9, 0xa73a3ae1, 0x4ba99586, 0xef5562e9, 0xc72fefd3, 0xf752f7da, 0x3f046f69, 0x77fa0a59,
			0x80e4a915, 0x87b08601, 0x9b09e6ad, 0x3b3ee593, 0xe990fd5a, 0x9e34d797, 0x2cf0b7d9, 0x022b8b51,
			0x96d5ac3a, 0x017da67d, 0xd1cf3ed6, 0x7c7d2d28, 0x1f9f25cf, 0xadf2b89b, 0x5ad6b472, 0x5a88f54c,
			0xe029ac71, 0xe019a5e6, 0x47b0acfd, 0xed93fa9b, 0xe8d3c48d, 0x283b57cc, 0xf8d56629, 0x79132e28,
			0x785f0191, 0xed756055, 0xf7960e44, 0xe3d35e8c, 0x15056dd4, 0x88f46dba, 0x03a16125, 0x0564f0bd,
			0xc3eb9e15, 0x3c9057a2, 0x97271aec, 0xa93a072a, 0x1b3f6d9b, 0x1e6321f5, 0xf59c66fb, 0x26dcf319,
			0x7533d928, 0xb155fdf5, 0x03563482, 0x8aba3cbb, 0x28517711, 0xc20ad9f8, 0xabcc5167, 0xccad925f,
			0x4de81751, 0x3830dc8e, 0x379d5862, 0x9320f991, 0xea7a90c2, 0xfb3e7bce, 0x5121ce64, 0x774fbe32,
			0xa8b6e37e, 0xc3293d46, 0x48de5369, 0x6413e680, 0xa2ae0810, 0xdd6db224, 0x69852dfd, 0x09072166,
			0xb39a460a, 0x6445c0dd, 0x586cdecf, 0x1c20c8ae, 0x5bbef7dd, 0x1b588d40, 0xccd2017f, 0x6bb4e3bb,
			0xdda26a7e, 0x3a59ff45, 0x3e350a44, 0xbcb4cdd5, 0x72eacea8, 0xfa6484bb, 0x8d6612ae, 0xbf3c6f47,
			0xd29be463, 0x542f5d9e, 0xaec2771b, 0xf64e6370, 0x740e0d8d, 0xe75b1357, 0xf8721671, 0xaf537d5d,
			0x4040cb08, 0x4eb4e2cc, 0x34d2466a, 0x0115af84, 0xe1b00428, 0x95983a1d, 0x06b89fb4, 0xce6ea048,
			0x6f3f3b82, 0x3520ab82, 0x011a1d4b, 0x277227f8, 0x611560b1, 0xe7933fdc, 0xbb3a792b, 0x344525bd,
			0xa08839e1, 0x51ce794b, 0x2f32c9b7, 0xa01fbac9, 0xe01cc87e, 0xbcc7d1f6, 0xcf0111c3, 0xa1e8aac7,
			0x1a908749, 0xd44fbd9a, 0xd0dadecb, 0xd50ada38, 0x0339c32a, 0xc6913667, 0x8df9317c, 0xe0b12b4f,
			0xf79e59b7, 0x43f5bb3a, 0xf2d519ff, 0x27d9459c, 0xbf97222c, 0x15e6fc2a, 0x0f91fc71, 0x9b941525,
			0xfae59361, 0xceb69ceb, 0xc2a86459, 0x12baa8d1, 0xb6c1075e, 0xe3056a0c, 0x10d25065, 0xcb03a442,
			0xe0ec6e0e, 0x1698db3b, 0x4c98a0be, 0x3278e964, 0x9f1f9532, 0xe0d392df, 0xd3a0342b, 0x8971f21e,
			0x1b0a7441, 0x4ba3348c, 0xc5be7120, 0xc37632d8, 0xdf359f8d, 0x9b992f2e, 0xe60b6f47, 0x0fe3f11d,
			0xe54cda54, 0x1edad891, 0xce6279cf, 0xcd3e7e6f, 0x1618b166, 0xfd2c1d05, 0x848fd2c5, 0xf6fb2299,
			0xf523f357, 0xa6327623, 0x93a83531, 0x56cccd02, 0xacf08162, 0x5a75ebb5, 0x6e163697, 0x88d273cc,
			0xde966292, 0x81b949d0, 0x4c50901b, 0x71c65614, 0xe6c6c7bd, 0x327a140a, 0x45e1d006, 0xc3f27b9a,
			0xc9aa53fd, 0x62a80f00, 0xbb25bfe2, 0x35bdd2f6, 0x71126905, 0xb2040222, 0xb6cbcf7c, 0xcd769c2b,
			0x53113ec0, 0x1640e3d3, 0x38abbd60, 0x2547adf0, 0xba38209c, 0xf746ce76, 0x77afa1c5, 0x20756060,
			0x85cbfe4e, 0x8ae88dd8, 0x7aaaf9b0, 0x4cf9aa7e, 0x1948c25c, 0x02fb8a8c, 0x01c36ae4, 0xd6ebe1f9,
			0x90d4f869, 0xa65cdea0, 0x3f09252d, 0xc208e69f, 0xb74e6132, 0xce77e25b, 0x578fdfe3, 0x3ac372e6
		)
	);
	protected static $bfp = array(
		0x243f6a88, 0x85a308d3, 0x13198a2e, 0x03707344, 0xa4093822, 0x299f31d0,
		0x082efa98, 0xec4e6c89, 0x452821e6, 0x38d01377, 0xbe5466cf, 0x34e90c6c,
		0xc0ac29b7, 0xc97c50dd, 0x3f84d5b5, 0xb5470917, 0x9216d5d9, 0x8979fb1b
	);
	private static function bff($i, $sbox){
		$x0 = $i & 0xff;
		$x1 = ($i >> 8)  & 0xff;
		$x2 = ($i >> 16) & 0xff;
		$x3 = ($i >> 24) & 0xff;
		$f  = ($sbox[0][$x3] + $sbox[1][$x2]) & 0xffffffff;
		$f  = ($f ^ $sbox[2][$x1]) & 0xffffffff;
		return ($f + $sbox[3][$x0]) & 0xffffffff;
	}
	protected static $tfq = array(
		array(
			0xA9, 0x67, 0xB3, 0xE8, 0x04, 0xFD, 0xA3, 0x76, 0x9A, 0x92, 0x80, 0x78, 0xE4, 0xDD, 0xD1, 0x38,
			0x0D, 0xC6, 0x35, 0x98, 0x18, 0xF7, 0xEC, 0x6C, 0x43, 0x75, 0x37, 0x26, 0xFA, 0x13, 0x94, 0x48,
			0xF2, 0xD0, 0x8B, 0x30, 0x84, 0x54, 0xDF, 0x23, 0x19, 0x5B, 0x3D, 0x59, 0xF3, 0xAE, 0xA2, 0x82,
			0x63, 0x01, 0x83, 0x2E, 0xD9, 0x51, 0x9B, 0x7C, 0xA6, 0xEB, 0xA5, 0xBE, 0x16, 0x0C, 0xE3, 0x61,
			0xC0, 0x8C, 0x3A, 0xF5, 0x73, 0x2C, 0x25, 0x0B, 0xBB, 0x4E, 0x89, 0x6B, 0x53, 0x6A, 0xB4, 0xF1,
			0xE1, 0xE6, 0xBD, 0x45, 0xE2, 0xF4, 0xB6, 0x66, 0xCC, 0x95, 0x03, 0x56, 0xD4, 0x1C, 0x1E, 0xD7,
			0xFB, 0xC3, 0x8E, 0xB5, 0xE9, 0xCF, 0xBF, 0xBA, 0xEA, 0x77, 0x39, 0xAF, 0x33, 0xC9, 0x62, 0x71,
			0x81, 0x79, 0x09, 0xAD, 0x24, 0xCD, 0xF9, 0xD8, 0xE5, 0xC5, 0xB9, 0x4D, 0x44, 0x08, 0x86, 0xE7,
			0xA1, 0x1D, 0xAA, 0xED, 0x06, 0x70, 0xB2, 0xD2, 0x41, 0x7B, 0xA0, 0x11, 0x31, 0xC2, 0x27, 0x90,
			0x20, 0xF6, 0x60, 0xFF, 0x96, 0x5C, 0xB1, 0xAB, 0x9E, 0x9C, 0x52, 0x1B, 0x5F, 0x93, 0x0A, 0xEF,
			0x91, 0x85, 0x49, 0xEE, 0x2D, 0x4F, 0x8F, 0x3B, 0x47, 0x87, 0x6D, 0x46, 0xD6, 0x3E, 0x69, 0x64,
			0x2A, 0xCE, 0xCB, 0x2F, 0xFC, 0x97, 0x05, 0x7A, 0xAC, 0x7F, 0xD5, 0x1A, 0x4B, 0x0E, 0xA7, 0x5A,
			0x28, 0x14, 0x3F, 0x29, 0x88, 0x3C, 0x4C, 0x02, 0xB8, 0xDA, 0xB0, 0x17, 0x55, 0x1F, 0x8A, 0x7D,
			0x57, 0xC7, 0x8D, 0x74, 0xB7, 0xC4, 0x9F, 0x72, 0x7E, 0x15, 0x22, 0x12, 0x58, 0x07, 0x99, 0x34,
			0x6E, 0x50, 0xDE, 0x68, 0x65, 0xBC, 0xDB, 0xF8, 0xC8, 0xA8, 0x2B, 0x40, 0xDC, 0xFE, 0x32, 0xA4,
			0xCA, 0x10, 0x21, 0xF0, 0xD3, 0x5D, 0x0F, 0x00, 0x6F, 0x9D, 0x36, 0x42, 0x4A, 0x5E, 0xC1, 0xE0
		), array(
			0x75, 0xF3, 0xC6, 0xF4, 0xDB, 0x7B, 0xFB, 0xC8, 0x4A, 0xD3, 0xE6, 0x6B, 0x45, 0x7D, 0xE8, 0x4B,
			0xD6, 0x32, 0xD8, 0xFD, 0x37, 0x71, 0xF1, 0xE1, 0x30, 0x0F, 0xF8, 0x1B, 0x87, 0xFA, 0x06, 0x3F,
			0x5E, 0xBA, 0xAE, 0x5B, 0x8A, 0x00, 0xBC, 0x9D, 0x6D, 0xC1, 0xB1, 0x0E, 0x80, 0x5D, 0xD2, 0xD5,
			0xA0, 0x84, 0x07, 0x14, 0xB5, 0x90, 0x2C, 0xA3, 0xB2, 0x73, 0x4C, 0x54, 0x92, 0x74, 0x36, 0x51,
			0x38, 0xB0, 0xBD, 0x5A, 0xFC, 0x60, 0x62, 0x96, 0x6C, 0x42, 0xF7, 0x10, 0x7C, 0x28, 0x27, 0x8C,
			0x13, 0x95, 0x9C, 0xC7, 0x24, 0x46, 0x3B, 0x70, 0xCA, 0xE3, 0x85, 0xCB, 0x11, 0xD0, 0x93, 0xB8,
			0xA6, 0x83, 0x20, 0xFF, 0x9F, 0x77, 0xC3, 0xCC, 0x03, 0x6F, 0x08, 0xBF, 0x40, 0xE7, 0x2B, 0xE2,
			0x79, 0x0C, 0xAA, 0x82, 0x41, 0x3A, 0xEA, 0xB9, 0xE4, 0x9A, 0xA4, 0x97, 0x7E, 0xDA, 0x7A, 0x17,
			0x66, 0x94, 0xA1, 0x1D, 0x3D, 0xF0, 0xDE, 0xB3, 0x0B, 0x72, 0xA7, 0x1C, 0xEF, 0xD1, 0x53, 0x3E,
			0x8F, 0x33, 0x26, 0x5F, 0xEC, 0x76, 0x2A, 0x49, 0x81, 0x88, 0xEE, 0x21, 0xC4, 0x1A, 0xEB, 0xD9,
			0xC5, 0x39, 0x99, 0xCD, 0xAD, 0x31, 0x8B, 0x01, 0x18, 0x23, 0xDD, 0x1F, 0x4E, 0x2D, 0xF9, 0x48,
			0x4F, 0xF2, 0x65, 0x8E, 0x78, 0x5C, 0x58, 0x19, 0x8D, 0xE5, 0x98, 0x57, 0x67, 0x7F, 0x05, 0x64,
			0xAF, 0x63, 0xB6, 0xFE, 0xF5, 0xB7, 0x3C, 0xA5, 0xCE, 0xE9, 0x68, 0x44, 0xE0, 0x4D, 0x43, 0x69,
			0x29, 0x2E, 0xAC, 0x15, 0x59, 0xA8, 0x0A, 0x9E, 0x6E, 0x47, 0xDF, 0x34, 0x35, 0x6A, 0xCF, 0xDC,
			0x22, 0xC9, 0xC0, 0x9B, 0x89, 0xD4, 0xED, 0xAB, 0x12, 0xA2, 0x0D, 0x52, 0xBB, 0x02, 0x2F, 0xA9,
			0xD7, 0x61, 0x1E, 0xB4, 0x50, 0x04, 0xF6, 0xC2, 0x16, 0x25, 0x86, 0x56, 0x55, 0x09, 0xBE, 0x91
		)
	);
	protected static $tfm = array(
		array(
			0xBCBC3275, 0xECEC21F3, 0x202043C6, 0xB3B3C9F4, 0xDADA03DB, 0x02028B7B, 0xE2E22BFB, 0x9E9EFAC8,
			0xC9C9EC4A, 0xD4D409D3, 0x18186BE6, 0x1E1E9F6B, 0x98980E45, 0xB2B2387D, 0xA6A6D2E8, 0x2626B74B,
			0x3C3C57D6, 0x93938A32, 0x8282EED8, 0x525298FD, 0x7B7BD437, 0xBBBB3771, 0x5B5B97F1, 0x474783E1,
			0x24243C30, 0x5151E20F, 0xBABAC6F8, 0x4A4AF31B, 0xBFBF4887, 0x0D0D70FA, 0xB0B0B306, 0x7575DE3F,
			0xD2D2FD5E, 0x7D7D20BA, 0x666631AE, 0x3A3AA35B, 0x59591C8A, 0x00000000, 0xCDCD93BC, 0x1A1AE09D,
			0xAEAE2C6D, 0x7F7FABC1, 0x2B2BC7B1, 0xBEBEB90E, 0xE0E0A080, 0x8A8A105D, 0x3B3B52D2, 0x6464BAD5,
			0xD8D888A0, 0xE7E7A584, 0x5F5FE807, 0x1B1B1114, 0x2C2CC2B5, 0xFCFCB490, 0x3131272C, 0x808065A3,
			0x73732AB2, 0x0C0C8173, 0x79795F4C, 0x6B6B4154, 0x4B4B0292, 0x53536974, 0x94948F36, 0x83831F51,
			0x2A2A3638, 0xC4C49CB0, 0x2222C8BD, 0xD5D5F85A, 0xBDBDC3FC, 0x48487860, 0xFFFFCE62, 0x4C4C0796,
			0x4141776C, 0xC7C7E642, 0xEBEB24F7, 0x1C1C1410, 0x5D5D637C, 0x36362228, 0x6767C027, 0xE9E9AF8C,
			0x4444F913, 0x1414EA95, 0xF5F5BB9C, 0xCFCF18C7, 0x3F3F2D24, 0xC0C0E346, 0x7272DB3B, 0x54546C70,
			0x29294CCA, 0xF0F035E3, 0x0808FE85, 0xC6C617CB, 0xF3F34F11, 0x8C8CE4D0, 0xA4A45993, 0xCACA96B8,
			0x68683BA6, 0xB8B84D83, 0x38382820, 0xE5E52EFF, 0xADAD569F, 0x0B0B8477, 0xC8C81DC3, 0x9999FFCC,
			0x5858ED03, 0x19199A6F, 0x0E0E0A08, 0x95957EBF, 0x70705040, 0xF7F730E7, 0x6E6ECF2B, 0x1F1F6EE2,
			0xB5B53D79, 0x09090F0C, 0x616134AA, 0x57571682, 0x9F9F0B41, 0x9D9D803A, 0x111164EA, 0x2525CDB9,
			0xAFAFDDE4, 0x4545089A, 0xDFDF8DA4, 0xA3A35C97, 0xEAEAD57E, 0x353558DA, 0xEDEDD07A, 0x4343FC17,
			0xF8F8CB66, 0xFBFBB194, 0x3737D3A1, 0xFAFA401D, 0xC2C2683D, 0xB4B4CCF0, 0x32325DDE, 0x9C9C71B3,
			0x5656E70B, 0xE3E3DA72, 0x878760A7, 0x15151B1C, 0xF9F93AEF, 0x6363BFD1, 0x3434A953, 0x9A9A853E,
			0xB1B1428F, 0x7C7CD133, 0x88889B26, 0x3D3DA65F, 0xA1A1D7EC, 0xE4E4DF76, 0x8181942A, 0x91910149,
			0x0F0FFB81, 0xEEEEAA88, 0x161661EE, 0xD7D77321, 0x9797F5C4, 0xA5A5A81A, 0xFEFE3FEB, 0x6D6DB5D9,
			0x7878AEC5, 0xC5C56D39, 0x1D1DE599, 0x7676A4CD, 0x3E3EDCAD, 0xCBCB6731, 0xB6B6478B, 0xEFEF5B01,
			0x12121E18, 0x6060C523, 0x6A6AB0DD, 0x4D4DF61F, 0xCECEE94E, 0xDEDE7C2D, 0x55559DF9, 0x7E7E5A48,
			0x2121B24F, 0x03037AF2, 0xA0A02665, 0x5E5E198E, 0x5A5A6678, 0x65654B5C, 0x62624E58, 0xFDFD4519,
			0x0606F48D, 0x404086E5, 0xF2F2BE98, 0x3333AC57, 0x17179067, 0x05058E7F, 0xE8E85E05, 0x4F4F7D64,
			0x89896AAF, 0x10109563, 0x74742FB6, 0x0A0A75FE, 0x5C5C92F5, 0x9B9B74B7, 0x2D2D333C, 0x3030D6A5,
			0x2E2E49CE, 0x494989E9, 0x46467268, 0x77775544, 0xA8A8D8E0, 0x9696044D, 0x2828BD43, 0xA9A92969,
			0xD9D97929, 0x8686912E, 0xD1D187AC, 0xF4F44A15, 0x8D8D1559, 0xD6D682A8, 0xB9B9BC0A, 0x42420D9E,
			0xF6F6C16E, 0x2F2FB847, 0xDDDD06DF, 0x23233934, 0xCCCC6235, 0xF1F1C46A, 0xC1C112CF, 0x8585EBDC,
			0x8F8F9E22, 0x7171A1C9, 0x9090F0C0, 0xAAAA539B, 0x0101F189, 0x8B8BE1D4, 0x4E4E8CED, 0x8E8E6FAB,
			0xABABA212, 0x6F6F3EA2, 0xE6E6540D, 0xDBDBF252, 0x92927BBB, 0xB7B7B602, 0x6969CA2F, 0x3939D9A9,
			0xD3D30CD7, 0xA7A72361, 0xA2A2AD1E, 0xC3C399B4, 0x6C6C4450, 0x07070504, 0x04047FF6, 0x272746C2,
			0xACACA716, 0xD0D07625, 0x50501386, 0xDCDCF756, 0x84841A55, 0xE1E15109, 0x7A7A25BE, 0x1313EF91
		), array(
			0xA9D93939, 0x67901717, 0xB3719C9C, 0xE8D2A6A6, 0x04050707, 0xFD985252, 0xA3658080, 0x76DFE4E4,
			0x9A084545, 0x92024B4B, 0x80A0E0E0, 0x78665A5A, 0xE4DDAFAF, 0xDDB06A6A, 0xD1BF6363, 0x38362A2A,
			0x0D54E6E6, 0xC6432020, 0x3562CCCC, 0x98BEF2F2, 0x181E1212, 0xF724EBEB, 0xECD7A1A1, 0x6C774141,
			0x43BD2828, 0x7532BCBC, 0x37D47B7B, 0x269B8888, 0xFA700D0D, 0x13F94444, 0x94B1FBFB, 0x485A7E7E,
			0xF27A0303, 0xD0E48C8C, 0x8B47B6B6, 0x303C2424, 0x84A5E7E7, 0x54416B6B, 0xDF06DDDD, 0x23C56060,
			0x1945FDFD, 0x5BA33A3A, 0x3D68C2C2, 0x59158D8D, 0xF321ECEC, 0xAE316666, 0xA23E6F6F, 0x82165757,
			0x63951010, 0x015BEFEF, 0x834DB8B8, 0x2E918686, 0xD9B56D6D, 0x511F8383, 0x9B53AAAA, 0x7C635D5D,
			0xA63B6868, 0xEB3FFEFE, 0xA5D63030, 0xBE257A7A, 0x16A7ACAC, 0x0C0F0909, 0xE335F0F0, 0x6123A7A7,
			0xC0F09090, 0x8CAFE9E9, 0x3A809D9D, 0xF5925C5C, 0x73810C0C, 0x2C273131, 0x2576D0D0, 0x0BE75656,
			0xBB7B9292, 0x4EE9CECE, 0x89F10101, 0x6B9F1E1E, 0x53A93434, 0x6AC4F1F1, 0xB499C3C3, 0xF1975B5B,
			0xE1834747, 0xE66B1818, 0xBDC82222, 0x450E9898, 0xE26E1F1F, 0xF4C9B3B3, 0xB62F7474, 0x66CBF8F8,
			0xCCFF9999, 0x95EA1414, 0x03ED5858, 0x56F7DCDC, 0xD4E18B8B, 0x1C1B1515, 0x1EADA2A2, 0xD70CD3D3,
			0xFB2BE2E2, 0xC31DC8C8, 0x8E195E5E, 0xB5C22C2C, 0xE9894949, 0xCF12C1C1, 0xBF7E9595, 0xBA207D7D,
			0xEA641111, 0x77840B0B, 0x396DC5C5, 0xAF6A8989, 0x33D17C7C, 0xC9A17171, 0x62CEFFFF, 0x7137BBBB,
			0x81FB0F0F, 0x793DB5B5, 0x0951E1E1, 0xADDC3E3E, 0x242D3F3F, 0xCDA47676, 0xF99D5555, 0xD8EE8282,
			0xE5864040, 0xC5AE7878, 0xB9CD2525, 0x4D049696, 0x44557777, 0x080A0E0E, 0x86135050, 0xE730F7F7,
			0xA1D33737, 0x1D40FAFA, 0xAA346161, 0xED8C4E4E, 0x06B3B0B0, 0x706C5454, 0xB22A7373, 0xD2523B3B,
			0x410B9F9F, 0x7B8B0202, 0xA088D8D8, 0x114FF3F3, 0x3167CBCB, 0xC2462727, 0x27C06767, 0x90B4FCFC,
			0x20283838, 0xF67F0404, 0x60784848, 0xFF2EE5E5, 0x96074C4C, 0x5C4B6565, 0xB1C72B2B, 0xAB6F8E8E,
			0x9E0D4242, 0x9CBBF5F5, 0x52F2DBDB, 0x1BF34A4A, 0x5FA63D3D, 0x9359A4A4, 0x0ABCB9B9, 0xEF3AF9F9,
			0x91EF1313, 0x85FE0808, 0x49019191, 0xEE611616, 0x2D7CDEDE, 0x4FB22121, 0x8F42B1B1, 0x3BDB7272,
			0x47B82F2F, 0x8748BFBF, 0x6D2CAEAE, 0x46E3C0C0, 0xD6573C3C, 0x3E859A9A, 0x6929A9A9, 0x647D4F4F,
			0x2A948181, 0xCE492E2E, 0xCB17C6C6, 0x2FCA6969, 0xFCC3BDBD, 0x975CA3A3, 0x055EE8E8, 0x7AD0EDED,
			0xAC87D1D1, 0x7F8E0505, 0xD5BA6464, 0x1AA8A5A5, 0x4BB72626, 0x0EB9BEBE, 0xA7608787, 0x5AF8D5D5,
			0x28223636, 0x14111B1B, 0x3FDE7575, 0x2979D9D9, 0x88AAEEEE, 0x3C332D2D, 0x4C5F7979, 0x02B6B7B7,
			0xB896CACA, 0xDA583535, 0xB09CC4C4, 0x17FC4343, 0x551A8484, 0x1FF64D4D, 0x8A1C5959, 0x7D38B2B2,
			0x57AC3333, 0xC718CFCF, 0x8DF40606, 0x74695353, 0xB7749B9B, 0xC4F59797, 0x9F56ADAD, 0x72DAE3E3,
			0x7ED5EAEA, 0x154AF4F4, 0x229E8F8F, 0x12A2ABAB, 0x584E6262, 0x07E85F5F, 0x99E51D1D, 0x34392323,
			0x6EC1F6F6, 0x50446C6C, 0xDE5D3232, 0x68724646, 0x6526A0A0, 0xBC93CDCD, 0xDB03DADA, 0xF8C6BABA,
			0xC8FA9E9E, 0xA882D6D6, 0x2BCF6E6E, 0x40507070, 0xDCEB8585, 0xFE750A0A, 0x328A9393, 0xA48DDFDF,
			0xCA4C2929, 0x10141C1C, 0x2173D7D7, 0xF0CCB4B4, 0xD309D4D4, 0x5D108A8A, 0x0FE25151, 0x00000000,
			0x6F9A1919, 0x9DE01A1A, 0x368F9494, 0x42E6C7C7, 0x4AECC9C9, 0x5EFDD2D2, 0xC1AB7F7F, 0xE0D8A8A8
		), array(
			0xBC75BC32, 0xECF3EC21, 0x20C62043, 0xB3F4B3C9, 0xDADBDA03, 0x027B028B, 0xE2FBE22B, 0x9EC89EFA,
			0xC94AC9EC, 0xD4D3D409, 0x18E6186B, 0x1E6B1E9F, 0x9845980E, 0xB27DB238, 0xA6E8A6D2, 0x264B26B7,
			0x3CD63C57, 0x9332938A, 0x82D882EE, 0x52FD5298, 0x7B377BD4, 0xBB71BB37, 0x5BF15B97, 0x47E14783,
			0x2430243C, 0x510F51E2, 0xBAF8BAC6, 0x4A1B4AF3, 0xBF87BF48, 0x0DFA0D70, 0xB006B0B3, 0x753F75DE,
			0xD25ED2FD, 0x7DBA7D20, 0x66AE6631, 0x3A5B3AA3, 0x598A591C, 0x00000000, 0xCDBCCD93, 0x1A9D1AE0,
			0xAE6DAE2C, 0x7FC17FAB, 0x2BB12BC7, 0xBE0EBEB9, 0xE080E0A0, 0x8A5D8A10, 0x3BD23B52, 0x64D564BA,
			0xD8A0D888, 0xE784E7A5, 0x5F075FE8, 0x1B141B11, 0x2CB52CC2, 0xFC90FCB4, 0x312C3127, 0x80A38065,
			0x73B2732A, 0x0C730C81, 0x794C795F, 0x6B546B41, 0x4B924B02, 0x53745369, 0x9436948F, 0x8351831F,
			0x2A382A36, 0xC4B0C49C, 0x22BD22C8, 0xD55AD5F8, 0xBDFCBDC3, 0x48604878, 0xFF62FFCE, 0x4C964C07,
			0x416C4177, 0xC742C7E6, 0xEBF7EB24, 0x1C101C14, 0x5D7C5D63, 0x36283622, 0x672767C0, 0xE98CE9AF,
			0x441344F9, 0x149514EA, 0xF59CF5BB, 0xCFC7CF18, 0x3F243F2D, 0xC046C0E3, 0x723B72DB, 0x5470546C,
			0x29CA294C, 0xF0E3F035, 0x088508FE, 0xC6CBC617, 0xF311F34F, 0x8CD08CE4, 0xA493A459, 0xCAB8CA96,
			0x68A6683B, 0xB883B84D, 0x38203828, 0xE5FFE52E, 0xAD9FAD56, 0x0B770B84, 0xC8C3C81D, 0x99CC99FF,
			0x580358ED, 0x196F199A, 0x0E080E0A, 0x95BF957E, 0x70407050, 0xF7E7F730, 0x6E2B6ECF, 0x1FE21F6E,
			0xB579B53D, 0x090C090F, 0x61AA6134, 0x57825716, 0x9F419F0B, 0x9D3A9D80, 0x11EA1164, 0x25B925CD,
			0xAFE4AFDD, 0x459A4508, 0xDFA4DF8D, 0xA397A35C, 0xEA7EEAD5, 0x35DA3558, 0xED7AEDD0, 0x431743FC,
			0xF866F8CB, 0xFB94FBB1, 0x37A137D3, 0xFA1DFA40, 0xC23DC268, 0xB4F0B4CC, 0x32DE325D, 0x9CB39C71,
			0x560B56E7, 0xE372E3DA, 0x87A78760, 0x151C151B, 0xF9EFF93A, 0x63D163BF, 0x345334A9, 0x9A3E9A85,
			0xB18FB142, 0x7C337CD1, 0x8826889B, 0x3D5F3DA6, 0xA1ECA1D7, 0xE476E4DF, 0x812A8194, 0x91499101,
			0x0F810FFB, 0xEE88EEAA, 0x16EE1661, 0xD721D773, 0x97C497F5, 0xA51AA5A8, 0xFEEBFE3F, 0x6DD96DB5,
			0x78C578AE, 0xC539C56D, 0x1D991DE5, 0x76CD76A4, 0x3EAD3EDC, 0xCB31CB67, 0xB68BB647, 0xEF01EF5B,
			0x1218121E, 0x602360C5, 0x6ADD6AB0, 0x4D1F4DF6, 0xCE4ECEE9, 0xDE2DDE7C, 0x55F9559D, 0x7E487E5A,
			0x214F21B2, 0x03F2037A, 0xA065A026, 0x5E8E5E19, 0x5A785A66, 0x655C654B, 0x6258624E, 0xFD19FD45,
			0x068D06F4, 0x40E54086, 0xF298F2BE, 0x335733AC, 0x17671790, 0x057F058E, 0xE805E85E, 0x4F644F7D,
			0x89AF896A, 0x10631095, 0x74B6742F, 0x0AFE0A75, 0x5CF55C92, 0x9BB79B74, 0x2D3C2D33, 0x30A530D6,
			0x2ECE2E49, 0x49E94989, 0x46684672, 0x77447755, 0xA8E0A8D8, 0x964D9604, 0x284328BD, 0xA969A929,
			0xD929D979, 0x862E8691, 0xD1ACD187, 0xF415F44A, 0x8D598D15, 0xD6A8D682, 0xB90AB9BC, 0x429E420D,
			0xF66EF6C1, 0x2F472FB8, 0xDDDFDD06, 0x23342339, 0xCC35CC62, 0xF16AF1C4, 0xC1CFC112, 0x85DC85EB,
			0x8F228F9E, 0x71C971A1, 0x90C090F0, 0xAA9BAA53, 0x018901F1, 0x8BD48BE1, 0x4EED4E8C, 0x8EAB8E6F,
			0xAB12ABA2, 0x6FA26F3E, 0xE60DE654, 0xDB52DBF2, 0x92BB927B, 0xB702B7B6, 0x692F69CA, 0x39A939D9,
			0xD3D7D30C, 0xA761A723, 0xA21EA2AD, 0xC3B4C399, 0x6C506C44, 0x07040705, 0x04F6047F, 0x27C22746,
			0xAC16ACA7, 0xD025D076, 0x50865013, 0xDC56DCF7, 0x8455841A, 0xE109E151, 0x7ABE7A25, 0x139113EF
		), array(
			0xD939A9D9, 0x90176790, 0x719CB371, 0xD2A6E8D2, 0x05070405, 0x9852FD98, 0x6580A365, 0xDFE476DF,
			0x08459A08, 0x024B9202, 0xA0E080A0, 0x665A7866, 0xDDAFE4DD, 0xB06ADDB0, 0xBF63D1BF, 0x362A3836,
			0x54E60D54, 0x4320C643, 0x62CC3562, 0xBEF298BE, 0x1E12181E, 0x24EBF724, 0xD7A1ECD7, 0x77416C77,
			0xBD2843BD, 0x32BC7532, 0xD47B37D4, 0x9B88269B, 0x700DFA70, 0xF94413F9, 0xB1FB94B1, 0x5A7E485A,
			0x7A03F27A, 0xE48CD0E4, 0x47B68B47, 0x3C24303C, 0xA5E784A5, 0x416B5441, 0x06DDDF06, 0xC56023C5,
			0x45FD1945, 0xA33A5BA3, 0x68C23D68, 0x158D5915, 0x21ECF321, 0x3166AE31, 0x3E6FA23E, 0x16578216,
			0x95106395, 0x5BEF015B, 0x4DB8834D, 0x91862E91, 0xB56DD9B5, 0x1F83511F, 0x53AA9B53, 0x635D7C63,
			0x3B68A63B, 0x3FFEEB3F, 0xD630A5D6, 0x257ABE25, 0xA7AC16A7, 0x0F090C0F, 0x35F0E335, 0x23A76123,
			0xF090C0F0, 0xAFE98CAF, 0x809D3A80, 0x925CF592, 0x810C7381, 0x27312C27, 0x76D02576, 0xE7560BE7,
			0x7B92BB7B, 0xE9CE4EE9, 0xF10189F1, 0x9F1E6B9F, 0xA93453A9, 0xC4F16AC4, 0x99C3B499, 0x975BF197,
			0x8347E183, 0x6B18E66B, 0xC822BDC8, 0x0E98450E, 0x6E1FE26E, 0xC9B3F4C9, 0x2F74B62F, 0xCBF866CB,
			0xFF99CCFF, 0xEA1495EA, 0xED5803ED, 0xF7DC56F7, 0xE18BD4E1, 0x1B151C1B, 0xADA21EAD, 0x0CD3D70C,
			0x2BE2FB2B, 0x1DC8C31D, 0x195E8E19, 0xC22CB5C2, 0x8949E989, 0x12C1CF12, 0x7E95BF7E, 0x207DBA20,
			0x6411EA64, 0x840B7784, 0x6DC5396D, 0x6A89AF6A, 0xD17C33D1, 0xA171C9A1, 0xCEFF62CE, 0x37BB7137,
			0xFB0F81FB, 0x3DB5793D, 0x51E10951, 0xDC3EADDC, 0x2D3F242D, 0xA476CDA4, 0x9D55F99D, 0xEE82D8EE,
			0x8640E586, 0xAE78C5AE, 0xCD25B9CD, 0x04964D04, 0x55774455, 0x0A0E080A, 0x13508613, 0x30F7E730,
			0xD337A1D3, 0x40FA1D40, 0x3461AA34, 0x8C4EED8C, 0xB3B006B3, 0x6C54706C, 0x2A73B22A, 0x523BD252,
			0x0B9F410B, 0x8B027B8B, 0x88D8A088, 0x4FF3114F, 0x67CB3167, 0x4627C246, 0xC06727C0, 0xB4FC90B4,
			0x28382028, 0x7F04F67F, 0x78486078, 0x2EE5FF2E, 0x074C9607, 0x4B655C4B, 0xC72BB1C7, 0x6F8EAB6F,
			0x0D429E0D, 0xBBF59CBB, 0xF2DB52F2, 0xF34A1BF3, 0xA63D5FA6, 0x59A49359, 0xBCB90ABC, 0x3AF9EF3A,
			0xEF1391EF, 0xFE0885FE, 0x01914901, 0x6116EE61, 0x7CDE2D7C, 0xB2214FB2, 0x42B18F42, 0xDB723BDB,
			0xB82F47B8, 0x48BF8748, 0x2CAE6D2C, 0xE3C046E3, 0x573CD657, 0x859A3E85, 0x29A96929, 0x7D4F647D,
			0x94812A94, 0x492ECE49, 0x17C6CB17, 0xCA692FCA, 0xC3BDFCC3, 0x5CA3975C, 0x5EE8055E, 0xD0ED7AD0,
			0x87D1AC87, 0x8E057F8E, 0xBA64D5BA, 0xA8A51AA8, 0xB7264BB7, 0xB9BE0EB9, 0x6087A760, 0xF8D55AF8,
			0x22362822, 0x111B1411, 0xDE753FDE, 0x79D92979, 0xAAEE88AA, 0x332D3C33, 0x5F794C5F, 0xB6B702B6,
			0x96CAB896, 0x5835DA58, 0x9CC4B09C, 0xFC4317FC, 0x1A84551A, 0xF64D1FF6, 0x1C598A1C, 0x38B27D38,
			0xAC3357AC, 0x18CFC718, 0xF4068DF4, 0x69537469, 0x749BB774, 0xF597C4F5, 0x56AD9F56, 0xDAE372DA,
			0xD5EA7ED5, 0x4AF4154A, 0x9E8F229E, 0xA2AB12A2, 0x4E62584E, 0xE85F07E8, 0xE51D99E5, 0x39233439,
			0xC1F66EC1, 0x446C5044, 0x5D32DE5D, 0x72466872, 0x26A06526, 0x93CDBC93, 0x03DADB03, 0xC6BAF8C6,
			0xFA9EC8FA, 0x82D6A882, 0xCF6E2BCF, 0x50704050, 0xEB85DCEB, 0x750AFE75, 0x8A93328A, 0x8DDFA48D,
			0x4C29CA4C, 0x141C1014, 0x73D72173, 0xCCB4F0CC, 0x09D4D309, 0x108A5D10, 0xE2510FE2, 0x00000000,
			0x9A196F9A, 0xE01A9DE0, 0x8F94368F, 0xE6C742E6, 0xECC94AEC, 0xFDD25EFD, 0xAB7FC1AB, 0xD8A8E0D8
		)
	);
	protected static $rjt = array(
		0x6363A5C6, 0x7C7C84F8, 0x777799EE, 0x7B7B8DF6, 0xF2F20DFF, 0x6B6BBDD6, 0x6F6FB1DE, 0xC5C55491,
		0x30305060, 0x01010302, 0x6767A9CE, 0x2B2B7D56, 0xFEFE19E7, 0xD7D762B5, 0xABABE64D, 0x76769AEC,
		0xCACA458F, 0x82829D1F, 0xC9C94089, 0x7D7D87FA, 0xFAFA15EF, 0x5959EBB2, 0x4747C98E, 0xF0F00BFB,
		0xADADEC41, 0xD4D467B3, 0xA2A2FD5F, 0xAFAFEA45, 0x9C9CBF23, 0xA4A4F753, 0x727296E4, 0xC0C05B9B,
		0xB7B7C275, 0xFDFD1CE1, 0x9393AE3D, 0x26266A4C, 0x36365A6C, 0x3F3F417E, 0xF7F702F5, 0xCCCC4F83,
		0x34345C68, 0xA5A5F451, 0xE5E534D1, 0xF1F108F9, 0x717193E2, 0xD8D873AB, 0x31315362, 0x15153F2A,
		0x04040C08, 0xC7C75295, 0x23236546, 0xC3C35E9D, 0x18182830, 0x9696A137, 0x05050F0A, 0x9A9AB52F,
		0x0707090E, 0x12123624, 0x80809B1B, 0xE2E23DDF, 0xEBEB26CD, 0x2727694E, 0xB2B2CD7F, 0x75759FEA,
		0x09091B12, 0x83839E1D, 0x2C2C7458, 0x1A1A2E34, 0x1B1B2D36, 0x6E6EB2DC, 0x5A5AEEB4, 0xA0A0FB5B,
		0x5252F6A4, 0x3B3B4D76, 0xD6D661B7, 0xB3B3CE7D, 0x29297B52, 0xE3E33EDD, 0x2F2F715E, 0x84849713,
		0x5353F5A6, 0xD1D168B9, 0x00000000, 0xEDED2CC1, 0x20206040, 0xFCFC1FE3, 0xB1B1C879, 0x5B5BEDB6,
		0x6A6ABED4, 0xCBCB468D, 0xBEBED967, 0x39394B72, 0x4A4ADE94, 0x4C4CD498, 0x5858E8B0, 0xCFCF4A85,
		0xD0D06BBB, 0xEFEF2AC5, 0xAAAAE54F, 0xFBFB16ED, 0x4343C586, 0x4D4DD79A, 0x33335566, 0x85859411,
		0x4545CF8A, 0xF9F910E9, 0x02020604, 0x7F7F81FE, 0x5050F0A0, 0x3C3C4478, 0x9F9FBA25, 0xA8A8E34B,
		0x5151F3A2, 0xA3A3FE5D, 0x4040C080, 0x8F8F8A05, 0x9292AD3F, 0x9D9DBC21, 0x38384870, 0xF5F504F1,
		0xBCBCDF63, 0xB6B6C177, 0xDADA75AF, 0x21216342, 0x10103020, 0xFFFF1AE5, 0xF3F30EFD, 0xD2D26DBF,
		0xCDCD4C81, 0x0C0C1418, 0x13133526, 0xECEC2FC3, 0x5F5FE1BE, 0x9797A235, 0x4444CC88, 0x1717392E,
		0xC4C45793, 0xA7A7F255, 0x7E7E82FC, 0x3D3D477A, 0x6464ACC8, 0x5D5DE7BA, 0x19192B32, 0x737395E6,
		0x6060A0C0, 0x81819819, 0x4F4FD19E, 0xDCDC7FA3, 0x22226644, 0x2A2A7E54, 0x9090AB3B, 0x8888830B,
		0x4646CA8C, 0xEEEE29C7, 0xB8B8D36B, 0x14143C28, 0xDEDE79A7, 0x5E5EE2BC, 0x0B0B1D16, 0xDBDB76AD,
		0xE0E03BDB, 0x32325664, 0x3A3A4E74, 0x0A0A1E14, 0x4949DB92, 0x06060A0C, 0x24246C48, 0x5C5CE4B8,
		0xC2C25D9F, 0xD3D36EBD, 0xACACEF43, 0x6262A6C4, 0x9191A839, 0x9595A431, 0xE4E437D3, 0x79798BF2,
		0xE7E732D5, 0xC8C8438B, 0x3737596E, 0x6D6DB7DA, 0x8D8D8C01, 0xD5D564B1, 0x4E4ED29C, 0xA9A9E049,
		0x6C6CB4D8, 0x5656FAAC, 0xF4F407F3, 0xEAEA25CF, 0x6565AFCA, 0x7A7A8EF4, 0xAEAEE947, 0x08081810,
		0xBABAD56F, 0x787888F0, 0x25256F4A, 0x2E2E725C, 0x1C1C2438, 0xA6A6F157, 0xB4B4C773, 0xC6C65197,
		0xE8E823CB, 0xDDDD7CA1, 0x74749CE8, 0x1F1F213E, 0x4B4BDD96, 0xBDBDDC61, 0x8B8B860D, 0x8A8A850F,
		0x707090E0, 0x3E3E427C, 0xB5B5C471, 0x6666AACC, 0x4848D890, 0x03030506, 0xF6F601F7, 0x0E0E121C,
		0x6161A3C2, 0x35355F6A, 0x5757F9AE, 0xB9B9D069, 0x86869117, 0xC1C15899, 0x1D1D273A, 0x9E9EB927,
		0xE1E138D9, 0xF8F813EB, 0x9898B32B, 0x11113322, 0x6969BBD2, 0xD9D970A9, 0x8E8E8907, 0x9494A733,
		0x9B9BB62D, 0x1E1E223C, 0x87879215, 0xE9E920C9, 0xCECE4987, 0x5555FFAA, 0x28287850, 0xDFDF7AA5,
		0x8C8C8F03, 0xA1A1F859, 0x89898009, 0x0D0D171A, 0xBFBFDA65, 0xE6E631D7, 0x4242C684, 0x6868B8D0,
		0x4141C382, 0x9999B029, 0x2D2D775A, 0x0F0F111E, 0xB0B0CB7B, 0x5454FCA8, 0xBBBBD66D, 0x16163A2C
	);
	protected static $rjinvt = array(
		0xF4A75051, 0x4165537E, 0x17A4C31A, 0x275E963A, 0xAB6BCB3B, 0x9D45F11F, 0xFA58ABAC, 0xE303934B,
		0x30FA5520, 0x766DF6AD, 0xCC769188, 0x024C25F5, 0xE5D7FC4F, 0x2ACBD7C5, 0x35448026, 0x62A38FB5,
		0xB15A49DE, 0xBA1B6725, 0xEA0E9845, 0xFEC0E15D, 0x2F7502C3, 0x4CF01281, 0x4697A38D, 0xD3F9C66B,
		0x8F5FE703, 0x929C9515, 0x6D7AEBBF, 0x5259DA95, 0xBE832DD4, 0x7421D358, 0xE0692949, 0xC9C8448E,
		0xC2896A75, 0x8E7978F4, 0x583E6B99, 0xB971DD27, 0xE14FB6BE, 0x88AD17F0, 0x20AC66C9, 0xCE3AB47D,
		0xDF4A1863, 0x1A3182E5, 0x51336097, 0x537F4562, 0x6477E0B1, 0x6BAE84BB, 0x81A01CFE, 0x082B94F9,
		0x48685870, 0x45FD198F, 0xDE6C8794, 0x7BF8B752, 0x73D323AB, 0x4B02E272, 0x1F8F57E3, 0x55AB2A66,
		0xEB2807B2, 0xB5C2032F, 0xC57B9A86, 0x3708A5D3, 0x2887F230, 0xBFA5B223, 0x036ABA02, 0x16825CED,
		0xCF1C2B8A, 0x79B492A7, 0x07F2F0F3, 0x69E2A14E, 0xDAF4CD65, 0x05BED506, 0x34621FD1, 0xA6FE8AC4,
		0x2E539D34, 0xF355A0A2, 0x8AE13205, 0xF6EB75A4, 0x83EC390B, 0x60EFAA40, 0x719F065E, 0x6E1051BD,
		0x218AF93E, 0xDD063D96, 0x3E05AEDD, 0xE6BD464D, 0x548DB591, 0xC45D0571, 0x06D46F04, 0x5015FF60,
		0x98FB2419, 0xBDE997D6, 0x4043CC89, 0xD99E7767, 0xE842BDB0, 0x898B8807, 0x195B38E7, 0xC8EEDB79,
		0x7C0A47A1, 0x420FE97C, 0x841EC9F8, 0x00000000, 0x80868309, 0x2BED4832, 0x1170AC1E, 0x5A724E6C,
		0x0EFFFBFD, 0x8538560F, 0xAED51E3D, 0x2D392736, 0x0FD9640A, 0x5CA62168, 0x5B54D19B, 0x362E3A24,
		0x0A67B10C, 0x57E70F93, 0xEE96D2B4, 0x9B919E1B, 0xC0C54F80, 0xDC20A261, 0x774B695A, 0x121A161C,
		0x93BA0AE2, 0xA02AE5C0, 0x22E0433C, 0x1B171D12, 0x090D0B0E, 0x8BC7ADF2, 0xB6A8B92D, 0x1EA9C814,
		0xF1198557, 0x75074CAF, 0x99DDBBEE, 0x7F60FDA3, 0x01269FF7, 0x72F5BC5C, 0x663BC544, 0xFB7E345B,
		0x4329768B, 0x23C6DCCB, 0xEDFC68B6, 0xE4F163B8, 0x31DCCAD7, 0x63851042, 0x97224013, 0xC6112084,
		0x4A247D85, 0xBB3DF8D2, 0xF93211AE, 0x29A16DC7, 0x9E2F4B1D, 0xB230F3DC, 0x8652EC0D, 0xC1E3D077,
		0xB3166C2B, 0x70B999A9, 0x9448FA11, 0xE9642247, 0xFC8CC4A8, 0xF03F1AA0, 0x7D2CD856, 0x3390EF22,
		0x494EC787, 0x38D1C1D9, 0xCAA2FE8C, 0xD40B3698, 0xF581CFA6, 0x7ADE28A5, 0xB78E26DA, 0xADBFA43F,
		0x3A9DE42C, 0x78920D50, 0x5FCC9B6A, 0x7E466254, 0x8D13C2F6, 0xD8B8E890, 0x39F75E2E, 0xC3AFF582,
		0x5D80BE9F, 0xD0937C69, 0xD52DA96F, 0x2512B3CF, 0xAC993BC8, 0x187DA710, 0x9C636EE8, 0x3BBB7BDB,
		0x267809CD, 0x5918F46E, 0x9AB701EC, 0x4F9AA883, 0x956E65E6, 0xFFE67EAA, 0xBCCF0821, 0x15E8E6EF,
		0xE79BD9BA, 0x6F36CE4A, 0x9F09D4EA, 0xB07CD629, 0xA4B2AF31, 0x3F23312A, 0xA59430C6, 0xA266C035,
		0x4EBC3774, 0x82CAA6FC, 0x90D0B0E0, 0xA7D81533, 0x04984AF1, 0xECDAF741, 0xCD500E7F, 0x91F62F17,
		0x4DD68D76, 0xEFB04D43, 0xAA4D54CC, 0x9604DFE4, 0xD1B5E39E, 0x6A881B4C, 0x2C1FB8C1, 0x65517F46,
		0x5EEA049D, 0x8C355D01, 0x877473FA, 0x0B412EFB, 0x671D5AB3, 0xDBD25292, 0x105633E9, 0xD647136D,
		0xD7618C9A, 0xA10C7A37, 0xF8148E59, 0x133C89EB, 0xA927EECE, 0x61C935B7, 0x1CE5EDE1, 0x47B13C7A,
		0xD2DF599C, 0xF2733F55, 0x14CE7918, 0xC737BF73, 0xF7CDEA53, 0xFDAA5B5F, 0x3D6F14DF, 0x44DB8678,
		0xAFF381CA, 0x68C43EB9, 0x24342C38, 0xA3405FC2, 0x1DC37216, 0xE2250CBC, 0x3C498B28, 0x0D9541FF,
		0xA8017139, 0x0CB3DE08, 0xB4E49CD8, 0x56C19064, 0xCB84617B, 0x32B670D5, 0x6C5C7448, 0xB85742D0
	);
	protected static $rjs = array(
		0x63, 0x7C, 0x77, 0x7B, 0xF2, 0x6B, 0x6F, 0xC5, 0x30, 0x01, 0x67, 0x2B, 0xFE, 0xD7, 0xAB, 0x76,
		0xCA, 0x82, 0xC9, 0x7D, 0xFA, 0x59, 0x47, 0xF0, 0xAD, 0xD4, 0xA2, 0xAF, 0x9C, 0xA4, 0x72, 0xC0,
		0xB7, 0xFD, 0x93, 0x26, 0x36, 0x3F, 0xF7, 0xCC, 0x34, 0xA5, 0xE5, 0xF1, 0x71, 0xD8, 0x31, 0x15,
		0x04, 0xC7, 0x23, 0xC3, 0x18, 0x96, 0x05, 0x9A, 0x07, 0x12, 0x80, 0xE2, 0xEB, 0x27, 0xB2, 0x75,
		0x09, 0x83, 0x2C, 0x1A, 0x1B, 0x6E, 0x5A, 0xA0, 0x52, 0x3B, 0xD6, 0xB3, 0x29, 0xE3, 0x2F, 0x84,
		0x53, 0xD1, 0x00, 0xED, 0x20, 0xFC, 0xB1, 0x5B, 0x6A, 0xCB, 0xBE, 0x39, 0x4A, 0x4C, 0x58, 0xCF,
		0xD0, 0xEF, 0xAA, 0xFB, 0x43, 0x4D, 0x33, 0x85, 0x45, 0xF9, 0x02, 0x7F, 0x50, 0x3C, 0x9F, 0xA8,
		0x51, 0xA3, 0x40, 0x8F, 0x92, 0x9D, 0x38, 0xF5, 0xBC, 0xB6, 0xDA, 0x21, 0x10, 0xFF, 0xF3, 0xD2,
		0xCD, 0x0C, 0x13, 0xEC, 0x5F, 0x97, 0x44, 0x17, 0xC4, 0xA7, 0x7E, 0x3D, 0x64, 0x5D, 0x19, 0x73,
		0x60, 0x81, 0x4F, 0xDC, 0x22, 0x2A, 0x90, 0x88, 0x46, 0xEE, 0xB8, 0x14, 0xDE, 0x5E, 0x0B, 0xDB,
		0xE0, 0x32, 0x3A, 0x0A, 0x49, 0x06, 0x24, 0x5C, 0xC2, 0xD3, 0xAC, 0x62, 0x91, 0x95, 0xE4, 0x79,
		0xE7, 0xC8, 0x37, 0x6D, 0x8D, 0xD5, 0x4E, 0xA9, 0x6C, 0x56, 0xF4, 0xEA, 0x65, 0x7A, 0xAE, 0x08,
		0xBA, 0x78, 0x25, 0x2E, 0x1C, 0xA6, 0xB4, 0xC6, 0xE8, 0xDD, 0x74, 0x1F, 0x4B, 0xBD, 0x8B, 0x8A,
		0x70, 0x3E, 0xB5, 0x66, 0x48, 0x03, 0xF6, 0x0E, 0x61, 0x35, 0x57, 0xB9, 0x86, 0xC1, 0x1D, 0x9E,
		0xE1, 0xF8, 0x98, 0x11, 0x69, 0xD9, 0x8E, 0x94, 0x9B, 0x1E, 0x87, 0xE9, 0xCE, 0x55, 0x28, 0xDF,
		0x8C, 0xA1, 0x89, 0x0D, 0xBF, 0xE6, 0x42, 0x68, 0x41, 0x99, 0x2D, 0x0F, 0xB0, 0x54, 0xBB, 0x16
	);
	protected static $rjinvs = array(
		0x52, 0x09, 0x6A, 0xD5, 0x30, 0x36, 0xA5, 0x38, 0xBF, 0x40, 0xA3, 0x9E, 0x81, 0xF3, 0xD7, 0xFB,
		0x7C, 0xE3, 0x39, 0x82, 0x9B, 0x2F, 0xFF, 0x87, 0x34, 0x8E, 0x43, 0x44, 0xC4, 0xDE, 0xE9, 0xCB,
		0x54, 0x7B, 0x94, 0x32, 0xA6, 0xC2, 0x23, 0x3D, 0xEE, 0x4C, 0x95, 0x0B, 0x42, 0xFA, 0xC3, 0x4E,
		0x08, 0x2E, 0xA1, 0x66, 0x28, 0xD9, 0x24, 0xB2, 0x76, 0x5B, 0xA2, 0x49, 0x6D, 0x8B, 0xD1, 0x25,
		0x72, 0xF8, 0xF6, 0x64, 0x86, 0x68, 0x98, 0x16, 0xD4, 0xA4, 0x5C, 0xCC, 0x5D, 0x65, 0xB6, 0x92,
		0x6C, 0x70, 0x48, 0x50, 0xFD, 0xED, 0xB9, 0xDA, 0x5E, 0x15, 0x46, 0x57, 0xA7, 0x8D, 0x9D, 0x84,
		0x90, 0xD8, 0xAB, 0x00, 0x8C, 0xBC, 0xD3, 0x0A, 0xF7, 0xE4, 0x58, 0x05, 0xB8, 0xB3, 0x45, 0x06,
		0xD0, 0x2C, 0x1E, 0x8F, 0xCA, 0x3F, 0x0F, 0x02, 0xC1, 0xAF, 0xBD, 0x03, 0x01, 0x13, 0x8A, 0x6B,
		0x3A, 0x91, 0x11, 0x41, 0x4F, 0x67, 0xDC, 0xEA, 0x97, 0xF2, 0xCF, 0xCE, 0xF0, 0xB4, 0xE6, 0x73,
		0x96, 0xAC, 0x74, 0x22, 0xE7, 0xAD, 0x35, 0x85, 0xE2, 0xF9, 0x37, 0xE8, 0x1C, 0x75, 0xDF, 0x6E,
		0x47, 0xF1, 0x1A, 0x71, 0x1D, 0x29, 0xC5, 0x89, 0x6F, 0xB7, 0x62, 0x0E, 0xAA, 0x18, 0xBE, 0x1B,
		0xFC, 0x56, 0x3E, 0x4B, 0xC6, 0xD2, 0x79, 0x20, 0x9A, 0xDB, 0xC0, 0xFE, 0x78, 0xCD, 0x5A, 0xF4,
		0x1F, 0xDD, 0xA8, 0x33, 0x88, 0x07, 0xC7, 0x31, 0xB1, 0x12, 0x10, 0x59, 0x27, 0x80, 0xEC, 0x5F,
		0x60, 0x51, 0x7F, 0xA9, 0x19, 0xB5, 0x4A, 0x0D, 0x2D, 0xE5, 0x7A, 0x9F, 0x93, 0xC9, 0x9C, 0xEF,
		0xA0, 0xE0, 0x3B, 0x4D, 0xAE, 0x2A, 0xF5, 0xB0, 0xC8, 0xEB, 0xBB, 0x3C, 0x83, 0x53, 0x99, 0x61,
		0x17, 0x2B, 0x04, 0x7E, 0xBA, 0x77, 0xD6, 0x26, 0xE1, 0x69, 0x14, 0x63, 0x55, 0x21, 0x0C, 0x7D
	);
	protected static $rjrcon = array(0,
		0x01000000, 0x02000000, 0x04000000, 0x08000000, 0x10000000,
		0x20000000, 0x40000000, 0x80000000, 0x1B000000, 0x36000000,
		0x6C000000, 0xD8000000, 0xAB000000, 0x4D000000, 0x9A000000,
		0x2F000000, 0x5E000000, 0xBC000000, 0x63000000, 0xC6000000,
		0x97000000, 0x35000000, 0x6A000000, 0xD4000000, 0xB3000000,
		0x7D000000, 0xFA000000, 0xEF000000, 0xC5000000, 0x91000000
	);
	protected static $rc2t = array(
		0xD9, 0x78, 0xF9, 0xC4, 0x19, 0xDD, 0xB5, 0xED, 0x28, 0xE9, 0xFD, 0x79, 0x4A, 0xA0, 0xD8, 0x9D,
		0xC6, 0x7E, 0x37, 0x83, 0x2B, 0x76, 0x53, 0x8E, 0x62, 0x4C, 0x64, 0x88, 0x44, 0x8B, 0xFB, 0xA2,
		0x17, 0x9A, 0x59, 0xF5, 0x87, 0xB3, 0x4F, 0x13, 0x61, 0x45, 0x6D, 0x8D, 0x09, 0x81, 0x7D, 0x32,
		0xBD, 0x8F, 0x40, 0xEB, 0x86, 0xB7, 0x7B, 0x0B, 0xF0, 0x95, 0x21, 0x22, 0x5C, 0x6B, 0x4E, 0x82,
		0x54, 0xD6, 0x65, 0x93, 0xCE, 0x60, 0xB2, 0x1C, 0x73, 0x56, 0xC0, 0x14, 0xA7, 0x8C, 0xF1, 0xDC,
		0x12, 0x75, 0xCA, 0x1F, 0x3B, 0xBE, 0xE4, 0xD1, 0x42, 0x3D, 0xD4, 0x30, 0xA3, 0x3C, 0xB6, 0x26,
		0x6F, 0xBF, 0x0E, 0xDA, 0x46, 0x69, 0x07, 0x57, 0x27, 0xF2, 0x1D, 0x9B, 0xBC, 0x94, 0x43, 0x03,
		0xF8, 0x11, 0xC7, 0xF6, 0x90, 0xEF, 0x3E, 0xE7, 0x06, 0xC3, 0xD5, 0x2F, 0xC8, 0x66, 0x1E, 0xD7,
		0x08, 0xE8, 0xEA, 0xDE, 0x80, 0x52, 0xEE, 0xF7, 0x84, 0xAA, 0x72, 0xAC, 0x35, 0x4D, 0x6A, 0x2A,
		0x96, 0x1A, 0xD2, 0x71, 0x5A, 0x15, 0x49, 0x74, 0x4B, 0x9F, 0xD0, 0x5E, 0x04, 0x18, 0xA4, 0xEC,
		0xC2, 0xE0, 0x41, 0x6E, 0x0F, 0x51, 0xCB, 0xCC, 0x24, 0x91, 0xAF, 0x50, 0xA1, 0xF4, 0x70, 0x39,
		0x99, 0x7C, 0x3A, 0x85, 0x23, 0xB8, 0xB4, 0x7A, 0xFC, 0x02, 0x36, 0x5B, 0x25, 0x55, 0x97, 0x31,
		0x2D, 0x5D, 0xFA, 0x98, 0xE3, 0x8A, 0x92, 0xAE, 0x05, 0xDF, 0x29, 0x10, 0x67, 0x6C, 0xBA, 0xC9,
		0xD3, 0x00, 0xE6, 0xCF, 0xE1, 0x9E, 0xA8, 0x2C, 0x63, 0x16, 0x01, 0x3F, 0x58, 0xE2, 0x89, 0xA9,
		0x0D, 0x38, 0x34, 0x1B, 0xAB, 0x33, 0xFF, 0xB0, 0xBB, 0x48, 0x0C, 0x5F, 0xB9, 0xB1, 0xCD, 0x2E,
		0xC5, 0xF3, 0xDB, 0x47, 0xE5, 0xA5, 0x9C, 0x77, 0x0A, 0xA6, 0x20, 0x68, 0xFE, 0x7F, 0xC1, 0xAD,
		0xD9, 0x78, 0xF9, 0xC4, 0x19, 0xDD, 0xB5, 0xED, 0x28, 0xE9, 0xFD, 0x79, 0x4A, 0xA0, 0xD8, 0x9D,
		0xC6, 0x7E, 0x37, 0x83, 0x2B, 0x76, 0x53, 0x8E, 0x62, 0x4C, 0x64, 0x88, 0x44, 0x8B, 0xFB, 0xA2,
		0x17, 0x9A, 0x59, 0xF5, 0x87, 0xB3, 0x4F, 0x13, 0x61, 0x45, 0x6D, 0x8D, 0x09, 0x81, 0x7D, 0x32,
		0xBD, 0x8F, 0x40, 0xEB, 0x86, 0xB7, 0x7B, 0x0B, 0xF0, 0x95, 0x21, 0x22, 0x5C, 0x6B, 0x4E, 0x82,
		0x54, 0xD6, 0x65, 0x93, 0xCE, 0x60, 0xB2, 0x1C, 0x73, 0x56, 0xC0, 0x14, 0xA7, 0x8C, 0xF1, 0xDC,
		0x12, 0x75, 0xCA, 0x1F, 0x3B, 0xBE, 0xE4, 0xD1, 0x42, 0x3D, 0xD4, 0x30, 0xA3, 0x3C, 0xB6, 0x26,
		0x6F, 0xBF, 0x0E, 0xDA, 0x46, 0x69, 0x07, 0x57, 0x27, 0xF2, 0x1D, 0x9B, 0xBC, 0x94, 0x43, 0x03,
		0xF8, 0x11, 0xC7, 0xF6, 0x90, 0xEF, 0x3E, 0xE7, 0x06, 0xC3, 0xD5, 0x2F, 0xC8, 0x66, 0x1E, 0xD7,
		0x08, 0xE8, 0xEA, 0xDE, 0x80, 0x52, 0xEE, 0xF7, 0x84, 0xAA, 0x72, 0xAC, 0x35, 0x4D, 0x6A, 0x2A,
		0x96, 0x1A, 0xD2, 0x71, 0x5A, 0x15, 0x49, 0x74, 0x4B, 0x9F, 0xD0, 0x5E, 0x04, 0x18, 0xA4, 0xEC,
		0xC2, 0xE0, 0x41, 0x6E, 0x0F, 0x51, 0xCB, 0xCC, 0x24, 0x91, 0xAF, 0x50, 0xA1, 0xF4, 0x70, 0x39,
		0x99, 0x7C, 0x3A, 0x85, 0x23, 0xB8, 0xB4, 0x7A, 0xFC, 0x02, 0x36, 0x5B, 0x25, 0x55, 0x97, 0x31,
		0x2D, 0x5D, 0xFA, 0x98, 0xE3, 0x8A, 0x92, 0xAE, 0x05, 0xDF, 0x29, 0x10, 0x67, 0x6C, 0xBA, 0xC9,
		0xD3, 0x00, 0xE6, 0xCF, 0xE1, 0x9E, 0xA8, 0x2C, 0x63, 0x16, 0x01, 0x3F, 0x58, 0xE2, 0x89, 0xA9,
		0x0D, 0x38, 0x34, 0x1B, 0xAB, 0x33, 0xFF, 0xB0, 0xBB, 0x48, 0x0C, 0x5F, 0xB9, 0xB1, 0xCD, 0x2E,
		0xC5, 0xF3, 0xDB, 0x47, 0xE5, 0xA5, 0x9C, 0x77, 0x0A, 0xA6, 0x20, 0x68, 0xFE, 0x7F, 0xC1, 0xAD
	);
	protected static $rc2invt = array(
		0xD1, 0xDA, 0xB9, 0x6F, 0x9C, 0xC8, 0x78, 0x66, 0x80, 0x2C, 0xF8, 0x37, 0xEA, 0xE0, 0x62, 0xA4,
		0xCB, 0x71, 0x50, 0x27, 0x4B, 0x95, 0xD9, 0x20, 0x9D, 0x04, 0x91, 0xE3, 0x47, 0x6A, 0x7E, 0x53,
		0xFA, 0x3A, 0x3B, 0xB4, 0xA8, 0xBC, 0x5F, 0x68, 0x08, 0xCA, 0x8F, 0x14, 0xD7, 0xC0, 0xEF, 0x7B,
		0x5B, 0xBF, 0x2F, 0xE5, 0xE2, 0x8C, 0xBA, 0x12, 0xE1, 0xAF, 0xB2, 0x54, 0x5D, 0x59, 0x76, 0xDB,
		0x32, 0xA2, 0x58, 0x6E, 0x1C, 0x29, 0x64, 0xF3, 0xE9, 0x96, 0x0C, 0x98, 0x19, 0x8D, 0x3E, 0x26,
		0xAB, 0xA5, 0x85, 0x16, 0x40, 0xBD, 0x49, 0x67, 0xDC, 0x22, 0x94, 0xBB, 0x3C, 0xC1, 0x9B, 0xEB,
		0x45, 0x28, 0x18, 0xD8, 0x1A, 0x42, 0x7D, 0xCC, 0xFB, 0x65, 0x8E, 0x3D, 0xCD, 0x2A, 0xA3, 0x60,
		0xAE, 0x93, 0x8A, 0x48, 0x97, 0x51, 0x15, 0xF7, 0x01, 0x0B, 0xB7, 0x36, 0xB1, 0x2E, 0x11, 0xFD,
		0x84, 0x2D, 0x3F, 0x13, 0x88, 0xB3, 0x34, 0x24, 0x1B, 0xDE, 0xC5, 0x1D, 0x4D, 0x2B, 0x17, 0x31,
		0x74, 0xA9, 0xC6, 0x43, 0x6D, 0x39, 0x90, 0xBE, 0xC3, 0xB0, 0x21, 0x6B, 0xF6, 0x0F, 0xD5, 0x99,
		0x0D, 0xAC, 0x1F, 0x5C, 0x9E, 0xF5, 0xF9, 0x4C, 0xD6, 0xDF, 0x89, 0xE4, 0x8B, 0xFF, 0xC7, 0xAA,
		0xE7, 0xED, 0x46, 0x25, 0xB6, 0x06, 0x5E, 0x35, 0xB5, 0xEC, 0xCE, 0xE8, 0x6C, 0x30, 0x55, 0x61,
		0x4A, 0xFE, 0xA0, 0x79, 0x03, 0xF0, 0x10, 0x72, 0x7C, 0xCF, 0x52, 0xA6, 0xA7, 0xEE, 0x44, 0xD3,
		0x9A, 0x57, 0x92, 0xD0, 0x5A, 0x7A, 0x41, 0x7F, 0x0E, 0x00, 0x63, 0xF2, 0x4F, 0x05, 0x83, 0xC9,
		0xA1, 0xD4, 0xDD, 0xC4, 0x56, 0xF4, 0xD2, 0x77, 0x81, 0x09, 0x82, 0x33, 0x9F, 0x07, 0x86, 0x75,
		0x38, 0x4E, 0x69, 0xF1, 0xAD, 0x23, 0x73, 0x87, 0x70, 0x02, 0xC2, 0x1E, 0xB8, 0x0A, 0xFC, 0xE6
	);
	protected static $desm = array(
		0x00, 0x10, 0x01, 0x11, 0x20, 0x30, 0x21, 0x31, 0x02, 0x12, 0x03, 0x13, 0x22, 0x32, 0x23, 0x33,
		0x40, 0x50, 0x41, 0x51, 0x60, 0x70, 0x61, 0x71, 0x42, 0x52, 0x43, 0x53, 0x62, 0x72, 0x63, 0x73,
		0x04, 0x14, 0x05, 0x15, 0x24, 0x34, 0x25, 0x35, 0x06, 0x16, 0x07, 0x17, 0x26, 0x36, 0x27, 0x37,
		0x44, 0x54, 0x45, 0x55, 0x64, 0x74, 0x65, 0x75, 0x46, 0x56, 0x47, 0x57, 0x66, 0x76, 0x67, 0x77,
		0x80, 0x90, 0x81, 0x91, 0xA0, 0xB0, 0xA1, 0xB1, 0x82, 0x92, 0x83, 0x93, 0xA2, 0xB2, 0xA3, 0xB3,
		0xC0, 0xD0, 0xC1, 0xD1, 0xE0, 0xF0, 0xE1, 0xF1, 0xC2, 0xD2, 0xC3, 0xD3, 0xE2, 0xF2, 0xE3, 0xF3,
		0x84, 0x94, 0x85, 0x95, 0xA4, 0xB4, 0xA5, 0xB5, 0x86, 0x96, 0x87, 0x97, 0xA6, 0xB6, 0xA7, 0xB7,
		0xC4, 0xD4, 0xC5, 0xD5, 0xE4, 0xF4, 0xE5, 0xF5, 0xC6, 0xD6, 0xC7, 0xD7, 0xE6, 0xF6, 0xE7, 0xF7,
		0x08, 0x18, 0x09, 0x19, 0x28, 0x38, 0x29, 0x39, 0x0A, 0x1A, 0x0B, 0x1B, 0x2A, 0x3A, 0x2B, 0x3B,
		0x48, 0x58, 0x49, 0x59, 0x68, 0x78, 0x69, 0x79, 0x4A, 0x5A, 0x4B, 0x5B, 0x6A, 0x7A, 0x6B, 0x7B,
		0x0C, 0x1C, 0x0D, 0x1D, 0x2C, 0x3C, 0x2D, 0x3D, 0x0E, 0x1E, 0x0F, 0x1F, 0x2E, 0x3E, 0x2F, 0x3F,
		0x4C, 0x5C, 0x4D, 0x5D, 0x6C, 0x7C, 0x6D, 0x7D, 0x4E, 0x5E, 0x4F, 0x5F, 0x6E, 0x7E, 0x6F, 0x7F,
		0x88, 0x98, 0x89, 0x99, 0xA8, 0xB8, 0xA9, 0xB9, 0x8A, 0x9A, 0x8B, 0x9B, 0xAA, 0xBA, 0xAB, 0xBB,
		0xC8, 0xD8, 0xC9, 0xD9, 0xE8, 0xF8, 0xE9, 0xF9, 0xCA, 0xDA, 0xCB, 0xDB, 0xEA, 0xFA, 0xEB, 0xFB,
		0x8C, 0x9C, 0x8D, 0x9D, 0xAC, 0xBC, 0xAD, 0xBD, 0x8E, 0x9E, 0x8F, 0x9F, 0xAE, 0xBE, 0xAF, 0xBF,
		0xCC, 0xDC, 0xCD, 0xDD, 0xEC, 0xFC, 0xED, 0xFD, 0xCE, 0xDE, 0xCF, 0xDF, 0xEE, 0xFE, 0xEF, 0xFF
	);
	protected static $desinvm = array(
		0x00, 0x80, 0x40, 0xC0, 0x20, 0xA0, 0x60, 0xE0, 0x10, 0x90, 0x50, 0xD0, 0x30, 0xB0, 0x70, 0xF0,
		0x08, 0x88, 0x48, 0xC8, 0x28, 0xA8, 0x68, 0xE8, 0x18, 0x98, 0x58, 0xD8, 0x38, 0xB8, 0x78, 0xF8,
		0x04, 0x84, 0x44, 0xC4, 0x24, 0xA4, 0x64, 0xE4, 0x14, 0x94, 0x54, 0xD4, 0x34, 0xB4, 0x74, 0xF4,
		0x0C, 0x8C, 0x4C, 0xCC, 0x2C, 0xAC, 0x6C, 0xEC, 0x1C, 0x9C, 0x5C, 0xDC, 0x3C, 0xBC, 0x7C, 0xFC,
		0x02, 0x82, 0x42, 0xC2, 0x22, 0xA2, 0x62, 0xE2, 0x12, 0x92, 0x52, 0xD2, 0x32, 0xB2, 0x72, 0xF2,
		0x0A, 0x8A, 0x4A, 0xCA, 0x2A, 0xAA, 0x6A, 0xEA, 0x1A, 0x9A, 0x5A, 0xDA, 0x3A, 0xBA, 0x7A, 0xFA,
		0x06, 0x86, 0x46, 0xC6, 0x26, 0xA6, 0x66, 0xE6, 0x16, 0x96, 0x56, 0xD6, 0x36, 0xB6, 0x76, 0xF6,
		0x0E, 0x8E, 0x4E, 0xCE, 0x2E, 0xAE, 0x6E, 0xEE, 0x1E, 0x9E, 0x5E, 0xDE, 0x3E, 0xBE, 0x7E, 0xFE,
		0x01, 0x81, 0x41, 0xC1, 0x21, 0xA1, 0x61, 0xE1, 0x11, 0x91, 0x51, 0xD1, 0x31, 0xB1, 0x71, 0xF1,
		0x09, 0x89, 0x49, 0xC9, 0x29, 0xA9, 0x69, 0xE9, 0x19, 0x99, 0x59, 0xD9, 0x39, 0xB9, 0x79, 0xF9,
		0x05, 0x85, 0x45, 0xC5, 0x25, 0xA5, 0x65, 0xE5, 0x15, 0x95, 0x55, 0xD5, 0x35, 0xB5, 0x75, 0xF5,
		0x0D, 0x8D, 0x4D, 0xCD, 0x2D, 0xAD, 0x6D, 0xED, 0x1D, 0x9D, 0x5D, 0xDD, 0x3D, 0xBD, 0x7D, 0xFD,
		0x03, 0x83, 0x43, 0xC3, 0x23, 0xA3, 0x63, 0xE3, 0x13, 0x93, 0x53, 0xD3, 0x33, 0xB3, 0x73, 0xF3,
		0x0B, 0x8B, 0x4B, 0xCB, 0x2B, 0xAB, 0x6B, 0xEB, 0x1B, 0x9B, 0x5B, 0xDB, 0x3B, 0xBB, 0x7B, 0xFB,
		0x07, 0x87, 0x47, 0xC7, 0x27, 0xA7, 0x67, 0xE7, 0x17, 0x97, 0x57, 0xD7, 0x37, 0xB7, 0x77, 0xF7,
		0x0F, 0x8F, 0x4F, 0xCF, 0x2F, 0xAF, 0x6F, 0xEF, 0x1F, 0x9F, 0x5F, 0xDF, 0x3F, 0xBF, 0x7F, 0xFF
	);
	protected static $dess = array(
		array(
			0x00808200, 0x00000000, 0x00008000, 0x00808202, 0x00808002, 0x00008202, 0x00000002, 0x00008000,
			0x00000200, 0x00808200, 0x00808202, 0x00000200, 0x00800202, 0x00808002, 0x00800000, 0x00000002,
			0x00000202, 0x00800200, 0x00800200, 0x00008200, 0x00008200, 0x00808000, 0x00808000, 0x00800202,
			0x00008002, 0x00800002, 0x00800002, 0x00008002, 0x00000000, 0x00000202, 0x00008202, 0x00800000,
			0x00008000, 0x00808202, 0x00000002, 0x00808000, 0x00808200, 0x00800000, 0x00800000, 0x00000200,
			0x00808002, 0x00008000, 0x00008200, 0x00800002, 0x00000200, 0x00000002, 0x00800202, 0x00008202,
			0x00808202, 0x00008002, 0x00808000, 0x00800202, 0x00800002, 0x00000202, 0x00008202, 0x00808200,
			0x00000202, 0x00800200, 0x00800200, 0x00000000, 0x00008002, 0x00008200, 0x00000000, 0x00808002
		), array(
			0x40084010, 0x40004000, 0x00004000, 0x00084010, 0x00080000, 0x00000010, 0x40080010, 0x40004010,
			0x40000010, 0x40084010, 0x40084000, 0x40000000, 0x40004000, 0x00080000, 0x00000010, 0x40080010,
			0x00084000, 0x00080010, 0x40004010, 0x00000000, 0x40000000, 0x00004000, 0x00084010, 0x40080000,
			0x00080010, 0x40000010, 0x00000000, 0x00084000, 0x00004010, 0x40084000, 0x40080000, 0x00004010,
			0x00000000, 0x00084010, 0x40080010, 0x00080000, 0x40004010, 0x40080000, 0x40084000, 0x00004000,
			0x40080000, 0x40004000, 0x00000010, 0x40084010, 0x00084010, 0x00000010, 0x00004000, 0x40000000,
			0x00004010, 0x40084000, 0x00080000, 0x40000010, 0x00080010, 0x40004010, 0x40000010, 0x00080010,
			0x00084000, 0x00000000, 0x40004000, 0x00004010, 0x40000000, 0x40080010, 0x40084010, 0x00084000
		), array(
			0x00000104, 0x04010100, 0x00000000, 0x04010004, 0x04000100, 0x00000000, 0x00010104, 0x04000100,
			0x00010004, 0x04000004, 0x04000004, 0x00010000, 0x04010104, 0x00010004, 0x04010000, 0x00000104,
			0x04000000, 0x00000004, 0x04010100, 0x00000100, 0x00010100, 0x04010000, 0x04010004, 0x00010104,
			0x04000104, 0x00010100, 0x00010000, 0x04000104, 0x00000004, 0x04010104, 0x00000100, 0x04000000,
			0x04010100, 0x04000000, 0x00010004, 0x00000104, 0x00010000, 0x04010100, 0x04000100, 0x00000000,
			0x00000100, 0x00010004, 0x04010104, 0x04000100, 0x04000004, 0x00000100, 0x00000000, 0x04010004,
			0x04000104, 0x00010000, 0x04000000, 0x04010104, 0x00000004, 0x00010104, 0x00010100, 0x04000004,
			0x04010000, 0x04000104, 0x00000104, 0x04010000, 0x00010104, 0x00000004, 0x04010004, 0x00010100
		), array(
			0x80401000, 0x80001040, 0x80001040, 0x00000040, 0x00401040, 0x80400040, 0x80400000, 0x80001000,
			0x00000000, 0x00401000, 0x00401000, 0x80401040, 0x80000040, 0x00000000, 0x00400040, 0x80400000,
			0x80000000, 0x00001000, 0x00400000, 0x80401000, 0x00000040, 0x00400000, 0x80001000, 0x00001040,
			0x80400040, 0x80000000, 0x00001040, 0x00400040, 0x00001000, 0x00401040, 0x80401040, 0x80000040,
			0x00400040, 0x80400000, 0x00401000, 0x80401040, 0x80000040, 0x00000000, 0x00000000, 0x00401000,
			0x00001040, 0x00400040, 0x80400040, 0x80000000, 0x80401000, 0x80001040, 0x80001040, 0x00000040,
			0x80401040, 0x80000040, 0x80000000, 0x00001000, 0x80400000, 0x80001000, 0x00401040, 0x80400040,
			0x80001000, 0x00001040, 0x00400000, 0x80401000, 0x00000040, 0x00400000, 0x00001000, 0x00401040
		), array(
			0x00000080, 0x01040080, 0x01040000, 0x21000080, 0x00040000, 0x00000080, 0x20000000, 0x01040000,
			0x20040080, 0x00040000, 0x01000080, 0x20040080, 0x21000080, 0x21040000, 0x00040080, 0x20000000,
			0x01000000, 0x20040000, 0x20040000, 0x00000000, 0x20000080, 0x21040080, 0x21040080, 0x01000080,
			0x21040000, 0x20000080, 0x00000000, 0x21000000, 0x01040080, 0x01000000, 0x21000000, 0x00040080,
			0x00040000, 0x21000080, 0x00000080, 0x01000000, 0x20000000, 0x01040000, 0x21000080, 0x20040080,
			0x01000080, 0x20000000, 0x21040000, 0x01040080, 0x20040080, 0x00000080, 0x01000000, 0x21040000,
			0x21040080, 0x00040080, 0x21000000, 0x21040080, 0x01040000, 0x00000000, 0x20040000, 0x21000000,
			0x00040080, 0x01000080, 0x20000080, 0x00040000, 0x00000000, 0x20040000, 0x01040080, 0x20000080
		), array(
			0x10000008, 0x10200000, 0x00002000, 0x10202008, 0x10200000, 0x00000008, 0x10202008, 0x00200000,
			0x10002000, 0x00202008, 0x00200000, 0x10000008, 0x00200008, 0x10002000, 0x10000000, 0x00002008,
			0x00000000, 0x00200008, 0x10002008, 0x00002000, 0x00202000, 0x10002008, 0x00000008, 0x10200008,
			0x10200008, 0x00000000, 0x00202008, 0x10202000, 0x00002008, 0x00202000, 0x10202000, 0x10000000,
			0x10002000, 0x00000008, 0x10200008, 0x00202000, 0x10202008, 0x00200000, 0x00002008, 0x10000008,
			0x00200000, 0x10002000, 0x10000000, 0x00002008, 0x10000008, 0x10202008, 0x00202000, 0x10200000,
			0x00202008, 0x10202000, 0x00000000, 0x10200008, 0x00000008, 0x00002000, 0x10200000, 0x00202008,
			0x00002000, 0x00200008, 0x10002008, 0x00000000, 0x10202000, 0x10000000, 0x00200008, 0x10002008
		), array(
			0x00100000, 0x02100001, 0x02000401, 0x00000000, 0x00000400, 0x02000401, 0x00100401, 0x02100400,
			0x02100401, 0x00100000, 0x00000000, 0x02000001, 0x00000001, 0x02000000, 0x02100001, 0x00000401,
			0x02000400, 0x00100401, 0x00100001, 0x02000400, 0x02000001, 0x02100000, 0x02100400, 0x00100001,
			0x02100000, 0x00000400, 0x00000401, 0x02100401, 0x00100400, 0x00000001, 0x02000000, 0x00100400,
			0x02000000, 0x00100400, 0x00100000, 0x02000401, 0x02000401, 0x02100001, 0x02100001, 0x00000001,
			0x00100001, 0x02000000, 0x02000400, 0x00100000, 0x02100400, 0x00000401, 0x00100401, 0x02100400,
			0x00000401, 0x02000001, 0x02100401, 0x02100000, 0x00100400, 0x00000000, 0x00000001, 0x02100401,
			0x00000000, 0x00100401, 0x02100000, 0x00000400, 0x02000001, 0x02000400, 0x00000400, 0x00100001
		), array(
			0x08000820, 0x00000800, 0x00020000, 0x08020820, 0x08000000, 0x08000820, 0x00000020, 0x08000000,
			0x00020020, 0x08020000, 0x08020820, 0x00020800, 0x08020800, 0x00020820, 0x00000800, 0x00000020,
			0x08020000, 0x08000020, 0x08000800, 0x00000820, 0x00020800, 0x00020020, 0x08020020, 0x08020800,
			0x00000820, 0x00000000, 0x00000000, 0x08020020, 0x08000020, 0x08000800, 0x00020820, 0x00020000,
			0x00020820, 0x00020000, 0x08020800, 0x00000800, 0x00000020, 0x08020020, 0x00000800, 0x00020820,
			0x08000800, 0x00000020, 0x08000020, 0x08020000, 0x08020020, 0x08000000, 0x00020000, 0x08000820,
			0x00000000, 0x08020820, 0x00020020, 0x08000020, 0x08020000, 0x08000800, 0x08000820, 0x00000000,
			0x08020820, 0x00020800, 0x00020800, 0x00000820, 0x00000820, 0x00020020, 0x08000000, 0x08020800
		)
	);
	protected static $desshs = array(
		1, 1, 2, 2, 2, 2, 2, 2, 1, 2, 2, 2, 2, 2, 2, 1
	);
	protected static $despc1 = array(
		0x00, 0x00, 0x08, 0x08, 0x04, 0x04, 0x0C, 0x0C, 0x02, 0x02, 0x0A, 0x0A, 0x06, 0x06, 0x0E, 0x0E,
		0x10, 0x10, 0x18, 0x18, 0x14, 0x14, 0x1C, 0x1C, 0x12, 0x12, 0x1A, 0x1A, 0x16, 0x16, 0x1E, 0x1E,
		0x20, 0x20, 0x28, 0x28, 0x24, 0x24, 0x2C, 0x2C, 0x22, 0x22, 0x2A, 0x2A, 0x26, 0x26, 0x2E, 0x2E,
		0x30, 0x30, 0x38, 0x38, 0x34, 0x34, 0x3C, 0x3C, 0x32, 0x32, 0x3A, 0x3A, 0x36, 0x36, 0x3E, 0x3E,
		0x40, 0x40, 0x48, 0x48, 0x44, 0x44, 0x4C, 0x4C, 0x42, 0x42, 0x4A, 0x4A, 0x46, 0x46, 0x4E, 0x4E,
		0x50, 0x50, 0x58, 0x58, 0x54, 0x54, 0x5C, 0x5C, 0x52, 0x52, 0x5A, 0x5A, 0x56, 0x56, 0x5E, 0x5E,
		0x60, 0x60, 0x68, 0x68, 0x64, 0x64, 0x6C, 0x6C, 0x62, 0x62, 0x6A, 0x6A, 0x66, 0x66, 0x6E, 0x6E,
		0x70, 0x70, 0x78, 0x78, 0x74, 0x74, 0x7C, 0x7C, 0x72, 0x72, 0x7A, 0x7A, 0x76, 0x76, 0x7E, 0x7E,
		0x80, 0x80, 0x88, 0x88, 0x84, 0x84, 0x8C, 0x8C, 0x82, 0x82, 0x8A, 0x8A, 0x86, 0x86, 0x8E, 0x8E,
		0x90, 0x90, 0x98, 0x98, 0x94, 0x94, 0x9C, 0x9C, 0x92, 0x92, 0x9A, 0x9A, 0x96, 0x96, 0x9E, 0x9E,
		0xA0, 0xA0, 0xA8, 0xA8, 0xA4, 0xA4, 0xAC, 0xAC, 0xA2, 0xA2, 0xAA, 0xAA, 0xA6, 0xA6, 0xAE, 0xAE,
		0xB0, 0xB0, 0xB8, 0xB8, 0xB4, 0xB4, 0xBC, 0xBC, 0xB2, 0xB2, 0xBA, 0xBA, 0xB6, 0xB6, 0xBE, 0xBE,
		0xC0, 0xC0, 0xC8, 0xC8, 0xC4, 0xC4, 0xCC, 0xCC, 0xC2, 0xC2, 0xCA, 0xCA, 0xC6, 0xC6, 0xCE, 0xCE,
		0xD0, 0xD0, 0xD8, 0xD8, 0xD4, 0xD4, 0xDC, 0xDC, 0xD2, 0xD2, 0xDA, 0xDA, 0xD6, 0xD6, 0xDE, 0xDE,
		0xE0, 0xE0, 0xE8, 0xE8, 0xE4, 0xE4, 0xEC, 0xEC, 0xE2, 0xE2, 0xEA, 0xEA, 0xE6, 0xE6, 0xEE, 0xEE,
		0xF0, 0xF0, 0xF8, 0xF8, 0xF4, 0xF4, 0xFC, 0xFC, 0xF2, 0xF2, 0xFA, 0xFA, 0xF6, 0xF6, 0xFE, 0xFE
	);
	protected static $despc2c = array(
		array(
			0x00000000, 0x00000400, 0x00200000, 0x00200400, 0x00000001, 0x00000401, 0x00200001, 0x00200401,
			0x02000000, 0x02000400, 0x02200000, 0x02200400, 0x02000001, 0x02000401, 0x02200001, 0x02200401
		),array(
			0x00000000, 0x00000800, 0x08000000, 0x08000800, 0x00010000, 0x00010800, 0x08010000, 0x08010800,
			0x00000000, 0x00000800, 0x08000000, 0x08000800, 0x00010000, 0x00010800, 0x08010000, 0x08010800,
			0x00000100, 0x00000900, 0x08000100, 0x08000900, 0x00010100, 0x00010900, 0x08010100, 0x08010900,
			0x00000100, 0x00000900, 0x08000100, 0x08000900, 0x00010100, 0x00010900, 0x08010100, 0x08010900,
			0x00000010, 0x00000810, 0x08000010, 0x08000810, 0x00010010, 0x00010810, 0x08010010, 0x08010810,
			0x00000010, 0x00000810, 0x08000010, 0x08000810, 0x00010010, 0x00010810, 0x08010010, 0x08010810,
			0x00000110, 0x00000910, 0x08000110, 0x08000910, 0x00010110, 0x00010910, 0x08010110, 0x08010910,
			0x00000110, 0x00000910, 0x08000110, 0x08000910, 0x00010110, 0x00010910, 0x08010110, 0x08010910,
			0x00040000, 0x00040800, 0x08040000, 0x08040800, 0x00050000, 0x00050800, 0x08050000, 0x08050800,
			0x00040000, 0x00040800, 0x08040000, 0x08040800, 0x00050000, 0x00050800, 0x08050000, 0x08050800,
			0x00040100, 0x00040900, 0x08040100, 0x08040900, 0x00050100, 0x00050900, 0x08050100, 0x08050900,
			0x00040100, 0x00040900, 0x08040100, 0x08040900, 0x00050100, 0x00050900, 0x08050100, 0x08050900,
			0x00040010, 0x00040810, 0x08040010, 0x08040810, 0x00050010, 0x00050810, 0x08050010, 0x08050810,
			0x00040010, 0x00040810, 0x08040010, 0x08040810, 0x00050010, 0x00050810, 0x08050010, 0x08050810,
			0x00040110, 0x00040910, 0x08040110, 0x08040910, 0x00050110, 0x00050910, 0x08050110, 0x08050910,
			0x00040110, 0x00040910, 0x08040110, 0x08040910, 0x00050110, 0x00050910, 0x08050110, 0x08050910,
			0x01000000, 0x01000800, 0x09000000, 0x09000800, 0x01010000, 0x01010800, 0x09010000, 0x09010800,
			0x01000000, 0x01000800, 0x09000000, 0x09000800, 0x01010000, 0x01010800, 0x09010000, 0x09010800,
			0x01000100, 0x01000900, 0x09000100, 0x09000900, 0x01010100, 0x01010900, 0x09010100, 0x09010900,
			0x01000100, 0x01000900, 0x09000100, 0x09000900, 0x01010100, 0x01010900, 0x09010100, 0x09010900,
			0x01000010, 0x01000810, 0x09000010, 0x09000810, 0x01010010, 0x01010810, 0x09010010, 0x09010810,
			0x01000010, 0x01000810, 0x09000010, 0x09000810, 0x01010010, 0x01010810, 0x09010010, 0x09010810,
			0x01000110, 0x01000910, 0x09000110, 0x09000910, 0x01010110, 0x01010910, 0x09010110, 0x09010910,
			0x01000110, 0x01000910, 0x09000110, 0x09000910, 0x01010110, 0x01010910, 0x09010110, 0x09010910,
			0x01040000, 0x01040800, 0x09040000, 0x09040800, 0x01050000, 0x01050800, 0x09050000, 0x09050800,
			0x01040000, 0x01040800, 0x09040000, 0x09040800, 0x01050000, 0x01050800, 0x09050000, 0x09050800,
			0x01040100, 0x01040900, 0x09040100, 0x09040900, 0x01050100, 0x01050900, 0x09050100, 0x09050900,
			0x01040100, 0x01040900, 0x09040100, 0x09040900, 0x01050100, 0x01050900, 0x09050100, 0x09050900,
			0x01040010, 0x01040810, 0x09040010, 0x09040810, 0x01050010, 0x01050810, 0x09050010, 0x09050810,
			0x01040010, 0x01040810, 0x09040010, 0x09040810, 0x01050010, 0x01050810, 0x09050010, 0x09050810,
			0x01040110, 0x01040910, 0x09040110, 0x09040910, 0x01050110, 0x01050910, 0x09050110, 0x09050910,
			0x01040110, 0x01040910, 0x09040110, 0x09040910, 0x01050110, 0x01050910, 0x09050110, 0x09050910
		),array(
			0x00000000, 0x00000004, 0x00001000, 0x00001004, 0x00000000, 0x00000004, 0x00001000, 0x00001004,
			0x10000000, 0x10000004, 0x10001000, 0x10001004, 0x10000000, 0x10000004, 0x10001000, 0x10001004,
			0x00000020, 0x00000024, 0x00001020, 0x00001024, 0x00000020, 0x00000024, 0x00001020, 0x00001024,
			0x10000020, 0x10000024, 0x10001020, 0x10001024, 0x10000020, 0x10000024, 0x10001020, 0x10001024,
			0x00080000, 0x00080004, 0x00081000, 0x00081004, 0x00080000, 0x00080004, 0x00081000, 0x00081004,
			0x10080000, 0x10080004, 0x10081000, 0x10081004, 0x10080000, 0x10080004, 0x10081000, 0x10081004,
			0x00080020, 0x00080024, 0x00081020, 0x00081024, 0x00080020, 0x00080024, 0x00081020, 0x00081024,
			0x10080020, 0x10080024, 0x10081020, 0x10081024, 0x10080020, 0x10080024, 0x10081020, 0x10081024,
			0x20000000, 0x20000004, 0x20001000, 0x20001004, 0x20000000, 0x20000004, 0x20001000, 0x20001004,
			0x30000000, 0x30000004, 0x30001000, 0x30001004, 0x30000000, 0x30000004, 0x30001000, 0x30001004,
			0x20000020, 0x20000024, 0x20001020, 0x20001024, 0x20000020, 0x20000024, 0x20001020, 0x20001024,
			0x30000020, 0x30000024, 0x30001020, 0x30001024, 0x30000020, 0x30000024, 0x30001020, 0x30001024,
			0x20080000, 0x20080004, 0x20081000, 0x20081004, 0x20080000, 0x20080004, 0x20081000, 0x20081004,
			0x30080000, 0x30080004, 0x30081000, 0x30081004, 0x30080000, 0x30080004, 0x30081000, 0x30081004,
			0x20080020, 0x20080024, 0x20081020, 0x20081024, 0x20080020, 0x20080024, 0x20081020, 0x20081024,
			0x30080020, 0x30080024, 0x30081020, 0x30081024, 0x30080020, 0x30080024, 0x30081020, 0x30081024,
			0x00000002, 0x00000006, 0x00001002, 0x00001006, 0x00000002, 0x00000006, 0x00001002, 0x00001006,
			0x10000002, 0x10000006, 0x10001002, 0x10001006, 0x10000002, 0x10000006, 0x10001002, 0x10001006,
			0x00000022, 0x00000026, 0x00001022, 0x00001026, 0x00000022, 0x00000026, 0x00001022, 0x00001026,
			0x10000022, 0x10000026, 0x10001022, 0x10001026, 0x10000022, 0x10000026, 0x10001022, 0x10001026,
			0x00080002, 0x00080006, 0x00081002, 0x00081006, 0x00080002, 0x00080006, 0x00081002, 0x00081006,
			0x10080002, 0x10080006, 0x10081002, 0x10081006, 0x10080002, 0x10080006, 0x10081002, 0x10081006,
			0x00080022, 0x00080026, 0x00081022, 0x00081026, 0x00080022, 0x00080026, 0x00081022, 0x00081026,
			0x10080022, 0x10080026, 0x10081022, 0x10081026, 0x10080022, 0x10080026, 0x10081022, 0x10081026,
			0x20000002, 0x20000006, 0x20001002, 0x20001006, 0x20000002, 0x20000006, 0x20001002, 0x20001006,
			0x30000002, 0x30000006, 0x30001002, 0x30001006,	0x30000002, 0x30000006, 0x30001002, 0x30001006,
			0x20000022, 0x20000026, 0x20001022, 0x20001026, 0x20000022, 0x20000026, 0x20001022, 0x20001026,
			0x30000022, 0x30000026, 0x30001022, 0x30001026, 0x30000022, 0x30000026, 0x30001022, 0x30001026,
			0x20080002, 0x20080006, 0x20081002, 0x20081006, 0x20080002, 0x20080006, 0x20081002, 0x20081006,
			0x30080002, 0x30080006, 0x30081002, 0x30081006, 0x30080002, 0x30080006, 0x30081002, 0x30081006,
			0x20080022, 0x20080026, 0x20081022, 0x20081026, 0x20080022, 0x20080026, 0x20081022, 0x20081026,
			0x30080022, 0x30080026, 0x30081022, 0x30081026, 0x30080022, 0x30080026, 0x30081022, 0x30081026
		),array(
			0x00000000, 0x00100000, 0x00000008, 0x00100008, 0x00000200, 0x00100200, 0x00000208, 0x00100208,
			0x00000000, 0x00100000, 0x00000008, 0x00100008, 0x00000200, 0x00100200, 0x00000208, 0x00100208,
			0x04000000, 0x04100000, 0x04000008, 0x04100008, 0x04000200, 0x04100200, 0x04000208, 0x04100208,
			0x04000000, 0x04100000, 0x04000008, 0x04100008, 0x04000200, 0x04100200, 0x04000208, 0x04100208,
			0x00002000, 0x00102000, 0x00002008, 0x00102008, 0x00002200, 0x00102200, 0x00002208, 0x00102208,
			0x00002000, 0x00102000, 0x00002008, 0x00102008, 0x00002200, 0x00102200, 0x00002208, 0x00102208,
			0x04002000, 0x04102000, 0x04002008, 0x04102008, 0x04002200, 0x04102200, 0x04002208, 0x04102208,
			0x04002000, 0x04102000, 0x04002008, 0x04102008, 0x04002200, 0x04102200, 0x04002208, 0x04102208,
			0x00000000, 0x00100000, 0x00000008, 0x00100008, 0x00000200, 0x00100200, 0x00000208, 0x00100208,
			0x00000000, 0x00100000, 0x00000008, 0x00100008, 0x00000200, 0x00100200, 0x00000208, 0x00100208,
			0x04000000, 0x04100000, 0x04000008, 0x04100008, 0x04000200, 0x04100200, 0x04000208, 0x04100208,
			0x04000000, 0x04100000, 0x04000008, 0x04100008, 0x04000200, 0x04100200, 0x04000208, 0x04100208,
			0x00002000, 0x00102000, 0x00002008, 0x00102008, 0x00002200, 0x00102200, 0x00002208, 0x00102208,
			0x00002000, 0x00102000, 0x00002008, 0x00102008, 0x00002200, 0x00102200, 0x00002208, 0x00102208,
			0x04002000, 0x04102000, 0x04002008, 0x04102008, 0x04002200, 0x04102200, 0x04002208, 0x04102208,
			0x04002000, 0x04102000, 0x04002008, 0x04102008, 0x04002200, 0x04102200, 0x04002208, 0x04102208,
			0x00020000, 0x00120000, 0x00020008, 0x00120008, 0x00020200, 0x00120200, 0x00020208, 0x00120208,
			0x00020000, 0x00120000, 0x00020008, 0x00120008, 0x00020200, 0x00120200, 0x00020208, 0x00120208,
			0x04020000, 0x04120000, 0x04020008, 0x04120008, 0x04020200, 0x04120200, 0x04020208, 0x04120208,
			0x04020000, 0x04120000, 0x04020008, 0x04120008, 0x04020200, 0x04120200, 0x04020208, 0x04120208,
			0x00022000, 0x00122000, 0x00022008, 0x00122008, 0x00022200, 0x00122200, 0x00022208, 0x00122208,
			0x00022000, 0x00122000, 0x00022008, 0x00122008, 0x00022200, 0x00122200, 0x00022208, 0x00122208,
			0x04022000, 0x04122000, 0x04022008, 0x04122008, 0x04022200, 0x04122200, 0x04022208, 0x04122208,
			0x04022000, 0x04122000, 0x04022008, 0x04122008, 0x04022200, 0x04122200, 0x04022208, 0x04122208,
			0x00020000, 0x00120000, 0x00020008, 0x00120008, 0x00020200, 0x00120200, 0x00020208, 0x00120208,
			0x00020000, 0x00120000, 0x00020008, 0x00120008, 0x00020200, 0x00120200, 0x00020208, 0x00120208,
			0x04020000, 0x04120000, 0x04020008, 0x04120008, 0x04020200, 0x04120200, 0x04020208, 0x04120208,
			0x04020000, 0x04120000, 0x04020008, 0x04120008, 0x04020200, 0x04120200, 0x04020208, 0x04120208,
			0x00022000, 0x00122000, 0x00022008, 0x00122008, 0x00022200, 0x00122200, 0x00022208, 0x00122208,
			0x00022000, 0x00122000, 0x00022008, 0x00122008, 0x00022200, 0x00122200, 0x00022208, 0x00122208,
			0x04022000, 0x04122000, 0x04022008, 0x04122008, 0x04022200, 0x04122200, 0x04022208, 0x04122208,
			0x04022000, 0x04122000, 0x04022008, 0x04122008, 0x04022200, 0x04122200, 0x04022208, 0x04122208
		)
	);
	protected static $despc2d = array(
		array(
			0x00000000, 0x00000001, 0x08000000, 0x08000001, 0x00200000, 0x00200001, 0x08200000, 0x08200001,
			0x00000002, 0x00000003, 0x08000002, 0x08000003, 0x00200002, 0x00200003, 0x08200002, 0x08200003
		),array(
			0x00000000, 0x00100000, 0x00000800, 0x00100800, 0x00000000, 0x00100000, 0x00000800, 0x00100800,
			0x04000000, 0x04100000, 0x04000800, 0x04100800, 0x04000000, 0x04100000, 0x04000800, 0x04100800,
			0x00000004, 0x00100004, 0x00000804, 0x00100804, 0x00000004, 0x00100004, 0x00000804, 0x00100804,
			0x04000004, 0x04100004, 0x04000804, 0x04100804, 0x04000004, 0x04100004, 0x04000804, 0x04100804,
			0x00000000, 0x00100000, 0x00000800, 0x00100800, 0x00000000, 0x00100000, 0x00000800, 0x00100800,
			0x04000000, 0x04100000, 0x04000800, 0x04100800, 0x04000000, 0x04100000, 0x04000800, 0x04100800,
			0x00000004, 0x00100004, 0x00000804, 0x00100804, 0x00000004, 0x00100004, 0x00000804, 0x00100804,
			0x04000004, 0x04100004, 0x04000804, 0x04100804, 0x04000004, 0x04100004, 0x04000804, 0x04100804,
			0x00000200, 0x00100200, 0x00000A00, 0x00100A00, 0x00000200, 0x00100200, 0x00000A00, 0x00100A00,
			0x04000200, 0x04100200, 0x04000A00, 0x04100A00, 0x04000200, 0x04100200, 0x04000A00, 0x04100A00,
			0x00000204, 0x00100204, 0x00000A04, 0x00100A04, 0x00000204, 0x00100204, 0x00000A04, 0x00100A04,
			0x04000204, 0x04100204, 0x04000A04, 0x04100A04, 0x04000204, 0x04100204, 0x04000A04, 0x04100A04,
			0x00000200, 0x00100200, 0x00000A00, 0x00100A00, 0x00000200, 0x00100200, 0x00000A00, 0x00100A00,
			0x04000200, 0x04100200, 0x04000A00, 0x04100A00, 0x04000200, 0x04100200, 0x04000A00, 0x04100A00,
			0x00000204, 0x00100204, 0x00000A04, 0x00100A04, 0x00000204, 0x00100204, 0x00000A04, 0x00100A04,
			0x04000204, 0x04100204, 0x04000A04, 0x04100A04, 0x04000204, 0x04100204, 0x04000A04, 0x04100A04,
			0x00020000, 0x00120000, 0x00020800, 0x00120800, 0x00020000, 0x00120000, 0x00020800, 0x00120800,
			0x04020000, 0x04120000, 0x04020800, 0x04120800, 0x04020000, 0x04120000, 0x04020800, 0x04120800,
			0x00020004, 0x00120004, 0x00020804, 0x00120804, 0x00020004, 0x00120004, 0x00020804, 0x00120804,
			0x04020004, 0x04120004, 0x04020804, 0x04120804, 0x04020004, 0x04120004, 0x04020804, 0x04120804,
			0x00020000, 0x00120000, 0x00020800, 0x00120800, 0x00020000, 0x00120000, 0x00020800, 0x00120800,
			0x04020000, 0x04120000, 0x04020800, 0x04120800, 0x04020000, 0x04120000, 0x04020800, 0x04120800,
			0x00020004, 0x00120004, 0x00020804, 0x00120804, 0x00020004, 0x00120004, 0x00020804, 0x00120804,
			0x04020004, 0x04120004, 0x04020804, 0x04120804, 0x04020004, 0x04120004, 0x04020804, 0x04120804,
			0x00020200, 0x00120200, 0x00020A00, 0x00120A00, 0x00020200, 0x00120200, 0x00020A00, 0x00120A00,
			0x04020200, 0x04120200, 0x04020A00, 0x04120A00, 0x04020200, 0x04120200, 0x04020A00, 0x04120A00,
			0x00020204, 0x00120204, 0x00020A04, 0x00120A04, 0x00020204, 0x00120204, 0x00020A04, 0x00120A04,
			0x04020204, 0x04120204, 0x04020A04, 0x04120A04, 0x04020204, 0x04120204, 0x04020A04, 0x04120A04,
			0x00020200, 0x00120200, 0x00020A00, 0x00120A00, 0x00020200, 0x00120200, 0x00020A00, 0x00120A00,
			0x04020200, 0x04120200, 0x04020A00, 0x04120A00, 0x04020200, 0x04120200, 0x04020A00, 0x04120A00,
			0x00020204, 0x00120204, 0x00020A04, 0x00120A04, 0x00020204, 0x00120204, 0x00020A04, 0x00120A04,
			0x04020204, 0x04120204, 0x04020A04, 0x04120A04, 0x04020204, 0x04120204, 0x04020A04, 0x04120A04
		),array(
			0x00000000, 0x00010000, 0x02000000, 0x02010000, 0x00000020, 0x00010020, 0x02000020, 0x02010020,
			0x00040000, 0x00050000, 0x02040000, 0x02050000, 0x00040020, 0x00050020, 0x02040020, 0x02050020,
			0x00002000, 0x00012000, 0x02002000, 0x02012000, 0x00002020, 0x00012020, 0x02002020, 0x02012020,
			0x00042000, 0x00052000, 0x02042000, 0x02052000, 0x00042020, 0x00052020, 0x02042020, 0x02052020,
			0x00000000, 0x00010000, 0x02000000, 0x02010000, 0x00000020, 0x00010020, 0x02000020, 0x02010020,
			0x00040000, 0x00050000, 0x02040000, 0x02050000, 0x00040020, 0x00050020, 0x02040020, 0x02050020,
			0x00002000, 0x00012000, 0x02002000, 0x02012000, 0x00002020, 0x00012020, 0x02002020, 0x02012020,
			0x00042000, 0x00052000, 0x02042000, 0x02052000, 0x00042020, 0x00052020, 0x02042020, 0x02052020,
			0x00000010, 0x00010010, 0x02000010, 0x02010010, 0x00000030, 0x00010030, 0x02000030, 0x02010030,
			0x00040010, 0x00050010, 0x02040010, 0x02050010, 0x00040030, 0x00050030, 0x02040030, 0x02050030,
			0x00002010, 0x00012010, 0x02002010, 0x02012010, 0x00002030, 0x00012030, 0x02002030, 0x02012030,
			0x00042010, 0x00052010, 0x02042010, 0x02052010, 0x00042030, 0x00052030, 0x02042030, 0x02052030,
			0x00000010, 0x00010010, 0x02000010, 0x02010010, 0x00000030, 0x00010030, 0x02000030, 0x02010030,
			0x00040010, 0x00050010, 0x02040010, 0x02050010, 0x00040030, 0x00050030, 0x02040030, 0x02050030,
			0x00002010, 0x00012010, 0x02002010, 0x02012010, 0x00002030, 0x00012030, 0x02002030, 0x02012030,
			0x00042010, 0x00052010, 0x02042010, 0x02052010, 0x00042030, 0x00052030, 0x02042030, 0x02052030,
			0x20000000, 0x20010000, 0x22000000, 0x22010000, 0x20000020, 0x20010020, 0x22000020, 0x22010020,
			0x20040000, 0x20050000, 0x22040000, 0x22050000, 0x20040020, 0x20050020, 0x22040020, 0x22050020,
			0x20002000, 0x20012000, 0x22002000, 0x22012000, 0x20002020, 0x20012020, 0x22002020, 0x22012020,
			0x20042000, 0x20052000, 0x22042000, 0x22052000, 0x20042020, 0x20052020, 0x22042020, 0x22052020,
			0x20000000, 0x20010000, 0x22000000, 0x22010000, 0x20000020, 0x20010020, 0x22000020, 0x22010020,
			0x20040000, 0x20050000, 0x22040000, 0x22050000, 0x20040020, 0x20050020, 0x22040020, 0x22050020,
			0x20002000, 0x20012000, 0x22002000, 0x22012000, 0x20002020, 0x20012020, 0x22002020, 0x22012020,
			0x20042000, 0x20052000, 0x22042000, 0x22052000, 0x20042020, 0x20052020, 0x22042020, 0x22052020,
			0x20000010, 0x20010010, 0x22000010, 0x22010010, 0x20000030, 0x20010030, 0x22000030, 0x22010030,
			0x20040010, 0x20050010, 0x22040010, 0x22050010, 0x20040030, 0x20050030, 0x22040030, 0x22050030,
			0x20002010, 0x20012010, 0x22002010, 0x22012010, 0x20002030, 0x20012030, 0x22002030, 0x22012030,
			0x20042010, 0x20052010, 0x22042010, 0x22052010, 0x20042030, 0x20052030, 0x22042030, 0x22052030,
			0x20000010, 0x20010010, 0x22000010, 0x22010010, 0x20000030, 0x20010030, 0x22000030, 0x22010030,
			0x20040010, 0x20050010, 0x22040010, 0x22050010, 0x20040030, 0x20050030, 0x22040030, 0x22050030,
			0x20002010, 0x20012010, 0x22002010, 0x22012010, 0x20002030, 0x20012030, 0x22002030, 0x22012030,
			0x20042010, 0x20052010, 0x22042010, 0x22052010, 0x20042030, 0x20052030, 0x22042030, 0x22052030
		),array(
			0x00000000, 0x00000400, 0x01000000, 0x01000400, 0x00000000, 0x00000400, 0x01000000, 0x01000400,
			0x00000100, 0x00000500, 0x01000100, 0x01000500, 0x00000100, 0x00000500, 0x01000100, 0x01000500,
			0x10000000, 0x10000400, 0x11000000, 0x11000400, 0x10000000, 0x10000400, 0x11000000, 0x11000400,
			0x10000100, 0x10000500, 0x11000100, 0x11000500, 0x10000100, 0x10000500, 0x11000100, 0x11000500,
			0x00080000, 0x00080400, 0x01080000, 0x01080400,	0x00080000, 0x00080400, 0x01080000, 0x01080400,
			0x00080100, 0x00080500, 0x01080100, 0x01080500, 0x00080100, 0x00080500, 0x01080100, 0x01080500,
			0x10080000, 0x10080400, 0x11080000, 0x11080400, 0x10080000, 0x10080400, 0x11080000, 0x11080400,
			0x10080100, 0x10080500, 0x11080100, 0x11080500, 0x10080100, 0x10080500, 0x11080100, 0x11080500,
			0x00000008, 0x00000408, 0x01000008, 0x01000408, 0x00000008, 0x00000408, 0x01000008, 0x01000408,
			0x00000108, 0x00000508, 0x01000108, 0x01000508, 0x00000108, 0x00000508, 0x01000108, 0x01000508,
			0x10000008, 0x10000408, 0x11000008, 0x11000408, 0x10000008, 0x10000408, 0x11000008, 0x11000408,
			0x10000108, 0x10000508, 0x11000108, 0x11000508, 0x10000108, 0x10000508, 0x11000108, 0x11000508,
			0x00080008, 0x00080408, 0x01080008, 0x01080408, 0x00080008, 0x00080408, 0x01080008, 0x01080408,
			0x00080108, 0x00080508, 0x01080108, 0x01080508, 0x00080108, 0x00080508, 0x01080108, 0x01080508,
			0x10080008, 0x10080408, 0x11080008, 0x11080408, 0x10080008, 0x10080408, 0x11080008, 0x11080408,
			0x10080108, 0x10080508, 0x11080108, 0x11080508, 0x10080108, 0x10080508, 0x11080108, 0x11080508,
			0x00001000, 0x00001400, 0x01001000, 0x01001400, 0x00001000, 0x00001400, 0x01001000, 0x01001400,
			0x00001100, 0x00001500, 0x01001100, 0x01001500, 0x00001100, 0x00001500, 0x01001100, 0x01001500,
			0x10001000, 0x10001400, 0x11001000, 0x11001400, 0x10001000, 0x10001400, 0x11001000, 0x11001400,
			0x10001100, 0x10001500, 0x11001100, 0x11001500, 0x10001100, 0x10001500, 0x11001100, 0x11001500,
			0x00081000, 0x00081400, 0x01081000, 0x01081400, 0x00081000, 0x00081400, 0x01081000, 0x01081400,
			0x00081100, 0x00081500, 0x01081100, 0x01081500, 0x00081100, 0x00081500, 0x01081100, 0x01081500,
			0x10081000, 0x10081400, 0x11081000, 0x11081400, 0x10081000, 0x10081400, 0x11081000, 0x11081400,
			0x10081100, 0x10081500, 0x11081100, 0x11081500, 0x10081100, 0x10081500, 0x11081100, 0x11081500,
			0x00001008, 0x00001408, 0x01001008, 0x01001408, 0x00001008, 0x00001408, 0x01001008, 0x01001408,
			0x00001108, 0x00001508, 0x01001108, 0x01001508, 0x00001108, 0x00001508, 0x01001108, 0x01001508,
			0x10001008, 0x10001408, 0x11001008, 0x11001408, 0x10001008, 0x10001408, 0x11001008, 0x11001408,
			0x10001108, 0x10001508, 0x11001108, 0x11001508, 0x10001108, 0x10001508, 0x11001108, 0x11001508,
			0x00081008, 0x00081408, 0x01081008, 0x01081408, 0x00081008, 0x00081408, 0x01081008, 0x01081408,
			0x00081108, 0x00081508, 0x01081108, 0x01081508, 0x00081108, 0x00081508, 0x01081108, 0x01081508,
			0x10081008, 0x10081408, 0x11081008, 0x11081408, 0x10081008, 0x10081408, 0x11081008, 0x11081408,
			0x10081108, 0x10081508, 0x11081108, 0x11081508, 0x10081108, 0x10081508, 0x11081108, 0x11081508
		)
	);
	protected static $sjf = array(
		0xa3, 0xd7, 0x09, 0x83, 0xf8, 0x48, 0xf6, 0xf4, 0xb3, 0x21, 0x15, 0x78, 0x99, 0xb1, 0xaf, 0xf9,
		0xe7, 0x2d, 0x4d, 0x8a, 0xce, 0x4c, 0xca, 0x2e, 0x52, 0x95, 0xd9, 0x1e, 0x4e, 0x38, 0x44, 0x28,
		0x0a, 0xdf, 0x02, 0xa0, 0x17, 0xf1, 0x60, 0x68, 0x12, 0xb7, 0x7a, 0xc3, 0xe9, 0xfa, 0x3d, 0x53,
		0x96, 0x84, 0x6b, 0xba, 0xf2, 0x63, 0x9a, 0x19, 0x7c, 0xae, 0xe5, 0xf5, 0xf7, 0x16, 0x6a, 0xa2,
		0x39, 0xb6, 0x7b, 0x0f, 0xc1, 0x93, 0x81, 0x1b, 0xee, 0xb4, 0x1a, 0xea, 0xd0, 0x91, 0x2f, 0xb8,
		0x55, 0xb9, 0xda, 0x85, 0x3f, 0x41, 0xbf, 0xe0, 0x5a, 0x58, 0x80, 0x5f, 0x66, 0x0b, 0xd8, 0x90,
		0x35, 0xd5, 0xc0, 0xa7, 0x33, 0x06, 0x65, 0x69, 0x45, 0x00, 0x94, 0x56, 0x6d, 0x98, 0x9b, 0x76,
		0x97, 0xfc, 0xb2, 0xc2, 0xb0, 0xfe, 0xdb, 0x20, 0xe1, 0xeb, 0xd6, 0xe4, 0xdd, 0x47, 0x4a, 0x1d,
		0x42, 0xed, 0x9e, 0x6e, 0x49, 0x3c, 0xcd, 0x43, 0x27, 0xd2, 0x07, 0xd4, 0xde, 0xc7, 0x67, 0x18,
		0x89, 0xcb, 0x30, 0x1f, 0x8d, 0xc6, 0x8f, 0xaa, 0xc8, 0x74, 0xdc, 0xc9, 0x5d, 0x5c, 0x31, 0xa4,
		0x70, 0x88, 0x61, 0x2c, 0x9f, 0x0d, 0x2b, 0x87, 0x50, 0x82, 0x54, 0x64, 0x26, 0x7d, 0x03, 0x40,
		0x34, 0x4b, 0x1c, 0x73, 0xd1, 0xc4, 0xfd, 0x3b, 0xcc, 0xfb, 0x7f, 0xab, 0xe6, 0x3e, 0x5b, 0xa5,
		0xad, 0x04, 0x23, 0x9c, 0x14, 0x51, 0x22, 0xf0, 0x29, 0x79, 0x71, 0x7e, 0xff, 0x8c, 0x0e, 0xe2,
		0x0c, 0xef, 0xbc, 0x72, 0x75, 0x6f, 0x37, 0xa1, 0xec, 0xd3, 0x8e, 0x62, 0x8b, 0x86, 0x10, 0xe8,
		0x08, 0x77, 0x11, 0xbe, 0x92, 0x4f, 0x24, 0xc5, 0x32, 0x36, 0x9d, 0xcf, 0xf3, 0xa6, 0xbb, 0xac,
		0x5e, 0x6c, 0xa9, 0x13, 0x57, 0x25, 0xb5, 0xe3, 0xbd, 0xa8, 0x3a, 0x01, 0x05, 0x59, 0x2a, 0x46
	);
	protected static function sjgptenc($bytes, $key){
		$l = ord($bytes[0]);
		$r = ord($bytes[1]);
		for($i = 3; $i >= 0; --$i){
			if($i == 0 || $i == 2)
				$l = $l ^ self::$sjf[$r ^ ord($key[$i])];
			else
				$r = $r ^ self::$sjf[$l ^ ord($key[$i])];
		}
		return pack('C2', $l, $r);
	}
	protected static function sjgptdec($bytes, $key){
		$l = ord($bytes[0]);
		$r = ord($bytes[1]);
		for($i = 0; $i < 4; ++$i){
			if($i == 0 || $i == 2)
				$l = $l ^ self::$sjf[$r ^ ord($key[$i])];
			else
				$r = $r ^ self::$sjf[$l ^ ord($key[$i])];
		}
		return pack('C2', $l, $r);
	}
	protected static function sjruleaenc($bytes, $key, $i){
		$w = str_split($bytes, 2);
		$w[4] = $w[3];
		$w[3] = $w[2];
		$w[2] = $w[1];
		$w[1] = self::sjgptenc($w[0], $key);
		$w[0] = $w[1] ^ $w[4] ^ "\0" . xnmath::decstr($i);
		return $w[0] . $w[1] . $w[2] . $w[3];
	}
	protected static function sjruleadec($bytes, $key, $i){
		$w = str_split($bytes, 2);
		$w[4] = $w[0] ^ $w[1] ^ "\0" . xnmath::decstr($i);
		$w[0] = self::sjgptdec($w[1], $key);
		return $w[0] . $w[2] . $w[3] . $w[4];
	}
	protected static function sjrulebenc($bytes, $key, $i){
		$w = str_split($bytes, 2);
		$w[4] = $w[3];
		$w[3] = $w[2];
		$w[2] = $w[0] ^ $w[1] ^ "\0" . xnmath::decstr($i);
		$w[1] = self::sjgptenc($w[0], $key);
		return $w[4] . $w[1] . $w[2] . $w[3];
	}
	protected static function sjrulebdec($bytes, $key, $i){
		$w = str_split($bytes, 2);
		$w[4] = $w[0];
		$w[0] = self::sjgptdec($w[1], $key);
		$w[1] = $w[0] ^ $w[2] ^ "\0" . xnmath::decstr($i);
		return $w[0] . $w[1] . $w[3] . $w[4];
	}
	protected static $vgt = array(
		'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		'B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A',
		'C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B',
		'D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C',
		'E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D',
		'F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E',
		'G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F',
		'H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G',
		'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H',
		'J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I',
		'K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J',
		'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K',
		'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L',
		'N','O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M',
		'O','P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N',
		'P','Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
		'Q','R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P',
		'R','S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q',
		'S','T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R',
		'T','U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S',
		'U','V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
		'V','W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U',
		'W','X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V',
		'X','Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W',
		'Y','Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X',
		'Z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y'
	);
	protected static function a4prga($key, $l){
		$res = '';
		$j = 0;
		$s = range(0, 255);
		$z = strlen($key);
		for($i = 0; $i < 256; ++$i){
			$k = $key[$i % $z];
			$j = ($j + $s[$i] + ord($k)) & 0xff;
			swap($s[$i], $s[$j]);
		}
		for($c = 0; $c < $l; ++$c){
			$i = ($i + 1) & 0xff;
			$j = ($j + $s[$i]) & 0xff;
			swap($s[$i], $s[$j]);
			$res .= chr($s[($s[$i] + $s[$j]) & 0xff]);
		}
		return $res;
	}
	protected static $casts = array(
		array(
			0x30FB40D4, 0x9FA0FF0B, 0x6BECCD2F, 0x3F258C7A, 0x1E213F2F, 0x9C004DD3, 0x6003E540, 0xCF9FC949,
			0xBFD4AF27, 0x88BBBDB5, 0xE2034090, 0x98D09675, 0x6E63A0E0, 0x15C361D2, 0xC2E7661D, 0x22D4FF8E,
			0x28683B6F, 0xC07FD059, 0xFF2379C8, 0x775F50E2, 0x43C340D3, 0xDF2F8656, 0x887CA41A, 0xA2D2BD2D,
			0xA1C9E0D6, 0x346C4819, 0x61B76D87, 0x22540F2F, 0x2ABE32E1, 0xAA54166B, 0x22568E3A, 0xA2D341D0,
			0x66DB40C8, 0xA784392F, 0x004DFF2F, 0x2DB9D2DE, 0x97943FAC, 0x4A97C1D8, 0x527644B7, 0xB5F437A7,
			0xB82CBAEF, 0xD751D159, 0x6FF7F0ED, 0x5A097A1F, 0x827B68D0, 0x90ECF52E, 0x22B0C054, 0xBC8E5935,
			0x4B6D2F7F, 0x50BB64A2, 0xD2664910, 0xBEE5812D, 0xB7332290, 0xE93B159F, 0xB48EE411, 0x4BFF345D,
			0xFD45C240, 0xAD31973F, 0xC4F6D02E, 0x55FC8165, 0xD5B1CAAD, 0xA1AC2DAE, 0xA2D4B76D, 0xC19B0C50,
			0x882240F2, 0x0C6E4F38, 0xA4E4BFD7, 0x4F5BA272, 0x564C1D2F, 0xC59C5319, 0xB949E354, 0xB04669FE,
			0xB1B6AB8A, 0xC71358DD, 0x6385C545, 0x110F935D, 0x57538AD5, 0x6A390493, 0xE63D37E0, 0x2A54F6B3,
			0x3A787D5F, 0x6276A0B5, 0x19A6FCDF, 0x7A42206A, 0x29F9D4D5, 0xF61B1891, 0xBB72275E, 0xAA508167,
			0x38901091, 0xC6B505EB, 0x84C7CB8C, 0x2AD75A0F, 0x874A1427, 0xA2D1936B, 0x2AD286AF, 0xAA56D291,
			0xD7894360, 0x425C750D, 0x93B39E26, 0x187184C9, 0x6C00B32D, 0x73E2BB14, 0xA0BEBC3C, 0x54623779,
			0x64459EAB, 0x3F328B82, 0x7718CF82, 0x59A2CEA6, 0x04EE002E, 0x89FE78E6, 0x3FAB0950, 0x325FF6C2,
			0x81383F05, 0x6963C5C8, 0x76CB5AD6, 0xD49974C9, 0xCA180DCF, 0x380782D5, 0xC7FA5CF6, 0x8AC31511,
			0x35E79E13, 0x47DA91D0, 0xF40F9086, 0xA7E2419E, 0x31366241, 0x051EF495, 0xAA573B04, 0x4A805D8D,
			0x548300D0, 0x00322A3C, 0xBF64CDDF, 0xBA57A68E, 0x75C6372B, 0x50AFD341, 0xA7C13275, 0x915A0BF5,
			0x6B54BFAB, 0x2B0B1426, 0xAB4CC9D7, 0x449CCD82, 0xF7FBF265, 0xAB85C5F3, 0x1B55DB94, 0xAAD4E324,
			0xCFA4BD3F, 0x2DEAA3E2, 0x9E204D02, 0xC8BD25AC, 0xEADF55B3, 0xD5BD9E98, 0xE31231B2, 0x2AD5AD6C,
			0x954329DE, 0xADBE4528, 0xD8710F69, 0xAA51C90F, 0xAA786BF6, 0x22513F1E, 0xAA51A79B, 0x2AD344CC,
			0x7B5A41F0, 0xD37CFBAD, 0x1B069505, 0x41ECE491, 0xB4C332E6, 0x032268D4, 0xC9600ACC, 0xCE387E6D,
			0xBF6BB16C, 0x6A70FB78, 0x0D03D9C9, 0xD4DF39DE, 0xE01063DA, 0x4736F464, 0x5AD328D8, 0xB347CC96,
			0x75BB0FC3, 0x98511BFB, 0x4FFBCC35, 0xB58BCF6A, 0xE11F0ABC, 0xBFC5FE4A, 0xA70AEC10, 0xAC39570A,
			0x3F04442F, 0x6188B153, 0xE0397A2E, 0x5727CB79, 0x9CEB418F, 0x1CACD68D, 0x2AD37C96, 0x0175CB9D,
			0xC69DFF09, 0xC75B65F0, 0xD9DB40D8, 0xEC0E7779, 0x4744EAD4, 0xB11C3274, 0xDD24CB9E, 0x7E1C54BD,
			0xF01144F9, 0xD2240EB1, 0x9675B3FD, 0xA3AC3755, 0xD47C27AF, 0x51C85F4D, 0x56907596, 0xA5BB15E6,
			0x580304F0, 0xCA042CF1, 0x011A37EA, 0x8DBFAADB, 0x35BA3E4A, 0x3526FFA0, 0xC37B4D09, 0xBC306ED9,
			0x98A52666, 0x5648F725, 0xFF5E569D, 0x0CED63D0, 0x7C63B2CF, 0x700B45E1, 0xD5EA50F1, 0x85A92872,
			0xAF1FBDA7, 0xD4234870, 0xA7870BF3, 0x2D3B4D79, 0x42E04198, 0x0CD0EDE7, 0x26470DB8, 0xF881814C,
			0x474D6AD7, 0x7C0C5E5C, 0xD1231959, 0x381B7298, 0xF5D2F4DB, 0xAB838653, 0x6E2F1E23, 0x83719C9E,
			0xBD91E046, 0x9A56456E, 0xDC39200C, 0x20C8C571, 0x962BDA1C, 0xE1E696FF, 0xB141AB08, 0x7CCA89B9,
			0x1A69E783, 0x02CC4843, 0xA2F7C579, 0x429EF47D, 0x427B169C, 0x5AC9F049, 0xDD8F0F00, 0x5C8165BF
		), array(
			0x1F201094, 0xEF0BA75B, 0x69E3CF7E, 0x393F4380, 0xFE61CF7A, 0xEEC5207A, 0x55889C94, 0x72FC0651,
			0xADA7EF79, 0x4E1D7235, 0xD55A63CE, 0xDE0436BA, 0x99C430EF, 0x5F0C0794, 0x18DCDB7D, 0xA1D6EFF3,
			0xA0B52F7B, 0x59E83605, 0xEE15B094, 0xE9FFD909, 0xDC440086, 0xEF944459, 0xBA83CCB3, 0xE0C3CDFB,
			0xD1DA4181, 0x3B092AB1, 0xF997F1C1, 0xA5E6CF7B, 0x01420DDB, 0xE4E7EF5B, 0x25A1FF41, 0xE180F806,
			0x1FC41080, 0x179BEE7A, 0xD37AC6A9, 0xFE5830A4, 0x98DE8B7F, 0x77E83F4E, 0x79929269, 0x24FA9F7B,
			0xE113C85B, 0xACC40083, 0xD7503525, 0xF7EA615F, 0x62143154, 0x0D554B63, 0x5D681121, 0xC866C359,
			0x3D63CF73, 0xCEE234C0, 0xD4D87E87, 0x5C672B21, 0x071F6181, 0x39F7627F, 0x361E3084, 0xE4EB573B,
			0x602F64A4, 0xD63ACD9C, 0x1BBC4635, 0x9E81032D, 0x2701F50C, 0x99847AB4, 0xA0E3DF79, 0xBA6CF38C,
			0x10843094, 0x2537A95E, 0xF46F6FFE, 0xA1FF3B1F, 0x208CFB6A, 0x8F458C74, 0xD9E0A227, 0x4EC73A34,
			0xFC884F69, 0x3E4DE8DF, 0xEF0E0088, 0x3559648D, 0x8A45388C, 0x1D804366, 0x721D9BFD, 0xA58684BB,
			0xE8256333, 0x844E8212, 0x128D8098, 0xFED33FB4, 0xCE280AE1, 0x27E19BA5, 0xD5A6C252, 0xE49754BD,
			0xC5D655DD, 0xEB667064, 0x77840B4D, 0xA1B6A801, 0x84DB26A9, 0xE0B56714, 0x21F043B7, 0xE5D05860,
			0x54F03084, 0x066FF472, 0xA31AA153, 0xDADC4755, 0xB5625DBF, 0x68561BE6, 0x83CA6B94, 0x2D6ED23B,
			0xECCF01DB, 0xA6D3D0BA, 0xB6803D5C, 0xAF77A709, 0x33B4A34C, 0x397BC8D6, 0x5EE22B95, 0x5F0E5304,
			0x81ED6F61, 0x20E74364, 0xB45E1378, 0xDE18639B, 0x881CA122, 0xB96726D1, 0x8049A7E8, 0x22B7DA7B,
			0x5E552D25, 0x5272D237, 0x79D2951C, 0xC60D894C, 0x488CB402, 0x1BA4FE5B, 0xA4B09F6B, 0x1CA815CF,
			0xA20C3005, 0x8871DF63, 0xB9DE2FCB, 0x0CC6C9E9, 0x0BEEFF53, 0xE3214517, 0xB4542835, 0x9F63293C,
			0xEE41E729, 0x6E1D2D7C, 0x50045286, 0x1E6685F3, 0xF33401C6, 0x30A22C95, 0x31A70850, 0x60930F13,
			0x73F98417, 0xA1269859, 0xEC645C44, 0x52C877A9, 0xCDFF33A6, 0xA02B1741, 0x7CBAD9A2, 0x2180036F,
			0x50D99C08, 0xCB3F4861, 0xC26BD765, 0x64A3F6AB, 0x80342676, 0x25A75E7B, 0xE4E6D1FC, 0x20C710E6,
			0xCDF0B680, 0x17844D3B, 0x31EEF84D, 0x7E0824E4, 0x2CCB49EB, 0x846A3BAE, 0x8FF77888, 0xEE5D60F6,
			0x7AF75673, 0x2FDD5CDB, 0xA11631C1, 0x30F66F43, 0xB3FAEC54, 0x157FD7FA, 0xEF8579CC, 0xD152DE58,
			0xDB2FFD5E, 0x8F32CE19, 0x306AF97A, 0x02F03EF8, 0x99319AD5, 0xC242FA0F, 0xA7E3EBB0, 0xC68E4906,
			0xB8DA230C, 0x80823028, 0xDCDEF3C8, 0xD35FB171, 0x088A1BC8, 0xBEC0C560, 0x61A3C9E8, 0xBCA8F54D,
			0xC72FEFFA, 0x22822E99, 0x82C570B4, 0xD8D94E89, 0x8B1C34BC, 0x301E16E6, 0x273BE979, 0xB0FFEAA6,
			0x61D9B8C6, 0x00B24869, 0xB7FFCE3F, 0x08DC283B, 0x43DAF65A, 0xF7E19798, 0x7619B72F, 0x8F1C9BA4,
			0xDC8637A0, 0x16A7D3B1, 0x9FC393B7, 0xA7136EEB, 0xC6BCC63E, 0x1A513742, 0xEF6828BC, 0x520365D6,
			0x2D6A77AB, 0x3527ED4B, 0x821FD216, 0x095C6E2E, 0xDB92F2FB, 0x5EEA29CB, 0x145892F5, 0x91584F7F,
			0x5483697B, 0x2667A8CC, 0x85196048, 0x8C4BACEA, 0x833860D4, 0x0D23E0F9, 0x6C387E8A, 0x0AE6D249,
			0xB284600C, 0xD835731D, 0xDCB1C647, 0xAC4C56EA, 0x3EBD81B3, 0x230EABB0, 0x6438BC87, 0xF0B5B1FA,
			0x8F5EA2B3, 0xFC184642, 0x0A036B7A, 0x4FB089BD, 0x649DA589, 0xA345415E, 0x5C038323, 0x3E5D3BB9,
			0x43D79572, 0x7E6DD07C, 0x06DFDF1E, 0x6C6CC4EF, 0x7160A539, 0x73BFBE70, 0x83877605, 0x4523ECF1
		), array(
			0x8DEFC240, 0x25FA5D9F, 0xEB903DBF, 0xE810C907, 0x47607FFF, 0x369FE44B, 0x8C1FC644, 0xAECECA90,
			0xBEB1F9BF, 0xEEFBCAEA, 0xE8CF1950, 0x51DF07AE, 0x920E8806, 0xF0AD0548, 0xE13C8D83, 0x927010D5,
			0x11107D9F, 0x07647DB9, 0xB2E3E4D4, 0x3D4F285E, 0xB9AFA820, 0xFADE82E0, 0xA067268B, 0x8272792E,
			0x553FB2C0, 0x489AE22B, 0xD4EF9794, 0x125E3FBC, 0x21FFFCEE, 0x825B1BFD, 0x9255C5ED, 0x1257A240,
			0x4E1A8302, 0xBAE07FFF, 0x528246E7, 0x8E57140E, 0x3373F7BF, 0x8C9F8188, 0xA6FC4EE8, 0xC982B5A5,
			0xA8C01DB7, 0x579FC264, 0x67094F31, 0xF2BD3F5F, 0x40FFF7C1, 0x1FB78DFC, 0x8E6BD2C1, 0x437BE59B,
			0x99B03DBF, 0xB5DBC64B, 0x638DC0E6, 0x55819D99, 0xA197C81C, 0x4A012D6E, 0xC5884A28, 0xCCC36F71,
			0xB843C213, 0x6C0743F1, 0x8309893C, 0x0FEDDD5F, 0x2F7FE850, 0xD7C07F7E, 0x02507FBF, 0x5AFB9A04,
			0xA747D2D0, 0x1651192E, 0xAF70BF3E, 0x58C31380, 0x5F98302E, 0x727CC3C4, 0x0A0FB402, 0x0F7FEF82,
			0x8C96FDAD, 0x5D2C2AAE, 0x8EE99A49, 0x50DA88B8, 0x8427F4A0, 0x1EAC5790, 0x796FB449, 0x8252DC15,
			0xEFBD7D9B, 0xA672597D, 0xADA840D8, 0x45F54504, 0xFA5D7403, 0xE83EC305, 0x4F91751A, 0x925669C2,
			0x23EFE941, 0xA903F12E, 0x60270DF2, 0x0276E4B6, 0x94FD6574, 0x927985B2, 0x8276DBCB, 0x02778176,
			0xF8AF918D, 0x4E48F79E, 0x8F616DDF, 0xE29D840E, 0x842F7D83, 0x340CE5C8, 0x96BBB682, 0x93B4B148,
			0xEF303CAB, 0x984FAF28, 0x779FAF9B, 0x92DC560D, 0x224D1E20, 0x8437AA88, 0x7D29DC96, 0x2756D3DC,
			0x8B907CEE, 0xB51FD240, 0xE7C07CE3, 0xE566B4A1, 0xC3E9615E, 0x3CF8209D, 0x6094D1E3, 0xCD9CA341,
			0x5C76460E, 0x00EA983B, 0xD4D67881, 0xFD47572C, 0xF76CEDD9, 0xBDA8229C, 0x127DADAA, 0x438A074E,
			0x1F97C090, 0x081BDB8A, 0x93A07EBE, 0xB938CA15, 0x97B03CFF, 0x3DC2C0F8, 0x8D1AB2EC, 0x64380E51,
			0x68CC7BFB, 0xD90F2788, 0x12490181, 0x5DE5FFD4, 0xDD7EF86A, 0x76A2E214, 0xB9A40368, 0x925D958F,
			0x4B39FFFA, 0xBA39AEE9, 0xA4FFD30B, 0xFAF7933B, 0x6D498623, 0x193CBCFA, 0x27627545, 0x825CF47A,
			0x61BD8BA0, 0xD11E42D1, 0xCEAD04F4, 0x127EA392, 0x10428DB7, 0x8272A972, 0x9270C4A8, 0x127DE50B,
			0x285BA1C8, 0x3C62F44F, 0x35C0EAA5, 0xE805D231, 0x428929FB, 0xB4FCDF82, 0x4FB66A53, 0x0E7DC15B,
			0x1F081FAB, 0x108618AE, 0xFCFD086D, 0xF9FF2889, 0x694BCC11, 0x236A5CAE, 0x12DECA4D, 0x2C3F8CC5,
			0xD2D02DFE, 0xF8EF5896, 0xE4CF52DA, 0x95155B67, 0x494A488C, 0xB9B6A80C, 0x5C8F82BC, 0x89D36B45,
			0x3A609437, 0xEC00C9A9, 0x44715253, 0x0A874B49, 0xD773BC40, 0x7C34671C, 0x02717EF6, 0x4FEB5536,
			0xA2D02FFF, 0xD2BF60C4, 0xD43F03C0, 0x50B4EF6D, 0x07478CD1, 0x006E1888, 0xA2E53F55, 0xB9E6D4BC,
			0xA2048016, 0x97573833, 0xD7207D67, 0xDE0F8F3D, 0x72F87B33, 0xABCC4F33, 0x7688C55D, 0x7B00A6B0,
			0x947B0001, 0x570075D2, 0xF9BB88F8, 0x8942019E, 0x4264A5FF, 0x856302E0, 0x72DBD92B, 0xEE971B69,
			0x6EA22FDE, 0x5F08AE2B, 0xAF7A616D, 0xE5C98767, 0xCF1FEBD2, 0x61EFC8C2, 0xF1AC2571, 0xCC8239C2,
			0x67214CB8, 0xB1E583D1, 0xB7DC3E62, 0x7F10BDCE, 0xF90A5C38, 0x0FF0443D, 0x606E6DC6, 0x60543A49,
			0x5727C148, 0x2BE98A1D, 0x8AB41738, 0x20E1BE24, 0xAF96DA0F, 0x68458425, 0x99833BE5, 0x600D457D,
			0x282F9350, 0x8334B362, 0xD91D1120, 0x2B6D8DA0, 0x642B1E31, 0x9C305A00, 0x52BCE688, 0x1B03588A,
			0xF7BAEFD5, 0x4142ED9C, 0xA4315C11, 0x83323EC5, 0xDFEF4636, 0xA133C501, 0xE9D3531C, 0xEE353783
		), array(
			0x9DB30420, 0x1FB6E9DE, 0xA7BE7BEF, 0xD273A298, 0x4A4F7BDB, 0x64AD8C57, 0x85510443, 0xFA020ED1,
			0x7E287AFF, 0xE60FB663, 0x095F35A1, 0x79EBF120, 0xFD059D43, 0x6497B7B1, 0xF3641F63, 0x241E4ADF,
			0x28147F5F, 0x4FA2B8CD, 0xC9430040, 0x0CC32220, 0xFDD30B30, 0xC0A5374F, 0x1D2D00D9, 0x24147B15,
			0xEE4D111A, 0x0FCA5167, 0x71FF904C, 0x2D195FFE, 0x1A05645F, 0x0C13FEFE, 0x081B08CA, 0x05170121,
			0x80530100, 0xE83E5EFE, 0xAC9AF4F8, 0x7FE72701, 0xD2B8EE5F, 0x06DF4261, 0xBB9E9B8A, 0x7293EA25,
			0xCE84FFDF, 0xF5718801, 0x3DD64B04, 0xA26F263B, 0x7ED48400, 0x547EEBE6, 0x446D4CA0, 0x6CF3D6F5,
			0x2649ABDF, 0xAEA0C7F5, 0x36338CC1, 0x503F7E93, 0xD3772061, 0x11B638E1, 0x72500E03, 0xF80EB2BB,
			0xABE0502E, 0xEC8D77DE, 0x57971E81, 0xE14F6746, 0xC9335400, 0x6920318F, 0x081DBB99, 0xFFC304A5,
			0x4D351805, 0x7F3D5CE3, 0xA6C866C6, 0x5D5BCCA9, 0xDAEC6FEA, 0x9F926F91, 0x9F46222F, 0x3991467D,
			0xA5BF6D8E, 0x1143C44F, 0x43958302, 0xD0214EEB, 0x022083B8, 0x3FB6180C, 0x18F8931E, 0x281658E6,
			0x26486E3E, 0x8BD78A70, 0x7477E4C1, 0xB506E07C, 0xF32D0A25, 0x79098B02, 0xE4EABB81, 0x28123B23,
			0x69DEAD38, 0x1574CA16, 0xDF871B62, 0x211C40B7, 0xA51A9EF9, 0x0014377B, 0x041E8AC8, 0x09114003,
			0xBD59E4D2, 0xE3D156D5, 0x4FE876D5, 0x2F91A340, 0x557BE8DE, 0x00EAE4A7, 0x0CE5C2EC, 0x4DB4BBA6,
			0xE756BDFF, 0xDD3369AC, 0xEC17B035, 0x06572327, 0x99AFC8B0, 0x56C8C391, 0x6B65811C, 0x5E146119,
			0x6E85CB75, 0xBE07C002, 0xC2325577, 0x893FF4EC, 0x5BBFC92D, 0xD0EC3B25, 0xB7801AB7, 0x8D6D3B24,
			0x20C763EF, 0xC366A5FC, 0x9C382880, 0x0ACE3205, 0xAAC9548A, 0xECA1D7C7, 0x041AFA32, 0x1D16625A,
			0x6701902C, 0x9B757A54, 0x31D477F7, 0x9126B031, 0x36CC6FDB, 0xC70B8B46, 0xD9E66A48, 0x56E55A79,
			0x026A4CEB, 0x52437EFF, 0x2F8F76B4, 0x0DF980A5, 0x8674CDE3, 0xEDDA04EB, 0x17A9BE04, 0x2C18F4DF,
			0xB7747F9D, 0xAB2AF7B4, 0xEFC34D20, 0x2E096B7C, 0x1741A254, 0xE5B6A035, 0x213D42F6, 0x2C1C7C26,
			0x61C2F50F, 0x6552DAF9, 0xD2C231F8, 0x25130F69, 0xD8167FA2, 0x0418F2C8, 0x001A96A6, 0x0D1526AB,
			0x63315C21, 0x5E0A72EC, 0x49BAFEFD, 0x187908D9, 0x8D0DBD86, 0x311170A7, 0x3E9B640C, 0xCC3E10D7,
			0xD5CAD3B6, 0x0CAEC388, 0xF73001E1, 0x6C728AFF, 0x71EAE2A1, 0x1F9AF36E, 0xCFCBD12F, 0xC1DE8417,
			0xAC07BE6B, 0xCB44A1D8, 0x8B9B0F56, 0x013988C3, 0xB1C52FCA, 0xB4BE31CD, 0xD8782806, 0x12A3A4E2,
			0x6F7DE532, 0x58FD7EB6, 0xD01EE900, 0x24ADFFC2, 0xF4990FC5, 0x9711AAC5, 0x001D7B95, 0x82E5E7D2,
			0x109873F6, 0x00613096, 0xC32D9521, 0xADA121FF, 0x29908415, 0x7FBB977F, 0xAF9EB3DB, 0x29C9ED2A,
			0x5CE2A465, 0xA730F32C, 0xD0AA3FE8, 0x8A5CC091, 0xD49E2CE7, 0x0CE454A9, 0xD60ACD86, 0x015F1919,
			0x77079103, 0xDEA03AF6, 0x78A8565E, 0xDEE356DF, 0x21F05CBE, 0x8B75E387, 0xB3C50651, 0xB8A5C3EF,
			0xD8EEB6D2, 0xE523BE77, 0xC2154529, 0x2F69EFDF, 0xAFE67AFB, 0xF470C4B2, 0xF3E0EB5B, 0xD6CC9876,
			0x39E4460C, 0x1FDA8538, 0x1987832F, 0xCA007367, 0xA99144F8, 0x296B299E, 0x492FC295, 0x9266BEAB,
			0xB5676E69, 0x9BD3DDDA, 0xDF7E052F, 0xDB25701C, 0x1B5E51EE, 0xF65324E6, 0x6AFCE36C, 0x0316CC04,
			0x8644213E, 0xB7DC59D0, 0x7965291F, 0xCCD6FD43, 0x41823979, 0x932BCDF6, 0xB657C34D, 0x4EDFD282,
			0x7AE5290C, 0x3CB9536B, 0x851E20FE, 0x9833557E, 0x13ECF0B0, 0xD3FFB372, 0x3F85C5C1, 0x0AEF7ED2
		), array(
			0x7EC90C04, 0x2C6E74B9, 0x9B0E66DF, 0xA6337911, 0xB86A7FFF, 0x1DD358F5, 0x44DD9D44, 0x1731167F,
			0x08FBF1FA, 0xE7F511CC, 0xD2051B00, 0x735ABA00, 0x2AB722D8, 0x386381CB, 0xACF6243A, 0x69BEFD7A,
			0xE6A2E77F, 0xF0C720CD, 0xC4494816, 0xCCF5C180, 0x38851640, 0x15B0A848, 0xE68B18CB, 0x4CAADEFF,
			0x5F480A01, 0x0412B2AA, 0x259814FC, 0x41D0EFE2, 0x4E40B48D, 0x248EB6FB, 0x8DBA1CFE, 0x41A99B02,
			0x1A550A04, 0xBA8F65CB, 0x7251F4E7, 0x95A51725, 0xC106ECD7, 0x97A5980A, 0xC539B9AA, 0x4D79FE6A,
			0xF2F3F763, 0x68AF8040, 0xED0C9E56, 0x11B4958B, 0xE1EB5A88, 0x8709E6B0, 0xD7E07156, 0x4E29FEA7,
			0x6366E52D, 0x02D1C000, 0xC4AC8E05, 0x9377F571, 0x0C05372A, 0x578535F2, 0x2261BE02, 0xD642A0C9,
			0xDF13A280, 0x74B55BD2, 0x682199C0, 0xD421E5EC, 0x53FB3CE8, 0xC8ADEDB3, 0x28A87FC9, 0x3D959981,
			0x5C1FF900, 0xFE38D399, 0x0C4EFF0B, 0x062407EA, 0xAA2F4FB1, 0x4FB96976, 0x90C79505, 0xB0A8A774,
			0xEF55A1FF, 0xE59CA2C2, 0xA6B62D27, 0xE66A4263, 0xDF65001F, 0x0EC50966, 0xDFDD55BC, 0x29DE0655,
			0x911E739A, 0x17AF8975, 0x32C7911C, 0x89F89468, 0x0D01E980, 0x524755F4, 0x03B63CC9, 0x0CC844B2,
			0xBCF3F0AA, 0x87AC36E9, 0xE53A7426, 0x01B3D82B, 0x1A9E7449, 0x64EE2D7E, 0xCDDBB1DA, 0x01C94910,
			0xB868BF80, 0x0D26F3FD, 0x9342EDE7, 0x04A5C284, 0x636737B6, 0x50F5B616, 0xF24766E3, 0x8ECA36C1,
			0x136E05DB, 0xFEF18391, 0xFB887A37, 0xD6E7F7D4, 0xC7FB7DC9, 0x3063FCDF, 0xB6F589DE, 0xEC2941DA,
			0x26E46695, 0xB7566419, 0xF654EFC5, 0xD08D58B7, 0x48925401, 0xC1BACB7F, 0xE5FF550F, 0xB6083049,
			0x5BB5D0E8, 0x87D72E5A, 0xAB6A6EE1, 0x223A66CE, 0xC62BF3CD, 0x9E0885F9, 0x68CB3E47, 0x086C010F,
			0xA21DE820, 0xD18B69DE, 0xF3F65777, 0xFA02C3F6, 0x407EDAC3, 0xCBB3D550, 0x1793084D, 0xB0D70EBA,
			0x0AB378D5, 0xD951FB0C, 0xDED7DA56, 0x4124BBE4, 0x94CA0B56, 0x0F5755D1, 0xE0E1E56E, 0x6184B5BE,
			0x580A249F, 0x94F74BC0, 0xE327888E, 0x9F7B5561, 0xC3DC0280, 0x05687715, 0x646C6BD7, 0x44904DB3,
			0x66B4F0A3, 0xC0F1648A, 0x697ED5AF, 0x49E92FF6, 0x309E374F, 0x2CB6356A, 0x85808573, 0x4991F840,
			0x76F0AE02, 0x083BE84D, 0x28421C9A, 0x44489406, 0x736E4CB8, 0xC1092910, 0x8BC95FC6, 0x7D869CF4,
			0x134F616F, 0x2E77118D, 0xB31B2BE1, 0xAA90B472, 0x3CA5D717, 0x7D161BBA, 0x9CAD9010, 0xAF462BA2,
			0x9FE459D2, 0x45D34559, 0xD9F2DA13, 0xDBC65487, 0xF3E4F94E, 0x176D486F, 0x097C13EA, 0x631DA5C7,
			0x445F7382, 0x175683F4, 0xCDC66A97, 0x70BE0288, 0xB3CDCF72, 0x6E5DD2F3, 0x20936079, 0x459B80A5,
			0xBE60E2DB, 0xA9C23101, 0xEBA5315C, 0x224E42F2, 0x1C5C1572, 0xF6721B2C, 0x1AD2FFF3, 0x8C25404E,
			0x324ED72F, 0x4067B7FD, 0x0523138E, 0x5CA3BC78, 0xDC0FD66E, 0x75922283, 0x784D6B17, 0x58EBB16E,
			0x44094F85, 0x3F481D87, 0xFCFEAE7B, 0x77B5FF76, 0x8C2302BF, 0xAAF47556, 0x5F46B02A, 0x2B092801,
			0x3D38F5F7, 0x0CA81F36, 0x52AF4A8A, 0x66D5E7C0, 0xDF3B0874, 0x95055110, 0x1B5AD7A8, 0xF61ED5AD,
			0x6CF6E479, 0x20758184, 0xD0CEFA65, 0x88F7BE58, 0x4A046826, 0x0FF6F8F3, 0xA09C7F70, 0x5346ABA0,
			0x5CE96C28, 0xE176EDA3, 0x6BAC307F, 0x376829D2, 0x85360FA9, 0x17E3FE2A, 0x24B79767, 0xF5A96B20,
			0xD6CD2595, 0x68FF1EBF, 0x7555442C, 0xF19F06BE, 0xF9E0659A, 0xEEB9491D, 0x34010718, 0xBB30CAB8,
			0xE822FE15, 0x88570983, 0x750E6249, 0xDA627E55, 0x5E76FFA8, 0xB1534546, 0x6D47DE08, 0xEFE9E7D4
		), array(
			0xF6FA8F9D, 0x2CAC6CE1, 0x4CA34867, 0xE2337F7C, 0x95DB08E7, 0x016843B4, 0xECED5CBC, 0x325553AC,
			0xBF9F0960, 0xDFA1E2ED, 0x83F0579D, 0x63ED86B9, 0x1AB6A6B8, 0xDE5EBE39, 0xF38FF732, 0x8989B138,
			0x33F14961, 0xC01937BD, 0xF506C6DA, 0xE4625E7E, 0xA308EA99, 0x4E23E33C, 0x79CBD7CC, 0x48A14367,
			0xA3149619, 0xFEC94BD5, 0xA114174A, 0xEAA01866, 0xA084DB2D, 0x09A8486F, 0xA888614A, 0x2900AF98,
			0x01665991, 0xE1992863, 0xC8F30C60, 0x2E78EF3C, 0xD0D51932, 0xCF0FEC14, 0xF7CA07D2, 0xD0A82072,
			0xFD41197E, 0x9305A6B0, 0xE86BE3DA, 0x74BED3CD, 0x372DA53C, 0x4C7F4448, 0xDAB5D440, 0x6DBA0EC3,
			0x083919A7, 0x9FBAEED9, 0x49DBCFB0, 0x4E670C53, 0x5C3D9C01, 0x64BDB941, 0x2C0E636A, 0xBA7DD9CD,
			0xEA6F7388, 0xE70BC762, 0x35F29ADB, 0x5C4CDD8D, 0xF0D48D8C, 0xB88153E2, 0x08A19866, 0x1AE2EAC8,
			0x284CAF89, 0xAA928223, 0x9334BE53, 0x3B3A21BF, 0x16434BE3, 0x9AEA3906, 0xEFE8C36E, 0xF890CDD9,
			0x80226DAE, 0xC340A4A3, 0xDF7E9C09, 0xA694A807, 0x5B7C5ECC, 0x221DB3A6, 0x9A69A02F, 0x68818A54,
			0xCEB2296F, 0x53C0843A, 0xFE893655, 0x25BFE68A, 0xB4628ABC, 0xCF222EBF, 0x25AC6F48, 0xA9A99387,
			0x53BDDB65, 0xE76FFBE7, 0xE967FD78, 0x0BA93563, 0x8E342BC1, 0xE8A11BE9, 0x4980740D, 0xC8087DFC,
			0x8DE4BF99, 0xA11101A0, 0x7FD37975, 0xDA5A26C0, 0xE81F994F, 0x9528CD89, 0xFD339FED, 0xB87834BF,
			0x5F04456D, 0x22258698, 0xC9C4C83B, 0x2DC156BE, 0x4F628DAA, 0x57F55EC5, 0xE2220ABE, 0xD2916EBF,
			0x4EC75B95, 0x24F2C3C0, 0x42D15D99, 0xCD0D7FA0, 0x7B6E27FF, 0xA8DC8AF0, 0x7345C106, 0xF41E232F,
			0x35162386, 0xE6EA8926, 0x3333B094, 0x157EC6F2, 0x372B74AF, 0x692573E4, 0xE9A9D848, 0xF3160289,
			0x3A62EF1D, 0xA787E238, 0xF3A5F676, 0x74364853, 0x20951063, 0x4576698D, 0xB6FAD407, 0x592AF950,
			0x36F73523, 0x4CFB6E87, 0x7DA4CEC0, 0x6C152DAA, 0xCB0396A8, 0xC50DFE5D, 0xFCD707AB, 0x0921C42F,
			0x89DFF0BB, 0x5FE2BE78, 0x448F4F33, 0x754613C9, 0x2B05D08D, 0x48B9D585, 0xDC049441, 0xC8098F9B,
			0x7DEDE786, 0xC39A3373, 0x42410005, 0x6A091751, 0x0EF3C8A6, 0x890072D6, 0x28207682, 0xA9A9F7BE,
			0xBF32679D, 0xD45B5B75, 0xB353FD00, 0xCBB0E358, 0x830F220A, 0x1F8FB214, 0xD372CF08, 0xCC3C4A13,
			0x8CF63166, 0x061C87BE, 0x88C98F88, 0x6062E397, 0x47CF8E7A, 0xB6C85283, 0x3CC2ACFB, 0x3FC06976,
			0x4E8F0252, 0x64D8314D, 0xDA3870E3, 0x1E665459, 0xC10908F0, 0x513021A5, 0x6C5B68B7, 0x822F8AA0,
			0x3007CD3E, 0x74719EEF, 0xDC872681, 0x073340D4, 0x7E432FD9, 0x0C5EC241, 0x8809286C, 0xF592D891,
			0x08A930F6, 0x957EF305, 0xB7FBFFBD, 0xC266E96F, 0x6FE4AC98, 0xB173ECC0, 0xBC60B42A, 0x953498DA,
			0xFBA1AE12, 0x2D4BD736, 0x0F25FAAB, 0xA4F3FCEB, 0xE2969123, 0x257F0C3D, 0x9348AF49, 0x361400BC,
			0xE8816F4A, 0x3814F200, 0xA3F94043, 0x9C7A54C2, 0xBC704F57, 0xDA41E7F9, 0xC25AD33A, 0x54F4A084,
			0xB17F5505, 0x59357CBE, 0xEDBD15C8, 0x7F97C5AB, 0xBA5AC7B5, 0xB6F6DEAF, 0x3A479C3A, 0x5302DA25,
			0x653D7E6A, 0x54268D49, 0x51A477EA, 0x5017D55B, 0xD7D25D88, 0x44136C76, 0x0404A8C8, 0xB8E5A121,
			0xB81A928A, 0x60ED5869, 0x97C55B96, 0xEAEC991B, 0x29935913, 0x01FDB7F1, 0x088E8DFA, 0x9AB6F6F5,
			0x3B4CBF9F, 0x4A5DE3AB, 0xE6051D35, 0xA0E1D855, 0xD36B4CF1, 0xF544EDEB, 0xB0E93524, 0xBEBB8FBD,
			0xA2D762CF, 0x49C92F54, 0x38B5F331, 0x7128A454, 0x48392905, 0xA65B1DB8, 0x851C97BD, 0xD675CF2F
		), array(
			0x85E04019, 0x332BF567, 0x662DBFFF, 0xCFC65693, 0x2A8D7F6F, 0xAB9BC912, 0xDE6008A1, 0x2028DA1F,
			0x0227BCE7, 0x4D642916, 0x18FAC300, 0x50F18B82, 0x2CB2CB11, 0xB232E75C, 0x4B3695F2, 0xB28707DE,
			0xA05FBCF6, 0xCD4181E9, 0xE150210C, 0xE24EF1BD, 0xB168C381, 0xFDE4E789, 0x5C79B0D8, 0x1E8BFD43,
			0x4D495001, 0x38BE4341, 0x913CEE1D, 0x92A79C3F, 0x089766BE, 0xBAEEADF4, 0x1286BECF, 0xB6EACB19,
			0x2660C200, 0x7565BDE4, 0x64241F7A, 0x8248DCA9, 0xC3B3AD66, 0x28136086, 0x0BD8DFA8, 0x356D1CF2,
			0x107789BE, 0xB3B2E9CE, 0x0502AA8F, 0x0BC0351E, 0x166BF52A, 0xEB12FF82, 0xE3486911, 0xD34D7516,
			0x4E7B3AFF, 0x5F43671B, 0x9CF6E037, 0x4981AC83, 0x334266CE, 0x8C9341B7, 0xD0D854C0, 0xCB3A6C88,
			0x47BC2829, 0x4725BA37, 0xA66AD22B, 0x7AD61F1E, 0x0C5CBAFA, 0x4437F107, 0xB6E79962, 0x42D2D816,
			0x0A961288, 0xE1A5C06E, 0x13749E67, 0x72FC081A, 0xB1D139F7, 0xF9583745, 0xCF19DF58, 0xBEC3F756,
			0xC06EBA30, 0x07211B24, 0x45C28829, 0xC95E317F, 0xBC8EC511, 0x38BC46E9, 0xC6E6FA14, 0xBAE8584A,
			0xAD4EBC46, 0x468F508B, 0x7829435F, 0xF124183B, 0x821DBA9F, 0xAFF60FF4, 0xEA2C4E6D, 0x16E39264,
			0x92544A8B, 0x009B4FC3, 0xABA68CED, 0x9AC96F78, 0x06A5B79A, 0xB2856E6E, 0x1AEC3CA9, 0xBE838688,
			0x0E0804E9, 0x55F1BE56, 0xE7E5363B, 0xB3A1F25D, 0xF7DEBB85, 0x61FE033C, 0x16746233, 0x3C034C28,
			0xDA6D0C74, 0x79AAC56C, 0x3CE4E1AD, 0x51F0C802, 0x98F8F35A, 0x1626A49F, 0xEED82B29, 0x1D382FE3,
			0x0C4FB99A, 0xBB325778, 0x3EC6D97B, 0x6E77A6A9, 0xCB658B5C, 0xD45230C7, 0x2BD1408B, 0x60C03EB7,
			0xB9068D78, 0xA33754F4, 0xF430C87D, 0xC8A71302, 0xB96D8C32, 0xEBD4E7BE, 0xBE8B9D2D, 0x7979FB06,
			0xE7225308, 0x8B75CF77, 0x11EF8DA4, 0xE083C858, 0x8D6B786F, 0x5A6317A6, 0xFA5CF7A0, 0x5DDA0033,
			0xF28EBFB0, 0xF5B9C310, 0xA0EAC280, 0x08B9767A, 0xA3D9D2B0, 0x79D34217, 0x021A718D, 0x9AC6336A,
			0x2711FD60, 0x438050E3, 0x069908A8, 0x3D7FEDC4, 0x826D2BEF, 0x4EEB8476, 0x488DCF25, 0x36C9D566,
			0x28E74E41, 0xC2610ACA, 0x3D49A9CF, 0xBAE3B9DF, 0xB65F8DE6, 0x92AEAF64, 0x3AC7D5E6, 0x9EA80509,
			0xF22B017D, 0xA4173F70, 0xDD1E16C3, 0x15E0D7F9, 0x50B1B887, 0x2B9F4FD5, 0x625ABA82, 0x6A017962,
			0x2EC01B9C, 0x15488AA9, 0xD716E740, 0x40055A2C, 0x93D29A22, 0xE32DBF9A, 0x058745B9, 0x3453DC1E,
			0xD699296E, 0x496CFF6F, 0x1C9F4986, 0xDFE2ED07, 0xB87242D1, 0x19DE7EAE, 0x053E561A, 0x15AD6F8C,
			0x66626C1C, 0x7154C24C, 0xEA082B2A, 0x93EB2939, 0x17DCB0F0, 0x58D4F2AE, 0x9EA294FB, 0x52CF564C,
			0x9883FE66, 0x2EC40581, 0x763953C3, 0x01D6692E, 0xD3A0C108, 0xA1E7160E, 0xE4F2DFA6, 0x693ED285,
			0x74904698, 0x4C2B0EDD, 0x4F757656, 0x5D393378, 0xA132234F, 0x3D321C5D, 0xC3F5E194, 0x4B269301,
			0xC79F022F, 0x3C997E7E, 0x5E4F9504, 0x3FFAFBBD, 0x76F7AD0E, 0x296693F4, 0x3D1FCE6F, 0xC61E45BE,
			0xD3B5AB34, 0xF72BF9B7, 0x1B0434C0, 0x4E72B567, 0x5592A33D, 0xB5229301, 0xCFD2A87F, 0x60AEB767,
			0x1814386B, 0x30BCC33D, 0x38A0C07D, 0xFD1606F2, 0xC363519B, 0x589DD390, 0x5479F8E6, 0x1CB8D647,
			0x97FD61A9, 0xEA7759F4, 0x2D57539D, 0x569A58CF, 0xE84E63AD, 0x462E1B78, 0x6580F87E, 0xF3817914,
			0x91DA55F4, 0x40A230F3, 0xD1988F35, 0xB6E318D2, 0x3FFA50BC, 0x3D40F021, 0xC3C0BDAE, 0x4958C24C,
			0x518F36B2, 0x84B1D370, 0x0FEDCE83, 0x878DDADA, 0xF2A279C7, 0x94E01BE8, 0x90716F4B, 0x954B8AA3
		), array(
			0xE216300D, 0xBBDDFFFC, 0xA7EBDABD, 0x35648095, 0x7789F8B7, 0xE6C1121B, 0x0E241600, 0x052CE8B5,
			0x11A9CFB0, 0xE5952F11, 0xECE7990A, 0x9386D174, 0x2A42931C, 0x76E38111, 0xB12DEF3A, 0x37DDDDFC,
			0xDE9ADEB1, 0x0A0CC32C, 0xBE197029, 0x84A00940, 0xBB243A0F, 0xB4D137CF, 0xB44E79F0, 0x049EEDFD,
			0x0B15A15D, 0x480D3168, 0x8BBBDE5A, 0x669DED42, 0xC7ECE831, 0x3F8F95E7, 0x72DF191B, 0x7580330D,
			0x94074251, 0x5C7DCDFA, 0xABBE6D63, 0xAA402164, 0xB301D40A, 0x02E7D1CA, 0x53571DAE, 0x7A3182A2,
			0x12A8DDEC, 0xFDAA335D, 0x176F43E8, 0x71FB46D4, 0x38129022, 0xCE949AD4, 0xB84769AD, 0x965BD862,
			0x82F3D055, 0x66FB9767, 0x15B80B4E, 0x1D5B47A0, 0x4CFDE06F, 0xC28EC4B8, 0x57E8726E, 0x647A78FC,
			0x99865D44, 0x608BD593, 0x6C200E03, 0x39DC5FF6, 0x5D0B00A3, 0xAE63AFF2, 0x7E8BD632, 0x70108C0C,
			0xBBD35049, 0x2998DF04, 0x980CF42A, 0x9B6DF491, 0x9E7EDD53, 0x06918548, 0x58CB7E07, 0x3B74EF2E,
			0x522FFFB1, 0xD24708CC, 0x1C7E27CD, 0xA4EB215B, 0x3CF1D2E2, 0x19B47A38, 0x424F7618, 0x35856039,
			0x9D17DEE7, 0x27EB35E6, 0xC9AFF67B, 0x36BAF5B8, 0x09C467CD, 0xC18910B1, 0xE11DBF7B, 0x06CD1AF8,
			0x7170C608, 0x2D5E3354, 0xD4DE495A, 0x64C6D006, 0xBCC0C62C, 0x3DD00DB3, 0x708F8F34, 0x77D51B42,
			0x264F620F, 0x24B8D2BF, 0x15C1B79E, 0x46A52564, 0xF8D7E54E, 0x3E378160, 0x7895CDA5, 0x859C15A5,
			0xE6459788, 0xC37BC75F, 0xDB07BA0C, 0x0676A3AB, 0x7F229B1E, 0x31842E7B, 0x24259FD7, 0xF8BEF472,
			0x835FFCB8, 0x6DF4C1F2, 0x96F5B195, 0xFD0AF0FC, 0xB0FE134C, 0xE2506D3D, 0x4F9B12EA, 0xF215F225,
			0xA223736F, 0x9FB4C428, 0x25D04979, 0x34C713F8, 0xC4618187, 0xEA7A6E98, 0x7CD16EFC, 0x1436876C,
			0xF1544107, 0xBEDEEE14, 0x56E9AF27, 0xA04AA441, 0x3CF7C899, 0x92ECBAE6, 0xDD67016D, 0x151682EB,
			0xA842EEDF, 0xFDBA60B4, 0xF1907B75, 0x20E3030F, 0x24D8C29E, 0xE139673B, 0xEFA63FB8, 0x71873054,
			0xB6F2CF3B, 0x9F326442, 0xCB15A4CC, 0xB01A4504, 0xF1E47D8D, 0x844A1BE5, 0xBAE7DFDC, 0x42CBDA70,
			0xCD7DAE0A, 0x57E85B7A, 0xD53F5AF6, 0x20CF4D8C, 0xCEA4D428, 0x79D130A4, 0x3486EBFB, 0x33D3CDDC,
			0x77853B53, 0x37EFFCB5, 0xC5068778, 0xE580B3E6, 0x4E68B8F4, 0xC5C8B37E, 0x0D809EA2, 0x398FEB7C,
			0x132A4F94, 0x43B7950E, 0x2FEE7D1C, 0x223613BD, 0xDD06CAA2, 0x37DF932B, 0xC4248289, 0xACF3EBC3,
			0x5715F6B7, 0xEF3478DD, 0xF267616F, 0xC148CBE4, 0x9052815E, 0x5E410FAB, 0xB48A2465, 0x2EDA7FA4,
			0xE87B40E4, 0xE98EA084, 0x5889E9E1, 0xEFD390FC, 0xDD07D35B, 0xDB485694, 0x38D7E5B2, 0x57720101,
			0x730EDEBC, 0x5B643113, 0x94917E4F, 0x503C2FBA, 0x646F1282, 0x7523D24A, 0xE0779695, 0xF9C17A8F,
			0x7A5B2121, 0xD187B896, 0x29263A4D, 0xBA510CDF, 0x81F47C9F, 0xAD1163ED, 0xEA7B5965, 0x1A00726E,
			0x11403092, 0x00DA6D77, 0x4A0CDD61, 0xAD1F4603, 0x605BDFB0, 0x9EEDC364, 0x22EBE6A8, 0xCEE7D28A,
			0xA0E736A0, 0x5564A6B9, 0x10853209, 0xC7EB8F37, 0x2DE705CA, 0x8951570F, 0xDF09822B, 0xBD691A6C,
			0xAA12E4F2, 0x87451C0F, 0xE0F6A27A, 0x3ADA4819, 0x4CF1764F, 0x0D771C2B, 0x67CDB156, 0x350D8384,
			0x5938FA0F, 0x42399EF3, 0x36997B07, 0x0E84093D, 0x4AA93E61, 0x8360D87B, 0x1FA98B0C, 0x1149382C,
			0xE97625A5, 0x0614D1B7, 0x0E25244B, 0x0C768347, 0x589E8D82, 0x0D2059D1, 0xA466BB1E, 0xF8DA0A82,
			0x04F19130, 0xBA6E4EC0, 0x99265164, 0x1EE7230D, 0x50B2AD80, 0xEAEE6801, 0x8DB2A283, 0xEA8BF59E
		)
	);
	protected static function rjsw($word){
		return   self::$rjt[4][$word       & 0xFF]        |
				(self::$rjt[4][$word >>  8 & 0xFF] <<  8) |
				(self::$rjt[4][$word >> 16 & 0xFF] << 16) |
				(self::$rjt[4][$word >> 24 & 0xFF] << 24);
	}
	protected function case128f1($r, $i){
		$n = $this->_mkey[$i] + $r;
		$n = parent::uInt32(parent::rotBitsLeft32($n, $this->_rkey[$i]));
		$n = parent::dec2Str($n, 4);
		$f = parent::uInt32(
				((self::$_s1[ord($n[0])] ^ self::$_s2[ord($n[1])]) -
				self::$_s3[ord($n[2])]) + self::$_s4[ord($n[3])]
			);
		return $f;
	}
	protected function case128f2($r, $i){
		$n = $this->_mkey[$i] ^ $r;
		$n = parent::uInt32(parent::rotBitsLeft32($n, $this->_rkey[$i]));
		$n = parent::dec2Str($n, 4);
		$f = parent::uInt32(
				((self::$_s1[ord($n[0])] - self::$_s2[ord($n[1])]) +
				self::$_s3[ord($n[2])]) ^ self::$_s4[ord($n[3])]
			);
		return $f;
	}
	protected function case128f3($r, $i){
		$n = $this->_mkey[$i] - $r;
		$n = parent::uInt32(parent::rotBitsLeft32($n, $this->_rkey[$i]));
		$n = parent::dec2Str($n, 4);
		$f = parent::uInt32(
				((self::$_s1[ord($n[0])] + self::$_s2[ord($n[1])]) ^
				self::$_s3[ord($n[2])]) - self::$_s4[ord($n[3])]
			);
		return $f;
	}

	public static function blocklength($cipher, $bits = null){
		$cipher = strtolower($cipher);
		if(substr($cipher, -3) == 'des' && is_numeric(substr($cipher, 0, -3)))$cipher = 'des';
		switch($cipher){
			case 'xor': 	 return $bits === true ? 8 : 1;
			case 'blowfish': return $bits === true ? 64 : 8;
			case 'twofish':  return $bits === true ? 128 : 16;
			case 'skipjack': return $bits === true ? 64 : 8;
			case 'vigenere': return $bits === true ? 8 : 1;
			case 'enigma':   return $bits === true ? 8 : 1;
			case 'rc2':  	 return $bits === true ? 64 : 8;
			case 'rc4':	     return $bits === true ? 8 : 1;
			case 'des':      return $bits === true ? 64 : 8;
			case 'tripledes':return $bits === true ? 64 : 8;
			case 'arc4':     return $bits === true ? 8 : 1;
			case 'cast128':  return $bits === true ? 128 : 16;
			case 'case256':  return $bits === true ? 256 : 32;
		}
		if(substr($cipher, 0, 8) == 'rijndael')
			return $bits === true ? (int)substr($cipher, 8) : (int)substr($cipher, 8) >> 3;
	}
	public static function keylength($cipher, $bits = null){
		$cipher = strtolower($cipher);
		if(substr($cipher, -3) == 'des' && is_numeric(substr($cipher, 0, -3)))$cipher = 'des';
		if(substr($cipher, 0, 8) == 'rijndael')$cipher = 'rijndael';
		switch($cipher){
			case 'xor': 	 return $bits === true ? 8 : 1;
			case 'blowfish': return $bits === true ? 128 : 16;
			case 'twofish':  return $bits === true ? 64 : 8;
			case 'skipjack': return $bits === true ? 80 : 10;
			case 'vigenere': return $bits === true ? 8 : 1;
			case 'enigma':   return $bits === true ? 8 : 1;
			case 'rc2':	     return $bits === true ? 128 : 16;
			case 'rc4':	     return $bits === true ? 128 : 16;
			case 'des':      return $bits === true ? 64 : 8;
			case 'tripledes':return $bits === true ? 64 : 24;
			case 'arc4':     return $bits === true ? 8 : 1;
			case 'rijndael': return $bits === true ? 32 : 4;
			case 'case128':  return $bits === true ? 128 : 16;
			case 'cast256':  return $bits === true ? 256 : 32;
		}
		$cipher = explode('-', $cipher, 2);
		if(isset($cipher[1]) && is_numeric($cipher[1]))
			return $bits === true ? (int)$cipher[1] : (int)$cipher[1] >> 3;
	}
	private static function keyinitsize($cipher, $key = null, $options = 0, $size = null){
		$cipher = strtolower($cipher);
		if($key === null)return null;
		if($size === null)
			$size = self::keylength($cipher);
		if($options & self::KEYPAD)
			switch($cipher){
				case 'twofish':
				case 'rijndael':
					$key = self::zeropad($key, $size);break;
				case 'blowfish':
				case 'skipjack':
				case 'rc2':
				case 'rc4':
				case 'des':
				case 'tripledes':
				case 'cast128':
				case 'cast256':
					return self::zeropad($key, $size);
			}
		elseif($options & self::KEYMIX)
			switch($cipher){
				case 'twofish':
				case 'rijndael':
					$key = self::mixpad($key, $size);break;
				case 'blowfish':
				case 'skipjack':
				case 'rc2':
				case 'rc4':
				case 'des':
				case 'tripledes':
				case 'cast128':
				case 'cast256':
					return self::mixpad($key, $size);
			}
		$lk = strlen($key);
		switch($cipher){
			case 'xor':
			case 'blowfish':
			case 'vigenere':
			case 'arc4':
				return $key;
			case 'twofish':
				if($lk < 8)return '';
				if($lk < 16)return substr($key, 0, 8);
				if($lk < 24)return substr($key, 0, 16);
				return substr($key, 0, 32);
			case 'rijndael':
				if($lk < 16)return self::mixpad($key, 16);
				if($lk < 20)return substr($key, 0, 16);
				if($lk < 24)return substr($key, 0, 20);
				if($lk < 28)return substr($key, 0, 24);
				if($lk < 32)return substr($key, 0, 28);
				return substr($key, 0, 32);
			case 'cast128':
				if($lk < 5)return $key . str_repeat("\0", 5 - $lk);
				return substr($key, 0, 16);
			break;
			case 'cast256':
				if($lk < 16)return $key . str_repeat("\0", 16 - $lk);
				if($lk % 2 == 1)$key .= "\0";
				return substr($key, 32);
			break;
			case 'enigma':
				return $lk < 16 ? $key . str_repeat("\0", 16 - $lk) : $key;
			case 'skipjack':
			case 'rc2':
			case 'rc4':
			case 'des':
			case 'tripledes':
				return $lk < $size ? self::mixpad($key, $size) : $key;
		}
	}
	private static function keyinstall($cipher, $key){
		switch($cipher){
			case 'xor':
			case 'arc4':
			if($key === null)$key = "\0";
				return array($key);
			case 'blowfish':
			if($key === null)return array(self::$bfp, self::$bfs);
				$p  = self::$bfp;
				$sb = self::$bfs;
				$key = array_values(unpack('C*', $key));
				$kl = count($key);
				for($j = $i = 0; $i < 18; ++$i) {
					for($data = $k = 0; $k < 4; ++$k) {
						$data = ($data << 8) | $key[$j];
						if(++$j >= $kl)
							$j = 0;
					}
					$p[$i] ^= $data;
				}
				$data = "\0\0\0\0\0\0\0\0";
				for($i = 0; $i < 18; $i += 2) {
					list($l, $r) = array_values(unpack('N*', $data = self::blockencrypt($cipher, $data, array($p, $sb))));
					$p[$i	] = $l;
					$p[$i + 1] = $r;
				}
				for($i = 0; $i < 4; ++$i) {
					for($j = 0; $j < 256; $j += 2) {
						list($l, $r) = array_values(unpack('N*', $data = self::blockencrypt($cipher, $data, array($p, $sb))));
						$sb[$i][$j	] = $l;
						$sb[$i][$j + 1] = $r;
					}
				}
			return array($p, $sb);
			case 'twofish':
			if($key === null)$key = '';
				$le = unpack('V*', $key);
				$key = unpack('C*', $key);
				list($q0, $q1) = self::$tfq;
				list($m0, $m1, $m2, $m3) = self::$tfm;
				$k = $s0 = $s1 = $s2 = $s3 = array();
				switch(count($key)) {
					case 0:
						for($i = 0, $j = 1; $i < 40; $i += 2, $j += 2) {
							$a =$m0[$i] ^ $m1[$i] ^
								$m2[$i] ^ $m3[$i];
							$b =$m0[$j] ^ $m1[$j] ^
								$m2[$j] ^ $m3[$j];
							$b = ($b << 8) | ($b >> 24 & 0xff);
							$a += $b;
							$k[] = $a;
							$a += $b;
							$k[] = ($a << 9 | $a >> 23 & 0x1ff);
						}
						for($i = 0; $i < 256; ++$i) {
							$s0[$i] = $m0[$i];
							$s1[$i] = $m1[$i];
							$s2[$i] = $m2[$i];
							$s3[$i] = $m3[$i];
						}
					break;
					case 8:
						list($r3, $r2, $r1, $r0) = xnmath::mdsrem($le[1], $le[2]);
						for($i = 0, $j = 1; $i < 40; $i += 2, $j += 2) {
							$a =$m0[$q0[$i] ^ $key[1]] ^
								$m1[$q0[$i] ^ $key[2]] ^
								$m2[$q1[$i] ^ $key[3]] ^
								$m3[$q1[$i] ^ $key[4]];
							$b =$m0[$q0[$j] ^ $key[5]] ^
								$m1[$q0[$j] ^ $key[6]] ^
								$m2[$q1[$j] ^ $key[7]] ^
								$m3[$q1[$j] ^ $key[8]];
							$b = ($b << 8) | ($b >> 24 & 0xff);
							$a += $b;
							$k[] = $a;
							$a += $b;
							$k[] = ($a << 9 | $a >> 23 & 0x1ff);
						}
						for($i = 0; $i < 256; ++$i) {
							$s0[$i] = $m0[$q0[$i] ^ $r0];
							$s1[$i] = $m1[$q0[$i] ^ $r1];
							$s2[$i] = $m2[$q1[$i] ^ $r2];
							$s3[$i] = $m3[$q1[$i] ^ $r3];
						}
					break;
					case 16:
						list($r7, $r6, $r5, $r4) = xnmath::mdsrem($le[1], $le[2]);
						list($r3, $r2, $r1, $r0) = xnmath::mdsrem($le[3], $le[4]);
						for($i = 0, $j = 1; $i < 40; $i += 2, $j += 2) {
							$a =$m0[$q0[$q0[$i] ^ $key[9 ]] ^ $key[1]] ^
								$m1[$q0[$q1[$i] ^ $key[10]] ^ $key[2]] ^
								$m2[$q1[$q0[$i] ^ $key[11]] ^ $key[3]] ^
								$m3[$q1[$q1[$i] ^ $key[12]] ^ $key[4]];
							$b =$m0[$q0[$q0[$j] ^ $key[13]] ^ $key[5]] ^
								$m1[$q0[$q1[$j] ^ $key[14]] ^ $key[6]] ^
								$m2[$q1[$q0[$j] ^ $key[15]] ^ $key[7]] ^
								$m3[$q1[$q1[$j] ^ $key[16]] ^ $key[8]];
							$b = ($b << 8) | ($b >> 24 & 0xff);
							$a += $b;
							$k[] = $a;
							$a += $b;
							$k[] = ($a << 9 | $a >> 23 & 0x1ff);
						}
						for($i = 0; $i < 256; ++$i) {
							$s0[$i] = $m0[$q0[$q0[$i] ^ $r4] ^ $r0];
							$s1[$i] = $m1[$q0[$q1[$i] ^ $r5] ^ $r1];
							$s2[$i] = $m2[$q1[$q0[$i] ^ $r6] ^ $r2];
							$s3[$i] = $m3[$q1[$q1[$i] ^ $r7] ^ $r3];
						}
					break;
					case 24:
						list($rb, $ra, $r9, $r8) = xnmath::mdsrem($le[1], $le[2]);
						list($r7, $r6, $r5, $r4) = xnmath::mdsrem($le[3], $le[4]);
						list($r3, $r2, $r1, $r0) = xnmath::mdsrem($le[5], $le[6]);
						for($i = 0, $j = 1; $i < 40; $i+= 2, $j+= 2) {
							$a =$m0[$q0[$q0[$q1[$i] ^ $key[17]] ^ $key[9 ]] ^ $key[1]] ^
								$m1[$q0[$q1[$q1[$i] ^ $key[18]] ^ $key[10]] ^ $key[2]] ^
								$m2[$q1[$q0[$q0[$i] ^ $key[19]] ^ $key[11]] ^ $key[3]] ^
								$m3[$q1[$q1[$q0[$i] ^ $key[20]] ^ $key[12]] ^ $key[4]];
							$b =$m0[$q0[$q0[$q1[$j] ^ $key[21]] ^ $key[13]] ^ $key[5]] ^
								$m1[$q0[$q1[$q1[$j] ^ $key[22]] ^ $key[14]] ^ $key[6]] ^
								$m2[$q1[$q0[$q0[$j] ^ $key[23]] ^ $key[15]] ^ $key[7]] ^
								$m3[$q1[$q1[$q0[$j] ^ $key[24]] ^ $key[16]] ^ $key[8]];
							$b = ($b << 8) | ($b >> 24 & 0xff);
							$a += $b;
							$k[] = $a;
							$a += $b;
							$k[] = ($a << 9 | $a >> 23 & 0x1ff);
						}
						for($i = 0; $i < 256; ++$i) {
							$s0[$i] = $m0[$q0[$q0[$q1[$i] ^ $r8] ^ $r4] ^ $r0];
							$s1[$i] = $m1[$q0[$q1[$q1[$i] ^ $r9] ^ $r5] ^ $r1];
							$s2[$i] = $m2[$q1[$q0[$q0[$i] ^ $ra] ^ $r6] ^ $r2];
							$s3[$i] = $m3[$q1[$q1[$q0[$i] ^ $rb] ^ $r7] ^ $r3];
						}
				break;
				case 32:
					list($rf, $re, $rd, $rc) = xnmath::mdsrem($le[1], $le[2]);
					list($rb, $ra, $r9, $r8) = xnmath::mdsrem($le[3], $le[4]);
					list($r7, $r6, $r5, $r4) = xnmath::mdsrem($le[5], $le[6]);
					list($r3, $r2, $r1, $r0) = xnmath::mdsrem($le[7], $le[8]);
					for($i = 0, $j = 1; $i < 40; $i+= 2, $j+= 2) {
						$a =$m0[$q0[$q0[$q1[$q1[$i] ^ $key[25]] ^ $key[17]] ^ $key[9 ]] ^ $key[1]] ^
							$m1[$q0[$q1[$q1[$q0[$i] ^ $key[26]] ^ $key[18]] ^ $key[10]] ^ $key[2]] ^
							$m2[$q1[$q0[$q0[$q0[$i] ^ $key[27]] ^ $key[19]] ^ $key[11]] ^ $key[3]] ^
							$m3[$q1[$q1[$q0[$q1[$i] ^ $key[28]] ^ $key[20]] ^ $key[12]] ^ $key[4]];
						$b =$m0[$q0[$q0[$q1[$q1[$j] ^ $key[29]] ^ $key[21]] ^ $key[13]] ^ $key[5]] ^
							$m1[$q0[$q1[$q1[$q0[$j] ^ $key[30]] ^ $key[22]] ^ $key[14]] ^ $key[6]] ^
							$m2[$q1[$q0[$q0[$q0[$j] ^ $key[31]] ^ $key[23]] ^ $key[15]] ^ $key[7]] ^
							$m3[$q1[$q1[$q0[$q1[$j] ^ $key[32]] ^ $key[24]] ^ $key[16]] ^ $key[8]];
						$b = ($b << 8) | ($b >> 24 & 0xff);
						$a += $b;
						$k[] = $a;
						$a += $b;
						$k[] = ($a << 9 | $a >> 23 & 0x1ff);
					}
					for($i = 0; $i < 256; ++$i) {
						$s0[$i] = $m0[$q0[$q0[$q1[$q1[$i] ^ $rc] ^ $r8] ^ $r4] ^ $r0];
						$s1[$i] = $m1[$q0[$q1[$q1[$q0[$i] ^ $rd] ^ $r9] ^ $r5] ^ $r1];
						$s2[$i] = $m2[$q1[$q0[$q0[$q0[$i] ^ $re] ^ $ra] ^ $r6] ^ $r2];
						$s3[$i] = $m3[$q1[$q1[$q0[$q1[$i] ^ $rf] ^ $rb] ^ $r7] ^ $r3];
					}
			}
			return array($s0, $s1, $s2, $s3, $k);
			case 'skipjack':
			if($key === null)$key = "\0\0\0\0\0\0\0\0";
				return array(str_repeat($key, 16));
			case 'vigenere':
			if($key === null)$key = 'A';
				return array(xnstring::getinrange(strtoupper($key), xnstring::UPPER_RANGE));
			case 'enigma':
			if($key === null)$key = "\0\0\0\0\0\0\0\0";
				$deck = $t1 = array();
				$t3 = $t2 = array_fill(0, 256, 0);
				$lk = strlen($key);
				$seed = 123;
				for($i = 0; $i < 13; ++$i)
					$seed = ($seed & 0xffffffff) * ord($key[$i]) + $i;
				for($i = 0; $i < 256; ++$i){
					$t1[] = $i;
					$deck[] = $i;
				}
				for($i = 0; $i < 256; ++$i){
					$seed = (5 * ($seed & 0xffffffff) + ord($key[$i % 13])) & 0xffffffff;
					$rand = $seed % 65521;
					$k = 0xff - $i;
					$ic = ($rand & 0377) % ($k + 1);
					$rand = $rand >> 8;
					$tmp = $t1[$k];
					$t1[$k] = $t1[$ic];
					$t1[$ic] = $tmp;
					if($t3[$k] != 0)
						continue;
					$ic = ($rand & 0377) % $k;
					while($t3[$ic] != 0)
						$ic = ($ic + 1) % $k;
					$t3[$k] = $ic;
					$t3[$ic] = $k;
				}
				for($i = 0; $i < 256; ++$i)
					$t2[$t1[$i] & 0377] = $i;
				return array(
					array_map(function($x){return $x & 0xff;}, $t1),
					array_map(function($x){return $x & 0xff;}, $t2),
					array_map(function($x){return $x & 0xff;}, $t3),
					array_map(function($x){return $x & 0xff;}, $deck)
				);
			case 'rc2':
			if($key === null)$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
				$t = strlen($key);
				$l = array_values(unpack('C*', $key));
				for($i = $t; $i < 128; ++$i)
					$l[$i] = self::$rc2t[$l[$i - 1] + $l[$i - $t]];
				$i = 112;
				$l[$i] = self::$rc2t[$l[$i] & 0xff];
				while(--$i >= 0)
					$l[$i] = self::$rc2t[$l[$i + 1] ^ $l[$i + 16]];
				$l[0] = self::$rc2invt[$l[0]];
				array_unshift($l, 'C*');
				$key = unpack('Ca/Cb/v*', call_user_func_array('pack', $l));
				array_unshift($key, self::$rc2t[$key['a']] | ($key['b'] << 8));
				unset($key['a'], $key['b']);
				return array($key);
			case 'rc4':
			if($key === null)$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
				$lk = strlen($key);
				$res = range(0, 255);
				for($i = $j = 0; $i < 256; ++$i) {
					$j = ($j + $res[$i] + ord($key[$i % $lk])) & 0xff;
					swap($res[$i], $res[$j]);
				}
				return $res;
			case 'tripledes':
			if($key === null)$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
				return array(
					self::keyinstall('des', substr($key, 0, 8)),
					self::keyinstall('des', substr($key, 8, 8)),
					self::keyinstall('des', substr($key, 16, 8))
				);
		}
		if(substr($cipher, -3) == 'des'){
			if($key === null)$key = "\0\0\0\0\0\0\0\0";
			$rounds = substr($cipher, 0, -3);
			$rounds = $rounds === '' ? 1 : (int)$rounds;
			$rounds = $rounds <= 0 ? 1 : ($rounds > 3 ? ($rounds - 1) % 3 + 1 : $rounds);
			$res = array();
			$shuffle = array_map(function($x){
				return strtr(str_pad(decbin($x), 8, '0', STR_PAD_LEFT), '01', "\0\xff");
			}, range(0, 255));
			for($round = 0; $round < $rounds; ++$round) {
				$tmp = str_pad(substr($key, $round * 8, 8), 8, "\0");
				$t = unpack('N2', $tmp);
				$l = $t[1];
				$r = $t[2];
				$tmp = ($shuffle[self::$despc1[ $r        & 0xFF]] & "\x80\x80\x80\x80\x80\x80\x80\x00") |
					   ($shuffle[self::$despc1[($r >>  8) & 0xFF]] & "\x40\x40\x40\x40\x40\x40\x40\x00") |
					   ($shuffle[self::$despc1[($r >> 16) & 0xFF]] & "\x20\x20\x20\x20\x20\x20\x20\x00") |
					   ($shuffle[self::$despc1[($r >> 24) & 0xFF]] & "\x10\x10\x10\x10\x10\x10\x10\x00") |
					   ($shuffle[self::$despc1[ $l        & 0xFF]] & "\x08\x08\x08\x08\x08\x08\x08\x00") |
					   ($shuffle[self::$despc1[($l >>  8) & 0xFF]] & "\x04\x04\x04\x04\x04\x04\x04\x00") |
					   ($shuffle[self::$despc1[($l >> 16) & 0xFF]] & "\x02\x02\x02\x02\x02\x02\x02\x00") |
					   ($shuffle[self::$despc1[($l >> 24) & 0xFF]] & "\x01\x01\x01\x01\x01\x01\x01\x00");
				$tmp = unpack('N2', $tmp);
				$c = ( $tmp[1] >> 4) & 0x0FFFFFFF;
				$d = (($tmp[2] >> 4) & 0x0FFFFFF0) | ($tmp[1] & 0x0F);
				$res[$round] = array(
					array(), array_fill(0, 32, 0)
				);
				for($i = 0, $ki = 31; $i < 16; ++$i, $ki-= 2) {
					$c <<= self::$desshs[$i];
					$c = ($c | ($c >> 28)) & 0x0FFFFFFF;
					$d <<= self::$desshs[$i];
					$d = ($d | ($d >> 28)) & 0x0FFFFFFF;
					$cp = self::$despc2c[0][ $c >> 24        ] | self::$despc2c[1][($c >> 16) & 0xFF] |
						  self::$despc2c[2][($c >>  8) & 0xFF] | self::$despc2c[3][ $c        & 0xFF];
					$dp = self::$despc2d[0][ $d >> 24        ] | self::$despc2d[1][($d >> 16) & 0xFF] |
						  self::$despc2d[2][($d >>  8) & 0xFF] | self::$despc2d[3][ $d        & 0xFF];
					$v1 = ( $cp        & 0xFF000000) | (($cp <<  8) & 0x00FF0000) |
						  (($dp >> 16) & 0x0000FF00) | (($dp >>  8) & 0x000000FF);
					$v2 = (($cp <<  8) & 0xFF000000) | (($cp << 16) & 0x00FF0000) |
						  (($dp >>  8) & 0x0000FF00) | ( $dp        & 0x000000FF);
					$res[$round][0][       ] = $v1;
					$res[$round][1][$ki - 1] = $v1;
					$res[$round][0][       ] = $v2;
					$res[$round][1][$ki    ] = $v2;
				}
			}
			$c = 1;
			$en = call_user_func_array('array_merge', array_map(function($x)use(&$c){
					return $x[$c = $c == 0 ? 1 : 0];
				}, $res));
			$c = 0;
			$de = call_user_func_array('array_merge', array_map(function($x)use(&$c){
					return $x[$c = $c == 0 ? 1 : 0];
				}, array_reverse($res)));
			return array($en, $de, $rounds);
		}
		if(substr($cipher, 0, 8) === 'rijndael'){
			if($key === null)$key = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
			if(!is_array(self::$rjt[0])){
				$tables = array(array(), array(), array(), array_map('intval', self::$rjt), self::$rjs);
				foreach($tables[3] as $i) {
					$tables[0][] = (($i << 24) & 0xFF000000) | (($i >>  8) & 0x00FFFFFF);
					$tables[1][] = (($i << 16) & 0xFFFF0000) | (($i >> 16) & 0x0000FFFF);
					$tables[2][] = (($i <<  8) & 0xFFFFFF00) | (($i >> 24) & 0x000000FF);
				}
				$invtables = array(array(), array(), array(), array_map('intval', self::$rjinvt), self::$rjinvs);
				foreach($invtables[3] as $i) {
					$invtables[0][] = (($i << 24) & 0xFF000000) | (($i >>  8) & 0x00FFFFFF);
					$invtables[1][] = (($i << 16) & 0xFFFF0000) | (($i >> 16) & 0x0000FFFF);
					$invtables[2][] = (($i <<  8) & 0xFFFFFF00) | (($i >> 24) & 0x000000FF);
				};
				self::$rjs = self::$rjinvs = null;
				self::$rjt = $tables;
				self::$rjinvt = $invtables;
				unset($tables, $invtables);
			}
			$b = (int)substr($cipher, 8) >> 5;
			$k = strlen($key) >> 2;
			$r = max($k, $b) + 6;
			switch($b) {
				case 4:
				case 5:
				case 6: $tc = array(0, 1, 2, 3); break;
				case 7: $tc = array(0, 1, 2, 4); break;
				case 8: $tc = array(0, 1, 3, 4); break;
				default: return;
			}
			$w = array_values(unpack('N*', $key));
			$l = $b * ($r + 1);
			for($i = $k; $i < $l; ++$i) {
				$tmp = $w[$i - 1];
				if($i % $k == 0) {
					$tmp = (($tmp << 8) & 0xFFFFFF00) | (($tmp >> 24) & 0x000000FF);
					$tmp = self::rjsw($tmp) ^ self::$rjrcon[($i / $k) % 31];
				}elseif($k > 6 && $i % $k == 4)
					$tmp = self::rjsw($tmp);
				$w[$i] = $w[$i - $k] ^ $tmp;
			}
			$tmp = $tw = $tdw = array();
			for($i = $row = $col = 0; $i < $l; ++$i, ++$col) {
				if($col == $b) {
					if($row == 0)
						$tdw[0] = $tw[0];
					else{
						$j = 0;
						while($j < $b) {
							$dw = self::rjsw($tw[$row][$j]);
							$tmp[$j] =  self::$rjinvt[0][$dw >> 24 & 0x000000FF] ^
										self::$rjinvt[1][$dw >> 16 & 0x000000FF] ^
										self::$rjinvt[2][$dw >>  8 & 0x000000FF] ^
										self::$rjinvt[3][$dw       & 0x000000FF];
							++$j;
						}
						$tdw[$row] = $tmp;
					}
					$col = 0;
					++$row;
				}
				$tw[$row][$col] = $w[$i];
			}
			$tdw[$row] = $tw[$row];
			$tdw = array_reverse($tdw);
			$w  = array_pop($tw);
			$dw = array_pop($tdw);
			foreach($tw as $i => $wr)
				foreach($wr as $c => $wc) {
					$w[]  = $wc;
					$dw[] = $tdw[$i][$c];
				}
			return array($b, $r, $tc, $w, $dw);
		}
		new XNError('installkey', 'Undefined cipher name', XNError::WARNING);
		return false;
	}
	private static function blockencrypt($cipher, $in, $key = null){
		$cipher = strtolower($cipher);
		switch($cipher){
			case 'xor':
				return self::xorcrypt($in, $key);
			case 'blowfish':
				$xl = array_value(unpack('N', substr($in, 0, 4)), 1);
				$xr = array_value(unpack('N', substr($in, 4, 4)), 1);
				for($i = 0; $i < 16; ++$i){
					$xl ^= $key[0][$i];
					$xr = self::bff($xl, $key[1]) ^ $xr;
					swap($xl, $xr);
				}
				swap($xl, $xr);
				$xr ^= $key[0][16];
				$xl  = $xl ^ $key[0][17];
			return pack('N2', $xl, $xr);
			case 'twofish':
				list($s0, $s1, $s2, $s3, $k) = $key;
				$in = unpack("V4", $in);
				$r0 = $k[0] ^ $in[1];
				$r1 = $k[1] ^ $in[2];
				$r2 = $k[2] ^ $in[3];
				$r3 = $k[3] ^ $in[4];
				$ki = 7;
				while($ki < 39) {
					$t0 =   $s0[ $r0		& 0xff] ^
							$s1[($r0 >>  8) & 0xff] ^
							$s2[($r0 >> 16) & 0xff] ^
							$s3[($r0 >> 24) & 0xff];
					$t1 =   $s0[($r1 >> 24) & 0xff] ^
							$s1[ $r1		& 0xff] ^
							$s2[($r1 >>  8) & 0xff] ^
							$s3[($r1 >> 16) & 0xff];
					$r2^= $t0 + $t1 + $k[++$ki];
					$r2 = ($r2 >> 1 & 0x7fffffff) | ($r2 << 31);
					$r3 = ((($r3 >> 31) & 1) | ($r3 << 1)) ^ ($t0 + ($t1 << 1) + $k[++$ki]);
					$t0 =   $s0[ $r2		& 0xff] ^
							$s1[($r2 >>  8) & 0xff] ^
							$s2[($r2 >> 16) & 0xff] ^
							$s3[($r2 >> 24) & 0xff];
					$t1 =   $s0[($r3 >> 24) & 0xff] ^
							$s1[ $r3		& 0xff] ^
							$s2[($r3 >>  8) & 0xff] ^
							$s3[($r3 >> 16) & 0xff];
					$r0^= $t0 + $t1 + $k[++$ki];
					$r0 = ($r0 >> 1 & 0x7fffffff) | ($r0 << 31);
					$r1 = ((($r1 >> 31) & 1) | ($r1 << 1)) ^ ($t0 + ($t1 << 1) + $k[++$ki]);
				}
			return pack("V4", $k[4] ^ $r2, $k[5] ^ $r3, $k[6] ^ $r0, $k[7] ^ $r1);
			case 'skipjack':
				for($i = 1; $i <= 32; ++$i){
					$subkey = substr($key, (4 * $i) - 4, 4);
					if($i >= 1 && $i <= 8)
						$in = self::sjruleaenc($in, $subkey, $i);
					if($i >= 9 && $i <= 16)
						$in = self::sjrulebenc($in, $subkey, $i);
					if($i >= 17 && $i <= 24)
						$in = self::sjruleaenc($in, $subkey, $i);
					if($i >= 25 && $i <= 32)
						$in = self::sjrulebenc($in, $subkey, $i);
				}
			return $in;
			case 'vigenere':
				$in = xnstring::getinrange(strtoupper($in), xnstring::UPPER_RANGE);
				$l = strlen($in);
				$key = xnstring::subrep($key, $l);
				for($i = 0; $i < $l; ++$i)
					$in[$i] = self::$vgt[ord($key[$i]) * 26 + ord($in[$i]) - 1755];
				return $in;
			case 'enigma':
				$n1 = $n2 = 0;
				for($j = 0; isset($in[$j]); ++$j){
					$i = ord($in[$j]);
					$p1 = ($i + $n1) & 0377;
					$p3 = ($key[0][$p1] + $n2) & 0377;
					$p2 = ($key[2][$p3] - $n2) & 0377;
					$i = $key[1][$p2] - $n1;
					$in[$j] = chr($i);
					++$n1;
					if($n1 == 256){
						$n1 = 0;
						++$n2;
						if($n2 == 256)
							$n2 = 0;
					}
				}
				return $in;
			break;
			case 'rc2':
				list($r0, $r1, $r2, $r3) = array_values(unpack('v*', $in));
				$limit = 20;
				$actions = array($limit => 44, 44 => 64);
				$j = 0;
				while(true) {
					$r0 = (($r0 + $key[$j++] + ((($r1 ^ $r2) & $r3) ^ $r1)) & 0xFFFF) << 1;
					$r0|= $r0 >> 16;
					$r1 = (($r1 + $key[$j++] + ((($r2 ^ $r3) & $r0) ^ $r2)) & 0xFFFF) << 2;
					$r1|= $r1 >> 16;
					$r2 = (($r2 + $key[$j++] + ((($r3 ^ $r0) & $r1) ^ $r3)) & 0xFFFF) << 3;
					$r2|= $r2 >> 16;
					$r3 = (($r3 + $key[$j++] + ((($r0 ^ $r1) & $r2) ^ $r0)) & 0xFFFF) << 5;
					$r3|= $r3 >> 16;
					if($j === $limit) {
						if($limit === 64)break;
						$r0 += $key[$r3 & 0x3F];
						$r1 += $key[$r0 & 0x3F];
						$r2 += $key[$r1 & 0x3F];
						$r3 += $key[$r2 & 0x3F];
						$limit = $actions[$limit];
					}
				}
				return pack('v4', $r0, $r1, $r2, $r3);
			case 'rc4':
				for($i = $j = $k = 0; isset($in[$k]); ++$k) {
					$i = ($i + 1) & 0xff;
					$ksi = $key[$i];
					$j = ($j + $ksi) & 0xff;
					$ksj = $key[$j];
					$key[$i] = $ksj;
					$key[$j] = $ksi;
					$in[$k] = $in[$k] ^ chr($key[($ksj + $ksi) & 0xff]);
				}
				return $in;
			case 'des':
				$sbox = self::$dess;
				if(is_float($sbox[3][0])){
					$sbox[0] = array_map('intval', $sbox[0]);
					$sbox[1] = array_map('intval', $sbox[1]);
					$sbox[2] = array_map('intval', $sbox[2]);
					$sbox[3] = array_map('intval', $sbox[3]);
					$sbox[4] = array_map('intval', $sbox[4]);
					$sbox[5] = array_map('intval', $sbox[5]);
					$sbox[6] = array_map('intval', $sbox[6]);
					$sbox[7] = array_map('intval', $sbox[7]);
					self::$dess = $sbox;
				}
				$shuffle = array_map(function($x){
					return strtr(str_pad(decbin($x), 8, '0', STR_PAD_LEFT), '01', "\0\xff");
				}, range(0, 255));
				$shuffleinvip = $shuffleip = array();
				for($i = 0; $i < 256; ++$i) {
					$shuffleip[]    = $shuffle[self::$desm[$i]];
					$shuffleinvip[] = $shuffle[self::$desinvm[$i]];
				}
				$box = $key[0];
				$ki  = -1;
				$t = unpack('N2', $in);
				$l = $t[1];
				$r = $t[2];
				$tmp =  ($shuffleip[ $r        & 0xFF] & "\x80\x80\x80\x80\x80\x80\x80\x80") |
						($shuffleip[($r >>  8) & 0xFF] & "\x40\x40\x40\x40\x40\x40\x40\x40") |
						($shuffleip[($r >> 16) & 0xFF] & "\x20\x20\x20\x20\x20\x20\x20\x20") |
						($shuffleip[($r >> 24) & 0xFF] & "\x10\x10\x10\x10\x10\x10\x10\x10") |
						($shuffleip[ $l        & 0xFF] & "\x08\x08\x08\x08\x08\x08\x08\x08") |
						($shuffleip[($l >>  8) & 0xFF] & "\x04\x04\x04\x04\x04\x04\x04\x04") |
						($shuffleip[($l >> 16) & 0xFF] & "\x02\x02\x02\x02\x02\x02\x02\x02") |
						($shuffleip[($l >> 24) & 0xFF] & "\x01\x01\x01\x01\x01\x01\x01\x01");
				$t = unpack('N2', $tmp);
				$l = $t[1];
				$r = $t[2];
				for($round = 0; $round < $key[2]; ++$round) {
					for($i = 0; $i < 16; ++$i) {
						$b1 = (($r >>  3) & 0x1FFFFFFF) ^ ($r << 29) ^ $box[++$ki];
						$b2 = (($r >> 31) & 0x00000001) ^ ($r <<  1) ^ $box[++$ki];
						$t = $sbox[0][($b1 >> 24) & 0x3F] ^ $sbox[1][($b2 >> 24) & 0x3F] ^
							 $sbox[2][($b1 >> 16) & 0x3F] ^ $sbox[3][($b2 >> 16) & 0x3F] ^
							 $sbox[4][($b1 >>  8) & 0x3F] ^ $sbox[5][($b2 >>  8) & 0x3F] ^
							 $sbox[6][ $b1        & 0x3F] ^ $sbox[7][ $b2        & 0x3F] ^ $l;
						$l = $r;
						$r = $t;
					}
					swap($l, $r);
				}
				return ($shuffleinvip[($r >> 24) & 0xFF] & "\x80\x80\x80\x80\x80\x80\x80\x80") |
					   ($shuffleinvip[($l >> 24) & 0xFF] & "\x40\x40\x40\x40\x40\x40\x40\x40") |
					   ($shuffleinvip[($r >> 16) & 0xFF] & "\x20\x20\x20\x20\x20\x20\x20\x20") |
					   ($shuffleinvip[($l >> 16) & 0xFF] & "\x10\x10\x10\x10\x10\x10\x10\x10") |
					   ($shuffleinvip[($r >>  8) & 0xFF] & "\x08\x08\x08\x08\x08\x08\x08\x08") |
					   ($shuffleinvip[($l >>  8) & 0xFF] & "\x04\x04\x04\x04\x04\x04\x04\x04") |
					   ($shuffleinvip[ $r        & 0xFF] & "\x02\x02\x02\x02\x02\x02\x02\x02") |
					   ($shuffleinvip[ $l        & 0xFF] & "\x01\x01\x01\x01\x01\x01\x01\x01");
			case 'tripledes':
				return self::blockencrypt('des', self::blockdecrypt('des', self::blockencrypt('des', $in, $key[0]), $key[1]), $key[2]);
			case 'arc4':
				return $in ^ self::a4prga($key, strlen($in));
			case 'rijndael':
				$state = array();
				$words = unpack('N*', $in);
				$wc = $key[0] - 1;
				foreach($words as $word)
					$state[] = $word ^ $key[3][++$wc];
				$tmp = array();
				for($round = 1; $round < $key[1]; ++$round) {
					$i = 0;
					$j = $key[2][1];
					$k = $key[2][2];
					$l = $key[2][3];
					while($i < $key[0]) {
						$tmp[$i] =  self::$rjt[0][$state[$i] >> 24 & 0x000000FF] ^
									self::$rjt[1][$state[$j] >> 16 & 0x000000FF] ^
									self::$rjt[2][$state[$k] >>  8 & 0x000000FF] ^
									self::$rjt[3][$state[$l]       & 0x000000FF] ^
									$key[3][++$wc];
						++$i;
						$j = ($j + 1) % $key[0];
						$k = ($k + 1) % $key[0];
						$l = ($l + 1) % $key[0];
					}
					$state = $tmp;
				}
				for($i = 0; $i < $key[0]; ++$i)
					$state[$i] = self::$rjt[4][$state[$i]       & 0x000000FF]        |
								(self::$rjt[4][$state[$i] >>  8 & 0x000000FF] <<  8) |
								(self::$rjt[4][$state[$i] >> 16 & 0x000000FF] << 16) |
								(self::$rjt[4][$state[$i] >> 24 & 0x000000FF] << 24);
				$i = 0;
				$j = $key[2][1];
				$k = $key[2][2];
				$l = $key[2][3];
				while($i < $key[0]) {
					$tmp[$i] =  ($state[$i] & 0xFF000000) ^
								($state[$j] & 0x00FF0000) ^
								($state[$k] & 0x0000FF00) ^
								($state[$l] & 0x000000FF) ^
								$key[3][$i];
					++$i;
					$j = ($j + 1) % $key[0];
					$k = ($k + 1) % $key[0];
					$l = ($l + 1) % $key[0];
				}
				array_unshift($tmp, 'N*');
				return call_user_func_array('pack', $tmp);
		}
		new XNError('blockencrypt', 'Undefined cipher name', XNError::WARNING);
		return false;
	}
	private static function blockdecrypt($cipher, $in, $key = null){
		$cipher = strtolower($cipher);
		switch($cipher){
			case 'xor':
				return self::xorcrypt($in, $key);
			case 'blowfish':
				$xl = array_value(unpack('N', substr($in, 0, 4)), 1);
				$xr = array_value(unpack('N', substr($in, 4, 4)), 1);
				for($i = 0; $i < 16; ++$i){
					$xl ^= $key[0][17 - $i];
					$xr = self::bff($xl, $key[1]) ^ $xr;
					swap($xl, $xr);
				}
				swap($xl, $xr);
				$xr ^= $key[0][1];
				$xl  = $xl ^ $key[0][0];
			return pack('N2', $xl, $xr);
			case 'twofish':
				list($s0, $s1, $s2, $s3, $k) = $key;
				$in = unpack("V4", $in);
				$r0 = $k[4] ^ $in[1];
				$r1 = $k[5] ^ $in[2];
				$r2 = $k[6] ^ $in[3];
				$r3 = $k[7] ^ $in[4];
				$ki = 40;
				while($ki > 8) {
					$t0 =   $s0[$r0	   & 0xff] ^
							$s1[$r0 >>  8 & 0xff] ^
							$s2[$r0 >> 16 & 0xff] ^
							$s3[$r0 >> 24 & 0xff];
					$t1 =   $s0[$r1 >> 24 & 0xff] ^
							$s1[$r1	   & 0xff] ^
							$s2[$r1 >>  8 & 0xff] ^
							$s3[$r1 >> 16 & 0xff];
					$r3^= ($t0 + ($t1 << 1) + $k[--$ki]);
					$r3 = $r3 >> 1 & 0x7fffffff | $r3 << 31;
					$r2 = ($r2 >> 31 & 0x1 | $r2 << 1) ^ ($t0 + $t1 + $k[--$ki]);
					$t0 =   $s0[$r2	   & 0xff] ^
							$s1[$r2 >>  8 & 0xff] ^
							$s2[$r2 >> 16 & 0xff] ^
							$s3[$r2 >> 24 & 0xff];
					$t1 =   $s0[$r3 >> 24 & 0xff] ^
							$s1[$r3	   & 0xff] ^
							$s2[$r3 >>  8 & 0xff] ^
							$s3[$r3 >> 16 & 0xff];
					$r1^= ($t0 + ($t1 << 1) + $k[--$ki]);
					$r1 = $r1 >> 1 & 0x7fffffff | $r1 << 31;
					$r0 = ($r0 >> 31 & 0x1 | $r0 << 1) ^ ($t0 + $t1 + $k[--$ki]);
				}
			return pack("V4", $k[0] ^ $r2, $k[1] ^ $r3, $k[2] ^ $r0, $k[3] ^ $r1);
			case 'skipjack':
				for($i = 32; $i >= 1; --$i){
					$subkey = substr($key, ($i - 1) * 4, 4);
					if($i <= 32 && $i >= 25)
						$in = self::sjrulebdec($in, $subkey, $i);
					if($i <= 24 && $i >= 17)
						$in = self::sjruleadec($in, $subkey, $i);
					if($i <= 16 && $i >= 9)
						$in = self::sjrulebdec($in, $subkey, $i);
					if($i <= 8 && $i >= 1)
						$in = self::sjruleadec($in, $subkey, $i);
				}
			return $in;
			case 'vigenere':
				$in = xnstring::getinrange(strtoupper($in), xnstring::UPPER_RANGE);
				$l = strlen($in);
				$key = xnstring::subrep($key, $l);
				for($i = 0; $i < $l; ++$i){
					$row = (ord($key[$i]) - 65) * 26;
					for($j = 0; $j < 26; ++$j)
						if(self::$vgt[$row + $j] == $in[$i]){
							$in[$i] = chr($j + 65);
							break;
						}
				}
			return $in;
			case 'enigma':
				$n1 = $n2 = 0;
				for($j = 0; isset($in[$j]); ++$j){
					$i = ord($in[$j]);
					$p1 = ($i + $n1) & 0377;
					$p3 = ($key[0][$p1] + $n2) & 0377;
					$p2 = ($key[2][$p3] - $n2) & 0377;
					$i = $key[1][$p2] - $n1;
					$in[$j] = chr($i);
					++$n1;
					if($n1 == 256){
						$n1 = 0;
						++$n2;
						if($n2 == 256)
							$n2 = 0;
					}
				}
				return $in;
			break;
			case 'rc2':
				list($r0, $r1, $r2, $r3) = array_values(unpack('v*', $in));
				$limit = 44;
				$actions = array($limit => 20, 20 => 0);
				$j = 64;
				while(true) {
					$r3 = ($r3 | ($r3 << 16)) >> 5;
					$r3 = ($r3 - $key[--$j] - ((($r0 ^ $r1) & $r2) ^ $r0)) & 0xFFFF;
					$r2 = ($r2 | ($r2 << 16)) >> 3;
					$r2 = ($r2 - $key[--$j] - ((($r3 ^ $r0) & $r1) ^ $r3)) & 0xFFFF;
					$r1 = ($r1 | ($r1 << 16)) >> 2;
					$r1 = ($r1 - $key[--$j] - ((($r2 ^ $r3) & $r0) ^ $r2)) & 0xFFFF;
					$r0 = ($r0 | ($r0 << 16)) >> 1;
					$r0 = ($r0 - $key[--$j] - ((($r1 ^ $r2) & $r3) ^ $r1)) & 0xFFFF;
					if($j === $limit) {
						if($limit === 0)break;
						$r3 = ($r3 - $key[$r2 & 0x3F]) & 0xFFFF;
						$r2 = ($r2 - $key[$r1 & 0x3F]) & 0xFFFF;
						$r1 = ($r1 - $key[$r0 & 0x3F]) & 0xFFFF;
						$r0 = ($r0 - $key[$r3 & 0x3F]) & 0xFFFF;
						$limit = $actions[$limit];
					}
				}
				return pack('v4', $r0, $r1, $r2, $r3);
			case 'rc4':
				for($i = $j = $k = 0; isset($in[$k]); ++$k) {
					$i = ($i + 1) & 0xff;
					$ksi = $key[$i];
					$j = ($j + $ksi) & 0xff;
					$ksj = $key[$j];
					$key[$i] = $ksj;
					$key[$j] = $ksi;
					$in[$k] = $in[$k] ^ chr($key[($ksj + $ksi) & 0xff]);
				}
				return $in;
			case 'des':
				$sbox = self::$dess;
				if(is_float($sbox[3][0])){
					$sbox[0] = array_map('intval', $sbox[0]);
					$sbox[1] = array_map('intval', $sbox[1]);
					$sbox[2] = array_map('intval', $sbox[2]);
					$sbox[3] = array_map('intval', $sbox[3]);
					$sbox[4] = array_map('intval', $sbox[4]);
					$sbox[5] = array_map('intval', $sbox[5]);
					$sbox[6] = array_map('intval', $sbox[6]);
					$sbox[7] = array_map('intval', $sbox[7]);
					self::$dess = $sbox;
				}
				$shuffle = array_map(function($x){
					return strtr(str_pad(decbin($x), 8, '0', STR_PAD_LEFT), '01', "\0\xff");
				}, range(0, 255));
				$shuffleinvip = $shuffleip = array();
				for($i = 0; $i < 256; ++$i) {
					$shuffleip[]    = $shuffle[self::$desm[$i]];
					$shuffleinvip[] = $shuffle[self::$desinvm[$i]];
				}
				$box = $key[1];
				$ki  = -1;
				$t = unpack('N2', $in);
				$l = $t[1];
				$r = $t[2];
				$tmp =  ($shuffleip[ $r        & 0xFF] & "\x80\x80\x80\x80\x80\x80\x80\x80") |
						($shuffleip[($r >>  8) & 0xFF] & "\x40\x40\x40\x40\x40\x40\x40\x40") |
						($shuffleip[($r >> 16) & 0xFF] & "\x20\x20\x20\x20\x20\x20\x20\x20") |
						($shuffleip[($r >> 24) & 0xFF] & "\x10\x10\x10\x10\x10\x10\x10\x10") |
						($shuffleip[ $l        & 0xFF] & "\x08\x08\x08\x08\x08\x08\x08\x08") |
						($shuffleip[($l >>  8) & 0xFF] & "\x04\x04\x04\x04\x04\x04\x04\x04") |
						($shuffleip[($l >> 16) & 0xFF] & "\x02\x02\x02\x02\x02\x02\x02\x02") |
						($shuffleip[($l >> 24) & 0xFF] & "\x01\x01\x01\x01\x01\x01\x01\x01");
				$t = unpack('N2', $tmp);
				$l = $t[1];
				$r = $t[2];
				for($round = 0; $round < $key[2]; ++$round) {
					for($i = 0; $i < 16; ++$i) {
						$b1 = (($r >>  3) & 0x1FFFFFFF) ^ ($r << 29) ^ $box[++$ki];
						$b2 = (($r >> 31) & 0x00000001) ^ ($r <<  1) ^ $box[++$ki];
						$t = $sbox[0][($b1 >> 24) & 0x3F] ^ $sbox[1][($b2 >> 24) & 0x3F] ^
							 $sbox[2][($b1 >> 16) & 0x3F] ^ $sbox[3][($b2 >> 16) & 0x3F] ^
							 $sbox[4][($b1 >>  8) & 0x3F] ^ $sbox[5][($b2 >>  8) & 0x3F] ^
							 $sbox[6][ $b1        & 0x3F] ^ $sbox[7][ $b2        & 0x3F] ^ $l;
						$l = $r;
						$r = $t;
					}
					swap($l, $r);
				}
				return ($shuffleinvip[($r >> 24) & 0xFF] & "\x80\x80\x80\x80\x80\x80\x80\x80") |
					   ($shuffleinvip[($l >> 24) & 0xFF] & "\x40\x40\x40\x40\x40\x40\x40\x40") |
					   ($shuffleinvip[($r >> 16) & 0xFF] & "\x20\x20\x20\x20\x20\x20\x20\x20") |
					   ($shuffleinvip[($l >> 16) & 0xFF] & "\x10\x10\x10\x10\x10\x10\x10\x10") |
					   ($shuffleinvip[($r >>  8) & 0xFF] & "\x08\x08\x08\x08\x08\x08\x08\x08") |
					   ($shuffleinvip[($l >>  8) & 0xFF] & "\x04\x04\x04\x04\x04\x04\x04\x04") |
					   ($shuffleinvip[ $r        & 0xFF] & "\x02\x02\x02\x02\x02\x02\x02\x02") |
					   ($shuffleinvip[ $l        & 0xFF] & "\x01\x01\x01\x01\x01\x01\x01\x01");
			case 'tripledes':
				$in = str_pad($in, (strlen($in) + 7) & 0xFFFFFFF8, "\0");
				return self::blockdecrypt('des', self::blockencrypt('des', self::blockdecrypt('des', $in, $key[2]), $key[1]), $key[0]);
			case 'arc4':
				return $in ^ self::a4prga($key, strlen($in));
			case 'rijndael':
				$state = array();
				$words = unpack('N*', $in);
				$wc = $key[0] - 1;
				foreach($words as $word)
					$state[] = $word ^ $key[4][++$wc];
				$tmp = array();
				for($round = $key[1] - 1; $round > 0; --$round) {
					$i = 0;
					$j = $key[0] - $key[2][1];
					$k = $key[0] - $key[2][2];
					$l = $key[0] - $key[2][3];
					while($i < $key[0]) {
						$tmp[$i]  = self::$rjinvt[0][$state[$i] >> 24 & 0x000000FF] ^
									self::$rjinvt[1][$state[$j] >> 16 & 0x000000FF] ^
									self::$rjinvt[2][$state[$k] >>  8 & 0x000000FF] ^
									self::$rjinvt[3][$state[$l]       & 0x000000FF] ^
									$key[4][++$wc];
						++$i;
						$j = ($j + 1) % $key[0];
						$k = ($k + 1) % $key[0];
						$l = ($l + 1) % $key[0];
					}
					$state = $tmp;
				}
				$i = 0;
				$j = $key[0] - $key[2][1];
				$k = $key[0] - $key[2][2];
				$l = $key[0] - $key[2][3];
				while($i < $key[0]) {
					$word = ($state[$i] & 0xFF000000) |
							($state[$j] & 0x00FF0000) |
							($state[$k] & 0x0000FF00) |
							($state[$l] & 0x000000FF);
					$tmp[$i] =			$key[4][$i] ^
										(self::$rjinvt[4][$word       & 0x000000FF]        |
										(self::$rjinvt[4][$word >>  8 & 0x000000FF] <<  8) |
										(self::$rjinvt[4][$word >> 16 & 0x000000FF] << 16) |
										(self::$rjinvt[4][$word >> 24 & 0x000000FF] << 24));
					++$i;
					$j = ($j + 1) % $key[0];
					$k = ($k + 1) % $key[0];
					$l = ($l + 1) % $key[0];
				}
				array_unshift($tmp, 'N*');
				return call_user_func_array('pack', $tmp);
		}
		new XNError('blockdecrypt', 'Undefined cipher name', XNError::WARNING);
		return false;
	}

	const KEYPAD = 1;
	const KEYMIX = 2;
	// CBC | CCM | CFB | CFB1 | CFB8 | CTR | COFB | ECB | GCM | NCFB | NOFB | OFB | PCBC | RAW | XTS
	private static function modeencrypt($cipher, $mode, $data, $key = null, $dsize = null, $iv = null, $options = 0){
		$cipher = strtolower($cipher);
		if(substr($cipher, 0, 3) == 'aes')$cipher = 'rijndael' . substr($cipher, 3);
		$mc = $cipher;
		if(substr($cipher, -3) == 'des')$cipher = 'des';
		$size = self::blocklength($cipher);
		if($size === null){
			new XNError('XNCrypt::encrypt', 'Undefined cipher name', XNError::WARNING);
			return false;
		}
		$mode = strtolower($mode);
		if(substr($cipher, 0, 8) == 'rijndael')$cipher = 'rijndael';
		if($key !== null && !is_array($key))
			$key = self::keyinstall($mc, self::keyinitsize($cipher, $key, $options, $dsize), $options, $dsize);
		if(is_array($key) && !isset($key[1]))
			$key = $key[0];
		if($iv === null)$iv = str_repeat("\0", $size);
		$iv = substr(self::zeropad($iv, $size), 0, $size);
		$res = '';
		switch($cipher . ':' . $mode){
			case 'tripledes:cbc3':
				$data = self::cryptopad($data, $size);
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= $iv = self::blockencrypt($cipher, substr($data, $i, $size) ^ $iv, $key);
			return $res;
			case 'tripledes:3cbc':
				$res = self::modeencrypt('des', 'cbc', self::modedecrypt('des', 'cbc', self::modeencrypt('des', 'cbc',
					$data, $key[0], $dsize, $iv, $options), $key[1], $dsize, $iv, $options), $key[2], $dsize, $iv, $options);
			return $res;
			case 'tripledes:3ecb':
				$res = self::modeencrypt('des', 'ecb', self::modedecrypt('des', 'ecb', self::modeencrypt('des', 'ecb',
					$data, $key[0], $dsize, $iv, $options), $key[1], $dsize, $iv, $options), $key[2], $dsize, $iv, $options);
			return $res;
		}
		switch($mode){
			case 'raw':
				return self::blockencrypt($cipher, $data, $key);
			case 'ecb':
				$data = self::cryptopad($data, $size);
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= self::blockencrypt($cipher, substr($data, $i, $size), $key);
			return $res;
			case 'cbc':
				$data = self::cryptopad($data, $size);
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= $iv = self::blockencrypt($cipher, substr($data, $i, $size) ^ $iv, $key);
			return $res;
			case 'pcbc':
				$data = self::cryptopad($data, $size);
				for($i = 0; isset($data[$i]); $i += $size){
					$tmp = substr($data, $i, $size);
					$res .= $iv = self::blockencrypt($cipher, $tmp ^ $iv, $key);
					$iv = $iv ^ $tmp;
				}
			return $res;
			case 'cfb':
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= $iv = self::blockencrypt($cipher, $iv, $key) ^ substr($data, $i, $size);
			return $res;
			case 'ofb':
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= ($iv = self::blockencrypt($cipher, $iv, $key)) ^ substr($data, $i, $size);
			return $res;
			case 'ctr':
				for($i = 0; isset($data[$i]); $i += $size){
					$res .= self::blockencrypt($cipher, $iv, $key) ^ substr($data, $i, $size);
					for($c = 0; isset($iv[$c]); ++$c)
						$iv[$c] = chr(ord($iv[$c]) + 1);
				}
			return $res;
			case 'ncfb':
				$l = strlen($data);
				for($i = 0; isset($data[$i]); $i += $n){
					$n = $size;
					if($i + $n > $l)
						$n -= $i + $n - $l;
					$res .= ($tmp = self::blockencrypt($cipher, $iv, $key)) ^ substr($data, $i, $n);
					$iv = substr($iv, $size) . $tmp;
				}
			return $res;
			case 'nofb':
				$l = strlen($data);
				for($i = 0; isset($data[$i]); $i += $n){
					$n = $size;
					if($i + $n > $l)
						$n -= $i + $n - $l;
					$res .= ($tmp = self::blockencrypt($cipher, $iv, $key)) ^ substr($data, $i, $n);
					$iv = substr($iv, $n) . substr($tmp, 0, $n);
				}
			return $res;
			case 'cfb8':
				for($i = 0; isset($data[$i]); $i += $size){
					$res .= $tmp = self::blockencrypt($cipher, $iv, $key) ^ substr($data, $i, $size);
					$iv = substr($iv, $size) . $tmp;
				}
			return $res;
		}
		new XNError('XNCrypt::modeencrypt', 'Undefined mode name', XNError::WARNING);
		return false;
	}
	private static function modedecrypt($cipher, $mode, $data, $key = null, $dsize = null, $iv = null, $options = 0){
		$cipher = strtolower($cipher);
		if(substr($cipher, 0, 3) == 'aes')$cipher = 'rijndael' . substr($cipher, 3);
		$mc = $cipher;
		if(substr($cipher, -3) == 'des')$cipher = 'des';
		$size = self::blocklength($cipher);
		if($size === null){
			new XNError('XNCrypt::decrypt', 'Undefined cipher name', XNError::WARNING);
			return false;
		}
		if(substr($cipher, 0, 8) == 'rijndael')$cipher = 'rijndael';
		if($key !== null && !is_array($key))
			$key = self::keyinstall($mc, self::keyinitsize($cipher, $key, $options, $dsize), $options, $dsize);
		if(is_array($key) && !isset($key[1]))
			$key = $key[0];
		if($iv === null)$iv = str_repeat("\0", $size);
		$iv = substr(self::zeropad($iv, $size), 0, $size);
		$res = '';
		switch($cipher . ':' . $mode){
			case 'tripledes:cbc3':
				for($i = 0; isset($data[$i]); $i += $size){
					$tmp = substr($data, $i, $size);
					$res .= self::blockdecrypt($cipher, $tmp, $key) ^ $iv;
					$iv = $tmp;
				}
			return self::cryptounpad($res);
			case 'tripledes:3cbc':
				$res = self::modedecrypt('des', 'cbc', self::modeencrypt('des', 'cbc', self::modedecrypt('des', 'cbc',
					$data, $key[2], $dsize, $iv, $options), $key[1], $dsize, $iv, $options), $key[0], $dsize, $iv, $options);
			return $res;
			case 'tripledes:3ecb':
				$res = self::modedecrypt('des', 'ecb', self::modeencrypt('des', 'ecb', self::modedecrypt('des', 'ecb',
					$data, $key[2], $dsize, $iv, $options), $key[1], $dsize, $iv, $options), $key[0], $dsize, $iv, $options);
			return $res;
		}
		switch($mode){
			case 'raw':
				return self::blockdecrypt($cipher, $data, $key);
			case 'ecb':
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= self::blockdecrypt($cipher, substr($data, $i, $size), $key);
			return self::cryptounpad($res);
			case 'cbc':
				for($i = 0; isset($data[$i]); $i += $size){
					$tmp = substr($data, $i, $size);
					$res .= self::blockdecrypt($cipher, $tmp, $key) ^ $iv;
					$iv = $tmp;
				}
			return self::cryptounpad($res);
			case 'pcbc':
				for($i = 0; isset($data[$i]); $i += $size){
					$tmp = substr($data, $i, $size);
					$res .= $iv = self::blockdecrypt($cipher, $tmp, $key) ^ $iv;
					$iv = $iv ^ $tmp;
				}
			return self::cryptounpad($res);
			case 'cfb':
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= self::blockencrypt($cipher, $iv, $key) ^ ($iv = substr($data, $i, $size));
			return $res;
			case 'ofb':
				for($i = 0; isset($data[$i]); $i += $size)
					$res .= ($iv = self::blockencrypt($cipher, $iv, $key)) ^ substr($data, $i, $size);
			return $res;
			case 'ctr':
				for($i = 0; isset($data[$i]); $i += $size){
					$res .= self::blockencrypt($cipher, $iv, $key) ^ substr($data, $i, $size);
					for($c = 0; isset($iv[$c]); ++$c)
						$iv[$c] = chr(ord($iv[$c]) + 1);
				}
			return $res;
			case 'ncfb':
				$l = strlen($data);
				for($i = 0; isset($data[$i]); $i += $n){
					$n = $size;
					if($i + $n > $l)
						$n -= $i + $n - $l;
					$res .= self::blockencrypt($cipher, $iv, $key) ^ ($iv = substr($data, $i, $n));
				}
			return $res;
			case 'nofb':
				$l = strlen($data);
				for($i = 0; isset($data[$i]); $i += $n){
					$n = $size;
					if($i + $n > $l)
						$n -= $i + $n - $l;
					$res .= ($tmp = self::blockencrypt($cipher, $iv, $key)) ^ substr($data, $i, $n);
					$iv = substr($iv, $n) . substr($tmp, 0, $n);
				}
			return $res;
			case 'cfb8':
				for($i = 0; isset($data[$i]); $i += $size){
					$res .= self::blockencrypt($cipher, $iv, $key) ^ ($iv = substr($data, $i, $size));
					$iv = substr($iv, $size) . $tmp;
				}
			return $res;
		}
		new XNError('XNCrypt::modedecrypt', 'Undefined mode name', XNError::WARNING);
		return false;
	}
	private static function modeextractiv($cipher, $mode, $plaintext, $encrypted, $key = null, $dsize = null, $options = 0){
		$size = self::blocklength($cipher);
		if($size === null){
			new XNError('XNCrypt::extractiv', 'Undefined cipher name', XNError::WARNING);
			return false;
		}
		$mode = strtolower($mode);
		if(substr($cipher, 0, 3) == 'aes')$cipher = 'rijndael' . substr($cipher, 3);
		$key = self::keyinitsize($cipher, $key, $options, $dsize);
		switch($mode){
			case 'raw':
			case 'ecb':
				return false;
			case 'cbc':
				return self::ivunpad(self::blockdecrypt($cipher, substr($encrypted, 0, $size), $key) ^ substr($plaintext, 0, $size));
			case 'pcbc':
				return self::ivunpad(self::blockdecrypt($cipher, substr($encrypted, 0, $size), $key) ^ substr($plaintext, 0, $size));
			case 'cfb':
			case 'ofb':
			case 'ctr':
			case 'cfb8':
				return self::ivunpad(self::blockdecrypt($cipher, substr($encrypted, 0, $size) ^ substr($plaintext, 0, $size), $key));
			case 'ncfb':
			case 'nofb':
				$l = strlen($data);
				$n = $size;
				if($n > $l)
					$n -= $n - $l;
				return self::ivunpad(self::blockdecrypt($cipher, substr($encrypted, 0, $n) ^ substr($plaintext, 0, $n), $key));
		}
		new XNError('XNCrypt::extractiv', 'Undefined mode name', XNError::WARNING);
		return false;
	}
	private static function cryptopad($string, $size){
		$length = strlen($string);
		$pad = $size - ($length % $size);
		return str_pad($string, $length + $pad, chr($pad));
	}
	private static function cryptounpad($string){
		if($string === '')return '';
		return substr($string, 0, -ord($string[-1]));
	}
	private static function zeropad($string, $size){
		$length = strlen($string);
		$pad = $size - ($length % $size);
		return str_pad($string, $length + $pad, "\0");
	}
	private static function mixpad($string, $size){
		$length = strlen($string);
		if($length % $size === 0)return $string;
		$pad = $size - ($length % $size);
		return str_pad($string, $length + $pad, "\0");
	}
	private static function ivunpad($iv){
		if($iv === "\0\0\0\0\0\0\0\0")return null;
		if($iv === '')return '';
		return substr($iv, 0, -ord($iv[-1]));
	}
	public static function encrypt($method, $plaintext, $key = null){
		$args = array_slice(func_get_args(), 3);
		$method = explode('-', $method, 4);
		$cipher = $method[0];
		if(isset($method[2]) && (int)$method[2] !== 0)$dsize = (int)$method[2] >> 3;
		else $dsize = null;
		$mode = isset($method[1]) ? $method[1] : (isset($args[1]) || (isset($args[0]) && is_string($args[0])) ? 'cbc' : 'ecb');
		if(isset($method[3]))
			if(isset($args[1]))
				$plaintext = self::encrypt($method[3], $plaintext, $key, $args[0], $args[1]);
			elseif(isset($args[0]))
				$plaintext = self::encrypt($method[3], $plaintext, $key, $args[0]);
			else
				$plaintext = self::encrypt($method[3], $plaintext, $key);
		switch(strtolower($mode)){
			case 'raw':
			case 'ecb':
				$iv = null;
				if(isset($args[0]))$options = (int)$args[0];
				else $options = 0;
			break;
			default:
				if(isset($args[0]))$iv = (string)$args[0];
				else $iv = null;
				if(isset($args[1]))$options = (int)$args[1];
				else $options = 0;
		}
		return self::modeencrypt($cipher, $mode, $plaintext, $key, $dsize, $iv, $options);
	}
	public static function decrypt($method, $plaintext, $key = null){
		$args = array_slice(func_get_args(), 3);
		$method = explode('-', $method);
		$cipher = $method[0];
		if(isset($method[2]) && (int)$method[2] !== 0)$dsize = (int)$method[2] >> 3;
		else $dsize = null;
		$mode = isset($method[1]) ? $method[1] : (isset($args[1]) || (isset($args[0]) && is_string($args[0])) ? 'cbc' : 'ecb');
		switch($mode){
			case 'raw':
			case 'ecb':
				$iv = null;
				if(isset($args[0]))$options = (int)$args[0];
				else $options = 0;
			break;
			default:
				if(isset($args[0]))$iv = (string)$args[0];
				else $iv = null;
				if(isset($args[1]))$options = (int)$args[1];
				else $options = 0;
		}
		if(isset($method[3]))
			if(isset($args[1]))
				$plaintext = self::decrypt($method[3], $plaintext, $key, $args[0], $args[1]);
			elseif(isset($args[0]))
				$plaintext = self::decrypt($method[3], $plaintext, $key, $args[0]);
			else
				$plaintext = self::decrypt($method[3], $plaintext, $key);
		return self::modedecrypt($cipher, $mode, $plaintext, $key, $dsize, $iv, $options);
	}
	public static function extractiv($method, $plaintext, $ciphertext, $key = null, $options = 0){
		$more = array();
		$method = explode('-', $method);
		$cipher = $method[0];
		if(isset($method[2]) && (int)$method[2] !== 0)$dsize = (int)$method[2] >> 3;
		else $dsize = null;
		$mode = isset($method[1]) ? $method[1] : 'cbc';
		return self::modeextractiv($cipher, $mode, $plaintext, $ciphertext, $key, $dsize, $options);
	}
	public static function installkey($cipher, $key = null, $options = 0){
		$cipher = explode('-', strtolower($cipher));
		if(isset($cipher[1]) && (int)$cipher[1] !== 0)$dsize = (int)$cipher[1] >> 3;
		else $dsize = null;
		$mc = $cipher[0];
		if(substr($mc, 0, 3) == 'aes')$mc = 'rijndael' . substr($mc, 3);
		$cipher = $mc;
		if(substr($cipher, -3) == 'des')$cipher = 'des';
		if(substr($cipher, 0, 8) == 'rijndael')$cipher = 'rijndael';
		$key = self::keyinitsize($cipher, $key, $options, $dsize);
		return self::keyinstall($mc, $key);
	}
	public static function modes(){
		return array(
			'raw', 'ecb', 'cbc', 'pcbc', 'cfb', 'ofb', 'ctr', 'ofb8', 'ncfb', 'nofb', 'cbc3', '3cbc'
		);
	}
	public static function ciphers(){
		return array(
			'xor', 'blowfish', 'twofish', 'skipjack', 'vigenere', 'enigma', 'rc2', 'rc4', 'des',
			'tripledes', 'arc4', 'rijndael', 'aes'
		);
	}
	public static function codings(){
		return array(
			'bin', 'base4', 'oct', 'hex', 'base32', 'base64', 'bcrypt64', 'url', 'json', 'serialization'
		);
	}

	const RAND = 0;
	const RAND_MD5 = 1;
	const DEV_RAND = 2;
	const DEV_URAND = 3;
	const RAND_WIN_COM = 4;
	const MT19937 = 0;
	const MTPHP = 1;

	private static $mtstate = array();
	private static $mtindex = 625;
	public static function makeseed(){
		$rand = explode(' ', microtime(), 2);
		return (int)($rand[1] + $rand[0] * 1000000);
	}
	public static function mtseed($seed = null, $mode = 0){
		if($seed === null)
			$seed = self::makeseed();
		if(__xnlib_data::$installedMtrand)
			return mt_srand($seed, $mode);
		if($mode === 1)
			self::$mtstate = true;
		elseif(PHP_INT_SIZE === 8){
			$int0 = $seed & 0xffffffff;
			$int1 = ($seed >> 32) & 0xffffffff;
			$state = array($seed);
			for($i = 1; $i < 312; ++$i) {
				$int0 ^= $int1 >> 30;
				$carry = (0x4c957f2d * $int0) + $i;
				$int1 = ((0x4c957f2d * $int1) & 0xffffffff) +
						((0x5851f42d * $int0) & 0xffffffff) +
						($carry >> 32) & 0xffffffff;
				$int0 = $carry & 0xffffffff;
				$state[$i] = ($int1 << 32) | $int0;
			}
			self::$mtstate = $state;
			self::$mtindex = $i;
		}else{
			$state = array($seed & 0xffffffff);
			$int0 = $seed & 0xffff;
			$int1 = ($seed >> 16) & 0xffff;
			for($i = 1; $i < 624; ++$i) {
				$int0 ^= $int1 >> 14;
				$carry = (0x8965 * $int0) + $i;
				$int1 = ((0x8965 * $int1) + (0x6C07 * $int0) + ($carry >> 16)) & 0xffff;
				$int0 = $carry & 0xffff;
				$state[$i] = ($int1 << 16) | $int0;
			}
			self::$mtstate = $state;
			self::$mtindex = $i;
		}
	}
	public static function mttwist($m, $u, $v){
		if(PHP_INT_SIZE === 8){
			$y = ($u & -2147483648) | ($v & 0x7fffffff);
			return $m ^ (($y >> 1) & 0x7fffffffffffffff) ^ (-5403634167711393303 * ($v & 1));
		}else{
			$y = ($u & 0x80000000) | ($v & 0x7fffffff);
			return $m ^ (($y >> 1) & 0x7fffffff) ^ (0x9908b0df * ($v & 1));
		}
	}
	public static function mtint(){
		if(__xnlib_data::$installedMtrand)
			return mt_rand();
		if(self::$mtstate === true)
			return rand(PHP_INT_MIN, PHP_INT_MAX);
		elseif(PHP_INT_SIZE === 8){
			if(self::$index >= 312) {
				if(self::$index === 313)
					self::mtseed(5489);
				$state = self::$mtstate;
				for($i = 0; $i < 156; ++$i)
					$state[$i] = self::mttwist($state[$i + 156], $state[$i], $state[$i + 1]);
				for(; $i < 311; ++$i)
					$state[$i] = self::mttwist($state[$i - 156], $state[$i], $state[$i + 1]);
				$state[311] = self::mttwist($state[155], $state[311], $state[0]);
				self::$mtstate = $state;
				self::$mtindex = 0;
			}
			$y = self::$mtstate[self::$mtindex++];
			$y ^= ($y >> 29) & 0x0000000555555555;
			$y ^= ($y << 17) & 0x71d67fffeda60000;
			$y ^= ($y << 37) &  -2270628950310912;
			$y ^= ($y >> 43) & 0x00000000001fffff;
			return $y;
		}else{
			if(self::$mtindex >= 624) {
				if(self::$mtindex === 625)
					self::mtseed(5489);
				$state = self::$mtstate;
				for($i = 0; $i < 227; ++$i)
					$state[$i] = self::mttwist($state[$i + 397], $state[$i], $state[$i + 1]);
				for(; $i < 623; ++$i)
					$state[$i] = self::mttwist($state[$i - 227], $state[$i], $state[$i + 1]);
				$state[623] = self::mttwist($state[396], $state[623], $state[0]);
				self::$mtstate = $state;
				self::$mtindex = 0;
			}
			$y = self::$mtstate[self::$mtindex++];
			$y ^= ($y >> 11) & 0x001fffff;
			$y ^= ($y <<  7) & 0x9d2c5680;
			$y ^= ($y << 15) & 0xefc60000;
			$y ^= ($y >> 18) & 0x00003fff;
			return $y;
		}
	}
	public static function mtintpb(){
		if(PHP_INT_SIZE === 8)
			return (self::mtint() >> 1) & 0x7fffffffffffffff;
		return (self::mtint() >> 1) & 0x7fffffff;
	}
	public static function mtrand($min, $max){
		if($max < $min)swap($max, $min);
		if(__xnlib_data::$installedMtrand)
			return mt_rand($MIn, $max);
		if(PHP_INT_SIZE === 8)
			return (int)($min + (($max - $min + 1) * (self::mtint() / 0x80000000)));
		return (int)($min + (($max - $min + 1) * (self::mtint() / 0x8000000000000000)));
	}

	public static function randint($min, $max, $type = 0){
		if($max < $min)swap($max, $min);
		switch($type){
			case 2:
			case 3:
			case 4:
				if(function_exists('random_int'))
					return random_int($min, $max);
				self::mtseed();
				return self::mtrand($min, $max);
			default:
				return rand($min, $max);
		}
	}
	public static function randbytes($length = 1, $type = 0){
		switch($type){
			case 1:
				$bin = '';
				self::mtseed();
				for($i = 0; $i < $length; $i += 16)
					$bin .= md5(self::mtint(), true);
				return substr($bin ,0, $length);
			case 2:
				if(is_readable('/dev/random')){
					$file = fopen('/dev/random', 'rb');
					stream_set_read_buffer($file, 0);
					$bin = fread($file, $length);
					fclose($file);
					return $bin;
				}
				if(function_exists('random_bytes'))
					return random_bytes($length);
				$bin = '';
				self::mtseed();
				for($i = 0; $i < $length; ++$i)
					$bin .= chr(self::mtrand(0, 255));
				return $bin;
			case 3:
				if(function_exists('random_bytes'))
					return random_bytes($length);
				if(is_readable('/dev/urandom')){
					$file = fopen('/dev/urandom', 'rb');
					stream_set_read_buffer($file, 0);
					$bin = fread($file, $length);
					fclose($file);
					return $bin;
				}
				$bin = '';
				self::mtseed();
				for($i = 0; $i < $length; ++$i)
					$bin .= chr(self::mtrand(0, 255));
				return $bin;
			case 4:
				if(extension_loaded('com_dotnet')){
					try{
						$com = @new \COM("CAPICOM.Utilities.1");
						return $com->GetRandom($length, 0);
					}catch(Exception $e){}
				}
			default:
				$bin = '';
				for($i = 0; $i < $length; ++$i)
					$bin .= chr(rand(0, 255));
				return $bin;
		}
	}
	public static function randiv($cipher, $type = 0){
		if(substr($cipher, -3) == 'des' && is_numeric(substr($cipher, 0, -3)))$cipher = 'des';
		$size = self::blocklength($cipher);
		if($size === null){
			new XNError('XNCrypt::decrypt', 'Undefined cipher name', XNError::WARNING);
			return false;
		}
		return self::randbytes($size, $type);
	}
	public static function randhex($length = 1, $type = 0){
		return substr(self::hexencode(self::randbytes(ceil($length / 2), $type)), 0, $length);
	}
	public static function randbin($length = 1, $type = 0){
		return substr(self::binencode(self::randbytes(ceil($length / 8), $type)), 0, $length);
	}
	public static function randoct($length = 1, $type = 0){
		return substr(self::octencode(self::randbytes(ceil($length / 8 * 3), $type)), 0, $length);
	}
	public static function randdec($length = 1, $type = 0){
		if($max < $min)swap($max, $min);
		$dec = '';
		switch($type){
			case 2:
			case 3:
			case 4:
				if(function_exists('random_int')){
					for($i = 0; $i < $length; ++$i)
						$dec .= random_int(0, 9);
					return $dec;
				}
				self::mtseed();
				for($i = 0; $i < $length; ++$i)
					$dec .= self::mtrand(0, 9);
				return $dec;
			default:
				for($i = 0; $i < $length; ++$i)
					$dec .= rand(0, 9);
				return $dec;
		}
	}
}



/* ---------- XNData ---------- */
class XNData {
	const VERSION = '4.2.7';
	public $zlib = false;

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
				if($key == floor($key)){
					$pkey = xnmath::number2ascii($key);
					if(!is_numeric($pkey))
						$key = $pkey;
					else
						$key = (string)$key;
				}else
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
				new XNError("XNData", "unsupported Type", XNError::TYPE, XNError::TTHROW);
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
				if($key == floor($key)){
					$pkey = xnmath::number2ascii($key);
					if(!is_numeric($pkey))
						$key = $pkey;
					else
						$key = (string)$key;
				}else
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
				new XNError("XNData", "unsupported Type", XNError::TYPE, XNError::TTHROW);
		}
		return chr($type).$key;
	}
	public static function decodeon($key){
		$type = ord($key[0]);
		$key = substr_replace($key,'',0,1);
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
				if(!is_numeric($key))
					$key = xnmath::ascii2number($key);
				$key = $key + 0;
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
				new XNError("XNData", "unsupported Type", XNError::TYPE, XNError::TTHROW);
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
		return array($key,$value);
	}
	public static function decodenz($key){
		return self::decodeon(substr($key,ord($key[0])+1));
	}
	public static function decodeez($key){
		return self::decodeel(substr($key,ord($key[0]) + 1));
	}

	// constructors
	public $xnd, $type;
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
		$xnd->xnd = new XNDataURL('php://input');
		$xnd->type = "url";
		return $xnd;
	}
	public static function xn_data(){
		if(__xnlib_data::$xndataFile)$xnd = xndata::file(__xnlib_data::$xndataFile);
		elseif(file_exists(__xnlib_data::$dirname . DIRECTORY_SEPARATOR . 'xndata.xnd'))$xnd = xndata::file(__xnlib_data::$dirname . DIRECTORY_SEPARATOR . 'xndata.xnd');
		elseif(file_exists('xndata.xnd'))$xnd = xndata::file('xndata.xnd');
		else $xnd = xndata::url("https://raw.githubusercontent.com/xnlib/xnphp/master/xndata.xnd");
		$xnd->xndata = true;
		return $xnd;
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
	public $ns = array();
	public function getNSs(){
		if($this->ns == array())return '';
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
				if($key == floor($key)){
					$pkey = xnmath::number2ascii($key);
					if(!is_numeric($pkey))
						$key = $pkey;
					else
						$key = (string)$key;
				}else
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
				new XNError("XNData", "unsupported Type", XNError::TYPE, XNError::TTHROW);
		}
		$z = function_exists('zlib_encode') && (!isset($this) || $this->zlib) ? zlib_encode($key,31) : $key;
		if(strlen($z) < strlen($key)){
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
			$key = function_exists('zlib_decode') ? zlib_decode($key) : $key;
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
				if(!is_numeric($key))
					$key = xnmath::ascii2number($key);
				$key = $key + 0;
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
				new XNError("XNData", "unsupported Type", XNError::TYPE, XNError::TTHROW);
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
		return $this->ns != array();
	}
	public function isInNS($ns = false){
		if($ns)return in_array($ns, $this->getNS());
		return $this->ns != array();
	}
	public function isLastNS($ns){
		if($this->ns == array())return false;
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
		$this->ns = array();
		return $this;
	}
	public function allNSs(){
		$ns = array();
		$this->readkeys(function($x)use(&$ns){
			$key = explode($key, "\xff", substr_count(substr($key, 0, strpos($key, "\\\xff")), "\xff") + 1);
			for($c = 0;isset($key[$c + 1]);)
				$ns[] = self::decodeon(str_replace("\\\xff", "\xff", $key[$c++]));
		});
		return array_unique($ns);
	}
	
	// size info
	public function get(){
		return $this->xnd->get();
	}
	public function get_hash($algo = 'md5'){
		return xncrypt::hash($algo, $this->xnd->get());
	}
	public function get_hmac($algo = 'md5', $pass = ''){
		return xncrypt::hash_hmac($algo, $this->xnd->get(), $pass);
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
	public $save = false, $xndata = false;
	public function save(){
		if($this->type != 'url' && $this->xnd->isChild())
			return $this->xnd->save();
	}
	public function __destruct(){
		if($this->xndata === true)return;
		if($this->type != 'url')
			$this->setLastModified();
		if(!$this->save)
			$this->save();
	}
	public function isChild(){
		return $this->xnd->isChild();
	}

	// get location
	public function locate(){
		if($this->type === 'string')
			new XNError("XNDataString", "String data not have locate", XNError::WARNING, XNError::TTHROW);
		return $this->xnd->locate();
	}
	public function stream(){
		if($this->type === 'string')
			new XNError("XNDataString", "String data not have locate", XNError::WARNING, XNError::TTHROW);
		return $this->xnd->stream();
	}
	
	// copy
	public function copy(){
		if($this->type === 'string')
			return xndata::string($this->xnd->get());
		elseif($this->type === 'url')
			return xndata::url($this->xnd->locate());
		else
			return xndata::file($this->xnd->stream());
	}

	// headers
	public function setName($name){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->set("\x01\x02\x09n",self::encodeon($name));
		return $this;
	}
	public function getName(){
		$name = $this->xnd->value("\x01\x02\x09n");
		if(!$name)return;
		return self::decodenz($name);
	}
	public function setDescription($desc){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->set("\x01\x02\x09d",self::encodeon($desc));
		return $this;
	}
	public function getDescription(){
		$desc = $this->xnd->value("\x01\x02\x09d");
		if(!$desc)return;
		return self::decodenz($desc);
	}
	private function setLastModified(){
		if($this->type === 'url')
			return false;
		$this->xnd->set("\x01\x02\x09m",self::encodeon(floor(microtime(true)*1000)));
	}
	public function getLastModified(){
		$modifi = $this->xnd->value("\x01\x02\x09m");
		if(!$modifi)return;
		return self::decodenz($modifi) / 1000;
	}
	private function setCreatedTime(){
		if($this->type === 'url')
			return false;
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
	public function dateCreatedTime($format){
		$modifi = $this->xnd->value("\x01\x02\x09c");
		if(!$modifi)return;
		return date($format, self::decodenz($modifi) / 1000);
	}
	public function dateLastModified($format){
		$modifi = $this->xnd->value("\x01\x02\x09m");
		if(!$modifi)return;
		return date($format, self::decodenz($modifi) / 1000);
	}
	public function hasName(){
		return $this->xnd->iskey("\x01\x02\x09n");
	}
	public function hasDescription(){
		return $this->xnd->iskey("\x01\x02\x09d");
	}

	// convertor
	public function convert($to = 'string',$file = ''){
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
						}elseif(!is_resource($file) || !xnstream::mode($file))
							return false;
						if(xnstream::mode($file) != 'r+b')
							$file = xnstream::fclone($file,'r+b');
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
						}elseif(!is_resource($file) || !xnstream::mode($file))
							return false;
						if(xnstream::mode($file) != 'r+b')
							$file = xnstream::fclone($file,'r+b');
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
						}elseif(!is_resource($file) || !xnstream::mode($file))
							return false;
						if(xnstream::mode($file) != 'r+b')
							$file = xnstream::fclone($file,'r+b');
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
		return self::decodeNS($key);
	}
	public function keys($value){
		$keys = $this->xnd->keys(self::encodeon($value));
		if(!$keys)return;
		$kys = array();
		$ns = $this->getNSs();
		foreach($keys as $key){
			if(!$ns || strpos($key, $ns) === 0)
				$kys[] = self::decodeNS($key);
		}
		return $kys;
	}
	public function keyNS($value){
		$key = $this->xnd->key(self::encodeon($value));
		if(!$key)return;
		$key = explode($key, "\xff", substr_count(substr($key, 0, strpos($key, "\\\xff")), "\xff") + 1);
		foreach($key as &$k)
			$k = self::decodeon(str_replace("\\\xff", "\xff", $k));
		return $key;
	}
	public function keysNS($value){
		$keys = $this->xnd->keys(self::encodeon($value));
		if(!$keys)return;
		foreach($keys as &$key){
			$key = explode($key, "\xff", substr_count(substr($key, 0, strpos($key, "\\\xff")), "\xff") + 1);
			foreach($key as &$k)
				$k = self::decodeon(str_replace("\\\xff", "\xff", $k));
		}
		return $keys;
	}

	// values
	public function isvalue($value){
		return $this->xnd->isvalue(self::encodeon($value));
	}
	public function value($key, $length = null){
		$keyNS = self::encodeNS($key);
		$value = $this->xnd->value($keyNS, $length === null ? null : strlen($keyNS) - strlen($key) + $length + 2);
		if(!$value)return;
		return $length === null ? self::decodenz($value) : substr(self::decodenz($value), 0, $length);
	}
	public function valen($key){
		$keyNS = self::encodeNS($key);
		$value = $this->xnd->valen($keyNS);
		return $value === false ? false : $value - strlen(self::encodesz($value)) - 2;
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
	public function make($dir,$ret = null){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$dir = self::encodeNS($dir);
		$this->xnd->set($dir,"\x01\x01\x09");
		if($this->save)
			$this->save();
		if($ret){
			if($this->type == "string")
				$xnd = new XNDataString();
			else
				$xnd = new XNDataFile(tmpfile());
			$xnd->setme(array($this->xnd,$dir));
			return self::xnd($xnd);
		}
		return $this;
	}
	public function mdir($dir){
		$name = self::encodeNS($dir);
		$dir = $this->xnd->dir($name);
		if(!$dir){
			if($this->type === 'url')
				new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
			$this->xnd->add($name,"\x01\x01\x09");
			if($this->save)
				$this->save();
			if($this->type == "string")
				$xnd = new XNDataString();
			else
				$xnd = new XNDataFile(tmpfile());
			$xnd->setme(array($this->xnd,$name));
			return self::xnd($xnd);
		}
		return self::xnd($dir);
	}

	// setters
	public function set($key,$value){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->set(self::encodeNS($key),self::encodeon($value));
		if($this->save)
			$this->save();
		return $this;
	}
	public function reset(){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->reset();
		if($this->save)
			$this->save();
		return $this;
	}
	public function delete($key){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->delete(self::encodeNS($key));
		if($this->save)
			$this->save();
		return $this;
	}

	// Math
	public function join($key,$value){
		$this->set($key,$x = $this->value($key) . $value);
		return $x;
	}
	public function sjoin($key,$value){
		$this->set($key,$x = $value . $this->value($key));
		return $x;
	}
	public function madd($key,$count = 1){
		$this->set($key,$x = $this->value($key) + $count);
		return $x;
	}
	public function msub($key,$count = 1){
		$this->set($key,$x = $this->value($key) - $count);
		return $x;
	}
	public function mdiv($key,$count = 1){
		$this->set($key,$x = $this->value($key) / $count);
		return $x;
	}
	public function mmul($key,$count = 1){
		$this->set($key,$x = $this->value($key) * $count);
		return $x;
	}
	public function mmod($key,$count = 1){
		$this->set($key,$x = $this->value($key) % $count);
		return $x;
	}
	public function mpow($key,$count = 2){
		$this->set($key,$x = pow($this->value($key), $count));
		return $x;
	}
	public function msqrt($key,$count = 2){
		$this->set($key,$x = pow($this->value($key), 1 / $count));
		return $x;
	}
	public function mxor($key,$count = 1){
		$this->set($key,$x = $this->value($key) ^ $count);
		return $x;
	}
	public function mand($key,$count = 1){
		$this->set($key,$x = $this->value($key) & $count);
		return $x;
	}
	public function mor($key,$count = 1){
		$this->set($key,$x = $this->value($key) | $count);
		return $x;
	}
	public function mshl($key,$count = 1){
		$this->set($key,$x = $this->value($key) % $count);
		return $x;
	}
	public function mshr($key,$count = 1){
		$this->set($key,$x = $this->value($key) % $count);
		return $x;
	}
	
	// Hashing
	public function hash_set($key, $value, $algo = 'md5'){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$hash = xncrypt::hash($algo, $value, true);
		return $hash ? $this->set($key, $hash) : false;
	}
	public function hmac_set($key, $value, $algo = 'md5', $pass = ''){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$hash = xncrypt::hash_hmac($algo, $value, $pass, true);
		return $hash ? $this->set($key, $hash) : false;
	}
	public function hash_equal($key, $value, $algo = 'md5'){
		$hash = xncrypt::hash($algo, $value, true);
		return $hash ? $this->value($key) === $hash : false;
	}
	public function hmac_equal($key, $value, $algo = 'md5', $pass = ''){
		$hash = xncrypt::hash_hmac($algo, $value, $pass, true);
		return $hash ? $this->value($key) === $hash : false;
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
	public function setlist($data){
		foreach($data as $key=>$value)
			$this->set($key,$value);
		return $this;
	}
	public function allkeys(){
		$keys = array();
		$this->xnd->allkey(function($key)use(&$keys){
			if($key[0] == "\x09")return;
			$key = self::decodeNS($key);
			if($key)$keys[] = $key;
		});
		return $keys;
	}
	public function all(){
		$all = array();
		$this->xnd->all(function($key,$value)use(&$all){
			if($key[0] == "\x09")return;
			$key = self::decodeNS($key);
			if(!$key)return;
			if($value[ord($value[0])+1] == "\x09")
				 $all[] = array($key,self::xnd(new XNDataString(substr_replace($value,'',0,ord($value[0])+2))));
			elseif(isset($value[2]) && $value[2] == "\x0a")
				$all[] = array($key);
			else $all[] = array($key,self::decodenz($value));
		});
		return $all;
	}
	public function readkeys($func){
		$this->xnd->allkey(function($k)use($func){
			if($k[0] == "\x09")return;
			$k = self::decodeNS($k);
			if($k)$func($k);
		});
		return $this;
	}
	public function read($func){
		$this->xnd->all(function($k,$v)use($func){
			if($k[0] == "\x09")return;
			$k = self::decodeNS($k);
			if($k)$func($k,self::decodenz($v));
		});
		return $this;
	}
	public function map($func){
		$this->xnd->map(function($k,$v)use($func){
			if($k[0] == "\x09")return $v;
			$k = self::decodeNS($k);
			if($k)return self::encodeon($func($k,self::decodenz($v)));
			return $v;
		});
		return $this;
	}

	// variables
	public $vars = array();
	public function setvar($variable, $content){
		$this->vars[$variable] = array(0, $content);
	}
	public function getvar($variable){
		return isset($this->vars[$variable]) ? $this->vars[$variable][1] : null;
	}
	public function addvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] += $content;
	}
	public function subvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] -= $content;
	}
	public function mulvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] *= $content;
	}
	public function divvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] /= $content;
	}
	public function modvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] %= $content;
	}
	public function xorvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] ^= $content;
	}
	public function andvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] &= $content;
	}
	public function powvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] = pow($this->vars[$variable][1], $content);
	}
	public function sqrtvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
		$this->vars[$variable][1] = pow($this->vars[$variable][1], 1 / $content);
	}
	public function orvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] |= $content;
	}
	public function joinvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] .= $content;
	}
	public function sjoinvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] = $content . $this->vars[$variable][1];
	}
	public function shlvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] >>= $content;
	}
	public function shrvar($variable, $content){
		if(isset($this->vars[$variable]) && $this->vars[$variable][0] === 0)
			$this->vars[$variable][1] <<= $content;
	}
	public function hasvar($variable){
		return isset($this->vars[$variable]);
	}
	public function setfunc($variable, $code, $args = array()){
		$this->vars[$variable] = array(1, $code, $args);
	}
	public function deletevar($variable){
		if(isset($this->vars[$variable]))
			unset($this->vars[$variable]);
	}
	public function addressvar($variable, $address){
		if(isset($this->vars[$variable]) && isset($this->vars[$address]))
			$this->vars[$variable] = &$this->vars[$address];
	}
	public function caddressvar($variable, $address){
		if(isset($this->vars[$variable]) && isset($this->vars[$address]))
			$this->vars[$variable][1] = &$this->vars[$address][1];
	}
	public function call($variable, $args = array()){
		if(!isset($this->vars[$variable]) || $this->vars[$variable][0] !== 1)
			return false;
		$pars = array();
		foreach($this->vars[$variable][2] as $k => $arg)
			if(isset($args[$k]))
				$pars[$arg] = $args[$k];
		$this->query($this->vars[$variable][1], $pars);
	}

	// query
	public function query($query = '', $vars = array()){
		$type = substr($query, 0, 3);
		if($type == "#1\n"){
			$type = 1;
			$query = substr($query, 3);
			$fvars = $this->vars;
		}elseif($type == "#2\n"){
			$type = 2;
			$query = substr($query, 3);
			$fvars = $this->vars;
			$this->vars = array();
		}elseif($type == "#3\n"){
			$type = 3;
			$query = substr($query, 3);
		}else $type = 3;
		foreach($vars as $var => $content)
			$this->setvar($var, $content);
		$params = $codes = $datas = array();
		$c = 0;
		$query = preg_replace_callback("/(?<x>(in|out|glob|)\{((?:\g<x>|\\\\\[|\\\\\]|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\})/",
		function($x)use(&$codes, &$c){
			if($x[2] == 'in')
				$x[2] = 1;
			elseif($x[2] == 'out' || $x[2] === '')
				$x[2] = 2;
			elseif($x[2] == 'glob')
				$x[2] = 3;
			$codes[$c] = '#' . $x[2] . "\n" . $x[3];
			return $c++;
		}, $query);
		$c = 0;
		$query = preg_replace_callback("/\"((?:\\\\\"|[^\"])*)\"|'((?:\\\\'|[^'])*)'/",
		function($x)use(&$datas, &$c){
			$datas[$c] = isset($x[2]) ? $x[2] : $x[1];
			return $c++;
		}, $query);
		$query = preg_replace_callback("/(?<x><((?:\g<x>|\\\\\[|\\\\\]|\\\\\(|\\\\\)|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)>)/",
		function($x)use(&$datas, &$c){
			$datas[$c] = unserialize($x[2]);
			return $c++;
		}, $query);
		$query = preg_replace_callback("/(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\\\\\(|\\\\\)|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\])/",
		function($x)use(&$datas, &$c){
			$datas[$c] = xncrypt::jsondecode($x[2]);
			return $c++;
		}, $query);
		$query = preg_replace_callback("/(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\\\\\(|\\\\\)|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\])/",
		function($x)use(&$datas, &$c){
			$datas[$c] = xncrypt::jsondecode($x[2]);
			return $c++;
		}, $query);
		$query = preg_replace_callback("/\"((?:\\\\\"|[^\"])*)\"|'((?:\\\\'|[^'])*)'/",
		function($x)use(&$datas, &$c){
			$datas[$c] = isset($x[2]) ? $x[2] : $x[1];
			return $c++;
		}, $query);
		$finish = '';
		while(strpos($query, '  ') > 0)
			$query = str_replace('  ', ' ', $query);
		$query = explode("\n", $query);
		foreach($query as $q) {
			$q = array_map('strtolower', explode(' ', str_replace(array("\r", "\t"), '', trim($q))));
			do{
				$pv = $q;
				foreach($q as $k=>&$t){
					$pr = $c;
					if(isset($t[1]) && $t[0] == '#' && isset($this->vars[substr($t, 1)]))
						$datas[$c++] = $this->getvar(substr($t, 1));
					elseif(isset($t[2]) && $t[1] == '#' && isset($this->vars[substr($t, 2)])){
						$datas[$c] = $this->getvar(substr($t, 2));
						$pr = $c++;
						$t = $t[0] . $pr;
					}
					if($t == 'true')
						$datas[$c++] = true;
					elseif($t == 'false')
						$datas[$c++] = false;
					elseif($t == 'null')
						$datas[$c++] = null;
					elseif($t == 'empty')
						$datas[$c++] = '';
					elseif(isset($t[1]) && $t[0] == '$' && is_numeric(substr($t, 1)))
						$datas[$c++] = $this->value($datas[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == '@' && is_numeric(substr($t, 1)))
						$datas[$c++] = $this->key($datas[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == 'i' && is_numeric(substr($t, 1)))
						$datas[$c++] = $this->of($datas[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == 'a' && is_numeric(substr($t, 1)))
						$datas[$c++] = $this->at($datas[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == 'l' && is_numeric(substr($t, 1)))
						$datas[$c++] = strlen($datas[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == 'c' && is_numeric(substr($t, 1)))
						$codes[$c++] = $datas[substr($t, 1)];
					elseif(isset($t[1]) && $t[0] == 's' && is_numeric(substr($t, 1)))
						$datas[$c++] = $codes[substr($t, 1)];
					elseif(isset($t[1]) && $t[0] == 'h' && is_numeric(substr($t, 1)))
						$datas[$c++] = $datas[substr($t, 1)];
					elseif(isset($t[1]) && $t[0] == 't' && is_numeric(substr($t, 1)))
						$codes[$c++] = strlen($codes[substr($t, 1)]);
					elseif(isset($t[1]) && $t[0] == 'f' && is_numeric(substr($t, 1)))
						$datas[$c++] = @file_get_contents($datas[$t[0]]);
					elseif(isset($t[1]) && $t[0] == 'g' && is_numeric(substr($t, 1)))
						$datas[$c++] = @$GLOBALS[$datas[$t[0]]];
					elseif($k > 0 && !is_numeric($t) && isset($this->vars[$t]))
						$datas[$c++] = $this->getvar($t);
					elseif(isset($t[1]) && $t[0] == '0' && is_numeric(substr($t, 1)))
						$datas[$c++] = (int)base_convert($t, 8, 10);
					elseif(isset($t[1]) && $t[0] == 'x' && is_numeric(substr($t, 1)))
						$datas[$c++] = (int)base_convert(substr($t, 1), 16, 10);
					elseif(isset($t[1]) && $t[0] == 'b' && is_numeric(substr($t, 1)))
						$datas[$c++] = (int)base_convert(substr($t, 1), 2, 10);
					elseif(isset($t[1]) && $t[0] == 'o' && is_numeric(substr($t, 1)))
						$datas[$c++] = (int)base_convert(substr($t, 1), 8, 10);
					elseif(isset($t[1]) && $t[0] == 'n' && is_numeric(substr($t, 1)))
						$datas[$c++] = (int)substr($t, 1);
					elseif(is_numeric($t) && !isset($datas[$t]) && !isset($codes[$t]))
						$datas[$c++] = (float)$t;
					if($c > $pr)
						$t = $c - 1;
				}
			}while($pv !== $q);
			foreach($q as $k=>&$t){
				if(isset($t[0]) && $t[0] == '#')
					$t = substr($t, 1);
			}
			if($q[0] == "set") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->set($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "make") {
				if(isset($datas[$q[1]]))$this->make($datas[$q[1]]);
			}
			elseif($q[0] == "delete") {
				if(isset($datas[$q[1]]))$this->delete($datas[$q[1]]);
			}
			elseif($q[0] == "madd") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->madd($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "msub") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->msub($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "mmul") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->mmul($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "mres") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->mres($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "join") {
				if(isset($datas[$q[1]]) && isset($datas[$q[2]]))$this->join($datas[$q[1]], $datas[$q[2]]);
			}
			elseif($q[0] == "dir") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))$dir = $this->dir($datas[$q[1]]);
				if($dir) {
					$dir->query($codes[$q[2]]);
					$dir->save();
				}
			}
			elseif($q[0] == "mdir") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]])){
					$dir = $this->mdir($datas[$q[1]]);
					if($dir) {
						$dir->query($codes[$q[2]]);
						$dir->save();
					}
				}elseif(isset($datas[$q[1]]))
					$this->mdir($datas[$q[1]]);
			}
			elseif($q[0] == "run") {
				if(isset($codes[$q[1]]))$this->query($codes[$q[1]]);
			}
			elseif($q[0] == "iskey") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->iskey($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "isvalue") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->isvalue($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "isdir") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->isdir($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "islist") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->islist($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "notkey") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if(!$this->iskey($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "notvalue") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if(!$this->isvalue($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "notvalue") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if(!$this->isdir($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "notlist") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if(!$this->islist($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "endquery") {
				return;
			}
			elseif($q[0] == "exit") {
				exit;
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
				if(isset($codes[$q[1]]))$finish.= "\n" . $codes[$q[1]];
			}
			elseif($q[0] == "type") {
				$cs = array();
				foreach(explode("-", $q[1]) as $n) {
					foreach(explode(',', $n) as $m) {
						if(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "iskey" && $this->iskey($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notkey" && !$this->iskey($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "isvalue" && $this->isvalue($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notvalue" && !$this->notvalue($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "isdir" && $this->isdir($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notdir" && !$this->isdir($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "islist" && $this->islist($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notlist" && !$this->islist($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "is" && $this->iskey($datas[$q[1]]) && $this->isvalue($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "exists" && ($this->iskey($datas[$q[1]]) || $this->isvalue($datas[$q[1]])))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "not" && !$this->iskey($datas[$q[1]]) && !$this->isvalue($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notlist" && !$this->islist($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notlastns" && !$this->isLastNS($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "notns" && !$this->isNS($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "islastns" && $this->isLastNS($datas[$q[1]]))$cs[] = $codes[$q[2]];
						elseif(isset($datas[$q[1]]) && isset($codes[$q[2]]) && $m == "isns" && $this->isNS($datas[$q[1]]))$cs[] = $codes[$q[2]];
					}
					foreach($cs as $co)$this->query($co);
				}
			}
			elseif($q[0] == "dump") {
				$this->dump();
			}
			elseif($q[0] == "add"){
				if(isset($datas[$q[1]]))$this->add($datas[$q[1]]);
			}
			elseif($q[0] == "setname"){
				if(isset($datas[$q[1]]))$this->setName($datas[$q[1]]);
			}
			elseif($q[0] == "setdescription"){
				if(isset($datas[$q[1]]))$this->setDescription($datas[$q[1]]);
			}
			elseif($q[0] == "openns"){
				if(isset($datas[$q[1]]))$this->openNS($datas[$q[1]]);
			}
			elseif($q[0] == "backns"){
				$this->backNS();
			}
			elseif($q[0] == "mainns"){
				$this->mainNS();
			}
			elseif($q[0] == "isns") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->isLastNS($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == "islastns") {
				if(isset($datas[$q[1]]) && isset($codes[$q[2]]))
				if($this->isNS($datas[$q[1]]))$this->query($codes[$q[2]]);
			}
			elseif($q[0] == 'out') {
				if(isset($fvars)){
					$lvars = $this->vars;
					$this->vars = $fvars;
					if(isset($codes[$q[1]]))$this->query($codes[$q[1]]);
					$fvars = $this->vars;
					$this->vars = $fvars;
				}elseif(isset($codes[$q[1]]))$this->query($codes[$q[1]]);
			}
			elseif($q[0] == 'print') {
				for($i = 1;isset($q[$i]) && isset($datas[$q[$i]]);)
					print $datas[$q[$i++]];
			}
			elseif($q[0] == 'println') {
				for($i = 1;isset($q[$i]) && isset($datas[$q[$i]]);)
					print $datas[$q[$i++]] . "\n";
			}
			elseif($q[0] == 'vardump') {
				for($i = 1;isset($q[$i]) && isset($datas[$q[$i]]);)
					var_dump($datas[$q[$i++]]);
			}
			elseif($q[0] == 'include') {
				$this->query(@file_get_contents($datas[$q[1]]));
			}
			elseif($q[0] == 'return' || $q[0] == 'ret'){
				$this->setvar('return', $datas[$q[1]]);
				return $datas[$q[1]];
			}
			elseif(isset($q[1]) && $q[1] == '=') {
				if(!isset($q[2]))
					$this->deletevar($q[0]);
				elseif(isset($q[2][1]) && $q[2][0] == '&' && $q[2][1] == '&')
					$this->caddressvar($q[0], substr($q[2], 2));
				elseif(isset($q[2][0]) && $q[2][0] == '&')
					$this->addressvar($q[0], substr($q[2], 1));
				elseif(is_numeric($q[0]))
					$this->set($datas[$q[0]], $datas[$q[1]]);
				else
					$this->setvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '+=') {
				if(!isset($q[2]))
					$this->addvar($q[0], 1);
				elseif(is_numeric($q[0]))
					$this->madd($datas[$q[0]], $datas[$q[1]]);
				else
					$this->addvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '-=') {
				if(!isset($q[2]))
					$this->subvar($q[0], 1);
				elseif(is_numeric($q[0]))
					$this->msub($datas[$q[0]], $datas[$q[1]]);
				else
					$this->subvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '*=') {
				if(!isset($q[2]))
					$this->mulvar($q[0], 2);
				elseif(is_numeric($q[0]))
					$this->mmul($datas[$q[0]], $datas[$q[1]]);
				else
					$this->mulvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '/=') {
				if(!isset($q[2]))
					$this->divvar($q[0], 2);
				elseif(is_numeric($q[0]))
					$this->mdiv($datas[$q[0]], $datas[$q[1]]);
				else
					$this->divvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '%=') {
				if(!isset($q[2]))
					$this->modvar($q[0], 2);
				elseif(is_numeric($q[0]))
					$this->mmod($datas[$q[0]], $datas[$q[1]]);
				else
					$this->modvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '^=') {
				if(!isset($q[2]))
					$this->xorvar($q[0], 1);
				elseif(is_numeric($q[0]))
					$this->mxor($datas[$q[0]], $datas[$q[1]]);
				else
					$this->xorvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '&=') {
				if(!isset($q[2]))
					$this->andvar($q[0], 1);
				elseif(is_numeric($q[0]))
					$this->mand($datas[$q[0]], $datas[$q[1]]);
				else
					$this->andvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '|=') {
				if(!isset($q[2]))
					$this->orvar($q[0], 1);
				elseif(is_numeric($q[0]))
					$this->mor($datas[$q[0]], $datas[$q[1]]);
				else
					$this->orvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '**=') {
				if(!isset($q[2]))
					$this->powvar($q[0], 2);
				elseif(is_numeric($q[0]))
					$this->mpow($datas[$q[0]], $datas[$q[1]]);
				else
					$this->powvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '/*=') {
				if(!isset($q[2]))
					$this->sqrtvar($q[0], 2);
				elseif(is_numeric($q[0]))
					$this->msqrt($datas[$q[0]], $datas[$q[1]]);
				else
					$this->sqrtvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '.=') {
				if(!isset($q[2]))
					$this->joinvar($q[0], '');
				elseif(is_numeric($q[0]))
					$this->mjoin($datas[$q[0]], $datas[$q[1]]);
				else
					$this->joinvar($q[0], $datas[$q[2]]);
			}
			elseif(isset($q[1]) && $q[1] == '..=') {
				if(!isset($q[2]))
					$this->sjoinvar($q[0], '');
				elseif(is_numeric($q[0]))
					$this->msjoin($datas[$q[0]], $datas[$q[1]]);
				else
					$this->sjoinvar($q[0], $datas[$q[2]]);
			}
			elseif($q[0] == 'function'){
				if(isset($q[2]) && isset($codes[$q[2]])){
					$args = array_slice($q, 3);
					if(is_numeric($q[1]))
						$this->set($q[1], $codes[$q[2]]);
					else
						$this->setfunc($q[1], $codes[$q[2]], $args);
				}
			}
			elseif($q[0] == 'call' && isset($this->vars[$q[1]])) {
				$args = array_slice($q, 2);
				$this->call($q[1], $args);
			}
			elseif(isset($this->vars[$q[0]])) {
				$args = array_slice($q, 1);
				foreach($args as &$arg){
					if(isset($datas[$arg]))
						$arg = $datas[$arg];
					else
						$arg = $codes[$arg];
				}
				$this->call($q[0], $args);
			}
		}
		if(isset($fvars))
			$this->vars = $fvars;
		if($finish)$this->query($finish);
	}
	
	// dump
	private function _dump($k){
		$c = 0;
		$this->xnd->all(function($key,$value)use(&$c,$k){
			if($key[0] == "\x09"){
				switch($key[1]){
					case 'n':
						print "$k# name : ".unce(self::decodenz($value))."\n";
					return;
					case 'd':
						print "$k# description : ".unce(self::decodenz($value))."\n";
					return;
					case 'm':
						print "$k# modified time : ".date(DATE_RFC822, (int)self::decodenz($value))."\n";
					return;
					case 'c':
						print "$k# created time :  ".date(DATE_RFC822, (int)self::decodenz($value))."\n";
					return;
				}
				return;
			}
			$key = self::decodeNS($key);
			if(!$key)return;
			++$c;
			if($value[ord($value[0])+1] == "\x09"){
				print "$k#$c dir ".unce($key)."\n";
				self::xnd(new XNDataString(substr_replace($value,'',0,ord($value[0])+2)))->_dump("$k| ");
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
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$this->xnd->set(self::encodeNS($key),"\x01\x01\x0a");
		if($this->save)
			$this->save();
		return $this;
	}
	public function islist($key){
		return $this->xnd->value(self::encodeNS($key)) == "\x01\x01\x0a";
	}
	public function at($x){
		$at = $this->xnd->numberat($x);
		if($at[0][0] == "\x09")
			switch($at[0][1]){
				case 'n':
					return array('name',self::decodenz($at[1]),true);
				case 'd':
					return array('description',self::decodenz($at[1]),true);
				case 'm':
					return array('last_modified_time',self::decodenz($at[1]),true);
				case 'c':
					return array('created_time',self::decodenz($at[1]),true);
			}
		$at[0] = self::decodeNS($at[0]);
		if(!$at[0])return;
		if($at[1][$p = ord($at[1][0])+1] == "\x09")
			return array($at[0],self::xnd(new XNDataString(substr_replace($at[1],'',0,$p+1))));
		elseif($at[1][$p] == "\x0a")
			return array($at[0]);
		return array($at[0],self::decodenz($at[1]));
	}
	public function of($key){
		return $this->xnd->numberof(self::encodeNS($key));
	}
	public function alllist(){
		$keys = $this->xnd->keys("\x01\x01\x0a");
		$kys = array();
		$ns = $this->getNSs();
		foreach($keys as $key){
			if(!$ns || strpos($key, $ns) === 0)
				$kys[] = self::decodeNS($key);
		}
		return $kys;
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
				$at = array($at1,$at2);
				if(isset($at1[2]) && $at1[2])unset($at[0]);
				if(isset($at2[2]) && $at2[2])unset($at[1]);
				if($at == array())
					return false;
				return $at[array_rand($at)];
			}
			$at1 = $this->at(1);
			$at2 = $this->at(2);
			$at3 = $this->at(3);
			$at = array($at1,$at2,$at3);
			if(isset($at1[2]) && $at1[2])unset($at[0]);
			if(isset($at2[2]) && $at2[2])unset($at[1]);
			if(isset($at3[2]) && $at3[2])unset($at[2]);
			if($at == array())
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
	public function search($by,$type = 0){
		$keys = array();
		$values = array();
		switch($type){
			case self::SEARCH_BY:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(strpos($key,$by) === 0)$keys[] = $key;
					if(strpos($value,$by) === 0)$values[] = $value;
				});
			break;
			case self::HAVE_IN:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(strpos($key,$by) != -1)$keys[] = $key;
					if(strpos($value,$by) != -1)$values[] = $value;
				});
			break;
			case self::HAVE_OUT:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(strpos($by,$key) != -1)$keys[] = $key;
					if(strpos($by,$value) != -1)$values[] = $value;
				});
			break;
			case self::HAVE_IN_OUT:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(strpos($key,$by) != -1 || strpos($by,$key) != -1)$keys[] = $key;
					if(strpos($value,$by) != -1 || strpos($by,$value) != -1)$values[] = $values;
				});
			break;
			case self::MATCH_CHARS:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(preg_match("/".implode(' *',array_map(function($x){
						return preg_quote($x,'/');
					},str_split($key)))."/",$key))$keys[] = $key;
					if(preg_match("/".implode(' *',array_map(function($x){
						return preg_quote($x,'/');
					},str_split($value)))."/",$value))$values[] = $value;
				});
			break;
			case self::MATCH_REGEX:
				$this->all(function($key,$value)use(&$keys,&$values,$by){
					if(preg_match($by,$key))$keys[] = $key;
					if(preg_match($by,$value))$values[] = $value;
				});
			break;
			default:
				new XNError("XNData", "invalid search type", XNError::NOTIC);
				return false;
		}
		return array($keys,$values);
	}
	
	// position
	public $position = 1;
	public function currect(){
		return $this->at($this->position);
	}
	public function eof(){
		return $this->count() <= $position || $position;
	}
	public function next($count = null){
		if($count === null)$count = 1;
		$this->position+= $count;
		return $this;
	}
	public function prev($count = null){
		if($count === null)$count = 1;
		$this->position-= $count;
		return $this;
	}
	public function go($index){
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
	public function password_encode($password,$limit = 5242880){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$password = self::encodeon($password);
		return $this->xnd->password_encode($password,$limit);
	}
	public function password_decode($password){
		if($this->type === 'url')
			new XNError("XNDataURL", "Can not change URL address contents", XNError::WARNING, XNError::TTHROW);
		$password = self::encodeon($password);
		return $this->xnd->password_decode($password);
	}

	// compact/extract
	public function compact($directory, $onlyfiles = null){
		$files = @dirscan($directory);
		if($files === false)return false;
		if($onlyfiles === true){
			foreach($files as $file)
				if(is_file($directory .DIRECTORY_SEPARATOR. $file))
					$this->set($file, file_get_contents($directory .DIRECTORY_SEPARATOR. $file));
		}else foreach($files as $file)
			if(is_dir($directory .DIRECTORY_SEPARATOR. $file))
				$this->mdir($file)->compact($directory .DIRECTORY_SEPARATOR. $file);
			else
				$this->set($file, file_get_contents($directory .DIRECTORY_SEPARATOR. $file));
	}
	public function extract($directory){
		if(!file_exists($directory))mkdir($directory);
		$this->read(function($file, $content)use($directory){
			if(is_xndata($content)){
				mkdir($directory .DIRECTORY_SEPARATOR. $file);
				$content->extract($directory .DIRECTORY_SEPARATOR. $file);
			}else file_put_contents($directory .DIRECTORY_SEPARATOR. $file, $content);
		});
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
				$tmp = new XNDataJson($this->xnd->dir($k));
				$tmp->_save((array)$v);
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

class XNDataString {
	private $data = '',$parent = false;
	public function __construct($data = null){
		$this->data = $data !== null ? $data : '';
	}
	public function isChild(){
		return $this->parent !== false;
	}
	public function setme($parent){
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
	public function value($key, $length = null){
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
				if($length !== null && $s > $length)
					$s = $length;
				return substr($data,$c,$s);
			}
			$c+= $s;
		}
		return false;
	}
	public function valen($key){
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
				return $s;
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
		if(@$this->data[0] == "\xff")return array();
		$data = $this->data;
		$value = substr($value,ord($value[0])+1);
		$z = strlen($value);
		$ks = array();
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
				return $data[$c + ord($data[$c]) + 1] == "\x09";
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
				if($data[$c + ($u = ord($data[$c]) + 1)] != "\x09")
					return false;
				$xnd = new XNDataString(substr($data,$c + $u + 1,$s - $u - 1));
				$xnd->setme(array($this,$j));
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
			$func($k);
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
			$func($k,$v);
			$c+= $s;
		}
	}
	public function map($func){
		if(@$this->data[0] == "\xff")return false;
		$data = &$this->data;
		for($c = 0;isset($data[$c]);){
			$t = ord($data[$c++]);
			$s = substr($data,$c,$t);
			$c+= $t;
			$s = xndata::decodesz($s);
			$l = ord($data[$c++]);
			--$s;
			$o = substr($data,$c,$l);
			$c+= $l;
			$s-= $l;
			$h = xndata::decodesz($o);
			$k = substr($data,$c,$h);
			$v = substr($data,$c+$h,$s-$h);
			$value = xndata::encodeel(chr($l).$o.$k, $func($k,$v));
			$data = substr_replace($data,$value,$c-$l-3,$s+$l+3);
			$c+= strlen($value) - $t - 3;
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
			return array($k,$v);
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
		}elseif(is_resource($file)&&xnstream::mode($file)=="r+b");
		else return;
		if($file){
			$this->file = $file;
			rewind($file);
		}else
			fclose($file);
	}
	public function isChild(){
		return $this->parent !== false;
	}
	public function setme($parent){
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
		return xnstream::name($this->file);
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
	public function value($key, $length = null){
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
				$s-= $h;
				if($length !== null && $s > $length)
					$s = $length;
				$r = fread($file,$s);
				rewind($file);
				return $r;
			}
			fseek($file,$s-$h,SEEK_CUR);
		}
		rewind($file);
		return false;
	}
	public function valen($key){
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
				$s-= $h;
				rewind($file);
				return $s;
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
		$ks = array();
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
			$file = xnstream::fclone($this->file,'ab');
			fwrite($file,xndata::encodeel($key,$value));
			fclose($file);
		}
	}
	public function add($key,$value){
		$file = xnstream::fclone($this->file,'ab');
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
			$s-= $h;
			if($k == $key){
				$u = ord(fgetc($file));
				fseek($file,$u,SEEK_CUR);
				if(fgetc($file) != "\x09"){
					rewind($file);
					return false;
				}
				$s-= $u + 2;
				$tmp = tmpfile();
				$xnd = new XNDataFile($tmp);
				$s0 = (int)($s / 1048576);
				$s1 = $s - $s0;
				while(--$s0 >= 0)
					fwrite($tmp,fread($file,1048576));
				if($s1)fwrite($tmp,fread($file,$s1));
				rewind($tmp);
				$xnd->setme(array($this,$j));
				rewind($file);
				return $xnd;
			}
			fseek($file,$s,SEEK_CUR);
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
			$func($k);
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
			$func($k,$v);
		}
		rewind($file);
	}
	public function map($func){
		$file = $this->file;
		$a = tmpfile();
		while(($t = fgetc($file)) !== false){
			$t = ord($t);
			$s = fread($file,$t);
			$s = xndata::decodesz($s);
			$l = ord(fgetc($file));
			--$s;
			$o = fread($file,$l);
			$s-= $l;
			$h = xndata::decodesz($o);
			$k = fread($file,$h);
			$v = fread($file,$s-$h);
			$value = xndata::encodeel(chr($l).$o.$k, $func($k,$v));
			fwrite($a,$value);
		}
		stream_copy_to_stream($file,$a);
		rewind($file);
		rewind($a);
		stream_copy_to_stream($a,$file);
		fclose($a);
		ftruncate($file,ftell($file));
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
			return array($k,$v);
		}
		rewind($file);
		return $o;
	}
	public function password_encode($password, $limit = 5242880){
		if($limit === 0)$limit = $this->size();
		$file = $this->file;
		$tmp = tmpfile();
		$iv = $password . sha1($password) . $password;
		$iv = substr(md5($password), 0, 16);
		while(($content = fread($file,$limit)) !== ''){
			$content = openssl_encrypt($content,'AES-192-CTR',$password,1,$iv);
			$s = xndata::encodesz(strlen($content));
			$l = strlen($s);
			$content = chr($l).$s.$content;
			fwrite($tmp,$content);
		}
		rewind($file);
		rewind($tmp);
		stream_copy_to_stream($tmp,$file);
		rewind($file);
		fclose($tmp);
		return true;
	}
	public function password_decode($password){
		$file = $this->file;
		$tmp = tmpfile();
		$iv = $password . sha1($password) . $password;
		$iv = substr(md5($password), 0, 16);
		while(($l = fgetc($file)) !== false){
			$l = ord($l);
			$s = fread($file,$l);
			$s = xndata::decodesz($s);
			$data = fread($file,$s);
			$data = openssl_decrypt($data,'AES-192-CTR',$password,1,$iv);
			if($data === false)
				return false;
			fwrite($tmp,$data);
		}
		rewind($file);
		rewind($tmp);
		stream_copy_to_stream($tmp,$file);
		rewind($file);
		fclose($tmp);
		return true;
	}
}

class XNDataURL {
	private $url = '';
	public function __construct($file){
		$this->url = $file;
	}
	public function get(){
		return fget($this->url);
	}
	public function size(){
		return strlen($this->get());
	}
	public function locate(){
		return $this->url;
	}
	public function stream(){
		return fopen($this->url,'rb');
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
	public function value($key, $length = null){
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
				if($length !== null && $s > $length)
					$s = $length;
				$r = fread($file,$s);
				fclose($file);
				return $r;
			}
			fread($file,$s-$h);
		}
		fclose($file);
		return false;
	}
	public function valen($key){
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
				fclose($file);
				return $s;
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
		$ks = array();
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
				close($file);
				return $r;
			}
			fread($file,$s-$h);
		}
		close($file);
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
			$s-= $h;
			if($k == $key){
				$u = ord(fgetc($file));
				fread($file,$u);
				if(fgetc($file) != "\x09"){
					fclose($file);
					return false;
				}
				$s-= $u + 2;
				$tmp = tmpfile();
				$xnd = new XNDataFile($tmp);
				$s0 = (int)($s / 1048576);
				$s1 = $s - $s0;
				while(--$s0 >= 0)
					fwrite($tmp,fread($file,1048576));
				if($s1)fwrite($tmp,fread($file,$s1));
				rewind($tmp);
				fclose($file);
				return $xnd;
			}
			fread($file,$s);
		}
		close($file);
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
			$func($k);
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
			$func($k,$v);
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
			return array($k,$v);
		}
		fclose($file);
		return $o;
	}
}

// --------- XNColor ---------- //
class xncolor {
	public static function rgb($red, $green, $blue){
		return ((($red & 0xff) << 16) | (($green & 0xff) << 8) | ($blue & 0xff));
	}
	public static function rgba($red, $green, $blue, $alpha){
		return ((($alpha & 0xff) << 24) | (($red & 0xff) << 16) | (($green & 0xff) << 8) | ($blue & 0xff));
	}
	public static function unrgb($color){
		return array(($color >> 16) & 0xff, ($color >> 8) & 0xff, $color & 0xff);
	}
	public static function unrgba($color){
		return array(($color >> 16) & 0xff, ($color >> 8) & 0xff, $color & 0xff, ($color >> 24) & 0xff);
	}
	private static function _hue($a, $b, $h){
		if($h < 0)
			$h += 1;
		if($h > 1)
			$h -= 1;
		if(6 * $h < 1)
			return $a + ($b - $a) * 6 * $h;
		if(2 * $h < 1)
			return $b;
		if(3 * $h < 2)
			return $a + ($b - $a) * (2 / 3 - $h) * 6;
		return $a;
	}
	public static function hslrgb($h, $s, $l){
		if($s == 0)
			return array($l * 255, $l * 255, $l * 255);
		if($l < 0.5)
			$a = $l * (1 + $s);
		else
			$a = ($l + $s) - ($s * $l);
		$b = 2 * $l - $a;
		return array(
			255 * self::_hue($b, $a, $h + 1 / 3),
			255 * self::_hue($b, $a, $h),
			255 * self::_hue($b, $a, $h - 1 / 3)
		);
	}
	public static function rgbhsl($r, $g, $b){
		$r /= 255;
		$g /= 255;
		$b /= 255;
		$min = min($r, $g, $b);
		$max = max($r, $g, $b);
		$delta = $max - $min;
		$l = ($max + $min) / 2;
		if($delta == 0)
			$s = $h = 0;
		else{
			if($l < 0.5)$s = $delta / ($max + $min);
			else 		$s = $delta / (2 - $max - $min);
			$dr = ((($max - $r) / 6) + ($delta / 2)) / $delta;
			$dg = ((($max - $g) / 6) + ($delta / 2)) / $delta;
			$db = ((($max - $b) / 6) + ($delta / 2)) / $delta;
			if	($r == $max)$h = $db - $dg;
			elseif($g == $max)$h = (1 / 3) + $dr - $db;
			elseif($b == $max)$h = (2 / 3) + $dg - $dr;
			if($h < 0)++$h;
			if($h > 1)--$h;
		}
		return array($h, $s, $l);
	}
	public static function hsl($h, $s, $l){
		$rgb = self::hslrgb($h, $s, $l);
		return self::rgb($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function hsla($h, $s, $l, $a){
		$rgb = self::hslrgb($h, $s, $l);
		return self::rgba($rgb[0], $rgb[1], $rgb[2], $a);
	}
	public static function unhsl($color){
		$rgb = self::unrgb($color);
		return self::rgbhsl($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function unhsla($color){
		$rgba = self::unrgba($color);
		$hsla = self::rgbhsl($rgb[0], $rgb[1], $rgb[2]);
		$hsla[] = $rgb[3];
		return $hsla;
	}
	public static function hsvrgb($h, $s, $v){
		if($s == 0)
			return array($v * 255, $v * 255, $v * 255);
		$h *= 6;
		$i = floor($h);
		$a = $v * (1 - $s);
		$b = $v * (1 - $s * ($h - $i));
		$c = $v * (1 - $s * (1 - ($h - $i)));
		switch($i){
			case 0: return array($v * 255, $c * 255, $a * 255);
			case 1: return array($b * 255, $v * 255, $a * 255);
			case 2: return array($a * 255, $v * 255, $c * 255);
			case 3: return array($a * 255, $b * 255, $v * 255);
			case 4: return array($c * 255, $a * 255, $v * 255);
			default:return array($v * 255, $a * 255, $b * 255);
		}
	}
	public static function rgbhsv($r, $g, $b){
		$r /= 255;
		$g /= 255;
		$b /= 255;
		$min = min($r, $g, $b);
		$max = max($r, $g, $b);
		$delta = $max - $min;
		$v = $max;
		if($max == 0)
		   $s = $h = 0;
		else{
		   $s = $delta / $max;
		   $dr = ((($max - $r) / 6) + ($max / 2)) / $delta;
		   $dg = ((($max - $g) / 6) + ($max / 2)) / $delta;
		   $db = ((($max - $b) / 6) + ($max / 2)) / $delta;
		   if	($r == $max)$h = $b - $dg;
		   elseif($g == $max)$h = (1 / 3) + $dr - $db;
		   elseif($b == $max)$h = (2 / 3) + $dg - $dr;
		   if($h < 0)++$h;
		   if($h > 1)--$h;
		}
		return array($h, $s, $v);
	}
	public static function hsv($h, $s, $v){
		$rgb = self::hsvrgb($h, $s, $v);
		return self::rgb($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function hsva($h, $s, $v, $a){
		$rgb = self::hsvrgb($h, $s, $v);
		return self::rgba($rgb[0], $rgb[1], $rgb[2], $a);
	}
	public static function unhvl($color){
		$rgb = self::unrgb($color);
		return self::rgbhsv($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function unhsva($color){
		$rgba = self::unrgba($color);
		$hsva = self::rgbhsv($rgb[0], $rgb[1], $rgb[2]);
		$hsva[] = $rgb[3];
		return $hsva;
	}
	public static function hslhsv($h, $s, $l){
		$rgb = self::hslrgb($h, $s, $l);
		return self::rgbhsv($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function hsvhsl($h, $s, $v){
		$rgb = self::hsvrgb($h, $s, $v);
		return self::rgbhsl($rgb[0], $rgb[1], $rgb[2]);
	}
	public static function red($color, $r = null){
		if($r === null)return ($color >> 16) & 0xff;
		return (((($color >> 24) << 8) | ($r & 0xff)) << 16) | ($color & 0xffff);
	}
	public static function green($color, $g = null){
		if($g === null)return ($color >> 8) & 0xff;
		return (((($color >> 16) << 8) | ($g & 0xff)) << 8) | ($color & 0xff);
	}
	public static function blue($color, $b = null){
		if($b === null)return $color & 0xff;
		return (($color >> 8) << 8) | ($b & 0xff);
	}
	public static function alpha($color, $a = null){
		if($a === null)return ($color >> 24) & 0xff;
		return ($color & 0xffffff) | (($a & 0xff) << 24);
	}
	public static function rgbhex($r, $g, $b){
		return str_pad(dechex(self::rgb($r, $g, $b)), 6, '0');
	}
	public static function rgbahex($r, $g, $b, $a){
		return str_pad(dechex(self::rgba($r, $g, $b, $a)), 8, '0');
	}
	public static function hexrgb($hex){
		return self::unrgb(hexdec($hex));
	}
	public static function hexrgba($hex){
		return self::unrgba(hexdec($hex));
	}
	public static function islight($r, $g, $b, $threshold = 130){
		return (($r * 299 + $g * 587 + $b * 114 ) / 1000 > $threshold);
	}
	public static function isdark($r, $g, $b, $threshold = 130){
		return (($r * 299 + $g * 587 + $b * 114 ) / 1000 <= $threshold);
	}
	public static function mixrgb($r1, $g1, $b1, $r2, $g2, $b2, $amount = 0){
		$m1 = $amount / 100 + 1;
		$m2 = 2 - $r1;
		return array(
			($r1 * $m1 + $r2 * $m2) / 2,
			($g1 * $m1 + $g2 * $m2) / 2,
			($b1 * $m1 + $b2 * $m2) / 2
		);
	}
	public static function mixrgba($r1, $g1, $b1, $a1, $r2, $g2, $b2, $a2, $amount = 0){
		$m1 = $amount / 100 + 1;
		$m2 = 2 - $r1;
		return array(
			($r1 * $a1 * (1 - $a2) * $m1 + $r2 * $a2 * $m2) / 2,
			($g1 * $a1 * (1 - $a2) * $m1 + $g2 * $a2 * $m2) / 2,
			($b1 * $a1 * (1 - $a2) * $m1 + $b2 * $a2 * $m2) / 2,
			($a1 * (1 - $a2) * $m1 + $a2 * $m2) / 2
		);
	}
	public static function mix($c1, $c2, $amount = 0){
		$c1 = self::unrgb($c1);
		$c2 = self::unrgb($c2);
		return self::mixrgba($c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $amount);
	}
	public static function mixa($c1, $c2, $amount = 0){
		$c1 = self::unrgba($c1);
		$c2 = self::unrgba($c2);
		return self::mixrgba($c1[0], $c1[1], $c1[2], $c1[3], $c2[0], $c2[1], $c2[2], $c2[3], $amount);
	}
	public static function grayscale($r, $g, $b){
		$x = $r * 0.3 + $g * 0.59 + $b * 0.11;
		return array($x, $x, $x);
	}
	public static function invert($r, $g, $b){
		return array(255 - $r, 255 - $g, 255 - $b);
	}
	public static function random(){
		return array(rand(0, 255), rand(0, 255), rand(0, 255));
	}
	public static function lighten($r, $g, $b, $amount = 1){
		$amount /= 100;
		return self::modrgb($r + $r * $amount, $g + $g * $amount, $b + $b * $amount);
	}
	public static function brightness($r, $g, $b, $l){
		$hsl = self::rgbhsl($r, $g, $b);
		$hsl[2] = $l < 0 ? $l - floor($l) + 1 : $l - floor($l);
		return self::rgb($hsl[0], $hsl[1], $hsl[2]);
	}
	public static function modrgb($r, $g, $b){
		return array(
			$r < 0 ? $r % 256 + 256 : $r % 256,
			$g < 0 ? $g % 256 + 256 : $g % 256,
			$b < 0 ? $b % 256 + 256 : $b % 256
		);
	}
	public static function modrgba($r, $g, $b, $a){
		return array(
			$r < 0 ? $r % 256 + 256 : $r % 256,
			$g < 0 ? $g % 256 + 256 : $g % 256,
			$b < 0 ? $b % 256 + 256 : $b % 256,
			$a < 0 ? $a % 256 + 256 : $a % 256
		);
	}
	public static function modhsl($h, $s, $l){
		return array(
			$h < 0 ? $h - floor($h) + 1 : $h - floor($h),
			$s < 0 ? $s - floor($s) + 1 : $s - floor($s),
			$l < 0 ? $l - floor($l) + 1 : $l - floor($l)
		);
	}
	public static function modhsv($h, $s, $l){
		return array(
			$h < 0 ? $h - floor($h) + 1 : $h - floor($h),
			$s < 0 ? $s - floor($s) + 1 : $s - floor($s),
			$v < 0 ? $v - floor($v) + 1 : $v - floor($v)
		);
	}
	public static function saturate($r, $g, $b, $amount = 1){
		$hsl = self::rgbhsl($r, $g, $b);
		$hsl[1] = $hsl[1] + $hsl[1] * ($amount / 100);
		$hsl[1] = $hsl[1] < 0 ? $hsl[1] - floor($hsl[1]) + 1 : $hsl[1] - floor($hsl[1]);
		return self::rgb($hsl[0], $hs[1], $hsl[2]);
	}
	public static function sepia($r, $g, $b){
		return array(
			$r * 0.393 + $g * 0.769 + $b * 0.189,
			$r * 0.349 + $g * 0.686 + $b * 0.168,
			$r * 0.272 + $g * 0.534 + $g * 0.131
		);
	}
	public static function contrast($r, $g, $b, $amount = 1){
		return self::modrgb($r + $amount, $g + $amount, $b + $amount);
	}
	public static function hue($r, $g, $b, $h){
		$hsl = self::rgbhsl($r, $g, $b);
		$hsl[0] = $h < 0 ? $h - floor($h) + 1 : $h - floor($h);
		return self::rgb($hsl[0], $hsl[1], $hsl[2]);
	}
}

// ---------- XNFile ---------- //
class XNGraphicPNG {
	const E_NONE = 0;
	const E_INVALID_HEADER = 1;

	public $error = 0,
		   $width = 0,
		   $height = 0,
		   $data = '',
		   $comments = array(),
		   $pixels = array();
	public function parse($content){
		$this->error = self::E_NONE;
		if(substr($content, 0, 8) != "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
			return $this->error = self::E_INVALID_HEADER;
		$length = strlen($content);
		$offset = 8;
		while($offset < $length){
			$dlen = array_value(unpack('N', substr($content, $offset, 4)), 1);
			$offset += 4;
			$type = substr($content, $offset, 4);
			$offset += 4;
			$raw = array_value(unpack('N', $type), 1);
			$data = substr($content, $offset, $dlen);
			$offset += $dlen + 4;
			switch($type){
				case 'IHDR':
					$this->width  = array_value(unpack('N', substr($data, 0, 4)), 1);
					$this->height = array_value(unpack('N', substr($data, 4, 4)), 1);
					$this->bit_depth = ord($data[8]);
					$this->color_type = ord($data[9]);
					$this->compression_method = ord($data[10]);
					$this->filter_method = ord($data[11]);
					$this->interlace_method = ord($data[12]);
					if($this->compression_method === 0)$this->compression_method = 'zlib';
					$this->color_palette = (bool)($this->color_type & 1);
					$this->true_color = (bool)($this->color_type & 2);
					$this->color_alpha = (bool)($this->color_type & 4);
				break;
				case 'CgBI':
					$this->iphone = true;
				break;
				case 'PLTE':
					$this->palette = array();
					for($i = 0; $i < 256; ++$i){
						$red   = ord($data[$i * 3]);
						$green = ord($data[$i * 3 + 1]);
						$blue  = ord($data[$i * 3 + 2]);
						$this->palette[$i] = xncolor::rgb($red, $green, $blue);
					}
				break;
				case 'tRNS':
					switch($this->color_type){
						case 0:
							$this->transparent_color_gray = array_value(unpack('n', substr($data, 0, 2)), 1);
						break;
						case 2:
							$this->transparent_color_red   = array_value(unpack('n', substr($data, 0, 2)), 1);
							$this->transparent_color_green = array_value(unpack('n', substr($data, 2, 2)), 1);
							$this->transparent_color_blue  = array_value(unpack('n', substr($data, 4, 2)), 1);
						break;
						case 3:
							$this->palette_opacity = array();
							for($i = 0; isset($data[$i]); ++$i)
								$this->palette_opacity[$i] = ord($data[$i]);
					}
				break;
				case 'gAMA':
					$this->gamma = xncrypt::intbe($data) / 100000;
				break;
				case 'cHRM':
					$this->white_x = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->white_y = array_value(unpack('N', substr($data, 4, 4)), 1) / 100000;
					$this->red_x   = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->red_y   = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->green_x = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->green_y = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->blue_x  = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->blue_y  = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
				break;
				case 'sRGB':
					$this->reindering_intent = array_value(unpack('N', $data), 1);
					switch($this->reindering_intent){
						case 0:
							$this->reindering_intent = 'Perceptual';
						break;
						case 100000:
							$this->reindering_intent = 'Relative colorimetric';
						break;
						case 200000:
							$this->reindering_intent = 'Saturation';
						break;
						case 300000:
							$this->reindering_intent = 'Absolute colorimetric';
					}
				break;
				case 'iCCP':
					list($this->profile_name, $compression) = explode("\0", $data, 2);
					$this->compression_method = ord($compression[0]);
					if($this->compression_method === 0)$this->compression_method = 'zlib';
					$this->compression_profile = substr($compression, 1);
				break;
				case 'tEXt':
					list($keyword, $text) = explode("\0", $data, 2);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = array(
						'text' => $text
					);
				break;
				case 'zTXt':
					list($keyword, $otherdata) = explode("\0", $data, 2);
					$compression = ord($otherdata[0]);
					$text = substr($otherdata, 1);
					if($compression === 0)
						$text = gzuncompress($text);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = array(
						'compression' => $compression,
						'text' => $text
					);
				break;
				case 'iTXt':
					list($keyword, $otherdata) = explode("\0", $data, 2);
					$compression = $otherdata[0] != "\0";
					$compression_method = ord($otherdata[1]);
					list($language_tag, $translated_keyword, $text) = explode("\0", substr($otherdata, 2), 3);
					if($compression === true && $compression_method === 0)
						$text = gzuncompress($text);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = array(
						'translated_keyword' => $translated_keyword,
						'language_tag' => $language_tag,
						'compression' => $compression === true ? $compression_method : false,
						'content' => $text
					);
				break;
				case 'bKGD':
					switch($this->color_type){
						case 0:
						case 4:
							$this->background_gray = array_value(unpack('N', $data), 1);
						break;
						case 2:
						case 6:
							$this->background_red   = array_value(unpack('N', substr($data, 0, $this->bit_depth)), 1);
							$this->background_green = array_value(unpack('N', substr($data, $this->bit_depth, $this->bit_depth)), 1);
							$this->background_blue  = array_value(unpack('N', substr($data, $this->bit_depth * 2, $this->bit_depth)), 1);
						break;
						case 3:
							$this->background_index = array_value(unpack('N', $data), 1);
					}
				break;
				case 'pHYs':
					$this->pixels_per_unit_x = array_value(unpack('N', substr($data, 0, 4)), 1);
					$this->pixels_per_unit_y = array_value(unpack('N', substr($data, 4, 4)), 1);
					$this->unit = ord($data[8]);
					if($this->unit === 0)$this->unit = 'unknown';
					elseif($this->unit === 1)$this->unit = 'meter';
				break;
				case 'sBIT':
					switch($this->color_type){
						case 0:
							$this->significant_bits_gray = ord($data[0]);
						break;
						case 2:
						case 3:
							$this->significant_bits_red   = ord($data[0]);
							$this->significant_bits_green = ord($data[1]);
							$this->significant_bits_blue  = ord($data[2]);
						break;
						case 4:
							$this->significant_bits_gray  = ord($data[0]);
							$this->significant_bits_alpha = ord($data[1]);
						break;
						case 6:
							$this->significant_bits_red   = ord($data[0]);
							$this->significant_bits_green = ord($data[1]);
							$this->significant_bits_blue  = ord($data[2]);
							$this->significant_bits_alpha = ord($data[3]);
						break;
					}
				break;
				case 'sPLT':
					list($palettename, $otherdata) = explode("\0", $data, 2);
					$this->palette_name = $palettename;
					$this->sample_depth_bits = ord($otherdata[0]);
					$this->sample_depth_bytes = $this->sample_depth_bits / 8;
					$this->suggested_palette = array(
						'red'   => array(),
						'green' => array(),
						'blue'  => array(),
						'alpha' => array()
					);
					for($c = 1; isset($otherdata[$i]);){
						$this->suggested_palette['red'][]   = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['green'][] = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['blue'][]  = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['alpha'][] = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
					}
				break;
				case 'hIST':
					$this->palette_histogram = array();
					for($c = 0; isset($data[$c]); $c += 2)
						$this->palette_histogram[$c] = array_value(unpack('n', substr($data, $c / 2, 2)), 1);
				break;
				case 'tIME':
					$this->last_modification = gmmktime(
						ord($data[4]),
						ord($data[5]),
						ord($data[6]),
						ord($data[2]),
						ord($data[3]),
						array_key(unpack('n', substr($data, 0, 2)), 1)
					);
				break;
				case 'oFFs':
					$this->position_x = xncrypt::intbe(substr($data, 0, 4), false, true);
					$this->position_y = xncrypt::intbe(substr($data, 4, 4), false, true);
					$this->offset_unit = ord($data[8]);
					if($this->offset_unit === 0)$this->offset_unit = 'unknown';
					elseif($this->offset_unit === 1)$this->offset_unit = 'meter';
				break;
				case 'pCAL':
					list($calibrationname, $otherdata) = explode("\0", $data, 2);
					$this->calibration_name = $calibrationname;
					$this->original_zero = xncrypt::intbe(substr($data, 0, 4), false, true);
					$this->original_max  = xncrypt::intbe(substr($data, 4, 4), false, true);
					$this->equation_type = ord($data[8]);
					switch($this->equation_type){
						case 0:
							$this->equation_type = 'Linear mapping';
						break;
						case 1:
							$this->equation_type = 'Base-e exponential mapping';
						break;
						case 2:
							$this->equation_type = 'Arbitrary-base exponential mapping';
						break;
						case 3:
							$this->equation_type = 'Hyperbolic mapping';
					}
					$this->parameter_count = ord($data[9]);
					$this->parameters = explode("\0", substr($data, 10));
				break;
				case 'sCAL':
					$this->scale_unit = ord($data[0]);
					if($this->scale_unit === 0)$this->scale_unit = 'unknown';
					elseif($this->scale_unit === 1)$this->scale_unit = 'meter';
					list($this->pixel_width, $this->pixel_height) = explode("\0", substr($data, 1));
				break;
				case 'gIFg':
					if(!isset($this->gifs)){
						$this->gifs = array();
						$this->gif_count = 0;
					}
					$this->gifs[$this->gif_count]['disposal_method'] = ord($data[0]);
					$this->gifs[$this->gif_count]['user_input_flag'] = ord($data[1]);
					$this->gifs[$this->gif_count]['delay_time']	  = unpack('n', substr($data, 2, 2));
					++$this->gif_count;
				break;
				case 'gIFx':
					if(!isset($this->extenstions)){
						$this->extensions = array();
						$this->extension_count = 0;
					}
					$this->extensions[$this->extension_count]['application_identifier'] = substr($data, 0, 8);
					$this->extensions[$this->extension_count]['authentication_code']	= substr($data, 8, 3);
					$this->extensions[$this->extension_count]['application_data']	   = substr($data, 11);
					++$this->extension_count;
				break;
				case 'IDAT':
					if($this->compression_method == 'zlib')
						if(isset($this->iphone) && $this->iphone)
							$this->data .= $data;
						else
							$this->data .= gzuncompress($data);
					else
						$this->data .= $data;
				break;
				case 'IEND':
				break 2;
			}
		}
		$offset = 0;
		if($this->color_alpha)
			for($y = 0; $y < $this->height; ++$y){
				$this->pixels[$y] = array();
				++$offset;
				for($x = 0; $x < $this->width; ++$x){
					$this->pixels[$y][$x] = xncrypt::intbe(substr($this->data, $offset, 3));
					$alpha = 127 - ord($this->data[$offset + 3]);
					$this->pixels[$y][$x] = xncolor::alpha($this->pixels[$y][$x], $alpha < 0 ? $alpha + 256 : $alpha);
					$offset += 4;
				}
			}
		else
			for($y = 0; $y < $this->height; ++$y){
				$this->pixels[$y] = array();
				++$offset;
				for($x = 0; $x < $this->width; ++$x){
					$this->pixels[$y][$x] = xncrypt::intbe(substr($this->data, $offset, 3));
					$offset += 3;
				}
			}
		$this->data = '';
		return true;
	}
	public static function make(){
		$file = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
		$data = '';
		if($this->bit_depth)
			foreach($this->pixels as $row){
				$data .= "\0";
				foreach($row as $col){
					$rgb = xncolor::unrgb($col);
					$rgb[3] = $rgb[3] > 127 ? -($rgb[3] - 256 - 127) : -($rgb[3] - 127);
					$file .= xncrypt::strbe(xncolor::rgba($rgb[0], $rgb[1], $rgb[2], $rgb[3]));
				}
			}
		else
			foreach($this->pixels as $row){
				$data .= "\0";
				foreach($row as $col)
					$data .= color::strbe($col, 3);
			}
		if(isset($this->idat_length))
			$data = str_split($data, max(1, min($this->idat_length, 4228250625)));
		else
			$data = str_split($data, 1677721600);
		foreach(array('IHDR', 'CgBI', 'PLTE', 'tRNS', 'gAMA', 'cHRM', 'cHRM', 'sRGB', 'iCCP', 'tEXt', 'bKGD',
					  'pHYs', 'sBIT', 'sPLT', 'hIST', 'tIME', 'oFFs', 'pCAL', 'sCAL', 'gIFg', 'gIFx', 'IDAT', 'IEND') as $header){
			$content = '';
			switch($header){
				case 'IHDR':
					$content .= pack('N', $this->width);
					$content .= pack('N', $this->height);
					$content .= chr($this->bit_depth);
					$content .= chr($this->color_type);
					$content .= chr($this->compression_method);
					$content .= chr($this->filter_method);
					$content .= chr($this->interlace_method);
				break;
				case 'CgBI':
					if($this->iphone !== true)
						continue;
				break;
				case 'PLTE':
					if(!isset($this->palette))continue;
					for($i = 0; $i < 256; ++$i){
						$rgb = xncolor::unrgb($this->palette[$i]);
						$content .= chr($rgb[0]) . chr($rgb[1]) . chr($rgb[2]);
					}
				break;
				case 'tRNS':
					switch($this->color_type){
						case 0:
							if(!isset($this->transparent_color_gray))
							   continue;
							$content .= pack('n', $this->transparent_color_gray);
						break;
						case 2:
							if(!isset($this->transparent_color_red) ||
							   !isset($this->transparent_color_green) ||
							   !isset($this->transparent_color_blue))
							   continue;
							$content .= pack('n', $this->transparent_color_red);
							$content .= pack('n', $this->transparent_color_green);
							$content .= pack('n', $this->transparent_color_blue);
						break;
						case 3:
							if(!isset($this->palette_opacity))
								continue;
							for($i = 0; isset($this->palette_opacity[$i]); ++$i)
								$content .= chr($this->palette_opacity[$i]);
						break;
					}
				break;
				case 'gAMA':
					if(!isset($this->gamma))continue;
					$content .= xncrypt::strbe($this->gamma * 100000);
				break;
				case 'cHRM':
					if(!isset($this->white_x) ||
					   !isset($this->white_y) ||
					   !isset($this->red_x) ||
					   !isset($this->red_y) ||
					   !isset($this->green_x) ||
					   !isset($this->green_y) ||
					   !isset($this->blue_x) ||
					   !isset($this->blue_y))continue;
					$content .=
						pack('N', $this->white_x * 100000) .
						pack('N', $this->white_y * 100000) .
						pack('N', $this->red_x   * 100000) .
						pack('N', $this->red_y   * 100000) .
						pack('N', $this->green_x * 100000) .
						pack('N', $this->green_y * 100000) .
						pack('N', $this->blue_x  * 100000) .
						pack('N', $this->blue_y  * 100000);
				break;
				case 'sRGB':
					if(!isset($this->reindering_intent))
						continue;
					switch($this->reindering_intent){
						case 'Perceptual':
							$content .= pack('N', 0);
						break;
						case 'Relative colorimetric':
							$content .= pack('N', 100000);
						break;
						case 'Saturation':
							$content .= pack('N', 200000);
						break;
						case 'Absolute colorimetric':
							$content .= pack('N', 300000);
					}
				break;
				case 'iCCP':
					if(!isset($this->profile_name) ||
					   !isset($this->compression_method) ||
					   !isset($this->compression_file))continue;
					$content .= $this->profile_name . "\0";
					$content .= chr($this->compression_method == 'zlib' ? 0 : $this->compression_method);
					$content .= $this->compression_profile;
				break;
				case 'tEXt':
					foreach($this->comments as $keyword => $comment){
						$content = $keyword . "\0";
						if(isset($comment['language_tag'])){
							$content .= $comment['compression'] === false ? "\0\0" : "\1" . ord($comment['compression']);
							$content .= $comment['language_tag'] . "\0" . $comment['translated_keyword'] . "\0";
							$content .= $comment['compression'] === 0 ? gzcompress($comment['text'], 9) : $comment['text'];
							$header = 'iEXt';
						}elseif(isset($comment['compression'])){
							$context .= chr($comment['compression']);
							$content .= $comment['compression'] === 0 ? gzcompress($comment['text']) : $comment['text'];
							$header = 'zEXt';
						}else{
							$comment .= $comment['text'];
							$header = 'tEXt';
						}
						$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
					}
				continue;
				case 'bKGD':
					switch($this->color_type){
						case 0:
						case 4:
							if(!isset($this->background_gray))continue;
							$content .= pack('N', $this->background_gray);
						break;
						case 2:
						case 6:
							if(!isset($this->background_red) ||
							   !isset($this->background_green) ||
							   !isset($this->background_blue))continue;
							$content .= pack('N', $this->background_red);
							$content .= pack('N', $this->background_green);
							$content .= pack('N', $this->background_blue);
						break;
						case 3:
							if(!isset($this->background_index))
								continue;
							$content .= pack('N', $this->background_index);
					}
				break;
				case 'pHYs':
					if(!isset($this->pixels_per_unit_x) ||
					   !isset($this->pixels_per_unit_y) ||
					   !isset($this->unit))continue;
					switch($this->unit){
						case 'unknown': $unit = 0; break;
						case 'meter'  : $unit = 1; break;
						default	   : $unit = $this->unit; break;
					}
					$content .= pack('N', $this->pixels_per_unit_x);
					$content .= pack('N', $this->pixels_per_unit_y);
					$content .= chr($unit);
				break;
				case 'sBIT':
					switch($this->color_type){
						case 0:
							if(!isset($this->significant_bits_gray))
								continue;
							$content .= chr($this->significant_bits_gray);
						break;
						case 2:
						case 3:
							if(!isset($this->significant_bits_red) ||
							   !isset($this->significant_bits_green) ||
							   !isset($this->significant_bits_blue))continue;
							$content .= chr($this->significant_bits_red);
							$content .= chr($this->significant_bits_green);
							$content .= chr($this->significant_bits_blue);
						break;
						case 4:
							if(!isset($this->significant_bits_gray) ||
							   !isset($this->significant_bits_alpha))continue;
								$content .= chr($this->significant_bits_gray);
								$content .= chr($this->significant_bits_alpha);
						break;
						case 6:
							if(!isset($this->significant_bits_red) ||
							   !isset($this->significant_bits_green) ||
							   !isset($this->significant_bits_blue) ||
							   !isset($this->significant_bits_alpha))continue;
							$content .= chr($this->significant_bits_red);
							$content .= chr($this->significant_bits_green);
							$content .= chr($this->significant_bits_blue);
							$content .= chr($this->significant_bits_alpha);
						break;
					}
				break;
				case 'sPLT':
					if(!isset($this->palette_name) ||
					   !isset($this->sample_depth_bits) ||
					   !isset($this->sample_depth_bytes) ||
					   !isset($this->suggested_palette))continue;
					$content .= $this->palette_name . "\0";
					$content .= chr($this->sample_depth_bits);
					$count = count($this->suggested_palette['red']);
					for($i = 0; $i < $count; ++$i){
						$content .= xncrypt::strbe($this->suggested_palette['red'][$i]  , $this->sample_depth_bytes);
						$content .= xncrypt::strbe($this->suggested_palette['green'][$i], $this->sample_depth_bytes);
						$content .= xncrypt::strbe($this->suggested_palette['blue'][$i] , $this->sample_depth_bytes);
						$content .= xncrypt::strbe($this->suggested_palette['alpha'][$i], $this->sample_depth_bytes);
					}
				break;
				case 'tIME':
					if(isset($this->update_last_modification) && $this->update_last_modification === true)
						$this->last_modification = gmmktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("n"), gmdate("j"), gmdate("Y"));
					if(!isset($this->last_modification))continue;
					$last_modification = getdate($this->last_modification);
					$this->last_modification = gmmktime(
						ord($data[4]),
						ord($data[5]),
						ord($data[6]),
						ord($data[2]),
						ord($data[3]),
						array_key(unpack('n', substr($data, 0, 2)), 1)
					);
					$content .= pack('n', $last_modification['year']);
					$content .= chr($last_modification['mon']);
					$content .= chr($last_modification['mday']);
					$content .= chr($last_modification['hours']);
					$content .= chr($last_modification['minutes']);
					$content .= chr($last_modification['seconds']);
				break;
				case 'oFFs':
					if(!isset($this->position_x) ||
					   !isset($this->position_y) ||
					   !isset($this->offset_unit))continue;
					switch($this->offset_unit){
						case 'unknown': $unit = 0; break;
						case 'meter':   $unit = 1; break;
						default:		$unit = $this->offset_unit; break;
					}
					$content .= xncrypt::strbe($this->position_x, 4, false, true);
					$content .= xncrypt::strbe($this->position_y, 4, false, true);
					$content .= chr($unit);
				break;
				case 'pCAL':
					if(!isset($this->calibration_name) ||
					   !isset($this->original_zero) ||
					   !isset($this->original_max) ||
					   !isset($this->equation_type) ||
					   !isset($this->parameter_count) ||
					   !isset($this->parameters))continue;
					$content .= $calibration_name . "\0";
					$content .= xncrypt::strbe($this->original_zero, 4, false, true);
					$content .= xncrypt::strbe($this->original_max , 4, false, true);
					switch($this->equation_type){
						case 'Linear mapping':
							$equation_type = 0;
						break;
						case 'Base-e exponential mapping':
							$equation_type = 1;
						break;
						case 'Arbitrary-base exponential mapping':
							$equation_type = 2;
						break;
						case 'Hyperbolic mapping':
							$equation_type = 3;
					}
					$content .= chr($equation_type);
					$content .= chr($this->parameter_count);
					$content .= implode("\0", $this->parameters);
				break;
				case 'sCAL':
					if(!isset($this->scale_unit) ||
					   !isset($this->pixel_width) ||
					   !isset($this->pixel_height))continue;
					switch($this->scale_unit){
						case 'unknown': $unit = 0; break;
						case 'meter':   $unit = 1; break;
						default:		$unit = $this->scale_unit; break;
					}
					$content .= chr($unit);
					$content .= $this->pixel_width . "\0" . $this->pixel_height;
				break;
				case 'gIFg':
					if(!isset($this->gifs))continue;
					foreach($this->gifs as $gif){
						$content = chr($gif['disposal_method']);
						$content.= chr($gif['user_input_flag']);
						$content.= pack('n', $gif['delay_time']);
						$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
					}
				continue;
				case 'gIFx':
					if(!isset($this->extenstions))continue;
					foreach($this->extenstions as $extenstion){
						$content = $extenstion['application_identifier'];
						$content.= $extenstion['authentication_code'];
						$content.= $extenstion['application_data'];
						$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
					}
				continue;
				case 'IDAT':
					if($this->compression_method == 'zlib' && (!isset($this->iphone) || !$this->iphone))
						foreach($data as $packet){
							$packet = gzcompress($packet, 9);
							$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
						}
					else
						foreach($data as $packet)
							$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
				continue;
				case 'IEND':
				break;
			}
			$file .= pack('N', strlen($content)) . $header . $content . xncrypt::hash('crc32', $content);
		}
		return $file;
	}
}
class XNBigGraphicPNG {
	const E_NONE = 0;
	const E_INVALID_HEADER = 1;
	const E_INVALID_FILE = 2;

	public $error = 0,
		   $width = 0,
		   $height = 0,
		   $comments = array(),
		   $pixels;

	public function parse($file){
		$this->error = self::E_NONE;
		if(!is_resource($file)){
			$file = @fopen($file, 'r');
			if($file === false)
				return $this->error = self::E_INVALID_FILE;
		}else rewind($file);
		$this->pixels = tmpfile();
		if(fread($file, 8) != "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
			return $this->error = self::E_INVALID_HEADER;
		$length = xnstream::size($file, true);
		while(ftell($file) < $length){
			$dlen = array_value(unpack('N', fread($file, 4)), 1);
			$type = fread($file, 4);
			$raw = array_value(unpack('N', $type), 1);
			$data = $dlen !== 0 ? fread($file, $dlen) : '';
			$crc = fread($file, 4);
			switch($type){
				case 'IHDR':
					$this->width  = array_value(unpack('N', substr($data, 0, 4)), 1);
					$this->height = array_value(unpack('N', substr($data, 4, 4)), 1);
					$this->bit_depth = ord($data[8]);
					$this->color_type = ord($data[9]);
					$this->compression_method = ord($data[10]);
					$this->filter_method = ord($data[11]);
					$this->interlace_method = ord($data[12]);
					if($this->compression_method)$this->compression_method = 'zlib';
					$this->color_palette = (bool)($this->color_type & 1);
					$this->true_color = (bool)($this->color_type & 2);
					$this->color_alpha = (bool)($this->color_type & 4);
				break;
				case 'CgBI':
					$this->iphone = true;
				break;
				case 'PLTE':
					$this->palette = array();
					for($i = 0; $i < 256; ++$i){
						$red   = ord($data[$i * 3]);
						$green = ord($data[$i * 3 + 1]);
						$blue  = ord($data[$i * 3 + 2]);
						$this->palette[$i] = xncolor::rgb($red, $green, $blue);
					}
				break;
				case 'tRNS':
					switch($this->color_type){
						case 0:
							$this->transparent_color_gray = array_value(unpack('n', substr($data, 0, 2)), 1);
						break;
						case 2:
							$this->transparent_color_red   = array_value(unpack('n', substr($data, 0, 2)), 1);
							$this->transparent_color_green = array_value(unpack('n', substr($data, 2, 2)), 1);
							$this->transparent_color_blue  = array_value(unpack('n', substr($data, 4, 2)), 1);
						break;
						case 3:
							$this->palette_opacity = array();
							for($i = 0; isset($data[$i]); ++$i)
								$this->palette_opacity[$i] = ord($data[$i]);
					}
				break;
				case 'gAMA':
					$this->gamma = xncrypt::intbe($data) / 100000;
				break;
				case 'cHRM':
					$this->white_x = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->white_y = array_value(unpack('N', substr($data, 4, 4)), 1) / 100000;
					$this->red_x   = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->red_y   = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->green_x = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->green_y = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->blue_x  = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
					$this->blue_y  = array_value(unpack('N', substr($data, 0, 4)), 1) / 100000;
				break;
				case 'sRGB':
					$this->reindering_intent = array_value(unpack('N', $data), 1);
					switch($this->reindering_intent){
						case 0:
							$this->reindering_intent = 'Perceptual';
						break;
						case 100000:
							$this->reindering_intent = 'Relative colorimetric';
						break;
						case 200000:
							$this->reindering_intent = 'Saturation';
						break;
						case 300000:
							$this->reindering_intent = 'Absolute colorimetric';
					}
				break;
				case 'iCCP':
					list($this->profile_name, $compression) = explode("\0", $data, 2);
					$this->compression_method = ord($compression[0]);
					if($this->compression_method === 0)$this->compression_method = 'zlib';
					$this->compression_profile = substr($compression, 1);
				break;
				case 'tEXt':
					list($keyword, $text) = explode("\0", $data, 2);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = $text;
				break;
				case 'zTXt':
					list($keyword, $otherdata) = explode("\0", $data, 2);
					$compression = ord($otherdata[0]);
					$text = substr($otherdata, 1);
					if($compression === 0)
						$text = gzuncompress($text);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = $text;
				break;
				case 'iTXt':
					list($keyword, $otherdata) = explode("\0", $data, 2);
					$compression = $otherdata[0] != "\0";
					$compression_method = ord($otherdata[1]);
					list($language_tag, $translated_keyword, $text) = explode("\0", substr($otherdata, 2), 3);
					if($compression === true && $compression_method === 0)
						$text = gzuncompress($text);
					if(!isset($this->comments[$keyword]))$this->comments[$keyword] = array();
					$this->comments[$keyword][] = array(
						'translated_keyword' => $translated_keyword,
						'language_tag' => $language_tag,
						'content' => $text
					);
				break;
				case 'bKGD':
					switch($this->color_type){
						case 0:
						case 4:
							$this->background_gray = array_value(unpack('N', $data), 1);
						break;
						case 2:
						case 6:
							$this->background_red   = array_value(unpack('N', substr($data, 0, $this->bit_depth)), 1);
							$this->background_green = array_value(unpack('N', substr($data, $this->bit_depth, $this->bit_depth)), 1);
							$this->background_blue  = array_value(unpack('N', substr($data, $this->bit_depth * 2, $this->bit_depth)), 1);
						break;
						case 3:
							$this->background_index = array_value(unpack('N', $data), 1);
					}
				break;
				case 'pHYs':
					$this->pixels_per_unit_x = array_value(unpack('N', substr($data, 0, 4)), 1);
					$this->pixels_per_unit_y = array_value(unpack('N', substr($data, 4, 4)), 1);
					$this->unit = ord($data[8]);
					if($this->unit === 0)$this->unit = 'unknown';
					elseif($this->unit === 1)$this->unit = 'meter';
				break;
				case 'sBIT':
					switch($this->color_type){
						case 0:
							$this->significant_bits_gray = ord($data[0]);
						break;
						case 2:
						case 3:
							$this->significant_bits_red   = ord($data[0]);
							$this->significant_bits_green = ord($data[1]);
							$this->significant_bits_blue  = ord($data[2]);
						break;
						case 4:
							$this->significant_bits_gray  = ord($data[0]);
							$this->significant_bits_alpha = ord($data[1]);
						break;
						case 6:
							$this->significant_bits_red   = ord($data[0]);
							$this->significant_bits_green = ord($data[1]);
							$this->significant_bits_blue  = ord($data[2]);
							$this->significant_bits_alpha = ord($data[3]);
						break;
					}
				break;
				case 'sPLT':
					list($palettename, $otherdata) = explode("\0", $data, 2);
					$this->palette_name = $palettename;
					$this->sample_depth_bits = ord($otherdata[0]);
					$this->sample_depth_bytes = $this->sample_depth_bits / 8;
					$this->suggested_palette = array(
						'red'   => array(),
						'green' => array(),
						'blue'  => array(),
						'alpha' => array()
					);
					for($c = 1; isset($otherdata[$i]);){
						$this->suggested_palette['red'][]   = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['green'][] = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['blue'][]  = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
						$this->suggested_palette['alpha'][] = xncrypt::intbe(substr($otherdata, $i, $this->sample_depth_bytes));
						$i += $this->sample_depth_bytes;
					}
				break;
				case 'hIST':
					$this->palette_histogram = array();
					for($c = 0; isset($data[$c]); $c += 2)
						$this->palette_histogram[$c] = array_value(unpack('n', substr($data, $c / 2, 2)), 1);
				break;
				case 'tIME':
					$this->last_modification = gmmktime(
						ord($data[4]),
						ord($data[5]),
						ord($data[6]),
						ord($data[2]),
						ord($data[3]),
						array_key(unpack('n', substr($data, 0, 2)), 1)
					);
				break;
				case 'oFFs':
					$this->position_x = xncrypt::intbe(substr($data, 0, 4), false, true);
					$this->position_y = xncrypt::intbe(substr($data, 4, 4), false, true);
					$this->offset_unit = ord($data[8]);
					if($this->offset_unit === 0)$this->offset_unit = 'unknown';
					elseif($this->offset_unit === 1)$this->offset_unit = 'meter';
				break;
				case 'pCAL':
					list($calibrationname, $otherdata) = explode("\0", $data, 2);
					$this->calibration_name = $calibrationname;
					$this->original_zero = xncrypt::intbe(substr($data, 0, 4), false, true);
					$this->original_max  = xncrypt::intbe(substr($data, 4, 4), false, true);
					$this->equation_type = ord($data[8]);
					switch($this->equation_type){
						case 0:
							$this->equation_type = 'Linear mapping';
						break;
						case 1:
							$this->equation_type = 'Base-e exponential mapping';
						break;
						case 2:
							$this->equation_type = 'Arbitrary-base exponential mapping';
						break;
						case 3:
							$this->equation_type = 'Hyperbolic mapping';
					}
					$this->parameter_count = ord($data[9]);
					$this->parameters = explode("\0", substr($data, 10));
				break;
				case 'sCAL':
					$this->scale_unit = ord($data[0]);
					if($this->scale_unit === 0)$this->scale_unit = 'unknown';
					elseif($this->scale_unit === 1)$this->scale_unit = 'meter';
					list($this->pixel_width, $this->pixel_height) = explode("\0", substr($data, 1));
				break;
				case 'gIFg':
					if(!isset($this->gifs)){
						$this->gifs = array();
						$this->gif_count = 0;
					}
					$this->gifs[$this->gif_count]['disposal_method'] = ord($data[0]);
					$this->gifs[$this->gif_count]['user_input_flag'] = ord($data[1]);
					$this->gifs[$this->gif_count]['delay_time']	  = unpack('n', substr($data, 2, 2));
					++$this->gif_count;
				break;
				case 'gIFx':
					if(!isset($this->extenstions)){
						$this->extensions = array();
						$this->extension_count = 0;
					}
					$this->extensions[$this->extension_count]['application_identifier'] = substr($data, 0, 8);
					$this->extensions[$this->extension_count]['authentication_code']	= substr($data, 8, 3);
					$this->extensions[$this->extension_count]['application_data']	   = substr($data, 11);
					++$this->extension_count;
				break;
				case 'IDAT':
					if($this->compression_method == 'zlib')
						if(isset($this->iphone) && $this->iphone)
							fwrite($this->pixels, $data);
						else
							fwrite($this->pixels, gzuncompress($data));
					else
						fwrite($this->pixels, $data);
				break;
				case 'IEND':
				break 2;
			}
		}
		fseek($this->pixels, 0);
		return true;
	}
	public function make($file){
		$this->error = self::E_NONE;
		if(!is_resource($file)){
			$file = @fopen($file, 'r');
			if($file === false)
				return $this->error = self::E_INVALID_FILE;
		}else rewind($file);
		fwrite($file, "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A");
	}
}
class XNGraphicArray {
	const E_NONE = 0;
	const E_INVALID_PIXELS = 0;

	public $error = 0,
		   $width = 0,
		   $height = 0,
		   $pixels = array();
	public function parse($array){
		$this->error = self::E_NONE;
		if(!isset($array[0][0]))
			return $this->error = self::E_INVALID_PIXELS;
		$width = count($array[0]);
		$height = count($array);
		foreach($array as $row)
			if(!is_array($row) || count($row) != $width)
				return $this->error = self::E_INVALID_PIXELS;
		$this->width = $width;
		$this->height = $height;
		$this->pixels = $array;
		return true;
	}
	public function make(){
		return $this->pixels;
	}
}

/* ---------- XNStream ---------- */
class XNStream {
	public static function seek($stream, $index, $curent = null){
		if($curent === true)
			return fseek($stream, $index, SEEK_CUR);
		if($index < 0)
			return fseek($stream, $index + 1, SEEK_END);
		return fseek($stream, $index, SEEK_SET);
	}
	public static function size($stream, $back = null){
		if($back === true){
			$locate = ftell($stream);
			fseek($stream, 0, SEEK_END);
			$size = ftell($stream);
			fseek($stream, $locate);
			return $size;
		}fseek($stream, 0, SEEK_END);
		return ftell($stream);
	}
	public static function eofsize($stream, $back = null){
		if($back === true){
			$locate = ftell($stream);
			fseek($stream, 0, SEEK_END);
			$size = ftell($stream);
			fseek($stream, $locate);
			return $size - $locate;
		}$locate = ftell($stream);
		fseek($stream, 0, SEEK_END);
		return ftell($stream) - $locate;
	}
	public static function hasIndex($stream, $index, $back = null){
		$locate = ftell($stream);
		if($locate > $index)return true;
		if($back === true){
			fseek($stream, 0, SEEK_END);
			$size = ftell($stream);
			fseek($stream, $locate);
			return $size > $index;
		}fseek($stream, 0, SEEK_END);
		return ftell($stream) > $index;
	}
	public static function index($stream, $index, $back = null){
		if($back === true){
			self::seek($stream, $index);
			return fgetc($stream);
		}$locate = ftell($stream);
		self::seek($stream, $index);
		$c = fgetc($stream);
		fseek($stream, $locate);
		return $c;
	}
	public static function slice($stream, $offset, $limit = null, $back = null){
		if($limit === 0)return '';
		if($back === true){
			if($limit === null){
				self::seek($stream, $offset);
				return stream_get_contents($stream);
			}if($limit < 0)
				$limit += self::size($stream) - $offset;
			self::seek($stream, $offset);
			return fread($stream, $limit);
		}$locate = ftell($stream);
		if($limit === null){
			self::seek($stream, $offset);
			$slice = stream_get_contents($stream);
		}else{
			if($limit < 0)
				$limit += self::size($stream) - $offset;
			self::seek($stream, $offset);
			$slice = fread($stream, $limit);
		}fseek($stream, $locate);
		return $slice;
	}
	public static function slicing($stream, $offset, $limit = null, $back = null){
		if($limit !== null && $limit >= $offset)$limit -= $offset;
		return self::slicing($stream, $offset, $limit, $back);
	}
	public static function name($stream){
		return @array_value(stream_get_meta_data($stream), 'uri');
	}
	public static function mode($stream){
		return @array_value(stream_get_meta_data($stream), 'mode');
	}
	public static function pos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($offset !== null)self::seek($stream, $offset, $back === true);
		$locate = ftell($stream);
		$pos = false;
		$l = strlen($search);
		$read = fread($stream, $l);
		for($i = $locate + $l; !feof($stream); ++$i){
			if($read === $search){
				$pos = $i;break;
			}$read = substr($read, 1) . fgetc($i);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function ipos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($offset !== null)self::seek($stream, $offset, $back === true);
		$search = strtolower($search);
		$locate = ftell($stream);
		$pos = false;
		$l = strlen($search);
		$read = strtolower(fread($stream, $l));
		for($i = $locate + $l; !feof($stream); ++$i){
			if($read === $search){
				$pos = $i;break;
			}$read = substr($read, 1) . strtolower(fgetc($i));
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function rpos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($offset !== null)self::seek($stream, $offset, $back === true);
		$locate = ftell($stream);
		$pos = false;
		$l = strlen($search);
		if($l > $locate)return false;
		fseek($stream, -$l, SEEK_CUR);
		$read = fread($stream, $l);
		fseek($stream, -$l - 1, SEEK_CUR);
		for($i = $locate - $l - 1; $i >= 0; ++$i){
			if($read === $search){
				$pos = $i;break;
			}$read = substr($read, 1) . fgetc($i);
			fseek($stream, -2, SEEK_CUR);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function ripos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($offset !== null)self::seek($stream, $offset, $back === true);
		$search = strtolower($search);
		$locate = ftell($stream);
		$pos = false;
		$l = strlen($search);
		if($l > $locate)return false;
		fseek($stream, -$l, SEEK_CUR);
		$read = strtolower(fread($stream, $l));
		fseek($stream, -$l - 1, SEEK_CUR);
		for($i = $locate - $l - 1; $i >= 0; ++$i){
			if($read === $search){
				$pos = $i;break;
			}$read = substr($read, 1) . strtolower(fgetc($i));
			fseek($stream, -2, SEEK_CUR);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function read($stream, $limit = null, $back = null){
		if($limit == null){
			if($back === true){
				$locate = ftell($stream);
				$read = stream_get_contents($stream);
				fseek($stream, $locate);
				return $read;
			}return stream_get_contents($stream);
		}if($back === true){
			$locate = ftell($stream);
			$read = fread($stream, $limit);
			fseek($stream, $locate);
			return $read;
		}if($limit < 0)
			$limit += self::size($stream, true);
		return fread($stream, $limit);
	}
	public static function prevread($stream, $limit = null){
		if($limit === null)$limit = ftell($stream);
		fseek($stream, -$limit, SEEK_CUR);
		return fread($stream, $limit);
	}
	public static function next($stream, $smallnexting = null){
		fseek($stream, 1, SEEK_CUR);
		$c = fgetc($stream);
		if($smallnexting === true)
			fseek($stream, -1, SEEK_CUR);
		return $c;
	}
	public static function current($stream, $currecting = null){
		$c = fgetc($stream);
		if($currecting === true)
			fseek($stream, -1, SEEK_CUR);
		return $c;
	}
	public static function prev($stream, $preving = null){
		fseek($stream, -1, SEEK_CUR);
		$c = fgetc($stream);
		if($preving === true)
			fseek($stream, -1, SEEK_CUR);
		return $c;
	}
	public static function first($stream, $back = null){
		if($back === true){
			$locate = ftell($stream);
			fseek($stream, 0);
			$c = fgetc($stream);
			fseek($stream, $locate);
			return $c;
		}fseek($stream, 0);
		$c = fgetc($stream);
		fseek($stream, 0);
		return $c;
	}
	public static function last($stream, $back = null){
		if($back === true){
			$locate = ftell($stream);
			fseek($stream, -1, SEEK_END);
			$c = fgetc($stream);
			fseek($sream, $locate);
			return $c;
		}fseek($stream, -1, SEEK_END);
		return fgetc($stream);
	}
	public static function skip($stream){
		fseek($stream, 1, SEEK_CUR);
	}
	public static function packet($stream, $length = 1, $format = null, $back = null){
		if($length === 0)return 0;
		if($back === true){
			$locate = ftell($stream);
			$read = fread($stream, $length);
			fseek($stream, $locate);
		}else $read = fread($stream, $length);
		if($format === null || $format == 'be')
			return xncrypt::intbe($read);
		if($format == 'le')
			return xncrypt::intle($read);
		if($fromat == 'int')
			return xnmath::base_convert($read, 'ascii', 10);
		return unpack($format, $read);
	}
	public static function match($stream, $regex, $flags = 0, $offset = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($offset !== null)self::seek($stream, $offset);
		do{
			$line = fgets($stream);
			if(preg_match($regex, $line, $match, $flags))break;
		}while(!feof($stream));
		if($back === true)fseek($stream, $locate);
		return !isset($match) || $match === array() ? false : $match;
	}
	public static function match_all($stream, $regex, $flags = 0, $offset = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($offset !== null)self::seek($stream, $offset);
		$matches = array();
		do{
			$line = fgets($stream);
			if(preg_match($regex, $line, $match, $flags))
				$matches[] = $match;
		}while(!feof($stream));
		if($back === true)fseek($stream, $locate);
		if(!isset($match) || $matches === array())return false;
		return call_user_func_array('array_array_merge', $matches);
	}
	public static function pregpos($stream, $regex, $offset = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($offset !== null)self::seek($stream, $offset);
		do{
			$line = fgets($stream);
			if(($pos = pregpos($regex, $line)) !== false){
				$pos += ftell($stream) - strlen($line);
				break;
			}
		}while(!feof($stream));
		if($back === true)fseek($stream, $locate);
		return !isset($pos) || $pos === false ? false : $pos;
	}
	public static function delete($stream, $context = null){
		$name = self::name($stream);
		if(!$name)return $name;
		if($context === null)
			unlink($name);
		else
			unlink($name, $context);
	}
	public static function fclone($stream, $mode = null){
		$data = @stream_get_meta_data($stream);
		if(!$data)return false;
		return fopen($data['uri'], $mode === null ? $data['mode'] : $mode);
	}
	public static function reopen($stream, $mode = null){
		$data = @stream_get_meta_data($stream);
		if(!$data)return false;
		fclose($stream);
		return fopen($data['uri'], $mode === null ? $data['mode'] : $mode);
	}
	public static function output(){
		return fopen('php://output', 'w');
	}
	public static function input(){
		return fopen('php://input', 'r');
	}
	public static function canmode($file){
		$file = strtolower($file);
		if($file == 'php://output')return 'w';
		if($file == 'php://input') return 'r';
		if(substr($file, 0, 7) == 'http://' ||
		   substr($file, 0, 8) == 'https://')
			return 'r';
		if(substr($file, 0, 6) == 'ftp://' ||
		   substr($file, 0, 6) == 'php://')return 'rw';
		$mode = '';
		if(is_readable($file))$mode .= 'r';
		if(is_writable($file))$mode .= 'w';
		return $mode;
	}
	public static function utf8get($stream, $back = null){
		if($back === true)$locate = ftell($stream);
		$cur = fgetc($stream);
		if($cur === false)return false;
		$cur = ord($cur);
		if(($cur | 0x07) == 0xF7)
			$char = (($cur				  & 0x07) << 18) &
					((ord(fgetc($stream)) & 0x3F) << 12) &
					((ord(fgetc($stream)) & 0x3F) <<  6) &
					 (ord(fgetc($stream)) & 0x3F);
		elseif(($cur | 0x0F) == 0xEF)
			$char = (($cur				  & 0x0F) << 12) &
					((ord(fgetc($stream)) & 0x3F) <<  6) &
					 (ord(fgetc($stream)) & 0x3F);
		elseif(($cur | 0x1F) == 0xDF)
			$char = (($cur				  & 0x1F) <<  6) &
					 (ord(fgetc($stream)) & 0x3F);
		elseif(($cur | 0x7F) == 0x7F)
			$char = $cur;
		else
			$char = false;
		if($char !== false)
			$char = $char < 256 ? chr($char) : '?';
		if($back === true)fseek($stream, $locate);
		return $char === false ? '' : $char;
	}
	public static function utf8read($stream, $limit = null, $back = null){
		if($limit = null){
			if($back === true){
				$locate = ftell($stream);
				$read = xncrypt::iconv(stream_get_contents($stream), 'utf-8', 'iso-8859-1');
				fseek($stream, $locate);
				return $read;
			}return xncrypt::iconv(stream_get_contents($stream), 'utf-8', 'iso-8859-1');
		}if($back === true){
			$locate = ftell($stream);
			$read = '';
			for($i = 0; $i < $limit; ++$i)
				$read .= self::utf8get($stream);
			fseek($stream, $locate);
			return $read;
		}$read = '';
		for($i = 0; $i < $limit; ++$i)
			$read .= self::utf8get($stream);
		return $read;
	}
	public static function utf8prevread($stream, $limit = null){
		if($limit === null)$limit = ftell($stream);
		fseek($stream, -$limit, SEEK_CUR);
		$read = '';
		for($i = 0; $i < $limit; ++$i)
			$read .= self::utf8get($stream);
		return $read;
	}
	public static function utf8next($stream, $smallnexting = null){
		fseek($stream, 1, SEEK_CUR);
		$c = self::utf8get($stream, $smallnexting);
		return $c;
	}
	public static function utf8current($stream, $currecting = null){
		$c = self::utf8get($stream, $currecting);
		return $c;
	}
	public static function utf8prev($stream, $preving = null){
		fseek($stream, -1, SEEK_CUR);
		$c = self::utf8get($stream, $preving);
		return $c;
	}
	public static function utf8first($stream, $back = null){
		if($back === true){
			$locate = ftell($stream);
			fseek($stream, 0);
			$c = self::utf8get($stream);
			fseek($stream, $locate);
			return $c;
		}fseek($stream, 0);
		$c = self::utf8get($stream);
		fseek($stream, 0);
		return $c;
	}
	public static function utf8skip($stream){
		return self::utf8get($stream) !== false;
	}
	public static function copy($from, $to, $offset = null, $length = null, $back = null){
		if($back === true)$locate = ftell($from);
		if($offset !== null)
			self::seek($from, $offset);
		if($length === null)$length = -1;
		elseif($length < 0)$length -= 1;
		$res = stream_copy_to_stream($from, $to, $length);
		if($back === true)fseek($from, $locate);
		return $res;
	}
	public static function xnsizeread($stream){
		$l = fgetc($stream);
		$r = fread($stream, $l);
		$l = xncrypt::sizedecode($l . $r);
		return fread($stream, $l);
	}
	public static function xnsizewrite($stream, $message){
		return fwrite($stream, xncrypt::sizeencode($message) . $message);
	}
	public static function forcewrite($stream, $message, $length = null){
		if($length === null)$length = strlen($message);
		$writed = 0;
		while($writed !== $length){
			$writed += $result = fwrite($stream, substr($message, $writed), $length - $writed);
			if($result === false || $result === 0)
				return false;
		}
		return true;
	}
	public static function predict($stream, $predict, $back = null){
		if($back === true){
			$length = strlen($predict);
			$res = fread($stream, $length) == $predict;
			fseek($stream, -$length, SEEK_CUR);
			return $res;
		}return fread($stream, strlen($predict)) == $predict;
	}
	public static function predoned($stream, $predoned){
		$length = strlen($predict);
		fseek($stream, -$length, SEEK_CUR);
		return fread($stream, $length) == $predoned;
	}
	public static function writeln($stream, $content, $length = null){
		if($length === null)
			return fwrite($stream, $content . "\n");
		$res = fwrite($stream, $content, $length);
		return fwrite($stream, "\n", 1) + $res;
	}
	public static function readto($stream, $to = "\n", $length = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($to == "\n"){
			if($length === null)
				$res = feof($stream) ? substr(fgets($stream), 0, -1) : false;
			else
				$res = feof($stream) ? rtrim(fgets($stream, $length), "\n") : false;
			if($back === true)fseek($stream, $locate);
			return $res;
		}
		$res = '';
		if($length === null){
			while(($c = fgetc($stream)) !== $to && $c !== false)
				$res .= $c;
			if($res === '' && $c === false)return false;
			if($back === true)fseek($stream, $locate);
			return $res;
		}
		while(($c = fgetc($stream)) !== $to && $c !== false && --$length >= 0)
			$res .= $c;
		if($res === '' && $c === false)return false;
		if($back === true)fseek($stream, $locate);
		return $res;
	}
	public static function readrto($stream, $to = "\n", $length = null, $back = null){
		if($back === true)$locate = ftell($stream);
		$res = '';
		if($length === null){
			while(($c = self::prev($stream)) !== $to && $c !== false)
				$res .= $c;
			if($res === '' && $c === false)return false;
			if($back === true)fseek($stream, $locate);
			return $res;
		}
		while(($c = self::prev($stream)) !== $to && $c !== false && --$length >= 0)
			$res .= $c;
		if($res === '' && $c === false)return false;
		if($back === true)fseek($stream, $locate);
		return $res;
	}
	public static function closed($stream){
		return gettype($stream) === 'resource (closed)';
	}
	public static function lseek($stream, $offset, $whence = 0){
		switch($whence){
			case SEEK_SET:
				fseek($stream, 0);
				while($offset --> 0 && !feof($stream))
					fgets($stream);
			break;
			case SEEK_CUR:
				while($offset --> 0 && !feof($stream))
					fgets($stream);
			break;
			case SEEK_END:
				fseek($stream, -1, SEEK_END);
				while($offset --> 0)
					while(true){
						$ch = fgetc($stream);
						fseek($stream, -2, SEEK_CUR);
						if($ch === false)break 2;
						if($ch == "\n")break;
					}
				fseek($stream, 2, SEEK_CUR);
			break;
		}
	}
	public static function wread($stream, $limit = 1, $back = false){
		if($back === true)$locate = ftell($stream);
		$r = '';
		$l = $limit + 1;
		do{
			$r .= $p = stream_get_contents($f);
			if($p !== '')
				$l = $limit;
		}while($p !== '' || --$l >= 0);
		if($back === true)fseek($stream, $locate);
		return $r;
	}
	public static function wait($stream, $limit = 1){
		if($back === true)$locate = ftell($stream);
		$free = (int)(xnlib::memlimitfree() / 10);
		$l = $limit + 1;
		do{
			$p = stream_get_contents($f, $free);
			if($p !== '')
				$l = $limit;
		}while($p !== '' || --$l >= 0);
		if($back === true)fseek($stream, $locate);
	}
}

__xnlib_data::$endMemory = memory_get_usage();
xnlib::$memoryUsage = __xnlib_data::$endMemory - __xnlib_data::$startMemory;
__xnlib_data::$endTime = microtime(true);
xnlib::$loadTime = __xnlib_data::$endTime - __xnlib_data::$startTime;
?>