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

$sql = "select er.id,er.bianhao,er.zuobiao,er.xxdizhi,er.dianpname from sk_erweima er join sk_orders dd on dd.order_id=er.dingdan where dd.status=4 and er.openid='$openid'";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,bianhao,zuobiao,xxdizhi,dianpname");
echo $json;
