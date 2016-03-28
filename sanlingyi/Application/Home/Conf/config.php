<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '117.34.72.251', // 服务器地址
    'DB_NAME'               =>  'office',      // 数据库名
    'DB_USER'               =>  'rongke',      // 用户名
    'DB_PWD'                =>  'rongke',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
	'API_SUCCESS_MSG'   	=>  '执行成功！',        // 所有接口执行成功的默认消息
	'IMG_URL'				=>   'http://117.34.72.251',
	'SHOW_PAGE_TRACE' =>false,
	'DOC_VISIT_MONEY'=>30,	
	/* 图片上传相关配置 */
	'PICTURE_UPLOAD' => array(
			'mimes'    => '', //允许上传的文件MiMe类型
			'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
			'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
			'autoSub'  => true, //自动子目录保存文件
			'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
			'rootPath' => './Uploads/', //保存根路径
			'savePath' => '', //保存路径
			'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			'saveExt'  => '', //文件保存后缀，空则使用原后缀
			'replace'  => false, //存在同名是否覆盖
			'hash'     => true, //是否生成hash编码
			'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //图片上传相关配置（文件上传类配置）	
	'PICTURE_UPLOAD_DRIVER'=>'local',
	//本地上传文件驱动配置
	'UPLOAD_LOCAL_CONFIG'=>array(),
	'domain'=>'http://117.34.72.251',
	'CUSTOMER_ROLE_ID'=>1593830108,//普通客服权限组id
);
?>