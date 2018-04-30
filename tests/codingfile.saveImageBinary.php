<?php
require "xn.php";

$im = imagecreatetruecolor(1024,814);
$x = 0;
while($x < 1024){
$y = 0;
while($y < 814){
imagesetpixel($im,$x,$y,$x%($y+1)+$y%($x+1));
$y++;
}$x++;
}

imagepng($im,"image.binary");
$image = fget("image.binary");
$image = base2_encode($image);
fput("image.binary",$image);

echo "<pre>OK!</pre>";

?>
