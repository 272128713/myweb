<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/2
 * Time: 14:01
 */
namespace Article\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function _initialize()
    {

        //$_GET['ss'] = 'iciERWPefe3f9GeedNF0lCVt0XmNbalz';
        //权限验证
        if (isset($_GET['ss'])) {
            //查询用户id
            $user_id=M('mall_user_session_info',null)->where(array('session'=>$_GET['ss']))->getField('user_id');
            session('yixin_user', $user_id);
            session('yixin_ss',$_GET['ss']);
        }
        if(session('?yixin_ss')){
            $user_info=M('mall_member',null)->find($user_id);
            session('user_info',$user_info);
        }
        
        $condition=array();
        $condition['_string']="doctor_id is  null and state=0 and examine_time<='".date('Y-m-d H:i:s')."'";
        M('com_top_article')->where($condition)->save(array('state'=>1));
    }
}