<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>医信头条</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<link rel="stylesheet" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/mui.min.css">
        <link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/app.css"/>
		<link rel="stylesheet" type="text/css" href="/shop_skyhospital/trunk/shopweb/Public/Article/css/article.css"/>
        <script src="/shop_skyhospital/trunk/shopweb/Public/Article/js/jquery-1.9.1.min.js"></script>
	</head>

	<body>
		<header class="mui-bar mui-bar-nav">
			<div  style="color: #fff; "onclick="getOs1()" class=" mui-icon mui-icon-left-nav mui-pull-left" style="width: 3em; "></div>
			<h1 class="mui-title">
                医信头条
            </h1>
			<a  href="<?php echo U('Search/index');?>" class="mui-icon mui-pull-right "><img src="/shop_skyhospital/trunk/shopweb/Public/Article/images/search.png"/></a>
		</header>
        <div id="nav_box">
            <?php
 echo nav_oprate(1); ?>
            <!--<a class="mui-control-item" href="" >你好奥</a>-->
        </div>
		<div class="mui-content">
			<div id="slider" class="mui-slider mui-fullscreen">
				<div id="sliderSegmentedControl" class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
					<div class="mui-scroll" id="nav_bar" >
						<?php echo ($nav); ?>
					</div>
					<span class="downarr" id="down_nav">
						<img  src="/shop_skyhospital/trunk/shopweb/Public/Article/images/down.png" />
					</span>
				</div>
				<div class="mui-slider-group">
					<div id="item1mobile" class="mui-slider-item mui-control-content mui-active">
						<div id="scroll1" class="mui-scroll-wrapper">
							<div class="mui-scroll">
								<ul class="mui-table-view">
									<ul class="mui-table-view" id="article_list">

	
	<?php echo ($article); ?>
	
	
</ul>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		
		<script src="/shop_skyhospital/trunk/shopweb/Public/Article/js/mui.min.js"></script>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Article/js/mui.pullToRefresh.js"></script>
		<script src="/shop_skyhospital/trunk/shopweb/Public/Article/js/mui.pullToRefresh.material.js"></script>
		<script>

			(function($) {
				$('.mui-scroll-wrapper').scroll({
					bounce: false,
					indicators: false //是否显示滚动条
				});
				$.ready(function() {
					//循环初始化所有下拉刷新，上拉加载。
					$.each(document.querySelectorAll('.mui-slider-group .mui-scroll'), function(index, pullRefreshEl) {
						$(pullRefreshEl).pullToRefresh({
							down: {
								callback: function() {
									console.log('pulldown');
									var self = this;
									var ul = self.element.querySelector('.mui-table-view');

										append_list();
										//ul.insertBefore(createFragment(ul, index, 10, true), ul.firstChild);
										self.endPullDownToRefresh();
									
								}
							},
							up: {
								callback: function() {
									console.log('pullup');
									var self = this;

										var ul = self.element.querySelector('.mui-table-view');
                                        append_more();
										self.endPullUpToRefresh();

								}
							}
						});
					});
					var createFragment = function(ul, index, count, reverse) {
						var length = ul.querySelectorAll('li').length;
						var fragment = document.createDocumentFragment();
						var li;
						for (var i = 0; i < count; i++) {
							li = document.createElement('li');
							li.className = 'mui-table-view-cell';
							li.innerHTML = '第' + (index + 1) + '个选项卡子项-' + (length + (reverse ? (count - i) : (i + 1)));
							fragment.appendChild(li);
						}
						return fragment;
					};
				});
			})(mui);
		</script>

			<script>
			mui('body').on('tap','a',function(){
				var href=this.href
				window.location.href=href;
			})
			</script>
            <script>

                function append_list(){
                    var article=$('#article_list');
                    var cid="<?php if(isset($_GET['cid'])) {echo $_GET['cid'];}else{echo 0;}?>";
                    //查询有没有更新
                    var url="<?php echo U('update','','');?>";
                    $.post(
                        url,{'cid':cid},function(date){
                                article.html(date);
                                setheight();
                            }
                    ,'text' );

                }
                sanlingyi_page=1;
                function append_more(){
                    //查询更多
                    var article=$('#article_list');
                    var url="<?php echo U('more','','');?>";
                    var cid="<?php if(isset($_GET['cid'])) {echo $_GET['cid'];}else{echo 0;}?>";
                    $.post(
                            url,{'cid':cid,'page':sanlingyi_page},function(date){

                               if(date!=0) {
                                   sanlingyi_page=sanlingyi_page+1;
                                   article.append(date);
                                   setheight();
                                   //alert(date);
                               }else{
                                   $('.mui-pull-loading').html('暂无更多');
                                   window.setTimeout(function () {
                                       $('.mui-pull-loading').html('上拉显示更多');
                                   },500);
                               }
                            }
                            ,'text' );
                };
            </script>
            <script>
                is_show_sanlingyi_nav=0;
                $('#down_nav').click(function(){
                    if(is_show_sanlingyi_nav==0){
                        $('#nav_box').show();
                        $(this).find('img').addClass('down_img');
                        is_show_sanlingyi_nav=1
                    }else{
                        $('#nav_box').css('display','none');
                        $('#down_nav img').get(0).removeAttribute('class')
                        is_show_sanlingyi_nav=0;
                    }

                });
                $('#nav_box').click(function(){
                   $('#nav_box').css('display','none');
                    $('#down_nav img').get(0).removeAttribute('class')
                    is_show_sanlingyi_nav=0;
                });
            </script>
            <script>
                function getOs1() {
//                   alert(1);
                    if(navigator.userAgent.match('iPhone')){
                        window.location.href="skyhospital://www.skyhospital.net/go_to_yixin";
                    }
                    if(navigator.userAgent.match('Android')){
                        go_to_yixin();
                    }
                }

                function go_to_yixin() {
                   //alert(1);
                    Android.go_to_yixin();
                }

            </script>
            <script type="text/javascript">

                function setheight(){
                    var pic3 = $('.pic3 img').width();
                    $('.pic3 img').height(pic3*2/3);
                    var width= $('.mui-table-view .mui-media-object').width();
                    $('.mui-table-view .mui-media-object').height(width*2/3);
                }
                setheight();


           </script>

    </body>

</html>