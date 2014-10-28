<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<title>查询百度link?ulr=真实链接表单</title>
</head>

<body>
<?php
/*
    getrealurl 获取301、302重定向后的URL地址  by enenba.com
    @param str $url 查询
    $return str  定向后的url的真实url
 */
function getrealurl($url){
    $header = get_headers($url,1);
    if (strpos($header[0],'301') || strpos($header[0],'302')) {
        if(is_array($header['Location'])) {
            return $header['Location'][count($header['Location'])-1];
        }else{
            return $header['Location'];
        }
    }else {
        return $url;
    }
}
$input = '<form method="get" action=""><input type="text" name="url" id="url" style="width:800px;" /><input type="submit" value="提交" /></form><body></html>';

$url = isset($_GET['url'])?$_GET['url']:'';
if(empty($url)) exit($input);
$urlreal = getrealurl($url);
echo '真实的url为：'.$urlreal;
$urlreal = ltrim($urlreal,'http://');

$search = '/ebac5573358cc3c0659257bfcf54([0-9a-f]+)/i';
preg_match($search,$url,$r);
$url_encode = $r[1];  unset($r);

echo '<br/>密文部分为：'.$url_encode.'<br/>';

$urlreal_arr = str_split($urlreal);
$url_encode_arr = str_split($url_encode,2);

echo '<br />';
echo $input;
?>