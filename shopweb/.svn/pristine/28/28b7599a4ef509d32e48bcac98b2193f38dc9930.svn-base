<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医信健康</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/shopweb/Public/Home/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/app.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/shopweb/Public/Home/css/agency_getcash.css" />

	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="background:#e45335;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">提现</h1>
			<div class="right">
				<a ><img src="/shopweb/Public/Home/images/shezhi.png" class="jiluzixun" /></a>
			</div>
		</header>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
        <div class="mui-content">
        	<div class="jilu">
	        	<img src="/shopweb/Public/Home/images/getcash.png" class="jiluimg"/>
	        	<span class="jilup">
	        		账户信息
	        	</span>
        	</div>
        	<div class="bank">
        		开户银行：<?php echo ($bank_name); ?>
        	</div>
        	<div class="bank">
        		银行账户：<?php echo ($bank_num); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($account_name); ?>
        	</div>
        	<div class="cash">	
	        	<!--div class="circle">
	        		<p>可提现金额<br />￥<?php echo ($agency_money); ?></p>
	        	</div-->
	        	<div class="lingxing">
	        		<img src="/shopweb/Public/Home/images/lingxing.png" />
	        		<div class="jine">可提现金额<br />￥<?php echo ($agency_money); ?></div>
	        	</div>
	        	<div class="cash_btn">
	        		<img src="/shopweb/Public/Home/images/tixian.png"/>提现
	        	</div>
        	</div>
        	<div class="cash_head">
        		以下是全部提现记录
        	</div>
        	<div class="bg-line"></div>
        	<!--
            	作者：272128713@qq.com
            	时间：2015-08-18
            	描述：
            -->
            <?php if(is_array($cashlist)): foreach($cashlist as $key=>$vo): if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="cash_list">
		            	<p class="p1">流水号：<?php echo ($v["id"]); ?>
		            	
		            		<?php  switch($v['apply_status']){ case 0: echo "<span>待处理</span>"; break; case 1: echo "<span class='fin'>已处理</span>"; break; case 2: echo "<span class='wrong'>失败</span>"; break; } ?>
		            	</p>
		            	<p class="p2">￥<?php echo ($v["money"]); ?><span><?php echo ($v["apply_time"]); ?></span></p>
		            </div><?php endforeach; endif; endforeach; endif; ?>
        </div>
        
        
        
        
        
        
        
        <!--
        	作者：272128713@qq.com
        	时间：2015-08-18
        	描述：设置账户
        -->
        <div id="hid-pl" class="hid-pl">
			<div class="hid-title">
				<img src="/shopweb/Public/Home/images/jg.png"/>设置银行账户
			</div>
			<div class="hid-con">
				<form action="" method="post" id="bank">
					<input type="" name="" id="" value="" placeholder="请填写您的开户行信息" onclick="focus()" /><br />
					<input type="" name="" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" id="" value="" placeholder="请填写您的银行账户"/><br />
					<input type="" name="" id="" value="" placeholder="开户人的姓名"/>
				</form>
			</div>
			<div class="hid-sub bank-submit">
				完&nbsp;&nbsp;&nbsp;&nbsp;成
			</div>
		</div>
		
        <!--
        	作者：272128713@qq.com
        	时间：2015-08-18
        	描述：提现
        -->
        <div id="hid-pl-2" class="hid-pl-2">
			<div class="hid-title">
				<img src="/shopweb/Public/Home/images/jg.png"/>提现申请
			</div>
			<div class="hid-con-2">
				您提交了提现申请，申请金额为
				<form action="" method="post" id="bank-2">
					<input type="" name="" id="" value="" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="可提现余额20000.00元"/><br />
				</form>
				请核对您的账户信息
				<div class="bank-sys">
					<p>招商银行西安市雁塔区小寨支行</p>
					<p>银行账户:6228455617963433&nbsp;&nbsp;&nbsp;&nbsp;赵本山</p>
				</div>
			</div>
			<div class="hid-sub">
				<div class="confirm">
					确认无误
				</div>
				<div class="set-bank">
					设置银行账户
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<script src="/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function(){
				var get_circle_wid = $('.circle').width();
				$('.circle').height(get_circle_wid);
				$('.jiluzixun').click(function(){
					layer_sz();
				});
				$('.bank-submit').click(function(){
					$('#bank').submit();
				});
				$('.cash_btn').click(function(){
					layer_tx();
				});
				$('.confirm').click(function(){
					$('#bank-2').submit();
				});
				$('.set-bank').click(function(){
					
					layer.closeAll()
					layer_sz();
				});
			});			
			function layer_sz(){
				layer.open({
				    type: 1,
				    title:false,
				    closeBtn: false,
				    shadeClose:true,
				    area:'80%',
				    content: $('#hid-pl')
				});
			}			
			function layer_tx(){
				layer.open({
				    type: 1,
				    title:false,
				    closeBtn: false,
				    shadeClose:true,
				    area:'80%',
				    content: $('#hid-pl-2')
				});
			}
		</script>
	</body>

</html>