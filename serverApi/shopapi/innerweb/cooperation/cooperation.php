<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>公益慈善机构</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="./css/mui.min.css">
    <link rel="stylesheet" type="text/css" href="css/app.css" />
    <link rel="stylesheet" type="text/css" href="css/common.css" />
    <link rel="stylesheet" type="text/css" href="css/charityOrg.css" />
    <?php include "php/org_list.php";?>
    <style>
        .headtitle .mui-content .area{
            float:right;
            position:relative;
        }

        .headtitle .mui-content .area{
            color:#fff;
            background:url(images/rightbg.png) no-repeat top center;
            background-size:100% 25px;
            padding-left:1em;
            padding-right:1.3em;
            line-height:26px;
            margin-top:14.5px;
        }
    </style>
</head>

<body>
<div class="headtitle">
    <div class="mui-content">
        <p class="tit">已有公益机构：<?php echo $num;?>家</p>
        <div class="area" onclick="gotoA()">
            机构申请
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php foreach($list as $k=>$v){?>
<div class="org" onclick="window.location.href='orgDetail.php?oid=<?php echo $v->id;?>&im=<?php echo $v->isMe;?>'">
    <div class="mui-content">
        <img class="logo" src="<?php echo $v->logo_url;?>">
        <div class="orgintro">
            <p class="name"><?php echo $v->name;?><p>
            <p class="start">成立时间：<?php echo date("Y年m月d日",strtotime($v->build_time));?></p>
            <p class="nature">机构性质：<?php echo $v->features;?></p>
        </div>
        <div class="clear"></div>
        <div class="line"></div>
        <div class="count">
            <p class="renshu">
                <img src="images/ren.png">
                志愿者<?php echo $v->sign_num;?>人
            </p>
            <p class="huodong">
                <img src="images/sidai.png">
                公益活动<?php echo $v->ac_num;?>次
            </p>
            <div class="clear"></div>
        </div>
        <?php if($v->isMe=='1'){echo "<div class='my'>我</div>";}?>

    </div>
</div>
<?php }?>
<script>
    function gotoA(){

        if(isiphone=navigator.userAgent.indexOf("iPhone")>0){

            window.location.href="organization_apply.php"

        }

        if(isAndroid=navigator.userAgent.indexOf("Android")>0){
            //alert(1);
            if(navigator.userAgent.match('Android')){
//            	 Android.goToAddauth();
                 Android.gotoApply();
//                alert(1111);
             }


        }



    }

</script>
</body>

</html>