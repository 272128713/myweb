<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>阅读内容</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/sanlingyi/Public/Article/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Article/css/app.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Article/css/actcontent.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Article/css/iconfont.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Article/css/pull.css"/>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff">我的评论</h1>
		</header>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
		<div class="mui-content" >
		   <div style="margin-top:0.8em">

        		<img src="<?php echo $pic ;?> " class="mui-pull-left com-img" style="border:1px solid #30C6DF"/>
        		<div style="margin-left:3.8em;padding-top: 0.5em;">
        			<div><?php echo ($me["user_name"]); ?></div>
        			<div>
        				<span class="mui-icon mui-icon-location" style="font-size: 14px;color:#666666"> <?php echo ($province["name"]); ?> <?php echo ($city["name"]); ?></span>
        			</div>
        		</div>
		   </div>
            <div class="leftline mui-pull-left" ></div>
            <?php
 $count=count($com); $page=C('PAGE_NUM'); ?>
            <?php
 if($count>=$page){ ?>
            <div id="wrapper">
                <div id="scroller">
                    <?php
 } ?>
            <div id="pullDown" style="text-align: center;padding: 10px 0; color:#555; visibility: hidden;  ">
                <span class="pullDownIcon"></span><span class="pullDownLabel"></span>
            </div>
		   <ul class="rightli" id="thelist">
               <?php if(is_array($mc)): foreach($mc as $key=>$v): ?><li class="libo" >
					<div class="mui-pull-left tu">
					</div>
					<div class="wen">
						<?php echo ($v["content"]); ?>
						<div class="ltitle">
							<?php echo ($v["article_from"]); ?>：<?php echo ($v["title"]); ?>
							<div class="">
								<span class="mui-pull-left">
										<?php echo ($v["create_time"]); ?>
								</span>
								
								<div class="mui-pull-right">
									<span class=" iconfont icon-zan blue" style="font-size:14px"></span>(<?php echo ($v["up_num"]); ?>)
									<span class ="delete_evalu" aid="<?php echo ($v["article_id"]); ?>" eid="<?php echo ($v["id"]); ?>"><span  class=" mui-icon mui-icon-trash blue" style="font-size:16px;"></span>删除</span>
								</div>
							</div>
						</div>
					</div>		
		   	</li><?php endforeach; endif; ?>

		   </ul>
                    <?php
 if($count>=$page){ ?>
            <div id="pullUp" style="text-align: center;padding: 10px 0; color:#555;">
                <span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span>
            </div>
                </div>
            </div>
            <?php
 } ?>
		</div>
        <script src="/sanlingyi/Public/Article/js/jquery-1.9.1.min.js"></script>
        <?php
 if($count>=$page){ ?>
        <script src="/sanlingyi/Public/Article/js/iscroll.js"></script>
        <script type="text/javascript">
            var myScroll,
                    pullDownEl, pullDownOffset,
                    pullUpEl, pullUpOffset,
                    generatedCount = 0;

            /**
             * 下拉刷新 （自定义实现此方法）
             * myScroll.refresh();		// 数据加载完成后，调用界面更新方法
             */
            function pullDownAction () {
               // sanlingyi_page=0;
                myScroll.refresh();
            }

            /**
             * 滚动翻页 （自定义实现此方法）
             * myScroll.refresh();		// 数据加载完成后，调用界面更新方法
             */
            sanlingyi_page=1;
            function pullUpAction () {
                //查询更多
                var article=$('#thelist');
                var url="<?php echo U('MyChat/more','','');?>";

                $.post(
                        url,{'page':sanlingyi_page},function(date){

                            if(date!=0) {
                                sanlingyi_page=sanlingyi_page+1;
                                article.append(date);
                                myScroll.refresh();
                                //alert(date);
                            }else{
                                myScroll.refresh();
                            }
                        }
                        ,'text' );

            }

            /**
             * 初始化iScroll控件
             */
            function loaded() {
                pullDownEl = document.getElementById('pullDown');
                pullDownOffset = pullDownEl.offsetHeight;
                pullUpEl = document.getElementById('pullUp');
                pullUpOffset = pullUpEl.offsetHeight;

                myScroll = new iScroll('wrapper', {
                    scrollbarClass: 'myScrollbar', /* 重要样式 */
                    useTransition: false, /* 此属性不知用意，本人从true改为false */
                    topOffset: pullDownOffset,
                    onRefresh: function () {
                        if (pullDownEl.className.match('loading')) {
                            pullDownEl.className = '';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
                        } else if (pullUpEl.className.match('loading')) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                        }
                    },
                    onScrollMove: function () {
                        if (this.y > 5 && !pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'flip';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '松手开始更新...';
                            this.minScrollY = 0;
                        } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                            pullDownEl.className = '';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
                            this.minScrollY = -pullDownOffset;
                        } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                            pullUpEl.className = 'flip';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
                            this.maxScrollY = this.maxScrollY;
                        } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                            this.maxScrollY = pullUpOffset;
                        }
                    },
                    onScrollEnd: function () {
                        if (pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'loading';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
                            pullDownAction();	// Execute custom function (ajax call?)
                        } else if (pullUpEl.className.match('flip')) {
                            pullUpEl.className = 'loading';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
                            pullUpAction();	// Execute custom function (ajax call?)
                        }
                    }
                });

                setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
            }

            //初始化绑定iScroll控件
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
            document.addEventListener('DOMContentLoaded', loaded, false);



        </script>
        <?php
 }?>



    </body>

	<script type="text/javascript">
		$(function(){
			var ulheight = $(".mui-content").height();
			$(".leftline").height(ulheight-43);
		});
        $
        //删除评论
        $('.delete_evalu').click(function(){
            var prents_node=$(this).parents('.libo');
            var eid=$(this).attr('eid');
            var aid=$(this).attr('aid');
            var url="<?php echo U('delete');?>";
            $.post(url,{'eid':eid,'aid':aid}, function (data) {
                if(data==1){
                    prents_node.remove();
                }
            },'text');
        });


	</script>
</html>