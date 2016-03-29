
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
	
	//商品信息
	$sql="select goods_content 
			from first_aid_goods_info 
			where id = '$gid'
			";
	$result=$db->getOne($sql);
	$cc = $result;
	$sql_count = "select id from first_aid_goods_belong_package where goods_id ='$gid' group by package_id";
	$result = $db->getCol($sql_count);
	$count = count($result);
?>	
	
	
	
	
	
	
	
	