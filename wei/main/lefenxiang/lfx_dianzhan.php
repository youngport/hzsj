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
$shifou = $do->selectsql("select count(*) cou from sk_lfx_dz where openid='$openid' and lfxid='$id'");

if($shifou[0]['cou']>0)
    echo 0;
else {
    $sql = "insert into sk_lfx_dz(lfxid,openid,shijian)values(".$id.",'$openid',".time().")";
    if($do->dealsql($sql))
        echo 1;
    else 
        echo 2;
}






?>