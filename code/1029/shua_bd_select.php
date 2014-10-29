<?php
 $url1="http://www.baidu.com/s?bs=www.naligouwu.com&f=8&rsv_bp=1&wd=%CD%F8%C9%CF%B9%BA%CE%EF%CD%F8%D5%BE%B4%F3%C8%AB&inputT=".rand(500,3000)."";
 $url2="http://www.baidu.com/s?bs=%C4%C4%C0%EF%B9%BA%CE%EF&f=8&rsv_bp=1&wd=%B9%BA%CE%EF&inputT=".rand(500,3000)."";
 $url3="http://www.baidu.com/s?bs=www.naligouwu.com&f=8&rsv_bp=1&wd=naligouwu&inputT=".rand(500,3000)."";
 $url4="http://www.baidu.com/s?bs=%C4%C4%C0%EF%B9%BA%CE%EF&f=8&rsv_bp=1&wd=naligouwu&inputT=".rand(500,3000)."";
function parseUrl($url)
{
    $parse_url = parse_url($url);
    return (!empty($parse_url['scheme']) && !empty($parse_url['host']));
}
//获取url的内容
function getUrlContent($url)
{
    $content = '';
    if(!parseUrl($url))
    {
        $content = @file_get_contents($url);
    }
    else
    {
        if(function_exists('curl_init'))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
            curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($ch);
            curl_close($ch);
        }
        else
        {
            $content = @file_get_contents($url);
        }
    }

    return $content;
}
 getUrlContent($url1);
 getUrlContent($url2);
 getUrlContent($url3);
 getUrlContent($url4);
 echo "成功"; 
