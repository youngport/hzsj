<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$cartnum = $_SESSION["cartnum"];

$injosn = json_encode($_POST);
$injosn = json_decode($injosn);

$sql = "delete from wei_cart where openid='$openid' and cart_id in(";

for($i=0;$i<count($injosn);$i++){
	$sql .= $injosn[$i].",";
}
$sql = rtrim($sql, ",");
$sql = $sql.")";
$do->dealsql($sql);

/*
if($type == "clean"){
	$sql = "delete from wei_cart where openid='$openid'";
	$_SESSION["cartnum"] = "0";
}else{
	$sql = "delete from wei_cart where openid='$openid' and goodsid='$type'";
	$_SESSION["cartnum"] = intval($cartnum) - 1;
}
$do->dealsql($sql);*/
echo "{\"success\":\"1\"}";
?>