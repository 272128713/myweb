
<?php

/**
 * 获取文章
 */
require  'lnk.php';
header("Content-type:text/html;charset=utf-8");
if($_POST){
    $data=$_POST;

    $name = htmlspecialchars(trim($data['name']));
    $mail = htmlspecialchars(trim($data['mail']));
    $title = htmlspecialchars(trim($data['title']));
    $content = htmlspecialchars(trim($data['content']));
    $ip = getIP();
        $sql = "insert into web_form SET name= '$name',mail='$mail',title='$title',content='$content',time=NOW(),ip='$ip'";
        $result = $database->exec($sql);

    if($result){
        echo "<script>alert('提交成功!');
                history.go(-1);
            </script>";

    }else{
        echo "<script>alert('有非法字符，提交失败!');
                history.go(-1);
            </script>";
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






