<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医苑天地</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

        
<link rel="stylesheet" href="/sanlingyi/Public/Hospital/css/mui.min.css">
<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/app.css" />
<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/common.css" />
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/exchange.css" />

	</head>

	<body>
    <header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
    <h1 class="mui-title" style="color:#fff">医苑天地</h1>

    <div class="right">
        <span style="color: #fff;display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;"><img class="xiala" src="/sanlingyi/Public/Hospital/images/xiala.png"  /></span>
    </div>
</header>

		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->
    <div class="navi">
    <div class="bg"></div>
    <p><a href="<?php echo U('Index/cate');?>"><img src="/sanlingyi/Public/Hospital/images/xb (2).png"/>热点话题</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>1));?>"><img src="/sanlingyi/Public/Hospital/images/xb (3).png"/>学术交流</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>2));?>"><img src="/sanlingyi/Public/Hospital/images/xb (4).png"/>有问必答</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>3));?>"><img src="/sanlingyi/Public/Hospital/images/xb (5).png"/>病例分析</a></p>
    <p><a href="<?php echo U('Index/lists',array('cid'=>4));?>"><img src="/sanlingyi/Public/Hospital/images/xb (6).png"/>行业观察</a></p>
    <p><a href="<?php echo U('MyChat/index');?>"><img src="/sanlingyi/Public/Hospital/images/xb (7).png"/>我的记录</a></p>
    <p class="p_btm" onclick="publishs('')"><a>
        <img src="/sanlingyi/Public/Hospital/images/xb (1).png"/>
        <br />
        发表
    </a>
    </p>
</div>
<script>
function publishs(aid){
    if(navigator.userAgent.match('iPhone')){
        // alert();
        publish(aid);
    }
    if(navigator.userAgent.match('Android')){
        //alert($uid);
        Android.publish(aid);
    }
}

</script>

		<div class="mui-content">
			<div class="nav">
				<ul>
					<?php echo get_ret_nav(); ?>
					<li>
						<a class="mui-icon mui-icon-arrowdown"></a>
					</li>
				</ul>
			</div>
            <div class="nav-hid" style="display: none;">
                <div class="rightnav" >
                    <?php if(is_array($ret)): foreach($ret as $key=>$v): ?><a>
                        【<?php echo ($v["name"]); ?>】<img src="/sanlingyi/Public/Hospital/images/iconfont-iconfont58.png" class="nav0"/>
                    </a>

                    <div class="recnav" >
                    	  <a href="<?php echo U('Index/lists',array('rid'=>$v['recollection_id'],'cid'=>$_GET['cid']));?>">
                            【<?php echo ($v["name"]); ?>】<img src="/sanlingyi/Public/Hospital/images/iconfont-right.png" />
                        </a>
                        <?php if(is_array($v['child'])): foreach($v['child'] as $key=>$vo): ?><a href="<?php echo U('Index/lists',array('rid'=>$vo['recollection_id'],'cid'=>$_GET['cid']));?>">
                            【<?php echo ($vo["name"]); ?>】<img src="/sanlingyi/Public/Hospital/images/iconfont-right.png" />
                        </a><?php endforeach; endif; ?>
                    </div><?php endforeach; endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
           
			<div class="content-list" id="thelist">
				<?php if($cname){ ?>
				<div class="list-box-1">
				当前栏目：<?php echo ($cname); ?>
				</div>
				<?php } ?>
                <?php if(is_array($news)): foreach($news as $key=>$vo): ?><div class="list-box">
                        <a href="<?php echo U('Index/detail',array('aid'=>$vo['id']));?>" style="color:#333">
                        <img src="<?php echo ($vo["img_url"]); ?>"/>
                        <div class="cont-li">
                            <div class="li-title">
                                <div class="p1">
                                    <?php echo ($vo["user_name"]); ?>
                                </div>
                                <p class="p2"><?php echo ($vo["createDate"]); ?></p>
                                <div class="clear"></div>
                            </div>
                            <p class="p3"><?php echo ($vo["title"]); ?></p>
                        </div>
                        <div class="clear"></div>
                        </a>
                    </div><?php endforeach; endif; ?>


			</div>
           
		</div>
		 <?php if($nums>=C('PAGE_NUM')){ ?>
             <div id="order_list" onclick="get_more()" style="text-align: center;line-height: 2em; margin:2em 0em 0 0; color: #333; font-size: 1em; background: #DDDDDD">
  				  点击加载更多
			</div>
    <?php } ?>
    <script src="/sanlingyi/Public/Hospital/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/sanlingyi/Public/Hospital/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
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
                        $('.content-list,#order_list').css('display','none');
                    }else if(get_navhid.css('display')=='block'){
                        $(this).removeClass('mui-icon-arrowup').addClass('mui-icon-arrowdown');
                        get_navhid.css('display','none');
                        $('.content-list,#order_list').css('display','block');
                    }
                });

                //三级科室导航
                $('.rightnav a').click(function(){
                    var get_asrc = $(this).children('.nav0');
                    if(get_asrc.attr('src')=='/sanlingyi/Public/Hospital/images/iconfont-iconfont58.png'){
                        get_asrc.attr('src','/sanlingyi/Public/Hospital/images/iconfont-up.png');
                        $(this).next('.recnav').css('display','block');
                    }else{
                        get_asrc.attr('src','/sanlingyi/Public/Hospital/images/iconfont-iconfont58.png');
                        $(this).next('.recnav').css('display','none');
                    }


                });

            });

        </script>
      
    	<script>
    	//分页
    sanlingyi_page=1;
    function get_more(){
            //加载文章
          	var article=$('#thelist');
            var url="<?php echo U('index/lists',array('rid'=>$_GET['rid'],'cid'=>$_GET['cid']),'');?>";
            $.post(
                    url,{"page":sanlingyi_page,"ajax":1},function(date){

                        if(date!=0){
                            sanlingyi_page+=1;
                            article.append(date);
                          
                        }else{
                        	$('#order_list').html('暂无更多')
                        }
                    }
                    ,'text' );

        
    }
</script>	
    


    </body>

</html>