<?php
/**
for($i=1;$i<=10;$i++){
	$url="http://weixin.sogou.com/pcindex/pc/pc_0/$i.html";
	$content= readsogou($url);
	var_dump($content);
	$html->load($content);
	$retA=$html->find('div.wx-news-info2 a');
	foreach($retA as $element){
		$titleA[]=$element->innertext;
	}
	print_r($titleA);
}
**/


$url = 'http://weixin.sogou.com/websearch/art.jsp?sg=CBf80b2xkgZt0LGAK3wZrzF5EsO4be2yDyzz9IZLBA5qy6E5DPHkVjg3BmQ_Ua9A7qwAeprsvK27IbGG9f4r2t6n19ux5nqZpbFAAkPANfwxE6g1HXDz7D5D9WH0skX6TYFa84J-CnmUC5YG9WXNvWH57fuM2F1D&url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wjoxkFsF8IxnEdzEY6_LQoN1EpZGR0F1jd44pBMsvKxrmQ3JxMQ3374X4V8hgKe4v2_ZUdfsxnKqABTUv25xY2dL5oWdS-44Y9Yy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk';

echo readsogou($url);


function readsogou($url) { 
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Accept-language: zh-CN,zh;q=0.8,en;q=0.6\r\n" .
				  "Accept-Encoding:gzip, deflate, sdch\r\n"  .
				  "Cookie: ABTEST=7|1455790190|v1; IPLOC=CN1101; SUID=879AF3726F1C920A0000000056C5986E; SUID=879AF3724FC80D0A0000000056C5986E; SUV=0056783872F39A8756C5986E6B871180; weixinIndexVisited=1\r\n"  .
				  "Referer:http://weixin.sogou.com/\r\n"  .
				  "User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36\r\n"  .
				  "X-Requested-With:XMLHttpRequest\r\n".
				  "Accept-encoding: gzip\r\n"
				  
	  )
	);
	$context=stream_context_create($opts); 
	//Header("Location:".$url); 
	return file_get_contents($url,false,$context); 
} 




//header("content-type: text/html; charset=utf-8");  
class HttpWrap  
{  
    //定义超时时间  
    public $timeout=2;  
      
    //定义连接状态  
    public $status='';  
  
    //主机名  
    public $host;  
      
    //端口号  
    public $port=80;  
      
    //第一次连接时将主机名解析成IP  
    private $ip;  
      
    //连接资源  
    private $conn;  
      
    //连接的地址  
    private $url;  
      
    //解析URL中的路径  
    private $path;  
      
    //URL中包含的模式，比如FTP,HTTPS  
    private $scheme;  
  
    //请求方式，比如GET,POST,PUT  
    public $http_method='GET';  
      
    //HTTP的版本信息  
    public $http_version="HTTP/1.1";  
      
    //代理软件信息  
    public $agent="Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0";  
      
    //定义可接收的MIME信息  
    public $accept="image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*";  
      
    //压缩方式  
    public $gzip="gzip";  
      
    //上级域名或者连接地址  
    public $referer;  
      
    //设置COOKIE  
    public $cookie;  
      
    //提交类型  
    public $submit_type="application/x-www-form-urlencoded";  
      
    //可接收的语言类型,q表示优先级,这里zh是0.8  
    public $accept_language="zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3";  
      
    //保持长连接  
    public $connection="close";  
  
    //HTTP请求行  
    private $cmd_line;  
      
    //HTP请求头部  
    private $header;  
      
    //HTTP请求，如果包含主体信息  
    public $post_content;  
  
      
      
    //重定向地址  
    private $redirect;  
      
    //是否支持GZIP压缩  
    private $is_gzip;  
      
    //传输方式如果是以块编码  
    private $is_chunked;  
      
    //chunk块的长度  
    private $chunk_length=0;  
      
    //HTTP响应状态码,比如200,404等  
    public $response_num;  
      
    //HTTP响应头部信息  
    public $response_header;  
      
    //HTTP响应主体  
    public $response_body;  
      
    //HTTP响应主体信息的长度  
    public $response_body_length;  
   
    //响应的编码信息  
    public $encoding;  
      
  
   public  function init($url)  
    {  
        $this->url=$url;  
        //解析url信息  
        $url_pair = parse_url($url);  
        //保存主机名  
        $this->host = $url_pair['host'];  
        //保存url中包含的路径信息  
        $this->path = $url_pair['path'];  
        //保存使用的模式信息  
        $this->scheme = $url_pair['scheme'];  
  
        if(!empty($url_pair['port']))  
        {  
            $this->port = $url_pair['port'];  
        }  
       
        //如果连接到远地主机成功，则发送请求  
        if($this->connect())  
        {  
            $this->sendRequest();  
        }  
        else  
        {     
            //如果连接失败，则休眠几秒，继续重连,比如出现网络不稳定时  
            echo str_repeat("  ", 2048);  
            echo $this->status.",  <font color='red'>网络异常，重新链接中....</font></br />";  
            $this->conn=null;  
            $this->init($this->url);  
        }  
          
        //如果响应头部存在重定向，则对重定向发送请求  
        if($this->redirect)  
        {  
            //默认设置只允许对当前域名下的主机的重定向，比如页面间的跳转  
            if(preg_match("#^http://".preg_quote($this->host)."#i",$this->redirect))  
            {  
                $this->referer=$this->host."/".parse_url($this->redirect)['path'];  
                $this->init($this->redirect);  
            }  
        }  
    }  
  
   private function connect()  
   {  
       $this->conn = fsockopen($this->host,$this->port,$errno,$errstr,$this->timeout);  
       if($this->conn)  
       {  
           $this->status = '连接成功';  
           return true;  
       }  
       else  
       {  
            switch($errno)  
            {  
                case -3:  
                        $this->status="创建socket链接失败";  
                case -4:  
                        $this->status="dns查询失败";  
                case -5:  
                        $this->status="链接被拒绝或超时";  
                default:  
                        $this->status="创建连接失败";  
            }  
            return false;  
       }  
   }  
     
   private function sendRequest()  
   {  
        //当请求的url中不存在路径时，默认设置为 /  这也是遵循HTTP协议的  
       if(empty($this->path))  
       {  
           $this->path="/";  
       }  
       //请求行: 请求方法 请求路径  HTTP版本信息  
       $this->cmd_line=$this->http_method." ".$this->url." ".$this->http_version."\r\n";  
  
       if(!empty($this->host))  
       {  
           $this->header .= "Host: ".$this->host."\r\n";  
       }  
  
       if(!empty($this->agent))  
       {  
           $this->header .="User-Agent: ".$this->agent."\r\n";  
       }  
  
       if(!empty($this->accept))  
       {  
           $this->header .= "Accept: ". $this->accept ."\r\n";  
       }  
         
       if(!empty($this->connection))  
       {  
            $this->header .= "Connection: ".$this->connection."\r\n";  
       }  
         
       if(!empty($this->gzip))  
       {  
           if ( function_exists("gzinflate") )  
           {  
                $this->header .= "Accept-encoding: gzip\r\n";  
            }  
            else  
            {  
                $this->status = "不支持压缩";  
            }  
       }  
       //第一次请求时,url指向当前页，后续请求时，referer总是指向上一个页面  
       if(empty($this->referer))  
       {  
           $this->header .= "Referer: ".$this->host."\r\n";  
           $this->referer = $this->url;  
       }  
       else  
       {  
            $this->header .= "Referer: ".$this->referer."\r\n";  
       }  
         
       //客户端可以接受的语言类型  
       if(!empty($this->accept_language))  
       {  
           $this->header .= "Accept-Language: ".$this->accept_language."\r\n";  
       }  
       //设置cookie，第一次请求时为空。第二次请求时，根据第一次请求完成时的头部SET-COOKIE信息来决定  
       if(!empty($this->cookie))  
       {  
           if(!is_array($this->cookie))  
           {  
               $this->header .="Cookie: ".$this->cookie;  
           }  
           else  
           {  
                //如果是数组就循环出每一项  
               if(count($this->cookie) >0)  
               {  
                   $cookie = "Cookie: ";  
                   foreach($this->cookie as $key => $val)  
                   {  
                       $cookie.=$key."=".urlencode($val).";";  
                   }  
                   //去掉最后的换行符  
                  $cookie = substr($cookie, 0, strlen($cookie)-1)."\r\n";  
               }  
               $this->header .= $cookie;  
           }  
       }  
         
        //当有主体信息提交时，需要设置content-type为application/x-www-form-urlencoded  
       if(!empty($this->post_content))  
       {  
           $this->header .= "Content-length: ".strlen($this->post_content)."\r\n";  
           if(!empty($this->submit_type))  
           {  
               $this->header .="Content-Type: ".$this->submit_type."\r\n";  
           }  
       }  
       //请求头部到这里完成  
       $this->header .="\r\n";  
        
       //echo $this->cmd_line.$this->header.$this->post_content; exit();  
       //发送请求  
        $len = strlen($this->cmd_line.$this->header.$this->post_content);  
        if($len != @fwrite($this->conn, $this->cmd_line.$this->header.$this->post_content,$len))  
        {  
            $this->status = "发送请求失败";  
            fclose($this->conn);  
            flush();  
            $this->cmd_line=null;  
            $this->header=null;  
            echo str_repeat("  ",2048);  
            echo $this->status."<font color='red'>尝试重置链接</font><br />";  
            $this->init($this->url);  
        }  
          
       //接受响应，每次读取一行内容，首先解析响应头  
       while($response_header = fgets($this->conn, 1024))  
       {  
           if(preg_match("#^HTTP/#",$response_header))  
            {  
                //匹配状态数字,200表示请求成功  
                if(preg_match("#^HTTP/[^\s]*\s(.*?)\s#",$response_header, $status))  
                {  
                        $this->response_num= $status[1];//返回代表数字的状态  
                }  
            }  
              
            // 判断是否需要重定向  
            if(preg_match("#^(Location:|URI:)#i",$response_header) && substr($this->response_num,0,1) == 3)  
            {  
                // 获取重定向地址  
                preg_match("#^(Location:|URI:)\s+(.*)#",trim($response_header),$matches);  
  
                //如果重定向字段不包含主机名，不是以以://开头的，则拼接王完整的请求地址，模式+主机+端口  
                if(!preg_match("#\:\/\/#",$matches[2]))  
                {  
                    // 补全主机名，这里限制只允许同一个域名下的重定向，也可以修改成允许重定向到其他域名  
                    $this->redirect = "http://".$this->host.":".$this->port;  
  
                    //添加路径  
                    if(!preg_match("|^/|",$matches[2]))  
                           $this->redirect .= "/".$matches[2];  
                    else  
                           $this->redirect .= $matches[2];  
                }  
                else  
                //包含完整的主机地址  
                $this->redirect = $matches[2];  
            }  
  
            //判断返回的数据的压缩格式  
              if (preg_match("#^Content-Encoding: gzip#", $response_header) )  
              {  
                    $this->is_gzip = true;  
              }  
              if(preg_match('#^Transfer-Encoding:\s*chunked#i', $response_header))  
              {  
                    $this->is_chunked = true;  
              }  
            
              //根据返回的头部信息判断主体信息长度  
              if(preg_match('#^Content-Length:\s*(\d+)#i', $response_header, $len))  
              {  
                  $this->response_body_length = $len[1];  
              }  
                
              //根据返回的头部信息获取COOKIE，用于下一次发送请求时设置COOKIE  
              if(preg_match('#^Set-Cookie:#i', $response_header))  
              {  
                  $items = explode(':', $response_header);  
                  $this->cookie = explode(';', $items[1])[0];  
              }  
            
  
            //解析完响应头部则跳出循环  
            if(preg_match("/^\r?\n$/", $response_header) )  
                break;  
  
            $this->response_header[]=$response_header;  
       }  
        /* echo "<pre>";  
        print_r($this->response_header); exit(); */  
        //如果请求成功且响应码为200  
        if($this->response_num==200)  
        {  
            if($this->is_chunked)  
            {  
                /* //读取chunk头部信息，获取chunk主体信息的长度 
                $chunk_size = (int)hexdec(trim(fgets($this->conn))); 
                 
                while(!feof($this->conn) && $chunk_size > 0)  
                {  
                    //读取chunk头部指定长度的信息 
                    $this->response_body .= fread( $this->conn, $chunk_size );  
                    fseek($this->conn, 2, SEEK_CUR); 
                    $next_line = trim(fgets($this->conn)); 
                    if($next_line === '0') 
                    { 
                        echo $next_line;exit(); 
                    } 
                    else 
                    { 
                        $chunk_size = (int)hexdec($next_line); 
                    } 
                     
               } */  
                    while(!feof($this->conn))  
                    {  
                        $this->response_body .= fread($this->conn, 1024);  
                    }  
                    if(preg_match_all("#\r\n#i", $this->response_body, $match))  
                    {  
                        $result=preg_split("#\r\n#i", $this->response_body, -1, PREG_SPLIT_NO_EMPTY );  
                        // echo "<pre>";  
                        // print_r($result);   
                        /* foreach($result as $v) 
                        { 
                            echo $v."<br /><hr />"; 
                        } 
                        echo "<hr />"; */  
                    /*  echo hexdec($result[0])."<br />"; 
                        echo mb_strlen($result[1])+mb_strlen($result[2])."<br />"; */  
                          
                        $len = count($result);  
                        $this->response_body='';  
                        for($i=1; $i<$len-1; $i++)  
                        {  
                            $this->response_body .= $result[$i];  
                        }  
                        //echo strlen($this->response_body); exit();  
                    }  
                    else  
                    {  
                        die("匹配结束符失败");  
                    }  
            }  
            else  
            {  
                $len=0;  
                //读取请求返回的主体信息  
                while($items = fread($this->conn, $this->response_body_length))  
                {  
                    $len = $len+strlen($items);  
                    $this->response_body = $items;  
                      
                    //当读取完请求的主体信息后跳出循环，不这样做，貌似会被阻塞！！！  
                    if($len >= $this->response_body_length)  
                    {  
                        break;  
                    }  
                }  
            }  
              
            if($this->is_gzip)  
            {  
                $this->response_body = @gzinflate(substr($this->response_body,10));  
                // $this->response_body = $this->gz_decode($this->response_body);  
            }  
            //echo $this->response_body; exit();  
            $this->getTrans($this->response_body);  
  
        }  
   }  
private function getTrans($string)  
{  
    if(preg_match('#<ul\s+class="dict-basic-ul">.*?</ul>#is', $string, $part))  
    {  
        if(preg_match_all('#<li>(.*?)</li>#is', $part[0], $match))  
        {  
            foreach($match[1] as $item)  
            {  
                if(preg_match('#<script[^>]+?>#is', $item))  
                {  
                    continue;  
                }  
                else  
                {  
                    echo strip_tags($item)."<br />";  
                }  
            }  
        }  
        else  
        {  
            //echo "匹配翻译失败";  
        }  
    }  
    else  
    {  
        //echo "匹配分组失败";  
    }  
}  
}  
//显式的刷新输出缓存  
ob_implicit_flush(true);  
//设置不使用超时  
set_time_limit(0);  
$url = "http://weixin.sogou.com/gzh?openid=oIWsFt_uej0Yv18l4EyZr5GIhEZI&ext=bC4jy94pB0RbZAicygt6PdeAuJNvL5438fCmZtLkKxnup9uIYk7GYfTLpn1oA-tJ";  
$http = new HttpWrap();  
$http->init($url);  
?>  