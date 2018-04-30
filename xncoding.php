<?php

// Created by ...
// xn plugin coding v1

function base10_encode($str){
$c=0;$r=0;
while(@$str[$c]){
$r=$r*256+ord($str[$c++]);
}return $r;
}function base10_decode($num){
$r='';
while($num>0){
$r=chr($num%256).$r;
$num=(int)($num/256);
}return $r;
}function base2_encode($text){
$l=strlen($text);$r='';
for($c=0;$c<$l;$c++){
$a=ord($text[$c]);
$r=$r.(($a>>7)&1).(($a>>6)&1).
(($a>>5)&1).(($a>>4)&1).
(($a>>3)&1).(($a>>2)&1).
(($a>>1)&1).(($a)&1);
}return $r;
}function base2_decode($text){
$l=strlen($text);$r='';$c=0;
while($c<$l){
$r=$r.chr(($text[$c++]<<7)+($text[$c++]<<6)+
($text[$c++]<<5)+($text[$c++]<<4)+
($text[$c++]<<3)+($text[$c++]<<2)+
($text[$c++]<<1)+($text[$c++]));
}return $r;
}function base64url_encode($data){
return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
}function base64url_decode($data){
return base64_decode(str_pad(strtr($data,'-_','+/'),strlen($data)%4,'=',STR_PAD_RIGHT));
}function baseconvert($text,$from,$to=false){
$fromel=mb_subsplit($from);
$frome=[];
foreach($fromel as $key=>$value){
$frome[$value]=$key;
}unset($fromel);
$fromc=count($frome);
$toe=mb_subsplit($to);
$toc=count($toe);
$texte=array_reverse(mb_subsplit($text));
$textc=count($texte);
$bs=0;
$th=1;
for($i=0;$i<$textc;$i++){
$bs=$bs+@$frome[$texte[$i]]*$th;
$th=$th*$fromc;
}$r='';
if($to===false)return "$bs";
while($bs>0){
$r=$toe[$bs%$toc].$r;
$bs=floor($bs/$toc);
}return "$r";
}


?>
