<?php
//CURLα��IP����Դ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://iwebqq.com/2.php");//�����ַ
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8')); //����IP
curl_setopt($ch, CURLOPT_REFERER, "http://www.phperzone.cn "); //������·
curl_setopt($ch, CURLOPT_HEADER, 1);
$out = curl_exec($ch);
curl_close($ch);
