<?php
  //个人中心里面，要显示当前个人的信息
  //之前做登陆的时候，是将当前登陆用户的信息存在session
  // 获取到当前的$_SESSION['user_info']中的id，再去数据库查询此数据  
  // 使用的时候，应该到数据库中查询最新的数据，以防管理修改了当前的用户信息

  // session_start();
  // print_r($_SESSION['user_info']) ;
  // exit;
  
  // 先判断是否登陆
  require '../functions.php';

  checkLogin(); //判断此页面是否为登陆之后的访问
  $user_id = $_SESSION['user_info'][0]['id'];
  // echo $user_id;
  // exit;  
  // 根据id查询此用户的数据，渲染当前的页面
     $rows = query('SELECT * FROM users WHERE id = ' . $user_id);
     // print_r($rows);
     // exit;

     if(!empty($_POST)){
        // 更新到数据
        unset($_POST['email']);//把提交过来的email信息删除 掉，只更新其它信息
       $result = update('users',$_POST,$user_id);

        if($result){
          header('location:/admin/profile.php');
          exit;
        }
        $msg = '修改失败...';
     }

    /**
     * 上传头像的思路 
     * 1. 头像图片上传了之后要放在一个独立的文件夹里面
     * 2. 需要把上传的图片路径永久存在数据库当中(数据库中图片的路径就是图片在服务器文件夹中的路径)
     * 3. 把当前的路径返回给浏览器
     * 4. 要把当前上传的图片渲染在对应页面的位置
     */
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
  <script>NProgress.start()</script>
  
  <div class="main">
   <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($msg)) {?>
     <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $msg?>
      </div>
      <?php }?>
      <form action='/admin/profile.php' method='post' class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="<?php echo isset($rows[0]['avatar'])?$rows[0]['avatar']:'/assets/img/default.png'?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $rows[0]['email']?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $rows[0]['slug']?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $rows[0]['nickname']?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" name='bio' class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $rows[0]['bio']?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.html">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php'?>

  
  <script>NProgress.done()</script>
</body>
</html>
<script>
  //给文件上传的标签注册事件，选择图片进行上传 
  $('#avatar').on('change',function(){
     // files
     // console.log(this) 
    // files是一个文件列表，里面保存了所有上传的文件信息
    // for(var k  in  this){
    //   console.log(k + "==="+ this[k]);
    // }
    // console.log(this.files[0]);

    //所有的文件都是以二进制的形式进行传递
    var data = new FormData(); 
    data.append('avatar',this.files[0]);

    //创建一个异步对象
    var xhr = new XMLHttpRequest();

    //发送请求
    xhr.open('post','/admin/upfile.php');

    // 发送数据
    xhr.send(data);

    //  发送成功之后，要接收返回来的数据
    xhr.onreadystatechange = function(){
      if(xhr.status == 200 &&xhr.readyState ==4){
        //  修改当前的头像信息
        $('.form-image img').attr('src',xhr.responseText);

        //修改侧边栏的图像信息
        $('.profile .avatar').attr('src',xhr.responseText);
      }
    }
  })
</script>
