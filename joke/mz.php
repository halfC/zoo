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
    //���峬ʱʱ��  
    public $timeout=2;  
      
    //��������״̬  
    public $status='';  
  
    //������  
    public $host;  
      
    //�˿ں�  
    public $port=80;  
      
    //��һ������ʱ��������������IP  
    private $ip;  
      
    //������Դ  
    private $conn;  
      
    //���ӵĵ�ַ  
    private $url;  
      
    //����URL�е�·��  
    private $path;  
      
    //URL�а�����ģʽ������FTP,HTTPS  
    private $scheme;  
  
    //����ʽ������GET,POST,PUT  
    public $http_method='GET';  
      
    //HTTP�İ汾��Ϣ  
    public $http_version="HTTP/1.1";  
      
    //���������Ϣ  
    public $agent="Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0";  
      
    //����ɽ��յ�MIME��Ϣ  
    public $accept="image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*";  
      
    //ѹ����ʽ  
    public $gzip="gzip";  
      
    //�ϼ������������ӵ�ַ  
    public $referer;  
      
    //����COOKIE  
    public $cookie;  
      
    //�ύ����  
    public $submit_type="application/x-www-form-urlencoded";  
      
    //�ɽ��յ���������,q��ʾ���ȼ�,����zh��0.8  
    public $accept_language="zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3";  
      
    //���ֳ�����  
    public $connection="close";  
  
    //HTTP������  
    private $cmd_line;  
      
    //HTP����ͷ��  
    private $header;  
      
    //HTTP�����������������Ϣ  
    public $post_content;  
  
      
      
    //�ض����ַ  
    private $redirect;  
      
    //�Ƿ�֧��GZIPѹ��  
    private $is_gzip;  
      
    //���䷽ʽ������Կ����  
    private $is_chunked;  
      
    //chunk��ĳ���  
    private $chunk_length=0;  
      
    //HTTP��Ӧ״̬��,����200,404��  
    public $response_num;  
      
    //HTTP��Ӧͷ����Ϣ  
    public $response_header;  
      
    //HTTP��Ӧ����  
    public $response_body;  
      
    //HTTP��Ӧ������Ϣ�ĳ���  
    public $response_body_length;  
   
    //��Ӧ�ı�����Ϣ  
    public $encoding;  
      
  
   public  function init($url)  
    {  
        $this->url=$url;  
        //����url��Ϣ  
        $url_pair = parse_url($url);  
        //����������  
        $this->host = $url_pair['host'];  
        //����url�а�����·����Ϣ  
        $this->path = $url_pair['path'];  
        //����ʹ�õ�ģʽ��Ϣ  
        $this->scheme = $url_pair['scheme'];  
  
        if(!empty($url_pair['port']))  
        {  
            $this->port = $url_pair['port'];  
        }  
       
        //������ӵ�Զ�������ɹ�����������  
        if($this->connect())  
        {  
            $this->sendRequest();  
        }  
        else  
        {     
            //�������ʧ�ܣ������߼��룬��������,����������粻�ȶ�ʱ  
            echo str_repeat("  ", 2048);  
            echo $this->status.",  <font color='red'>�����쳣������������....</font></br />";  
            $this->conn=null;  
            $this->init($this->url);  
        }  
          
        //�����Ӧͷ�������ض�������ض���������  
        if($this->redirect)  
        {  
            //Ĭ������ֻ����Ե�ǰ�����µ��������ض��򣬱���ҳ������ת  
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
           $this->status = '���ӳɹ�';  
           return true;  
       }  
       else  
       {  
            switch($errno)  
            {  
                case -3:  
                        $this->status="����socket����ʧ��";  
                case -4:  
                        $this->status="dns��ѯʧ��";  
                case -5:  
                        $this->status="���ӱ��ܾ���ʱ";  
                default:  
                        $this->status="��������ʧ��";  
            }  
            return false;  
       }  
   }  
     
   private function sendRequest()  
   {  
        //�������url�в�����·��ʱ��Ĭ������Ϊ /  ��Ҳ����ѭHTTPЭ���  
       if(empty($this->path))  
       {  
           $this->path="/";  
       }  
       //������: ���󷽷� ����·��  HTTP�汾��Ϣ  
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
                $this->status = "��֧��ѹ��";  
            }  
       }  
       //��һ������ʱ,urlָ��ǰҳ����������ʱ��referer����ָ����һ��ҳ��  
       if(empty($this->referer))  
       {  
           $this->header .= "Referer: ".$this->host."\r\n";  
           $this->referer = $this->url;  
       }  
       else  
       {  
            $this->header .= "Referer: ".$this->referer."\r\n";  
       }  
         
       //�ͻ��˿��Խ��ܵ���������  
       if(!empty($this->accept_language))  
       {  
           $this->header .= "Accept-Language: ".$this->accept_language."\r\n";  
       }  
       //����cookie����һ������ʱΪ�ա��ڶ�������ʱ�����ݵ�һ���������ʱ��ͷ��SET-COOKIE��Ϣ������  
       if(!empty($this->cookie))  
       {  
           if(!is_array($this->cookie))  
           {  
               $this->header .="Cookie: ".$this->cookie;  
           }  
           else  
           {  
                //����������ѭ����ÿһ��  
               if(count($this->cookie) >0)  
               {  
                   $cookie = "Cookie: ";  
                   foreach($this->cookie as $key => $val)  
                   {  
                       $cookie.=$key."=".urlencode($val).";";  
                   }  
                   //ȥ�����Ļ��з�  
                  $cookie = substr($cookie, 0, strlen($cookie)-1)."\r\n";  
               }  
               $this->header .= $cookie;  
           }  
       }  
         
        //����������Ϣ�ύʱ����Ҫ����content-typeΪapplication/x-www-form-urlencoded  
       if(!empty($this->post_content))  
       {  
           $this->header .= "Content-length: ".strlen($this->post_content)."\r\n";  
           if(!empty($this->submit_type))  
           {  
               $this->header .="Content-Type: ".$this->submit_type."\r\n";  
           }  
       }  
       //����ͷ�����������  
       $this->header .="\r\n";  
        
       //echo $this->cmd_line.$this->header.$this->post_content; exit();  
       //��������  
        $len = strlen($this->cmd_line.$this->header.$this->post_content);  
        if($len != @fwrite($this->conn, $this->cmd_line.$this->header.$this->post_content,$len))  
        {  
            $this->status = "��������ʧ��";  
            fclose($this->conn);  
            flush();  
            $this->cmd_line=null;  
            $this->header=null;  
            echo str_repeat("  ",2048);  
            echo $this->status."<font color='red'>������������</font><br />";  
            $this->init($this->url);  
        }  
          
       //������Ӧ��ÿ�ζ�ȡһ�����ݣ����Ƚ�����Ӧͷ  
       while($response_header = fgets($this->conn, 1024))  
       {  
           if(preg_match("#^HTTP/#",$response_header))  
            {  
                //ƥ��״̬����,200��ʾ����ɹ�  
                if(preg_match("#^HTTP/[^\s]*\s(.*?)\s#",$response_header, $status))  
                {  
                        $this->response_num= $status[1];//���ش������ֵ�״̬  
                }  
            }  
              
            // �ж��Ƿ���Ҫ�ض���  
            if(preg_match("#^(Location:|URI:)#i",$response_header) && substr($this->response_num,0,1) == 3)  
            {  
                // ��ȡ�ض����ַ  
                preg_match("#^(Location:|URI:)\s+(.*)#",trim($response_header),$matches);  
  
                //����ض����ֶβ���������������������://��ͷ�ģ���ƴ���������������ַ��ģʽ+����+�˿�  
                if(!preg_match("#\:\/\/#",$matches[2]))  
                {  
                    // ��ȫ����������������ֻ����ͬһ�������µ��ض���Ҳ�����޸ĳ������ض�����������  
                    $this->redirect = "http://".$this->host.":".$this->port;  
  
                    //���·��  
                    if(!preg_match("|^/|",$matches[2]))  
                           $this->redirect .= "/".$matches[2];  
                    else  
                           $this->redirect .= $matches[2];  
                }  
                else  
                //����������������ַ  
                $this->redirect = $matches[2];  
            }  
  
            //�жϷ��ص����ݵ�ѹ����ʽ  
              if (preg_match("#^Content-Encoding: gzip#", $response_header) )  
              {  
                    $this->is_gzip = true;  
              }  
              if(preg_match('#^Transfer-Encoding:\s*chunked#i', $response_header))  
              {  
                    $this->is_chunked = true;  
              }  
            
              //���ݷ��ص�ͷ����Ϣ�ж�������Ϣ����  
              if(preg_match('#^Content-Length:\s*(\d+)#i', $response_header, $len))  
              {  
                  $this->response_body_length = $len[1];  
              }  
                
              //���ݷ��ص�ͷ����Ϣ��ȡCOOKIE��������һ�η�������ʱ����COOKIE  
              if(preg_match('#^Set-Cookie:#i', $response_header))  
              {  
                  $items = explode(':', $response_header);  
                  $this->cookie = explode(';', $items[1])[0];  
              }  
            
  
            //��������Ӧͷ��������ѭ��  
            if(preg_match("/^\r?\n$/", $response_header) )  
                break;  
  
            $this->response_header[]=$response_header;  
       }  
        /* echo "<pre>";  
        print_r($this->response_header); exit(); */  
        //�������ɹ�����Ӧ��Ϊ200  
        if($this->response_num==200)  
        {  
            if($this->is_chunked)  
            {  
                /* //��ȡchunkͷ����Ϣ����ȡchunk������Ϣ�ĳ��� 
                $chunk_size = (int)hexdec(trim(fgets($this->conn))); 
                 
                while(!feof($this->conn) && $chunk_size > 0)  
                {  
                    //��ȡchunkͷ��ָ�����ȵ���Ϣ 
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
                        die("ƥ�������ʧ��");  
                    }  
            }  
            else  
            {  
                $len=0;  
                //��ȡ���󷵻ص�������Ϣ  
                while($items = fread($this->conn, $this->response_body_length))  
                {  
                    $len = $len+strlen($items);  
                    $this->response_body = $items;  
                      
                    //����ȡ�������������Ϣ������ѭ��������������ò�ƻᱻ����������  
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
            //echo "ƥ�䷭��ʧ��";  
        }  
    }  
    else  
    {  
        //echo "ƥ�����ʧ��";  
    }  
}  
}  
//��ʽ��ˢ���������  
ob_implicit_flush(true);  
//���ò�ʹ�ó�ʱ  
set_time_limit(0);  
$url = "http://weixin.sogou.com/gzh?openid=oIWsFt_uej0Yv18l4EyZr5GIhEZI&ext=bC4jy94pB0RbZAicygt6PdeAuJNvL5438fCmZtLkKxnup9uIYk7GYfTLpn1oA-tJ";  
$http = new HttpWrap();  
$http->init($url);  
?>  