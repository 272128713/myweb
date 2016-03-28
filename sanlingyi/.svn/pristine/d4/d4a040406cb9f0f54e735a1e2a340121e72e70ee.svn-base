<?php if (!defined('THINK_PATH')) exit(); if(is_array($news)): foreach($news as $key=>$v): ?><div class="art-box-list">
        <p class="p1"><?php echo ($v["title"]); ?></p>
        <div class="p2">
            <p class="p3"><span class="blue">【<?php echo get_name($v['columns']); ?>】</span><?php echo ($v["name"]); ?></p>
            <?php if($v['report_flag']!=0){ ?>
            <p class="p4"><span class="blue">【医讯头条】</span>心血管</p>
            <?php } ?>
            <div class="clear"></div>
        </div>
        <div class="time">
            <p class="ptime"><?php echo ($v["createDate"]); ?></p>
            <div class="jh">
                <?php if($v['report_flag']==0){ $data=json_encode($v); $name='data'.$key; ?>
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
                    审批中
                </div>
                <div class="sc">
                    <img src="/sanlingyi/Public/Hospital/images/iconfont-shanchu.png"/>删除
                </div>
                <?php } ?>

            </div>
            <div class="clear"></div>
        </div>
    </div><?php endforeach; endif; ?>