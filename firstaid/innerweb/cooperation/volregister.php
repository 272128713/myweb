<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>志愿者注册</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/publish.css" />
		<link rel="stylesheet" type="text/css" href="css/volregister.css" />
		<link href="css/date/mobiscroll.css" rel="stylesheet" />
		<link href="css/date/mobiscroll_date.css" rel="stylesheet" />
        <?php include "php/org_userjoin_get.php";?>
	</head>
	
	<body>
		<div class="container">
			<div class="header">
				<p class="p1">欢迎加入我们的志愿者队伍。</p>
				<p class="p2">请认真填写注册表单，并保证你所填信息均为真实信息。</p>
			</div>
			<div class="baseinfo">
				<div class="row">
					<div class="heading">姓名</div>
					<input class="heading detail" id="name" maxlength="4" value="<?php echo $result->name;?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">性别</div>
					<input class="heading detail" id="sex" readonly="readonly" vl="<?php echo $result->sex;?>" value="<?php echo $sexVal[$result->sex];?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">出生日期</div>
					<input class="heading detail" id="timer" value="<?php echo $result->birthday;?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">民族</div>
					<input class="heading detail" id="nation" maxlength="5" value="<?php echo $result->nation;?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">住址</div>
					<input class="heading detail" id="address" maxlength="25" value="<?php echo $result->address;?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">联系电话</div>
					<input class="heading detail" id="phone" maxlength="11" value="<?php echo $result->phone;?>"  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">联系邮件</div>
					<input class="heading detail" id="email" maxlength="20" value="<?php echo $result->email;?>"/>
					<div class="clear"></div>
				</div>
				<div class="row">
					<div class="heading">教育程度</div>
					<input class="heading detail" id="education" readonly="readonly" vl="<?php echo $result->education;?>"  value="<?php echo $eduVal[$result->education];?>"/>
					<div class="clear"></div>
				</div>
				<div class="content">专业特长</div>
				<textarea placeholder="请填写..." id="speciality" maxlength="30" /><?php echo $result->speciality;?></textarea>
				<div class="row">
					<div class="heading">学校/单位</div>
					<input class="heading detail" id="company" maxlength="20" value="<?php echo $result->company;?>"/>
					<div class="clear"></div>
				</div>
				<div class="content">志愿经历</div>
				<textarea placeholder="请填写..." id="undergo" maxlength="100" /><?php echo $result->undergo;?></textarea>
			</div>
			<div class="submit">
				<div class="button" id="submit">提交注册</div>
		</div>
<!--     隐藏        -->
        <div class="hidden" id="hid-gender">
            <div class="sex" vl="1">男</div>
            <div class="sex famale" vl="2">女</div>
        </div>

        <div class="hidden" id="hid-edu">
            <div class="edu" vl="1">小学</div>
            <div class="edu " vl="2">初中</div>
            <div class="edu " vl="3">高中</div>
            <div class="edu " vl="4">本科</div>
            <div class="edu " vl="5">研究生</div>
            <div class="edu " vl="6">博士</div>
            <div class="edu famale" vl="7">其他</div>
        </div>
		<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date/mobiscroll_date.js" charset="gb2312"></script> 
		<script src="js/date/mobiscroll.js"></script>
        <script src="js/layer/layer.js"></script>
	    <script type="text/javascript">
            //提交表单
            sex="";
            education="";

            $('#submit').click(function(){
                    name = $('#name').val();
                    sex = $('#sex').attr("vl");
                    birthday = $('#timer').val();
                    nation = $('#nation').val();
                    address = $('#address').val();
                    phone = $('#phone').val();
                    email = $('#email').val();
                    education = $('#education').attr("vl");
                    speciality = $('#speciality').val();
                    company = $('#company').val();
                    undergo = $('#undergo').val();



                    //取时间
                    var stringTime = birthday+" 00:00:00";
                    var timestamp2 = Date.parse(new Date(stringTime));
                    birthday_timestamp = timestamp2 / 1000;
                    var timestampnow = Date.parse(new Date())/1000;



                    if(name==""||sex==""||birthday==""||nation==""||address==""||phone==""||email==""||education==""||speciality==""||company==""||undergo==""){
                        layer.msg("请填写完整!");
                    }else if(birthday_timestamp>timestampnow){
                        layer.msg("出生日期不得大于当前时间");
                    }else if(!testTel(phone)){
                        layer.msg("请填写正确的手机号");
                    }else if(!testMail(email)){
                        layer.msg("请填写正确的邮箱");
                    }else{
                        data = {
                          'ss':"<?php echo $_SESSION['ss'];?>",
                          'org_id':"<?php echo $_GET['oid']?>",
                          'name':name,
                          'sex':sex,
                          'birthday':birthday,
                          'nation':nation,
                          'address':address,
                          'phone':phone,
                          'email':email,
                          'education':education,
                          'speciality':speciality,
                          'company':company,
                          'undergo':undergo
                        };
                        $.post("php/org_userjoin_apply.php",data,function(data){
                            if(data.code==1){
                                layer.msg("提交成功",{time:1000},function(){
//                                   history.back(-1);
                                    window.location.replace("orgDetail.php?oid=<?php echo $_GET['oid'];?>&im=0");
                                });
                            }else{
                                layer.msg("系统错误");
                            }
                        },'json');
                    }
                }

            );
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

            //
	  		$(function () {
				var currYear = (new Date()).getFullYear();	
				var opt={};
				opt.date = {preset : 'date'};
				opt.datetime = {preset : 'datetime'};
				opt.time = {preset : 'time'};
				opt.default = {
					theme: 'android-ics light', //皮肤样式
					display: 'modal', //显示方式 
					mode: 'scroller', //日期选择模式
					dateFormat: 'yyyy-mm-dd',
					lang: 'zh',
					showNow: true,
					nowText: "今天",
					startYear: currYear - 50, //开始年份
					endYear: currYear + 10 //结束年份
				};
			
				$("#timer").mobiscroll($.extend(opt['date'], opt['default']));




                //性别
                $("#sex").click(function(){
                    layer.open({
                        type: 1,
                        title:false,
                        closeBtn: false,
                        shadeClose:true,
                        area:'80%',
                        content: $('#hid-gender')
                    });
                });
                $('#hid-gender .sex').click(function(){
                    sexVal = $(this).attr("vl");
                    sexHtml = $(this).html();
                    layer.closeAll();
                    $('#sex').val(sexHtml);
                    $('#sex').attr('vl',sexVal);
                });
                //教育程度
                $("#education").click(function(){
                    layer.open({
                        type: 1,
                        title:false,
                        closeBtn: false,
                        shadeClose:true,
                        area:'80%',
                        content: $('#hid-edu')
                    });
                });
                $('#hid-edu .edu').click(function(){
                    eduVal = $(this).attr("vl");
                    eduHtml = $(this).html();
                    layer.closeAll();
                    $('#education').val(eduHtml);
                    $('#education').attr('vl',eduVal);
                });
			});
	    </script>
            <style>
                .hidden{
                    width: 100%;
                    padding: 0;
                    background: #fff;
                    display: none;
                    border-radius:5px;
                    color:#333;
                }
                .hidden .sex,.hidden .edu{
                    height:35px;
                    line-height:35px;
                    text-align: center;
                    border-bottom:1px solid #eee;
                }
                .hidden .sex:active,.hidden .edu:active{
                    background: #eee;
                }
                .hidden .famale{
                    border-bottom:none;
                }
            </style>
	</body>

</html>