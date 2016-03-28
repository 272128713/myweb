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
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" onclick="history.go(-1)"></a>
			<h1 class="mui-title" style="" onclick="history.go(-1)"><?php echo_title(); ?></h1>
			<div class="right">
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
        <?php if($isend){ ?>
        <div class="accept">
            <img src="/sanlingyi/Public/Consult/images/accept.png" class="accept"/>
        </div>
        <!--是最佳-->
        <?php if(is_array($breply)): foreach($breply as $key=>$vo): ?><div class="mui-content" style="padding: 0;">
            <img src="<?php echo ($vo["img_url"]); ?>" class="headimg left"/>
            <div class="headp"><p style="margin-top: 4px"><span class="hh3"><?php echo ($vo["user_name"]); ?></span><span class="pp3"><?php echo ($vo["recollection"]); ?>&nbsp;&nbsp;&nbsp;<?php echo ($vo["duty"]); ?></span></p>
                <p class="pp12"><?php echo ($vo["hospital"]); ?></p>
            </div>

            <div class="clear option">
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
                <p aid ="<?php echo ($vo["answer_id"]); ?>" class="mui-pull-right iconfont icon-zan blue jhbtn up_bom"><span> (<span class="up_num"><?php echo ($vo["up_num"]); ?></span>)</span></p><?php  ?>
                <?php if(!$isuser){ ?>
                <p onclick="go_to_app_consult(<?php echo ($vo["doctor_id"]); ?>)" class="mui-pull-right iconfont icon-consult blue jhbtn"><span> 咨询</span></p>
                <?php } ?>
            </div><?php endforeach; endif; ?>
        </div>
        <?php if(count($reply)>0){ ?>
        <div class="clear rela" style="margin: 0;">
            <div style="height:1.5em;width:3px;background:red;float:left;margin-top: 0.5em;"></div>
            <div class="relatext">其他回答</div>
        </div>
        <?php }} ?>
        <?php if(count($reply)>0){ ?>
		<div class="mui-content" style="padding: 0;">
			
        <!--不是最佳-->
		<ul class="mui-table-view" style="margin-top: 0">
			<?php if(is_array($reply)): foreach($reply as $key=>$vo): ?><li class="mui-table-view-cell uli" >
                    <img src="<?php echo ($vo["img_url"]); ?>" class="headimg left"/>
                    <div class="headp"><p style="margin-top: 4px" ><span class="hh3"><?php echo ($vo["user_name"]); ?></span><span class="pp3"><?php echo ($vo["recollection"]); ?>&nbsp;&nbsp;&nbsp;<?php echo ($vo["duty"]); ?></span></p>
                        <p class="pp12"><?php echo ($vo["hospital"]); ?></p>
                    </div>

                    <div class="clear option">
                    <div class="optiont">医生建议：<span class="mui-pull-right"  style="font-size: 13px;color:#999"><?php echo substr($vo['creatDate'],0,10); ?></span>
                    <p  style="margin-top:5px;margin-bottom: 0;color:#808080;font-size: 13px;">
                        <?php echo ($vo["content"]); ?>
                    </p>
                    </div>
                        <!--追问-->
                        <?php if(is_array($vo['answers'])): foreach($vo['answers'] as $key=>$an): ?><div class="optiont">
                                <?php if($an['user_id']==$vo['doctor_id']){ echo '医生回复：';} else{ echo '患者追问：';} ?>
                                <span class="mui-pull-right"><?php echo substr($an['createDate'],0,10); ?></span>
                                <p  style="margin-top:0.7em;margin-bottom: 0;">
                                    <?php echo ($an["content"]); ?>
                                </p>
                            </div><?php endforeach; endif; ?>
                        <p aid ="<?php echo ($vo["answer_id"]); ?>" class="mui-pull-right iconfont icon-zan blue jhbtn up_bom"><span> (<span class="up_num"><?php echo ($vo["up_num"]); ?></span>)</span></p>
                    <?php if(!$isuser){ ?>
                    <p onclick="go_to_app_consult(<?php echo ($vo["doctor_id"]); ?>)" class="mui-pull-right iconfont icon-consult blue jhbtn"><span> 咨询</span></p>
                        <?php } ?>
                    </div>
                </li><?php endforeach; endif; ?>

			
		</ul>
		</div>
        <?php } ?>
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
        </script>
        <script src="/sanlingyi/Public/Consult/js/app_com.js"></script>
	</body>

</html>