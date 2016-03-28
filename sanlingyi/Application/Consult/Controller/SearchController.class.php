<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/10
 * Time: 11:07
 */
namespace  Consult\Controller;
use        Think\Controller;
class SearchController extends CommonController{
    /**
     * 搜索显示视图
     */
    public  function  index(){
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


}