<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 员工控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/8/21
 * Time: 13:43
 */

class WorkerController extends  CommonController{

    /**
     * 查看所有会员
     */
    public  function  allMember(){
    	$page=I('get.page',1);
        //调用接口
        $data=array(
            'ss'=>session('yixin_ss'),
            'page'=>$page
        );
        $result=poster("Worker/getMember",$data);
        if($result['code']==1){
            $this->rs=$result['result']['member'];
            $this->nums=count($result['result']['member']);
            $this->now_num=$result['result']['now_num'];
            $this->has_num=$result['result']['has_num'];
        }else{
            //$this->error($result['msg']);
        }
        if(isset($_GET['page'])){
        	$this->display('Data:allMember');
        }else{
        	$this->display();
        }
    }
    /**
     * 查看某个员工
     */
    public  function  oneMember(){
            //调用接口
            $data=array(
                'ss'=>session('yixin_ss'),
                'uid'=>$_GET['uid'],
            );
            $result=poster("Worker/getUserbyId",$data);
            //dump($result);
            //die();
            if($result['code']==1){
                $this->mm=$result['result'];
                $this->v=$result['result'];
                //获取相关信息

            }else{
                opError($result['msg']);
            }
            $data=array(
            'ss'=>session('yixin_ss'),
            'uid'=>$_GET['uid'],
            'page'=>1,
             );

            $result=poster("Worker/MemberConsumeList",$data);
            if($result['code']==1) {
                 $this->cv = $result['result'];
                 $this->nums=count($this->cv);
             }else{
                opError($result['msg']);
            }

       $this->display();

    }



    /**
     * 更多订单
     */
    public  function  getMoreOrder(){
        $page=I('get.page',1);
        $data=array(
            'ss'=>session('yixin_ss'),
            'uid'=>$_GET['uid'],
            'page'=>$page,
        );

        $result=poster("Worker/MemberConsumeList",$data);
        if($result['code']==1) {
            $this->cv = $result['result'];
            $this->nums=count($this->cv);
            if($this->nums==0){
                echo 0;
            }else {
                $this->display('Data:moreOrder');
            }
        }else{
            opError($result['msg']);
        }

    }

    /**
     * 提货
     */
    public  function  getGoods(){
        if(IS_AJAX){

            $data=array(
                'ss'=>session('yixin_ss'),
                'uid'=>I('uid',0),
                'code'=>I('code',0),
            );
            $result=poster("Worker/getGoods",$data);
            if($result['code']==1) {
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    /**
     * 退货
     *
     */
    public  function  returnGoods(){
        if(IS_AJAX){

            $data=array(
                'ss'=>session('yixin_ss'),
                'uid'=>I('uid',0),
                'code'=>I('code',0),
            );
            $result=poster("Worker/returnGoods",$data);
            if($result['code']==1) {
               echo 1;
            }else{
               echo 0;
            }
        }
    }

    } 

