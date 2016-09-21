<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

$categoryid = intval($_POST["categoryid"]);
if($_POST["leix"])
	$sql = "select id,good_name,img,price from sk_goods where cate_id='$categoryid' and goodtype='0' order by ".addslashes($_POST["leix"])." ".addslashes($_POST["paix"]);
else
	$sql = "select id,good_name,img,price from sk_goods where cate_id='$categoryid' and goodtype='0' order by htis desc";
$resultArr = $do->selectsql($sql);
$json = $com->createBaseJson($resultArr, "id,good_name,img,price");
echo $json;
?>