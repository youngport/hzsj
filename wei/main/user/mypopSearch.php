<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$type = intval($_POST["type"]);

$sql = "";
if($type == "1"){
	$sql = "select open_id,wei_nickname,headimgurl,join_time,pop from sk_member where pid='$openid' and wei_nickname!='' ";
}else{
	$sql = "select open_id,wei_nickname,headimgurl,join_time,pop from sk_member where pid='$openid' and wei_nickname='' ";
}
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "open_id,wei_nickname,headimgurl,join_time,pop");
echo $json;
?>