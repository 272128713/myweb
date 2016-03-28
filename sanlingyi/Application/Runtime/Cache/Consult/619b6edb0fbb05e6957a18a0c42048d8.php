<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>用户看自己未解决</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<link rel="stylesheet" href="/sanlingyi/Public/Consult/css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/app.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/common.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/resolved.css"/>
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Consult/css/iconfont.css"/>
        <style>



            .mui-table-view:after {
                right: -8px;
                left: -10px;
                background-color: #c8c7cc;

            }
        </style>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav" style="position: fixed;">
            <?php if(isset($_GET['areply'])){ ?>
			<a  href="<?php echo U('Index/index');?>" class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
            <a  href="<?php echo U('Index/index');?>" class="mui-title" style="">在线义诊</a>
            <?php }else{ ?>
            <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
            <h1 class="mui-title" style="" onclick="history.go(-1)">在线义诊</h1>
            <?php } ?>

			<div class="right">
                <?php if(!is_asked()){ ?>
				<span id="ask_start" style="display: block;height: 44px;width:3em;text-align:center;line-height:44px;font-size:16px;" onclick="answer()">解答</span>
                <?php } ?>
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
                            <div class="tifoot"><?php echo get_sex($de['sex']); ?>&nbsp;&nbsp;&nbsp;<span style="color:#e5e5e5">|</span>&nbsp;&nbsp;&nbsp;<?php echo ($de["age"]); ?>岁&nbsp;&nbsp;&nbsp;<span style="color:#e5e5e5">|</span>&nbsp;&nbsp;&nbsp;<?php echo ($de["sicken_time"]); ?></div>
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
                    </li>
                    <li class="mui-table-view-cell">
                        <img src="/sanlingyi/Public/Consult/images/quezhen.png" class="wen1"/>
                        <div class="ti">确诊状况：


                            <div class="tifoot"><?php echo see_doc($de['is_see_doctor'],$de['disease']);?></div>
                        </div>
                    </li>
                    <li class="mui-table-view-cell">
                        <img src="/sanlingyi/Public/Consult/images/yao.png" class="wen1"/>
                        <div class="ti">用药记录：
                            <div class="tifoot">
                                <?php
 if(!$de['medicine']){ echo "暂无"; } ?>
                                <?php echo ($de["medicine"]); ?>
                            </div>
                        </div>
                        </a>
                    </li>
                </ul>
            </div>

	</div>
		<div style="height: 10px;width: 100%;background: #eee;border-bottom: 1px solid #e2e2e2;"></div>
		<div class="mui-content" style="padding: 0;">


            <ul class="mui-table-view" id="answer_info" style="margin-top: 0">
                <?php if(is_array($reply)): foreach($reply as $key=>$vo): ?><li class="mui-table-view-cell uli" >
                        <img src="<?php echo ($vo["img_url"]); ?>" class="headimg left"/>
                        <div class="headp"><p style="margin-top: 4px"><span class="hh3"><?php echo ($vo["user_name"]); ?></span><span class="pp3"><?php echo ($vo["recollection"]); ?>&nbsp;&nbsp;&nbsp;<?php echo ($vo["duty"]); ?></span></p>
                            <p class="pp12"><?php echo ($vo["hospital"]); ?></p>
                        </div>

                        <div class="clear option reply_list<?php echo ($vo["doctor_id"]); ?> " >
                            <div class="optiont">医生建议：<span class="mui-pull-right" style="font-size: 13px;color:#999"><?php echo substr($vo['creatDate'],0,10); ?></span>
                                <p  style="margin-top:5px;margin-bottom: 0;color:#808080;font-size: 13px">
                                    <?php echo ($vo["content"]); ?>
                                </p>
                            </div>
                            <!--追问-->
                            <?php if(is_array($vo['answers'])): foreach($vo['answers'] as $key=>$an): ?><div class="optiont">
                                    <?php if($an['user_id']==$vo['doctor_id']){ echo '医生回复：';} else{ echo '患者追问：';} ?>
                                    <span class="mui-pull-right" style="font-size: 13px;color:#999"><?php echo substr($an['createDate'],0,10); ?></span>
                                    <p  style="margin-top:5px;margin-bottom: 0;color:#808080;font-size: 13px;">
                                        <?php echo ($an["content"]); ?>
                                    </p>
                                </div><?php endforeach; endif; ?>
                            <p aid ="<?php echo ($vo["answer_id"]); ?>" class="mui-pull-right iconfont icon-zan blue jhbtn up_bom"><span> (<span class="up_num"><?php echo ($vo["up_num"]); ?></span>)</span></p>

                        </div>
                    </li><?php endforeach; endif; ?>


            </ul>
            <?php if($is_reply){ ?>
            <form action="" method="" class="zhuiwen_form">
                <textarea class="as_contens" name="" style="margin:0; color#fff; height:5em"></textarea>
                <div><input type="button"  name="" class="mui-pull-right zhuiwen_submit" id="as_submit" style="background:#30C6DF;border:0 ;margin-bottom:0.2em;margin-right:0.2em" value="回答" />
                </div>
            </form>
            <?php } ?>
		</div>
        <script src="/sanlingyi/Public/Article/js/jquery-1.9.1.min.js"></script>
		<script src="/sanlingyi/Public/Consult/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
		function answer(){
			layer.open({
			    type: 1, //page层
			    area: ['80%', ''],
			    title: '感谢您为患者提供帮助',
                closeBtn : 2,
                skin: '', //墨绿风格
			    shade: 0.6, //遮罩透明度
			    shift: 0, //0-6的动画形式，-1不开启
			    content: '<form action="" method=""><textarea  onfocus="notice()" id="reply_cnt" name="" style="margin:0; color#fff; height:5em"></textarea><div><span id="notice_reply" style="padding-left: 1em; display: none;">不能超过500个字</span><input type="button" onclick="add_replay()" name="" class="mui-pull-right" id="as_submit" style="background:#30C6DF;border:0 ;margin-bottom:0.2em;margin-right:0.2em" value="回答" /></div></form>'
			});    
		}
			$(function(){
				var cellwidth =  $('.mui-table-view-cell').width();	
				var imgwidth = $('.wen').width()*1.3;
				$('.ti').width(cellwidth-imgwidth);
				
				$(".ask").click(function(){
					
				});
			});
		</script>
        <!--点赞-->
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
        // 提交解答
            function add_replay(){
                //预留验证
                var url="<?php echo U('SelfConsult/replyQ');?>";
                var aid="<?php echo ($_GET['aid']); ?>";
                var value=$('#reply_cnt').val();
                if(value.length<1){
                    return false;
                }else if(value.length>500){
                    $('#notice_reply').show();
                    return false;
                }
                $.post(url,{'aid':aid,'value':$('#reply_cnt').val()},function(data){
                    if(data!=0){
                        $('#answer_info').prepend(data);
                        $('#ask_start').hide();
                        $('.layui-layer-close').click();
                    }
                },'text')
            }
            function notice(){
                $('#notice_reply').hide();
            }
            //医生回复
            $('.zhuiwen_submit').click(
                    function () {

                        var url="<?php echo U('SelfConsult/dquestion');?>";
                        var aid="<?php echo ($_GET['aid']); ?>";
                        var value=$('.as_contens').val();
                        if(value.length<1){
                            return false;
                        }else if(value.length>500){

                        }
                        var hdom=$('.reply_list<?php echo session('yixin_user'); ?>');
                        $.post(url,{'aid':aid,'value':value},function(data){
                            if(data!=0){

                                hdom.append(data);
                                $('.zhuiwen_form').hide();
                            }
                        },'text')


                    }
            );
        </script>

	</body>

</html>