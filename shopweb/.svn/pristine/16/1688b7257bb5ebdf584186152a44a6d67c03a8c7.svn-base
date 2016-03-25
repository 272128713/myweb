<?php
namespace  Article\Controller;
use        Think\Controller;
class IndexController extends CommonController
{
    public function index()
    {
        //先更新要发布的文章
        session('page', 0);
        //文章查询
        if (!isset($_GET['cid'])) {
            $id=M('article_class')->order('sort asc')->limit(1)->select();
            if(!empty($id)){
                    $id=$id[0];
                    $_GET['cid']=$id['id'];
                $article = M()->query("SELECT a.* FROM com_top_article a LEFT JOIN
					           article_belong_class b ON  a.id=b.article_id
					          WHERE b.class_id=" . $id['id'] . " and state=1 "
                    . " order by a.sort asc,a.examine_time desc LIMIT 0, " . C('PAGE_NUM'));


            }else{
                $article = M('com_top_article')->where(array('state' => 1))
                    ->order('sort asc,examine_time desc')->limit(C('PAGE_NUM'))->select();
            }

        } else {
            $article = M()->query("SELECT a.* FROM com_top_article a LEFT JOIN
					           article_belong_class b ON  a.id=b.article_id 
					          WHERE b.class_id=" . $_GET['cid'] . " and state=1 "
                . " order by a.sort asc,a.examine_time desc LIMIT 0, " . C('PAGE_NUM'));

        }
        $this->article = procedure($article);
        //导航处理
        $this->nav = nav_oprate();
        $this->display();
    }

    //更新新闻
    public function  update()
    {
        session('page', '0');
        if ($_POST['cid'] == 0) {
            $article = M('com_top_article')->where(array('state' => 1))
                ->order('sort asc,id desc')->limit(C('PAGE_NUM'))->select();
        } else {
            $article = M()->query("SELECT a.* FROM com_top_article a LEFT JOIN
					           article_belong_class b ON  a.id=b.article_id
					          WHERE b.class_id=" . $_POST['cid'] . " and state=1 "
                . " order by a.sort asc,a.examine_time desc LIMIT 0, " . C('PAGE_NUM'));

        }
        $articles = procedure($article);
        echo $articles;
    }

    /**
     * 获取更多新闻
     */
    public function  more()
    {

        if (session('page') >= $_POST['page']) {
            echo 0;
            die();
        } else {
            session('page', $_POST['page']);
        }
        $limit = $_POST['page'] * C('PAGE_NUM');
        if ($_POST['cid'] == 0) {
            $article = M('com_top_article')->where(array('state' => 1))
                ->order('sort asc,examine_time desc')->limit($limit, C('PAGE_NUM'))->select();
        } else {
            $article = M()->query("SELECT a.* FROM com_top_article a LEFT JOIN
					           article_belong_class b ON  a.id=b.article_id
					          WHERE b.class_id=" . $_POST['cid'] . " and state=1 "
                . " order by a.sort asc,a.examine_time desc LIMIT $limit, " . C('PAGE_NUM'));

        }
        $articles = procedure($article);
        if (strpos($articles, '暂无内容')) {
            echo 0;
        } else {
            //session('page', session('page')+1);
            echo $articles;
        }
    }

    /**
     * 详细页
     */
    public function  detail()
    {
        //这篇文章
        $de = M('com_top_article')->where(array('id' => $_GET['aid']))->find();
        $aid=$_GET['aid'];
        if (!is_null($de['doctor_id'])) {
//            $username = M('user_base_info')->where(array('user_id' => $de['doctor_id']))
//                ->getField('user_name');
//            $de['article_from'] = $username . '医生原创';
        }
        $a = $de;
        //文章内容处理
        $this->de = $de;
        //评价个数
        $this->evalute = M('article_evaluation')->where(array('article_id' => $_GET['aid']))->count();
        //相关文章
        $this->sde = M()->query('SELECT id,title FROM com_top_article where state=1 and id!=' . $_GET['aid'] . ' ORDER BY rand() LIMIT 3');
        //是否登录
        $this->islogin = session('?yixin_user') ? 1 : 0;

        //评价最热
        $hotcom = M()->query("select a.*,b.member_truename as user_name,c.thumbnail_image_url from article_evaluation as a  LEFT JOIN mall_member as b ON b.member_id=a.user_id LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id WHERE
a.article_id=$aid and a.up_num >0 ORDER BY a.up_num desc limit 5 ");
        $condition='0';
        foreach($hotcom as $keys=>$values){

                $condition.=','.$values['id'];

        }


        //评价最新
        $newcom = M()->query("select a.*,b.member_truename as user_name,c.thumbnail_image_url from article_evaluation as a  LEFT JOIN mall_member as b ON b.member_id=a.user_id LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id WHERE
a.article_id=$aid and a.id not in($condition) ORDER BY a.id desc limit 5 ");

        //是否收藏
        $a=M('user_favorite_info')->where(array('src_user_id'=>$aid,'type'=>5,'user_id'=> session('yixin_user')))->find();
        if($a){
           $this->is_save=1;
        }else{
            $this->is_save=0;
        }
        $this->newcom=oprate_evalute($newcom);
        $this->hotcom=oprate_evalute($hotcom);
        $this->display();
    }

    /**
     * 赞和踩
     */
    public function  addtend()
    {
        $db = M('com_top_article');
        if ($_POST['type'] == 1) {
            if (session('?san_up' . $_POST['aid'])) {
                echo 0;
            } else {
                session('san_up' . $_POST['aid'], 1);
                $db->where(array('id' => $_POST['aid']))->setInc('up_num', 1);
                echo 1;
                //保存
            }
        } else {
            if (session('?san_down' . $_POST['aid'])) {
                echo 0;
            } else {
                session('san_down' . $_POST['aid'], 1);
                $db->where(array('id' => $_POST['aid']))->setInc('down_num', 1);
                echo 1;
            }
        }
    }


    /**
     * 保存评价内容和显示评价第一页
     */
    public function  evalute()
    {

        if($_POST['ajax']==1){
            $isajax=1;
            if(isset($_POST['update'])){
                $page=0;
            }else{
                $page=$_POST['page'];
            }
            $_GET['aid']=$_POST['aid'];
            $_GET['type']=$_POST['type'];
        }else{
            $page=0;
        }
        $limit=$page*C('PAGE_NUM');
        $DB = M('article_evaluation');
        $aid = $_GET['aid'];
        if (!isset($_POST['content'])) {
            if ($_GET['type'] == 1) {
                $com = M()->query("select a.*,b.member_truename as user_name,c.thumbnail_image_url from article_evaluation as a  LEFT JOIN mall_member as b ON b.member_id=a.user_id LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id WHERE
a.article_id=$aid ORDER BY a.id desc limit $limit ," . C('PAGE_NUM'));
                $this->type = 1;
            } else {
                $com = M()->query("select a.*,b.member_truename as user_name,c.thumbnail_image_url from article_evaluation as a  LEFT JOIN mall_member as b ON b.member_id=a.user_id LEFT JOIN mall_user_version_info as c ON c.user_id=a.user_id WHERE
a.article_id=$aid ORDER BY a.up_num desc limit $limit ," . C('PAGE_NUM'));
                $this->type = 2;
            }

            foreach($com as $key=>$v){

                    $com[$key]['thumbnail_image_url']=getImg($com[$key]['thumbnail_image_url']);

            }
            $this->com = $com;
            //文章相关
            $this->de = M('com_top_article')->find($_GET['aid']);
            //评价个数
            $this->evalute = M('article_evaluation')->where(array('article_id' => $_GET['aid']))->count();
           //为刷新

            if($isajax){

               echo json_encode($this->com);
           }else {
               $this->display();
           }
        } else {
            $arr = array();
            $arr['article_id'] = $_POST['aid'];
            $arr['user_id'] = session('yixin_user');
            $arr['content'] = htmlspecialchars($_POST['content']);
            $arr['create_time'] = date('Y-m-d H:i:s');
            M('article_evaluation')->add($arr);
            M('com_top_article')->where(array('id'=>$_POST['aid']))->setInc('eval_num',1);
            $this->redirect('Index/evalute', array('aid' => $_POST['aid'], 'type' => 1));
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
            M('article_evaluation')->where(array('article_id'=>$aid,'id'=>$eid))->setInc('up_num',1);
            session($ssname.'_'.$eid,1);
            echo 1;
        }
    }

    /**
     * 搜索文章
     */
    public  function  search(){
        //如果为历史记录
        if(isset($_GET['value'])){
            $_POST['value']=urldecode($_GET['value']);
        }

//        页面处理
        if(!session('?s_page') or !isset($_POST['s_page'])){
            session('s_page',0);
            $_POST['s_page']=0;
        }else {
            //加载更多
            if (session('s_page') >= $_POST['s_page']) {
                echo 0;
                die();
            } else {
                session('s_page', $_POST['s_page']);
            }
        }
        $limit = $_POST['s_page'] * C('PAGE_NUM');
        //搜索查询
        $value=htmlspecialchars(trim($_POST['value']));
        $value=mb_substr($value,0,50,'utf-8');
        //浏览记录处理
        if(isset($_POST['search'])){
            loop_history($value);
        }

        if($value==''){
            $article = M('com_top_article')->where(array('state' => 1))
                ->order('sort asc,examine_time desc')->limit($limit,C('PAGE_NUM'))->select();
        }else{
            $where=array();
            $where['title']=array('like','%'.$value.'%');
            $where['state']=1;
            $article = M('com_top_article')->where($where)
                ->order('sort asc,examine_time desc')->limit($limit,C('PAGE_NUM'))->select();
        }
        $this->anum=count($article);
        $this->article = procedure($article);
        if (strpos($this->article, '暂无内容</')) {
        	echo $this->articles=0;
        } 
        $this->value=$value;
        if(isset($_POST['is_ajax'])) {
            if (strpos($this->article, '暂无内容</')) {
                echo 0;
            } else{
                echo $this->article;
            }
        }else{
            $this->display();
        }

    }

}