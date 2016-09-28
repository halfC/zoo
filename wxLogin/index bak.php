<?php

include './Snoopy.class.php';
include('./phpqrcode/qrlib.php'); 
$snoopy = new Snoopy();

$uurl = "https://login.weixin.qq.com/jslogin?appid=wx782c26e4c19acffb&redirect_uri=https%3A%2F%2Fwx.qq.com%2Fcgi-bin%2Fmmwebwx-bin%2Fwebwxnewloginpage&fun=new&lang=zh_CN&_=1388994062250";

$ucontent = file_get_contents($uurl);
$uuid =  substr($ucontent, -14,12);


$qrcodeUrl = "https://login.weixin.qq.com/qrcode/".$uuid."?t=webwx";

$codeContents = 'https://login.weixin.qq.com/l/'.$uuid;
//$codeContents = '123456';
$text = QRcode::text($codeContents); 
$raw = join("\n", $text); 

$raw = strtr($raw, array( 
    '0' => "\033[47m  \033[0m" ,
    '1' => "\033[40m  \033[0m"
));
echo $raw . "\n\n"; 
$rUrl = '';

waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);

//while (waitLogin($uuid,$rUrl) === false) {
//	sleep(5);
//}

function waitLogin($uuid,&$rUrl){
	if($rUrl){
		goto end;
	}
	$time = microtime()*10000;
	$time = substr($time, 0,-1);
	$url = 'https://login.weixin.qq.com/cgi-bin/mmwebwx-bin/login?uuid='.$uuid.'&tip=1&_='.$time;
	$_r = file_get_contents($url);
	if($rUrl = check200($_r)){
		goto ok;
	}
	sleep(5);
	end:
	return false;
	ok:
	return true;
}

end:;//scan ok
echo 'check ok'."\n";
echo "step1 : \n";
echo "request url:".$rUrl."\n";
$step1 = $snoopy->fetchtext($rUrl.'&fun=new');
$time = microtime()*10000;
	$time = substr($time, 0,-1);
echo "print_r cookies uin:\n".print_r($snoopy->cookies['header']['wxuin'])."\n";
echo "\n\n\n";
echo "print_r cookies:\n".print_r($snoopy->header['wxsid'])."\n";
echo "\n\n\n";
echo "print step1:\n".print_r($step21)."\n";
exit;
//post 
$step2PostUlr = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxstatreport?type=1&r='.$time;

$step2PostData['baseRequest']['Unid'] = $uuid;
$step2 = $snoopy->submit($step2PostUlr);







/*
$cc = 'window.code=200;
window.redirect_uri="https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxnewloginpage?ticket=AWxC8NVl8O29uK0Y-OcbIdTZ@qrticket_0&uuid=geuR32LOjQ==&lang=zh_CN&scan=1471845480";';

echo (check200($cc));
*/
function check200($content){
	list($codeStr,$urlStr) = explode(';', $content);
	list($__z,$code) = explode('=', $codeStr);
	if($code == 200){
		$url = str_replace('window.redirect_uri="', '', $urlStr);
		return trim(trim($url,'"'));
	}else{
		return false;
	}
}







