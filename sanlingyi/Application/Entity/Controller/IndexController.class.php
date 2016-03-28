<?php
namespace  Entity\Controller;
use        Think\Controller;
class IndexController extends CommonController
{
    /**
     * 首页视图
     */
   public  function  index(){
       //判断是否为搜索
       $search=null;
       isset($_POST['svalue']) and trim($_POST['svalue'])!='' ? $search=$_POST['svalue'] : $search=null;;
       isset($_GET['svalue']) and trim($_GET['svalue'])!='' ? $search=urldecode($_GET['svalue']) :$search=$search;
       if(is_null($search)){
           $this->search='';
       }else{
           loop_history($search);
           $this->search=urlencode($search);
       }
       $model=D('Entity');
       $condition=array(
           ' a.sort asc ',      //综合0
           0,                   //达成最多
           ' a.manage_time DESC ', //最新
           );
       if(!isset($_GET['type'])){
           $type=3;
       }else{
           $type=$_GET['type'];
       }
       !isset($_GET['page']) ?$page=0 :$page=$_GET['page'];
       $page=$page*C('PAGE_NUM');
       if($type!=3){
      	 $this->news=$model->getHospital($condition[$type],"$page,".C('PAGE_NUM'),null,$search);
      	
       }else{
       	
       	$this->news=$model->getNewHospital($page,C('PAGE_NUM'),$search);
       }
       $this->nums=count($this->news);
	   $this->assign('type',$type);
       if(isset($_GET['ajax'])){
            $this->display('content');
       }else {
           $this->display();
       }

   }

    /**
     * 验证订单
     */
     public  function  checkOrder(){
       $order=M('entity_insurance_order_info')->where(array('order_num'=>$_POST['order']))->find();
       if(!$order){
               echo '订单号不正确';
       }else{
           if($order['status']==1){
               echo '订单号无效';
           }else{
               M('entity_insurance_order_info')->where(array('order_num'=>$_POST['order']))->save(array('status'=>1));
               $now=date('Y-m-d H:i:s');
               $data=array();
               $data['doc_id']=$_POST['dic'];
               $data['entity_id']=$data['entity_id']=$_POST['hid'];              
               $data['user_id']=session('yixin_user');
               $data['service_state']=1;
               $data['pay_state']=2;
               $data['number_of_policy']=$_POST['order'];
               $data['createDate']=$now;
               $data['start_time']=$now;
               $na_members  = time();
               $na_members=strtotime("+1 years",$na_members);  //
               $data['end_time']=date('Y-m-d H:i:s',$na_members);
               if(M('entity_private_doctor_service_info')->add($data)){
                   echo 1;
               }else{
                   echo 0;
               }
           }
       }
    }
    /**
     * 选择保险
     */

    public  function  cate(){
        $arr=array('state'=>1);
        if(isset($_GET['cid'])){
            $arr['class_id']=$_GET['cid'];
        }
        if(isset($_GET['coid'])){
            $arr['company_id']=$_GET['coid'];
        }

        $model=D('Entity');
        $cate=  $model->getClass();
        $this->cate=array_slice($cate,0,C('DEFAUL_NUM'));
        $this->cates=$cate;
        $this->com=  $model->getCompany();
        !isset($_GET['page']) ?$page=0 :$page=$_GET['page'];
        $page=$page*C('PAGE_NUM');
        $this->pt=  $model->getProduct($arr,$page,C('PAGE_NUM'));
        $this->nums=count($this->pt);
        if(isset($_GET['ajax'])){
            $this->display('cate_block');
        }else {
            $this->display();
        }
    }
    /**
     * 填写订单号
     */
    public  function  optipon(){
          $this->display();
    }

    /**
     * 详细页
     */
    public  function  detail(){
        $model=D('Entity');
        if(isset($_GET['uid'])){
        	$code=M('entity_skyhospital_doctor_info')->WHERE(array('doctor_id'=>$_GET['uid']))->getField('hospital_id');
        	if($code){
        		$_GET['id']=$code;
        	}else{
        		$_GET['id']=0;
        	}
        }
        $a=$model->getHospital('a.manage_time DESC',1,$_GET['id']);
        $this->v=$a[0];

        //是否收藏
        $a=M('user_favorite_info',null)->where(array('src_user_id'=>$_GET['id'],'type'=>7,'user_id'=> session('yixin_user')))->find();
        if($a){
            $this->is_save=1;
        }else{
            $this->is_save=0;
        }
        $this->evalute=$model->get_coments($_GET['id'],0,C('DEFAUL_EVALUTE'));
        //显示第一条评价
        $this->display();
    }

    /**
     * 选择医生
     */
    public  function  part(){
        $model=D('Entity');
        $a=$model->getHospital('a.manage_time DESC',1,$_GET['id']);
        $this->v=$a[0];
        $this->display();
    }

    /**
     * 确定购买
     */
    public  function  sure(){
        $model=D('Entity');
        if(isset($_GET['uid'])){
        	$code=M('entity_skyhospital_doctor_info')->WHERE(array('doctor_id'=>$_GET['uid']))->getField('hospital_id');
        	if($code){
        		$_GET['id']=$code;
        	}else{
        		$_GET['id']=0;
        	}
        }
        $h_belong_doctor = M('h_assistant_belong_doctor')->WHERE(array('doc_id'=>$_GET['uid']))->getField('user_id');
      
        if($h_belong_doctor){
        	$h_belong_doctor = M('user_base_info')->WHERE(array('user_id'=>$h_belong_doctor))->getField('user_name');
        }else{
        	$h_belong_doctor = '';
        }
        $a=$model->getHospital('a.manage_time DESC',1,$_GET['id']);
    
        $this->v=$a[0];
        $this->h=$h_belong_doctor;
        $this->display();
    }

    /**
     * 搜索显示视图
     */
    public  function  search(){
        $this->hs=cookie('history');
        $this->display();
    }
    /**
     * 删除历史记录
     */
    public  function  delete(){
        cookie('history',null);
        echo 1;
    }

	public function	  k_xy(){
		$url= 'http://210.14.72.58/yixin/skyHospital/innerweb/kprotocol_doc_verify.html';
		$get_url = file_get_contents($url);
		$this->assign('get_url',$get_url);
		$this->display();
	}
	
	

}