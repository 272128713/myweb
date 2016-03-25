<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>确认收货</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/confirmReceipt.css" />
        <link rel="stylesheet" type="text/css" href="css/area.css" />
    	
	</head>
	
	<body>
		<div class="mui-content">
			<div class="infobox pcarea" id="live_p">
				<div class="detail" id="live_pVal"  style="line-height: 37px;padding-bottom:10px;font-size: 14px;color:#858585;" vl="">请选择所在省市区</div>
			</div>
			<div class="infobox box2">
				<input type="text" id="address" maxlength="35" placeholder="请输入详细地址">
			</div>
			<div class="infobox box2">
				<input type="text" id="receive_name" maxlength="6" placeholder="请输入收货人姓名">
			</div>
			<div class="infobox box2">
				<input type="text" id="receive_phone" maxlength="11"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" placeholder="请输入收货人联系手机号">
			</div>
			<div class="pay">确定提交</div>
		</div>
		<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/layer/layer.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="js/area.js"></script>
    	<script type="text/javascript">
            //校验手机号
            function testTel(val){

                var reg = /^1[034578][0-9]{9}$/;

                if(!reg.test(val)){
                    return false;
                }else{
                    return true;
                }

            }
			$(function(){
				 $('.pcarea').click(function(){
	                var pcid = $(this).children('.detail').attr('id');
	                setCookie('pcVal',pcid,7);
	                getProvinceBuy();
            	});
			});
            live_pVal='';
            $('.pay').click(function(){
                live_pVal = $('#live_pVal').attr('vl');
                address = $('#address').val();
                receive_name = $('#receive_name').val();
                receive_phone = $('#receive_phone').val();
                console.log(live_pVal);

                if(live_pVal==""||address==""||receive_name==""||receive_phone==""){
                    layer.msg("请填写完整");
                }else if(!testTel(receive_phone)){
                    layer.msg("请填写正确手机号");

                }else{
                    datas={
                        odnum:<?php echo $_GET['on'];?>,
                        area_code:live_pVal,
                        address:address,
                        receive_name:receive_name,
                        receive_phone:receive_phone
                    };
                    $.post(
                        "php/set_goods_address.php",
                        datas,function(e){
                            console.log(e);
                            if(e.code==1){
                                layer.msg('确定成功，请等待收货',{time:1000},function(){
                                   window.location.replace('receive.php');

                                });


                            }

                        },'json'
                    );
                }




            });
		</script>
	</body>

</html>