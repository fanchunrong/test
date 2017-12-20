<?php

		require '../config.php';    //注意这个地方的路径
		// include如果当前引入文件出错的话，会继续执行后面的代码
		// require如果当前引入出错的话，就不执行了
	


		session_start();  //使用session的时候一定要先开启
		// 在此php文件当中，定义公用的函数

		/**
		 * 1. 封装了一个连接数据库的函数
		 * @return [type] [description]
		 */
		function connect(){
				//1. 连接数据库服务器
			$connect=	mysqli_connect(DB_HOST,DB_USERS,DB_PASSWORD);
				
			// var_dump($connect);
			// exit;
			if(!$connect) {
					// echo '连接数据库失败...';
					// return
				die('连接数据库失败...');
				// 相当于是echo  '连接数据库失败...' + return 
			}

			 //2. 选择要连接的数据库
	  		mysqli_select_db($connect,DB_NAME);
	 		 //3. 设置字符集  防止乱码
	  		mysqli_set_charset($connect,DB_CHARSET);
				return $connect; //返回连接信息
		}


		// 2. 定义了一个查询的函数
		function query($sql){
			  $connect = connect(); // 调用 connent()函数，获取连接信息

			  //根据sql语句，查询数据库，获取字符集
			 $result=	mysqli_query($connect,$sql); 

			 $rows=	fetch($result);

			 return $rows; //这个数组当中，存储着所有查询到的数据
			 // return $result; 这个结果就不用返回了，直接返回$rows
		} 

		//3. 获取数据集中的第一条数据	
		function fetch($result){
			$rows = array();
			while($row = mysqli_fetch_assoc($result)){
				$rows[] = $row; //把循环获取到的数据存到一个数组当中
			}
			
			 return $rows; //返回数组中存储的所有的数据
		}

		/**
		 * 4. 封装了一个检测是否登陆的函数
		 * @return [type] [description]
		 */
		function checkLogin(){
			 
			if(!isset($_SESSION['user_info'])){
				// 如果没有此信息的话，则需要先跳转到登陆页面，先登陆之后再访问主页面碳
				header('location:/admin/login.php');
				exit;
			}
		}

		//5. 封装了一个插入数据的函数
		function insert($table,$arr){
				$connect=	connect();

			$keys = array_keys($arr); //获取数组中的属性
		$values = array_values($arr); //获取数组中的属性值
 $sql = 'insert into '.$table.' ('.implode(',',$keys).') values ("'.implode('", "',$values).'")';
			
			// print_r($sql);
			// exit;
			$result =	mysqli_query($connect,$sql);
			return  $result;
		}

		//6. 封装一个删除数据的函数
		function delete($sql){
				$connection = connect();
				// DELETE FROM  表名 WHERE 条件
			 $result=	mysqli_query($connection,$sql);

			 return $result;
		}

		//7. 封装一个更新数据的函数 
		function update($table,$arr,$id){
			// UPDATE FROM 表名  SET  字段=值,字段=值...WHERE id = 

				$connection = connect();
				$str = '';
          foreach($arr as $key => $val){
             $str .= $key . '=' . '"'.$val .'", ';
          }

          $str = substr($str,0,-2); //截取字符串，将最后的,和空格去掉
        // 拼写真正的sql语句
         $sql = 'UPDATE '.$table.' SET ' .$str .' WHERE id = ' . $id;
         // echo $sql;
         // exit;
			 $result =	mysqli_query($connection,$sql);

			 return  $result;
		}
		// 封装的函数越小越好，一个函数就代表一个功能,越单一越好
		// 这样可以让我们的程序足够的健壮  鲁棒

		// connect();
?>