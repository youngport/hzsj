<?php
header("Content-type: text/html; charset=utf-8"); 

include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();

session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
if($_POST['dpi']){
	$dpi = intval($_POST['dpi']);
	
	$sql = "select id,bianhao,zuobiao,xxdizhi,dianpname,dianp_lxfs from sk_erweima where id='$dpi'";
	$resultArr = $do->selectsql($sql);
	$json = $com->createBaseJson($resultArr, "id,bianhao,zuobiao,xxdizhi,dianpname,dianp_lxfs");
	echo $json;
}
