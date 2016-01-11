<?php
	//引入config文件
	include_once('./config.php');
	


	//指定要操作的表的名称
	$table = 'jokes';



	//制定需要操作的数据数组
	$data['name'] = '掌上';


	//执行添加操作 返回1 插入成功
	$r = $db->insert($table,$data);

	//add test