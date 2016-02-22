<?php

//test
echo file_get_contents('http://weixin.sogou.com/weixin?type=2&ie=utf8&query=%E5%BC%A0%E7%BF%B0%E5%A8%9C%E6%89%8E');die;

function getUrlContent($url, $set_ip) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_INTERFACE, $set_ip);
    $data = curl_exec($ch);
#var_dump($url,$data);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_exec($ch) === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        echo '';
    }
    curl_close($ch);

    return ($httpcode >= 200 && $httpcode < 300) ? $data : false;
}


//http://63.141.248.226/fetch.php?url=http%3A%2F%2Fnews.51xiangshui.com%2Fe%2Fextend%2Fhotwords%2Ftest.php&set_ip=63.141.248.226
//echo urlencode("http://news.51xiangshui.com/e/extend/hotwords/test.php");die;
//var_dump(get_server_ip());

$url=$_GET['url'];
$url=  urldecode($url);
$set_ip=$_GET['set_ip'];


#var_dump($url);
#$url='http://weixin.sogou.com/weixin?type=2&ie=utf8&query=�ź�����';
//var_dump($url);die;

$rs=getUrlContent($url, $set_ip);
if($rs===FALSE){
echo 'false';die;
}
echo $rs;
