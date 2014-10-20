<?php 
/**
 * 如果你的网站上有多种语言，那么可以使用这段代码作为默认的语言来检测浏览器语言。该段代码将返回浏览器客户端使用的初始语言。
 * 
 */

function get_client_language($availableLanguages, $default='en'){
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$langs=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);

		foreach ($langs as $value){
			$choice=substr($value,0,2);
			if(in_array($choice, $availableLanguages)){
				return $choice;
			}
		}
	}
	return $default;
}


?>