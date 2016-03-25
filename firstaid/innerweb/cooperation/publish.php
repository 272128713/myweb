<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>发布公益活动</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/publish.css" />
<!--		<link rel="stylesheet" href="css/date/normalize3.0.2.min.css" />-->
		<link href="css/date/mobiscroll.css" rel="stylesheet" />
		<link href="css/date/mobiscroll_date.css" rel="stylesheet" />
        <?php include "php/org_activedetail_get.php";?>
	</head>
	
	<body>
		<div class="container step1">
			<p class="title">第一步：提供活动内容</p>
			<div class="baseinfo">
				<div class="row">
					<div class="heading">活动名称</div>
					<input class="heading detail" id="name" style="margin: 0;padding: 0;border:0;line-height: 43px;"  maxlength="20" value="<?php echo $result->name;?>"/>
					<div class="clear"></div>
				</div>
				<!--div class="row">
					<div class="heading">发布机构</div>
					<select class="heading detail">
						<option value="机构1">机构1</option>
						<option value="机构2">机构2</option>
						<option value="机构3" selected="selected">机构3</option>
						<option value="机构4">机构4</option>
					</select>
					<div class="clear"></div>
				</div-->
				<div class="row active_time">
					<div class="heading">开始时间</div>
					<input class="heading detail " id="activity_time_begin" style="margin: 0;padding: 0;border:0;line-height: 43px;"  value="<?php if($result->activity_time_begin){ echo date("Y-m-d",strtotime($result->activity_time_begin));}?>" readonly="readonly"/>
					<div class="clear"></div>
				</div>
                <div class="row active_time">
                    <div class="heading">结束时间</div>
                    <input class="heading detail" id="activity_time_finish" style="margin: 0;padding: 0;border:0;line-height: 43px;"  value="<?php if($result->activity_time_finish){ echo date("Y-m-d",strtotime($result->activity_time_finish));}?>" readonly="readonly"/>
                    <div class="clear"></div>
                </div>
				<div class="row">
					<div class="heading">活动名额</div>
					<input class="heading detail" id="people_nums" style="margin: 0;padding: 0;border:0;line-height: 43px;"  maxlength="7" value="<?php echo $result->people_nums;?>"  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
					<div class="clear"></div>
				</div>
				<div class="content">活动内容</div>
				<textarea placeholder="请填写活动内容" id="content" maxlength="200" style="border: 1px solid rgba(221, 64, 59,0.5);background:#fff"/><?php echo $result->content;?></textarea>
				<div class="poster">上传活动海报</div>
				<div id="uploadbox" style="height: 131px;background-image:url('images/js_logo.png');background-size:contain;background-position:center center;background-repeat:no-repeat;position: relative;margin: 10px 15px 15px;border: 1px solid #fff;border-radius: 3px;transition: all 0.3s;-webkit-transition: all 0.3s;overflow: hidden;margin-bottom: 5px;">
                    <input id="filetest"  type="file" accept="image/*" id="img_url" capture="camera"  style="width: 100%;height: 85%;opacity: 0;position: absolute;left: 0px;top: 0px;z-index: 4;"/>
                </div>
				
			</div>
			<div class="next" id="submitstep1">下一步</div>
		</div>

        <div class="container step2" >
            <p class="title">第二步：活动赞助申请</p>
            <div class="baseinfo">
                <div class="row">
                    <div class="heading">经费赞助</div>
                    <!--input class="heading detail" id="expression_form" style="margin: 0;padding: 0;border:0;line-height: 43px;"  maxlength="20" value="<?php echo $result->expression_form;?>" /-->
                    <input type="radio" value="1" style="margin: 0;padding: 0;border:0;margin-right:5px;line-height: 43px;" id="expression_form1" name="expression_form"/><label for="expression_form1" style="font-size: 14px;color: #333333;margin-right:10px;">需要</label>
                    <input type="radio" value="0" style="margin: 0;padding: 0;border:0;margin-right:5px;line-height: 43px;" id="expression_form2" name="expression_form"/><label for="expression_form2" style="font-size: 14px;color: #333333;">不需要</label>
                    <div class="clear"></div>
                </div>
                <div class="row" id="cash">
                    <div class="heading">经费金额</div>
                    <input class="heading detail" id="money" style="margin: 0;padding: 0;border:0;line-height: 43px;"  value="<?php echo $result->money;?>" maxlength="9" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
                    <div class="clear"></div>
                </div>
                <div id="cashuse">
                <div class="content">经费用途</div>
                <textarea placeholder="请填写活动经费预算"  id="money_purpose" maxlength="100" style="border: 1px solid rgba(221, 64, 59,0.5);background:#fff"/><?php echo $result->money_purpose;?></textarea>
                </div>
                <div class="row">
                    <div class="heading">联系人</div>
                    <input class="heading detail" id="linkman" style="margin: 0;padding: 0;border:0;line-height: 43px;"  maxlength="10" value="<?php echo $result->linkman;?>"  />
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <div class="heading">联系电话</div>
                    <input class="heading detail" id="phone" style="margin: 0;padding: 0;border:0;line-height: 43px;"  maxlength="11" value="<?php echo $result->phone;?>"  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="next" id="submitstep2">提交审核</div>
        </div>
		
		<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>

		<script src="js/layer/layer.js" type="text/javascript" charset="utf-8"></script>

		<script src="js/date/mobiscroll_date.js" charset="gb2312"></script>

		<script src="js/date/mobiscroll.js"></script> 
		<script src="js/lib/exif.js" type="text/javascript"></script>
        <script src="js/lib/mobileFix.mini.js" type="text/javascript"></script>
        <script src="js/lrz.js" type="text/javascript"></script>

	    <script type="text/javascript">
            //点击经费需要、不需要 隐藏 cash、cashuse
            $("input[type='radio']").click(function(){
                getVal = $(this).val();
                if(getVal == 0){
                    $('#cash').hide();
                    $("#cashuse").hide();
                    $('#money').val("0");
                    $('#money_purpose').val(" ");
                }else if(getVal ==1){

                    $('#cash').show();
                    $("#cashuse").show();
                }
            });




            img_url = "";
            $('#submitstep1').click(function(){

                name= $('#name').val();
                activity_time_begin= $('#activity_time_begin').val();
                activity_time_finish= $('#activity_time_finish').val();
                people_nums= $('#people_nums').val();
                content= $('#content').val();


                //取时间
                var stringTime = activity_time_begin+" 23:59:59";
                var timestamp2 = Date.parse(new Date(stringTime));
                begin_timestamp = timestamp2 / 1000;
                var timestampnow = Date.parse(new Date())/1000;


                if(name==""||activity_time_begin==""||activity_time_finish==""||people_nums==""||content==""||img_url==""){
                    layer.msg("请填写完整");
                }else if(people_nums<=0){
                    layer.msg("请输入正确参与人数");
                }else if(begin_timestamp<timestampnow){
                    layer.msg("开始时间不得小于当前时间");
                }else if(activity_time_finish<activity_time_begin){
                    layer.msg("结束时间不应小于开始时间");
                }else{
//                    window.location.href="pubNext.php?org_id=<?php //echo $_GET['oid'];?>//&name="+name+"&activity_time_begin="+activity_time_begin+"&activity_time_finish="+activity_time_finish+"&people_nums="+people_nums+"&content="+content+"&img_url="+img_url;
                    $('.step1').hide();
                    $('.step2').show();
                }
            });
            $('#submitstep2').click(function(){
                expression_form = $("input[name='expression_form']").val();
                money = $('#money').val();
                money_purpose = $('#money_purpose').val();
                linkman = $('#linkman').val();
                phone = $('#phone').val();
                if(expression_form==""||money==""||money_purpose==""||linkman==""||phone==""){
                    layer.msg("请填写完整");
                }else if(!testTel(phone)){
                    layer.msg("请填写正确手机号");
                }else{
                    datas={
                        <?php if($_GET['aid']){?>
                        'active_id':<?php echo $_GET['aid'];?>,
                        <?php }?>
                        'org_id':<?php echo $_GET['oid'];?>,
                        'name':name,
                        'activity_time_begin':activity_time_begin,
                        'activity_time_finish':activity_time_finish,
                        'people_nums':people_nums,
                        'content':content,
                        'img_url':img_url,
                        'expression_form':expression_form,
                        'money':money,
                        'money_purpose':money_purpose,
                        'linkman':linkman,
                        'phone':phone
                    };
                    $.post("php/org_active_set.php",datas,function(data){
                       if(data.code==1){
                           window.location.reload('pubrecord.php?oid=<?php echo $_GET['oid'];?>');
                       }else{
                           layer.msg("系统错误");
                       }


                    },'json');
                }

            });
            function testTel(val){

                var reg = /^1[034578][0-9]{9}$/;

                if(!reg.test(val)){
//                    index= layer.tips("请填写正确手机号","#tel",{tips:[2,'#4dbbaa']});
                    return false;
                }else{
                    return true;
                }

            }
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
				$('.active_time').children("input").mobiscroll($.extend(opt['date'], opt['default']));


    		
    		//压缩图片2
                        $('#filetest').change(function() {
                            layer.load(2, {time: 7*1000});
                            lrz(this.files[0], {width: 400,quality:1}, function (result) {
                                // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                                console.log(result);
                                 submitData={
                                    base64_string:result.base64
                                };

                                //提交
                                $.ajax({
                                    type: "POST",
                                    url: "upload.php",
                                    data: submitData,
                                    dataType: 'json',
                                    success: function(data){
                                        console.log(data);
                                        if(data.code==1){
                                            layer.closeAll();
                                            img_url = 'http://'+data.data;
                                            console.log(img_url);
                                            $("#uploadbox").css({
                                                "background-image":"url('http://"+data.data+"')",
                                                "background-size":"contain"
                                            });
                                        }else{
                                            alert(data.msg);
                                        }
                                    }
                                });
                            });
                        });
	    </script>
	    <style>
            .layui-layer{
                left:0;
            }
            .ui-loader{
                display: none;
            }
        </style>
	</body>

</html>