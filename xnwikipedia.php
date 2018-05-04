<?php

// Created by ...
// xn plugin wikipedia v1


class wikipedia {
static function status(){
$g=file_get_contents("https://www.wikipedia.org/");
$x=new DOMDocument;
@$x->loadHTML($g);
$x=@new DOMXPath($x);
$x=$x->query("//div[@class='central-featured']")[0]->getElementsByTagName('a');
$c=0;
$r=[];
while(isset($x[$c])){
$el=$x[$c++];
$r[]=[
"code"=>substr($el->getAttribute("href"),2,2),
"name"=>$el->getElementsByTagName("strong")[0]->nodeValue,
"articels"=>str_replace(['+',' ',' '],'',$el->getElementsByTagName("bdi")[0]->nodeValue),
"slogan"=>$el->getAttribute("data-slogan")
];
}
return $r;
}static function search($q,$offset=0,$limit=20,$lang='en'){
$g=file_get_contents("https://$lang.wikipedia.org/w/index.php?limit=$limit&offset=$offset&profile=default&fulltext=1&search=".urlencode($q));
if(!$g){
return false;
}$x=new DOMDocument;
@$x->loadHTML($g);
$p=@new DOMXPath($x);
$total=$p->query("//div[@class='results-info']");
if(isset($total[0])){
$total=$total[0]->getElementsByTagName("strong");
if(isset($total[1])){
$total=$total[1]->nodeValue;
$total=str_replace(',','',strtr($total,'۱۲۳۴۵۶۷۸۹۰','1234567890'));
}else $total=false;
}else $total=false;
$search=$p->query("//ul[@class='mw-search-results']");
if(isset($search[0])){
$search=$search[0]->getElementsByTagName('li');
$c=0;
$schs=[];
while(isset($search[$c])){
$srch=$search[$c++]->getElementsByTagName("div");
preg_match('/([۱۲۳۴۵۶۷۸۹۰1234567890, ]{1,}) [^ ]{1,} \(([۱۲۳۴۵۶۷۸۹۰1234567890, ]{1,}) [^\)]{1,}\) \- (.{1,})/',$srch[2]->nodeValue,$info);
$size=(int)str_replace([',',' '],'',strtr($info[1],'۱۲۳۴۵۶۷۸۹۰','1234567890'))*1000;
$length=(int)str_replace([',',' '],'',strtr($info[2],'۱۲۳۴۵۶۷۸۹۰','1234567890'));
$date=strtr($info[3],'۱۲۳۴۵۶۷۸۹۰','1234567890');
$description=$srch[1]->nodeValue;
$srch=$srch[0]->getElementsByTagName('a')[0];
$title=$srch->nodeValue;
$address=$lang.$srch->getAttribute("href");
$link="https://$lang.wikipedia.org".$srch->getAttribute("href");
$schs[]=(new wikipedia)->createWikiParResultObject($title,$address,$link,$size,$length,$date,$description);
}}else return false;
if(isset($p->query("//p[@class='mw-search-exists']")[0]))$exists=true;
else $exists=false;
return (new wikipedia)->createWikiSearchObject($exists,$total,$schs);
}public function createWikiSearchObject($exists,$total,$schs){
$this->exists=$exists;
if($total)$this->total=$total;
elseif(isset($this->total))$this->total=null;
$this->schs=$schs;
return $this;
}public function createWikiParResultObject($title,$address,$link,$size,$words,$date,$description){
$this->title=$title;
$this->address=$address;
$this->link=$link;
$this->size=$size;
$this->words=$words;
$this->date=$date;
$this->description=$description;
return $this;
}static function info($address,$remref=false){
$lang=substr($address,0,2);
$address=substr($address,3);
$g=file_get_contents($link="https://$lang.wikipedia.org/$address");
if(!$g){
return false;
}$x=new DOMDocument;
@$x->loadHTML($g);
$p=@new DOMXPath($x);
$title=$x->getElementById("firstHeading");
if(isset($title->nodeValue)){
$title=$title->nodeValue;
}else return false;
$lastmod=$x->getElementById("footer-info-lastmod");
if(isset($lastmod->nodeValue)){
$lastmod=trim($lastmod->nodeValue);
}else $lastmod=false;
$content=$p->query("//div[@class='mw-parser-output']");
if(isset($content[0])){
$content=$content[0];
$links=[];
$lnks=$content->getElementsByTagName("a");
$c=0;
while(isset($lnks[$c])){
$lnk=$lnks[$c++];
if($lnk->nodeValue&&$lnk->nodeValue[0]!=='['){
$links[$lnk->nodeValue]=$lnk->getAttribute("href");
$links[$lnk->nodeValue]=(new wikipedia)->createWikiParLinksObject(
"$lang".$links[$lnk->nodeValue],
$links[$lnk->nodeValue]
);
}}$content=str_replace([
"[۱]","[۲]","[۳]","[۴]","[۵]","[۶]","[۷]","[۸]","[۹]","[۰]"
],[
"[1]","[2]","[3]","[4]","[5]","[6]","[7]","[8]","[9]","[0]"
],$content->nodeValue);
}else $content=null;
$cate=$x->getElementById("mw-normal-catlinks")->getElementsByTagName('a');
if(isset($cate[1])){
$c=1;
$cates=[];
while(isset($cate[$c])){
$cates[]=$cate[$c++]->nodeValue;
}$cate=$cates;
}else $cate=false;
$ref=$p->query("//ol[@class='references']");
if(isset($ref[0])){
$refs=$ref[0]->getElementsByTagName("li");
$rfs=[];
$c=0;
while(isset($refs[$c])){
$ref=$refs[$c++];
$refnum=$c;
$refpos=strpos($content,"[$c]");
if($remref)$content=substr_replace($content,'',$refpos,2+strlen($c));
$refcontent=$ref->getElementsByTagName("span");
$refcontent=$refcontent[1];
$reflink=$refcontent->getElementsByTagName('a');
if(isset($reflink[0])){
$reflink=$reflink[0]->getAttribute("href");
}else $reflink=false;
$refcontent=trim($refcontent->nodeValue);
$rfs[]=(new wikipedia)->createWikiParRefObject($refnum,$refcontent,$reflink,$refpos);
}
}else $rfs=false;
$content=str_replace(['[edit]','[ویرایش]'],'',trim($content));
$imgs=$p->query('//div[@class="thumbinner"]');
$images=[];
$c=0;
while($imgs[$c]){
$imgcaption=$imgs[$c]->getElementsByTagName("div")[0]->nodeValue;
$content=str_replace($imgcaption,'',$content);
$img=$imgs[$c++]->getElementsByTagName('a')[0];
$imglink=$img->getAttribute("href");
$img=$img->getElementsByTagName("img")[0];
$imgsrc=$img->getAttribute("src");
$imgwidth=(int)$img->getAttribute("width");
$imgheight=(int)$img->getAttribute("height");
$imgtitle=$img->getAttribute("alt");
$imgims=$img->getAttribute("srcset");
$imgims=explode(", ",$imgims);
$imims=[];
$o=0;
while(isset($imgims[$o])){
$imgim=explode(' ',$imgims[$o++]);
$imims[$imgim[1]]=$imgim[0];
}$images[]=(new wikipedia)->createWikiParImagesObject($imgtitle,$imglink,$imgwidth,$imgheight,$imgsrc,$imgcaption,$imims);
}return (new wikipedia)->createWikiInfoObject($title,$lastmod,$content,$images,$cate,$link,$rfs,$links);
}public function createWikiInfoObject($title,$lastmod,$content,$images,$cate,$link,$refs,$links){
$this->title=$title;
$this->link=$link;
if($content!==null)$this->content=$content;
elseif(isset($this->content))$this->content=null;
$this->images=$images;
if($lastmod)$this->lastmod=$lastmod;
elseif(isset($this->lastmod))$this->lastmod=null;
if($cate)$this->category=$cate;
elseif(isset($this->category))$this->category=null;
$this->links=$links;
if($refs)$this->references=$refs;
elseif(isset($this->references))$this->references=null;
return $this;
}public function createWikiParLinksObject($address,$link){
if(substr($link,0,4)!='http')$this->address=$address;
$this->link=$link;
return $this;
}public function createWikiParRefObject($num,$name,$link,$pos){
$this->number=$num;
$this->reference=$name;
if($link)$this->link=$link;
elseif(isset($this->link))$this->link=null;
$this->position=$pos;
return $this;
}public function createWikiParImagesObject($title,$link,$width,$height,$src,$caption,$images){
$this->title=$title;
$this->link=$link;
$this->width=$width;
$this->height=$height;
$this->src=$src;
$this->caption=$caption;
$this->images=$images;
return $this;
}static function linkToAddress($link){
$link=explode('//',$link);
unset($link[0]);
$link=implode('//',$link);
$lang=substr($link,0,2);
$link=explode('/',$link);
unset($link[0]);
$link=implode('/',$link);
return "$lang/$link";
}static function addressToLink($address){
$address=explode('/',$address);
$lang=$address[0];
unset($address[0]);
$address=implode('/',$address);
return "https://$lang.wikipedia.org/$address";
}
}


?>
