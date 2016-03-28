<?php
/**
 * 医院信息审核
 * 
 */

namespace Home\Controller;
use Think\Controller;
class HospitalApplyController extends TemplateController{
	public function index(){
		
	}	
	
	public function HospitalApplyList(){
		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		
		
		$dbc=M('k_examine','',$this->getDbLink(1));

		$condition=$pageList['condition'];
		if($condition != ''){			
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
					$end=$arr[2]?$arr[2]:date('Y-m-d',time());
					$start=$arr[1]?$arr[1]:0;
					$start=$start.' 00:00:00';
					$end=$end.' 23:59:59';
					$s = ' and k_examine.examine_time >"'.$start.'" and k_examine.examine_time < "'.$end.'"';				
			}			
		}
		//dump($condition);
		if($s==''){
			$end=date('Y-m-d H:i:m',time());
			$start=date('Y-m-d H:i:m',strtotime('today'));
			$s = ' and k_examine.examine_time >"'.$start.'" and k_examine.examine_time < "'.$end.'"';
		}
		
		$result=$dbc->field('hospital as name,hospital as province,hospital as city,hospital as section')
					->join('JOIN user_base_info ON k_examine.docId = user_base_info.user_id')					
					->where('k_examine.state=5'.$s)				
					->order('k_examine.examine_time desc')
					->select();
		//echo $dbc->getLastSql();
		$new=array();
		foreach($result as $k=> &$v){
			$hArr=explode('-', $v['name']);
			$f=M('hospital_base_info_sys','',$this->getDbLink(1))
				->where(array('province'=>$hArr[0],'city'=>$hArr[1],'section'=>$hArr[2],'name'=>$hArr[3]))
				->find();			
			if(!$f){
				$new[$k]['name']=$hArr[3];
				$new[$k]['province']=M('hat_province','',$this->getDbLink(1))->where('provinceID='.$hArr[0])->getField('province');
				$new[$k]['city']=M('hat_city','',$this->getDbLink(1))->where('cityID='.$hArr[1])->getField('city');
				$new[$k]['section']=M('hat_area','',$this->getDbLink(1))->where('areaID='.$hArr[2])->getField('area');								
			}			
		}			
		$data=$this->array_unique_fb($new);
		
		$count=count($data);
		$this->tplListItem(session('SESS_optModuleID'),array_values($data),$count);	
	}
	//按条件导出员工审核列表
	public function getExcel(){

		$ListData=session('SESS_ListItemData');
		$pageList=$ListData[session('SESS_optModuleID')];
		$pageList=$pageList['parameter'];
		
		
		
		
		
		$dbc=M('k_examine','',$this->getDbLink(1));
		
		$condition=$pageList['condition'];
		if($condition != ''){
			if(strstr($condition, 'form,')){//复杂搜索
				$arr=explode(',', $condition);
				$end=$arr[2]?$arr[2]:date('Y-m-d',time());
				$start=$arr[1]?$arr[1]:0;
				$start=$start.' 00:00:00';
				$end=$end.' 23:59:59';
				$s = ' and k_examine.examine_time >"'.$start.'" and k_examine.examine_time < "'.$end.'"';
			}
		}
		//dump($condition);
		if($s==''){
			$end=date('Y-m-d H:i:m',time());
			$start=date('Y-m-d H:i:m',strtotime('today'));
			$s = ' and k_examine.examine_time >"'.$start.'" and k_examine.examine_time < "'.$end.'"';
		}
		
		$result=$dbc->field('hospital as name,hospital as province,hospital as city,hospital as section')
		->join('JOIN user_base_info ON k_examine.docId = user_base_info.user_id')
		->where('k_examine.state=5'.$s)
		->order('k_examine.examine_time desc')
		->select();
		//echo $dbc->getLastSql();
		$new=array();
		foreach($result as $k=> &$v){
			$hArr=explode('-', $v['name']);
			$f=M('hospital_base_info_sys','',$this->getDbLink(1))
			->where(array('province'=>$hArr[0],'city'=>$hArr[1],'section'=>$hArr[2],'name'=>$hArr[3]))
			->find();
			if(!$f){
				$new[$k]['name']=$hArr[3];
				$new[$k]['province']=M('hat_province','',$this->getDbLink(1))->where('provinceID='.$hArr[0])->getField('province');
				$new[$k]['city']=M('hat_city','',$this->getDbLink(1))->where('cityID='.$hArr[1])->getField('city');
				$new[$k]['section']=M('hat_area','',$this->getDbLink(1))->where('areaID='.$hArr[2])->getField('area');
				
			}
		}		
	
		$row=$this->array_unique_fb($new);
		
		// 输出Excel文件头，可把user.csv换成你要的文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="待审核医院信息.csv"');
		header('Cache-Control: max-age=0');
	
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		$fp = fopen('php://output', 'a');
	
		// 输出Excel列名信息
		$head = array('医院名','省','市','区');
		foreach ($head as $i => $v) {
			// CSV的Excel支持GBK编码，一定要转换，否则乱码
			$head[$i] = iconv('utf-8', 'gbk', $v);
		}
	
		// 将数据通过fputcsv写到文件句柄
		fputcsv($fp, $head);
		foreach ($row as $key => $val) {
			foreach($val as $k=>$v){
				$data[$k] = iconv('utf-8', 'gbk', $v);
			}
			fputcsv($fp, $data);
		}
		fputcsv($fp, array(iconv('utf-8', 'gbk', '筛选时间'.$start.'到'.$end),));
	}
	
	//二维数组去掉重复值
	function array_unique_fb($array2D)
	{
		foreach ($array2D as $v)
		{
			$v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			$temp[] = $v;
		}
	
		$temp = array_unique($temp);    //去掉重复的字符串,也就是重复的一维数组
		foreach ($temp as $k => $v)
		{
			$temp[$k] = explode(",",$v);   //再将拆开的数组重新组装
		}
		return $temp;
	}
	
}