
<?php
/**
* 订单列表（善有善报）
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
	
	if($_GET["ss"]){
		$_SESSION['ss']=$_GET["ss"];
		$sessionArr = $databaseManager->checkSession($_SESSION['ss']);
		$_SESSION['uid']=$sessionArr['user_id'];
		if(!$sessionArr){
			$logger->error(sprintf(" Session fail."));
			ErrCode::echoErr(ErrCode::API_ERR_INVALID_SESSION,1);
	
		}
	}else{
	
	}
	$sql = "select info.goods_name,buy.buy_time 
				from user_buy_goods_info as buy
				left join first_aid_goods_info as info 
				on info.id = buy.goods_id
				where user_id = ".$_SESSION['uid']."
				and buy.pay_state=1
			";
	$result = $db->getAll($sql);
	$count = count($result);
	

?>	
	
	
	
	
	
	
	
	