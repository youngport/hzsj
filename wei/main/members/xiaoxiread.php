<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
$openid=$_SESSION['openid'];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
if($_POST['id']){
	$id=explode(",",rtrim($_POST['id'],","));
	array_walk($id,"intval");
	foreach($id as $v){
		$sql="insert into sk_message_read values($v,'$openid')";
		$do->dealsql($sql);
	}
}
?>