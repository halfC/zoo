<?php 
/**
 * 从网页中提取关键字
 * 
 * 能够轻松的从网页中提取关键字。
 * 
 */


$meta = get_meta_tags('http://www.phperzone.cn/');
$keywords = $meta['keywords'];
// Split keywords
$keywords = explode(',', $keywords );
// Trim them
$keywords = array_map( 'trim', $keywords );
// Remove empty values
$keywords = array_filter( $keywords );

print_r( $keywords );




?>