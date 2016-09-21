<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$productName = addslashes($_POST["productName"]);
if($_POST["leix"])
	$sql = "select id,good_name,img,price from sk_goods where good_name like '%$productName%' order by ".addslashes($_POST["leix"])." ".addslashes($_POST["paix"]);
else
	$sql = "select id,good_name,img,price from sk_goods where good_name like '%$productName%' order by `sk_goods`.`hits` desc";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>