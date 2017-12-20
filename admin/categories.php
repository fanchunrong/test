<?php
  require '../functions.php';

  checkLogin();

    //查询数据库，获取数据，渲染到对应的位置 
  // $sql = 'SELECT * FROM categories';
  $lists = query('SELECT * FROM categories');
  // print_r($lists);
  // exit;

  // 地址栏中只有拼接参数的方式的数据，都可以使用 $_GET来获取

   $action = isset($_GET['action'])?$_GET['action']:'add';

   $ct_id = isset($_GET['ct_id'])? $_GET['ct_id']:0; // 获取传递过来的id
   if($action =='add'){

      $title = '新增分类目录';
      $btnText = '增 加';
   }else if($action == 'edit'){
      $title = '修改新分类目录';
      $btnText = '修 改';
      $action = 'update';
     $rows= query('SELECT * FROM categories WHERE id = ' .$ct_id);
     // print_r($rows);
     // exit;

   }else if($action =='delete'){

     $result = delete('DELETE FROM categories WHERE id = '.$ct_id );

     if($result){
        header('location:/admin/categories.php');//刷新页面
        exit;
     }
   }else if($action == 'update'){
      //$_GET 可以获取到所有的通过get方式提交过来的表单数据，但是得有name
      // print_r($_GET);
      // exit;  
    // action不能提交
       unset($_GET['action']);
      $ct_id = $_GET['id'];//先取出来
      unset($_GET['id']);

      $result = update('categories',$_GET,$ct_id);

      if($result){
        header('location:/admin/categories.php');
        exit;
      }
   }

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  <script>NProgress.start()</script>
      
  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action='/admin/categories.php' method='get'>
           <input type='hidden' name='action' value="<?php echo $action ?>"> 
           <input type='hidden' name='id' value="<?php echo $ct_id ?>">
            <h2><?php echo $title?></h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" value="<?php echo isset($rows[0]['name'])?$rows[0]['name']:'' ?>" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" value="<?php echo isset($rows[0]['slug'])?$rows[0]['slug']:'' ?>" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btnText?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($lists as  $key => $vals){ ?>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><?php echo $vals['name']?></td>
                <td><?php echo $vals['slug']?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?action=edit&ct_id=<?php echo $vals['id']?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories.php?action=delete&ct_id=<?php echo $vals['id']?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php'?>

  <script>NProgress.done()</script>
</body>
</html>
