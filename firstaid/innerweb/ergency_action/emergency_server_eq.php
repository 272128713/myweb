
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
		die();
	}
	if($_GET["ss"]){
		$_SESSION['ss']=$_GET["ss"];
		$sessionArr = $databaseManager->checkSession($_SESSION['ss']);
		$_SESSION['uid']=$sessionArr['user_id'];
		if(!$sessionArr){			
			echo "<div style='margin:1em 0 0 2em'>Session Error</div>";
		?>
			<?php
			die();
		}
	}else{
	
	}
		//分类列表
		$sql_classify = "select id,describes from first_aid_goods_classify_info ";
		$result = $db->getAll($sql_classify);
		$class = $result;
	if($_GET["con"]){
		//条件筛选
		$con =$_GET['con'];
		$sql_eq = "select info.id,info.goods_name,img.img_url
		from first_aid_goods_info as info
		left join first_aid_goods_img_info as img
		on info.id = img.goods_id
		where info.type=1 and img.type=2
		and info.state =1
		and info.goods_name like '%$con%'
		";
		$result = $db->getAll($sql_eq);
		$eq=$result;
	}else{
		//分类查询
		if($_GET['cid']){
			$cid=$_GET['cid'];
		}else{
			$cid=1;
		}
		//单品列表
		$sql_eq = "select info.id,info.goods_name,img.img_url
				from first_aid_goods_info as info 
				left join first_aid_goods_img_info as img
				on info.id = img.goods_id
				left join first_aid_goods_belong_classify as cls
				on info.id = cls.goods_id
				where info.type=1 and img.type=2 
				and info.state =1
				and cls.classify_id='$cid' 
				";
		$result = $db->getAll($sql_eq);
		$eq=$result;
	}
?>	
	
	
	
	
	
	
	
	