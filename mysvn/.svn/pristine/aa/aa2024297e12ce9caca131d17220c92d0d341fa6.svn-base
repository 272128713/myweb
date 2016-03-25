<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use Think\Page;

/**
 * 后台内容控制器
 * @author huajie <banhuajie@163.com>
 */
class HrController extends AdminController {



    public function add(){

        $this->display();
    }
    public function addajax(){
        $post = $_POST;
        $data['jobname']=$post['title'];
        $data['jobdetail']=$post['content'];
        $data['createdate']=date('Y-m-d H:i:s',time());
        $Form   =   M('hr','web_');
        $insertHr = $Form -> data($data) ->add();
        $postajax = json_encode($insertHr);
        echo $postajax;
    }



    public function show(){
        $Form   =   M('hr','web_');
        //分页
        $page = new \Think\Page($Form->count(),15);

        $list = $Form->order("createdate desc")->limit($page->firstRow,$page->listRows)->select();
        //分页设置主题
        $page ->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        //fenye
        $this->assign('_page',$page->show());

        $this->assign('list', $list);
        $this->display();
    }

    public function delete(){
        $id =  I('id');
        $Form   =   M('hr','web_');
        $result= $Form->where("id='$id'")->delete();
    }

    public function edit(){
        $id =  I('id');
        $Form   =   M('hr','web_');
        $result= $Form->where("id='$id'")->select();
        $this->assign('id',$id);
        $this->assign('result',$result[0]);
        $this->display();

    }
    public function editDo(){
        $post = $_POST;
        $id=$post['id'];
        $data['jobname']=$post['title'];
        $data['jobdetail']=$post['content'];
        $data['createdate']=date('Y-m-d H:i:s',time());
        $Form   =   M('hr','web_');
        $result= $Form->where("id='$id'")->save($data);
        echo $result;
    }
}
