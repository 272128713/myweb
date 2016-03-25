
<?php
/**
* 取消订单
*/ 
	
	include(dirname(__FILE__) . "/../../1.0/common/inc.php");
	session_start();
	
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
	$result=array();
	if($_POST){

		$uid = $_SESSION['uid'];
		$order_num = $_POST['odnum'];


}
	//取消订单
	$sql="delete from user_buy_goods_info where order_nums='$order_num' and user_id = '$uid'";
	$result = $db->execute($sql);
	
	if($result){

		$result_arr=array(
			code=>"1",
			msg=>"取消成功"
		);

	}else{
		$result_arr=array(
				code=>"6645",
				msg=>"取消订单失败"
		);		
	}

	echo json_encode($result_arr);
	
	



	
	
	


?>	
	
	
	
	
	
	
	
	