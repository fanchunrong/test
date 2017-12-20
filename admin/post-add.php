<?php 
  header('Content-type:text/html;charset=UTF-8');
  require '../functions.php';
  checkLogin();// 检测是否登陆

   $lists =  query('SELECT * FROM categories');
   // print_r($lists);
   // exit;

   $action = isset($_GET['action']) ? $_GET['action'] :'';

   //接收上传过来的文件
   if($action == 'upfile'){
      //$_FILES //存储了接收到的文件  把此接收到的文件放到我们指定的目录里面
    if(!file_exists('../uploads/thumbs')){
      mkdir('../uploads/thumbs');// 如果没有的话，则创建此文件夹
    }

    //为了保证上传的图片文件的名称不冲突，可以根据时间戳来命名
    $fileName = time(); //时间戳

    //获取上传的文件的后缀
    $ext = explode('.',$_FILES['feature']['name']);

    //拼接路径 
    $path = '../uploads/thumbs/'.$fileName . '.' .$ext[1];

    move_uploaded_file($_FILES['feature']['tmp_name'],$path);

    //把路径转换成互联网路径传递给前台
    echo substr($path,2);
    exit;
   }

   // 接收post过来的数据
   if(!empty($_POST)){
      $result =  insert('posts',$_POST);
      // var_dump($result);
      // exit;
      if($result){
        header('location:/admin/posts.php'); //跳转到所有的文件的列表页
        exit;
      }
   }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action='/admin/post-add.php' method = 'post'>
      <input type='hidden' value='<?php echo $_SESSION['user_info'][0]['id']?>' name='user_id'>
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control"  type="file">
            <input type='hidden' name= 'feature' value='' id = 'thumb'>
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">

            <?php foreach($lists as $key => $vals){ ?>
              <option value="<?php echo $vals['id']?>"><?php echo $vals['name']?></option>
              <?php } ?>
            </select>

          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
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
  $('#feature').on('change',function(){
    

    //所有的文件都是以二进制的形式进行传递
    var data = new FormData(); 
    data.append('feature',this.files[0]);

    //创建一个异步对象
    var xhr = new XMLHttpRequest();

    //发送请求
    xhr.open('post','/admin/post-add.php?action=upfile');

    // 发送数据
    xhr.send(data);

    //  发送成功之后，要接收返回来的数据
    xhr.onreadystatechange = function(){
      if(xhr.status == 200 &&xhr.readyState ==4){
        //  修改当前的头像信息
        $('.thumbnail').attr('src',xhr.responseText).show();

        //还应该把此图片的路径上传到服务器存到数据库里面
        //把后台传过来的图片路径存在一个隐藏域里面
        $('#thumb').val(xhr.responseText);
      }
    }
  })
</script>
