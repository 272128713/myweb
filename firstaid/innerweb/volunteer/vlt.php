<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>志愿者联盟</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<link rel="stylesheet" type="text/css" href="css/app.css" />
	<link rel="stylesheet" type="text/css" href="css/common.css" />
	<link rel="stylesheet" type="text/css" href="css/volunteer.css" />
	
	<?php include "php/getProvincecount.php";?>
</head>
<body>
	<div class="container">
		<div class="altogether">全国共有空中急救志愿者<span>321</span>人</div>
		<div class="border"></div>
		<div class="area-list">
            <?php foreach($list as $k=>$l){?>
                <div class="place">
                    <div class="center">
                        <img src="images/rtri.png"><?php

                            $pid = $l->live_province_id;
                            $sql ="select pr.province
                                  from hat_area AS ar
                                  LEFT JOIN hat_city AS ci ON ar.father = ci.cityID
                                  LEFT JOIN hat_province AS pr ON ci.father = pr.provinceID
                                  WHERE ar.areaID = '$pid'";
                            $pname = $db251->query($sql)->fetch();
                            echo  $pname['province'];
                        ?>
                        <span><?php echo $l->num;?></span>
                    </div>
                </div>
            <?php
                $totalnum += $l->num;
            }
            ?>
		</div>
	</div>
    <script src="js/jquery.min.js"></script>
    <script>
        $('.altogether span').html(<?php echo $totalnum;?>);

    </script>
</body>
</html>
