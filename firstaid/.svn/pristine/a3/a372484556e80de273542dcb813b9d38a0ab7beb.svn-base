<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>善有善报</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" href="css/kind.css">
		<?php include 'emergency_server_classlist.php';?>
	</head>

	<body>
		<div class="banner">
			<img src="images/kind.png">
			<div class="sum">
				<p class="mon">￥<?php echo $count?>.00</p>
				<p class="text">爱心捐助</p>
			</div>
		</div>
		<!-- nav class="mui-bar mui-bar-tab">
			<a id="defaultTab" class="mui-tab-item mui-active" href="#source_detail">
				<span class="mui-tab-label">来源明细</span>
			</a>
			<a class="mui-tab-item" href="#income_detail">
				<span class="mui-tab-label">收支明细</span>
			</a>
		</nav-->
		<!--第一个子页面开始-->
		<div id="source_detail" class="mui-control-content mui-active">
			<div class="list-container">
				<?php
				if($count){
				foreach ($result as $k=>$res){?>
				<div class="list">
					<div class="list-left">
						<p class="pro_name"><?php echo $res['goods_name']?><p>
						<p class="date"><?php echo $res['buy_time']?></p>
					</div>
					<div class="list-right">
						<p class="jine">基金获得<span>￥1.00</span></p>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php }
				
				}else{
					echo "<div style='color:#999;line-height:2em;font-size:14px;margin-top:50px;text-align:center'>每购买一件爱心产品<br />10%直接捐给慈善机构</div>";
					echo "<a href='../ergency_action/emergency_action.php?ss=".$_SESSION['ss']."' style='color:#fff;margin:0 auto;margin-top:50px;border-radius:5px;line-height:3em;height:3em;width:50%;background:#dd403b;font-size:14px;text-align:center;display:block'>立即奉献爱心</a>";

}
				
				
				
				?>
			</div>
		</div>
		<!--第一个子页面结束-->
		<!--第二个子页面开始-->
		<!-- div id="income_detail" class="mui-control-content">
			<div class="list-container">
				<div class="list">
					<div class="list-left">
						<p class="pro_name">防震便携急救包<p>
						<p class="date">2015-11-03</p>
					</div>
					<div class="list-right">
						<p class="jine">基金获得<span>￥100</span></p>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="list-container">
				<div class="list">
					<div class="list-left">
						<p class="pro_name">防震便携急救包<p>
						<p class="date">2015-11-03</p>
					</div>
					<div class="list-right">
						<p class="jine">基金获得<span>￥100</span></p>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div-->
		<!--第二个子页面结束-->
		<script src="js/jquery.min.js"></script>
		<script src="js/mui.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(function(){
				var banner_width = $('.banner img').width();
				$('.mui-bar-tab').css('top',banner_width * 0.4375);
			})
		</script>
	</body>
</html>