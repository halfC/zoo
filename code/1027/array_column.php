<?php
/**
 * 兼容低于PHP 5.5版本的array_column()函数,array_column是PHP 5.5新增函数,有时在低版本中也可能要用到,需要的朋友可以参考下
 * 
 */
if (! function_exists ( 'array_column' )) {
	function array_column($input, $columnKey, $indexKey = NULL) {
		$columnKeyIsNumber = (is_numeric ( $columnKey )) ? TRUE : FALSE;
		$indexKeyIsNull = (is_null ( $indexKey )) ? TRUE : FALSE;
		$indexKeyIsNumber = (is_numeric ( $indexKey )) ? TRUE : FALSE;
		$result = array ();
		foreach ( ( array ) $input as $key => $row ) {
			if ($columnKeyIsNumber) {
				$tmp = array_slice ( $row, $columnKey, 1 );
				$tmp = (is_array ( $tmp ) && ! empty ( $tmp )) ? current ( $tmp ) : NULL;
			} else {
				$tmp = isset ( $row [$columnKey] ) ? $row [$columnKey] : NULL;
			}
			if (! $indexKeyIsNull) {
				if ($indexKeyIsNumber) {
					$key = array_slice ( $row, $indexKey, 1 );
					$key = (is_array ( $key ) && ! empty ( $key )) ? current ( $key ) : NULL;
					$key = is_null ( $key ) ? 0 : $key;
				} else {
					$key = isset ( $row [$indexKey] ) ? $row [$indexKey] : 0;
				}
			}
			$result [$key] = $tmp;
		}
		return $result;
	}
}