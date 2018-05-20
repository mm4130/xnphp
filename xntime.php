<?php

// Created by ...
// xn plugin time v1

function xndateoption($date=1){
if($date==2)return -19603819800;
if($date==3)return -18262450800;
return 0;
}function xntimeoption($time){
return (new DateTime(null,new DateTimeZone($time)))->getOffset();
}function xntime($option=0,$unix=false){
return ($unix===false?microtime(true):$unix)+$option;
}function xndate($date="c",$option=0,$unix){
return date($date,xntime($option,$unix));
}function xndatetimeoption($time,$date=1){
return xntimeoption($time)+xndateoption($date);
}

?>
