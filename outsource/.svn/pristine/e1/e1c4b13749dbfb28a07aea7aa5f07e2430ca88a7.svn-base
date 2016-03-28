<?php
require_once(dirname(__FILE__)."/class.aes128.php");
/***********************************
* @class AES 封装后的AES类
* @author: jessica.yang
* @date: 10:06 2008-9-11 
************************************/
class AES2
{
	var $keystring; // 加密字符串密钥
	var $aesobj;// AES_128加密算法对象
	/***********************************
	* @function AES 构造函数
	************************************/
	function AES2($keystr='')
	{
		$this->keystring = $keystr;// 密钥
		$this->aesobj = new AES_128(true);// 实例化AES128算法对象
	}
	/***********************************
	* @function getEncryptString 获取加密后的字符串
	* @param string enstring, 被加密字符串 
	* @return string pwd 加密后的字符串
	************************************/
	function getEncryptString($enstring)
	{
		if($enstring == "")
			return;
		$keys = $this->aesobj->makeKey($this->keystring);
		$pwd = $this->aesobj->encryptString($enstring, $keys);
		return $pwd;
	} 
	/***********************************
	* @function getDecryptString 获取解密后的字符串
	* @param string destring, 被解密字符串 
	* @return string pwd 解密后的字符串
	************************************/
	function getDecryptString($destring)
	{
		if($destring == "")
			return;
		$keys = $this->aesobj->makeKey($this->keystring);
		$pwd = $this->aesobj->decryptString($destring, $keys);
		return $pwd;
	} 		
		
}
?>