<?php

// Created by avid
// xn plugin data v1

$GLOBALS['-XN-']['xnData']="xndata.txt";
class XNData {
static function get($data){
$f=$GLOBALS['-XN-']['xnData'];
$leng=strlen($data);
if(file_exists($f)){
$f=fopen($f,'r');
}else{
new XNError("XNData","file 'xndata.txt' invalid");
return false;
}while(true){
$size=base10_encode(fread($f,2))-$leng;
if($size==0)return false;
if(fread($f,$leng)==$data){
fseek($f,1,1);
$data=fread($f,$size-1);
break;
}fseek($f,$size,1);
}return unserialize(zlib_decode($data));
}static function download(){
$file=$GLOBALS['-XN-']['xnData'];
return copy("https://github.com/xnlib/xnphp/blob/master/xndata.txt?raw=true",$file);
}static function set($file="xndata.txt"){
$_GLOBALS['-XN-']['xnData']=$file;
}
}

?>
