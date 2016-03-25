<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>用户看自己已解决</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/sanlingyi/Public/Consult/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/app.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/common.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/resolved.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/iconfont.css"/>

	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="background:#30c6df;position: fixed;">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="color:#fff">在线咨询</h1>
			<div class="right">
				<a href=""><img src="/sanlingyi/Public/Consult/images/jilu.png" class="jiluzixun" /></a>
				<a href=""><img src="/sanlingyi/Public/Consult/images/consult.png" class="jiluzixun" /></a>
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
			<div class="clear">
				<ul class="mui-table-view">
				  <li class="mui-table-view-cell">
				      <img src="/sanlingyi/Public/Consult/images/wen.png" class="wen"/>
				      <div class="ti"><?php echo ($de["title"]); ?>
				      		<div class="tifoot"><?php echo get_sex($de['sex']); ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo ($de["age"]); ?>岁&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo ($de["sicken_time"]); ?></div>
				      </div>
				    </a>
				  </li>	
				  <li class="mui-table-view-cell">
				      <img src="/sanlingyi/Public/Consult/images/yiwen.png" class="wen1"/>
				      <div class="ti">病情描述及疑问：
				      		<div class="tifoot" style="width:100%;"><?php echo ($de["content"]); ?>
				      		<div class="ct">
                                <?php if(is_array($img)): foreach($img as $key=>$v): ?><img src="<?php echo ($v["source_image_url"]); ?>"/><?php endforeach; endif; ?>

                            </div>
				      		
				      		</div>
				      </div>
				      <img src="/sanlingyi/Public/Consult/images/quezhen.png" class="wen1"/>
				      <div class="ti">确诊状况：


				      		<div class="tifoot"><?php echo see_doc($de['is_see_doctor'],$de['disease']);?></div>
				      </div>
				      <img src="/sanlingyi/Public/Consult/images/yao.png" class="wen1"/>
				      <div class="ti">用药记录：
				      		<div class="tifoot"><?php echo ($de["medicine"]); ?></div>
				      </div>
				    </a>
				  </li>	
				</ul>
			</div>

	</div>

		<div class="mui-content" style="padding: 0;">
			
        <!--不是最佳-->
		<ul class="mui-table-view" style="margin-top: 0">
			<?php if(is_array($reply)): foreach($reply as $key=>$vo): ?><li class="mui-table-view-cell uli" >
                    <img src="<?php echo ($vo["img_url"]); ?>" class="headimg left"/>
                    <div class="headp"><p><span class="hh3"><?php echo ($vo["user_name"]); ?></span><span class="pp3"><?php echo ($vo["recollection"]); ?>&nbsp;&nbsp;&nbsp;<?php echo ($vo["duty"]); ?></span></p>
                        <p class="pp12"><?php echo ($vo["hospital"]); ?></p>
                    </div>

                    <div class="clear option">
                    <div class="optiont">医生建议：<span class="mui-pull-right"><?php echo substr($vo['creatDate'],0,10); ?></span>
                    <p  style="margin-top:0.7em;margin-bottom: 0;">
                        <?php echo ($vo["content"]); ?>
                    </p>
                    </div>
                        <div class="ack_out">
                        <!--追问-->
                        <?php if(is_array($vo['answers'])): foreach($vo['answers'] as $key=>$an): ?><div class="optiont">
                                <?php if($an['user_id']==$vo['doctor_id']){ echo '医生回复：';} else{ echo '患者追问：';} ?>
                                <span class="mui-pull-right"><?php echo substr($an['createDate'],0,10); ?></span>
                                <p  style="margin-top:0.7em;margin-bottom: 0;">
                                    <?php echo ($an["content"]); ?>
                                </p>
                            </div><?php endforeach; endif; ?>
                        </div>

                        <p aid ="<?php echo ($vo["answer_id"]); ?>" class="mui-pull-left iconfont icon-zan blue up_bom">
                            <span> (<span class="up_num"><?php echo ($vo["up_num"]); ?></span>)</span></p>
                        <div class="mui-pull-right">
                            <div class="iconfont icon-consult blue left jhbtn"><span> 会话</span></div>
                            <div class="iconfont icon-wenyisheng blue left jhbtn zhuiwen"><span> 追问</span></div>

                            <div  aid ="<?php echo ($vo["answer_id"]); ?>" class="blue left jhbtn ask_accept ask_caina" aid ="<?php echo ($vo["answer_id"]); ?>">OK<span> 采纳</span></div>

                        </div>
                        <div class="zhuiwen_box" style="padding-top:0.5em; display: none;">
                            <textarea class="as_contens" name="" rows="" cols="" style="margin-bottom: 0;font-size:14px;color: #666666;"></textarea>
                            <div aid ="<?php echo ($vo["answer_id"]); ?>" class="right zhuiwen_submit" style="padding:0.2em 1.2em 0.2em 1.2em;background: #30C6DF;border-radius: 0.2em;color: #fff;font-size: 14px;">
                                追问
                            </div>
                        </div>
                    </div>
                </li><?php endforeach; endif; ?>

			
		</ul>
		</div>
        <script src="/sanlingyi/Public/Article/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript">
			$(function(){
				var cellwidth =  $('.mui-table-view-cell').width();	
				var imgwidth = $('.wen').width()*1.3;
				$('.ti').width(cellwidth-imgwidth);
			});
		</script>
        <script>
            $('.up_bom').click(
                    function () {
                        var now_up=$(this).find('.up_num').html();
                        var up_dom=$(this).find('.up_num');
                        var url="<?php echo U('evalueUp');?>";
                        var aid=$(this).attr('aid');
                        $.post(url,{'aid':aid},function(data){
                            if(data==1){
                                var new_up=parseInt(now_up)+1;
                                // alert(new_up);
                                up_dom.html(new_up);
                            }
                        },'text')


                    }
            );
            //追问
            $('.zhuiwen').click(function(){
                var dom=$(this).parent('.mui-pull-right').siblings('.zhuiwen_box');
                $('.zhuiwen_box').hide();
                dom.css('display','block');

            });
            $('.zhuiwen_submit').click(
                    function () {

                        var url="<?php echo U('SelfConsult/question');?>";
                        var aid=$(this).attr('aid');
                        var value=$(this).siblings('.as_contens').val();
                        var pdom=$(this).parents('.zhuiwen_box');
                        var hdom=pdom.siblings('.ack_out');
                        $.post(url,{'aid':aid,'value':value},function(data){
                            if(data!=0){
                                pdom.hide();
                                hdom.append(data);
                            }
                        },'text')

                    }
            );

            //采纳
            $('.ask_caina').click(
                    function () {
                        var url="<?php echo U('SelfConsult/accept');?>";
                        var aid=$(this).attr('aid');//回复id
                        var pid="<?php echo ($_GET['aid']); ?>";
                        var pdom=$(this).parents('.zhuiwen_box');
                        $.post(url,{'aid':aid,'pid':pid},function(data){
                            if(data==1){
                                location.reload()
                            }
                        },'text')


                    }
            );
        </script>
	</body>

</html>