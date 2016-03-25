<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '210.14.72.62', // 服务器地址
    'DB_NAME'               =>  'shop_skyhospital',      // 数据库名
    'DB_USER'               =>  'shop',      // 用户名
    'DB_PWD'                =>  '301301',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'SHOW_PAGE_TRACE' =>false,
    'API_PATH'              =>'http://210.14.72.62/outsource/index.php/',
    //路径
    'TMPL_PARSE_STRING'=>array(
        '__CSS__'=>__ROOT__ .'/Public/Home/css',
        '__IMG__'=>__ROOT__ .'/Public/Home/images',
        '__JS__'=>__ROOT__ .'/Public/Home/js',
        '__VIDEO__'=>__ROOT__ .'/Public/Home/video',
    	'__NC_UPLOAD_PATH_GOODS__'=>'http://210.14.72.62/shop_new/data/upload/shop/store/goods/1/',

    ),
    'IMG_HOST'=>'210.14.72.62', //头像地址
	'NC_UPLOAD_PATH'=>'http://210.14.72.62/shop_new/data/upload/',
	'PAGE_VAL'=>'10',

	'MEMBER_POINTS_X'=>'0.01',//比例，积分换算规则：MEMBER_POINTS_MONEY=MEMBER_POINTS*MEMBER_POINTS_X
	'MEMBER_POINTS_Y'=>'0.5',//积分可抵扣比例，例：100元货 可用积分 抵扣 100*Y

    'IF_EMPTY'=>'未设置',
    'PAKAGE_NUM'=>3 , //套餐个数
		'LOG_RECORD' => true, // 开启日志记录
		'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误

);
