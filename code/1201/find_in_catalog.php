<?php
/**
 * 在目录中查找指定字符串，
 * 
 * 可用于网站木马查找
 * 
 */
function parAllFiles($d) {
	$dh = dir ( $d );
	while ( $filename = $dh->read () ) {
		if ($filename == '.' || $filename == '..')
			continue;
		$tfile = $d . '/' . $filename;
		if (is_dir ( $tfile )) {
			// echo "检查到： $tfile
			parAllFiles ( $tfile );
		} else {
			if (! ereg ( "\.html", $tfile ))
				continue;
			$bd = file_get_contents ( $tfile );
			if (eregi ( "VBScript", $bd )) {
				echo "$tfile \r\n";
			}
		}
	}
}
parAllFiles ( dirname ( __FILE__ ) );

?>