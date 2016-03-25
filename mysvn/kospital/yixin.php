<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>医信健康</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/yixin.css">
</head>
<body>
    <?php include 'header.php' ?>
	<!-- Swiper -->
	<div class="banner_yixin">
	    <div class="bannerimg" style="background-image:url(images/banner-yixin.png)"></div>
	</div>
	<div class="content">
        <div class="tec">
            <div class="tectitle"><span style="color: #516275;">科技引领健康</span><span style="color: #4dbbaa;margin-left: 20px">让关爱更有温度</span></div>
            <div class="shotline"></div>
            <div class="tecen">Lead a trend of internet+ health. Make your life better.</div>
            <div class="tecimg" style="background: url('images/tec.png')"></div>
        </div>
    </div>
    <div class="arrownav" onclick="location.href='#download'">
        <div class="arrow"></div>
    </div>
    <div class="content" id="download">
        <div class="download">
            <div class="downloadtitle">选择平台下载APP</div>
            <div class="titleshot">您的掌上健康管理专家</div>
            <div class="downloadlist">
                <div class="dlist" style="cursor:pointer;">
                    <img  src="images/apple.png"/>
                    <div class="dp">iPhone下载</div>
                </div>
                <div class="dlist" style="cursor:pointer;">
                    <img  src="images/android.png"/>
                    <div class="dp">Andriod下载</div>
                </div>
                <div class="dlist" onclick="alert('对不起，暂不支持下载')" style="margin:0;display: none;">
                    <img  src="images/code1.png"/>
                    <div class="dp">扫描二维码</div>
                </div>
                <div class="clearfix"></div>
            </div>
            <img class="ar" src="images/arrow.png" onclick="location.href='#product'"/>
        </div>
    </div>
    <div class="product" id="product">
        <div class="content-p">
            <div class="product-box">
                <div class="p-title">健康商城</div>
                <div class="p-detail">专业的健康医疗保健品服务电子商务平台，给您提供十大慢病整体防治方案，每日根据您的血压、血糖等综合指标由专家为您提供专业的健康管理方案和风险评估。</div>
            </div>
            <div class="product-box-2">
                <div class="p-title">健康圈</div>
                <div class="p-detail">由健康管理师牵头组建的医信线上健康圈，所有用户可在群里沟通交流属于自己的健康心得，健康管理师也在圈内分享健康知识和健康产品。</div>
            </div>
            <div class="product-box-3">
                <div class="p-title">失联防范</div>
                <div class="p-detail">专门为学生、妇女、老年人量身定做的行动轨迹定位系统，系统通过GPS，每分钟锁定一次，将行动轨迹发给指定的监护人。防止目标失去联系。</div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <?php include 'footer.php' ?>
    <script>

        $('ul li:nth-child(4)').addClass('selected');
        $(function(){	
	        $('.dlist').click(function(){
	              layer.msg('对不起，暂不支持下载',{time:2000});
	        });
        });
    </script>
</body>
</html>