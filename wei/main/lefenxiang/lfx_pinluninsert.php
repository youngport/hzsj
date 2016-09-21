<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$openid = $_SESSION["openid"];
$id = intval($_POST["id"]);
$neirong = addslashes($_POST["neirong"]);

$sql = "insert into sk_lfx_pinglun (lfx_id,openid,shijian,neirong) values('$id','$openid',".time().",'$neirong')";
if($do->dealsql($sql))
    echo 1;
else 
    echo 0;

?>