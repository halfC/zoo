<?php
error_reporting(1);
ignore_user_abort(1);
set_time_limit(0);
include_once('./config.php');
include ('./snoopy.class.php');
include ('./simple_html_dom.php');
$table = 'jokes';
for ($i=1; $i < 3629; $i++) { 
	$sourceURL = 'http://www.waduanzi.com/lengtu/page/1';
	$html =file_get_html($sourceURL);
	for ($n=0; $n < 20; $n++) { 
		
		$aa = $html->find('h2.item-title',$n)->innertext;
		$img = $html->find('img.bmiddle',$n);
		$content = $html->find('div.item-content',$n)->innertext;
		$_d['title'] =  strip_tags($aa);
		$_d['content'] = $content;
		
		$_d ['local_pic'] = getImg($img->src,'','zdw');
		$imgInfo = (getimagesize($img->src));
		$_d['pic'] =  ($img->src);
		$_d['pic_m_w'] = $imgInfo[0];
		$_d['pic_m_h'] = $imgInfo[1];
		$r = $db->insert($table,$_d);
		$sql = $db->getLastSql();
		echo $sql."<br/>";
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