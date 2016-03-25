
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>空中诊所</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/main.css" />
        <style>
            .mui-card .mui-control-content {
                padding: 10px;
            }
            .mui-control-content {
                height: 150px;
            }
        </style>

	</head>
	
	<body>
		<!--轮播-->
        <div id="slider" class="mui-slider">
            <div class="mui-slider-group mui-slider-loop">
                <!--div class="mui-slider-item mui-slider-item-duplicate">
                    <a href=""><img src="images/banner0.png"></a>
                </div>
                <div class="mui-slider-item">
                    <a href=""><img src="images/banner0.png"></a>
                </div-->
                <div class="mui-slider-item">
                    <a href=""><img src="images/banner0.png"></a>
                </div>
                <!--div class="mui-slider-item mui-slider-item-duplicate">
                    <a href=""><img src="images/banner0.png"></a>
                </div-->
            </div>
            <!--div class="mui-slider-indicator">
                <div class="mui-indicator mui-active"></div>
                <div class="mui-indicator"></div>
            </div-->
        </div>
            <div class="gray-line"></div>
            <div class="nav-all">
                <div id="segmentedControl" class="mui-segmented-control">
                    <a class="mui-control-item mui-active" href="#item1" id="s1">
                        我们的服务
                    </a>
                    <a class="mui-control-item" href="#item2" id="s2">
                        套餐服务
                    </a>
                    <a class="mui-control-item" href="#item3" id="s3">
                        加入我们
                    </a>
                    <a class="mui-control-item" href="#item4" id="s4">
                        店面展示
                    </a>
                </div>
            </div>
            <div class="imgbot"></div>

            <div>
                <!--我们的服务-->
                <div id="item1" class="mui-control-content mui-active">
                    <div class="mui-content">
                        <div class="s-title">
                            <span>OUR</span><span>SERVICE</span>
                        </div>
                        <div class="title1">空中诊所</div>
                        <img src="images/gang.png" alt="" style="display: block;width: 12px;margin:0 auto;"/>
                        <div style="text-align: center;margin-top:5px;"><span style="background: #bfbfbf;color:#fff;padding: 5px 14px;font-size:13px;">“有温度”的私人医生服务实体连锁</span></div>
                        <img src="images/sanjiao.png" alt="" style="width:95%;display: block;margin:5px auto;"/>
                        <img src="images/dream.png" alt="" style="width:90%;display: block;margin:10px auto;"/>
<!--                        <div class="title2">-->
<!--                            <p>空中诊所让您活到百岁不是梦！</p>-->
<!--                            <p>我们的服务为预期寿命延长5—10年，对四大类疾病可以降低60%~80%发生的可能性，实现国人平均寿命达75岁。</p>-->
<!--                        </div>-->
                        <div style="color:#4dbbaa;font-size: 18px;border-left: 3px solid #4dbbaa;margin-bottom:14px;padding-left: 0.5em">诊所服务</div>
                        <div class="servicebox">
                            <div class="servicelist" onclick="window.location.href='server1.php'">
                                <img src="images/service1.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p1">私人医生服务</p>
                                <p class="p2">私人医生六大板块，点击了解更多...</p>
                            </div>
                            <div class="centerline"></div>
                            <div class="servicelist" onclick="window.location.href='server2.php'">
                                <img src="images/service2.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p1">督脉正阳理疗</p>
                                <p class="p2">疏通阳脉之海，提振阳气点击查看详情...</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="servicebox">
                            <div class="servicelist" onclick="window.location.href='server3.php'">
                                <img src="images/service3.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p1">子午流注理疗</p>
                                <p class="p2">结合传统针灸，活血通络点击查看详情...</p>
                            </div>
                            <div class="centerline"></div>
                            <div class="servicelist" onclick="window.location.href='server4.php'">
                                <img src="images/service4.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p1">正阳舱保健</p>
                                <p class="p2">舒缓肌体疲劳，内守真气点击查看详情...</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="servicebox">
                            <div class="servicelist" onclick="window.location.href='server5.php'">
                                <img src="images/service5.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p1">诊所急救培训</p>
                                <p class="p2">提高急救技能，保障生命安全...</p>
                            </div>
                            <div class="centerline"></div>
                            <div class="servicelist">
                                <img src="images/service6.png" alt=""/>
                                <div class="line2"></div>
                                <p class="p3" style="height: 47px">更多服务<br/>敬请期待</p>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </div>

                    <?php include "footer.php";?>

                </div>

                <!--套餐服务-->
                <div id="item2" class="mui-control-content">
                    <div class="mui-content">
                        <div class="s-title">
                            <span>PACKAGE</span><span>SERVICE</span>
                        </div>
                        <div class="title3">
                            <div class="hidnum">1</div>
                            <div class="p1">充值会员</div>
                        </div>
                        <img id="money" cid="0" src="images/money.png" alt="" style="width:90%;display:block;margin: 0 auto;"/>
                        <div class="title3">
                            <div class="hidnum">2</div>
                            <div class="p1">资格会员</div>
                        </div>
                        <img src="images/money2.png" alt="" style="width:90%;display:block;margin: 0 auto;margin-top:5px"/>
                        <div class="title3">
                            <div class="hidnum">3</div>
                            <div class="p1">合伙会员</div>
                        </div>
                        <img src="images/money3.png" alt="" style="width:90%;display:block;margin: 0 auto;margin-top:5px;margin-bottom:26px"/>

                    </div>
                    <div class="btable">
                        <div class="fbox">
                            <div class="tt">只有不到五折<br/>合伙股金可溢价转让、可全额退还！</div>
                            <img src="images/formtmp.png" alt="" style="width: 100%"/>
                        </div>
                    </div>
                    <?php include "footer.php";?>
                </div>
                <!--加入我们-->
                <div id="item3" class="mui-control-content">
                    <div class="mui-content">
                        <div class="s-title">
                            <span>JOIN</span><span>US</span>
                        </div>
                    </div>
                    <div class="btable">
                        <img src="images/list.png" alt="" style="width:80%;margin: 0 auto;display: block"/>
                        <div class="toge" style="background: rgba(173, 215, 208,0.5);width: 80%;margin: 17px auto;padding: 15px 0;margin-bottom:0">找到能与你一块儿共事的合伙人<br/>空中医院为你投资<span>100</span>万！</div>
                    </div>
                    <img src="images/subto.png" alt="" style="width:95%;margin:0 auto;display:block;margin-top:34px" onclick="setform()"/>
                    <img src="images/fuwu.png" alt="" style="width:100%;margin-top:20px"/>
                    <?php include "footer.php";?>
                </div>
                <!--店面展示-->
                <div id="item4" class="mui-control-content">
                    <div class="mui-content">
                        <div class="s-title">
                            <span>IN-STORE</span><span>DISPLAY</span>
                        </div>


                        <div class="servicebox" style="margin-top: 12px">
                            <div class="servicelist" onclick="window.location.href='shoplz.php';">
                                <img src="images/klinic.png" alt=""/>
                                <div class="line2" style="background-image: url('images/sd.png');background-size: contain;background-repeat: no-repeat"></div>
                                <p class="p1">西安枫林绿洲连锁</p>
                                <p class="p2">西安市高新路枫林绿洲F区1号楼2单元204室</p>
                                <p class="p4">029—88452408</p>
                            </div>
                            <div class="centerline"></div>
                            <div class="servicelist" onclick="window.location.href='shopdt.php';">
                                <img src="images/dt.png" alt=""/>
                                <div class="line2" style="background-image: url('images/sd.png');background-size: contain;background-repeat: no-repeat"></div>
                                <p class="p1">西安大唐西市连锁</p>
                                <p class="p2">西安市大唐西市北丰庆苑C座4号楼202</p>
                                <p class="p4">029-88770889</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="servicebox">
                            <div class="servicelist" onclick="window.location.href='shopcq.php';">
                                <img src="images/dt2.png" alt=""/>
                                <div class="line2" style="background-image: url('images/sd.png');background-size: contain;background-repeat: no-repeat"></div>
                                <p class="p1">重庆春晖路连锁</p>
                                <p class="p2">重庆市大渡口区西城大道666号锦愉社区</p>
                                <p class="p4">023—68935923</p>
                            </div>
                            <div class="centerline"></div>
                            <div class="servicelist">
                                <img src="images/service6.png" alt=""/>
                                <div class="line2" style="background-image: url('images/sd.png');background-size: contain;background-repeat: no-repeat"></div>
                                <p class="p1">西安i都会连锁</p>
                                <p class="p2">逸翠园二期—i都2A期商铺G2园3栋2层</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php include "footer.php";?>
                </div>
            </div>
            <div id="layercontent" class="layercontent">
                <form action="" id="formindex">
                <div style="text-align: right;margin-top:15px;"><img id="closeBtn" src="images/xx.png" alt="" style="width: 35px;position:relative;top:-10px;left:10px;"/></div>
                <img src="images/find.png" alt="" style="width: 85%;margin:0 auto;display:block;margin-top: -12px;"/>
                <div style="text-align: center;color:#4c4c4c;font-size: 15px;margin-top:22px;margin-bottom:5px" class="inputind">
                    <input type="radio" name="type" value="1"/>医生&nbsp;&nbsp;
                    <input type="radio" name="type" value="2"/>用户&nbsp;&nbsp;
                    <input type="radio" name="type" value="3"/>按摩师&nbsp;&nbsp;
                    <input type="radio" name="type" value="4"/>创业者
                </div>
                <textarea name="content" id="content" placeholder="在这里留言" style="border:1px solid #c1c1c1;width: 100%; height: 104px;margin-top:10px"></textarea>
                <input type="text" placeholder="留下您的电话号码或QQ号码" id="mail" name="mail" style="border: 0;border-bottom: 1px solid #c1c1c1"/>
                <div style="text-align: center;margin-bottom:20px;background: #fff"><div class="submitTo" id="submitTo" style="background: #4dbbaa">发&nbsp;&nbsp;&nbsp;&nbsp;送</div></div>
                </form>
            </div>
        <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/mui.min.js"></script>

        <script>

            //轮播
            var slider = mui("#slider");
            slider.slider({
                interval: 2000
            });
//            mui.init({
//                swipeBack:true //启用右滑关闭功能
//            });
            (function($) {
                $('#scroll').scroll({
                    indicators: true //是否显示滚动条
                });
                var segmentedControl = document.getElementById('segmentedControl');
                $('.mui-input-group').on('change', 'input', function() {
                    if (this.checked) {
                        var styleEl = document.querySelector('input[name="style"]:checked');
                        var colorEl = document.querySelector('input[name="color"]:checked');
                        if (styleEl && colorEl) {
                            var style = styleEl.value;
                            var color = colorEl.value;
                            segmentedControl.className = 'mui-segmented-control' + (style ? (' mui-segmented-control-' + style) : '') + ' mui-segmented-control-' + color;
                        }
                    }
                });
            })(mui);
            //money切换
            $('#money').click(function(){
               cid = $(this).attr("cid");
                console.log(cid);
               if(cid==0){
                 $(this).attr("src","images/money1.png");
                 $(this).attr("cid","1");
               }else if(cid==1){
                   $(this).attr("src","images/money.png");
                   $(this).attr("cid","0");

               }

            });
            function setform(){
                layer.open({
                    type: 1, //page层
                    area: ['90%', '366px'],
                    title: false,
                    closeBtn: false,
                    shade: 0.6, //遮罩透明度
                    content: $('#layercontent')
                });
            }
//            关闭弹框
            $("#closeBtn").click(function(){
                layer.closeAll();
            });
//            提交表单
            $("#submitTo").click(function(){
                type="";
                type = $("input[type='radio']:checked").val();
                content = $("#content").val();
                mail = $("#mail").val();
//                console.log(type+","+content+","+mail);
                if(mail==""){
                    layer.tips("请填写电话或QQ号",'#mail',{
                            tips: 1,
                        }
                    );
                }else{
                    $.post("server/form.php",{'type':type,'content':content,'mail':mail},function(data){
                        console.log(data);
                        if(data==1){

                            layer.closeAll();
                            layer.alert("申请成功");
                        }else{
                            layer.closeAll();
                            layer.alert("申请失败，请稍后重试");
                        }
                    });
                }
            });

        </script>
        <style>
            .layui-layer-tips .layui-layer-content{
                background-color: #4dbbaa;
            }
            .layui-layer-tips i.layui-layer-TipsB, .layui-layer-tips i.layui-layer-TipsT{
                border-right-color: #4dbbaa;
            }
        </style>
	</body>
</html>
