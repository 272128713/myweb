<?php
namespace Home\Model;
use Think\Model;
class LoginModel extends CommonModel{
	public function getUserPower($roleID){
		if($roleID>1){
			$dbc=M('module_info');
			$d=$dbc->join('power_info on module_info.module_id=power_info.module_id')
					->field('module_info.*')
					->where('power_info.role_id='.$roleID.' and module_info.status>0')
					->order('module_info.position')->select();
			/* $sql='select module_info.module_id,module_info.area_limit FROM module_info INNER JOIN power_info on
			 module_info.module_id=power_info.module_id WHERE ( power_info.role_id='.$se['role_id'].' and
			 		module_info.status>0 )';
			$t=$dbc->query($sql);*/
			foreach ($d as $k=>$v){
				$power[]=$v['module_id'];
				if($v['area_limit']>0){
					$area[]=$v['module_id'];
				}
			}
			$dbc=M('role_remind');
			//检查用户所属的角色是否被设置了提醒监控
			$remind=$dbc->join('remind_info on role_remind.remind_id=remind_info.id')
						->where('role_remind.role_id='.$roleID.' and remind_info.status>0')
						->getField('remind_id,name,controller',true);
		}
		else{
			$dbm=M('module_info');
			$power=$dbm->order('position')->getField('module_id',true);
			$dbr=M('remind_info');
			$remind=$dbr->where('status>0')->getField('id,name,controller',true);
			$area=$dbm->where('status>0 and area_limit>0')->order('position')->getField('module_id',true);
			//$ssss= $dbm->getLastSql();
		}
		$result=array(
				'power'=>$power,
				'area'=>$area,
				'remind'=>$remind
		);
		return $result;
	}
	public function getUserRegionModule($empID,$roleID,$area){
		$dbc=M('com_sic_region_info','',$this->getDB(1));
		$dbr=M('module_role_region');
		$opt=D('radioedit');
		foreach ($area as $key=>$val){
			if($roleID>1){
				//取出当前用户的角色和模块对应的操作区域范围
				$t=$dbr->where('module_id='.$val,' and role_id='.$roleID)->order('province,city')->select();
				//如果没有结果，说明该角色在该功能的范围是当前用户所属的公司的所在地
				if(empty($t)){
					$dbd=M('employee_department');
					$a=$dbd->where('employee_id='.$empID)->getField('department_id',true);
					foreach ($a as $k=>$v){
						$cp=R('department/getCompany',array('id'=>$v));
						$co[]=$cp['province'];
						$ci[]=$cp['city'];
					}
					$cp=array_unique($co);
					$ci=array_unique($ci);
					foreach ($ci as $k=>$v){
						$b=$opt->getOptData('com_sic_region_info',$v,1);
						$b=$b['optData'];
						$cp=array_merge($cp,$b);
					}
					$region[$val]=$dbc->where('status>0 and id in ('.implode(',', $cp).')')
					                  ->order('position')->getField('id',true);
				}
				else{
					//如果有值，说明改用户的所属角色已经被指定了访问范围
					//如果province=9，说明：1、当前用户操作该模块不受地域显示；2、该用户针对该模块在module_role_region表中只有这一条记录。
					if($t['province']==9){
						$region[$val]=9;
					}
					else{
						//否则，需要循环检查每个province的每个city值
						$result=array();
						foreach ($t as $k=>$v){
							$tp=array($v['province']);
							$result=array_merge($result,$tp);
							//如果city=9，说明：当前用户操作该模块在当前province范围内不受city限制
							if($v['city']==9){
								$tp=$opt->getOptData('com_sic_region_info',$v['province'],1);
								$tp=$tp['optData'];
							}
							else{
								$tp=$opt->getOptData('com_sic_region_info',$v['city'],1);
								$tp=$tp['optData'];
							}
							$result=array_merge($result,$tp);
						}
						$region[$val]=$dbc->where('status>0 and id in ('.implode(',', $result).')')
										  ->order('position')->getField('id',true);
					}
				}
			}
			else{
				$region[$val]=9;
			}
		}
		return $region;
	}
}