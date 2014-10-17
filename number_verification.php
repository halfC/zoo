<?php 
session_start(); 
$sessionvar = 'vdcode'; //Session变量名称 
$width = 150; //图像宽度 
$height = 20; //图像高度 

$operator = '+-*'; //运算符 

$code = array(); 

$code[] = mt_rand(1,9); //第一个数字
$code[] = $operator{mt_rand(0,2)}; //第一个运算符
$code[] = mt_rand(1,9); //第二个数字
$code[] = $operator{mt_rand(0,2)}; //二个运算符
$code[] = mt_rand(1,9); //第三个数字
$codestr = implode('',$code); //合成字符串
eval("\$result = ".implode('',$code).";"); //计算结果
//$result = implode('',$code);
$code[] = '='; 

$_SESSION[$sessionvar] = $result; 

$img = ImageCreate($width,$height); 
ImageColorAllocate($img, mt_rand(230,250), mt_rand(230,250), mt_rand(230,250)); 
$color = ImageColorAllocate($img, 0, 0, 0); 

$offset = 0; 
foreach ($code as $char) { 
$offset += 20; 
$txtcolor = ImageColorAllocate($img, mt_rand(0,255), mt_rand(0,150), mt_rand(0,255)); 
ImageChar($img, mt_rand(3,5), $offset, mt_rand(1,5), $char, $txtcolor); 
} 

for ($i=0; $i<100; $i++) { 
$pxcolor = ImageColorAllocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); 
ImageSetPixel($img, mt_rand(0,$width), mt_rand(0,$height), $pxcolor); 
} 

header('Content-type: image/png'); 
ImagePng($img); 
?> 
