<?php
/**
 * 众筹操作模型
 */
namespace Entity\Model;
use Think\Model;
class EntityModel extends  Model{

    /**
     * 返回分类信息
     */
   public  function  getClass(){
       $class=M('entity_insurance_class')->where(array('state'=>1))->order('sort asc,id desc')->select();
       return $class;
   }

    /**返回保险产品信息
     * @param $condition
     * @param $page
     * @param $limit
     * @return mixed
     */
    public  function  getProduct($condition,$page,$limit){
        $class=M('entity_insurance_info')->where($condition)->order('sort asc, create_time desc')->limit("$page,$limit")
                                        ->select();
        return $class;
    }
    /**
    * 返回所有保险公司
    */
    public  function  getCompany(){
        $class=M('entity_company')->where(array('state'=>1))->order('sort asc,create_time desc')->select();
        return $class;
    }

    /**
     * 获取保险产品
     */

    /**
     * 返回诊所信息
     * @param string $order
     * @param string $limit
     * @return mixed
     */
    public  function  getHospital($order=' a.manage_time DESC ',$limit='10',$hid=null,$search=null){
        if($order===0){
            $order=' a.manage_time DESC ';
            $is_order=true;
        }
        if(!is_null($hid)){
            //查询某个
            $hcondition="a.id= $hid";
        }else{
            //查询所有
            $hcondition="a.state=2 ";
        }
        //搜索
        if(!is_null($search)){
            //查询某个
            $sstr=' and (a.clinic_name like "%'.$search.'%"  OR a.clinic_introduce like "%'.$search.'%" )';
        }else{
            //查询所有
            $sstr="";
        }
        $sql="select a.id,a.type,a.manage_time,a.address,a.clinic_name,a.clinic_introduce,a.other_service_content from entity_skyhospital_apply_info as a
              where $hcondition $sstr order by $order limit  $limit
              ";
        $result=M()-> query($sql);
        $type=C('HOS_TYPE');
        $db=M('com_sic_region_info');
        foreach ($result as $k=>$v){
            //地址处理
            $p=$db->find(substr($v['address'],0,6));
            $c=$db->find(substr($v['address'],7,6));
            $a=$db->find(substr($v['address'],14,6));
            $d=substr($v['address'],21);
            $result[$k]['address']=$p['name'].$c['name'].$a['name'].$d;
            //类型处理
            $result[$k]['type']=$type[$v['type']];
            $doc_list=$this->getDocList($v['id']);
            $result[$k]['doc']=$doc_list;
            //总筹集
            $result[$k]['all_money']=$this->count_money($doc_list);

        }
        //按照筹集的钱数排序
        if(isset($is_order)){
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => 'all_money',       //排序字段
            );
            $arrSort = array();
            foreach($result AS $uniqid => $row){
                foreach($row AS $key=>$value){
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            if($sort['direction']){
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $result);
            }

            return $result;
        }
        return $result;
    }

    /**
     * 根据医院返回医生信息
     */
    public  function  getDocList($hid){
        $db=M('com_sic_region_info');
        $sql="SELECT a.doctor_id,b.user_name,b.summary,b.recollection_id,b.duty_id ,b.hospital,b.authentication,
              c.name as recollection_name,d.name as duty_name,e.thumbnail_image_url as img_url,
              f.name  as k_name ,f.sale_num,f.money
              from entity_skyhospital_doctor_info as a
              LEFT JOIN user_base_info as b ON b.user_id=a.doctor_id
              LEFT JOIN dim_recollection_code as c ON c.recollection_id=b.recollection_id
              LEFT JOIN dim_duty_code as d ON d.duty_id=b.duty_id
              LEFT JOIN user_version_info as e ON e.user_id=a.doctor_id
              LEFT JOIN com_sic_service_info as f ON f.id=b.k
              where a.hospital_id=$hid";
        $arr=M()->query($sql);
        foreach($arr as $k=>$v){
            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
            //k服务购买个数
            $count=M()->query('select count(*) as service_num from entity_private_doctor_service_info WHERE doc_id='.$v['doctor_id'].
                      " and service_state=1 and pay_state=1");
            $money=$count[0]['service_num']*$v['money'];
            //详细页
            $arr[$k]['dnmoney']=$money;     //已卖服务
            $arr[$k]['dtmoney']=$v['money']*$v['sale_num']; //总共欲卖

            $arr[$k]['service_num']=$count[0]['service_num'];
            $money = ($money / 10000).'万';  //实际筹集
            $arr[$k]['tmoney']=($v['money']*$v['sale_num']/10000).'万';
            $arr[$k]['nmoney']=$money;    //实际筹得
            //医院地址
            $p=$db->find(substr($v['hospital'],0,6));
            $c=$db->find(substr($v['hospital'],7,6));
            $a=$db->find(substr($v['hospital'],14,6));
            $d=substr($v['hospital'],21);
            $arr[$k]['hospital']=$d;
            $arr[$k]['service_num']=$count[0]['service_num'];

            //是否为直辖市
            $parr=array(110000,120000,310000,500000);
            if(in_array($p['id'],$parr)){
                $arr[$k]['h_area']='['.$p['name'].']';
            }else{
                $arr[$k]['h_area']='['.$p['name'].'-'.$c['name'].']';
            }

        }
        return $arr;

    }

    public  function  count_money($arr){
        $t=0;
        foreach($arr as $k=>$v){
            $t+=$v['nmoney'];
        }

        return $t;
    }

    /**
     * 返回某个文章的评论列表
     */
    public  function  get_coments($aid,$page=0,$limit=5){
        $sql="select a.* ,b.thumbnail_image_url as img_url,c.user_name
from entity_evaluation as a LEFT JOIN user_version_info as b ON b.user_id=a.user_id
LEFT JOIN user_base_info AS c ON c.user_id=a.user_id where a.hospital_id=$aid order by a.create_time desc limit $page,$limit";
        $arr=M()->query($sql);
        foreach($arr as $k=>$v){
            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
        }
        return $arr;
    }
    
    /**
     * 获取附近的诊所
     */
    public  function  getNewHospital($page,$limit='10',$search=null){
    
    	//搜索
    	if(!is_null($search)){
    		//查询某个
    		$sstr=' and (a.clinic_name like "%'.$search.'%"  OR a.clinic_introduce like "%'.$search.'%" )';
    	}else{
    		//查询所有
    		$sstr="";
    	}
    	$hcondition="a.state=2 ";
    	$sql="select a.longitude,a.latitude, a.id,a.type,a.manage_time,a.address,a.clinic_name,a.clinic_introduce,a.other_service_content from entity_skyhospital_apply_info as a
    	where $hcondition $sstr 
    	";
    	$result=M()-> query($sql);
    	$type=C('HOS_TYPE');
    	$db=M('com_sic_region_info');
    	$data=array('ss'=>session('yixin_ss'));
    	$data['type']=1;
    	$myLocation=poster('find_near_skyhospital.php', $data);
    	$ages = array();
        foreach ($result as $k=>$v){
            //地址处理
        	$mete=getDistance($myLocation['result'][1],$myLocation['result'][0],$v['latitude'],$v['longitude']);
        	$result[$k]['mete']=$mete;
            $p=$db->find(substr($v['address'],0,6));
            $c=$db->find(substr($v['address'],7,6));
            $a=$db->find(substr($v['address'],14,6));
            $d=substr($v['address'],21);
            $result[$k]['address']=$p['name'].$c['name'].$a['name'].$d;
            		//类型处理
            		$result[$k]['type']=$type[$v['type']];
            $doc_list=$this->getDocList($v['id']);
            $result[$k]['doc']=$doc_list;
            //总筹集
            $result[$k]['all_money']=$this->count_money($doc_list);
            $ages[]=$mete;
    
         }
           
            //按照筹集的钱数排序
         
            array_multisort($ages,SORT_ASC, $result);
            $result=array_slice($result,$page,$limit);
            return $result;
            	
        }
        
        
        
       
    



}