<?php
ignore_user_abort(1);
include_once('./config.php');
include ('./snoopy.class.php');
set_time_limit(0);
include 'simple_html_dom.php';
// 新建一个Dom实例
$html = new simple_html_dom();


$snoopy = new Snoopy();
$url = 'http://www.xicidaili.com/nn';
//$url = 'http://10.10.0.18';

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)');

$html = file_get_html($url);

$list = $html->find('#ip_list tr');

foreach ($list as $key => $value) {
	$ip = $value->find('td',2);
	$port = $value->find('td',3);
	$speed = $value->find('td .bar',0);
	echo $speed->title;

}
















 ?>