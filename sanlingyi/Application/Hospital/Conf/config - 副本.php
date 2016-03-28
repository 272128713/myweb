<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '192.168.0.55', // 服务器地址
    'DB_NAME'               =>  'yixin',      // 数据库名
    'DB_USER'               =>  'rongke',      // 用户名
    'DB_PWD'                =>  'rongke!@#$%',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
	'API_SUCCESS_MSG'   	=>  '执行成功！',        // 所有接口执行成功的默认消息
	//'DB_PREFIX'             => 'article_',
	//'IMG_URL'				=>   'http://117.34.72.251',
	'SHOW_PAGE_TRACE' =>false,
	/* 图片上传相关配置 */
	//本地上传文件驱动配置
	'DOMAIN'=>'http://117.34.72.251:8081', //前端项目地址
    'URL_HTML_SUFFIX'=>'',
	//分页数字
	'PAGE_NUM'=>10,
    'WEB_URL'=>'http://210.14.72.35:8080',  //图片文件地址
    'HISTORY_EMPIRE'=>3600*24*7,
    'IMG_HOST'=>'210.14.72.56'              //头像地址



);
