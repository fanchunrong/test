<?php
	// 连接数据库的一些信息是经常用到的，而不是经常的变量
	// 可以使用一些常量  定义常量 使用define

	//1. 定义数据库服务器地址
		define('DB_HOST','127.0.0.1');

		//2. 定义数据库用户名
		define('DB_USERS','root');

		//3.定义数据库的连接密码
		define('DB_PASSWORD','123456');

		//4.定义一个数据库的名称
		define('DB_NAME','baixiu');

		//5. 定义数据库的编码格式 
		define('DB_CHARSET','utf8');
?>