<?php 
/**
 * 简单的文本加密
 * 
 * 
 */
class SimpleCrypt{
	/**
	 * 密钥
	 * 越复杂越好，但字符串长度会整加
	 * @var String
	 */
	private static $key = 'jdkhafvxdicstuyi';
	/**
	 * 用于生成随机字符串
	 * @var array
	 */
	private static $word = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','v','u','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','V','U','W','X','Y','Z');
	 
	/**
	 * 加密算法
	 * @param $str 需要加密的字符串
	 * @return String 加密后的字符串
	 */
	public static function encode($str){
		 
		#首次加密
		$str = base64_encode($str);
		#计算字符串长度
		$str_num = mb_strlen($str);
		#取一半长度
		$half = ceil($str_num / 2);
		#获取前半和后半字符串
		$head_str = substr($str , 0 , $half);
		$end_str = substr($str , $half , $str_num);
		#计算前半和后半加密次数
		$head_loop_num = $half < 50 ? ceil($half / 5) : ceil($half / 10);
		$end_loop_num = $str_num > 200 ? ceil($str_num / 10) : ceil($str_num /8);
		#生成随机字符串
		$head_rand_loop_num = ($head_loop_num * 10) - $head_loop_num;
		$end_rand_loop_num = ($end_loop_num + $head_loop_num) * 10 - $end_loop_num;
		$head_rand_str = null;
		$end_rand_str = null;
		#前半部分随机字符串
		for($i = 0 ; $i < $head_rand_loop_num ; $i++){
			$head_rand_str .= self::$word[rand(0 , 51) ];
		}
		#后半部分随机字符串
		for($i = 0 ; $i < $end_rand_loop_num ; $i++){
			$end_rand_str .= self::$word[rand(0 , 51)];
		}
		#前半部分内容加密
		for($i = 0 ; $i < $head_loop_num ; $i++){
			$head_str = base64_encode($head_str);
		}
		#撕毁变量
		unset($i);
		#后半部分内容加密
		for($i = 0 ; $i < $end_loop_num ; $i++){
			$end_str = base64_encode($end_str);
		}
		#合并
		$implode_str = $head_rand_str . $head_str . self::$key . base64_encode($head_loop_num) . self::$key . base64_encode($end_loop_num) . self::$key . $end_str . $end_rand_str;
		 
		$result = base64_encode($implode_str);
		#以后继续，先返回
		return $result;
	}
	 
	/**
	 * 解密算法
	 * @param $str 需解密的字符串
	 * @return String 解密后字符串
	 */
	public static function decode($str){
		 
		#获取加密信息
		$get = explode(self::$key , base64_decode($str));
		$head_str = $get[0];
		$end_str = $get[3];
		$head_loop_num = base64_decode($get[1]);
		$end_loop_num = base64_decode($get[2]);
		#去除随机字符串的算法
		$head_rand_num = ($head_loop_num * 10) - $head_loop_num;
		$end_rand_num = ($end_loop_num + $head_loop_num) * 10 - $end_loop_num;
		#去除随机字符串，获取原文
		$head_str = substr($head_str , $head_rand_num , strlen($head_str));
		$end_str = substr($end_str , 0 , strlen($end_str) - $end_rand_num);
		#解密前半部分
		for($i = 0 ; $i < $head_loop_num ; $i++){
			$head_str = base64_decode($head_str);
		}
		#撕毁变量
		unset($i);
		#解密后半部分
		for($i = 0 ; $i < $end_loop_num ; $i++){
			$end_str = base64_decode($end_str);
		}
		#合并并解密
		$result = base64_decode($head_str . $end_str);
		 
		return $result;
	}
}



