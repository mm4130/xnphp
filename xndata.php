<?php

// Created by avid
// xn plugin data v1

function get_xndata($data){
$f=$GLOBALS['-XN-']['dirName'].DIRECTORY_SEPARATOR.'xndata.txt';
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
}

?>
