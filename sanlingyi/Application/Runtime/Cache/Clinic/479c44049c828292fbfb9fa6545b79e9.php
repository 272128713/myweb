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
                <div class="housebottom">
                    <div class="distin" Lng="<?php echo ($vo->longitude); ?>" Lat="<?php echo ($vo->latitude); ?>" style="">距离计算中...</div>
                    <a class="position" href="<?php echo U('Index/gaode',array('longitude'=>$vo->longitude,'latitude'=>$vo->latitude,'clinicname'=>$vo->name,'address'=>$vo->address));?>">查看地图上的位置</a>
                    <div class="clear"></div>
                </div>
                <div class="houseborder"></div>
            </div><?php endforeach; endif; ?>

        <script>

            getImgwidth = $('.clinicbox').width();
            $('.houseintroduce').width(getImgwidth-100);
            $('.posnum').html(<?php echo ($num); ?>);




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
                    setCookie('getLng',getLng,0.02);
                    setCookie('getLat',getLat,0.02);
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

        </script>