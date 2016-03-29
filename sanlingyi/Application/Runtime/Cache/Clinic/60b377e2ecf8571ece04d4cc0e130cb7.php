<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <style type="text/css">
        body,html,#container{
            height: 100%;
            margin: 0px
        }
    </style>
    <title><?php echo ($clinicname); ?></title>

</head>
<body>
<img src="/sanlingyi/Public/Clinic/images/close_image@3x.png" alt="" style="position: absolute;
    top: 6px;
    right: 5px;
    width: 35px;
    height: 35px;
    z-index: 99999;" onclick="history.go(-1)"/>
<div id="container" tabindex="0"></div>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=2936b33c7519f47b2013c5c1e92c0f07"></script>
<script type="text/javascript">

    //初始化 设置中心点
    var map = new AMap.Map('container',{
        resizeEnable: true,
        zoom: 15,
        center: [<?php echo ($longitude); ?>,<?php echo ($latitude); ?>]
    });
    //标注位置
    var marker = new AMap.Marker({
        position: [<?php echo ($longitude); ?>,<?php echo ($latitude); ?>]
    });
    marker.setMap(map);
    map.setCenter(marker.getPosition());


    //标尺，放大缩小
    AMap.plugin(['AMap.ToolBar','AMap.Scale'],function(){
        AMap.plugin(['AMap.ToolBar','AMap.Scale'],function(){
            var toolBar = new AMap.ToolBar();
            var scale = new AMap.Scale();
            map.addControl(toolBar);
            map.addControl(scale);
        });
    });
    //信息窗体的创建与设定
    var infowindow = new AMap.InfoWindow({
        content: '<div style="color:#343434;font-weight: 700;font-size: 15px;line-height: 30px"><?php echo ($clinicname); ?></div><div style="color:#343434;font-size: 14px"><?php echo ($address); ?></div>',
        offset: new AMap.Pixel(0, -30),
        size:new AMap.Size(230,0)
    });
    var clickHandle = AMap.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker.getPosition());
    });

</script>
</body>
</html>