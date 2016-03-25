
<?php
/**
* 提交订单
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
		$result = $_POST;
		$user_id = $_SESSION['uid'];
		$goods_id = $result['goods_id'];
		$goods_name = $result['goods_name'];
		$price = $result['price'];
		$numbers = $result['numbers'];
		$send_address_id = trim($result['send_address_id']);
		$orderId = createNewID();//dingdanhao
// 		echo $orderId;
		$sql = "select goods_stock from first_aid_goods_info where id = '$goods_id'";
		$rsda = $db->getOne($sql);
		if($rsda <=0){
			//库存不足
					$rs_data["code"] = "0";//ok
					$rs_data["result"] = "库存".$rsda;
			
		}else{
				$sql="insert into user_buy_goods_info 
					(user_id,goods_id,price,buy_time,numbers,order_nums,pay_state,logistics_state,order_state,send_address_id) 
					values 
					('$user_id','$goods_id','$price',now(),$numbers,'$orderId','0','0','0','$send_address_id')";
		
				$rs_order = $db->execute($sql);
				if($rs_order){
					$rs_data["code"] = "1";//ok
					$rs_data["result"] = $orderId;
				}else{
					$rs_data["code"] = "-1";//error
					$rs_data["result"] = "$sql";
				}
		}
		
		echo json_encode($rs_data);
	}else{
		die();
	}
	//商品信息
	
	
	
	/**
	 * 生成随机数种子
	 *
	 * @return number
	 */
// 	function makeSeed() {
// 		list ( $u, $c ) = explode ( ' ', microtime () );
// 		return ( float ) $c + (( float ) $u * 100000);
// 	}
	/**
	 * 创建一个新的ID
	 */
	function createNewID() {
// 		srand ( makeSeed () );
// 		$id = rand ( 100, 199 );
// 		srand ( makeSeed () );
// 		$id .= rand ( 100, 999 );
// 		srand ( makeSeed () );
// 		$id .= rand ( 1000, 9999 );
// 		return $id;
		$id = rand(100,199).strtotime("now")*rand(1,5);
		return $id;
	}

?>	
	
	
	
	
	
	
	
	