<?php
/**
 * 中文转数字
 * 
 * @param String $var 需要解析的中文数
 * @param Int $start 初始值
 * @return int
 */
function CnToInt($var, $start = 0) {
	if (is_numeric ( $var )) {
		return $var;
	}
	if (intval ( $var ) === 0) {
		$splits = array (
				'亿' => 100000000,
				'万' => 10000 
		);
		$chars = array (
				'万' => 10000,
				'千' => 1000,
				'百' => 100,
				'十' => 10,
				'一' => 1,
				'零' => 0 
		);
		$Ints = array (
				'零' => 0,
				'一' => 1,
				'二' => 2,
				'三' => 3,
				'四' => 4,
				'五' => 5,
				'六' => 6,
				'七' => 7,
				'八' => 8,
				'九' => 9,
				'十' => 10 
		);
		$var = str_replace ( '零', "", $var );
		foreach ( $splits as $key => $step ) {
			if (strpos ( $var, $key )) {
				$strs = explode ( $key, $var );
				$start += CnToInt ( array_shift ( $strs ) ) * $step;
				$var = join ( '', $strs );
			}
		}
		foreach ( $chars as $key => $step ) {
			if (strpos ( $var, $key ) !== FALSE) {
				$vs = explode ( $key, $var );
				if ($vs [0] === "") {
					$vs [0] = '一';
				}
				$start += $Ints [array_shift ( $vs )] * $step;
				$var = join ( '', $vs );
			} elseif (mb_strlen ( $var, 'utf-8' ) === 1) {
				$start += $Ints [$var];
				$var = '';
				break;
			}
		}
		return $start;
	} else {
		return intval ( $var );
	}
}