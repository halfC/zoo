<?php 
header("Content-type: text/html; charset=utf-8"); 
/***
  *  采集微信号
  *
  * */
//ignore_user_abort(1);
include_once('./config.php');
include ('./snoopy.class.php');
set_time_limit(0);
include 'simple_html_dom.php';
// 新建一个Dom实例
$html = new simple_html_dom();


$snoopy = new Snoopy();

$type = 2;

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
$urltest = 'http://weixin.sogou.com/gzhjs?openid=oIWsFt1T5OJoSaIq98T5QWao828c&ext=bC4jy94pB0QAEAVtJPC5z_gPow3fG8HAZvhWGKDtND5SAzk5uwfOEdkI2TQpq_Lq';
//saveArticle($urltest,'xxx',$db);
function saveArticle1($url,$data,$db){
	if($url == ''){
		return false;
	}
	$html = file_get_html($url);
	if(!$html){
		return false;
	}
	$data['title'] = $html->find('h2',0)->innertext;
	$data['wx_name'] = $html->find("#post-user",0)->innertext;
	//$data['is_original'] = $html->find('span#copyright_logo',0)->innertext ? 1:0;
	$data['pub_time'] = strtotime($html->find('#post-date',0)->innertext);
	$data['content'] = $html->find('#js_content',0)->innertext;
	$db->insert('wx_article',$data);
	$sql = $db->getLastSql();
	//echo $sql."<br />";
	echo "save one article:".date('Y-m-d H:i:s')."<br />";
	saveSql($sql);
}
function saveArticle($url,$wxID,&$db){
	echo $url."<br>";
	$content = file_get_contents($url);
	//var_dump($content);
	$arr = json_decode($content,1);
	if(!$arr){
		return '';
	}
	echo 'has content'."<br />";

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
		$_d['title'] = trimTag($listInfo['title']);
		$_d['head_image'] = trimTag($listInfo['imglink']);
		$_d['intro'] = trimTag($listInfo['content168']);
		$_d['logo'] = trimTag($listInfo['headimage']);
		$_d['openid'] = trimTag($listInfo['openid']);
		$_d['ext'] = trimTag($listInfo['ext']);
		$table = 'wx_article';
		$db->insert($table,$_d);
		$sql = $db->getLastSql();
		//echo $sql ."<br />";
		//echo $con = str_replace('art.jsp?sg', 'shareFavorite.jsp?sg', $_d['url'])."<hr>";
		//echo gzdecode(readsogou('http://weixin.sogou.com'.$con));
		//echo "<hr>";
		//echo getC('http://weixin.sogou.com'.$con);

		saveSql($sql);
	}
}
function trimTag($str){
	$arr = array('<![CDATA[',']]>');
	return str_replace($arr, '', $str);
}
//saveArticle($urltest,'aaa',$db);
//T	7|1455852148|v1	weixin.sogou.com	/	2016-03-20T03:22:27.693Z	21				
				



// $url = "http://mp.weixin.qq.com/s?__biz=MjM5MDA1OTg4MA==&mid=406835306&idx=1&sn=61a6b23c9d4980f14f4e341d651ba81e&3rd=MzA3MDU4NTYzMw==&scene=6#rd";


$url = 'http://weixin.sogou.com/websearch/art.jsp?sg=CBf80b2xkgaZRI6AudJREnAGlB7nIcyocDHVX5LUqxYIDUCv_dBAyml-z5Mo5E_X6LpqnZNSo6SQOHqrKcZ2Xp8u1Myc2uKFpbFAAkPANfwxE6g1HXDz7D5D9WH0skX6M9Wf_8Q36klEqk-m7sfvrD0a7rmrVa5c&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wih87LVdgsMxROmRU9-Q5FDYbsUjhZ6TVEFmlnIzr07MWQ3JxMQ3374b_sRKFULbYy5HfBF8DH3YQCAouKv9nxi_wVhTL6a5WtYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk';

// $url = "http://weixin.sogou.com/websearch/shareFavorite.jsp?sg=-kjBTUzEYBCkYhLhiNfJiI6uweSa7qBhTCKcIS3L7O-xjw6Utw9dAZqdf5CjeUHAoDwrIEbPsFYovODsmmXnjVviwzAV55proU7WwVkRbVCyd9mvw1NYKopiRAlJa4Bwc24duCPSc7uvU7oxMlmUFmH57fuM2F1D&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8whzDxkRUhI9uvWUoE6RbBoBoBqemXi7RxPCUYF-Tec3JKCL_1-Sd1UOxg-5KPI3BuSWHaT2pDF9CbIE_cQKZemTU-Za8NseSpBYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk";

// $a = 'sg=CBf80b2xkgaZRI6AudJREnAGlB7nIcyocDHVX5LUqxYIDUCv_dBAyml-z5Mo5E_X6LpqnZNSo6SQOHqrKcZ2Xp8u1Myc2uKFpbFAAkPANfwxE6g1HXDz7D5D9WH0skX6M9Wf_8Q36klEqk-m7sfvrD0a7rmrVa5c&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wih87LVdgsMxROmRU9-Q5FDYbsUjhZ6TVEFmlnIzr07MWQ3JxMQ3374b_sRKFULbYy5HfBF8DH3YQCAouKv9nxi_wVhTL6a5WtYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk';
// $b = 'sg=-kjBTUzEYBCkYhLhiNfJiI6uweSa7qBhTCKcIS3L7O-xjw6Utw9dAZqdf5CjeUHAoDwrIEbPsFYovODsmmXnjVviwzAV55proU7WwVkRbVCyd9mvw1NYKopiRAlJa4Bwc24duCPSc7uvU7oxMlmUFmH57fuM2F1D&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8whzDxkRUhI9uvWUoE6RbBoBoBqemXi7RxPCUYF-Tec3JKCL_1-Sd1UOxg-5KPI3BuSWHaT2pDF9CbIE_cQKZemTU-Za8NseSpBYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk';
// $html = file_get_html_c($url);
// var_dump($html);
// echo getC($url);
// echo file_get_contents($url);
// $content =  $html->find('.rich_media_content',0)->innertext;
// foreach ($content->find('img') as $key => $value) {
// 	$value->src = saveImg($value->src);
// }
// function saveImg(){


 // $zip = new ZipArchive; 
 //  $res = $zip->open('./1.zip');
 //  if ($res === TRUE) { 
 //      echo 'ok'; 
 //      //解压缩到test文件夹 
 //      $zip->extractTo('data'); 
 //      $zip->close(); 
 //  } else { 
 //      echo 'failed, code:' . $res; 
 //  } 
//echo gzdecode(readsogou($url));
$url = 'http://weixin.sogou.com/websearch/art.jsp?sg=xaXJ7lM8QYRB8i2CWL2b7Sn2_HeP8g_8fzCW0DGtd5wIDUCv_dBAyml-z5Mo5E_X6LpqnZNSo6SQOHqrKcZ2Xp8u1Myc2uKFpbFAAkPANfwxE6g1HXDz7D5D9WH0skX6kHg3ondG_J3vYAZQpe1_SQ..&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wjcJhF95Qda8LTW4IY5hUxS1EpZGR0F1jdJVRLegP16bj8FAQFjW_foLDkQeHT5eOmcjfEH_68wJcXd0uQsmo_xZluUFn5ipWFYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk';
// echo $aa_link = sogou_weixin($url); 
// echo file_get_contents($aa_link);

// echo gzdecode(readsogou($aa_link));

function sogou_weixin($url) { 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
$data = curl_exec($ch); 
$Headers = curl_getinfo($ch); 
curl_close($ch); 
if ($data != $Headers) 
return $Headers["url"]; 
else 
return false; 
}
// }
//echo readsogou('http://127.0.0.1/code/tellingJokes/cookie.php');
//file_put_contents('1.zip', readsogou($url)) ;
function readsogou($url) { 
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Accept-language: zh-CN,zh;q=0.8,en;q=0.6\r\n" .
				  "Accept-Encoding: gzip, deflate, sdch\r\n"  .
				  "Cookie: ABTEST=7|1455852148|v1; IPLOC=CN1101; SUID=09DDB03043466E54BD3E29A343E52C4D; SUV=00922EFD72F39F4B56C68A74D6228743; weixinIndexVisited=1\r\n"  .
				  "Referer:http://weixin.sogou.com/gzh?openid=oIWsFt6Jz41fAg2eQHTA1wIbSp0Y&ext=bC4jy94pB0TEf5RQi8iq5RAN5VnrOmfcwRtsEJ8wpw-VMr43IO7sr_jQnuWMmh2i\r\n"  .
				  "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36\r\n"  .
				  "X-Requested-With:XMLHttpRequest\r\n".
				  "Accept:text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01 \r\n" .
				  "Accept-Language:zh-CN,zh;q=0.8 \r\n" . 
				  "Cache-Control:no-cache \r\n" . 
				  "Connection:keep-alive \r\n" . 
				  "Pragma:no-cache \r\n" 
	  )
	);
	$context=stream_context_create($opts); 
	//Header("Location:".$url); 
	return file_get_contents($url,false,$context); 
} 


if (!function_exists('gzdecode')) {      
    function gzdecode ($data) {      
        $flags = ord(substr($data, 3, 1));      
        $headerlen = 10;      
        $extralen = 0;      
        $filenamelen = 0;      
        if ($flags & 4) {      
            $extralen = unpack('v' ,substr($data, 10, 2));      
            $extralen = $extralen[1];      
            $headerlen += 2 + $extralen;      
        }      
        if ($flags & 8) // Filename      
            $headerlen = strpos($data, chr(0), $headerlen) + 1;      
        if ($flags & 16) // Comment      
            $headerlen = strpos($data, chr(0), $headerlen) + 1;      
        if ($flags & 2) // CRC at end of file      
            $headerlen += 2;      
        $unpacked = @gzinflate(substr($data, $headerlen));      
        if ($unpacked === FALSE)      
              $unpacked = $data;      
        return $unpacked;      
     }      
} 


//start
for($i=1;$i<16;$i++){
	$return = array();
	if($i == 0){
		$sourceList1 = $sourceList.'pc_'.$type.'.html';
	}else{
		$sourceList1 = $sourceList.$i.'.html';
	}
	 echo $sourceList1."<br>";
	//echo file_get_contents($sourceList1);
	// $snoopy->fetchlinks($sourceList1);
	// $return = $snoopy->results;
	$html = file_get_html($sourceList1);
	if(!$html){
		return false;
	}
	$return = $html->find('li');

	sleep(1);
	foreach ($return as $key => $value) {
		$link = $value->find('a');
		$wxName = $value->find('p[title]');
		foreach($wxName as $x){
			$wxNameStr =  ($x->innertext);
		}
		$head_pic = $value->find('img',0)->src;
		$intro = $value->find('.wx-news-info',0)->innertext;
		$view = number(strip_tags($value->find('.s-p',0)->innertext));
		foreach($link as $k=>$v){
			if(checkUrl($v->href)){
				saveInfoWx($value->href,$type,$db);
				break;
			}elseif(checkUrlWx($v->href)){
				//$v->href."<br />";
				$_d['url'] = $v->href;
				$_d['view'] = $view;
				$_d['type'] = $type;
				$_d['head_image'] = $head_pic;
				$_d['intro'] = $intro;
				saveArticle1($v->href,$_d,$db);
				break;
			}
		}
		sleep(2);
//		if(checkUrl($value->href)){
//			//echo $value."<br />";
//			//$wxIdArr[] = $value;
//			//saveInfoWx($value->href,$type,$db);
//		}elseif(checkUrlWx($value->href)){
//			echo $value->href."<br />";
//
//			//saveArticle($value->href,$wxID,$db);
//			//echo $i.":false url:".$value."<br />";
//		}
	}
	sleep(3);
}

//saveInfoWx('http://weixin.sogou.com/gzh?openid=oIWsFt3xCQPgywg2M8XwLqg9Q-Gg&ext=lA5I5al3X8BmTukCoE07jSYFtmu9Fii51vxry5GdRXza5fMN9eqMB_D6KwFVSSe5',$type,$db);
function saveInfoWx($url,$type='',$db = ''){
	echo $url."<br />";
	$html = file_get_html($url);
	if(!$html){
		return false;
	}
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
	$_d['url'] = $url;
	$_d['auto'] = $auto;
	$_d['qr_img'] = $qr_img->src;
	$_d['logo'] = $logo;
	//$_d['sp'] = $sp;
	//echo $str."<br />";
	//saveInfoWx($str);
	$db->insert('wx_no',$_d);
	//echo $db->getLastSql()."<br />";
	$savePath = 'data/weixin_'.$type.'.txt';
	$article_url = str_replace('gzh?openid', 'gzhjs?openid', $url);
	//$article_link =  
	//saveArticle($article_url,$wxID,$db);
	file_put_contents('data/weixin_'.$type.'.txt', $str."\n",FILE_APPEND);
	sleep(3);
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
function checkUrlWx($url){
	//echo $url."<br/>";
	
	if( strpos($url, 's?__biz=') && (strlen($url) < 200)){
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

