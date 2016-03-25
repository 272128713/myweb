<?php if (!defined('THINK_PATH')) exit();?> <?php if(is_array($com)): foreach($com as $key=>$vo): ?><div class="chat-list">
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