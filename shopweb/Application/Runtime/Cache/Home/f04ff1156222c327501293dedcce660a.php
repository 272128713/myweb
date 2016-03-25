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
        <link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Home/css/agency_detail_staff.css" />

	</head>
    <body>
    <header class="mui-bar mui-bar-nav" style="background:#e45335;position: fixed;">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
        <h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">店面详情</h1>

        <div class="right">
            <a ><img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/newuser.png" class="jiluzixun"/></a>
        </div>
    </header>
    <!--
        时间：2015-06-29
        描述：主要内容
    -->
    <div class="mui-content">
        <div class="detail-head">
            <p class="p1">西安市莲湖区土门社区店</p>
            <p class="p2">西安市莲湖区丰登路138号</p>
            <p class="p2">开张日期：2014-08-15</p>
            <p class="p2">累计销售额：<span>￥245800.00</span></p>
        </div>

    </div>
    <div class="nav"></div>
    <div class="content">
        <div class="retitle">
            <a href="agency_detail_staff.html" class="a1 active">
                员工22
            </a>
            <a href="agency_detail_user.html" class="a1">
                用户160
            </a>
            <a href="agency_detail_agency.html">
                销售记录
            </a>
            <div class="clear"></div>
        </div>
        <ul>
            <li class="assign">
                <div class="aleft">
                    <img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/headimg.png" />
                    <span class="xm">宋丹丹</span>
                    <span class="zw">店长</span>
                </div>
                <div class="right" style="position:relative;top:-22px;">
                    <span class="gl">管理用户(72/100)</span>
                    <div class="mui-icon mui-icon-arrowright right arrow" style="position:relative;top:-36px;">
                    </div>
                    <div class="clear"></div>
            </li>
            <li class="assign">
                <div class="aleft">
                    <img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/headimg.png" />
                    <span class="xm">宋丹丹</span>
                </div>
                <div class="right" style="position:relative;top:-22px;">
                    <span class="gl">管理用户(72/100)</span>
                    <div class="mui-icon mui-icon-arrowright right arrow" style="position:relative;top:-36px;">
                    </div>
                    <div class="clear"></div>
            </li>
            <li class="assign">
                <div class="aleft">
                    <img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/headimg.png" />
                    <span class="xm">宋丹丹</span>
                </div>
                <div class="right" style="position:relative;top:-22px;">
                    <span class="gl">管理用户(72/100)</span>
                    <div class="mui-icon mui-icon-arrowright right arrow" style="position:relative;top:-36px;">
                    </div>
                    <div class="clear"></div>
            </li>
        </ul>
    </div>


    <!--
        作者：272128713@qq.com
        时间：2015-08-20
        描述：新增员工
    -->
    <div id="hid-pl" class="hid-pl">
        <div class="hid-title">
            <img src="/shop_skyhospital/trunk/shopweb/Public/Home/images/warning.png"/>新增员工
        </div>
        <div class="hid-con">
            <form action="<?php echo U('addWoker');?>" method="post" id="bank">
                <input type="" name="mobile" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" id="" value="" placeholder="请填写会员的手机号码"/><br />
            </form>
        </div>
        <div class="hid-sub bank-submit">
            完&nbsp;&nbsp;&nbsp;&nbsp;成
        </div>
    </div>
    <script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/shop_skyhospital/trunk/shopweb/Public/Home/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $('.jiluzixun').click(function(){
            layer_sz();
        });
        $('.bank-submit').click(function(){
            $('#bank').submit();
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
    </script>
    </body>

</html>