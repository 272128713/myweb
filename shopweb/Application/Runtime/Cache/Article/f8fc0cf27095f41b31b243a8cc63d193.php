<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>搜索</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/actcontent.css"/>
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/iconfont.css"/>
	</head>

	<body>
    <style>
        ::-webkit-input-placeholder { /* WebKit browsers */
            color:    #d8d8d8;
        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color:    #d8d8d8;
        }
        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color:    #d8d8d8;
        }
        :-ms-input-placeholder { /* Internet Explorer 10+ */
            color:    #d8d8d8;
        }
    </style>
		<header class="mui-bar mui-bar-nav" style="background:#23272a;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff">
                <div class="mui-input-row mui-search">
                    <form action="<?php echo U('Index/search');?>" method="post">
                        <input  type="search" size="80%" name="value" class="mui-input-clear" placeholder="在此输入搜索内容">
                        <input type="hidden" name="search" value="1" />
                    </form>

                </div>
            </h1>
		</header>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
		<div class="mui-content">
            <ul class="mui-table-view">

                <?php if(is_array($hs)): $i = 0; $__LIST__ = $hs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell hlist">
                      <a href="<?php echo U('Index/search',array('value'=>urlencode($vo)));?>">  <?php echo ($vo); ?></a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                <li id="delete" class="mui-table-view-cell" style="color: #0062cc;">
                    <span class="mui-icon mui-icon-trash"></span>清除历史
                </li>
            </ul>
        </div>
        <script src="/shop_skyhospital/trunk/shopweb/Public/Article/js/jquery-1.9.1.min.js"></script>
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
    </body>

</html>