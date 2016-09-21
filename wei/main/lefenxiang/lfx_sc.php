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
$sc = $do->getone("select count(*) as sc from sk_soucang where yonghu='$openid' and chanpin='$id' and type='3'");
if($sc['sc']>0)
    echo 0;
else {
    $sql = "insert into sk_soucang(yonghu,chanpin,type)values('$openid',$id,3)";
    if($do->dealsql($sql))
        echo 1;
    else 
        echo 2;
}






?>