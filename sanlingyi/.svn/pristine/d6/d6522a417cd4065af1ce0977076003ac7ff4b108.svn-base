<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/2
 * Time: 10:24
 */
namespace Article\Controller;
use Think\Controller;
class SearchController extends Controller{
    public  function  index(){
       // cookie('history',null);
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