<?php 
/***
  *  采集 nbsw.cc
  *
  * */
error_reporting(1);
ignore_user_abort(1);
include_once('./config.php');
include ('./snoopy.class.php');
include ('./simple_html_dom.php');
set_time_limit(0);
#获取页面url
#获取合法url
#抓取指定页面的内容
#存库

$snoopy = new Snoopy();
$ids = array();

#part 1 获取列表页面的详情连接
// $sourceURL = "http://nbsw.cc/page/1";
// $snoopy->fetchlinks($sourceURL);
// $return = $snoopy->results;
// foreach ($return as $key => $value) {
// 	if(checkUrl($value)){
// 		echo $value."<br />";
// 	}
// }
// die();
#通过详情链接获取详情内容
//$contentUrl = "http://wap.xxhh.com/418928.html";
 //$snoopy->fetchlinks($sourceURL);
// $snoopy->fetchtext($contentUrl);
//$snoopy->fetch($contentUrl);
//$return = $snoopy->results;
// foreach ($return as $key => $value) {
// 	if(checkUrl($value)){
// 		echo $value."<br />";
// 	}
// }
// die();
//print_r($return);
/**
$regex3="/<title>.*?<\/title>/ism";
$regex3 = '/<title>(.*)<\/title>/i';
if(preg_match_all($regex3, $return, $title)){  
   $_t = explode('_', $title[1][0]); 
   $title = $_t;
}else{  
   echo '0';
}
//print_r($title);
//<div id="content-18933" class="postcontent">
echo $id = number($contentUrl);
	$regex4="/<div id=\"content-$id\" class=\"postcontent\">.*?<\/div>/ism";  
	$regex4 = "/<div class=\"postcontent\".*?>.*?<\/div>/ism";
	$regex4 = '/<div[^>]*class="postcontent"[^>]*>(.*?) <\/div>/si';
	$regex4 = '/<h2>(.*?)<\/h2>/si';
	if(preg_match_all($regex4, $return, $content)){  
	    
	}else{  
	   $content =  '0';  
	}
	print_r($content);
die();
**/

// var_dump(checkUrl($contentUrl));
// exit();
for ($i=2; $i < 2000; $i++) { 
	$sourceURL = "http://nbsw.cc/cat/duanzi/page/".$i;
	$html =file_get_html($sourceURL);
	for($n=0;$n<12;$n++){
		$aa = $html->find('div.postcontent h2 a',$n);
		echo $aa->href."<br>";
		if(checkUrl($aa->href)){
			//echo 'zzz';
			$has = 1;
			saveInfo($aa->href,$db,$ids);
			//sleep(2);
		}
	}
	
	// preg_match('/http:\/\/[\w-]*(\.[\w-]*)+/ig', $aa,$z);
	// print_r($z);
	// print_r($aa->href);
	// exit();
	//echo $sourceURL."<br/>";
	// foreach ($aa as $key => $value) {
	// 	echo $value->href."<br>";
	// }
	// $snoopy = new Snoopy();
	// $snoopy->fetchlinks($sourceURL);
	// $links = $snoopy->results;
	// $has = 0;
	// //print_r($links);
	// foreach ($links as $key => $value) {
	// 	//$value
	// 	echo $value."<br />";
	// 	if(checkUrl($value)){
	// 		//echo 'zzz';
	// 		$has = 1;
	// 		saveInfo($value,$db,$ids);
	// 		//sleep(2);
	// 	}
	// }
	// if($has == 0){
	// 	die();
	// }
	

}
function checkUrl($url){
	//echo $url."<br/>";
	 $part = explode('/', $url);
	 $part1 = explode('.', $url);
	 $pathInfo = (pathinfo($url));
	 // var_dump(strpos($url, 'youmo'));
		// var_dump(strpos($url, '.htm') );
		// echo count($part) ;
	 //echo count($part1);
	if( strpos($url, 'nbsw.cc')&&(number($pathInfo['basename']) == ($pathInfo['basename']) ) &&( $pathInfo['basename'] = $pathInfo['filename']) && !(strpos($url,'page')) ){
		// var_dump(strpos($url, 'youmo'));
		// var_dump(strpos($url, '.htm') );
		//echo 'ok';
		//echo $url."<br/>";
		//echo $part1[2]."<br/>";
		return true;
	}else{
		return false;
	}
}
function saveInfo($url,$db,&$ids){
	echo $url."<br/>";
	// $ids = $GLOBALS['ids'] = something;;
	//$ids = $GLOBALS['ids'] ;
	$id = number($url);

	if(!in_array($id, $ids)){
		$html = new simple_html_dom();
		$html =file_get_html($url);
		$title = $html->find('h2',0)->innertext;
		$content = $html->find('div.postcontent p',0)->innertext;
		$c = $html->find('span.tags',0)->innertext;
		$c = strip_tags($c);
		$c = str_replace(array('笑点:','(',')'), '', $c);
		$c = trim(delnumber($c));

		$table = 'joke_test_ct';
		$data['title'] = $title;
		$data['content'] = ($content);
		$data['url'] = $url;
		$data['mark'] = number($url);
		$data['site'] = 3;
		$data['tag'] = $c;
		//print_r($data);
		$r = $db->insert($table,$data);
		$sql = $db->getLastSql();
		echo $sql ."<br>";
		saveSql($sql);
		array_push($ids, $id);
		//print_r($ids);
	}
}
function number($str)
 {
    return preg_replace('/\D/s', '', $str);
 }

function delnumber($str)
 {
    return preg_replace('/\d/s', '', $str);
 }

 function saveSql($sql){
 	file_put_contents('./data/sql_log_nbsw.txt', $sql.";\n", FILE_APPEND);
 }

/**
// $return = $snoopy->results;

// $regex3="/<title>.*?<\/title>/ism";
// if(preg_match_all($regex3, $return, $title)){  
//    print_r($title);  
// }else{  
//    echo '0';
// }


// // print_r($return);
// $regex4="/<div class=\"content-nr\".  * ?>.*? < \/div>/ism";  
// if(preg_match_all($regex4, $return, $matches)){  
//    print_r($matches);  
// }else{  
//    echo '0';  
// }

**/



 ?>