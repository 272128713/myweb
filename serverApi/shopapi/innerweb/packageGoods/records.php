<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>赠送记录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <link rel="stylesheet" href="css/mui.min.css">
	    <link rel="stylesheet" type="text/css" href="css/app.css" />
	    <link rel="stylesheet" type="text/css" href="css/common.css" />
	    <link rel="stylesheet" type="text/css" href="css/records.css" />
        <?php include "php/getrecord.php";?>
	</head>
	<body>
		<ul class="recordlist">
            <?php

            if(!$result){
                echo "<div style='text-align:center;margin-top:40px;line-height: 74px;font-size: 14px; color:#333;'>暂无记录</div>";
            }

            foreach ($result as $k=>$v){?>
			<li>
				<div class="center">
					<p class="who" style="width: 64%;line-height: 23px;">赠送<span><?php echo $v->user_name;?></span>一份稻草包</p>
					<p class="time" style="line-height: 24px;"><?php echo $v->createDate;?></p>
				</div>
				<div class="center second">
					<p >共有<?php echo $v->count;?>件商品</p>
					<p class="rmb">价值：￥<?php echo sprintf('%.2f', $v->totalprice); ?>元</p>
				</div>
			</li>
            <?php }?>
		</ul>
	</body>
</html>
