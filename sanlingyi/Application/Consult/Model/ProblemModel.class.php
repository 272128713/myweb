<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/9
 * Time: 9:54
 * 问题模型
 */
namespace Consult\Model;
use Think\Model;
class ProblemModel extends  Model{

        protected  $tableName='user_question_info';  //表
        protected  $pk='question_id';                //主键

        /**
         * 取问题列表
         */
        public  function  get_llist($page=null,$orders=null,$is_end=null,$s=null,$uid=null,$doctype=null){
            $suid=session('yixin_user'); //当前用户id
            if(is_null($page)){
               $page=0;
            }
            if(is_null($orders)){
                $orders='question_id';
            }
            //是否为查询
            $condition=array();
             if(!is_null($s)){
                $condition['_string']='(title like "%'.$s.'%"  OR disease like "%'.$s.'%" ) and ';
             }
            //有id只查询部分信息用户自己的
            if(!is_null($uid)){
                $condition['_string'].="user_id=$uid";
            }else{
                $condition['_string'].="(answer_id =10000000 or user_id=".$suid." or answer_id=$suid".')' ;
            }


            if(!is_null($is_end)){
                $condition['is_end']=array('eq',$is_end);
                $list=M('user_question_info')->where($condition)->order($orders .' desc')->limit($page*C('PAGE_NUM'),C('PAGE_NUM'))->select();
            }else{
                $list=M('user_question_info')->where($condition)->order($orders .' desc,createDate desc')->limit($page*C('PAGE_NUM').' , '.C('PAGE_NUM'))->select();

            }
            if(!is_null($doctype)){
                //查询医生相关问答
                $list=$this->getDocAsk($doctype,$page,$s);
            }
//            foreach($list as $k=>$v){
//                   $name=M('com_sic_section_info',null)->where(array('id'=>$v['recollection']))->getField('name');
//                    $list[$k]['recollection']=$name;
//            }

            return $list;
        }
    //医生首页
   public function getDocAsk($type,$page,$s){
           if(!is_null($s)){
               $limit='(title like "%'.$s.'%"  OR disease like "%'.$s.'%" ) and ';
           }else{
               $limit='';
           }


            if($type==1){
                $sql="select * from bbs_user_question_info where $limit
                     answer_id=".session('yixin_user').' order by
                     question_id desc limit '.$page*C('PAGE_NUM').','.C('PAGE_NUM');
            }else{
                //已经回答过的问题
                $sql="select * from bbs_user_question_info where $limit
                     question_id in (SELECT DISTINCT question_id FROM bbs_doctor_answer_info WHERE
                      doctor_id =".session('yixin_user').") order by
                     question_id desc limit ".$page*C('PAGE_NUM').','.C('PAGE_NUM');
            }
           return M()->query($sql);
   }
      /**
       *获取一条问题极其相关
       */
    public function getOne($aid){
        return M('user_question_info')->where(array('question_id'=>$aid))->find();
    }

    /**
     * 根据问题返回图片
     */
    public  function  getImg($aid){
        $IMG=M('question_images_info')->where(array('question_id'=>$aid))->select();
        foreach($IMG as $k=>$v){
            $IMG[$k]['source_image_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['source_image_url']);
        }
        return $IMG;
    }

    /**
     * 根据问题返回相关回答内容
     */
    public  function  getReply($aid,$isend,$best=null){
        $condition="";
        if(!is_null($isend)){
            $condition="and a.is_best!=1";
        }else if(!is_null($best)){
            $condition="and a.is_best=1";
        }
        $arr=M()->query("SELECT a.*,b.user_name,b.hospital,c.name as duty,d.name as recollection ,e.thumbnail_image_url as img_url from bbs_doctor_answer_info as a LEFT JOIN user_base_info as b ON b.user_id=a.doctor_id LEFT JOIN dim_duty_code as c ON c.duty_id=b.duty_id
LEFT JOIN dim_recollection_code as d ON d.recollection_id=b.recollection_id LEFT JOIN user_version_info as e ON e.user_id=b.user_id WHERE a.question_id=$aid $condition ORDER BY a.creatDate desc");

        foreach($arr as $k=>$v){

            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
            $arr[$k]['hospital']=substr($v['hospital'],21);
            $arr[$k]['answers']=M('question_to_answer_info')->where(array('answer_id'=>$v['answer_id']))->select();

        }
        return $arr;
    }

    //返回一条回答
    public  function  getOneReply($id){
        $arr= $arr=M()->query("SELECT a.*,b.user_name,b.hospital,c.name as duty,d.name as recollection ,e.thumbnail_image_url as img_url from bbs_doctor_answer_info as a LEFT JOIN user_base_info as b
                    ON b.user_id=a.doctor_id LEFT JOIN dim_duty_code as c ON c.duty_id=b.duty_id
                    LEFT JOIN dim_recollection_code as d ON d.recollection_id=b.recollection_id LEFT
                    JOIN user_version_info as e ON e.user_id=b.user_id WHERE a.answer_id=$id
                    ORDER BY a.creatDate desc");
        foreach($arr as $k=>$v){

            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
            $arr[$k]['hospital']=substr($v['hospital'],21);
            if($v['is_answer']==1){
                $arr[$k]['answers']=M('question_to_answer_info')->where(array('answer_id'=>$v['answer_id']))->select();
            }
        }
        return $arr[0];
    }


}