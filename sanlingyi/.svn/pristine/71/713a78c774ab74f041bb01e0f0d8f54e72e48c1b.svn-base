<?php
//众筹参与控制器
namespace Home\Controller;
use Think\Controller;
class EntityHospitalPayController extends TemplateController{
	public function index(){
		
	}	
	public function EntityHospitalPayList(){
		
		//取得分页设置
		$pageList=session('SESS_ListItemData');
		$pageList=$pageList[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		$dbc=M('entity_private_doctor_service_info','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		//dump($condition);
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:' 00:00:00';
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s .= ' and entity_private_doctor_service_info.createDate >="'.$start.'" and entity_private_doctor_service_info.createDate < "'.$end.'"';
				
				if($arr[3] != -1){
					$s .=' and entity_private_doctor_service_info.service_state='.$arr[3];
				}
			}
		}
		
				
		$count=$dbc->where($s)->count();		
		$result=$dbc->field('entity_private_doctor_service_info.doc_id,entity_skyhospital_apply_info.clinic_name,entity_private_doctor_service_info.user_id,service_state,pay_state,entity_private_doctor_service_info.createDate,entity_private_doctor_service_info.id')
					->join('LEFT JOIN entity_skyhospital_apply_info ON entity_private_doctor_service_info.entity_id=entity_skyhospital_apply_info.id')
					->where('1=1'.$s)
					->order('createDate desc')
					->limit($pageList['start'],$pageList['limit'])
					->select();
		//echo  $dbc->getLastSql();
// 		dump($result);
		
		foreach ($result as &$val){
			$statusArr=array(
				0=>'未付款',
				1=>'服务中',
				2=>'服务结束',
			);
			$val['service_state']=$statusArr[$val['service_state']];
			
			$payArr=array(
					1=>'现金购买',
					2=>'保险赠送',
			);
			$val['pay_state']=$payArr[$val['pay_state']];
			
			$val['user_id']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$val['user_id'])->getField('user_name');			
			$val['doc_id']=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$val['doc_id'])->getField('user_name');
				
		}
		
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);
	}

	//设置列表上的操作
	public function EntityHospitalPayAuthItemOpt($optData){
		return 'EntityHospitalPayAuth';
	}
	public function EntityHospitalPayAuth($id,$data){
		
		$dbc=M('entity_private_doctor_service_info','',$this->getDbLink(1));
		$poList=$dbc->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
	
	
		$data=$dbc->where('id='.$data)->find();		
	
		//dump($data);
		$this->assign('data',$data);
		$this->display('auth');
	
	}
	//提交审批
	public function EntityHospitalPayModify(){
		if(IS_POST){
			
			if(I('post.status')==1){
				
			
			//actionLog(1,'提现处理');//操作日志记录
			$pi=I('post.');
			$dbc=M('entity_private_doctor_service_info','',$this->getDbLink(1));
			
			
			$dbc->startTrans();
			$result=$dbc->where('id='.$pi['id'])->save(array('service_state'=>$pi['status'],'employee_id'=>$_SESSION['SESS_EmployeeInfo']['id'],'auth_time'=>date('Y-m-d H:i:s',time()),'resume'=>$pi['resume'],'start_time'=>date('Y-m-d H:i:s',time()),'end_time'=>date('Y-m-d H:i:s',strtotime('+1 years'))));

			$info=$dbc->where('id='.$pi['id'])->find();
			
			$doc_model=M('user_base_info','',$this->getDbLink(1));
			
			$doc_info=$doc_model->field('com_sic_service_info.money,k_service_doc.content as doc_content,user_base_info.k as k_state,com_sic_service_info.resume as sys_content')
					->join('LEFT JOIN k_service_doc ON user_base_info.user_id=k_service_doc.docId')
					->join('LEFT JOIN com_sic_service_info ON user_base_info.k=com_sic_service_info.id')
					->where('user_base_info.user_id='.$info['doc_id'])
					->find();			
				//echo $doc_model->getLastSql();exit;		
			
// 			$u_area=M('user_base_info','',$this->getDbLink(1))->where('user_id='.$info['user_id'])->getField('live_place');
			
// 			$area=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$u_area)->find();
			
// 			$city=M('com_sic_region_info','',$this->getDbLink(1))->where('parentId='.$area['parentId'])->find();
			
// 			//echo M('com_sic_region_info','',$this->getDbLink(1))->getLastSql();exit;
			
// 			$province=M('com_sic_region_info','',$this->getDbLink(1))->where('parentId='.$city['parentId'])->find();			
			
			
// 			//dump($city);dump($province);dump($doc_info);exit;
			
// 			$preData=array(
// 				'id'=>$this->getOrderID(1),
// 				'userId'=>$info['user_id'],
// 				'docId'=>$info['doc_id'],
// 				'start_time'=>$info['start_time'],
// 				'end_time'=>$info['end_time'],
// 				'createDate'=>$info['createDate'],
// 				'sessionID'=>$info['sessionID'],
// 				'sys_content'=>$doc_info['sys_content'],
// 				'doc_content'=>empty($doc_info['doc_content'])?'':$doc_info['doc_content'],
// 				'k_state'=>$doc_info['k_state'],
// 				'auth'=>0,//电子健康档案权限
// 				'order_form'=>'',
// 				'province'=>$province['id'],//用户的省市区
// 				'city'=>$city['id'],
// 				'district'=>$u_area

// 			);
// 			$k_dbc=M('k_user_buy','',$this->getDbLink(1));		
// 			$k_dbc->add($preData);
// 			//echo $k_dbc->getLastSql();exit;
			
			$fit_service_data=array(
				'id'=>$this->getOrderID(1),
				'service_id'=>$doc_info['k_state'],
				'user_id'=>$info['user_id'],
				'doctor_id'=>$info['doc_id'],
				'book_date'=>date('Y-m-d H:i:s',time()),
				'pay_date'=>date('Y-m-d H:i:s',time()),
				'status'=>2,
				'end_date'=>$info['end_time'],
				'money'=>$doc_info['money'],
			);			
			M('fit_service_record','',$this->getDbLink(1))->add($fit_service_data);

			
			//dump(M('fit_service_record','',$this->getDbLink(1))->getLastSql());exit;
			M('fit_mtb_account','',$this->getDbLink(1))->where(array('id'=>$info['doc_id']))->setInc('service',$doc_info['money']);	

			//echo M('fit_mtb_account','',$this->getDbLink(1))->getLastSql();exit;
			$loop_data=array(
					'id'=>$this->createNewID(),
					'object_id'=>$fit_service_data['id'],
					'opt_date'=>date('d',time()),
					'status'=>0
			);
			
			//dump($loop_data);
			
			M('fit_loop_service_allot','',$this->getDbLink(1))->add($loop_data);			
			
			//echo M('fit_loop_service_allot','',$this->getDbLink(1))->getLastSql();exit;
			}
			if($result!==false){
				$dbc->commit();
				$this->ajaxReturn('0');
			}
			else{
				$dbc->rollback();
				$this->ajaxReturn('2');
			}
		}
		else{
			$this->loginError('3');
		}
	}	
	//按条件导出提现申请列表
	public function getExcel(){
	
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		$dbc=M('fit_refund_apply','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s['_string'] = 'opt_date >="'.$start.'" and opt_date < "'.$end.'"';
				
				if($arr[3] != -1){
					$s['fit_refund_apply.status']=$arr[3];
				}
			}
		}
	

		$result=$dbc->field('user_base_info.user_name,money,opt_date,bank_name,account_name,card_no,end_time,employee_id,resume,status')
		->join('LEFT JOIN user_base_info ON fit_refund_apply.user_id=user_base_info.user_id')
		->where($s)
		->order('opt_date desc')
		->select();
		
		$success_count=0;
		$error_count=0;
		$no_count=0;

		$success_money=0;
		$error_money=0;
		$no_money=0;
		
		foreach ($result as &$data){
			
			if($data['status']==2){
				$success_count++;
				$success_money+=$data['money'];
			}elseif($data['status']==1){
				$error_count++;
				$error_money+=$data['money'];
			}else{
				$no_count++;
				$no_money+=$data['money'];
			}
			
			$statusArr=array(
					0=>'未处理',
					2=>'成功处理',
					1=>'未成功处理',
			);
			$data['status']=$statusArr[$data['status']];
			
			$data['employee_id']=M('employee_info')->where('id='.$data['employee_id'])->getField('name');
				
		}

		
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="健康宝提现申请.csv"');
		header('Cache-Control: max-age=0');
	
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
	
		// 输出Excel列名信息
		$head = array('申请人','金额','申请时间','账户类型','账户名','卡号','处理时间','处理人','备注','状态');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
	
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		
		foreach ($result as $key => $val) {
			foreach($val as $k=>$v){
				$new[$k] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $new);
		}
		
		$count=count($result);
		
		
		$null=array('','','','','','','','');
		//统计信息
		fputcsv($fp,$null);
		fputcsv($fp,$null);
		fputcsv($fp, array(iconv('utf-8', 'gbk', '共'.$count.'条数据')));
		fputcsv($fp, array(iconv('utf-8', 'gbk', '未处理'.$no_count.'条，金额'.$no_money.'元')));		
		fputcsv($fp, array(iconv('utf-8', 'gbk', '未成功处理'.$error_count.'条，金额'.$error_money.'元')));		
		fputcsv($fp, array(iconv('utf-8', 'gbk', '成功处理'.$success_count.'条，金额'.$success_money.'元')));		

		
				
	}

	/**
	 * 生成一个全局唯一的19位纯数字的订单
	 * @param string $type 业务类型  1：空中医院；2：健康宝；3：商城；4：健康档案
	 */
	function getOrderID($type){
		$numArr = array('0','1','2','3','4','5','6','7','8','9');
		$randStr='';
		for($i=0;$i<8;$i++){
			$randStr.=$numArr[mt_rand(0,9)];
		}
		$randStr=$type.time().$randStr;
		return $randStr;
	}
	
	/**
	 * 生成随机数种子
	 * @return number
	 */
	public function makeSeed(){
		list($u, $c) = explode(' ', microtime());
		return (float) $c + ((float) $u * 100000);
	}
	/**
	 * 创建一个新的ID
	 */
	public function createNewID(){
		srand($this->makeSeed());
		$id= rand(100,199);
		srand($this->makeSeed());
		$id.=rand(100,999);
		srand($this->makeSeed());
		$id.=rand(1000,9999);
		return $id;
	}
	

}