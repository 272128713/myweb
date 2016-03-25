<?php
namespace Think\Template\TagLib;
use Think\Template\TagLib;
class  Dd extends TagLib{
	protected $tags=array(
		'category'=>array('attr'=>'id,sort,state','close'=>1),
		
	);  
	
	/**
	 * 分类列表标签
	 * @param unknown $tag
	 * @param unknown $content
	 */
	public  function  _category($tag,$content){
		$condition=array();
		isset($tag['id']) ?$condition['id']=$tag['id'] :null; 
		isset($tag['sort']) ? $sort=$tag['sort'] :$sort='asc';  //排序
		isset($tag['state']) ? $condition['state']=$tag['state'] :$condition['state']=1;
	    $db=M('class');
	    $cate=$db->where($condition)->order('sort ' . $sort)->select();
	    $str='';
	    $count=count($cate);
	    for($i=0;$i<$count;$i++){
	    	$c=str_replace(array("[filed:id]","[filed:name]","[filed:sort]","[filed:key]"),
	    			       array($cate[$i]['id'],$cate[$i]['class_name'],$cate[$i]['sort'],$i+2),$content);
	    	$str.=$c;
	    }
	   
	    return $str;
	    
	}
	
}