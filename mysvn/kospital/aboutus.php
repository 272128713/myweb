<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>关于我们</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/aboutus.css">
</head>
<body>
    <?php include 'header.php' ?>
    <?php include 'server/about.php' ?>
	<!-- Swiper -->
	<div class="banner_yixin">
	        <div class="bannerimg" style="background-image:url(images/banner-angel.png);background-repeat: no-repeat"></div>
	</div>
	<div class="content">
        <div class="recruit" id="recruit">
            <p class="tese">招聘职位</p>
            <div class="lingxing">
                <img src="images/lingxing.png">
            </div>
            <div class="recbox">
                <div class="job">
                    <ul>
                        <?php
                        foreach($datas as $k=>$v){ ?>
                        <li <?php if($k==0){ echo "class='select'";}?> fid="<?php echo $k+1;?>"><?php echo $v['jobname']?></li>
                        <?php
                            $job[$k]=addslashes($v['jobdetail']);

                        }
                        ?>
                    </ul>
                </div>
                <div class="showjob">

                </div>
                <a class="emailjob" href="Mailto:kzyxzqn@163.com">
                    <img src="images/mail.png">
                    <div class="p1">简历投递至</div>
                    <div class="p2">kzyxzqn@163.com</div>
                </a>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
        <div class="du">
            <p class="tese">我们有度</p>
            <div class="lingxing">
                <img src="images/lingxing.png">
            </div>
            <div class="du-box">
                <div class="content" style="padding-left: 7px">
                    <div class="du-listbox">
                        <div class="p1">有深度</div>
                        <div class="p2"></div>
                        <div class="p3">专注打造大健康产业链布局</div>
                    </div>
                    <div class="du-imgbox" style="background:url('images/du0.png') 100% 100% no-repeat"></div>
                    <div class="du-listbox">
                        <div class="p1">有温度</div>
                        <div class="p2"></div>
                        <div class="p3">健康产业实体线下服务品牌</div>
                    </div>
                    <div class="du-imgbox" style="background:url('images/du1.png')  no-repeat;background-size: cover"></div>
                    <div class="du-listbox">
                        <div class="p1">有态度</div>
                        <div class="p2"></div>
                        <div class="p3">我们都是健康投资的合伙人</div>
                    </div>
                    <div class="du-imgbox" style="background:url('images/du2.png') 100% 100% no-repeat"></div>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
        <div class="content">
            <div class="healthform" id="healthform" style="padding-top:29px;margin-top:61px">
                <p class="tese">健康有你</p>
                <div class="lingxing">
                    <img src="images/lingxing.png">
                </div>
                <img src="images/guwen.png" style="margin-bottom: 70px">
                <form class="ff" action="server/form.php" method="post">
                    <div class="inp">
                        <input type="text" placeholder="姓名" name="name" id="name"/>
                        <input type="text" placeholder="邮箱" name="mail" id="mail" />
                        <input type="text" placeholder="主题" name="title" id="title"/>
                    </div>
                    <textarea placeholder="在这里写下你的留言..." name="content" id="content"></textarea>
                    <div class="clearfix"></div>
                    <div class="submit">
                        <span>发 送 </span>
                        <img src="images/send.png">
                    </div>
                </form>
            </div>
        </div>
    <?php include 'footer.php' ?>
    <script src="js/layer/layer.js"></script>
    <script>
        getUlheight = $('.job ul').height();
        $('.emailjob').height(getUlheight);
        $('.emailjob img').css('margin-top',getUlheight/2-45);
        $('.showjob').height(getUlheight-60);
        html="<?php echo $job[0];?>";
        $('.showjob').html(html);

        //招聘
        $('.job ul li').hover(function () {
            $(this).addClass('select');
            $(this).siblings().removeClass('select');
            var zpid = $(this).attr('fid');
            console.log(zpid);
            if(zpid==1) {
                html = "<?php echo $job[0];?>";
            }
            <?php foreach($job as $k=>$v){
                if($k==0){

                }else{
                ?>
                    else if(zpid==<?php echo $k+1;?>){
                        html = "<?php echo $v;?>";
                    }
                <?php
                }
            }?>


        $('.showjob').html(html);


        });



    </script>
























    <script>
        //表单
        
        $('.submit').click(function(){
            var content = $('#content').val();
            var checkContent = testContent(content);
            var title = $('#title').val();
            var checkTitle = testTitle(title);
            var mail = $('#mail').val();
            var checkMail = testMail(mail);
            var name = $('#name').val();
            var checkName = testName(name);



            if(checkName&&checkMail&&checkTitle&&checkContent){
                $('.ff').submit();
            }

        });



        //校验姓名
        function testName(val){

            var reg = /^[\u4E00-\u9FA5]{2,4}$/;

            if(!reg.test(val)){
               index= layer.tips("请填写真实姓名","#name",{tips:[4,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验邮件
        function testMail(val){

            var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;


            if(!reg.test(val)){
                layer.tips("请填写真实邮件地址","#mail",{tips:[4,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验标题
        function testTitle(val){



            if(!val){
                layer.tips("请填写主题","#title",{tips:[4,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验内容
        function testContent(val){



            if(!val){
                layer.tips("请填写主题","#content",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
    </script>
</body>
</html>
