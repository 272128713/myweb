<?php if (!defined('THINK_PATH')) exit(); if(is_array($rs)): foreach($rs as $key=>$v): ?><li class="userinfo" onclick="jump()" url="<?php echo U('oneMember',array('uid'=>$v['member_id']));?>">
        <img class="userphoto" src="<?php echo getImg($v['thumbnail_image_url']);?>" />
        <div class="moreinfo">
            <p>
						<span class="nam">
                            <?php if_empty($v['member_name']); ?>
                        </span>
                <img class="icon" src="/shop_skyhospital/trunk/shopweb/Public/Home/images/lv<?php echo ($v["member_level"]); ?>.png"/>
                <span class="sex"><?php if_empty($v['member_sex']); ?></span>
                <span class="year"><?php getDay($v['member_birthday']); ?></span>
            </p>
            <p style="color:#000;"><?php if_empty($v['member_address']); ?></p>
        </div>
        <div class="clear"></div>
    </li><?php endforeach; endif; ?>