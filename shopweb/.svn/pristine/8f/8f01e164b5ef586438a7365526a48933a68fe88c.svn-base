<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>用户查找结果</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="/sanlingyi/Public/Consult/css/mui.min.css">
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/app.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/common.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/doctorresult.css"/>
        <link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/iconfont.css"/>
        <script src="/sanlingyi/Public/Article/js/jquery-1.9.1.min.js"></script>
        <script src="/sanlingyi/Public/Consult/js/app_com.js"></script>
	</head>

	<body>

		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings('')"></a>
			<h1 class="mui-title" style="color:#fff" onclick="logoutConsultings('')">在线义诊</h1>
            <div class="right">
                <!--<a href="<?php echo U('SelfConsult/index');?>"><img src="/sanlingyi/Public/Consult/images/jilu.png" class="jiluzixun" /></a>-->
                <a href="<?php echo U('Search/index');?>"><img src="/sanlingyi/Public/Consult/images/shaixuan.png" class="jiluzixun" /></a>
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
	        <div class="mui-pull-left mui-segmented-control cc">
				<a href="<?php echo U('docindex',array('svalue'=>$search));?>" class="mui-control-item <?php if(!isset($_GET['type'])) echo 'mui-active'; ?>">未解决</a>
				<a href="<?php echo U('docindex',array('type'=>1,'svalue'=>$search));?>" class="mui-control-item <?php if($_GET['type']==1) echo 'mui-active'; ?> ">已解决</a>
				<a href="<?php echo U('docindex',array('type'=>2,'svalue'=>$search));?>" class="mui-control-item <?php if($_GET['type']==2) echo 'mui-active'; ?> ">找我的</a>
                <a href="<?php echo U('docindex',array('type'=>3,'svalue'=>$search));?>" class="mui-control-item <?php if($_GET['type']==3) echo 'mui-active'; ?> ">我的回答</a>
                <?php $type=$_GET['type']; ?>
            </div>
            <?php if($nums==0){ echo_empty(); } ?>
			<div class="clear ">
                <?php if($nums>=C('PAGE_NUM')){ ?>
                <style type="text/css" media="all">
                    #wrapper {
                        position:absolute; z-index:1;
                        top:100px; bottom:0px; left:0;
                        width: 98%;
                        background:none;
                        overflow:auto;
                    }
                </style>
                <div id="wrapper">
                    <div id="scroller">
                        <div id="pullDown" style="text-align: center;padding: 10px 0; color:#555;">
                            <span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新</span>
                        </div>
                <?php } ?>
				<ul class="mui-table-view" id="thelist">
                    <?php if(is_array($ask)): foreach($ask as $key=>$v): ?><li class="mui-table-view-cell">
				    <a href="<?php echo U('Index/detail',array('aid'=>$v['question_id']));?>" class="mui-navigate-right">
				      <img src="/sanlingyi/Public/Consult/images/wen.png" class="wen"/>
				      <div class="ti"><?php echo ($v["title"]); ?><span>（<?php echo get_sex($v['sex']); ?>，<?php echo ($v["age"]); ?>岁）</span>
                          <?php if($v['is_see_doctor']==0){ ?>
                          <div class="clear tifoot">未就医
                              <?php }else{ ?>
                              <div class="clear tifoot"><?php echo ($v["recollection"]); ?>、<?php echo ($v["disease"]); ?>
                                  <?php } ?>
				      		<div class="right"><?php echo substr($v['createDate'],0,10); ?>&nbsp;&nbsp;&nbsp;&nbsp;回复 <span class="red"><?php echo ($v["answer_num"]); ?></span></div>
				      	</div>
				      </div>
				    </a>
				  </li><?php endforeach; endif; ?>

				</ul>
                        <?php if($nums>=C('PAGE_NUM')){ ?>
                        <div id="pullUp" style="text-align: center;padding: 10px 0; color:#555;">
                            <span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span>
                        </div>
                    </div>
                </div>
                        <?php } ?>


		</div>
        </div>

        <?php if($nums>=C('PAGE_NUM')){ ?>

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
                //查询更多
               var article=$('#thelist');
                var url="<?php echo U('index/docindex','','');?>";
                var type="<?php echo ($type); ?>";
                var search="<?php echo urldecode($search); ?>";
               $.post(
                        url,{"update":1,"type":type,"ajax":1,"svalue":search},function(date){
                           article.html('');
                           article.html(date);
                           myScroll.refresh();
                       }
                        ,'text')
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
                var url="<?php echo U('index/docindex','','');?>";
                var type="<?php echo ($type); ?>";
                var search="<?php echo urldecode($search); ?>";
                $.post(
                        url,{"page":sanlingyi_page,"type":type,"ajax":1,"svalue":search},function(date){

                            if(date!=0){
                                sanlingyi_page+=1;
                                article.append(date);
                                myScroll.refresh();
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
        <?php } ?>

	</body>

</html>