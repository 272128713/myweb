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
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Entity/css/insurance.css" />

	</head>

	<body >
	
		  <?php if(!isset($_GET['back'])){ ?>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">保险产品</h1>

			<div class="right" style="width:44px;padding:11px 11px 0 11px;">
				<img class="xiala" src="/sanlingyi/Public/Entity/images/topmore.png"  />
			</div>
		</header>
		 <?php }else{ ?>
            	<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings()"></a>
			<h1 class="mui-title" style="color:#fff" onclick="logoutConsultings()">保险产品</h1>

			<div class="right" style="width:44px;padding:11px 11px 0 11px;">
				<img class="xiala" src="/sanlingyi/Public/Entity/images/topmore.png"  />
			</div>
		</header>
		<script>
            /**
             * 返回app
             */
            function logoutConsultings(){

                if(navigator.userAgent.match('iPhone')){
                    logoutConsulting();
                }
                if(navigator.userAgent.match('Android')){
                    Android.logoutConsulting();
                }
            }
        </script>
         <?php } ?>   
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
		<div class="navi" >
			<div class="bg"></div>
            <?php if(is_array($com)): foreach($com as $key=>$vo): ?><p>
                    <a href="<?php echo U('cate',array('cid'=>$_GET['cid'],'coid'=>$vo['id']));?>"><?php echo ($vo["name"]); ?></a>
                </p><?php endforeach; endif; ?>
            <p>
                <a href="<?php echo U('cate',array('cid'=>$_GET['cid']));?>">所有公司</a>
            </p>
		</div>

		<div class="mui-content">
			<div class="nav">
				<ul>
					<li  <?php if(!isset($_GET['cid'])){ echo 'class="active"'; }?> ><a href="<?php echo U('cate',array('cid'=>$v['id'],'coid'=>$_GET['coid']));?>">热销</a></li>
                    <?php if(is_array($cate)): foreach($cate as $key=>$v): ?><li
                        <?php if(isset($_GET['cid']) && $v['id']===$_GET['cid']){ ?>
                         <?php echo 'class="active"'; } ?>
                                ><a href="<?php echo U('cate',array('cid'=>$v['id'],'coid'=>$_GET['coid']));?>"><?php echo ($v["class_name"]); ?></a></li><?php endforeach; endif; ?>
					<li>
						<a class="mui-icon mui-icon-arrowdown"></a>
					</li>
				</ul>
			</div>
			<div class="nav-hid" style="display: none">
				<div class="rightnav" >
                    <?php if(is_array($cates)): foreach($cates as $key=>$v): ?><a href="<?php echo U('cate',array('cid'=>$v['id'],'coid'=>$_GET['coid']));?>">
							【<?php echo ($v["class_name"]); ?>】<img src="/sanlingyi/Public/Entity/images/iconfont-right.png" class="nav0"/>
						</a><?php endforeach; endif; ?>
						<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="conlist">
                <?php if(is_array($pt)): foreach($pt as $key=>$p): ?><div class="list-left" url="<?php echo ($p["buy_url"]); ?>">
                        <div class="lishadow">
                            <img src="2132"  />
                            <div class="mask"></div>
                            <p class="p2"><?php echo ($p["title"]); ?></p>
                        </div>
                        <p class="p1"><?php echo ($p["introduce"]); ?></p>
                    </div><?php endforeach; endif; ?>
                <?php if($nums>=C('PAGE_NUM')){ ?>
                <div id="order_list" onclick="get_more()" style="text-align: center; float:left;  width:100%; line-height: 2em; margin:2em 0em 0 0; color: #333; font-size: 1em; background: #DDDDDD">
                    点击加载更多
                </div>
                <?php } ?>
            </div>

        </div>

		<script src="/sanlingyi/Public/Entity/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="/sanlingyi/Public/Entity/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function(){
				 var get_navi = $('.navi');
				  var get_navhid = $('.nav-hid');
				 //一级导航
				 
				  $(".xiala").click(function(){
				  		get_navi.slideToggle(0);
				  		$('.nav li:last-child a').removeClass().addClass('mui-icon mui-icon-arrowdown');
				  		get_navhid.css('display','none');
						$('.content-list').css('display','block');
				  });
				  $('.mui-content').click(function(){
				  		if(get_navi.css('display')=="block"){
				  		get_navi.slideUp('fast');
				  		}
				  });
				  
				  
				  //二级导航
				  $('.nav li:last-child a').click(function(){
				  		if(get_navhid.css('display')=='none'){
						$(this).removeClass('mui-icon-arrowdown').addClass('mui-icon-arrowup');
						get_navhid.css('display','block');
						//$('.conlist').css('display','none');
						}else if(get_navhid.css('display')=='block'){
						$(this).removeClass('mui-icon-arrowup').addClass('mui-icon-arrowdown');
						get_navhid.css('display','none');	
						//$('.conlist').css('display','block');
						}
				  });
			});

            $('.conlist .list-left').click(function(){
                var url=$(this).attr('url');
                window.location.href=url;

            });

			
		</script>
        <script>
            //分页
            sanlingyi_page=1;
            function get_more(){
                //加载文章、/cate/cid/4/coid/1
                var article=$('#order_list');
                var url="<?php echo U('Index/cate',array('cid'=>$_GET['cid'],coid=>$_GET['coid']),'');?>";
                $.get(
                        url,{"page":sanlingyi_page,"ajax":1},function(date){

                            if(date!=0){
                                sanlingyi_page+=1;
                                article.before(date);

                            }else{
                                $('#order_list').html('暂无更多')
                            }
                        }
                        ,'text' );


            }
        </script>
	</body>

</html>