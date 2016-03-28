<?php
/**
 * 健康宝交易流水
 * 
 */

namespace Home\Controller;
use Think\Controller;
class TradeRecordController extends TemplateController{
	public function index(){
		
	}	
	
	public function TradeRecordList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('fit_trade_record','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s['_string'] = 'opt_date >"'.$start.'" and opt_date < "'.$end.'"';
				if($arr[3]!=-1){//类型
					$s['fit_trade_record.opt_type']=$arr[3];
				}
				if($arr[4]!==''){//用户名
					$s['com_dic_user_info.name']=$arr[4];
				}
				if($arr[5]!==''){//手机
					$s['com_dic_user_info.mobile']=$arr[5];
				}								
			}else{//简单搜索
		
			}
		}
		$count=$dbc->join('LEFT JOIN com_dic_user_info ON fit_trade_record.user_id = com_dic_user_info.id')->where($s)->count();
		$result=$dbc->field('com_dic_user_info.name,opt_type,money,pay_method,trade_type,opt_date,fit_trade_record.id')
		->join('LEFT JOIN com_dic_user_info ON fit_trade_record.user_id = com_dic_user_info.id')
		->where($s)
		->limit($pageList['start'],$pageList['limit'])
		->order('opt_date desc')
		->select();
		
		 //echo $dbc->getLastSql();
		foreach($result as &$v){
			$v['opt_type']=$v['opt_type']>0?'出账':'入账';
			
			$pmArr=array(
				0=>'健康宝',
				1=>'支付宝',
				2=>'银联',
			);
			$v['pay_method']=empty($pmArr[$v['pay_method']])?$v['pay_method']:$pmArr[$v['pay_method']];
			$ttArr=array(
					0=>'用户自己转入',
					1=>'提现',
					2=>'预约挂号',
					3=>'私人医生服务',
					4=>'购买商城商品',
					5=>'购买商城商品',
					6=>'购买商城商品',
					7=>'电子健康档案使用费',
					8=>'预定系统K服务',
					9=>'取消预订',
					10=>'开启系统K服务',
					11=>'退订系统K服务',
					13=>'K服务分账',
			);
			$v['trade_type']=empty($ttArr[$v['trade_type']])?$v['trade_type']:$ttArr[$v['trade_type']];
				
			
		}
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}
	//按条件导出员工审核列表
	public function getExcel(){
		
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		
		
		
		
		
		$dbc=M('fit_trade_record','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s['_string'] = 'opt_date >"'.$start.'" and opt_date < "'.$end.'"';
				if($arr[3]!=-1){//类型
					$s['fit_trade_record.opt_type']=$arr[3];
				}
				if($arr[4]!==''){//用户名
					$s['com_dic_user_info.name']=$arr[4];
				}
				if($arr[5]!==''){//手机
					$s['com_dic_user_info.mobile']=$arr[5];
				}
			}else{//简单搜索
		
			}
		}
		$result=$dbc->field('com_dic_user_info.name,opt_type,money,pay_method,trade_type,opt_date,fit_trade_record.id')
		->join('LEFT JOIN com_dic_user_info ON fit_trade_record.user_id = com_dic_user_info.id')
		->where($s)
		->order('opt_date desc')
		->select();
		
		foreach($result as &$v){
			$v['opt_type']=$v['opt_type']>0?'出账':'入账';
			
			$pmArr=array(
				0=>'健康宝',
				1=>'支付宝',
				2=>'银联',
			);
			$v['pay_method']=empty($pmArr[$v['pay_method']])?$v['pay_method']:$pmArr[$v['pay_method']];
			$ttArr=array(
					0=>'用户自己转入',
					1=>'提现',
					2=>'预约挂号',
					3=>'私人医生服务',
					4=>'购买商城商品',
					5=>'购买商城商品',
					6=>'购买商城商品',
					7=>'电子健康档案使用费',
					8=>'预定系统K服务',
					9=>'取消预订',
					10=>'开启系统K服务',
					11=>'退订系统K服务',
					13=>'K服务分账',
			);
			$v['trade_type']=empty($ttArr[$v['trade_type']])?$v['trade_type']:$ttArr[$v['trade_type']];
				
			
		}
		foreach($result as $k=>$v){
			$new_arr[$k]=$v;
		}
		
		//dump($result);exit;
		
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="健康宝流水记录表.csv"');
		header('Cache-Control: max-age=0');

		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
		
		// 输出Excel列名信息
		$head = array('用户名','操作类型','金额','支付方式','交易类型','操作时间');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
		
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);			
		foreach ($new_arr as $key => $val) {			
			//var_dump($val);			
				foreach($val as $k=>$v){
					$new[$k] = iconv('utf-8', 'gbk', $v);
				}				
			fputcsv($fp, $new);
		}	
	}

	public function StaffManageChangeItemOpt(){
		$result='staffManageChange';
		return $result;
	}
	//修改发展人
	public function staffManageChange($id,$data){
		
		$model=M('staff_doctor_check','',$this->getDbLink(1));
		$da['staff_name']=$model->where('doctor_id='.$data)->getField('staff_name');
		$da['doctor_id']=$data;
		$this->assign('data',$da);
		
		$agent_model=M('com_dic_authentication_info','',$this->getDbLink(1));
		$agents=$agent_model->field('id,name')->where('status=1')->select();
		$this->assign('agents',$agents);
		
		$poList=$model->order('position')->select();
		$this->getPlaceModule($id,0);
		$this->assign('poList',$poList);
		$this->display('change');
	}	
	public function staffManageChangeModify(){
		if(IS_POST){

			$dbc=M('staff_doctor_check','',$this->getDbLink(1));

			$doctor_id=I('post.doctor_id');
			$staff_name=I('post.staff_name');			
			
			$dbc->startTrans();
			$d_info=$dbc->where('doctor_id='.$doctor_id)->find();
			actionLog(1,$_SESSION['SESS_EmployeeInfo']['name'].'修改'.$d_info['doctor_phone'].'的认证人('.$d_info['staff_name'].'->'.$staff_name.')');//操作日志记录
			
			$result=$dbc->where('doctor_id='.$doctor_id)->save(array('staff_name'=>$staff_name));
			
			if(I('post.agent')!=-1 && I('post.spreader')!=-1){
				$agent_code=M('com_dic_authentication_info','',$this->getDbLink(1))->where('id='.I('post.agent'))->getField('code');
				$spreader_code=M('spreader_info','',$this->getDbLink(1))->where('id='.I('post.spreader'))->getField('code');
				//修改医生的代理商
				M('com_dic_doctor_info','',$this->getDbLink(1))->where('id='.$doctor_id)->save(array('agent'=>$agent_code,'spreader'=>$spreader_code));				
			}
			

						
			if($result!==false){
				$dbc->commit();
				$this->ajaxReturn('0');
			}else{
				$dbc->rollback();
				$this->ajaxReturn('1');
			}
		}else{
			$this->loginError('3');
		}
	} 
	
	
	
}