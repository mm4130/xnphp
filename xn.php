<?php

// Created by whiteweb // xn.white-web.ir // @white_web
// xn script v1

$GLOBALS['-XN-'] = [];
$GLOBALS['-XN-']['startTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['startTime'] = $GLOBALS['-XN-']['startTime'][0] + $GLOBALS['-XN-']['startTime'][1];

// include librarys
ob_start();
include "xntypes.php";

include "xnfiles.php";
include "xntelegram.php";
ob_end_clean();

$GLOBALS['-XN-']['endTime'] = explode(' ',microtime());
$GLOBALS['-XN-']['endTime'] = $GLOBALS['-XN-']['endTime'][0] + $GLOBALS['-XN-']['endTime'][1];

function xnscript() {
return ["version"=> "1", "libs"=> ["types", "index", "telegram", "files"],
"start_time"=> $GLOBALS['-XN-']['startTime'], "end_time"=> $GLOBALS['-XN-']['endTime'],
"loaded_time"=> $GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime']];
}


?>
