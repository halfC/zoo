<?php 
session_start(); 
$sessionvar = 'vdcode'; //Session�������� 
$width = 150; //ͼ���� 
$height = 20; //ͼ��߶� 

$operator = '+-*'; //����� 

$code = array(); 

$code[] = mt_rand(1,9); //��һ������
$code[] = $operator{mt_rand(0,2)}; //��һ�������
$code[] = mt_rand(1,9); //�ڶ�������
$code[] = $operator{mt_rand(0,2)}; //���������
$code[] = mt_rand(1,9); //����������
$codestr = implode('',$code); //�ϳ��ַ���
eval("\$result = ".implode('',$code).";"); //������
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
