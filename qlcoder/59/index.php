<?php
//phpinfo();
//echo 'zoo';
//$a = intval($_GET['a']);
//$b = intval($_GET['b']);
//echo $a * $b;
$arr = array();
$file = "./nick.txt";
if(file_exists($file)){
	$arr = file_get_contents($file);
	$arr = json_decode($arr,1);
}
print_r($arr);
$nick = trim(htmlspecialchars($_GET['registerusername']));
if(file_exists($file) && in_array($nick,$arr)){
	echo 'already used';
}else{
	if($arr){
		$arr = array_push($arr,$nick);
	}else{
		$arr[] = $nick;
	}
	echo 'register success';
	file_put_contents($file,json_encode($arr));

}
