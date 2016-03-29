<?php
    header("Content-Type:text/html;charset=utf-8");
     $configs = parse_ini_file("apiConfig.ini",true);
?>
<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title><?php echo $configs["global"]["projectname"]?></title>
<style>
	*{
		font-size:14px;
		margin:2px;
	}
</style>
</head>
<body>
	<center><h1 style="background-color:green;height:30px;color:white"><?php echo $configs["global"]["projectname"]?></h1></center>
	<br>
	<?php 
	    $strHtml = "";
	    foreach($configs as $key=>$config){
	        if($key == "global") continue;
	        $keyArr = explode(",", $key);
	        $apiName = "【" . $keyArr[0] . "】";
	        $keyTrue = $keyArr[1];
	        $strHtml .= "<fieldset style='position:relative;float:left;width:49%;height:380px;border:1px solid #369'><legend style=''>" . $apiName . $configs["global"]["host"] . $keyTrue . "</legend><br>";
	        $filedStr = "";
	        $fileExists = false;
	        //print_r($configs[$key]);
	        foreach($configs[$key] as $name=>$value){
	            $valueT = explode("|", $value);
	            //print_r($valueT);
	            if($valueT[0] == "file"){
	                $fileExists = true;
	            }
	            $valueTemp = isset($valueT[1])?$valueT[1]:"";
	            $filedStr .= "<div style='float:left;width:45%'><span style='width:80px;display:block;float:left'>$name</span> ：<input type='" . $valueT[0] . "' value='" . $valueTemp. "' name='" . $name . "' style='width:60%;border:1px solid #369'></div>";
	        }
	        $formEncoding = $fileExists?" enctype='multipart/form-data'":"";
	        $strHtml .= "<form action='" . $configs["global"]["host"] . $keyTrue . "' method='post' target='$key' " . $formEncoding . ">";
	        $strHtml .= $filedStr . "<br><input type='submit' style='right:5px;position:absolute;bottom:130px;width:150px' value='提交'></form>";	        
	        $strHtml .= "<div style='position:absolute;bottom:0px;width:99%'><fieldset style='border:1px solid #369;bottom:0px'><legend>接口返回值</legend><iframe name='$key' frameborder='0' style='width:99%;height:100px'></iframe></fieldset></div>";
	        $strHtml .= "</fieldset>";
	    }
	    echo $strHtml;
	?>
</body>
</html>
