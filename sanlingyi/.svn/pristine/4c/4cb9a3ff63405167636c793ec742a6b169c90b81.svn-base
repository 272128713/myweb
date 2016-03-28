<?php
/**
 * 监控信息控制器
 *
 */
namespace Home\Controller;
use Think\Controller;
class MonitorController extends TemplateController{
	public function index(){
		
	}
	//健康宝监控信息	
	public function monitorFitpay($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function monitorFitpayList($moduleID){
			$this->getOptData($moduleID,2);				
	}
	//解决状态
	public function monitorFitpayStatusItemOpt($optData){
		$result=$optData['status']>1?'true':'false';
		return $result;
	}	
	public function monitorFitpayStatus($data){
		return $this->changeStatus($data);
	}
	
	
	//空中医院监控信息
	public function monitorHospital($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function monitorHospitalList($moduleID){
		$this->getOptData($moduleID,1);	
	}
	//解决状态
	public function monitorHospitalStatusItemOpt($optData){
		$result=$optData['status']>1?'true':'false';
		return $result;
	}
	public function monitorHospitalStatus($data){
		return $this->changeStatus($data);
	}	
	//商城监控信息
	public function monitorShop($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function monitorShopList($moduleID){
		$this->getOptData($moduleID,3);	
	}
	//解决状态
	public function monitorShopStatusItemOpt($optData){
		$result=$optData['status']>1?'true':'false';
		return $result;
	}
	public function monitorShopStatus($data){
			return $this->changeStatus($data);
	}		
	//健康档案监控信息
	public function monitorHealth($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function monitorHealthList($moduleID){
		$this->getOptData($moduleID,4);	
	}		
	//解决状态
	public function monitorHealthStatusItemOpt($optData){
		$result=$optData['status']>1?'true':'false';
		return $result;
	}
	public function monitorHealthStatus($data){
			return $this->changeStatus($data);
	}	
	
	//通用接口监控信息
	public function monitorCommon($id){
		$this->tplList($id);
		$this->display('Template/list');
	}
	
	public function monitorCommonList($moduleID){
		$this->getOptData($moduleID,5);
	}	
	
	//解决状态
	public function monitorCommonStatusItemOpt($optData){
		$result=$optData['status']>1?'true':'false';
		return $result;
	}
	public function monitorCommonStatus($data){
		return $this->changeStatus($data);
	}	
	
	function getOptData($moduleID,$type){
		$dbc=M('com_sys_error_report','',$this->getDbLink(1));
		$message_dbc=M('com_conf_message_code','',$this->getDbLink(1));
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[$moduleID];
		
		
		switch($type){
			case 1://空中医院
				$s['opt_type']=array('between',array(100,399));
				break;
			case 2://健康宝
				$s['opt_type']=array('between',array(400,499));
				break;
			case 3://商城
				$s['opt_type']=array('between',array(0,0));
				break;				
			case 4://健康档案
				$s['opt_type']=array('between',array(0,0));
				break;	
			case 5://接口异常
				$s['opt_type']=array('between',array(10,99));
				break;								
		}
		
		$count=$dbc->where($s)->count();
		//echo $dbc->getLastSql();exit;
		$result=$dbc->field('object_id,opt_type,err_type,com_conf_message_code.resume,opt_date,com_sys_error_report.status as status_name,com_sys_error_report.status,com_sys_error_report.id')//错误对象ID，错误代码，错误信息，错误时间，
		->join('LEFT JOIN com_conf_message_code ON com_sys_error_report.err_type = com_conf_message_code.id')
		->where($s)
		->limit($pageList['start'],$pageList['limit'])
		->order('opt_date desc')->select();
		foreach ($result as &$v){
			$statusArr=array(
					0=>'未处理',
					1=>'未处理',
					2=>'已处理',
			);
			$v['status_name']=$statusArr[$v['status_name']];			
			$resume=$message_dbc->where('id='.$v['opt_type'])->getField('resume');
			$v['opt_type']=$v['opt_type'].'-'.$resume;			
		}
		$this->tplListItem($moduleID,$result,$count);
	}
	function changeStatus($data){
		$dbc=M('com_sys_error_report','',$this->getDbLink(1));
		$t=$dbc->find($data);
		$s=$t['status']>1?1:2;
		$result=$dbc->where('id='.$data)->setField('status',$s);
		return $result;
	}
			
}