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
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/staff_detail.css" />

	</head>
	<body>
		<div class="userinfo">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)" style="height:44px;line-height:44px;margin-left:-2.5%"></a>
			<h1 class="mui-title" style="color:#fff;font-size:18px;" onclick="history.go(-1)">会员详情</h1>
			<div class="right" style="margin-right:-2.5%">
				<span style="color: #fff;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;" onclick="startChats(<?php echo ($v["member_id"]); ?>)"><img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/chat.png" /></span>
			</div>

            <script>
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
            </script>
			<div class="clear"></div>
			<div class="user">
				<img class="userphoto" src="<?php echo getImg($v['thumbnail_image_url']);?>" />
			</div>
				<div class="moreinfo">
					<p>
						<span class="nam"><?php if_empty($v['member_truename']); ?></span>
						<img class="icon" src="/shop_skyhospital/trunk/shopweb/Public/Home/images/lv<?php echo ($v["member_level"]); ?>.png"/>
						<span class="sex"><?php if_empty($v['member_sex']); ?></span>
						<span class="year"><?php getDay($v['member_birthday']); ?></span>
					</p>
					<p><?php if_empty($v['member_address']); ?></p>
					<p>联系电话：<?php if_empty($v['member_name']); ?></p>
						<!--<p style="color: #fff;margin-right: 1em;">-->
							<!--骨质疏松-->
						<!--</p>-->
					<div class="doct" style="height:25px;line-height:25px;">
						<img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/doctor.png" />
						
						营养师：<?php if_empty(session('user_info.member_truename')); ?>
					</div>
				</div>
				<div class="clear"></div>
				<!--div class="otherinfo">
					<div class="s1">
						<img src="images/warning.png" style="width:20px;"/>
						<div class="s2">骨质疏松</div>
						<div class="clear"></div>
					</div>
					<div class="tel"><img src="images/tel.png" style="width:25px;" />15596851596</div>
					<div class="clear"></div>
					<div class="tx"><img src="images/list-img.png"  /></div>
				</div-->
		</div>
		<div class="consumRecord">
			<div class="retitle">
				<a href="">
					<span>消费记录</span>
				</a>
			</div>
			<div class="nav">
				
			</div>
			
			<div id="hid-confirm" class="hid-pl">
			<!--div class="hid-title">
				确认提货
			</div-->
			<div class="hid-con">
				<form class="goods_form" action="" method="post" id="bank">
					<input type="" name="" id="" value="" placeholder="请输入您的提货码" onclick="focus()" /><br />
				</form>
			</div>
			<div class="hid-sub bank-submit" id="sure_goods">
				确认提货
			</div>
		</div>
		
		
			<ul id="data_box">
                <?php if(is_array($cv)): foreach($cv as $key=>$vo): ?><input type="hidden" id="order_id" />
				<li class="consumList  delivery" id="str_content<?php echo ($vo["order_id"]); ?>">
					<img src="http://192.168.20.29/shop_skyhospital/shop_new/data/upload/shop/store/goods/1/<?php echo $vo['product_list'][0]['goods_image'] ?>" />
					<div class="listintro">
						<p class="name">
                            <?php if(count($vo['product_list'])>1){ echo $vo['pakage_name']; echo '('; foreach($vo['product_list'] as $k=>$v){ if($k!=0){ echo ','.$v['goods_name']; }else{ echo $v['goods_name']; } } echo ')'; }else{ echo $vo['product_list'][0]['goods_name']; } ?>
                        </p>
						<p class="p3"><span>￥<?php echo ($vo["order_amount"]); ?>
                            <?php if($vo['use_points']>0){ echo '('.$vo['use_points'].'积分)'; } ?>
                        </span>
						<p class="p5"><?php echo date('Y-m-d',$vo['add_time']) ?>购买
                        <?php if($vo['order_state']==20){ ?>
                        <span order_id="<?php echo ($vo["order_code"]); ?>" class="confirm_btn return_order" style="right: 18%;">退款</span><span class="confirm_btn get_order" style="right: 3%;">提货</span>
                        <?php }else{ ?>
                        <span style="background: none; color: #666"><?php echo get_order_staus($vo['order_state']) ?></span>
                        <?php } ?>
                        </p>
					</div>
					<div class="list-clear"></div>
				</li><?php endforeach; endif; ?>
			</ul>
            <?php if($nums>=C('PAGE_VAL')){ ?>
            <div id="order_list" onclick="get_more()" class="getmore">
                点击加载更多
            </div>
            <?php } ?>
			</div>
			<script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
            //layer

			$(function(){
				var get_circle_wid = $('.userphoto').width();
				var get_circle_wid1 = $('.tx img').width();
				$('.userphoto').height(get_circle_wid);
				$('.tx img').height(get_circle_wid1);
			});
		</script>
		<script type="text/javascript">
			$(function(){
				$('.get_order').click(function(){
                    $('#order_id').val($(this).parents('li').attr('id'));
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
				    content:$('#hid-confirm'),


				});
			}

            //进行提货
           $('#sure_goods').click(function(){
               var url="<?php echo U('getGoods');?>";
               var value=$('.goods_form input').val();
               var uid="<?php echo ($v["member_id"]); ?>";
               if(value.length!=11){
                   layer.msg('提货码长度不合法',{
                       offset:'80%'

                   });
                   return false;
               }
               layer.closeAll();
               layer.load(2)
               $.post(url,{"code":value,"uid":uid},function(data){
                    if(data==1){
                        layer.closeAll('loading');
                        var dom=$('#order_id').val();
                        layer.msg('提货成功');
                        $('#'+dom).find('.p5 .confirm_btn').replaceWith(' <span style="background: none; color: #666">已提货</span>');

                    }else{

                            layer.closeAll('loading');
                            layer.msg('提货失败请重试');

                    }
               },'text');
           });

            //进行退货
            $('.return_order').click(function(){
                $('#order_id').val($(this).parents('li').attr('id'));
                var value=$(this).attr('order_id')
                layer.confirm('您确定要取消订单吗？', {
                    btn: ['确定','取消'], //按钮
                    title:false,
                    closeBtn: false,
                    shadeClose:true,
                    area:'80%',
                    skin: 'worker-class'

                }, function(){
                    layer.closeAll();
                    layer.load(2);
                    var url="<?php echo U('returnGoods');?>";
                    var uid="<?php echo ($v["member_id"]); ?>";
                    $.post(url,{"code":value,"uid":uid},function(data){
                        if(data==1){
                            layer.closeAll('loading');
                            var dom=$('#order_id').val();
                            layer.msg('退款成功');
                            $('#'+dom).find('.p5 .confirm_btn').replaceWith(' <span style="background: none; color: #666">已取消</span>');

                        }else{
                            layer.closeAll('loading');
                            layer.msg('退款失败请重试');
                        }
                    },'text');
                }, function(){
                    layer.closeAll();
                });


            });
		</script>
        <script>
            //分页
            sanlingyi_page_a=2;
            function get_more(){
                layer.load(2);
                //加载消费记录
                var article=$('#data_box');
                var url="<?php echo U('Worker/getMoreOrder');?>";
                var uid="<?php echo ($v["member_id"]); ?>";
                $.get(
                        url,{"page":sanlingyi_page_a,"uid":uid},function(date){
                            if(date!=0){
                                sanlingyi_page_a+=1;
                                article.append(date);
                                // alert(date);
                                layer.closeAll('loading');
                            }else{
                                $('#order_list').html('暂无更多')
                            }
                        }
                        ,'html' );


            }
        </script>

    </body>

</html>