<?php // xn php v2.3

if(defined('XNVERSION')){
	trigger_error('library before loaded', E_USER_WARNING);
	return;
}

class __xnlib_data {
	static $startTime;
	static $endTime;
	static $dirname;
	static $dirnamedir;
	static $source = false;
	static $locvar = array();
	static $saveMemory = array();
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
}
class xnlib {
	const version = 2.3;

	static $thumb;
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
}

__xnlib_data::$startTime =  microtime(true);
__xnlib_data::$dirname = substr(__FILE__, 0, strrpos(__FILE__, DIRECTORY_SEPARATOR));

xnlib::$includeAt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
if(!isset(xnlib::$includeAt[0])){
	trigger_error('Can not run directly', E_USER_WARNING);
	exit;
}
xnlib::$includeAt = xnlib::$includeAt[0];

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
	xnlib::$isMobile =  strpos(xnlib::$userAgent, 'Mobile')     !== false ||
						strpos(xnlib::$userAgent, 'Android')    !== false ||
						strpos(xnlib::$userAgent, 'Silk/')      !== false ||
						strpos(xnlib::$userAgent, 'Kindle')     !== false ||
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
	eval('function call_user_method_array($method, $class, $params){
		return $class::$method(...$params);
	};');
}
if(!function_exists('call_user_method')){
	eval('function call_user_method($method, $class, ...$params){
		return $class::$method(...$params);
	};');
}
if(!function_exists('call_user_func')){
	eval('function call_user_func($func, ...$params){
		if(is_array($func)){
			$funct = $func[0];
			unset($func[0]);
			foreach($func as $f)
				$funct = $funct->$f;
			$func = $funct;
		}
		return $func(...$params);
	}');
}
if(!function_exists('call_user_func_array')){
	eval('function call_user_func_array($func, $params){
		if(is_array($func)){
			$funct = $func[0];
			unset($func[0]);
			foreach($func as $f)
				$funct = $funct->$f;
			$func = $funct;
		}
		return $func(...$params);
	}');
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
function thumbCode($func){
	return new ThumbCode($func);
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

if(!defined('PASSWORD_BCRYPT')){
    define('PASSWORD_BCRYPT', 1);
    define('PASSWORD_DEFAULT', 1);
    define('PASSWORD_BCRYPT_DEFAULT_COST', 10);
}

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

if(!function_exists('json_last_error') || !function_exists('json_last_error_msg')){
	__xnlib_data::$jsonerror = JSON_ERROR_NONE;
	function json_last_error() {
		return __xnlib_data::$jsonerror;
	}
	function json_last_error_msg() {
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
}
if(!function_exists('json_encode')){
	function _json_encode($value, $options = 0, $depth = 512){
		if($value === null)
			return 'null';
		if($value === false)
			return 'false';
		if($value === true)
			return 'true';
		switch(gettype($value)){
			case 'string':
				if($options & JSON_NUMERIC_CHECK && is_numeric($value))
					return ($value + 0).'';
				if(~$options & JSON_UNESCAPED_UNICODE)
					$value = unicode_encode($value);
				$value = '"'.str_replace(array('\\','"',"\n","\r","\t"),array('\\\\','\"','\n','\r','\t'),$value).'"';
				if($options & JSON_HEX_TAG)
					$value = str_replace(array('<','>'),array('\u003C','\u003E'),$value);
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
					__xnlib_data::$jsonerror = JSON_ERROR_INF_OR_NAN;
					if(~$options & JSON_PARTIAL_OUTPUT_ON_ERROR)return null;
					return '0';
				}
				if($options & JSON_PRESERVE_ZERO_FRACTION && !is_int($value))
					return $value.'.0';
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
								$str .= str_replace("\n","\n	",_json_encode($val,$options,$depth - 1)) . ",\n	";
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
						if($val[0] == "\0")continue;
						$str .= _json_encode((string)$key,$options,$depth - 1) . ': ' . str_replace("\n","\n	",_json_encode($val,$options,$depth - 1)) . ",\n	";
						if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return null;
					}
					if($str == "{\n	")
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
							$str .= _json_encode($val,$options,$depth - 1) . ',';
						else{
							$str = '';
							break;
						}
						if(__xnlib_data::$jsonerror > 0 && ~$options & JSON_FORCE_OBJECT)return nulll;
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
					if($val[0] == "\0")continue;
					$str .= _json_encode((string)$key,$options,$depth - 1) . ':' . _json_encode($val,$options,$depth - 1) . ',';
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
	function json_encode($value, $options = 0, $depth = 512){
		__xnlib_data::$jsonerror = JSON_ERROR_NONE;
		return _json_encode($value, $options, $depth);
	}
}
if(!function_exists('json_decode')){
	function _json_decode($value, $assoc = false, $depth = 512, $options = 0){
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
				$pos = _json_decode(trim($pos), $assoc, $depth - 1, $options);
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
					$pos[1] = _json_decode(trim($pos[1]), $assoc, $depth - 1, $options);
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
	function json_decode($value, $assoc = false, $depth = 512, $options = 0){
		__xnlib_data::$jsonerror = JSON_ERROR_NONE;
		return _json_decode($value, $assoc, $depth, $options);
	}
}
if(!function_exists('random_int')){
	function random_int($min, $max){
		if($min < $max)
			throw new Error('Minimum value must be less than or equal to the maximum value');
		return rand($min, $max);
	}
}
if(!function_exists('random_bytes')){
	function random_bytes($length){
		if($length < 1)
			throw new Error('Length must be greater than 0');
		$random = '';
		while(--$length >= 0)
			$random .= chr(rand(0, 255));
		return $random;
	}
}
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
		$date = date("Y-n-j G:i:s");
		$this->file = $debug['file'];
		$this->line = $debug['line'];
		$console = "[$date]XN $level > $from: $text in {$debug['file']} on line {$debug['line']}";
		$message = "[$date]<b>XN $level</b> &gt; <i>$from</i>: " . nl2br($text). " in <b>{$debug['file']}</b> on line <b>{$debug['line']}</b><br />";
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
	return is_string($json) && ($json == 'null' || @json_decode($json, true) !== null);
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
function array_string($arr, $js = false){
	if(!is_array($arr) && !is_object($arr)) {
		new XNError("array_string", "can not convert " . gettype($arr). " to array string", XNError::TYPE, XNError::TTHROW);
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
	while($c > 0)	
		$r.= $func($c--);
	return $r;
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
__xnlib_data::$xndataFile = __xnlib_data::$dirname . DIRECTORY_SEPARATOR . 'xndata.xnd';
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
	const remove_KEYBOARD = 'remove_keyboard';
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
			'149.154.167.211'
		));
	}
	public function checkTelegram(){
		if(!$this->isTelegram())
			exit;
	}
	public function update($offset = -1, $limit = 1, $timeout = 0){
		if(isset($this->data) && xnlib::$PUT)return $this->data;
		elseif($this->data = xnlib::$PUT)return $this->data = json_decode($this->data);
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
			print json_encode($args);
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
			$res = json_decode(curl_exec($c));
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
			foreach($message as $msg)
				$this->request("deleteMessage", array(
					"chat_id"	=> $chat,
					"message_id" => $msg
				), $level);
			return true;
		}
		return $this->request("deleteMessage", array(
			"chat_id"	=> $chat,
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
		$args['results'] = is_array($results)? json_encode($results): $results;
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
		$args['media'] = json_encode($media);
		return $this->request("sendMediaGroup", $args, $level);
	}
	public function forwardMessage($chat, $from, $message, $disable = false, $level = 3){
		return $this->request("forwardMessage", array("chat_id" => $chat, "from_chat_id" => $from, "message_id" => $message, "disable_notification" => $disable), $level);
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
		if(isset($update->message))return (object)array("chat" => $update->message->chat, "from" => $update->from);
		if(!isset($update->chat))return (object)array("chat" => $update->from, "from" => $update->from);
		return (object)array("chat" => $update->chat, "from" => $update->from);
	}
	public function getMessage($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->message))
			return $update->message->message_id;
		if(isset($update->message_id))
			return $update->message_id;
		return false;
	}
	public function getDate($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
		if(isset($update->date))return $update->date;
		return false;
	}
	public function getData($update = false){
		$update = $this->getUpdateInType($update);
		if(!$update)return false;
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
		$file = xncrypt::base64urldecode($file);
		$token = xncrypt::base64urldecode($this->token);
		$file = chr(strlen($file)). $file;
		return xncrypt::base64urlencode($file . $token);
	}
	public function fromGFile($chat, $file, $name, $type = "document", $level = 3){
		$r = xncrypt::base64urldecode($file);
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
		$r = xncrypt::base64urldecode($file);
		$p = ord($r[0]);
		$file = substr($r, 1, $p);
		$token = substr($r, $p + 1);
		$bot = new TelegramBot($token);
		return $bot->downloadFile($file, $level);
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
	public function sendMessageFromUpdate($chat, $update = false, $args = array(), $level = 3){
		if($update)$update = $this->dataUpdate()->message;
		elseif(isset($update->message))$update = $update->message;
		$args['file'] = isset($args['file']) 				? $args['file'] :
						isset($args['document']) 			? $args['document'] :
						isset($args['video']) 				? $args['video'] :
						isset($args['voice']) 				? $args['voice'] :
						isset($args['video_note']) 			? $args['video_note'] :
						isset($args['audio']) 				? $args['audio'] :
						isset($args['sticker']) 			? $args['sticker'] :
						isset($args['photo_file_id'])   	? $args['photo_file_id'] :
						isset($args['document_file_id'])	? $args['document_file_id'] :
						isset($args['video_file_id'])   	? $args['video_file_id'] :
						isset($args['voice_file_id']) 		? $args['voice_file_id'] :
						isset($args['video_note_file_id']) 	? $args['video_note_file_id'] :
						isset($args['audio_file_id']) 		? $args['audio_file_id'] :
						isset($args['sticker_file_id']) 	? $args['sticker_file_id'] :
						isset($args['photo_url']) 			? $args['photo_url'] :
						isset($args['document_url']) 		? $args['document_url'] :
						isset($args['video_url']) 			? $args['video_url'] :
						isset($args['voice_url']) 			? $args['voice_url'] :
						isset($args['video_note_url']) 		? $args['video_note_url'] :
						isset($args['audio_url']) 			? $args['audio_url'] :
						isset($args['sticker_url']) 		? $args['sticker_url'] :
						isset($args['file_id']) 			? $args['file_id'] :
						isset($args['photo']) 				? $args['photo'] :
						false;
		if($args['file']) {
			$args['photo'] = $args['document'] = $args['video'] = $args['voice'] = $args['video_note'] = $args['audio'] = $args['sticker'] = $args['photo_file_id'] = $args['document_file_id'] = $args['video_file_id'] = $args['voice_file_id'] = $args['video_note_file_id'] = $args['audio_file_id'] = $args['sticker_file_id'] = $args['photo_url'] = $args['document_url'] = $args['video_url'] = $args['voice_url'] = $args['video_note_url'] = $args['audio_url'] = $args['sticker_url'] = $args['file_id'] = $args['file'];
			if(isset($update->caption))	  $args['caption'] = isset($args['caption']) ? $args['caption'] : $update->caption;
			if(isset($update->photo))	  return $this->sendPhoto($chat, 	 isset($args['photo']) 		? $args['photo'] 	  : end($update->photo)->file_id, $args, $level);
			if(isset($update->video))	  return $this->sendVideo($chat, 	 isset($args['video']) 		? $args['video'] 	  : $update->video->file_id, 	  $args, $level);
			if(isset($update->voice))	  return $this->sendVoice($chat, 	 isset($args['voice']) 		? $args['voice'] 	  : $update->voice->file_id, 	  $args, $level);
			if(isset($update->audio))	  return $this->sendAudio($chat,	 isset($args['audio']) 		? $args['audio'] 	  : $update->audio->file_id, 	  $args, $level);
			if(isset($update->video_note))return $this->sendVideoNote($chat, isset($args['video_note']) ? $args['video_note'] : $update->video_note->file_id, $args, $level);
			if(isset($update->sticker))	  return $this->sendSticker($chat, 	 isset($args['sticker']) 	? $args['sticker'] 	  : $update->sticker->file_id, 	  $args, $level);
			if(isset($update->document))  return $this->sendDocument($chat,  isset($args['document']) 	? $args['document']   : $update->document->file_id,   $args, $level);
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
			$latitude = isset($args['latitude']) ? $args['latitude'] : $update->location->latitude;
			$longitude = isset($args['longitude']) ? $args['longitude'] : $update->location->longitude;
			return $this->sendLocation($chat, $latitude, $longitude, $args, $level);
		}
		if(isset($update->venue)) {
			$latitude = isset($args['latitude']) ? $args['latitude'] : $update->venue->latitude;
			$longitude = isset($args['longitude']) ? $args['longitude'] : $update->venue->longitude;
			$address = isset($args['address']) ? $args['address'] : $update->venue->address;
			$title = isset($args['title']) ? $args['title'] : $update->venue->title;
			return $this->sendVenue($laitude, $longitude, $address, $title, $args, $level);
		}
		return false;
	}
	public function parse_args($method, $args = array()){
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
		if(isset($args['chat_id']) && ($args['chat_id'] == 'chat' || $args['chat_id'] === ''))
			$args['chat_id'] = $this->getUser()->chat;
		elseif(isset($args['chat_id']) && $args['chat_id'] == 'user')
			$args['chat_id'] = $this->getUser()->from;
		if(isset($args['from_chat_id']) && ($args['from_chat_id'] == 'chat' || $args['from_chat_id'] === ''))
			$args['from_chat_id'] = $this->getUser()->chat;
		elseif(isset($args['from_chat_id']) && $args['from_chat_id'] == 'user')
			$args['from_chat_id'] = $this->getUser()->from;
		if(isset($args['user_id']) && $args['user_id'] == 'chat')
			$args['user_id'] = $this->getUser()->chat;
		elseif(isset($args['user_id']) && ($args['user_id'] == 'user' || $args['user_id'] === ''))
			$args['user_id'] = $this->getUser()->from;
		$msg = $this->getMessage();
		if($msg !== false && isset($args['message_id']) && ($args['message_id'] == 'message' || $args['message_id'] === ''))
			$args['message_id'] = $msg;
		if($msg !== false && isset($args['reply_from_message_id']) && ($args['reply_from_message_id'] == 'message' || $args['reply_from_message_id'] === ''))
			$args['reply_from_message_id'] = $msg;
		return $args;
	}
}
class TelegramBotTestOutput extends TelegramBot {
	public function __construct($token = ''){
		$this->token = $token;
		$this->keyboard = new TelegramBotKeyboard;
		$this->inlineKeyboard = new TelegramBotInlineKeyboard;
		$this->queryResult = new TelegramBotQueryResult;
		$this->menu = new TelegramBotButtonSave;
		$this->send = new TelegramBotSends($this);
		$this->msgs = new TelegramBotSaveMsgs;
		$this->forceReply = array("force_reply" => true);
		$this->removeKeyboard = array("remove_keyboard" => true);
		println("token : $token\n");
	}
	public function request($method, $args = array(), $level = 3, $result = array()){
		$args = $this->parse_args($method, $args);
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
		println("# $method");
		foreach($args as $name => $arg)
			switch($name){
				case 'chat_id':
					println("| to : $arg");
				break;
				case 'from_chat_id':
					println("| from : $arg");
				break;
				case 'user_id':
					println("| to : $arg");
				break;
				case 'text':
					println("| text : $arg");
				break;
				case 'photo_id':
				case 'video_id':
				case 'voice_id':
				case 'audio_id':
				case 'thumb':
				case 'thumb_id':
				case 'file_id':
				case 'file':
				case 'document_id':
				case 'sticker_id':
					println("| " . array_value(explode('_', $name), 0) . " : $arg");
				break;
				case 'video_note_id':
					println("| video note : $arg");
				break;
				case 'longitude':
				case 'latitude':
				case 'address':
					println("| location $name : $arg");
				break;
				case 'title':
					println("| title : $arg");
				break;
				case 'parse_mode':
					println("| parse mode : $arg");
				break;
				case 'message_id':
					println("| message : $arg");
				break;
				case 'reply_to_message_id':
					println("| reply to message : $arg");
				break;
				case 'mark_down':
					$data = '| ';
					$args = json_decode($arg, true);
					if(isset($arg['keyboard'])){
						if(isset($arg['keyboard_resize']) && $arg['keyboard_resize'])
							$data .= 'small ';
						$data .= 'keyboard ';
						$arg = $arg['keyboard'];
					}elseif(isset($arg['inline_keyboard'])){
						$data .= 'inline keyboard';
						$arg = $arg['inline_keyboard'];
					}else break;
					println($data);
					foreach($arg as $line){
						println('| line ' . count($line) . 'buttons');
						foreach($line as $btn){
							println('| | ' . $btn['text']);
							if(isset($btn['url']))
								println('| | | URL ' . $btn['url']);
							elseif(isset($btn['switch_inline_query']))
								println('| | | switch inline query : ' . $btn['switch_inline_query']);
							elseif(isset($btn['switch_inline_query_current_chat']))
								println('| | | switch inline query current chat : ' . $btn['switch_inline_query_current_chat']);
							elseif(isset($btn['callback_data']))
								println('| | | callback query : ' . $btn['callback_query']);
							else continue;
						}
					}
				break;
				case 'caption':
					println("| caption : $arg");
				break;
				case 'phone_number':
					println("| phone number : $arg");
				break;
				case 'duration':
					println("| $name : $arg");
				break;
				case 'first_name':
					println("| first name : $arg");
				break;
				case 'last_name':
					println("| last name : $arg");
				break;
				case 'callback_query_id':
					println("| callback query id : $arg");
				break;
				case 'inline_query_id':
					println("| inline query id : $arg");
				break;
				default:
					println("| $name : $arg");
			}
		echo "\n";
		return (object)array('ok' => true, 'result' => json_decode(json_encode($result)));
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
		$res = json_decode($result,true);
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
		$f = fopen($file, 'r');
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
		$f = fopen($file, 'w');
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
	public static function attach($file_id,$type = null){
		$bot = self::getbot();
		if($type == "text")$result = $bot->sendMessage("@tebrobot",$file_id);
		else $result = $bot->sendFile("@tebrobot",$file_id);
		if(!$result || !$result->ok)return false;
		return $result->result->message_id;
	}
}
class XNPWRTelegram {
	public static function getId($username){
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
	return xnstring::end($file, DIRECTORY_SEPARATOR);
}
function fileformat($file){
	$f = xnstring::end($file, '.');
	return strhave($f, DIRECTORY_SEPARATOR)? false : $f;
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
function fwget($file, $limit = 1){
	$f = fopen($file, 'r');
	if(!$f)return false;
	$r = '';
	$l = $limit + 1;
	do{
		$r .= $p = stream_get_contents($f);
		if($p !== '')
			$l = $limit;
	}while($p !== '' || --$l >= 0);
	fclose($f);
	return $r;
}
function filewait($file, $limit = 1){
	$f = fopen($file, 'r');
	if(!$f)return false;
	$l = $limit + 1;
	do{
		$p = stream_get_contents($f);
		if($p !== '')
			$l = $limit;
	}while($p !== '' || --$l >= 0);
	$s = ftell($f);
	fclose($f);
	return $s;
}
function fwput($file, $content, $limit = 1){
	filewait($file, $limit);
	return fput($file, $content);
}
function fwadd($file, $content, $limit = 1){
	filewait($file, $limit);
	return fadd($file, $content);
}
function fwgetjson($file, $json = false, $limit = 1){
	return json_decode(fwget($file, $limit), $json);
}
function fwputjson($file, $content, $json = false, $limit = 1){
	return fwput($file, json_encode($content, $json), $limit);
}
function fwaddjson($file, $content, $json = false, $limit = 1){
	$f = fopen($file, 'r+b');
	if(!$f)return false;
	$r = '';
	$l = $limit + 1;
	do{
		$r .= $p = fread($f, $limit);
		if($p !== '')
			$l = $limit;
	}while($p !== '' || --$l >= 0);
	rewind($f);
	$r = json_decode($r, true);
	$r = array_merge($r, (array)$content);
	$w = fwrite($f, json_encode($r, $json));
	fclose($f);
	return $w;
}
function get_resource_id($resource){
	mustbe($resource, 'resource');
	return array_search($resource, get_resources());
}
function dirdel($dir){
	$s = scandir($dir);
	if(isset($s[0])){
		if($s[0] == '.')unset($s[0]);
		if($s[0] == '..')unset($s[0]);
		if(isset($s[1])){
			if($s[1] == '.')unset($s[1]);
			if($s[1] == '..')unset($s[1]);
		}
	}
	foreach($s as $f) {
		if(is_dir($dir .DIRECTORY_SEPARATOR. $f))dirdel($dir .DIRECTORY_SEPARATOR. $f);
		else unlink($dir .DIRECTORY_SEPARATOR. $f);
	}
	return rmdir($dir);
}
function dirscan($dir){
	$s = scandir($dir);
	if(isset($s[0])){
		if($s[0] == '.')unset($s[0]);
		if($s[0] == '..')unset($s[0]);
		if(isset($s[1])){
			if($s[1] == '.')unset($s[1]);
			if($s[1] == '..')unset($s[1]);
		}
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
function xnrand(&$xnrand){
	if(!is_array($xnrand) || !$xnrand) {
		new XNError("xnrand", "give a range array", XNError::NOTIC);
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
function xndateoption($date = 1){
	if($date == 2)return -19603819800;
	if($date == 3)return -18262450800;
	if($date == 4)return -62167219200;
	return 0;
}
function xntimeoption($time){
	$tmp = new DateTimeZone($time);
	$tmp = new DateTime(null, $tmp);
	return $tmp->getOffset();
}
function xntime($option = 0, $unix = false){
	return ($unix === false ? microtime(true): $unix) + $option;
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
function msleep($seconds, $microseconds){
	$st = explode(' ', microtime(), 2);
	$st[1] = (int)substr($st, 2) + $microseconds;
	$st[0] = (int)$st[0] + $seconds;
	do{
		$mc = explode(' ', microtime(), 2);
		$mc[1] = (int)substr($mc, 2);
	}while($mc[0] < $st[0] && $mc[1] < $st[1]);
}
function nsleep($seconds, $nanoseconds){
	return time_nanosleep($seconds, $nanoseconds);
}
function is_serialized($data){
	return (@unserialize($data) !== false || $data == 'b:0;');
}
function array_random($x){
	return $x[array_rand($x)];
}
function chars_random($x){
	$x = str_split($x);
	return $x[array_rand($x)];
}
function array_clone($array){
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
function locvar_locate($offset = 2,$limit = 0,$type = 'ictf'){
	$trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit);
	while(--$offset >= 0)
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
function locvar_set($key,$value,$data = array()){
	__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)][$key] = $value;
}
function locvar_get($key,$data = array()){
	return @__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)][$key];
}
function locvar_isset($key,$data = array()){
	return isset(__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)][$key]);
}
function locvar_unset($key,$data = array()){
	unset(__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)][$key]);
}
function locvar_delete($data = array()){
	unset(__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)]);
}
function locvar_list($data = array()){
	return array_keys(__xnlib_data::$locvar[call_user_func_array('locvar_locate', $data)]);
}
function strprogress($p1, $p2, $c, $x, $n, $o = ''){
	if($n > $x)swap($x, $n);
	$p = (int)($n / $x * $c);
	if($p == $c)return str_repeat($p1, $p). $o;
	if($p == 0)return $o . str_repeat($p2, $c);
	return str_repeat($p1, $p) . $o . str_repeat($p2, $c - $p);
}
function clockanalogimage($req = array(), $rs = null){
	$size = isset($req['size'])?$req['size']:512;
	$borderwidth = isset($req['borderwidth'])?$req['borderwidth']:3;
	$bordercolor = isset($req['bordercolor'])?$req['bordercolor']:'000';
	$numberspace = isset($req['numberspace'])?$req['numberspace']:76;
	$line1space = isset($req['line1space'])?$req['line1space']:98;
	$line1length = isset($req['line1length'])?$req['line1length']:10;
	$line1width = isset($req['line1width'])?$req['line1width']:1;
	$line1color = isset($req['line1color'])?$req['line1color']:'000';
	$line1type = isset($req['line1type'])?$req['line1type']:3;
	$line2space = isset($req['line2space'])?$req['line2space']:98;
	$line2length = isset($req['line2length'])?$req['line2length']:10;
	$line2width = isset($req['line2width'])?$req['line2width']:1;
	$line2color = isset($req['line2color'])?$req['line2color']:'000';
	$line2type = isset($req['line2type'])?$req['line2type']:3;
	$line3space = isset($req['line3space'])?$req['line3space']:98;
	$line3length = isset($req['line3length'])?$req['line3length']:10;
	$line3width = isset($req['line3width'])?$req['line3width']:1;
	$line3color = isset($req['line3color'])?$req['line3color']:'000';
	$line3type = isset($req['line3type'])?$req['line3type']:3;
	$numbersize = isset($req['numbersize'])?$req['numbersize']:20;
	$numbertype = isset($req['numbertype'])?$req['numbertype']:1;
	$hourcolor = isset($req['hourcolor'])?$req['hourcolor']:'000';
	$mincolor = isset($req['mincolor'])?$req['mincolor']:'000';
	$seccolor = isset($req['seccolor'])?$req['seccolor']:'f00';
	$hourlength = isset($req['hourlength'])?$req['hourlength']:45;
	$minlength = isset($req['minlength'])?$req['minlength']:70;
	$seclength = isset($req['seclength'])?$req['seclength']:77;
	$hourwidth = isset($req['hourwidth'])?$req['hourwidth']:5;
	$minwidth = isset($req['minwidth'])?$req['minwidth']:5;
	$secwidth = isset($req['secwidth'])?$req['secwidth']:1;
	$hourtype = isset($req['hourtype'])?$req['hourtype']:3;
	$mintype = isset($req['mintype'])?$req['mintype']:3;
	$sectype = isset($req['sectype'])?$req['sectype']:3;
	$hourcenter = isset($req['hourcenter'])?$req['hourcenter']:0;
	$mincenter = isset($req['mincenter'])?$req['mincenter']:5;
	$seccenter = isset($req['seccenter'])?$req['seccenter']:3;
	$colorin = isset($req['colorin'])?$req['colorin']:'fff';
	$colorout = isset($req['colorout'])?$req['colorout']:'fff';
	$circlecolor = isset($req['circlecolor'])?$req['circlecolor']:'false';
	$circlewidth = isset($req['circlewidth'])?$req['circlewidth']:3;
	$circlespace = isset($req['circlespace'])?$req['circlespace']:60;
	$circle = $circlecolor == 'false'?'':"/hcc$circlecolor/hcw$circlewidth/hcd$circlespace";
	$shadow = isset($req['shadow'])?'/hwc' . ($req['shadow']):'';
	$hide36912 = isset($req['hide3,6,9,12'])?'/fav0':'';
	$hidenumbers = isset($req['hidenumbers'])?'/fiv0':'';
	$numbercolor = isset($req['numbercolor'])?$req['numbercolor']:'000';
	$numberfont = isset($req['numberfont'])?$req['numberfont']:1;
	$get = "https://www.timeanddate.com/clocks/onlyforusebyconfiguration.php/i6554451/n246/szw$size/" . "szh$size/hoc000/hbw$borderwidth/hfceee/cf100/hncccc/fas$numbersize/fnu$numbertype/fdi$numberspace/" . "mqc$line1color/mql$line1length/mqw$line1width/mqd$line1space/mqs$line1type/mhc$line2color/mhl$line2length/" . "mhw$line2width/mhd$line2space/mhs$line2type/mmc$line3color/mml$line3length/mmw$line3width/mmd$line3space/" . "mms$line3type/hhc$hourcolor/hmc$mincolor/hsc$seccolor/hhl$hourlength/hml$minlength/hsl$seclength/" . "hhs$hourtype/hms$mintype/hss$sectype/hhr$hourcenter/hmr$mincenter/hsr$seccenter/hfc$colorin/hnc$colorout/" . "hoc$bordercolor$circle$shadow$hide36912$hidenumbers/fac$numbercolor/fan$numberfont";
	if(isset($req['special']))$get = "http://free.timeanddate.com/clock/i655jtc5/n246/szw$size/szh$size/hoc00f/hbw0/hfc000/cf100/hgr0/facf90/mqcfff/mql6/mqw2/mqd74/mhcfff/mhl6/mhw1/mhd74/mmcf90/mml4/mmw1/mmd74/hhcfff/hmcfff";
	$get = screenshot($get . '?' . rand(0, 999999999) . rand(0, 999999999) . rand(0, 999999999), 1280, true);
	$im = imagecreatefromstring($get);
	$im2 = imagecrop($im, array('x' => 0, 'y' => 0, 'width' => $size, 'height' => $size));
	imagedestroy($im);
	if($rs === true)return $im2;
	$get = imagepngstring($im2);
	imagedestroy($im2);
	return $get;
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
function virusscanner($file){
	$key = '639ed0eea3f1b650a7c35ef6dac6685f83c01cf08c67d44d52b043f5d26f5519';
	if(file_exists($file)) {
		$post = array('apikey' => $key, 'file' => new CURLFile($file));
	}
	elseif(strpos($file, '://')> 0) {
		$post = array('apikey' => $key, 'url' => $file);
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
	canbe($callable, 'callable|array');
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
	canbe($callable, 'callable|array');
	$outer = get_callable_outer($callable);
	return trim(substr($outer, strpos($outer, '{') + 1, -1));
}
function closure_of_callable($callable){
	canbe($callable, 'callable');
	if(!is_string($callable))
		return eval('return ' . unce($callable) . ';');
	$code = get_callable_outer($callable);
	$code = substr_replace($code, '', strpos($code, $callable), strlen($callable));
	return eval("return $code;");
}
function get_callable_args($callable){
	canbe($callable, 'callable|object:ReflectionFunction');
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
	canbe($callable, 'callable|object:ReflectionFunction');
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
	canbe($classname, 'object|str');
	if(is_object($classname))
		$classname = get_class($classname);
	$params = func_get_args();
	unset($params[0]);
	$args = $params === array() ? '' : '$params[' . implode('],$params[', array_keys($params)) . ']';
	eval('$object = new ' . $classname . '(' . $args . ');');
	return $object;
}
function call_class_constructor_array($classname, $params = array()){
	canbe($classname, 'object|str');
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
function str_offset($str, $algo = 'x+y'){
	for($c = 0; isset($str[$c]); ++$c)$str[$c] = chr(chrget((int)eval("return " . str_replace(array('x', 'y'), array(ord($str[$c]), $c), $algo). ";")));
	return $str;
}
function str_offset_encode($str, $key = 'x'){
	if($key === null)$key = "\x01";
	for($c = 0; isset($key[$c]); ++$c)$algo.= '+' . ord($key[$c]). '*y';
	return str_foffset($str, $algo);
}
function str_offset_decode($str, $key = 'x'){
	if($key === null)$key = "\x01";
	for($c = 0; isset($key[$c]); ++$c)$algo.= '-' . ord($key[$c]). '*y';
	return str_foffset($str, $algo);
}
function REMOTE_ADDTR_encode($r){
	return pack('c*', explode('.', $r));
}
function REMOTE_ADDTR_decode($r){
	return implode('.', unpack('c*', $r));
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
function random_binary($length = null){
	if($length === null)$length = 1;
	$str = '';
	while(--$length >= 0)
		$str .= rand(0,1);
	return $str;
}
function random_binary_bytes($length = null){
	if($length === null)$length = 1;
	$str = random_binary($length);
	return xncrypt::bindecode(str_pad($str, floor(strlen($str) / 8 + 1) * 8, '0', STR_PAD_LEFT));
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
	public static function shl($str, $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, 0, $shift);
	}
	public static function shr($str, $shift = 1){
		$l = strlen($str);
		$shift = $shift < 0 ? 1 : $shift % $l;
		return substr($str, 0, $l - $shift);
	}
	public static function usedchars($str){
		return array_unique(str_split($str));
	}
	public static function max(){
		$chars = func_get_args();
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = - 1;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))> $l)$l = $h;
		return $l;
	}
	public static function min(){
		$chars = func_get_args();
		if(isset($chars[0][1]))$chars = str_split($chars[0]);
		elseif(is_array(@$chars[0]))$chars = $chars[0];
		$chars = array_unique($chars);
		$l = 256;
		for($c = 0; isset($chars[$c]); ++$c)
		if(($h = ord($chars[$c]))< $l)$l = $h;
		return $l;
	}
	public static function range(){
		$chars = func_get_args();
		return range(call_user_func_array(array('XNString', 'min'), $chars),call_user_func_array(array('XNString', 'max'), $chars));
	}
	public static function end($str, $im){
		return substr($str, strrpos($str, $im) + 1);
	}
	public static function start($str, $im){
		return substr($str, 0, strpos($str, $im));
	}
	public static function noend($str, $im){
		return substr($str, 0, strrpos($str, $im));
	}
	public static function nostart($str, $im){
		return substr($str, strpos($str, $im) + 1);
	}
	public static function endi($str, $im){
		return substr($str, strripos($str, $im) + 1);
	}
	public static function starti($str, $im){
		return substr($str, 0, stripos($str, $im));
	}
	public static function noendi($str, $im){
		return substr($str, 0, strripos($str, $im));
	}
	public static function nostarti($str, $im){
		return substr($str, stripos($str, $im) + 1);
	}
	public static function char($str, $x){
		return @$str[$x];
	}
	public static function islength($str, $x){
		return isset($str[$x - 1]);
	}
	public static function endchar($str){
		return $str[strlen($str)- 1];
	}
	public static function startby($str, $by){
		return strpos($str, $by) === 0;
	}
	public static function endby($str, $by){
		return strrpos($str, $by) === strlen($str) - strlen($by);
	}
	public static function startiby($str, $by){
		return stripos($str, $by) === 0;
	}
	public static function endiby($str, $by){
		return strripos($str, $by) === strlen($str) - strlen($by);
	}
	public static function match($str, $by){
		return $str == $by;
	}
	public static function matchi($str, $by){
		return strtolower($str) == strtolower($by);
	}
	public static function toString($str = 20571922739462){
		if($str === 20571922739462)return '';
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
	const ASCII_RANGE = "\0\1\2\3\4\5\6\7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x20\x21\x22\x23\x24\x25\x26\x27\x28\x29\x2a\x2b\x2c\x2d\x2e\x2f\x30\x31\x32\x33\x34\x35\x36\x37\x38\x39\x3a\x3b\x3c\x3d\x3e\x3f\x40\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4a\x4b\x4c\x4d\x4e\x4f\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5a\x5b\x5c\x5d\x5e\x5f\x60\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6a\x6b\x6c\x6d\x6e\x6f\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7a\x7b\x7c\x7d\x7e\x7f\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8a\x8b\x8c\x8d\x8e\x8f\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9a\x9b\x9c\x9d\x9e\x9f\xa0\xa1\xa2\xa3\xa4\xa5\xa6\xa7\xa8\xa9\xaa\xab\xac\xad\xae\xaf\xb0\xb1\xb2\xb3\xb4\xb5\xb6\xb7\xb8\xb9\xba\xbb\xbc\xbd\xbe\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf7\xf8\xf9\xfa\xfb\xfc\xfd\xfe\xff";
	const BITFLAGS_RANGE = "\0\1\2\4\x8\x10\x20\x40\x80";
	const CONTROL_RANGE = "\0\1\2\3\4\5\6\7\x8\x9\xa\xb\xc\xd\xe\xf\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x7f";
	const BLANK_RANGE = " \t";
	const BASE2_RANGE = '01';
	const HEXA_RANGE = '0123456789abcdefABCDEF';
	const HEX_RANGE  = '0123456789abcdef';
	const HEXU_RANGE = '0123456789ABCDEF';
	const BASE4_RANGE = '01234';
	const BASE64_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	const BASE64T_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	const BASE64URL_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
	const BCRYPT64_RANGE = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	const BASE32_RANGE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789=';
	const ALPHBA_NUMBERS_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	const WORD_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
	const GMAIL_USERNAME_RANGE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-_';
	const TOKEN_REGEX = "/[0-9]{4,16}:AA[GFHE][a-zA-Z0-9-_]{32}/";
	const NUMBER_REGEX = "/[0-9]+(?:\.[0-9]+){0,1}|\.[0-9]+|[0-9]+\./";
	const HEX_REGEX = "/[0-9a-fA-F]+/";
	const BINARY_REGEX = "/[01]+/";
	const LINK_REGEX = "/(?:[a-zA-Z0-9]+:\/\/){0,1}(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))(?:\?[^ \n\r\t\/\\#]*){0,1}(?:#[^ \n\r\t\/]*){0,1}/";
	const EMAIL_REGEX = "/(?:[^ \n\r\t\/\\#?@]+)@(?:(?:[^ \n\r\t\.\/\\#?]+\.)*[^ \n\r\t\.\/\\#@?]{1,61}\.[^ \n\r\t\.\/\\#@?]{2,})/";
	const FILE_REGEX = "/[^ \n\r\t\/\\#@?]+/";
	const DIRACTORY_REGEX = "/(?:(?:(?:\/+)[^ \n\r\t\/\\#@?]+)*(?:\/*))/";
	public static function number_of_alphba($char){
		return strpos(self::ALPHBA_RANGE, substr($char, 0, 1)) % 26 + 1;
	}
	public static function lalphba_char_at($index){
		return array_value(self::ALPHBA_LOWER_RANGE, abs($index) % 26);
	}
	public static function ualphba_char_at($index){
		return array_value(self::ALPHBA_UPPER_RANGE, abs($index) % 26);
	}
	public static function char_in_range($char, $range){
		if($char === '')return false;
		return strpos($range, $char[0]) !== false;
	}
	public static function str_in_range($str, $range){
		for($c = 0;isset($str[$c]);++$c)
			if(strpos($range, $str[$c]) === false)
				return false;
		return true;
	}
	public static function get_in_range($str, $range){
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
	public static function append($string, $child, $index = 0){
		return substr_replace($string, $child, $index, 0);
	}
	public static function remove($string, $index = 0, $length = null){
		if($length === null)
			return substr_replace($string, '', $index);
		return substr_replace($string, '', $index, $length);
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
	public static function xorx($a, $b){
		return xncrypt::bindecode(set_bytes(self::xorn($a, $b), 8, '0'));
	}
	public static function bxorx($a, $b){
		return xnbinary::toString(xnbinary::xorx(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function badd($a, $b){
		return xnbinary::toString(xnbinary::add(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function bsub($a, $b){
		return xnbinary::toString(xnbinary::sub(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function bmul($a, $b){
		return xnbinary::toString(xnbinary::mul(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function bdiv($a, $b){
		return xnbinary::toString(xnbinary::div(xncrypt::binencode($a), xncrypt::binencode($b)));
	}
	public static function brev($x){
		return xncrypt::bindecode(strrev(xncrypt::binencode($x)));
	}
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
	public static function equlen($string1, $string2){
		$l1 = strlen($string1);
		$l2 = strlen($string2);
		return substr(str_repeat($string2, ceil($l1 / $l2)), 0, $l1);
	}
}
function script_runtime(){
	return microtime(true) - xnlib::$requestTime;
}
function userip(){
	if(@$_SERVER['HTTP_CLIENT_IP'])return $_SERVER['HTTP_CLIENT_IP'];
	elseif(@$_SERVER['HTTP_X_FORWARDED'])return $_SERVER['HTTP_X_FORWARDED'];
	elseif(@$_SERVER['HTTP_X_FORWARDED_FOR'])return $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif(@$_SERVER['REMOTE_ADDTR'])return $_SERVER['REMOTE_ADDTR'];
	else return "127.0.0.1";
}
function save_memory($file = false){
	if($file)fput($file, serialize($GLOBALS));
	else __xnlib_data::$saveMemory = $GLOBALS;
}
function back_memory($file = false){
	if($file && file_exists($file))$GLOBALS = xnunserialize(fget($file));
	elseif(!$file)$GLOBALS = __xnlib_data::$saveMemory;
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
function unce_dump(){
	$vars = func_get_args();
	foreach($vars as $var)print unce($var);
}
function string_dump(){
	$vars = func_get_args();
	foreach($vars as $var)print xnstring::toString($var);
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
function militime(){
	return floor(microtime(true) * 1000);
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
	unset($input[0]);
	unset($input[1]);
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
function fulllength($input){
	switch(gettype($input)){
		case 'object':
			$c = strlen(get_class($input));
			foreach((array)$input as $x => $y)
				$c += (is_bool($x) || $x === null ? 1 : strlen($x)) + fulllength($y);
			return $c;
		case 'array':
			$c = 0;
			foreach($input as $x => $y)
				$c += (is_bool($x) || $x === null ? 1 : strlen($x)) + fulllength($y);
			return $c;
		case 'string':
		case 'integer':
		case 'float':
		case 'double':
			return strlen($input);
		default:
			return 1;
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
function tbotGetHighScores($data){
	$ch = curl_init("https://tbot.xyz/api/getHighScores");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($data));
	$res = curl_exec($ch);
	curl_close($ch);
	$res = json_decode($res, true);
	return isset($res['scores']) ? $res['scores'] : false;
}
function tbotSetScore($data, $score){
	$ch = curl_init("https://tbot.xyz/api/setScore");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($data) . "&score=" . $score);
	$res = curl_exec($ch);
	curl_close($ch);
	$res = json_decode($res, true);
	return isset($res['scores']) ? $res['scores'] : false;
}
function tbotGetSelf($data){
	$scores = tbotGetHighScores($data);
	if($scores === false)
		return false;
	$self = false;
	foreach($scores as $user)
		if(isset($user['current']))
			return array(
				'pos' => $user['pos'],
				'score' => $user['score'],
				'name' => $user['name']
			);
}
function tbotInfoData($data){
	$data = json_decode(substr(base64_decode($data), 0, -32), true);
	if($data === false)
		return false;
	return array(
		'game' => $data['g'],
		'id' => $data['u'],
		'name' => $data['n']
	);
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
function rrand($x, $y, $z = 0){
	if($z == 0)
		return rand($x, $y);
	if($z > 0)
		return rrand(rand($x, $y), $y, $z - 1);
	if($z < 0)
		return rrand($x, rand($x, $y), $z + 1);
}
function prand($x, $y, $z){
	if($y < $x)
		swap($x, $y);
	return rand(rand($x, $z), rand($y, $z));
}
function rprand($x, $y, $z = 0, $j = null){
	if($j === null)
		$j = ($x + $y) / 2;
	if($z == 0)
		return prand($x, $y, $j);
	if($z > 0)
		return rprand(prand($x, $y, $j), $y, $z - 1);
	if($z < 0)
		return rprand($x, prand($x, $y, $j), $z + 1);
}
function prrand($x, $y, $z, $j = 0){
	if($y < $x)
		swap($x, $y);
	return rand(rrand($x, $z, $j), rrand($y, $z, $j));
}
function rprrand($x, $y, $z = 0, $j = null, $k = 0){
	if($j === null)
		$j = ($x + $y) / 2;
	if($z == 0)
		return prrand($x, $y, $j, $k);
	if($z > 0)
		return rprrand(prrand($x, $y, $j, $k), $y, $z - 1);
	if($z < 0)
		return rprrand($x, prrand($x, $y, $j, $k), $z + 1);
}
function brand($x = 0){
	if($x == 0)
		return rand(0, 1) === 1;
	if($x < 0)
		return rand(0, -$x + 1) === 0;
	return rand(0, $x + 1) != 0;
}
function sarrand($x, $y, $a, $b){
	if($a === $b)
		return $a;
	while($a++ < $b)
		if(rand($x, $y) === $x)
			return $a - 1;
	return sarrand($x, $y, rand($a, $b), rand($b, $a));
}
function proposal_username($username, $options = array()){
	$l = strlen($username);
	$ll = floor(pow($l, 0.2));
	$s = $username;
	if(!isset($options['ander']))
		$options['ander'] = '_';
	if(!isset($options['space']))
		$options['space'] = '.';
	if(!isset($options['from_space']))
		$options['from_space'] = ' ';
	if(!isset($options['random']))
		$options['random'] = xnstring::ALPHBA_NUMBERS_RANGE;
	if(!isset($options['max_rand']))
		$options['max_rand'] = 9999;
	if(!isset($options['min_rand']))
		$options['min_rand'] = 0;
	if(!isset($options['limit_rand']))
		$options['limit_rand'] = -4;
	for($c = 0;isset($username[$c]);$c += rrand(1, $ll, -4)){
		if(brand(-2) && $c > 0 && xnstring::char_in_range(@$username[$c], xnstring::ALPHBA_UPPER_RANGE) && !xnstring::char_in_range(@$username[$c - 1], xnstring::ALPHBA_UPPER_RANGE)){
			$username = substr_replace($username, xnstring::random($options['ander'],1 ), $c, 0);
			++$l;++$c;
		}
		if(brand(-10) && $c > 0 && xnstring::char_in_range(@$username[$c], xnstring::ALPHBA_UPPER_RANGE . xnstring::NUMBER_RANGE . $options['from_space'])){
			$username = substr_replace($username, $p = rrand($options['min_rand'], $options['max_rand'], $options['limit_rand']), $c,0);
			$l += $p;$c += $p;
		}
		elseif(brand(-29) && xnstring::char_in_range(@$username[$c], $options['space'] . $options['ander'] . $options['from_space']) && xnstring::char_in_range(@$username[$c + 1], $options['space'] . $options['ander'] . $options['from_space'])){
			$username = substr_replace($username, xnstring::random($options['random'], $p = rrand(1, 7, $options['limit_rand'])), $c, 0);
			$l += $p;$c += $p;
		}
		if(brand(-10) && xnstring::char_in_range(@$username[$c], $options['from_space'])){
			$username = substr_replace($username, brand() ? $options['space'] : $options['ander'], $c, 1);
			++$l;++$c;
		}
		if(brand(-50) && isset($options['changein']))
			$username = substr_replace($username, xnstring::random($username . $s, 1), $c, 1);
		if(brand(-64)){
			$username = substr_replace($username, @$username[$c], $c, 0);
			if(brand(2)){
				++$l;++$c;
			}
		}
		if(brand(-110) && isset($options['changein'])){
			$username = substr_replace($username, '', 0, 1);
			if(brand()){
				--$l;--$c;
			}
		}
		if(brand(-270) && $c > 0 && $c < $l - 1 && isset($options['changein'])){
			$s1 = @$username[$c];
			$s2 = @$username[$c - 1];
			$username = substr_replace($username, $s2, $c, 1);
			$username = substr_replace($username, $s1, $c + 1, 1);
			if(brand(-2)){
				--$l;--$c;
			}
		}if(brand(-73) && xnstring::char_in_range(@$username[$c], xnstring::ALPHBA_RANGE)){
			if(brand())
				$username = substr_replace($username, strtolower(@$username[$c]), $c, 1);
			else
				$username = substr_replace($username, strtoupper(@$username[$c]), $c, 1);
		}
	}
	if((isset($options['i']) && strtolower($s) == strtolower($username)) || $s == $username)
		return proposal_username($username, $options);
	return $username;
}
function is_stream($stream){
	return is_resource($stream) && strtolower(get_resource_type($stream)) == 'stream';
}
function is_gd($gd){
	return is_resource($gd) && strtolower(get_resource_type($gd)) == 'gd';
}
function imagecreatefromfile($file){
	mustbe($file, 'file');
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
			file_exists($file) && ($data = file_get_contents($file)) && ($data = json_decode($data, true)));
		elseif(isset($this->settings['elements']['file']) && is_array($this->settings['elements']['file']) && $data = $this->settings['elements']['file']);
		elseif(($data = file_get_contents('https://core.telegram.org/scheme/json')) && ($data = json_decode($data, true))){
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
			file_put_contents($file,json_encode($data));
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
				(file_exists($file) && ($data = file_get_contents($file)) && $data = json_decode($data,true))) &&
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
			file_put_contents($file,json_encode($data));
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
		$random = random_bytes(64);
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
function array_value($array, $key){
	return $array[$key];
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
function utf8_split($str, $length = null){
	$str = preg_split('//u', $str);
	if(end($str) === '')
		unset($str[count($str) - 1]);
	if($length <= 1)
		return $str;
	return array_map('implodes', array_chunk($str, $length));
}
function urf8_strlen($str){
	return count(preg_split('//u', $str));
}
function utf8_substr($str, $offset,  $length = null){
	if($length === null)
		return implode('', array_slice(preg_split('//u', $str), $offset));
	return implode('', array_slice(preg_split('//u', $str), $offset, $length));
}
if(function_exists('mb_substr')){
	function mb_substr_replace($string, $replacement, $start, $length = null, $encoding = null){
		if($encoding === null)$encoding = mb_internal_encoding();
		if($length === null)
			return mb_substr($string, 0, $start, $encoding) . $string . mb_substr($string, $start + mb_strlen($string, $coding), null, $coding);
		return mb_substr($string, 0, $start, $encoding) . $string . mb_substr($string, $start, $length, $encoding);
	}
}
function mb_strrev($str){
	return implode('', array_reverse(preg_split('//u', $str)));
}
if(!function_exists('random_bytes')){
	function random_bytes($length){
		$r = '';
		while(--$length >= 0)
			$r .= chr(rand(0, 255));
		return $r;
	}
}
if(!function_exists('random_int')){
	function random_int($min, $max){
		return rand($min, $max);
	}
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
if(!function_exists('preg_replace_array')){
	function preg_replace_array($patterns_and_replacements, $subject, $limit = -1, $count = null){
		if($count === null)
			foreach($patterns_and_replacements as $pattern=>$replacement)
				$subject = preg_replace($pattern, $replacement, $subject, $limit);
		else
			foreach($patterns_and_replacements as $pattern=>$replacement)
				$subject = preg_replace($pattern, $replacement, $subject, $limit, $count);
		return $subject;
	}
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
function printr($expression, $return = null){
	return print_r($expression, $return === true);
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
	return ini_get('memory_limit');
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
function xogame_create(){
	return array(0, 0, 0, 
			0, 0, 0,
			0, 0, 0);
}
function xogame_set(array &$table, $p, $user){
	if(is_array($p))
		$p = $p[0] + $p[1] * 3;
	$table[$p] = $user;
}
function xogame_get($table, $p, $user){
	if(is_array($p))
		$p = $p[0] + $p[1] * 3;
	return $table[$p];
}
function xogame_check($table){
	foreach(array(array(0, 3, 6), array(1, 4, 7), array(2, 5, 8), array(0, 1, 2), array(3, 4, 5), array(6, 7, 8), array(0, 4, 8), array(2, 4, 6)) as $t)
		if($table[$t[0]] == $table[$t[1]] && $table[$t[1]] == $table[$t[2]] && $table[$t[0]] !== 0)
			return $table[$t[0]];
	foreach($table as $t)
		if($t === 0)
			return 0;
	return 3;
}
define('XOGAME_ROBOT', 2);
define('XOGAME_PLAYER', 1);
define('XOGAME_PLAYER1', 1);
define('XOGAME_PLAYER2', 2);

define('XOGAME_VERY_EASY', 1);
define('XOGAME_EASY', 2);
define('XOGAME_NORMAL', 3);
define('XOGAME_HARD', 4);
define('XOGAME_VERY_HARD', 5);

function xogame_go(array &$table, $level = null){
	$list = array(
		array(0, 3, 6), array(3, 0, 6), array(6, 3, 0), array(1, 4, 7), array(4, 1, 7),
		array(7, 1, 4), array(2, 5, 8), array(5, 2, 8), array(8, 2, 5), array(0, 1, 2),
		array(1, 0, 2), array(2, 0, 1), array(3, 4, 5), array(4, 3, 5), array(5, 3, 4),
		array(6, 7, 8), array(7, 6, 8), array(8, 6, 7), array(0, 4, 5), array(4, 0, 8),
		array(8, 0, 4), array(8, 0, 4), array(2, 4, 6), array(4, 2, 6), array(6, 2, 4),
	);
	$uns = $use = array();
	foreach($list as $t)
		if($table[$t[1]] == $table[$t[2]] && $table[$t[0]] === 0)
			$use[] = $t[0];
	$list = array(
		array(0, array(1, 3, 4, 1, 3, 4)),
		array(1, array(0, 3, 5, 0, 3, 4, 5, 2)),
		array(2, array(1, 4, 5, 1, 4, 5)),
		array(3, array(0, 6, 4, 0, 1, 4, 6, 7)),
		array(4, array(0, 1, 2, 3, 5, 6, 7, 8, 0, 1, 2, 3, 5, 6, 7, 8)),
		array(5, array(2, 4, 8, 1, 2, 4, 7, 8)),
		array(6, array(3, 4, 7, 3, 4, 7)),
		array(7, array(6, 4, 8, 6, 3, 4, 5, 8)),
		array(8, array(4, 5, 7, 4, 5, 7))
	);
	foreach($list as $t)
		if($table[$t[0]] == 2 && brand())
			foreach($t[1] as $p)
				if($table[$p] === 0)
					$uns[] = $p;
	switch($level){
		case 1:
			if($uns !== array())
				$use = $uns;
		break;
		case 2:
			$use = array_merge($use, $use, $uns);
		break;
		case null:
		case 3:
			$use = array_merge($use, $use, $use, $use, $uns);
		break;
		case 4:
			$use = array_merge($use, $use, $use, $use, $use, $use, $use, $uns);
		break;
		case 5:
			if($use === array())
				$use = $uns;
	}
	if($use === array())
		foreach(array(0, 0, 0, 1, 1, 2,
				 2, 2, 3, 3, 4, 4,
				 4, 4, 5, 5, 6, 6,
				 6, 7, 7, 8, 8, 8) as $t)
			if($table[$t] === 0)
				$use[] = $t;
	$table[$use[array_rand($use)]] = 2;
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
		return json_decode(xndata('apk-dictionary'), true);
	}
	public function flush_dictionary(){
		if($this->dictionary !== null)
			return;
		$this->dictionary = json_decode(xndata('apk-dictionary'), true);
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
function fullurlencode($str){
	if($str === '')
		return '';
	return '%' . implode('%', str_split(strtoupper(bin2hex($str)), 2));
}
function wss_secaccept($key, $magic = null){
	return sha1($key . ($magic ? $magic : '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'), true);
}
function wss_makekey(){
	return random_bytes(20);
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
							$list = array(json_decode('"' . substr($block, 0, $p === false ? strlen($block) : $p) . '"'));
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
							$list = array(json_decode('"' . substr($block, 0, $p === false ? strlen($block) : $p) . '"'));
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
	return (isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] : __xnlib_data::$startTime) + ini_get('max_execution_time');
}
define('M_DEG', M_PI / 180);
define('M_RAD', 180 / M_PI);
function rad($x){
	return $x * M_DEG;
}
function deg($x){
	return $x * M_RAD;
}
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
	canbe($replacement, 'bool|int|str|null');
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
		return $b > 0 ? self::gcd($b, $a % $b): $a;
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
		$x = $x < 0 ? -$x : $x;
		if($x == 0)return 0;
		$y = (int)sqrt($x);
		for($c = 2; $c <= $y; ++$c)
		if($x % $c == 0)return $c;
		return $x;
	}
	public static function natives($x){
		$x = $x < 0 ? -$x : $x;
		if($x == 0)return array(0);
		$r = array();
		for($c = 1; $c <= $x; ++$c)
		if($x % $c == 0)$r[] = $c;
		return $r;
	}
	public static function tree($x){
		if($x == 0)return array(0);
		$r = array($l = self::native($x));
		while(($x/= $l)> 1)$r[] = $l = self::native($x);
		return $r;
	}
	public static function nominal($x, $y){
		return (pow(($x + 1), (1 / $y)) - 1)* $y;
	}
	public static function pnan($x){
		if($x == 0)return array();
		$a = array(1);
		for($c = 2;$c < $x;++$c)
			if(self::gcd($x,$c) == 1)
				$a[] = $c;
		return $a;
	}
	public static function pnt($x){
		return self::native($x) == $x;
	}
	public static function pnpnt($x){
		$a = array();
		for($c = 2;$c < $x;++$c)
			if(self::native($c) == $c)
				$a[] = $c;
		return $a;
	}
	public static function onpnt($x){
		$x = abs($x);
		while(--$x >= 1)
			if(self::native($x) == $x)
				return $x - 1;
		return false;
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
		$n = array();
		for($c = 2;$c < $x;)
			if(self::gcd($x,$c++) == 1)
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
	public static function distancepositions($x1, $y1, $x2, $y2){
		return rad2deg(acos((sin(deg2rad($x1)) * sin(deg2rad($x2))) + (cos(deg2rad($x1)) * cos(deg2rad($x2)) * cos(deg2rad($y1 - $y2))))) * 111189.57696;
	}
	public static function distancepoints($x1, $y1, $x2, $y2){
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
		return $bin;
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
	public static function rand($min = 0, $max = 1){
		$log = pow(10, max(self::datdc($min), self::datdc($max)));
		return rand($min * $log, $max * $log) / $log;
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
		if(function_exists('bcadd')){
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
		if(function_exists('bcsub')){
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
		if(function_exists('bcadd')){
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
		if(function_exists('bcdiv')){
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
		if(function_exists('bcmod'))
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
		if(function_exists('bcpow')){
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
		if(function_exists('bcmod')){
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
		if(function_exists('bcmod')){
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
	public static function time(){
		$time = microtime();
		return self::_get(substr($time, 11) . '.' . substr($time, 2, 8));
	}
	public static function sqrt($n, $limit = 15){
		if(function_exists('bcsqrt'))
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
	public static function toInt($a){
		return (int)base_convert($a, 2, 10);
	}
	public static function tonumber($a){
		return xnnumber::base_convert($a, 2, 10);
	}
	public static function tostring($a){
		return xncrypt::bindecode(set_bytes($a, 8, '0'));
	}
	public static function bytes($a){
		return xncrypt::bindecode(set_bytes($a, 8, '0'));
	}
	public static function init($a, $init = 2){
		return xnnumber::base_convert($a, $init, 2);
	}
}
if(PHP_INT_SIZE === 4){
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
    public static function hash($algo, $data, $raw = null){
        if(function_exists('hash_algos') && in_array($algo, hash_algos()))
            return hash($algo, $data, $raw === true);
        if(function_exists('mhash') && defined('MHASH_' . strtoupper($algo)))
            return $raw === true ? mhash(constant('MHASH_' . strtoupper($algo)), $data) : self::hexencode(mhash(constant('MHASH_' . strtoupper($algo)), $data));
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
            case 'sha3-256':
                return self::sha3($data, 512, 1088, 32, 2, $raw === true);
            case 'sha3-384':
                return self::sha3($data, 768, 832, 48, 2, $raw === true);
            case 'sha3-512':
				return self::sha3($data, 1024, 576, 64, 2, $raw === true);
			case 'pearson16':
				return $raw === true ? self::pearson16($data) : self::hexencode(self::pearson16($data));
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
        trigger_error("XNCrypt::hash(): Unknown hashing algorithm: $algo", E_USER_WANING);
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
            "md2" => 16,         "md4" => 16,         "md5" => 16,
            "sha1" => 20,        "sha224" => 28,      "sha256" => 32,
            "sha384" => 48,      "sha512/224" => 28,  "sha512/256" => 32,
            "sha512" => 64,      "sha3-224" => 28,    "sha3-256" => 32,
            "sha3-384" => 48,    "sha3-512" => 64,    "ripemd128" => 16,
            "ripemd160" => 20,   "ripemd256" => 32,   "ripemd320" => 40,
            "whirlpool" => 64,   "tiger128,3" => 16,  "tiger160,3" => 20,
            "tiger192,3" => 24,  "tiger128,4" => 16,  "tiger160,4" => 20,
            "tiger192,4" => 24,  "snefru" => 32,      "snefru256" => 32,
            "gost" => 32,        "gost-crypto" => 32, "adler32" => 4,
            "crc32" => 4,        "crc32b" => 4,       "crc16" => 2,
			"crc8" => 1,         "bsd" => 2,          "pearson" => 8,
			"fnv132" => 4,       "fnv1a32" => 4,
            "fnv164" => 8,       "fnv1a64" => 8,      "joaat" => 4,
            "haval128,3" => 16,  "haval160,3" => 20,  "haval192,3" => 24,
            "haval224,3" => 28,  "haval256,3" => 32,  "haval128,4" => 16,
            "haval160,4" => 20,  "haval192,4" => 24,  "haval224,4" => 28,
            "haval256,4" => 32,  "haval128,5" => 16,  "haval160,5" => 20,
            "haval192,5" => 24,  "haval224,5" => 28,  "haval256,5" => 32,
            "keccak224" => 56,   "keccak256" => 64,   "keccak384" => 96,
            "keccak512" => 128,  "shake128"  => 64,   "shake256"  => 128
        );
        if(!isset($algos[$algo]))return false;
        $length = $algos[$algo];
        if($raw === null)return $length;
        if($raw === true)return $length * 2;
        $algos = array(
            "md2" => 1,        "md4" => 4,        "md5" => 4,
            "sha256" => 2,     "sha512/256" => 4, "sha512" => 2,
            "ripemd128" => 4,  "ripemd256" => 2,  "whirlpool" => 1,
            "tiger128,3" => 4, "tiger128,4" => 4, "snefru" => 1,
            "snefru256" => 1,  "gost" => 1,       "gost-crypto" => 1,
            "haval128,3" => 8, "haval256,3" => 4, "haval128,4" => 8,
            "haval256,4" => 4, "haval128,5" => 8, "haval256,5" => 4,
        );
        if(!isset($algos[$algo]))return null;
        return $length * $algos[$algo];
    }
    public static function hash_algos(){
        return array(
            "md2",        "md4",        "md5",         "sha1",        "sha224",
            "sha256",     "sha384",     "sha512/224",  "sha512/256",  "sha512",
            "sha3-224",   "sha3-256",   "sha3-384",    "sha3-512",    "ripemd128",
            "ripemd160",  "ripemd256",  "ripemd320",   "whirlpool",   "tiger128,3",
            "tiger160,3", "tiger192,3", "tiger128,4",  "tiger160,4",  "tiger192,4",
            "snefru",     "snefru256",  "gost",        "gost-crypto", "adler32",
			"crc32",      "crc32b",     "crc16",       "crc8",        "bsd",
			"pearson",    "fnv132",
            "fnv1a32",    "fnv164",      "fnv1a64",    "joaat",       "haval128,3",
            "haval160,3", "haval192,3", "haval224,3",  "haval256,3",  "haval128,4",
            "haval160,4", "haval192,4", "haval224,4",  "haval256,4",  "haval128,5",
            "haval160,5", "haval192,5", "haval224,5",  "haval256,5",  "keccak224",
            "keccak256",  "keccak384",  "keccak512",   "shake128",    "shake256"
        );
    }
    public static function hash_hmac_algos(){
        return array(
            "md2",        "md4",        "md5",        "sha256",     "sha512/256",
            "sha512",     "ripemd128",  "ripemd256",  "whirlpool",  "tiger128,3",
            "tiger128,4", "snefru",     "snefru256",  "gost",       "gost-crypto",
            "haval128,3", "haval256,3", "haval128,4", "haval256,4", "haval128,5",
            "haval256,5",
        );
    }
    public static function hash_hmac($algo, $data, $key, $raw = null) {
        if(function_exists('hash_hmac_algos') && in_array($algo, hash_hmac_algos()))
            return hash_hmac($algo, $data, $key, $raw === true);
        if(function_exists('mhash') && defined('MHASH_' . strtoupper($algo)))
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
        if(function_exists('hash_hkdf') && function_exists('hash_hmac_algos') && in_array($algo, hash_hmac_algos()))
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
        if(function_exists('hash_pbkdf2') && function_exists('hash_hmac_algos') && in_array($algo, hash_hmac_algos()))
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
			'crc1'              => array(0x1, 0x1, 0x0, 0x0, false, false),
			'crc4'              => array(0x3, 0x4, 0x0, 0x0, true, true),
			'crc4/itu'          => array(0x3, 0x4, 0x0, 0x0, true, true),
			'crc4/interlaken'   => array(0x3, 0x4, 0xf, 0xf, false, false),
            'crc8'              => array(0x7,  0x8, 0x0,  0x0,  false, false),
            'crc8/cdma2000'     => array(0x9b, 0x8, 0xff, 0x0,  false, false),
            'crc8/darc'         => array(0x39, 0x8, 0x0,  0x0,  true, true),
            'crc8/dvb-s2'       => array(0xd5, 0x8, 0x0,  0x0,  false, false),
            'crc8/ebu'          => array(0x1d, 0x8, 0xff, 0x0,  true, true),
            'crc8/i-code'       => array(0x1d, 0x8, 0xfd, 0x0,  false, false),
            'crc8/itu'          => array(0x7,  0x8, 0x0,  0x55, false, false),
            'crc8/maxim'        => array(0x31, 0x8, 0x0,  0x0,  true, true),
            'crc8/rohc'         => array(0x7,  0x8, 0xff, 0x0,  true, true),
            'crc8/wcdma'        => array(0x9b, 0x8, 0x0,  0x0,  true, true),
            'crc8/autosar'      => array(0x2f, 0x8, 0xff, 0xff, false, false),
            'crc8/bluetooth'    => array(0xa7, 0x8, 0x0,  0x0,  true, true),
            'crc8/gsma'         => array(0x1d, 0x8, 0x0,  0x0,  false, false),
            'crc8/gsmb'         => array(0x49, 0x8, 0x0,  0xff, false, false),
            'crc8/lte'          => array(0x9b, 0x8, 0x0,  0x0,  false, false),
            'crc8/opensafety'   => array(0x2f, 0x8, 0x0,  0x0,  false, false),
            'crc8/sae-j1850'    => array(0x1d, 0x8, 0xff, 0xff, false, false),
            'crc16'             => array(0x8005, 0x10, 0x0,    0x0,    true, true),
            'crc16/arc'         => array(0x8005, 0x10, 0x0,    0x0,    true, true),
            'crc16/aug-ccitt'   => array(0x1021, 0x10, 0x1d0f, 0x0,    false, false),
            'crc16/buypass'     => array(0x8005, 0x10, 0x0,    0x0,    false, false),
            'crc16/ccitt-false' => array(0x1021, 0x10, 0xffff, 0x0,    false, false),
            'crc16/cdma2000'    => array(0xc867, 0x10, 0xffff, 0x0,    false, false),
            'crc16/cms'         => array(0x8005, 0x10, 0xffff, 0x0,    false, false),
            'crc16/dds'         => array(0x8005, 0x10, 0x800d, 0x0,    false, false),
            'crc16/dect-r'      => array(0x589,  0x10, 0x0,    0x1,    false, false),
            'crc16/dect-x'      => array(0x589,  0x10, 0x0,    0x0,    false, false),
            'crc16/dnp'         => array(0x3d65, 0x10, 0x0,    0xffff, true, true),
            'crc16/en-13757'    => array(0x3d65, 0x10, 0x0,    0xffff, false, false),
            'crc16/genibus'     => array(0x1021, 0x10, 0xffff, 0xffff, false, false),
            'crc16/gsm'         => array(0x1021, 0x10, 0x0,    0xffff, false, false),
            'crc16/kermit'      => array(0x1021, 0x10, 0x0,    0x0,    true, true),
            'crc16/lj1200'      => array(0x6f63, 0x10, 0x0,    0x0,    false, false),
            'crc16/maxim'       => array(0x8005, 0x10, 0x0,    0xffff, true, true),
            'crc16/mcrf4xx'     => array(0x1021, 0x10, 0xffff, 0x0,    true, true),
            'crc16/modbus'      => array(0x8005, 0x10, 0xffff, 0x0,    true, true),
            'crc16/opensafetya' => array(0x5935, 0x10, 0x0,    0x0,    false, false),
            'crc16/opensafetyb' => array(0x755b, 0x10, 0x0,    0x0,    false, false),
            'crc16/profibus'    => array(0x1dcf, 0x10, 0xffff, 0xffff, false, false),
            'crc16/ps2ff'       => array(0x1021, 0x10, 0x1d0f, 0x0,    false, false),
            'crc16/riello'      => array(0x1021, 0x10, 0xb2aa, 0x0,    true, true),
            'crc16/t10-dif'     => array(0x8bb7, 0x10, 0x0,    0x0,    false, false),
            'crc16/teledisk'    => array(0xa097, 0x10, 0x0,    0x0,    false, false),
            'crc16/tms37157'    => array(0x1021, 0x10, 0x89ec, 0x0,    true, true),
            'crc16/usb'         => array(0x8005, 0x10, 0xffff, 0xffff, true, true),
            'crc16/x-25'        => array(0x1021, 0x10, 0xffff, 0xffff, true, true),
            'crc16/xmodem'      => array(0x1021, 0x10, 0x0,    0x0,    false, false),
            'crca'              => array(0x1021, 0x10, 0xc6c6, 0x0,    true, true),
            'crc24'             => array(0x864cfb, 0x18, 0xb704ce, 0x0,      false, false),
            'crc24/flexraya'    => array(0x5d6dcb, 0x18, 0xfedcba, 0x0,      false, false),
            'crc24/flexrayb'    => array(0x5d6dcb, 0x18, 0xabcdef, 0x0,      false, false),
            'crc24/interlaken'  => array(0x328b63, 0x18, 0xffffff, 0xffffff, false, false),
            'crc24/ltea'        => array(0x864cfb, 0x18, 0x0,      0x0,      false, false),
            'crc24/lteb'        => array(0x800063, 0x18, 0x0,      0x0,      false, false),
            'crc32'             => array(0x4c11db7,  0x20, 0xffffffff, 0xffffffff, true, true),
            'crc32c'            => array(0x1edc6f41, 0x20, 0xffffffff, 0xffffffff, true, true),
            'crc32d'            => array(0xa833982b, 0x20, 0xffffffff, 0xffffffff, true, true),
            'crc32q'            => array(0x814141ab, 0x20, 0x0,        0x0,        false, false),
            'crc32/bzip2'       => array(0x4c11db7,  0x20, 0xffffffff, 0xffffffff, false, false),
            'crc32/jamcrc'      => array(0x4c11db7,  0x20, 0xffffffff, 0x0,        true, true),
            'crc32/mpeg-2'      => array(0x4c11db7,  0x20, 0xffffffff, 0x0,        false, false),
            'crc32/posix'       => array(0x4c11db7,  0x20, 0x0,        0xffffffff, false, false),
            'crc32/xfer'        => array(0xaf,       0x20, 0x0,        0x0,        false, false),
            'crc32/autosar'     => array(0xf4acfb13, 0x20, 0xffffffff, 0xffffffff, true, true)
        );
        $algo = isset($algos[$algo]) ? $algos[$algo] : false;
        return array(
            'polynomial' => $algo[0],
            'length'     => $algo[1],
            'init'       => $algo[2],
            'xorout'     => $algo[3],
            'refin'      => $algo[4],
            'refout'     => $algo[5]
        );
    }
    public static function crcalgos(){
        return array(
			"crc1",              "crc4",              "crc4/itu",       "crc4/interlaken",
			"crc8",              "crc8/cdma2000",     "crc8/darc",      "crc8/dvb-s2",
            "crc8/ebu",          "crc8/i-code",       "crc8/itu",       "crc8/maxim",
            "crc8/rohc",         "crc8/wcdma",        "crc8/autosar",   "crc8/bluetooth",
            "crc8/gsma",         "crc8/gsmb",         "crc8/lte",       "crc8/opensafety",
            "crc8/sae-j1850",    "crc16",             "crc16/arc",      "crc16/aug-ccitt",
            "crc16/buypass",     "crc16/ccitt-false", "crc16/cdma2000", "crc16/cms",
            "crc16/dds",         "crc16/dect-r",      "crc16/dect-x",   "crc16/dnp",
            "crc16/en-13757",    "crc16/genibus",     "crc16/gsm",      "crc16/kermit",
            "crc16/lj1200",      "crc16/maxim",       "crc16/mcrf4xx",  "crc16/modbus",
            "crc16/opensafetya", "crc16/opensafetyb", "crc16/profibus", "crc16/ps2ff",
            "crc16/riello",      "crc16/t10-dif",     "crc16/teledisk", "crc16/tms37157",
            "crc16/usb",         "crc16/x-25",        "crc16/xmodem",   "crca",
            "crc24",             "crc24/flexraya",    "crc24/flexrayb", "crc24/interlaken",
            "crc24/ltea",        "crc24/lteb",        "crc32",          "crc32c",
            "crc32d",            "crc32q",            "crc32/bzip2",    "crc32/jamcrc",
            "crc32/mpeg-2",      "crc32/posix",       "crc32/xfer",     "crc32/autosar"
        );
	}
    public static function crc($algo, $data, $crc = null){
        $algo = self::crcalgo($algo);
        if($algo === false){
            trigger_error("XNCrypt::crc(): Unknown CRC hashing algorithm: $algo", E_USER_WANING);
            return false;
        }
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
		if(function_exists('crypt'))
			if($salt === null)
				return crypt($str);
			else return crypt($str, $salt);
	}

    public static function hexencode($string){
        if(function_exists('bin2hex'))
			return bin2hex($string);
		return array_value(npack('H*', $string), 1);
    }
    public static function hexdecode($string){
        $l = strlen($string);
        if($l % 2 === 1)$string = '0' . $string;
        if(function_exists('hex2bin'))
			return hex2bin($string);
		return pack('H*', $string);
    }
    public static function binencode($string){
        if(function_exists('bin2hex'))
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
        if($l % 8 !== 0)$string = str_repeat('0', 8 - $l) . $string;
        if(function_exists('hex2bin'))
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
    public static function base4encode($string){
        if(function_exists('bin2hex'))
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
        if($l % 4 !== 0)$string = str_repeat('0', 4 - $l) . $string;
        if(function_exists('hex2bin'))
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
    public static function base64encode($string){
        if(function_exists('base64_encode'))return base64_encode($string);
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
        if(function_exists('base64_decode'))return base64_decode($string);
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
    public static function decencode($string){
        return xnnumber::base_convert($string, 'ascii', 10);
    }
    public static function decdecode($string){
        return xnnumber::base_convert($string, 10, 'ascii');
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

	protected static $iconvcharset = array(
		'iso-8859-1' => array(
			'utf-8'    => 'iso88591utf8',
			'utf-16be' => 'iso88591utf16be',
			'utf-16le' => 'iso88591utf16le',
			'utf-16'   => 'iso88591utf16'
		),
		'utf-8' => array(
			'iso-8859-1' => 'utf8iso88591',
			'utf-16be'   => 'utf8utf16be',
			'utf-16le'   => 'utf8utf16le',
			'utf-16'     => 'utf8utf16',
		),
		'utf-16le' => array(
			'iso-8859-1' => 'utf16leiso88591',
			'utf-8'      => 'utf16leutf8',
			'utf-16be'   => 'utf16lebe'
		),
		'utf-16be' => array(
			'iso-8859-1' => 'utf16beiso88591',
			'utf-8'      => 'utf16beutf8',
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
		if(function_exists('utf8_encode'))
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
		if(function_exists('utf8_decode'))
			return utf8_decode($string);
		$iso88591 = '';
		$i = 0;
		while(isset($string[$i])) {
			$cur = ord($string[$i]);
			if(($cur | 0x07) == 0xF7) {
				$char = (($cur                 & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif(($cur | 0x0F) == 0xEF) {
				$char = (($cur                 & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif(($cur | 0x1F) == 0xDF) {
				$char = (($cur                 & 0x1F) <<  6) &
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
				$char = ((ord($string[$i    ]) & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif((ord($string[$i]) | 0x0F) == 0xEF) {
				$char = ((ord($string[$i    ]) & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif((ord($string[$i]) | 0x1F) == 0xDF) {
				$char = ((ord($string[$i    ]) & 0x1F) <<  6) &
						 (ord($string[$i + 1]) & 0x3F);
				$i += 2;
			}elseif((ord($string[$i]) | 0x7F) == 0x7F)
				$char = ord($string[$i++]);
			else{
				$char = false;
				++$i;
			}
			if($char !== false)
				$utf16le .= $char < 65536 ? self::strbe($char, 2) : "\0?";
		}
		return $utf16le;
	}
	public static function utf8utf16le($string, $bom = null){
		if(substr($string, 0, 3) == "\xEF\xBB\xBF")$string = substr($string, 3);
		if($bom === true)
			$utf16be = "\xFF\xFE";
		$i = 0;
		while(isset($string[$i])) {
			if((ord($string[$i]) | 0x07) == 0xF7) {
				$char = ((ord($string[$i    ]) & 0x07) << 18) &
						((ord($string[$i + 1]) & 0x3F) << 12) &
						((ord($string[$i + 2]) & 0x3F) <<  6) &
						 (ord($string[$i + 3]) & 0x3F);
				$i += 4;
			}elseif((ord($string[$i]) | 0x0F) == 0xEF) {
				$char = ((ord($string[$i    ]) & 0x0F) << 12) &
						((ord($string[$i + 1]) & 0x3F) <<  6) &
						 (ord($string[$i + 2]) & 0x3F);
				$i += 3;
			}elseif((ord($string[$i]) | 0x1F) == 0xDF) {
				$char = ((ord($string[$i    ]) & 0x1F) <<  6) &
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
	public static function translit($string){
		return self::dictencode($string, self::dictget(xndata("encoding-translit")));
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
		if(function_exists('iconv'))
			$res = @iconv($fromu, $tou, $string);
		if($res !== false)return $res;
		if(function_exists('mb_convert_encoding'))
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
			if($fromdict === null)
				new XNError('xncrypt::iconv', "Unknown encoding charset $from", XNError::WARNING, XNError::TTHROW);
			if($todict === null)
				new XNError('xncrypt::iconv', "Unknown encoding charset $to", XNError::WARNING, XNError::TTHROW);
			if(count($fromdict) == count($todict) && array_keys($fromdict) === arary_keys($todict))
				return self::dictencode($string, array_combine(array_values($from), array_values($to)));
			$string = self::dictencode(self::dictdecode($string, $fromdict), $todict);
			if(isset($toe))
				return self::iconv($string, 'iso-8859-1', $toe);
			return $string;
		}
		list($func1, $func2) = explode('/', $func . '/', 3);
		if($func2 !== '')
			return self::$func2(self::$func1($string));
		return self::$func1($string);
	}
}

/* ---------- XNData ---------- */
class XNData {
	const VERSION = '4.2.4';
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
						}elseif(!is_resource($file) || !xnstream::mode($file))
							return false;
						if(xnstream::mode($file) != 'r+b')
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
						}elseif(!is_resource($file) || !xnstream::mode($file))
							return false;
						if(xnstream::mode($file) != 'r+b')
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
			$datas[$c] = json_decode($x[2]);
			return $c++;
		}, $query);
		$query = preg_replace_callback("/(?<x>\[((?:\g<x>|\\\\\[|\\\\\]|\\\\\(|\\\\\)|\"(?:\\\\\"|[^\"])*\"|'(?:\\\\'|[^'])*'|[^\]])*)\])/",
		function($x)use(&$datas, &$c){
			$datas[$c] = json_decode($x[2]);
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
		return $this->parent === true;
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
		return $this->parent === true;
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
			if    ($r == $max)$h = $db - $dg;
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
		   if    ($r == $max)$h = $b - $dg;
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
					$this->gifs[$this->gif_count]['delay_time']      = unpack('n', substr($data, 2, 2));
					++$this->gif_count;
				break;
				case 'gIFx':
					if(!isset($this->extenstions)){
						$this->extensions = array();
						$this->extension_count = 0;
					}
					$this->extensions[$this->extension_count]['application_identifier'] = substr($data, 0, 8);
					$this->extensions[$this->extension_count]['authentication_code']    = substr($data, 8, 3);
					$this->extensions[$this->extension_count]['application_data']       = substr($data, 11);
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
						default       : $unit = $this->unit; break;
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
						default:        $unit = $this->offset_unit; break;
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
						default:        $unit = $this->scale_unit; break;
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
					$this->gifs[$this->gif_count]['delay_time']      = unpack('n', substr($data, 2, 2));
					++$this->gif_count;
				break;
				case 'gIFx':
					if(!isset($this->extenstions)){
						$this->extensions = array();
						$this->extension_count = 0;
					}
					$this->extensions[$this->extension_count]['application_identifier'] = substr($data, 0, 8);
					$this->extensions[$this->extension_count]['authentication_code']    = substr($data, 8, 3);
					$this->extensions[$this->extension_count]['application_data']       = substr($data, 11);
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
		if($back === true)$locate = ftell($stream);
		$l = strlen($search);
		for($i = $offset === null ? ftell($stream) : $offset; true;){
			$read = fread($stream, $l);
			if($read == $search){
				$pos = $i;break;
			}if(strlen($read) <= $l){
				$pos = false;break;
			}fseek($stream, ++$i);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function ipos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($back === true)$locate = ftell($stream);
		$search = strtolower($search);
		$l = strlen($search);
		for($i = $offset === null ? ftell($stream) : $offset; true;){
			$read = strtolower(fread($stream, $l));
			if($read == $search){
				$pos = $i;break;
			}if(strlen($read) <= $l){
				$pos = false;break;
			}fseek($stream, ++$i);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function rpos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($back === true)$locate = ftell($stream);
		$l = strlen($search);
		for($i = $offset === null ? ftell($stream) : $offset; true;){
			$read = fread($stream, $l);
			if($read == $search){
				$pos = $i;break;
			}if($i <= 0){
				$pos = false;break;
			}
			fseek($stream, --$i);
		}
		if($back === true)fseek($stream, $locate);
		return $pos;
	}
	public static function ripos($stream, $search, $offset = null, $back = null){
		if($search == '')
			return 0;
		if($back === true)$locate = ftell($stream);
		$search = strtolower($search);
		$l = strlen($search);
		for($i = $offset === null ? ftell($stream) : $offset; true;){
			$read = strtolower(fread($stream, $l));
			if($read == $search){
				$pos = $i;break;
			}if($i <= 0){
				$pos = false;break;
			}
			fseek($stream, --$i);
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
		}return fread($stream, $limit);
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
		if($priving === true)
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
			return xncrypt::decdecode($read);
		return unpack($format, $read);
	}
	public static function match($stream, $regex, $flags = 0, $offset = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($offset !== null)fseek($stream, $offset);
		do{
			$line = fgets($stream);
			if(preg_match($regex, $line, $match, $flags))break;
		}while(!feof($stream));
		if($back === true)fseek($stream, $locate);
		return $match === array() ? false : $match;
	}
	public static function match_all($stream, $regex, $flags = 0, $offset = null, $back = null){
		if($back === true)$locate = ftell($stream);
		if($offset !== null)fseek($stream, $offset);
		$matches = array();
		do{
			$line = fgets($stream);
			if(preg_match($regex, $line, $match, $flags))
				$matches[] = $match;
		}while(!feof($stream));
		if($back === true)fseek($stream, $locate);
		if($matches === array())return false;
		return call_user_func_array('array_array_merge', $matches);
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
	public static function wait($stream, $limit = 2, $back = null){
		if($back === true)$locate = ftell($file);
		$l = $limit + 1;
		$c = 0;
		do{++$c;
			$read = stream_get_contents($stream);
			if($read !== '')
				$l = $limit;
		}while($read !== '' || --$l >= 0);
		if($back === true)fseek($file, $locate);
		return $c;
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
			$char = (($cur                & 0x07) << 18) &
					((ord(fgetc($stream)) & 0x3F) << 12) &
					((ord(fgetc($stream)) & 0x3F) <<  6) &
					 (ord(fgetc($stream)) & 0x3F);
		elseif(($cur | 0x0F) == 0xEF)
			$char = (($cur                & 0x0F) << 12) &
					((ord(fgetc($stream)) & 0x3F) <<  6) &
					 (ord(fgetc($stream)) & 0x3F);
		elseif(($cur | 0x1F) == 0xDF)
			$char = (($cur                & 0x1F) <<  6) &
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
}

__xnlib_data::$endTime = microtime(true);
xnlib::$loadTime = __xnlib_data::$endTime - __xnlib_data::$startTime;
?>