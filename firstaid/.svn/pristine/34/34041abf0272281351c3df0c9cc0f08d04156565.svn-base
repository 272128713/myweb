<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>稻草垛</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="css/mui.min.css">
    <link rel="stylesheet" type="text/css" href="css/app.css" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <?php include "php/get_receive.php";?>
</head>
<body>
<div class="headtitle">
    <div class="mui-content">
        <div class="number">当前有<?php if($count){ echo $count;}else{ echo 0;}?>个稻草包</div>
        <div class="jilu" style="color:#ff5622">【我的赠送记录】</div>
        <div class="clear"></div>
    </div>
</div>

<?php
if(!$result){
    echo "<div style='text-align:center;margin-top:40px;line-height: 74px;font-size: 14px; color:#333;'>当前还没有人给你送稻草包！</div>";

}


foreach($result as $k=>$v){
    $imgcount = count($v->img_url);
    ?>
<div class="strawlist">
    <div class="mui-content">
        <div class="title">
            <div class="name"><span><?php echo $v->user_name;?></span>送来的稻草包</div>
            <div class="time" style="color:#999"><?php echo $v->createDate;?></div>
            <div class="clear"></div>
        </div>
        <div class="scroll">
            <div class="swiper-container">
                <div class="swiper-wrapper">

                    <?php for($i=0;$i<$imgcount;$i=$i+3){?>
                    <div class="swiper-slide">
                        <?php for($j=0;$j<3;$j++){
                        $n = $i+$j;
                        if($n>=$imgcount){

                        }else{
                        ?>
                            <div class="pull-left">
                                <img src="<?php echo $v->img_url[$n]->img_url;?>">
                            </div>
                        <?php }}?>
                    </div>
                    <?php }?>



                </div>
            </div>
        </div>
        <p class="p1">共有<?php echo $v->count;?>件商品<span>总价值<?php echo sprintf('%.2f',$v->totalprice);?>元</span></p>
        <!--        -->
        <?php if($v->order_state ==1){?>
        <div class="button">
            <div class="btn btn1" style="background: #ff5622;" onclick="window.location.href='transfer.php?on=<?php echo $v->order_nums;?>'">转送稻友</div>
            <div class="btn btn2" onclick="window.location.href='confirmReceipt.php?on=<?php echo $v->order_nums;?>'">确认收货</div>
            <div class="clear"></div>
        </div>
        <?php }elseif($v->order_state ==3){?>
        <div class="sure">
            <img src="images/xing.png">已确定收货
        </div>
        <?php }?>
    </div>
</div>
<?php }?>
<script src="js/jquery.min.js"></script>
<script src="js/swiper.min.js"></script>
<script type="text/javascript">
    //图片滚动初始化
    var mySwiper = new Swiper('.swiper-container',{
        autoplay: 0,
        loop : false
    });
    $('.jilu').click(function(){
        window.location.href="records.php";
    });
</script>
</body>
</html>
