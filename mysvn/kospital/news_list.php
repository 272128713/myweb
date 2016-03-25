<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>新闻列表</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/news_detail.css">
	</head>
	<body>
		<?php
            include "header.php";
            include "server/show_articlelist.php";

        ?>
		<div class="content" style="margin-bottom: 50px;">
			<div class="title">
				<div class="line"></div>
				<span><a style="color:#595959" href="news_center.php">新闻中心</a></span>
				<div class="clearfix"></div>
			</div>
			<div class="search">
				<input type="text" placeholder="快速搜索..." id="searchin">
				<div class="search_btn" id="search">搜索</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
			<div class="skydynamic first">
				<div class="top">
					<div class="header">
						<img src="images/header.png">
						<div class="text"><?php echo $sname["title"];?></div>
					</div>
					<span class="subtitle">information</span>
				</div>
				<img class="dis_line" src="images/line2.png">
			</div>
            <?php foreach($datas as $k=>$v){?>
                <div class="inner">
                    <div class="date">
                        <span class="p1"><?php echo date('d',$v['update_time'])?></span>
                        <p class="p2"><?php echo date('Y.m',$v['update_time'])?></p>
                    </div>
                    <div class="outline">
                        <p class="biaoti"><?php echo $v['title']?></p>
                        <div class="listbox">
                            <?php if($v['cover_id']!=0){
                                $style="";
                                ?>
                                <div class="tupian">
                                    <img src="./admin/<?php echo $v['path'];?>">
                                </div>
                            <?php }else {

                                $style="width:100%";
                            }?>
                            <p class="news_content" style="<?php echo $style;?>">
                                <?php echo $v['description']?>
                            </p>
                            <div class="clearfix"></div>
                        </div>
                        <div class="seedetail" onclick="window.location.href='artview.php?aid=<?php echo $v['id'];?>'">查看详情</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php }?>
			<div class="paging">
                <?php if($num==0){ }else{
                    if($page==0){
                        $pagebehind=1;
                    }else{
                        $pagebehind=$page;
                    }
                    if($page+1==$num){
                        $pagenext=$num;
                    }else{
                        $pagenext=$page+2;
                    }
                    ?>
                    <a class="pre" href="news_list.php?sort=<?php echo $sort;?>&page=1"></a>
                    <a class="behindto" href="news_list.php?sort=<?php echo $sort;?>&page=<?php echo $pagebehind;?>"></a>

                    <?php
                        pagebar($num,$page+1,9,$sort);
                    ?>
                    <a class="nextto" href="news_list.php?sort=<?php echo $sort;?>&page=<?php echo $pagenext;?>"></a>
                    <a class="last" href="news_list.php?sort=<?php echo $sort;?>&page=<?php echo $num;?>"></a>
                <?php }?>
                <div class="clearfix"></div>
			</div>
		</div>
		
		<?php include "footer.php"; ?>
        <script>
            $('#search').click(function () {
                var get_val = $('#searchin').val();
                window.location.href="search_list.php?vl="+get_val;
            });

        </script>
	</body>
</html>
<?php
/**
 * $count 总页数
 * $page 当前页号
 * $num 显示的页码数
 **/
function pagebar($count, $page, $num, $sort) {
    $num = min($count, $num); //处理显示的页码数大于总页数的情况
    if($page > $count || $page < 1) return; //处理非法页号的情况
    $end = $page + floor($num/2) <= $count ? $page + floor($num/2) : $count; //计算结束页号
    $start = $end - $num + 1; //计算开始页号
    if($start < 1) { //处理开始页号小于1的情况
        $end -= $start - 1;
        $start = 1;
    }
    for($i=$start; $i<=$end; $i++) { //输出分页条，请自行添加链接样式
        if($i == $page){
            $active= "class='active'";
        }
        else{
            $active= "";
        }
        echo "<a ".$active." href='news_list.php?sort=".$sort."&page=".$i."'>" . $i . "</a>";

    }
}

?>



