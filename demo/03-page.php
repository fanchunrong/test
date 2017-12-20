<?php
	//比如说当前数据库里面有102
	$total = 100;

	//假设每页显示5条
	$pageSize = 5;

	//计算出当前数据的总页数
	$pageCount = ceil($total / $pageSize); //50

	// 获取当前页面码
	$currentPage = isset($_GET['page'])?$_GET['page']:1;

	//设置上一页
	$prevPage = $currentPage - 1;

	//设置下一页
	$nextPage = $currentPage + 1 ;

	//设置一个当前显示的页数的编码
	// 5   6   7   8		9		10		11	
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
	}

	//将所有的页码都存入数组当中
	// $pages =	range(1,$pageCount); 
	$pages =	range($start,$end); 

	// print_r($pages);

	//1   2   3   4   5
	//6   7   8   9  10
	//11  12   13   14  15

	//想一页显示7条数据  
	//1		2		3		4		5		6		7
	//8		9		10	11	12	13	14
	//15	16	17	18	19	20	21
					//										0	7   
					//										7	7   2
					//									  14 7			3

	//SELECT * FROM posts LIMIT  14,7; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>分布</title>
</head>
<body>
	<?php if($start > 1 ) {?>
	<a href="./03-page.php?page=<?php echo $prevPage?>">上一页</a>
	<?php } ?>
	<?php foreach($pages as $key => $vals){ ?>
	<a href="./03-page.php?page=<?php echo $vals?>"><?php echo $vals?></a>
	<?php }?>
	<?php if($end < $pageCount){ ?>
	<a href="./03-page.php?page=<?php echo $nextPage?>">下一页</a>
	<?php } ?>
</body>
</html>