
<?php
/**
* 购物车,订单列表
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
	if($_GET['gid']){
		$gid=$_GET['gid'];
	}else{
		die();
	}
	//商品信息
	$sql_od="select  info.id,info.goods_name,info.goods_price,img.img_url
			from first_aid_goods_info as info
			left join first_aid_goods_img_info as img 
			on info.id= img.goods_id
			where info.id = '$gid'
			and img.type= 2
			";
	$result=$db->getRow($sql_od);
	$ord= $result;
	
	//收货地信息
	$sql = "select * from user_receive_address_info where user_id = ".$_SESSION['uid'];
	$result_address = $db->getRow($sql);
	if($result_address){
		$address = $result_address;
	}

?>	
	
	
	
	
	
	
	
	