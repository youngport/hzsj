<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = 0;
if(isset($_SESSION["cartnum"])){
	$cartnum = $_SESSION["cartnum"];
}
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
$nickname = $_SESSION["nickname"];
$headimgurl = $_SESSION["headimgurl"];
$openid = $_SESSION["openid"];

$do = new condbwei();
$com = new commonFun();
//手机，名称，公司名称 ，邮箱，官网，地址，头像，电话，职业
$sql = "select wei_phone,wei_nickname,gongsi_name,youxiang,guanwang,dizhi,touimg,dianhua,zhiye from wei_pop ";
$json = "";
$popArr = $do->selectsql($sql);
for($i=0;$i<count($popArr);$i++){
	$json .= "{\"wei_phone\":\"".$menuArr[$i]["wei_phone"]."\",";
	$json .= "\"wei_nickname\":\"".$menuArr[$i]["wei_nickname"]."\",";
	$json .= "{\"gongsi_name\":\"".$menuArr[$i]["gongsi_name"]."\",";
	$json .= "\"youxiang\":\"".$menuArr[$i]["youxiang"]."\",";
	$json .= "{\"guanwang\":\"".$menuArr[$i]["guanwang"]."\",";
	$json .= "\"dizhi\":\"".$menuArr[$i]["dizhi"]."\",";
	$json .= "{\"touimg\":\"".$menuArr[$i]["touimg"]."\",";
	$json .= "\"zhiye\":\"".$menuArr[$i]["zhiye"]."\",";
	$json = substr($json, 0, strlen($json)-1)."]";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>会员中心</title>
	</head>
	<style>
	body,div,ul,li{ margin:0;padding:0;list-style:none;}
	.kuangwai{ width:98%;margin:5px auto 0; border:1px solid #ccc;}
	.kuangwai .tupian{ width:35%; float:left;}
	.kuangwai .tupian img{ max-width:100%;}
	.kuangwai ul{ float:right; width:64%;}
	.kuangwai ul li{line-height:25px; font-size:14px; color:#555; text-indent: 15px;}
	.kuangwai ul .name{line-height:30px; font-size:22px; color:#555; font-family:"微软雅黑";font-weight: 900; margin-top:5px;}
	</style>
	
<body>
	<div id="category" style="width:100%;">
	</div>
<?php include '../gony/returntop.php';?>
</body>
</html>
	<script>
	var data = eval("("<?php echo $json;?>")");
	var appStr = "";
	for(var i=0;i<data.length;i++){
		appStr = "<div class=\"kuangwai\"><div class=\"tupian\"><img src=\""+data[i].wei_nickname+"\" /></div><ul>";
		appStr += "<li class=\"name\">" +  + "</li>";
		appStr += "<li>"+data[i].gongsi_name+"</li>";
		appStr += "<li>"+data[i].zhiye+"</li>";
		appStr += "<li>"+data[i].wei_phone+"</li></ul><div style="clear:both;float:none;"></div></div>";
		$("#category").append(appStr);
	}
	</script>
