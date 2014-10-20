<?php 
/**
 * 
 * 从服务器上下载&保存一个远程图片 
 * 
 */


$image = file_get_contents('http://www.phperzone.cn.com/image.jpg');

file_put_contents('/images/image.jpg', $image);

?>