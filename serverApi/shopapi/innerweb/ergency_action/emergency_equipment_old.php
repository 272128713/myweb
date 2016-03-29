<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>急救装备</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/emergency_equipment.css" />
		<?php include 'emergency_server_eq.php';
		?>
	</head>
	
	<body>
		<div class="st-container" id="st-container">
			<div class="st-menu " id="menu-1">
				<div class="bg"></div><?php
						foreach($class as $key=>$cls){
					?>
					<p>
						<a href="emergency_equipment.php?cid=<?php echo $cls["id"]?>"
						<?php if($cls['id']==$cid){
							echo "style='color:#ff0000'>";
							echo "<img src='images/xb1.png' />";
						}else{?>
						><img src="images/xb.png" /><?php } echo $cls["describes"]; ?></a>
					</p>
					<?php
						}
					?>
			</div>
			<div class="bg-shadow"></div>
			<div class="st-pusher">
				<div class="st-content">
					<div class="header">
						<div class="header_con">
							<div class="sort" id="st-trigger-effects" data-effect="st-effect-1">
								<span class="icon"></span>
								<span class="wenzi">分类</span>
							</div>
							<input type="text" name="search" placeholder=""
							<?php if($con){
								echo "value='$con'";
							}?>
							>
							<div class="em-search" style="">搜</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="pro_list" id="wrapper">
						<?php if($eq){?>
						
						
						<div>
							<?php foreach ($eq as $k=>$eqv){?>
							<div class="list-item" gid="<?php echo $eqv["id"]?>">
								<img src="<?php echo $eqv['img_url']?>">
								<p class="pro_name"><?php echo $eqv['goods_name']?></p>
							</div>
							<?php }
							?> 
						</div>
						
						
						
						
						
					   <?php }else{
								echo "<div style='line-height:1.5em;font-size:14px;margin-top: 10px;margin-left: 9px;'>无相关产品<br />This is no introduction.</div>";
							}?>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<script src="../common/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../common/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script src="../common/js/iscroll.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$(function(){
					var sort_width = $('.sort').width();
					$('.sort .icon').css('margin-left',(sort_width - 15)/2);
			});
			$(".list-item").on("click",function(){
				var gid = $(this).attr("gid");
				window.location.href="equipment_detail.php?gid="+gid;
				});

			$('input').focus(function(){
					$('.em-search').show();
				});
			$('.em-search').on('click',function(){
				var sh = $(this).prev('input').val();
				if(!sh){
					layer.msg("请输入产品名称");
					}else{
						window.location.href="emergency_equipment.php?con="+sh;
					}
			});

			$('.sort').on('click',function(){
						$('.st-menu').show();
						$('.bg-shadow').show();
					
				});
			$('.bg-shadow').on('click',function(){
						$('.st-menu').hide();
						$('.bg-shadow').hide();
				});
			console.log('<?php echo $_SESSION['ss'];?>');
		</script>
		<!-- 初始化scroll -->
		<script type="text/javascript">
					function loaded() {
					    pullDownEl = document.getElementById('pullDown');
					    pullDownOffset = pullDownEl.offsetHeight;
					    pullUpEl = document.getElementById('pullUp');  
					    pullUpOffset = pullUpEl.offsetHeight;
					     
					    myScroll = new iScroll('wrapper', {
					        useTransition: true,
					        topOffset: pullDownOffset,
					        onRefresh: function () {
					            if (pullDownEl.className.match('loading')) {
					                pullDownEl.className = '';
					                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新';
					            } else if (pullUpEl.className.match('loading')) {
					                pullUpEl.className = '';
					                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多';
					            }
					        },
					        onScrollMove: function () {
					            if (this.y > 5 && !pullDownEl.className.match('flip')) {
					                pullDownEl.className = 'flip';
					                pullDownEl.querySelector('.pullDownLabel').innerHTML = '松开刷新';
					                this.minScrollY = 0;
					            } else if (this.y < 5 && pullDownEl.className.match('flip')) {
					                pullDownEl.className = '';
					                pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新';
					                this.minScrollY = -pullDownOffset;
					            } else if (this.y < (this.maxScrollY - 10) && !pullUpEl.className.match('flip')) {
					                pullUpEl.className = 'flip';
					                pullUpEl.querySelector('.pullUpLabel').innerHTML = '松开刷新';
					                this.maxScrollY = this.maxScrollY;
					            } else if (this.y > (this.maxScrollY + 10) && pullUpEl.className.match('flip')) {
					                pullUpEl.className = '';
					                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多';
					                this.maxScrollY = pullUpOffset;
					            }
					        },
					        onScrollEnd: function () {
					            if (pullDownEl.className.match('flip')) {
					                pullDownEl.className = 'loading';
					                pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中';              
					                pullDownAction();   // Execute custom function (ajax call?)
					            } else if (pullUpEl.className.match('flip')) {
					                pullUpEl.className = 'loading';
					                pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中';              
					                pullUpAction(); // Execute custom function (ajax call?)
					            }
					        }
					    });
					     
					    setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
					}
					document.addEventListener('touchmove', function (e) {  e.preventDefault(); }, false);
					document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);

		</script>
		<!-- 执行 -->
		<script type="text/javascript">
				function pullDownAction () { // 下拉刷新
				    window.location.reload();
				}
				var i = 2; //初始化页码为2
				function pullUpAction () { 上拉加载更多
				    var page = i++; // 每上拉一次页码加一次 （就比如下一页下一页）
				    Ajax(page); // 运行ajax 把2传过去告诉后台我上拉一次后台要加一页数据（当然 这个具体传什么还得跟后台配合）
				    myScroll.refresh();// <-- Simulate network congestion, remove setTimeout from production!
				}
				function Ajax(page){ // ajax后台交互
				    $.ajax({
				        type : "post",
				        dataType : "JSON",
				        url : "/installerAjax", // 你请求的地址
				        data : {
				            'page': page  // 传过去的页码
				        },
				        success : function(data){
				            data =  eval(data.clientList);
				            if(data.length){ // 如果后台传过来有数据执行如下操作 ， 没有就执行else 告诉用户没有更多内容呢
				                for( var i=0; i<(data.length/2); i++){  // 这里为你自己的代码不要照抄 , 操作你自己后台返回的数据
				                    var oLis = "<li><a href='/apps/clientCase?clientId=" +data[i+i].id+ "' class='left'><p class='jsyh_logo'><img src='"+"http://localhost:8080"+ "/" + data[i+i].photo+"'></p><div class='text'><p>" + data[i+i].clientName +"</p><span class='blue_icon'>"+data[i+i].number+"</span></div></a><a href='/apps/clientCase?clientId=" +data[i+i+1].id+ "' class='left'><p class='jsyh_logo'><img src='"+"http://localhost:8080"+ "/" + data[i+i+1].photo+"'></p><div class='text'><p>" + data[i+i+1].clientName +"</p><span class='blue_icon'>"+data[i+i+1].number+"</span></div></a></li>";
				                    $('ul.customer').append(oLis);
				                }
				            }else{
				                $('.pullUpLabel').html('亲，没有更多内容了！');
				            }
				 
				        },
				        error : function(){
				             
				        }
				    });
				     
				}

		</script>
	</body>
</html>
