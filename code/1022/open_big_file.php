<?php
/**
 * 介绍几个打开大文件的方法
 * 
 */
// Method 1 耗时 116.9613 (s) 不可取
ini_set ( 'memory_limit', '-1' );
$file = 'access.log';
$data = file ( $file );
$line = $data [count ( $data ) - 1];
echo $line;

// --------------------------------------------------------------------------------------

// Method 1 耗时 0.0034 (s) 需要调用linux命令
$file = 'access.log';
$file = escapeshellarg ( $file ); // 对命令行参数进行安全转义
$line = `tail -n 1 $file`;
echo $line;

// --------------------------------------------------------------------------------------

// 直接使用PHP的 fseek 来进行文件操作

// 耗时 0.0095 (s)
$fp = fopen ( $file, "r" );
$line = 10;
$pos = - 2;
$t = " ";
$data = "";
while ( $line > 0 ) {
	while ( $t != "\n" ) {
		fseek ( $fp, $pos, SEEK_END );
		$t = fgetc ( $fp );
		$pos --;
	}
	$t = " ";
	$data .= fgets ( $fp );
	$line --;
}
fclose ( $fp );
echo $data


//--------------------------------------------------------------------------------------


//耗时 0.0009(s)。

$fp = fopen ( $file, "r" );
$num = 10;
$chunk = 4096;
$fs = sprintf ( "%u", filesize ( $file ) );
$max = (intval ( $fs ) == PHP_INT_MAX) ? PHP_INT_MAX : filesize ( $file );
for($len = 0; $len < $max; $len += $chunk) {
	$seekSize = ($max - $len > $chunk) ? $chunk : $max - $len;
	fseek ( $fp, ($len + $seekSize) * - 1, SEEK_END );
	$readData = fread ( $fp, $seekSize ) . $readData;
	if (substr_count ( $readData, "\n" ) >= $num + 1) {
		preg_match ( "!(.*?\n){" . ($num) . "}$!", $readData, $match );
		$data = $match [0];
		break;
	}
}
fclose ( $fp );
echo $data;

// --------------------------------------------------------------------------------------

// 耗时 0.0003(s)
function tail($fp, $n, $base = 5) {
	assert ( $n > 0 );
	$pos = $n + 1;
	$lines = array ();
	while ( count ( $lines ) <= $n ) {
		try {
			fseek ( $fp, - $pos, SEEK_END );
		} catch ( Exception $e ) {
			fseek ( 0 );
			break;
		}
		$pos *= $base;
		while ( ! feof ( $fp ) ) {
			array_unshift ( $lines, fgets ( $fp ) );
		}
	}
	
	return array_slice ( $lines, 0, $n );
}

var_dump ( tail ( fopen ( "access.log", "r+" ), 10 ) );



