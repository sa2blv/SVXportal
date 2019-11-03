<?php

$imgname = "test.png";
$im = imagecreatefrompng($imgname);
imagealphablending($im, false);
for ($x = imagesx($im); $x--;) {
    for ($y = imagesy($im); $y--;) {
        $rgb = imagecolorat($im, $x, $y);
        $c = imagecolorsforindex($im, $rgb);
        if ($c['red'] == 0 && $c['green'] == 0 && $c['blue'] == 0) { // dark colors
            // here we use the new color, but the original alpha channel
            $colorB = imagecolorallocatealpha($im, 205, 203, 70, 0);
            imagesetpixel($im, $x, $y, $colorB);
        }
        
    }
}
imageAlphaBlending($im, true);
imageSaveAlpha($im, true);
header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
?>