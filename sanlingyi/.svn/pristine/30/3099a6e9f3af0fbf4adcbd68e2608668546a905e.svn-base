///-------------------------------------------------------------------------
//jQuery弹出窗口 By Await [2009-11-22]
//--------------------------------------------------------------------------
/*参数：[可选参数在调用时可写可不写,其他为必写]
----------------------------------------------------------------------------
    title:	窗口标题
  content:  内容(可选内容为){ text | id | img | url | iframe }
    width:	内容宽度
   height:	内容高度
	 drag:  是否可以拖动(ture为是,false为否)
     time:	自动关闭等待的时间，为空是则不自动关闭
   showbg:	[可选参数]设置是否显示遮罩层(0为不显示,1为显示)
	 icon:  窗口的图标
 ------------------------------------------------------------------------*/
 //示例:
 //------------------------------------------------------------------------
 //simpleWindown("例子","text:例子","500","400","true","3000","0","exa")
 //------------------------------------------------------------------------
var showWindown = true;
var templateSrc = "http://www.7daysinn.cn"; //设置loading.gif路径
function windown(title,content,width,height,icon,backcall) 
{
	//$("#windown-box",top.document).remove(); //请除内容
	var width = width>= 950?this.width=950:this.width=width;	    //设置最大窗口宽度
	var height = height>= 527?this.height=527:this.height=height;  //设置最大窗口高度	
	$('#windownbg',top.document).css({height:$(top.document).height()+"px",width:$(top.document).width()});
	$("#windownbg",top.document).show();
	$("#windownbg",top.document).animate({opacity:"0.5"},"normal");//设置透明度
	$("#windown-box",top.document).show();	
	$("#windown-title",top.document).css({width:(parseInt(width)+10)+"px"});
	window.top.document.getElementById('windown-icon').src=_PUBLIC+"/images/button/"+icon+".gif";
	$("#windown-content",top.document).css({width:(parseInt(width)+10)+"px",height:height+"px"});
	$("#windown-content",top.document).html("<iframe src=\""+content+"\" width=\"100%\" height=\""+parseInt(height)+"px"+"\" scrolling=\"no\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>");	
	$("#windown_title",top.document).html(title);
	var	cw = $(top.document).width();
	var ch = $(top.document).height();
	//var est = window.top.document.documentElement.scrollTop; 
	$("#windown-box",top.document).css({left:"50%",top:"50%",marginTop:-((parseInt(height)+53)/2)+"px",marginLeft:-((parseInt(width)+32)/2)+"px"});
	var Drag_ID = window.top.document.getElementById('windown-box');
	var DragHead = window.top.document.getElementById('windown-title');		
	var moveX = 0,moveY = 0,moveTop=0,moveLeft = 0,moveable = false;

	var	sw = Drag_ID.scrollWidth,sh = Drag_ID.scrollHeight;
	DragHead.onmouseover = function(e) {
		DragHead.style.cursor = "move";
	};
	DragHead.onmousedown = function(e) {
		moveable = true;
		e = window.top.event?window.top.event:e;
		var ol = Drag_ID.offsetLeft, ot = Drag_ID.offsetTop-moveTop;
		moveX = e.clientX-ol;
		moveY = e.clientY-ot;
		window.top.document.onmousemove = function(e) {
			if (moveable) 
			{
				e = window.top.event?window.top.event:e;
				var x = e.clientX - moveX;
				var y = e.clientY - moveY;
				if ( x > 0 &&( x + sw < cw) && y > 60 && (y + sh < ch) ) 
				{
					Drag_ID.style.left = x + "px";
					Drag_ID.style.top = parseInt(y+moveTop) + "px";
					Drag_ID.style.margin = "auto";
				}
			}
		}
		window.top.document.onmouseup = function () {moveable = false;};
		Drag_ID.onselectstart = function(e){return false;}
	}
}

function winClose(){
	$("#windownbg").hide();
	$("#windown-box").fadeOut("slow",function(){
		$(this).hide();
	});
}