<?php
$a = "./images/8760/10/26.jpg";
$fileInfo = getimagesize($a);
$src_img = imagecreatefrompng($a);//创建原图片的画布
var_dump($fileInfo['mime']);
var_dump($src_img);