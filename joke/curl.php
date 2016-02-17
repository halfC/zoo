<?php

$contentUrl = "http://weixin.sogou.com/gzh?openid=oIWsFt9eVugAjPSViucxPUMqZRTc&ext=lA5I5al3X8BYrtW1H7KizeSlxz3j7jXNbhYq5hHUiK3kRa_38c2fM0YicIPGGskc";

//$c = get_curlcuconent2($contentUrl,'weixin.sogou.com');
//echo $c;
function get_curlcuconent2($filename,$referer)  
{  
   $cookie_jar = tempnam('./tmp','JSESSIONID');  
         
   $ch = curl_init();  
   curl_setopt($ch, CURLOPT_URL, $filename);  
   curl_setopt($ch, CURLOPT_HEADER, false);  
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
  
   //设置文件读取并提交的cookie路径  
   curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);  
   $filecontent=curl_exec($ch);  
   curl_close($ch);  
         
   $ch = curl_init();  
   $hostname ="weixin.sogou.com";  
   //$referer="http://www.domain.com/";  
   curl_setopt($ch, CURLOPT_URL, $filename);  
   curl_setopt($ch, CURLOPT_REFERER, $referer); // 看这里，你也可以说你从google来  
   curl_setopt($ch, CURLOPT_USERAGENT, "weixin.sogou.com");  
  
   //$request = "JSESSIONID=abc6szw15ozvZ_PU9b-8r"; //设置POST参数  
   //curl_setopt($ch, CURLOPT_POSTFIELDS, $request);     
   // 上面这句，当然你可以说你是baidu，改掉这里的值就ok了，可以实现小偷的功能，$_SERVER['HTTP_USER_AGENT']  
   //你也可以自己做个 spider 了，那么就伪装这里的 CURLOPT_USERAGENT 吧  
   //如果你要把这个程序放到linux上用php -q执行那也要写出具体的$_SERVER['HTTP_USER_AGENT']，伪造的也可以  
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
   curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);  
   curl_setopt($ch, CURLOPT_HEADER, false);//设定是否输出页面内容  
   //curl_setopt($ch, CURLOPT_GET, 1); // post,get 过去  
  
   $filecontent = curl_exec($ch);  
   preg_match_all("/charset=(.+?)[NULL\"\']/is",$filecontent, $charsetarray);  
   if(strtolower($charsetarray[1][0])=="utf-8")  
         $filecontent=iconv( 'utf-8', 'gb18030//IGNORE' , $filecontent);  
   curl_close($ch);  
   return $filecontent;  
}  
  



/**
//初始化
$ch = curl_init();

//设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, $contentUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);

//执行并获取HTML文档内容
$output = curl_exec($ch);

//释放curl句柄
curl_close($ch);

//打印获得的数据
print_r($output);
**/


/***
@header('Content-type: text/html;charset=UTF-8');

echo "开始爬取微信文章......";

$count = 0;
*******
     数据库操作
*****/
//$myconn=mysql_connect("localhost","root","root");
//mysql_query("set names 'utf8'"); //指定写入编码
//mysql_select_db("article",$myconn);
/**    
for($page = 1; $page <= 10; $page++)
{
     $url = "http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt-4lR2-450wfo60XXrtklqY&eqs=cls2o4dgqyYXowtDdJkJRuTSG9PcwNTSF%2B8KujiGLML7bPu3Nc9gcwQOZa6WL7Ob44OuT&ekv=7&page=".$page."&t=1435421383410";
     $ch = curl_init();
     curl_setopt ($ch, CURLOPT_URL, $url);
     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,100);
    
     $content = curl_exec($ch);

     $s = str_replace("\\","",$content);  //去掉转义符

     $arr = explode("\",\"",$s);         //分割xml段

     $m = 0;
    
     foreach($arr as $value){
          preg_match_all("/\<title\>\<!\[CDATA\[(.*)\]\]\>\<\/title\>/",$value,$titleArea);//匹配标题
          $title = current($titleArea[1]);
         
          preg_match_all("/\<url\>\<!\[CDATA\[(.*)\]\]\>\<\/url\>/",$value,$urlArea);//匹配文章url
          $url = current($urlArea[1]);
         
          preg_match_all("/\<imglink\>\<!\[CDATA\[(.*)\]\]\>\<\/imglink\>/",$value,$imglinkArea);//匹配图片url
          $imglink = current($imglinkArea[1]);
         
          preg_match_all("/\<content168\>\<!\[CDATA\[(.*)\]\]\>\<\/content168\>/",$value,$contentArea);//匹配文章内容
          $content = current($contentArea[1]);
         
          preg_match_all("/\<date\>\<!\[CDATA\[(.*)\]\]\>\<\/date\>/",$value,$dateArea);//匹配文章发表时间
          $date = current($dateArea[1]);
         
          $strSql="insert into weixinarticles(title,url,imglink,content,postday) values('".$title."','".$url."','".$imglink."','".$content."','".$date."')";
          //$result=mysql_query($strSql,$myconn);
		  echo $strSql."<br />";
          $count++;
         
     }
    
     echo "爬取结束!,共抓取到".$count."篇文章！";
}
//关闭对数据库的连接
 // mysql_close($myconn);
**/


$ip = "110.73.1.250:8123";
$ch = curl_init($contentUrl);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
echo ($output);


?>  