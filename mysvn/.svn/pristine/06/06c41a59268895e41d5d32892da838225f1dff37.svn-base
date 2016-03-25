<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>用户注册</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/signup.css">

</head>
<body>
    <?php include 'server/province.php' ?>
    <?php include 'header.php' ?>
	<div class="content">
        <div class="title">注册 <span>SIGN UP</span></div>
        <div class="xieyi">账号注册成功后，空中医院相应的APP软件，可免注册直接登录。</div>
        <div class="formbox">
            <form class="form" method="post" action="server/signupto.php" id="signup">
                <div class="int">
                    <div class="lable">姓名</div>
                    <div class="ipt"><input type="text" name="name" id="name" placeholder="请输入中文姓名"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">创建密码</div>
                    <div class="ipt"><input type="password" name="pwd1" id="pwd1" placeholder="请输入8-20位字母或数字"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">确认密码</div>
                    <div class="ipt"><input type="password" name="pwd2" id="pwd2"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">手机号</div>
                    <div class="ipt"><input type="text" name="tel" id="tel"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">验证码</div>
                    <div class="ipt1"><input type="text" name="code" id="code"></div>
                    <div class="getcode" id="get_code">获取验证码</div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">邮箱</div>
                    <div class="ipt"><input type="text" name="mail" id="mail"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="int">
                    <div class="lable">所在城市</div>
                    <div class="ipt">
                        <select name="province" id="province">
                            <option></option>
                            <?php foreach($province as $k=>$v){ ?>
                              <option value="<?php echo $v['provinceID']?>"><?php echo $v['province'];?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="ipt">
                        <select name="city" id="city">
                            <option></option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="submit">注 册</div>
            </form>
            <div class="formimg">
                <div class="p1">还没有注册？</div>
                <div class="p2">还没有特权？</div>
                <div class="p3">快来加入我们<img src="images/you.png"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="js/layer/layer.js"></script>
    <script>
        $('.headli .signup a').css('color','#b0b8b9');
        html="";
        $('#province').change(function(){
            var province_value = $(this).val();
            $.post('server/city.php',{fid:province_value},function(data){
                get_length = data.length;
                for(i=0;i<get_length;i++){
                    html+="<option value='"+data[i].cityID+"'>"+data[i].city+"</option>";
                }
                $('#city').html(html);
            },'json');
        });
        $('.submit').click(function(){

            var tel = $('#tel').val();
            var code = $('#code').val();
            var pwd1 = $('#pwd1').val();
            var pwd2 = $('#pwd2').val();
            var mail = $('#mail').val();
            var province = $('#province').val();
            var city = $('#city').val();
            var name = $('#name').val();

            var checkCity = testCity(city);
            var checkMail = testMail(mail);
            var checkCode = testCode(code);
            var checkTel = testTel(tel);
            var checkPwd = testPwd(pwd1,pwd2);
            var checkName = testName(name);

            if(checkCity&&checkMail&&checkPwd&&checkCode&&checkTel&&checkName){

                    $(this).html('正在注册');
                    ajaxSave(2);

            }

        });





        //校验
        //校验姓名
        function testName(val){

            var reg = /^[\u4E00-\u9FA5]{2,4}$/;

            if(!reg.test(val)){
                index= layer.tips("请填写真实姓名","#name",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验手机号
        function testTel(val){

            var reg = /^1[034578][0-9]{9}$/;

            if(!reg.test(val)){
                index= layer.tips("请填写正确手机号","#tel",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验邮件
        function testMail(val){

            var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;


            if(!reg.test(val)){
                layer.tips("请填写真实邮件地址","#mail",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验验证码
        function testCode(val){



            if(!val){
                layer.tips("请填写验证码",".getcode",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验地区
        function testCity(val){



            if(!val){
                layer.tips("请填写地区","#city",{tips:[2,'#4dbbaa']});
                return false;
            }else{
                return true;
            }

        }
        //校验密码
        function testPwd(val1,val2){



            if(!val1){

                layer.tips("请填写密码","#pwd1",{tips:[2,'#4dbbaa']});
                return false;

            }else if(val1.length<8||val1.length>20){
                layer.tips("密码长度需大于等于8位小于等于20位","#pwd1",{tips:[2,'#4dbbaa']});
                return false;
            }else if(!val2){
                layer.tips("请确认密码","#pwd2",{tips:[2,'#4dbbaa']});
                return false;
            }else if(val1!=val2){

                layer.tips("两次密码不一致，请重新填写","#pwd2",{tips:[2,'#4dbbaa']});
                return false;


            }else{
                return true;
            }

        }



        //获取验证码
        $('#get_code').click(
            function(){
                $(this).html('正在获取');
                ajaxSave(1);
            }
        );
        function show_error(msg){
            $('#get_code').html('获取验证码');
            layer.alert(msg,{icon:2,title:false,closeBtn:false});
        }
        function ajaxSave(type){
            var un=$("#tel").val();
            var pw=$("#pwd1").val();
            var rn=$("#name").val();
            var pin=$("#code").val();
            var province=$("#province").val();
            var city=$("#city").val();
            var mail=$("#mail").val();
            $.post('server/php/save.php',{'un':un,'pw':pw,'rn':rn,'pin':pin,'type':type,'province':province,'city':city,'mail':mail},function(data){
                if(type==1){
                    if(data==001){
                        show_error('手机号不正确');
                    }else if(data==002){
                        show_error('密码格式不正确');
                    }else if(data==003){
                        show_error('姓名格式不正确');
                    }else if(data==1002){
                        show_error('账号已经存在');
                    }else if(data==1003){
                        $('#get_code').html('获取成功');


                    }else if(data==1004){
                        show_error('注册次数过多');
                    }else if(data==1005){
                        show_error('发送短信失败');
                    }else if(data==1020){
                        show_error('注册时间间隔过短');
                    }else{
                        show_error('系统错误');
                    }
                    //注册
                }else if(type==2){
                    if(data==004){
                        show_error('验证码错误');
                        $('.submit').html("注 册");
                    }else if(data==0){
                        $('.submit').html("注册成功");
                        window.location.href="login.php";
                    }

                }
            },'text')
        }

















    </script>
    <style>
        .layui-layer-btn .layui-layer-btn0{
            border-color: #4dbbaa;
            background-color: #4dbbaa;
        }
    </style>

</body>
</html>
