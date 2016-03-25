<?php if (!defined('THINK_PATH')) exit(); if(is_array($cv)): foreach($cv as $key=>$vo): ?><li class="consumList  delivery">
        <img src="http://192.168.20.29/shop_skyhospital/shop_new/data/upload/shop/store/goods/1/<?php echo $vo['product_list'][0]['goods_image'] ?>" />
        <div class="listintro">
            <p class="name">
                <?php if(count($vo['product_list'])>1){ echo $vo['pakage_name']; echo '('; foreach($vo['product_list'] as $k=>$v){ if($k!=0){ echo ','.$v['goods_name']; }else{ echo $v['goods_name']; } } echo ')'; }else{ echo $vo['product_list'][0]['goods_name']; } ?>
            </p>
            <p class="p3"><span>￥<?php echo ($vo["order_amount"]); ?>
                            <?php if($vo['use_points']>0){ echo '('.$vo['use_points'].'积分)'; } ?>
                        </span>
            <p class="p5"><?php echo date('Y-m-d',$vo['add_time']) ?>购买
                <?php if($vo['order_state']==20){ ?>
                <span class="confirm_btn" style="right: 18%;">退款</span><span class="confirm_btn" style="right: 3%;">提货</span>
                <?php }else{ ?>
                <span style="background: none; color: #666"><?php echo get_order_staus($vo['order_state']) ?></span>
                <?php } ?>
            </p>
        </div>
        <div class="list-clear"></div>
    </li><?php endforeach; endif; ?>