<?php

namespace  Clinic\Controller;
use        Think\Controller;

class IndexController extends CommonController
{
    /**
     * 首页视图
     */
   public  function  index(){
//       http://182.254.209.88/api/v1/clinic?os_type=android&version=1.0.4
       //获取诊所信息
       $postUrl = C('API_URL').'api/v1/clinic';
       $postArr = array(
           'os_type' => 'android',
           'version' => '1.0.4'
       );
       $returnArr = httpRequest($postUrl,$postArr,'post');
       $returnObj = json_decode($returnArr);
       if($returnObj->code==1){
           $result = $returnObj->result;
//           file_put_contents('11.txt',var_export($result,TRUE));
           $this->cmsg=$result;
           $this->num= count($result);
       }
       $this->display();
   }

    public function showArea(){
        $province = $_POST['province'];
        $city = $_POST['city'];
        $postUrl = C('API_URL').'api/v1/clinic';
        $postArr = array(
            'os_type' => 'android',
            'version' => '1.0.4',
            'province' => $province,
            'city' => $city
        );
        $returnArr = httpRequest($postUrl,$postArr,'post');
        $returnObj = json_decode($returnArr);
        if($returnObj->code==1){
            $result = $returnObj->result;
//           file_put_contents('11.txt',var_export($result,TRUE));
            $this->cmsg=$result;
            $this->num= count($result);
            if(count($result)){
                echo $this->display();
            }
        }

    }
}