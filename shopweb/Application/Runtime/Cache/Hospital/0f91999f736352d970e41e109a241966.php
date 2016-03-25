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
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/content.css" />

	</head>

	<body>
    <style>
        .con img{ max-width: 90%; display: block; margin: 0 auto;}

    </style>
    <header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
        <?php if(!isset($_GET['type'])){ ?>
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>

        <h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">医苑天地</h1>
        <?php }else{ ?>
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
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="logoutConsultings()"></a>

        <h1 class="mui-title" style="color:#fff" onclick="logoutConsultings()">医苑天地</h1>
        <?php } ?>
        <div class="right" style="height: 45px;padding: 11px 11px 0 11px;">
            <?php if(!isset($_GET['wxfx'])){ ?>
            <a><img class="pl"  style="width: 1.3em;height: 1.3em;" src="/sanlingyi/Public/Hospital/images/xb (1).png" onclick="layer_pl()" /></a>
            <?php } ?>
        </div>
    </header>
    <div id="hid-pl" class="hid-pl">
        <div class="hid-title">
            <p class="p1">发表评论</p>
            <p class="p2"><a onclick="submit_com()">发表</a></p>
            <div class="clear"></div>
        </div>
        <form id="com_form" action="<?php echo U('MyChat/saveComment');?>" method="post">

            <input type="hidden" name="aid" value="<?php echo ($at["id"]); ?>"/>
           <textarea id="c_content" placeholder="输入内容" name="content"></textarea>
            <p id="notice" style="margin-bottom: 1em; display: none;">不能超过500个字</p>
        </form>

    </div>
		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->

		<div class="mui-content">
			<p class="title">
                <?php echo ($at["title"]); ?>
			</p>
			<div class="time">
				<p><?php echo ($at["createDate"]); ?></p>
				 <?php if(!isset($_GET['type']) and !isset($_GET['wxfx'])){ ?>
				<div class="fx-sc">
					<a id="add_favorite" is_save="<?php echo $is_save; ?>">
						<?php if($is_save==0){ ?>
                        <img src="/sanlingyi/Public/Hospital/images/xx1.png"/>
                        <?php }else{ ?>
                        <img src="/sanlingyi/Public/Hospital/images/xx.png"/>
                        <?php } ?>
						<span>收藏</span>
					</a>
					<a onclick="sh_evoke_shares()">
						<img src="/sanlingyi/Public/Hospital/images/fx.png"/>
						<span>分享</span>
					</a>
				</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
            <?php if(is_array($img)): foreach($img as $key=>$vo): ?><img src="<?php echo ($vo["source_image_url"]); ?>" style="width: 100%;margin-top: 0.5em;margin-bottom: 0.5em;"/><?php endforeach; endif; ?>
			<div class="con">
		        <?php echo ($at['content']); ?>
			</div>

		</div>

       <?php if($com_count!=0){ ?>
		<div class="cy">
				<p>百家争鸣</p>
				<div class="til">
					<img src="/sanlingyi/Public/Hospital/images/canyu.png"/>
					已参与<span class="red"><?php echo ($at["recommend_num"]); ?></span>人次
				</div>
		</div>
       <?php } ?>
        <?php if(is_array($com)): foreach($com as $key=>$v): ?><div class="list-box">
			<div class="im">
				<img src="<?php echo ($v["img_url"]); ?>"/>
			</div>
			<div class="li-box-p">
				<p><span class="blue">
					<?php echo ($v["user_name"]); ?>：
				</span><?php echo ($v["content"]); ?></p>
				<div class="tim">
					<p><?php echo ($v["createDate"]); ?></p>
					<div class="zan up_evalute_list" aid="<?php echo ($at["id"]); ?>" eid="<?php echo ($v["id"]); ?>">
						<img src="/sanlingyi/Public/Hospital/images/zan.png"/><span id="" class="up_num">
							<?php echo ($v["up_num"]); ?>
						</span>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div><?php endforeach; endif; ?>
    <script src="/sanlingyi/Public/Hospital/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/sanlingyi/Public/Hospital/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			function layer_pl(){
				layer.open({
				    type: 1,
				    title:false,
				    closeBtn: false,
				    shadeClose:true,
				    area:'100%',
				    content: $('#hid-pl'),
				    style: 'width:240px; height:180px; padding:10px; background-color:#F05133; color:#fff; border:none;'
				});
			}
			$(function(){
			});
            //增加收藏
            $('#add_favorite').click(function(){
                var ss="<?php echo session('yixin_ss'); ?>";
                var snaliyi_artid="<?php echo ($_GET['aid']); ?>"
                var save_url="<?php echo U('MyChat/saveFavorite');?>";
                var is_save=$(this).attr("is_save");
                var isLogin=1;
                //var isLogin=1;
                if(isLogin!=1){
                    mui.alert('用户未登录', '消息', function() {

                    });
                }else{
                    $.post(save_url,{'aid':snaliyi_artid,'is_save':is_save},function(data){
                        if(data==1){
                            if(is_save==0) {
                                $('#add_favorite').attr('is_save','0');
                                $('#add_favorite img').attr('src', "/sanlingyi/Public/Hospital/images/xx.png");
                            }else if(is_save==1){
                                $('#add_favorite').attr('is_save','0');
                                $('#add_favorite img').attr('src', "/sanlingyi/Public/Article/images/sc.png");
                            }
                        }
                    },'text');
                }
            });

            //攒评论
            $('.up_evalute_list').click(
                    function () {
                        var now_up=$(this).find('.up_num').html();
                        var up_dom=$(this).find('.up_num');
                        var url="<?php echo U('MyChat/evalueUp');?>";
                        var aid=$(this).attr('aid');
                        var eid=$(this).attr('eid');
                        $.post(url,{'aid':aid,'eid':eid},function(data){
                            if(data==1){
                                var new_up=parseInt(now_up)+1;
                                // alert(new_up);
                                up_dom.html(new_up);
                            }
                        },'text')


                    }
            );

            //提交评论
            function submit_com() {
                var value=$('#c_content').val();
                if(value.length==0){
                	$('#notice').html('内容不能为空');
                	$('#notice').show();	
                    return false;
                }
                if(value.length>500){
                	$('#notice').html('不能超过500个字');
                    $('#notice').show();
                    return false;
                }
                $('#com_form').submit();
            }
            $('#c_content').click(function(){
                $('#notice').css('display','none');
            });
		</script>
         <script>
        //分享
        function sh_evoke_shares(){

            var url="<?php echo C('DOMAIN').U('Hospital/Index/detail',array('aid'=>$at['id'],'wxfx'=>1)) ?>"
            var imgurl="<?php echo $img[0]['source_image_url']; ?>"
            var title="<?php echo ($at["title"]); ?>";
            if(navigator.userAgent.match('iPhone')){
                //alert(1);
                return sh_evoke_share(url,title,imgurl);
            }
            if(navigator.userAgent.match('Android')){
                return  Android.sh_evoke_share(url,title,imgurl);
            }
        }
      </script>

	</body>

</html>