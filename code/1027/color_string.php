<?php
/**
 * 生成彩虹字符串
 */
function color_txt($str) {
	$len = mb_strlen ( $str );
	$colorTxt = '';
	for($i = 0; $i < $len; $i ++) {
		$colorTxt .= '<span style="color:' . rand_color () . '">' . mb_substr ( $str, $i, 1, 'utf-8' ) . '</span>';
	}
	return $colorTxt;
}
function rand_color() {
	return '#' . sprintf ( "%02X", mt_rand ( 0, 255 ) ) . sprintf ( "%02X", mt_rand ( 0, 255 ) ) . sprintf ( "%02X", mt_rand ( 0, 255 ) );
}