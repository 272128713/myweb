<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医信商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/shopweb/Public/Home/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/video-js.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/user_video.css" />
	</head>
	<body>
		<header class="mui-bar mui-bar-nav" style="background:#0ac9c2;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="goToUserA()"></a>
			<h1 class="mui-title" style="color:#fff" onclick="goToUserA()">微访谈</h1>
		</header>
		<div class="mui-content" id="video-box">
			<?php if(is_array($result)): foreach($result as $key=>$v): ?><div class="video-list">
					<video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto"  poster="/shopweb/Public/Home/images/product/QQ20150819134521.jpg" data-setup="{}" width="100%">
					<source src="<?php echo ($v["video_url"]); ?>" type='video/mp4'>
					</video>
					
					<div class="list-p">
						<?php echo ($v["video_title"]); ?>
					</div>
				</div><?php endforeach; endif; ?>
			
		</div>
		<?php if(count($result)>=C('PAGE_VAL')){ ?>
			<div id="order_list" onclick="get_more()" class="getmore">
			点击加载更多
			</div>
			<?php } ?>
        
        
		<script src="/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shopweb/Public/Home/js/common.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shopweb/Public/Home/js/video.js"></script>
		<script type="text/javascript" charset="utf-8">
			 sanlingyi_page_a=2;
			    function get_more(){
			            //加载
			            var article=$('#video-box');
			            var url="<?php echo U('User/videoAjax');?>";
			            $.get(
			                    url,{"page":sanlingyi_page_a,"ajax":1},function(date){
			                        if(date!=0){
			                            sanlingyi_page_a+=1;
			                            article.append(date);
			                            //alert(date);
			
			                        }else{
			                            $('#order_list').html('暂无更多')
			                        }
			                    }
			                    ,'html' );
			    }
		 </script>
	</body>

</html>