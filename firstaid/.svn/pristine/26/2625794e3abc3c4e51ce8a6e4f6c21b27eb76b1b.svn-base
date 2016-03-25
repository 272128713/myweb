<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>急救装备详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="./css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/equipment_detail_list.css" />
		<?php include 'emergency_server_eq_detaillist.php';?>
	</head>
	
	<body>
		<div class="container">
			<div class="total">共计<?php echo count($pack)?>套应急预案配备</div>
			<?php foreach ($pack as $k=>$pa){?>
			<div class="eq_list">
				<div class="title"><?php echo $pa['goods_name']?></div>
				<div class="box_cc">
					<div class="ccimg"><img src="<?php echo $pa['img_url']?>"/></div>
					<div class="box_right">
						<div class="right_title">	
							<span class="span1">￥<?php echo sprintf("%.2f",$pa['goods_price']) ?></span><span class="span2">适用1~3人</span>
						</div>
						<div class="p1"><?php echo $pa["goods_summary"]?></div>
						<a class="p2" href="emergency_detail.php?gid=<?php echo $pa['id']?>&ss=<?php echo $_SESSION['ss'];?>">了解详情</a>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<?php }?>
		</div>
	</body>
</html>
