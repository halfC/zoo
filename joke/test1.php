<?php
$cookiefile = './cookiefile';//存放的路径
// $cookie = "uid";//意思只能发送一个  键  或者 一个值??
$curlObj = curl_init();
// $url = "http://weixin.sogou.com/websearch/art.jsp?sg=CBf80b2xkgawIob58M2TIT4Ei4lrAbB4umO7oBrRrjU5aVLxVDpsWeGMkCNh6Dg3dXcwPeXYzp-f_JABfGkRxfb5N1inCVWsxKi2BpCETT4.&amp;url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8wgimd2ixoMyaZzVpqsMwNmtFJmAPOq06MxRskN4yUZCRebySLAvfHXdeTwRLdLI3ZKcC-_rg4yMaMRe0cKEWrpqawd1gJaD2jBYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk";
// $url = "http://weixin.sogou.com/";//1
$url = "http://weixin.sogou.com/weixin?type=2&query=头发&ie=utf8"; //2 这个地址也不能随便写啊  难道js还可以控制这个位置??
$url = "http://weixin.sogou.com/websearch/art.jsp?sg=CBf80b2xkgYkPAW9OiC51QQmHs4otsc81GgZkIcqH63D5kER81i7HBTiGPCLCqJpEBiKSlh8hV1FWvVFjoplU4iAFl_yKAV8xKi2BpCETT4.&amp;url=p0OVDH8R4SHyUySb8E88hkJm8GF_McJfBfynRTbN8whsmYQaon9iar_AaYb6Yg5OUW-zPEc5g3w6Jyc0lAQKJ1LvkvBrfA0sgzc9atWlBvQ5V8xSrtqcrpu5LtoIppRpP_PvE6ZVpctYy-5x5In7jJFmExjqCxhpkyjFvwP6PuGcQ64lGQ2ZDMuqxplQrsbk";//3 得到的链接
// $url = "http://test.com/setCookie.php";
// $url = "http://test.com/test3.php";//向tet3发送一下 cookie 看看是否能够得到 curl发送的
curl_setopt($curlObj, CURLOPT_URL, $url);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curlObj, CURLOPT_REFERER,"http://weixin.sogou.com/weixin?type=2&query=头发&ie=utf8");//可以设置来源 没错 $_SERVER['HTTP_REFERER']
// curl_setopt($curlObj,CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; InfoPath.3'); //可以设置 useragent  
curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, 1); //支持重定向功能  可以递归的去抓取页面的代码 这个位置不对吗???
date_default_timezone_set("PRC");
// curl_setopt($curlObj, CURLOPT_COOKIESESSION, true); //这个东西不能有
// 
// if(//$cookiefile存在 并且里面的内容里有 对应域名的 key=>value 那么我就是读取){
// }else{
// 		//设置 key=>value 以文件的形式
// }
// curl_setopt($curlObj, CURLOPT_COOKIE, "SNUID=6C89B83342446669B5CC6325421C0847");
curl_setopt($curlObj, CURLOPT_COOKIEJAR, $cookiefile);//保存从服务器获得的cookie数据 文件形式(需要你规定cookie的路径)  我总不能手动去开关这个东西吧
curl_setopt($curlObj, CURLOPT_COOKIEFILE, $cookiefile);// 这个难道是要去判断吗 ?? 如果  发送给服务器这个 cookie 当中的信息 正常状况应该输出cookie的内容
$outPut = curl_exec($curlObj);
file_put_contents("./really.txt", $outPut);
echo $outPut;
curl_close($curlObj);
// /*-----保存COOKIE-----*/
// $url = 'www.xxx.com'; //url地址
// $post = "id=user&pwd=123456"; //POST数据
// $cookie = tempnam('./','cookie'); //cookie临时文件
// $ch = curl_init($url); //初始化
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回获取的输出文本流
// curl_setopt($ch,CURLOPT_POSTFIELDS,$post); //发送POST数据
// curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie); //保存获得的cookie
// curl_exec($ch); //执行curl
// curl_close($ch); //关闭curl
// /*-----使用COOKIE-----*/
// curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie)
// 
// 
// weixin.sogou.com	FALSE	/	FALSE	1452914253	ABTEST	0|1450322253|v1
// .sogou.com	TRUE	/	FALSE	1451186253	SNUID	1F7F95CACBC9ECC4BD0CB86ECC770449
// .sogou.com	TRUE	/	FALSE	1481858253	IPLOC	CN1100
// .weixin.sogou.com	TRUE	/	FALSE	2081042253	SUID	D4B45E01E518920A000000005672294D
// 
// weixin.sogou.com	FALSE	/	FALSE	1452930955	ABTEST	4|1450338955|v1
// .sogou.com	TRUE	/	FALSE	1481874955	IPLOC	CN1100
// .weixin.sogou.com	TRUE	/	FALSE	2081058955	SUID	E1D0830E260C930A0000000056726A8B
// 
// 
// 
// weixin.sogou.com	FALSE	/	FALSE	1452931267	ABTEST	0|1450339267|v1
// .sogou.com	TRUE	/	FALSE	1481875267	IPLOC	CN1100
// .weixin.sogou.com	TRUE	/	FALSE	2081059267	SUID	E1D0830E260C930A0000000056726BC3