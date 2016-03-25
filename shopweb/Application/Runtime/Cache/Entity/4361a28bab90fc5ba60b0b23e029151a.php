<?php if (!defined('THINK_PATH')) exit(); if(is_array($news)): foreach($news as $key=>$v): ?><div class="zs-box" url="<?php echo U('Index/detail',array('id'=>$v['id']));?>">
        <div class="content">
            <p class="title1"><?php echo ($v["clinic_name"]); ?></p>
            <p class="title2"><?php echo ($v["type"]); ?></p>
            <div class="clear"></div>
            <p class="title3"><?php echo ($v["address"]); ?></p>
            <!--
                作者：272128713@qq.com
                时间：2015-07-30
                描述：医生box
            -->
            <?php if(is_array($v['doc'])): foreach($v['doc'] as $key=>$vo): ?><div class="zs-list">
                    <img src="<?php echo ($vo["img_url"]); ?>"/>
                    <div class="zs-list-p">
                        <p class="p1"><?php echo ($vo["user_name"]); ?></p>
                        <p class="p2"><?php echo ($vo["k_name"]); ?></p>
                        <p class="p3"><?php echo ($vo["service_num"]); ?>/<?php echo ($vo["sale_num"]); ?></p>
                        <div class="clear"></div>
                        <div class="p4"><span><?php echo ($vo["recollection_name"]); ?> </span><span> <?php echo ($vo["duty_name"]); ?></span></div>
                        <p class="p5"><?php echo ($vo["hospital"]); ?> <?php echo ($vo["h_area"]); ?></p>
                    </div>
                    <div class="clear"></div>
                    <div class="money">
                        <div class="money1 left">
                            <img src="/sanlingyi/Public/Entity/images/jisuanqi.png"/>
							<span>
								预筹：
							</span>
							<span class="red">
								￥<?php echo ($vo["tmoney"]); ?>
							</span>
                        </div>
                        <div class="money1 right">
                            <img src="/sanlingyi/Public/Entity/images/money.png"/>
							<span>
								达成：
							</span>
							<span class="red">
								￥<?php echo ($vo["nmoney"]); ?>
							</span>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div><?php endforeach; endif; ?>
            <!--
                作者：272128713@qq.com
                时间：2015-07-30
                描述：医生box
            -->

            <!--
                作者：272128713@qq.com
                时间：2015-07-30
                描述：医生box
            -->

        </div>
    </div><?php endforeach; endif; ?>