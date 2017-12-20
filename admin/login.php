<?php

	header("Content-type:text/html;charset=utf-8");
	// 因为当前这个页面有两种功能
	// 如果是get请求的时候，就是一般的显示页面
	// 如果是post请求的话，则需要接收提交过来的用户名和密码
	// 如果用户是用post的方式提交过来的数据的话，则是用$_POST的方式来接收
		
		require '../functions.php'; // 引入 functions.php文件，就相当于将此文件下面的所有的代码都拿到当前文件里面来执行了，

	$message = '';
	if(!empty($_POST)){ // 如果$_POST里面有值的话，则返回一个false
	  // 说明当前是以post的方式提交过来的数据
	  $email = $_POST['email']; // 获取提交过来的邮箱
	  $password = $_POST['password']; // 获取提交过来的密码

	  // 如果用户名和密码都正确的话，才会进行跳转
	  // 应该先根据获取到的用户名，到数据库里面去查询当前提交过来的用户名是否存在
	  // //1. 连接数据库服务器
	  // 	$connect =	mysqli_connect('localhost','root','123456');
	  	// $connect = connect(DB_HOST,DB_USERS,DB_PASSWORD);
	  // //2. 选择要连接的数据库
	  // 		mysqli_select_db($connect,'baixiu');
	  // //3. 设置字符集  防止乱码
	  // 	mysqli_set_charset($connect,'utf8');
	  // 4. 查询当前用户名是否存
	  //SELECT * FROM users WHERE email = "admin@baixiu.com"
		 // $result = mysqli_query($connect,'SELECT * FROM users WHERE email = "'.$email.'"');
	  // $result是一个查询之后的结果集，有可能没有数据或是一条数据或是多条数据
//	  var_dump($result);
	  	$sql = 'SELECT * FROM users WHERE email = "'.$email.'"'; //查询邮箱的sql语句
	    $rows=	query($sql); //在数据库中查询数据

	    // print_r($rows); //到这一步为止，会打印输出一个二维数据，第一项数据就是查询到的结果
	    // exit;
		  //5. 获取结果集中的第一条数据
		  // $row = mysqli_fetch_assoc($result);
//		  var_dump($row);
			if($rows[0]){  // if小括号里面如果不是boolean类型不是关系 表达式不是逻辑 表达式的时候，if小括号里面会默认的调用 Boolean()函数   ''   0  NaN  null  undefined  false
				//6. 再次判断密码是否正确
				if($rows[0]['password']==$password){
//					echo '登陆成功...';

//				$message = '登陆成功';
			// 在这个地方进行一个session的设置，需要 跟随浏览器的cookie返回到客户端，当第二次向服务器请求的时候
			//会携带当前域名 下面的所有的cookie信息  PHP当中设置的sessin默认是PHPSESSID

			session_start();//先启用一下sesson
			$_SESSION['user_info'] = $rows;

				header('location:/admin');  //路转到主页面

				exit;
				}else {
//					echo '密码错误...';

			$message = '密码错误...';
				}
			}else {
//				echo '用户名不存在...';
			$message = '用户名不存在';
			}

//	  exit;
	}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <?php include './inc/style.php'?>
</head>
<body>
  <div class="login">
    <form action="./login.php" method="post" class="login-wrap">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(!empty($message)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $message?>
      </div>
      <?php }?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" name="email" value="admin@baixiu.com" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="密码" value="123456">
      </div>
      <input type="submit" value="登陆" class="btn btn-primary btn-block">

    </form>
  </div>
</body>
</html>
