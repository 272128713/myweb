<?php
namespace  Consult\Controller;
use        Think\Controller;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/13
 * Time: 14:28
 */
class SelfConsultController extends CommonController{
    //我的问题首页
    public  function  index(){
        $uid=session('yixin_user');
        //排序方式
        if(isset($_POST['type'])){
            $_GET['type']= $_POST['type'];
        }
        if(!isset($_GET['type'])){
            $_GET['type']=2;
        }
        //页码
        $page=$_POST['page'];
        if($_GET['type']==1) {
            $ask = D('Problem')->get_llist($page,null,1,null,$uid);
        }else if ($_GET['type']==2){
            $ask = D('Problem')->get_llist($page,null,0,null,$uid);
        }
        //输出方式
        if(isset($_POST['ajax'])){
            if(count($ask)<1){
                die();
            }
            echo get_lists_ask($ask);
        }else {
            $this->ask = $ask;
            $this->nums=count($ask);
            $this->display() ;
        }

    }

    /**
     * 用户追问
     */
    public  function  question(){
        $data=array();
        $data['answer_id']=$_POST['aid'];
        $data['user_id']=session('yixin_user');
        $data['createDate']=date('Y-m-d H:i:s');
        $data['content']=mb_substr(htmlspecialchars($_POST['value']),0,255,'utf8');
        if(M('question_to_answer_info')->add($data)){
            M('doctor_answer_info')->where(array('answer_id'=>$_POST['aid']))->save(array('is_answer'=>1));
            $value=$data['content'];
            $str='<div class="optiont">患者追问<span class="mui-pull-right">';
            $str.=date('Y-m-d').'</span><p  style="margin-top:0.7em;margin-bottom: 0;">';
            $str.=$value.'</p></div>';
            //发送mec消息
            //发送者电话
            $mobile=M('user_base_info',null)->where(array('user_id'=>$data['user_id']))->getField('mobile');
            //医生id
            $docid=M('doctor_answer_info')->where(array('answer_id'=>$data['answer_id']))->getField('doctor_id');
            $accepters = $this->getUserInfoByUid($docid);
            \Think\Log::record('地址'.json_encode($accepters));
            //loading message
            //问题id
            $qid=M('doctor_answer_info')->where(array('answer_id'=>$data['answer_id']))->getField('question_id');
            $msgObj = array("type" => "CNM",
                "ot"   => "1", //it 操作类型 1表示邀请 2表示申请 加注释
                "src"  => $data['user_id'],
                "srcm" => $mobile,
                'url'=>C('DOMAIN').U('Index/detail',array('aid'=>$qid,'areply'=>'1')),
                "time" =>time());

            \Think\Log::record('消息'.json_encode($msgObj));
            $mecManager=new \Consult\Model\MecManagerModel($data['user_id'],$msgObj,$accepters);
            $mecManager->sendMessage();
            echo $str;
        }else{
            echo 0;
        }
    }
    /**
     * 医生回复用户追问
     */
    public  function  dquestion(){
        //本医生回复的
        $as_id=M('doctor_answer_info')->where(array('doctor_id'=>session('yixin_user'),'question_id'=>$_POST['aid']))->getField('answer_id');
        $data=array();
        $data['answer_id']=$as_id;
        $data['user_id']=session('yixin_user');
        $data['createDate']=date('Y-m-d H:i:s');
        $data['content']=mb_substr(htmlspecialchars($_POST['value']),0,255,'utf8');
        if(M('question_to_answer_info')->add($data)){
            M('doctor_answer_info')->where(array('answer_id'=>$as_id))->save(array('is_answer'=>0));
            $value=$data['content'];
            $str='<div class="optiont">医生回复<span class="mui-pull-right">';
            $str.=date('Y-m-d').'</span><p  style="margin-top:0.7em;margin-bottom: 0;">';
            $str.=$value.'</p></div>';
            echo $str;

        }else{
            echo 0;
        }
    }
    /**
     * 采纳
     */
    public  function  accept(){
        $qid=$_POST['pid'];
        $aid=$_POST['aid'];
        $qdb=M('user_question_info');
        $is_end=$qdb->where(array('question_id'=>$qid,'is_end'=>1))->find();
        if($is_end){
            die(0);
        }else{
            $qdb->where(array('question_id'=>$qid))->save(array('is_end'=>1));
            M('doctor_answer_info')->where(array('answer_id'=>$aid))->save(array('is_best'=>1));
            echo 1;
        }
    }

    /**
     * 医生回复
     */
    public  function  replyQ(){
        $qid=$_POST['aid'];
        if(is_asked($qid)){
            echo 0;
        }else{
            $data=array();
            $data['question_id']=$qid;
            $data['doctor_id']=session('yixin_user');
            $data['creatDate']=date('Y-m-d H:i:s');
            $data['content']=mb_substr(htmlspecialchars($_POST['value']),0,500,'utf8');
            $data['is_best']=0;
            $data['is_answer']=0;
            $data['up_num']=0;
            $id=M('doctor_answer_info')->add($data);
            if($id){
            	$status=M('user_question_info')->where(array('question_id'=>$qid))->setInc('answer_num',1);
               $r=D('Problem')->getOneReply($id);
                $str='<li class="mui-table-view-cell uli" >
                        <img src="'.$r["img_url"].'" class="headimg left"/>
                        <div class="headp"><p><span class="hh3">'.$r["user_name"].'</span><span class="pp3">'.$r["recollection"].'&nbsp;&nbsp;&nbsp;'.$r["duty"].'</span></p>
                            <p class="pp12">'.$r["hospital"].'</p>
                        </div>

                        <div class="clear option">
                            <div class="optiont">医生建议：<span class="mui-pull-right">'.date("Y-m-d").'</span>
                                <p  style="margin-top:0.7em;margin-bottom: 0;">
                                    '.$r["content"].'                               </p>
                            </div>
                            <!--追问-->
                                                        <p aid ="3" class="mui-pull-right iconfont icon-zan blue jhbtn up_bom"><span> (<span class="up_num">0</span>)</span></p>

                        </div>
                    </li>';
                echo $str;

            }else{
                echo 0;
            }

        }
    }

    /*
	 *根据UID返回用户对应的相关信息
	*/
    public function getUserInfoByUid($uidlist){
        $Model = M();
        $condition = " a.user_id in (".$uidlist.")";
        $sql = "SELECT a.user_id,a.client_type,a.mid,a.push_service_type,a.mec_ip,a.mec_port,a.lps_ip,a.lps_port,b.mobile FROM user_session_info as a, user_base_info as b where a.user_id=b.user_id and $condition";
        return $Model->query($sql);
    }


}