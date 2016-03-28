<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>用户查找结果</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

        <link rel="stylesheet" href="/sanlingyi/Public/Consult/css/mui.min.css">
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/common.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/app.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/search.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/iconfont.css"/>

	</head>

	<body>
    <?php $is_doc=M('user_base_info',null)->where(array('user_id'=> session('yixin_user')))->getField('user_type_id'); ?>
        <style>
            .sarch_list{ padding: 0; margin: 0;}
            .sarch_list li{ padding: 0; margin: 0;}
        </style>
		<header class="mui-bar mui-bar-nav" style="position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="" onclick="history.go(-1)"><?php echo_title(); ?></h1>
            <div class="right">

                <?php
 if($is_doc==2){ ?>
             
                <?php
 }else{ ?>
                <a onclick="go_to_app_consult('')"><img src="/sanlingyi/Public/Consult/images/consult.png" class="jiluzixun" /></a>
                <a href="<?php echo U('SelfConsult/index');?>"><img src="/sanlingyi/Public/Consult/images/jilu.png" class="jiluzixun" /></a>

                <?php
 } ?>
            </div>
		</header>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
		<div class="mui-content">
		<!--
        	时间：2015-06-29
        	描述：选项卡
        -->
        <div class="cc left">

            <form id="search" method="get" name="search"
                  <?php
 if($is_doc==2){ ?>
                  action="<?php echo U('Index/docindex');?>"
                  <?php
 }else{ ?>
                     action="<?php echo U('Index/index');?>"
                    <?php
 } ?>
                    >
			<input type="search"  name="svalue" required="" placeholder="请输入相关症状或疾病" lang="zh-CN">
            </form>
			<img src="/sanlingyi/Public/Consult/images/sousuo1.png" style="width:24px;height: 24px;position: relative;top:-2.6em;left:0.4em"/>

            <div class="mui-content">
                <ul class="mui-table-view">

                    <?php if(is_array($hs)): $i = 0; $__LIST__ = $hs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell hlist">
                            <?php
 if($is_doc==2){ ?>
                            <a href="<?php echo U('Index/docindex',array('svalue'=>urlencode($vo)));?>">
                            <?php
 }else{ ?>
                            <a href="<?php echo U('Index/index',array('svalue'=>urlencode($vo)));?>">
                            <?php
 } ?>
                              <?php echo ($vo); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <li id="delete" class="mui-table-view-cell" style="color: #0062cc;">
                        <span class="mui-icon mui-icon-trash"></span>清除历史
                    </li>
                </ul>
            </div>
	     </div>	
	    <a  id="submit"><div class="search right">搜索</div></a>

		</div>
        <script>
            document.getElementById("submit").onclick=function(){
                document.getElementById("search").submit();
            }
        </script>
        <script src="/sanlingyi/Public/Article/js/jquery-1.9.1.min.js"></script>
        <script>
            $('#delete').click(function () {
                var url="<?php echo U('delete');?>"
                $.post(url,{},function(data){
                    if(data==1){
                        $('.hlist').remove();
                    }
                },'text');
            })
        </script>
    <script src="/sanlingyi/Public/Consult/js/app_com.js"></script>
	</body>

</html>