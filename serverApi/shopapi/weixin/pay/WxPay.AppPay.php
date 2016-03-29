<?php
/**
 * Created by PhpStorm.
 * User: sdcxyz
 * Date: 16-1-15
 * Time: 下午3:01
 */
require_once dirname(__FILE__) ."/../lib/WxPay.Api.php";
require_once dirname(__FILE__) ."/../lib/WxPay.Data.php";
class AppPay{
    /**
     *
     * 获取appapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws WxPayException
     *
     * @return json数据
     */
    public function GetAppParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayAppPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $jsapi->setPartnerid(WxPayConfig::MCHID);
        $jsapi->setPrepayid($UnifiedOrderResult['prepay_id']);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("Sign=WXPay");
        $jsapi->SetSign($jsapi->MakeSign());
        //$parameters = json_encode($jsapi->GetValues());
        return $jsapi->GetValues();
    }

}

class WxPayAppPay extends WxPayDataBase{

    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
     * 设置支付时间戳
     * @param string $value
     **/
    public function SetTimeStamp($value)
    {
        $this->values['timestamp'] = $value;
    }
    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function GetTimeStamp()
    {
        return $this->values['timestamp'];
    }
    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function IsTimeStampSet()
    {
        return array_key_exists('timestamp', $this->values);
    }

    /**
     * 随机字符串
     * @param string $value
     **/
    public function SetNonceStr($value)
    {
        $this->values['noncestr'] = $value;
    }
    /**
     * 获取notify随机字符串值
     * @return 值
     **/
    public function GetReturn_code()
    {
        return $this->values['noncestr'];
    }
    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function IsReturn_codeSet()
    {
        return array_key_exists('noncestr', $this->values);
    }


    /**
     * 设置订单详情扩展字符串
     * @param string $value
     **/
    public function SetPackage($value)
    {
        $this->values['package'] = $value;
    }
    /**
     * 获取订单详情扩展字符串的值
     * @return 值
     **/
    public function GetPackage()
    {
        return $this->values['package'];
    }
    /**
     * 判断订单详情扩展字符串是否存在
     * @return true 或 false
     **/
    public function IsPackageSet()
    {
        return array_key_exists('package', $this->values);
    }


    /**
     * 设置签名方式
     * @param string $value
     **/
    public function SetSign($value)
    {
        $this->values['sign'] = $value;
    }
    /**
     * 获取签名方式
     * @return 值
     **/
    public function GetSign()
    {
        return $this->values['sign'];
    }
    /**
     * 判断签名方式是否存在
     * @return true 或 false
     **/
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values);
    }


    /**
     * @return mixed
     */
    public function getPrepayid()
    {
        return $this->values['prepayid'];;
    }

    /**
     * @param mixed $prepayid
     */
    public function setPrepayid($prepayid)
    {
        $this->values['prepayid'] = $prepayid;
    }

    public function isPrepayidSet()
    {
        return array_key_exists('prepayid', $this->values);
    }

    

    /**
     * @return mixed
     */
    public function getPartnerid()
    {
        return $this->values['partnerid'];;
    }

    /**
     * @param mixed $partnerid
     */
    public function setPartnerid($partnerid)
    {
        $this->values['partnerid'] = $partnerid;
    }

    public function isPartneridSet()
    {
        return array_key_exists('partnerid', $this->values);
    }
}