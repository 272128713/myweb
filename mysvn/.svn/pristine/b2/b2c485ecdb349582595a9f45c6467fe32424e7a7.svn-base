<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>新闻中心</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/news_center.css">
	</head>
	<body>
		<?php
            include "header.php";
            include "server/get_articlesortlist.php";
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

            <!-- 列表-->
			<?php foreach($ct as $k=>$v){ ?>
                <div class="skydynamic">
                    <div class="top">
                        <div class="header">
                            <img src="images/header.png">
                            <div class="text"><?php echo $v['title'];?></div>
                        </div>
                        <span class="subtitle">information</span>
                        <div class="right">
                                <a class="more" href="news_list.php?sort=<?php echo $v['id'];?>">更多</a>
                                <img src="images/tria.png">
                                <div class="clearfix"></div>
                        </div>
                    </div>
                    <img class="dis_line" src="images/line.png">
                    <div class="innert<?php echo $v['id']?>">



                    </div>
                    <script>
                        var sid = "<?php echo $v['id'];?>";
                        var html = "";
                        $.post("server/get_articlelist.php",{'sid':sid},function(data){
                            console.log(data);
                            if(data.code=="1001"){
                                html = "<div class='inner'>";
                                html +="<p style='font-size: 18px;color:#595959;'>暂无文章</p>";
                                html += "</div>";
                            }else if(data.code=="000"){
                                rs = data.result;
                                time = rs.update_time;
                                //有图
                                html = "<div class='inner'>";
                                html += "<div class='date'>";
                                html += "<span class='p1'>"+transtime(time,'dd')+"</span>";
                                html += "<p class='p2'>"+transtime(time,'yyyy.MM')+"</p>";
                                html += "</div>";
                                html += "<div class='outline'>";
                                html += "<p class='biaoti'>"+rs.title+"</p>";
                                html += "<div class='tupian'>";
                                html += "<img style='height:110px;width:280px;' src='./admin"+rs.path+"'>";
                                html += "</div>";
                                html += "<p class='news_content'>"+rs.description+"</p>";
                                html += "<div class='seedetail' onclick='detail("+rs.id+")'>查看详情</div>";
                                html += "</div>";
                                html += "<div class='clearfix'></div>";
                                html += "</div>";

                            }else if(data.code=="001"){
                                //无图
                                var rs = data.result;
                                    html = "<div class='inner inner2'>";
                                    html += "<div class='date'>";
                                    html += "<span class='p1'>"+transtime(rs[0].update_time,'dd')+"</span>";
                                    html += "<p class='p2'>"+transtime(rs[0].update_time,'yyyy.MM')+"</p>";
                                    html += "</div>";
                                    html += "<div class='outline'>";
                                    html += "<p class='biaoti'>"+rs[0].title+"</p>";
                                    html += "<p class='news_content news2'>"+rs[0].description+"</p>";
                                    html += "<div class='seedetail detail2' onclick='detail("+rs[0].id+")'>查看详情</div>";
                                    html += "</div>";
                                    html += "<div class='clearfix'></div>";
                                    html += "</div>";
                                    html += "<div class='inner inner3'>";
                                    html += "<div class='date'>";
                                    html += "<span class='p1'>"+transtime(rs[1].update_time,'dd')+"</span>";
                                    html += "<p class='p2'>"+transtime(rs[1].update_time,'yyyy.MM')+"</p>";
                                    html += "</div>";
                                    html += "<div class='outline'>";
                                    html += "<p class='biaoti'>"+rs[1].title+"</p>";
                                    html += "<p class='news_content news2'>"+rs[1].description+"</p>";
                                    html += "<div class='seedetail detail2' onclick='detail("+rs[1].id+")'>查看详情</div>";
                                    html += "</div>";
                                    html += "<div class='clearfix'></div>";
                                    html += "</div>";

                            }

                            $(".innert<?php echo $v['id']?>").html(html);


                        },"json");

                    </script>
                </div>
            <?php }?>
			<div class="clearfix"></div>
			<!--div class="paging">
				<img src="images/page.png">
				<img class="button1" src="images/prevbtn.png">
				<img class="button2" src="images/nextbtn.png">
			</div-->
		</div>
		
		<?php include "footer.php";?>
        <script>
            function detail(aid){
                window.location.href="artview.php?aid="+aid;

            }


            $('#search').click(function () {
                var get_val = $('#searchin').val();
                window.location.href="search_list.php?vl="+get_val;
            });
        </script>
	</body>
</html>
