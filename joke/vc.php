<?php 
header("Content-type: text/html; charset=utf-8"); 
/***
  *  采集微信号
  *
  * */
ignore_user_abort(1);
include_once('./config.php');
include ('./snoopy.class.php');
set_time_limit(0);
include 'simple_html_dom.php';
// 新建一个Dom实例
$html = new simple_html_dom();


$snoopy = new Snoopy();

$type = 10;

$user_url = 'http://www.vccoo.com/a/';


function checkUrls($url){
	$ch = curl_init (); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 200); 
	$zz = curl_exec($ch); 
	$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
	curl_close($ch);
	//return $httpCode;
	if($httpCode == 200){
		return true;
	}else{
		return false;
	}
}


function getRandChar($length){
   $str = null;
   $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;

   for($i=0;$i<$length;$i++){
    	$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }

   return $str;
 }
// var_dump(checkUrls('http://www.vccoo.com/a/wme8u'));
// exit();
for($i=0;$i<10000;$i++){
	$_id = getRandChar(5);
	$url = $user_url.$_id;
	if(checkUrls($url)){
		saveAid($url);
		echo $url."<br />";
	}else{
		
	}
}
function saveAid($str){
	file_put_contents('data/vc.aid.txt', $str."\n",FILE_APPEND);
}






exit();



















// $sourceURL = "http://www.3jy.com/tag/12/2.html";
// $sourceURL = "http://weixin.sogou.com/pcindex/pc/pc_5/pc_5.html";
//$contentUrl = "http://weixin.sogou.com/pcindex/pc/pc_5/5.html";
$sourceList = 'http://weixin.sogou.com/pcindex/pc/pc_'.$type.'/';


/**
//==============测试获取微信基本信息

$contentUrl = "http://weixin.sogou.com/gzh?openid=oIWsFt9eVugAjPSViucxPUMqZRTc&ext=lA5I5al3X8BYrtW1H7KizeSlxz3j7jXNbhYq5hHUiK3kRa_38c2fM0YicIPGGskc";

 //$snoopy->fetchlinks($contentUrl);
// $snoopy->fetchtext($sourceURL);
//$snoopy->fetch($contentUrl);
//$return = $snoopy->results;
$html = file_get_html($contentUrl);

// //var_dump($html);
$a = file_get_contents($contentUrl);
echo $a;
$logo = $html->find('div.img-box img',0);
$wxName = $html->find('h3',0)->innertext;
$wxID = $html->find('label[name=em_weixinhao]',0)->innertext;
$sp = $html->find('span.sp-txt',0)->innertext;
$auto = $html->find('span.sp-txt',1)->innertext;
$qr_img = $html->find('div.v-box img',0);



echo "logo:".$logo->src;
echo "<br />";
echo "wxname:".($wxName);
echo "<br />";
echo "wxID:".$wxID;
echo "<br />";
echo "descript:".$sp;
echo "<br />";
echo "auto:".$auto;

exit();

**/
// echo "<br />";
// echo "qr_path:".$qr_img->src;
// echo "<br />";

//文章信息
// $titleObj = $html->find('h4 a',0);
// //var_dump($titleObj);
// $title  = $titleObj->innertext;
// $link = $titleObj->href;
// $head_pic = $html->find('div.img_box2 img',0);

// $create_time = $html->find('div.s-p',0);
// echo $title;
// echo "<br />";
// echo $link;
// echo "<br />";
// echo $head_pic->src;
// var_dump($head_pic);
// echo "<br />";
// echo $create_time->t;
// echo "<br />";
// echo "<br />";
// echo "<br />";

// print_r($return);
// exit();
// 
// 
function xml_to_array( $xml ) 
{ 
$reg = "/<([\w!]+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/"; 
if(preg_match_all($reg, $xml, $matches)) 
{ 
$count = count($matches[0]); 
for($i = 0; $i < $count; $i++) 
{ 
$subxml= $matches[2][$i]; 
$key = $matches[1][$i]; 
if(preg_match( $reg, $subxml )) 
{ 
$arr[$key] = xml_to_array( $subxml ); 
}else{ 
$arr[$key] = $subxml; 
} 
} 
} 
return $arr; 
}   
//$urltest = 'http://weixin.sogou.com/gzhjs?openid=oIWsFtwDn59WGm87jyUwWequ1Qzg&ext=lA5I5al3X8BQD-Jx-wEt66ePM02mZzHmmua41EddmOJPqTlBm0Nmf7xM_jPIK-To';
function saveArticle($url,$wxID,&$db){
	//echo $url."<br>";
	$content = file_get_contents($url);
	var_dump($content);
	$arr = json_decode($content,1);
	if(!$arr){
		return '';
	}
	foreach ($arr['items'] as $key => $value) {
		 // print_r($value);
		$art = xml_to_array($value);
		$listInfo = array();
		//
		$listInfo = $art_arr [] = $art['DOCUMENT'];

		$_d = array();
		$_d['wid'] = $wxID;
		$_d['wx_name'] = trimTag($listInfo['sourcename']);
		// $_d['aid'] = '';
		$_d['pub_time'] = $listInfo['pagesize']['lastModified'];
		$_d['url'] = trimTag($listInfo['url']);
		$_d['content'] = '';
		$_d['view'] = '';
		$_d['tag'] = '';
		$_d['class_id'] = $listInfo['docid']['classid'];
		$_d['title'] = $listInfo['title'];
		$_d['head_image'] = $listInfo['imglink'];
		$_d['intro'] = $listInfo['content168'];
		$_d['logo'] = $listInfo['headimage'];
		$_d['openid'] = $listInfo['openid'];
		$_d['ext'] = $listInfo['ext'];
		$table = 'wx_article';
		$db->insert($table,$_d);
		echo $sql = $db->getLastSql();
		echo "<br />";
		saveSql($sql);
	}
}
function trimTag($str){
	$arr = array('<![CDATA[',']]>');
	return str_replace($arr, '', $str);
}
// saveArticle($urltest);
// exit();

/**

for($i=2;$i<4;$i++){
	$return = array();
	$sourceList1 = $sourceList.$i.'.html';
	// echo "<br>";
	// $snoopy->fetchlinks($sourceList1);
	// $return = $snoopy->results;
	$html = file_get_html($sourceList1);
	$return = $html->find('a');
	sleep(5);
	foreach ($return as $key => $value) {
		if(checkUrl($value->href)){
			//echo $value."<br />";
			$wxIdArr[] = $value;
			saveInfoWx($value->href,$type,$db);
		}else{
			//echo $i.":false url:".$value."<br />";
		}
	}
	sleep(3);
}
**/
//saveInfoWx('http://weixin.sogou.com/gzh?openid=oIWsFt3xCQPgywg2M8XwLqg9Q-Gg&ext=lA5I5al3X8BmTukCoE07jSYFtmu9Fii51vxry5GdRXza5fMN9eqMB_D6KwFVSSe5',$type,$db);
function saveInfoWx($url,$type='',$db = ''){
	echo $url."<br />";

	$html = file_get_html($url);
	// var_dump($html);
	$logo = $html->find('div.img-box img',0)->src;
	$wxName = $html->find('h3',0)->innertext;
	//var_dump($wxName);
	// echo "<br>";
	$wxID = $html->find('label[name=em_weixinhao]',0)->innertext;
	//var_dump($wxName);
	//echo "<br>";
	$sp = $html->find('span.sp-txt',0)->innertext;
	$auto = $html->find('span.sp-txt',0)->innertext;
	$qr_img = $html->find('div.v-box img',0);
	//先保存到本地文件
	//顺序微信id，微信昵称，logo，描述，认证单位，二维码
	$str = $wxID.';'.$wxName.';'.$sp.';'.$auto.';'.$qr_img->src;
	$_d['wid'] = $wxID;
	$_d['wx_name'] = $wxName;
	$_d['sp'] = $sp;
	$_d['auto'] = $auto;
	$_d['qr_img'] = $qr_img;
	//$_d['sp'] = $sp;
	//$_d['sp'] = $sp;
	//echo $str."<br />";
	//saveInfoWx($str);
	$db->insert('wx_no',$_d);
	echo $db->getLastSql()."<br />";
	$savePath = 'data/weixin_'.$type.'.txt';
	$article_url = str_replace('gzh?openid', 'gzhjs?openid', $url);
	//$article_link =  
	//saveArticle($article_url,$wxID,$db);
	file_put_contents('data/weixin_'.$type.'.txt', $str."\n",FILE_APPEND);
}

function checkUrl($url){
	//echo $url."<br/>";
	 $part = explode('/', $url);
	 $part1 = explode('.', $url);
	if( strpos($url, 'gzh?openid')&& (strlen($url) < 200)){
		return true;
	}else{
		return false;
	}
}

function number($str)
 {
    return preg_replace('/\D/s', '', $str);
 }
 function saveSql($sql){
 	file_put_contents('./data/sql_log_wx.txt', $sql.";\n", FILE_APPEND);
 }




 ?>

