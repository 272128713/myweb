<?php if (!defined('THINK_PATH')) exit();?>
                        <?php if(is_array($news)): foreach($news as $key=>$vo): ?><li>
                                <a href="<?php echo U('Index/detail',array('aid'=>$vo['id']));?>">

                                <div class="headimg">
                                    <div style="position: absolute;z-index:1;width: 100%;">
                                        <?php if($vo['authentication']==11){ ?>
                                        <img src="/sanlingyi/Public/Hospital/images/icon_authdoctor_v2.png" style="width:8.5%;"/>
                                        <?php }else if($vo['authentication']==1){ ?>
                                        <img src="/sanlingyi/Public/Hospital/images/icon_authdoctor_v1.png" style="width:8.5%;"/>
                                        <?php } ?>
                                        <img class="auth" src="/sanlingyi/Public/Hospital/images/icon_authdoctor.png" style="width:5.5%;position:absolute;left:9%;"/>
                                    </div>
                                    <img src="<?php echo ($vo["img_url"]); ?>"/>
                                </div>

                                <div class="list-box">
                                    <p class="p1 ">
                                         <?php echo ($vo["user_name"]); ?>
                                        <p class="p2"><?php echo $vo['recollection_id']; ?></p>
                                        <div class="clear"></div>
                                    </p>

                                    <div class="p3">
                                        <div><?php echo ($vo["title"]); ?></div>
                                        <?php if($vo['cimg_url'][0]['source_image_url']){ ?>
                                        <img src="<?php echo $vo['cimg_url'][0]['source_image_url']; ?>" alt="" style="width: 35%;float: none;margin-top: 5px"/>
                                        <?php } ?>
                                    </div>
                                    <div class="p4">
                                        <div class="time"><?php echo substr($vo['createDate'],5,5); ?></div>
                                        <div class="blue sort"><?php echo get_name($vo['columns']); ?></div>
                                        <div class="joined">已参与人次：<?php echo ($vo["comment_cont"]); ?></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                </a>
                            </li><?php endforeach; endif; ?>