<?php
		// 在主页面里面进行判断，如果是直接访问的这个页面，则获取不到对应的cookie
		// 如果要是登陆之后的跳转，则一定会有一个cookie信息，可以借此判断是登陆之后的跳转，还是直接的访问
    
   //   session_start();  //使用session的时候一定要先开启
			// if(!isset($_SESSION['user_info'])){
			// 	// 如果没有此信息的话，则需要先跳转到登陆页面，先登陆之后再访问主页面碳
			// 	header('location:/admin/login.php');
			// 	exit;
			// }
    require '../functions.php';

    checkLogin();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  <!--<script>NProgress.start()</script>-->

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default   ">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong>10</strong>篇文章（<strong>2</strong>篇草稿）</li>
              <li class="list-group-item"><strong>6</strong>个分类</li>
              <li class="list-group-item"><strong>5</strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

   <?php include './inc/aside.php'?>


  <!--<script>NProgress.done()</script>-->
</body>
</html>
