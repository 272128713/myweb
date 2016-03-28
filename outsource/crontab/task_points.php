<?php
#!/alidata/server/php/bin/php -q
ignore_user_abort();
set_time_limit(3000);
$interval=5;
$db=mysqli_connect('210.14.72.62', 'shop', '301301', 'shop_skyhospital', '3306');
mysqli_query($db, 'set names utf8');
$page=0;
$result=mysqli_query($db, "call project_task()");
if($result){
	echo 'excute success time:'.date('Y-m-d H:i:s')."\n";
}else{
	echo 'excute fail time:'.date('Y-m-d H:i:s')."\n";
}