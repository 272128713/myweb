<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>商品详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/shopweb/Public/Home/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/user_product_detail.css" />
		<style>
			.jfbox{
				color:red;
			}
			.jfbox img{
				position:relative;
				top:3px;
			}
			.toggle-tc{
				color:#ff952b;
				font-size:12px;
				line-height:12px;
			}
		</style>
	</head>

	<body>
		<!--div class="head">
			<a onclick="history.go(-1)">
				<img src="/shopweb/Public/Home/images/arrowLeft.png" style="width:16px;position:relative;top:2px;" />
			</a>
			<span class="title" onclick="history.go(-1)">商品详情</span>
		</div-->
		<!--ul class="mui-table-view mui-table-view-chevron" style="margin-top:44px;">
				<li id="switch" class="mui-table-view-cell">
					定时轮播
					<div class="mui-switch">
						<div class="mui-switch-handle"></div>
					</div>
				</li>
		</ul-->
		<div id="slider" class="mui-slider" >
			<div class="mui-slider-group mui-slider-loop">
				<!-- 额外增加的一个节点(循环轮播：第一个节点是最后一张轮播) -->
				<div class="mui-slider-item mui-slider-item-duplicate">

				</div>
				<!-- 第一张 -->
				<?php if(is_array($result['goods_img_list'])): $i = 0; $__LIST__ = $result['goods_img_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$res): $mod = ($i % 2 );++$i;?><div class="mui-slider-item">
						<a href="#"  >
							<img src="http://210.14.72.62/shop_new/data/upload/shop/store/goods/1/<?php echo ($res["img_url"]); ?>"/>
						</a>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<!-- 额外增加的一个节点(循环轮播：最后一个节点是第一张轮播) -->
				<div class="mui-slider-item mui-slider-item-duplicate">
		
				</div>
			</div>
			<div class="mui-slider-indicator">
				<?php if(is_array($result['goods_img_list'])): $i = 0; $__LIST__ = $result['goods_img_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$res): $mod = ($i % 2 );++$i; if($i == 1): ?><div class="mui-indicator mui-active"></div>
					<?php else: ?>
						<div class="mui-indicator"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				
			</div>
		</div>
		
		<!-- 积分 -->
		<div class="jf" style="width:95%;margin:auto;margin-top:6px;border-top:1px solid #dfdfdf;border-bottom:1px solid #dfdfdf;font-size:13px;color:#8b8b8b;line-height:40px;height:40px;">		
					<?php if($allow_points==0){ ?>
					<div id='jfcheckbox' cc='0' style="">暂无积分<!--span style="color:red"> 如何获取积分？</span--></div>
					<div class="clear"></div>
					<?php }else{ ?>
					<!--div id='jfcheckbox' cc='0' class="mui-input-row mui-checkbox mui-left">
						<label style="font-size:13px;color:#777;line-height:14px">本单可用<?php echo ($allow_points); ?>积分，可以为您节省<?php echo ($allow_points*$point_x); ?>元</label>
						<input  name="checkbox" value="1" type="checkbox">
					</div-->
					<div id='jfcheckbox' cc='0' class="jfbox">
						<img src="/shopweb/Public/Home/images/check-none.png" style="width:16px;margin-right:5px "/><span>本单可用<?php echo ($allow_points); ?>积分，可以为您节省<?php echo ($allow_points*$point_x); ?>元</span>
					</div>
					<?php } ?>
		</div>
		<div class="pro_profile">
			<p class="title"><?php echo ($result["goods_name"]); ?></p>
			<div style="position:relative">
				<span style="font-size:12px;color:#666666">优惠价：</span><span class="oriprice">￥<?php echo ($result["goods_price"]); ?></span>
				<span class="moborder">手机下单</span><div style="float:right;line-height:24px;right:2.5%;font-size:12px;color:red">节省：￥<?php echo number_format ( $result['goods_marketprice']-$result['goods_price'],2 ) ?></div>
			</div>
			<div style="margin-top:11px;font-size:12px;line-height:12px;color:#666"><del>进店价格：￥<?php echo ($result["goods_marketprice"]); ?></del>
				<?php if($result['pakege_list']){ ?>
				<div class="toggle-tc" style="float:right;padding:10px;position:relative;top:-10px">
					<span>购买套餐</span><img style="width:13px;margin-left:6px;" src="/shopweb/Public/Home/images/down.png"/>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php if($result['pakege_list']){ ?>
		
			<div class="proshow">
			<?php if(is_array($result["pakege_list"])): $i = 0; $__LIST__ = $result["pakege_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p_goods): $mod = ($i % 2 );++$i;?><div class="prod" hf="<?php echo U('goodsDetial',array('id'=>$p_goods['goods_id']));?>">
					<img src="http://210.14.72.62/shop_new/data/upload/shop/store/goods/1/<?php echo ($p_goods["img_url"]); ?>" />
					<p class="proname"><?php echo ($p_goods["goods_name"]); ?></p>
					<p class="pri">￥<?php echo ($p_goods["goods_price_now"]); ?></p>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>	
				<div class="clear"></div>
				<div class="taocan">
					<div class="p2">套餐价：
						<b>￥<?php echo ($result["pakege_price"]); ?></b>
						<a onclick="buy_moreconfirm()" class="buybtn">购买套餐</a>
					</div>
					<div class="p3">
						<del>价格：￥<?php echo number_format($result['pakege_price']+$result['pakege_save'],2) ?></del>
						<span style="color:red;margin-left:2%">省：￥<?php echo ($result["pakege_save"]); ?></span>
						
					</div>
					
				</div>
			</div>
		<?php } ?>
		<div class="intro-title">
			<div class="rect"></div>
			<span class="title">产品介绍</span>
		</div>
		<div class="proinfo">
				<?php echo ($result["mobile_body"]); ?>
		</div>
		<div class="bottomsec">
			<div class="content">
				<a class="a1" onclick="startChats(<?php echo ($result['worker_id']); ?>)">
					<img src="/shopweb/Public/Home/images/zixun.png" />
					在线咨询
				</a>
				<script>
					var mobile="<?php echo ($result['worker_mobile']); ?>";
				</script>
				<a class="a1" style="border-right:0" onclick="callWorker(mobile)">
					<img src="/shopweb/Public/Home/images/call.png" />
					联系营养师
				</a>
				<a id="buy" class="active" onclick="buyconfirm()">
					立即购买
				</a>
				<div class="clear"></div>
			</div>
		</div>
		
		<script src="/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shopweb/Public/Home/js/mui.min.js"></script>
		<script src="/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" charset="utf-8">
		       $('#jfcheckbox').click(function(){
		   			if($('#jfcheckbox').attr('cc')==0){
		   				$('#jfcheckbox').attr('cc',1);
						$(this).children('img').attr('src','/shopweb/Public/Home/images/check-ed.png');
						$(this).children('span').html('本单已用<?php echo ($allow_points); ?>积分，已为您节省<?php echo ($allow_points*$point_x); ?>元');
		   			}else{
		   				$('#jfcheckbox').attr('cc',0);
						$(this).children('img').attr('src','/shopweb/Public/Home/images/check-none.png');
						$(this).children('span').html('本单可用<?php echo ($allow_points); ?>积分，可以为您节省<?php echo ($allow_points*$point_x); ?>元');
		   			}
		   		});
		       $('.toggle-tc').click(function(){
		    	   $(".proshow").toggle(); 
		    	   $('.jf').toggle();
		    	   var pro_dis = $('.proshow').css('display');
		    	   if(pro_dis=='none'){
		    	  	 $(this).children('img').attr('src','/shopweb/Public/Home/images/down.png');
		    	  	 $(this).children('span').html('购买套餐');
		    	   }else{
			    	 $(this).children('img').attr('src','/shopweb/Public/Home/images/up.png');
		    	  	 $(this).children('span').html('收起套餐');
						var get_pro_wid = $('.proshow img').width();
						$('.proshow img').height(get_pro_wid * 0.8);
		    	   }
		       });

            /**
             * 开始聊天
       
             */
            function startChats(uid){

                if(navigator.userAgent.match('iPhone')){
                    startChat(uid);
                }
                if(navigator.userAgent.match('Android')){
                    Android.startChat(uid);
                }
            }
            /**
             * 开始打电话
             */
            function callWorker(mobile){

                if(navigator.userAgent.match('iPhone')){
                    callWorker(mobile);
                }
                if(navigator.userAgent.match('Android')){
                    Android.callWorker(mobile);
                }
            }            
            /**
             * 购买
             */
            function buyconfirm(){
            	layer.confirm('确认购买？',{title:false,closeBtn: false},function(){
            		buy();
            	});
            }
            function buy_moreconfirm(){
            	layer.confirm('确认购买？',{title:false,closeBtn: false},function(){
            		buy_more();
            	});
            	
            }
            function buy(){
            	var url="<?php echo U('User/buyGoods');?>";
            	var	get_cc = $('#jfcheckbox').attr('cc');
            	
            	
            	var url="<?php echo U('User/buyGoods');?>";
            	$.get(
            		url,
            		{
            			"gid":<?php echo ($result["goods_id"]); ?>,
            			"is_pakage":0,
            			"use_poionts":get_cc
            		},
            		function(date){
            			if(date==1){
            				buy_true();
            			}else{
            				buy_false();
            			}
            		}
            	);
            	
            }
            function buy_more(){
            	//href="<?php echo U('buyGoods',array('gid'=>$result['goods_id'],'is_pakage'=>1,'use_poionts'=>0));?>"
            	var url="<?php echo U('User/buyGoods');?>";
            	$.get(
            		url,
            		{
            			"gid":<?php echo ($result["goods_id"]); ?>,
            			"is_pakage":1,
            			"use_poionts":0
            		},
            		function(date){
            			if(date==1){
            				buy_true();
            			}else{
            				buy_false();
            			}
            		}
            	);
            }
            
        </script>		
		<script type="text/javascript" charset="utf-8">
   		
		layer.config({
		    extend: ['skin/user/style.css'], //加载您的扩展样式
		    skin: 'layer-ext-user'
		});
			
			var slider = mui("#slider");
					slider.slider({
						interval: 2000
				});
			var get_slider_width = $('.mui-slider .mui-slider-group .mui-slider-item img').width();
			$('.mui-slider .mui-slider-group .mui-slider-item img').height(get_slider_width*5/6);
			
			
			
			//购买成功
            function buy_true(){
            	layer.alert('购买成功，请到店内领取商品',{title:false,closeBtn: false},function(){
            		//window.location.href="<?php echo U('buyRecord',array('ss'=>session('yixin_ss')));?>";
            		layer.closeAll();
            		goTobuyRecordA();
            		
            	});
            	}
            
			//购买失败
            function buy_false(){
            	layer.alert('您的可用余额不足，请到店内充值！',{title:false,closeBtn: false});
            	}
            //跳往记录页
            function goTobuyRecordA(){
            	Android.goTobuyRecord();
            }
            
			$('.prod').click(function(){
				var hf = $(this).attr('hf');

				window.location.href=hf;
					
				
			});
		</script>
		<script type="text/javascript" charset="utf-8">
			$(function(){
				var get_pro_wid = $('.proshow img').width();
				$('.proshow img').height(get_pro_wid * 0.8);
			});
		</script>
	</body>

</html>