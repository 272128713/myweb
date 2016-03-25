<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php include 'emergency_server_classlist.php';?>
		<title><?php echo $rc ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/sort_list.css" />
		
	</head>
	
	<body>
		<div class="container">
			<div class="sousuo">
				<input type="text" name="retrieval" placeholder="" value="<?php echo $con?>">
				<div class="em-search" style="">搜</div>
			</div>
			<div class="list">
			<?php if(!$list){
				echo "<div style='margin-left:3%;line-height:2em;font-size:14px;color:#333;margin-top:1em'>无相关产品！<br />No related products!</div>";
				
			}?>
				<?php foreach ($list as $k=>$li){
					$liid = $li["id"];
					?>
				<div class="list-item" gid="<?php echo $liid?>">
					<div class="list-item-con">
						<img src="<?php echo $li["img_url"]; ?>">
						<div class="item_t">
							<p class="name"><?php echo $li["goods_name"]; ?></p>
							<p class="time"><span class="price">￥<?php echo sprintf("%.2f", $li["goods_price"]); ?></span>适用1~3人</p>
							<p class="intro"><?php echo $li["goods_summary"]; ?></p>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<script src="../common/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../common/js/mui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../common/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			mui.init({
				pullRefresh: {
					container: '.list',
					up: {
						contentrefresh: '正在加载...'
					}
				}
			});
		
			$('.list-item').on("click",function(){
				gid = $(this).attr("gid");
				window.location.href="emergency_detail.php?gid="+gid;
				});
			

			$('input').focus(function(){
					$('.em-search').show();
				});
			$('.em-search').on('click',function(){
					var sh = $(this).prev('input').val();
					if(!sh){
						layer.msg("请输入产品名称");
						}else{
					window.location.href="sort_list.php?con="+sh;
						}
				});
			function pullup(){
				alert('up');
				}
		</script>
	</body>
</html>
