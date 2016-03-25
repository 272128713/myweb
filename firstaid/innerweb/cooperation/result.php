<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>审核结果</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="./css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/result.css" />
	</head>
	
	<body>
		<div class="content">
            <?php if($_GET['state']==0){?>
			<div class="result-list">
				<p class="title">公益活动审核中</p>
				<p class="p2">你发布的公益活动已经提交，请等待审核结果。</p>
				<div class="choose">
					<p class="goback">返回</p>
				</div>
			</div>
            <?php }?>
            <?php if($_GET['state']==2){?>
            <div class="result-list">
				<p class="title">公益活动审核中</p>
				<p class="p2">对不起，你发布的公益活动存在问题，申请未被批准。</p>
				<div class="choose">
<!--					<div class="lpart">放弃重申请</div>-->
					<div class="rpart" style="width: 100%">修改再申请</div>
					<div class="clear"></div>
				</div>
			</div>
            <?php }?>
            <?php if($_GET['state']==1){?>
            <div class="result-list">
				<p class="title">申请已批准</p>
				<p class="p2">恭喜你，你发布的公益活动已批准正式上线，申请的赞助款项，相关人员会与你取得联系安排放款事宜。</p>
				<div class="choose">
					<p class="goback">返回</p>
				</div>
			</div>
            <?php }?>
        </div>
		<script src="js/jquery.min.js"></script>
		<script>
			$('.goback').click(function(){
				history.back(-1);
    		});
//			$('.lpart').click(function(){
//				window.location.href="publish.php?oid=<?php //echo $_GET['oid'];?>//";
//    		});
    		$('.rpart').click(function(){
                if(isiphone=navigator.userAgent.indexOf("iPhone")>0){
                    window.location.href="publish.php?oid=<?php echo $_GET['oid'];?>&aid=<?php echo $_GET['aid'];?>";
                }

                if(isAndroid=navigator.userAgent.indexOf("Android")>0){
                    if(navigator.userAgent.match('Android')){
                        Android.gotoPublish(<?php echo $_GET['oid'];?>,<?php echo $_GET['aid'];?>);
                    }
                }
    		});
		</script>
	</body>
	

</html>