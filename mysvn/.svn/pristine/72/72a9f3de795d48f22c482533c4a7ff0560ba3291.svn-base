<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>登录</title>
		<link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/login.css">
	</head>
	<body>
        <?php include "header.php";?>
		<div class="content-t">
			<div class="login">
				<p class="header">登录<span>LOG&nbsp;IN</span></p>
				<form class="loginbox">
					<div class="input1">
	                    <label for="account"><img src="images/login_account.png">账号</label>
						<input type="text" name="account" id="account" placeholder="手机号"/>
					</div>
					<div class="input1 input2">
	                    <label for="key"><img src="images/login_key.png">密码</label>
						<input type="password" name="key" id="key" />
					</div>
					<div class="checkboxFive" style="visibility: hidden">
  						<input type="checkbox" id="checkboxFiveInput"  />
	  					<label for="checkboxFiveInput"></label>
	  					<span class="state">记住登录状态</span>
	  					<span class="line">|</span>
	  					<a href="">忘记密码？</a>
  					</div>
  					<div class="button" id="loginTo">登&nbsp;&nbsp;&nbsp;录</div>
                </form>
			</div>
			<div class="notyet">
				<img src="images/login.png">
				<div class="noresign">还没有注册？</div>
				<div class="noright">还没有特权？</div>
				<div class="join" onclick="location.href='signup.php'">快来加入我们<img src="images/xiaosanjiao.png"></div>
			</div>
			<div class="clearfix"></div>
		</div>
    <?php include "footer.php";?>

    <script src="js/layer/layer.js"></script>
    <script>
        $('.headli .logindo a').css('color','#b0b8b9');
        $('#loginTo').click(function(){
            var ac = $('#account').val();
            var key = $("#key").val();
            checkKey = testKey(key);
            checkAc = testAc(ac);

            if(checkAc&&checkKey){

                $.post("server/php/logindo.php",{'un':ac,'pw':key},function(data){
                    oper_result = data[0].split("=");
//                    console.log(data[0]=="oper_result=0");
                    if(oper_result[1]=='1010'){
                        showError("账号或密码不正确");

                    }else if(oper_result[1]=='0'){

                        layer.alert("登录成功",{icon:1,title:false,closeBtn:false},function(){
                            ss = data[3].split("=");
                            console.log(ss);
                            $.post('server/php/getusername.php',{'ss':ss[1]},function(data){
                                console.log(data);
                                uname = data[1].split("=");


                                setCookie('user',decodeURI(uname[1]),7);
                                window.location.href="index.php";

                            },'json');
                        });
                    }


                },'json');



            }


        });
        function testAc(val){
            if(!val){
                layer.tips("请填写用户名","#account",{tips:[2,'#4dbbaa']});
                return false;

            }else{
                return true;
            }
        }
        function testKey(val){
            if(!val){
                layer.tips("请填写密码","#key",{tips:[2,'#4dbbaa']});
                return false;

            }else{
                return true;
            }
        }
        function showError(msg){
            layer.alert(msg,{icon:2,title:false,closeBtn:false});
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
