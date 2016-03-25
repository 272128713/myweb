<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>健康宝</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/health_treasure.css">
	</head>
	<body>
        <?php include "header.php";?>
		<div class="container">
			<div class="banner_health">
				<div class="bg"></div>
				<div class="health"></div>
			</div>
			<div class="platform">
				<div class="content">
					<p class="choose">选择平台下载APP</p>
					<p class="subtitle"><span>健康宝为您的健康理财</span></p>
					<div class="download">
						<div class="plat" onclick="window.location.href='https://itunes.apple.com/cn/app/jian-kang-bao/id960931071?mt=8'" style="cursor:pointer;">
							<img src="images/health_apple.png">
							<span>iPhone下载</span>
						</div>
						<div class="plat plat2" onclick="window.location.href='android/healthpay.apk'" style="cursor:pointer;">
							<img src="images/health_android.png">
							<span>Android下载</span>
						</div>
						<div class="plat">
							<img src="images/health_code.png">
							<span>扫描二维码</span>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="health_treasure">
				<div class="content">
					<p class="title">认识我<span>你会更健康</span></p>
					<div class="line"></div>
					<p class="subtitle">健康&nbsp;&nbsp;时间&nbsp;&nbsp;收益全给你</p>
					<div class="row">
						<div class="left">
							<p class="tit">安全保障</p>
							<p class="tit2">Safety assurance</p>
							<p class="tit3">作为接受K服务的资金储值工具，健康宝具备保障资金安全的能力</p>
							
						</div>
						<div class="middle">
							<img src="images/security.png">
						</div>
						<div class="right big">01</div>
						<div class="clearfix"></div>
					</div>
					<div class="row row2">
						<div class="left big">02</div>
						<div class="middle middle2">
							<img src="images/access.png">
						</div>
						<div class="right">
							<p class="tit">随时存取</p>
							<p class="tit2">Easy access</p>
							<p class="tit3">作为理财工具，可随时向健康宝内存入资金，也可随时申请提现</p>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="row row3">
						<div class="left">
							<p class="tit">消费有礼</p>
							<p class="tit2">Free gifts with purchase</p>
							<p class="tit3" style="padding-left:25px;">作为空中医院服务的线上支付工具，购买服务和商品均可获得可抵扣现金的K币赠送</p>
							
						</div>
						<div class="middle">
							<img src="images/gifts.png">
						</div>
						<div class="right big">03</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
        <script src="js/jquery.min.js"></script>
        <script>
            $('ul li:nth-child(6)').addClass('selected');
        </script>
        <?php include "footer.php";?>
	</body>
</html>
