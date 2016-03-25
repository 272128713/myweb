<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>志愿者</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="./css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/volunteerinfo.css" />
        <?php include "php/org_userdetail.php";?>
	</head>
	<body>

    <div class="container">
        <div class="grxx">
            <img class="tx" src="../common/img/default.jpg" />
            <p class="name"><?php echo $result->name;?><img src="images/<?php if($result->sex==0){echo "female";}else{ echo "male";}?>.png"></p>
            <p class="age"><span><?php echo date('Y',time())-date('Y',strtotime($result->birthday))+1; ?>岁</span><?php echo $result->nation; ?></p>
        </div>
        <div class="border"></div>
        <ul>
            <li>
                <img src="images/add.png">
                <p class="p2">家庭住址：<?php echo $result->address;?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/call.png">
                <p class="p2">联系电话：<?php echo $result->phone;?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/email.png">
                <p class="p2">联系邮箱：<?php echo $result->email;?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/edu.png">
                <p class="p2">教育程度：<?php echo $eduVal[$result->education];?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/zhuanye.png">
                <p class="p2">专业特长：<?php echo $result->speciality;?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/school.png">
                <p class="p2">学校/单位：<?php echo $result->company;?></p>
                <div class="clear"></div>
            </li>
            <li>
                <img src="images/xin.png">
                <p class="p2">志愿经历：<?php echo $result->undergo;?></p>
                <div class="clear"></div>
            </li>
        </ul>
    </div>
		<script type="text/javascript">
		    //浏览器适配 兼容所有浏览器 rem单位专用 设置html root 字号
		    (function (doc, win) {
		        var docEl = doc.documentElement,
		            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
		            recalc = function () {
		                var clientWidth = docEl.clientWidth;
		                if (!clientWidth) return;
		                docEl.style.fontSize = 100 * (clientWidth / 320) + 'px';
		            };
		        if (!doc.addEventListener) return;
		        win.addEventListener(resizeEvt, recalc, false);
		        doc.addEventListener('DOMContentLoaded', recalc, false);
		    })(document, window);
		</script>
	</body>
</html>
