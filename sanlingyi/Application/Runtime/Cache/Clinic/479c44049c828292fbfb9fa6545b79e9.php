<?php if (!defined('THINK_PATH')) exit();?>

        <?php if(is_array($cmsg)): foreach($cmsg as $key=>$vo): ?><div class="clinicbox">
                <img class="houseimg" src="<?php if($vo->logo_url){echo $vo->logo_url;}else{echo '/sanlingyi/Public/Clinic/images/default@2x.png';} ?>" alt=""/>
                <div class="houseintroduce">
                    <div class="housetitile"><?php echo $vo->name; ?></div>
                    <div class="houseaddr"><?php echo $vo->address; ?></div>
                </div>

                <div class="clear"></div>


                <div class="housedoctor">
                    <div class="titledoc">坐诊医生：</div>
                        <div class="doc">
                            <?php $physicians = $vo->physicians; foreach($physicians as $k=>$v){ if($k<=2){ ?>
                                <div class="docbox">
                                    <img  src="<?php if($v->thumbnail_image_url){echo $v->thumbnail_image_url;}else{echo '/sanlingyi/Public/Clinic/images/督脉正阳师-@2x.png';} ?>" alt=""/>
                                    <div class="p1"><?php echo $v->user_name ?></div>
                                    <div class="p2"><?php echo (dim_rec_code($v->recollection_id)); ?></div>
                                </div>
                            <?php } } ?>
                            <div class="clear"></div>
                        </div>
                </div>
                <!--div class="housebottom">
                    <div class="distin">距离我3500米</div>
                    <div class="position">查看地图上的位置</div>
                    <div class="clear"></div>
                </div-->
                <div class="houseborder"></div>
            </div><?php endforeach; endif; ?>

        <script>

            getImgwidth = $('.clinicbox').width();
            $('.houseintroduce').width(getImgwidth-100);
            $('.posnum').html(<?php echo ($num); ?>);
        </script>