<?php
/**
 * 所有针对树形菜单的删除、改变状态、变更位置等操作
 * 此类树形菜单的数据表必须满足以下条件
 * 必须包含的字段：position,level
 * 数据排列依据：position
 */
namespace Home\Model;
use Think\Model;
class RadioeditModel extends CommonModel{
	/**
	 * 获取与传入ID相同父节点的全部同级信息，不包含自己
	 * @param $table
	 * @param $id
	 * @param $dbName
	 * @return $result  
	 */
	public function getBrother($table, $parentID, $id, $dbID=0){
		$idKey=$this->getPrimaryKey($table, $dbID);
		$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
		$result=$dbc->where('parentId='.$parentID.' and '.$idKey.' <> '.$id)->order('position')->select();
		return $result;
	}
	/**
	 * 获得传入id以及属于该ID下所有子节点信息直到节点末尾的全部信息id，只返回id
	 * @param $table 	数据表的名称
	 * @param $id 		操作数据的ID
	 * @return $result  数组型，包含endPosition：本次操作的数据集之外的第一条信息的位置；optData：数据集
	 */
	public function getOptData($table, $id, $dbID=0){
		$idKey=$this->getPrimaryKey($table, $dbID);
		$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
		$data=$dbc->find($id);
		//找到level小于等于当前level且position>当前position的最小值，定义为tp，则本次需要操作的数据总数=tp-position
		$tp=$dbc->where('level<='.$data['level'].' and position>'.$data['position'])->min('position');
		//如果tp小于1，说明当前信息及其等级以下的信息处在树形菜单的最末位
		if($tp<1){
			$tp=$dbc->max('position')+1;
		}
		//取出本次需要处理的所有数据
		$optData=$dbc->where('position>='.$data['position'].' and position<'.$tp)->order('position')->getField($idKey,true);
		$result=array(
			'endPosition'	=>	$tp,
			'optData'		=>	$optData
		);
		return $result;
	}
	/**
	 * singleTree模版框架下变更数据的使用状态
	 * @param $table 	数据表的名称
	 * @param $id 		操作数据的ID
	 * @return $result  boolean
	 */
	 public function changeStatus($table, $id, $dbID=0){
	 	$idKey=$this->getPrimaryKey($table, $dbID);
	 	$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
	 	$optData=$this->getOptData($table, $id, $dbID);
	 	$opt=$dbc->find($id);
	 	$data['status']=$opt['status']>0 ? 0:1;
	 	$result=$dbc->where($idKey.' in ('.implode(',', $optData['optData']).')')->save($data) >0 ? true : false;
	 	return $result;
	 }
	 /**
	  * 获取所有需要删除的数据ID
	  * @param $table 	数据表的名称
	  * @param $id 		需要删除的数据ID
	  * @return $result 数组型，包含status：执行状态；optData：数据集
	  */
	 public function getDel($table, $id, $dbID=0){
	 	$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
	 	$data=$dbc->find($id);
	 	if(count($data)>0){
	 		$data=$this->getOptData($table, $id, $dbID);
	 		$idList=$data['optData'];
	 		//执行删除
	 		$status=$dbc->delete(implode(',',$idList))!==false ? true : false;
	 		if($status){	 				
	 			$dbc->where('position>='.$data['endPosition'])->setDec('position',count($idList));
	 		}
	 		$result=array(
 				'status'	=>	$status,
 				'optData'	=>	$idList
	 		);
	 	}
	 	else{
	 		$result=array(
 				'status'	=>	true,
 				'optData'	=>	''
	 		);
	 	}	 	
	 	//移动位置
	 	return $result;
	 }
	 
	 /**
	  * 获取最终的position值
	  * @param $table 		数据表的名称
	  * @param $id 			页面中“显示位置”下拉列表所选择的“在【×××】之后”的数据的ID
	  * @param $parentID	当前操作数据的parentID
	  * @return $position   返回位置信息
	  */
	 public function getPosition($table, $id, $parentID, $dbID=0){
	 	$idKey=$this->getPrimaryKey($table, $dbID);
	 	$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
	 	//1、判断传入的ID是否为0，如果是0，说明是创建一级信息且显示在第一位，position=1；结束。
	 	if($id>0){
	 		//2、判断传入的parentID和id是否相等，如果不相等说明当前操作的记录和传入的ID属于同一级别，执行下面的操作；否则，说明选择的位置信息时是“显示在第一位”，position=parentID的positionID+1
	 		if($parentID!=$id){
	 			$data=$dbc->find($id);
	 			$tp=$dbc->where('level<='.$data['level'].' and position>'.$data['position'])->min('position');
	 			if($tp>0){
	 				$position=$tp;
	 			}
	 			else{
	 				$tp=$dbc->max('position');
	 				$position=$tp+1;
	 			}	
	 		}
	 		else{
	 			$data=$dbc->find($parentID);
	 			$position=$data['position']+1;
	 		}
	 	}
	 	else{
	 		$position=1;
	 	}
	 	return $position;
	 }	 
	 /**
	  * 变更数据显示位置
	  * @param $table 		数据表的名称
	  * @param $id 			当前操作的数据ID
	  * @param $parentID	当前操作的数据ID的所属信息ID
	  * @param $positionID	页面中“显示位置”下拉列表所选择的“在【×××】之后”的数据的ID
	  * @return $result boolean
	  */
	 public function movePosition($table, $id, $parentID, $positionID, $dbID=0){
	 	$idKey=$this->getPrimaryKey($table, $dbID);
		$db=$this->getDB();
		$dbc=M($table,'',$db['db'][$dbID]['link']);
	 	$od=$dbc->find($id);
	 	//1、首先判断位置有没有变化，如果没有变化就跳出function
	 	if($positionID>0){// 说明选择的是某个数据，而不是“显示在第一位”
	 		$pod=$this->getPosition($table, $positionID, $parentID, $dbID);
	 	}
	 	else{//说明选择的是显示在第一位
	 		if($parentID>0){//说明选择的是某个数据，不是“本身就是一级”
	 			$pod=$this->getPosition($table, $parentID, $parentID, $dbID);
	 		}
	 		else{//本身就是一级，这时，fp=1
	 			$pod=1;
	 		}
	 	}
	 	//如果不想等，说明位置信息发生变化，否则跳出function
	 	if($od['position']!=$pod){
	 		//2、取出本次需要处理的所有数据信息，记录在$optData中；
	 		$data=$this->getOptData($table, $id, $dbID);
	 		$optData=$data['optData'];
	 		//将这些记录的position改成0；
	 		$dbc->where($idKey.' in ('.implode(',', $optData).')')->setField('position',0);
	 		//3、将所有position大于等于tp的数据的position-nums
	 		$dbc->where('position>='.$data['endPosition'])->setDec('position',count($optData));
	 		//4、将传入的positionID的数据信息再取一遍，获得最新的最终position
		 	if($positionID>0){
		 		$pod=$this->getPosition($table, $positionID, $parentID, $dbID);
		 	}
		 	else{
		 		if($parentID>0){
		 			$pod=$this->getPosition($table, $parentID, $parentID, $dbID);
		 		}
		 		else{
		 			$pod=1;
		 		}
		 	}
	 		//5、将所有大于最终position的数据的position+nums
	 		$dbc->where('position>='.$pod)->setInc('position',count($optData));
	 		//6、判断当前数据的parentID和传入的parentID是否相等
	 		//	如果不相等，说明数据的所属信息发生改变，要根据情况决定是否需要变更当前数据的level
	 		if($od['parentId']!=$parentID){
	 			//9、如果$parentID>0说明不是一级
	 			if($parentID>0){
	 				//取出传入parentID的数据信息
	 				$pad=$dbc->find($parentID);
	 				$ml=$pad['level']+1;
	 			}
	 			else{
	 				$ml=1;
	 			}
	 			//7、计算当前数据的最终level和目前level的差距
	 			$ml=$od['level']-$ml;
	 			//如果大于零说明等级提升，数据库操作应该是-，否则相反，
	 			$ml=$ml>0?'-'.$ml:'+'.abs($ml);
	 		}
	 		//8、将需要移动的所有数据的level和position重新赋值，更新数据库
	 		$sql='update '.$table.' set level=level'.$ml.',position=case '.$idKey.' ';
	 		$i=0;
	 		foreach ($optData as $k=>$v){
	 			$position=$i+$pod;
	 			$sql .= sprintf('when %d then %d ', $v, $position);
	 			$i++;
	 		}
	 		$sql .= 'end where '.$idKey.' in ('.implode(',',$optData).')';
	 		$result=$dbc->execute($sql);
	 		$result=$result!==false?true:false;
	 	}
	 	else{
	 		//如果相等，说明位置信息没有变化，结束函数
	 		$result=true;
	 	}
		return $result;
	}
	
	public function singlePosition($table,$poOld,$poNew,$dbID=0){
		//判断位置信息是否有变化，如果没有，结束方法
		if($poOld!=$poNew+1){
			$db=$this->getDB();
			$dbc=M($table,'',$db['db'][$dbID]['link']);
			$dbc->where('position>'.$poOld)->setDec('position');
			if($poOld>$poNew){
				$dbc->where('position>'.$poNew)->setInc('position');
				$position=$poNew+1;
			}
			else{
				$dbc->where('position>='.$poNew)->setInc('position');
				$position=$poNew;
			}
		}
		else{
			$position=$poOld;
		}
		return $position;
	}
}
?>