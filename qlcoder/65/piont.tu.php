<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>Document</title>
  <style type="text/css">
	div{width: 2px; height: 2px;background-color: #ccc;}
  </style>
 </head>
 <body>
  <?php 
$c = file_get_contents('point.txt');
$arr = explode("\n", $c);
echo "<style>";
foreach ($arr as $key => $value) {
	//if($key <100){
		if($value){
			$v = explode(" ", $value);
			//print_r($v);
			// echo $v[1];
			echo ".class$key { position:absolute; left:$v[0]px; top:".trim($v[1])."px;} ";
		}
	//}
	
	
	
}

echo "</style>";

foreach ($arr as $key => $value) {
	// $v = explode(" ", $value);
	echo "<div class='class$key'>0</div>";
}

 ?>
 </body>
</html>
