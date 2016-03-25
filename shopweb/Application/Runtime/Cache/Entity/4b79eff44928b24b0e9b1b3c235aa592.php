<?php if (!defined('THINK_PATH')) exit(); if(is_array($pt)): foreach($pt as $key=>$p): ?><div class="list-left" url="<?php echo ($p["buy_url"]); ?>">
        <div class="lishadow">
            <img src="2132"  />
            <div class="mask"></div>
            <p class="p2"><?php echo ($p["title"]); ?></p>
        </div>
        <p class="p1"><?php echo ($p["introduce"]); ?></p>
    </div><?php endforeach; endif; ?>