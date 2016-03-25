
<?php
/**
* 分类列表
*/ 
	
	include(dirname(__FILE__) . "/../../1.0/common/inc.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$databaseManager = new DatabaseManager();
	$db = $databaseManager->getConn();
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
	
	if($_GET["classid"]){
		$clsid = $_GET["classid"];
		//分类列表
		$sql_list = "select info.id,info.goods_name,info.goods_price,info.goods_summary,img.img_url
		from first_aid_goods_belong_classify as be
		LEFT JOIN first_aid_goods_info as info
		ON info.id = be.goods_id
		LEFT JOIN first_aid_goods_img_info as img
		ON info.id = img.goods_id
		where be.classify_id = '$clsid'
		and info.type = 2
		and info.state = 1
		and img.type = 2
		order by info.createDate desc
		";
		$result = $db->getAll($sql_list);
		$list = $result;
		$sql = "select describes from first_aid_goods_classify_info where id = '$clsid'";
// 		echo $sql;
		$rc = $db->getOne($sql);
	}elseif($_GET["con"]){
		$con = $_GET["con"];
		//搜索列表
		$sql_list = "select info.id,info.goods_name,info.goods_price,info.goods_summary,img.img_url
		from first_aid_goods_info as info
		LEFT JOIN first_aid_goods_img_info as img
		ON info.id = img.goods_id
		where info.type = 2
		and info.state = 1
		and img.type = 2
		and info.goods_name like '%$con%'
		order by info.createDate desc
		";
		$result = $db->getAll($sql_list);
		$list = $result;
		$rc="预案列表";
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
	
	
	
	

?>	
	
	
	
	
	
	
	
	