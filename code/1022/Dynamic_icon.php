<?php

//读的favicon favicon.png的模板
//从当前目录的文件
$im = imagecreatefrompng("favicon.png");
//$im = imagecreatefromjpg("favicon.jpg"); //使用此函数来加载JPEG类型的favicon
//$im = imagecreatefrombmp("favicon.bmp"); //使用此函数来加载BMP类型的favicon


/* 读取的字符，需要在favicon添加
* get请求
*/
if(isset($_GET['char']) && !empty($_GET['char'])) {
$string = $_GET['char'];
} else {
/* 如果没有指定字符添加一些默认值 */
$string = 'V';
}

/* 的favicon的背景颜色 */
$bg = imagecolorallocate($im, 255, 255, 255);

/* foreground (font) color for the favicon */
$black = imagecolorallocate($im, 0, 0, 0);

/* 写favicon字符
* arguements：图像，字号，x坐标，
* Y坐标，characterstring，彩色
*/
imagechar($im, 2, 5, 1, $string, $black);

header('Content-type: image/png');

imagepng($im);

?>