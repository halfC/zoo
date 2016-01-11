<?php 
include 'simple_html_dom.php';
// 新建一个Dom实例
$html = new simple_html_dom();
 
// 从url中加载
//$html->load_file('http://nbsw.cc/19421');
 
// 从字符串中加载
// $html->file_get_html('<html><body>从字符串中加载html文档演示<h2>h2 test </h2><div><div></div></div></body></html>');
$html =file_get_html('http://nbsw.cc/19421');
//查找html文档中的超链接元素
$a = $html->find('h2',0)->innertext;
$b = $html->find('div.postcontent p',0)->innertext;

$c = $html->find('span.tags',0)->innertext;
$c = strip_tags($c);
$c = str_replace(array('笑点:','(',')'), '', $c);
$c = trim(delnumber($c));
print_r($c);
//print_r($b);

//print_r($a);


function delnumber($str)
 {
    return preg_replace('/\d/s', '', $str);
 }


 ?>