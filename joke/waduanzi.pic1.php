<?php
error_reporting(1);
ignore_user_abort(1);
set_time_limit(0);
include_once('./config.php');
include ('./snoopy.class.php');
include ('./simple_html_dom.php');
$ids = array();
$table = 'jokes_ct_pic';
for ($i=1; $i < 3; $i++) { //3629
	$sourceURL = 'http://www.waduanzi.com/lengtu/page/'.$i;
	$html =file_get_html($sourceURL);
	for ($n=0; $n < 20; $n++) { 
		$aa = $html->find('h2.item-title',$n)->innertext;
		$img = $html->find('img.bmiddle',$n);
    $link = $html->find('h2.item-title a',$n);
		$content = $html->find('div.item-content',$n)->innertext;
		$_d['title'] =  strip_tags($aa);
		$_d['content'] = $content;
		$ext=strtolower(substr($url,strrpos($img->src,'.')+1));
    //savepic("zdw",$img->src,$data['source_id']);
    if($ext == 'gif'){
      $_d['is_gif'] = 1;
    }else{
      $_d['is_gif'] = 0;
    }
    $id = number($link->href);
    if(!in_array($id,$ids)){
      //$_d ['local_pic'] = getImg($img->src,'','zdw');
      $_d['source_id'] = $id;
      $_d['source_name'] = $link->href;
  		$_d ['local_pic'] =  savepic("zdw1",$img->src,$id);
  		$imgInfo = (getimagesize($img->src));
  		$_d['pic'] =  ($img->src);
  		$_d['pic_m_w'] = $imgInfo[0];
  		$_d['pic_m_h'] = $imgInfo[1];
  		$r = $db->insert($table,$_d);
  		$sql = $db->getLastSql();
      saveSql($sql);
      echo $sql."<br>";
      array_push($ids, $id);
    }
		//echo $sql."<br/>";
    //print_r($ids);
	}
	
	
}
 function saveSql($sql){
  file_put_contents('./data/sql_log_wdz.txt', $sql.";\n", FILE_APPEND);
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
