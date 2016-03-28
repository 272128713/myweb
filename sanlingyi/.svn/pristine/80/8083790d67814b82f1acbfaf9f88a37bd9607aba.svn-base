<?php
/**
 * 普通用户管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class UserLoginInfoController extends TemplateController{
	public function index(){
		
	}	
	
	public function UserLoginInfoList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		//dump($pageList);
		
		$dbc=M('user_session_info','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s = ' and last_get_session_date >"'.$start.'" and last_get_session_date < "'.$end.'"';
				if($arr[3]!=-1){
					$s.=" and user_base_info.user_type_id = $arr[3]";
				}				
				if($arr[4]!=''){
					$s.=" and user_base_info.user_name LIKE '%".trim($arr[4])."%'";
				}
				if($arr[5]!=''){
				$s.=" and user_base_info.mobile LIKE '%".trim($arr[5])."%'";
				}
				
				}
			}
			//排序条件
			$order=$pageList['order'];
			if($order != ''){
				$arr=explode(',',$order);		
				$list=array(
						'最后登录'=>'last_get_session_date',
						'注册时间'=>'reg_date',
				);
				$orderStr=$list[$arr[0]].' '.$arr[1];
			}else{
				$orderStr='last_get_session_date desc';
			}
			$where='1=1 '.$s;
				$count=$dbc
				->join('LEFT JOIN user_base_info ON user_session_info.user_id = user_base_info.user_id')
				->where($where)
				->count();
				$result=$dbc->field('user_name,sex_id,user_type_id,reg_date,last_get_session_date')
					->join('LEFT JOIN user_base_info ON user_session_info.user_id = user_base_info.user_id')
					->where($where)
					->limit($pageList['start'],$pageList['limit'])
					->order($orderStr)
                    ->select();
				//echo $dbc->getLastSql();
		foreach($result as &$v){
				$sexArr=array(
				0=>'未选择',
				1=>'男',
				2=>'女',
				);
				$v['sex_id']=$sexArr[$v['sex_id']];
				
				$typeArr=array(
						0=>'非空中医院首次注册',
						1=>'普通用户',
						2=>'医生用户',
						3=>'公共账号',
				);
				$v['user_type_id']=$typeArr[$v['user_type_id']];				
		
		}
							//dump($result);
							$this->tplListItem(session('SESS_optModuleID'),$result,$count);
		
	}
	
	//按条件导出员工审核列表
	public function getExcel(){
		ini_set("memory_limit","-1");
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		//dump($pageList);
		
		$dbc=M('user_session_info','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s = ' and last_get_session_date >"'.$start.'" and last_get_session_date < "'.$end.'"';
				if($arr[3]!=-1){
					$s.=" and user_base_info.user_type_id = $arr[3]";
				}				
				if($arr[4]!=''){
					$s.=" and user_base_info.user_name LIKE '%".trim($arr[4])."%'";
				}
				if($arr[5]!=''){
				$s.=" and user_base_info.mobile LIKE '%".trim($arr[5])."%'";
				}
				
				}
			}
			//排序条件
			$order=$pageList['order'];
			if($order != ''){
				$arr=explode(',',$order);		
				$list=array(
						'最后登录'=>'last_get_session_date',		
						'注册时间'=>'reg_date',
				);
				$orderStr=$list[$arr[0]].' '.$arr[1];
			}else{
				$orderStr='last_get_session_date desc';
			}
			$where='1=1 '.$s;
				$count=$dbc
				->join('LEFT JOIN user_base_info ON user_session_info.user_id = user_base_info.user_id')
				->where($where)
				->count();
				$result=$dbc->field('user_name,sex_id,user_type_id,reg_date,last_get_session_date')
					->join('LEFT JOIN user_base_info ON user_session_info.user_id = user_base_info.user_id')
					->where($where)
					//->limit($pageList['start'],$pageList['limit'])
					->order($orderStr)
                    ->select();
		foreach($result as $k=>&$v){
			$new_arr[$k]=$v;
				$sexArr=array(
				0=>'未选择',
				1=>'男',
				2=>'女',
				);
				$new_arr[$k]['sex_id']=$sexArr[$v['sex_id']];				
				$typeArr=array(
						0=>'非空中医院首次注册',
						1=>'普通用户',
						2=>'医生用户',
						3=>'公共账号',
				);
				$new_arr[$k]['user_type_id']=$typeArr[$v['user_type_id']];						
		}

		//dump($new_arr);
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="userLoginInfo.csv"');
		header('Cache-Control: max-age=0');

		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
		
		// 输出Excel列名信息
		$head = array('用户名','性别','角色','注册时间','最后登录');
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
	
	
	
}