<?php
/**
 * 普通用户管理
 * 
 */

namespace Home\Controller;
use Think\Controller;
class UserManageController extends TemplateController{
	public function index(){
		
	}	
	
	public function UserManageList($id=0){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('user_base_info','',$this->getDbLink(1));
		
		
		
		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		if(is_array($reg)){
			$r=implode(',',$reg);
			//$w='district in ('.$r.')';
			$s['com_dic_doctor_info.district']  = array('in',$r);
		}
		else{
			if($reg==9){
				//$w='true';
			}
			else{
				//$s['com_dic_user_info.district']  = 0;
			}
		}
		if($id>0){
			$rt=$this->getRegion(4,$id);
			if($rt['level']==1){
				$sql="select id from com_sic_region_info where parentId in (select id from com_sic_region_info where parentId=".$id.") ";				
				$s .=" and user_base_info.live_place in (".$sql.") ";				
			}
			elseif($rt['level']==2){
				$sql="select id from com_sic_region_info where parentId=".$id."";				
				$s .=" and user_base_info.live_place in (".$sql.") ";	
			}
			else{
				$s .=" and user_base_info.live_place = $id ";
			}
		}
		
		

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s .= ' and reg_date >"'.$start.'" and reg_date < "'.$end.'"';	
					if($arr[3]!=''){
						$s.=" and user_base_info.user_name LIKE '%$arr[3]%'";					
					}			
					if($arr[4]!=''){
						$s.=" and user_base_info.mobile LIKE '%$arr[4]%'";
					}					
			}			
		}	
		$where='user_base_info.user_type_id=1 '.$s;
		//dump($condition);
		$count=$dbc->where($where)
					->count();
		//echo $dbc->getLastSql();
		$result=$dbc->field('user_name,mobile,sex_id,reg_date,live_province_id,live_place')
					->where($where)
					->limit($pageList['start'],$pageList['limit'])
					->order('reg_date desc')
					->select();
		//echo $dbc->getLastSql();
		foreach($result as &$v){		
			//dump($v);	
			$sexArr=array(
				0=>'未选择',
				1=>'男',
				2=>'女',
			);
			$v['sex_id']=$sexArr[$v['sex_id']];			
			$v['live_province_id']=M('hat_province')->where('provinceID='.$v['live_province_id'])->getField('province');
			
			$areaInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$v['live_place'])->find();

			$cityInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$areaInfo['parentId'])->find();
			
			$provinceInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$cityInfo['parentId'])->find();
			
			
			$v['live_place']=$provinceInfo['name'].$cityInfo['name'].$areaInfo['name'];					
		}				
		$_SESSION['region'][session('SESS_optModuleID')]=$id;
		$this->tplListItem(session('SESS_optModuleID'),$result,$count);	
	}	
	
	
	//按条件导出员工审核列表
	public function getExcel(){
		ini_set("memory_limit","-1");
			$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];

		$dbc=M('user_base_info','',$this->getDbLink(1));
		
		$id=$_SESSION['region'][session('SESS_optModuleID')];
		
		//分地区条件
		$reg=$this->getRegionCatch(session('SESS_optModuleID'));
		if(is_array($reg)){
			$r=implode(',',$reg);
			//$w='district in ('.$r.')';
			$s['com_dic_doctor_info.district']  = array('in',$r);
		}
		else{
			if($reg==9){
				//$w='true';
			}
			else{
				//$s['com_dic_user_info.district']  = 0;
			}
		}
		if($id>0){
			$rt=$this->getRegion(4,$id);
			if($rt['level']==1){
				$sql="select id from com_sic_region_info where parentId in (select id from com_sic_region_info where parentId=".$id.") ";				
				$s .=" and user_base_info.live_place in (".$sql.") ";				
			}
			elseif($rt['level']==2){
				$sql="select id from com_sic_region_info where parentId=".$id."";				
				$s .=" and user_base_info.live_place in (".$sql.") ";	
			}
			else{
				$s .=" and user_base_info.live_place = $id ";
			}
		}
		
		

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s .= ' and reg_date >"'.$start.'" and reg_date < "'.$end.'"';	
					if($arr[3]!=''){
						$s.=" and user_base_info.user_name LIKE '%$arr[3]%'";					
					}			
					if($arr[4]!=''){
						$s.=" and user_base_info.mobile LIKE '%$arr[4]%'";
					}					
			}			
		}	
		$where='user_base_info.user_type_id=1 '.$s;
		//dump($condition);
		$count=$dbc->where($where)
					->count();
		//echo $dbc->getLastSql();
		$result=$dbc->field('user_name,mobile,sex_id,reg_date,live_place')
					->where($where)					
					->order('reg_date desc')
					->select();
		//echo $dbc->getLastSql();
		foreach($result as $k=>$v){		
			$new_arr[$k]=$v;
			$sexArr=array(
				0=>'未选择',
				1=>'男',
				2=>'女',
			);
			$new_arr[$k]['sex_id']=$sexArr[$v['sex_id']];	
			
			$areaInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$v['live_place'])->find();
			$cityInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$areaInfo['parentId'])->find();			
			$provinceInfo=M('com_sic_region_info','',$this->getDbLink(1))->where('id='.$cityInfo['parentId'])->find();
			
			$new_arr[$k]['live_place']=$provinceInfo['name'].$cityInfo['name'].$areaInfo['name'];
						
		}
		
		
	
		//dump($new_arr);
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="userList.csv"');
		header('Cache-Control: max-age=0');
	
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
	
		// 输出Excel列名信息
		$head = array('用户名','手机号','性别','注册时间','居住地');
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