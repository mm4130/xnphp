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
}static function abs($a){
if($a[0]=='-'||$a[0]=='+')return substr($a,1);
return $a;
}static function _change($a){
if($a==0)return '0';
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
if(self::_view($a))return '-'.self::_get2(self::abs($a));
return self::_get2(self::abs($a));
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
}if(isset($a[1]))$a[1]=self::_set1($a[1],@$b[1]);
$a[0]=self::_set0($a[0],$b[0]);
if(!isset($a[1]))return "{$a[0]}";
return "{$a[0]}.{$a[1]}";
}static function _set3($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_set2(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_set2(self::abs($a),self::abs($b));
                                      return     self::_set2(self::abs($a),self::abs($b));
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
}static function _st($a,$b){
if(!isset($a[$b])||$b==0)return $a;
return substr_replace($a,'.',$b,0);
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
}static function _pl($a){
$l='0';
while($a!=$l){
$l=$a;
$a=str_replace(['--','-+','+-','++'],['+','-','-','+'],$a);
}return $a;
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
if(self::_view($a))return '-'.self::_powTen0(self::abs($a),$b);
return self::_powTen0(self::abs($a),$b);
}static function powTen($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
return self::_get(self::_powTen1($a,$b));
}static function _mulTwo0($a){
$a=subsplit($a,13);
$c=count($a)-1;
while($c>=0){
$a[$c]*=2;
$k=0;
while(@$a[$c-$k]>9999999999999){
$a[$c-$k-1]+=1;
$a[$c-$k]-=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _mulTwo1($a){
$a=self::_mo($a);
$a[0]=self::_so($a[0],13);
$a[0]=self::_mulTwo0("0000000000000{$a[0]}");
if(isset($a[1])){
$l=strlen($a[1]);
$a[1]=self::_so($a[1],13);
$a[1]=self::_mulTwo0("0000000000000{$a[1]}");
$a[2]=substr($a[1],0,-$l);
$a[1]=substr($a[1],-$l);
if($a[2]>0)$a[0]=self::_add0("0000000000000{$a[0]}","0000000000000".str_repeat('0',strlen($a[0])-1).'1');
return "{$a[0]}.{$a[1]}";
}return $a[0];
}static function _mulTwo2($a){
if(self::_view($a))return '-'.self::_mulTwo1(self::abs($a));
return self::_mulTwo1(self::abs($a));
}static function mulTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_mulTwo2(self::_get3($a)));
}static function _divTwo0($a){
$s='';
$c=0;
$k=false;
while(isset($a[$c])){
$h=substr($a,$c,14);
$b=floor($h/2);
$b=$k?$b+50000000000000:$b;
$s.=self::_so($b,14);
if($h%2==1)$k=true;
$c+=14;
}if($k)$s.='5';
return $s;
}static function _divTwo1($a){
$p=self::_lm($a);
$a=self::_nm($a);
if($p===false||$p==-1)$p=strlen($a);
$l=strlen($a);
$a=self::_so($a,14);
$p+=strlen($a)-$l;
$a=self::_divTwo0($a);
return self::_st($a,$p);
}static function _divTwo2($a){
if(self::_view($a))return '-'.self::_divTwo1(self::abs($a));
return self::_divTwo1(self::abs($a));
}static function divTwo($a){
return self::_get(self::_divTwo2(self::_get($a)));
}static function _powTwo0($a){
$a=subsplit($a,1);
$x=false;
$c=$d=count($a)-1;
$k=0;
while($c>=0){
$y='';
$e=$d;
$s=0;
while($e>=0){
$t=$a[$c]*$a[$e]+$s;
$s=floor($t/10);
$t-=$s*10;
$y=$t.$y;
$e--;
}$c--;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
$k++;
}return $x;
}static function _powTwo1($a){
$p=self::_lm($a);
if(!$p)return self::_powTwo0($a);
$p=strlen($a)-$p-1;
$p*=2;
$a=self::_nm($a);
$a='0'.self::_powTwo0($a);
return self::_st($a,strlen($a)-$p);
}static function _powTwo2($a){
return self::_powTwo1(self::abs($a));
}static function powTwo($a){
if(!self::_check($a))return false;
return self::_get3(self::_powTwo2(self::_get3($a)));
}
// set functions
static function floor($a){
if(!self::_check($a))return false;
return explode('.',"$a")[0];
}static function ceil($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])?self::add($a[0],'1'):$a[0];
}static function round($a){
if(!self::_check($a))return false;
$a=explode('.',"$a");
return isset($a[1])&&$a[1][0]>=5?self::add($a[0],'1'):$a[0];
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
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_add0($a,$b),$p);
return self::_add0($a,$b);
}static function _add2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return     self::_rem1(self::abs($b),self::abs($a));
if(!self::_view($a)&& self::_view($b))return     self::_rem1(self::abs($a),self::abs($b));
                                      return     self::_add1(self::abs($a),self::abs($b));
}static function add($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==0?$b:
   $b==0?$a:
   self::_add2($a,$b);
return self::_get3($r);
}public function _rem0($a,$b){
$a=subsplit($a,13);
$b=subsplit($b,13);
$c=count($a)-1;
while($c>=0){
$a[$c]-=$b[$c];
$k=0;
while(@$a[$c-$k]<0){
$a[$c-$k-1]-=1;
$a[$c-$k]+=10000000000000;
$k++;
}$a[$c]=self::_so($a[$c],13);
$c--;
}return implode('',$a);
}static function _rem1($a,$b){
$o=self::_lm($a);
$p=$o+(13-(strlen($a)-1)%13);
$a=self::_so(self::_nm($a),13);
$b=self::_so(self::_nm($b),13);
if($o!==false&&$o!==-1)return self::_st(self::_add0($a,$b),$p);
return self::_rem0($a,$b);
}static function _rem2($a,$b){
if( self::_view($a)&& self::_view($b))return '-'.self::_rem1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_add1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return     self::_add1(self::abs($a),self::abs($b));
                                      return     self::_rem1(self::abs($a),self::abs($b));
}static function _rem3($a,$b){
if($a<$b){
return '-'.self::_rem2($b,$a);
}return self::_rem2($a,$b);
}static function rem($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==0?self::_change($b):
   $b==0?$a:
   self::_rem3($a,$b);
return self::_pl(self::_get3($r));
}static function _mul0($a,$b){
$a=subsplit($a,1);
$b=subsplit($b,1);
$x=false;
$c=$d=count($a)-1;
$k=0;
while($c>=0){
$y='';
$e=$d;
$s=0;
while($e>=0){
$t=$a[$c]*$b[$e]+$s;
$s=floor($t/10);
$t-=$s*10;
$y=$t.$y;
$e--;
}$c--;
$t=$s.$y.($k?str_repeat('0',$k):'');
$x=$x?self::add($x,$t):$t;
$k++;
}return $x;
}static function _mul1($a,$b){
$ap=self::_lm($a);
$bp=self::_lm($b);
if(!$ap)return self::_mul0($a,$b);
$ap=strlen($a)-$ap-1;
$bp=strlen($b)-$bp-1;
$p=$ap+$bp;
$a=self::_nm($a);
$b=self::_nm($b);
$a='0'.self::_mul0($a,$b);
return self::_st($a,strlen($a)-$p);
}static function _mul2($a,$b){
if( self::_view($a)&& self::_view($b))return     self::_mul1(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
if( self::_view($a)&&!self::_view($b))return '-'.self::_mul1(self::abs($a),self::abs($b));
                                      return     self::_mul1(self::abs($a),self::abs($b));
}static function mul($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$b==0?0:
   $b==1?$a:
   $b==2?self::mulTwo($a):
   $a==0?0:
   $a==$b?powTwo($a):
   $a==1?$b:
   self::_mul2($a,$b);
return self::_get3($r);
}static function _rand0($a){
$rand="0.";
$b=floor($a/9);
for($c=0;$c<$b;$c++){
$rand.=self::_so(rand(0,999999999),9);
}if($a%9==0)return $rand;
return $rand.self::_so(rand(0,str_repeat('9',$a%9)),$a%9);
}static function _rand1($a,$b){
$c=self::rem($a,$b);
$d=self::_rand0(strlen($a));
return self::add(self::floor(self::mul(self::add($c,'1'),$d)),$b);
}static function _rand2($a,$b){
$p=self::_lm($a);
if(!$p)return self::_rand1($a,$b);
$p=strlen($a)-$p-1;
$a=self::_nm($a);
$b=self::_nm($b);
$a='0'.self::_rand1($a,$b);
return self::_st($a,strlen($a)-$p);
}static function _rand3($b,$a){
if($a>$b)return self::_rand2($a,$b);
return self::_rand2($b,$a);
}static function _rand4($a,$b){
if(self::_view($a)&&self::_view($b))return '-'.self::_rand3(self::abs($a),self::abs($b));
if(!self::_view($a)&& self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$a));
}if(self::_view($a)&&!self::_view($b)){
return self::_change(self::rem(self::_rand3('0',self::add(self::abs($a),self::abs($b))),$b));
}return self::_rand3(self::abs($a),self::abs($b));
}static function rand($a,$b){
if(!self::_check($a))return false;
if(!self::_check($b))return false;
self::_setfull($a,$b);
$r=$a==$b?$a:
   self::_rand4($a,$b);
return self::_get($r);
}static function _div0($a,$b){
if($b>$a)return 0;
if(($c=self::mulTwo($b))>$a)return 1;
if(self::mul($b,'3')>$a)return 2;
if(($c=self::mulTwo($c))>$a)return 3;
if(self::mul($b,'5')>$a)return 4;
if(self::mul($b,'6')>$a)return 5;
if(self::mul($b,'7')>$a)return 6;
if(self::mulTwo($c)>$a)return 7;
if(self::mul($b,'9')>$a)return 8;
                        return 9;
}static function _div2($a,$b,$c=0){
$a=subsplit($a,1);
$p=$r=$i=$d='0';
$c=count($a);
while($i<$c){
$d.=$a[$i];
if($d>=$b){
$p=self::_div0($d,$b);
$d=self::rem($d,self::_mul($p,$b));
$r.=$p;
}else $d.='0';
$i++;
}if($c==0||$d==0)return $r;
return $d;
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
// run functions

}



?>
