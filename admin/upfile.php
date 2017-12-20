<?php
		
		require '../functions.php';

		checkLogin();
		//接收用户传递过来的文件   图片 
		//$_FILES 是用来接收文件的
	// print_r($_FILES); //打印接收到的图片
			
		//要把接收过来的图片文件存在指定的文件夹里面
		//	php还有一个特点，就是会将文件接收到之后存到一个临时的文件夹里面
		// if(file_exists('../uploads')){
		// 		//直接存在此文件夹里面
		// }else {
		// 	mkdir('../uploads');//如果没有的话，则去创建一个图片文件夹
		// }

			if(!file_exists('../uploads')){
				mkdir('../uploads');
			}

		//为了避免上传过来的图片名称冲突，需要将上传过来的所有的图片进行一个统一的名字，而且 名字也不能重复,可以根据时间戳来命名
			
		$fileName = time();//获取时间戳

		// 获取后缀名   explode()是将字符串以什么分割符切割成数组,类似于js中的split
		$ext = explode('.',$_FILES['avatar']['name']);

		//拼接存储的路径
		$path = '/uploads/'.$fileName.'.'.$ext[1];
		// print_r($ext);
		move_uploaded_file($_FILES['avatar']['tmp_name'],'..'.$path);


		// var str = 'a.b.c.d';
		// var arr = str.split('.');

		// 把路径存在数据库一份，还要返回给浏览器
		// 就是相当给当前的用户修改或是更新一份信息

		$user_id = $_SESSION['user_info'][0]['id'];
		// $arr = array('avatar'=>$path);
	  // update('users',$arr,$user_id);
	  update('users',array('avatar'=>$path),$user_id);

	  //还要将保存到数据库中的路径返回给浏览器

	  echo $path;

?>