<?php
// creator : avid
// xn plugin Codeing v2


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
}function number_string_encode($str){
$c=0;
$s='';
while(isset($str[$c])){
$s.='9'.base_convert(ord($str[$c++]),10,9);
}return substr($s,1);
}function number_string_decode($str){
$c=0;
$str=explode('9',$str);
$s='';
while(isset($str[$c])){
$s.=chr(base_convert($str[$c++],9,10));
}return $s;
}
class XNJsonMath {
private $xnj;
public function __construct($xnj){
$this->xnj=$xnj;
}public function add($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)+$count);
return $this;
}public function rem($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)-$count);
return $this;
}public function div($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)/$count);
return $this;
}public function mul($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)*$count);
return $this;
}public function pow($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)**$count);
return $this;
}public function rect($key,$count=1){
$this->xnj->set($key,$this->xnj->value($key)%$count);
return $this;
}public function calc($key,$calc){
$this->xnj->set($key,XNCalc::calc($calc,['x'=>$this->xnj->value($key)]));
return $this;
}public function join($key,$data){
$this->xnj->set($key,$this->xnj->value($key).$data);
return $this;
}
}class XNJsonProMath {
private $xnj;
public function __construct($xnj){
$this->xnj=$xnj;
}public function add($key,$count=1){
$this->xnj->set($key,XNProCalc::add($this->xnj->value($key),$count));
return $this;
}public function rem($key,$count=1){
$this->xnj->set($key,XNProCalc::rem($this->xnj->value($key),$count));
return $this;
}public function mul($key,$count=1){
$this->xnj->set($key,XNProCalc::mul($this->xnj->value($key),$count));
return $this;
}public function div($key,$count=1){
$this->xnj->set($key,XNProCalc::div($this->xnj->value($key),$count));
return $this;
}public function rect($key,$count=1){
$this->xnj->set($key,XNProCalc::rect($this->xnj->value($key),$count));
return $this;
}public function pow($key,$count=1){
$this->xnj->set($key,XNProCalc::pow($this->xnj->value($key),$count));
return $this;
}public function calc($key,$calc){
$this->xnj->set($key,XNProCalc::calc($calc,['x'=>$this->xnj->value($key)]));
return $this;
}
}
class XNJsonString {
private $data;
public $Math,$proMath;
public function __construct($data=','){
$this->data=$data;
$this->Math=new XNJsonMath($this);
$this->proMath=new XNJsonProMath($this);
}public function convert($file){
fput($file,$this->data);
return new XNJsonFile($file);
}public function reset(){
$this->data=',';
return $this;
}public function get(){
return $this->data;
}public function close(){
$this->data=null;
}public function __toString(){
return $this->data;
}private function encode($data){
$type=gettype($data);
switch($type){
case "NULL":
$type=1;
$data='';
break;case "boolean":
if($data)$type=2;
else $type=3;
$data='';
break;case "integer":
$type=4;
break;case "float":
$type=5;
break;case "double":
$type=6;
break;case "string":
$type=7;
break;case "array":
case "object":
$type=8;
$data=serialize($data);
break;default:
new XNError("XNJson","invalid data type");
return false;
}$zdata=zlib_encode($data,31);
if(strlen($zdata)<strlen($data)){
$data=$zdata;
$type+=8;
}$data=base64url_encode(chr($type).$data);
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return $size.':'.$data;
}private function decode($data){
$data=explode(':',$data);
$data=end($data);
$data=base64url_decode($data);
$type=ord($data);
$data=substr($data,1);
if($type>8){
$data=zlib_decode($data);
$type-=8;
}switch($type){
case 1:
return null;
break;case 2:
return true;
break;case 3:
return false;
break;case 4:
return (int)$data;
break;case 5:
return (float)$data;
break;case 6:
return (double)$data;
break;case 7:
return (string)$data;
break;case 8:
return unserialize($data);
}
}private function elencode($key,$value){
$data="$key.$value";
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return "$size;$data";
}private function eldecode($code){
return explode('.',explode(";",$code)[1]);
}private function sizedecode($size){
return base_convert(bin2hex(base64url_decode($size)),16,10);
}public function value($key){
$key=';'.$this->encode($key).'.';
$p=strpos($this->data,$key);
if($p===false||$p==-1)return false;
$p+=strlen($key);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$size=$this->sizedecode($size);
return $this->decode(substr($this->data,$p,$size));
}public function key($value){
$value='.'.$this->encode($value).',';
$p=strpos($this->data,$value);
if($p===false||$p==-1)return false;
$key='';
while(($h=$this->data[$p--])!==':')$key=$h.$key;
return $this->decode($key);
}public function iskey($key){
$key=';'.$this->encode($key).'.';
$p=strpos($this->data,$key);
return $p!=-1&&$p!==false;
}public function isvalue($value){
$value='.'.$this->encode($value).',';
$p=strpos($this->data,$key);
return $p!=-1&&$p!==false;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}public function keys($value){
$values=[];
$data=$this->data;
$value='.'.$this->encode($value).',';
$vallen=strlen($value)-1;
while($data!=','){
$p=strpos($data,$value);
if($p===false||$p==-1)break;
$pp=$p;
$key='';
while(($h=$data[$p--])!==':')$key=$h.$key;
$data=substr($data,$pp+$vallen);
$values[]=$this->decode($key);
}return $values;
}private function replace($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el2=$this->elencode($key,$value);
$ky=';'.$key.'.';
$p=strpos($this->data,$ky)+strlen($ky);
$size='';
while(($h=$this->data[$p++])!==':')$size.=$h;
$sizee=$size;
$size=$this->sizedecode($size);
$value=$sizee.':'.substr($this->data,$p,$size);
$el1=$this->elencode($key,$value);
$this->data=str_replace($el1,$el2,$this->data);
return $this;
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$this->data.="$el,";
return $this;
}public function set($key,$value){
if(self::iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function array(){
$data=explode(',',substr($this->data,1,-1));
foreach($data as &$dat){
$dat=$this->eldecode($dat);
$dat[0]=$this->decode($dat[0]);
$dat[1]=$this->decode($dat[1]);
}return $data;
}public function count(){
return count(explode(',',$this->data))-2;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}
}class XNJsonFile {
private $file;
public $Math,$proMath;
public function __construct($file){
$this->file=$file;
$this->Math=new XNJsonMath($this);
$this->proMath=new XNJsonProMath($this);
if(!file_exists($file))fput($file,',');
}public function convert(){
return new XNJsonString(fget($this->file));
}public function reset(){
fput($this->file,',');
return $this;
}public function get(){
return fget($this->file);
}public function close(){
$this->file=null;
}public function __toString(){
return fget($this->file);
}public function getFile(){
return $this->file;
}private function encode($data){
$type=gettype($data);
switch($type){
case "NULL":
$type=1;
$data='';
break;case "boolean":
if($data)$type=2;
else $type=3;
$data='';
break;case "integer":
$type=4;
break;case "float":
$type=5;
break;case "double":
$type=6;
break;case "string":
$type=7;
break;case "array":
case "object":
$type=8;
$data=serialize($data);
break;default:
new XNError("XNJson","invalid data type");
return false;
}$zdata=zlib_encode($data,31);
if(strlen($zdata)<strlen($data)){
$data=$zdata;
$type+=8;
}$data=base64url_encode(chr($type).$data);
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return $size.':'.$data;
}private function decode($data){
$data=explode(':',$data);
$data=end($data);
$data=base64url_decode($data);
$type=ord($data);
$data=substr($data,1);
if($type>8){
$data=zlib_decode($data);
$type-=8;
}switch($type){
case 1:
return null;
break;case 2:
return true;
break;case 3:
return false;
break;case 4:
return (int)$data;
break;case 5:
return (float)$data;
break;case 6:
return (double)$data;
break;case 7:
return (string)$data;
break;case 8:
return unserialize($data);
}
}private function elencode($key,$value){
$data="$key.$value";
$size=base_convert(strlen($data),10,16);
if(strlen($size)%2==1)$size="0$size";
$size=base64url_encode(hex2bin($size));
return "$size;$data";
}private function eldecode($code){
return explode('.',explode(";",$code)[1]);
}private function sizedecode($size){
return base_convert(bin2hex(base64url_decode($size)),16,10);
}public function key($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
$p--;
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=ftell($f);
fseek($f,$s+1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fseek($f,$key);
$key=fread($f,$s);
fclose($f);
return $this->decode($key);
}public function value($key){
$f=fopen($this->file,'r');
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fseek($f,$p,SEEK_CUR);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
$value=fread($f,$p);
fclose($f);
return $this->decode($value);
}public function keys($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
$keys=[];
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1){
$m=0;
$o=1;
$p='';
$s='';
$keys[]=$this->decode($key);
}elseif($value[$m]==$h){
$m++;
}else{
$m=0;
@fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
$key=fread($f,$s);
fseek($f,1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}fclose($f);
return $keys;
}public function iskey($key){
$f=fopen($this->file,'r');
fseek($f,1);
$key=$this->encode($key).'.';
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1)break;
if($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fseek($f,$p,SEEK_CUR);
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function isvalue($value){
$f=fopen($this->file,'r');
fseek($f,1);
$value=$this->encode($value).',';
$l=strlen($value);
$p='';
$m=0;
$o=1;
$s='';
while(($h=fgetc($f))!==false){
if($o==2){
$p--;
if($m==$l-1)break;
if($value[$m]==$h){
$m++;
}else{
$m=0;
fseek($f,$p,SEEK_CUR);
$o=1;
$p='';
$s='';
}
}elseif($o==3){
if($h==':'){
$o=2;
$p-=($s=$this->sizedecode($s))+1;
fseek($f,$s+1,SEEK_CUR);
}else{
$s.=$h;
}
}else{
if($h==';'){
$o=3;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}if($h===false)return false;
fclose($f);
return true;
}public function type($key){
return $this->iskey($key)?"key":$this->isvalue($key)?"value":false;
}private function replace($key,$value){
$key=$this->encode($key).'.';
$value=$this->encode($value).',';
$el=$this->elencode($key,$value);
$f=fopen($this->file,'r');
$random=rand(0,999999999).rand(0,999999999);
$t=fopen("xn$random.$this->file.log",'w');
fwrite($t,',');
$l=strlen($key);
$p='';
$m=0;
$o=false;
while(($h=fgetc($f))!==false){
if($o){
$p--;
if($m==$l-1){
$m=0;
fwrite($t,$this->elencode(substr($key,0,-1),$value));
fseek($f,$p+1,SEEK_CUR);
break;
}elseif($key[$m]==$h){
$m++;
}else{
$m=0;
$o=false;
fwrite($t,substr($key,0,$m).fread($f,$p).',');
$p='';
}
}else{
if($h==';'){
$o=true;
$p=$this->sizedecode($p);
}else{
$p.=$h;
}}}
$g=ftell($f);
fseek($f,0,SEEK_END);
$u=ftell($f)-$g;
if($u>0){
fseek($f,$g);
fwrite($t,fread($f,$u));
}fclose($f);
fclose($t);
copy("xn$random.$this->file.log",$this->file);
unlink("xn$random.$this->file.log");
}private function add($key,$value){
$key=$this->encode($key);
$value=$this->encode($value);
$el=$this->elencode($key,$value);
$f=fopen($this->file,'a');
fwrite($f,"$el,");
fclose($f);
}public function set($key,$value){
if($this->iskey($key))$this->replace($key,$value);
else $this->add($key,$value);
return $this;
}public function array(){
$f=fopen($this->file,'r');
fseek($f,1);
$arr=[];
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
$ar=$this->eldecode(';'.fread($f,$p));
$ar[0]=$this->decode($ar[0]);
$ar[1]=$this->decode($ar[1]);
$arr[]=$ar;
fseek($f,1,SEEK_CUR);
$p='';
}else{
$p.=$h;
}}return $arr;
}public function count(){
$f=fopen($this->file,'r');
fseek($f,1);
$c=0;
$p='';
while(($h=fgetc($f))!==false){
if($h==';'){
$p=$this->sizedecode($p);
fseek($f,$p+1,SEEK_CUR);
$c++;
$p='';
}else{
$p.=$h;
}}return $c;
}public function list($list){
foreach((array)$list as $key=>$value)
$this->set($key,$value);
return $this;
}
}function XNJson($j=',',$file=false){
if(is_array($j)){
if($file&&$file!='.'&&$file!='..')$xnj=new XNJsonFile($file);
else $xnj=new XNJsonString();
$xnj->list($j);
return $xnj;
}if(!$file&&$j!='.'&&$j!='..'&&file_exists($j))return new XNJsonFile($j);
if($file)return new XNJsonFile($j);
return new XNJsonString($j);
}
?>
