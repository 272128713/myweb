<?php
/**
 * 店铺模型
 * @author Administrator
 *
 */
class ShopModel extends Model
{
    
	/**
	 * 查询店铺详情
	 */
	public  function  shopDetail($shop_id)
	{
   		$sql="select a.shop_name,
   				     a.shop_address,
   				     a.shop_goods_num,
   				     a.shop_money,
   				     a.shop_member_num,
   				     a.shop_woker_num,
   				     a.member_name,
   				     a.piv,
   				     a.pav,
   				     a.thumbnail_image_url,
   					 FROM_UNIXTIME(a.shop_time) as shop_time,
   				     a.member_truename as manager_name,
   				     a.member_id as manager_id 
   				     from mall_shop_view as a                      				      
   				     where  a.shop_id=$shop_id        				
   				";
     
   		return  $this->conn->getRow($sql);
    }	
}