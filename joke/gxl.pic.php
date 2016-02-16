<?php
header("Content-type:text/html;charset=utf-8");
error_reporting(1);
ignore_user_abort(1);
set_time_limit(0);
include_once('./config.php');
include ('./snoopy.class.php');
include ('./simple_html_dom.php');
$table = 'jokes_ct_5';
$ids = array();
for ($i=2; $i < 10000; $i++) { //3629
	$sourceURL = "http://www.gaoxiaola.com/neihantu/index_".$i.".html";
	$html =file_get_html($sourceURL);
	 //iconv('GB2312', 'UTF-8', $html); 
	for ($n=0; $n < 40; $n++) { 
    $title = $html->find('ul.list-pic2 li span',$n)->innertext;
	  $title = iconv('GB2312', 'UTF-8', $title); 
		$link = $html->find('a.picture',$n);
    $id = number($link->href);
    //print_r($link->href);
		$img = $html->find('a.picture img',$n);

    //$link = $html->find('h2.item-title a',$n);
    //echo $link->href;exit();
		$content = $html->find('div.item-content',$n)->innertext;
		$_d['title'] =  $_d['content']=  strip_tags($title);
		//$_d['content'] = $content;
		$ext=strtolower(substr($url,strrpos($img->src,'.')+1));
    //savepic("zdw",$img->src,$data['source_id']);
    if($ext == 'gif'){
      $_d['is_gif'] = 1;
    }else{
      $_d['is_gif'] = 0;
    }
    //$_d ['local_pic'] = getImg($img->src,'','zdw');
    $_d['source_id'] = $id;
    $_d['source_name'] = $link->href;
    if(!in_array($id,$ids)){
      array_push($ids, $id);
  		$_d ['local_pic'] =  savepic("gxl1",$img->src,$id);
  		$imgInfo = (getimagesize($img->src));
  		$_d['pic'] =  ($img->src);
  		$_d['pic_m_w'] = $imgInfo[0];
  		$_d['pic_m_h'] = $imgInfo[1];
      //print_r($_d);
  		$r = $db->insert($table,$_d);
  		$sql = $db->getLastSql();
      saveSql($sql);
  		//echo $sql."<br/>";
    }
	}
	
	
}
 function saveSql($sql){
  file_put_contents('./data/sql_log_gxl.txt', $sql.";\n", FILE_APPEND);
 }
//savepic("11",$data['pic'],$data['source_id']);
function getLink($html){
  //preg_match_all('/\<a [\s+]? href=\"(\s)\"/', $html, $s);
  preg_match_all("/<a[^<>]+href *\= *[\"']?(http\:\/\/[^ '\"]+)/i", $html, $s);
  return $s;
}
function savepic($sourceid,$url,$picid){
  $ext=strtolower(substr($url,strrpos($url,'.')+1));//扩展名
  if(in_array($ext,array("jpg","png","gif","webp"))){//扩展名合法
    $dirA=str_split(str_pad($picid,8,0,STR_PAD_LEFT),2);//两位数目录
    $dirS="pic/".$sourceid."/";//项目目录
    $i=0;
    foreach($dirA as $dir){//逐级目录判断
      $dirS.=$dir;
      //echo $dirS."\n";
      ++$i;
      if(!file_exists ($dirS) and $i<4){//目录存在
        mkdir($dirS,0777,1);
        chmod($dirS,0777);
      }
      if($i==4)$dirS.=".".$ext;
      else $dirS.="/";
    }
    copy($url,$dirS);
    return $dirS;
//    file_put_contents(file_get_contents($url),$dirS);//写文件
  }
}

function getImg($url,$filename,$pre=''){
      if($url=='') return false;
      if($filename == ''){
        $pre = './'.$pre.'/'.date('Ymd').'/';
        $ext = strrchr($url,'.');
        if($ext != '.gif' && $ext != ".jpg") return false;
        if(!is_dir($pre)){
        	//echo $pre;
        	mkdir($pre,0777,1);
        }
        $filename = $pre.time().rand(1000,9999).$ext;
      }
      //save file
      ob_start();
      readfile($url);
      $img = ob_get_contents();
      ob_end_clean();
      $size = strlen($img);//备用 文件大小
      $fp = @fopen($filename,"a");
      fwrite($fp,$img);
      fclose($fp);
      return trim($filename,'.');
    }

    function number($str)
 {
    return preg_replace('/\D/s', '', $str);
 }

function delnumber($str)
 {
    return preg_replace('/\d/s', '', $str);
 }
