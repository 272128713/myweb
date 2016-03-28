<?php
namespace  Hospital\Controller;
use        Think\Controller;
class IndexController extends CommonController
{
    /**
     * 首页视图
     */
   public  function  index(){
       $model=D('Article');
       $a=$model->getHot();

       $this->at=$a;
       $this->img=$model->getImg($a['id'],0);
       $this->news=$model->getNews();
       $this->display();
   }
   public  function morenews(){
       $page = $_POST['page'];
       $model=D('Article');
       $this->news=$model->getNews($page);
       $this->display('newsblock');
//       =$model->getNews($page);
//       if($newsmore){
//            echo json_encode($newsmore);
//       }
   }
    /**
     * 热门话题更多（列表）
     */
    public  function  cate(){
        $model=D('Article');
        $this->news=$model->get_Hots();
        $this->display();
    }
    /**
     * 栏目列表
     */
    public  function  lists(){
       $this->ret=get_ret();

        //判断是否有栏目id和科室id
        $condition=get_cate_condition();
        $model=D('Article');
        $page=$_POST['page'] || 0;
        if(isset($_POST['page'])){
            $page=$_POST['page']*C('PAGE_NUM');
        }else{
            $page=0;
        }
        $this->news=$model->getNews($page,C('PAGE_NUM'),$condition);
        $this->nums=count($this->news);
        $condition= session('san_cate');
        $arr=explode(',',$condition);
        //栏目名称
        if(isset($_GET['rid'])){
        	if(!in_array($_GET['rid'], $arr)){
        		
   				$cname=M('dim_recollection_code',null)->where(array('recollection_id'=>$_GET['rid']))->getField('name');	
        	}else{
        		
        		$cname=0;
        	}
        }else{
        	     $cname=0;
        }
        $this->cname=$cname;
        if(isset($_POST['ajax'])){
            echo get_lists_up($this->news);
        }else {
            $this->display('list');
        }
    }
    public  function  detail(){
        $model=D('Article');
        $a=$model->getArticel($_GET['aid']);

        if($a['sys_flag']==0){
        	//医生发布
        	$a=article_change($a);
            $this->img=$model->getImg($a['id']);
        }else{
            $this->img=$model->getImg($a['id'],0);
        }
        $this->at=$a;
        $this->com=$model->get_coments($a['id']);
        $this->com_count=count($this->com);
        //此文章是否已经收藏
        //是否收藏
        $a=M('user_favorite_info',null)->where(array('src_user_id'=>$_GET['aid'],'type'=>6,'user_id'=> session('yixin_user')))->find();
        if($a){
            $this->is_save=1;
        }else{
            $this->is_save=0;
        }
        $this->display();
    }

}