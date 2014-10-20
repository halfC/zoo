<?php
/**
* 查看邮件是否已被阅读
* 当你在发送邮件时，你或许很想知道该邮件是否被对方已阅读。这里有段非常有趣的代码片段能够显示对方IP地址记录阅读的实际日期和时间。
*/
error_reporting(0);
Header("Content-Type: image/jpeg");

//Get IP
if (!empty($_SERVER['HTTP_CLIENT_IP']))
{
  $ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
  $ip=$_SERVER['REMOTE_ADDR'];
}
 
//Time
$actual_time = time();
$actual_day = date('Y.m.d', $actual_time);
$actual_day_chart = date('d/m/y', $actual_time);
$actual_hour = date('H:i:s', $actual_time);
 
//GET Browser
$browser = $_SERVER['HTTP_USER_AGENT'];
     
//LOG
$myFile = "log.txt";
$fh = fopen($myFile, 'a+');
$stringData = $actual_day . ' ' . $actual_hour . ' ' . $ip . ' ' . $browser . ' ' . "\r\n";
fwrite($fh, $stringData);
fclose($fh);
 
//Generate Image (Es. dimesion is 1x1)
$newimage = ImageCreate(1,1);
$grigio = ImageColorAllocate($newimage,255,255,255);
ImageJPEG($newimage);
ImageDestroy($newimage);
