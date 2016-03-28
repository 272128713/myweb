<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
class CommonController extends Controller {
	/**
	 * 正常登录进入系统以后，每次操作都需要判断用户的登录状态
	 * loginStatus:	0 = 正常状态 	1 = 长时间未操作	2 = 重复登录	3 = 非法的访问来源   4=更改配置 
	 */
	public function _initialize(){	
		

		
		$loginStatus=0; //初始化登录状态为0，默认登录状态为正常
		$e=session('SESS_EmployeeInfo');
		$data=S($e['id']); //取出用户在catch中保存的sessionID
		
		//判断流程：
		//1、首先判断session和catch是否都不存在，是：没登录，直接回到登录界面
		if($_POST['my_session']){
			//ajax上传图片特殊处理
			$loginStatus=11;
		}
		elseif(empty($data['CATCH_SessionID']) && empty($e['id'])){
			$loginStatus=9;
		}
		else{
			//如果session和catch都存在
			if(!empty($data['CATCH_SessionID']) && !empty($e['id'])){
				//判断两者的sessionID是否相同，如果相同，正常登录中，否则，说明用户在别的设备登录过
				if($data['CATCH_SessionID']!=session_id()){
					$loginStatus=1;//帐号在别的设备上登录了
				}
			}
			else{//如果session和catch只存在一个，区别对待
				//如果sessiuon存在，说明用户在别的设备登录并正常退出，删除了catch
				if(!empty($e['id'])){
					$loginStatus=1;//帐号在别的设备上登录了
				}
				else{//如果catch存在，说明用户长时间没有操作
					$loginStatus=2;//长时间没有操作
				}
			}
		}
		if($loginStatus==11){
			//ajax上传图片特殊处理
		}
		elseif($loginStatus>0){
			$this->loginError($loginStatus);
		}
		else{
			//登录状态正常的情况下，判断系统配置是否修改，如果修改，需要重新登录
			$sys=F('Common');
			if(strtotime($sys['time'])>strtotime(session('SESS_LoginTime'))){
				$this->loginError('4');
			}
		}
	}	
	/**
	 * 检测到登录状态不符合正常登陆的要求是，进行错误提示并退出系统
	 * @param int $status 错误代码	1：长时间没有操作
	 * 								2：同一账号在两台设备上登录
	 * 								3：没有通过正常途径访问系统
	 * 								4：系统管理员更改了配置，强制退出
	 */
	public function loginError($status)
	{
		$this->assign('status',$status);
		$this->display('Login/exit');
	}	
	/**
	 * 更改系统配置，强制用户重新登录
	 */
	public function resetPower(){
		$time=date('Y-m-d H:i:s');
		$sys=F('Common');
		$sys['time']=$time;
		F('Common',$sys);
	}
	/**
	 * 切换数据，获取指定数据库连接编号的url
	 * @param unknown $id  数据库连接编号，参考系统功能“数据库配置”
	 * @return string 数据库连接url
	 */
	public function getDbLink($id){
		$opt=D('Common');
		$db=$opt->getDB();
		$data=$db['db'][$id]['link'];
		return $data;
	}
	/**
	 * 生成一个新的ID
	 * @return number
	 */
	public function createNewID()
	{
		$ID="1".rand(100, 999).rand(100, 999).rand(100, 999);
		$ID=$ID*1;
		return $ID;
	}
	/**
	 * 将模块信息格式化，生成可供operateAjax.js处理的格式
	 * @param  $data  需要格式化模块信息的数据集
	 * @param  $parentName 上级模块的名称
	 * @param  $defined 个别需要自定的参数，数组型
	 * @return $property 完成格式化的字符串
	 */
	public function formatModuleProperty($data,$parentName='',$defined=''){
		$property='';
		$property.=$data['module_id'];
		$property.=','.session('SESS_optModuleEN');
		if(isset($defined['en'])){
			$property.=','.$defined['en'];
		}
		else{
			$property.=','.$data['en'];
		}		
		$property.=','.$data['operate_type'];
		$property.=','.$data['reload_type'];
		$property.=$data['operate_type']==1? ','.$parentName.'--'.$data['module_name']:'';
		if(isset($defined['winWidth'])){
			$property.=$data['operate_type']==1? ','.$defined['winWidth'].','.$defined['winHeight']:'';
		}
		else{
			$property.=$data['operate_type']==1? ','.$data['window_width'].','.$data['window_height']:'';
		}
		$property.=','.$data['isConfirm'];
		$property.=$data['isConfirm']==1 ? ','.$data['confirm_text']:'';
		return $property;
	}
	/**
	 * 列表分页处理
	 * @param int $moduleID 显示列表的模块id
	 * @param int $rowNums 所有结果的总数
	 * @return array $result	next:下一页 ；pre：上一页；last：最后一页；pageNums：分页总数；nowPage：当前页数；
	 * 							limit：每页显示的记录数；rowNums：记录总数	
	 */
	public function pages($moduleID,$rowNums){
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[$moduleID]['parameter'];
		$start=$pageList['start']>0?($pageList['start']-1)*$pageList['limit']:0;
		$pre=$start-$pageList['limit'];
		$next=$start+$pageList['limit'];
		$pages=ceil($rowNums/$pageList['limit']);
		$last=($pages-1)*$pageList['limit'];
		if($pre<0)
		{
			$pre=0;
		}
		if($next>=$rowNums)
		{
			$next=$last;
		}
		$result=array(
			'next'=>$next,
			'pre'=>$pre,
			'last'=>$last,
			'pageNums'=>$pages,
			'nowPage'=>$pageList['nowPage'],
			'limit'=>$pageList['limit'],
			'rowNums'=>$rowNums
		);
		return $result;
	}
	
	public function formatConfig($data,$table='',$dbID=0){
		if($table!=''){
			$opt=D('Common');
			$key=$opt->getPrimaryKey($table,$dbID);
		}
		else{
			$key='id';
		}
		foreach ($data as $k=>$v){
			$result[$v[$key]]=$v;
		}
		return $result;
	}
	
	/**
	 * 远程调用接口
	 * @param int $id 数据库连接编号
	 * @param string $controller 接收操作的方法名
	 * @param array $data 需要提交的参数
	 * @return array
	 */
	public function Post($id,$controller,$param=array()){
		$opt=D('Common');
		$url=$opt->getDB();
		$url=$url['db'][$id]['server'].$controller;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		$resp=json_decode($server_output,true);
		return $resp;
	}
	/**
	 * 获取行政区划
	 * @param number $opt 操作类型：	0、获取全部信息；
	 * 								1、获取省列表；
	 * 								2、获取当前id下的市或地区列表；
	 * 								3、获取当前省下的全部资料包含市和区；
	 * 								4、获取$id的信息；
	 * @param number $id  区域编号	在$opt>1的情况下，根据$opt的值确定操作，
	 * 								$opt=2:$id=省或市编号，查找结果为，当前$id下的所有城市或地区；
	 * 								$opt=3:$id=省编号，查找结果为，当前省下的所有市和地区；
	 * 								$opt=4:$id=编号，查找结果为，当前$id的资料；
	 * @param boolean $type 范围		true：全部信息；false：status>0的信息
	 * @return array 
	 */
	public function getRegion($opt=0,$id=0,$type=false){
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$condition=$type?'true':'status>0';
		switch ($opt){
			case 0:
				$d=$dbc->where($condition)->order('position')->select();
				break;
			case 1:
				$d=$dbc->where('level=1 and '.$condition)->order('position')->select();
				break;
			case 2:
				$d=$dbc->where('parentId='.$id.' and '.$condition)->order('position')->select();
				break;
			case 3:
				$t=$dbc->where('parentId='.$id.' and '.$condition)->order('position')->getField('id',true);
				$d=$dbc->where('(id in ('.implode(',', $t).') or parentId in ('.implode(',', $t).')) and '.$condition)->order('position')->select();
				break;
			default:
				$d=$dbc->find($id);
				break;
		}
		//echo $dbc->getLastSql();exit;
		return $d;
	}
	public function getRegionCatch($id=0){		
		$e=session('SESS_EmployeeInfo');
		$r=S($e['id']);		
		
		
		//dump($r);exit;
		
		if($id==0){
			$moduleID=session('SESS_optModuleID');
		}
		else{
			$moduleID=$id;
		}
		return $r['CATCH_ModuleRegion'][$moduleID];
	}
	/**
	 * 获取当前用户在传入的模块中的操作范围
	 * @param int $id  模块id
	 */
	public function getRegionByModule($id=0){
		$dbc=M('com_sic_region_info','',$this->getDbLink(1));
		$r=$this->getRegionCatch($id);
		if(is_array($r)){
			$reg=implode(',', $r);
			$result=$dbc->where('status>0 and id in ('.$reg.')')->order('position')->select();
		}
		else{
			if($r==9){
				$result=$dbc->where('status>0')->order('position')->select();
			}
			else{
				$result='';
			}			
		}
		return $result;
	}
	/**
	 * 上传图片
	 * @author huajie <banhuajie@163.com>
	 */
	public function uploadPicture(){
		//TODO: 用户登录检测

		/* 返回标准数据 */
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
	
		/* 调用文件上传组件上传文件 */
		$Picture = D('Picture');
		$pic_driver = C('PICTURE_UPLOAD_DRIVER');
		$info = $Picture->upload(
				$_FILES,
				C('PICTURE_UPLOAD'),
				C('PICTURE_UPLOAD_DRIVER'),
				C("UPLOAD_{$pic_driver}_CONFIG")
		); //TODO:上传到远程服务器
	
		//print_r($info);exit;
		
		/* 记录图片信息 */
		if($info){
			$return['status'] = 1;
			$return = array_merge($info['download'], $return);
		} else {
			$return['status'] = 0;
			$return['info']   = $Picture->getError();
		}
		
		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}	
	//同步医信商城数据
	public function updateData(){
		$dbc=M();
		$sql="select * from yixin.user_base_info
				where user_id not in (select id from healthinfo.mtb_doctor) and user_type_id=2 order by reg_date desc";
		$data=$dbc->query($sql);
	
		//dump($data);
	
		foreach ($data as $docInfo){
				
				
			//更新healthinfo
				
			$hosArr=explode('-',$docInfo['hospital']);
			$versionInfo=M('user_version_info','',$this->getDbLink(1))->where('user_id='.$docInfo['user_id'])->find();
			$img=strtr($versionInfo['thumbnail_image_url'],array('M00'=>'MOO/data'));
			$mtb_model=M('mtb_doctor','',$this->getDbLink(2));
			
			
			$recollection=M('dim_recollection_code','',$this->getDbLink(1))->where('recollection_id='.$docInfo['recollection_id'])->getField('name');
			$duty=M('dim_duty_code','',$this->getDbLink(1))->where('duty_id='.$docInfo['duty_id'])->getField('name');
				
			$arr=array(
					'id'=>$docInfo['user_id'],
					'name'=>$docInfo['user_name'],
					'headImg'=>$img,
					'phone'=>$docInfo['mobile'],
					'province'=>empty($hosArr[0])?'':$hosArr[0],
					'city'=>empty($hosArr[1])?'':$hosArr[1],
					'section'=>empty($hosArr[2])?'':$hosArr[2],
					'hospital'=>empty($hosArr[3])?'':$hosArr[3],
					'depart'=>empty($recollection)?'':$recollection,
					'title'=>empty($duty)?'':$duty,
					'remark'=>$docInfo['comm'],
			);
			//dump($arr);
			$mtb_model->add($arr);
		}
	}
	// 更新user_base_info信息到com_dic_doctor_info
	public function updateUserbase(){
		$dbc=M('user_base_info','',$this->getDbLink(1));
		$data=$dbc
				->field('user_base_info.user_id,user_base_info.hospital')
				->join('LEFT JOIN com_dic_doctor_info ON user_base_info.user_id=com_dic_doctor_info.id')
				->where('user_base_info.user_type_id=2 and user_base_info.hospital !="" and com_dic_doctor_info.hospital ="" ')
				->select();
		//echo $dbc->getLastSql();
		\Think\Log::record(json_encode($data));	
		$new_dbc=M('com_dic_doctor_info','',$this->getDbLink(1));
		foreach ($data as $val){
			
			$hosArr=explode('-',$val['hospital']);
			
			$optData=array(
					'province'=>empty($hosArr[0])?'':$hosArr[0],
					'city'=>empty($hosArr[1])?'':$hosArr[1],
					'district'=>empty($hosArr[2])?'':$hosArr[2],
					'hospital'=>empty($hosArr[3])?'':$hosArr[3],					
			);
			//dump($optData);
			$f=$new_dbc->where('id='.$val['user_id'])->save($optData);
			if($f==false){
				\Think\Log::record(json_encode('出错ID'.$val['user_id'].'!!!!'));
			}
		}		
		//dump($data);
		
	}
	
}
?>