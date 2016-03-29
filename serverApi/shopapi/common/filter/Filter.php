<?php

class Filter{
	
	/**
	 * 
	 * 校验提交的数据是否参数完整，如果完整，返回结果数组
	 * @param mix $_POST
	 * @param Array $pamList
	 */
	public static function paramCheckAndRetRes($post,$pamList){
		$result = array();
		$checkFlag = true;
		$mqg=get_magic_quotes_gpc();//ini设置里面是否已经自动经过转义
		foreach($pamList as $param){
		    $paramT = "";
		    $blankCheck = false;
		    if(gettype($param) == "array"){
		        $paramT = $param[0];
		        $blankCheck = $param[1];
		    } else {
		        $paramT = $param;
		    }
		    if($blankCheck){
				if(isset($post[$paramT])){
					if($mqg==1){
						$result[$paramT] = trim($post[$paramT]);
					}elseif ($mqg==0){
						$result[$paramT] = addslashes(trim($post[$paramT]));
					}
				} else {
					$checkFlag = false;
					break;
				}
		    }else{
		    	if(isset($post[$paramT])){			    
		    		if($mqg==1){
						$result[$paramT] = trim($post[$paramT]);
					}elseif ($mqg==0){
						$result[$paramT] = addslashes(trim($post[$paramT]));
					}
				}
		    }
			if($blankCheck && trim($result[$paramT]) == ""){
			    $checkFlag = false;
			    break;
			}
		}
		if($checkFlag){
			return $result;
		} else {
			return false;
		}      
	}
	/**
	 * 
	 * 校验参数，返回结果数组
	 * @param mix $_POST
	 * @param Array $pamList
	 */
	public static function paramCheck($post,$pamList){
		$result = array();
		foreach($pamList as $param){
			if(isset($post[$param])){
				$result[$param]= $post[$param];
			} else {
				$result[$param]= null;
			}
		}
		return $result;
	}
	public static function paramAddSlash($param,$columns){
		$temp=$param;
		foreach ($columns as $v){
			if(isset($temp[$v])){
				$temp[$v]=addslashes($temp[$v]);
			}
		}
		return $temp;
	}
}
?>