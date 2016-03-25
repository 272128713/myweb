
<?php
error_reporting(0);
/**
 * 获取文章
 */
require  'lnk.php';
header("Content-type:text/html;charset=utf-8");
if($_POST){
    $data=$_POST;

    $type = htmlspecialchars(trim($data['type']));
    $mail = htmlspecialchars(trim($data['mail']));
//    $title = htmlspecialchars(trim($data['title']));
    $content = htmlspecialchars(trim($data['content']));
//    echo $type.','.$content.','.$mail;
//    die();
    $ip = getIP();
        $sql = "insert into web_form SET name= '',mail='$mail',title='',content='$content',time=NOW(),ip='$ip',type='$type'";
        $result = $database->exec($sql);

    if($result){
        echo 1;
    }else{
        echo 0;
    }

}





function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');

    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>






