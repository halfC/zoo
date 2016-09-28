<?php
include 'Snoopy.class.php';
//echo microtime(1);
$snoopy = new Snoopy();
$data = array('id'=>123,'name'=>'tom');

//$snoopy->submit('http://u.test.com/wxLogin/out.php',json_encode($data));
//echo $r = $snoopy->results;
//print_r($r);
//exit();


$url = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxnewloginpage?ticket=AcrBMiS_M9kXy7pb1RDhv-pq@qrticket_0&uuid=IfZETLGQpw==&lang=zh_CN&scan=1471920830';

$arr = parse_url($url);
//print_r($arr);





$_arr[] = 'HTTP/1.0 200 OK';
$_arr[] = 'Connection: close';
$_arr[] = 'Content-Type: text/plain';
$_arr[] = 'Set-Cookie: wxuin=820966031; Domain=wx.qq.com:443; Path=/; Expires=Thu, 25-Aug-2016 18:30:57 GMT';
$_arr[] = 'Set-Cookie: wxsid=9xxcZXnrl9Fj/v14; Domain=wx.qq.com:443; Path=/; Expires=Thu, 25-Aug-2016 18:30:57 GMT';
$_arr[] = 'Set-Cookie: wxloadtime=1472106657; Domain=wx.qq.com:443; Path=/; Expires=Thu, 25-Aug-2016 18:30:57 GMT';
$_arr[] = 'Set-Cookie: mm_lang=zh_CN; Domain=wx.qq.com:443; Path=/; Expires=Thu, 25-Aug-2016 18:30:57 GMT';
$_arr[] = 'Set-Cookie: webwx_data_ticket=gSediIlgMlXIXRGqyi7IXdNX; Domain=.qq.com; Path=/; Expires=Thu, 25-Aug-2016 18:30:57 GMT';
$_arr[] = 'Set-Cookie: webwxuvid=05a163657b552852f8fd127a79cebb5f7eb4065acab8c5fe8e329d2902ad912f7bb3d86078b0226fed5ae9883cdd4660; Domain=wx.qq.com:443; Path=/; Expires=Sun, 23-Aug-2026 06:30:57 GMT';
$_arr[] = 'Content-Length: 283';

function headerParse($cookieArr,$key,$keys){
	foreach ($cookieArr as $key => $value) {
		if(strpos($value,$keys )){
			$uinArr = explode(';', $value);
			return str_replace('Set-Cookie: '.$keys.'=', '', $uinArr[0]);
		}
	}
	//$uinStr = $cookieArr[$key];
	//$uinArr = explode(';', $uinStr);
	//return str_replace('Set-Cookie: '.$keys.'=', '', $uinArr[0]);
}


echo headerParse($_arr,1,'wxuin');

exit();

$arr = convertUrlQuery($arr['query']);

print_r($arr);



function convertUrlQuery($query)
{ 
    $queryParts = explode('&', $query); 
    
    $params = array(); 
    foreach ($queryParts as $param) 
	{ 
        $item = explode('=', $param); 
        $params[$item[0]] = $item[1]; 
    } 
    
    return $params; 
}

