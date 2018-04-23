<?php

// Created by whiteweb // xn.white-web.ir // @white_web
// xn plugin files

function fvalid($file){
ob_start();
$f=fopen($file,'r');
ob_end_clean();
if(!$f)return false;
fclose($f);
return true;
}function fcreate($file){
ob_start();
$f=fopen($file,'w');
ob_end_clean();
if(!$f)return false;
fclose($f);
return true;
}function fget($file){
ob_start();
$f=fopen($file,'r');
ob_end_clean();
if(!$f)return false;
$r='';
while(($c=fgetc($f))!==false)$r="$r$c";
fclose($f);
return $r;
}function fput($file,$con){
$f=fopen($file,'w');
if(!$f)return false;
$r=fwrite($f,$con);
fclose($f);
return $r;
}function fadd($file,$con){
$f=fopen($file,'a');
if(!$f)return false;
$r=fwrite($f,$con);
fclose($f);
return $r;
}function fdel($file){
return unlink($file);
}function fputjson($file,$con,$json=false){
return fput($file,json_encode($con,$json));
}function fgetjson($file,$json=false){
return json_decode(fget($file),$json);
}function faddjson($file,$con,$json=false){
$f=fopen($file,'a+');
if(!$f)return false;
$r='';
while($c=fgetc($f))$r="$r$c";
$r=json_decode($r,true);
$r=array_merge($r,(array)$con);
$w=fwrite($f,json_encode($con,$json));
fclose($f);
return $w;
}function fexists($file){
return file_exists($file);
}function fsize($file){
return filesize($file);
}function fspeed($file,$type='r'){
if($f=fopen($file,$type))fclose($f);
}function ftype($file){
return filetype($file);
}function fdir($file){
return dirname($file);
}function fname($file){
$file=explode('/',$file);
return end($file);
}function fformat($file){
$file=explode('.',$file);
return end($file);
}function dirdel($dir){
$s=scandir($dir);
if($s[0]=='..')unset($s[0]);
if($s[1]=='.')unset($s[1]);
foreach($s as $f){
if(filetype("$dir/$f")=='dir')dirdel("$dir/$f");
else unlink("$dir/$f");
}return rmdir($dir);
}function dirscan($dir){
$s=scandir($dir);
if($s[0]=='..')unset($s[0]);
if($s[1]=='.')unset($s[1]);
return $s;
}function dircopy($from,$to){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
mkdir($to);
foreach($s as $file){
if(filetype("$dir/$file")=='dir')dircopy("$dir/$file","$to/$file");
else copy("$dir/$file","$to/$file");
}
}function dirsearch($dir,$search){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
$r=[];
foreach($s as $file){
if(strpos($file,$search))$r[]="$dir/$file";
if(filetype("$dir/$file")=='dir')$r=array_merge($r,dirsearch("$dir/$file",$search));
}return $r;
}function preg_dirsearch($dir,$search){
$s=scandir($dir);
unset($s[0]);
unset($s[1]);
$r=[];
foreach($s as $file){
if(preg_match($search,$file))$r[]="$dir/$file";
if(filetype("$dir/$file")=='dir')$r=array_merge($r,dirsearch("$dir/$file",$search));
}return $r;
}function dirread($dir){
$s=scandir($dir);
$r=[];
foreach($s as $file){
if($file=='..')$r[$file]=true;
elseif($file=='.')$r[$file]=&$r;
elseif(filetype("$dir/$file")=='dir'){
$r[$file]=dirread("$dir/$file");
$r[$file]['..']=&$r;
}else $r=(object)[
"read"=>function()use($dir,$file){
return fget("$dir/$file");
},"write"=>function($con)use($dir,$file){
return fput("$dir/$file",$con);
},"add"=>function($con)use($dir,$file){
return fadd("$dir/$file",$con);
},"pos"=>function($pos)use($dir,$file){
return fpos("$dir,$file",$pos);
},"explode"=>function($ex)use($dir,$file){
return fexplode("$dir/$file",$ex);
},"size"=>filesize("$dir/$file"),
"mode"=>fileperms("$dir/$file"),
"address"=>"$dir/$file"
];
}
}function fperms($file){
return fileperms($file);
}function fpos($file,$str,$from=false){
$f=fopen($file,'r');
if($from)fseek($f,$from);
$s='';$m=0;$o=0;
while(($c=fgetc($f))!==false&&$s!=$str){
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o-1;
return false;
}function mb_fgetc($file){
$l='';$s='';
while(mb_strlen($s)<2&&!feof($file)){
$l=$s;$s=$s.fgetc($file);
}fseek($file,-1,SEEK_CUR);
return $l;
}function mb_fpos($file,$str,$from=false){
$f=fopen($file,'r');
if($from)fseek($f,$from);
$s='';$m=0;$o=0;
while(($c=mb_fgetc($f))&&$s!=$str){
if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$s='';$m=0;
}$o++;
}fclose($f);
if($s==$str)return $o-1;
return false;
}function fexplode($file,$str){
$f=fopen($file,'r');
$s='';$m=0;$r=[];$k='';
$p=true;
while(($c=fgetc($f))!==false){
$l=$c;
if($s==$str){
$r[]=$k;
$s='';$m=0;$k='';
}if($str[$m]==$c){
$m++;$s="$s$c";
}else{
$k="$k$s$c";
$s='';$m=0;
}}$r[]=$k;
fclose($f);
if($str==$l||$str=='')$r[]='';
return $r;
}function foundurl($file){
return filter_var($file,FILTER_VALIDATE_URL)&&fvalid($file)&&!file_exists($file);
}function fsubget($file,$from=0,$to=-1){
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=fgetc($f))!==false&&$to!=0){
$r="$r$c";
$to--;
}fclose($r);
return $r;
}function mb_fsubget($file,$from=0,$to=-1){
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=mb_fgetc($f))&&$to!=0){
$r="$r$c";
$to--;
}fclose($r);
return $r;
}function fcopy($from,$to){
$fm=fopen($from,'r');
$to=fopen($to,'w');
fwrite($to,fread($fm,filesize($from)));
fclose($fm);
return fclose($to);
}class CURLURL {
private $file;
public function load($url,$name=false,$mime='',$postname=''){
if(!$name)$name="xn_log.CURLFile.".fname($url);
if(!copy($url,$name))return false;
$this->file=$name;
return new CURLFile($name,$mime,$postname);
}public function __destruct(){
if(isset($this->file)){
unlink($this->file);
unset($this->file);
}}
}class CURLStr {
private $file;
public function load($str,$name=false,$mime='',$postname=''){
if(!$name)$name="xn_log.CURLFile.txt";
if(!fput($name,$str))return false;
$this->file=$name;
return new CURLFile($name,$mime,$postname);
}public function __destruct(){
if(isset($this->file)){
unlink($this->file);
unset($this->file);
}}
}function CURLFile($name='',$mime='',$postname=''){
return new CURLFile($name,$mime,$postname);
}function CURLURL($url,$name='',$mime='',$postname=''){
return (new CURLURL)->load($url,$name,$mime,$postname);
}function CURLStr($str,$name='',$mime='',$postname=''){
return (new CURLStr)->load($str,$name,$mime,$postname);
}function freplace($file,$str,$to){
$f=fopen($file,'r');
$d=fopen("xn_log.$file",'w');
$s='';$m=0;
while(($c=fgetc($f))!==false){
if($s==$str){
fwrite($d,$to);
$s='';$m=0;
}if($str[$m]==$c){
$m++;$s="$s$c";
}else{
fwrite($d,"$s$c");
$s='';$m=0;
}}if($s==$str){
fwrite($d,$to);
$s='';$m=0;
}fclose($f);
fclose($d);
copy("xn_log.$file",$file);
return unlink("xn_log.$file");
}function fgetprogress($file,$func,$al){
ob_start();
$f=fopen($file,'r');
ob_end_clean();
if(!$f)return false;
$r='';
$k=$al;
while(($c=fgetc($f))!==false){
$r="$r$c";
if((--$k)<=0){
$k=$al;if($func($r)){
fclose($f);
return $r;
}}
}fclose($f);
return $r;
}function fgetjsonprogress($file,$func,$al,$json=false){
ob_start();
$f=fopen($file,'r');
ob_end_clean();
if(!$f)return false;
$r='';
$k=$al;
while(($c=fgetc($f))!==false){
$r="$r$c";
if((--$k)<=0){
$k=$al;if($func($r)){
fclose($f);
return json_decode($r,$json);
}}
}fclose($f);
return json_decode($r,$json);
}function dirfilesinfo($dir){
$size=0;
$foldercount=0;
$filecount=0;
$s=scandir($dir);
if($s[0]=='..')unset($s[0]);
if($s[1]=='.')unset($s[1]);
if($dir=='/')$dir='';
foreach($s as $file){
if($file=='.'||$file=='..');
if(filetype("$dir/$file")=="dir"){
$dircount++;
$size+=filesize("$dir/$file");
$i=dirfilesinfo("$dir/$file");
$size+=$i->size;
$foldercount+=$i->folder;
$filecount+=$i->file;
}else{
$filecount++;
$size+=filesize("$dir/$file");
}
}return (object)["size"=>$size,"folder"=>$foldercount,"file"=>$filecount];
}
?>
