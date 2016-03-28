<?php
namespace Home\Controller;
use Think\Controller;
class TemplateController extends CommonController{
	/**
	 * 生成列表模板
	 * @param $moduleID  调用列表页面的按钮的ID
	 * @param $objectID  读取列表数据时的选择条件
	 */
	public function tplList($moduleID,$objectID=''){
		//判断session中有没有当前模块的列表信息，如果没有，创建，否则直接跳过
		$listData=session('SESS_ListItemData');				
		if(empty($listData[$moduleID])){
			$dbc=M('module_item');
			//获得当前操作模块的列表配置信息
			$d=$dbc->where('module_id='.$moduleID.' and status>0')->order('position')->select();
			foreach ($d as $k=>$v){
				$title[$k]['name']=$v['item_name'];
				$title[$k]['width']=$v['item_width'];
				$title[$k]['center']=$v['isCenter'];
				$title[$k]['order']=$v['isOrder'];
				$item[$k]['width']=$v['item_width'];
				$item[$k]['operate']=$v['operate'];
				$item[$k]['field']=$v['item_field'];
				$item[$k]['center']=$v['isCenter'];
				$item[$k]['itemType']=0;
			}
			
			$title=is_array($title)?$title:array(array('name'=>'未定义项目','width'=>0));
			//获得当前操作模块的所有允许显示在列表中的子功能
			$dbm=M('module_info');
			$t=$dbm->find($moduleID);
			$m=$dbm->where('status>0 and parentId='.$moduleID.' and show_place=2')->order('position')->select();
			foreach ($m as $k=>$v){
				$operate[$k]['id']=$v['module_id'];
				$operate[$k]['name']=$v['module_name'];
				$operate[$k]['width']=strlen($v['module_name'])*6;
				$operate[$k]['en']=$v['en'];
				$operate[$k]['optType']=$v['operate_type'];
				$operate[$k]['center']=1;
				$operate[$k]['itemType']=1;
				$porperty=$this->formatModuleProperty($v, $t['module_name']);
				$operate[$k]['property']=$porperty;
			}
			$operate=is_array($operate)?$operate:array(array('name'=>'未定义操作','width'=>100));
			$title=array_merge($title,$operate);
			$item=is_array($item)?array_merge($item,$operate):$operate;						
			//如果当前模块的list_limit>0，说明需要使用分页模版，初始化分页的设置信息，记录在session中，否则，session变量为0
			if($t['list_limit']>0){
				$parameter=array(
					'nowPage'=>1,
					'start'=>0,
					'limit'=>$t['list_limit'],
					'condition'=>'',
				);
			}
			else{
				$parameter=0;
			}
			$listData[$moduleID]=array(
					'title'		=> $title,
					'item'		=> $item,
					'parameter'	=> $parameter,
					'controller'=> $t['en'].'List',
			);
			session('SESS_ListItemData',$listData);
		}
		$pageEnable=$listData[$moduleID]['parameter']==0?0:1;
		$this->assign('listTitle',$listData[$moduleID]['title']);
		$param=array(
			'moduleID'	=> $moduleID,
			'objectID'	=> $objectID
		);		
		$this->assign('param',$param);
		$this->assign('listController',$listData[$moduleID]['controller']);
		$this->assign('pageEnable',$pageEnable);
		//$this->display('template/listTitle');
	}
	/**
	 * 获取列表的数据
	 * @param int $moduleID 调用列表信息的模块id
	 * @param array $data 本次计算的列表数据
	 * @param int $count  如果需要分页，传入全部记录的总数
	 */
	public function tplListItem($moduleID,$data,$count=''){
		$listData=session('SESS_ListItemData');
		$listData=$listData[$moduleID];
		if(is_array($listData['parameter'])){
			$pageData=$this->pages($moduleID,$count);
			$this->assign('page',$pageData);
		}
		$opt=A(session('SESS_optModuleEN'));
		foreach($data as $k=>$v){
			$itemData[$k]['id']=$v['id'];
			//$v=array_values($v);
			foreach($listData['item'] as $k1=>$v1){			
				$itemData[$k]['value'][$k1]['width']=$v1['width'];
				$itemData[$k]['value'][$k1]['center']=$v1['center'];
				$itemData[$k]['value'][$k1]['itemType']=$v1['itemType'];
				if($v1['itemType']==0){
					$v_tmp=array_values($v);
					$itemData[$k]['value'][$k1]['name']=$v_tmp[$k1];
				}
				else{
					$function=$v1['en'].'ItemOpt';
					
					$result=$opt->$function($v);
					
					if(is_array($result)){
						$itemData[$k]['value'][$k1]['pic']=$result['pic'];
						$itemData[$k]['value'][$k1]['property']=$result['property'];
					}
					else{
						$itemData[$k]['value'][$k1]['pic']=$result;
						$itemData[$k]['value'][$k1]['property']=$v1['property'];
					}					
				}				
			}
			$itemData[count($itemData)-1]['value'][$k1]['width']=$itemData[count($itemData)-1]['value'][$k1]['width']-3;
		}	
		$this->assign('itemList',$itemData);
		$this->display('Template/listItem'); 
	}
	/**
	 * 分析生成树形菜单的controller地址
	 * @param number $moduleID
	 * @param string $objectID
	 */
	public function directAnalyse($moduleID,$objectID=''){
		$dbc=M('module_info');
		$t=$dbc->find($moduleID);
		$direct='tree/'.$t['en'].'Tree';
		$param=array(
			'moduleID' =>$moduleID,
			'objectID' =>$objectID,
		);
		R($direct,$param);
	}
	/**
	 * 生成树形菜单模板
	 * @param string $title 树形菜单显示的标题
	 * @param array $data 菜单数据，数据中必须由下标为id，name，parentId的值
	 * @param int  $type  树形菜单的类型。	
	 * 						0：不可选择，只能浏览；
	 * 						1：单选；
	 * 						2：单选、有条件的控制单选框显示；
	 * 						3：复选、子父节点脱节；
	 * 						4：复选、子父节点联动；
	 * 						5：复选框单选；
	 * @param int $target [可选]点击菜单后页面跳转的类型
	 * 						0：不跳转
	 * 						1：父框架optFrame跳转，用于tree+操作页面
	 * 						2：父框架子页面的optFrame跳转，用于tree+列表页面
	 * @param boolean $hidden [可选]是否需要将选中的菜单信息记录在隐藏变量中
	 * @param boolean $back [可选]方法返回类型的设置，true=显示页面，false=只渲染，不返回
	 * @param string $direct [可选] 点击树形菜单之后，主框架页面执行的操作，只对（type=1或者6）有效
	 * @param array $parameter  [可选] 点击树形菜单以后，除了菜单本身传递的参数之外，需要额外传递的参数。
	 * 					                 该参数的下标是$direct指定的页面接收的参数名称，该参数的值则是传递的值。
	 *						       例如：$parameter=array('objectID'=>'123456');其中，objectID是$direct制定操作的
	 *						       接收参数名称，必须一致。
	 *@return NULL 直接生成树形菜单页面 |渲染树形菜单页面
	 */
	public function tplTree($title,$data,$type,$target=0,$hidden=false,$back=false,$direct='',$parameter=''){
		if($target>0){
			if(!empty($direct)){
				$optUrl=session('SESS_optModuleEN').'/'.$direct;
				$treeData['optUrl']=$optUrl;
				if(is_array($parameter)){
					$treeData['param']=$parameter;
				}
			}
		}
		//根据tree_type设置相应的tree的js脚本
		switch($type){
			case 0:
				$jsTree='viewTree';
				break;
			case 1:
				$jsTree='radioTree';			
				break;
			case 2:
				$jsTree='radioView';				
				break;
			case 3:
				$jsTree='batchTree';
				break;
			case 4:
				$jsTree='checkTree';
				break;
			default:
				$jsTree='';
		}
		$treeData['title']=$title;
		$treeItem=$data;
		$treeData['target']=$target;
		$treeData['hidden']=$hidden;
		$treeData['jsTree']=$jsTree;
		$this->assign('data',$treeData);
		$this->assign('treeList',$treeItem);
		if($back){
			$this->display('Template/treeList');
		}
	}
	
	/**
	 * 获得公用模版bar，button，batch（有复选框的列表页面中，批量处理的按钮）页面的按钮信息并生成模版页面
	 * @param string|int $module  当前显示页面的来源功能模块的ID或en
	 * @param int $place 显示的位置，0：form表单；1：bar；2：batch
	 * @param int $objectID 当前正在操作的信息ID
	 * @param int $page 是否需要获取页面上的参数
	 */
	public function getPlaceModule($module, $place, $objectID='', $page=0){
		$dbc=M('module_info');
		if(is_numeric($module)){
			$mData=$dbc->find($module);
		}
		else{
			$mData=$dbc->where("en='".$module."'")->find();
		}
		$order=$place==1?' desc':'';
		$p=implode(',',session('SESS_optPower'));
		$moduleList=$dbc->where('status>0 and show_place='.$place.' and parentId='.$mData['module_id'].' and module_id in ('.$p.')')->order('position'.$order)->select();
		foreach($moduleList as $k=>$v){
			$data[$k]=array(
					'id' => $v['module_id'],
					'name' => $v['module_name'],
					'en' => $v['en'],
					'operateType' => $v['operate_type'],
					'property'	=> $this->formatModuleProperty($v, $mData['module_name'])
			);
		}
		if($place==1){
			$pData=$dbc->find($mData['parentId']);
			//这里设置的是“您的位置：××× -> ×××”
			$barTitle=$pData['module_name'].'&nbsp;->&nbsp;'.$mData['module_name'];
			$this->assign('barInfo',$barTitle);
			$volist='barList';
		}
		else{
			$this->assign('page',$page);
			$volist='buttonList';
		}
		$this->assign('objectID',$objectID);
		$this->assign($volist,$data);
	}
}
?>