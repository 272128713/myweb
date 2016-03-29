<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>空中诊所</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="/sanlingyi/Public/Clinic/css/myclinic.css" />
        <style>

            /*图标向上旋转*/
            .hover-up{
                transition-duration: .5s;
                transform: rotate(180deg);
                -webkit-transform: rotate(180deg);
            }
            /*图标向下旋转*/
            .hover-down{
                transition-duration: .5s;
                transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
            }
        </style>
	</head>

	<body>
        <div class="headerbar">
            <div class="areanum"><span class="positiontop">全国</span>共有<span class="posnum"><?php echo ($num); ?></span>家</div>
            <div class="selectarea" cl="1">
                <img src="/sanlingyi/Public/Clinic/images/down.png" alt="" style="width: 19px;height: 12px"/>
                选择地区
            </div>
            <div class="detail" id="birth_pVal" vl="" style="display: none"></div>
            <div class="clear"></div>
        </div>
        <div id="hospitalbox">
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
                    <div class="housebottom">
                        <div class="distin" Lng="<?php echo ($vo->longitude); ?>" Lat="<?php echo ($vo->latitude); ?>" style="">距离计算中...</div>
                        <a class="position" href="<?php echo U('Index/gaode',array('longitude'=>$vo->longitude,'latitude'=>$vo->latitude,'clinicname'=>$vo->name,'address'=>$vo->address));?>">查看地图上的位置</a>
                        <div class="clear"></div>
                    </div>
                    <div class="houseborder"></div>
                </div><?php endforeach; endif; ?>
        </div>
        <script src="/sanlingyi/Public/Clinic/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="/sanlingyi/Public/Clinic/js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
        <script src="/sanlingyi/Public/Clinic/js/iscroll.js" type="text/javascript" charset="utf-8"></script>
        <script src="/sanlingyi/Public/Clinic/js/area_city.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/sanlingyi/Public/Clinic/css/area.css"/>
        <script type="text/javascript">
            isclick = '';
            html = '';
            docthtml = "<div style='position: absolute;top: 39%;width:100%;text-align:center;color:#A0A0A0;font-size: 14px;'>该地区暂无诊所</div>";
            hbox = $('#hospitalbox');
            getImgwidth = $('.clinicbox').width();
            $('.houseintroduce').width(getImgwidth-100);

            $('.selectarea').click(function(){

                isclick = $(this).attr('cl');
                if(isclick == 1){
                    $(this).children('img').removeClass('hover-down').addClass('hover-up');
                    $('.clinicbox').hide();
                    $('.selectarea').attr('cl',2);
                    var pcid = $(this).children('.detail').attr('id');
    //                localStorage["pcVal"]=pcid;
                    setCookie('pcVal',pcid,7);
                    getProvinceBuy(function(pid,cid,pcname){
                        $('.clinicbox').show();
                        areaUrl = '<?php echo U('Index/showArea');?>';
                        data = {
                            'province' : pid,
                            'city' : cid
                        }
                        loadindex = layer.load();
                        $('.selectarea').children('img').removeClass('hover-up').addClass('hover-down');
                        $.post(areaUrl,data, function(data){

                            layer.close(loadindex);
                            $('.selectarea').attr('cl',1);
                            $('.positiontop').html(pcname);
                            if(data){
                                hbox.html(data);
                            }else{

                                $('.posnum').html(0);
                                hbox.html(docthtml);
                            }
                        });
                    });
                }else if(isclick == 2){

                    $(this).children('img').removeClass('hover-up').addClass('hover-down');
                    $('.clinicbox').show();
                    $('.dqld_div').hide();
                    $('.selectarea').attr('cl',1);
                }
            });



        </script>
        <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=2936b33c7519f47b2013c5c1e92c0f07"></script>
        <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
        <script type="text/javascript">
            var map, geolocation;
            //加载地图，调用浏览器定位服务
            getLng = getCookie('getLng');
            getLat = getCookie('getLat');
            if(getLng&&getLat){
                //
                $('.clinicbox').each(function(){
                    //诊所位置
                    clnLng = $(this).children('.housebottom').children('.distin').attr('Lng');
                    clnLat = $(this).children('.housebottom').children('.distin').attr('Lat');

                    //计算距离
                    var lnglat = new AMap.LngLat(getLng, getLat);
                    dist = lnglat.distance([clnLng, clnLat]);
                    distcalc = clac(dist);
                    $(this).children('.housebottom').children('.distin').html("距离我"+distcalc);
                    //                    $(this).children('.housebottom').children('.distin').show();
                });
            }else{
                map = new AMap.Map('container', {
                    resizeEnable: true
                });
                map.plugin('AMap.Geolocation', function() {
                    geolocation = new AMap.Geolocation({
                        enableHighAccuracy: true,//是否使用高精度定位，默认:true
                        timeout: 10000,          //超过10秒后停止定位，默认：无穷大
                        buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                        zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                        buttonPosition:'RB'
                    });
                    map.addControl(geolocation);
                    geolocation.getCurrentPosition();
                    AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息

                    arr_error =AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
                });
                //解析定位结果
                function onComplete(data) {
                    getLng = data.position.getLng();
                    getLat = data.position.getLat();
                    setCookie('getLng',getLng,30*60*1000);
                    setCookie('getLat',getLat,30*60*1000);
    //                console.log(getLng+','+getLat);
    //                var lnglat = new AMap.LngLat(getLng, getLat);
    //                dist = lnglat.distance([116.387271, 39.922501]);
    //                console.log(dist);
                    $('.clinicbox').each(function(){
                        //诊所位置
                        clnLng = $(this).children('.housebottom').children('.distin').attr('Lng');
                        clnLat = $(this).children('.housebottom').children('.distin').attr('Lat');

                        //计算距离
                        var lnglat = new AMap.LngLat(getLng, getLat);
                        dist = lnglat.distance([clnLng, clnLat]);
                        distcalc = clac(dist);
                        $(this).children('.housebottom').children('.distin').html("距离我"+distcalc);
    //                    $(this).children('.housebottom').children('.distin').show();
                    });
                }
                //解析定位错误信息
                function onError(data) {
    //                getLL = false;
                    $('.distin').html('无法获取您的位置');
                }
            }

            function clac(cc){
                //取整+换算km
                cc = Math.round(cc);
                if(cc>=1000){
                    cc = cc/1000+'km';
                }else if(cc>10000000){
                    cc = "大于10000km";
                }else{
                    cc = cc+'m';
                }
                return cc;
            }
        </script>


	</body>

</html>