<?php
namespace  Consult\Controller;
use        Think\Controller;
class IndexController extends CommonController
{
    /**
     * 问题列表视图
     */
    public function index()
    {   

        $is_doc=M('user_base_info',null)->where(array('user_id'=> session('yixin_user')))->getField('user_type_id');
        if($is_doc==2){
            $this->redirect('docindex');
        }
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

        //排序方式
        if(isset($_POST['type'])){
            $_GET['type']= $_POST['type'];
        }
        //页码
        $page=$_POST['page'];
        if($_GET['type']==1) {
            $ask = D('Problem')->get_llist($page,'answer_num',null,$search,null);
        }else if ($_GET['type']==2){
            $ask = D('Problem')->get_llist($page,null,1,$search,null);
        }else{
            $ask = D('Problem')->get_llist($page,null,null,$search,null);
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
     * 医生问题列表
     */
    public  function  docindex(){
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

        //排序方式
        if(isset($_POST['type'])){
            $_GET['type']= $_POST['type'];
        }
        //页码
        $page=$_POST['page'];
        if($_GET['type']==1) {
            $ask = D('Problem')->get_llist($page,null,1,$search,null);
        }else if ($_GET['type']==2){
            $ask = D('Problem')->get_llist($page,null,null,$search,null,1);
        }else if($_GET['type']==3){
            $ask = D('Problem')->get_llist($page,null,null,$search,null,2);
        }
        else{
            $ask = D('Problem')->get_llist($page,null,0,$search,null);
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
     * 详细视图
     */
    public  function  detail(){
        $aid=$_GET['aid'];
        $a=D('Problem')->getOne($aid);
       //无内容
        if(!$a){
            echo_empty();
            die();
        }
        $this->de=$a;
        $this->img=D('Problem')->getImg($aid);

        //是否已经解决
        if($a['is_end']==1){
            //已经解决
            $this->isend=1;
            $this->breply=D('Problem')->getReply($aid,null,1); //是最佳的答案
            $this->reply = D('Problem')->getReply($aid,1,null); //不是最佳的答案

        }else {
            //未解决
            $this->isend=0;
            $this->reply = D('Problem')->getReply($aid,null,null);
        }
        //是否为医生
        $is_doc=is_doc();
        $this->is_doc=$is_doc;
        if($a['user_id']==session('yixin_user') or $is_doc==true){
            $this->isuser=1;
        }else{
            $this->isuser=0;
        }
        //是否为用户自己的问题
        if($a['user_id']==session('yixin_user') && $this->isend==0){
            $this->display('udetail');

        }else{
            if($is_doc && $this->isend==0){
               //是否有用户追问
                $as_id=M('doctor_answer_info')->where(array('doctor_id'=>session('yixin_user'),
                                                            'question_id'=>$_GET['aid'],
                                                            'is_answer'=>1
                 ))
                    ->getField('answer_id');
                if($as_id){
                    $this->is_reply=1;
                }else{
                    $this->is_reply=0;
                }

                $this->display('ddetail');
            }else {
                $this->display();
            }
        }


    }
    /**
     * 点赞
     */
    public function evalueUp(){
        $aid=$_POST['aid'];
        $ssname='askevalue'.$aid;
        if(session('?'.$ssname)){
            echo 0;
        }else{
            M('doctor_answer_info')->where(array('answer_id'=>$aid))->setInc('up_num',1);
            session($ssname,1);
            echo 1;
        }
    }

}