<?php
header('Content-type: text/html;charset=UTF-8');
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
$agent_arr = array('User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat',
					'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
					'Sosospider+(+http://help.soso.com/webspider.htm)',
					'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
					'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Win64; x64; Trident/4.0)',
					'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.12) Gecko/20080219 Firefox/2.0.0.12 Navigator/9.0.0.6',
					'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13',
					'Mozilla/5.0 (iPhone; U; CPU like Mac OS X) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3',
					'Mozilla/5.0 (Macintosh; PPC Mac OS X; U; en) Opera 8.0'
					);


ini_set('user_agent',$agent_arr[array_rand($agent_arr)]);
//ini_set('user_agent','Sosospider+(+http://help.soso.com/webspider.htm)');

$contentUrl = "http://weixin.sogou.com/gzh?openid=oIWsFt3ZFu_NDjJrAqjXu2_NXnt8&ext=bC4jy94pB0T2a5IHjVQ2OfL_9GAuXxDUsCelwLQf8uSULgJ_FNDyAgQiYfRM0yoC";

//$contentUrl = 'http://weixin.sogou.com/';

$ip = "110.73.1.250:8123";
$header[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
$header[] = 'Accept-Encoding:gzip, deflate, sdch';
$header[] = 'Accept-Language:zh-CN,zh;q=0.8';
$header[] = 'Cache-Control:no-cache';
$header[] = 'Connection:keep-alive';
$header[] = 'Cookie:SUID=F3A6C16F7F23900A000000005640074A; SUV=1447036750683881; CXID=3743134985C01FF35F91B0483F17CEB8; pgv_pvi=7629323264; ABTEST=0|1455591066|v1; weixinIndexVisited=1; SNUID=13BDD550222409FB46EC3E2A221D5AB6; IPLOC=CN1101';
$header[] = 'Host:weixin.sogou.com';
$header[] = 'Pragma:no-cache';
$header[] = 'Upgrade-Insecure-Requests:1';
$header[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36';

/**



Cache-Control:no-cache

Host:weixin.sogou.com
Pragma:no-cache
Upgrade-Insecure-Requests:1
User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36



Accept-Encoding:gzip, deflate, sdch



Cookie:SUV=00F36E2172F4980456A83B8A59F4E681; ABTEST=0|1455606086|v1; SUID=329FF4721E10930A0000000056C2C946; SUID=329FF4722E08990A0000000056C2C946; weixinIndexVisited=1; PHPSESSID=k0crrbb2ffutfs6inca4rtf2p3; SUIR=1455608302; SNUID=A9046FE99B9EB044A3967AE19B2AFEFA; sct=2; IPLOC=CN1101
Host:weixin.sogou.com
Referer:http://weixin.sogou.com/
Upgrade-Insecure-Requests:1
User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36

**/
echo $ip = getIp();
$ip = '110.73.7.221';
$header1[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
$header1[] = 'Accept-Encoding:gzip, deflate, sdch';
$header1[] = 'Accept-Language:zh-CN,zh;q=0.8,en;q=0.6';
$header1[] = 'Cache-Control:max-age=0';
$header1[] = 'Connection:keep-alive';
$header1[] = 'Host:weixin.sogou.com';
$header1[] = 'Upgrade-Insecure-Requests:1';
$header1[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36';
$header1[] = 'X-FORWARDED-FOR:'.$ip.'CLIENT-IP:'.$ip;
//$ips = get_client_ip();
echo $agents = $agent_arr[array_rand($agent_arr)];
$ch = curl_init($contentUrl);
$cookie_jar = tempnam('./tmp','JSESSIONID');  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ips, 'CLIENT-IP:'.$ips));
curl_setopt($ch, CURLOPT_USERAGENT, $agents);
//curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_REFERER, 'http://weixin.sogou.com/'); // 看这里，你也可以说你从google来  
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIE,'Cookie:SUV=00F36E2172F4980456A83B8A59F4E681; ABTEST=0|1455606086|v1; SUID=329FF4721E10930A0000000056C2C946; SUID=329FF4722E08990A0000000056C2C946; weixinIndexVisited=1; PHPSESSID=k0crrbb2ffutfs6inca4rtf2p3; SUIR=1455608302; SNUID=A9046FE99B9EB044A3967AE19B2AFEFA; sct=2; IPLOC=CN1101');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
echo ($output);

function getIp(){
	$url = 'http://10.10.0.18/proxy_healthip';
	$ipD = file_get_contents($url);
	$arr = explode("\n",$ipD);
	$r = array_rand($arr);
	return $arr[$r];
}



echo file_get_contents($contentUrl);