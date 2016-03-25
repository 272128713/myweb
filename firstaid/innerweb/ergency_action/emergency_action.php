<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<title>安全订制</title>
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/emergency_plan.css">
	</head>
	<body>
		<div class="container">
			<div class="banner-top">
				<img src="images/banner.png">
				<div class="circle">
					<img src="images/circle.png">
				</div>
			</div>
			<div class="plans">
				<div class="line" style='left:49.9%'></div>
				<div class="row row1">
					<div class="left">
						<p class="tit">A级套餐</p>
						<p class="tit2">The first plan</p>
						<p class="tit3">免费培训</p>
						<p class="tit3">个人意外险</p>
						<p class="tit3">额外急救服务</p>
					</div>
					<div class="middle">
						<img src="images/first.png">
					</div>
					<div class="right big">01</div>
					<div class="clearfix"></div>
				</div>
				<div class="row row2">
					<div class="left big">02</div>
					<div class="middle middle2">
						<img src="images/second.png">
					</div>
					<div class="right">
						<p class="tit">B级套餐</p>
						<p class="tit2">The second plan</p>
						<p class="tit3">120急救中心</p>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="row row3">
					<div class="left">
						<p class="tit">C级套餐</p>
						<p class="tit2">The third plan</p>
						<p class="tit3">专业救援队</p>
					</div>
					<div class="middle middle2">
						<img src="images/third.png">
					</div>
					<div class="right big">03</div>
					<div class="clearfix"></div>
				</div>
				<div class="row row4">
					<div class="left big">04</div>
					<div class="middle middle2">
						<img src="images/more.png">
					</div>
					<div class="right">
						<p class="tit more">更多套餐</p>
						<p class="tit2 more">More plan</p>
						<p class="tit4">敬请期待</p>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="../common/js/jquery.min.js"></script>
		<script>
			$(function(){
				var ww = $(window).width();
				var circle_width = $('.circle').width();
				var middle_width = $('.middle img').width();
				var int_width = parseInt(middle_width);
				$('.circle').css('left',(ww - circle_width)/2);
				//$('.line').css('left',ww/2);
				$('.middle img').css('top',(106 - int_width)/2);
				$('.row1 .left').css('top',13.5);
				$('.row2 .right').css('top',29.5);
				$('.row3 .left').css('top',29.5);
				$('.row4 .right').css('top',26);
			})
		</script>
	</body>
</html>
