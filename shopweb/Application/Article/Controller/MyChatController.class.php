<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/1
 * Time: 16:16
 */

namespace Article\Controller;


use Think\Controller;

class MyChatController extends CommonController {
   /**
    * 我的评价
    */
    public  function  index(){
        $uid=session('yixin_user');
        //我的评价
        $mc=M()->query("select a.*,c.thumbnail_image_url,d.title,d.article_from from article_evaluation as a  LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id LEFT JOIN com_top_article as d on d.id=a.article_id
 where a.user_id=$uid ORDER BY a.id desc limit ".C('PAGE_NUM'));
        $this->mc=$mc;
        //我的信息
        $my=M('mall_member')->field('member_truename')->where(array('member_id'=>$uid))->find();
        $this->me=$my;

        $pic=M('mall_user_version_info')->where(array('user_id'=>$uid))->getField('thumbnail_image_url');
        if($pic==''){
            $pic=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
        }else{
            $pic='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$pic);
        }
        $this->pic=$pic;
        $this->display();
    }

    /**
     * 删除我的评价
     */
    public  function  delete(){
        if(isset($_POST['eid'])){
            $eid=$_POST['eid'];
            if(M('article_evaluation')->delete($eid)){
                M('com_top_article')->where(array('id'=>$_POST['aid']))->setDec('eval_num',1);
                echo 1;
            }else{
                echo 0;
            }

        }
    }

    /**
     * 获取更多我的评价
     */
    public  function  more(){
        if(!isset($_POST['page'])){
          $page=0;
        }else{
           $page=$_POST['page'];
        }
        $uid=session('yixin_user');
        $limit=$page*C('PAGE_NUM');
        $mc=M()->query("select a.*,c.thumbnail_image_url,d.title,d.article_from from article_evaluation as a  LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id LEFT JOIN com_top_article as d on d.id=a.article_id
 where a.user_id=$uid ORDER BY a.id desc limit $limit , ".C('PAGE_NUM'));
        if(empty($mc)){
            echo 0;
        }else{
            echo get_my_html($mc);
        }
    }

    /**
     * 收藏头条信息
     */
    public  function  saveFavorite(){
        $id=$_POST['aid'];
        $is_save=$_POST['is_save'];
        $db=M('user_favorite_info');
        if($is_save==1){
            $a = $db->where(array('src_user_id' => $id, 'type' => 5, 'user_id' => session('yixin_user')))->delete();
            if ($a) {
                echo 1;
                die();
            }else{
                echo 4;
            }
        }else {
            $a = $db->where(array('src_user_id' => $id, 'type' => 5, 'user_id' => session('yixin_user')))->find();
            if ($a) {
                echo 2;
                die();
            }
            //查询次文章相关信息
            $article = M('com_top_article')->where(array('id' => $id))->find();
//        var_dump($article);
//        die();
            $img = unserialize($article['pic']);

            if (!$img) {
                $img_url = '';
            } else {
                $img_url = $img[0];
            }
            $data = array();
            $data['user_id'] = session('yixin_user');
            $data['type'] = 5;
            $data['src_date'] = $article['examine_time'];
            $data['src_user_id'] = $id;
            $data['favorite_text'] = $article['title'];
            //$data['thumbnail_image_url']=$img_url;
            $data['source_image_url'] = $img_url;
            if ($db->add($data)) {
                echo 1;
            } else {
                echo 0;
            }
        }

    }
}