<?php
//CURL伪造IP和来源
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://iwebqq.com/2.php");//请求地址
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8')); //构造IP
curl_setopt($ch, CURLOPT_REFERER, "http://www.phperzone.cn "); //构造来路
curl_setopt($ch, CURLOPT_HEADER, 1);
$out = curl_exec($ch);
curl_close($ch);
