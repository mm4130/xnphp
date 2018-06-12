<?php

namespace xn;

/* nter methods
 * you can't use this methods
 */
function set_last_update_nter() {
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f, "{[LASTUPDATE]}");
  while($p > 0 && $f[$p--] != '"');
  if($p <= 0) return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  if(! is_numeric($h)) return false;
  $f = str_replace("$h{[LASTUPDATE]}", time() . "{[LASTUPDATE]}", $f);
  return file_put_contents($file, $f);
}
function set_last_use_nter() {
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f, "{[LASTUSE]}");
  while($p > 0 && $f[$p--] != '"');
  if($p <= 0) return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  if(! is_numeric($h)) return false;
  $f = str_replace("$h{[LASTUSE]}", time() . "{[LASTUSE]}", $f);
  return file_put_contents($file, $f);
}
function set_data_nter() {
  $data = base64_encode(json_encode($GLOBALS['DATA']));
  $file = $GLOBALS['-XN-']['dirNameDir'] . 'xn.php';
  $f = file_get_contents($file);
  $p = strpos($f, "{[DA" . "TA]}");
  while($p > 0 && $f[$p--] != '"');
  if($p<=0) return false;
  $h = '';
  $p += 2;
  while($f[$p] != '{')$h .= $f[$p++];
  $f=str_replace("$h{[DA" . "TA]}", "$data{[D" . "ATA]}", $f);
  return file_put_contents($file, $f);
}
/* xnscript
 * return : Array [
     String version,
     Double start_time,
     Double end_time,
     Double loaded_time,
     String dir_name, // dir address for xn script
     Double last_update,
     Double last_use
   ]
 * use : require for get info
 
   xn\xnscript();
 */
function xnscript() {
return [
  "version" => "1.5",
  "start_time" => $GLOBALS['-XN-']['startTime'],
  "end_time" => $GLOBALS['-XN-']['endTime'],
  "loaded_time" => $GLOBALS['-XN-']['endTime'] - $GLOBALS['-XN-']['startTime'],
  "dir_name" => $GLOBALS['-XN-']['dirName'],
  "last_update" => substr($GLOBALS['-XN-']['lastUpdate'], 0, -14),
  "last_use" => substr($GLOBALS['-XN-']['lastUse'], 0, -11)
];
}

?>
