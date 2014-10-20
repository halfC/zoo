<?php
/**
 * 扫描同一个二维码根据浏览器类型访问不同下载地址
 * 
 */

$Agent = $_SERVER['HTTP_USER_AGENT'];
preg_match('/android|iphone/i',$Agent,$matches);
if (strtolower($matches[0]) == 'android') { // echo "安卓";
	header("Location: url.com");
} elseif (strtolower($matches[0]) == 'iphone') {
	header("'Location:u.com ");
}else{ //不确定是什么系统或者是pc
	header("Location: phperzone.cn");
}

