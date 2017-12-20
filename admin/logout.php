<?php
	
	// 怎么样才算是真正的退出当前的登陆  清除掉sessionid的信息
		
	//用session之前先开启 session
	session_start();
	unset($_SESSION['user_info']);
	
	//退出之后跳转到登陆页面
	header('location:/admin/login.php');
	
?>