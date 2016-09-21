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
if($_POST['pd']){
	$id = intval($_POST['pd']);
	$ym = intval($_POST['ym']);
	$sql = "SELECT sp.xingji,sp.neirong,from_unixtime(sp.shijian) shijian,yh.wei_nickname,yh.headimgurl from sk_sppl sp join sk_member yh on yh.open_id=sp.openid where spid='$id' and sp.neirong not in('') order by shijian desc limit ".$ym.",20";
	$resultArr = $do->selectsql($sql);
	$json = $com->createBaseJson($resultArr, "xingji,neirong,neirong,shijian,wei_nickname,headimgurl");
	echo $json;
}
?>