<?php

// Created by ...
// xn plugin files v1

function fvalid($file){
$f=@fopen($file,'r');
if(!$f)return false;
fclose($f);
return true;
}function fcreate($file){
$f=@fopen($file,'w');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}fclose($f);
return true;
}function fget($file){
$size=@filesize($file);
if($size!==false&&$size!==null){
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}$r=fread($f,$size);
}else{
$ch=@curl_init($file);
if($ch){
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$r=curl_exec($ch);
curl_close($ch);
return $r;
}else{
$r='';
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}while(($c=fgetc($f))!==false)$r.=$c.fread($f,1024);
}}fclose($f);
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
while($c=fgetc($f))$r.=$c;
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
if($f=@fopen($file,$type))fclose($f);
return $f;
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
$s=dirscan($dir);
foreach($s as $f){
if(filetype("$dir/$f")=='dir')dirdel("$dir/$f");
else unlink("$dir/$f");
}return rmdir($dir);
}function dirscan($dir){
$s=scandir($dir);
if($s[0]=='..')unset($s[0]);
if($s[1]=='.')unset($s[1]);
if($s[0]=='.')unset($s[0]);
return $s;
}function dircopy($from,$to){
$s=dirscan($dir);
mkdir($to);
foreach($s as $file){
if(filetype("$dir/$file")=='dir')dircopy("$dir/$file","$to/$file");
else copy("$dir/$file","$to/$file");
}
}function dirsearch($dir,$search){
$s=dirscan($dir);
$r=[];
foreach($s as $file){
if(strpos($file,$search))$r[]="$dir/$file";
if(filetype("$dir/$file")=='dir')$r=array_merge($r,dirsearch("$dir/$file",$search));
}return $r;
}function preg_dirsearch($dir,$search){
$s=dirscan($dir);
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
}function fsubget($file,$from=0,$to=false){
if($to===false)$t=filesize($file);
elseif($to<0)$to=filesize($file)+$to;
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=fgetc($f))!==false&&$to!=0){
$r.=$c;
$to--;
}fclose($r);
return $r;
}function mb_fsubget($file,$from=0,$to=false){
if($to===false)$t=filesize($file);
elseif($to<0)$to=filesize($file)+$to;
$f=fopen($file,'r');
fseek($f,$from);
$r='';
while(($c=mb_fgetc($f))&&$to!=0){
$r.=$c;
$to--;
}fclose($r);
return $r;
}function fcopy($from,$to){
$to=@fopen($to,'w');
if(!$to)return false;
$w=fwrite($to,fget($from));
return fclose($to)?$w:false;
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
$al=$al>0?$al:1;
$f=@fopen($file,'r');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}$r='';
while(!feof($f)){
$r.=fread($f,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function dirfilesinfo($dir){
$size=0;
$foldercount=0;
$filecount=0;
$s=dirscan($dir);
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
}function dirfcreate($dir,$cur='.',$in=false){
$dirs=$dir=explode('/',$dir);
unset($dirs[count($dirs)-1]);
foreach($dirs as $d){
$pt=false;
if(@file_exists("$cur/$d")&&@filetype("$cur/$d")=="file"){
if($in)$pt=fget("$cur/$d");
@unlink("$cur/$d");
}@mkdir($cur="$cur/$d");
if($in&&$pt!==false)@fput("$cur/$d/$in",$pt);
}return @fcreate("$cur/".end($dir));
}function fputprogress($file,$content,$func,$al){
$al=$al>0?$al:1;
$f=@fopen($file,'w');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}$r='';
while($content){
$r.=$th=substr($content,0,$al);
fwrite($f,$th);
$content=substr($content,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function faddprogress($file,$content,$func,$al){
$al=$al>0?$al:1;
$f=@fopen($file,'a');
if(!$f){
new XNError("Files","No such file or directory.");
return false;
}$r='';
while($content){
$r.=$th=substr($content,0,$al);
fwrite($f,$th);
$content=substr($content,$al);
if($func($r)){
fclose($f);
return $r;
}}fclose($f);
return $r;
}function sizeformater($size,$join=' ',$offset=1){
if($size<1024*$offset)return floor($size).$join.'B';
if($size<1048576*$offset)return floor($size/1024).$join.'K';
if($size<1073741824*$offset)return floor($size/1048576).$join.'M';
if($site<1099511627776*$offset)return floor($size/1073741824).$join.'G';
return floor($size/109951162776).$join.'T';
}
?>
