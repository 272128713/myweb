<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医信商城</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/staff_list.css" />

	</head>
	<body>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="goToUserA()"></a>
			<h1 class="mui-title" style="color:#fff" onclick="goToUserA()">我的会员</h1>
			<div class="right">
				<img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/chat.png" onclick='goToChatallA()'  style="width:48px;height:48px;margin-top:-5px"/>
			</div>
		</header>
		<p class="sec-top">
			已管理会员<?php echo ($now_num); ?>人，还能管理<?php echo ($has_num); ?>人
		</p>
		<ul id="data_box">
            <?php if(is_array($rs)): foreach($rs as $key=>$v): ?><li class="userinfo" onclick="jump()" url="<?php echo U('oneMember',array('uid'=>$v['member_id']));?>">
				<img class="userphoto" src="<?php echo getImg($v['thumbnail_image_url']);?>" />
				<div class="moreinfo">
					<p>
						<span class="nam">
                            <?php if_empty($v['member_name']); ?>
                        </span>
						<img class="icon" src="/shop_skyhospital/trunk/shopweb/Public/Home/images/lv<?php echo ($v["member_level"]); ?>.png"/>
						<span class="sex"><?php if_empty($v['member_sex']); ?></span>
						<span class="year"><?php getDay($v['member_birthday']); ?></span>
					</p>
					<p style="color:#000;"><?php if_empty($v['member_address']); ?></p>
				</div>
				<div class="clear"></div>
			</li><?php endforeach; endif; ?>

		</ul>
		<?php if($nums>=C('PAGE_VAL')){ ?>
<div id="order_list" onclick="get_more()" class="getmore">
    点击加载更多
</div>
<?php } ?>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/common.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function(){
				var get_circle_wid = $('.userphoto').width();
				var get_circle_wid1 = $('.tx img').width();
				$('.userphoto').height(get_circle_wid);
				$('.tx img').height(get_circle_wid1);
			});
		</script>
		<script type="text/javascript">
            function jump(){
                $('.userinfo').click(function(){
                    var url=$(this).attr('url');
                    window.location.href=url;
                });
            }
			$(function(){
				$('.confirm_btn').click(function(){
					layer_confirm();
				});


			});				
			function layer_confirm(){
				layer.open({
				    type: 1,
				    title:false,
				    closeBtn: false,
				    shadeClose:true,
				    area:'80%',
				    content:$('#hid-confirm')
				});
			}
		</script>
		<script>
    sanlingyi_page_a=2;
    function get_more(){
            layer.load(2)
            //加载消费记录
            var article=$('#data_box');
            var url="<?php echo U('Worker/allMember');?>";
            $.get(
                    url,{"page":sanlingyi_page_a},function(date){
                        if(date!=0){
                            sanlingyi_page_a+=1;
                            article.append(date);
                            layer.closeAll('loading');
                           // alert(date);

                        }else{
                            $('#order_list').html('暂无更多')
                        }
                    }
                    ,'html' );

       
    }
</script>        
		
	</body>

</html>