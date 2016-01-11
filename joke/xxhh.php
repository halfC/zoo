<?php 
/***
  *  采集xxhh.com
  *
  * */
ignore_user_abort(1);
include_once('./config.php');
include ('./snoopy.class.php');
set_time_limit(0);
#获取页面url
#获取合法url
#抓取指定页面的内容
#存库

$snoopy = new Snoopy();


/**
// $sourceURL = "http://www.3jy.com/tag/12/2.html";
$contentUrl = "http://wap.xxhh.com/418928.html";
 // $snoopy->fetchlinks($sourceURL);
// $snoopy->fetchtext($sourceURL);
$snoopy->fetch($contentUrl);
$return = $snoopy->results;

$regex3="/<title>.*?<\/title>/ism";
$regex3 = '/<title>(.*)<\/title>/i';
if(preg_match_all($regex3, $return, $title)){  
   $_t = explode('_', $title[1][0]); 
   $title = $_t;
}else{  
   echo '0';
}print_r($title);
$id = number($contentUrl);
	$regex4="/<pre id=\"pre-".$id."\".*?>.*?<\/pre>/ism";  
	if(preg_match_all($regex4, $return, $content)){  
	    
	}else{  
	   $content =  '0';  
	}
	print_r($content);
die();
**/

// var_dump(checkUrl($contentUrl));
// exit();
for ($i=2; $i < 10000; $i++) { 
	$sourceURL = "http://wap.xxhh.com/duanzi/page/".$i."/";
	$snoopy = new Snoopy();
	$snoopy->fetchlinks($sourceURL);
	$links = $snoopy->results;
	$has = 0;
	
	foreach ($links as $key => $value) {
		//$value
		//echo $value."<br />";
		if(checkUrl($value)){
			//echo 'zzz';
			$has = 1;
			saveInfo($value,$db);
			//sleep(2);
		}
	}
	if($has == 0){
		die();
	}

}
function checkUrl($url){
	//echo $url."<br/>";
	 $part = explode('/', $url);
	 $part1 = explode('.', $url);
	 // var_dump(strpos($url, 'youmo'));
		// var_dump(strpos($url, '.htm') );
		// echo count($part) ;
	 //echo count($part1);
	if( strpos($url, 'wap.xxhh.com/')&& strpos($url, '.htm')&&(number($part1[2]) > 0 ) &&( count($part) ==4) &&(count($part1)==4) ){
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
function saveInfo($url,$db){
	// echo 'saveInfo';
	$snoopy = new Snoopy();
	$snoopy->fetch($url);
	$return = $snoopy->results;

	// $regex3="/<title>.*?<\/title>/ism";
	$regex3 = '/<title>(.*)<\/title>/i';
	if(preg_match_all($regex3, $return, $title)){  
	       	$_t = explode('_', $title[1][0]); 
   		$title = $_t[0];
	}else{  
	   $title = '0';
	}
	$id = number($url);
	$regex4="/<pre id=\"pre-".$id."\".*?>.*?<\/pre>/ism";  
	if(preg_match_all($regex4, $return, $content)){  
	    
	}else{  
	   $content =  '0';  
	}
	$table = 'joke';
	$data['title'] = $title;
	$data['content'] = ($content[0][0]);
	$data['url'] = $url;
	$data['mark'] = number($url);
	$data['site'] = 2;
	//print_r($data);
	$r = $db->insert($table,$data);
	$sql = $db->getLastSql();
	//echo "<br>";
	saveSql($sql);


}
function number($str)
 {
    return preg_replace('/\D/s', '', $str);
 }
 function saveSql($sql){
 	file_put_contents('./data/sql_log_xxhh.txt', $sql.";\n", FILE_APPEND);
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