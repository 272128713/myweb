<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>众筹平台</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/sanlingyi/Public/Entity/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/orderConfirm.css" />

	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">订购确认</h1>
		</header>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->	<div class="textarea">
        		<div class="text">
                    <p class="title1"><?php echo ($v["clinic_name"]); ?></p>
                    <p class="title2"><?php echo ($v["type"]); ?></p>
                    <p class="title3"><?php echo ($v["address"]); ?></p>
				</div>
			</div>
		
			<div class="content">
                <?php if(is_array($v['doc'])): foreach($v['doc'] as $key=>$vo): if($vo['doctor_id']==$_GET['uid']){ $service_num=$vo['service_num']; $sale_num=$vo['sale_num']; ?>

                <div class="content-inner" >
                    <img src="<?php echo ($vo["img_url"]); ?>" style="border-radius:3px;" />
                    <div class="doctor_info">
                        <p class="xingming"><?php echo ($vo["user_name"]); ?>
                            <span class="orange"><?php echo ($vo["k_name"]); ?></span>
                            <span class="red">¥<?php echo ($vo["money"]); ?><span class="red" style="margin-left:1em"><?php echo ($vo["service_num"]); ?>/<?php echo ($vo["sale_num"]); ?></span></span>
                        </p>
                        <p class="keshi"><?php echo ($vo["recollection_name"]); ?><span class="yishi"><?php echo ($vo["duty_name"]); ?></span></p>
                        <div class="clear"></div>
                        <p class="address"><?php echo ($vo["hospital"]); ?> <?php echo ($vo["h_area"]); ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
                 <?php } endforeach; endif; ?>
			</div>
			<div class="img-hb">
				<img src="/sanlingyi/Public/Entity/images/huibao.png" id="hb" style="width:50%;">
			</div>
			<div class="service">
				<div class="serv">
					<p class="sd">标准化K服务</p>
				<div class="know">
					<a href="">
						<img src="/sanlingyi/Public/Entity/images/icon.png"/>
						<span>了解K服务</span>
					</a>
				</div>
				<div class="clear"></div>
				</div>
			</div>
			<div style="width:100%;height:20px;background:#e8e8e8">
			</div>
			<div class="service-other">
				<p class="serv">其他医疗服务</p>
			</div>
			<div class="jieshao">
				<p class="wenzi"><?php echo ($v["other_service_content"]); ?></p>
			</div>
			<div class="btn">
				<div class="btn1">
					<div class="canyu"> <a href="<?php echo U('optipon',array('id'=>$_GET['id'],'uid'=>$vo['doctor_id']));?>">购买健康险参与</a></div>
					<?php if($service_num<$sale_num){ ?>
                    <div class="buy"><a onclick="save_order()">确认订购</a></div>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
		<script src="/sanlingyi/Public/Entity/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/sanlingyi/Public/Entity/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function(){
				  var get_navi = $('.navi');
				  $(".xiala").click(function(){
				  		get_navi.slideToggle('fast');
				  });
				  $('.mui-content').click(function(){
				  		if(get_navi.css('display')=="block"){
				  		get_navi.slideUp('fast');
				  		}
				  });
				  $('.new-list li').hover(function(){
				  	$(this).css('background','#EFEFEF');
				  },function(){
				  	$(this).css('background','#fff');
				  });
				  
			});
			layer.config({
					    skin: 'demo-class'
					})
			function laconfirm(){
				layer.alert('<p>恭喜您拥有了自己的私人医生恭喜您拥有了自己的私人医生恭喜您拥有了自己的私人医生恭喜您拥</p>', {
					
					title:'恭喜您拥有了自己的私人医生',
					closeBtn: false,
					area: '90%'
					
				},function(index){
				    //do something
				    layer.close(index)
				});
			}

            function save_order(){
                var value="<?php echo ($_GET['uid']); ?>";
                var url="<?php echo U('MyChat/saveOrder');?>";
                $.post(url,{'dic':value},function (date){
                            if(date==1){
                                laconfirm()
                            }else{
                                //fail(date);
                            }

                        }
                        ,'text');
            }
		</script>
	</body>

</html>