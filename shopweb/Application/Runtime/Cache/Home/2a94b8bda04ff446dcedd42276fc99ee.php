<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>西安三聆医信息有限公司</title>
<script language="JavaScript">
var _PUBLIC="/sanlingyi/Public";
</script>
<link rel="stylesheet" href="/sanlingyi/Public/css/default.css" type="text/css" />
<link rel="stylesheet" href="/sanlingyi/Public/css/menu.css" type="text/css" />
<link rel="stylesheet" href="/sanlingyi/Public/css/windown.css" type="text/css" />
<link rel="stylesheet" href="/sanlingyi/Public/css/index.css" type="text/css" />
<script language="JavaScript" src="/sanlingyi/Public/js/jquery/jquery-1.9.1.min.js"></script>
<script language="JavaScript" src="/sanlingyi/Public/js/windown.js"></script>
<script type="text/javascript">

<?php if($isRemind == 1): ?>var remind='<?php echo ($remind); ?>';
$(function(){
	function showUnreadNews(){
		var url="<?php echo U('Loop/remind');?>";
    	url=url.substring(0,url.indexOf('.html'));
        $.ajax({
            type: "GET",
            url: url+'/data/'+remind,
            dataType: "json",
            success: function(data) {
                //alert(data);
                $.each(data, function(id, msg) {
                    $("#remind").css('display','block');
                    $("#remind").attr('tips',msg);
                });
            }
        });
	}
	setInterval(showUnreadNews,60000);
	$('#remind').click(function(){
		alert($('#remind').attr('tips'));
	});
});<?php endif; ?>


function hiddenWindow(){
	$('#windown-box').hide();
	$('#windownbg').hide();
	
}
</script>
</head>
<body>
<div id='windownbg'></div>
<div id='windown-box'>
	<div id='windown-title'>
		<img id='windown-icon' />
		<span id='windown_title'></span>
		<img id='windown-close' src='/sanlingyi/Public/images/windown/close.gif' onmouseover="this.style.cursor='pointer'" onclick="javascript:winClose()" />
	</div>
	<div id='windown-content-border'>
		<div id='windown-content'></div>
	</div> 
</div>
<div id="mainIndex">
	<div id="head">
		<img id="logo" src="/sanlingyi/Public/images/head/logo.gif" alt="三聆医" />
		<img id="title" src="/sanlingyi/Public/images/head/title.gif" alt="西安三聆医信息有限公司" />
		<img id="power" src="/sanlingyi/Public/images/head/power.gif" alt="Power by CenturySite2.0" />
	</div>
	<div id="menuMain">
		<img class="split" src="/sanlingyi/Public/images/head/split_line.gif" />
		<div id="menuList">
			<div class="imrcmain0 imgl" style="z-index:900; width: 900px; top:1px; position: relative;">
				<div class="imcm imde" id="imouter0">
			      	<ul id="imenus0">
			      		<?php if(is_array($modList)): $i = 0; $__LIST__ = $modList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><LI class="imatm" style="WIDTH:110px">
			      	  		<A href='#' onfocus='this.blur()'><SPAN class='imea imeam'><SPAN></SPAN></SPAN><img src='/sanlingyi/Public/images/button/<?php echo ($vo["en"]); ?>.gif' width='18'><span style='mangin-left:2px'> <?php echo ($vo["module_name"]); ?></span></A>
						  	<DIV class='imsc'>
						    	<DIV class='imsubc' style='LEFT: 0px; WIDTH: 145px; TOP: 3px'>
						      		<DIV class='imunder'></DIV>
						      		<UL>
						      			<?php if(is_array($vo["item"])): $i = 0; $__LIST__ = $vo["item"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><LI class="dvs"><A href="/sanlingyi/index.php?s=/Home/Index/moduleDirect/id/<?php echo ($item['module_id']); ?>" onfocus='this.blur()' target='mainFrame'><img src='/sanlingyi/Public/images/button/<?php echo ($item["en"]); ?>.gif' width='18' align='middle'><span style='font-size:12px;'>&nbsp;&nbsp;<?php echo ($item["module_name"]); ?></span></A></LI><?php endforeach; endif; else: echo "" ;endif; ?>
						      		</UL>
						    	</DIV>
						  	</DIV>
						</LI><?php endforeach; endif; else: echo "" ;endif; ?>
			      	</ul>
			      	<div class="imclear"></div>
			   	</div>
			</div>
		</div>
		<script language="JavaScript" src="/sanlingyi/Public/js/ocscript.js"></script>
		<div id="menuOpt">
			<script language="JavaScript">
			$(function(){
				$('.exit').mouseover(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/exit_over.gif');
					$(this).css({'cursor':'pointer'});
				});
				$('.exit').mouseout(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/exit.gif');
				});
				$('.exit').click(function(){
					window.location="<?php echo U('Login/loginOut');?>";
				});
				
				$('.passwd').mouseover(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/passwd_over.gif');
					$(this).css({'cursor':'pointer'});
				});
				$('.passwd').mouseout(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/passwd.gif');
				});
				$('.passwd').click(function(){
					windown("修改密码","/sanlingyi/index.php?s=/Home/Index/changePasswd","300","200","changePasswd");
				});
							
				$('.home').mouseover(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/home_over.gif');
					$(this).css({'cursor':'pointer'});
				});
				$('.home').mouseout(function(){
					$(this).attr('src','/sanlingyi/Public/images/head/home.gif');
				});
				$('.home').click(function(){
					alert('');
				});
			});
			</script>
			<img class='exit' id='btImg' src="/sanlingyi/Public/images/head/exit.gif" title='退出系统' />
			<img class='passwd' id='btImg' src="/sanlingyi/Public/images/head/passwd.gif" title='修改密码' />
			<img class='home' id='btImg' src="/sanlingyi/Public/images/head/home.gif" title='返回首页' />
			<img id='line1' src="/sanlingyi/Public/images/head/split_line.gif" />
			<img id='remind' src="/sanlingyi/Public/images/head/remind.gif" onmouseover="this.style.cursor='pointer'" style="display:none" tips=""/>
		</div>
		<div style="clear:both"></div>
	</div>
	<iframe id="mainFrame" name="mainFrame" src="/sanlingyi/index.php?s=/Home/Index/blank"></iframe>
</div>
<script language="JavaScript">
var th = $(window).height()-60;
var cw = $(window).width()-2;

if(th<600){
	th=600;
}
if(cw<1000){
	cw=1003;
}
$("#mainFrame").height(th);
$("#mainIndex").width(cw);
$(function(){
	$(window).resize(function() {
		var th = $(window).height()-60;
		var cw = $(window).width()-2;
		if(th<600){
			th=600;
		}
		if(cw<1000){
			cw=1024;
		}
		$("#mainFrame").height(th);
		$("#mainIndex").width(cw);
	});
});
</script>
</body>
</html>