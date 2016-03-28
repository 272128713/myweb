<?php
include_once(ROOT_PATH.'/common/inc.php');
include_once('Prourl.class.php');
include_once('Controller.class.php');
include_once('Model.class.php');
include_once (ROOT_PATH.'/'.APP_NAME.'/Common/function.php');
Prourl::parseUrl();
//自动加载
spl_autoload_register(array('Loader', 'autoload'));
/**
 * 自动加载类
 * @author Administrator
 *
 */
class Loader
{
	/**
	 * 自动加载类
	 * @param $class 类名
	 */
	public static function autoload($class)
	{  
		if(strpos($class,'Controller')>=1){
			include  ROOT_PATH.'/'.APP_NAME.'/Controller/'.ucfirst($class).'.php';
		}else if(strpos($class,'Model')>=1){
			include ROOT_PATH.'/'.APP_NAME.'/Model/'.ucfirst($class).'.php';
		}else{
			//todo
		}
				
	}
}

$m=ucfirst($_GET['m']).'Controller';
$run=new $m;
$run->$_GET['a']();