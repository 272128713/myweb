<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/4
 * Time: 15:45
 */
namespace  Entity\Controller;
use        Think\Controller;
class MyChatController extends CommonController{
        public  function  saveFavorite(){
            if(!IS_AJAX){
                echo 0;
                die();
            }
            $id=$_POST['id'];      //诊所id
            $is_save=$_POST['is_save'];
            $db=M('user_favorite_info',null);
            if($is_save==1){
                echo 0;
                die();
            }else {
                $a = $db->where(array('src_user_id' => $id, 'type' => 7, 'user_id' => session('yixin_user')))->find();
                if ($a) {
                    echo 2;
                    die();
                }
                //查询次文章相关信息
                $article = M('entity_skyhospital_apply_info')->where(array('id' => $id))->find();
                $data = array();
                $data['user_id'] = session('yixin_user');
                $data['type'] = 7;
                $data['src_date'] = $article['manage_time'];
                $data['src_user_id'] = $id;
                $data['favorite_text'] = $article['clinic_name'];
                //$data['thumbnail_image_url']=$img_url;

                //进行保存
                if ($db->add($data)) {
                    echo 1;
                } else {
                    echo 0;
                }
            }
        }

    public  function  saveOrder(){
        if(!IS_AJAX){
            echo 0;
            die();
        }
        if(!session('?yixin_user')){
        	echo 0;
        	die();
        }
        $now=date('Y-m-d H:i:s');
        $data=array();
        $data['doc_id']=$_POST['dic'];
        $data['entity_id']=$_POST['hid'];
        $data['user_id']=session('yixin_user');
        $data['service_state']=0;
        $data['pay_state']=1;
        $data['number_of_policy']='';
        $data['createDate']=$now;
       
        $user_info=M('user_base_info',null)->where(array('user_id'=>$data['user_id']))->find();
        if($user_info['sex_id']==1){
        	$user['sex']='男';
        }elseif($user_info['sex_id']==2){
        	$user['sex']='女';
        }else{
        	$user['sex']='未选择';
        }
        $user['age']=date('Y')-$user_info['birthday'];
        $user['doc_name']=M('user_base_info',null)->where(array('user_id'=>$data['doc_id']))->getField('user_name');
        $k_id=M('entity_private_doctor_service_info')->add($data);
        if($k_id){
        	//增加操作记录
        	$odata=array();
        	$odata['user_id']=$data['user_id'];
        	$odata['k_id']=$k_id;
        	$odata['type']=11;
        	$odata['content']='预定众筹健康服务';
        	$odata['createDate']=date('Y-m-d H:i:s');
        	
        	M('h_k_user_handle_info')->add($odata);
        	//发送mec消息
        	//发送者电话
        	$mobile=M('user_base_info',null)->where(array('user_id'=>$data['user_id']))->getField('mobile');        	
        	//健康代表id
        	$hid=M('h_assistant_belong_doctor')->where(array('doc_id'=>$data['doc_id']))->getField('user_id');        	
        	if($hid){ 
        		//给健康代表的消息       		
        		$accepters = A('Consult/SelfConsult')->getUserInfoByUid($hid);
			    $messageid=$data['user_id'].floor(microtime(true)*1000).rand(10,99); //格式为：uid+10位秒级时间戳+2位随机数
		        $msgObj = array(
			        "id" => $messageid,
			        "type" => "MMS",
			        "mime" => "text/plain",
			  		"srcm" => $mobile,
			        "src" => $data['user_id'],
			        "text" => rawurlencode(date('Y年m月d日',time()).'，用户'.$user_info['user_name'].'，'.$user['sex'].'，'.$user['age'].'岁，电话'.$user_info['mobile'].',投资'. $user['doc_name'].'医生私人医生服务，请您与用户取得联系'),
			        "time" => time());
        		
        		$mecManager=new \Consult\Model\MecManagerModel($data['user_id'],$msgObj,$accepters);
        		$mecManager->sendMessage();        
        		\Think\Log::record('健康代表'.json_encode($accepters));
        		\Think\Log::record('健康代表消息'.json_encode($msgObj));        		
        	}
        	//给医生发送消息
        	$doc_accepters = A('Consult/SelfConsult')->getUserInfoByUid($data['doc_id']);
        	$messageid=$data['user_id'].floor(microtime(true)*1000).rand(10,99); //格式为：uid+10位秒级时间戳+2位随机数
        	$doc_msgObj = array(
        			"id" => $messageid,
        			"type" => "MMS",
        			"mime" => "text/plain",
        			"srcm" => $mobile,
        			"src" => $data['user_id'],
        			"text" => rawurlencode(date('Y年m月d日',time()).'，用户'.$user_info['user_name'].'，'.$user['sex'].'，'.$user['age'].'岁，电话'.$user_info['mobile'].',已投资您的私人医生服务，稍后健康代表会与用户取得联系'),
        			"time" => time());
        	
        	$mecManager=new \Consult\Model\MecManagerModel($data['user_id'],$doc_msgObj,$doc_accepters);
        	$mecManager->sendMessage();
        	\Think\Log::record('医生'.json_encode($doc_accepters));
        	\Think\Log::record('医生消息'.json_encode($doc_msgObj));

            echo 1;
        }else{
            echo 0;
        }
     




    }
    
    
    public  function  deleteOrder(){
    	if(!IS_AJAX){
    		echo 0;
    		die();
    	}
    	$oid=I('oid',0);
    	if(M('entity_private_doctor_service_info')->where(array('id'=>$oid))->delete()){
    		echo 1;
    	}else{
    		echo 0;
    	}
    	 
    
    
    
    
    }
}