<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/9
 * Time: 9:54
 * 医院天地文章模型
 */
namespace Hospital\Model;
use Think\Model;
class ArticleModel extends  Model{

    /**
     * @return 返回最热1条文章
     */
    public  function  getHot(){
        $a= M('doc_article_info')->where(array('sys_flag'=>1))
               ->order('createDate desc')->limit(1)->select();
        return $a[0];
    }

    /**
     * @param $aid
     * @return mixed
     * 根据id获取文章相关图片
     */
    public  function  getImg($aid,$is_hot=null){
        $img=M('doc_article_images')->where(array('article_id'=>$aid))->select();
        foreach($img as $k=>$v){
        	if(is_null($is_hot)){
           		 $url='http://'.C('IMG_HOST').$v['source_image_url'];
                 $img[$k]['source_image_url']=str_replace('M00','MOO/data',$url);
            }else{
            	$img[$k]['source_image_url']=C('WEB_URL').$v['source_image_url'];
            }
        }
        return $img;
    }

    /**获取新闻
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public  function  getNews($page=0,$limit=10,$condition=''){
        $pagestart = $page*$limit;
        $sql="select a.* ,b.thumbnail_image_url as img_url,c.authentication,c.user_name,c.recollection_id from bbs_doc_article_info as a LEFT JOIN user_version_info
              as b ON  b.user_id=a.doc_id LEFT  JOIN  user_base_info AS c ON c.user_id=a.doc_id
              where a.sys_flag=0 $condition order by a.createDate desc limit $pagestart,$limit";
        $arr=M()->query($sql);
        foreach($arr as $k=>$v){
            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
            $sql= "select name from dim_recollection_code WHERE recollection_id = ".$v['recollection_id'];
            $resrec = M()->query($sql);
            $arr[$k]['recollection_id'] = $resrec[0]['name'];
            $model=D('Article');
            $a= $model-> getArticel($v['id']);
            $arr[$k]['comment_cont'] = count($model-> get_coments($a['id']));
            if($a['sys_flag']==0){
                //医生发布
                $a=article_change($a);
                $arr[$k]['cimg_url']=$model->getImg($a['id']);
            }else{
                $arr[$k]['cimg_url']=$model->getImg($a['id'],0);
            }
        }
        return $arr;
    }

    /**
     * 热门话题列表
     * @param int $page
     * @return mixed
     */
    public  function  get_Hots($page=0){
        $a= M('doc_article_info')->where(array('sys_flag'=>1))
            ->order('createDate desc')->limit("$page,".C('PAGE_NUM'))->select();
        foreach($a as $key=>$value){
                $a[$key]['img']=$this->getImg($value['id'],0);
        }
        return $a;

    }

    /**
     * 获取某一条文章相关
     */
    public  function  getArticel($aid){
        $a= M('doc_article_info')->where(array('id'=>$aid))->find();
        return $a;
    }

    /**
     * 返回某个文章的评论列表
     */
    public  function  get_coments($aid,$page=0,$limit=10){
        $sql="select a.* ,b.thumbnail_image_url as img_url,c.user_name from bbs_doc_article_comment_info as a LEFT JOIN user_version_info
              as b ON  b.user_id=a.user_id LEFT  JOIN  user_base_info AS c ON c.user_id=a.user_id
              where a.article_id=$aid  order by a.createDate desc limit $page,$limit";
        $arr=M()->query($sql);
        foreach($arr as $k=>$v){
            if($v['img_url']==''){
                $arr[$k]['img_url']=__ROOT__.'/Public/Article/images/MOO/data/default.jpg';
            }else{
                $arr[$k]['img_url']='http://'.C('IMG_HOST').str_replace('M00','MOO/data',$v['img_url']);
            }
        }
        return $arr;
    }

    /**
     * 我发表的
     */
    public  function  myArticle($page=0,$limit=10){
        $uid=session('yixin_user');
        $sql="select a.* ,b.name ,c.class_name from bbs_doc_article_info as a LEFT JOIN dim_recollection_code
              as b ON  b.recollection_id=a.recollection_id LEFT  JOIN  article_class AS c ON c.id=a.report_columns
              where a.doc_id=$uid  order by a.createDate desc limit $page,$limit";
        $arr=M()->query($sql);
        return $arr;
    }



}