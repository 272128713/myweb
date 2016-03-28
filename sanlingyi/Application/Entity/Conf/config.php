<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '117.34.72.251', // 服务器地址
    'DB_NAME'               =>  'yixin_duplicate',      // 数据库名
    'DB_USER'               =>  'rongke',      // 用户名
    'DB_PWD'                =>  'rongke',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'SHOW_PAGE_TRACE' =>true,
    /* 图片上传相关配置 */
    //本地上传文件驱动配置
    'DOMAIN'=>'http://117.34.72.251',
    'URL_HTML_SUFFIX'=>'',
    //分页数字
    'PAGE_NUM'=>10,
    'WEB_URL'=>'http://117.34.72.251:8081',  //图片文件地址
    'YIXIN_API_PATH'=>'http://117.34.72.251/yixin/1.0/',
    'HISTORY_EMPIRE'=>3600*24*7,
    'IMG_HOST'=>'117.34.72.251',
    'DEFAULT_CATE'=>'0,1,2,4',
    //路径
    'TMPL_PARSE_STRING'=>array(
        '__CSS__'=>__ROOT__ .'/Public/Entity/css',
        '__IMG__'=>__ROOT__ .'/Public/Entity/images',
        '__JS__'=>__ROOT__ .'/Public/Entity/js'
    ),
    'HOS_TYPE'=>array(
        '1'=>'全科诊所',
        '2'=>'中医诊所',
        '3'=>'专科诊所',
        '4'=>'健康咨询中心',
        '5'=>'督脉正阳保健中心',
        '6'=>'现有诊所',
        '7'=>'现有健康服务机构'
    ),
    'DEFAUL_NUM'=>2        //显示险种个数


);
