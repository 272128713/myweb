<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/2
 * Time: 14:01
 */
namespace Entity\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function _initialize()
    {

       // $_GET['ss'] = 'mxetfUAPtvkISLyAODsSYtenbfcWo7NI';
        //权限验证
        if (isset($_GET['ss'])) {
            //查询用户id
            $user_id=M('user_session_info',null)->where(array('session'=>$_GET['ss']))->getField('user_id');
            session('yixin_user', $user_id);
            session('yixin_ss',$_GET['ss']);
        }

        if(!session('?is_doc')){
                session('is_doc',is_doc());
        }
        $this->is_doc=session('is_doc');


    }
}