<?php
session_start();

//include 'captcha.php';

$im = @ImageCreate (80, 20)
or die ("Cannot Initialize new GD image stream");
$background_color = ImageColorAllocate ($im, 204, 204, 204); // Assign background color
$text_color = ImageColorAllocate ($im, 51, 51, 255);      // text color is given
ImageString($im,5,5,2,$_SESSION['vercode'],$text_color); // Random string  from session added 

ImagePng ($im); // image displayed
imagedestroy($im); // Memory allocation for the image is removed. 
?>