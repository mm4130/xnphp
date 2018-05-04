<?php

// Created by ...
// xn plugin cfile (creating...)

class XNColor {
static function init($color=0){
return [$color&0xff,($color>>8)&0xff,($color>>16)&0xff,($color>>24)&0xff];
}static function read($color=0){
return ["red"=>$color&0xff,"green"=>($color>>8)&0xff,"blue"=>($color>>16)&0xff,"alpha"=>($color>>24)&0xff];
}static function par($a=0,$b=false,$c=false,$d=false){
if(is_array($a)){
$b=isset($a[1])?$a[1]:0;
$c=isset($a[2])?$a[2]:0;
$d=isset($a[3])?$a[3]:0;
$a=isset($a[0])?$a[0]:0;
}elseif($a&&gettype($a)=="string"&&$b===false&&$c===false&&$d===false){
$r=@[][$a];
if($r===null){
$l=strlen($a);
if($l%2==1&&$l!=3){
$a=substr($a,1);
$l--;
}if($l==3)$a=$a[0].$a[0].$a[1].$a[1].$a[2].$a[2];
elseif($l==4)$a=$a[0].$a[0].$a[1].$a[1].$a[2].$a[2].$a[3].$a[3];
elseif($l!=6&&$l!=8){
new XNError("XNColor","Invalid hex color or color name.",1);
return false;
}$d=isset($a[6])?hexdec($a[6].$a[7]):0;
$b=hexdec($a[2].$a[3]);
$c=hexdec($a[4].$a[5]);
$a=hexdec($a[0].$a[1]);
return [$a,$b,$c,$d];
}return $r;
}if(!is_numeric($a)){
new XNError("XNColor","Parameters is not number.",1);
return false;
}$a=$a&&$a>-1?$a%256:0;
$b=$b&&$b>-1?$b%256:0;
$c=$c&&$c>-1?$c%256:0;
$d=$d&&$d>-1?$d%256:0;
return [$a,$b,$c,$d];
}static function get($a=0,$b=false,$c=false,$d=false){
$color=self::par($a,$b,$c,$d);
if($color===false)return false;
return $color[0]+($color[1]<<8)+($color[2]<<16)+($color[3]<<24);
}static function hex($a=0,$b=false,$c=false,$d=false,$tag=true){
$color=self::par($a,$b,$c,$d);
if($color===false)return false;
$last=[$color[0],$color[1],$color[2],$color[3]];
$color[0]=dechex($color[0]);
$color[1]=dechex($color[1]);
$color[2]=dechex($color[2]);
$color[3]=$color[3]?dechex($color[3]):false;
$color[0]=$last[0]<10?'0'.$color[0]:$color[0];
$color[1]=$last[1]<10?'0'.$color[1]:$color[1];
$color[2]=$last[2]<10?'0'.$color[2]:$color[2];
$color[3]=$color[3]?($last[3]<10?'0'.$color[3]:$color[3]):false;
if(!$color[3]){
if($color[0][0]==$color[0][1]&&$color[1][0]==$color[1][1]&&$color[2][0]==$color[2][1])
return ($tag?"#":'').$color[0][0].$color[1][0].$color[2][0];
return ($tag?"#":'').$color[0].$color[1].$color[2];
}else{
if($color[0][0]==$color[0][1]&&$color[1][0]==$color[1][1]&&$color[2][0]==$color[2][1]&&$color[3][0]==$color[3][1])
return ($tag?"#":'').$color[0][0].$color[1][0].$color[2][0].$color[3][0];
return ($tag?"#":'').$color[0].$color[1].$color[2].$color[3];
}
}static function fromXYBri($x,$y,$br){
$_x=($x*$br)/$y;
$_y=$br;
$_z=((1-$x-$y)*$br)/$y;
$r=$_x*3.2406 +$_y*-1.5372+$_z*-0.4986;
$g=$_x*-0.9689+$_y*1.8758 +$_z*0.0415 ;
$b=$_x*0.0557 +$_y*-0.2040+$_z*1.0570 ;
$r=$r>0.0031308?1.055*pow($r,1/2.4)-0.055:12.92*$r;
$g=$g>0.0031308?1.055*pow($g,1/2.4)-0.055:12.92*$g;
$b=$b>0.0031308?1.055*pow($b,1/2.4)-0.055:12.92*$b;
$r=$r>0?round($r*255):0;
$g=$g>0?round($g*255):0;
$b=$b>0?round($b*255):0;
return ["red"=>$r,"green"=>$g,"blue"=>$b];
}static function toHsvInt($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$min=min($rgb);
$max=max($rgb);
$hsv=['hue'=>0,'sat'=>0,'val'=>$max];
if($max==0)return $hsv;
$hsv['sat']=round(255*($max-$min)/$hsv['val']);
if($hsv['sat']==0){
$hsv['hue']=0;
return $hsv;
}$hsv['hue']=$max==$rgb['red']?round(0+43*($rgb['green']-$rgb['blue'])/($max-$min)):
($max==$rgb['green']?round(171+43*($rgb['red']-$rgb['green'])/($max-$min)):
round(171+43*($rgb['red']-$rgb['green'])/($max-$min)));
if($hsv['hue']<0)$hsv['hue']+=255;
return $hsv;
}static function toHsvFloat($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$min=min($rgb);
$max=max($rgb);
$hsv=['hue'=>0,'sat'=>0,'val'=>$max];
if($hsv['val']==0)return $hsv;
$rgb['red']/=$hsv['val'];
$rgb['green']/=$hsv['val'];
$rgb['blue']/=$hsv['val'];
$min=min($rgb);
$max=max($rgb);
$hsv['sat']=$max-$min;
if($hsv['sat']==0){
$hsv['hue']=0;
return $hsv;
}$rgb['red'] =($rgb['red']  -$min)/($max-$min);
$rgb['green']=($rgb['green']-$min)/($max-$min);
$rgb['blue'] =($rgb['blue'] -$min)/($max-$min);
$min=min($rgb);
$max=max($rgb);
if($max==$rgb['red']){
$hsv['hue']=0.0+60*($rgb['green']-$rgb['blue']);
if($hsv['hue']<0){
$hsv['hue']+=360;
}}else $hsv['hue']=$max==$rgb['green']?120+(60*($rgb['blue']-$rgb['red'])):
240+(60*($rgb['red']-$rgb['green']));
return $hsv;
}static function toXYZ($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$rgb=array_map(function($i){
return $i/255;
},$rgb);
$rgb=array_map(function($i){
return $i>0.04045?pow((($i+0.055)/1.055)*100,2.4):$item/12.92*100;
},$rgb);
$xyz=[
'x'=>($rgb['red']*0.4124)+($rgb['green']*0.3576)+($rgb['blue']*0.1805),
'y'=>($rgb['red']*0.2126)+($rgb['green']*0.7152)+($rgb['blue']*0.0722),
'z'=>($rgb['red']*0.0193)+($rgb['green']*0.1192)+($rgb['blue']*0.9505)
];return $xyz;
}static function toLabCie($a=0,$b=false,$c=false) {
$xyz=$this->toXYZ($a,$b,$c);
if($xyz===false)return false;
$xyz['x']/=95.047;
$xyz['y']/=100;
$xyz['z']/=108.883;
$xyz=array_map(function($item){
if($item>0.008856){
return pow($item,1/3);
}else{
return (7.787*$item)+(16/116);
}},$xyz);
$lab=[
'l'=>(116*$xyz['y'])-16,
'a'=>500*($xyz['x']-$xyz['y']),
'b'=>200*($xyz['y']-$xyz['z'])
];return $lab;
}static function toXYBri($a=0,$b=false,$c=false){
$rgb=self::par($a,$b,$c);
if($rgb===false)return false;
$rgb=["red"=>$rgb[0],"green"=>$rgb[1],"blue"=>$rgb[2]];
$r=$rgb['red'];
$g=$rgb['green'];
$b=$rgb['blue'];
$r=$r/255;
$g=$g/255;
$b=$b/255;
if($r<0||$r>1||$g<0||$g>1||$b<0||$b>1){
new XNError("XNColor XYBri","Invalid RGB array. [{$r},{$b},{$g}]");
}$rt=($r>0.04045)?pow(($r+0.055)/(1.0+0.055),2.4):($r/12.92);
$gt =($g>0.04045)?pow(($g+0.055)/(1.0+0.055),2.4):($g/12.92);
$bt =($b>0.04045)?pow(($b+0.055)/(1.0+0.055),2.4):($b/12.92);
$cie_x=$rt*0.649926 +$gt*0.103455+$bt*0.197109;
$cie_y=$rt*0.234327 +$gt*0.743075+$bt*0.022598;
$cie_z=$rt*0.0000000+$gt*0.053077+$bt*1.035763;
if($cie_x+$cie_y+$cie_z==0){
$hue_x=0.1;
$hue_y=0.1;
}else{
$hue_x=$cie_x/($cie_x+$cie_y+$cie_z);
$hue_y=$cie_y/($cie_x+$cie_y+$cie_z);
}return ['x'=>$hue_x,'y'=>$hue_y,'bri'=>$cie_y];
}static function average($from,$to=false){
$from=self::init($from);
if(!$to){
return ($from[0]+$from[1]+$from[2])/3;
}$to=self::init($to);
$from[0]=($from[0]+$to[0])/2;
$from[1]=($from[1]+$to[1])/2;
$from[2]=($from[2]+$to[2])/2;
$from[3]=($from[3]+$to[3])/2;
return $from;
}static function averageAll($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2)/3;
return [$av,$av,$av];
}static function averageAllAlpha($from,$to){
$from=self::init($from);
$to=self::init($to);
$av=(($from[0]+$to[0])/2+($from[1]+$to[1])/2+($from[2]+$to[2])/2+($from[3]+$to[3]))/4;
return [$av,$av,$av,$av];
}static function toBW($color){
$color=self::init($color);
return 16777215*(int)(($color[0]+$color[1]+$color[2])/3>127.5);
}
}


class XNImage {
private $headers=[];
public $pixels=[],$info=[];
private const HEADER_PNG="\x89\x50\x4e\x47\x0d\x0a\x1a\x0a";
public function __construct($data=''){
$this->color=new XNColor;

}private function _clone($headers,$pixels,$info){
$this->headers=$headers;
$this->pixels=$pixels;
$this->info=$info;
}public function clone(){
$im=new XNImage;
$im->_clone($this->headers,$this->pixels,$this->info);
return $im;
}public function __clone(){
$im=new XNImage;
$im->_clone($this->headers,$this->pixels,$this->info);
return $im;
}public function serialize(){
$im=new XNImage;
unset($im->color);
$im->headers=$this->headers;
$im->pixels=$this->pixels;
$im->info=$this->info;
return serialize($im);
}static function unserialize($str){
$im=new XNImage;
$str=unserialize($str);
$im->headers=$str->headers;
$im->pixels=$str->pixels;
$im->info=$str->info;
return $im;
}public function reset(){
$this->headers=[];
$this->pixels=[];
$this->info=[];
}public function close(){
$this->color=null;
$this->headers=null;
$this->pixels=null;
$this->info=null;
}public function __destruct(){
$this->color=null;
$this->headers=null;
$this->pixels=null;
$this->info=null;
}public function frompng($png){
$pos=0;
if(isset($png[7])&&substr($png,0,8)==self::HEADER_PNG){
$pos=8;
}elseif(file_exists($png)){
return $this->frompng(file_get_contents($png));
}else{
new XNError("XNImage","invalid png image");
return false;
}$htitle='';
while($htitle!="IEND"){
$hsize=base10_encode(substr($png,$pos,4));
$pos+=4;
$htitle=substr($png,$pos,4);
$pos+=4;
$hcontent=substr($png,$pos,$hsize);
$pos+=$hsize;
$hcrc=substr($png,$pos,4);
$pos+=4;
if(!$htitle){
new XNError("XNImage","invalid png image");
return false;
}if(!isset($this->headers[$htitle]))$this->headers[$htitle]=["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc];
elseif(is_string($this->headers[$htitle]))$this->headers[$htitle]=[
$this->headers[$htitle],
["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc]
];else $this->headers[$htitle][]=["size"=>$hsize,"content"=>$hcontent,"crc"=>$hcrc];
}if(!isset($this->headers['IDAT'])||!isset($this->headers['IHDR'])){
new XNError("XNImage","invalid png image");
return false;
}$this->info['width']=base10_encode(substr($this->headers['IHDR']['content'],0,4));
$this->info['height']=base10_encode(substr($this->headers['IHDR']['content'],4,4));
$this->info['depth']=ord($this->headers['IHDR']['content'][8]);
$this->info['color']=ord($this->headers['IHDR']['content'][9]);
$this->info['compression']=ord($this->headers['IHDR']['content'][10]);
$this->info['filter']=ord($this->headers['IHDR']['content'][11]);
$this->info['interlace']=ord($this->headers['IHDR']['content'][12]);
$pixels=$this->headers['IDAT']['content'];
$pixels=zlib_decode($pixels);
$pos=0;
$x=-1;$y=0;
while(@$pixels[$pos+3]){
$x++;
if($x+1>$this->info['width']){
$x=0;$y++;
}$this->pixels[$y][$x]=base10_encode(substr($pixels,$pos,4));
$pos+=4;
}

return $this;
}

}


?>
