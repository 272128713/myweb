
<?php
/**
* 分类列表
*/ 
	
	include(dirname(__FILE__) . "/../../1.0/common/inc.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$databaseManager = new DatabaseManager();
	$db = $databaseManager->getConn();
	session_start();
	//数据库链接失败
	if(!$db){
		echo "<div style='margin:1em 0 0 2em'>Server Error</div>";
		echo "<div style='margin:1em 0 0 2em'>服务器开小差~！跳转中。。。</div>";
		?>
			<script>
			setTimeout("goBack()",2000);
			function goBack(){
				history.go(-1);
			}
			</script>
			<?php
		die();
	}
	
	if($_GET["gid"]){
		$gid = $_GET["gid"];
	}else{
		echo "<div style='margin:1em 0 0 2em'>Server Error</div>";
		echo "<div style='margin:1em 0 0 2em'>服务器开小差~！跳转中。。。</div>";
		?>
			<script>
			setTimeout("goBack()",2000);
			function goBack(){
				history.go(-1);
			}
			</script>
			<?php
		die();
	}
	
	//预案列表
	$sql_ls = "select info.id,info.goods_name,info.goods_price,info.goods_summary,img.img_url 
					from first_aid_goods_belong_package as pa 
					left join first_aid_goods_info as info on info.id=pa.package_id
					left join first_aid_goods_img_info as img on info.id=img.goods_id
					where pa.goods_id ='$gid' 
					and img.type=2
					group by pa.package_id";
	$result=$db->getAll($sql_ls);
	$pack = $result;
?>	
	
	
	
	
	
	
	
	