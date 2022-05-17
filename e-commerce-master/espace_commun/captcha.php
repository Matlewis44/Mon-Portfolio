<?php
session_start();
$_SESSION["captcha"] = mt_rand(1000,9999);
$img = imagecreate(100,50);
$bg = imagecolorallocate($img,255,255,255);
$textcolor = imagecolorallocate($img,0,0,0);
$font = 'font/28DaysLater.ttf';
imagettftext($img,30,0,15,45,$textcolor,$font,$_SESSION["captcha"]);

header('Content-type:image/jpeg');
imagejpeg($img);
imagedestroy($img);
?>  