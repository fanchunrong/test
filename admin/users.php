<?php

header("Content-type:text/html;charset=utf-8");

    require '../functions.php';

    checkLogin(); // 检测是否登陆

    // 当前这个页面有两种功能，一个显示当前页面信息，一个是收集提取过来的数据
    // 当get的时候，就是一个页面的显示
    // 当post的时候，就是一个提交数据

    // 判断GET过来的URL中有无action,如果有则获取，如果没有就添加一个默认的add
    $action = isset($_GET['action'])? $_GET['action']:'add';
    $msg = '';
    $title = '添加新用户';
    $btnText = '添加';


    if(!empty($_POST)){
      
      if($action=='add'){
      // $email =$_POST['email'];
      // $slug = $_POST['slug'];
      // $password = $_POST['password'];
      // // $nickname = $_POST['nickname'];
      // $status = "unactivated";

      //'insert into users (id,slug,email,password,status) values (null,"'.$slug.'","'.$email.'","'.$password.'","'.$status.'")';
       //  $sql = 'insert into users (id,slug,email,password,status) values (null,"'.$slug.'","'.$email.'","'.$password.'","'.$status.'")';
       // $result = insert($sql);
        $_POST['status'] = "unactivated"; //设置一个默认的状态

        $result=  insert('users',$_POST);
       // print_r($result);
       // exit;
       if($result){
          header("location:/admin/users.php");//跳转到当前页面，其实就是相当于刷新
       }else {
          $msg = '插入数据失败...';
          // exit;
       }
      }

      //更新数据时的操作
      if($action =='update'){
        // 信息修改之后的更新操作

          // $str = '';
          // foreach($_POST as $key => $val){
          //    $str .= $key . '=' . '"'.$val .'", ';
          // }
          // $str = substr($str,0,-2);
          // echo $str;  
          $id = $_POST['id']; //先获取id，存到一个变量当中
          unset($_POST['id']); //因为不能修改主键的id，所以需要先将此数组中的id删除掉,
          $result = update('users',$_POST,$id);
          if($result) {
            header('location:/admin/users.php'); //更新成功的话，需要跳转到当前的页面
            exit;
          }
          // exit;
      }

      // 批量删除时的操作
      if($action=='deleteAll') {
          // 'DELETE FROM users WHERE id in ()''
        //拼接sql语句
        
        $sql = 'DELETE FROM users WHERE id in ('.implode(',',$_POST['ids']).')';
        // echo $sql;
        // exit;
         $result = delete($sql);
         // 设置一个返回来的解析格式
         header('Content-type:application/json'); //告知前端jQuery如何来解析数据
         if($result){ // 如果删除成功的话
            $info = array('code'=>10000,'message'=>'删除成功!');
            
         }else {
            $info = array('code'=>10001,'message'=>'删除失败!');
            
         }
         echo json_encode($info);
         exit;
      }
    }

    // 查询数据库，把数据库里面的所有的用户的信息，显示在当前的页面上
     $lists= query('SELECT * FROM USERS');
     // print_r($rows);
     // exit;


     // 编辑和删除的操作
     // 当单击编辑按钮的时候，需要先去数据库中，查询当前这条用户下的所有的信息，显示在对应的左侧位置，然后进行相应的修改

     // if(isset($_GET['action'])){  // 因为将获取action的代码提升到最前面了，因此这一行就没有必要了
       // $action = $_GET['action'];//将此行代码提升到最上面
       $user_id = isset($_GET['user_id'])?$_GET['user_id']:'';

       if($action=='edit'){
        // 说明这是一个编辑的操作
        // 先根据id查询当前的数据，
        $action = 'update';// 重新给此变量赋值
        $title = '编辑此用户';
        $btnText = '更新';
        $rows=  query('SELECT * FROM users WHERE id = '.$user_id);

        // print_r($rows);
        // exit;

       }else if($action == 'delete'){
        // 这是一个删除数据的操作
        // delete  from users where id = 
         $result =  delete('DELETE FROM users WHERE id = '.$user_id);
         if($result){
           // 判断一下，如果删除成功的话，要刷新当前页面
           header('location:/admin/users.php') ;//刷新 当前页面
           exit;
         }
       }
     // }
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  // <script>NProgress.start()</script>

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($msg)){ ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $msg?>
      </div>
      <?php }?>
      <div class="row">
        <div class="col-md-4">
          <form action="./users.php?action=<?php echo $action?>" method='post'>
            <h2><?php echo $title ?></h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <?php if($action!='add'){?>
              <input type="hidden" name="id" value="<?php echo isset($rows[0]['id'])?$rows[0]['id']:''?>">
              <?php }?>
              <input id="email" value="<?php echo isset($rows[0]['email'])?$rows[0]['email']:''?>" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" value="<?php echo isset($rows[0]['slug'])?$rows[0]['slug']:''?>" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" value="<?php echo isset($rows[0]['nickname'])?$rows[0]['nickname']:''?>" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" value="<?php echo isset($rows[0]['password'])?$rows[0]['password']:''?>" class="form-control" name="password" type="text" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btnText?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm deleteBtn" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40">
                  <input type="checkbox" class="deleteAll" >
                </th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($lists as $keys=>$vals){?>
              <tr>
                <td class="text-center">
                  <input type="checkbox" value="<?php echo $vals['id']?>" class="deleteChk">
                </td>
                <td class="text-center"><img class="avatar" src="<?php echo isset($vals['avatar'])?$vals['avatar']:'/uploads/avatar.jpg'?>"></td>
                <td><?php echo $vals['email']?></td>
                <td><?php echo $vals['slug']?></td>
                <td><?php echo $vals['nickname']?></td>
                <?php if($vals['status']=='activated'){?>
                <td>激活</td>
                <?php }else if($vals['status']=='unactivated'){?>
                <td>未激活</td>
                <?php }else if($vals['status']=='forbidden'){?>
                <td>禁止</td>
                <?php } else{ ?>
                <td>删除</td>
                <?php }?>
                <td class="text-center">
                  <a href="/admin/users.php?action=edit&user_id=<?php echo $vals['id']?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/users.php?action=delete&user_id=<?php echo $vals['id']?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <?php include './inc/aside.php'?>


  // <script>NProgress.done()</script>
</body>
</html>
<script>
  //1. 当单击总按钮的时候，下面的每一个小按钮也要被选中,批量删除按钮要显示出来，反之隐藏起来
  $('.deleteAll').on('click',function(){
      // this是DOM对象   $(this)是一个jQuery对象
    if(this.checked){
      // selected  disabled checked 这三个是比较特殊的，如果要设置属性的话，得用prop
      $('.deleteChk').prop('checked',true);
      $('.deleteBtn').show(400);  //要让批量删除的按钮显示出来
    }else {
      $('.deleteChk').prop('checked',false);
      $('.deleteBtn').hide(400);  //要让批量删除的按钮显示出来
    }
  })

  //2. 如果单独的选中若干个小按钮的话，也需要显示批量删除的按钮
  $('.deleteChk').on('click',function(){
    // 每次触发一个按钮的时候，都要判断一下当前有多少被选中了
     var size = $('.deleteChk:checked').size(); // 获取被选中的个数
     if(size>0){
        $('.deleteBtn').show(400);
        return ; //在函数内部，代码执行return关键字这里之后，不再往下执行
     }
      $('.deleteBtn').hide(400);
  })
  //3.给批量删除按钮，注册事件来删除对应的数据
  $('.deleteBtn').on('click',function(){
     // 先查询一下选中的按钮，并获取到对应的id
    var ids = [];
     $('.deleteChk:checked').each(function(){ //each函数用来遍历所有被选中的input
        ids.push($(this).val()); //将被选中的按钮的value值(id)存入数组当中
     })
     // console.log(ids);

     //4. 发送ajax请求，删除所有的选中的按钮的那些数据
     $.ajax({
      url:'/admin/users.php?action=deleteAll',
      type:'post',
      data:{ids:ids},
      success:function(info){
        // alert(info.message) ;
        if(info.code == 10000){
          // 说明 删除成功了，需要刷新 当前页面
          location.reload(true); // 重新加载页面
        }
      }
     })
  })
</script>