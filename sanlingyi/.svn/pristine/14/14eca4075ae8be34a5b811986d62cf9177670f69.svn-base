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
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Hospital/css/mychat.css" />

	</head>

	<body>
    <header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
        <h1 class="mui-title" style="color:#fff" onclick="history.go(-1)">医苑天地</h1>

    </header>

		<!--
        	时间：2015-06-29
        	描述：主要内容
        -->

		<div class="mui-content">
			<div class="title">
				<div class="headimg">
					<img src="<?php echo ($pic); ?>"/>
				</div>
				<div class="name">
					<p class="p1"><?php echo ($me["user_name"]); ?></p>
					<!-- 
					<p class="p2">
						<img src="/sanlingyi/Public/Hospital/images/weizhi.png"/>
						<?php echo ($province["name"]); ?> <?php echo ($city["name"]); ?>
					</p>
					 -->
				</div>
				<div class="btn">
					<div class="btnarea">
						<p class="active" id="art">文章</p>
						<p id="chat">评论</p>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>


			<!--
            	作者：272128713@qq.com
            	时间：2015-07-28
            	描述：art
            -->
            <div class="art-box">
    <?php if(is_array($news)): foreach($news as $key=>$v): ?><div class="art-box-list">
            <p class="p1"><?php echo ($v["title"]); ?></p>
            <div class="p2">
                <p class="p3"><span class="blue">【<?php echo get_name($v['columns']); ?>】</span><?php echo ($v["name"]); ?></p>
                <?php if($v['report_flag']!=0){ ?>
                <p class="p4"><span class="blue">【医讯头条】</span><?php echo ($v["class_name"]); ?></p>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <div class="time">
                <p class="ptime"><?php echo ($v["createDate"]); ?></p>
                <div class="jh">
                    <?php if($v['report_flag']==0){ $model=D('Article'); $img=$model->getImg($v['id']); $v['img_url']= $img[0]['source_image_url']; $v['img_id']= $img[0]['article_image_id']; $v['content']=urlencode($v['content']); $data=json_encode($v); $name='data'.$key; ?>
                    <script>
                        var <?php echo $name; ?>='<?php echo $data; ?>';
                    </script>
                    <div class="bj" onclick="publishs(<?php echo $name;?>)">
                        <img src="/sanlingyi/Public/Hospital/images/iconfont-bianji.png" />编辑
                    </div>
                    <div class="sc delete_article"  aid="<?php echo ($v["id"]); ?>">
                        <img  src="/sanlingyi/Public/Hospital/images/iconfont-shanchu.png"/>删除
                    </div>
                    <?php }else{ ?>
                    <div class="sh">
                        <?php echo get_status($v['report_flag']); ?>
                    </div>
                    <!--<div class="sc">-->
                        <!--<img src="/sanlingyi/Public/Hospital/images/iconfont-shanchu.png"/>删除-->
                    <!--</div>-->
                    <?php } ?>

                </div>
                <div class="clear"></div>
            </div>
        </div><?php endforeach; endif; ?>
</div>
			<!--
            	作者：272128713@qq.com
            	时间：2015-07-28
            	描述：chat-box
            -->
            <div class="chat-box">
    <?php if(is_array($com)): foreach($com as $key=>$vo): ?><div class="chat-list">
            <img src="/sanlingyi/Public/Hospital/images/circle.png" class="img-c"/>
            <div class="chat-list-li">
                <p class="p1"><?php echo ($vo["content"]); ?></p>
                <p class="p2"><?php echo ($vo["title"]); ?></p>
                <div class="time">
                    <p class="p3"><?php echo ($vo["createDate"]); ?></p>
                    <div class="right">
                        <p class="p4"><img src="/sanlingyi/Public/Hospital/images/zan.png"/><?php echo ($vo["up_num"]); ?></p>
                        <p class="p5 delete_evalu" eid="<?php echo ($vo["id"]); ?>" aid="<?php echo ($vo["article_id"]); ?>"><img src="/sanlingyi/Public/Hospital/images/iconfont-shanchu.png"/>删除</p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div><?php endforeach; endif; ?>

</div>
<?php if($nums>=C('PAGE_NUM') or $cnums>=C('PAGE_NUM')){ ?>
<div id="order_list" onclick="get_more()" style="text-align: center;line-height: 2em; color: #333; font-size: 1em; background: #DDDDDD">
    点击加载更多
</div>
<?php } ?>
<script>
    sanlingyi_page_a=1;
    sanlingyi_page_c=1;
    function get_more(){
        if(sanlingyi_show_tab==0){
            //加载文章
            var article=$('.art-box');
            var url="<?php echo U('MyChat/updateArtAjax');?>";
            $.get(
                    url,{"page":sanlingyi_page_a,"ajax":1},function(date){

                        if(date!=0){
                            sanlingyi_page_a+=1;
                            article.append(date);
                           // alert(date);

                        }else{
                            $('#order_list').html('暂无更多')
                        }
                    }
                    ,'html' );

        }else{
        	  //加载评论
            var article=$('.chat-box');
            var url="<?php echo U('MyChat/updateCom');?>";
            $.get(
                    url,{"page":sanlingyi_page_c,"ajax":1},function(date){

                        if(date!=0){
                            sanlingyi_page_c+=1;
                            article.append(date);
                           // alert(date);

                        }else{
                            $('#order_list').html('暂无更多')
                        }
                    }
                    ,'html' );
        }
    }
</script>

		</div>
    <script src="/sanlingyi/Public/Hospital/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/sanlingyi/Public/Hospital/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            $(function(){
                sanlingyi_show_tab=0;
                $('#chat').click(function(){
                    sanlingyi_show_tab=1;
                    $('#order_list').html('点击加载更多')
                    $('.art-box').css('display','none');
                    $('.chat-box').css('display','block');
                    $(this).addClass('active1');
                    $('#art').removeClass('active');
                });
                $('#art').click(function(){
                    sanlingyi_show_tab=0;
                    $('#order_list').html('点击加载更多')
                    $('.art-box').css('display','block');
                    $('.chat-box').css('display','none');
                    $(this).addClass('active');
                    $('#chat').removeClass('active1');
                });
            });
        </script>
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
        //删除评论
        $('.delete_evalu').click(function(){
            var prents_node=$(this).parents('.chat-list');
            var eid=$(this).attr('eid');
            var aid=$(this).attr('aid');
            var url="<?php echo U('delete');?>";
            $.post(url,{'eid':eid,'aid':aid}, function (data) {
                if(data==1){
                    prents_node.remove();
                }
            },'text');
        });
        //删除文章
        $('.delete_article').click(function(){
            var prents_node=$(this).parents('.art-box-list');
            var aid=$(this).attr('aid');
            var url="<?php echo U('deleteArt');?>";
            $.post(url,{'aid':aid}, function (data) {
                if(data==1){
                    prents_node.remove();
                }
            },'text');
        });
    </script>

    </body>

</html>