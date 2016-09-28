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
echo $raw."\n\n"; 
$rUrl = '';

waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
//waitLogin($uuid,$rUrl);
//waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);


$snoopy->accept= 'application/json,test/plain,*/*';
$step1 = $snoopy->fetch($rUrl.'&fun=new');

$xmlObj = simplexml_load_string($snoopy->results);
$step1Result = json_decode(json_encode($xmlObj),TRUE);

$pass_ticket = $step1Result['pass_ticket'];
$ticketData = '';
 $r = list($uin,$sid) = checkUin($snoopy->headers,$snoopy,$ticketData);

 $skey = $step1Result['skey'];
//exit();


//post 
$reportUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxstatreport?type=1&r='.getTime();

$report  = $snoopy->fetchtext($reportUrl);

//echo "print report url:\n".$reportUrl."\n";
//echo "print report :\n".print_r($report)."\n";
$initUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxinit?pass_ticket='.$pass_ticket.'&r='.getTime();


$step2PostData['BaseRequest']['Uin'] = $uin;
$step2PostData['BaseRequest']['Sid'] = $sid;
$step2PostData['BaseRequest']['Skey'] = $skey;
$deviceId = $step2PostData['BaseRequest']['DeviceID'] = 'e'.microtime(1)*10000;
//echo "init url :".$initUrl."\n";
//echo "print step2PostData:\n";
//echo json_encode($step2PostData) ."\n";
//print_r($step2PostData);
$step2 = $snoopy->submit($initUrl,json_encode($step2PostData));
$userInfo = json_decode($step2->results,1);

if($userInfo['BaseResponse']['Ret'] == 1101){
	exit('init false'."\n");
}

$userName = $userInfo['User']['UserName'];
if(count($userInfo['ContactList']) > 0){
	foreach ($userInfo['ContactList'] as $key => $value) {
		$_userInfo[$value['UserName']] = $value['NickName'];
		if($value['MemberCount'] > 0){
			$i = 0;
			$_qunList[$i]['UserName'] = $value['UserName'];
			$_qunList[$i]['EncryChatRoomId'] = "";
			$i++;
		}
	}
}


$synckeyArr = $userInfo['SyncKey']['List'];
$syncKey = '';
foreach ($synckeyArr as $key => $value) {
	//print_r($value);
	$syncKey .= $value['Key'].'_'.$value['Val'].'%7C';
}


$syncCheckUrl = 'https://webpush.weixin.qq.com/cgi-bin/mmwebwx-bin/synccheck?r='.getTime().'&skey='.$skey.'&sid='.$sid.'&uin='.$uin.'&deviceid='.$deviceId.'&synckey='.$syncKey.'_='.getTime();


$syncUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsync?sid='.$sid.'&skey='.$skey.'&lang=cn_ZH&pass_ticket='.$pass_ticket.'&r='.getTime();

//sync post data 

$syncPostData = $step2PostData;
$syncPostData['SyncKey']['Count'] = count($synckeyArr);
$syncPostData['SyncKey']['List'] = $synckeyArr;
$syncPostData['rr'] = -1873101010;

echo "print init result:\n";
//print_r($userInfo);
if($userInfo['Count'] > 0){
	//echo 'init ok'."\n";
}
$i=10;
while ($i <= 10) {
	$i++;
	$newMsg = $snoopy->submit($syncUrl,json_encode($syncPostData));
	$syncResultData = json_decode($newMsg->results,1);
	responsData($syncResultData);
	if($syncResultData['SyncCheckKey']){
		$syncPostData['SyncKey']['Count'] = $syncResultData['SyncCheckKey']['Count'];
		$syncPostData['SyncKey']['List'] = $syncResultData['SyncCheckKey']['List'];
	}
	sleep(2);
}

//get contact

$contactUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxgetcontact?pass_ticket='.$pass_ticket.'&r='.getTime().'&skey='.$skey;
$snoopy->submit($contactUrl);
//echo "contact data : $contactUrl  \n";
//print_r($snoopy->results);
echo  "\n";
echo  "\n";

//群成员信息
$qunContactUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxbatchgetcontact?type=ex&r='.getTime().'&pass_ticket='.$pass_ticket;
$qunPostData['BaseRequest'] = $step2PostData['BaseRequest'];
$qunPostData['Count'] = $i;
$qunPostData['List'] = $_qunList;

$snoopy->submit($qunContactUrl,json_encode($qunPostData));
$qunData = $snoopy->results;

if($qunData['ContactList']){
	foreach ($qunData['ContactList'] as $key => $value) {
		$_userInfo[$value['UserName']] = $value['NickName'];
		$_qunInfo[$value['UserName']] = $value['NickName'];
		foreach ($value['MemberList'] as $k => $val) {
			# code...
			$_userInfo[$val['UserName']] = $val['NickName'];
		}
	}
}

//print_r($userInfo);
// $sendMsgUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?lang=zh_CN&pass_ticket='.$pass_ticket;//sid='.$sid.'&r='.getTime()
// echo "send msg Url :".$sendMsgUrl."\n";
// $msgId = getMsgId();
// $ToUserName = $userInfo['ContactList'][1]['UserName'];
// $sendMsg = '{"BaseRequest":{"Uin":'.$uin.',"Sid":"'.$sid.'","Skey":"'.$skey.'"
// ,"DeviceID":"'.$deviceId.'"},"Msg":{"Type":1,"Content":"msg from robot","FromUserName":"'.$userInfo['User']['UserName'].'"
// ,"ToUserName":"'.$ToUserName.'","LocalID":"'.$msgId.'"
// ,"ClientMsgId":"'.$msgId.'"},"Scene":0}';
// echo "print send msg data: \n";
// //print_r($sendMsg);
// echo "\n";

// $msg['Type'] = 1;
// $msg['Content'] = 'msg from robot,ok  haha ';
// $msg['FromUserName'] = $userInfo['User']['UserName'] ;
// $msg['ToUserName'] = $ToUserName;
// $msg['LocalID'] = $msgId;
// $msg['ClientMsgId'] = $msgId;
// $sendData['BaseRequest'] = $step2PostData['BaseRequest'];
// $sendData['Msg'] = $msg;
// $sendData['Scene'] = 0;
// print_r($sendData);
// $snoopy->submit($sendMsgUrl,json_encode($sendData));

// echo "send msg results: \n ".$snoopy->results."\n";

function sendMsgText($ToUserName,$msgContent){
	global  $uin,$sid,$skey,$deviceId,$userName,$pass_ticket,$step2PostData,$snoopy;
	$sendMsgUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?lang=zh_CN&pass_ticket='.$pass_ticket;//sid='.$sid.'&r='.getTime()
	//echo "send msg Url :".$sendMsgUrl."\n";
	$msgId = getMsgId();
	$msg['Type'] = 1;
	$msg['Content'] = $msgContent;
	$msg['FromUserName'] = $userName;
	$msg['ToUserName'] = $ToUserName;
	$msg['LocalID'] = $msgId;
	$msg['ClientMsgId'] = $msgId;
	$sendData['BaseRequest'] = $step2PostData['BaseRequest'];
	$sendData['Msg'] = $msg;
	$sendData['Scene'] = 0;
	//print_r($sendData);
	$snoopy->submit($sendMsgUrl,json_encode($sendData,JSON_UNESCAPED_UNICODE));

	echo "send msg results: \n ".$snoopy->results."\n";

}


function responsData($data){
	if($data['AddMsgCount'] > 0){
		foreach ($data['AddMsgList'] as $key => $value) {
			if($value['FromUserName'] && $value['Content']){
				echo 'FromUserName:'.$value['FromUserName'].' content :'.$value['Content']."\n";
				sendMsgText($value['FromUserName'],'收到消息：'.$value['Content']);
			}
		}
	}
}











function waitLogin($uuid,&$rUrl){
	if($rUrl){
		goto end;
	}
	$time = microtime()*10000;
	$time = substr($time, 0,-1);
	$time = ~getTime();
	$url = 'https://login.weixin.qq.com/cgi-bin/mmwebwx-bin/login?uuid='.$uuid.'&r='.$time.'&tip=1&_='.$time;
	$_r = file_get_contents($url);
	if($rUrl = check200($_r)){
		goto end;
	}
	sleep(3);
	end:
	return false;
}

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
function checkUin($cookieArr,$snoopy,&$ticket_data){
	//print_r($cookieArr);
	$uin = headerParse($cookieArr,3,'wxuin');
	$sid = headerParse($cookieArr,4,'wxsid');
	$snoopy->cookies['uin'] = $snoopy->cookie['uin']  = $uin;
	$snoopy->cookies['sid'] = $snoopy->cookie['sid']  = $sid;
	$snoopy->cookies['wxloadtime'] = $snoopy->cookie['wxloadtime'] = headerParse($cookieArr,5,'wxloadtime');
	$snoopy->cookies['mm_lang'] = $snoopy->cookie['mm_lang'] =headerParse($cookieArr,6,'mm_lang');
	$snoopy->cookies['webwx_data_ticket'] = $ticket_data = $snoopy->cookie['webwx_data_ticket'] = headerParse($cookieArr,7,'webwx_data_ticket');
	$snoopy->cookies['webwxuvid'] = $snoopy->cookie['webwxuvid'] =headerParse($cookieArr,8,'webwxuvid');
	//$uinStr = $cookieArr[5];
	//$uinArr = explode(';', $uinStr);
	//$uin = str_replace('Set-Cookie: wxuin=', '', $uinArr[0]);
	//$sidStr = $cookieArr[6];
	//$sidArr = explode(';', $sidStr);
	//$sid = str_replace('Set-Cookie: wxsid=', '',$sidArr[0] );
	//echo "print cookies : \n";
	//print_r($snoopy->cookies);

	$_x = array($uin,$sid);
	//print_r($_x);
	return $_x;
}

function headerParse($cookieArr,$key,$keys){
	foreach ($cookieArr as $key => $value) {
		if(strpos($value, $keys)){
			$uinArr = explode(';', $value);
			return str_replace('Set-Cookie: '.$keys.'=', '', $uinArr[0]);
		}
	}
	//$uinStr = $cookieArr[$key];
	//$uinArr = explode(';', $uinStr);
	//return str_replace('Set-Cookie: '.$keys.'=', '', $uinArr[0]);
}


function getTime(){
	$time = microtime(1)*10000;
	return substr($time, 0,-1);
}
function getMsgId(){
	return getTime().rand(1000,9999);
}

function getPost($url,$data,$cookie){

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $output = curl_exec($ch);
　　curl_close($ch);
}



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
