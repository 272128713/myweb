<?php
namespace Home\Controller;
use Think\Controller;
class AjaxController extends CommonController{	
	/**
	 * 修改树形菜单数据时，选择上级信息的下拉列表框触发的事件，获得相应的数据
	 */
	public function getRadioOption($id, $parentID, $table, $dbID=0){
		$dbc=D('radioedit');
		$data=$dbc->getBrother($table, $parentID, $id, $dbID);
		$this->ajaxReturn($data);
	}
	
	public function doAjax($id,$data,$parameter){
		$dbc=M('module_info');
		$mData=$dbc->find($id);
		$controller=session('SESS_optModuleEN').'/'.$mData['en'];
		$result=R($controller,array('data'=>$data,'parameter'=>$parameter));
		if($result){
			$this->ajaxReturn($mData['reload_type']);
		}
		else{
			$this->ajaxReturn('E');
		}
	}
	
	public function getTableInfo($dbID){
		if(empty($dbID)){
			$dbID=0;
		}
		$opt=D('Common');
		$db=$opt->getDB();
		$option['TABLE_SCHEMA']=$db['db'][$dbID]['en'];
		$dbc=M('information_schema.tables','',$db['db'][$dbID]['link']);
		$data=$dbc->field('TABLE_NAME as name')->where($option)->order('TABLE_NAME')->select();
		$this->ajaxReturn($data);
	}
	//翻页和搜索
	public function pageTo($opt,$rowNums){
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')]['parameter'];		
		$nowPage=$pageList['nowPage'];
		$condition=$pageList['condition'];
		$pages=ceil($rowNums/$pageList['limit']);
		if(is_numeric($opt)){
			$nowPage=$opt;
			$start=($nowPage-1)*$pageList['limit'];
		}
		else{
			switch($opt){
				case 'F':
					$nowPage=1;
					$start=0;
					break;
				case 'P':
					$nowPage--;
					if($nowPage>0){
						$start=($nowPage-1)*$pageList['limit'];
					}
					else{
						$start=0;
					}
					break;
				case 'N':
					$nowPage++;
					$start=($nowPage-1)*$pageList['limit'];
					break;
				case 'L':
					$nowPage=$pages;
					$start=($nowPage-1)*$pageList['limit'];
					break;
				case 'R':
					$nowPage=1;
					$start=0;
					$condition='';
					break;
				default:
					$nowPage=1;
					$start=0;
					$condition=$opt;
			}	
		}

		$param=session('SESS_ListItemData');
		$param[session('SESS_optModuleID')]['parameter']=array(
			'nowPage'=>$nowPage,
			'start'=>$start,
			'limit'=>$pageList['limit'],
			'condition'=>$condition
		);
		session('SESS_ListItemData',$param);
		$this->ajaxReturn('0');
	}
	//排序
	public function orderTo(){		
		$_SESSION['SESS_ListItemData'][session('SESS_optModuleID')]['parameter']['order']=I('post.ord').','.I('post.ordType');
		$this->ajaxReturn('0');
	}	
	
	public function getRegionOption($id){
		$data=$this->getRegion(2,$id);
		$this->ajaxReturn($data);
	}
	
	public function getDepartmentOption($id){
		$dbc=M('department_info');
		$opt=D('radioedit');
		$d=$opt->getOptData('department_info',$id);
		$data=$dbc->where('id in ('.implode(',',$d['optData']).') and id<>'.$id.' and status>0')->order('position')->select();
		$this->ajaxReturn($data);
	}
	
	public function getDutyOption($id){
		$dbc=M('duty_info');
		$data=$dbc->join('department_duty on duty_info.id=department_duty.duty_id')->field('duty_info.*')->where('department_duty.department_id='.$id)->order('duty_info.position')->select();
		$this->ajaxReturn($data);
	}
	/**
	 * 查询省市区
	 */
	public function changeProvince(){
		$province_id=I('post.province');
			$citys=M('hat_city','',$this->getDbLink(1))->where('father='.$province_id)->select();
			echo json_encode($citys);			

	}	
	/**
	 * 查询省市区
	 */
	public function changeCity(){
		$city_id=I('post.city');
			$areas=M('hat_area','',$this->getDbLink(1))->where('father='.$city_id)->select();
			echo json_encode($areas);			

	}	
	/**
	 * 区指定代理商下推广人员
	 */
	public function selectSpreader(){
		$id=I('post.id');
		$spreader=M('spreader_info','',$this->getDbLink(1))->where('agent_id='.$id)->select();
		echo json_encode($spreader);
	
	}	
}