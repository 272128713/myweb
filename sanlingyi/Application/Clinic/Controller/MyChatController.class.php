<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/1
 * Time: 16:16
 */

namespace Hospital\Controller;


use Think\Controller;

class MyChatController extends CommonController {
    public  function  index(){
        //我的信息
        $uid=session('yixin_user');
        $my=M('user_base_info',null)->field('user_name,live_place')->where(array('user_id'=>$uid))->find();
        $this->me=$my;
        $db=M('com_sic_region_info',null);
        $this->province=$db->find(substr($my['live_place'],0,2).'0000');

        $this->city=$db->find(substr($my['live_place'],0,4).'00');
        $pic=M('user_version_info',null)->where(array('user_id'=>$uid))->getField('thumbnail_image_url');
        if($pic==''){
            $pic=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
        }else{
            $pic='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$pic);
        }
        $this->pic=$pic;
        //文章信息
        $model=D('Article');
        $page=0;
        $this->news=$model->myArticle($page,C('PAGE_NUM'));
        $this->nums=count($this->news);
        $this->com=$this->myCom(0,C('PAGE_NUM'));
        $this->cnums=count($this->com);
        //评论信息
        $this->display();
    }

    /**
     * 我的评论
     * @param int $page
     * @param $limit
     */
    public  function  myCom($page=0,$limit){
        $uid=session('yixin_user');
        $arr= M()->query("select a.*,b.title from bbs_doc_article_comment_info as a,bbs_doc_article_info as b
                    where a.user_id=$uid and b.id=a.article_id order by a.createDate desc limit $page,$limit
                    ");
        return $arr;
    }

    /**
     * 收藏头条信息
     */
    public  function  saveFavorite(){
        if(!IS_AJAX){
            echo 0;
            die();
        }
        $id=$_POST['aid'];
        $is_save=$_POST['is_save'];
        $db=M('user_favorite_info',null);
        if($is_save==1){
//            $a = $db->where(array('src_user_id' => $id, 'type' => 5, 'user_id' => session('yixin_user')))->delete();
//            if ($a) {
//                echo 1;
//                die();
//            }else{
//                echo 4;
//            }
        }else {
            $a = $db->where(array('src_user_id' => $id, 'type' => 6, 'user_id' => session('yixin_user')))->find();
            if ($a) {
                echo 2;
                die();
            }
            //查询次文章相关信息
            $article = M('doc_article_info')->where(array('id' => $id))->find();
//        var_dump($article);
//        die();
            //查询文章相关图片

            $img=M('doc_article_images')->where(array('article_id'=>$article['id']))->select();

            if (!$img) {
                $img_url = '';
            } else {
                $img_url = $img[0]['source_image_url'];
            }
            $data = array();
            $data['user_id'] = session('yixin_user');
            $data['type'] = 6;
            $data['src_date'] = $article['createDate'];
            $data['src_user_id'] = $id;
            $data['favorite_text'] = $article['title'];
            if($article['sys_flag']==0){
                //医生发表
                $data['thumbnail_image_url']=0;
            }else{
                //系统发表
                $data['thumbnail_image_url']=1;
            }
            //$data['thumbnail_image_url']=$img_url;
            $data['source_image_url'] = $img_url;
            //进行保存
            if ($db->add($data)) {
                echo 1;
            } else {
                echo 0;
            }
        }

    }

    /**
     * 保存评论
     */
    public  function  saveComment(){
       $data=array();
       $data['content']=mb_substr($_POST['content'],0,500,'utf8');
       $data['article_id']=$_POST['aid'];
       $data['user_id']= session('yixin_user');
       $data['createDate']=date('Y-m-d H:i:s');
       if(M('doc_article_comment_info')->add($data)){
           M('doc_article_info')->where(array('id'=>$_POST['aid']))->setInc('recommend_num',1);
            $this->redirect('Index/detail',array('aid'=>$_POST['aid']));
       }else{
            echo 'error';
       }
    }

    /**
     * 对评价点赞
     */
    public  function  evalueUp(){
        $aid=$_POST['aid'];
        $eid=$_POST['eid'];
        $ssname='evalue'.$aid;
        if(session('?'.$ssname.'_'.$eid)){
            echo 0;
        }else{
            M('doc_article_comment_info')->where(array('id'=>$eid))->setInc('up_num',1);
            session($ssname.'_'.$eid,1);
            echo 1;
        }
    }

    /**
     * 删除评论
     */
    public  function  delete(){
        $eid=$_POST['eid'];
        if(M('doc_article_comment_info')->delete($eid)){
            M('doc_article_info')->where(array('id'=>$_POST['aid']))->setDec('recommend_num',1);
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 删除我的文章
     */
    public  function  deleteArt(){
        if(isset($_POST['aid'])){
            $aid=$_POST['aid'];
            if(M('doc_article_info')->delete($aid)){
                M('doc_article_images')->where(array('article_id'=>$aid))->delete();
                echo 1;
            }else{
                echo 0;
            }

        }
    }

    /**
     *	文章分页
     */
    public  function  updateArt(){

        $model=D('Article');
        $page=$_GET['page']*C('PAGE_NUM');
        $this->news=$model->myArticle($page,C('PAGE_NUM'));
        if(count($this->news)==0){
            return 0;
            die();
        }
        return $this->fetch('art');



    }
    /**
     *文章分页
     */
    public  function  updateArtAjax(){

        echo ($this->updateArt());



    }
    
    public  function  updateCom(){
    	$page=$_GET['page']*C('PAGE_NUM');
        $this->com=$this->myCom($page,C('PAGE_NUM'));
        if(count($this->com)==0){
        	return 0;
        	die();
        }
    	$this->display('com');
    	//var_dump( $str);
    }
}