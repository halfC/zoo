<?php
	//引入操作类 一般不需要修改了
	include_once('./BaseSql.class.php');

	//以下是数据库的配置信息 轻根据本地状况配置

	$config['host_name'] = '10.10.0.78';

	$config['user'] = 'admin';

	$config['password'] = 'admin';

	$config['dbname'] = 'ata';

	//实例化数据库操作类
	$db = new BaseSql($config);