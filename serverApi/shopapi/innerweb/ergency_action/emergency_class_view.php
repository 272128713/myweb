
<?php
/**
* 应急预案，急救包
*/ 
	
	include(dirname(__FILE__) . "/../../1.0/common/inc.php");
	$logger = Logger::getLogger(basename(__FILE__));
	$databaseManager = new DatabaseManager();
	$db = $databaseManager->getConn();
	
	
	
	//分类推荐商品（目前展示排序前三位）,急救包
	if($_POST['id']){
		$cid = $_POST['id'];
		$sql = "select info.id,info.goods_name,info.goods_summary,img.img_url 
				from first_aid_goods_info as info 
				left join first_aid_goods_belong_classify as class 
				on info.id = class.goods_id 
				left join first_aid_goods_img_info as img
				on info.id = img.goods_id
				where class.classify_id = '$cid' 
				and info.type = 2  
				and img.type = 2
				order by info.createDate desc 
				limit 0,3";
		$result = $db->getAll($sql);
		echo json_encode($result);
	}
	
?>	
	
	
	
	
	
	
	
	