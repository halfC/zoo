<?php
/**
 * Create shortcut
 * 网站生存左面快捷方式
 * 
 *  调用DEMO : <a href="a.php?url=www.phperzone.cn&name=phper地带">生成左面快捷方式</a>
 * 
 */


$url = $_GET['url'];

$filename = urldecode($_GET['name']);

$filename = iconv('GBk','utf-8',$filename);//字符集转换（没有需要转的就不转）

if (!$url || !$filename) exit();

$Shortcut = "[InternetShortcut]

URL={$url}

IDList=

[{000214A0-0000-0000-C000-000000000046}]

Prop3=19,2";

header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename={$filename}.url;");

echo $Shortcut;

?>

