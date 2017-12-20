<?php 

  require '../functions.php';
  checkLogin();

  //比如说当前数据库里面有102
  // $total = 100;
  $total = query('SELECT count(*) AS total FROM posts');
  $total = $total[0]['total'];
    // print_r($total);
    // exit;

  //假设每页显示7条数据
  $pageSize = 12;

  //计算出当前数据的总页数
  $pageCount = ceil($total / $pageSize); //50

  // 获取当前页面码
  $currentPage = isset($_GET['page'])?$_GET['page']:1;
  
  
  //设置上一页
  $prevPage = $currentPage - 1;


  //设置下一页
  $nextPage = $currentPage + 1 ;

  //设置一个当前显示的页数的编码
  // 5   6   7   8    9   10    11  
  // 19  20  21   22  23    24  25
  $pageLimit = 7;

  //设置起始页
  $start = $currentPage - floor($pageLimit / 2);

  //判断特殊情况 
  $start = $start < 1 ? 1 :  $start;

  //设置尾页编码
  $end = $start + $pageLimit - 1 ;

  if($end > $pageCount) {
    $end = $pageCount;

    //以最后的页码向前推算
    $start = $end - $pageLimit + 1;
  }

  //重新设置一个显示数据的起始编码
  $offset = ($currentPage -1)*$pageSize;
  //将所有的页码都存入数组当中
  // $pages = range(1,$pageCount); 
  $pages =  range($start,$end); 


  //查询数据库，获取当前的信息
  // $lists = query('SELECT * FROM posts');
  // print_r($lists);
  // $lists = query('SELECT * FROM posts LEFT JOIN users on posts.user_id=users.id');
  // print_r($lists);
  // $sql = 'SELECT * FROM posts LEFT JOIN users on posts.user_id=users.id LEFT JOIN categories on posts.category_id = categories.id';
 // $sql = 'SELECT posts.id,posts.title,users.nickname,categories.name,posts.created,posts.status FROM posts LEFT JOIN users on posts.user_id=users.id LEFT JOIN categories on posts.category_id = categories.id';
  $sql = 'SELECT posts.id,posts.title,users.nickname,categories.name,posts.created,posts.status FROM posts LEFT JOIN users on posts.user_id=users.id LEFT JOIN categories on posts.category_id = categories.id LIMIT '.$offset . ','.$pageSize;
  $lists = query($sql);


  // print_r($lists);
  // exit;
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">

          <?php if($currentPage >1){ ?>
          <li><a href="/admin/posts.php?page=<?php echo $prevPage?>">上一页</a></li>
          <?php }?>

          <?php foreach($pages as $key => $vals){ ?>

            <?php if($currentPage == $vals){ ?>
            <li class="active"><a href="/admin/posts.php?page=<?php echo $vals?>"><?php echo $vals?></a></li>
            <?php }else { ?>
            <li ><a href="/admin/posts.php?page=<?php echo $vals?>"><?php echo $vals?></a></li>
            <?php } ?>

          <?php }?>

          <?php if($currentPage < $pageCount){ ?>
          <li><a href="/admin/posts.php?page=<?php echo $nextPage?>">下一页</a></li>
          <?php } ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($lists as $key => $vals){ ?>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $vals['title']?></td>
            <td><?php echo $vals['nickname']?></td>
            <td><?php echo $vals['name']?></td>
            <td class="text-center"><?php echo $vals['created']?></td>
            <?php if($vals['status']=='published'){ ?>
            <td class="text-center">已发布</td>
            <?php }else {?>
            <td class="text-center">草稿</td>
            <?php }?>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
         <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

 <?php include './inc/aside.php'?>

 
  <script>NProgress.done()</script>
</body>
</html>
