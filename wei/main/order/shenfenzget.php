<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$orderid = intval($_POST["orderid"]);
$shenfenz = addslashes($_POST["shenfenz"]);
$name = addslashes($_POST["name"]);

$sql = "update sk_orders set shenfenz = '$shenfenz', buyer_name = '$name' where order_id='$orderid'";
$do->selectsql($sql);
$sql = "update sk_member set wei_shousfz = '$shenfenz' where open_id='$openid'";
$do->selectsql($sql);
echo "{\"success\":\"1\"}";
?>