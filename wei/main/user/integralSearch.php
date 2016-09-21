<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$selyear = $_POST["selyear"];

$json = "";
$sql = "select sum(p1.pop) as pop,p2.popdui,p3.poping from wei_pop p1,
		(select sum(pop) as popdui from wei_pop where openid='$openid' and duitag='1' and poptime>='$selyear-01' and poptime<='$selyear-31') p2,
		(select sum(pop) as poping from wei_pop where openid='$openid' and duitag='2' and poptime>='$selyear-01' and poptime<='$selyear-31') p3 
		where p1.openid='$openid' and p1.poptime>='$selyear-01' and p1.poptime<='$selyear-31'";
$resultArr = $do->selectsql($sql);

$sql = "select count(order_id) as con from sk_orders where buyer_id='$openid' and duitag='0' and order_time>='$selyear-01' and order_time<='$selyear-31'";
$myOrderArr = $do->selectsql($sql);
echo "{\"pop\":\"".$resultArr[0]["pop"]."\",\"popdui\":\"".$resultArr[0]["popdui"]."\",\"poping\":\"".$resultArr[0]["poping"]."\",\"con\":\"".$myOrderArr[0]["con"]."\"}";
?>