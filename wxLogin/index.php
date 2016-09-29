<?php
/**
 * 流程说明
 * 		一、登录
 * 			1.二维码输出
 * 			2.等待确认
 * 			3.返回跳转链接
 * 			4.请求回调地址
 * 			5.获取必要参数
 * 			6.初始化
 * 			
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * 
 */
include './Snoopy.class.php';
include('./phpqrcode/qrlib.php'); 
$snoopy = new Snoopy();
# step 1.1  二维码输出 
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
# step 1.1 end
# step 1.2  等待二维码扫描结果
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
waitLogin($uuid,$rUrl);
//检测 是否成功返回 跳转地址
if($rUrl == ''){
	exit("not scan qrcode or not confirm \n");
}
#1.4请求回调地址，返回必要参数值（uin,sid,skey,pass_ticket）
$snoopy->accept= 'application/json,test/plain,*/*';
$step1 = $snoopy->fetch($rUrl.'&fun=new');
$xmlObj = simplexml_load_string($snoopy->results);
#1.5 提取值
$step1Result = json_decode(json_encode($xmlObj),TRUE);
$pass_ticket = $step1Result['pass_ticket'];
$ticketData = '';
$r = list($uin,$sid) = checkUin($snoopy->headers,$snoopy,$ticketData);
$skey = $step1Result['skey'];



# 初始化 
$initUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxinit?pass_ticket='.$pass_ticket.'&r='.getTime();

//公共参数
$step2PostData['BaseRequest']['Uin'] = $uin;
$step2PostData['BaseRequest']['Sid'] = $sid;
$step2PostData['BaseRequest']['Skey'] = $skey;
$deviceId = $step2PostData['BaseRequest']['DeviceID'] = 'e'.microtime(1)*10000;

$step2 = $snoopy->submit($initUrl,json_encode($step2PostData));
$userInfo = json_decode($step2->results,1);

#判断初始化是否成功
if($userInfo['BaseResponse']['Ret'] == 1101){
	exit('init false'."\n");
}

$userName = $userInfo['User']['UserName'];
//echo "print ContactList info : \n";
//print_r($userInfo['ContactList']);
if(count($userInfo['ContactList']) > 0){
	$i = 0;
	foreach ($userInfo['ContactList'] as $key => $value) {
		$_userInfo[$value['UserName']] = $value['NickName'];
		#获取最近联系人里的 群UserName 列表
		if($value['MemberCount'] > 0){			
			$_qunList[$i]['UserName'] = $value['UserName'];
			$_qunList[$i]['EncryChatRoomId'] = "";
			$i++;
		}
	}
}


$synckeyArr = $userInfo['SyncKey']['List'];
$syncKey = '';

//sync post data 

$syncPostData = $step2PostData;
$syncPostData['SyncKey']['Count'] = count($synckeyArr);
$syncPostData['SyncKey']['List'] = $synckeyArr;
$syncPostData['rr'] = ~ time();

if($userInfo['Count']  == 0){
	exit();
}
# 获取联系人  flase
//get contact
$contactUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxgetcontact?pass_ticket='.$pass_ticket.'&r='.getTime().'&skey='.$skey;
$snoopy->fetch($contactUrl);
print_r($snoopy->results);
// echo  "\n";
// echo  "\n";



#获取群具体信息
$qunContactUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxbatchgetcontact?type=ex&r='.getTime().'&pass_ticket='.$pass_ticket;
$qunPostData['BaseRequest'] = $step2PostData['BaseRequest'];
$qunPostData['Count'] = $i;
$qunPostData['List'] = $_qunList;
$snoopy->submit($qunContactUrl,json_encode($qunPostData));
$qunData = json_decode($snoopy->results,1);
# 保存群信息
if($qunData['ContactList'] && (count($qunData['ContactList']) > 0)){
	foreach ($qunData['ContactList'] as $key => $value) {
		$_userInfo[$value['UserName']] = $value['NickName'];
		$_qunInfo[$value['UserName']] = $value['NickName'];
		foreach ($value['MemberList'] as $k => $val) {
			# code...
			$_userInfo[$val['UserName']] = $val['DisplayName']?$val['DisplayName']:$val['NickName'];
		}
	}
}

$syncUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsync?sid='.$sid.'&skey='.$skey.'&lang=cn_ZH&pass_ticket='.$pass_ticket.'&r='.getTime();

// 循环 执行 
while (1) {
	$i++;
	// 获取 消息
	$newMsg = $snoopy->submit($syncUrl,json_encode($syncPostData));
	$syncResultData = json_decode($newMsg->results,1);
	//消息处理
	responsData($syncResultData);
	//更新 syncPostData 
	if($syncResultData['SyncCheckKey']){
		$syncPostData['SyncKey']['Count'] = $syncResultData['SyncCheckKey']['Count'];
		$syncPostData['SyncKey']['List'] = $syncResultData['SyncCheckKey']['List'];
	}
	// 每3秒 请求一次
	sleep(3);
}

/**
 *  发送文本信息
 * @param  [string] $ToUserName [接收者]
 * @param  [string] $msgContent [接收到的内容]
 * @return [type]             [description]
 */
function sendMsgText($ToUserName,$msgContent){
	global  $uin,$sid,$skey,$deviceId,$userName,$pass_ticket,$step2PostData,$snoopy,$_userInfo;
	$sendMsgUrl = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxsendmsg?lang=zh_CN&pass_ticket='.$pass_ticket;//sid='.$sid.'&r='.getTime()
	//echo "send msg Url :".$sendMsgUrl."\n";
	$msgContent = strip_tags($msgContent);

	if(strpos($msgContent, '@') !== false && strpos($msgContent, ':')){
		$arr = explode(':', $msgContent);
		$toMsg = htmlspecialchars_decode($arr[1]);
		$reMsg = tulingReply($toMsg,$ToUserName);
		record($_userInfo[$ToUserName].'|'.$_userInfo[$arr[0]].'|'.$toMsg.'|'.$reMsg);
		if($reMsg ==''){
			return false;
		}
		//$msgContent = $_userInfo[$ToUserName].':'.$_userInfo[$arr[0]].':'.$reMsg;
		$msgContent = $reMsg;
	}
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
/**
 * 回复 图片 信息  还未成功 
 * @param  [type] $ToUserName [description]
 * @param  [type] $msgId      [description]
 * @return [type]             [description]
 */
function sendMsgImg($ToUserName,$msgId){
	global  $uin,$sid,$skey,$deviceId,$userName,$pass_ticket,$step2PostData,$snoopy,$_userInfo;

	$url = 'https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxgetmsgimg?MsgID='.$msgId.'&skey='.$skey.'&type=';
	echo " print msgImg url : ".$url."\n";
	$snoopy->fetch($url);
	echo "print img info :\n";
	print_r($snoopy->results);
	echo "\n";
}

/**
 * 检测是否有信息，并回复
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function responsData($data){
	if($data['AddMsgCount'] > 0){
		foreach ($data['AddMsgList'] as $key => $value) {
			//print_r($value);
			if($value['FromUserName'] && $value['Content']){
				echo 'FromUserName:'.$value['FromUserName'].' content :'.$value['Content']."\n";
				if($value['ImgHeight'] > 0){
					sendMsgImg($value['FromUserName'] , $value['MsgId']);
				}else{
					sendMsgText($value['FromUserName'] , $value['Content']);
				}
			}
		}
	}
}



/**
 * 等待二维码扫描结果
 * @param  [int] $uuid  [description]
 * @param  [string] &$rUrl [description]
 * @return []    无返回
 */
function waitLogin($uuid,&$rUrl){
	$time = ~getTime();
	$url = 'https://login.weixin.qq.com/cgi-bin/mmwebwx-bin/login?uuid='.$uuid.'&r='.$time.'&tip=1&_='.$time;
	$_r = file_get_contents($url);
	if($rUrl = check200($_r)){
		//log 
	}
	sleep(3);
	return TRUE;
	
}

/**
 * check 200 test
 * 
	$cc = 'window.code=200;
	window.redirect_uri="https://wx.qq.com/cgi-bin/mmwebwx-bin/webwxnewloginpage?ticket=AWxC8NVl8O29uK0Y-OcbIdTZ@qrticket_0&uuid=geuR32LOjQ==&lang=zh_CN&scan=1471845480";';
	echo (check200($cc));
 */


/**
 * 检查返回结果是否是200
 * @param  [string] $content [请求返回值]
 * @return [string|bool]   [成功返回跳转url，否则返回false]
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
/**
 * [checkUin description]
 * @param  [type] $cookieArr    [description]
 * @param  [type] $snoopy       [description]
 * @param  [type] &$ticket_data [description]
 * @return [type]               [description]
 */
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
	$_x = array($uin,$sid);
	return $_x;
}
/**
 * 格式化 cookie 信息
 * @param  [type] $cookieArr [description]
 * @param  [type] $key       [description]
 * @param  [type] $keys      [description]
 * @return [type]            [description]
 */
function headerParse($cookieArr,$key,$keys){
	foreach ($cookieArr as $key => $value) {
		if(strpos($value, $keys)){
			$uinArr = explode(';', $value);
			return str_replace('Set-Cookie: '.$keys.'=', '', $uinArr[0]);
		}
	}
}

/**
 * 生成时间
 * @return [type] [description]
 */
function getTime(){
	$time = microtime(1)*10000;
	return substr($time, 0,-1);
}
/**
 * 生成 消息 id
 * @return [type] [description]
 */
function getMsgId(){
	return getTime().rand(1000,9999);
}
/**
 * [tulingReply 调用图灵接口回复信息 #开头]
 * @param  [type] $msg        [description]
 * @param  [type] $ToUserName [description]
 * @return [type]             [description]
 */
function tulingReply($msg,$ToUserName){
	global $snoopy;
	$start = substr($msg,0,1);
	if($start != '#'){
		return false;
	}
	$url = 'https://www.tuling123.com/openapi/api';
	$data['key'] = '100b0df5eac743d8b3abc19bc11220f8';
	$data['info'] = $msg;
	$userid= md5(trim($ToUserName,'@'));
	$r = getPost($msg,$userid);
	$r = json_decode($r,1);
	if( $r && $r['code'] == 100000 ){
		return $r['text'];
	}else{
		echo "tuling is respons error : $msg \n";
		return false;
	}
}

/**
 *  POST 
 * @param  [type] $data   [description]
 * @param  [type] $userid [description]
 * @return [type]         [description]
 */
function getPost($data,$userid){
	$url = 'http://www.tuling123.com/openapi/api';
	$post_string = 'key=100b0df5eac743d8b3abc19bc11220f8&info='.$data.'&userid='.$userid;
	$sh = curl_init();
	curl_setopt($sh,CURLOPT_URL,$url);
	curl_setopt($sh,CURLOPT_POSTFIELDS,$post_string);
	curl_setopt($sh,CURLOPT_RETURNTRANSFER,true);
	$data = curl_exec($sh);
	curl_close($sh);
	return $data;
}

/**
 * 记录收到和回复的信息
 * @param  [type] $msg [description]
 * @return [type]      [description]
 */
function record($msg){
	$fileName = './Log/r_'.date('Y-m-d').'.log';
	file_put_contents($fileName, $msg."\n",FILE_APPEND);
}