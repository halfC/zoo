<?php 
header("Content-type: text/html; charset=utf-8"); 
/***
  *  采集xxhh.com
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

$type = 11;

// $sourceURL = "http://www.3jy.com/tag/12/2.html";
// $sourceURL = "http://weixin.sogou.com/pcindex/pc/pc_5/pc_5.html";
$contentUrl = "http://weixin.sogou.com/pcindex/pc/pc_5/5.html";
$sourceList = 'http://weixin.sogou.com/pcindex/pc/pc_'.$type.'/';
// $contentUrl = "http://weixin.sogou.com/gzh?openid=oIWsFt9eVugAjPSViucxPUMqZRTc&ext=lA5I5al3X8BYrtW1H7KizeSlxz3j7jXNbhYq5hHUiK3kRa_38c2fM0YicIPGGskc";

 //$snoopy->fetchlinks($contentUrl);
// $snoopy->fetchtext($sourceURL);
//$snoopy->fetch($contentUrl);
//$return = $snoopy->results;
// $html = file_get_html($contentUrl);

// //var_dump($html);

// $logo = $html->find('div.img-box img',0);
// $wxName = $html->find('h3',0)->innertext;
// $wxID = $html->find('label[name=em_weixinhao]',0)->innertext;
// $sp = $html->find('span.sp-txt',0)->innertext;
// $auto = $html->find('span.sp-txt',1)->innertext;
// $qr_img = $html->find('div.v-box img',0);



// echo "logo:".$logo->src;
// echo "<br />";
// echo "wxname:".($wxName);
// echo "<br />";
// echo "wxID:".$wxID;
// echo "<br />";
// echo "descript:".$sp;
// echo "<br />";
// echo "auto:".$auto;

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
$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/"; 
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
$urltest = 'http://weixin.sogou.com/gzhjs?openid=oIWsFtwDn59WGm87jyUwWequ1Qzg&ext=lA5I5al3X8BQD-Jx-wEt66ePM02mZzHmmua41EddmOJPqTlBm0Nmf7xM_jPIK-To';
function saveArticle($url){
	echo $url."<br>";
	$content = file_get_contents($url);
	echo $content;
	$arr = json_decode($content,1);
	print_r($arr);
	foreach ($arr['items'] as $key => $value) {
		 // print_r($value);
		$art = xml_to_array($value);
		print_r($art);
	}
}
saveArticle($urltest);
exit();

for($i=2;$i<4;$i++){
	$return = array();
	$sourceList1 = $sourceList.$i.'.html';
	// echo "<br>";
	// $snoopy->fetchlinks($sourceList1);
	// $return = $snoopy->results;
	$html = file_get_html($sourceList1);
	$return = $html->find('a');
	foreach ($return as $key => $value) {
		if(checkUrl($value->href)){
			//echo $value."<br />";
			$wxIdArr[] = $value;
			saveInfoWx($value->href,$type);
		}else{
			//echo $i.":false url:".$value."<br />";
		}
	}
	sleep(3);
}

function saveInfoWx($url,$type){
	echo $url."<br />";

	$html = file_get_html($url);
	$logo = $html->find('div.img-box img',0);
	$wxName = $html->find('h3',0)->innertext;
	$wxID = $html->find('label[name=em_weixinhao]',0)->innertext;
	$sp = $html->find('span.sp-txt',0)->innertext;
	$auto = $html->find('span.sp-txt',0)->innertext;
	$qr_img = $html->find('div.v-box img',0);
	//先保存到本地文件
	//顺序微信id，微信昵称，logo，描述，认证单位，二维码
	$str = $wxID.';'.$wxName.';'.$logo->src.';'.$sp.';'.$auto.';'.$qr_img->src;
	//echo $str."<br />";
	//saveInfoWx($str);
	$savePath = 'data/weixin_'.$type.'.txt';
	$article_url = str_replace('gzh?openid', 'gzhjs?openid', $url);
	$article_link =  
	
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