<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>空中医院新闻资讯</title>
		<link rel="stylesheet" href="css/common.css">
        <style>
            .title{

                font-size: 25px;
                color: #516275;
                margin-top: 50px;
                margin-bottom: 10px;
            }
            .time{

                font-size: 16px;
                color: #a0a0a0;
                line-height: 26px;
                margin-bottom: 20px;
            }
            .des{

                font-size: 16px;
                color: #a0a0a0;
                margin-bottom: 50px;
                line-height: 26px;

            }
            .article{
                margin-top: 50px;
                margin-bottom: 100px;
                color:#516275;
                font-size: 16px;
                line-height: 1.7em;
                font-family: "微软雅黑", "Helvetica Neue", Helvetica, Arial, sans-serif;
            }

        </style>
	</head>
	<body>
        <?php include "header.php";?>
        <?php include "server/article_view.php";?>
        <div class="content">
            <div class="title">
                <?php echo $datas['title'];?>

            </div>
            <div class="time">
                <?php echo date("Y-m-d H:i:s",$datas['update_time']);?>

            </div>
            <div class="des">
                摘要：<?php echo $datas['description'];?>

            </div>
            <div class="article">
                <?php
                echo $datas['content'];

                ?>
            </div>
        </div>
        <?php include "footer.php";?>
	</body>
</html>
