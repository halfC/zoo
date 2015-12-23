<?php 

require_once "Imghash.class.php";
$instance = ImgHash::getInstance();
// foreach ($variable as $key => $value) {
// 	# code...
// }
$result = $instance->checkIsSimilarImg('2/1.png', 'ok2/1.jpg');

var_dump($result);



/***********************
第二种实现办法：用readdir()函数
************************/
// function listDir($dir)
// {
// 	if(is_dir($dir))
//    	{
//      	if ($dh = opendir($dir)) 
// 		{
//         	while (($file = readdir($dh)) !== false)
// 			{
//      			if((is_dir($dir."/".$file)) && $file!="." && $file!="..")
// 				{
//      				return $file
//      				listDir($dir."/".$file."/");
//      			}
// 				else
// 				{
//          			if($file!="." && $file!="..")
// 					{
//          				return $file;
//       				}
//      			}
//         	}
//         	closedir($dh);
//      	}
//    	}
// }