<?php

	error_reporting(0);
	$ss=$_REQUEST['ss'];
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>公益慈善机构</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="stylesheet" href="./css/mui.min.css">
		<link rel="stylesheet" type="text/css" href="css/app.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="../common/common.js"></script>

	</head>
	<style>
		.mui-content{
			width: 96%;
		}
		.wrap{
			padding:3px 0 10px 0;
			width:100%;
		}
		.agency{
			margin-top:5%;
		}
		.loading{
			position:absolute;
			top: 50%;
			background-size:40px 40px;
			width: 40px;
			height:40px;
			margin-top:-20px;
			margin-left:-20px;
			left:50%;
			z-index: 999;
			background-image: url('images/loading.gif');
			display: none;
			
		}
	</style>
	<body>
		<div class="mui-content">
			<div class="wrap">
				<div class="col1"></div>
				<div class="col2"></div>
				<div class="clearfix"></div>
			</div>
			<div class="loading"></div>
		</div>
		
		
		
		
		
		
		<script type="text/javascript" src="js/zepto.min.js"></script>
		<script type="text/javascript">

		var urla = '../../1.0/get_cooperation_message.php';
		var ss = '<?php echo $ss; ?>';
		var html1 = "";
		var html2 = "";
			
			$.ajax({
				type:'POST',
				data:{ss:ss},
				url: urla,
				dataType:'json',
				beforeSend:function(){
					$('.loading').show();
				},
				success:function(data){
					$('.loading').hide();
					if(data.code==1){
						res = data.result;
						length = res.length;
						for(i=0;i<length;i++){
							if(i%2==0){
								html1 += "<div class='agency agency1'><img class='img1' src='"+res[i].url+"' /><div class='line'></div><div class='con con2'><p class='title'>"+res[i].name+"</p><p class='intro intro1'>"+res[i].summary+"</p></div></div>";
							}else{
								html2 += "<div class='agency agency1'><img class='img1' src='"+res[i].url+"' /><div class='line'></div><div class='con con2'><p class='title'>"+res[i].name+"</p><p class='intro intro1'>"+res[i].summary+"</p></div></div>";
							}
						}
						
						$('.col1').html(html1);
						$('.col2').html(html2);
						
						
					}else{
						$('.loading').hide();
					}
				},
				error:function(xhr, type){
				    
				}
			});
		</script>
	</body>

</html>