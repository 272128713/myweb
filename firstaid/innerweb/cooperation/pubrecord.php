<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>发布记录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="./css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/pubrecord.css" />
        <?php include "php/org_active_get.php";?>
	</head>
	
	<body>
		<div class="content">
            <?php if(!$result){


                echo "<div style='text-align:center;margin-top:40px;line-height: 74px;font-size: 14px; color:#333;'>暂无记录</div>";

            }?>
            <?php foreach($result as $k=>$v){?>
                <div class="pro_list" onclick="window.location.href='result.php?aid=<?php echo $v->id;?>&state=<?php echo $v->apply_state;?>&oid=<?php echo $_GET['oid'];?>'">
                    <div class="pic">
                        <img src="<?php echo $v->img_url;?>">
                    </div>
                    <div class="proinfo">
                        <p class="pro_name"><?php echo $v->name;?></p>
                        <p class="time">
                            <img src="images/clock.png" /><?php echo date("Y.m.d",strtotime($v->activity_time_begin))."-".date("Y.m.d",strtotime($v->activity_time_finish));?>
                        </p>
                    </div>
                    <div class="clear"></div>
                    <div class="bott">
                        <p class="left">
                            <img src="<?php echo $logo_url;?>"><?php echo $orgname;?>
                        </p>
                        <p class="right">
                            <?php
                                if($v->apply_state === '0'){
                                    echo "<span style='color:#4caf50'>活动审核中</span>";
                                }elseif($v->apply_state=='1'){
                                    echo "<img src='images/finish.png' style='width: 14px;position:relative;top: 2px;right: 1px'/><span style='color:#4caf50'>活动已批准</span>";
                                }elseif($v->apply_state=='2'){
                                    echo "<img src='images/pass.png' style='width: 14px;position:relative;top: 2px;right: 1px'/><span style='color:#dd403b'>活动未通过</span>";
                                }
                            ?>
                        </p>
                        <div class="clear"></div>
                    </div>
                </div>
            <?php }?>
		</div>
		<script src="js/jquery.min.js"></script>
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