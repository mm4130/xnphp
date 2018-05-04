<?php

// Created by ...
// xn plugin calc (creating...)

class XNProCalc {
// system functions
static function _check($a){
if(!is_numeric($a)){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","invalid number \"$a\".");
return false;
}return true;
}static function _view($a){
if($a[0]=='-')return true;
return false;
}static function _not($a){
if($a[0]=='-'||$a[0]=='+')return substr($a,1);
return $a;
}static function _change($a){
if($a[0]=='-')return substr($a,1);
if($a[0]=='+')return '-'.substr($a,1);
return '-'.$a;
}static function _get0($a){
$c=0;$k=0;
while(@$a[$c++]==='0')$k++;
return substr($a,$k);
}static function _get1($a){
$c=strlen($a)-1;$k=0;
while(@$a[$c--]==='0')$k++;
return substr($a,0,strlen($a)-$k);
}static function _get2($a){
$a=self::_mo($a);
$a[1]=isset($a[1])?$a[1]:'0';
$a[0]=self::_get0($a[0]);
$a[1]=self::_get1($a[1]);
if($a[0]&&$a[1])return "{$a[0]}.{$a[1]}";
if($a[1])return "0.{$a[1]}";
if($a[0])return "{$a[0]}";
return "0";
}static function _get3($a){
if(self::_view($a))return '-'.self::_get2(self::_not($a));
return self::_get2(self::_not($a));
}static function _get($a){
if(!self::_check($a))return false;
return self::_get3($a);
}static function _set0($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return str_repeat('0',$l).$a;
}static function _set1($a,$b){
$l=strlen($b)-strlen($a);
if($l<=0)return $a;
else return $a.str_repeat('0',$l);
}static function _set2($a,$b){
$a=self::_mo($a);
$b=self::_mo($b);
if(!isset($a[1])&&isset($b[1])){
$a[1]='0';
}if(isset($a[1]))$a[1]=self::_set1($a[1],$b[1]);
$a[0]=self::_set0($a[0],$b[0]);
if(!isset($a[1]))return "{$a[0]}";
return "{$a[0]}.{$a[1]}";
}static function _set3($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_set2(self::_not($a),self::_not($b));
if(!self::_view($a)&& self::_view($b))return     self::_set2(self::_not($a),self::_not($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_set2(self::_not($a),self::_not($b));
                                      return     self::_set2(self::_not($a),self::_not($b));
}static function _set($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set3($a,$b);
}static function _full($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_set(self::_get($a),self::_get($b));
}static function _setfull(&$a,&$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
$a=self::_get($a);
$b=self::_get($b);
$a=self::_set($a,$b);
$b=self::_set($b,$a);
}static function _mo($a){
return explode('.',$a);
}static function _lm($a){
return strpos($a,'.');
}static function _im($a){
$p=self::_lm($a);
return $p!==false&&$p!=-1;
}static function _nm($a){
return str_replace('.','',$a);
}static function _iz($a){
$a=$a[strlen($a)-1];
return $a=='0'||$a=='2'||$a=='4'||$a=='6'||$a=='8';
}static function _if($a){
$a=$a[strlen($a)-1];
return $a=='1'||$a=='3'||$a=='5'||$a=='7'||$a=='9';
}static function _so($a,$b){
$l=strlen($a)%$b;
if($l==0)return $a;
else return str_repeat('0',$b-$l).$a;
}
// retry calc functions
static function _powTen0($a,$b){
$p=self::_lm($a);
$i=$p===false||$p==-1;
$a=self::_nm($a);
$l=strlen($a);
if($i)$s=strlen($a)+$b;
else $s=$p+$b;
if($s==$l)return $a;
if($s>$l)return $a.str_repeat('0',$s-$l);
if($s==0)return "0.$a";
if($s<0)return "0.".str_repeat('0',abs($s)).$a;
return substr_replace($a,".",$s,0);
}static function _powTen1($a,$b){
if(self::_view($a))return '-'.self::_powTen0(self::_not($a),$b);
return self::_powTen0(self::_not($a),$b);
}static function powTen($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_get(self::_powTen1($a,$b));
}
// calc functions
static function _add0($a,$b){
$a=subsplit($a,13);
$b=subsplit($b,13);
$c=count($a)-1;
while($c>=0){
$a[$c]+=$b[$c];
$k=0;
while(@$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _add1($a,$b){
$a=self::_mo($a);
$b=self::_mo($b);
$a[0]=self::_so($a[0],13);
$b[0]=self::_so($b[0],13);
$a[0]=self::_add0("0000000000000{$a[0]}","0000000000000{$b[0]}");
if(isset($a[1])){
$l=strlen($a[1]);
$a[1]=self::_so($a[1],13);
$b[1]=self::_so($b[1],13);
$a[1]=self::_add0("0000000000000{$a[1]}","0000000000000{$b[1]}");
$a[2]=substr($a[1],0,-$l);
$a[1]=substr($a[1],-$l);
if($a[2]>0)$a[0]=self::_add0("0000000000000{$a[0]}","0000000000000".str_repeat('0',strlen($a[0])-1).'1');
return "{$a[0]}.{$a[1]}";
}return $a[0];
}static function _add2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_add1(self::_not($a),self::_not($b));
if( self::_view($a)&&!self::_view($b))return     self::_rem1(self::_not($b),self::_not($a));
if(!self::_view($a)&& self::_view($b))return     self::_rem1(self::_not($a),self::_not($b));
                                      return     self::_add1(self::_not($a),self::_not($b));
}static function add($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
return self::_get(self::_add2($a,$b));
}
// run functions
static function fromNumberString($a='0'){
if(!self::_check($a))return false;
return $a*1;
}static function toNumberString($a=0){
if("$a"=="INF"){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","this number is NAN");
return false;
}if("$a"=="NAN"){
if(strlen($a)>20)$a=substr($a,0,12).'...'.substr($a,-5);
new XNError("XNProCalc","this number is NAN");
return false;
}$a=explode('E',$a);
if(!isset($a[1]))return "{$a[0]}";
$a=self::powTen($a[0],$a[1]);
return $a;
}
}

class XNCalc {

}

?>
