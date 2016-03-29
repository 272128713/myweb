<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>转送稻友</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <link rel="stylesheet" href="css/mui.min.css">
	    <link rel="stylesheet" type="text/css" href="css/app.css" />
	    <link rel="stylesheet" type="text/css" href="css/common.css" />
	    <link rel="stylesheet" type="text/css" href="css/transfer.css" />
        <?php include "php/get_friends_base_info.php"; ?>
	</head>
	<body>
		<div class="mui-content" style="margin-bottom: 42px">
			<p class="title">请选择要转送的稻友</p>
			<ul>
                <?php foreach($result as $k=>$v){?>
				<li vl="<?php echo $v->uid;?>" sl="">
					<div class="name"><?php echo $v->name;?></div>
					<span></span>
				</li>
                <?php }?>
			</ul>
		</div>
        <div class="button" style="">确定转送</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/layer/layer.js"></script>
		<script>
		    $('li').click(function(){
		        sl = $(this).attr("sl");

		        if(sl != 1){
		            css = $(this).children('span');
                    $("li").children('span').css("background-image","url('./images/xuan.png')");
		            css.css("background-image","url('./images/xuan1.png')");
                    $("li").attr("sl",0);
		            $(this).attr("sl","1");
		        }else if(sl==1){
		            css = $(this).children('span');
		            css.css("background-image","url('./images/xuan.png')");
		            $(this).attr("sl","0");
		        }
		    });
		    $('.button').click(function(){
                $('li').each(function(i){
                    sl = $(this).attr("sl");
                    if(sl==1){
                        vl = $(this).attr("vl");
                        $.post("php/sendToother.php",{order_nums:<?php echo $_GET['on'];?>,acc:vl},function(data){
                            if(data.code==1){
                                layer.msg("赠送成功",{time:1000},function(){
                                    window.location.replace('records.php');
                                });

                            }
                        },'json');
                    }

                });

    		});
		
		</script>

	</body>
</html>